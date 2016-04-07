/*
 * Load the Spark components.
 */
require('./core/components');

/**
 * Export the Spark application.
 */
module.exports = {
    el: '#spark-app',

    /*
     * Bootstrap the application. Load the initial data.
     */
    ready: function () {
        $(function () {
            $('.spark-first-field').filter(':visible:first').focus();
        });

        if (Spark.userId) {
            this.getUser();
        }

        if (Spark.currentTeamId) {
            this.getTeams();
            this.getCurrentTeam();
        }

        this.whenReady();
    },


    events: {
        /**
         * Handle requests to update the current user from a child component.
         */
        updateUser: function () {
            this.getUser();

            return true;
        },


        /**
         * Handle requests to update the teams from a child component.
         */
        updateTeams: function () {
            this.getTeams();

            return true;
        }
    },


    methods: {
        /**
         * This method would be overridden by developer.
         */
        whenReady: function () {
            //
        },


        /**
         * Retrieve the user from the API and broadcast it to children.
         */
        getUser: function () {
            this.$http.get('/spark/api/users/me')
                .success(function (user) {
                    this.$broadcast('userRetrieved', user);
                });
        },

        /*
         * Get all of the user's current teams from the API.
         */
        getTeams: function () {
            this.$http.get('/spark/api/teams')
                .success(function (teams) {
                    this.$broadcast('teamsRetrieved', teams);
                });
        },


        /*
         * Get the user's current team from the API.
         */
        getCurrentTeam: function () {
            this.$http.get('/spark/api/teams/' + Spark.currentTeamId)
                .success(function (team) {
                    this.$broadcast('currentTeamRetrieved', team);
                });
        }
    }
};
