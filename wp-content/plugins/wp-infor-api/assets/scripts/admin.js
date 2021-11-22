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

    $('#testCustomerParams').on('click', function () {
        $('#ajax-response').html('Working...');
        $('.modal.testCustomerParams').show();
        let security = wpiai_ajax_object.security;
        let url = wpiai_ajax_object.ajax_url;
        $.ajax({
            type: "POST",
            url: url,
            data: {action: 'wpiai_get_customer_params', security: security},
            success: function (data) {
                try {
                    let resp = JSON.parse(data);
                    let rstr = JSON.stringify(resp, undefined, 4);
                    $('#ajax-response').html(syntaxHighlight(rstr));
                    $('.modal.testCustomerParams').hide();
                    window.console.log('Data is JSON');
                }
                catch (e) {
                    if(typeof data === 'object' && data !== null) {
                        let x = JSON.stringify(data);
                        $('#ajax-response').html(syntaxHighlight(x.replace(/\\(.)/mg, "")));
                    } else {
                        $('#ajax-response').html(syntaxHighlight(data));
                    }
                    $('.modal.testCustomerParams').hide();
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
                    $('.modal.salesOrderRecordResponse').hide();
                }
            }
        });
    });

    $('#shipToRecordResponse').on('click', function () {
        $('#ajax-response').html('Working...');
        $('.modal.shipToRecordResponse').show();
        let security = wpiai_ajax_object.security;
        let url = wpiai_ajax_object.ajax_url;
        $.ajax({
            type: "POST",
            url: url,
            data: {action: 'wpiai_get_ship_to_response', security: security},
            success: function (data) {
                try {
                    let resp = JSON.parse(data);
                    let rstr = JSON.stringify(resp, undefined, 4);
                    $('#ajax-response').html(syntaxHighlight(rstr));
                    $('.modal.shipToRecordResponse').hide();
                    window.console.log('Data is JSON');
                }
                catch (e) {
                    if(typeof data === 'object' && data !== null) {
                        let x = JSON.stringify(data);
                        $('#ajax-response').html(syntaxHighlight(x.replace(/\\(.)/mg, "")));
                    } else {
                        $('#ajax-response').html(syntaxHighlight(data));
                    }
                    $('.modal.shipToRecordResponse').hide();
                }
            }
        });
    });

    $('#contactRecordResponse').on('click', function () {
        $('#ajax-response').html('Working...');
        $('.modal.contactRecordResponse').show();
        let security = wpiai_ajax_object.security;
        let url = wpiai_ajax_object.ajax_url;
        $.ajax({
            type: "POST",
            url: url,
            data: {action: 'wpiai_get_contact_response', security: security},
            success: function (data) {
                try {
                    let resp = JSON.parse(data);
                    let rstr = JSON.stringify(resp, undefined, 4);
                    $('#ajax-response').html(syntaxHighlight(rstr));
                    $('.modal.contactRecordResponse').hide();
                    window.console.log('Data is JSON');
                }
                catch (e) {
                    if(typeof data === 'object' && data !== null) {
                        let x = JSON.stringify(data);
                        $('#ajax-response').html(syntaxHighlight(x.replace(/\\(.)/mg, "")));
                    } else {
                        $('#ajax-response').html(syntaxHighlight(data));
                    }
                    $('.modal.contactRecordResponse').hide();
                }
            }
        });
    });

    $('#testContactXML').on('click', function () {
        $('#ajax-response').html('Working...');
        $('.modal.testContactXML').show();
        let security = wpiai_ajax_object.security;
        let url = wpiai_ajax_object.ajax_url;
        $.ajax({
            type: "POST",
            url: url,
            data: {action: 'wpiai_get_contact_xml', security: security},
            success: function (data) {
                try {
                    let resp = JSON.parse(data);
                    let rstr = JSON.stringify(resp, undefined, 4);
                    $('#ajax-response').html(syntaxHighlight(rstr));
                    $('.modal.testContactXML').hide();
                    window.console.log('Data is JSON');
                }
                catch (e) {
                    if(typeof data === 'object' && data !== null) {
                        let x = JSON.stringify(data);
                        $('#ajax-response').html(syntaxHighlight(x.replace(/\\(.)/mg, "")));
                    } else {
                        $('#ajax-response').html(syntaxHighlight(data));
                    }
                    $('.modal.testContactXML').hide();
                }
            }
        });
    });

    $('#testShipToXML').on('click', function () {
        $('#ajax-response').html('Working...');
        $('.modal.testShipToXML').show();
        let security = wpiai_ajax_object.security;
        let url = wpiai_ajax_object.ajax_url;
        $.ajax({
            type: "POST",
            url: url,
            data: {action: 'wpiai_get_ship_to_xml', security: security},
            success: function (data) {
                try {
                    let resp = JSON.parse(data);
                    let rstr = JSON.stringify(resp, undefined, 4);
                    $('#ajax-response').html(syntaxHighlight(rstr));
                    $('.modal.testShipToXML').hide();
                    window.console.log('Data is JSON');
                }
                catch (e) {
                    if(typeof data === 'object' && data !== null) {
                        let x = JSON.stringify(data);
                        $('#ajax-response').html(syntaxHighlight(x.replace(/\\(.)/mg, "")));
                    } else {
                        $('#ajax-response').html(syntaxHighlight(data));
                    }
                    $('.modal.testShipToXML').hide();
                }
            }
        });
    });

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
                }
                catch (e) {
                    if(typeof data === 'object' && data !== null) {
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

    function _oi(text) {
        $('#ajax-response').prepend(text + '<br>');
    }

    let productDefaultPricesQueue = new ts_execute_queue('#ajax-response', function () {
        _oi('finished');
        $('.modal.productDefaultPrices').hide();
    }, function (action, request, data) {
        _oi('Action Completed: ' + action);
        _oi('Request: ' + request);
        _oi('<br>Data: ' + data);
        if(action==='wpiai_update_default_prices') {
            let obj = this;
            /*obj.queue(ajaxurl,{action: 'wpiai_update_default_price_for_product', ID: 'EMRCHJ34RED'});
            obj.queue(ajaxurl,{action: 'wpiai_update_default_price_for_product', ID: 'IS4825OR'});
            obj.queue(ajaxurl,{action: 'wpiai_update_default_price_for_product', ID: 'PFA20B'});
            obj.queue(ajaxurl,{action: 'wpiai_update_default_price_for_product', ID: 'TC1D0910P7'});
            obj.queue(ajaxurl,{action: 'wpiai_update_default_price_for_product', ID: 'GSC4'});*/
            $(data).each(function (index, sku) {
                //window.console.log('Adding: '+sku+' to the queue');
                obj.queue(ajaxurl,{action: 'wpiai_update_default_price_for_product', sku: sku});
            });
        }
        if(action=='wpiai_update_default_price_for_product') {
            window.console.log('Processed Product');
        }

    });

    let productTestGetInforPricesQueue = new ts_execute_queue('#ajax-response', function () {
        _oi('finished');
        $('.modal.productTestGetInforPrices').hide();
    }, function (action, request, data) {
        _oi('Action Completed: ' + action);
        _oi('Request: ' + request);
        _oi('<br>Data: ' + JSON.stringify(data));
    });

    $('#productTestGetInforPrices').on('click', function () {
        $('#ajax-response').html('Working...');
        $('.modal.productTestGetInforPrices').show();
        productTestGetInforPricesQueue.reset();
        productTestGetInforPricesQueue.queue(ajaxurl,{action: 'wpiai_test_get_infor_prices'});
        productTestGetInforPricesQueue.process();
        window.console.log('productTestGetInforPricesQueue processing');
    });

    $('#productDefaultPrices').on('click', function () {
        $('#ajax-response').html('Working...');
        $('.modal.productDefaultPrices').show();
        productDefaultPricesQueue.reset();
        productDefaultPricesQueue.queue(ajaxurl,{action: 'wpiai_update_default_prices'});
        productDefaultPricesQueue.process();
        window.console.log('productDefaultPricesQueue processing');
        /*let security = wpiai_ajax_object.security;
        let url = wpiai_ajax_object.ajax_url;
        $.ajax({
            type: "POST",
            url: url,
            data: {action: 'wpiai_update_default_prices', security: security},
            success: function (data) {
                try {
                    let resp = JSON.parse(data);
                    let rstr = JSON.stringify(resp, undefined, 4);
                    $('#ajax-response').html('<strong>Products to Update:</strong><br><br>'+syntaxHighlight(rstr));
                    $('.modal.productDefaultPrices').hide();
                    window.console.log('Data is JSON');
                }
                catch (e) {
                    if(typeof data === 'object' && data !== null) {
                        let x = JSON.stringify(data);
                        window.console.log('Data is object');
                        //$('#ajax-response').html(syntaxHighlight(x.replace(/\\(.)/mg, "")));
                        c = 0;
                        $(data).each(function () {
                            c++;
                        });
                        _oi('Processing ' +c+' products');
                        $(data).each(function (productID) {
                            $.ajax((
                                {
                                    type: "POST",
                                    url: url,
                                    data: {action: 'wpiai_update_default_price_for_product', security: security, ID: productID},
                                    success: function (data) {
                                        _oi(data);
                                    }
                                }
                            ))
                        });
                    } else {
                        $('#ajax-response').html(syntaxHighlight(data));
                    }
                    $('.modal.productDefaultPrices').hide();
                }
            }
        });*/
    });

    $('#productUpdatesAPIResponse').on('click', function () {
        $('#ajax-response').html('Working...');
        $('.modal.productUpdatesAPIResponse').show();
        let security = wpiai_ajax_object.security;
        let url = wpiai_ajax_object.ajax_url;
        $.ajax({
            type: "POST",
            url: url,
            data: {action: 'wpiai_get_product_updates_api_response', security: security},
            success: function (data) {
                //window.console.log('data returned');
                try {
                    let resp = JSON.parse(data);
                    let rstr = JSON.stringify(resp, undefined, 4);
                    $('#ajax-response').html(syntaxHighlight(rstr));
                    $('.modal.productUpdatesAPIResponse').hide();
                    window.console.log('Data is JSON');
                }
                catch (e) {
                    if(typeof data === 'object' && data !== null) {
                        let x = JSON.stringify(data);
                        $('#ajax-response').html(syntaxHighlight(x.replace(/\\(.)/mg, "")));
                    } else {
                        $('#ajax-response').html(syntaxHighlight(data));
                    }
                    $('.modal.productUpdatesAPIResponse').hide();
                }
            }
        });
    });

    $('#accountsGetCustomerBalances').on('click', function () {
        $('#ajax-response').html('Working...');
        $('.modal.accountsGetCustomerBalances').show();
        let security = wpiai_ajax_object.security;
        let url = wpiai_ajax_object.ajax_url;
        //let custNum = $('#customer_number').val();
        $.ajax({
            type: "POST",
            url: url,
            data: {action: 'wpiai_get_accounts_customer_balances_api_response', security: security},
            success: function (data) {
                //window.console.log('data returned');
                try {
                    let resp = JSON.parse(data);
                    let rstr = JSON.stringify(resp, undefined, 4);
                    $('#ajax-response').html(syntaxHighlight(rstr));
                    $('.modal.accountsGetCustomerBalances').hide();
                    window.console.log('Data is JSON');
                }
                catch (e) {
                    if(typeof data === 'object' && data !== null) {
                        let x = JSON.stringify(data);
                        $('#ajax-response').html(syntaxHighlight(x.replace(/\\(.)/mg, "")));
                    } else {
                        $('#ajax-response').html(syntaxHighlight(data));
                    }
                    $('.modal.accountsGetCustomerBalances').hide();
                }
            }
        });
    });

    $('#accountsGetCustomerDataCredit').on('click', function () {
        $('#ajax-response').html('Working...');
        $('.modal.accountsGetCustomerDataCredit').show();
        let security = wpiai_ajax_object.security;
        let url = wpiai_ajax_object.ajax_url;
        //let custNum = $('#customer_number').val();
        $.ajax({
            type: "POST",
            url: url,
            data: {action: 'wpiai_get_accounts_customer_data_credit_api_response', security: security},
            success: function (data) {
                //window.console.log('data returned');
                try {
                    let resp = JSON.parse(data);
                    let rstr = JSON.stringify(resp, undefined, 4);
                    $('#ajax-response').html(syntaxHighlight(rstr));
                    $('.modal.accountsGetCustomerDataCredit').hide();
                    window.console.log('Data is JSON');
                }
                catch (e) {
                    if(typeof data === 'object' && data !== null) {
                        let x = JSON.stringify(data);
                        $('#ajax-response').html(syntaxHighlight(x.replace(/\\(.)/mg, "")));
                    } else {
                        $('#ajax-response').html(syntaxHighlight(data));
                    }
                    $('.modal.accountsGetCustomerDataCredit').hide();
                }
            }
        });
    });

    $('#invoicesAPIResponse').on('click', function () {
        $('#ajax-response').html('Working...');
        $('.modal.invoicesAPIResponse').show();
        let security = wpiai_ajax_object.security;
        let url = wpiai_ajax_object.ajax_url;
        //let custNum = $('#customer_number').val();
        $.ajax({
            type: "POST",
            url: url,
            data: {action: 'wpiai_get_invoices_api_response', security: security},
            success: function (data) {
                //window.console.log('data returned');
                try {
                    let resp = JSON.parse(data);
                    let rstr = JSON.stringify(resp, undefined, 4);
                    $('#ajax-response').html(syntaxHighlight(rstr));
                    $('.modal.invoicesAPIResponse').hide();
                    window.console.log('Data is JSON');
                }
                catch (e) {
                    if(typeof data === 'object' && data !== null) {
                        let x = JSON.stringify(data);
                        $('#ajax-response').html(syntaxHighlight(x.replace(/\\(.)/mg, "")));
                    } else {
                        $('#ajax-response').html(syntaxHighlight(data));
                    }
                    $('.modal.invoicesAPIResponse').hide();
                }
            }
        });
    });

    $('#singleInvoiceAPIResponse').on('click', function () {
        $('#ajax-response').html('Working...');
        $('.modal.singleInvoiceAPIResponse').show();
        let security = wpiai_ajax_object.security;
        let url = wpiai_ajax_object.ajax_url;
        //let custNum = $('#customer_number').val();
        $.ajax({
            type: "POST",
            url: url,
            data: {action: 'wpiai_get_single_invoice_api_response', security: security},
            success: function (data) {
                //window.console.log('data returned');
                try {
                    let resp = JSON.parse(data);
                    let rstr = JSON.stringify(resp, undefined, 4);
                    $('#ajax-response').html(syntaxHighlight(rstr));
                    $('.modal.singleInvoiceAPIResponse').hide();
                    window.console.log('Data is JSON');
                }
                catch (e) {
                    if(typeof data === 'object' && data !== null) {
                        let x = JSON.stringify(data);
                        $('#ajax-response').html(syntaxHighlight(x.replace(/\\(.)/mg, "")));
                    } else {
                        $('#ajax-response').html(syntaxHighlight(data));
                    }
                    $('.modal.singleInvoiceAPIResponse').hide();
                }
            }
        });
    });

    $('#singleInvoiceAPIResponseTest').on('click', function () {
        $('#ajax-response').html('Working...');
        $('.modal.singleInvoiceAPIResponseTest').show();
        let security = wpiai_ajax_object.security;
        let url = wpiai_ajax_object.ajax_url;
        //let custNum = $('#customer_number').val();
        $.ajax({
            type: "POST",
            url: url,
            data: {action: 'wpiai_get_single_invoice_api_response_test', security: security, order: $('#wpiai_single_invoice_number').val()},
            success: function (data) {
                //window.console.log('data returned');
                try {
                    let resp = JSON.parse(data);
                    let rstr = JSON.stringify(resp, undefined, 4);
                    $('#ajax-response').html(syntaxHighlight(rstr));
                    $('.modal.singleInvoiceAPIResponseTest').hide();
                    window.console.log('Data is JSON');
                }
                catch (e) {
                    if(typeof data === 'object' && data !== null) {
                        let x = JSON.stringify(data);
                        $('#ajax-response').html(syntaxHighlight(x.replace(/\\(.)/mg, "")));
                    } else {
                        $('#ajax-response').html(syntaxHighlight(data));
                    }
                    $('.modal.singleInvoiceAPIResponseTest').hide();
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

    $('#testSalesChangeOrderXML').on('click', function () {
        $('#ajax-response').html('Working...');
        $('.modal.testChangeSalesOrderXML').show();
        let security = wpiai_ajax_object.security;
        let url = wpiai_ajax_object.ajax_url;
        $.ajax({
            type: "POST",
            url: url,
            data: {action: 'wpiai_get_changed_order_XML', security: security},
            success: function (data) {
                try {
                    let resp = JSON.parse(data);
                    let rstr = JSON.stringify(resp, undefined, 4);
                    $('#ajax-response').html(syntaxHighlight(rstr));
                    $('.modal.testSalesChangeOrderXML').hide();
                    window.console.log('Data is JSON');
                }
                catch (e) {
                    if(typeof data === 'object' && data !== null) {
                        let x = JSON.stringify(data);
                        $('#ajax-response').html(syntaxHighlight(x.replace(/\\(.)/mg, "")));
                    } else {
                        $('#ajax-response').html(syntaxHighlight(data));
                    }
                    $('.modal.testSalesChangeOrderXML').hide();
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