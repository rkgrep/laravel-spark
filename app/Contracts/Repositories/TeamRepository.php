<?php

namespace App\Contracts\Repositories;

interface TeamRepository
{
    /**
     * Create a new team for the given user and data.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $data
     * @return \App\Teams\Team
     */
    public function create($user, array $data);

    /**
     * Get the team for the given ID.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $teamId
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getTeam($user, $teamId);

    /**
     * Get all of the teams for the user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllTeamsForUser($user);

    /**
     * Get all of the pending invitations for the user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPendingInvitationsForUser($user);

    /**
     * Attach a user to a given team based on their invitation.
     *
     * @param  string  $invitationId
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    public function attachUserToTeamByInvitation($invitationId, $user);
}
