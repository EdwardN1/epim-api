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
    $('#TestAuthentication').on('click', function () {
        $('#ajax-response').html('Working...');
        $('.modal.TestAuthentication').show();
        let security = wpiai_ajax_object.security;
        let url = wpiai_ajax_object.ajax_url;
        $.ajax({
            type: "POST",
            url: url,
            data: {action: 'wpiai_get_access_token', security: security},
            success: function (data) {
                try {
                    let resp = JSON.parse(data);
                    let rstr = JSON.stringify(resp, undefined, 4);
                    $('#ajax-response').html(syntaxHighlight(rstr));
                    $('.modal.TestAuthentication').hide();
                }
                catch (e) {
                    $('#ajax-response').html(data);
                    $('.modal.TestAuthentication').hide();
                }
            }
        });
    });
});