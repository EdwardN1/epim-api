<?php
/**
** Get waze icon from plugin
**/
function wps_store_get_waze_icon($width = '') {
	$attr = !empty($width) ? 'width="' . $width . '"' : '';
	return '<img src="' . plugin_dir_url(__DIR__) . 'assets/images/icon_waze.svg' . '" ' . $attr . ' />';
}

/**
** No stores message
**/
function wps_no_stores_availables_message() {
	return apply_filters('wps_no_stores_availables_message', __('There are not available stores. Please choose another shipping method.', WPS_TEXTDOMAIN));
}

/**
** Store table row layout
**/
function wps_store_row_layout() {
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;

	$chosen_shipping = wps_get_chosen_shipping_method();
	$post_data = wps_validate_ajax_request();
	$checkout_notification = WPS()->checkout_notification;
	$no_stores_message = wps_no_stores_availables_message();
	$show_store_details = (isset(WPS()->hide_store_details) && WPS()->hide_store_details == 'yes') ? false : true;

	$store_default = apply_filters('wps_first_store', get_user_meta($user_id, '_shipping_pickup_stores', true));
	if(empty($store_default)) {
		$store_default = get_theme_mod('wps_store_default');
	}

	if ($chosen_shipping === WPS()->id && is_checkout()) :
		$store_default = !empty($post_data['shipping_pickup_stores']) ? $post_data['shipping_pickup_stores'] : $store_default;
		$ship_to_store = !empty($post_data['shipping_by_store']) ? $post_data['shipping_by_store'] : '';
		$total_costs = 0;
		?>
		<tr class="shipping-pickup-store">
		<?php if(count(wps_store_get_store_admin()) > 0) :
			if(WPS()->enable_store_select == 'yes') : ?>
			<th><strong><?php echo __('Local pickup', WPS_TEXTDOMAIN) ?></strong></th>
			<td>
				<select id="shipping-pickup-store-select" class="<?= (WPS()->costs_per_store == 'yes') ? 'wps-costs-per-store' : 'wps-no-costs' ?>" name="shipping_pickup_stores" data-store="<?= $store_default ?>">
					<?php if(empty($store_default)) : ?>
						<option value=""><?= __('Select a store', WPS_TEXTDOMAIN) ?></option>
					<?php endif; ?>
					<?php
					foreach (wps_store_get_store_admin(true) as $post_id => $store) {
						$store_shipping_cost = get_post_meta($post_id, 'store_shipping_cost', true);
						$cost = WPS()->wps_get_calculated_costs($store_shipping_cost);
						$formatted_title = ($cost > 0) ? $store . ': ' . wc_price($cost) : $store;
						$ship_to_store = ($store_default == $store) ? $cost : $ship_to_store;
						?>
						<option data-cost="<?= $cost ?>" data-id="<?= $post_id ?>" value="<?= $store; ?>" <?php selected($store, $store_default); ?>><?= $formatted_title; ?></option>
						<?php
					}
					?>
				</select>
				<input type="hidden" id="store_shipping_cost" name="shipping_by_store" value="<?= $ship_to_store ?>">
				<?php if($ship_to_store > 0 && !isset($post_data['shipping_pickup_stores'])) : ?>
					<script type="text/javascript">
						jQuery('body').trigger('update_checkout');
					</script>
				<?php endif; ?>
			</td>
			<?php elseif(!empty($store_default) && in_array($store_default, wps_store_get_store_admin())) : ?>
			<th><strong><?php echo __('Store', WPS_TEXTDOMAIN) ?></strong></th>
			<td>
				<strong><?= $store_default ?></strong>
				<input type="hidden" name="shipping_pickup_stores" value="<?= $store_default; ?>">
			</td>
			<?php else :  ?>
				<td colspan="2">
					<span class="no-store-default"><?= wps_no_stores_availables_message(); ?></span>
				</td>
			<?php endif;
			else : ?>
				<td colspan="2">
					<span class="no-store-available"><?= wps_no_stores_availables_message(); ?></span>
				</td>
			<?php endif; ?>
		</tr>
		
		<?php if ($show_store_details || !empty($checkout_notification)) : ?>
			<tr class="shipping-pickup-store">
				<td colspan="2">
					<?php  if ($show_store_details) : ?>
						<div class="store-template">
							<?php
								$template_file = wps_locate_template('selected-store-details.php');
								include $template_file;
							?>
						</div>
					<?php endif; ?>

					<?php if(!empty($checkout_notification)) : ?>
						<span class="store-message"><?= sanitize_textarea_field($checkout_notification) ?></span>
					<?php endif; ?>
				</td>
			</tr>
		<?php endif; ?>
		
		<?php
	endif;
}
add_action('woocommerce_after_shipping_calculator', 'wps_store_row_layout');
add_action('woocommerce_review_order_after_shipping', 'wps_store_row_layout');


/**
** Order detail styles
**/
function wps_store_style() {
	?>
	<style type="text/css">
		.shipping-pickup-store td .title {
			float: left;
			line-height: 30px;
		}
		.shipping-pickup-store td span.text {
			float: right;
		}
		.shipping-pickup-store td span.description {
			clear: both;
		}
		.shipping-pickup-store td > span:not([class*="select"]) {
			display: block;
			font-size: 14px;
			font-weight: normal;
			line-height: 1.4;
			margin-bottom: 0;
			padding: 6px 0;
			text-align: justify;
		}
		.wps-store-details iframe {
			width: 100%;
		}
	</style>
	<?php
}
add_action('wp_head', 'wps_store_style');

