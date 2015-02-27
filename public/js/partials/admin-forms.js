(function($){

    $(document).ready(function() {

        $('form.js-quick-update').each(function () {
            $(this).find('select').on('change', function() {
                var $form = $(this.form);

                $.ajax({
                    type: 'POST',
                    dataType: "json",
                    data: $form.serialize(),
                    url: $form.attr('action')

                }).then(function(data) {
                    BB.SnackBar.displayMessage(data);
                }, function() {
                    BB.SnackBar.displayMessage("There was an error");
                })
            });
        });
    });


})(jQuery);