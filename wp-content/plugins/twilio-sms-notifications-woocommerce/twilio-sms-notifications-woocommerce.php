<?php
/*
*		Plugin Name: Twilio SMS Notifications for WooCommerce
*		Plugin URI: https://www.northernbeacheswebsites.com.au
*		Description: Twilio SMS notifications for WooCommerce
*		Version: 1.0
*		Author: Martin Gibson
*		Text Domain: twilio-sms-notifications-woocommerce   
*		Support: https://www.northernbeacheswebsites.com.au/contact
*		Licence: GPL2
*/


/**
* 
*
*
* Get plugin version number
*/
function twilio_sms_notifications_woocommerce_get_version() {
	if ( ! function_exists( 'get_plugins' ) )
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
	$plugin_file = basename( ( __FILE__ ) );
	return $plugin_folder[$plugin_file]['Version'];
}

/**
* 
*
*
* Get available shortcodes
*/
function twilio_sms_notifications_woocommerce_available_shortcodes(){

    $shortcodes = array(
        '[shop_name]',
        '[order_id]',
        '[order_count]',
        '[order_amount]',
        '[order_status]',
        '[billing_name]',
        '[billing_first]',
        '[billing_last]',
        '[shipping_name]',
        '[shipping_method]',
        '[payment_method]',
    );

    //if woocommerce tracking plugin is active also add tracking number in SMS template
    if(is_plugin_active( 'woocommerce-shipment-tracking/woocommerce-shipment-tracking.php' )){
        array_push($shortcodes,'[tracking_provider]','[tracking_number]','[tracking_link]','[date_shipped]');
    }

    //add code tag prefix and suffix to each item in the array
    $shortcodes = preg_filter('/^/', '<code>', $shortcodes);
    $shortcodes = preg_filter('/$/', '</code>', $shortcodes);

    //turn into comma list
    return implode(', ',$shortcodes);

}

/**
* 
*
*
* Load admin style and scripts
*/
function twilio_sms_notifications_woocommerce_register_admin_styles($hook){

    global $pagenow;
    

    if('admin.php' == $pagenow && $_GET['page'] == 'wc-settings' && $_GET['tab'] == 'twilio_sms' ){
        //scripts
        wp_enqueue_script( 'admin-script-twilio-sms', plugins_url( 'adminscript.js', __FILE__ ), array( 'jquery' ),twilio_sms_notifications_woocommerce_get_version());
        wp_enqueue_script( 'alertify-twilio-sms', plugins_url( 'alertify.js', __FILE__ ), array( 'jquery' ),twilio_sms_notifications_woocommerce_get_version(),true);
        //styles
        wp_enqueue_style( 'admin-style-twilio-sms', plugins_url( 'adminstyle.css', __FILE__ ), array(),twilio_sms_notifications_woocommerce_get_version());
    }
    
    
}
add_action( 'admin_enqueue_scripts', 'twilio_sms_notifications_woocommerce_register_admin_styles' );
/**
* 
*
*
* Add custom links to plugin on plugins page
*/
function twilio_sms_notifications_woocommerce_plugin_links( $links, $file ) {
    if ( strpos( $file, 'twilio-sms-notifications-woocommerce.php' ) !== false ) {
       $new_links = array(
                // '<a href="https://northernbeacheswebsites.com.au/product/donate-to-northern-beaches-websites/" target="_blank">' . __('Donate') . '</a>',
                '<a href="https://northernbeacheswebsites.com.au/twilio-sms-notifications-for-woocommerce/" target="_blank">' . __('Product Website') . '</a>',
             );
       $links = array_merge( $links, $new_links );
    }
    return $links;
 }
 add_filter( 'plugin_row_meta', 'twilio_sms_notifications_woocommerce_plugin_links', 10, 2 );
