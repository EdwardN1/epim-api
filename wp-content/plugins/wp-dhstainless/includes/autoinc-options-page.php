<?php
if (!defined('ABSPATH')) {
	exit;
}

function dhs_epim_register_options_page()
{
	add_menu_page(__('ePim Menu (DH Stainless)'), __('ePim (DH Stainless)'), 'manage_options', 'dhs_epim', 'dhs_epim_options_page', plugins_url('assets/img/epim-logo.png', __DIR__), 2);
}

add_action('admin_menu', 'dhs_epim_register_options_page');

function dhs_epim_register_settings()
{
	add_option('dhs_epim_url', 'The base URL for your ePim API');
	register_setting('dhs_epim_options_group', 'dhs_epim_url');
	add_option('dhs_epim_key', 'The Subscription Key for your ePim API');
	register_setting('dhs_epim_options_group', 'dhs_epim_key');
	add_option('dhs_epim_api_retrieval_method', 'API Retrieval Method');
	register_setting('dhs_epim_options_group', 'dhs_epim_api_retrieval_method');
	add_option('dhs_epim_background_updates_max_run_time', '23');
	register_setting('dhs_epim_options_group', 'dhs_epim_background_updates_max_run_time');

	add_option('_dhs_epim_update_running', '');
	add_option('_dhs_epim_background_process_data', '');
	add_option('_dhs_epim_background_last_process_data', '');
	add_option('_dhs_epim_background_current_index', 0);
}

add_action('admin_init', 'dhs_epim_register_settings');

function dhs_epim_options_page()
{
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<?php
		if (isset($_GET['tab'])) {
			$active_tab = sanitize_text_field($_GET['tab']);
		} else {
			$active_tab = 'dhs_epim_management';
		}
		?>
		<h2 class="nav-tab-wrapper">
			<a href="?page=dhs_epim&tab=dhs_epim_management"
			   class="nav-tab <?php echo $active_tab == 'dhs_epim_management' ? 'nav-tab-active' : ''; ?>">Update Data</a>
			<a href="?page=dhs_epim&tab=dhs_epim_settings"
			   class="nav-tab <?php echo $active_tab == 'dhs_epim_settings' ? 'nav-tab-active' : ''; ?>">ePim Settings</a>
			<a href="?page=dhs_epim&tab=dhs_epim_background_updates"
			   class="nav-tab <?php echo $active_tab == 'dhs_epim_background_updates' ? 'nav-tab-active' : ''; ?>">ePim
				Background Updates</a>
		</h2>



		<?php if ($active_tab == 'dhs_epim_background_updates'): ?>
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
				<h1>ePim Background Updater</h1>
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
									src="<?php echo dhstainless_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
						</td>
						<td>
							<button id="StopCurrentUpdate" class="button">Stop Current Update</button>&nbsp;
							&nbsp;<span class="modal StopCurrentUpdate"><img
									src="<?php echo dhstainless_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
							<br>
							NB Stops and Cancels Current Background Update.
						</td>
					</tr>
					<tr>
						<td>
							<button id="BackgroundUpdateAll" class="button">Update all</button>&nbsp;
							&nbsp;<span class="modal BackgroundUpdateAll"><img
									src="<?php echo dhstainless_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
						</td>
						<td>
							NB restarts current background import if one is active.
						</td>
					</tr>
					<tr>
						<td>
							<button id="BackgroundUnfreezeQueue" class="button">Unfreeze Queue</button>&nbsp;
							&nbsp;<span class="modal BackgroundUnfreezeQueue"><img
									src="<?php echo dhstainless_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
						</td>
						<td>
							NB attempts to unfreeze the queue by reloading product data.
						</td>
					</tr>
				</table>
				<div id="ePimResult">

				</div>
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
		<?php if ($active_tab == 'dhs_epim_management'): ?>
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
									src="<?php echo dhstainless_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
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
						<td colspan="2">
							<button id="CreateAllProducts" class="button">Update all Products</button>&nbsp;
							&nbsp;<span class="modal CreateAllProducts"><img
									src="<?php echo dhstainless_PLUGINURI; ?>/assets/img/FhHRx.gif"></span>
						</td>
					</tr>

				</table>

				<div id="ePimResult">

				</div>
			</div>
		<?php endif; ?>
		<?php if ($active_tab == 'dhs_epim_settings'): ?>
			<h1>ePim Settings</h1>
			<form method="post" action="options.php">
				<?php settings_fields('dhs_epim_options_group'); ?>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="dhs_epim_url">base URL</label></th>
						<td><input type="text" id="dhs_epim_url" name="dhs_epim_url"
						           value="<?php echo get_option('dhs_epim_url'); ?>" class="regular-text"/></td>
					</tr>
					<tr>
						<th scope="row"><label for="dhs_epim_key">Subscription Key</label></th>
						<td><input type="text" id="dhs_epim_key" name="dhs_epim_key"
						           value="<?php echo get_option('dhs_epim_key'); ?>" class="regular-text"/></td>
					</tr>
					<tr>
						<th scope="row"><label for="dhs_epim_api_retrieval_method">API Retrieval Method</label></th>
						<td>
							<select name="dhs_epim_api_retrieval_method" id="dhs_epim_api_retrieval_method">
								<option value="file_get_contents" <?php if (get_option('dhs_epim_api_retrieval_method') == 'file_get_contents') {
									echo 'selected';
								} ?>>wp_remote_get
								</option>
								<?php if (function_exists('curl_init')): ?>
									<option value="curl" <?php if (get_option('dhs_epim_api_retrieval_method') == 'curl') {
										echo 'selected';
									} ?>>cUrl
									</option>
								<?php endif; ?>

							</select>

					</tr>


					<tr>
						<th scope="row"><label for="dhs_epim_background_updates_max_run_time">Max runtime for background
								tasks (seconds)</label></th>
						<td><input type="text" id="dhs_epim_background_updates_max_run_time"
						           name="dhs_epim_background_updates_max_run_time"
						           value="<?php echo get_option('dhs_epim_background_updates_max_run_time'); ?>"
						           class="regular-text"/>
							<p>Maximum recommended setting 450</p></td>
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

	</div>
	<?php
}