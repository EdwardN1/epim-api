<?php
/**
** Add shipping method to WC
**/
function wps_store_shipping_method( $methods ) {
	$methods['wc_pickup_store'] = 'WC_PICKUP_STORE';

	return $methods;
}
add_filter('woocommerce_shipping_methods', 'wps_store_shipping_method');

/**
** Declare Shipping Method
**/
function wps_store_shipping_method_init() {
	class WC_PICKUP_STORE extends WC_Shipping_Method {
		/**
		 * Constructor for your shipping class
		 *
		 * @access public
		 * @return void
		 */
		public function __construct() {
			$this->id = 'wc_pickup_store';
			$this->method_title = __('WC Pickup Store');
			$this->method_description = __('Lets users to choose a store to pick up their products', WPS_TEXTDOMAIN);

			$this->init();
			// $this->includes();
		}

		// public function includes() {}

		/**
		 * Init your settings
		 *
		 * @access public
		 * @return void
		 */
		function init() {
			// Load the settings API
			$this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
			$this->init_settings(); // This is part of the settings API. Loads settings you previously init.

			// Turn these settings into variables we can use
			foreach ( $this->settings as $setting_key => $value ) {
				$this->$setting_key = apply_filters('wps_settings_data', $value, $setting_key, $this->settings);
			}

			// Save settings in admin if you have any defined
			add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
			add_filter('woocommerce_get_order_item_totals', array($this, 'wc_reordering_order_item_totals'), 10, 3);
		}

		public function init_form_fields() {
			$this->form_fields = array(
				'enabled' => array(
					'title' => __( 'Enable/Disable', 'woocommerce' ),
					'type' => 'checkbox',
					'label' => __( 'Enable', 'woocommerce' ),
					'default'  => 'yes',
					'description' => __( 'Enable/Disable shipping method', WPS_TEXTDOMAIN ),
					'desc_tip'    => true
				),
				'enable_store_select' => array(
					'title' => __( 'Enable stores in checkout', WPS_TEXTDOMAIN ),
					'type' => 'checkbox',
					'label' => __( 'Enable', 'woocommerce' ),
					'default'  => 'no',
					'description' => __( 'Shows select field to pick a store in checkout', WPS_TEXTDOMAIN ),
					'desc_tip'    => true
				),
				'title' => array(
					'title' => __( 'Shipping Method Title', WPS_TEXTDOMAIN ),
					'type' => 'text',
					'description' => __( 'Label that appears in checkout options', WPS_TEXTDOMAIN ),
					'default' => __( 'Pickup Store', WPS_TEXTDOMAIN ),
					'desc_tip'    => true
				),
				'costs_type' => array(
					'title' => __( 'Shipping Costs Type', WPS_TEXTDOMAIN ),
					'type' => 'select',
					'class' => 'wc-enhanced-select',
					'description' => __( 'Choose a shipping costs type to calculate Pick up store costs. Use None to deactivate shipping store costs', WPS_TEXTDOMAIN ),
					'default' => 'flat',
					'options' => array(
						'none' => __('None', WPS_TEXTDOMAIN),
						'flat' => __('Flat Rate', WPS_TEXTDOMAIN),
						'percentage' => __('Percentage', WPS_TEXTDOMAIN)
					),
					'desc_tip'    => true
				),
				'costs' => array(
					'title' => __( 'Shipping Costs', WPS_TEXTDOMAIN ),
					'type' => 'text',
					'description' => __( 'Adds main shipping cost to store pickup', WPS_TEXTDOMAIN ),
					'default' => 0,
					'placeholder' => '0',					
					'desc_tip'    => true
				),
				'costs_per_store' => array(
					'title' => __( 'Enable costs per store', WPS_TEXTDOMAIN ),
					'type' => 'checkbox',
					'label' => __( 'Enable', 'woocommerce' ),
					'default'  => 'no',
					'description' => __( 'Allows to add shipping costs by store that will override the main shipping cost.', WPS_TEXTDOMAIN ),
					'desc_tip'    => true
				),
				'stores_order_by' => array(
					'title' => __( 'Order Stores by', WPS_TEXTDOMAIN ),
					'type' => 'select',
					'class' => 'wc-enhanced-select',
					'description' => __( 'Choose what order the stores will be shown', WPS_TEXTDOMAIN ),
					'default' => 'title',
					'options' => array(
						'title' => 'Title',
						'date' => 'Date',
						'ID' => 'ID',
						'rand' => 'Random'
					),
					'desc_tip'    => true
				),
				'stores_order' => array(
					'title' => __( 'Order', WPS_TEXTDOMAIN ),
					'type' => 'select',
					'class' => 'wc-enhanced-select',
					'description' => __( 'Choose what order the stores will be shown', WPS_TEXTDOMAIN ),
					'default' => 'DESC',
					'options' => array(
						'DESC' => 'DESC',
						'ASC' => 'ASC'
					),
					'desc_tip'    => true
				),
				'store_default' => array(
					'type' => 'store_default',
					'description' => __( 'Choose a default store to checkout', WPS_TEXTDOMAIN ),
					'desc_tip'    => true
				),
				'checkout_notification' => array(
					'title' => __( 'Checkout notification', WPS_TEXTDOMAIN ),
					'type' => 'textarea',
					'description' => __( 'Message that appears next to shipping options in Checkout page', WPS_TEXTDOMAIN ),
					'default' => __( '', WPS_TEXTDOMAIN ),
					'desc_tip'    => true
				),
				'hide_store_details' => array(
					'title' => __( 'Hide store details on Checkout', WPS_TEXTDOMAIN ),
					'type' => 'checkbox',
					'label' => __( 'Hide', 'woocommerce' ),
					'default'  => 'no',
					'description' => __( 'Hide selected store details in the Checkout page.', WPS_TEXTDOMAIN ),
					'desc_tip'    => true
				),
				'external_bootstrap' => array(
					'title' => __( 'Disable Bootstrap', WPS_TEXTDOMAIN ),
					'type' => 'checkbox',
					'label' => __( 'Disable', 'woocommerce' ),
					'default'  => 'no',
					'description' => __( 'Disable external Bootstrap library. Current version 3.3.7.', WPS_TEXTDOMAIN ),
					'desc_tip'    => true
				),
				'external_font_awesome' => array(
					'title' => __( 'Disable Font Awesome', WPS_TEXTDOMAIN ),
					'type' => 'checkbox',
					'label' => __( 'Disable', 'woocommerce' ),
					'default'  => 'no',
					'description' => __( 'Disable external Font Awesome library. Current version 4.7.0.', WPS_TEXTDOMAIN ),
					'desc_tip'    => true
				),
				'local_css' => array(
					'title' => __( 'Disable local css', WPS_TEXTDOMAIN ),
					'type' => 'checkbox',
					'label' => __( 'Disable', 'woocommerce' ),
					'default'  => 'no',
					'description' => __( 'Disable WC Pickup Store css library.', WPS_TEXTDOMAIN ),
					'desc_tip'    => true
				),
				'plugin_version' => array(
					'type' => 'plugin_version',
				),
			);
		}

		public function is_available( $package ) {
			$is_available = ($this->enabled == 'yes') ? true : false;

			return apply_filters( 'woocommerce_shipping_' . $this->id . '_is_available', $is_available, $package, $this );
		}

		/**
		 * calculate_shipping function.
		 *
		 * @access public
		 * @param mixed $package
		 * @return void
		 */
		public function calculate_shipping( $package = array() ) {
			$rate = array(
				'id' => $this->id,
				'label' => $this->title,
				'cost' => apply_filters('wps_shipping_costs', $this->wps_get_calculated_costs($this->costs, true)),
				'package' => $package,
				'calc_tax' => 'per_order' // 'per_item'
			);

			// Register the rate
			$this->add_rate( $rate );
		}

		public function generate_store_default_html() {
			ob_start();
			?>
			<tr valign="top">
				<th scope="row" class="titledesc"><?php _e('Default store', WPS_TEXTDOMAIN); ?>:</th>
				<td class="forminp">
					<p><?php
						echo sprintf(__('Find this option in <a href="%s" target="_blank">the Customizer</a>', WPS_TEXTDOMAIN), admin_url('/customize.php?autofocus[section]=wps_store_customize_section'));
					?></p>
				</td>
			</tr>
			<?php
			return ob_get_clean();
		}

		public function generate_plugin_version_html() {
			ob_start();
			?>
			<tr valign="top">
				<td colspan="2" align="right">
					<p><em><?php echo sprintf(__('Version %s', $this->id), WPS_PLUGIN_VERSION); ?></em></p>
				</td>
			</tr>
			<?php
			return ob_get_clean();
		}

		public function wc_reordering_order_item_totals($total_rows, $order, $tax_display) {
			/* Update 1.5.9 */
			$order_id = $order->get_id();
			$store = get_post_meta($order_id, '_shipping_pickup_stores', true);
			$formatted_title = (!empty($this->costs) && $this->costs_per_store != 'yes') ? $this->title . ': ' . wc_price($this->costs) : $this->title;
			$item_label[] = __('Pickup Store', WPS_TEXTDOMAIN);
			if (!empty($this->checkout_notification))
				$item_label[] = $this->checkout_notification;

			if($order->has_shipping_method($this->id) && !empty($store)) {
				foreach ($total_rows as $key => $row) {
					$new_rows[$key] = $row;
					if($key == 'shipping') {
						$new_rows['shipping']['value'] = $formatted_title;
						$new_rows[$this->id] = array(
							'label' => apply_filters('wps_order_shipping_item_label', implode(': ', $item_label), $this->checkout_notification),
							'value' => $store
						);
					}
				}
				$total_rows = $new_rows;
			}

			return $total_rows;
		}

		/**
		** Get calculated costs based on flat/percentage cost type
		**/
		public function wps_get_calculated_costs($shipping_costs, $costs_on_method = false) {
			$store_shipping_cost = (double) (!empty($shipping_costs) && $this->costs_per_store == 'yes') ? $shipping_costs : $this->costs;
			switch ($this->costs_type) {
				case 'flat':
					$costs = (!empty($store_shipping_cost) && $this->costs_per_store == 'yes') ? $store_shipping_cost : 0;

					$costs = (($this->costs_per_store == 'yes' && !$costs_on_method) || ($this->costs_per_store == 'no' && $costs_on_method)) ? $store_shipping_cost : 0;
				break;
				case 'percentage':
					$subtotal = (double) apply_filters('wps_subtotal_for_store_cost', WC()->cart->get_subtotal());
					$costs = (($this->costs_per_store == 'yes' && !$costs_on_method) || ($this->costs_per_store == 'no' && $costs_on_method)) ? ($subtotal * $store_shipping_cost) / 100 : 0;
				break;
				default:
					$costs = 0;
				break;
			}

			return $costs;
		}
	}
	new WC_PICKUP_STORE();
}
add_action('init', 'wps_store_shipping_method_init');

/**
** Returns the main instance for WC_PICKUP_STORE class
**/
function wps() {
	return new WC_PICKUP_STORE();
}