<div class="panel panel-default">
    <div class="panel-heading">Discount</div>

    <div class="panel-body bg-success">
        <div>
            Your coupon
            awards @{{ currentCouponDisplayDiscount | lowercase }} @{{ getCouponDisplayDuration(selectedPlan) | lowercase }}
            !
        </div>
    </div>
</div>
