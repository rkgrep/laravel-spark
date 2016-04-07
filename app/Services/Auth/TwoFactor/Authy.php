<?php

namespace App\Services\Auth\TwoFactor;

use Exception;
use GuzzleHttp\Client as HttpClient;
use App\Contracts\Auth\TwoFactor\Provider;
use App\Contracts\Auth\TwoFactor\Authenticatable as TwoFactorAuthenticatable;

class Authy implements Provider
{
    /**
     * Determine if the given user has two-factor authentication enabled.
     *
     * @param  \App\Contracts\Auth\TwoFactor\Authenticatable  $user
     * @return bool
     */
    public function isEnabled(TwoFactorAuthenticatable $user)
    {
        return isset($user->getTwoFactorAuthProviderOptions()['id']);
    }

    /**
     * Register the given user with the provider.
     *
     * @param  \App\Contracts\Auth\TwoFactor\Authenticatable  $user
     * @return void
     */
    public function register(TwoFactorAuthenticatable $user)
    {
        $key = config('services.authy.key');

        $response = json_decode((new HttpClient)->post('https://api.authy.com/protected/json/users/new?api_key='.$key, [
            'form_params' => [
                'user' => [
                    'email' => $user->getEmailForTwoFactorAuth(),
                    'cellphone' => preg_replace('/[^0-9]/', '', $user->getAuthPhoneNumber()),
                    'country_code' => $user->getAuthCountryCode(),
                ],
            ],
        ])->getBody(), true);

        $user->setTwoFactorAuthProviderOptions([
            'id' => $response['user']['id'],
        ]);
    }

    /**
     * Determine if the given token is valid for the given user.
     *
     * @param  \App\Contracts\Auth\TwoFactor\Authenticatable  $user
     * @param  string  $token
     * @return bool
     */
    public function tokenIsValid(TwoFactorAuthenticatable $user, $token)
    {
        try {
            $key = config('services.authy.key');

            $options = $user->getTwoFactorAuthProviderOptions();

            $response = json_decode((new HttpClient)->get(
                'https://api.authy.com/protected/json/verify/'.$token.'/'.$options['id'].'?force=true&api_key='.$key
            )->getBody(), true);

            return $response['token'] === 'is valid';
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Delete the given user from the provider.
     *
     * @param  \App\Contracts\Auth\TwoFactor\Authenticatable  $user
     * @return bool
     */
    public function delete(TwoFactorAuthenticatable $user)
    {
        $key = config('services.authy.key');

        $options = $user->getTwoFactorAuthProviderOptions();

        (new HttpClient)->post(
            'https://api.authy.com/protected/json/users/delete/'.$options['id'].'?api_key='.$key
        );

        $user->setTwoFactorAuthProviderOptions([]);
    }
}
