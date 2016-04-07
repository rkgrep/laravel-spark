<div class="modal fade" id="modal-change-plan" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="fa fa-btn fa-random"></i>Change Plan</h4>
            </div>

            <div class="modal-body">
                <spark-errors :form="forms.changePlan"></spark-errors>

                <!-- Plan Selector -->
                <div class="row">
                    <!-- Monthly / Available Plans -->
                    <div class="col-md-6">
                        <div class="spark-plan-change-selector-heading">
                            <span v-if="includesBothPlanIntervals">
                                <strong>Monthly Plans</strong>
                            </span>

                            <span v-else>
                                <strong>Available Plans</strong>
                            </span>
                        </div>

                        <div v-for="plan in defaultPlansExceptCurrent" style="margin-bottom: 10px;">
                            @include('settings.tabs.subscription.modals.change.plan')
                        </div>
                    </div>

                    <!-- Yearly Plans -->
                    <div class="col-md-6" v-if="includesBothPlanIntervals">
                        <div class="spark-plan-change-selector-heading">
                            <strong>Yearly Plans</strong>
                        </div>

                        <div v-for="plan in yearlyPlansExceptCurrent" style="margin-bottom: 10px;">
                            @include('settings.tabs.subscription.modals.change.plan')
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>

                <button type="button" class="btn btn-primary" @click.prevent="changePlan"
                        :disabled="forms.changePlan.busy">
                    <span v-if="forms.changePlan.busy">
                        <i class="fa fa-btn fa-spinner fa-spin"></i>Changing
                    </span>

                    <span v-else>
                        <i class="fa fa-btn fa-random"></i>Change Plan
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
