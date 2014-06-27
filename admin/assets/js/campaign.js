(function ( $ ) {
    "use strict";

    $(function () {

        $('#nc_campaign_send_campaign').click(function(e) {

            // Has already been clicked once, therefore got to save the post and send campaign
            if ($(this).hasClass('nc-campaign__send-ready')) {
                return true;
            }

            // Add a class to identify the second click
            $(this).addClass('nc-campaign__send-ready');

            // Show the confirmation
            $('.nc-campaign__confirmation').slideDown();

            var data = {
                'action': 'my_action',
                'whatever': ajax_object.we_value      // We pass php values differently!
            };
            $.post(ajax_object.ajax_url, data, function(response) {
                //alert('Got this from the server: ' + response);
            });

            e.preventDefault();
        });


        $('#nc_campaign_send_campaign_cancel').click(function() {

            // Remove the class to ensure the campaign doesn't get sent
            $('#nc_campaign_send_campaign').removeClass('nc-campaign__send-ready');

            // Hide the confirmation
            $('.nc-campaign__confirmation').slideUp();
        })


    });

}(jQuery));