import React from 'react';

class PaymentModule extends React.Component {

    constructor(props) {
        super(props);
        this.state = {amount: 10.00, method:'gocardless'};

        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleAmountChange = this.handleAmountChange.bind(this);
        this.handleMethodChange = this.handleMethodChange.bind(this);
    }

    handleAmountChange(event) {
        event.preventDefault();

        this.setState({amount: event.target.value});
    }

    handleMethodChange(event) {
        this.setState({method: event.target.value});
    }

    handleSubmit(event) {
        event.preventDefault();

        console.log(this.state.method);
        console.log(this.state.amount);

        if (this.state.method === 'stripe') {

        }
    }

    render() {

        return (
            <div className="form-inline">
                <strong>New Payment Form</strong><br />
                <div className="form-group">
                    <div className="input-group">
                        <div className="input-group-addon">£</div>
                        <input className="form-control" step="0.01" required="required" type="number" value={this.state.amount} onChange={this.handleAmountChange} />
                    </div>
                </div>
                <div className="form-group">
                    <select className="form-control" value={this.state.method} onChange={this.handleMethodChange}>
                        <option value="gocardless">Direct Debit</option>
                        <option value="stripe">Credit/Debit Card</option>
                    </select>
                </div>

                <input className="btn btn-primary" type="submit" value="Top Up" onClick={this.handleSubmit} />
            </div>
        );
    }

}

export default PaymentModule;