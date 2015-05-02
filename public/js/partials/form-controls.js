(function($){

    $(document).ready(function() {

        $(".advanced-dropdown").select2({dropdownAutoWidth:false});

        $('.date-select').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true
        });
    });

    //Support for simple ajax forms
    $('.js-ajaxForm').on('submit', function(event) {
        event.preventDefault();

        //Get the success and failure functions for the form
        var successFunction = $(this).attr('data-successFunction');
        var errorFunction = $(this).attr('data-errorFunction');

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
            }, window[successFunction]],
            error: [function() {
                $form.find('input[type=submit]').removeAttr('disabled');
            }, window[errorFunction]],
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

    //Change sub amount button
    $('.js-show-alter-subscription-amount').click(function(event) {
        event.preventDefault();
        $('.js-alter-subscription-amount-form').removeClass('hidden');
        $(this).hide();
    });

})(jQuery);