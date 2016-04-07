<spark-settings-security-password-screen inline-template>
    <div class="panel panel-default">
        <div class="panel-heading">Update Password</div>

        <div class="panel-body">
            <spark-error-alert :form="forms.updatePassword"></spark-error-alert>

            <div class="alert alert-success" v-if="forms.updatePassword.successful">
                <strong>Great!</strong> Your password was successfully updated.
            </div>

            <form class="form-horizontal" role="form">
                <spark-password :display="'Current Password'"
                                :form="forms.updatePassword"
                                :name="'current_password'"
                                :input.sync="forms.updatePassword.current_password">
                </spark-password>

                <spark-password :display="'New Password'"
                                :form="forms.updatePassword"
                                :name="'password'"
                                :input.sync="forms.updatePassword.password">
                </spark-password>

                <spark-password :display="'Confirm Password'"
                                :form="forms.updatePassword"
                                :name="'password_confirmation'"
                                :input.sync="forms.updatePassword.password_confirmation">
                </spark-password>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary" @click.prevent="updatePassword"
                                :disabled="forms.updatePassword.busy">
                            <span v-if="forms.updatePassword.busy">
                                <i class="fa fa-btn fa-spinner fa-spin"></i> Updating
                            </span>

                            <span v-else>
                                <i class="fa fa-btn fa-save"></i> Update
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</spark-settings-security-password-screen>
