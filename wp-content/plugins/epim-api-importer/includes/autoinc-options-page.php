<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Creating an Options Page
 */


function wpb_new_product_tab_content() {
	// The new tab content
	echo 'Discount';
	echo 'Here\'s your new discount product tab.';
}

function epim_register_options_page() {
	//Add to settings menu
	//add_options_page('Page Title', 'Plugin Menu', 'manage_options', 'myplugin', 'myplugin_options_page');
	// Add to admin_menu function
	add_menu_page( __( 'ePim Menu' ), __( 'ePim' ), 'manage_options', 'epim', 'epim_options_page', plugins_url( 'assets/img/epim-logo.png', __DIR__ ), 2 );

}

add_action( 'admin_menu', 'epim_register_options_page' );

/**
 * Register Settings For Plugin
 */

function epim_register_settings() {
	add_option( 'epim_url', 'The base URL for your ePim API' );
	register_setting( 'epim_options_group', 'epim_url' );
	add_option( 'epim_key', 'The Subscription Key for your ePim API' );
	register_setting( 'epim_options_group', 'epim_key' );
	add_option( 'epim_api_retrieval_method', 'API Retrieval Method' );
	register_setting( 'epim_options_group', 'epim_api_retrieval_method' );
	add_option( 'epim_no_price_or_stocks', '1' );
	register_setting( 'epim_options_group', 'epim_no_price_or_stocks' );
	add_option( 'epim_always_include_epim_attributes', '1' );
	register_setting( 'epim_options_group', 'epim_always_include_epim_attributes' );
	add_option( 'epim_exclude_luckins_data', '1' );
	register_setting( 'epim_options_group', 'epim_exclude_luckins_data' );

    add_option( 'epim_enable_scheduled_updates', '0' );
    register_setting( 'epim_schedule_options_group', 'epim_enable_scheduled_updates' );
    add_option( 'epim_update_schedule', 'daily' );
    register_setting( 'epim_schedule_options_group', 'epim_update_schedule' );
    add_option( 'epim_schedule_log', '' );
    register_setting( 'epim_schedule_options_group', 'epim_schedule_log' );
}

add_action( 'admin_init', 'epim_register_settings' );


/**
 * Display Settings on Option’s Page
 */

