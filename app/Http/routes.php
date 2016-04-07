<?php

Route::group(['middleware' => 'web'], function ($router) {
    $router->get('/', function () {
        return view('welcome');
    });

    $router->get('home', ['middleware' => 'auth', function () {
        return view('home');
    }]);

    $router->auth();

    // Terms Routes...
    $router->get('terms', 'TermsController@show');

    // Settings Dashboard Routes...
    $router->get('settings', 'Settings\DashboardController@show');

    // Profile Routes...
    $router->put('settings/user', 'Settings\ProfileController@updateUserProfile');

    // Team Routes...
    if (Spark::usingTeams()) {
        $router->post('settings/teams', 'Settings\TeamController@store');
        $router->get('settings/teams/{id}', 'Settings\TeamController@edit');
        $router->put('settings/teams/{id}', 'Settings\TeamController@update');
        $router->delete('settings/teams/{id}', 'Settings\TeamController@destroy');
        $router->get('settings/teams/switch/{id}', 'Settings\TeamController@switchCurrentTeam');

        $router->post('settings/teams/{id}/invitations', 'Settings\InvitationController@sendTeamInvitation');
        $router->post('settings/teams/invitations/{invite}/accept', 'Settings\InvitationController@acceptTeamInvitation');
        $router->delete('settings/teams/invitations/{invite}', 'Settings\InvitationController@destroyTeamInvitationForUser');
        $router->delete('settings/teams/{team}/invitations/{invite}', 'Settings\InvitationController@destroyTeamInvitationForOwner');

        $router->put('settings/teams/{team}/members/{user}', 'Settings\TeamController@updateTeamMember');
        $router->delete('settings/teams/{team}/members/{user}', 'Settings\TeamController@removeTeamMember');
        $router->delete('settings/teams/{team}/membership', 'Settings\TeamController@leaveTeam');
    }

    // Security Routes...
    $router->put('settings/user/password', 'Settings\SecurityController@updatePassword');
    $router->post('settings/user/two-factor', 'Settings\SecurityController@enableTwoFactorAuth');
    $router->delete('settings/user/two-factor', 'Settings\SecurityController@disableTwoFactorAuth');

    // Subscription Routes...
    if (count(Spark::plans()) > 0) {
        $router->post('settings/user/plan', 'Settings\SubscriptionController@subscribe');
        $router->put('settings/user/plan', 'Settings\SubscriptionController@changeSubscriptionPlan');
        $router->delete('settings/user/plan', 'Settings\SubscriptionController@cancelSubscription');
        $router->post('settings/user/plan/resume', 'Settings\SubscriptionController@resumeSubscription');
        $router->put('settings/user/card', 'Settings\SubscriptionController@updateCard');
        $router->put('settings/user/vat', 'Settings\SubscriptionController@updateExtraBillingInfo');
        $router->get('settings/user/plan/invoice/{id}', 'Settings\SubscriptionController@downloadInvoice');
    }

    // Two-Factor Authentication Routes...
    if (Spark::supportsTwoFactorAuth()) {
        $router->get('login/token', 'Auth\AuthController@showTokenForm');
        $router->post('login/token', 'Auth\AuthController@token');
    }

    // User API Routes...
    $router->get('spark/api/users/me', 'API\UserController@getCurrentUser');

    // Team API Routes...
    if (Spark::usingTeams()) {
        $router->get('spark/api/teams/invitations', 'API\InvitationController@getPendingInvitationsForUser');
        $router->get('spark/api/teams/roles', 'API\TeamController@getTeamRoles');
        $router->get('spark/api/teams/{id}', 'API\TeamController@getTeam');
        $router->get('spark/api/teams', 'API\TeamController@getAllTeamsForUser');
        $router->get('spark/api/teams/invitation/{code}', 'API\InvitationController@getInvitation');
    }

    // Subscription API Routes...
    if (count(Spark::plans()) > 0) {
        $router->get('spark/api/subscriptions/plans', 'API\SubscriptionController@getPlans');
        $router->get('spark/api/subscriptions/coupon/{code}', 'API\SubscriptionController@getCoupon');
        $router->get('spark/api/subscriptions/user/coupon', 'API\SubscriptionController@getCouponForUser');
    }

});

Route::group(['middleware' => ['api']], function ($router) {

    // Stripe Routes...
    if (count(Spark::plans()) > 0) {
        $router->post('stripe/webhook', 'Stripe\WebhookController@handleWebhook');
    }
});
