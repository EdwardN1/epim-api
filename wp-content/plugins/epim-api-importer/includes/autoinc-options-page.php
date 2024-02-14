<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Creating an Options Page
 */

function wpb_new_product_tab_content()
{
    // The new tab content
    echo 'Discount';
    echo 'Here\'s your new discount product tab.';
}

function epim_register_options_page()
{
    //Add to settings menu
    //add_options_page('Page Title', 'Plugin Menu', 'manage_options', 'myplugin', 'myplugin_options_page');
    // Add to admin_menu function
    add_menu_page(__('ePim Menu'), __('ePim'), 'manage_options', 'epim', 'epim_options_page', plugins_url('assets/img/epim-logo.png', __DIR__), 2);

}

add_action('admin_menu', 'epim_register_options_page');

/**
 * Register Settings For Plugin
 */

function epim_register_settings()
{
    add_option('epim_url', 'The base URL for your ePim API');
    register_setting('epim_options_group', 'epim_url');
    add_option('epim_key', 'The Subscription Key for your ePim API');
    register_setting('epim_options_group', 'epim_key');
    add_option('epim_api_retrieval_method', 'API Retrieval Method');
    register_setting('epim_options_group', 'epim_api_retrieval_method');
    add_option('epim_no_price_or_stocks', '1');
    register_setting('epim_options_group', 'epim_no_price_or_stocks');
    add_option('epim_always_include_epim_attributes', '1');
    register_setting('epim_options_group', 'epim_always_include_epim_attributes');
    add_option('epim_exclude_luckins_data', '1');
    register_setting('epim_options_group', 'epim_exclude_luckins_data');
    add_option('epim_prioritise_epim_images', '1');
    register_setting('epim_options_group', 'epim_prioritise_epim_images');
    add_option('epim_background_updates_max_run_time', '23');
    register_setting('epim_options_group', 'epim_background_updates_max_run_time');
    add_option('epim_use_dynamic_data_sheets', '0');
    register_setting('epim_options_group', 'epim_use_dynamic_data_sheets');
    add_option('epim_dynamic_data_sheets_url', '');
    register_setting('epim_options_group', 'epim_dynamic_data_sheets_url');
    add_option('epim_dynamic_data_sheets_templates', '');
    register_setting('epim_options_group', 'epim_dynamic_data_sheets_templates');
    add_option('epim_dynamic_data_sheets_names', '');
    register_setting('epim_options_group', 'epim_dynamic_data_sheets_names');
    add_option('epim_dynamic_data_sheets_tab_name', 'Data Sheets');
    register_setting('epim_options_group', 'epim_dynamic_data_sheets_tab_name');

    add_option('epim_enable_scheduled_updates', '0');
    register_setting('epim_schedule_options_group', 'epim_enable_scheduled_updates');
    add_option('epim_update_schedule', 'daily');
    register_setting('epim_schedule_options_group', 'epim_update_schedule');
    add_option('epim_schedule_log', '');
    register_setting('epim_schedule_options_group', 'epim_schedule_log');

    add_option('epim_divi_primary_color', '');
    register_setting('epim_divi_options_group', 'epim_divi_primary_color');
    add_option('epim_divi_secondary_color', '');
    register_setting('epim_divi_options_group', 'epim_divi_secondary_color');
    add_option('epim_divi_number_menu_items', '');
    register_setting('epim_divi_options_group', 'epim_divi_number_menu_items', '10');
    add_option('epim_use_qty_price_breaks', '0');
    register_setting('epim_divi_options_group', 'epim_use_qty_price_breaks');

    add_option('epim_tabs_advanced', '0');
    register_setting('epim_restricted_options_group', 'epim_tabs_advanced');
    add_option('epim_tabs_settings', '0');
    register_setting('epim_restricted_options_group', 'epim_tabs_settings');
    add_option('epim_tabs_schedule', '0');
    register_setting('epim_restricted_options_group', 'epim_tabs_schedule');
    add_option('epim_tabs_divi', '0');
    register_setting('epim_restricted_options_group', 'epim_tabs_divi');

    add_option('_epim_update_running', '');
    add_option('_epim_background_process_data', '');
    add_option('_epim_background_last_process_data', '');
    add_option('_epim_background_current_index', 0);
}

