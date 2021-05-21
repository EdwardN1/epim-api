<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Creating an Options Page
 */


function wpiai_register_options_page()
{
    add_menu_page(__('Infor Options'), __('Infor Options'), 'manage_options', 'infor-options', 'wpiai_options_page', plugins_url('assets/img/infor-logo.png', __DIR__), 2);
}

add_action('admin_menu', 'wpiai_register_options_page');

/**
 * Register Settings For Plugin
 */

function wpiai_register_settings()
{
	add_option('wpiai_api_enabled', 1);
	register_setting('wpiai_options_group', 'wpiai_api_enabled');
	add_option('wpiai_token_url', 'The base URL for your INFOR API');
    register_setting('wpiai_options_group', 'wpiai_token_url');
    add_option('wpiai_username', 'The Username for your INFOR API');
    register_setting('wpiai_options_group', 'wpiai_username');
    add_option('wpiai_password', 'The Password for your INFOR API');
    register_setting('wpiai_options_group', 'wpiai_password');
    add_option('wpiai_client_id', 'The Client ID for your INFOR API');
    register_setting('wpiai_options_group', 'wpiai_client_id');
    add_option('wpiai_client_secret', 'The Client Secret for your INFOR API');
    register_setting('wpiai_options_group', 'wpiai_client_secret');
    add_option('wpiai_current_token', 'Current Access Token');
    register_setting('wpiai_options_group', 'wpiai_current_token');
    add_option('wpiai_token_refresh_period', 'Token Refresh Period');
    register_setting('wpiai_options_group', 'wpiai_token_refresh_period');
    add_option('wpiai_token_refresh_time', 'Token Refresh Period');
    register_setting('wpiai_options_group', 'wpiai_token_refresh_time');


    add_option('wpiai_message_test_url', 'Message URL');
    register_setting('wpiai_test_group', 'wpiai_message_test_url');
    add_option('wpiai_message_test_parameters', 'Message Parameters');
    register_setting('wpiai_test_group', 'wpiai_message_test_parameters');
    add_option('wpiai_message_test_xml', 'Message XML');
    register_setting('wpiai_test_group', 'wpiai_message_test_xml');

    add_option('wpiai_customer_url', 'API URL');
    register_setting('wpiai_customer_group', 'wpiai_customer_url');
    add_option('wpiai_customer_parameters', 'API Parameters');
    register_setting('wpiai_customer_group', 'wpiai_customer_parameters');
    add_option('wpiai_customer_xml', 'API XML');
    register_setting('wpiai_customer_group', 'wpiai_customer_xml');

    add_option('wpiai_sales_order_url', 'API URL');
    register_setting('wpiai_sales_order_group', 'wpiai_sales_order_url');
    add_option('wpiai_sales_order_parameters', 'API Parameters');
    register_setting('wpiai_sales_order_group', 'wpiai_sales_order_parameters');
    add_option('wpiai_sales_order_xml', 'API XML');
    register_setting('wpiai_sales_order_group', 'wpiai_sales_order_xml');

    add_option('wpiai_ship_to_url', 'API URL');
    register_setting('wpiai_ship_to_group', 'wpiai_ship_to_url');
    add_option('wpiai_ship_to_parameters', 'API Parameters');
    register_setting('wpiai_ship_to_group', 'wpiai_ship_to_parameters');
    add_option('wpiai_ship_to_xml', 'API XML');
    register_setting('wpiai_ship_to_group', 'wpiai_ship_to_xml');

    add_option('wpiai_contact_url', 'API URL');
    register_setting('wpiai_contact_group', 'wpiai_contact_url');
    add_option('wpiai_contact_parameters', 'API Parameters');
    register_setting('wpiai_contact_group', 'wpiai_contact_parameters');
    add_option('wpiai_contact_xml', 'API XML for Adding');
    register_setting('wpiai_contact_group', 'wpiai_contact_xml');
	add_option('wpiai_contact_xml_update', 'API XML for Updating');
	register_setting('wpiai_contact_group', 'wpiai_contact_xml_update');

    add_option('wpiai_guest_customer_number', 'Customer Number for Guest Orders');
    register_setting('wpiai_settings_group', 'wpiai_guest_customer_number');
    add_option('wpiai_default_warehouse', 'Default Warehouse for Orders');
    register_setting('wpiai_settings_group', 'wpiai_default_warehouse');
    add_option('wpiai_warehouse_ids');
    register_setting('wpiai_settings_group', 'wpiai_warehouse_ids');
    add_option('wpiai_warehouse_names');
    register_setting('wpiai_settings_group', 'wpiai_warehouse_names');

    add_option('wpiai_product_api_url', 'Product URL');
    register_setting('wpiai_products_group', 'wpiai_product_api_url');
    add_option('wpiai_product_api_request', 'Product Request');
    register_setting('wpiai_products_group', 'wpiai_product_api_request');

	add_option('wpiai_product_pricing_updates_api_url', 'https://mingle-ionapi.inforcloudsuite.com/ERFELECTRIC_TRN/APIFLOWS/productPricing/getPricingUpdates');
	register_setting('wpiai_product_pricing_updates_group', 'wpiai_product_pricing_updates_api_url');
	add_option('wpiai_product_pricing_updates_operator', 'BS1');
	register_setting('wpiai_product_pricing_updates_group', 'wpiai_product_pricing_updates_operator');
	add_option('wpiai_product_pricing_updates_restartRowId', '0');
	register_setting('wpiai_product_pricing_updates_group', 'wpiai_product_pricing_updates_restartRowId');
	add_option('wpiai_product_pricing_updates_lookbackExp', 'today - 10');
	register_setting('wpiai_product_pricing_updates_group', 'wpiai_product_pricing_updates_lookbackExp');
	add_option('wpiai_product_pricing_updates_ionapiRespStyle', 'sync');
	register_setting('wpiai_product_pricing_updates_group', 'wpiai_product_pricing_updates_ionapiRespStyle');

	add_option('wpiai_accounts_customer_balance_url', 'https://mingle-ionapi.inforcloudsuite.com/ERFELECTRIC_TRN/SX/web/sxapirestservice/sxapiARGetCustomerBalanceV2');
	register_setting('wpiai_accounts_settings_group', 'wpiai_accounts_customer_balance_url');
	add_option('wpiai_accounts_customer_data_credit_url', 'https://mingle-ionapi.inforcloudsuite.com/ERFELECTRIC_TRN/SX/web/sxapirestservice/sxapiARGetCustomerDataCredit');
	register_setting('wpiai_accounts_settings_group', 'wpiai_accounts_customer_data_credit_url');
	add_option('wpiai_accounts_request', 'API Request');
	register_setting('wpiai_accounts_settings_group', 'wpiai_accounts_request');

	add_option('wpiai_invoices_url', 'https://mingle-ionapi.inforcloudsuite.com/ERFELECTRIC_TRN/SX/web/sxapirestservice/sxapiargetinvoicelistv3');
	register_setting('wpiai_invoices_settings_group', 'wpiai_invoices_url');
	add_option('wpiai_invoices_request', 'API Request');
	register_setting('wpiai_invoices_settings_group', 'wpiai_invoices_request');

	add_option('wpiai_single_invoice_url', 'https://mingle-ionapi.inforcloudsuite.com/ERFELECTRIC_TRN/SX/web/sxapirestservice/Sxapioegetsingleorderv3');
	register_setting('wpiai_single_invoice_settings_group', 'wpiai_single_invoice_url');
	add_option('wpiai_single_invoice_request', 'API Request');
	register_setting('wpiai_single_invoice_settings_group', 'wpiai_single_invoice_request');
	add_option('wpiai_invoice_print_url', 'https://mingle-ionapi.inforcloudsuite.com/ERFELECTRIC_TRN/SX/web/sxapirestservice/sxapisasubmitreportv2');
	register_setting('wpiai_invoice_print_settings_group', 'wpiai_invoice_print_url');
	add_option('wpiai_invoice_print_request', 'API Request');
	register_setting('wpiai_invoice_print_settings_group', 'wpiai_invoice_print_request');

    add_option('wpiai_users_updated', '');

}

