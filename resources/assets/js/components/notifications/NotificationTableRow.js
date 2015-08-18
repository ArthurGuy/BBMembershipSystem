import React from 'react';

var moment = require('moment');

var Tick = require('./../elements/Tick');

class NotificationTableRow extends React.Component {

    constructor(props) {
        super(props);

        this.markAsRead = this.markAsRead.bind(this);
    }

    markAsRead() {
        this.props.notification.save({unread:false}, {wait: true});
    }

    render() {

        var date = moment(this.props.notification.get('created_at'));

        var unread = '';
        var rowClass = '';
        if (this.props.notification.get('unread')) {
            unread = '<span class="glyphicon glyphicon-ok" title="Unread"></span>';
            rowClass = 'success';
        }

        var rowStyle = {
            cursor: 'pointer'
        };

        return (
            <tr className={rowClass} onClick={this.markAsRead} style={rowStyle}>
                <td>{ this.props.notification.get('message') }</td>
                <td>{ this.props.notification.get('type') }</td>
                <td>{ date.format('MMM D, YYYY') }</td>
                <td><Tick ticked={this.props.notification.get('unread')} title="Unread"></Tick></td>
            </tr>
        );
    }

}

export default NotificationTableRow;