function epim_options_page() {
	?>
    <div class="wrap">
		<?php screen_icon(); ?>
		<?php
		if ( isset( $_GET['tab'] ) ) {
			$active_tab = sanitize_text_field( $_GET['tab'] );
		} else {
			$active_tab = 'epim_management';
		}
		?>
        <h2 class="nav-tab-wrapper">
            <a href="?page=epim&tab=epim_management"
               class="nav-tab <?php echo $active_tab == 'epim_management' ? 'nav-tab-active' : ''; ?>">ePim
                Management</a>
            <a href="?page=epim&tab=epim_settings"
               class="nav-tab <?php echo $active_tab == 'epim_settings' ? 'nav-tab-active' : ''; ?>">ePim Settings</a>
            <a href="?page=epim&tab=epim_updates"
               class="nav-tab <?php echo $active_tab == 'epim_updates' ? 'nav-tab-active' : ''; ?>">ePim Update Schedule</a>
			<?php
			$current_user = wp_get_current_user();
			$email        = (string) $current_user->user_email;
			if ( $email === 'edward@technicks.com' ):?>
                <a href="?page=epim&tab=epim_restricted_settings"
                   class="nav-tab <?php echo $active_tab == 'epim_restricted_settings' ? 'nav-tab-active' : ''; ?>">ePim Restricted Settings</a>
			<?php endif; ?>
        </h2>
		<?php if ( $active_tab == 'epim_management' ): ?>
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
                <h1>ePim Management</h1>

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
					<?php
					if ( is_plugin_active( 'click-collect/click-collect.php' ) ) {
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
					}
					?>

                </table>

                <div id="ePimResult">

                </div>
            </div>
		<?php endif; ?>
		<?php if ( $active_tab == 'epim_settings' ): ?>
            <h1>ePim Settings</h1>
            <form method="post" action="options.php">
				<?php settings_fields( 'epim_options_group' ); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="epim_url">base URL</label></th>
                        <td><input type="text" id="epim_url" name="epim_url"
                                   value="<?php echo get_option( 'epim_url' ); ?>" class="regular-text"/></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="epim_key">Subscription Key</label></th>
                        <td><input type="text" id="epim_key" name="epim_key"
                                   value="<?php echo get_option( 'epim_key' ); ?>" class="regular-text"/></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="epim_api_retrieval_method">API Retrieval Method</label></th>
                        <td>
                            <select name="epim_api_retrieval_method" id="epim_api_retrieval_method">
                                <option value="file_get_contents" <?php if ( get_option( 'epim_api_retrieval_method' ) == 'file_get_contents' ) {
									echo 'selected';
								} ?>>wp_remote_get
                                </option>
								<?php if ( function_exists( 'curl_init' ) ): ?>
                                    <option value="curl" <?php if ( get_option( 'epim_api_retrieval_method' ) == 'curl' ) {
										echo 'selected';
									} ?>>cUrl
                                    </option>
								<?php endif; ?>

                            </select>

                    </tr>
                    <tr>
                        <th scope="row"><label for="epim_no_price_or_stock">Do Not Import Branch Stock or Price</label></th>
                        <?php $options = get_option( 'epim_no_price_or_stocks' );?>
                        <td>
                            <input type="checkbox" id="epim_no_price_or_stocks" name="epim_no_price_or_stocks[checkbox_value]" value="1" <?php  if(is_array($options)) echo checked( '1', $options['checkbox_value'], false );?>/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="epim_always_include_epim_attributes">Always Include EPIM Attributes</label></th>
		                <?php $options = get_option( 'epim_always_include_epim_attributes' );?>
                        <td>
                            <input type="checkbox" id="epim_always_include_epim_attributes" name="epim_always_include_epim_attributes[checkbox_value]" value="1" <?php  if(is_array($options)) echo checked( '1', $options['checkbox_value'], false );?>/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="epim_exclude_luckins_data">Exclude Luckins Data</label></th>
		                <?php $options = get_option( 'epim_exclude_luckins_data' );?>
                        <td>
                            <input type="checkbox" id="epim_exclude_luckins_data" name="epim_exclude_luckins_data[checkbox_value]" value="1" <?php  if(is_array($options)) echo checked( '1', $options['checkbox_value'], false );?>/>
                        </td>
                    </tr>
                </table>
				<?php submit_button(); ?>
            </form>
		<?php endif; ?>
        <?php if ( $active_tab == 'epim_updates' ): ?>
            <h1>ePim Schedule Settings</h1>
            <form method="post" action="options.php">
                <?php settings_fields( 'epim_schedule_options_group' ); ?>
                <table class="form-table">
                     <tr>
                        <th scope="row"><label for="epim_update_schedule">Stock Update Schedule</label></th>
                        <td>
                            <select name="epim_update_schedule" id="epim_update_schedule">
                                <option value="daily" <?php if ( get_option( 'epim_update_schedule' ) == 'daily' ) {
                                    echo 'selected';
                                } ?>>Daily
                                </option>
                                <option value="minutes" <?php if ( get_option( 'epim_update_schedule' ) == 'minutes' ) {
                                    echo 'selected';
                                } ?>>Every 10 minutes
                                </option>

                            </select>

                    </tr>
                    <tr>
                        <th scope="row"><label for="epim_enable_scheduled_updates">Enable Scheduled Updates</label></th>
                        <?php $options = get_option( 'epim_enable_scheduled_updates' ); ?>
                        <td>
                            <input type="checkbox" id="epim_enable_scheduled_updates" name="epim_enable_scheduled_updates[checkbox_value]" value=1 <?php if(is_array($options)) echo checked( 1, $options['checkbox_value'], false );?>/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p>
                                <strong>Last Update Log:</strong>
                            </p>
                            <hr>
                            <p><?php echo get_option('epim_schedule_log');?></p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        <?php endif; ?>
		<?php
		$current_user = wp_get_current_user();
		$email        = (string) $current_user->user_email;
		if ( $email === 'edward@technicks.com' ):
			if ( $active_tab == 'epim_restricted_settings' ):?>
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
                <table class="form-table">
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