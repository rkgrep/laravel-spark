<?php

namespace App\Http\Controllers\Settings;

use Exception;
use App\Spark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Events\Team\Created as TeamCreated;
use App\Events\Team\Deleting as DeletingTeam;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Contracts\Repositories\TeamRepository;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

class TeamController extends Controller
{
    use ValidatesRequests;

    /**
     * The team repository instance.
     *
     * @var \App\Contracts\Repositories\TeamRepository
     */
    protected $teams;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Contracts\Repositories\TeamRepository  $teams
     * @return void
     */
    public function __construct(TeamRepository $teams)
    {
        $this->teams = $teams;

        $this->middleware('auth');
    }

    /**
     * Create a new team.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if (Spark::$validateNewTeamsWith) {
            $this->callCustomValidator(
                Spark::$validateNewTeamsWith, $request
            );
        } else {
            $this->validate($request, [
                'name' => 'required|max:255',
            ]);
        }

        $team = $this->teams->create(
            $user, ['name' => $request->name]
        );

        event(new TeamCreated($team));

        return $this->teams->getAllTeamsForUser($user);
    }

    /**
     * Show the edit screen for a given team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $teamId
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $teamId)
    {
        $user = $request->user();

        $team = $user->teams()->findOrFail($teamId);

        $activeTab = $request->get(
            'tab', Spark::firstTeamSettingsTabKey($team, $user)
        );

        return view('settings.team', compact('team', 'activeTab'));
    }

    /**
     * Update the team's owner information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $teamId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $teamId)
    {
        $user = $request->user();

        $team = $user->teams()
                ->where('owner_id', $user->id)
                ->findOrFail($teamId);

        $this->validateTeamUpdate($request, $team);

        if (Spark::$updateTeamsWith) {
            $this->callCustomUpdater(Spark::$updateTeamsWith, $request, [$team]);
        } else {
            $team->fill(['name' => $request->name])->save();
        }

        return $this->teams->getTeam($user, $teamId);
    }

    /**
     * Validate a team update request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Teams\Team
     * @return void
     */
    protected function validateTeamUpdate(Request $request, $team)
    {
        if (Spark::$validateTeamUpdatesWith) {
            $this->callCustomValidator(
                Spark::$validateTeamUpdatesWith, $request, [$team]
            );
        } else {
            $this->validate($request, [
                'name' => 'required|max:255',
            ]);
        }
    }

    /**
     * Switch the team the user is currently viewing.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $teamId
     * @return \Illuminate\Http\Response
     */
    public function switchCurrentTeam(Request $request, $teamId)
    {
        $user = $request->user();

        $team = $user->teams()->findOrFail($teamId);

        $user->switchToTeam($team);

        return back();
    }

    /**
     * Update a team member on the given team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $teamId
     * @param  string  $userId
     * @return \Illuminate\Http\Response
     */
    public function updateTeamMember(Request $request, $teamId, $userId)
    {
        $user = $request->user();

        $team = $user->teams()
                ->where('owner_id', $user->id)->findOrFail($teamId);

        $userToUpdate = $team->users->find($userId);

        if (! $userToUpdate) {
            abort(404);
        }

        $this->validateTeamMemberUpdate($request, $team, $userToUpdate);

        if (Spark::$updateTeamMembersWith) {
            $this->callCustomUpdater(Spark::$updateTeamMembersWith, $request, [$team, $userToUpdate]);
        } else {
            $userToUpdate->teams()->updateExistingPivot(
                $team->id, ['role' => $request->role]
            );
        }

        return $this->teams->getTeam($user, $teamId);
    }

    /**
     * Validate a team update request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateTeamMemberUpdate(Request $request, $team, $user)
    {
        if (Spark::$validateTeamMemberUpdatesWith) {
            $this->callCustomValidator(
                Spark::$validateTeamMemberUpdatesWith, $request, [$team, $user]
            );
        } else {
            $availableRoles = implode(
                ',', array_except(array_keys(Spark::roles()), 'owner')
            );

            $this->validate($request, [
                'role' => 'required|in:'.$availableRoles,
            ]);
        }
    }

    /**
     * Remove a team member from the team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $teamId
     * @param  string  $userId
     * @return \Illuminate\Http\Response
     */
    public function removeTeamMember(Request $request, $teamId, $userId)
    {
        $user = $request->user();

        $team = $user->teams()
                ->where('owner_id', $user->id)->findOrFail($teamId);

        $team->removeUserById($userId);

        return $this->teams->getTeam($user, $teamId);
    }

    /**
     * Remove the user from the given team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $teamId
     * @return \Illuminate\Http\Response
     */
    public function leaveTeam(Request $request, $teamId)
    {
        $user = $request->user();

        $team = $user->teams()
                    ->where('owner_id', '!=', $user->id)
                    ->where('id', $teamId)->firstOrFail();

        $team->removeUserById($user->id);

        return $this->teams->getAllTeamsForUser($user);
    }

    /**
     * Destroy the given team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $teamId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $teamId)
    {
        $user = $request->user();

        $team = $request->user()->teams()
                ->where('owner_id', $user->id)
                ->findOrFail($teamId);

        event(new DeletingTeam($team));

        $team->users()->where('current_team_id', $team->id)
                        ->update(['current_team_id' => null]);

        $team->users()->detach();

        $team->delete();

        return $this->teams->getAllTeamsForUser($user);
    }
}
