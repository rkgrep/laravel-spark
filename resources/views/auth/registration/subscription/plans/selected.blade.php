<div class="panel panel-default">
    <div class="panel-heading">Your Plan</div>
    <div class="panel-body">

        <!-- Current Plan -->
        <div class="pull-left" style="line-height: 36px;">
            You have selected the <strong>@{{ selectedPlan.name }}</strong>
            (@{{ selectedPlanPrice }} / @{{ selectedPlan.interval | capitalize}}) plan.
        </div>

        <!-- Select Another Plan -->
        <div class="pull-right" style="line-height: 32px;">
            <button class="btn btn-primary" @click.prevent="selectAnotherPlan">
                <i class="fa fa-btn fa-arrow-left"></i>Change Plan
            </button>
        </div>

        <div class="clearfix"></div>
    </div>
</div>
