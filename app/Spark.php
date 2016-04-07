<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use App\Teams\CanJoinTeams;
use App\Subscriptions\Plans;
use App\Ux\Settings\TeamTabs;
use App\Ux\Settings\DashboardTabs;
use App\Services\Auth\TwoFactor\Authy;

class Spark
{
    /**
     * The default role that is assigned to new team members.
     *
     * @var string
     */
    protected static $defaultRole;

    /**
     * The team roles that may be assigned to users.
     *
     * @var array
     */
    protected static $roles = [];

    /**
     * The Spark plan collection instance.
     *
     * @var \App\Subscriptions\Plans
     */
    protected static $plans;

    /**
     * The coupon-code that is being forced as promo.
     *
     * @var string
     */
    protected static $forcedPromotion;

    /**
     * The callback used to retrieve the users.
     *
     * @var callable|null
     */
    public static $retrieveUsersWith;

    /**
     * The callback used to retrieve the user registration validator.
     *
     * @var callable|null
     */
    public static $validateRegistrationsWith;

    /*
     * The callback used to retrieve the user subscription validator.
     *
     * @var callable|null
     */
    public static $validateSubscriptionsWith;

    /**
     * The callback used to create the new users.
     *
     * @var callable|null
     */
    public static $createUsersWith;

    /**
     * The callback used to create new subscriptions.
     *
     * @var callable|null
     */
    public static $createSubscriptionsWith;

    /**
     * The callback used to move a user to another plan.
     *
     * @var callable|null
     */
    public static $swapSubscriptionsWith;

    /**
     * Indicates if two-factor authentication is supported.
     *
     * @var bool
     */
    public static $twoFactorAuth = false;

    /**
     * The path to redirect to after authentication.
     *
     * @var string
     */
    public static $afterAuthRedirectTo = '/home';

    /**
     * The callback used to retrieve the user profile validator.
     *
     * @var callable|null
     */
    public static $validateProfileUpdatesWith;

    /**
     * The callback used to update the user's profiles.
     *
     * @var callable|null
     */
    public static $updateProfilesWith;

    /**
     * The callback used to retrieve the new team validator.
     *
     * @var callable|null
     */
    public static $validateNewTeamsWith;

    /**
     * The callback used to retrieve the team validator.
     *
     * @var callable|null
     */
    public static $validateTeamUpdatesWith;

    /**
     * The callback used to update teams.
     *
     * @var callable|null
     */
    public static $updateTeamsWith;

    /**
     * The callback used to retrieve the team member validator.
     *
     * @var callable|null
     */
    public static $validateTeamMemberUpdatesWith;

    /**
     * The callback used to update a team member.
     *
     * @var callable|null
     */
    public static $updateTeamMembersWith;

    /**
     * The invoice's meta attributes.
     *
     * @var array
     */
    public static $invoiceData = [];

    /**
     * The settings tabs configuration.
     *
     * @var \App\Ux\Settings\Tabs
     */
    public static $settingsTabs;

    /**
     * The team settings tabs configuration.
     *
     * @var \App\Ux\Settings\TeamTabs
     */
    public static $teamSettingsTabs;

    /**
     * Indicates if the application is supporting teams.
     *
     * @var bool
     */
    protected static $usingTeams;

    /**
     * The Spark configuration options.
     *
     * @var array
     */
    protected static $options = [];

    /**
     * Configure the Spark application.
     *
     * @param  array  $options
     * @return void
     */
    public static function configure(array $options)
    {
        static::$options = $options;
    }

    /**
     * Get a Spark configuration option.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public static function option($key, $default)
    {
        return array_get(static::$options, $key, $default);
    }

    /**
     * Get the class name for a given Spark model.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public static function model($key, $default = null)
    {
        return array_get(static::$options, 'models.'.$key, $default);
    }

    /**
     * Get or define the default role for team members.
     *
     * @param  string|null  $role
     * @return string|void
     */
    public static function defaultRole($role = null)
    {
        if (is_null($role)) {
            return static::$defaultRole;
        } else {
            static::$defaultRole = $role;
        }
    }

    /**
     * Get or define the team roles that can be assigned to a user.
     *
     * @param  array|null  $roles
     * @return array|void
     */
    public static function roles(array $roles = null)
    {
        if (is_null($roles)) {
            return array_merge(static::$roles, ['owner' => 'Owner']);
        } else {
            static::$roles = $roles;
        }
    }

