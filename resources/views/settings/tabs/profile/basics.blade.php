<spark-settings-profile-basics-screen inline-template>
    <div id="spark-settings-profile-basics-screen" class="panel panel-default">
        <div class="panel-heading">The Basics</div>

        <div class="panel-body">
            <spark-error-alert :form="forms.updateProfileBasics"></spark-error-alert>

            <div class="alert alert-success" v-if="forms.updateProfileBasics.successful">
                <i class="fa fa-btn fa-check-circle"></i>Great! Your profile was successfully updated.
            </div>

            <form class="form-horizontal" role="form">
                <spark-text :display="'Name'"
                            :form="forms.updateProfileBasics"
                            :name="'name'"
                            :input.sync="forms.updateProfileBasics.name">
                </spark-text>

                <spark-email :display="'E-Mail Address'"
                             :form="forms.updateProfileBasics"
                             :name="'email'"
                             :input.sync="forms.updateProfileBasics.email">
                </spark-email>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary"
                                @click.prevent="updateProfileBasics" :disabled="forms.updateProfileBasics.busy">
                            <span v-if="forms.updateProfileBasics.busy">
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
</spark-settings-profile-basics-screen>

