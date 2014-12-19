App.Router.map(function() {
    /*
    this.resource('roles', function(){
        this.resource('role', { path:':role_id' }, function(){
            //this.route('show');
            this.route('edit');
        });
        this.route('create');
        //this.route("view", {path: "/:role_id" });
    });
    */

    this.resource('roles');
    this.resource('role', { path: 'roles/:role_id' });

    //Standard route
    //this.route('roles');

    this.resource('users');
});

//We want to use normal urls not hash fragments
App.Router.reopen({
    location: 'history'
});