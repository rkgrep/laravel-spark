<?php

namespace App\Ux\Settings;

use App\Spark;

class DashboardTabs extends Tabs
{
    /**
     * Get the tab configuration for the "profile" tab.
     *
     * @return \App\Ux\Settings\Tab
     */
    public function profile()
    {
        return new Tab('Profile', 'settings.tabs.profile', 'fa-user');
    }

    /**
     * Get the tab configuration for the "teams" tab.
     *
     * @return \App\Ux\Settings\Tab
     */
    public function teams()
    {
        return new Tab('Teams', 'settings.tabs.teams', 'fa-users', function () {
            return Spark::usingTeams();
        });
    }

    /**
     * Get the tab configuration for the "security" tab.
     *
     * @return \App\Ux\Settings\Tab
     */
    public function security()
    {
        return new Tab('Security', 'settings.tabs.security', 'fa-lock');
    }

    /**
     * Get the tab configuration for the "subscription" tab.
     *
     * @param  bool  $force
     * @return \App\Ux\Settings\Tab|null
     */
    public function subscription($force = false)
    {
        return new Tab('Subscription', 'settings.tabs.subscription', 'fa-credit-card', function () use ($force) {
            return count(Spark::plans()->paid()) > 0 || $force;
        });
    }
}
