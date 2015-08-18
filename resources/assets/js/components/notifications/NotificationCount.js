import React from 'react';

var BackboneMixin = require('../../mixins/Backbone');

var NotificationCount = React.createClass({
    mixins: [BackboneMixin],

    getBackboneCollections: function () {
        return [this.props.notifications];
    },

    render: function() {

        var notificationCount = this.props.notifications.unread().length;

        return (
            <span>
                {notificationCount}
            </span>
        );
    }

});

export default NotificationCount;