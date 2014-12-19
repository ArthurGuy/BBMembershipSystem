App.Role = DS.Model.extend({
    name: DS.attr('string'),

    users: DS.attr(),

    //users: DS.hasMany('user')

    destroyRecord: function () {

        var record = this;
        console.log("destroyingRecord");
        this.deleteRecord();
        this.save().then(
            function (){
                //Success
                console.log("success");
                return true;
            },
            function () {
                //Failure
                console.log("failure");
                record.rollback();
                return false;
            }
        );
    }
});



//This router gets called in addition, after the main router
/*
 App.RoleRoute = Ember.Route.extend({

    setupController: function(controller) {
        //App.Role.find(1);
        //this.store.find('1');
        //controller.set('content', App.Role.find('role'));
        //controller.set('content', this.store.find('role'));
        //this.set('controller.content', role);
    },

    //the special model method attaches the correct model
    model: function() {
        console.log("run");
        // the model is an Array of all of the posts
        //return this.store.find('role');
        //return App.Role.find();
    }
});
*/