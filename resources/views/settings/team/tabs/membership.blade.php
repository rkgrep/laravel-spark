<!-- Main Content -->
<spark-team-settings-membership-screen inline-template>
    <div id="spark-team-settings-membership-screen">
        <div v-if="everythingIsLoaded">
            <!-- Invite New Members -->
            <div class="panel panel-default" v-if="userOwns(team)">
                <div class="panel-heading">Send Invitation</div>

                <div class="panel-body">
                    <spark-error-alert :form="forms.sendInvite"></spark-error-alert>

                    <form method="POST" class="form-horizontal" role="form">
                        <div class="alert alert-success" v-if="forms.sendInvite.successful">
                            <strong>Done!</strong> The invitation has been sent.
                        </div>

                        <spark-email :display="'E-Mail Address'"
                                     :form="forms.sendInvite"
                                     :name="'email'"
                                     :input.sync="forms.sendInvite.email">
                        </spark-email>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary" @click.prevent="sendInvite"
                                        :disabled="forms.sendInvite.busy">
                                    <span v-if="forms.sendInvite.busy">
                                        <i class="fa fa-btn fa-spinner fa-spin"></i> Sending
                                    </span>

                                    <span v-else>
                                        <i class="fa fa-btn fa-envelope"></i> Send
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Pending Invitations -->
            <div class="panel panel-default" v-if="userOwns(team) && team.invitations.length > 0">
                <div class="panel-heading">Pending Invitations</div>

                <div class="panel-body">
                    <table class="table table-responsive">
                        <thead>
                        <tr>
                            <th>E-Mail Address</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="invite in team.invitations">
                            <td class="spark-table-pad">
                                @{{ invite.email }}
                            </td>

                            <td>
                                <button class="btn btn-danger" @click.prevent="cancelInvite(invite)">
                                    <i class="fa fa-btn fa-times"></i>Cancel
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Team Member List -->
            <div class="panel panel-default" v-if="teamMembersExceptMe.length > 0">
                <div class="panel-heading">Team Members</div>

                <div class="panel-body">
                    <table class="table table-responsive">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Role</th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="teamMember in teamMembersExceptMe">
                            <td class="spark-table-pad">
                                @{{ teamMember.name }}
                            </td>

                            <td class="spark-table-pad">
                                @{{ teamMember.pivot.role | role }}
                            </td>

                            <td>
                                <button class="btn btn-primary" v-if="userOwns(team)"
                                        @click.prevent="editTeamMember(teamMember)">
                                    <i class="fa fa-btn fa-edit"></i>Edit
                                </button>
                            </td>

                            <td>
                                <button class="btn btn-danger" v-if="userOwns(team)"
                                        @click.prevent="removeTeamMember(teamMember)">
                                    <i class="fa fa-btn fa-times"></i>Remove
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Leave Team -->
            <div class="panel panel-default" v-if=" ! userOwns(team)">
                <div class="panel-heading">Leave Team</div>

                <div class="panel-body">
                    <button class="btn btn-warning" @click.prevent="leaveTeam" :disabled="leavingTeam">
                        <span v-if="leavingTeam">
                            <i class="fa fa-btn fa-spinner fa-spin"></i>Leaving
                        </span>

                        <span v-else>
                            <i class="fa fa-btn fa-sign-out"></i>Leave Team
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Team Member Modal -->
    @include('settings.team.tabs.membership.modals.edit-team-member')

</spark-team-settings-membership-screen>