/**
* 
*
*
* Add our new settings tab and settings
*/
class WC_Settings_Twilio_SMS_Notifications_WooCommerce {
    /**
     * Bootstraps the class and hooks required actions & filters.
     *
     */
    public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
        add_action( 'woocommerce_settings_tabs_twilio_sms', __CLASS__ . '::settings_tab' );
        add_action( 'woocommerce_update_options_twilio_sms', __CLASS__ . '::update_settings' );
    }
    
    
    /**
     * Add a new settings tab to the WooCommerce settings tabs array.
     *
     * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
     * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
     */
    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['twilio_sms'] = __( 'Twilio SMS', 'twilio-sms-notifications-woocommerce' );
        return $settings_tabs;
    }
    /**
     * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
     *
     * @uses woocommerce_admin_fields()
     * @uses self::get_settings()
     */
    public static function settings_tab() {
        woocommerce_admin_fields( self::get_settings() );
    }
    /**
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_update_options()
     * @uses self::get_settings()
     */
    public static function update_settings() {
        woocommerce_update_options( self::get_settings() );
    }
    /**
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
    public static function get_settings() {
        $settings = array(

            //updates
            array(
                'name'     => __( 'Licence Settings', 'twilio-sms-notifications-woocommerce' ),
                'type'     => 'title',
            ),
            array(
                'name' => __( 'Order Email', 'twilio-sms-notifications-woocommerce' ),
                'type' => 'text',
                'desc_tip' => __( 'The email used to purchase the plugin.', 'twilio-sms-notifications-woocommerce' ),
                'id'   => 'wc_settings_twilio_sms_order_email'
            ),
            array(
                'name' => __( 'Order ID', 'twilio-sms-notifications-woocommerce' ),
                'type' => 'text',
                'desc_tip' => __( 'This order id number was sent to your email address upon purchase of the plugin.', 'twilio-sms-notifications-woocommerce' ),
                'id'   => 'wc_settings_twilio_sms_order_id'
            ),

            array( 'type' => 'sectionend' ),

            //connection
            array(
                'name'     => __( 'Twilio Connection Settings', 'twilio-sms-notifications-woocommerce' ),
                'type'     => 'title',
            ),
            array(
                'name' => __( 'Twilio Account SID', 'twilio-sms-notifications-woocommerce' ),
                'type' => 'text',
                'desc_tip' => __( 'This can be found on the dashboard page of Twilio (<a target="_blank" href="https://www.twilio.com/console">https://www.twilio.com/console</a>)', 'twilio-sms-notifications-woocommerce' ),
                'id'   => 'wc_settings_twilio_sms_twilio_account_sid'
            ),
            array(
                'name' => __( 'Twilio Auth Token', 'twilio-sms-notifications-woocommerce' ),
                'type' => 'text',
                'desc_tip' => __( 'This can be found on the dashboard page of Twilio (<a target="_blank" href="https://www.twilio.com/console">https://www.twilio.com/console</a>)', 'twilio-sms-notifications-woocommerce' ),
                'id'   => 'wc_settings_twilio_sms_twilio_auth_token'
            ),

            array(
                'name' => __( 'Twilio From Number', 'twilio-sms-notifications-woocommerce' ),
                'type' => 'text',
                'desc_tip' => __( 'This should be entered starting with a + and entered with any spaces or brackets. This can be found on the active number page of Twilio (<a target="_blank" href="https://www.twilio.com/console/phone-numbers/incoming">https://www.twilio.com/console/phone-numbers/incoming</a>)', 'twilio-sms-notifications-woocommerce' ),
                'id'   => 'wc_settings_twilio_sms_twilio_from_number'
            ),

            array( 'type' => 'sectionend' ),

            //opt in
            array(
                'name'     => __( 'Opt-in Settings', 'twilio-sms-notifications-woocommerce' ),
                'type'     => 'title',
            ),

            array(
                'name' => __( 'Opt-in Checkbox Label', 'twilio-sms-notifications-woocommerce' ),
                'type' => 'text',
                'desc_tip' => __( 'Label for the Opt-in checkbox on the Checkout page. Leave blank to disable the opt-in and force ALL customers to receive SMS updates.', 'twilio-sms-notifications-woocommerce' ),
                'default'  => __( 'Please send me order updates via text message', 'twilio-sms-notifications-woocommerce' ),
                'id'   => 'wc_settings_twilio_sms_opt_in_checkbox_label'
            ),

            array(
                'name' => __( 'Opt-in Checkbox Default', 'twilio-sms-notifications-woocommerce' ),
                'type' => 'select',
                'desc_tip' => __( 'Default status for the Opt-in checkbox on the Checkout page.', 'twilio-sms-notifications-woocommerce' ),
                'default'  => 'unchecked',
                'std'      => 'unchecked',
                'id'   => 'wc_settings_twilio_sms_opt_in_checkbox_default',
                'options'  => array(
					'unchecked' => __( 'Unchecked', 'twilio-sms-notifications-woocommerce' ),
					'checked'   => __( 'Checked', 'twilio-sms-notifications-woocommerce' )
				)
            ),

            array( 'type' => 'sectionend' ),


            //admin
            array(
                'name'     => __( 'Admin Notifications Settings', 'twilio-sms-notifications-woocommerce' ),
                'type'     => 'title',
            ),

            array(
				'id'      => 'wc_settings_twilio_sms_enable_admin_sms',
				'name'    => __( 'Enable new order SMS admin notifications.', 'twilio-sms-notifications-woocommerce' ),
				'default' => 'no',
				'type'    => 'checkbox'
			),

			array(
				'id'       => 'wc_settings_twilio_sms_admin_sms_recipients',
				'name'     => __( 'Admin Mobile Number', 'twilio-sms-notifications-woocommerce' ),
                'desc_tip' => __( 'Send to multiple recipients by separating numbers with commas.', 'twilio-sms-notifications-woocommerce' ),
                'desc' => __( 'Enter the mobile number (starting with a + followed by the country code) where the New Order SMS should be sent.', 'twilio-sms-notifications-woocommerce'),
				'type'     => 'text'
			),

			array(
				'id'       => 'wc_settings_twilio_sms_admin_sms_template',
				'name'     => __( 'Admin SMS Message', 'twilio-sms-notifications-woocommerce' ),
				'desc' => sprintf( __( 'Use these tags to customize your message: '.twilio_sms_notifications_woocommerce_available_shortcodes().'. Remember that SMS messages are limited to 160 characters.', 'twilio-sms-notifications-woocommerce' ), '<code>', '</code>' ),
				// 'css'      => 'min-width:500px;',
				'default'  => __( '[shop_name] : You have a new order ([order_id]) for [order_amount]!', 'twilio-sms-notifications-woocommerce' ),
				'type'     => 'textarea'
			),

            array( 'type' => 'sectionend' ),

            //customer
            array(
                'name'     => __( 'Customer Notifications Settings', 'twilio-sms-notifications-woocommerce' ),
                'type'     => 'title',
            ),

        );


        $order_statuses = wc_get_order_statuses();

		$settings[] = array(
			'id'                => 'wc_settings_twilio_sms_customer_order_statuses',
			'name'              => __( 'Order statuses to send SMS notifications for', 'twilio-sms-notifications-woocommerce' ),
			'desc_tip'          => __( 'Orders with these statuses will have SMS notifications sent.', 'twilio-sms-notifications-woocommerce' ),
			'type'              => 'multiselect',
			'options'           => $order_statuses,
			'default'           => array_keys( $order_statuses ),
			'class'             => 'wc-enhanced-select',
			'css'               => 'min-width: 250px',
			'custom_attributes' => array(
				'data-placeholder' => __( 'Select statuses to automatically send notifications', 'twilio-sms-notifications-woocommerce' ),
			),
		);

		$settings[] = array(
			'id'       => 'wc_settings_twilio_sms_customer_sms_template',
			'name'     => __( 'Default Customer SMS Message', 'twilio-sms-notifications-woocommerce' ),
			/* translators: %1$s is <code>, %2$s is </code> */
			'desc' => sprintf( __( 'Use these tags to customize your message: '.twilio_sms_notifications_woocommerce_available_shortcodes().'. Remember that SMS messages are limited to 160 characters.', 'twilio-sms-notifications-woocommerce' ), '<code>', '</code>' ),
			'css'      => 'min-width:500px;',
			'default'  => __( '[shop_name] : Your order ([order_id]) is now [order_status].', 'twilio-sms-notifications-woocommerce' ),
			'type'     => 'textarea'
		);

		// Display a textarea setting for each available order status
		foreach( $order_statuses as $slug => $label ) {

			$slug = 'wc-' === substr( $slug, 0, 3 ) ? substr( $slug, 3 ) : $slug;

			$settings[] = array(
				'id'       => 'wc_settings_twilio_sms_' . $slug . '_sms_template',
				'name'     => sprintf( __( '%s SMS Message', 'twilio-sms-notifications-woocommerce' ), $label ),
				'desc_tip' => sprintf( __( 'Add a custom SMS message for %s orders or leave blank to use the default message above.', 'twilio-sms-notifications-woocommerce' ), $slug ),
				'css'      => 'min-width:500px;',
				'type'     => 'textarea'
			);
		}

        $settings = array_merge( $settings, array(

            array( 'type' => 'sectionend' ),

            array(
                'name'     => __( 'Send Test SMS', 'twilio-sms-notifications-woocommerce' ),
                'type'     => 'title',
            ),
            array(
                'name' => __( 'Mobile Number', 'twilio-sms-notifications-woocommerce' ),
                'type' => 'text',
                'desc_tip' => __( 'Enter the mobile number starting with a + followed by the country code.', 'twilio-sms-notifications-woocommerce'),
                'id'   => 'wc_settings_twilio_sms_test_mobile_number'
            ),
            array(
                'name' => __( 'Message', 'twilio-sms-notifications-woocommerce' ),
                'type' => 'textarea',
                'id'   => 'wc_settings_twilio_sms_test_message'
            ),

            array( 'type' => 'sectionend' ),

        ) );

      
        return apply_filters( 'wc_settings_twilio_sms', $settings );
    }
}
WC_Settings_Twilio_SMS_Notifications_WooCommerce::init();
/**
* 
*
*
* Add checkbox to woocommerce checkout
*/
function twilio_sms_notifications_woocommerce_add_opt_in_checkbox() {

    // use previous value or default value when loading checkout page
    if ( ! empty( $_POST['wc_twilio_sms_optin'] ) ) {
        $value = wc_clean( $_POST['wc_twilio_sms_optin'] );
    } else {
        $value = ( 'checked' === get_option( 'wc_settings_twilio_sms_opt_in_checkbox_default', 'unchecked' ) ) ? 1 : 0;
    }

    $optin_label = get_option( 'wc_settings_twilio_sms_opt_in_checkbox_label', '' );

    if ( ! empty( $optin_label ) ) {

        // output checkbox
        woocommerce_form_field( 'wc_twilio_sms_optin', array(
            'type'  => 'checkbox',
            'class' => array( 'form-row-wide' ),
            'label' => $optin_label,
        ), $value );
    }
}

