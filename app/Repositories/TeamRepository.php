<?php

namespace App\Repositories;

use App\Spark;
use App\Teams\Team;
use App\Contracts\Repositories\TeamRepository as Contract;

class TeamRepository implements Contract
{
    /**
     * Create a new team for the given user and data.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $data
     * @return \App\Teams\Team
     */
    public function create($user, array $data)
    {
        $class = Spark::model('teams', Team::class);

        $team = new $class(['name' => $data['name']]);

        $team->owner_id = $user->id;

        $team->save();

        $team = $user->teams()->attach(
            $team, ['role' => 'owner']
        );

        return $team;
    }

    /**
     * Get the team for the given ID.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $teamId
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getTeam($user, $teamId)
    {
        return $user->teams()->with('users', 'invitations')->findOrFail($teamId);
    }

    /**
     * Get all of the teams for the user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllTeamsForUser($user)
    {
        $teams = $user->teams()->with('owner')->get();

        foreach ($teams as $team) {
            $team->owner->setVisible(['name']);
        }

        return $teams;
    }

    /**
     * Get all of the pending invitations for the user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPendingInvitationsForUser($user)
    {
        $invitations = $user->invitations()->with('team.owner')->get();

        foreach ($invitations as $invite) {
            $invite->setVisible(['id', 'team']);

            $invite->team->setVisible(['name', 'owner']);

            $invite->team->owner->setVisible(['name']);
        }

        return $invitations;
    }

    /**
     * Attach a user to a given team based on their invitation.
     *
     * @param  string  $invitationId
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    public function attachUserToTeamByInvitation($invitationId, $user)
    {
        $userModel = get_class($user);

        $inviteModel = get_class((new $userModel)->invitations()->getQuery()->getModel());

        $invitation = (new $inviteModel)->where('token', $invitationId)->first();

        if ($invitation) {
            $user->joinTeamById($invitation->team->id);

            $user->switchToTeam($invitation->team);

            $invitation->delete();
        }
    }
}
