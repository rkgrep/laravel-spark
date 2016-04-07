module.exports = {
    /**
     * A few helper methods for making HTTP requests and doing common form actions.
     */
    post: function (uri, form) {
        return Spark.sendForm('post', uri, form);
    },


    put: function (uri, form) {
        return Spark.sendForm('put', uri, form);
    },


    delete: function (uri, form) {
        return Spark.sendForm('delete', uri, form);
    },


    /**
     * Send the form to the back-end server. Perform common form tasks.
     *
     * This function will automatically clear old errors, update "busy" status, etc.
     */
    sendForm: function (method, uri, form) {
        return new Promise(function (resolve, reject) {
            form.startProcessing();

            Vue.http[method](uri, form)
                .success(function (response) {
                    form.finishProcessing();

                    resolve(response);
                })
                .error(function (errors) {
                    form.errors.set(errors);
                    form.busy = false;

                    reject(errors);
                });
        });
    }
};