add_action( 'woocommerce_after_checkout_billing_form', 'twilio_sms_notifications_woocommerce_add_opt_in_checkbox' );
/**
* 
*
*
* Save the checkbox value to the order meta
*/
function twilio_sms_notifications_woocommerce_process_opt_in_checkbox( $order_id ) {
    if ( ! empty( $_POST['wc_twilio_sms_optin'] ) ) {
        update_post_meta( $order_id, 'wc_twilio_sms_optin', 1 );
    }
}

add_action( 'woocommerce_checkout_update_order_meta', 'twilio_sms_notifications_woocommerce_process_opt_in_checkbox' );
/**
* 
*
*
* Lets send the admin notification if enabled
*/
//loop through the action because there can be many cases of when the notification could occur
foreach ( array( 'pending_to_on-hold', 'pending_to_processing', 'pending_to_completed', 'failed_to_on-hold', 'failed_to_processing', 'failed_to_completed' ) as $status ) {
    add_action( 'woocommerce_order_status_' . $status, 'twilio_sms_notifications_woocommerce_send_admin_order_notification' );
}
//send the notification
function twilio_sms_notifications_woocommerce_send_admin_order_notification( $order_id ) {

    //only send if admin notifications are enabled and a phone number and message exist
    $message = get_option( 'wc_settings_twilio_sms_admin_sms_template' );
    $recipients = trim(get_option( 'wc_settings_twilio_sms_admin_sms_recipients' ));

    if('yes' === get_option( 'wc_settings_twilio_sms_enable_admin_sms' )  && strlen($recipients) > 0  && strlen($message) > 0 ){

        //replace the variables in the message
        $messageWithReplacedShortcodes = twilio_sms_notifications_woocommerce_replace_shortcodes($message, $order_id );

        $recipientsExploded = explode( ',', $recipients);

        foreach($recipientsExploded as $recipient){
            twilio_sms_notifications_woocommerce_send_sms($recipient,$messageWithReplacedShortcodes);
        }
    }
}
/**
* 
*
*
* Lets send the customer notification
*/
//loop through the action for each status
$statuses = array('wc-pending' ,'wc-processing', 'wc-on-hold', 'wc-completed','wc-cancelled','wc-refunded', 'wc-failed' );

foreach ($statuses as $status) {

    $status_slug = ( 'wc-' === substr( $status, 0, 3 ) ) ? substr( $status, 3 ) : $status;

    add_action( 'woocommerce_order_status_' . $status_slug, 'twilio_sms_notifications_woocommerce_send_customer_order_notification');
}
//send the notification
function twilio_sms_notifications_woocommerce_send_customer_order_notification( $order_id ) {


    //get the order object
    $order = wc_get_order( $order_id );
    $customerOptIn = get_post_meta($order_id,'wc_twilio_sms_optin',true);

    if( strlen(get_option( 'wc_settings_twilio_sms_opt_in_checkbox_label' )) < 1 || $customerOptIn == 1 ){


        if( in_array('wc-'.$order->get_status(),get_option( 'wc_settings_twilio_sms_customer_order_statuses' ) ) ){

            $message = get_option( 'wc_settings_twilio_sms_' . $order->get_status() . '_sms_template', '' );

            //if theres no custom message get the default
            if(empty($message)){
                $message = get_option( 'wc_settings_twilio_sms_customer_sms_template');
            }

            $messageWithReplacedShortcodes = twilio_sms_notifications_woocommerce_replace_shortcodes($message, $order_id );

            if( strlen($messageWithReplacedShortcodes) > 0 && strlen($order->get_billing_phone()) > 0 ){

                //for customers we need to do some logic to make sure the phone number is twilio friendly
                $goodPhoneNumber = twilio_sms_notifications_woocommerce_make_number_sendable($order->get_billing_phone(),$order_id);
                twilio_sms_notifications_woocommerce_send_sms($goodPhoneNumber,$messageWithReplacedShortcodes);
            }



        }

    }

}    




