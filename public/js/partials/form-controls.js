(function($){

    $(document).ready(function() {

        $(".advanced-dropdown").select2({dropdownAutoWidth:false});

        $('.date-select').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true
        });
    });

})(jQuery);