var React = require('react');



//Configure a global private pusher channel
var userId = document.getElementById('userId').value;

if (typeof Pusher != 'undefined') {
    Pusher.log = function (message) {
        window.console.log(message);
    };
    var pusher = new Pusher('76cf385da8c9087f9d68', {authEndpoint: '/session/pusher'});
    global.privateMemberChannel = pusher.subscribe('private-' + userId);
}




var SiteInteraction = require('./SiteInteraction');
new SiteInteraction();

//var PaymentForm = require('./PaymentForm');
//new PaymentForm();

var AdminForms = require('./AdminForms');
new AdminForms();

var Snackbar = require('./Snackbar');
new Snackbar();

var FeedbackWidget = require('./FeedbackWidget');
new FeedbackWidget();


global.jQuery = require('jquery');
require('bootstrap');



//Site wide notification loading
var Notifications = require('./collections/Notifications');
var notifications = new Notifications();
notifications.fetch();  //fetch the current data once so it can be used in various places

//If a new notification is received by pusher add it to the collection
privateMemberChannel.bind("BB\\Events\\NewMemberNotification", function(data) {
    notifications.add(data.notification);
});



jQuery('.js-notifications-table').each(function () {
    var NotificationsTable = require('./components/notifications/NotificationsTable');
    React.render(<NotificationsTable notifications={notifications} />, jQuery(this)[0]);
});

jQuery('.js-notifications-count').each(function () {
    var NotificationCount = require('./components/notifications/NotificationCount');
    React.render(<NotificationCount notifications={notifications} />, jQuery(this)[0]);
});

if (jQuery('body').hasClass('payment-page')) {
    var FilterablePaymentTable = require('./components/FilterablePaymentTable');
    React.render(<FilterablePaymentTable />, document.getElementById('react-test'));
}

var PaymentModule = require('./components/PaymentModule');
jQuery('.paymentModule').each(function () {

    var reason = jQuery(this).data('reason');
    var displayReason = jQuery(this).data('displayReason');
    var buttonLabel = jQuery(this).data('buttonLabel');
    var methods = jQuery(this).data('methods');
    var amount = jQuery(this).data('amount');
    var ref = jQuery(this).data('ref');
    var memberEmail = document.getElementById('memberEmail').value;
    var userId = document.getElementById('userId').value;
    var stripeKey = document.getElementById('stripePublicKey').value;
    var csrfToken = document.getElementById('csrfToken').value;

    var handleSuccess = () => { document.location.reload(true) };

    React.render(<PaymentModule csrfToken={csrfToken} description={displayReason} reason={reason} amount={amount} email={memberEmail} userId={userId} onSuccess={handleSuccess} buttonLabel={buttonLabel} methods={methods} reference={ref} stripeKey={stripeKey} />, jQuery(this)[0]);
});


var memberExpensesPanel = jQuery('#memberExpenses');
if (memberExpensesPanel.length) {

    var MemberExpenses = require('./components/expenses/MemberExpenses');
    var Expenses = require('./collections/Expenses');
    var expenses = new Expenses();
    //global.expenses = expenses;
    var userId = memberExpensesPanel.data('userId');
    React.render(<MemberExpenses expenses={expenses} userId={userId} />, memberExpensesPanel[0]);

}