/**
* 
*
*
* Function to replace message shortcodes with actual order information
*/
function twilio_sms_notifications_woocommerce_replace_shortcodes($message, $order_id ) {

    //get the order object
    $order = wc_get_order( $order_id );



    $replacementArray = array(
        '[shop_name]'       => get_bloginfo( 'name' ),
        '[order_id]'        => $order->get_id(),
        '[order_count]'     => $order->get_item_count(),
        '[order_amount]'    => $order->get_total(),
        '[order_status]'    => ucfirst($order->get_status()),
        '[billing_name]'    => $order->get_formatted_billing_full_name(),
        '[shipping_name]'   => $order->get_formatted_shipping_full_name(),
        '[shipping_method]' => $order->get_shipping_method(),
        '[billing_first]'   => $order->get_billing_first_name(),
        '[billing_last]'    => $order->get_billing_last_name(),
        '[payment_method]'  => $order->get_payment_method_title(),
    );

    foreach($replacementArray as $key => $value){
        $message = str_replace($key,$value,$message);
    }

    //do additional shortcode replacement if using tracking plugin
    if(is_plugin_active( 'woocommerce-shipment-tracking/woocommerce-shipment-tracking.php' )){

        $tracking_data = $order->get_meta( '_wc_shipment_tracking_items', true );




        //we need to get the tracking link

        if(strlen($tracking_data[0]['custom_tracking_link'])>0){
            $tracking_link = $tracking_data[0]['custom_tracking_link'];
        } else {
            //no custom tracking link provided we need to make up the link
            //create variable which stores all our links
            $tracking_links = array(
                'Australia Post'   => 'http://auspost.com.au/track/track.html?id=%1$s',
                'Fastway Couriers' => 'http://www.fastway.com.au/courier-services/track-your-parcel?l=%1$s',
                'post.at' => 'https://www.post.at/sv/sendungsdetails?snr=%1$s',
                'dhl.at'  => 'http://www.dhl.at/content/at/de/express/sendungsverfolgung.html?brand=DHL&AWB=%1$s',
                'DPD.at'  => 'https://tracking.dpd.de/parcelstatus?locale=de_AT&query=%1$s',
                'Correios' => 'http://websro.correios.com.br/sro_bin/txect01$.QueryList?P_LINGUA=001&P_TIPO=001&P_COD_UNI=%1$s',
                'bpost' => 'https://track.bpost.be/btr/web/#/search?itemCode=%1$s',
                'Canada Post' => 'http://www.canadapost.ca/cpotools/apps/track/personal/findByTrackNumber?trackingNumber=%1$s',
                'PPL.cz'      => 'http://www.ppl.cz/main2.aspx?cls=Package&idSearch=%1$s',
                'Česká pošta' => 'https://www.postaonline.cz/trackandtrace/-/zasilka/cislo?parcelNumbers=%1$s',
                'DHL.cz'      => 'http://www.dhl.cz/cs/express/sledovani_zasilek.html?AWB=%1$s',
                'DPD.cz'      => 'https://tracking.dpd.de/parcelstatus?locale=cs_CZ&query=%1$s',
                'Itella' => 'http://www.posti.fi/itemtracking/posti/search_by_shipment_id?lang=en&ShipmentId=%1$s',
                'Colissimo' => 'http://www.colissimo.fr/portail_colissimo/suivre.do?language=fr_FR&colispart=%1$s',
                'DHL Intraship (DE)' => 'http://nolp.dhl.de/nextt-online-public/set_identcodes.do?lang=de&idc=%1$s&rfn=&extendedSearch=true',
                'Hermes'             => 'https://tracking.hermesworld.com/?TrackID=%1$s',
                'Deutsche Post DHL'  => 'http://nolp.dhl.de/nextt-online-public/set_identcodes.do?lang=de&idc=%1$s',
                'UPS Germany'        => 'http://wwwapps.ups.com/WebTracking/processInputRequest?sort_by=status&tracknums_displayed=1&TypeOfInquiryNumber=T&loc=de_DE&InquiryNumber1=%1$s',
                'DPD.de'             => 'https://tracking.dpd.de/parcelstatus?query=%1$s&locale=en_DE',
                'DPD.ie'  => 'http://www2.dpd.ie/Services/QuickTrack/tabid/222/ConsignmentID/%1$s/Default.aspx',
                'An Post' => 'https://track.anpost.ie/TrackingResults.aspx?rtt=1&items=%1$s',
                'BRT (Bartolini)' => 'http://as777.brt.it/vas/sped_det_show.hsm?referer=sped_numspe_par.htm&Nspediz=%1$s',
                'DHL Express'     => 'http://www.dhl.it/it/express/ricerca.html?AWB=%1$s&brand=DHL',
                'DTDC' => 'http://www.dtdc.in/tracking/tracking_results.asp?Ttype=awb_no&strCnno=%1$s&TrkType2=awb_no',
                'PostNL' => 'https://postnl.nl/tracktrace/?B=%1$s&P=%2$s&D=%3$s&T=C',
                'DPD.NL' => 'http://track.dpdnl.nl/?parcelnumber=%1$s',
                'UPS Netherlands'        => 'http://wwwapps.ups.com/WebTracking/processInputRequest?sort_by=status&tracknums_displayed=1&TypeOfInquiryNumber=T&loc=nl_NL&InquiryNumber1=%1$s',
                'Courier Post' => 'http://trackandtrace.courierpost.co.nz/Search/%1$s',
                'NZ Post'      => 'http://www.nzpost.co.nz/tools/tracking?trackid=%1$s',
                'Fastways'     => 'http://www.fastway.co.nz/courier-services/track-your-parcel?l=%1$s',
                'PBT Couriers' => 'http://www.pbt.com/nick/results.cfm?ticketNo=%1$s',
                'InPost' => 'https://inpost.pl/sledzenie-przesylek?number=%1$s',
                'DPD.PL' => 'https://tracktrace.dpd.com.pl/parcelDetails?p1=%1$s',
                'Poczta Polska' => 'https://emonitoring.poczta-polska.pl/?numer=%1$s',
                'Fan Courier'      => 'https://www.fancourier.ro/awb-tracking/?xawb=%1$s',
                'DPD Romania'     => 'https://tracking.dpd.de/parcelstatus?query=%1$s&locale=ro_RO',
                'Urgent Cargus' => 'https://app.urgentcargus.ro/Private/Tracking.aspx?CodBara=%1$s',
                'SAPO' => 'http://sms.postoffice.co.za/TrackingParcels/Parcel.aspx?id=%1$s',
                'Fastway' => 'http://www.fastway.co.za/our-services/track-your-parcel?l=%1$s',
                'PostNord Sverige AB' => 'http://www.postnord.se/sv/verktyg/sok/Sidor/spara-brev-paket-och-pall.aspx?search=%1$s',
                'DHL.se'              => 'http://www.dhl.se/content/se/sv/express/godssoekning.shtml?brand=DHL&AWB=%1$s',
                'Bring.se'            => 'http://tracking.bring.se/tracking.html?q=%1$s',
                'UPS.se'              => 'http://wwwapps.ups.com/WebTracking/track?track=yes&loc=sv_SE&trackNums=%1$s',
                'DB Schenker'         => 'http://privpakportal.schenker.nu/TrackAndTrace/packagesearch.aspx?packageId=%1$s',
                'DHL'                       => 'http://www.dhl.com/content/g0/en/express/tracking.shtml?brand=DHL&AWB=%1$s',
                'DPD.co.uk'                 => 'http://www.dpd.co.uk/tracking/trackingSearch.do?search.searchType=0&search.parcelNumber=%1$s',
                'InterLink'                 => 'http://www.interlinkexpress.com/apps/tracking/?reference=%1$s&postcode=%2$s#results',
                'ParcelForce'               => 'http://www.parcelforce.com/portal/pw/track?trackNumber=%1$s',
                'Royal Mail'                => 'https://www.royalmail.com/track-your-item/?trackNumber=%1$s',
                'TNT Express (consignment)' => 'http://www.tnt.com/webtracker/tracking.do?requestType=GEN&searchType=CON&respLang=en&respCountry=GENERIC&sourceID=1&sourceCountry=ww&cons=%1$s&navigation=1&g
    enericSiteIdent=',
                'TNT Express (reference)'   => 'http://www.tnt.com/webtracker/tracking.do?requestType=GEN&searchType=REF&respLang=en&respCountry=GENERIC&sourceID=1&sourceCountry=ww&cons=%1$s&navigation=1&genericSiteIdent=',
                'DHL Parcel UK'             => 'https://track.dhlparcel.co.uk/?con=%1$s',
                'Fedex'         => 'http://www.fedex.com/Tracking?action=track&tracknumbers=%1$s',
                'FedEx Sameday' => 'https://www.fedexsameday.com/fdx_dotracking_ua.aspx?tracknum=%1$s',
                'OnTrac'        => 'http://www.ontrac.com/trackingdetail.asp?tracking=%1$s',
                'UPS'           => 'http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=%1$s',
                'USPS'          => 'https://tools.usps.com/go/TrackConfirmAction_input?qtc_tLabels1=%1$s',
                'DHL US'        => 'https://www.logistics.dhl/us-en/home/tracking/tracking-ecommerce.html?tracking-id=%1$s',
            );

            //we need to loop through all the tracking links and find a match
            foreach($tracking_links as $tracking_provider => $tracking_provider_link){
                if($tracking_provider == $tracking_data[0]['tracking_provider']){
                    //we found a match
                    $tracking_link = str_replace('%1$s',$tracking_data[0]['tracking_number'],$tracking_provider_link);
                }
            }

        }

        if(!isset($tracking_link)){
            $tracking_link = '';
        }

        
        

        $replacementArray = array(
            '[tracking_provider]'       => $tracking_data[0]['tracking_provider'],
            '[tracking_number]'         => $tracking_data[0]['tracking_number'],
            '[date_shipped]'            => $tracking_data[0]['date_shipped'],
            '[tracking_link]'            => $tracking_link,
        );
    
        foreach($replacementArray as $key => $value){
            $message = str_replace($key,$value,$message);
        }    

    }

    return $message;

}   
/**
* 
*
*
* Send sms
*/
function twilio_sms_notifications_woocommerce_send_sms($recipient,$message){

    //the message has been sent, lets add this to the log
    $logger = wc_get_logger();
    $context = array( 'source' => 'twilio-sms-notifications-woocommerce' );

    $logger->info( 'Attempting to send message to: '.$recipient.' with the message: '.$message, $context );

    //get options from settings
    $twilio_account_sid = get_option( 'wc_settings_twilio_sms_twilio_account_sid' );
    $twilio_auth_token = get_option( 'wc_settings_twilio_sms_twilio_auth_token' );
    $twilio_from_number = get_option( 'wc_settings_twilio_sms_twilio_from_number' );

    $authorization = base64_encode($twilio_account_sid.':'.$twilio_auth_token);

    $response = wp_remote_post( 'https://api.twilio.com/2010-04-01/Accounts/'.$twilio_account_sid.'/Messages.json', array(
        'headers' => array(
            'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8',
            'Authorization' => 'Basic '.$authorization,
            'Content-Type' => 'application/x-www-form-urlencoded',
        ),
        'body' => array(
            'Body' => $message,
            'To' => $recipient,
            'From' => $twilio_from_number,
        ),
    ));

    $status = wp_remote_retrieve_response_code( $response );

    if($status == 201){
        $jsondata = json_decode(preg_replace('/("\w+"):(\d+(\.\d+)?)/', '\\1:"\\2"', wp_remote_retrieve_body($response)), true);
        $message_id = $jsondata['sid']; 

        $logger->info( 'SMS Sent! Message ID: '.$message_id, $context );
    } else {
        $error_message = wp_remote_retrieve_response_message( $response );
        $logger->error( 'There was an error: '.$error_message, $context ); 
    }

} 

