<?php

namespace App\Http\Controllers\API;

use Auth;
use Exception;
use Stripe\Stripe;
use App\Spark;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Stripe\Coupon as StripeCoupon;
use Stripe\Customer as StripeCustomer;
use App\Subscriptions\Coupon;

class SubscriptionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['only' => [
            'getCouponForUser',
        ]]);
    }

    /**
     * Get all of the plans defined for the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPlans()
    {
        return response()->json(Spark::plans());
    }

    /**
     * Get the coupon for a given code.
     *
     * Used for the registration page.
     *
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function getCoupon($code)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        if (count(Spark::plans()) === 0) {
            abort(404);
        }

        try {
            return response()->json(
                Coupon::fromStripeCoupon(StripeCoupon::retrieve($code))
            );
        } catch (Exception $e) {
            abort(404);
        }
    }

    /**
     * Get the current coupon for the authenticated user.
     *
     * Used to display current discount on settings -> subscription tab.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCouponForUser()
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        if (count(Spark::plans()) === 0) {
            abort(404);
        }

        try {
            $customer = StripeCustomer::retrieve(Auth::user()->stripe_id);

            if ($customer->discount) {
                return response()->json(
                    Coupon::fromStripeCoupon(
                        StripeCoupon::retrieve($customer->discount->coupon->id)
                    )
                );
            } else {
                abort(404);
            }
        } catch (Exception $e) {
            abort(404);
        }
    }
}
