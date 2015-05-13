import React from 'react';

class PaymentModule extends React.Component {

    constructor(props) {
        super(props);

        var csrfToken = document.getElementById('csrfToken').value;

        this.state = {
            amount: 10.00,
            method: 'gocardless',
            stripeToken: null,
            stripeLowValueWarning: false,
            csrfToken,
            requestInProgress: false
        };

        this.handleSubmit       = this.handleSubmit.bind(this);
        this.handleAmountChange = this.handleAmountChange.bind(this);
        this.handleMethodChange = this.handleMethodChange.bind(this);

        //Load in the stripe js file and configure our instance
        var stripeKey = document.getElementById('stripePublicKey').value;
        this.loadConfigureStripe(stripeKey);

    }

    /**
     * On initialisation stripe gets loaded in and setup
     *
     * @param stripeKey
     */
    loadConfigureStripe(stripeKey) {
        //Load the stripe js
        var stripeScript = document.createElement('script');
        stripeScript.src = 'https://checkout.stripe.com/checkout.js';
        document.head.appendChild(stripeScript);

        stripeScript.onload = function() {
            console.log('Stripe JS Loaded');

            if (typeof StripeCheckout === 'undefined') {
                throw Error('Stripe unavailable');
            }

            //Handle the storing of the stripe token and submit the form
            var onToken = function(token) {
                //The set state function may take some time, so dont move on untill we know its ready
                this.setState({stripeToken:token.id}, function() {
                    //The callback will only run when the state is ready
                    this.handleSubmit();
                });
            }.bind(this);

            //Setup the stripe handler
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

            }.bind(this),
            error: function(xhr, status, err) {

                var responseData = JSON.parse(xhr.responseText);

                this.setState({requestInProgress:false});

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
        this.state.stripeHandler.open({
            description: this.props.description,
            amount:      this.state.amount * 100,
            email:       this.props.email
        });
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
                        <option value="gocardless">Direct Debit</option>
                        <option value="stripe">Credit/Debit Card</option>
                        <option value="balance">Balance</option>
                    </select>
                </div>

                <input className="btn btn-primary" type="submit" value="Top Up" disabled={this.state.requestInProgress} onClick={this.handleSubmit} />

                <div className="has-feedback has-error">
                    <span className={this.state.stripeLowValueWarning ? 'help-block' : 'hidden'}>Because of processing fees the payment must be £10 or over when paying by card</span>
                    <span className={this.state.requestInProgress ? 'help-block' : 'hidden'}>Sending...</span>
                </div>
            </div>
        );
    }

}

export default PaymentModule;