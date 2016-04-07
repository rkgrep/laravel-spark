<!-- Current Coupon If Not One-Time Use -->
<div class="panel panel-default" v-if="currentCoupon && (currentCoupon.lastsForever || currentCoupon.expiresOn)">
    <div class="panel-heading">Current Discount</div>

    <div class="panel-body bg-success">
        <!-- Lifetime Coupons -->
        <div v-if="currentCoupon.lastsForever">
            You receive a @{{ currentPlan.interval }} discount of @{{ currentCouponDisplayDiscount }}.
        </div>

        <!-- Expiring Coupons -->
        <div v-if=" ! currentCoupon.lastsForever && currentCoupon.expiresOn">
            Your @{{ currentPlan.interval }} discount of @{{ currentCouponDisplayDiscount }} expires
            on @{{ currentCouponDisplayExpiresOn }}.
        </div>
    </div>
</div>
