<?php


if (!defined('ABSPATH')) {
    exit;
}

/**
 * Creating an Options Page
 */


function wpmace_register_options_page() {
    add_menu_page(__('Mace Options'), __('Mace Options'), 'manage_options', 'mace-options', 'wpmace_options_page', plugins_url('assets/img/mace-logo.png', __DIR__), 2);
}

add_action('admin_menu', 'wpmace_register_options_page');

/**
 * Register Settings For Plugin
 */
function wpmace_register_settings() {
    add_option('wpmace_request_products_api_url', 'Request Products URL');
    register_setting('wpmace_products_group', 'wpmace_request_products_api_url');
    add_option('wpmace_request_products_api_body', 'Request Products Body');
    register_setting('wpmace_products_group', 'wpmace_request_products_api_body');

}

add_action('admin_init', 'wpmace_register_settings');

/**
 * Display Settings on Option’s Page
 */

function wpmace_options_page()
{
    if (isset($_GET['tab'])) {
        $active_tab = sanitize_text_field($_GET['tab']);
    } else {
        $active_tab = 'mace_actions';
    }
    ?>
    <div class="wrap">
        <h2 class="nav-tab-wrapper">
            <a href="?page=mace-options&tab=mace_actions"
               class="nav-tab <?php echo $active_tab == 'mace_actions' ? 'nav-tab-active' : ''; ?>">Actions</a>
            <a href="?page=mace-options&tab=mace_request_products"
               class="nav-tab <?php echo $active_tab == 'mace_request_products' ? 'nav-tab-active' : ''; ?>">Request Products</a>

            <?php
            /*$current_user = wp_get_current_user();
            $email = (string)$current_user->user_email;
            if ($email === 'edward@technicks.com'):*/?><!--
                <a href="?page=infor-options&tab=wpiai_restricted_settings"
                   class="nav-tab <?php /*echo $active_tab == 'wpiai_restricted_settings' ? 'nav-tab-active' : ''; */?>">Infor
                    Restricted Settings</a>-->
            <?php //endif; ?>
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
        if ($active_tab == 'mace_actions'):
            ?>

            <div class="wrap">
                <h1>Mace Actions</h1>
            </div>
            <form method="post" action="options.php">
                <?php settings_fields('wpmace_actions_group'); ?>
                <table class="form-table">


                </table>
                <?php submit_button(); ?>

            </form>
            <table class="form-table">
                <tr>
                    <td colspan="2">
                        <button id="Test" class="button">Test</button>&nbsp;
                        &nbsp;<span class="modal Test"><img
                                src="<?php echo wpmace_PLUGINPATH; ?>/assets/img/FhHRx.gif"></span>
                    </td>
                </tr>
            </table>
            <div class="wrap">
            <pre id="ajax-response">

            </pre>
            </div>
        <?php
        endif;
        if ($active_tab == 'mace_request_products'):
            ?>
            <div class="wrap">
                <h1>Mace Request Products</h1>
            </div>
            <form method="post" action="options.php">
                <?php settings_fields('wpmace_products_group'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="wpmace_request_products_api_url">URL</label></th>
                        <td><input type="text" id="wpmace_request_products_api_url" name="wpmace_request_products_api_url"
                                   value="<?php echo get_option('wpmace_request_products_api_url'); ?>" class="regular-text"
                                   style="width: 100%;"/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpmace_request_products_api_body">Request Body</label></th>
                        <td><textarea id="wpmace_request_products_api_body" name="wpmace_request_products_api_body" rows="20"
                                      style="width: 100%;"><?php echo get_option('wpmace_request_products_api_body'); ?></textarea>
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
                                src="<?php echo wpmace_PLUGINPATH; ?>/assets/img/FhHRx.gif"></span>
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


        ?>
    </div>
    <?php
}