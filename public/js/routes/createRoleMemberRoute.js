App.createRoleMemberRoute = Ember.Route.extend({
    model: function(){
        return this.modelFor('user');
    }
});