add_action('admin_init', 'wpiai_register_settings');

/**
 * Display Settings on Option’s Page
 */

function wpiai_options_page()
{
    if (isset($_GET['tab'])) {
        $active_tab = sanitize_text_field($_GET['tab']);
    } else {
        $active_tab = 'wpiai_security';
    }
    ?>
    <div class="wrap">
        <h2 class="nav-tab-wrapper">
            <a href="?page=infor-options&tab=wpiai_security"
               class="nav-tab <?php echo $active_tab == 'wpiai_security' ? 'nav-tab-active' : ''; ?>">Security</a>
            <a href="?page=infor-options&tab=wpiai_message_test"
               class="nav-tab <?php echo $active_tab == 'wpiai_message_test' ? 'nav-tab-active' : ''; ?>">Message
                Test</a>
            <a href="?page=infor-options&tab=wpiai_customer_record"
               class="nav-tab <?php echo $active_tab == 'wpiai_customer_record' ? 'nav-tab-active' : ''; ?>">Customer
                Master Record</a>
            <a href="?page=infor-options&tab=wpiai_sales_order_record"
               class="nav-tab <?php echo $active_tab == 'wpiai_sales_order_record' ? 'nav-tab-active' : ''; ?>">Sales
                Order Master Record</a>
            <a href="?page=infor-options&tab=wpiai_ship_to_record"
               class="nav-tab <?php echo $active_tab == 'wpiai_ship_to_record' ? 'nav-tab-active' : ''; ?>">Ship To
                Master Record</a>
            <a href="?page=infor-options&tab=wpiai_contact_record"
               class="nav-tab <?php echo $active_tab == 'wpiai_contact_record' ? 'nav-tab-active' : ''; ?>">Contact
                Master Record</a>
            <a href="?page=infor-options&tab=wpiai_settings"
               class="nav-tab <?php echo $active_tab == 'wpiai_settings' ? 'nav-tab-active' : ''; ?>">Settings</a>
            <a href="?page=infor-options&tab=wpiai_products_api"
               class="nav-tab <?php echo $active_tab == 'wpiai_products_api' ? 'nav-tab-active' : ''; ?>">Products API</a>
            <a href="?page=infor-options&tab=wpiai_product_updates_api"
               class="nav-tab <?php echo $active_tab == 'wpiai_product_updates_api' ? 'nav-tab-active' : ''; ?>">Product Updates</a>
            <a href="?page=infor-options&tab=wpiai_accounts_api"
               class="nav-tab <?php echo $active_tab == 'wpiai_accounts_api' ? 'nav-tab-active' : ''; ?>">Accounts API</a>
            <a href="?page=infor-options&tab=wpiai_invoices_api"
               class="nav-tab <?php echo $active_tab == 'wpiai_invoices_api' ? 'nav-tab-active' : ''; ?>">Invoices API</a>
            <a href="?page=infor-options&tab=wpiai_single_invoice_api"
               class="nav-tab <?php echo $active_tab == 'wpiai_single_invoice_api' ? 'nav-tab-active' : ''; ?>">Single Invoice API</a>
            <a href="?page=infor-options&tab=wpiai_invoice_print_api"
               class="nav-tab <?php echo $active_tab == 'wpiai_invoice_print_api' ? 'nav-tab-active' : ''; ?>">Print Invoice API</a>
            <?php
            $current_user = wp_get_current_user();
            $email = (string)$current_user->user_email;
            if ($email === 'edward@technicks.com'):?>
                <a href="?page=infor-options&tab=wpiai_restricted_settings"
                   class="nav-tab <?php echo $active_tab == 'wpiai_restricted_settings' ? 'nav-tab-active' : ''; ?>">Infor
                    Restricted Settings</a>
            <?php endif; ?>
        </h2>
        <style>
            .modal {
                display: none;
            }

            .modal.active {
                display: inline-block;
            }

            .modal img {
                max-height: 25px;
                width: auto;
            }

            input[type=text] {
                vertical-align: bottom;
            }

            pre {
                outline: 1px solid #ccc;
                padding: 5px;
                margin: 5px;
                white-space: pre-wrap; /* css-3 */
                white-space: -moz-pre-wrap; /* Mozilla, since 1999 */
                white-space: -o-pre-wrap; /* Opera 7 */
                word-wrap: break-word; /* Internet Explorer 5.5+ */
            }

            .string {
                color: green;
            }

            .number {
                color: darkorange;
            }

            .boolean {
                color: blue;
            }

            .null {
                color: magenta;
            }

            .key {
                color: red;
            }


        </style>
        <?php
        if ($active_tab == 'wpiai_security'):
            ?>

            <div class="wrap">
                <h1>INFOR Security</h1>
            </div>
            <form method="post" action="options.php">
                <?php settings_fields('wpiai_options_group'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="wpiai_api_enabled">API Enabled</label></th>
                        <td><input type="checkbox" id="wpiai_api_enabled" name="wpiai_api_enabled"
                                   value="1"<?php checked( 1 == get_option('wpiai_api_enabled') ); ?> class="regular-text"/></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_token_url">Token URL</label></th>
                        <td><input type="text" id="wpiai_token_url" name="wpiai_token_url"
                                   value="<?php echo get_option('wpiai_token_url'); ?>" class="regular-text"/></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_token_url">Username</label></th>
                        <td><input type="text" id="wpiai_username" name="wpiai_username"
                                   value="<?php echo get_option('wpiai_username'); ?>" class="regular-text"/></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_token_url">Password</label></th>
                        <td><input type="text" id="wpiai_password" name="wpiai_password"
                                   value="<?php echo get_option('wpiai_password'); ?>" class="regular-text"/></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_client_id">Client ID</label></th>
                        <td><input type="text" id="wpiai_client_id" name="wpiai_client_id"
                                   value="<?php echo get_option('wpiai_client_id'); ?>" class="regular-text"/></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_client_secret">Client Secret</label></th>
                        <td><input type="text" id="wpiai_client_secret" name="wpiai_client_secret"
                                   value="<?php echo get_option('wpiai_client_secret'); ?>" class="regular-text"/></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_token_refresh_period">Token Expires Every (seconds)</label></th>
                        <td><input type="text" id="wpiai_token_refresh_period" name="wpiai_token_refresh_period"
                                   value="<?php echo get_option('wpiai_token_refresh_period'); ?>" class="regular-text"/></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_current_token">Current Token</label></th>
                        <td><pre><?php echo get_option('wpiai_current_token'); ?></pre></td>
                    </tr>

                </table>
                <?php submit_button(); ?>

            </form>
            <table class="form-table">
                <tr>
                    <td colspan="2">
                        <button id="TestAuthentication" class="button">Test Authentication</button>&nbsp;
                        &nbsp;<span class="modal TestAuthentication"><img
                                    src="<?php echo wpiai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                    </td>
                </tr>
            </table>
            <div class="wrap">
            <pre id="ajax-response">

            </pre>
            </div>
        <?php
        endif;
        if ($active_tab == 'wpiai_message_test'):
            ?>
            <div class="wrap">
                <h1>INFOR Message Test</h1>
            </div>
            <form method="post" action="options.php">
                <?php settings_fields('wpiai_test_group'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="wpiai_message_test_url">URL</label></th>
                        <td><input type="text" id="wpiai_message_test_url" name="wpiai_message_test_url"
                                   value="<?php echo get_option('wpiai_message_test_url'); ?>" class="regular-text"
                                   style="width: 100%;"/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_message_test_parameters">Parameters</label></th>
                        <td><textarea id="wpiai_message_test_parameters" name="wpiai_message_test_parameters" rows="20"
                                      style="width: 100%;"><?php echo get_option('wpiai_message_test_parameters'); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_message_test_xml">XML</label></th>
                        <td><textarea id="wpiai_message_test_xml" name="wpiai_message_test_xml" rows="40"
                                      style="width: 100%;"><?php echo get_option('wpiai_message_test_xml'); ?></textarea>
                        </td>
                    </tr>

                </table>
                <?php submit_button(); ?>

            </form>
            <table class="form-table">
                <tr>
                    <td>
                        <button id="TestResponse" class="button">Test Response</button>&nbsp;
                        &nbsp;<span class="modal TestResponse"><img
                                    src="<?php echo wpiai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                        <button id="PingInfor" class="button">Ping Infor</button>&nbsp;
                        &nbsp;<span class="modal PingInfor"><img
                                    src="<?php echo wpiai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                    </td>
                    <td>

                    </td>
                </tr>
            </table>
            <div class="wrap">
            <pre id="ajax-response">

            </pre>
            </div>
        <?php
        endif;
        if ($active_tab == 'wpiai_customer_record'):
            ?>
            <div class="wrap">
                <h1>INFOR Customer Master Record</h1>
            </div>
            <form method="post" action="options.php">
                <?php settings_fields('wpiai_customer_group'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="wpiai_customer_url">URL</label></th>
                        <td><input type="text" id="wpiai_customer_url" name="wpiai_customer_url"
                                   value="<?php echo get_option('wpiai_customer_url'); ?>" class="regular-text"
                                   style="width: 100%;"/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_customer_parameters">Parameters</label></th>
                        <td><textarea id="wpiai_customer_parameters" name="wpiai_customer_parameters" rows="20"
                                      style="width: 100%;"><?php echo get_option('wpiai_customer_parameters'); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_customer_xml">XML</label></th>
                        <td><textarea id="wpiai_customer_xml" name="wpiai_customer_xml" rows="40"
                                      style="width: 100%;"><?php echo get_option('wpiai_customer_xml'); ?></textarea>
                        </td>
                    </tr>

                </table>
                <?php submit_button(); ?>

            </form>
            <table class="form-table">
                <tr>
                    <td>
                        <button id="customerRecordResponse" class="button">API Response</button>&nbsp;
                        &nbsp;<span class="modal customerRecordResponse"><img
                                    src="<?php echo wpiai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                        <button id="PingInfor" class="button">Ping Infor</button>&nbsp;
                        &nbsp;<span class="modal PingInfor"><img
                                    src="<?php echo wpiai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                        <button id="testCustomerXML" class="button">Generate XML for CustomerID4</button>&nbsp;
                        &nbsp;<span class="modal testCustomerXML"><img
                                    src="<?php echo wpiai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                        <button id="testCustomerParams" class="button">Generate Params</button>&nbsp;
                        &nbsp;<span class="modal testCustomerParams"><img
                                    src="<?php echo wpiai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                    </td>
                    <td>

                    </td>
                </tr>
            </table>
            <div class="wrap">
            <pre id="ajax-response">

            </pre>
            </div>
        <?php
        endif;
        if ($active_tab == 'wpiai_sales_order_record'):
            ?>
            <div class="wrap">
                <h1>INFOR Sales Order Master Record</h1>
            </div>
            <form method="post" action="options.php">
                <?php settings_fields('wpiai_sales_order_group'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="wpiai_sales_order_url">URL</label></th>
                        <td><input type="text" id="wpiai_sales_order_url" name="wpiai_sales_order_url"
                                   value="<?php echo get_option('wpiai_sales_order_url'); ?>" class="regular-text"
                                   style="width: 100%;"/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_sales_order_parameters">Parameters</label></th>
                        <td><textarea id="wpiai_sales_order_parameters" name="wpiai_sales_order_parameters" rows="20"
                                      style="width: 100%;"><?php echo get_option('wpiai_sales_order_parameters'); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_sales_order_xml">XML</label></th>
                        <td><textarea id="wpiai_sales_order_xml" name="wpiai_sales_order_xml" rows="40"
                                      style="width: 100%;"><?php echo get_option('wpiai_sales_order_xml'); ?></textarea>
                        </td>
                    </tr>

                </table>
                <?php submit_button(); ?>

            </form>
            <table class="form-table">
                <tr>
                    <td>
                        <button id="salesOrderRecordResponse" class="button">API Response</button>&nbsp;
                        &nbsp;<span class="modal salesOrderRecordResponse"><img
                                    src="<?php echo wpiai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                        <button id="PingInfor" class="button">Ping Infor</button>&nbsp;
                        &nbsp;<span class="modal PingInfor"><img
                                    src="<?php echo wpiai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                        <button id="testSalesOrderXML" class="button">Generate XML to Add a Sales Order</button>&nbsp;
                        &nbsp;<span class="modal testSalesOrderXML"><img
                                    src="<?php echo wpiai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                    </td>
                    <td>

                    </td>
                </tr>
            </table>

            <div class="wrap">
            <pre id="ajax-response">

            </pre>
            </div>
        <?php
        endif;
        if ($active_tab == 'wpiai_ship_to_record'):
            ?>
            <div class="wrap">
                <h1>INFOR Ship To Master Record</h1>
            </div>
            <form method="post" action="options.php">
                <?php settings_fields('wpiai_ship_to_group'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="wpiai_ship_to_url">URL</label></th>
                        <td><input type="text" id="wpiai_ship_to_url" name="wpiai_ship_to_url"
                                   value="<?php echo get_option('wpiai_ship_to_url'); ?>" class="regular-text"
                                   style="width: 100%;"/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_ship_to_parameters">Parameters</label></th>
                        <td><textarea id="wpiai_ship_to_parameters" name="wpiai_ship_to_parameters" rows="20"
                                      style="width: 100%;"><?php echo get_option('wpiai_ship_to_parameters'); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_ship_to_xml">XML</label></th>
                        <td><textarea id="wpiai_ship_to_xml" name="wpiai_ship_to_xml" rows="40"
                                      style="width: 100%;"><?php echo get_option('wpiai_ship_to_xml'); ?></textarea>
                        </td>
                    </tr>

                </table>
                <?php submit_button(); ?>

            </form>
            <table class="form-table">
                <tr>
                    <td>
                        <button id="shipToRecordResponse" class="button">API Response</button>&nbsp;
                        &nbsp;<span class="modal shipToRecordResponse"><img
                                    src="<?php echo wpiai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                        <button id="PingInfor" class="button">Ping Infor</button>&nbsp;
                        &nbsp;<span class="modal PingInfor"><img
                                    src="<?php echo wpiai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                        <button id="testShipToXML" class="button">Generate XML to Add a Ship To</button>&nbsp;
                        &nbsp;<span class="modal testShipToXML"><img
                                    src="<?php echo wpiai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                    </td>
                    <td>

                    </td>
                </tr>
            </table>

            <div class="wrap">
            <pre id="ajax-response">

            </pre>
            </div>
        <?php
        endif;
        if ($active_tab == 'wpiai_products_api'):
            ?>
        <div class="wrap">
            <h1>INFOR Products API</h1>
        </div>
        <form method="post" action="options.php">
            <?php settings_fields('wpiai_products_group'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="wpiai_product_api_url">URL</label></th>
                    <td><input type="text" id="wpiai_product_api_url" name="wpiai_product_api_url"
                               value="<?php echo get_option('wpiai_product_api_url'); ?>" class="regular-text"
                               style="width: 100%;"/>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="wpiai_product_api_request">Parameters</label></th>
                    <td><textarea id="wpiai_product_api_request" name="wpiai_product_api_request" rows="20"
                                  style="width: 100%;"><?php echo get_option('wpiai_product_api_request'); ?></textarea>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
            <table class="form-table">
                <tr>
                    <td>
                        <button id="productAPIResponse" class="button">API Response</button>&nbsp;
                        &nbsp;<span class="modal productAPIResponse"><img
                                    src="<?php echo wpiai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                    </td>
                    <td>
                        <button id="productDefaultPrices" class="button">Update all prices for Guest User</button>&nbsp;
                        &nbsp;<span class="modal productDefaultPrices"><img
                                    src="<?php echo wpiai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                    </td>
                </tr>
            </table>

            <div class="wrap">
            <pre id="ajax-response">

            </pre>
            </div>
        <?php
        endif;
        if ($active_tab == 'wpiai_product_updates_api'):
	        ?>
            <div class="wrap">
                <h1>INFOR Product Updates API</h1>
            </div>
            <form method="post" action="options.php">
		        <?php settings_fields('wpiai_product_pricing_updates_group'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="wpiai_product_pricing_updates_api_url">URL</label></th>
                        <td><input type="text" id="wpiai_product_pricing_updates_api_url" name="wpiai_product_pricing_updates_api_url"
                                   value="<?php echo get_option('wpiai_product_pricing_updates_api_url'); ?>" class="regular-text"
                                   style="width: 100%;"/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_product_pricing_updates_operator">Operator</label></th>
                        <td><input type="text" id="wpiai_product_pricing_updates_operator" name="wpiai_product_pricing_updates_operator"
                                   value="<?php echo get_option('wpiai_product_pricing_updates_operator'); ?>" class="regular-text"
                                   style="width: 100%;"/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_product_pricing_updates_restartRowId">Restart Row ID</label></th>
                        <td><input type="text" id="wpiai_product_pricing_updates_restartRowId" name="wpiai_product_pricing_updates_restartRowId"
                                   value="<?php echo get_option('wpiai_product_pricing_updates_restartRowId'); ?>" class="regular-text"
                                   style="width: 100%;"/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_product_pricing_updates_lookbackExp">Look Back To</label></th>
                        <td><input type="text" id="wpiai_product_pricing_updates_lookbackExp" name="wpiai_product_pricing_updates_lookbackExp"
                                   value="<?php echo get_option('wpiai_product_pricing_updates_lookbackExp'); ?>" class="regular-text"
                                   style="width: 100%;"/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_product_pricing_updates_ionapiRespStyle">ION Response Style</label></th>
                        <td><input type="text" id="wpiai_product_pricing_updates_ionapiRespStyle" name="wpiai_product_pricing_updates_ionapiRespStyle"
                                   value="<?php echo get_option('wpiai_product_pricing_updates_ionapiRespStyle'); ?>" class="regular-text"
                                   style="width: 100%;"/>
                        </td>
                    </tr>
                </table>
		        <?php submit_button(); ?>
            </form>
            <table class="form-table">
                <tr>
                    <td>
                        <button id="productUpdatesAPIResponse" class="button">API Response</button>&nbsp;
                        &nbsp;<span class="modal productUpdatesAPIResponse"><img
                                    src="<?php echo wpiai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                    </td>
                    <td>

                    </td>
                </tr>
            </table>

            <div class="wrap">
            <pre id="ajax-response">

            </pre>
            </div>
        <?php
        endif;
        if ($active_tab == 'wpiai_accounts_api'):
	        ?>
            <div class="wrap">
                <h1>INFOR Accounts API</h1>
            </div>
            <form method="post" action="options.php">
		        <?php settings_fields('wpiai_accounts_settings_group'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="wpiai_accounts_customer_balance_url">Customer Balances URL</label></th>
                        <td><input type="text" id="wpiai_accounts_customer_balance_url" name="wpiai_accounts_customer_balance_url"
                                   value="<?php echo get_option('wpiai_accounts_customer_balance_url'); ?>" class="regular-text"
                                   style="width: 100%;"/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_accounts_customer_data_credit_url">Customer Data Credit URL</label></th>
                        <td><input type="text" id="wpiai_accounts_customer_data_credit_url" name="wpiai_accounts_customer_data_credit_url"
                                   value="<?php echo get_option('wpiai_accounts_customer_data_credit_url'); ?>" class="regular-text"
                                   style="width: 100%;"/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_accounts_request">Parameters</label></th>
                        <td><textarea id="wpiai_accounts_request" name="wpiai_accounts_request" rows="20"
                                      style="width: 100%;"><?php echo get_option('wpiai_accounts_request'); ?></textarea>
                        </td>
                    </tr>
                </table>
		        <?php submit_button(); ?>
            </form>
            <table class="form-table">
                <!--<tr>
                    <th scope="row"><label for="customer_number">Customer Number</label></th>
                    <td><input type="text" id="customer_number" name="customer_number" value="" class="regular-text" style="width: 100%;"/></td>
                </tr>-->
                <tr>
                    <td>
                        <button id="accountsGetCustomerBalances" class="button">Get Customer Balances</button>&nbsp;
                        &nbsp;<span class="modal accountsGetCustomerBalances"><img
                                    src="<?php echo wpiai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                    </td>
                    <td>
                        <button id="accountsGetCustomerDataCredit" class="button">Get Customer Data Credit</button>&nbsp;
                        &nbsp;<span class="modal accountsGetCustomerDataCredit"><img
                                    src="<?php echo wpiai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                    </td>
                </tr>
            </table>

            <div class="wrap">
            <pre id="ajax-response">

            </pre>
            </div>
        <?php
        endif;
        if ($active_tab == 'wpiai_invoices_api'):
	        ?>
            <div class="wrap">
                <h1>INFOR Invoices API</h1>
            </div>
            <form method="post" action="options.php">
		        <?php settings_fields('wpiai_invoices_settings_group'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="wpiai_invoices_url">Invoices URL</label></th>
                        <td><input type="text" id="wpiai_invoices_url" name="wpiai_invoices_url"
                                   value="<?php echo get_option('wpiai_invoices_url'); ?>" class="regular-text"
                                   style="width: 100%;"/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_invoices_request">Parameters</label></th>
                        <td><textarea id="wpiai_invoices_request" name="wpiai_invoices_request" rows="20"
                                      style="width: 100%;"><?php echo get_option('wpiai_invoices_request'); ?></textarea>
                        </td>
                    </tr>
                </table>
		        <?php submit_button(); ?>
            </form>
            <table class="form-table">
                <tr>
                    <td>
                        <button id="invoicesAPIResponse" class="button">API Response</button>&nbsp;
                        &nbsp;<span class="modal invoicesAPIResponse"><img
                                    src="<?php echo wpiai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                    </td>
                    <td>

                    </td>
                </tr>
            </table>

            <div class="wrap">
            <pre id="ajax-response">

            </pre>
            </div>
        <?php
        endif;
        if ($active_tab == 'wpiai_single_invoice_api'):
	        ?>
            <div class="wrap">
                <h1>INFOR Single Invoice API</h1>
            </div>
            <form method="post" action="options.php">
		        <?php settings_fields('wpiai_single_invoice_settings_group'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="wpiai_single_invoice_url">Single Invoice URL</label></th>
                        <td><input type="text" id="wpiai_single_invoice_url" name="wpiai_single_invoice_url"
                                   value="<?php echo get_option('wpiai_single_invoice_url'); ?>" class="regular-text"
                                   style="width: 100%;"/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_single_invoice_request">Parameters</label></th>
                        <td><textarea id="wpiai_single_invoice_request" name="wpiai_single_invoice_request" rows="20"
                                      style="width: 100%;"><?php echo get_option('wpiai_single_invoice_request'); ?></textarea>
                        </td>
                    </tr>
                </table>
		        <?php submit_button(); ?>
            </form>
            <table class="form-table">
                <tr>
                    <td>
                        <button id="singleInvoiceAPIResponse" class="button">API Response</button>&nbsp;
                        &nbsp;<span class="modal singleInvoiceAPIResponse"><img
                                    src="<?php echo wpiai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                    </td>
                    <td>
                        <input type="text" id="wpiai_single_invoice_number"
                               value="13279664" class="regular-text"
                               style="width: 100%;"/>
                    </td>
                    <td>
                        <button id="singleInvoiceAPIResponseTest" class="button">Test</button>&nbsp;
                        &nbsp;<span class="modal singleInvoiceAPIResponseTest"><img
                                    src="<?php echo wpiai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                    </td>
                </tr>
            </table>

            <div class="wrap">
            <pre id="ajax-response">

            </pre>
            </div>
        <?php
        endif;
        if ($active_tab == 'wpiai_invoice_print_api'):
	        ?>
            <div class="wrap">
                <h1>INFOR Single Invoice API</h1>
            </div>
            <form method="post" action="options.php">
		        <?php settings_fields('wpiai_invoice_print_settings_group'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="wpiai_invoice_print_url">Print Invoice URL</label></th>
                        <td><input type="text" id="wpiai_invoice_print_url" name="wpiai_invoice_print_url"
                                   value="<?php echo get_option('wpiai_invoice_print_url'); ?>" class="regular-text"
                                   style="width: 100%;"/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_invoice_print_request">Parameters</label></th>
                        <td><textarea id="wpiai_invoice_print_request" name="wpiai_invoice_print_request" rows="20"
                                      style="width: 100%;"><?php echo get_option('wpiai_invoice_print_request'); ?></textarea>
                        </td>
                    </tr>
                </table>
		        <?php submit_button(); ?>
            </form>
            <table class="form-table">
                <tr>
                    <td>
                        <button id="singleInvoiceAPIResponse" class="button">API Response</button>&nbsp;
                        &nbsp;<span class="modal singleInvoiceAPIResponse"><img
                                    src="<?php echo wpiai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                    </td>

                </tr>
            </table>

            <div class="wrap">
            <pre id="ajax-response">

            </pre>
            </div>
        <?php
        endif;
        if ($active_tab == 'wpiai_contact_record'):
            ?>
            <div class="wrap">
                <h1>INFOR Contact Master Record</h1>
            </div>
            <form method="post" action="options.php">
                <?php settings_fields('wpiai_contact_group'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="wpiai_contact_url">URL</label></th>
                        <td><input type="text" id="wpiai_contact_url" name="wpiai_contact_url"
                                   value="<?php echo get_option('wpiai_contact_url'); ?>" class="regular-text"
                                   style="width: 100%;"/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_contact_parameters">Parameters</label></th>
                        <td><textarea id="wpiai_contact_parameters" name="wpiai_contact_parameters" rows="20"
                                      style="width: 100%;"><?php echo get_option('wpiai_contact_parameters'); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_contact_xml">XML</label></th>
                        <td><textarea id="wpiai_contact_xml" name="wpiai_contact_xml" rows="40"
                                      style="width: 100%;"><?php echo get_option('wpiai_contact_xml'); ?></textarea>
                        </td>
                    </tr>
                   <!-- <tr>
                        <th scope="row"><label for="wpiai_contact_xml_update">XML - used for updating a contact</label></th>
                        <td><textarea id="wpiai_contact_xml_update" name="wpiai_contact_xml_update" rows="40"
                                      style="width: 100%;"><?php /*echo get_option('wpiai_contact_xml_update'); */?></textarea>
                        </td>
                    </tr>-->


                </table>
                <?php submit_button(); ?>

            </form>
            <table class="form-table">
                <tr>
                    <td>
                        <button id="contactRecordResponse" class="button">API Response</button>&nbsp;
                        &nbsp;<span class="modal contactRecordResponse"><img
                                    src="<?php echo wpiai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                        <button id="PingInfor" class="button">Ping Infor</button>&nbsp;
                        &nbsp;<span class="modal PingInfor"><img
                                    src="<?php echo wpiai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                        <button id="testContactXML" class="button">Generate XML to Add a Contact</button>&nbsp;
                        &nbsp;<span class="modal testContactXML"><img
                                    src="<?php echo wpiai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                    </td>
                    <td>

                    </td>
                </tr>
            </table>

            <div class="wrap">
            <pre id="ajax-response">

            </pre>
            </div>
        <?php
        endif;
        if ($active_tab == 'wpiai_settings'):
            //wpiai_guest_customer_number
            //wpiai_default_warehouse
            ?>
            <div class="wrap">
                <h1>INFOR Settings</h1>
            </div>
            <form method="post" action="options.php" id="inforSettinsForm">
                <?php settings_fields('wpiai_settings_group'); ?>
                <table class="form-table">
                    <tr>
                        <th colspan="2">Sales Order Options
                            <hr>
                        </th>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_guest_customer_number">Guest Customer Number</label></th>
                        <td><input type="text" id="wpiai_guest_customer_number" name="wpiai_guest_customer_number"
                                   value="<?php echo get_option('wpiai_guest_customer_number'); ?>"
                                   class="regular-text"/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_default_warehouse">Default Warehouse</label></th>
                        <td><input type="text" id="wpiai_default_warehouse" name="wpiai_default_warehouse"
                                   value="<?php echo get_option('wpiai_default_warehouse'); ?>" class="regular-text"/>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2">
                            <hr>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="2">Warehouses
                            <hr>
                        </th>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <?php
                            $warehouseNames = get_option('wpiai_warehouse_names');
                            $warehouseIDs = get_option('wpiai_warehouse_ids');
                            $warehouses = array();
                            $i = 0;
                            if($warehouseIDs) {
                                foreach ($warehouseIDs as $warehouseID) {
                                    $thisWarehouse = array(
                                        "id" => $warehouseID,
                                        "name" => $warehouseNames[$i]
                                    );
                                    $warehouses[] = $thisWarehouse;
                                    $i++;
                                }
                            }
                            ?>
                            <script type="text/javascript">
                                jQuery(document).ready(function ($) {
                                    $('#warehouse-add-row').on('click', function () {
                                        var row = $('.warehouse-blank-row .repeater-row').clone(true);
                                        $('#warehouse-repeatable-fieldset-one').append(row);
                                        $('#warehouse-repeatable-fieldset-one *').prop("disabled", false);
                                        return false;
                                    });

                                    $('.remove-row').on('click', function () {
                                        $(this).parents('tr.repeater-row').remove();
                                        //$(this).parents('tr.repeater-row').css('outline', '1px solid red');
                                        return false;
                                    });

                                });
                            </script>
                            <table id="warehouse-repeatable-fieldset-one" width="100%">

                                <tbody>
                                <?php

                                if ($warehouses) :

                                    foreach ($warehouses as $field) {
                                        ?>
                                        <tr class="repeater-row">
                                            <td>

                                                <table class="form-table">
                                                    <tr>
                                                        <th>
                                                            <label for="wpiai_warehouse_ids[]">Warehouse ID:</label>
                                                        </th>
                                                        <td>
                                                            <input type="text" class="regular-text"
                                                                   name="wpiai_warehouse_ids[]"
                                                                   value="<?php if ($field['id'] != '') {
                                                                       echo esc_attr($field['id']);
                                                                   } ?>"/>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            <label for="wpiai_warehouse_names[]">Warehouse Name:</label>
                                                        </th>
                                                        <td>
                                                            <input type="text" class="regular-text"
                                                                   name="wpiai_warehouse_names[]"
                                                                   value="<?php if ($field['name'] != '') {
                                                                       echo esc_attr($field['name']);
                                                                   } ?>"/>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2"><br><a class="button remove-row" href="#">Remove
                                                                Warehouse</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <hr>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <?php
                                    }

                                endif; ?>


                                </tbody>
                            </table>
                            <!-- empty hidden one for jQuery -->
                            <table class="warehouse-blank-row" style="display: none;">
                                <tr class="repeater-row">
                                    <td>
                                        <table class="form-table">
                                            <tr>
                                                <th>
                                                    <label for="wpiai_warehouse_ids[]">Warehouse ID:</label>
                                                </th>
                                                <td>
                                                    <input disabled type="text" class="regular-text"
                                                           name="wpiai_warehouse_ids[]" value=""/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <label for="wpiai_warehouse_names[]">Warehouse Name:</label>
                                                </th>
                                                <td>
                                                    <input disabled type="text" class="regular-text"
                                                           name="wpiai_warehouse_names[]" value=""/>
                                                </td>
                                            </tr>


                                            <tr>
                                                <td colspan="2"><br><a class="button remove-row" href="#">Remove
                                                        Warehouse</a></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <hr>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <p><a id="warehouse-add-row" class="button" href="#">Add Warehouse</a></p>
                        </td>
                    </tr>

                </table>
                <?php submit_button(); ?>

            </form>
        <?php
        endif;
        if ($active_tab == 'wpiai_restricted_settings'):
            ?>
            <div class="wrap">
                <h1>INFOR Restricted</h1>
            </div>
        <?php
        endif;

        ?>
    </div>
    <?php
}

/**
 * Save the Warehouses
 */

function wpiai_warehousese_after_save($old_value, $new_value)
{

    error_log('Warehouses saved');

}

add_action('update_option_wpiai_warehouses', 'wpiai_warehousese_after_save', 10, 2);