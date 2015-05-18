import React from 'react';

class ExpenseItem extends React.Component {

    render() {
        return (
            <tr>
                <td>{ this.props.expense.get('category') }</td>
                <td>{ this.props.expense.get('description') }</td>
                <td>{ this.props.expense.get('amount') }</td>
            </tr>
        );
    }

}

export default ExpenseItem;