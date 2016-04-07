<!-- Registration -> Individual Plan Display Block -->
<div class="panel panel-default spark-plan spark-plan-@{{ plan.id }}">
    <div class="panel-heading text-center">@{{ plan.name }}</div>
    <div class="panel-body">
        <ul>
            <li v-for="feature in plan.features">@{{ feature }}</li>
        </ul>

        <hr>

        <!-- Plan Price -->
        <div class="spark-plan-price">
            <div v-if="currentCoupon">
                <div v-if="plan.price > 0 && currentCoupon.lastsForever">
                    <strike>@{{ plan.currencySymbol }}@{{ plan.price }}</strike>
                </div>

                <div v-if="plan.price === 0 || ! currentCoupon.lastsForever">
                    @{{ plan.currencySymbol }}@{{ plan.price }}
                </div>
            </div>

            <div v-else>
                @{{ plan.currencySymbol }}@{{ plan.price }}
            </div>
        </div>

        <!-- Plan Interval -->
        <div class="spark-plan-interval">
            @{{ plan.interval }}
        </div>

        <!-- Plan Discount -->
        <div v-if="plan.price > 0 && currentCoupon">
            <hr>

            <div>
                <div class="spark-plan-discount">
                    @{{ plan.currencySymbol }}@{{ getDiscountPlanPrice(plan.price) }}
                </div>

                <div class="spark-plan-discount-interval">
                    @{{ getCouponDisplayDuration(plan) }}
                </div>
            </div>
        </div>

        <hr>

        <div class="spark-plan-subscribe-button-container">
            <button class="btn btn-primary spark-plan-subscribe-button" @click.prevent="selectPlan(plan)">
                <span v-if=" ! plan.trialDays && plan.price == 0">
                    Register
                </span>

                <span v-if=" ! plan.trialDays && plan.price > 0">
                    Subscribe
                </span>

                <span v-if="plan.trialDays">
                    Begin @{{ plan.trialDays }} Day Trial
                </span>
            </button>
        </div>
    </div>
</div>
