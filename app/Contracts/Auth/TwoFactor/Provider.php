<?php

namespace App\Contracts\Auth\TwoFactor;

use App\Contracts\Auth\TwoFactor\Authenticatable as TwoFactorAuthenticatable;

interface Provider
{
    /**
     * Determine if the given user has two-factor authentication enabled.
     *
     * @param  \App\Contracts\Auth\TwoFactor\Authenticatable  $user
     * @return bool
     */
    public function isEnabled(TwoFactorAuthenticatable $user);

    /**
     * Register the given user with the provider.
     *
     * @param  \App\Contracts\Auth\TwoFactor\Authenticatable  $user
     * @return void
     */
    public function register(TwoFactorAuthenticatable $user);

    /**
     * Determine if the given token is valid for the given user.
     *
     * @param  \App\Contracts\Auth\TwoFactor\Authenticatable  $user
     * @param  string  $token
     * @return bool
     */
    public function tokenIsValid(TwoFactorAuthenticatable $user, $token);

    /**
     * Delete the given user from the provider.
     *
     * @param  \App\Contracts\Auth\TwoFactor\Authenticatable  $user
     * @return bool
     */
    public function delete(TwoFactorAuthenticatable $user);
}
