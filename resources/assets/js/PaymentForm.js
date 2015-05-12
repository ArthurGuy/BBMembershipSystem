class PaymentForm {

    constructor() {


        var $ = require('jquery');

        console.log("Payment Form Loading");


        var stripeAvailable = false;


        if (typeof StripeCheckout !== 'undefined') {
            var stripeHandler = StripeCheckout.configure({
                key: stripePublicKey,
                name: 'Build Brighton',
                currency: 'GBP',
                email: memberEmail,
                allowRememberMe: false,
                token: function (token) {
                    //Fill in the token and submit the form again
                    $('.js-stripeToken').val(token.id);
                    $('.js-multiPaymentForm').submit();
                }
            });
            stripeAvailable = true;
        }


        var multiPaymentFormChecked = false;
        $('.js-multiPaymentForm').on('submit', function(event) {

            //Clear the error messages
            $(this).find('.help-block').text('');

            var source = $('.js-multiPaymentForm [name=source] option:selected').val();

            //Update the form target
            $(this).attr('action', paymentRoutes[source]);

            //Validation rules
            if (source == 'stripe') {
                if (!stripeAvailable) {
                    $(this).find('.help-block').text("Sorry, card processing is currently unavailable");
                    event.preventDefault();
                } else {
                    if ($('.js-stripeToken').val() == '') {
                        //Stripe is handled separately so stop this form post
                        event.preventDefault();
                        if (($(this).find('.js-amount').val() * 1) < 10) {
                            $(this).find('.help-block').text("Because of processing fees the payment must be £10 or over when paying by card");
                        } else {
                            var topUpAmount = ($(this).find('.js-amount').val() * 100);
                            var description = ($(this).find('.js-paymentDescription').val());

                            stripeHandler.open({
                                description: description,
                                amount: topUpAmount
                            });
                        }
                    } else {
                        //Card collection is complete send it to the server
                    }
                }
            } else {
                //$(this).submit();
                //return true;
            }
        });




        console.log("Payment Form Loaded");
    }

}

export default PaymentForm;