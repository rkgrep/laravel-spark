<div class="panel panel-default" v-if="userIsSubscribed">
    <div class="panel-heading">
        <div class="pull-left">
            Update Card
        </div>

        <div class="pull-right">
            <span v-if="user.card_last_four">
                <i class="fa fa-btn fa-cc-@{{ creditCardBrandIcon }}"></i>
                ************@{{ user.card_last_four }}
            </span>
        </div>

        <div class="clearfix"></div>
    </div>

    <div class="panel-body">
        <spark-error-alert :form="forms.updateCard"></spark-error-alert>

        <div class="alert alert-success" v-if="forms.updateCard.successful">
            <strong>Done!</strong> Your card has been updated.
        </div>

        <form class="form-horizontal" role="form">
            <div class="form-group" :class="{'has-error': forms.updateCard.errors.has('number')}">
                <label for="number" class="col-md-4 control-label">Card Number</label>

                <div class="col-md-6">
                    <input type="text"
                           class="form-control"
                           name="number"
                           data-stripe="number"
                           placeholder="************@{{ user.card_last_four }}"
                           v-model="forms.updateCard.number">

                    <span class="help-block" v-show="forms.updateCard.errors.has('number')">
                        <strong>@{{ forms.updateCard.errors.get('number') }}</strong>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label for="cvc" class="col-md-4 control-label">Security Code</label>

                <div class="col-md-6">
                    <input type="text" class="form-control" name="cvc" data-stripe="cvc" v-model="forms.updateCard.cvc">
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">Expiration</label>

                <div class="col-md-3">
                    <input type="text" class="form-control" name="month" placeholder="MM" maxlength="2"
                           data-stripe="exp-month" v-model="forms.updateCard.month">
                </div>

                <div class="col-md-3">
                    <input type="text" class="form-control" name="year" placeholder="YYYY" maxlength="4"
                           data-stripe="exp-year" v-model="forms.updateCard.year">
                </div>
            </div>

            <div class="form-group">
                <label for="zip" class="col-md-4 control-label">ZIP / Postal Code</label>

                <div class="col-md-6">
                    <input type="text" class="form-control" name="zip" v-model="forms.updateCard.zip">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <button type="submit" class="btn btn-primary" @click.prevent="updateCard"
                            :disabled="forms.updateCard.busy">
                        <span v-if="forms.updateCard.busy">
                            <i class="fa fa-btn fa-spinner fa-spin"></i> Updating
                        </span>

                        <span v-else>
                            <i class="fa fa-btn fa-credit-card"></i> Update
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
