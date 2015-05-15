import React from 'react';

var StripePayment = require('../services/StripePayment');

class PaymentModule extends React.Component {

    constructor(props) {
        super(props);

        var csrfToken = document.getElementById('csrfToken').value;

        this.state = {
            amount: this.props.amount,
            method: 'gocardless',
            stripeToken: null,
            stripeLowValueWarning: false,
            csrfToken,
            requestInProgress: false,
            desiredPaymentMethods: this.props.methods.split(',')
        };

        this.handleSubmit       = this.handleSubmit.bind(this);
        this.handleAmountChange = this.handleAmountChange.bind(this);
        this.handleMethodChange = this.handleMethodChange.bind(this);

        //Load in the stripe js file and configure our instance
        var stripeKey = document.getElementById('stripePublicKey').value;

        StripePayment.loadConfigureStripe(stripeKey, this.props.name, this.props.email, (token) => {
            this.setState({stripeToken:token.id}, () => { this.handleSubmit(); });  //set state isn't immediate so wait until its done
        });
    }

    handleAmountChange(event) {
        //this doesn't allow decimal places to be entered by hand
        var amount = parseFloat(event.target.value);

        //The amount needs to be at least £5
        if (!amount || amount < 5) {
            amount = 5;
        }

        if (amount > 200) {
            //We should probably do something here as gocardless will most likely fail
        }

        this.setState({amount});

        this.checkLowValue(this.state.method, amount);
    }

    handleMethodChange(event) {
        var method = event.target.value;

        this.setState({method});

        this.checkLowValue(method, this.state.amount);
    }

    /**
     * Ensure that if stripe is being used the amount isnt to low
     * @param method
     * @param amount
     */
    checkLowValue(method, amount) {
        var stripeLowValueWarning = (method === 'stripe') && (amount < 10);
        this.setState({stripeLowValueWarning});
    }

    handleSubmit() {

        var $ = require('jquery');

        if (this.state.stripeLowValueWarning) {
            return;
        }

        //The stripe process starts with the dialog box to collect card details
        if ((this.state.method === 'stripe') && (this.state.stripeToken === null)) {
            this.displayStripeDialog();
            return;
        }

        this.setState({requestInProgress:true});


        $.ajax({
            url: this.getTargetUrl(this.props.userId, this.state.method),
            dataType: 'json',
            contentType: "application/json",
            type: 'POST',
            data: this.prepareRequestData(),
            success: function(responseData) {

                //Reset the state
                this.setState({requestInProgress:false, amount:10, stripeToken:null});

                BB.SnackBar.displayMessage('Your payment has been processed');

                //run the passed in success function
                this.props.onSuccess();

            }.bind(this),
            error: function(xhr, status, err) {

                var responseData = JSON.parse(xhr.responseText);

                this.setState({requestInProgress:false});

                if (xhr.status == 303) {
                    document.location.href = responseData.url;
                }

                BB.SnackBar.displayMessage(responseData.error);

            }.bind(this)
        });
    }

    /**
     * Generate data data for the ajax request
     * @returns string
     */
    prepareRequestData() {
        return JSON.stringify({
            amount: (this.state.amount * 100) + '',
            reason: this.props.reason,
            stripeToken: this.state.stripeToken,
            '_token': this.state.csrfToken
        });
    }

    /**
     * Where will the request be sent?
     *
     * @param userId
     * @param method
     * @returns {string}
     */
    getTargetUrl(userId, method) {
        return '/account/'+userId+'/payment/'+method;
    }

    displayStripeDialog() {
        StripePayment.collectCardDetails(this.state.amount * 100, this.props.description);
    }

    paymentMethodHidden(method) {
        return (this.state.desiredPaymentMethods.indexOf(method) === -1)
    }

    render() {

        return (
            <div className="form-inline">
                <div className="form-group">
                    <div className="input-group">
                        <div className="input-group-addon">£</div>
                        <input className="form-control" step="0.1" required="required" type="number" value={this.state.amount} onChange={this.handleAmountChange} />
                    </div>
                </div>
                <div className="form-group">
                    <select className="form-control" value={this.state.method} onChange={this.handleMethodChange}>
                        <option value="gocardless" hidden={this.paymentMethodHidden('gocardless')}>Direct Debit</option>
                        <option value="stripe" hidden={this.paymentMethodHidden('stripe')}>Credit/Debit Card</option>
                        <option value="balance" hidden={this.paymentMethodHidden('balance')}>Balance</option>
                    </select>
                </div>

                <input className="btn btn-primary" type="submit" value={this.props.buttonLabel} disabled={this.state.requestInProgress} onClick={x => this.handleSubmit(x)} />

                <div className="has-feedback has-error">
                    <span className={this.state.stripeLowValueWarning ? 'help-block' : 'hidden'}>Because of processing fees the payment must be £10 or over when paying by card</span>
                    <span className={this.state.requestInProgress ? 'help-block' : 'hidden'}>Processing...</span>
                </div>
            </div>
        );
    }

}

PaymentModule.defaultProps = {
    name: 'Build Brighton',
    buttonLabel: 'Pay Now',
    onSuccess: function() {},
    methods: 'gocardless,stripe,balance',
    amount: 10
};

export default PaymentModule;