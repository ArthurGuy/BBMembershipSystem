var React = require('react');


var SiteInteraction = require('./SiteInteraction');
new SiteInteraction();

var PaymentForm = require('./PaymentForm');
new PaymentForm();

var AdminForms = require('./AdminForms');
new AdminForms();

var Snackbar = require('./Snackbar');
new Snackbar();

var FormControls = require('./FormControls');
new FormControls();

var FeedbackWidget = require('./FeedbackWidget');
new FeedbackWidget();


global.jQuery = require('jquery');
require('bootstrap');


if (jQuery('body').hasClass('payment-page')) {
    var FilterablePaymentTable = require('./components/FilterablePaymentTable');
    React.render(<FilterablePaymentTable />, document.getElementById('react-test'));
}
