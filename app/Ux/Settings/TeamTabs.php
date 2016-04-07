<?php

namespace App\Ux\Settings;

class TeamTabs extends Tabs
{
    /**
     * Get the tab configuration for the "Owner Settings" tab.
     *
     * @return \App\Ux\Settings\Tab
     */
    public function owner()
    {
        return new Tab('Owner Settings', 'spark::settings.team.tabs.owner', 'fa-star', function ($team, $user) {
            return $user->ownsTeam($team);
        });
    }

    /**
     * Get the tab configuration for the "Membership" tab.
     *
     * @return \App\Ux\Settings\Tab
     */
    public function membership()
    {
        return new Tab('Membership', 'spark::settings.team.tabs.membership', 'fa-users');
    }
}
