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

    let debug = false;
    let cMax = 5;

    function _o(text) {
        $('#ajax-response').prepend(text + '<br>');
    }

    $('#CheckStatus').on('click', function () {
        $('#ajax-response').html('Working...');
        $('.modal.CheckStatus').show();
        let security = wpmai_ajax_object.security;
        let url = wpmai_ajax_object.ajax_url;
        $.ajax({
            type: "POST",
            url: url,
            data: {action: 'wpmai_get_check_status', security: security},
            success: function (data) {
                try {
                    let resp = JSON.parse(data);
                    let rstr = JSON.stringify(resp, undefined, 4);
                    $('#ajax-response').html(syntaxHighlight(rstr));
                    $('.modal.CheckStatus').hide();
                }
                catch (e) {
                    $('#ajax-response').html(data);
                    $('.modal.CheckStatus').hide();
                }
            }
        });
    });

    let ProductUpdatesQueue = new ts_execute_queue('#ajax-response',function() {
        _o('Finished');
        $('.modal.MerlinImport').hide();
    },function (action, request, data) {
        _o('Action Completed: ' + action);
        _o('Request: ' + request);
        try {
            let resp = JSON.parse(data);
            let rstr = JSON.stringify(resp, undefined, 4);
            _o('<br>Data: ' + syntaxHighlight(data));
        }
        catch (e) {
            _o('<br>Data: ' + data);
        }
        if(action==='wpmai_get_start_import') {
            let products = JSON.parse(data);
            let obj = this;
            $(products).each(function (index,product) {
                obj.queue(
                    ajaxurl,
                    {
                        action: 'wpmai_update_product',
                        sku: product.sku,
                        price: product.price,
                        qty: product.qty,
                    }
                )
            });
        }
    });

    $('#MerlinImport').on('click', function () {
        $('#ajax-response').html('Working...');
        $('.modal.MerlinImport').show();
        ProductUpdatesQueue.reset();
        ProductUpdatesQueue.queue(ajaxurl,{action: 'wpmai_get_start_import'});
        ProductUpdatesQueue.process();
        /*let security = wpmai_ajax_object.security;
        let url = wpmai_ajax_object.ajax_url;
        $.ajax({
            type: "POST",
            url: url,
            data: {action: 'wpmai_get_start_import', security: security},
            success: function (data) {
                try {
                    let resp = JSON.parse(data);
                    let rstr = JSON.stringify(resp, undefined, 4);
                    $('#ajax-response').html(syntaxHighlight(rstr));
                    $('.modal.MerlinImport').hide();
                }
                catch (e) {
                    $('#ajax-response').html(data);
                    $('.modal.MerlinImport').hide();
                }
            }
        });*/
    });

});