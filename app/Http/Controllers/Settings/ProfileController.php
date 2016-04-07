<?php

namespace App\Http\Controllers\Settings;

use Exception;
use App\Spark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Events\User\ProfileUpdated;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Contracts\Repositories\UserRepository;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

class ProfileController extends Controller
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
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateUserProfile(Request $request)
    {
        $this->validateUserProfile($request);

        $originalEmail = Auth::user()->email;

        if (Spark::$updateProfilesWith) {
            $this->callCustomUpdater(Spark::$updateProfilesWith, $request);
        } else {
            Auth::user()->fill($request->all())->save();
        }

        if (Auth::user()->stripe_id && $originalEmail !== Auth::user()->email) {
            $this->updateStripeEmailAddress();
        }

        event(new ProfileUpdated(Auth::user()));

        return $this->users->getCurrentUser();
    }

    /**
     * Validate the incoming request to update the user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateUserProfile(Request $request)
    {
        if (Spark::$validateProfileUpdatesWith) {
            $this->callCustomValidator(
                Spark::$validateProfileUpdatesWith, $request
            );
        } else {
            $this->validate($request, [
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users,email,'.Auth::id(),
            ]);
        }
    }

    /**
     * Update the user's e-mail address on Stripe.
     *
     * @return void
     */
    protected function updateStripeEmailAddress()
    {
        $customer = Auth::user()->asStripeCustomer();

        $customer->email = Auth::user()->email;

        $customer->save();
    }
}
