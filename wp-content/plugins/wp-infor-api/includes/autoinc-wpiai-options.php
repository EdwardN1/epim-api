<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Creating an Options Page
 */


function wpiai_register_options_page() {
	add_menu_page( __( 'Infor Options' ), __( 'Infor Options' ), 'manage_options', 'infor-options', 'wpiai_options_page', plugins_url( 'assets/img/infor-logo.png', __DIR__ ), 2 );
}

add_action( 'admin_menu', 'wpiai_register_options_page' );

/**
 * Register Settings For Plugin
 */

function wpiai_register_settings() {
	add_option( 'wpiai_token_url', 'The base URL for your INFOR API' );
	register_setting( 'wpiai_options_group', 'wpiai_token_url' );
	add_option( 'wpiai_username', 'The Username for your INFOR API' );
	register_setting( 'wpiai_options_group', 'wpiai_username' );
	add_option( 'wpiai_password', 'The Password for your INFOR API' );
	register_setting( 'wpiai_options_group', 'wpiai_password' );
	add_option( 'wpiai_client_id', 'The Client ID for your INFOR API' );
	register_setting( 'wpiai_options_group', 'wpiai_client_id' );
	add_option( 'wpiai_client_secret', 'The Client Secret for your INFOR API' );
	register_setting( 'wpiai_options_group', 'wpiai_client_secret' );

	add_option( 'wpiai_message_test_url', 'Message URL' );
	register_setting( 'wpiai_test_group', 'wpiai_message_test_url' );
	add_option( 'wpiai_message_test_parameters', 'Message Parameters' );
	register_setting( 'wpiai_test_group', 'wpiai_message_test_parameters' );
	add_option( 'wpiai_message_test_xml', 'Message XML' );
	register_setting( 'wpiai_test_group', 'wpiai_message_test_xml' );

	add_option( 'wpiai_customer_url', 'API URL' );
	register_setting( 'wpiai_customer_group', 'wpiai_customer_url' );
	add_option( 'wpiai_customer_parameters', 'API Parameters' );
	register_setting( 'wpiai_customer_group', 'wpiai_customer_parameters' );
	add_option( 'wpiai_customer_xml', 'API XML' );
	register_setting( 'wpiai_customer_group', 'wpiai_customer_xml' );
}

add_action( 'admin_init', 'wpiai_register_settings' );

/**
 * Display Settings on Option’s Page
 */

function wpiai_options_page() {
	if ( isset( $_GET['tab'] ) ) {
		$active_tab = sanitize_text_field( $_GET['tab'] );
	} else {
		$active_tab = 'wpiai_security';
	}
	?>
    <div class="wrap">
        <h2 class="nav-tab-wrapper">
            <a href="?page=infor-options&tab=wpiai_security"
               class="nav-tab <?php echo $active_tab == 'wpiai_security' ? 'nav-tab-active' : ''; ?>">Security</a>
            <a href="?page=infor-options&tab=wpiai_message_test"
               class="nav-tab <?php echo $active_tab == 'wpiai_message_test' ? 'nav-tab-active' : ''; ?>">Message Test</a>
            <a href="?page=infor-options&tab=wpiai_customer_record"
               class="nav-tab <?php echo $active_tab == 'wpiai_customer_record' ? 'nav-tab-active' : ''; ?>">Customer Master Record</a>
            <a href="?page=infor-options&tab=wpiai_settings"
               class="nav-tab <?php echo $active_tab == 'wpiai_settings' ? 'nav-tab-active' : ''; ?>">Settings</a>
			<?php
			$current_user = wp_get_current_user();
			$email        = (string) $current_user->user_email;
			if ( $email === 'edward@technicks.com' ):?>
                <a href="?page=infor-options&tab=wpiai_restricted_settings"
                   class="nav-tab <?php echo $active_tab == 'wpiai_restricted_settings' ? 'nav-tab-active' : ''; ?>">Infor Restricted Settings</a>
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
		if ( $active_tab == 'wpiai_security' ):
			?>

            <div class="wrap">
                <h1>INFOR Security</h1>
            </div>
            <form method="post" action="options.php">
				<?php settings_fields( 'wpiai_options_group' ); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="wpiai_token_url">Token URL</label></th>
                        <td><input type="text" id="wpiai_token_url" name="wpiai_token_url"
                                   value="<?php echo get_option( 'wpiai_token_url' ); ?>" class="regular-text"/></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_token_url">Username</label></th>
                        <td><input type="text" id="wpiai_username" name="wpiai_username"
                                   value="<?php echo get_option( 'wpiai_username' ); ?>" class="regular-text"/></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_token_url">Password</label></th>
                        <td><input type="text" id="wpiai_password" name="wpiai_password"
                                   value="<?php echo get_option( 'wpiai_password' ); ?>" class="regular-text"/></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_client_id">Client ID</label></th>
                        <td><input type="text" id="wpiai_client_id" name="wpiai_client_id"
                                   value="<?php echo get_option( 'wpiai_client_id' ); ?>" class="regular-text"/></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_client_secret">Client Secret</label></th>
                        <td><input type="text" id="wpiai_client_secret" name="wpiai_client_secret"
                                   value="<?php echo get_option( 'wpiai_client_secret' ); ?>" class="regular-text"/></td>
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
		if ( $active_tab == 'wpiai_message_test' ):
			?>
            <div class="wrap">
                <h1>INFOR Message Test</h1>
            </div>
            <form method="post" action="options.php">
				<?php settings_fields( 'wpiai_test_group' ); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="wpiai_message_test_url">URL</label></th>
                        <td><input type="text" id="wpiai_message_test_url" name="wpiai_message_test_url" value="<?php echo get_option( 'wpiai_message_test_url' ); ?>" class="regular-text" style="width: 100%;"/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_message_test_parameters">Parameters</label></th>
                        <td><textarea id="wpiai_message_test_parameters" name="wpiai_message_test_parameters" rows="20"
                                      style="width: 100%;"><?php echo get_option( 'wpiai_message_test_parameters' ); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_message_test_xml">XML</label></th>
                        <td><textarea id="wpiai_message_test_xml" name="wpiai_message_test_xml" rows="40" style="width: 100%;"><?php echo get_option( 'wpiai_message_test_xml' ); ?></textarea>
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
		if ( $active_tab == 'wpiai_customer_record' ):
			?>
            <div class="wrap">
                <h1>INFOR Customer Master Record</h1>
            </div>
            <form method="post" action="options.php">
				<?php settings_fields( 'wpiai_customer_group' ); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="wpiai_customer_url">URL</label></th>
                        <td><input type="text" id="wpiai_customer_url" name="wpiai_customer_url" value="<?php echo get_option( 'wpiai_customer_url' ); ?>" class="regular-text" style="width: 100%;"/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_customer_parameters">Parameters</label></th>
                        <td><textarea id="wpiai_customer_parameters" name="wpiai_customer_parameters" rows="20"
                                      style="width: 100%;"><?php echo get_option( 'wpiai_customer_parameters' ); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wpiai_customer_xml">XML</label></th>
                        <td><textarea id="wpiai_customer_xml" name="wpiai_customer_xml" rows="40" style="width: 100%;"><?php echo get_option( 'wpiai_customer_xml' ); ?></textarea>
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
		if ( $active_tab == 'wpiai_settings' ):
			?>
            <div class="wrap">
                <h1>INFOR Settings</h1>
            </div>
		<?php
		endif;
		if ( $active_tab == 'wpiai_restricted_settings' ):
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
