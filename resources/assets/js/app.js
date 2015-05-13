var React = require('react');


var SiteInteraction = require('./SiteInteraction');
new SiteInteraction();

var PaymentForm = require('./PaymentForm');
new PaymentForm();

var AdminForms = require('./AdminForms');
new AdminForms();

var Snackbar = require('./Snackbar');
new Snackbar();

var FeedbackWidget = require('./FeedbackWidget');
new FeedbackWidget();


global.jQuery = require('jquery');
require('bootstrap');


if (jQuery('body').hasClass('payment-page')) {
    var FilterablePaymentTable = require('./components/FilterablePaymentTable');
    React.render(<FilterablePaymentTable />, document.getElementById('react-test'));
}

if (document.getElementById('paymentModuleTest')) {
    var PaymentModule = require('./components/PaymentModule');
    React.render(<PaymentModule name="Build Brighton" description="Sample Description" email={memberEmail} />, document.getElementById('paymentModuleTest'));
}


