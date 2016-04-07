/*
 * Common Error Display Component.
 */
Vue.component('spark-errors', {
    props: ['form'],

    template: "<div><div class='alert alert-danger' v-if='form.errors && form.errors.hasErrors()'>\
                <strong>Whoops!</strong> There were some problems with your input.<br><br>\
                <ul>\
                    <li v-for='error in form.errors.flatten()'>\
                        {{ error }}\
                    </li>\
                </ul>\
            </div></div>"
});


Vue.component('spark-error-alert', {
    props: ['form'],

    template: "<div><div class='alert alert-danger' v-if='form.errors && form.errors.hasErrors()'>\
                <i class='fa fa-btn fa-warning'></i><strong>Whoops!</strong> There were some problems with your input.\
            </div></div>"
});
