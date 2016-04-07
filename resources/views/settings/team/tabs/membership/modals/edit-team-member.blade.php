<spark-team-settings-edit-team-member-screen :team-member="editingTeamMember" inline-template>
    <div class="modal fade" id="modal-edit-team-member" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content" v-if="teamMember">
                <div class="modal-header">
                    <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-btn fa-edit"></i>Edit Team Member (@{{ teamMember.name }})
                    </h4>
                </div>

                <div class="modal-body">
                    <spark-error-alert :form="forms.updateTeamMember"></spark-error-alert>

                    <!-- Edit Team Member Form -->
                    <form class="form-horizontal" role="form">
                        <spark-select :display="'Role'"
                                      :form="forms.updateTeamMember"
                                      :name="'role'"
                                      :items="assignableRoles"
                                      :input.sync="forms.updateTeamMember.role">
                        </spark-select>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>

                    <button type="button" class="btn btn-primary" @click.prevent="updateTeamMember"
                            :disabled="forms.updateTeamMember.busy">
                        <span v-if="forms.updateTeamMember.busy">
                            <i class="fa fa-btn fa-spinner fa-spin"></i> Updating
                        </span>

                        <span v-else>
                            <i class="fa fa-btn fa-save"></i> Update
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</spark-team-settings-edit-team-member-screen>
