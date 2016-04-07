<?php

namespace App\Http\Controllers\Settings;

use Exception;
use App\Spark;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Events\User\ProfileUpdated;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Contracts\Repositories\UserRepository;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

class SecurityController extends Controller
{
    use ValidatesRequests;

    /**
     * The user repository implementation.
     *
     * @var \App\Contracts\Repositories\UserRepository
     */
    protected $users;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Contracts\Repositories\UserRepository  $users
     * @return void
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;

        $this->middleware('auth');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        if (! Hash::check($request->current_password, Auth::user()->password)) {
            return response()->json(
                ['current_password' => ['The current password you provided is incorrect.']], 422
            );
        }

        Auth::user()->password = Hash::make($request->password);

        Auth::user()->save();
    }

    /**
     * Enable two-factor authentication for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function enableTwoFactorAuth(Request $request)
    {
        if (! is_null($response = $this->validateEnablingTwoFactorAuth($request))) {
            return $response;
        }

        $user = Auth::user();

        $user->setAuthPhoneInformation(
            $request->country_code, $request->phone_number
        );

        try {
            Spark::twoFactorProvider()->register($user);
        } catch (Exception $e) {
            app(ExceptionHandler::class)->report($e);

            return response()->json(['phone_number' => ['The provided phone information is invalid.']], 422);
        }

        $user->save();

        return $this->users->getCurrentUser();
    }

    /**
     * Validate an incoming request to enable two-factor authentication.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|null
     */
    protected function validateEnablingTwoFactorAuth(Request $request)
    {
        $input = $request->all();

        if (isset($input['phone_number'])) {
            $input['phone_number'] = preg_replace('/[^0-9]/', '', $input['phone_number']);
        }

        $validator = Validator::make($input, [
            'country_code' => 'required|numeric|integer',
            'phone_number' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    }

    /**
     * Disable two-factor authentication for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function disableTwoFactorAuth(Request $request)
    {
        Spark::twoFactorProvider()->delete(Auth::user());

        Auth::user()->save();

        return $this->users->getCurrentUser();
    }
}
