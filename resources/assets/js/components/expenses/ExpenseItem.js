import React from 'react';

var moment = require('moment');

class ExpenseItem extends React.Component {

    capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    render() {

        var status = null;
        if (this.props.expense.get('approved')) {
            status = 'Approved';
        } else if (this.props.expense.get('declined')) {
            status = 'Declined';
        } else {
            status = 'Pending';
        }

        var amount = (this.props.expense.get('amount') / 100).toFixed(2);

        var category = this.capitalizeFirstLetter(this.props.expense.get('category'));

        var date = moment(this.props.expense.get('expense_date'));

        return (
            <tr>
                <td>{ date.format('MMM D, YYYY') }</td>
                <td>{ category }</td>
                <td>{ this.props.expense.get('description') }</td>
                <td>{ amount }</td>
                <td>{ status }</td>
            </tr>
        );
    }

}

export default ExpenseItem;