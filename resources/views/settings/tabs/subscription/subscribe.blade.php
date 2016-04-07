<!-- Subscribe Plan Selector -->
<div class="panel panel-default" v-if=" ! userIsSubscribed && ! userIsOnGracePeriod && ! forms.subscribe.plan">
    <div class="panel-heading">Subscribe</div>

    <div class="panel-body">
        @include('settings.tabs.subscription.subscribe.selector')
    </div>
</div>

<!-- Plan Is Selected -->
<div v-if=" ! userIsSubscribed && ! userIsOnGracePeriod && forms.subscribe.plan">
    <!-- Selected Plan / Select Another Plan -->
    <div class="panel panel-default">
        <div class="panel-heading">Your Plan</div>

        <div class="panel-body">
            <div class="pull-left" style="line-height: 36px;">
                You have selected the <strong>@{{ selectedPlan.name }}</strong>
                (@{{ selectedPlanPrice }} / @{{ selectedPlan.interval | capitalize}}) plan.
            </div>

            <div class="pull-right" style="line-height: 32px;">
                <button class="btn btn-primary" @click.prevent="selectAnotherPlan">
                    <i class="fa fa-btn fa-arrow-left"></i>Change Plan
                </button>
            </div>

            <div class="clearfix"></div>
        </div>
    </div>

    <!-- Subscription Billing Information -->
    <div class="panel panel-default">
        <div class="panel-heading">
            Billing Information
        </div>

        <div class="panel-body">
            <spark-error-alert :form="forms.subscribe"></spark-error-alert>
            <spark-error-alert :form="forms.card"></spark-error-alert>

            <form class="form-horizontal" role="form">
                <div class="form-group" :class="{'has-error': forms.card.errors.has('number')}">
                    <label for="number" class="col-md-4 control-label">Card Number</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control spark-first-field" name="number" data-stripe="number"
                               v-model="forms.card.number">

                        <span class="help-block" v-show="forms.card.errors.has('number')">
                            <strong>@{{ forms.card.errors.get('number') }}</strong>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="cvc" class="col-md-4 control-label">Security Code</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="cvc" data-stripe="cvc" v-model="forms.card.cvc">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-4 control-label">Expiration</label>

                    <div class="col-md-3">
                        <input type="text" class="form-control" name="month" placeholder="MM" maxlength="2"
                               data-stripe="exp-month" v-model="forms.card.month">
                    </div>

                    <div class="col-md-3">
                        <input type="text" class="form-control" name="year" placeholder="YYYY" maxlength="4"
                               data-stripe="exp-year" v-model="forms.card.year">
                    </div>
                </div>

                <div class="form-group">
                    <label for="zip" class="col-md-4 control-label">ZIP / Postal Code</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="zip" v-model="forms.card.zip">
                    </div>
                </div>

                <div class="form-group" :class="{'has-error': forms.subscribe.errors.has('terms')}">
                    <div class="col-md-6 col-md-offset-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" v-model="forms.subscribe.terms">
                                I Accept The <a href="/terms" target="_blank">Terms Of Service</a>
                            </label>
                        </div>

                        <span class="help-block" v-show="forms.subscribe.errors.has('terms')">
                            <strong>@{{ forms.subscribe.errors.get('terms') }}</strong>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary" @click.prevent="subscribe"
                                :disabled="forms.subscribe.busy">
                            <span v-if="forms.subscribe.busy">
                                <i class="fa fa-btn fa-spinner fa-spin"></i> Subscribing
                            </span>

                            <span v-else>
                                <i class="fa fa-btn fa-check-circle"></i> Subscribe
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
