<div class="panel panel-default" v-if="userIsSubscribed">
    <div class="panel-heading">Current Subscription</div>

    <div class="panel-body">
        <div class="pull-left" style="line-height: 36px;">
            You are subscribed to the <strong>@{{ currentPlan.name }}</strong>
            (@{{ currentPlan.currencySymbol }}@{{ currentPlan.price }} / @{{ currentPlan.interval | capitalize}}) plan.
        </div>

        <div class="pull-right" style="line-height: 32px;">
            <button class="btn btn-primary" @click.prevent="confirmPlanChange" v-if="plans.length > 1">
                <i class="fa fa-btn fa-random"></i>Change Plan
            </button>
        </div>

        <div class="clearfix"></div>
    </div>
</div>
