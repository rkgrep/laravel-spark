<?php

namespace App\Subscriptions;

use JsonSerializable;

class Plan implements JsonSerializable
{
    /**
     * The Stripe ID of the plan.
     *
     * @var string
     */
    public $id;

    /**
     * The human-readable name of the plan.
     *
     * @var string
     */
    public $name;

    /**
     * The price of the plan.
     *
     * This price should be in a human-readable format.
     *
     * @var string
     */
    public $price;

    /**
     * The currency symbol used by the plans.
     *
     * @var string
     */
    public $currencySymbol = '$';

    /**
     * The number of trial days the plan receives.
     *
     * @var int
     */
    public $trialDays;

    /**
     * The "tier" of the plan.
     *
     * @var string
     */
    public $tier;

    /**
     * The features provided by the plan.
     *
     * @var array
     */
    public $features = [];

    /**
     * The user-defined attributes of the plan.
     *
     * @var array
     */
    public $attributes = [];

    /**
     * The billing interval for the plan.
     *
     * @var string
     */
    public $interval = 'monthly';

    /**
     * Specifies whether the plan is active.
     *
     * @var bool
     */
    public $active = true;

    /**
     * Create a new plan instance.
     *
     * @param  string  $name
     * @param  string  $id
     * @return void
     */
    public function __construct($name, $id)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * Determine whether the plan is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * Set the Stripe ID of the plan.
     *
     * @param  string  $id
     * @return $this|string
     */
    public function id($id = null)
    {
        if (is_null($id)) {
            return $this->id;
        }

        $this->id = $id;

        return $this;
    }

    /**
     * Set the display name of the plan.
     *
     * @param  string  $name
     * @return $this|string
     */
    public function name($name = null)
    {
        if (is_null($name)) {
            return $this->name;
        }

        $this->name = $name;

        return $this;
    }

    /**
     * Specify that the plan is free.
     *
     * @return $this
     */
    public function free()
    {
        return $this->price(0);
    }

    /**
     * Set the price of the plan.
     *
     * @param  int  $price
     * @return $this|int
     */
    public function price($price = null)
    {
        if (is_null($price)) {
            return $this->price;
        }

        $this->price = $price;

        return $this;
    }

    /**
     * Set the currency symbol used by the plan.
     *
     * @param  string  $currencySymbol
     * @return $this|string
     */
    public function currencySymbol($currencySymbol)
    {
        if (is_null($currencySymbol)) {
            return $this->currencySymbol;
        }

        $this->currencySymbol = $currencySymbol;

        return $this;
    }

    /**
     * Determine if the plan has a trial period.
     *
     * @return bool
     */
    public function hasTrial()
    {
        return $this->trialDays && $this->trialDays > 0;
    }

    /**
     * Specify the number of trial days the plan receives.
     *
     * @param  int  $trialDays
     * @return $this
     */
    public function trialDays($trialDays = null)
    {
        if (is_null($trialDays)) {
            return $this->trialDays;
        }

        $this->trialDays = $trialDays;

        return $this;
    }

    /**
     * Set the "tier" of the plan.
     *
     * @param  string  $tier
     * @return $this
     */
    public function tier($tier = null)
    {
        if (is_null($tier)) {
            return $this->tier;
        }

        $this->tier = $tier;

        return $this;
    }

    /**
     * Set the features of the plan.
     *
     * @param  array  $features
     * @return $this|array
     */
    public function features(array $features = null)
    {
        if (is_null($features)) {
            return $this->features;
        }

        $this->features = $features;

        return $this;
    }

    /**
     * Set the attributes of the plan.
     *
     * @param  array|null  $attributes
     * @return $this|array
     */
    public function attributes(array $attributes = null)
    {
        if (is_null($attributes)) {
            return $this->attributes;
        }

        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Specify that the plan has a monthly interval.
     *
     * @return $this
     */
    public function monthly()
    {
        $this->interval = 'monthly';

        return $this;
    }

    /**
     * Determine if the plan is on a monthly interval.
     *
     * @return bool
     */
    public function isMonthly()
    {
        return $this->interval === 'monthly';
    }

    /**
     * Specify that the plan has a yearly interval.
     *
     * @return $this
     */
    public function yearly()
    {
        $this->interval = 'yearly';

        return $this;
    }

    /**
     * Determine if the plan is on a yearly interval.
     *
     * @return bool
     */
    public function isYearly()
    {
        return $this->interval === 'yearly';
    }

    /**
     * Specify that the plan is currently hidden.
     *
     * @return $this
     */
    public function hidden()
    {
        $this->active = false;

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
            'name' => $this->name,
            'price' => $this->price,
            'currencySymbol' => $this->currencySymbol,
            'trialDays' => $this->trialDays,
            'tier' => $this->tier,
            'features' => $this->features,
            'attributes' => $this->attributes,
            'interval' => $this->interval,
            'active' => $this->active,
        ];
    }

    /**
     * Provide dynamic access to the object's methods as properties.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        if (method_exists($this, $key)) {
            return $this->{$key}();
        }

        throw new Exception("No property or method [{$key}] exists on this object.");
    }
}
