<?php
register_activation_hook(wpiai_PLUGINFILE, 'wpiai_cron_activation');

function wpiai_cron_activation()
{
    error_log('Running wpiai_cron_activation');
    if (!wp_next_scheduled('wpiai_every_minute_action')) {
        wp_schedule_event(time(), 'everyminute', 'wpiai_every_minute_action');
    }
    if (!wp_next_scheduled('wpiai_every_twenty_minutes_action')) {
        wp_schedule_event(time(), 'everytwentyminutes', 'wpiai_every_twenty_minutes_action');
    }
}

add_action('wpiai_every_minute_action', 'wpiai_do_every_minute');
add_action('wpiai_every_twenty_minutes_action', 'wpiai_do_every_twenty_minutes');

function wpiai_do_every_minute()
{
    // do something every minute
    //error_log('WP Cron is working....Every Minute Event');
    wpiai_check_user_meta();
}

function wpiai_do_every_twenty_minutes()
{
    // do something every twenty minutes
    //error_log('WP Cron is working....');
}

add_filter('cron_schedules', 'wpiai_add_cron_interval');
function wpiai_add_cron_interval($schedules)
{
    $schedules['everyminute'] = array(
        'interval' => 60, // time in seconds
        'display' => 'Every Minute'
    );
    $schedules['everytwentyminutes'] = array(
        'interval' => 1200, // time in seconds
        'display' => 'Every Twenty Minutes'
    );
    return $schedules;
}

function wpiai_get_woo_skus_ids($max, $seconds)
{
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1
    );
    $loop = new WP_Query($args);
    $res = array();
    $i = 0;
    if ($loop->have_posts()):
        while ($loop->have_posts()): $loop->the_post();
            global $product;
            $id = get_the_id();
            if ($max > 0) {
                $product_price_updated = get_post_meta($id, 'product_price_updated');
                if($product_price_updated=='') {
                    $product_price_updated=0;
                }
                $now = time();
                if (is_numeric($seconds)) {
                    if (($now - $product_price_updated) > $seconds) {
                        $sku = $product->get_sku();
                        $res[] = array(
                            'id' => $id,
                            'sku' => $sku
                        );
                    }
                } else {
                    $sku = $product->get_sku();
                    $res[] = array(
                        'id' => $id,
                        'sku' => $sku
                    );
                }

                if ($max >= $i) {
                    return $res;
                }
                $i++;
            } else {
                $sku = $product->get_sku();
                $res[] = array(
                    'id' => $id,
                    'sku' => $sku
                );
            }

        endwhile;
    endif;
    wp_reset_postdata();
    return $res;
}

function wpiai_process_products($max,$seconds) {
    $products = wpiai_get_woo_skus_ids($max,$seconds);
    $productSKUs = array();
    foreach ($products as $product) {
        if($product['id'] > 0) {
            $productSKUs[] = $product['sku'];
        }
    }
    $prices = getDefaultProductPrices('',$productSKUs);
    foreach ($prices as $price) {
        $sku = $price['SKU'];
        $p = $price['price'];
        $k = array_search($sku,$products);
        if($k) {
            $id = $products[$k]['id'];
            $wooProduct = wc_get_product($id);
            $wooProduct->set_regular_price($p);
            $wooProduct->set_price($p);
            $wooProduct->save();
            update_post_meta($id, 'product_price_updated',time());
        }
    }
}


