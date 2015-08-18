var Backbone = require('backbone');

var Notifications = Backbone.Collection.extend({
    // Reference to this collection's model.
    model: require('../models/Notification'),

    url: '/notifications',

    unread: function () {
        return this.where({unread: true});
    }
});

export default Notifications;