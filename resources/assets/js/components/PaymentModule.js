import React from 'react';

class PaymentModule extends React.Component {

    constructor(props) {
        super(props);
        this.state = {amount: 10.00, method: 'gocardless', stripeToken: null, stripeLowValueWarning: false};

        this.handleSubmit       = this.handleSubmit.bind(this);
        this.handleAmountChange = this.handleAmountChange.bind(this);
        this.handleMethodChange = this.handleMethodChange.bind(this);

        //Load in the stripe js file and configure our instance
        var stripeKey = document.getElementById('stripePublicKey').value;
        this.loadConfigureStripe(stripeKey);
    }

    loadConfigureStripe(stripeKey) {
        var stripeScript = document.createElement('script');
        stripeScript.src = 'https://checkout.stripe.com/checkout.js';
        document.head.appendChild(stripeScript);

        stripeScript.onload = function() {
            console.log('Stripe JS Loaded');

            if (typeof StripeCheckout === 'undefined') {
                throw Error('Stripe unavailable');
            }

            var onToken = function(token) {
                this.setState({stripeToken:token});
                this.handleSubmit();
            }.bind(this);
            var stripeHandler = StripeCheckout.configure({
                key:             stripeKey,
                name:            this.props.name,
                currency:        'GBP',
                allowRememberMe: false,
                token:           onToken
            });
            this.setState({stripeHandler:stripeHandler});
        }.bind(this);
    }

    handleAmountChange(event) {
        var amount = parseFloat(event.target.value);

        //The amount needs to be positive
        if (!amount || amount < 0) {
            amount = 0;
        }

        if (amount > 200) {
            //We should probably do something here as gocardless will most likely fail
        }

        this.setState({amount});

        checkLowValue();
    }

    handleMethodChange(event) {
        var method = event.target.value;

        this.setState({method});

        checkLowValue();
    }

    checkLowValue() {
        this.state.stripeLowValueWarning = ((this.state.method === 'stripe') && (this.state.amount < 10));
    }

    handleSubmit() {

        console.log(this.state.method);
        console.log(this.state.amount);
        console.log(this.state.stripeToken);

        if (this.state.stripeLowValueWarning) {
            return;
        }

        if ((this.state.method === 'stripe') && (this.state.stripeToken === null)) {
            this.displayStripeDialog();
        }
    }

    displayStripeDialog() {
        this.state.stripeHandler.open({
            description: this.props.description,
            amount:      this.state.amount * 100,
            email:       this.props.email
        });
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

                <div className="has-feedback has-error">
                    <span className={this.state.stripeLowValueWarning ? 'help-block' : 'hidden'}>Because of processing fees the payment must be £10 or over when paying by card</span>
                </div>
            </div>
        );
    }

}

export default PaymentModule;