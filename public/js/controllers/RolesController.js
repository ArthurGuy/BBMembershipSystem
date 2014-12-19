App.RolesController = Ember.ArrayController.extend({
    actions: {
        createRole: function () {
            var roleName, role;

            // Get the role title
            roleName = this.get('newRoleName').trim();
            if (!roleName) {
                return;
            }

            // Create the new Role model
            role = this.store.createRecord('role', {
                name: roleName
            });

            //Save it to the server

            role.save().catch(function(){
                console.log(role.get('errors'));
                console.log("hello");
            });

            // Clear the form
            this.set('newRoleName', '');
        },

        deleteRole: function (role) {
            console.log(role);
            role.destroyRecord();
            /*
            role.save().catch(function(){
                console.log(role.get('errors').messages);
                console.log("hello");
                role.rollback();
            });
            */
        }
    }
});