/**
* 
*
*
* Make the phone number sendable
*/
function twilio_sms_notifications_woocommerce_make_number_sendable($phoneNumber,$order_id){

    $phoneNumber = trim($phoneNumber); 
    
    //if the phone number starts with a plus then assume its good
    if( substr($phoneNumber, 0, 1) == '+' ){
        return $phoneNumber;
    } else {

        //get order object
        $order = wc_get_order( $order_id );
        $billingCountry = $order->get_billing_country();

        $countryCodesArray = array(
            'AD'=>array('name'=>'ANDORRA','code'=>'376'),
            'AE'=>array('name'=>'UNITED ARAB EMIRATES','code'=>'971'),
            'AF'=>array('name'=>'AFGHANISTAN','code'=>'93'),
            'AG'=>array('name'=>'ANTIGUA AND BARBUDA','code'=>'1268'),
            'AI'=>array('name'=>'ANGUILLA','code'=>'1264'),
            'AL'=>array('name'=>'ALBANIA','code'=>'355'),
            'AM'=>array('name'=>'ARMENIA','code'=>'374'),
            'AN'=>array('name'=>'NETHERLANDS ANTILLES','code'=>'599'),
            'AO'=>array('name'=>'ANGOLA','code'=>'244'),
            'AQ'=>array('name'=>'ANTARCTICA','code'=>'672'),
            'AR'=>array('name'=>'ARGENTINA','code'=>'54'),
            'AS'=>array('name'=>'AMERICAN SAMOA','code'=>'1684'),
            'AT'=>array('name'=>'AUSTRIA','code'=>'43'),
            'AU'=>array('name'=>'AUSTRALIA','code'=>'61'),
            'AW'=>array('name'=>'ARUBA','code'=>'297'),
            'AZ'=>array('name'=>'AZERBAIJAN','code'=>'994'),
            'BA'=>array('name'=>'BOSNIA AND HERZEGOVINA','code'=>'387'),
            'BB'=>array('name'=>'BARBADOS','code'=>'1246'),
            'BD'=>array('name'=>'BANGLADESH','code'=>'880'),
            'BE'=>array('name'=>'BELGIUM','code'=>'32'),
            'BF'=>array('name'=>'BURKINA FASO','code'=>'226'),
            'BG'=>array('name'=>'BULGARIA','code'=>'359'),
            'BH'=>array('name'=>'BAHRAIN','code'=>'973'),
            'BI'=>array('name'=>'BURUNDI','code'=>'257'),
            'BJ'=>array('name'=>'BENIN','code'=>'229'),
            'BL'=>array('name'=>'SAINT BARTHELEMY','code'=>'590'),
            'BM'=>array('name'=>'BERMUDA','code'=>'1441'),
            'BN'=>array('name'=>'BRUNEI DARUSSALAM','code'=>'673'),
            'BO'=>array('name'=>'BOLIVIA','code'=>'591'),
            'BR'=>array('name'=>'BRAZIL','code'=>'55'),
            'BS'=>array('name'=>'BAHAMAS','code'=>'1242'),
            'BT'=>array('name'=>'BHUTAN','code'=>'975'),
            'BW'=>array('name'=>'BOTSWANA','code'=>'267'),
            'BY'=>array('name'=>'BELARUS','code'=>'375'),
            'BZ'=>array('name'=>'BELIZE','code'=>'501'),
            'CA'=>array('name'=>'CANADA','code'=>'1'),
            'CC'=>array('name'=>'COCOS (KEELING) ISLANDS','code'=>'61'),
            'CD'=>array('name'=>'CONGO, THE DEMOCRATIC REPUBLIC OF THE','code'=>'243'),
            'CF'=>array('name'=>'CENTRAL AFRICAN REPUBLIC','code'=>'236'),
            'CG'=>array('name'=>'CONGO','code'=>'242'),
            'CH'=>array('name'=>'SWITZERLAND','code'=>'41'),
            'CI'=>array('name'=>'COTE D IVOIRE','code'=>'225'),
            'CK'=>array('name'=>'COOK ISLANDS','code'=>'682'),
            'CL'=>array('name'=>'CHILE','code'=>'56'),
            'CM'=>array('name'=>'CAMEROON','code'=>'237'),
            'CN'=>array('name'=>'CHINA','code'=>'86'),
            'CO'=>array('name'=>'COLOMBIA','code'=>'57'),
            'CR'=>array('name'=>'COSTA RICA','code'=>'506'),
            'CU'=>array('name'=>'CUBA','code'=>'53'),
            'CV'=>array('name'=>'CAPE VERDE','code'=>'238'),
            'CX'=>array('name'=>'CHRISTMAS ISLAND','code'=>'61'),
            'CY'=>array('name'=>'CYPRUS','code'=>'357'),
            'CZ'=>array('name'=>'CZECH REPUBLIC','code'=>'420'),
            'DE'=>array('name'=>'GERMANY','code'=>'49'),
            'DJ'=>array('name'=>'DJIBOUTI','code'=>'253'),
            'DK'=>array('name'=>'DENMARK','code'=>'45'),
            'DM'=>array('name'=>'DOMINICA','code'=>'1767'),
            'DO'=>array('name'=>'DOMINICAN REPUBLIC','code'=>'1809'),
            'DZ'=>array('name'=>'ALGERIA','code'=>'213'),
            'EC'=>array('name'=>'ECUADOR','code'=>'593'),
            'EE'=>array('name'=>'ESTONIA','code'=>'372'),
            'EG'=>array('name'=>'EGYPT','code'=>'20'),
            'ER'=>array('name'=>'ERITREA','code'=>'291'),
            'ES'=>array('name'=>'SPAIN','code'=>'34'),
            'ET'=>array('name'=>'ETHIOPIA','code'=>'251'),
            'FI'=>array('name'=>'FINLAND','code'=>'358'),
            'FJ'=>array('name'=>'FIJI','code'=>'679'),
            'FK'=>array('name'=>'FALKLAND ISLANDS (MALVINAS)','code'=>'500'),
            'FM'=>array('name'=>'MICRONESIA, FEDERATED STATES OF','code'=>'691'),
            'FO'=>array('name'=>'FAROE ISLANDS','code'=>'298'),
            'FR'=>array('name'=>'FRANCE','code'=>'33'),
            'GA'=>array('name'=>'GABON','code'=>'241'),
            'GB'=>array('name'=>'UNITED KINGDOM','code'=>'44'),
            'GD'=>array('name'=>'GRENADA','code'=>'1473'),
            'GE'=>array('name'=>'GEORGIA','code'=>'995'),
            'GH'=>array('name'=>'GHANA','code'=>'233'),
            'GI'=>array('name'=>'GIBRALTAR','code'=>'350'),
            'GL'=>array('name'=>'GREENLAND','code'=>'299'),
            'GM'=>array('name'=>'GAMBIA','code'=>'220'),
            'GN'=>array('name'=>'GUINEA','code'=>'224'),
            'GQ'=>array('name'=>'EQUATORIAL GUINEA','code'=>'240'),
            'GR'=>array('name'=>'GREECE','code'=>'30'),
            'GT'=>array('name'=>'GUATEMALA','code'=>'502'),
            'GU'=>array('name'=>'GUAM','code'=>'1671'),
            'GW'=>array('name'=>'GUINEA-BISSAU','code'=>'245'),
            'GY'=>array('name'=>'GUYANA','code'=>'592'),
            'HK'=>array('name'=>'HONG KONG','code'=>'852'),
            'HN'=>array('name'=>'HONDURAS','code'=>'504'),
            'HR'=>array('name'=>'CROATIA','code'=>'385'),
            'HT'=>array('name'=>'HAITI','code'=>'509'),
            'HU'=>array('name'=>'HUNGARY','code'=>'36'),
            'ID'=>array('name'=>'INDONESIA','code'=>'62'),
            'IE'=>array('name'=>'IRELAND','code'=>'353'),
            'IL'=>array('name'=>'ISRAEL','code'=>'972'),
            'IM'=>array('name'=>'ISLE OF MAN','code'=>'44'),
            'IN'=>array('name'=>'INDIA','code'=>'91'),
            'IQ'=>array('name'=>'IRAQ','code'=>'964'),
            'IR'=>array('name'=>'IRAN, ISLAMIC REPUBLIC OF','code'=>'98'),
            'IS'=>array('name'=>'ICELAND','code'=>'354'),
            'IT'=>array('name'=>'ITALY','code'=>'39'),
            'JM'=>array('name'=>'JAMAICA','code'=>'1876'),
            'JO'=>array('name'=>'JORDAN','code'=>'962'),
            'JP'=>array('name'=>'JAPAN','code'=>'81'),
            'KE'=>array('name'=>'KENYA','code'=>'254'),
            'KG'=>array('name'=>'KYRGYZSTAN','code'=>'996'),
            'KH'=>array('name'=>'CAMBODIA','code'=>'855'),
            'KI'=>array('name'=>'KIRIBATI','code'=>'686'),
            'KM'=>array('name'=>'COMOROS','code'=>'269'),
            'KN'=>array('name'=>'SAINT KITTS AND NEVIS','code'=>'1869'),
            'KP'=>array('name'=>'KOREA DEMOCRATIC PEOPLES REPUBLIC OF','code'=>'850'),
            'KR'=>array('name'=>'KOREA REPUBLIC OF','code'=>'82'),
            'KW'=>array('name'=>'KUWAIT','code'=>'965'),
            'KY'=>array('name'=>'CAYMAN ISLANDS','code'=>'1345'),
            'KZ'=>array('name'=>'KAZAKSTAN','code'=>'7'),
            'LA'=>array('name'=>'LAO PEOPLES DEMOCRATIC REPUBLIC','code'=>'856'),
            'LB'=>array('name'=>'LEBANON','code'=>'961'),
            'LC'=>array('name'=>'SAINT LUCIA','code'=>'1758'),
            'LI'=>array('name'=>'LIECHTENSTEIN','code'=>'423'),
            'LK'=>array('name'=>'SRI LANKA','code'=>'94'),
            'LR'=>array('name'=>'LIBERIA','code'=>'231'),
            'LS'=>array('name'=>'LESOTHO','code'=>'266'),
            'LT'=>array('name'=>'LITHUANIA','code'=>'370'),
            'LU'=>array('name'=>'LUXEMBOURG','code'=>'352'),
            'LV'=>array('name'=>'LATVIA','code'=>'371'),
            'LY'=>array('name'=>'LIBYAN ARAB JAMAHIRIYA','code'=>'218'),
            'MA'=>array('name'=>'MOROCCO','code'=>'212'),
            'MC'=>array('name'=>'MONACO','code'=>'377'),
            'MD'=>array('name'=>'MOLDOVA, REPUBLIC OF','code'=>'373'),
            'ME'=>array('name'=>'MONTENEGRO','code'=>'382'),
            'MF'=>array('name'=>'SAINT MARTIN','code'=>'1599'),
            'MG'=>array('name'=>'MADAGASCAR','code'=>'261'),
            'MH'=>array('name'=>'MARSHALL ISLANDS','code'=>'692'),
            'MK'=>array('name'=>'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF','code'=>'389'),
            'ML'=>array('name'=>'MALI','code'=>'223'),
            'MM'=>array('name'=>'MYANMAR','code'=>'95'),
            'MN'=>array('name'=>'MONGOLIA','code'=>'976'),
            'MO'=>array('name'=>'MACAU','code'=>'853'),
            'MP'=>array('name'=>'NORTHERN MARIANA ISLANDS','code'=>'1670'),
            'MR'=>array('name'=>'MAURITANIA','code'=>'222'),
            'MS'=>array('name'=>'MONTSERRAT','code'=>'1664'),
            'MT'=>array('name'=>'MALTA','code'=>'356'),
            'MU'=>array('name'=>'MAURITIUS','code'=>'230'),
            'MV'=>array('name'=>'MALDIVES','code'=>'960'),
            'MW'=>array('name'=>'MALAWI','code'=>'265'),
            'MX'=>array('name'=>'MEXICO','code'=>'52'),
            'MY'=>array('name'=>'MALAYSIA','code'=>'60'),
            'MZ'=>array('name'=>'MOZAMBIQUE','code'=>'258'),
            'NA'=>array('name'=>'NAMIBIA','code'=>'264'),
            'NC'=>array('name'=>'NEW CALEDONIA','code'=>'687'),
            'NE'=>array('name'=>'NIGER','code'=>'227'),
            'NG'=>array('name'=>'NIGERIA','code'=>'234'),
            'NI'=>array('name'=>'NICARAGUA','code'=>'505'),
            'NL'=>array('name'=>'NETHERLANDS','code'=>'31'),
            'NO'=>array('name'=>'NORWAY','code'=>'47'),
            'NP'=>array('name'=>'NEPAL','code'=>'977'),
            'NR'=>array('name'=>'NAURU','code'=>'674'),
            'NU'=>array('name'=>'NIUE','code'=>'683'),
            'NZ'=>array('name'=>'NEW ZEALAND','code'=>'64'),
            'OM'=>array('name'=>'OMAN','code'=>'968'),
            'PA'=>array('name'=>'PANAMA','code'=>'507'),
            'PE'=>array('name'=>'PERU','code'=>'51'),
            'PF'=>array('name'=>'FRENCH POLYNESIA','code'=>'689'),
            'PG'=>array('name'=>'PAPUA NEW GUINEA','code'=>'675'),
            'PH'=>array('name'=>'PHILIPPINES','code'=>'63'),
            'PK'=>array('name'=>'PAKISTAN','code'=>'92'),
            'PL'=>array('name'=>'POLAND','code'=>'48'),
            'PM'=>array('name'=>'SAINT PIERRE AND MIQUELON','code'=>'508'),
            'PN'=>array('name'=>'PITCAIRN','code'=>'870'),
            'PR'=>array('name'=>'PUERTO RICO','code'=>'1'),
            'PT'=>array('name'=>'PORTUGAL','code'=>'351'),
            'PW'=>array('name'=>'PALAU','code'=>'680'),
            'PY'=>array('name'=>'PARAGUAY','code'=>'595'),
            'QA'=>array('name'=>'QATAR','code'=>'974'),
            'RO'=>array('name'=>'ROMANIA','code'=>'40'),
            'RS'=>array('name'=>'SERBIA','code'=>'381'),
            'RU'=>array('name'=>'RUSSIAN FEDERATION','code'=>'7'),
            'RW'=>array('name'=>'RWANDA','code'=>'250'),
            'SA'=>array('name'=>'SAUDI ARABIA','code'=>'966'),
            'SB'=>array('name'=>'SOLOMON ISLANDS','code'=>'677'),
            'SC'=>array('name'=>'SEYCHELLES','code'=>'248'),
            'SD'=>array('name'=>'SUDAN','code'=>'249'),
            'SE'=>array('name'=>'SWEDEN','code'=>'46'),
            'SG'=>array('name'=>'SINGAPORE','code'=>'65'),
            'SH'=>array('name'=>'SAINT HELENA','code'=>'290'),
            'SI'=>array('name'=>'SLOVENIA','code'=>'386'),
            'SK'=>array('name'=>'SLOVAKIA','code'=>'421'),
            'SL'=>array('name'=>'SIERRA LEONE','code'=>'232'),
            'SM'=>array('name'=>'SAN MARINO','code'=>'378'),
            'SN'=>array('name'=>'SENEGAL','code'=>'221'),
            'SO'=>array('name'=>'SOMALIA','code'=>'252'),
            'SR'=>array('name'=>'SURINAME','code'=>'597'),
            'ST'=>array('name'=>'SAO TOME AND PRINCIPE','code'=>'239'),
            'SV'=>array('name'=>'EL SALVADOR','code'=>'503'),
            'SY'=>array('name'=>'SYRIAN ARAB REPUBLIC','code'=>'963'),
            'SZ'=>array('name'=>'SWAZILAND','code'=>'268'),
            'TC'=>array('name'=>'TURKS AND CAICOS ISLANDS','code'=>'1649'),
            'TD'=>array('name'=>'CHAD','code'=>'235'),
            'TG'=>array('name'=>'TOGO','code'=>'228'),
            'TH'=>array('name'=>'THAILAND','code'=>'66'),
            'TJ'=>array('name'=>'TAJIKISTAN','code'=>'992'),
            'TK'=>array('name'=>'TOKELAU','code'=>'690'),
            'TL'=>array('name'=>'TIMOR-LESTE','code'=>'670'),
            'TM'=>array('name'=>'TURKMENISTAN','code'=>'993'),
            'TN'=>array('name'=>'TUNISIA','code'=>'216'),
            'TO'=>array('name'=>'TONGA','code'=>'676'),
            'TR'=>array('name'=>'TURKEY','code'=>'90'),
            'TT'=>array('name'=>'TRINIDAD AND TOBAGO','code'=>'1868'),
            'TV'=>array('name'=>'TUVALU','code'=>'688'),
            'TW'=>array('name'=>'TAIWAN, PROVINCE OF CHINA','code'=>'886'),
            'TZ'=>array('name'=>'TANZANIA, UNITED REPUBLIC OF','code'=>'255'),
            'UA'=>array('name'=>'UKRAINE','code'=>'380'),
            'UG'=>array('name'=>'UGANDA','code'=>'256'),
            'US'=>array('name'=>'UNITED STATES','code'=>'1'),
            'UY'=>array('name'=>'URUGUAY','code'=>'598'),
            'UZ'=>array('name'=>'UZBEKISTAN','code'=>'998'),
            'VA'=>array('name'=>'HOLY SEE (VATICAN CITY STATE)','code'=>'39'),
            'VC'=>array('name'=>'SAINT VINCENT AND THE GRENADINES','code'=>'1784'),
            'VE'=>array('name'=>'VENEZUELA','code'=>'58'),
            'VG'=>array('name'=>'VIRGIN ISLANDS, BRITISH','code'=>'1284'),
            'VI'=>array('name'=>'VIRGIN ISLANDS, U.S.','code'=>'1340'),
            'VN'=>array('name'=>'VIET NAM','code'=>'84'),
            'VU'=>array('name'=>'VANUATU','code'=>'678'),
            'WF'=>array('name'=>'WALLIS AND FUTUNA','code'=>'681'),
            'WS'=>array('name'=>'SAMOA','code'=>'685'),
            'XK'=>array('name'=>'KOSOVO','code'=>'381'),
            'YE'=>array('name'=>'YEMEN','code'=>'967'),
            'YT'=>array('name'=>'MAYOTTE','code'=>'262'),
            'ZA'=>array('name'=>'SOUTH AFRICA','code'=>'27'),
            'ZM'=>array('name'=>'ZAMBIA','code'=>'260'),
            'ZW'=>array('name'=>'ZIMBABWE','code'=>'263')
        );


        //get country code
        $specificCountryCode = $countryCodesArray[$billingCountry]['code'];

        $lengthOfCountryCode = strlen($specificCountryCode);

        if( substr($phoneNumber, 0, $lengthOfCountryCode) == $specificCountryCode ){
            //they have put in the country prefix but they haven't added a plus
            return '+'.$phoneNumber;
        } else {
            //they haven't added a country prefix or plus
            return '+'.$specificCountryCode.substr($phoneNumber,1);
        }

    }

}   
/**
* 
*
*
* Do pro update check
*/
require 'plugin-update-checker/plugin-update-checker.php';

$updateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://northernbeacheswebsites.com.au/?update_action=get_metadata&update_slug=twilio-sms-notifications-woocommerce', //Metadata URL.
    __FILE__, //Full path to the main plugin file.
    'twilio-sms-notifications-woocommerce' //Plugin slug. Usually it's the same as the name of the directory.
);


//add queries to the update call
$updateChecker->addQueryArgFilter('filter_update_checks_twilio_sms_notifications_woocommerce');
function filter_update_checks_twilio_sms_notifications_woocommerce($queryArgs) {

    if (!empty(get_option('wc_settings_twilio_sms_order_email')) &&  !empty(get_option('wc_settings_twilio_sms_order_id'))) {

        $purchaseEmailAddress = get_option('wc_settings_twilio_sms_order_email');
        $orderId = get_option('wc_settings_twilio_sms_order_id');
        $siteUrl = get_site_url();

        if (!empty($purchaseEmailAddress) &&  !empty($orderId)) {
            $queryArgs['purchaseEmailAddress'] = $purchaseEmailAddress;
            $queryArgs['orderId'] = $orderId;
            $queryArgs['siteUrl'] = $siteUrl;
            $queryArgs['productId'] = '15501';
        }

    }

    return $queryArgs;   
}



// define the puc_request_info_result-<slug> callback 
function filter_puc_request_info_result_slug_twilio_sms_notifications_woocommerce( $plugininfo, $result ) { 
    //get the message from the server and set as transient
    set_transient('twilio-sms-notifications-woocommerce-update',$plugininfo->{'message'},YEAR_IN_SECONDS * 1);

    return $plugininfo; 
}; 
add_filter( "puc_request_info_result-twilio-sms-notifications-woocommerce", 'filter_puc_request_info_result_slug_twilio_sms_notifications_woocommerce', 10, 2 ); 






