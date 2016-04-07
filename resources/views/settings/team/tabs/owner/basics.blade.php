<spark-team-settings-owner-basics-screen inline-template>
    <div class="panel panel-default">
        <div class="panel-heading">The Basics</div>

        <div class="panel-body">
            <spark-error-alert :form="forms.updateTeamOwnerBasics"></spark-error-alert>

            <div class="alert alert-success" v-if="forms.updateTeamOwnerBasics.successful">
                <strong>Great!</strong> Your team was successfully updated.
            </div>

            <form class="form-horizontal" role="form">
                <spark-text :display="'Name'"
                            :form="forms.updateTeamOwnerBasics"
                            :name="'name'"
                            :input.sync="forms.updateTeamOwnerBasics.name">
                </spark-text>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary" @click.prevent="updateTeam"
                                :disabled="forms.updateTeamOwnerBasics.busy">
                            <span v-if="forms.updateTeamOwnerBasics.busy">
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
</spark-team-settings-owner-basics-screen>
