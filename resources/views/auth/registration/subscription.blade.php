@extends('layouts.app')

<!-- Scripts -->
@section('scripts')
    <script src="https://js.stripe.com/v2/"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/URI.js/1.15.2/URI.min.js"></script>
@endsection

<!-- Main Content -->
@section('content')
<spark-subscription-register-screen inline-template>
    <div id="spark-register-screen" class="container spark-screen">
        <!-- Inspiration -->
        <div>
            @include('auth.registration.subscription.inspiration')
        </div>

        <!-- Subscription Plan Selector -->
        <div class="col-md-12" v-if="plans.length > 1 && plansAreLoaded && ! forms.registration.plan">
            @include('auth.registration.subscription.plans.selector')
        </div>

        <!-- User Information -->
        <div class="col-md-8 col-md-offset-2" v-if="selectedPlan">
            <!-- The Selected Plan -->
            <div class="row" v-if="plans.length > 1">
                @include('auth.registration.subscription.plans.selected')
            </div>

            <!-- Current Coupon / Discount -->
            <div class="row" v-if="currentCoupon && forms.registration.plan && ! freePlanIsSelected">
                @include('auth.registration.subscription.coupon')
            </div>

            <!-- Invitation -->
            <div class="row">
                @include('auth.registration.subscription.invitation')
            </div>

            <!-- Basic Information -->
            <div class="row">
                @include('auth.registration.subscription.basics')
            </div>

            <!-- Billing Information -->
            <div class="row" v-if=" ! freePlanIsSelected">
                @include('auth.registration.subscription.billing')
            </div>
        </div>
    </div>
</spark-subscription-register-screen>
@endsection
