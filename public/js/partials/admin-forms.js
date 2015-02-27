(function(QuickUpdateForms, $){

    $(document).ready(function() {

        $('form.js-quick-update').each(function () {
            $(this).on('change', function() {
                console.log("form change");
                //submit the form

                $.ajax({
                    type: "post",
                    dataType: "json",
                    data: $(this).serialize(),
                    url: $(this).attr('action')

                }).then(function(data) {
                    console.log(data);
                    console.log("Success");
                    BB.SnackBar.displayMessage(data);
                }, function() {
                    console.log("Error");
                    BB.SnackBar.displayMessage("There was an error");
                })
            });
        });
    });


})(window.BB.QuickUpdateForms = window.BB.QuickUpdateForms || {}, jQuery);