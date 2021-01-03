import React from 'react';

var StripePayment = require('../services/StripePayment');
var Select = require('./form/Select');
var Loader = require('halogen/PulseLoader');

class PaymentModule extends React.Component {

    constructor(props) {
        super(props);

        this.state = {
            amount: this.props.amount,
            method: 'gocardless',
            stripeToken: null,
            stripeLowValueWarning: false,
            csrfToken: this.props.csrfToken,
            requestInProgress: false,
            desiredPaymentMethods: this.props.methods.split(',')
        };

        this.handleSubmit       = this.handleSubmit.bind(this);
        this.handleAmountChange = this.handleAmountChange.bind(this);
        this.handleMethodChange = this.handleMethodChange.bind(this);


        //Load in the stripe js file and configure our instance
        StripePayment.loadConfigureStripe(this.props.stripeKey, this.props.name, this.props.email, (token) => {
            this.setState({stripeToken:token.id}, () => { this.handleSubmit(); });  //set state isn't immediate so wait until its done
        });

        this.availableMethods = [
            {key:'gocardless', value:'Direct Debit'},
            {key:'balance', value:'Balance'},
            {key:'stripe', value:'Credit/Debit Card'}
        ];

        this.availablePaymentMethods = this.getPaymentMethodArray();
    }

    componentDidMount() {
        //Set the default payment method to be the first item in the array, the one the user sees
        this.setState({method:this.availablePaymentMethods[0].key});
    }

    handleAmountChange(event) {
        //this doesn't allow decimal places to be entered by hand
        var amount = parseFloat(event.target.value);

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

        if (this.state.amount == 0) {
            return;
        }

        //The stripe process starts with the dialog box to collect card details
        if ((this.state.method === 'stripe') && (this.state.stripeToken === null)) {
            StripePayment.collectCardDetails(this.state.amount * 100, this.props.description);
            return;
        }

        this.setState({requestInProgress:true});

        // loading indicator
        // https://madscript.com/halogen/


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
            amount:      (this.state.amount * 100) + '',
            reason:      this.props.reason,
            ref:         this.props.reference,
            '_token':    this.state.csrfToken,
            stripeToken: this.state.stripeToken
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
        return '/account/' + userId + '/payment/' + method;
    }

    /**
     * Get an array of payment methods for the dropdown, this is controlled by the data being passed in
     *
     * @returns {Array}
     */
    getPaymentMethodArray() {
        var methods = [];
        for (var i in this.availableMethods) {
            if (this.state.desiredPaymentMethods.indexOf(this.availableMethods[i]['key']) !== -1) {
                methods.push(this.availableMethods[i]);
            }
        }
        return methods;
    }

    render() {

        var amountField = null;

        if (!this.props.amount) {
            amountField =
                <div className="form-group">
                    <div className="input-group">
                        <div className="input-group-addon">£</div>
                        <input style={{width: 70}} className="form-control" step="0.1" required="required" type="number" value={this.state.amount} onChange={this.handleAmountChange} />
                    </div>
                </div>;
        }

        return (
            <div className="form-inline multi-payment-form">

                { amountField }

                <Select value={this.state.method} onChange={this.handleMethodChange} options={this.availablePaymentMethods} style={{width:150}} />

                <button className="btn btn-primary" disabled={this.state.requestInProgress} onClick={x => this.handleSubmit(x)}>{this.props.buttonLabel}</button>

                <div className={this.state.requestInProgress ? 'has-feedback has-success' : 'hidden'}>
                    <span className="help-block">Please wait, processing...</span>
                </div>

                <div className="has-feedback has-error">
                    <span className={this.state.stripeLowValueWarning ? 'help-block' : 'hidden'}>Because of processing fees the payment must be £10 or over when paying by card</span>
                </div>
            </div>
        );
    }

}

PaymentModule.defaultProps = {
    name: 'Build Brighton',
    email: null,
    userId: null,
    amount: 0,
    buttonLabel: 'Pay Now',
    onSuccess: function() {},
    methods: 'gocardless,stripe,balance',
    reference: null,
    reason: null,
    description: null,
    stripeKey: ''
};

export default PaymentModule;