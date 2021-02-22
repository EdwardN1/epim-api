adminJQ = jQuery.noConflict();

function syntaxHighlight(json) {
    json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
        var cls = 'number';
        if (/^"/.test(match)) {
            if (/:$/.test(match)) {
                cls = 'key';
            } else {
                cls = 'string';
            }
        } else if (/true|false/.test(match)) {
            cls = 'boolean';
        } else if (/null/.test(match)) {
            cls = 'null';
        }
        return '<span class="' + cls + '">' + match + '</span>';
    });
}

adminJQ(function ($) {

    $('#TestResponseRequestProducts').on('click', function () {
        $('#ajax-response').html('Working...');
        $('.modal.TestResponseRequestProducts').show();
        let security = wpmace_ajax_object.security;
        let url = wpmace_ajax_object.ajax_url;
        $.ajax({
            type: "POST",
            url: url,
            data: {action: 'mace_get_request_products_response', security: security},
            success: function (data) {
                try {
                    let resp = JSON.parse(data);
                    let rstr = JSON.stringify(resp, undefined, 4);
                    $('#ajax-response').html(syntaxHighlight(rstr));
                    $('.modal.TestResponseRequestProducts').hide();
                    window.console.log('Data is JSON');
                } catch (e) {
                    if (typeof data === 'object' && data !== null) {
                        let x = JSON.stringify(data);
                        $('#ajax-response').html(syntaxHighlight(x.replace(/\\(.)/mg, "")));
                    } else {
                        $('#ajax-response').html(syntaxHighlight(data));
                    }
                    $('.modal.TestResponseRequestProducts').hide();
                }
            }
        });
    });
});