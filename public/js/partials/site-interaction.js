$(".menuToggleButton").on('click', function() {
    $("body").addClass("menuOpen");
    $(".modalMask").addClass("display");
});
$(".modalMask").on('click', function() {
    $(".modalMask").removeClass("display");
    $("body").removeClass("menuOpen");
});

$(window).on("scroll", function(e) {
    if ($("body").scrollTop() > 103) {
        $("#bodyWrap").addClass("fixedHeader");
    } else {
        $("#bodyWrap").removeClass("fixedHeader");
    }

});

$(".mainSidenav .toggleSettings").on('click', function(event) {
    event.preventDefault();
    $(".mainSidenav .memberAccountLinks").toggleClass('open');
});


$('[data-toggle=tooltip]').tooltip({});