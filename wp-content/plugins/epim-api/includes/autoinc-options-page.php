<?php

/**
 * Creating an Options Page
 */


function wpb_new_product_tab_content() {
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
}

add_action('admin_init', 'epim_register_settings');


/**
 * Display Settings on Option’s Page
 */

function epim_options_page()
{
    ?>
    <div class="wrap">
        <?php screen_icon(); ?>
        <?php
        if (isset($_GET['tab'])) {
            $active_tab = $_GET['tab'];
        } else {
            $active_tab = 'epim_management';
        }
        ?>
        <h2 class="nav-tab-wrapper">
            <a href="?page=epim&tab=epim_management" class="nav-tab <?php echo $active_tab == 'epim_management' ? 'nav-tab-active' : ''; ?>">ePim Management</a>
            <a href="?page=epim&tab=epim_settings" class="nav-tab <?php echo $active_tab == 'epim_settings' ? 'nav-tab-active' : ''; ?>">ePim Settings</a>
        </h2>
        <?php if ($active_tab == 'epim_management'): ?>
            <div class="wrap">
                <h1>ePim Management</h1>

                <table class="form-table">
                    <tr>
                        <th><label for="pCode">Update by product code (SKU):</label></th>
                        <td>
                            <input type="text" id="pCode" class="regular-text">&nbsp;<button id="UpdateCode" class="button">Update</button>
                        </td>
                    </tr>
                    <tr><td colspan="2" style="padding-left: 0; padding-top: 0;">This will only update existing products. If you have added new products in ePim then you need to Create them using either of the two options below first.<hr></td></tr>
                    <tr>
                        <th style="width: 250px;"><label for="start_date">Update by product changed since:</label></th>
                        <td><input type="text" class="custom_date" name="start_date" id="start_date" value=""/>&nbsp;<button id="UpdateSince" class="button">Update</button></td>
                    </tr>
                    <tr><td colspan="2" style="padding-left: 0; padding-top: 0;">NB if you have added new Categories in ePim, Create and Update those first as per below.<hr></td></tr>
                    <tr><td colspan="2"><button id="CreateCategories" class="button">Create and Update Categories</button></td></tr>
                    <tr><td colspan="2" style="padding-left: 0; padding-top: 0;">Creates and Updates All Categories. You should run this before the above 2 options if you have added or updated any category information.<hr></td></tr>
                    <tr>
                        <td colspan="2">
                            <button id="CreateAll" class="button">Create and Update all</button>&nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding-left: 0; padding-top: 0;">Updates and creates all categories and all products. If you have a lot of products this will take a long time to complete.<hr></td>
                    </tr>

                </table>

                <div id="ePimResult">
                    <?php
/*                    global $ep_WooCommerce;

                    try {
	                    $results = $ep_WooCommerce->get('products/categories');
	                    echo '<h3>Results</h3>';
	                    echo '<pre><code>' . print_r( $results, true ) . '</code><pre>'; // JSON output.

	                    // Last request data.
	                    echo '<hr><h3>Request Data</h3>';
	                    $lastRequest = $ep_WooCommerce->http->getRequest();
	                    echo '<pre><code>' . print_r( $lastRequest->getUrl(), true ) . '</code><pre>'; // Requested URL (string).
	                    echo '<pre><code>' . print_r( $lastRequest->getMethod(), true ) . '</code><pre>'; // Request method (string).
	                    echo '<pre><code>' . print_r( $lastRequest->getParameters(), true ) . '</code><pre>'; // Request parameters (array).
	                    echo '<pre><code>' . print_r( $lastRequest->getHeaders(), true ) . '</code><pre>'; // Request headers (array).
	                    echo '<pre><code>' . print_r( $lastRequest->getBody(), true ) . '</code><pre>'; // Request body (JSON).

	                    // Last response data.
	                    echo '<hr><h3>Response Data</h3>';
	                    $lastResponse = $ep_WooCommerce->http->getResponse();
	                    echo '<pre><code>' . print_r( $lastResponse->getCode(), true ) . '</code><pre>'; // Response code (int).
	                    echo '<pre><code>' . print_r( $lastResponse->getHeaders(), true ) . '</code><pre>'; // Response headers (array).
	                    echo '<pre><code>' . print_r( $lastResponse->getBody(), true ) . '</code><pre>'; // Response body (JSON).
                    } catch (HttpClientException $e) {
	                    echo '<hr><h3>Error Data</h3>';
	                    echo '<pre><code>' . print_r( $e->getMessage(), true ) . '</code><pre>'; // Error message.
	                    echo '<pre><code>' . print_r( $e->getRequest(), true ) . '</code><pre>'; // Last request data.
	                    echo '<pre><code>' . print_r( $e->getResponse(), true ) . '</code><pre>'; // Last response data.
                    }

                    */?>
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
                        <td><input type="text" id="epim_url" name="epim_url" value="<?php echo get_option('epim_url'); ?>" class="regular-text"/></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="epim_key">Subscription Key</label></th>
                        <td><input type="text" id="epim_key" name="epim_key" value="<?php echo get_option('epim_key'); ?>" class="regular-text"/></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="epim_api_retrieval_method">API Retrieval Method</label></th>
                        <td>
                            <select name="epim_api_retrieval_method" id="epim_api_retrieval_method">
                                <option value="curl" <?php if (get_option('epim_api_retrieval_method') == 'curl') {
                                    echo 'selected';
                                } ?>>cUrl
                                </option>
                                <option value="file_get_contents" <?php if (get_option('epim_api_retrieval_method') == 'file_get_contents') {
                                    echo 'selected';
                                } ?>>file_get_contents
                                </option>
                            </select>

                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        <?php endif; ?>
    </div>
    <?php
}