add_action('admin_init', 'epim_register_settings');

/**
 * Display Settings on Option’s Page
 */

function epim_options_page()
{
    global $is_divi;
    ?>
    <div class="wrap">
        <?php screen_icon(); ?>
        <?php
        if (isset($_GET['tab'])) {
            $active_tab = sanitize_text_field($_GET['tab']);
        } else {
            $active_tab = 'epim_dashboard';
        }
        ?>
        <h2 class="nav-tab-wrapper">
            <?php settings_fields('epim_restricted_options_group'); ?>

            <a href="?page=epim&tab=epim_dashboard"
               class="nav-tab <?php echo $active_tab == 'epim_dashboard' ? 'nav-tab-active' : ''; ?>">Dashboard</a>

            <?php $epim_tabs_advanced = get_option('epim_tabs_advanced'); ?>
            <?php $epim_tabs_advanced_show = false; ?>
            <?php if (is_array($epim_tabs_advanced)) {
                if ($epim_tabs_advanced['checkbox_value'] == '1') {
                    $epim_tabs_advanced_show = true;
                }
            } ?>
            <?php if ($epim_tabs_advanced_show): ?>
                <a href="?page=epim&tab=epim_management"
                   class="nav-tab <?php echo $active_tab == 'epim_management' ? 'nav-tab-active' : ''; ?>">Advanced</a>
            <?php endif; ?>

            <?php $epim_tabs_settings = get_option('epim_tabs_settings'); ?>
            <?php $epim_tabs_settings_show = false; ?>
            <?php if (is_array($epim_tabs_settings)) {
                if ($epim_tabs_settings['checkbox_value'] == '1') {
                    $epim_tabs_settings_show = true;
                }
            } ?>
            <?php if ($epim_tabs_settings_show): ?>
                <a href="?page=epim&tab=epim_settings"
                   class="nav-tab <?php echo $active_tab == 'epim_settings' ? 'nav-tab-active' : ''; ?>">Settings</a>
            <?php endif; ?>

            <?php $epim_tabs_schedule = get_option('epim_tabs_schedule'); ?>
            <?php $epim_tabs_schedule_show = false; ?>
            <?php if (is_array($epim_tabs_schedule)) {
                if ($epim_tabs_schedule['checkbox_value'] == '1') {
                    $epim_tabs_schedule_show = true;
                }
            } ?>
            <?php if ($epim_tabs_schedule_show): ?>
                <a href="?page=epim&tab=epim_updates"
                   class="nav-tab <?php echo $active_tab == 'epim_updates' ? 'nav-tab-active' : ''; ?>">Schedules</a>
            <?php endif; ?>

            <?php $epim_tabs_divi = get_option('epim_tabs_divi'); ?>
            <?php $epim_tabs_divi_show = false; ?>
            <?php if (is_array($epim_tabs_schedule)) {
                if ($epim_tabs_divi['checkbox_value'] == '1') {
                    $epim_tabs_divi_show = true;
                }
            } ?>
            <?php if ($epim_tabs_divi_show): ?>
                <a href="?page=epim&tab=epim_divi_settings"
                   class="nav-tab <?php echo $active_tab == 'epim_divi_settings' ? 'nav-tab-active' : ''; ?>">Divi</a>
            <?php endif; ?>

            <?php
            $current_user = wp_get_current_user();
            $email = (string)$current_user->user_email;
            $restrictedTab = $email === 'edward@technicks.com';
            if (!$restrictedTab) {
                $length = strlen('@ng15.co.uk');
                $restrictedTab = substr($email, -$length) === '@ng15.co.uk';
            }
            if ($restrictedTab):?>
                <a href="?page=epim&tab=epim_restricted_settings"
                   class="nav-tab <?php echo $active_tab == 'epim_restricted_settings' ? 'nav-tab-active' : ''; ?>">Restricted
                    Settings</a>
            <?php endif; ?>

        </h2>

        <?php if ($active_tab == 'epim_dashboard'): ?>
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

            </style>
            <div class="wrap">
                <h1>ePim Dashboard</h1>
                <div><p>Start, stop or view an API update performed on the server.</p></div>
                <style>
                    table.form-table td, table td * {
                        vertical-align: top;
                    }
                </style>
                <table class="form-table" style="max-width: 750px;">
                    <tr>
                        <td>
                            <button id="GetCurrentUpdateData" class="button">Get Status</button>&nbsp;
                            &nbsp;<span class="modal GetCurrentUpdateData"><img
                                        src="<?php echo epimaapi_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                        </td>
                        <td>
                            <div id="ePimResult">

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button id="StopCurrentUpdate" class="button">Stop Current Update</button>&nbsp;
                            &nbsp;<span class="modal StopCurrentUpdate"><img
                                        src="<?php echo epimaapi_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                        </td>
                        <td>
                            NB Stops and Cancels Current Background Update.
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button id="BackgroundUpdateAll" class="button">Update all</button>&nbsp;
                            &nbsp;<span class="modal BackgroundUpdateAll"><img
                                        src="<?php echo epimaapi_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                        </td>
                        <td>
                            NB restarts current background import if one is active.
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-bottom: 0;">
                            <label for="start_date" style="font-weight: bold;">Update by product changed since:</label>

                        </td>
                        <td style="padding-bottom: 0;">NB Only updates products which have already been imported.</td>

                    </tr>
                    <tr>
                        <td><input type="text" class="custom_date" name="start_date" id="start_date" value=""/></td>
                        <td>
                            <button id="BackgroundUpdateSince" class="button">Update</button>&nbsp; &nbsp;
                            <span class="modal BackgroundUpdateSince"><img
                                        src="<?php echo epimaapi_PLUGINURI; ?>/assets/img/FhHRx.gif"></span></td>
                    </tr>
                    <tr>
                        <td>
                            <button id="BackgroundUnfreezeQueue" class="button">Unfreeze Queue</button>&nbsp;
                            &nbsp;<span class="modal BackgroundUnfreezeQueue"><img
                                        src="<?php echo epimaapi_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                        </td>
                        <td>
                            NB attempts to unfreeze the queue by reloading product data from the API, starting at the
                            current background index.
                        </td>
                    </tr>
                </table>

                <h3>Activity on the server:</h3>
                <div>
                    <hr>
                </div>
                <script type="text/javascript"
                        src="https://creativecouple.github.io/jquery-timing/jquery-timing.min.js"></script>
                <style>
                    #ePimTail {
                        width: 80%;
                        height: 65vh;
                        overflow-y: scroll;
                    }
                </style>
                <div id="ePimTail">

                </div>
            </div>
        <?php endif; ?>

        <?php if ($active_tab == 'epim_divi_settings'): ?>
            <div class="wrap">
                <h1>Divi Options</h1>
                <form method="post" action="options.php">
                    <?php settings_fields('epim_divi_options_group'); ?>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><label for="epim_divi_primary_color">Primary Colour</label></th>
                            <td><input type="text" id="epim_divi_primary_color" name="epim_divi_primary_color"
                                       value="<?php echo get_option('epim_divi_primary_color'); ?>"
                                       class="ir"/></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="epim_divi_secondary_color">Secondary Colour</label></th>
                            <td><input type="text" id="epim_divi_secondary_color" name="epim_divi_secondary_color"
                                       value="<?php echo get_option('epim_divi_secondary_color'); ?>"
                                       class="ir"/></td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <button id="WriteDiviCss" class="button">Set these colours live</button>&nbsp;
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <hr/>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row" colspan="2"><label for="epim_divi_secondary_color">Category Menu</label>
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <label for="epim_divi_number_menu_items">Number of top level menu items to
                                    create:</label><br>
                                <input type="text" id="epim_divi_number_menu_items" name="epim_divi_number_menu_items"
                                       value="<?php echo get_option('epim_divi_number_menu_items'); ?>"
                                       class="regular-text"/>
                            </td>
                            <td>
                                <button id="BuildDiviCategoryMenu" class="button">Build Now</button>&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <hr/>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="epim_use_qty_price_breaks">Use ePim Quantity Price
                                    Breaks</label>
                            </th>
                            <?php $options = get_option('epim_use_qty_price_breaks'); ?>
                            <td>
                                <input type="checkbox" id="epim_use_qty_price_breaks"
                                       name="epim_use_qty_price_breaks[checkbox_value]"
                                       value="1" <?php if (is_array($options)) {
                                    echo checked('1', $options['checkbox_value'], false);
                                } ?>/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <hr/>
                            </td>
                        </tr>

                    </table>
                    <?php submit_button(); ?>
                </form>
            </div>
            <script>
                jQuery(document).ready(function ($) {
                    $('.ir').wpColorPicker();
                });
            </script>
        <?php endif; ?>

        <?php if ($active_tab == 'epim_management'): ?>
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

            </style>
            <div class="wrap">
                <h1>ePim Advanced Functions</h1>

                <p>These functions do not run on the server and are not intended for day to day use. They are for
                    diagnostic and setup purposes only.</p>

                <table class="form-table">
                    <tr>
                        <th><label for="pCode">Update by product code (SKU):</label></th>
                        <td>
                            <input type="text" id="pCode" class="regular-text">&nbsp;<button id="UpdateCode"
                                                                                             class="button">Update
                            </button>&nbsp; &nbsp;<span class="modal UpdateCode"><img
                                        src="<?php echo epimaapi_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding-left: 0; padding-top: 0;">This will only update existing
                            products. If you have added new products in ePim then you need to Create them using either
                            of the
                            two options below first.
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 250px;"><label for="start_date">Update by product changed since:</label></th>
                        <td><input type="text" class="custom_date" name="start_date" id="start_date" value=""/>&nbsp;<button
                                    id="UpdateSince" class="button">Update
                            </button>&nbsp; &nbsp;<span class="modal UpdateSince"><img
                                        src="<?php echo epimaapi_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding-left: 0; padding-top: 0;">NB if you have added new Categories in
                            ePim, Create and Update those first as per below.
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button id="CreateCategories" class="button">Create and Update Categories</button>&nbsp;
                            &nbsp;<span class="modal CreateCategories"><img
                                        src="<?php echo epimaapi_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding-left: 0; padding-top: 0;">Creates and Updates All Categories. You
                            should run this before the above 2 options if you have added or updated any category
                            information.
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button id="CreateAllProducts" class="button">Create and Update all Products</button>&nbsp;
                            &nbsp;<span class="modal CreateAllProducts"><img
                                        src="<?php echo epimaapi_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding-left: 0; padding-top: 0;">NB if you have added new Categories in
                            ePim, Create and Update those first as per above. Updates and creates all products. If you
                            have a lot of products this will take a long time to complete.
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button id="CreateAll" class="button">Create and Update all Categories and Products</button>&nbsp;
                            &nbsp;<span class="modal CreateAll"><img
                                        src="<?php echo epimaapi_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding-left: 0; padding-top: 0;">Updates and creates all categories and
                            all products. If you have a lot of products this will take a long time to complete.
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button id="DeletedStock" class="button">Check for Deleted Stock</button>&nbsp;
                            &nbsp;<span class="modal DeletedStock"><img
                                        src="<?php echo epimaapi_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding-left: 0; padding-top: 0;">Checks for Deleted Products and
                            removes them from WooCommerce.
                            <hr>
                        </td>
                    </tr>
                    <?php
                    if (is_plugin_active('click-collect/click-collect.php')) {
                        ?>
                        <tr>
                            <td colspan="2">
                                <button id="CreateBranches" class="button">Create and Update Branches</button>&nbsp;
                                &nbsp;<span class="modal CreateBranches"><img
                                            src="<?php echo epimaapi_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding-left: 0; padding-top: 0;">Updates and creates all Branches.
                                <hr>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button id="UpdateBranchStock" class="button">Update Branch Stock Levels</button>&nbsp;
                                &nbsp;<span class="modal UpdateBranchStock"><img
                                            src="<?php echo epimaapi_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding-left: 0; padding-top: 0;">Updates Branch Stock Levels - Only
                                updates imported product stock levels - does not import products.
                                <hr>
                            </td>
                        </tr>

                        <?php
                    } else {
                        ?>
                        <tr>
                            <td colspan="2">
                                <button id="CreateBranches" class="button">Create and Update Branches</button>&nbsp;
                                &nbsp;<span class="modal CreateBranches"><img
                                            src="<?php echo epimaapi_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding-left: 0; padding-top: 0;">Updates and creates all Branches.
                                <hr>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button id="UpdateBranchStock" class="button">Update Branch Stock Levels</button>&nbsp;
                                &nbsp;<span class="modal UpdateBranchStock"><img
                                            src="<?php echo epimaapi_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding-left: 0; padding-top: 0;">Updates Branch Stock Levels - Only
                                updates imported product stock levels - does not import products.
                                <hr>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>

                </table>

                <div id="ePimResult">

                </div>
            </div>
        <?php endif; ?>
        <?php if ($active_tab == 'epim_settings'): ?>
            <h1>ePim Settings</h1>
            <form method="post" action="options.php">
                <?php settings_fields('epim_options_group'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="epim_url">base URL</label></th>
                        <td><input type="text" id="epim_url" name="epim_url"
                                   value="<?php echo get_option('epim_url'); ?>" class="regular-text"/></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="epim_key">Subscription Key</label></th>
                        <td><input type="text" id="epim_key" name="epim_key"
                                   value="<?php echo get_option('epim_key'); ?>" class="regular-text"/></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="epim_api_retrieval_method">API Retrieval Method</label></th>
                        <td>
                            <select name="epim_api_retrieval_method" id="epim_api_retrieval_method">
                                <option value="file_get_contents" <?php if (get_option('epim_api_retrieval_method') == 'file_get_contents') {
                                    echo 'selected';
                                } ?>>wp_remote_get
                                </option>
                                <?php if (function_exists('curl_init')): ?>
                                    <option value="curl" <?php if (get_option('epim_api_retrieval_method') == 'curl') {
                                        echo 'selected';
                                    } ?>>cUrl
                                    </option>
                                <?php endif; ?>

                            </select>

                    </tr>
                    <tr>
                        <th scope="row"><label for="epim_no_price_or_stock">Do Not Import Branch Stock or Price</label>
                        </th>
                        <?php $options = get_option('epim_no_price_or_stocks'); ?>
                        <td>
                            <input type="checkbox" id="epim_no_price_or_stocks"
                                   name="epim_no_price_or_stocks[checkbox_value]"
                                   value="1" <?php if (is_array($options)) {
                                echo checked('1', $options['checkbox_value'], false);
                            } ?>/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="epim_always_include_epim_attributes">Always Include EPIM
                                Attributes</label></th>
                        <?php $options = get_option('epim_always_include_epim_attributes'); ?>
                        <td>
                            <input type="checkbox" id="epim_always_include_epim_attributes"
                                   name="epim_always_include_epim_attributes[checkbox_value]"
                                   value="1" <?php if (is_array($options)) {
                                echo checked('1', $options['checkbox_value'], false);
                            } ?>/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="epim_exclude_luckins_data">Exclude Luckins Attribute Data</label>
                        </th>
                        <?php $options = get_option('epim_exclude_luckins_data'); ?>
                        <td>
                            <input type="checkbox" id="epim_exclude_luckins_data"
                                   name="epim_exclude_luckins_data[checkbox_value]"
                                   value="1" <?php if (is_array($options)) {
                                echo checked('1', $options['checkbox_value'], false);
                            } ?>/>
                        </td>
                    </tr>
                    <?php //epim_use_dynamic_data_sheets ?>
                    <style>
                        .visible-for-datasheets {
                            display: none;
                        }

                        .visible-for-datasheets.revealed {
                            display: table-row;
                        }
                    </style>
                    <tr>
                        <th scope="row"><label for="epim_use_dynamic_data_sheets">Use Dynamic Data Sheets</label></th>
                        <?php $options = get_option('epim_use_dynamic_data_sheets'); ?>
                        <td>
                            <input type="checkbox" id="epim_use_dynamic_data_sheets"
                                   name="epim_use_dynamic_data_sheets[checkbox_value]"
                                   value="1" <?php if (is_array($options)) {
                                echo checked('1', $options['checkbox_value'], false);
                            } ?>/>
                        </td>
                    </tr>
                    <tr class="visible-for-datasheets">
                        <th scope="row"><label for="epim_dynamic_data_sheets_url">Dynamic Data Sheets URL</label></th>
                        <td><input type="text" id="epim_dynamic_data_sheets_url" name="epim_dynamic_data_sheets_url"
                                   value="<?php echo get_option('epim_dynamic_data_sheets_url'); ?>"
                                   class="regular-text"/></td>
                    </tr>
                    <tr class="visible-for-datasheets">
                        <th scope="row"><label for="epim_dynamic_data_sheets_templates">List of Templates to
                                Retrieve</label></th>
                        <td><textarea id="epim_dynamic_data_sheets_templates" name="epim_dynamic_data_sheets_templates"
                                      value=""
                                      class="regular-text"><?php echo get_option('epim_dynamic_data_sheets_templates'); ?></textarea>
                        </td>
                    </tr>
                    <tr class="visible-for-datasheets">
                        <th scope="row"><label for="epim_dynamic_data_sheets_names">Names to display for
                                Templates</label></th>
                        <td><textarea id="epim_dynamic_data_sheets_names" name="epim_dynamic_data_sheets_names"
                                      value=""
                                      class="regular-text"><?php echo get_option('epim_dynamic_data_sheets_names'); ?></textarea>
                        </td>
                    </tr>
                    <tr class="visible-for-datasheets">
                        <th scope="row"><label for="epim_dynamic_data_sheets_tab_name">Dynamic Data Sheets Tab
                                Name</label></th>
                        <td><input type="text" id="epim_dynamic_data_sheets_tab_name"
                                   name="epim_dynamic_data_sheets_tab_name"
                                   value="<?php echo get_option('epim_dynamic_data_sheets_tab_name'); ?>"
                                   class="regular-text"/></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="epim_prioritise_epim_images">Prioritise ePim images</label></th>
                        <?php $options = get_option('epim_prioritise_epim_images'); ?>
                        <td>
                            <input type="checkbox" id="epim_prioritise_epim_images"
                                   name="epim_prioritise_epim_images[checkbox_value]"
                                   value="1" <?php if (is_array($options)) {
                                echo checked('1', $options['checkbox_value'], false);
                            } ?>/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="epim_background_updates_max_run_time">Max runtime for background
                                tasks (seconds)</label></th>
                        <td><input type="text" id="epim_background_updates_max_run_time"
                                   name="epim_background_updates_max_run_time"
                                   value="<?php echo get_option('epim_background_updates_max_run_time'); ?>"
                                   class="regular-text"/>
                            <p>Maximum recommended setting 450</p></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button id="ClearProducts" class="button">Clear Products</button>&nbsp;
                            &nbsp;<span class="modal ClearProducts"><img
                                        src="<?php echo epimaapi_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding-left: 0; padding-top: 0;">
                            DANGER!!! This will completely delete all products, categories and attributes in
                            WooCommerce.
                            <hr>
                        </td>
                    </tr>


                </table>
                <div id="ePimResult"></div>
                <?php submit_button(); ?>
            </form>

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

            </style>

        <?php endif; ?>
        <?php if ($active_tab == 'epim_updates'): ?>
            <h1>ePim Schedule Settings</h1>
            <form method="post" action="options.php">
                <?php settings_fields('epim_schedule_options_group'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="epim_update_schedule">Stock Update Schedule</label></th>
                        <td>
                            <select name="epim_update_schedule" id="epim_update_schedule">
                                <option value="daily" <?php if (get_option('epim_update_schedule') == 'daily') {
                                    echo 'selected';
                                } ?>>Daily
                                </option>
                                <option value="minutes" <?php if (get_option('epim_update_schedule') == 'minutes') {
                                    echo 'selected';
                                } ?>>Every 10 minutes
                                </option>

                            </select>

                    </tr>
                    <tr>
                        <th scope="row"><label for="epim_enable_scheduled_updates">Enable Scheduled Updates</label></th>
                        <?php $options = get_option('epim_enable_scheduled_updates'); ?>
                        <td>
                            <input type="checkbox" id="epim_enable_scheduled_updates"
                                   name="epim_enable_scheduled_updates[checkbox_value]"
                                   value=1 <?php if (is_array($options)) {
                                echo checked(1, $options['checkbox_value'], false);
                            } ?>/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p>
                                <strong>Last Update Log:</strong>
                            </p>
                            <hr>
                            <p><?php echo get_option('epim_schedule_log'); ?></p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        <?php endif; ?>
        <?php
        $current_user = wp_get_current_user();
        $email = (string)$current_user->user_email;
        if ($restrictedTab):
            if ($active_tab == 'epim_restricted_settings'):?>
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

                </style>
                <h2>ePim Restricted Settings</h2>
                <form method="post" action="options.php">
                    <?php settings_fields('epim_restricted_options_group'); ?>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><label for="epim_tabs_advanced">Show Advanced Tab</label></th>
                            <?php $options = get_option('epim_tabs_advanced'); ?>
                            <td>
                                <input type="checkbox" id="epim_tabs_advanced"
                                       name="epim_tabs_advanced[checkbox_value]"
                                       value="1" <?php if (is_array($options)) {
                                    echo checked('1', $options['checkbox_value'], false);
                                } ?>/>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="epim_tabs_settings">Show Settings Tab</label></th>
                            <?php $options = get_option('epim_tabs_settings'); ?>
                            <td>
                                <input type="checkbox" id="epim_tabs_settings"
                                       name="epim_tabs_settings[checkbox_value]"
                                       value="1" <?php if (is_array($options)) {
                                    echo checked('1', $options['checkbox_value'], false);
                                } ?>/>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="epim_tabs_schedule">Show Schedule Tab</label></th>
                            <?php $options = get_option('epim_tabs_schedule'); ?>
                            <td>
                                <input type="checkbox" id="epim_tabs_schedule"
                                       name="epim_tabs_schedule[checkbox_value]"
                                       value="1" <?php if (is_array($options)) {
                                    echo checked('1', $options['checkbox_value'], false);
                                } ?>/>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="epim_tabs_divi">Show Divi Tab</label></th>
                            <?php $options = get_option('epim_tabs_divi'); ?>
                            <td>
                                <input type="checkbox" id="epim_tabs_divi"
                                       name="epim_tabs_divi[checkbox_value]"
                                       value="1" <?php if (is_array($options)) {
                                    echo checked('1', $options['checkbox_value'], false);
                                } ?>/>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button(); ?>
                </form>
                <table
                <tr>
                    <td colspan="2">
                        <button id="deleteAttributes" class="button">Delete All Attributes</button> &nbsp;<span
                                class="modal deleteAttributes"><img
                                    src="<?php echo epimaapi_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button id="deleteCategories" class="button">Delete All Categories</button> &nbsp;<span
                                class="modal deleteCategories"><img
                                    src="<?php echo epimaapi_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button id="deleteImages" class="button">Delete All Images</button> &nbsp;<span
                                class="modal deleteImages"><img
                                    src="<?php echo epimaapi_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button id="deleteOrphanedImages" class="button">Delete All Orphaned Images</button>
                        &nbsp;<span
                                class="modal deleteOrphanedImages"><img
                                    src="<?php echo epimaapi_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button id="deleteProducts" class="button">Delete All Products</button> &nbsp;<span
                                class="modal deleteProducts"><img
                                    src="<?php echo epimaapi_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
                    </td>
                </tr>
                </table>

                <div id="ePimResult">

                </div>
            <?php endif;
        endif;
        ?>
    </div>
    <?php
}