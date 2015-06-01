class FeedbackWidget {

    constructor() {


        var $ = require('jquery');

        console.log("Feedback Widget Loading");


        (function($){

            //Support for simple ajax forms
            $('.js-feedbackModalForm').on('submit', function(event) {
                event.preventDefault();

                $(this).find('input[type=submit]').attr('disabled', 'disabled');

                //Clear the error messages
                $(this).find('.js-errorMessages').text('');
                $(this).find('.has-error').removeClass('has-error');

                //Store the context for the callbacks
                var $form = $(this);
                $.ajax({
                    type: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: [function() {
                        $form.find('input[type=submit]').removeAttr('disabled');
                    }, feedbackFormSuccess],
                    error: [function() {
                        $form.find('input[type=submit]').removeAttr('disabled');
                    }, feedbackFormError],
                    dataType: 'json'
                });
            });

            //Specific handlers for the feedback form
            function feedbackFormSuccess(data) {
                if (data.success) {
                    $('#feedbackWidgetModal').modal('hide');
                    $('.js-feedbackModalForm textarea').val('');
                }
            }
            function feedbackFormError(xhr, status, error) {
                var errors = eval("(" + xhr.responseText + ")");
                for(var n in errors) {
                    var $field = $('.js-feedbackModalForm').find('.js-field-'+n+'');
                    $field.addClass('has-error');
                    $field.find('.js-errorMessages').text(errors[n]);
                }
            }

        })($);

        console.log("Feedback Widget Loaded");
    }

}

export default FeedbackWidget;