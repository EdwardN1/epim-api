<?php

if (!defined('WPINC')) {
    die;
}

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    function click_collect_method()
    {
        if (!class_exists('click_collect_method')) {
            class click_collect_method extends WC_Shipping_Method
            {
                /**
                 * Constructor for your shipping class
                 *
                 * @access public
                 * @return void
                 */
                public function __construct()
                {
                    $this->id = 'clickcollect';
                    $this->method_title = __('Click & Collect Shipping', 'clickcollect');
                    $this->method_description = __('Custom Shipping Method for Click & Collect', 'clickcollect');

                    $this->init();

                    $this->enabled = isset($this->settings['enabled']) ? $this->settings['enabled'] : 'yes';
                    $this->title = isset($this->settings['title']) ? $this->settings['title'] : __('Click & Collect Shipping', 'clickcollect');
                }

                /**
                 * Init your settings
                 *
                 * @access public
                 * @return void
                 */
                function init()
                {
                    // Load the settings API
                    $this->init_form_fields();
                    $this->init_settings();

                    // Save settings in admin if you have any defined
                    add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
                }

                /**
                 * Define settings field for this shipping
                 * @return void
                 */
                function init_form_fields()
                {

                    // We will add our settings here

                    $this->form_fields = array(

                        'enabled' => array(
                            'title' => __('Enable', 'clickcollect'),
                            'type' => 'checkbox',
                            'description' => __('Enable this shipping.', 'clickcollect'),
                            'default' => 'yes'
                        ),

                        'title' => array(
                            'title' => __('Title', 'clickcollect'),
                            'type' => 'text',
                            'description' => __('Title to be display on site', 'clickcollect'),
                            'default' => __('Click & Collect', 'clickcollect')
                        ),

                        'mincarttotal' => array(
                            'title' => __('Minimum Cart value', 'clickcollect'),
                            'type' => 'number',
                            'description' => __('Minimum Cart value to apply charge (Blank or 0 = Always apply charge)', 'clickcollect'),
                            'default' => 50
                        ),

                        'collectcharge' => array(
                            'title' => __('Collect charge', 'clickcollect'),
                            'type' => 'text',
                            'description' => __('Cost for Click & Collect Service (Blank, NaN or 0 = No Charge)', 'clickcollect'),
                            'default' => 0
                        ),

                    );

                }

                /**
                 * This function is used to calculate the shipping cost. Within this function we can check for weights, dimensions and other parameters.
                 *
                 * @access public
                 * @param mixed $package
                 * @return void
                 */
                public function calculate_shipping($package = array())
                {

                    // We will add the cost, rate and logics in here

                    $cost = $this->settings['collectcharge'];

                    foreach ($package['contents'] as $item_id => $values) {
                        $_product = $values['data'];
                    }

                    $rate = array(
                        'id' => $this->id,
                        'label' => $this->title,
                        'cost' => $cost
                    );

                    $this->add_rate($rate);

                }
            }
        }
    }

    add_action('woocommerce_shipping_init', 'click_collect_method');

    function add_click_collect_method($methods)
    {
        $methods[] = 'click_collect_method';
        return $methods;
    }

    add_filter('woocommerce_shipping_methods', 'add_click_collect_method');

    function cac_store_row_layout()
    {
        $packages = WC()->shipping->get_packages();
        $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
        if( is_array( $chosen_methods ) && in_array( 'clickcollect', $chosen_methods ) ) {

            foreach ($packages as $i => $package) {
                if ($chosen_methods[$i] != "clickcollect") {
                    continue;
                }
                ?>
                <tr class="shipping-cac-store">
                    <th><strong>Select a Branch</strong></th>
                    <td>List of branches to select go here.</td>
                </tr>
                <?php
            }
        }
    }

    add_action('woocommerce_after_shipping_calculator', 'cac_store_row_layout');
    add_action('woocommerce_review_order_after_shipping', 'cac_store_row_layout');
}

