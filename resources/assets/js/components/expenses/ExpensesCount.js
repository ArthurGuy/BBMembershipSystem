import React from 'react';

var BackboneMixin = require('../../mixins/Backbone');

var ExpensesCount = React.createClass({
    mixins: [BackboneMixin],

    getBackboneCollections: function () {
        return [this.props.expenses];
    },

    render: function() {

        var expensesCount = this.props.expenses.unapproved().length;

        return (
            <span>
                {expensesCount}
            </span>
        );
    }

});

export default ExpensesCount;