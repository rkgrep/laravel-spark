<?php

namespace App\Subscriptions;

use Carbon\Carbon;
use JsonSerializable;

class Coupon implements JsonSerializable
{
    /**
     * The ID of the coupon on Stripe.
     *
     * @var string
     */
    public $id;

    /**
     * The duration of the coupons in months.
     *
     * @var int
     */
    public $months;

    /**
     * Indicates if the coupon lasts for one billing period.
     *
     * @var bool
     */
    public $lastsOnce = false;

    /**
     * Indicates if the coupon lasts forever.
     *
     * @var bool
     */
    public $lastsForever = false;

    /**
     * The "amount off" the coupon provides.
     *
     * @var int
     */
    public $amountOff;

    /**
     * The percent off the coupon provides.
     *
     * @var int
     */
    public $percentOff;

    /**
     * The date the coupon expires on.
     *
     * @var \Carbon\Carbon|null
     */
    protected $expiresOn;

    /**
     * Create a new coupon instance.
     *
     * @param  string  $id
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Generate a coupon instance from the given Stripe coupon instance.
     *
     * @param  \Stripe\Coupon  $stripeCoupon
     * @return $this
     */
    public static function fromStripeCoupon($stripeCoupon)
    {
        $coupon = new static($stripeCoupon->id);

        if ($stripeCoupon->duration === 'forever') {
            $coupon->shouldLastForever();
        } elseif ($stripeCoupon->duration === 'once') {
            $coupon->shouldLastOnce();
        } else {
            $coupon->months($stripeCoupon->duration_in_months);
        }

        if (! is_null($stripeCoupon->amount_off)) {
            $coupon->amountOff($stripeCoupon->amount_off / 100);
        } else {
            $coupon->percentOff($stripeCoupon->percent_off);
        }

        return $coupon;
    }

    /**
     * Specify the "amount off" the coupon provides.
     *
     * @param  int  $amountOff
     * @return $this
     */
    public function amountOff($amountOff)
    {
        $this->amountOff = $amountOff;

        return $this;
    }

    /**
     * Specify the "percent off" the coupon provides.
     *
     * @param  int  $percentOff
     * @return $this
     */
    public function percentOff($percentOff)
    {
        $this->percentOff = $percentOff;

        return $this;
    }

    /**
     * Specify how many months the coupon is valid for.
     *
     * @param  int  $months
     * @return $this
     */
    public function months($months)
    {
        $this->months = $months;

        return $this;
    }

    /**
     * Specify that the coupon should last for one billing period.
     *
     * @return $this
     */
    public function shouldLastOnce()
    {
        $this->lastsOnce = true;

        return $this;
    }

    /**
     * Specify that the coupon should last forever.
     *
     * @return $this
     */
    public function shouldLastForever()
    {
        $this->lastsForever = true;

        return $this;
    }

    /**
     * Set the expiration time of the coupon.
     *
     * @param  \Carbon\Carbon  $expires
     * @return $this
     */
    public function expiresOn(Carbon $expires)
    {
        $this->expiresOn = $expires;

        return $this;
    }

    /**
     * Get the JSON serializable fields for the object.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'months' => $this->months,
            'lastsOnce' => $this->lastsOnce,
            'lastsForever' => $this->lastsForever,
            'amountOff' => $this->amountOff,
            'percentOff' => $this->percentOff,
            'expiresOn' => $this->expiresOn ? $this->expiresOn->format('Y-m-d') : null,
        ];
    }
}
