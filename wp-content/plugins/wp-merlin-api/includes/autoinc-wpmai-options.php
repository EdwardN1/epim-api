<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Creating an Options Page
 */


function wpmai_register_options_page() {
	add_menu_page( __( 'Merlin Options' ), __( 'Merlin Options' ), 'manage_options', 'merlin-options', 'wpmai_options_page', plugins_url( 'assets/img/merlin-logo.png', __DIR__ ), 2 );
}

add_action( 'admin_menu', 'wpmai_register_options_page' );

function wpiai_register_settings() {
	add_option( 'wpmai_url', 'The base URL for your Merlin API' );
	register_setting( 'wpmai_options_group', 'wpmai_url' );
	}

add_action( 'admin_init', 'wpiai_register_settings' );

function wpmai_options_page() {
	if ( isset( $_GET['tab'] ) ) {
		$active_tab = sanitize_text_field( $_GET['tab'] );
	} else {
		$active_tab = 'wpmai_options';
	}
	?>
    <div class="wrap">
        <h2 class="nav-tab-wrapper">
            <a href="?page=infor-options&tab=wpiai_security"
               class="nav-tab <?php echo $active_tab == 'wpmai_options' ? 'nav-tab-active' : ''; ?>">Options</a>
            <?php
			$current_user = wp_get_current_user();
			$email        = (string) $current_user->user_email;
			if ( $email === 'edward@technicks.com' ):?>
                <a href="?page=infor-options&tab=wpmai_restricted_settings"
                   class="nav-tab <?php echo $active_tab == 'wpmai_restricted_settings' ? 'nav-tab-active' : ''; ?>">Merlin Restricted Settings</a>
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
				<?php settings_fields( 'wpmai_options_group' ); ?>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="wpiai_token_url">Token URL</label></th>
						<td><input type="text" id="wpiai_token_url" name="wpiai_token_url"
						           value="<?php echo get_option( 'wpiai_token_url' ); ?>" class="regular-text"/></td>
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
}