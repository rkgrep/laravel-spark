Vue.component('spark-settings-profile-basics-screen', $.extend(true, {
    /*
     * Bootstrap the component. Load the initial data.
     */
    ready: function () {
        //
    },


    /*
     * Initial state of the component's data.
     */
    data: function () {
        return {
            user: null,

            forms: {
                updateProfileBasics: $.extend(true, new SparkForm({
                    name: '',
                    email: '',
                }), Spark.forms.updateProfileBasics)
            }
        };
    },


    events: {
        /*
         * Handle the "userRetrieved" event.
         */
        userRetrieved: function (user) {
            this.user = user;

            this.updateProfileBasicsFormForNewUser(user);

            return true;
        }
    },


    methods: {
        /**
         * Update the user profile form with new user information.
         */
        updateProfileBasicsFormForNewUser: function (user) {
            this.forms.updateProfileBasics.name = user.name;
            this.forms.updateProfileBasics.email = user.email;
        },


        /**
         * Update the user's profile information.
         */
        updateProfileBasics: function () {
            var self = this;

            Spark.put('/settings/user', this.forms.updateProfileBasics)
                .then(function () {
                    self.$dispatch('updateUser');
                });
        }
    }
}, Spark.components.profileBasics));
