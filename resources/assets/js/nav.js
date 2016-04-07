Vue.component('spark-nav-bar-dropdown', $.extend(true, {
    /*
     * Initial state of the component's data.
     */
    data: function () {
        return {
            user: null,
            teams: []
        };
    },


    events: {
        /**
         * Handle the "userRetrieved" event.
         */
        userRetrieved: function (user) {
            this.user = user;

            return true;
        },


        /**
         * Handle the "teamsRetrieved" event.
         */
        teamsRetrieved: function (teams) {
            this.teams = teams;

            return true;
        }
    }
}, Spark.components.navDropdown));
