Vue.component('spark-team-settings-screen', {
    props: ['teamId'],

    /*
     * Bootstrap the component. Load the initial data.
     */
    ready: function () {
        this.getTeam();
        this.getRoles();
    },


    /*
     * Initial state of the component's data.
     */
    data: function () {
        return {team: null};
    },


    events: {
        /*
         * Handle the "updateTeam" event. Re-retrieve the team.
         */
        updateTeam: function () {
            this.getTeam();

            return true;
        }
    },


    methods: {
        /*
         * Get the team from the API.
         */
        getTeam: function () {
            this.$http.get('/spark/api/teams/' + this.teamId)
                .success(function (team) {
                    this.team = team;

                    this.$broadcast('teamRetrieved', team);
                });
        },


        /**
         * Get all of the roles that may be assigned to users.
         */
        getRoles: function () {
            this.$http.get('/spark/api/teams/roles')
                .success(function (roles) {
                    this.roles = roles;

                    this.$broadcast('rolesRetrieved', roles);
                });
        }
    }
});
