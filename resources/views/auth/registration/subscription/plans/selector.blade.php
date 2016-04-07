<!-- Registration Plan Selection -->
<div class="row spark-plan-selector-row" v-if="monthlyPlans.length > 0 && yearlyPlans.length > 0">
    <div class="col-md-6 col-md-offset-3 text-center">
        <span class="spark-plan-selector-interval">
            Monthly &nbsp;
        </span>

        <input type="checkbox"
               id="plan-type-toggle"
               class="spark-toggle spark-toggle-round-flat"
               v-model="planTypeState">

        <label for="plan-type-toggle"></label>

        <span class="spark-plan-selector-interval">
            &nbsp; Yearly
        </span>
    </div>
</div>

<!-- Default / Monthly Plans -->
<div class="row" v-if="defaultPlans.length > 0 && shouldShowDefaultPlans">
    <div class="@{{ getPlanColumnWidth(defaultPlans.length) }}" v-for="plan in defaultPlans">
        @include('auth.registration.subscription.plans.plan')
    </div>
</div>

<!-- Yearly Plans, If Applicable -->
<div class="row" v-if="yearlyPlans.length > 0 && shouldShowYearlyPlans">
    <div class="@{{ getPlanColumnWidth(yearlyPlans.length) }}" v-for="plan in yearlyPlans">
        @include('auth.registration.subscription.plans.plan')
    </div>
</div>