    /**
     * Define a new free Spark plan.
     *
     * @param  string  $name
     * @return \App\Subscriptions\Plan
     */
    public static function free($name = 'Free')
    {
        return static::plan($name, 'free-plan')->free();
    }

    /**
     * Define a new Spark plan.
     *
     * @param  string  $name
     * @param  string  $id
     * @return \App\Subscriptions\Plan
     */
    public static function plan($name, $id = null)
    {
        return static::plans()->create($name, $id);
    }

    /**
     * Get the Spark plan collection.
     *
     * @return \App\Subscriptions\Plans
     */
    public static function plans()
    {
        return static::$plans ?: static::$plans = new Plans;
    }

    /**
     * Set a forced coupon-code as a promo.
     *
     * @param  string  $couponCode
     * @return void
     */
    public static function promotion($couponCode)
    {
        static::$forcedPromotion = $couponCode;
    }

    /**
     * Get the coupon-code that is being forced as a promo.
     *
     * @return string
     */
    public static function forcedPromotion()
    {
        return static::$forcedPromotion;
    }

    /**
     * Determine if a coupon-code is currently being forced.
     *
     * @return bool
     */
    public static function forcingPromotion()
    {
        return isset(static::$forcedPromotion);
    }

    /**
     * Retrieve the current user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public static function user()
    {
        return static::$retrieveUsersWith
                        ? call_user_func(static::$retrieveUsersWith)
                        : Auth::user();
    }

    /**
     * Set a callback to be used to retrieve the user.
     *
     * @param  callable  $callback
     * @return void
     */
    public static function retrieveUsersWith(callable $callback)
    {
        static::$retrieveUsersWith = $callback;
    }

    /**
     * Determine if the Spark application supports teams.
     *
     * @return bool
     */
    public static function usingTeams()
    {
        if (! is_null(static::$usingTeams)) {
            return static::$usingTeams;
        } else {
            return static::$usingTeams = in_array(
                CanJoinTeams::class, class_uses_recursive(config('auth.providers.users.model'))
            );
        }
    }

    /**
     * Set a callback to be used to retrieve the user validator.
     *
     * @param  callable|string  $callback
     * @return void
     */
    public static function validateRegistrationsWith($callback)
    {
        static::$validateRegistrationsWith = $callback;
    }

    /**
     * Set a callback to be used to retrieve the subscription validator.
     *
     * @param  callable|string  $callback
     * @return void
     */
    public static function validateSubscriptionsWith($callback)
    {
        static::$validateSubscriptionsWith = $callback;
    }

    /**
     * Set a callback to be used to create the users.
     *
     * @param  callable|string  $callback
     * @return void
     */
    public static function createUsersWith($callback)
    {
        static::$createUsersWith = $callback;
    }

    /**
     * Set a callback to be used to create new user subscriptions.
     *
     * @param  callable|string  $callback
     * @return void
     */
    public static function createSubscriptionsWith($callback)
    {
        static::$createSubscriptionsWith = $callback;
    }

    /**
     * Set a callback to be used when moving the user to another plan.
     *
     * @param  callable|string  $callback
     * @return void
     */
    public static function swapSubscriptionsWith($callback)
    {
        static::$swapSubscriptionsWith = $callback;
    }

    /**
     * Specify that two-factor authentication should be available.
     *
     * @return void
     */
    public static function withTwoFactorAuth()
    {
        static::$twoFactorAuth = true;
    }

    /**
     * Determine if the application supports two-factor authentication.
     *
     * @return bool
     */
    public static function supportsTwoFactorAuth()
    {
        return static::$twoFactorAuth;
    }

    /**
     * Get the default two-factor authentication provider.
     *
     * Currently Authy is the only provider, so this is not configurable.
     *
     * @return  \App\Contracts\Auth\TwoFactor\Provider
     */
    public static function twoFactorProvider()
    {
        return new Authy;
    }

    /**
     * Set the redirect path after authentication.
     *
     * @param  string  $path
     * @return void
     */
    public static function afterAuthRedirectTo($path)
    {
        static::$afterAuthRedirectTo = trim('/'.$path, '/');
    }

