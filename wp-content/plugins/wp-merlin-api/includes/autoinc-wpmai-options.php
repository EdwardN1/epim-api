<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Creating an Options Page
 */


function wpmai_register_options_page()
{
    add_menu_page(__('Merlin Options'), __('Merlin Options'), 'manage_options', 'merlin-options', 'wpmai_options_page', plugins_url('assets/img/merlin-logo.png', __DIR__), 2);
}

/**
 * Register the options for the plugin
 */

add_action('admin_menu', 'wpmai_register_options_page');

function wpmai_register_settings()
{
    add_option('wpmai_url', 'The base URL for your Merlin API');
    register_setting('wpmai_options_group', 'wpmai_url');
    add_option('wpmai_datasource', 'The Datasource for your Merlin API');
    register_setting('wpmai_options_group', 'wpmai_datasource');
}

add_action('admin_init', 'wpmai_register_settings');

/**
 * Display the Plugin Pages
 */

function wpmai_options_page()
{
    if (isset($_GET['tab'])) {
        $active_tab = sanitize_text_field($_GET['tab']);
    } else {
        $active_tab = 'wpmai_import';
    }
    ?>
    <div class="wrap">
    <h2 class="nav-tab-wrapper">
        <a href="?page=merlin-options&tab=wpmai_import"
           class="nav-tab <?php echo $active_tab == 'wpmai_import' ? 'nav-tab-active' : ''; ?>">Import</a>
        <a href="?page=merlin-options&tab=wpmai_settings"
           class="nav-tab <?php echo $active_tab == 'wpmai_settings' ? 'nav-tab-active' : ''; ?>">Settings</a>
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
    if ($active_tab == 'wpmai_import'):
        ?>
        <div class="wrap">
            <h1>Merlin Import</h1>
        </div>
        <table class="form-table">
            <tr>
               <th><label for="sku">Update a product code (SKU):</label></th>
                   <td>
                       <input type="text" id="sku" class="regular-text">&nbsp;<button id="UpdateCode" class="button">Update</button>
                       &nbsp; &nbsp;<span class="modal UpdateCode"><img src="<?php echo wpmai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                   </td>
               </tr>
            <tr>
                <td colspan="2">
                <hr>
                    <button id="MerlinImport" class="button">Import All Stock Prices and Quantities</button>&nbsp;
                    &nbsp;<span class="modal MerlinImport"><img
                                src="<?php echo wpmai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                </td>
            </tr>
        </table>
        <div class="wrap">
            <pre id="ajax-response" lang="xml">

            </pre>
        </div>
    <?php
    endif;
    if ($active_tab == 'wpmai_settings'):
        ?>

        <div class="wrap">
            <h1>Merlin Settings</h1>
        </div>
        <form method="post" action="options.php">
            <?php settings_fields('wpmai_options_group'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="wpmai_url">Merlin Base URL</label></th>
                    <td><input type="text" id="wpmai_url" name="wpmai_url"
                               value="<?php echo get_option('wpmai_url'); ?>" class="regular-text"/></td>
                </tr>
                <tr>
                    <th scope="row"><label for="wpmai_datasource">Merlin Datasource</label></th>
                    <td><input type="text" id="wpmai_datasource" name="wpmai_datasource"
                               value="<?php echo get_option('wpmai_datasource'); ?>" class="regular-text"/></td>
                </tr>
            </table>
            <?php submit_button(); ?>

        </form>
        <table class="form-table">
            <tr>
                <td colspan="2">
                    <button id="CheckStatus" class="button">Check Status</button>&nbsp;
                    &nbsp;<span class="modal CheckStatus"><img
                                src="<?php echo wpmai_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                </td>
            </tr>
        </table>
        <div class="wrap">
            <textarea id="ajax-response" class="widefat" cols="80" rows="40"></textarea>
        </div>
    <?php
    endif;
}

