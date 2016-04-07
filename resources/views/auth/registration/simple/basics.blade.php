<div class="panel panel-default">
    <div class="panel-heading">Register</div>

    <div class="panel-body">
        <spark-error-alert :form="forms.registration"></spark-error-alert>

        <form class="form-horizontal" role="form">
            @if (Spark::usingTeams())
                <spark-text :display="'Team Name'"
                            :columns="4"
                            :form="forms.registration"
                            :name="'team_name'"
                            :input.sync="forms.registration.team_name">
                </spark-text>
            @endif

            <spark-text :display="'Name'"
                        :form="forms.registration"
                        :name="'name'"
                        :input.sync="forms.registration.name">
            </spark-text>

            <spark-email :display="'E-Mail Address'"
                         :form="forms.registration"
                         :name="'email'"
                         :input.sync="forms.registration.email">
            </spark-email>

            <spark-password :display="'Password'"
                            :form="forms.registration"
                            :name="'password'"
                            :input.sync="forms.registration.password">
            </spark-password>

            <spark-password :display="'Confirm Password'"
                            :form="forms.registration"
                            :name="'password_confirmation'"
                            :input.sync="forms.registration.password_confirmation">
            </spark-password>

            <div class="form-group" :class="{'has-error': forms.registration.errors.has('terms')}">
                <div class="col-sm-6 col-sm-offset-4">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" v-model="forms.registration.terms"> I Accept The <a href="/terms"
                                                                                                       target="_blank">Terms
                                Of Service</a>

                            <span class="help-block" v-show="forms.registration.errors.has('terms')">
                                <strong>@{{ forms.registration.errors.get('terms') }}</strong>
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-6 col-sm-offset-4">
                    <button type="submit" class="btn btn-primary" @click.prevent="register"
                            :disabled="forms.registration.busy">
                        <span v-if="forms.registration.busy">
                            <i class="fa fa-btn fa-spinner fa-spin"></i> Registering
                        </span>

                        <span v-else>
                            <i class="fa fa-btn fa-check-circle"></i> Register
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