    /**
     * Set a callback to be used to retrieve the user profile validator.
     *
     * @param  callable|string  $callback
     * @return void
     */
    public static function validateProfileUpdatesWith($callback)
    {
        static::$validateProfileUpdatesWith = $callback;
    }

    /**
     * Set a callback to be used to update the user's profiles.
     *
     * @param  callable|string  $callback
     * @return void
     */
    public static function updateProfilesWith($callback)
    {
        static::$updateProfilesWith = $callback;
    }

    /**
     * Set a callback to be used to retrieve the new team validator.
     *
     * @param  callable|string  $callback
     * @return void
     */
    public static function validateNewTeamsWith($callback)
    {
        static::$validateNewTeamsWith = $callback;
    }

    /**
     * Set a callback to be used to retrieve the team update validator.
     *
     * @param  callable|string  $callback
     * @return void
     */
    public static function validateTeamUpdatesWith($callback)
    {
        static::$validateTeamUpdatesWith = $callback;
    }

    /**
     * Set a callback to be used to update teams.
     *
     * @param  callable|string  $callback
     * @return void
     */
    public static function updateTeamsWith($callback)
    {
        static::$updateTeamsWith = $callback;
    }

    /**
     * Set a callback to be used to retrieve the team member update validator.
     *
     * @param  callable|string  $callback
     * @return void
     */
    public static function validateTeamMemberUpdatesWith($callback)
    {
        static::$validateTeamMemberUpdatesWith = $callback;
    }

    /**
     * Set a callback to be used to update team members.
     *
     * @param  callable|string  $callback
     * @return void
     */
    public static function updateTeamMembersWith($callback)
    {
        static::$updateTeamMembersWith = $callback;
    }

    /**
     * Get the company / vendor name for the application.
     *
     * @return string
     */
    public static function company()
    {
        return static::generateInvoicesWith()['vendor'];
    }

    /**
     * Get the product name for the application.
     *
     * @return string
     */
    public static function product()
    {
        return static::generateInvoicesWith()['product'];
    }

    /**
     * Get or set the Cashier invoice's meta attributes.
     *
     * @param  array  $invoiceData
     * @return array|null
     */
    public static function generateInvoicesWith(array $invoiceData = null)
    {
        if (is_null($invoiceData)) {
            return static::$invoiceData;
        } else {
            static::$invoiceData = $invoiceData;
        }
    }

    /**
     * Get the configuration for the Spark settings tabs.
     *
     * @return \App\Ux\Settings\DashboardTabs
     */
    public static function settingsTabs()
    {
        return static::$settingsTabs ?:
                static::$settingsTabs = static::createDefaultSettingsTabs();
    }

    /**
     * Create the default settings tabs configuration.
     *
     * @return \App\Ux\Settings\DashboardTabs
     */
    protected static function createDefaultSettingsTabs()
    {
        $tabs = [(new DashboardTabs)->profile(), (new DashboardTabs)->security()];

        if (count(static::plans()->active()) > 0) {
            $tabs[] = (new DashboardTabs)->subscription();
        }

        return new DashboardTabs($tabs);
    }

    /**
     * Get the configuration for the Spark team settings tabs.
     *
     * @return \App\Ux\Settings\TeamTabs
     */
    public static function teamSettingsTabs()
    {
        return static::$teamSettingsTabs ?:
                static::$teamSettingsTabs = static::createDefaultTeamSettingsTabs();
    }

    /**
     * Create the default team settings tabs configuration.
     *
     * @return \App\Ux\Settings\TeamTabs
     */
    protected static function createDefaultTeamSettingsTabs()
    {
        $tabs = [(new TeamTabs)->owner(), (new TeamTabs)->membership()];

        return new TeamTabs($tabs);
    }

    /**
     * Get the key for the first settings tab in the collection.
     *
     * @return string
     */
    public static function firstSettingsTabKey()
    {
        return static::settingsTabs()->tabs[0]->key;
    }

    /**
     * Get the key for the first team settings tab in the collection.
     *
     * @param  mixed  $team
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return string
     */
    public static function firstTeamSettingsTabKey($team, $user)
    {
        return static::teamSettingsTabs()->displayable($team, $user)[0]->key;
    }

    /**
     * Determine if the application is currently displaying a Spark settings screen.
     *
     * @return bool
     */
    public static function isDisplayingSettingsScreen()
    {
        return app('request')->is('settings*');
    }
}