$path = plugin_basename( __FILE__ );

add_action("after_plugin_row_{$path}", function( $plugin_file, $plugin_data, $status ) {

    //get plugin settings

    if (!empty(get_option('wc_settings_twilio_sms_order_email')) &&  !empty(get_option('wc_settings_twilio_sms_order_id'))) {


        //get transient
        $message = get_transient('twilio-sms-notifications-woocommerce-update');

        if($message !== 'Yes'){

            $purchaseLink = 'https://northernbeacheswebsites.com.au/twilio-sms-notifications-for-woocommerce/';

            if($message == 'Incorrect Details'){
                $displayMessage = 'The Order ID and Purchase ID you entered is not correct. Please double check the details you entered to receive product updates.';    
            } elseif ($message == 'Licence Expired'){
                $displayMessage = 'Your licence has expired. Please <a href="'.$purchaseLink.'" target="_blank">purchase a new licence</a> to receive further updates for this plugin.';    
            } elseif ($message == 'Website Mismatch') {
                $displayMessage = 'This plugin has already been registered on another website using your details. Under the licence terms this plugin can only be used on one website. Please <a href="'.$purchaseLink.'" target="_blank">click here</a> to purchase an additional licence.';    
            } else {
                $displayMessage = '';    
            }

            echo '<tr class="plugin-update-tr active"><td colspan="3" class="plugin-update colspanchange"><div class="update-message notice inline notice-error notice-alt"><p class="installer-q-icon">'.$displayMessage.'</p></div></td></tr>';

        }

    } else {

        echo '<tr class="plugin-update-tr active"><td colspan="3" class="plugin-update colspanchange"><div class="update-message notice inline notice-error notice-alt"><p class="installer-q-icon">Please enter your Order ID and Purchase ID in the plugin settings to receive automatics updates.</p></div></td></tr>';

    }


}, 10, 3 );
/**
* 
*
*
* Send test SMS
*/
function twilio_sms_notifications_woocommerce_send_test_sms() {
    

    //get board name from ajax call
    $mobile = $_POST['mobile'];
    $message = $_POST['message'];
    
    twilio_sms_notifications_woocommerce_send_sms($mobile,$message);
    
    //die
    wp_die();

 
}
add_action( 'wp_ajax_send_test_sms_twilio', 'twilio_sms_notifications_woocommerce_send_test_sms' );


?>