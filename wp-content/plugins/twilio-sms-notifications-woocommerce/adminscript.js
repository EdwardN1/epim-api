jQuery(document).ready(function ($) {

    //console.log('hello world');

    //lets first add a send button after message
    $('#wc_settings_twilio_sms_test_message').after('<a style="display: inherit;" href="#" class="send_test_twilio_sms button">Send</a>');

    $('.wrap').on("click",".send_test_twilio_sms", function(event){

        event.preventDefault();

        //get mobile number and message
        var mobile = $('#wc_settings_twilio_sms_test_mobile_number').val();
        var message = $('#wc_settings_twilio_sms_test_message').val();

        if(mobile.length < 1 || message.length < 1 || mobile.charAt(0) !== '+'){
            alertify.alert('Please ensure a mobile number and message is entered and the mobile number begins with a +');
        } else {
            //console.log('we are good to go');
            var data = {
                'action': 'send_test_sms_twilio',
                'mobile': mobile,
                'message': message,
            };

            jQuery.post(ajaxurl, data, function (response) {
                
                // console.log(response);

                alertify.success('SMS sent');

            });    
        }


    });


});    