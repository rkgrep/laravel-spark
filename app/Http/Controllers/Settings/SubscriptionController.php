<?php

namespace App\Http\Controllers\Settings;

use Exception;
use App\Spark;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Events\User\Subscribed;
use Illuminate\Support\Facades\Validator;
use App\InteractsWithSparkHooks;
use Illuminate\View\Expression as ViewExpression;
use App\Events\User\SubscriptionResumed;
use App\Events\User\SubscriptionCancelled;
use App\Events\User\SubscriptionPlanChanged;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Contracts\Repositories\UserRepository;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

class SubscriptionController extends Controller
{
	use InteractsWithSparkHooks, ValidatesRequests;

    /**
     * The user repository instance.
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
     * Subscribe the user to a new plan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request)
    {
        $this->validateSubscription($request);

        $this->users->createSubscriptionOnStripe($request, Auth::user());

        event(new Subscribed(Auth::user()));

        return $this->users->getCurrentUser();
    }

    /**
     * Validate the incoming request to subscribe the user to a plan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateSubscription(Request $request)
    {
        if (Spark::$validateSubscriptionsWith) {
            $this->callCustomValidator(
                Spark::$validateSubscriptionsWith, $request
            );
        } else {
            $this->validate($request, [
                'plan' => 'required',
                'terms' => 'required|accepted',
                'stripe_token' => 'required',
            ]);
        }
    }

    /**
     * Change the user's subscription plan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changeSubscriptionPlan(Request $request)
    {
        $this->validate($request, [
            'plan' => 'required',
        ]);

        $plan = Spark::plans()->find($request->plan);

        if ($plan->price() === 0) {
            $this->cancelSubscription();
        } elseif (Spark::$swapSubscriptionsWith) {
            $this->callCustomUpdater(Spark::$swapSubscriptionsWith, $request, [Auth::user()]);
        } else {
            Auth::user()->subscription('main')->swap($request->plan);
        }

        event(new SubscriptionPlanChanged(Auth::user()));

        return $this->users->getCurrentUser();
    }

    /**
     * Update the user's billing card information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateCard(Request $request)
    {
        $this->validate($request, [
            'stripe_token' => 'required',
        ]);

        Auth::user()->updateCard($request->stripe_token);

        return $this->users->getCurrentUser();
    }

    /**
     * Update the extra billing information for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateExtraBillingInfo(Request $request)
    {
        Auth::user()->extra_billing_info = $request->text;

        Auth::user()->save();
    }

    /**
     * Cancel the user's subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancelSubscription()
    {
        Auth::user()->subscription('main')->cancel();

        event(new SubscriptionCancelled(Auth::user()));

        return $this->users->getCurrentUser();
    }

    /**
     * Resume the user's subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function resumeSubscription()
    {
        $user = Auth::user();

        $user->subscription('main')->resume();

        event(new SubscriptionResumed(Auth::user()));

        return $this->users->getCurrentUser();
    }

    /**
     * Download the given invoice for the user.
     *
     * @param  string  $invoiceId
     * @return \Illuminate\Http\Response
     */
    public function downloadInvoice(Request $request, $invoiceId)
    {
        $data = array_merge([
            'vendor' => 'Vendor',
            'product' => 'Product',
            'vat' => new ViewExpression(nl2br(e($request->user()->extra_billing_info))),
        ], Spark::generateInvoicesWith());

        return Auth::user()->downloadInvoice($invoiceId, $data);
    }
}