/**
** Remove cart shipping label
**/
function wps_shipping_method_label( $label, $method ) {
	if($method->method_id == 'wc_pickup_store' && ((int)$method->get_cost()) == 0) {
		$label = $method->get_label();
	}

	return apply_filters('wps_shipping_method_label', $label);
};
add_filter('woocommerce_cart_shipping_method_full_label', 'wps_shipping_method_label', 10, 2);

/**
** Validate ajax request
**/
function wps_validate_ajax_request() {
	if(!$_POST || (is_admin() && !is_ajax()))
		return;

	if(isset($_POST['post_data'])) {
		parse_str($_POST['post_data'], $post_data);
	} else {	
		$post_data = $_POST;
	}

	return $post_data;
}

/**
** Get chosen shipping method
**/
function wps_get_chosen_shipping_method() {
	$chosen_methods = WC()->session->get('chosen_shipping_methods');

	return $chosen_methods[0];
}

/**
** Add CSS/JS
**/
function wps_store_enqueue_styles() {
	$min = (!preg_match('/localhost/', site_url())) ? '.min' : '';
	
	if( !isset(WPS()->external_bootstrap) || (isset(WPS()->external_bootstrap) && WPS()->external_bootstrap != 'yes') )
		wp_enqueue_style('wps_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
	
	if( !isset(WPS()->external_font_awesome) || (isset(WPS()->external_font_awesome) && WPS()->external_font_awesome != 'yes') )
		wp_enqueue_style('wps_fontawesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
	
	if( !isset(WPS()->local_css) || (isset(WPS()->local_css) && WPS()->local_css != 'yes') ) {
		wp_enqueue_style('store-styles', plugin_dir_url(__DIR__) . 'assets/css/stores' . $min . '.css');
	}

	if (is_checkout()) {
		wp_enqueue_script('wp-util');
		wp_enqueue_script('store-checkout', plugin_dir_url(__DIR__) . 'assets/js/stores' . $min . '.js', array('jquery', 'wp-util'));
		wp_localize_script('store-checkout', 'wps_ajax', array(
			'stores' => wps_stores_fields()
		));
	}
}
add_action('wp_enqueue_scripts', 'wps_store_enqueue_styles');

/**
** Add store shipping cost to cart amount
**/
function wps_add_store_shipping_to_cart($cart) {
	$post_data = wps_validate_ajax_request();
	$chosen_shipping = wps_get_chosen_shipping_method();

	if (isset($post_data['shipping_by_store']) && $post_data['shipping_by_store'] > 0 && $chosen_shipping == 'wc_pickup_store') {
		$amount = $post_data['shipping_by_store'];    
		WC()->cart->add_fee(
			apply_filters('wps_store_pickup_cost_label', sprintf(__('Ship to %s', WPS_TEXTDOMAIN), $post_data['shipping_pickup_stores']), $post_data['shipping_pickup_stores']),
			$amount,
			true,
			''
		); 
	}
}
add_action('woocommerce_cart_calculate_fees', 'wps_add_store_shipping_to_cart');

/**
** Adding CC to email order notification
**/
function wps_cc_email_headers($header, $email_id, $order) {
	// $custom_email = 'wpsorders@mailinator.com';
	if('new_order' !== $email_id)
		return $headers;
	
	if($custom_email = wps_get_email_address($order)) {
		$formatted_email = utf8_decode('<' . $custom_email . '>');
		// Add Cc to headers
		$header .= 'Cc: '.$formatted_email .'\r\n';
	}

	return $header;
}
add_filter('woocommerce_email_headers', 'wps_cc_email_headers', 10, 3);

/**
** Get email address from store
**/
function wps_get_email_address($order) {
	$order_id = $order->get_id();
	$store_name = get_post_meta($order_id, '_shipping_pickup_stores', true); // Get store title for this order
	$store = get_page_by_title($store_name, OBJECT, 'store');

	$store_order_email = get_post_meta($store->ID, 'store_order_email', true);
	$enable_order_email = get_post_meta($store->ID, 'enable_order_email', true);

	if($enable_order_email == 1 && !empty($store_order_email)) {
		return sanitize_email($store_order_email);
	} else {
		return false;
	}
}

/**
** Get the stores with its custom fields
** v1.5.22
**/
function wps_stores_fields() {
	$the_stores = wps_store_get_store_admin(true); // Get stores with ID
	$the_fields = array();
	$custom_fields = apply_filters('wps_get_store_custom_fields', 
		array('city', 'phone', 'address', 'map')
	);

	foreach ($the_stores as $store_id => $store) {
		$the_fields[$store_id] = array(
			array(
				'key' => 'title',
				'value' => $store
			)
		);
		
		foreach ($custom_fields as $key => $custom_field) {
			if ($the_field = get_post_meta($store_id, $custom_field, true)) {
				$the_fields[$store_id][] = array(
					'key' => $custom_field,
					'value' => esc_attr($the_field)
				);
			}
		}
	}

	return apply_filters('wps_stores_fields', $the_fields);
}

/**
** Get WPS template
** v1.5.22
**/
function wps_locate_template($template_name) {
	$template_name = ltrim($template_name, '/');
	$template_file = trailingslashit(plugin_dir_path(__DIR__)) . 'templates/' . $template_name;
	
	if (locate_template('template-parts/' . $template_name)) {
		$template_file = locate_template('template-parts/' . $template_name, false);
	}

	if (file_exists($template_file)) {
		return $template_file;
	} else {
		return false;
	}
}