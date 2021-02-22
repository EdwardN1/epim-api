adminJQ(function ($) {

    $('#productAPIResponse').on('click', function () {
        $('#ajax-response').html('Working...');
        $('.modal.productAPIResponse').show();
        let security = wpiai_ajax_object.security;
        let url = wpiai_ajax_object.ajax_url;
        $.ajax({
            type: "POST",
            url: url,
            data: {action: 'wpiai_get_product_api_response', security: security},
            success: function (data) {
                try {
                    let resp = JSON.parse(data);
                    let rstr = JSON.stringify(resp, undefined, 4);
                    $('#ajax-response').html(syntaxHighlight(rstr));
                    $('.modal.productAPIResponse').hide();
                    window.console.log('Data is JSON');
                } catch (e) {
                    if (typeof data === 'object' && data !== null) {
                        let x = JSON.stringify(data);
                        $('#ajax-response').html(syntaxHighlight(x.replace(/\\(.)/mg, "")));
                    } else {
                        $('#ajax-response').html(syntaxHighlight(data));
                    }
                    $('.modal.productAPIResponse').hide();
                }
            }
        });
    });
});