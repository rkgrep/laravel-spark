Vue.component('spark-settings-teams-screen', {
    /*
     * Bootstrap the component. Load the initial data.
     */
    ready: function () {
        this.getInvitations();
    },


    /*
     * Initial state of the component's data.
     */
    data: function () {
        return {
            user: null,
            teams: [],
            invitations: [],

            teamToDelete: null,

            forms: {
                createTeam: new SparkForm({
                    name: ''
                }),

                deleteTeam: new SparkForm({})
            }
        };
    },


    events: {
        /*
         * Handle the "userRetrieved" event.
         */
        userRetrieved: function (user) {
            this.user = user;

            return true;
        },


        /*
         * Handle the "teamsRetrieved" event.
         */
        teamsRetrieved: function (teams) {
            this.teams = teams;

            return true;
        }
    },


    methods: {
        /*
         * Get all of the user's pending invitations from the API.
         */
        getInvitations: function () {
            this.$http.get('/spark/api/teams/invitations')
                .success(function (invitations) {
                    this.invitations = invitations;
                });
        },


        /**
         * Create a new team.
         */
        createTeam: function () {
            var self = this;

            Spark.post('/settings/teams', this.forms.createTeam)
                .then(function () {
                    self.forms.createTeam.name = '';

                    self.$dispatch('updateUser');
                    self.$dispatch('updateTeams');
                });
        },


        /*
         * Leave the team.
         */
        leaveTeam: function (team) {
            this.teams = _.reject(this.teams, function (t) {
                return t.id == team.id;
            });

            this.$http.delete('/settings/teams/' + team.id + '/membership')
                .success(function () {
                    this.$dispatch('updateUser');
                    this.$dispatch('updateTeams');
                });
        },


        /*
         * Confirm that the user really wants to delete the team.
         */
        confirmTeamDeletion: function (team) {
            this.teamToDelete = team;

            $('#modal-delete-team').modal('show');
        },


        /*
         * Delete the given team.
         */
        deleteTeam: function () {
            var self = this;

            Spark.delete('/settings/teams/' + this.teamToDelete.id, this.forms.deleteTeam)
                .then(function () {
                    $('#modal-delete-team').modal('hide');

                    self.$dispatch('updateUser');
                    self.$dispatch('updateTeams');
                });
        },


        /*
         * Accept a pending invitation.
         */
        acceptInvite: function (invite) {
            this.removeInvitationFromList(invite);

            this.$http.post('/settings/teams/invitations/' + invite.id + '/accept')
                .success(function () {
                    this.$dispatch('updateUser');
                    this.$dispatch('updateTeams');
                });
        },


        /*
         * Reject a pending invitation.
         */
        rejectInvite: function (invite) {
            this.removeInvitationFromList(invite);

            this.$http.delete('settings/teams/invitations/' + invite.id);
        },


        /*
         * Remove an invitation from the list of invitations.
         */
        removeInvitationFromList: function (invite) {
            this.invitations = _.reject(this.invitations, function (i) {
                return i.id == invite.id;
            });
        },


        /*
         * Determine if the current user owns the given team.
         */
        userOwns: function (team) {
            if (!this.user) {
                return false;
            }

            return this.user.id === team.owner_id;
        }
    }
});
