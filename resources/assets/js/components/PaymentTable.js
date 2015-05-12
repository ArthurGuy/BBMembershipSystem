import React from 'react';

var PaymentTableRow = require('./PaymentTableRow');

class PaymentTable extends React.Component {

    render() {
        var displayRow = (payment) => <PaymentTableRow payment={payment} key={payment.id} />;

        return (
            <div>
                <h3>New Payment List</h3>
                <table className="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Member</th>
                            <th>Reason</th>
                            <th>Method</th>
                            <th>Amount</th>
                            <th>Reference</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    { this.props.payments.map(displayRow) }
                    </tbody>
                </table>
            </div>
        );
    }

}

export default PaymentTable;