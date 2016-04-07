/**
 * Initialize the Spark form extension points.
 */
Spark.forms = {
    registration: {},
    updateProfileBasics: {},
    updateTeamOwnerBasics: {}
};

/**
 * Load the SparkForm helper class.
 */
require('./instance');

/**
 * Define the form error collection class.
 */
require('./errors');

/**
 * Add additional form helpers to the Spark object.
 */
$.extend(Spark, require('./http'));

/**
 * Define the Spark form input components.
 */
require('./components');