function wpiai_process_user_shiptos($user_id)
{
    error_log('Processing ShipTos for user_id: ' . $user_id);
    $shipTo_meta = get_user_meta($user_id, 'wpiai_delivery_addresses', true);
    $shipTo = array();
    $shipAdd = array();
    $shipChange = array();
    if (is_array($shipTo_meta)) {
        foreach ($shipTo_meta as $shipTo_m) {
            $shipTo_rec = $shipTo_m;
            if (($shipTo_rec['delivery-CSD-ID'] == '') || (!array_key_exists('delivery-CSD-ID', $shipTo_rec))) {
                $shipTo_rec['CREATED_BY'] = 'WOO';
                if (($shipTo_rec['delivery_UNIQUE_ID'] == '') || (!array_key_exists('delivery_UNIQUE_ID', $shipTo_rec))) {
                    $shipTo_rec['delivery_UNIQUE_ID'] = uniqid();
                    $shipAdd[] = $shipTo_rec;
                }
            } else {
                $shipTo_rec['CREATED_BY'] = 'EXTERNAL';
            }
            if (($shipTo_rec['delivery_UNIQUE_ID'] == '') || (!array_key_exists('delivery_UNIQUE_ID', $shipTo_rec))) {
                $shipTo_rec['delivery_UNIQUE_ID'] = uniqid();
                $shipChange[] = $shipTo_rec;
            }
            $shipTo[] = $shipTo_rec;
        }
    } else {
        error_log('No ShipTo Meta');
    }
    if (!update_user_meta($user_id, 'wpiai_delivery_addresses', $shipTo)) {
        error_log('Ship To update_user_meta Failed for $user_id: ' . $user_id);
    } else {
        $shipTo_url = get_option('wpiai_ship_to_url');
        $shipTo_paramaters = set_messageid(get_option('wpiai_ship_to_parameters'));
        if ((count($shipAdd) > 0)) {
            foreach ($shipAdd as $add_shipTo) {
                $shipTo_xml = get_shipTo_XML_record($user_id, 'Add', $add_shipTo);
                error_log('shipto Add');
                $updated = wpiai_get_infor_message_multipart_message($shipTo_url, $shipTo_paramaters, $shipTo_xml);
            }
        }
        $oldShipTos = get_user_meta($user_id, 'wpiai_last_delivery_addresses', true);
        if (is_array($oldShipTos)) {
            error_log('Processing wpiai_last_delivery_addresses for user_id: ' . $user_id);
            //error_log(print_r($oldShipTos,true));
            foreach ($oldShipTos as $old_ship_to) {
                $shipToRecID = array_search($old_ship_to['delivery_UNIQUE_ID'], array_column($shipTo, 'delivery_UNIQUE_ID'));
                if ($shipToRecID) {
                    $different = false;
                    if ($shipTo[$shipToRecID]['delivery-first-name'] <> $old_ship_to['delivery-first-name']) $different = true;
                    if ($shipTo[$shipToRecID]['delivery-last-name'] <> $old_ship_to['delivery-last-name']) $different = true;
                    if ($shipTo[$shipToRecID]['delivery-company-name'] <> $old_ship_to['delivery-company-name']) $different = true;
                    if ($shipTo[$shipToRecID]['delivery-country'] <> $old_ship_to['delivery-country']) $different = true;
                    if ($shipTo[$shipToRecID]['delivery-street-address-1'] <> $old_ship_to['delivery-street-address-1']) $different = true;
                    if ($shipTo[$shipToRecID]['delivery-street-address-2'] <> $old_ship_to['delivery-street-address-2']) $different = true;
                    if ($shipTo[$shipToRecID]['delivery-street-address-3'] <> $old_ship_to['delivery-street-address-3']) $different = true;
                    if ($shipTo[$shipToRecID]['delivery-town-city'] <> $old_ship_to['delivery-town-city']) $different = true;
                    if ($shipTo[$shipToRecID]['delivery-county'] <> $old_ship_to['delivery-county']) $different = true;
                    if ($shipTo[$shipToRecID]['delivery-postcode'] <> $old_ship_to['delivery-postcode']) $different = true;
                    if ($shipTo[$shipToRecID]['delivery-phone'] <> $old_ship_to['delivery-phone']) $different = true;
                    if ($shipTo[$shipToRecID]['delivery-email'] <> $old_ship_to['delivery-email']) $different = true;
                    if ($different) {
                        error_log('Shipto Updated');
                        error_log('old delivery_UNIQUE_ID: ' . $old_ship_to['delivery_UNIQUE_ID'] . ', new delivery_UNIQUE_ID: ' . $shipTo[$shipToRecID]['delivery_UNIQUE_ID']);
                        $shipChange[] = $shipTo[$shipToRecID];
                    }
                }
            }
        }
        if ((count($shipChange) > 0)) {
            foreach ($shipChange as $update_shipTo) {
                $shipTo_xml = get_shipTo_XML_record($user_id, 'Change', $update_shipTo);
                error_log('shipto Change');
                $updated = wpiai_get_infor_message_multipart_message($shipTo_url, $shipTo_paramaters, $shipTo_xml);
            }
        }
        update_user_meta($user_id, 'wpiai_last_delivery_addresses', $shipTo);
    }
    error_log('Finished Processing ShipTos for user_id: ' . $user_id);
}

