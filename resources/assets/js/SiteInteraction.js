class SiteInteraction {

    constructor() {


        var jQuery = require('jquery');


        console.log("Site Interaction Loading");

        jQuery(window).on("scroll", function(e) {
            if (jQuery("body").scrollTop() > 103) {
                jQuery("#bodyWrap").addClass("fixedHeader");
            } else {
                jQuery("#bodyWrap").removeClass("fixedHeader");
            }

        });

        //Navbar top menu
        jQuery(".mainSidenav .toggleSettings").on('click', function(event) {
            event.preventDefault();
            jQuery(".mainSidenav .memberAccountLinks").toggleClass('open');
        });


        global.jQuery = jQuery;

        //Tooltips
        require('bootstrap');
        jQuery('[data-toggle=tooltip]').tooltip({});

        //Fancy dropdown
        require('select2');
        jQuery(".js-advanced-dropdown").select2({dropdownAutoWidth:false});


        //Main side nav - mobile view
        jQuery(".menuToggleButton").on('click', function() {
            jQuery("body").addClass("menuOpen");
            jQuery(".modalMask").addClass("display");
        });

        
        //Modal window background mask
        jQuery(".modalMask").on('click', function() {
            jQuery(".modalMask").removeClass("display");
            jQuery("body").removeClass("menuOpen");
        });


        //Change sub amount button
        jQuery('.js-show-alter-subscription-amount').click(function(event) {
            event.preventDefault();
            jQuery('.js-alter-subscription-amount-form').removeClass('hidden');
            jQuery(this).hide();
        });

        jQuery('.js-notification-row').click(function(event) {
            event.preventDefault();
            var id = jQuery(this).data('id');
            var that = this;
            jQuery.post('notifications/' + id, {read: true, _method: 'PUT'}, function() {
                jQuery(that).removeClass('success');
                jQuery(that).find('.js-unread').text('');
            });
        });


        //Input date picker
        require('bootstrap-datepicker');
        jQuery('.js-date-select').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true
        });

        //Activity page - Date picker, auto form submit
        jQuery('#activityDatePicker').find('.js-date-select').datepicker()
            .on('changeDate', function(e) {
                jQuery('#activityDatePicker').submit();
            });


        console.log("Site Interaction Loaded");

    }

}

export default SiteInteraction;