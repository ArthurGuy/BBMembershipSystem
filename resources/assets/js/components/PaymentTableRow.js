import React from 'react';

class PaymentTableRow extends React.Component {

    render() {

        return (
            <tr>
                <td>{ this.props.payment.date }</td>
                <td>{ this.props.payment.user.name }</td>
                <td>{ this.props.payment.reason }</td>
                <td>{ this.props.payment.method }</td>
                <td>{ this.props.payment.amount }</td>
                <td>{ this.props.payment.reference }</td>
                <td>{ this.props.payment.status }</td>
                <td></td>
            </tr>
        );
    }

}

export default PaymentTableRow;