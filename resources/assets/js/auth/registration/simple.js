Vue.component('spark-simple-registration-screen', {
    /*
     * Bootstrap the component. Load the initial data.
     */
    ready: function () {
        $(function () {
            $('.spark-first-field').filter(':visible:first').focus();
        });

        var queryString = URI(document.URL).query(true);

        /**
         * Load the query string items such as the invitation.
         */
        if (queryString.invitation) {
            this.getInvitation(queryString.invitation);
        }
    },

    /*
     * Initial state of the component's data.
     */
    data: function () {
        return {
            invitation: null,
            failedToLoadInvitation: false,

            forms: {
                registration: $.extend(true, new SparkForm({
                    team_name: '',
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    plan: '',
                    terms: false,
                    invitation: null
                }), Spark.forms.registration)
            }
        };
    },


    methods: {
        /**
         * Get the specified invitation.
         */
        getInvitation: function (invitation) {
            this.$http.get('/spark/api/teams/invitation/' + invitation)
                .success(function (invitation) {
                    this.invitation = invitation;
                    this.forms.registration.invitation = invitation.token;

                    setTimeout(function () {
                        $(function () {
                            $('.spark-first-field').filter(':visible:first').focus();
                        });
                    }, 250);
                })
                .error(function (errors) {
                    this.failedToLoadInvitation = true;

                    console.error('Unable to load invitation for given code.');
                });
        },


        /*
         * Initialize the registration process.
         */
        register: function () {
            Spark.post('/register', this.forms.registration)
                .then(function (response) {
                    window.location = response.path;
                });
        }
    }
});
