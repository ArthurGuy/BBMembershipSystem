class FormControls {

    constructor() {


        var $ = require('jquery');
        require('select2');
        require('bootstrap-datepicker');

        console.log("Form Controls Loading");


        (function($){

            $(document).ready(function() {

                $(".advanced-dropdown").select2({dropdownAutoWidth:false});

                $('.date-select').datepicker({
                    format: "yyyy-mm-dd",
                    autoclose: true,
                    todayHighlight: true
                });
            });

            //Change sub amount button
            $('.js-show-alter-subscription-amount').click(function(event) {
                event.preventDefault();
                $('.js-alter-subscription-amount-form').removeClass('hidden');
                $(this).hide();
            });


            //Activity page - Date Picker
            $('#activityDatePicker .date-select').on('change', function(e){
                $('#activityDatePicker').submit();
            });

        })($);

        console.log("Form Controls Loaded");
    }

}

export default FormControls;