class StripePayment {

    constructor() {

        this.stripeKey       = null;
        this.stripeHandler   = null;
        this.name            = null;
        this.email           = null;
        this.currency        = 'GBP';
        this.isLoaded        = false;
        this.onTokenCallback = null;

    }


    loadConfigureStripe(stripeKey, name, email, onTokenCallback) {

        //We only want to load this library once
        if (this.isLoaded) {
            return;
        }

        this.stripeKey       = stripeKey;
        this.name            = name;
        this.email           = email;
        this.onTokenCallback = onTokenCallback;

        //Create a script tag for stripes checkout.js library
        var stripeScript = document.createElement('script');
        stripeScript.src = 'https://checkout.stripe.com/checkout.js';


        //When its loaded in set everything up
        stripeScript.onload = () => {
            console.log('Stripe JS Loaded');

            if (typeof StripeCheckout === 'undefined') {
                throw Error('Stripe unavailable');
            }

            //Setup the stripe handler
            this.createHandler();
        };

        //Add the script tag to the head to kick this process off
        document.head.appendChild(stripeScript);

        //We are done so lets not do it again
        this.isLoaded = true;
    }

    /**
     * Create the stripe handler which will control the modal generation
     */
    createHandler() {
        this.stripeHandler = StripeCheckout.configure({
            key:             this.stripeKey,
            name:            this.name,
            currency:        this.currency,
            allowRememberMe: false, // this generates a confusing ui
            email:           this.email,
            token:           this.onTokenGeneration.bind(this)
        });
    }

    /**
     * Load the collection dialog box
     *
     * @param amount        amount in pence
     * @param description   description for the dialog box
     */
    collectCardDetails(amount, description=null) {
        this.stripeHandler.open({
            description: description,
            amount: amount
        });
    }

    /**
     * When a token is generated, what do we do?
     *
     * @param token
     */
    onTokenGeneration(token) {
        if (this.onTokenCallback) {
            this.onTokenCallback(token);
        }
    }

}
//Returns a singleton
export default new StripePayment();