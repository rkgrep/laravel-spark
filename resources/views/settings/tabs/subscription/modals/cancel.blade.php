<div class="modal fade" id="modal-cancel-subscription" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="fa fa-btn fa-times"></i>Cancel Subscription</h4>
            </div>

            <spark-errors :form="forms.cancelSubscription"></spark-errors>

            <div class="modal-body">
                <p>Are you sure you want to cancel your subscription?</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>

                <button type="button" class="btn btn-danger" @click.prevent="cancelSubscription"
                        :disabled="forms.cancelSubscription.busy">
                    <span v-if=" ! forms.cancelSubscription.busy">
                        <i class="fa fa-btn fa-times"></i>Cancel Subscription
                    </span>

                    <span v-else>
                        <i class="fa fa-btn fa-spinner fa-spin"></i>Cancelling
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
