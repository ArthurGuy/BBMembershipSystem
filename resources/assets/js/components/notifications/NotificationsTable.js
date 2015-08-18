import React from 'react';

var NotificationTableRow = require('./NotificationTableRow');
var BackboneMixin = require('../../mixins/Backbone');

var NotificationsTable = React.createClass({
    mixins: [BackboneMixin],

    getBackboneCollections: function () {
        return [this.props.notifications];
    },

    componentDidMount: function() {
        //Notifications are loaded in the main app file and the connection is passed in
        //this.props.notifications.fetch();
    },

    render: function() {

        var notifications = this.props.notifications;

        var notificationItems = notifications.map(function (notification) {
            return (<NotificationTableRow key={notification.get('id')} notification={notification} />);
        }, this);

        return (
            <div>
                <table className="table">
                    <thead>
                        <tr>
                            <th>Message</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Unread</th>
                        </tr>
                    </thead>
                    <tbody>
                    {notificationItems}
                    </tbody>
                </table>
            </div>
        );
    }

});

export default NotificationsTable;