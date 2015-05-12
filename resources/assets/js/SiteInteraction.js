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


        jQuery(".mainSidenav .toggleSettings").on('click', function(event) {
            event.preventDefault();
            jQuery(".mainSidenav .memberAccountLinks").toggleClass('open');
        });


        global.jQuery = jQuery;
        require('bootstrap');
        jQuery('[data-toggle=tooltip]').tooltip({});


        jQuery(".menuToggleButton").on('click', function() {
            jQuery("body").addClass("menuOpen");
            jQuery(".modalMask").addClass("display");
        });
        jQuery(".modalMask").on('click', function() {
            jQuery(".modalMask").removeClass("display");
            jQuery("body").removeClass("menuOpen");
        });


        console.log("Site Interaction Loaded");

    }

}

export default SiteInteraction;