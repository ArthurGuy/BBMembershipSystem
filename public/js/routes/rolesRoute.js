
App.RolesRoute = Ember.Route.extend({
    model: function(){
        return this.store.find('role');
    }
});

App.RoleRoute = Ember.Route.extend({
    model: function(attr){
        return this.store.find('role', attr.role_id);
    }
});