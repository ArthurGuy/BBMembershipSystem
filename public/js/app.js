
window.App = Ember.Application.create({
    rootElement: '#bodyWrap .container-fluid',
    LOG_TRANSITIONS: true
});


//This is the main app controller and provides assets to the main template
App.ApplicationController = Ember.Controller.extend({
    appName: 'BBMS'
});

/*
var forEach = Ember.EnumerableUtils.forEach;

App.ApplicationAdapter = DS.RESTAdapter.extend({
    ajaxError: function(jqXHR) {

        if (jqXHR && jqXHR.status === 422) {
            var jsonErrors = Ember.$.parseJSON(jqXHR.responseText)["errors"],
                errors = {};

            forEach(Ember.keys(jsonErrors), function(key) {
                errors[Ember.String.camelize(key)] = jsonErrors[key];
            });

            return new DS.InvalidError(errors);
        } else {
            return error;
        }
    }
});
*/

//Specify a different rest adapter - this oen supports errors
App.ApplicationAdapter = DS.ActiveModelAdapter;

//Connect the rest data adapter with the main store method
App.ApplicationStore = DS.Store.extend({});






/*

 App.RoleController = Ember.Controller.extend({
    appName: 'BBMS Roles'
});

 App.UserController = Ember.Controller.extend({
    appName: 'BBMS Users'
});

*/