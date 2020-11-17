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

    $('#TestResponse').on('click', function () {
        $('#ajax-response').html('Working...');
        $('.modal.TestResponse').show();
        let security = wpiai_ajax_object.security;
        let url = wpiai_ajax_object.ajax_url;
        $.ajax({
            type: "POST",
            url: url,
            data: {action: 'wpiai_get_message_response', security: security},
            success: function (data) {
                try {
                    let resp = JSON.parse(data);
                    let rstr = JSON.stringify(resp, undefined, 4);
                    $('#ajax-response').html(syntaxHighlight(rstr));
                    $('.modal.TestResponse').hide();
                    window.console.log('Data is JSON');
                }
                catch (e) {
                    if(typeof data === 'object' && data !== null) {
                        let x = JSON.stringify(data);
                        $('#ajax-response').html(syntaxHighlight(x.replace(/\\(.)/mg, "")));
                    } else {
                        $('#ajax-response').html(syntaxHighlight(data));
                    }
                    $('.modal.TestResponse').hide();
                }
            }
        });
    });

    $('#customerRecordResponse').on('click', function () {
        $('#ajax-response').html('Working...');
        $('.modal.customerRecordResponse').show();
        let security = wpiai_ajax_object.security;
        let url = wpiai_ajax_object.ajax_url;
        $.ajax({
            type: "POST",
            url: url,
            data: {action: 'wpiai_get_customer_response', security: security},
            success: function (data) {
                try {
                    let resp = JSON.parse(data);
                    let rstr = JSON.stringify(resp, undefined, 4);
                    $('#ajax-response').html(syntaxHighlight(rstr));
                    $('.modal.customerRecordResponse').hide();
                    window.console.log('Data is JSON');
                }
                catch (e) {
                    if(typeof data === 'object' && data !== null) {
                        let x = JSON.stringify(data);
                        $('#ajax-response').html(syntaxHighlight(x.replace(/\\(.)/mg, "")));
                    } else {
                        $('#ajax-response').html(syntaxHighlight(data));
                    }
                    $('.modal.TestResponse').hide();
                }
            }
        });
    });

    $('#testCustomerXML').on('click', function () {
        $('#ajax-response').html('Working...');
        $('.modal.testCustomerXML').show();
        let security = wpiai_ajax_object.security;
        let url = wpiai_ajax_object.ajax_url;
        $.ajax({
            type: "POST",
            url: url,
            data: {action: 'wpiai_get_customer_xml', security: security},
            success: function (data) {
                try {
                    let resp = JSON.parse(data);
                    let rstr = JSON.stringify(resp, undefined, 4);
                    $('#ajax-response').html(syntaxHighlight(rstr));
                    $('.modal.customerRecordResponse').hide();
                    window.console.log('Data is JSON');
                }
                catch (e) {
                    if(typeof data === 'object' && data !== null) {
                        let x = JSON.stringify(data);
                        $('#ajax-response').html(syntaxHighlight(x.replace(/\\(.)/mg, "")));
                    } else {
                        $('#ajax-response').html(syntaxHighlight(data));
                    }
                    $('.modal.testCustomerXML').hide();
                }
            }
        });
    });

    $('#salesOrderRecordResponse').on('click', function () {
        $('#ajax-response').html('Working...');
        $('.modal.salesOrderRecordResponse').show();
        let security = wpiai_ajax_object.security;
        let url = wpiai_ajax_object.ajax_url;
        $.ajax({
            type: "POST",
            url: url,
            data: {action: 'wpiai_get_sales_order_response', security: security},
            success: function (data) {
                try {
                    let resp = JSON.parse(data);
                    let rstr = JSON.stringify(resp, undefined, 4);
                    $('#ajax-response').html(syntaxHighlight(rstr));
                    $('.modal.salesOrderRecordResponse').hide();
                    window.console.log('Data is JSON');
                }
                catch (e) {
                    if(typeof data === 'object' && data !== null) {
                        let x = JSON.stringify(data);
                        $('#ajax-response').html(syntaxHighlight(x.replace(/\\(.)/mg, "")));
                    } else {
                        $('#ajax-response').html(syntaxHighlight(data));
                    }
                    $('.modal.TestResponse').hide();
                }
            }
        });
    });

    $('#testSalesOrderXML').on('click', function () {
        $('#ajax-response').html('Working...');
        $('.modal.testSalesOrderXML').show();
        let security = wpiai_ajax_object.security;
        let url = wpiai_ajax_object.ajax_url;
        $.ajax({
            type: "POST",
            url: url,
            data: {action: 'wpiai_get_sales_order_xml', security: security},
            success: function (data) {
                try {
                    let resp = JSON.parse(data);
                    let rstr = JSON.stringify(resp, undefined, 4);
                    $('#ajax-response').html(syntaxHighlight(rstr));
                    $('.modal.testSalesOrderXML').hide();
                    window.console.log('Data is JSON');
                }
                catch (e) {
                    if(typeof data === 'object' && data !== null) {
                        let x = JSON.stringify(data);
                        $('#ajax-response').html(syntaxHighlight(x.replace(/\\(.)/mg, "")));
                    } else {
                        $('#ajax-response').html(syntaxHighlight(data));
                    }
                    $('.modal.testSalesOrderXML').hide();
                }
            }
        });
    });

    $('#PingInfor').on('click', function () {
        $('#ajax-response').html('Working...');
        $('.modal.PingInfor').show();
        let security = wpiai_ajax_object.security;
        let url = wpiai_ajax_object.ajax_url;
        $.ajax({
            type: "POST",
            url: url,
            data: {action: 'wpiai_get_infor_ping', security: security},
            success: function (data) {
                try {
                    let resp = JSON.parse(data.body);
                    let rstr = JSON.stringify(resp, undefined, 4);
                    $('#ajax-response').html(syntaxHighlight(rstr));
                    $('.modal.PingInfor').hide();
                }
                catch (e) {
                    if(typeof data === 'object' && data !== null) {
                        window.console.log('JSON Parse Error - Data is object but will not parse' );
                        let x = JSON.stringify(data);
                        $('#ajax-response').html(syntaxHighlight(x.replace(/\\(.)/mg, "")));
                    } else {
                        window.console.log('JSON Parse Error - not an object or NULL');
                        $('#ajax-response').html(syntaxHighlight(data));
                    }
                    $('.modal.PingInfor').hide();
                }
            }
        });
    });

});