function wpiai_process_user_contacts($user_id)
{
    error_log('Processing Contacts for user_id: ' . $user_id);
    $contactRec_meta = get_user_meta($user_id, 'wpiai_contacts', true);
    $contactRec = array();
    $contactAdd = array();
    $contactChange = array();
    if (is_array($contactRec_meta)) {
        foreach ($contactRec_meta as $contactRec_m) {
            $contactRec_rec = $contactRec_m;
            if (($contactRec_rec['contact_CSD_ID'] == '') || (!array_key_exists('contact_CSD_ID', $contactRec_rec))) {
                $contactRec_rec['CREATED_BY'] = 'WOO';
                if (($contactRec_rec['contact_CONTACT_ID'] == '') || (!array_key_exists('contact_CONTACT_ID', $contactRec_rec))) {
                    $contactRec_rec['contact_CONTACT_ID'] = uniqid();
                    $contactAdd[] = $contactRec_rec;
                }
            } else {
                $contactRec_rec['CREATED_BY'] = 'EXTERNAL';
            }
            if (($contactRec_rec['contact_CONTACT_ID'] == '') || (!array_key_exists('contact_CONTACT_ID', $contactRec_rec))) {
                $contactRec_rec['contact_CONTACT_ID'] = uniqid();
                $contactChange[] = $contactRec_rec;
            }
            $contactRec[] = $contactRec_rec;
        }
    } else {
        error_log('No Contact Meta');
    }
    if (!update_user_meta($user_id, 'wpiai_contacts', $contactRec)) {
        error_log('Contact update_user_meta Failed for $user_id: ' . $user_id);
    } else {
        $contactRec_url = get_option('wpiai_contact_url');
        $contactRec_paramaters = set_messageid(get_option('wpiai_contact_parameters'));

        if ((count($contactAdd) > 0)) {
            foreach ($contactAdd as $add_contact) {
                $contactRec_xml = get_contact_XML_record($user_id, 'Add', $add_contact);
                error_log('contact Add');
                $updated = wpiai_get_infor_message_multipart_message($contactRec_url, $contactRec_paramaters, $contactRec_xml);
            }
        }
        $oldContacts = get_user_meta($user_id, 'wpiai_last_contacts', true);
        if (is_array($oldContacts)) {
            error_log('Processing wpiai_last_contacts for user_id: ' . $user_id);
            //error_log(print_r($oldContacts,true));
            foreach ($oldContacts as $old_contact) {
                $contactRecRecID = array_search($old_contact['contact_CONTACT_ID'], array_column($contactRec, 'contact_CONTACT_ID'));
                if ($contactRecRecID) {
                    $different = false;
                    if ($contactRec[$contactRecRecID]['contact_status_code'] <> $old_contact['contact_status_code']) $different = true;
                    if ($contactRec[$contactRecRecID]['contact_first_name'] <> $old_contact['contact_first_name']) $different = true;
                    if ($contactRec[$contactRecRecID]['contact_last_name'] <> $old_contact['contact_last_name']) $different = true;
                    if ($contactRec[$contactRecRecID]['contact_job_title'] <> $old_contact['contact_job_title']) $different = true;
                    if ($contactRec[$contactRecRecID]['contact_addr_1'] <> $old_contact['contact_addr_1']) $different = true;
                    if ($contactRec[$contactRecRecID]['contact_addr_2'] <> $old_contact['contact_addr_2']) $different = true;
                    if ($contactRec[$contactRecRecID]['contact_addr_3'] <> $old_contact['contact_addr_3']) $different = true;
                    if ($contactRec[$contactRecRecID]['contact_addr_4'] <> $old_contact['contact_addr_4']) $different = true;
                    if ($contactRec[$contactRecRecID]['contact_email'] <> $old_contact['contact_email']) $different = true;
                    if ($contactRec[$contactRecRecID]['contact_postcode'] <> $old_contact['contact_postcode']) $different = true;
                    if ($contactRec[$contactRecRecID]['contact_phone'] <> $old_contact['contact_phone']) $different = true;
                    if ($contactRec[$contactRecRecID]['contact_type'] <> $old_contact['contact_type']) $different = true;
                    if ($contactRec[$contactRecRecID]['contact_phone_channel'] <> $old_contact['contact_phone_channel']) $different = true;
                    if ($contactRec[$contactRecRecID]['contact_fax_channel'] <> $old_contact['contact_fax_channel']) $different = true;
                    if ($contactRec[$contactRecRecID]['contact_mail_channel'] <> $old_contact['contact_mail_channel']) $different = true;
                    if ($contactRec[$contactRecRecID]['contact_email_channel'] <> $old_contact['contact_email_channel']) $different = true;
                    if ($different) {
                        error_log('Contact Updated');
                        error_log('old contact_CONTACT_ID: ' . $old_contact['contact_CONTACT_ID'] . ', new contact_CONTACT_ID: ' . $contactRec[$contactRecRecID]['contact_CONTACT_ID']);
                        $contactChange[] = $contactRec[$contactRecRecID];
                    }
                }
            }
        }
        if ((count($contactChange) > 0)) {
            foreach ($contactChange as $update_contact) {
                $contactRec_xml = get_contact_XML_record($user_id, 'Change', $update_contact);
                error_log('Contact Change');
                $updated = wpiai_get_infor_message_multipart_message($contactRec_url, $contactRec_paramaters, $contactRec_xml);
            }
        }
        update_user_meta($user_id, 'wpiai_last_contacts', $contactRec);
    }
    error_log('Finished Processing Contacts for user_id: ' . $user_id);
}

function wpiai_check_user_meta()
{
    //error_log('Checking User Meta');
    $users_updated = get_option('wpiai_users_updated');
    update_option('wpiai_users_updated', array());
    foreach ($users_updated as $user_id) {
        wpiai_process_user_shiptos($user_id);
        //wpiai_process_user_contacts($user_id);
    }
}