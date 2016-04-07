var settingsSubscriptionScreenForms = {
    updateCard: function () {
        return new SparkForm({
            number: '', cvc: '', month: '', year: '', zip: ''
        });
    }
};

Vue.component('spark-settings-subscription-screen', {
    /*
     * Bootstrap the component. Load the initial data.
     */
    ready: function () {
        Stripe.setPublishableKey(Spark.stripeKey);

        this.getPlans();

        this.initializeTooltips();
    },


    /*
     * Configure watched data listeners.
     */
    watch: {
        'forms.subscribe.plan': function (value, oldValue) {
            if (value.length > 0) {
                setTimeout(function () {
                    $('.spark-first-field').filter(':visible:first').focus();
                }, 250);
            } else {
                this.initializeTooltips();
            }
        }
    },

    /*
     * Initial state of the component's data.
     */
    data: function () {
        return {
            user: null,
            currentCoupon: null,

            plans: [],

            forms: {
                subscribe: new SparkForm({
                    plan: '', terms: false, stripe_token: null
                }),

                card: new SparkForm({
                    number: '', cvc: '', month: '', year: '', zip: ''
                }),

                changePlan: new SparkForm({
                    plan: ''
                }),

                updateCard: settingsSubscriptionScreenForms.updateCard(),

                extraBillingInfo: new SparkForm({
                    text: ''
                }),

                resumeSubscription: new SparkForm({}),
                cancelSubscription: new SparkForm({})
            }
        };
    },


    computed: {
        /*
         * Determine if the user has been loaded from the database.
         */
        userIsLoaded: function () {
            if (this.user) {
                return true;
            }

            return false;
        },


        /*
         * Get the main subscription instance.
         */
        subscription: function () {
            return _.find(this.user.subscriptions, function (subscription) {
                return subscription.name == 'main';
            });
        },


        /*
         * Determine if the user is subscribed to the main plain.
         */
        userIsSubscribed: function () {
            var subscription = this.subscription;

            if (subscription !== 'undefined') {
                if (subscription.ends_at) {
                    return false;
                } else {
                    return true;
                }
            }
        },


        /*
         * Determine if the user is currently on the "grace period".
         */
        userIsOnGracePeriod: function () {
            if (this.subscription.ends_at) {
                return moment().isBefore(this.subscription.ends_at);
            }

            return false;
        },


        /*
         * Determine the date that the user's grace period is over.
         */
        subscriptionEndsAt: function () {
            if (this.subscription.ends_at) {
                return moment(this.subscription.ends_at).format('MMMM Do');
            }
        },


        /*
         * Determine if the plans have been loaded from the database.
         */
        plansAreLoaded: function () {
            return this.plans.length > 0;
        },


        /*
         * This method is used to determine if we need to display both a
         * monthly and yearly plans column, or if we will just show a
         * single column of available plans for the user to choose.
         */
        includesBothPlanIntervals: function () {
            return this.plansAreLoaded && this.monthlyPlans.length > 0 && this.yearlyPlans.length > 0;
        },


        /*
         * Retrieve the plan that the user is currently subscribed to.
         */
        currentPlan: function () {
            var self = this;

            if (!this.userIsLoaded) {
                return null;
            }

            var plan = _.find(this.plans, function (plan) {
                return plan.id == self.subscription.stripe_plan;
            });

            if (plan !== 'undefined') {
                return plan;
            }
        },


        /*
         * Get the plan currently selected on the subscribe form.
         */
        selectedPlan: function () {
            var self = this;

            return _.find(this.plans, function (plan) {
                return plan.id == self.forms.subscribe.plan;
            });
        },


        /*
         * Get the full selected plan price with currency symbol.
         */
        selectedPlanPrice: function () {
            if (this.selectedPlan) {
                return this.selectedPlan.currencySymbol + this.selectedPlan.price;
            }
        },


        /*
         * Get all of the plans that have a mnthly interval.
         */
        monthlyPlans: function () {
            return _.filter(this.plans, function (plan) {
                return plan.interval == 'monthly' && plan.active;
            });
        },


        /*
         * Get all of the plans that have a yearly interval.
         */
        yearlyPlans: function () {
            return _.filter(this.plans, function (plan) {
                return plan.interval == 'yearly' && plan.active;
            });
        },


        /*
         * Get all of the "default" available plans. Typically this is monthly.
         */
        defaultPlans: function () {
            if (this.monthlyPlans.length > 0) {
                return this.monthlyPlans;
            }

            if (this.yearlyPlans.length > 0) {
                return this.yearlyPlans;
            }
        },


        /*
         * Get all of the available plans except this user's current plan.
         * This'll typically be the monthly plans unless there are none.
         */
        defaultPlansExceptCurrent: function () {
            if (this.monthlyPlansExceptCurrent.length > 0) {
                return this.monthlyPlansExceptCurrent;
            }

            if (this.yearlyPlansExceptCurrent.length > 0) {
                return this.yearlyPlansExceptCurrent;
            }
        },


        /*
         * Get all of the monthly plans except the user's current plan.
         */
        monthlyPlansExceptCurrent: function () {
            var self = this;

            if (!this.currentPlan) {
                return [];
            }

            return _.filter(this.monthlyPlans, function (plan) {
                return plan.id != self.currentPlan.id;
            });
        },


        /*
         * Get all of the yearly plans except the user's current plan.
         */
        yearlyPlansExceptCurrent: function () {
            var self = this;

            if (!this.currentPlan) {
                return [];
            }

            return _.filter(this.yearlyPlans, function (plan) {
                return plan.id != self.currentPlan.id;
            });
        },


        /*
         * Get the expiratoin date for the current coupon in displayable form.
         */
        currentCouponDisplayDiscount: function () {
            if (this.currentCoupon) {
                if (this.currentCoupon.amountOff) {
                    return this.currentPlan.currencySymbol + this.currentCoupon.amountOff;
                }

                if (this.currentCoupon.percentOff) {
                    return this.currentCoupon.percentOff + '%';
                }
            }
        },


        /*
         * Get the expiratoin date for the current coupon in displayable form.
         */
        currentCouponDisplayExpiresOn: function () {
            return moment(this.currentCoupon.expiresOn).format('MMMM Do, YYYY');
        },


        /**
         * Get the proper brand icon for the customer's credit card.
         */
        creditCardBrandIcon: function () {
            if (!this.user.card_brand) {
                return 'stripe';
            }

            switch (this.user.card_brand) {
                case 'American Express':
                    return 'amex';
                case 'Diners Club':
                    return 'diners-club';
                case 'Discover':
                    return 'discover';
                case 'JCB':
                    return 'jcb';
                case 'MasterCard':
                    return 'mastercard';
                case 'Visa':
                    return 'visa';
                default:
                    return 'stripe';
            }
        }
    },


    events: {
        /**
         * Handle the "userRetrieved" event.
         */
        userRetrieved: function (user) {
            this.user = user;

            this.forms.extraBillingInfo.text = this.user.extra_billing_info;

            if (this.user.stripe_id) {
                this.getCoupon();
            }

            return true;
        }
    },


    methods: {
        /*
         * Get the coupon currently applying to the customer.
         */
        getCoupon: function () {
            this.$http.get('spark/api/subscriptions/user/coupon')
                .success(function (coupon) {
                    this.currentCoupon = coupon;
                });
        },


        /*
         * Get all of the Spark plans from the API.
         */
        getPlans: function () {
            this.$http.get('spark/api/subscriptions/plans')
                .success(function (plans) {
                    this.plans = plans;
                });
        },


        /*
         * Subscribe the user to a new plan.
         */
        subscribe: function () {
            var self = this;

            this.forms.card.errors.forget();
            this.forms.subscribe.errors.forget();

            this.forms.subscribe.busy = true;

            /*
             * Here we will build the payload to send to Stripe, which will
             * return a token. This token can be used to make charges on
             *  the user's credit cards instead of storing the numbers.
             */
            var payload = {
                name: this.user.name,
                number: this.forms.card.number,
                cvc: this.forms.card.cvc,
                exp_month: this.forms.card.month,
                exp_year: this.forms.card.year,
                address_zip: this.forms.card.zip
            };

            Stripe.card.createToken(payload, function (status, response) {
                if (response.error) {
                    self.forms.card.errors.set({number: [response.error.message]});

                    self.forms.subscribe.busy = false;
                } else {
                    self.forms.subscribe.stripe_token = response.id;
                    self.sendSubscription();
                }
            });
        },


        /*
         * Subscribe the user to a new plan.
         */
        sendSubscription: function () {
            var self = this;

            Spark.post('/settings/user/plan', this.forms.subscribe)
                .then(function () {
                    self.$dispatch('updateUser');
                });
        },


        /*
         * Reset the subscription form plan to allow another plan to be selected.
         */
        selectAnotherPlan: function () {
            this.forms.subscribe.plan = '';
        },


        /*
         * Show the modal screen to select another subscription plan.
         */
        confirmPlanChange: function () {
            this.forms.changePlan.busy = false;
            this.forms.changePlan.errors.forget();

            $('#modal-change-plan').modal('show');

            this.initializeTooltips();
        },


        /*
         * Subscribe the user to another subscription plan.
         */
        changePlan: function () {
            var self = this;

            Spark.put('/settings/user/plan', this.forms.changePlan)
                .then(function () {
                    self.$dispatch('updateUser');

                    /*
                     * We need to re-initialize the tooltips on the screen because
                     * some of the plans may not have been displayed yet if the
                     * users just "switched" plans to another available plan.
                     */
                    self.initializeTooltips();

                    $('#modal-change-plan').modal('hide');
                });
        },


        /*
         * Update the user's subscription billing card (Stripe portion).
         */
        updateCard: function () {
            var self = this;

            this.forms.updateCard.errors.forget();

            this.forms.updateCard.busy = true;
            this.forms.updateCard.successful = false;

            /*
             * Here we will build the payload to send to Stripe, which will
             * return a token. This token can be used to make charges on
             * the user's credit cards instead of storing the numbers.
             */
            var payload = {
                name: this.user.name,
                number: this.forms.updateCard.number,
                cvc: this.forms.updateCard.cvc,
                exp_month: this.forms.updateCard.month,
                exp_year: this.forms.updateCard.year,
                address_zip: this.forms.updateCard.zip
            };

            Stripe.card.createToken(payload, function (status, response) {
                if (response.error) {
                    self.forms.updateCard.errors.set({number: [response.error.message]});

                    self.forms.updateCard.busy = false;
                } else {
                    self.updateCardUsingToken(response.id);
                }
            });
        },


        /*
         * Update the user's subscription billing card.
         */
        updateCardUsingToken: function (token) {
            this.$http.put('settings/user/card', {stripe_token: token})
                .success(function () {
                    this.$dispatch('updateUser');

                    this.forms.updateCard = settingsSubscriptionScreenForms.updateCard();
                    this.forms.updateCard.successful = true;
                })
                .error(function (errors) {
                    this.forms.updateCard.busy = false;

                    this.forms.updateCard.errors.set(errors);
                });
        },


        /*
         * Update the user's extra billing information.
         */
        updateExtraBillingInfo: function () {
            Spark.put('/settings/user/vat', this.forms.extraBillingInfo);
        },


        /*
         * Display the modal window to confirm subscription deletion.
         */
        confirmSubscriptionCancellation: function () {
            $('#modal-cancel-subscription').modal('show');
        },


        /*
         * Cancel the user's subscription with Stripe.
         */
        cancelSubscription: function () {
            var self = this;

            Spark.delete('/settings/user/plan', this.forms.cancelSubscription)
                .then(function () {
                    self.$dispatch('updateUser');

                    $('#modal-cancel-subscription').modal('hide');

                    setTimeout(function () {
                        self.forms.cancelSubscription.cancelling = false;
                    }, 500);
                });
        },


        /*
         * Resume the user's subscription on Stripe.
         */
        resumeSubscription: function () {
            var self = this;

            Spark.post('/settings/user/plan/resume', this.forms.resumeSubscription)
                .then(function () {
                    self.$dispatch('updateUser');
                });
        },


        /*
         * Get the feature list from the plan formatted for a tooltip.
         */
        getPlanFeaturesForTooltip: function (plan) {
            var result = '<ul>';

            _.each(plan.features, function (feature) {
                result += '<li>' + feature + '</li>';
            });

            return result + '</ul>';
        },


        /*
         * Initialize the tooltips for the plan features.
         */
        initializeTooltips: function () {
            setTimeout(function () {
                $('[data-toggle="tooltip"]').tooltip({
                    html: true
                });
            }, 250);
        }
    }
});
