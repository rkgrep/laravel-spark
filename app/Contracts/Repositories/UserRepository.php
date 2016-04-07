<?php

namespace App\Contracts\Repositories;

use Illuminate\Http\Request;

interface UserRepository
{
    /**
     * Get the current user of the application.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function getCurrentUser();

    /**
     * Create a new user of the application based on a registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $withSubscription
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function createUserFromRegistrationRequest(Request $request, $withSubscription = false);

    /**
     * Create the subscription on Stripe.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    public function createSubscriptionOnStripe(Request $request, $user);
}
