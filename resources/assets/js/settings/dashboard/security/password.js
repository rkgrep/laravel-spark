Vue.component('spark-settings-security-password-screen', {
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
                updatePassword: new SparkForm({
                    current_password: '',
                    password: '',
                    password_confirmation: ''
                })
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
        }
    },


    methods: {
        /**
         * Update the user's password.
         */
        updatePassword: function () {
            var self = this;

            Spark.put('/settings/user/password', this.forms.updatePassword)
                .then(function () {
                    self.forms.updatePassword.current_password = '';
                    self.forms.updatePassword.password = '';
                    self.forms.updatePassword.password_confirmation = '';
                });
        }
    }
});
