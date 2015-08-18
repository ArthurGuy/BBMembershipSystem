
var Backbone = require('backbone');

var Notification = Backbone.Model.extend({

    urlRoot: '/notifications',

    defaults: {
        user_id: null,
        message: null,
        type: null,
        date: null,
        unread: true
    },

    seen: function () {
        this.save({
            unread: false
        });
    }
});

export default Notification;