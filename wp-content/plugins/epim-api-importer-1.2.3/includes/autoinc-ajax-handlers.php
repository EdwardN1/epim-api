<?php

if (!defined('ABSPATH')) {
    exit;
}

function epimaapi_checkSecure()
{
    if (!check_ajax_referer('epim-security-nonce', 'security')) {
        wp_send_json_error('Invalid security token sent.');
        wp_die();
    }
}

$log = true;


/**
 * ========================== Actions ==============================
 */


add_action('wp_ajax_get_all_categories', 'ajax_get_epimaapi_all_categories');
add_action('wp_ajax_get_all_branches', 'ajax_get_epimaapi_all_branches');
add_action('wp_ajax_update_branch_stock', 'ajax_update_epimaapi_branch_stock');
add_action('wp_ajax_get_branch_stock', 'ajax_get_epimaapi_branch_stock');
add_action('wp_ajax_get_all_attributes', 'ajax_get_epimaapi_all_attributes');
add_action('wp_ajax_get_all_products', 'ajax_get_epimaapi_all_products');
add_action('wp_ajax_get_all_changed_products_since', 'ajax_get_epimaapi_all_changed_products_since');
add_action('wp_ajax_get_all_changed_products_since_starting', 'ajax_get_epimaapi_all_changed_products_since_starting');
add_action('wp_ajax_get_product', 'ajax_get_epimaapi_product');
add_action('wp_ajax_get_category', 'ajax_get_epimaapi_category');
add_action('wp_ajax_get_picture', 'ajax_get_epimaapi_picture');
add_action('wp_ajax_get_variation', 'ajax_get_epimaapi_variation');
add_action('wp_ajax_create_category', 'ajax_epimaapi_create_category');
add_action('wp_ajax_create_branch', 'ajax_epimaapi_create_branch');
add_action('wp_ajax_get_category_images', 'ajax_epimaapi_get_category_images');
add_action('wp_ajax_get_picture_web_link', 'ajax_get_epimaapi_picture_web_link');
add_action('wp_ajax_import_picture', 'ajax_epimaapi_import_picture');
add_action('wp_ajax_sort_categories', 'ajax_epimaapi_sort_categories');
add_action('wp_ajax_cat_image_link', 'ajax_epimaapi_cat_image_link');
add_action('wp_ajax_product_image_link', 'ajax_epimaapi_product_image_link');
add_action('wp_ajax_product_group_image_link', 'ajax_epimaapi_product_group_image_link');
add_action('wp_ajax_create_product', 'ajax_epimaapi_create_product');
add_action('wp_ajax_get_product_images', 'ajax_get_epimaapi_product_images');
add_action('wp_ajax_product_ID_code', 'ajax_epimaapi_product_ID_from_code');
add_action('wp_ajax_get_single_product_images', 'ajax_get_epimaapi_single_product_images');
add_action('wp_ajax_import_single_product_images', 'ajax_epimaapi_import_single_product_images');
add_action('wp_ajax_image_imported', 'ajax_epimaapi_image_imported');
add_action('wp_ajax_delete_attributes', 'ajax_epimaapi_delete_attributes');
add_action('wp_ajax_get_deleted_entities_count', 'ajax_get_epimaapi_deleted_entities_count');
add_action('wp_ajax_get_deleted_entities_variations', 'ajax_get_epimaapi_deleted_entities_variations');
add_action('wp_ajax_delete_variation', 'ajax_epimaapi_delete_variation');
add_action('wp_ajax_delete_categories', 'ajax_epimaapi_delete_categories');
add_action('wp_ajax_delete_epim_images', 'ajax_epimaapi_delete_epim_images');
add_action('wp_ajax_delete_epim_orphaned_images', 'ajax_epimaapi_delete_epim_orphaned_images');
add_action('wp_ajax_delete_products', 'ajax_epimaapi_delete_products');
add_action('wp_ajax_clear_woo_down', 'ajax_epimaapi_clear_woo_down');

add_action('wp_ajax_fast_create', 'ajax_epimaapi_fast_create');
add_action('wp_ajax_stop_background_update', 'ajax_epimaapi_stop_background_update');
add_action('wp_ajax_get_background_changed_products_since', 'ajax_get_epimaapi_background_changed_products_since');
add_action('wp_ajax_force_background_update', 'ajax_epimaapi_force_background_update');
add_action('wp_ajax_unfreeze_queue', 'ajax_epimaapi_unfreeze_queue');
add_action('wp_ajax_import_by_variation_id', 'ajax_epimaapi_import_by_variation_id');

add_action('wp_ajax_divi_write_css_file', 'ajax_epimaapi_divi_write_css_file');
add_action('wp_ajax_divi_build_category_menu', 'ajax_epimaapi_divi_build_category_menu');


add_action('wp_ajax_cron_tail', 'ajax_epimaapi_cron_tail');


function ajax_epimaapi_import_by_variation_id() {
    epimaapi_checkSecure();
    if($_POST['variation_id']) {
        if(epimapi_get_one_variation($_POST['variation_id'])==2) {
            if(epimaapi_load_category_data()) {
                update_option('_epim_update_running', 'Preparing to process ePim categories');
                cron_log('Checking Category Data');
            } else {
                cron_log('Failed to load Category Data');
            }

        }
    } else {
        cron_log('ID not entered');
    }
    exit;
}


function ajax_epimaapi_divi_build_category_menu() {
    epimaapi_checkSecure();
    if(!empty($_POST['numItems'])) {
        //error_log($_POST['numItems']);
        if(ctype_digit($_POST['numItems'])) {
            epim_generate_category_menu($_POST['numItems']);
        } else {
            epim_generate_category_menu();
        }
    } else {
        epim_generate_category_menu();
    }
    echo 'Menu Created';
    exit;
}

function ajax_epimaapi_divi_write_css_file() {
	epimaapi_checkSecure();
	if (!empty($_POST['primary'])) {
		if (!empty($_POST['secondary'])) {
			epim_write_css_file( $_POST['primary'], $_POST['secondary'] );
		}
	}
    exit;
}

function ajax_epimaapi_cron_tail()
{
    epimaapi_checkSecure();
    $log_dir = WP_PLUGIN_DIR . '/epim-api-importer';
    if (is_dir($log_dir)) {
        $log_file = $log_dir . '/cron-log.log';
        if (file_exists($log_file)) {
            session_start();
            $handle = fopen($log_file, 'r');
            if (isset($_SESSION['offset'])) {
                $data = stream_get_contents($handle, -1, $_SESSION['offset']);
                echo nl2br($data);
                //error_log('Session is Set: '.$_SESSION['offset']);
                $_SESSION['offset'] = ftell($handle);
            } else {
                fseek($handle, 0, SEEK_END);
                $_SESSION['offset'] = ftell($handle);
                //error_log('Session is not Set');
            }
        }
    }
    exit;
}

function ajax_epimaapi_clear_woo_down()
{
    epimaapi_checkSecure();
    $res = 'Woo clear down not successful';
    global $wpdb;
    $sql = "DELETE relations.*, taxes.*, terms.* FROM wp_term_relationships AS relations INNER JOIN wp_term_taxonomy AS taxes ON relations.term_taxonomy_id=taxes.term_taxonomy_id  ON taxes.term_id=terms.term_id WHERE object_id IN (SELECT ID FROM wp_posts WHERE post_type='product')";
    $res = '<br>Delete Product relations Result: '.print_r($wpdb->get_results($sql),true);
    $sql = "DELETE FROM wp_postmeta WHERE post_id IN (SELECT ID FROM wp_posts WHERE post_type = 'product')";
    $res .= '<br>Delete Products Meta Data Result: '.print_r($wpdb->get_results($sql),true);
    $sql = "DELETE FROM wp_posts WHERE post_type = 'product'";
    $res .= '<br>Delete Products Result: '.print_r($wpdb->get_results($sql),true);
    $sql = "DELETE a,c FROM wp_terms AS a LEFT JOIN wp_term_taxonomy AS c ON a.term_id = c.term_id LEFT JOIN wp_term_relationships AS b ON b.term_taxonomy_id = c.term_taxonomy_id WHERE c.taxonomy = 'product_tag'";
    $res .= '<br>Delete Product Tags Result: '.print_r($wpdb->get_results($sql),true);
    $sql = "DELETE a,c FROM wp_terms AS a LEFT JOIN wp_term_taxonomy AS c ON a.term_id = c.term_id LEFT JOIN wp_term_relationships AS b ON b.term_taxonomy_id = c.term_taxonomy_id WHERE c.taxonomy = 'product_cat'";
    $res .= '<br>Delete Product Categories Result: '.print_r($wpdb->get_results($sql),true);
    epimaapi_delete_attributes();
    $res .= '<br>All attributes removed';
    echo $res;
    exit;
}

function ajax_epimaapi_force_background_update()
{
    epimaapi_checkSecure();
    update_option('_epim_update_running', '');
    update_option('_epim_background_process_data', '');
    update_option('_epim_background_current_index', 0);
    session_start();
    $_SESSION['offset'] = 0;
    $f = @fopen(WP_PLUGIN_DIR . '/epim-api-importer/cron-log.log', "r+");
    if ($f !== false) {
        ftruncate($f, 0);
        fclose($f);
    }
    cron_log('Starting Update All..');
    echo epimaapi_background_import_all_start();
    exit;
}

function ajax_epimapi_background_remove_epim_images() {
    epimaapi_checkSecure();
    update_option('_epim_update_running', '');
    update_option('_epim_background_process_data', '');
    update_option('_epim_background_current_index', 0);
    session_start();
    $_SESSION['offset'] = 0;
    $f = @fopen(WP_PLUGIN_DIR . '/epim-api-importer/cron-log.log', "r+");
    if ($f !== false) {
        ftruncate($f, 0);
        fclose($f);
    }
    echo epim_api_background_remove_epim_images_start();
    exit;
}

function ajax_get_epimaapi_background_changed_products_since()
{
    epimaapi_checkSecure();
    update_option('_epim_update_running', '');
    update_option('_epim_background_process_data', '');
    update_option('_epim_background_current_index', 0);
    if (!empty($_POST['timeCode'])) {

        epimaapi_background_import_products_from($_POST['timeCode']);
    }
    exit;
}

function ajax_epimaapi_unfreeze_queue()
{
    epimaapi_checkSecure();
    update_option('_epim_update_running', 'Categories Updated and Sorted');
    cron_log('Unfreezing Queue Please wait.');
    echo 'Unfreezing Queue Please wait.';
    exit;
}

function ajax_epimaapi_fast_create()
{
    epimaapi_checkSecure();
    //update_option('_epim_update_running','Categories Updated and Sorted');
    //update_option('_epim_background_current_index',1660);
    $epim_update_running = get_option('_epim_update_running');
    if ($epim_update_running == '') {
        //echo epimaapi_background_import_all_start();
        echo 'No active Import Jobs';
    } else {
        echo $epim_update_running . '. background_current_index = ' . get_option('_epim_background_current_index');
    }
    exit;
}

function ajax_epimaapi_stop_background_update()
{
    epimaapi_checkSecure();

    update_option('_epim_update_running', '');
    update_option('_epim_background_stop_update', 1);
    update_option('_epim_background_current_index', 0);
    update_option('_epim_background_last_index', 0);
    update_option('_epim_background_product_attribute_data', '');
    update_option('_epim_background_process_data', '');
    update_option('_epim_background_attribute_data', '');
    update_option('_epim_background_product_attribute_data', '');
    update_option('_epim_background_category_data','');
    update_option('_epim_products_to_process','');
    update_option('_epim_cron_busy', '');

    echo 'Current Update Stopped';
    exit;
}

function ajax_epimaapi_delete_categories()
{
    epimaapi_checkSecure();
    include_once(ABSPATH . "wp-config.php");
    include_once(ABSPATH . "wp-includes/wp-db.php");
    global $wpdb;
    $sql = "DELETE a,c FROM wp_terms AS a LEFT JOIN wp_term_taxonomy AS c ON a.term_id = c.term_id LEFT JOIN wp_term_relationships AS b ON b.term_taxonomy_id = c.term_taxonomy_id WHERE c.taxonomy = 'product_cat'";
    $results = $wpdb->get_results($sql);
    echo json_encode($results);
    exit;
}

function ajax_epimaapi_delete_epim_images()
{
    epimaapi_checkSecure();
    $args = array(
        'post_type' => 'attachment',
        'numberposts' => -1,
        'post_status' => null,
        'post_parent' => null, // any parent
    );
    $attachments = get_posts($args);
    $i = 0;
    $epI = 0;
    if ($attachments) {
        foreach ($attachments as $post) {
           $i++;
            /*setup_postdata($post);
            the_title();
            the_attachment_link($post->ID, false);
            the_excerpt();*/
            if(get_post_meta($post->ID,'epim_api_id',true)||get_post_meta($post->ID,'epim_luckins_path',true)) {
                if(wp_delete_attachment($post->ID,true)) {
                    $epI++;
                }
            }
        }
    }
    echo ' Number of imported images deleted = '.$epI;
    exit;
}

function ajax_epimaapi_delete_epim_orphaned_images()
{
    epimaapi_checkSecure();
    $args = array(
        'post_type' => 'attachment',
        'numberposts' => -1,
        'post_status' => null,
        'post_parent' => null, // any parent
    );
    $attachments = get_posts($args);
    $i = 0;
    $epI = 0;
    if ($attachments) {
        foreach ($attachments as $post) {
            $i++;
            /*setup_postdata($post);
            the_title();
            the_attachment_link($post->ID, false);
            the_excerpt();*/
            if(get_post_meta($post->ID,'epim_api_id',true)||get_post_meta($post->ID,'epim_luckins_path',true)) {
                $file=get_attached_file($post->ID);
                if(!file_exists($file)) {
                    if(wp_delete_attachment($post->ID,true)) {
                        $epI++;
                    }
                }
            }
        }
    }
    echo ' Number of imported images deleted = '.$epI;
    exit;
}

function ajax_epimaapi_delete_products()
{
    epimaapi_checkSecure();
    include_once(ABSPATH . "wp-config.php");
    include_once(ABSPATH . "wp-includes/wp-db.php");
    global $wpdb;
    $sql = "DELETE relations.*, taxes.*, terms.* FROM wp_term_relationships AS relations INNER JOIN wp_term_taxonomy AS taxes ON relations.term_taxonomy_id=taxes.term_taxonomy_id INNER JOIN wp_terms AS terms ON taxes.term_id=terms.term_id WHERE object_id IN (SELECT ID FROM wp_posts WHERE post_type='product')";
    $results1 = $wpdb->get_results($sql);
    $sql = "DELETE FROM wp_postmeta WHERE post_id IN (SELECT ID FROM wp_posts WHERE post_type = 'product')";
    $results2 = $wpdb->get_results($sql);
    $sql = "DELETE FROM wp_posts WHERE post_type = 'product'";
    $results3 = $wpdb->get_results($sql);
    echo '<pre>' . json_encode($results1) . '</pre>' . '<pre>' . json_encode($results2) . '</pre>' . '<pre>' . json_encode($results3) . '</pre>';
    exit;
}

function ajax_get_epimaapi_deleted_entities_count()
{
    epimaapi_checkSecure();
    echo get_epimaapi_deleted_entities_count();
    exit;
}

function ajax_get_epimaapi_deleted_entities_variations()
{
    epimaapi_checkSecure();
    if (!empty($_POST['TotalResults'])) {
        echo get_epimaapi_deleted_entities_variations(sanitize_text_field($_POST['TotalResults']));
    } else {
        echo 'Need a Limit Please...';
    }

}

function ajax_epimaapi_delete_variation()
{
    epimaapi_checkSecure();
    if (!empty($_POST['variationID'])) {
        echo epimaapi_delete_variation(sanitize_text_field($_POST['variationID']));
    } else {
        echo 'No Variation Supplied...';
    }
}

function ajax_epimaapi_delete_attributes()
{
    epimaapi_checkSecure();
    if (!empty($_POST['limit'])) {
        $limit = sanitize_text_field($_POST['limit']);
        $res = epimaapi_delete_attributes($limit);
        if(!$res) {
            echo 'Timed Out';
        } else {
            echo $res;
        }
    } else {
        $res = epimaapi_delete_attributes();
        if(!$res) {
            echo 'Timed Out';
        } else {
            echo $res;
        }
    }
    exit;
}


function ajax_epimaapi_image_imported()
{
    epimaapi_checkSecure();
    if (!empty($_POST['ID'])) {
        if (epimaapi_imageImported(sanitize_text_field($_POST['ID']))) {
            echo 'Image Imported';
        } else {
            echo 'Image not Imported';
        }
    }
    exit;
}

function ajax_epimaapi_import_single_product_images()
{
    epimaapi_checkSecure();
    if (!empty($_POST['productID'])) {
        if (!empty($_POST['variationID'])) {
            $response = importSingleProductImages(sanitize_text_field($_POST['productID']), sanitize_text_field($_POST['variationID']));
            echo $response;
        } else {
            echo 'error no variationID supplied';
        }
    } else {
        echo 'error no productID supplied';
    }
    exit;
}

function ajax_get_epimaapi_branch_stock()
{
    epimaapi_checkSecure();
    if (!empty($_POST['ID'])) {
        $response = get_epimaapi_branch_stock(sanitize_text_field($_POST['ID']));
        echo $response;
    }
    exit;
}


function ajax_get_epimaapi_single_product_images()
{
    epimaapi_checkSecure();
    if (!empty($_POST['ID'])) {
        $response = getSingleProductImages(sanitize_text_field($_POST['ID']));
        header("Content-Type: application/json charset=utf-8");
        echo json_encode($response);
    } else {
        echo 'error no ID supplied';
    }
    exit;
}

function ajax_epimaapi_product_ID_from_code()
{
    epimaapi_checkSecure();
    $response = 'Not Found';
    if (!empty(sanitize_text_field($_POST['CODE']))) {
        $response = epimaapi_getAPIIDFromCode(sanitize_text_field($_POST['CODE']));
        //error_log('Code = '.$_POST['CODE'].' | API = '.$response);
    }
    echo $response;
    exit;
}

function ajax_get_epimaapi_product()
{
    epimaapi_checkSecure();
    if (!empty($_POST['ID'])) {
        $jsonResponse = get_epimaapi_product(sanitize_text_field($_POST['ID']));
        $response = $jsonResponse;
        header("Content-Type: application/json");
        echo json_encode($response);
    } else {
        echo 'error no ID supplied';
    }
    exit;
}

function ajax_epimaapi_get_category_images()
{
    epimaapi_checkSecure();
    if (!empty($_POST['ID'])) {
        header("Content-Type: application/json");
        echo epimaapi_getCategoryImages(sanitize_text_field($_POST['ID']));
    }
    exit;
}

function ajax_get_epimaapi_product_images()
{
    epimaapi_checkSecure();
    $response = getProductImages();
    //error_log(json_encode($response));
    header("Content-Type: application/json charset=utf-8");
    echo json_encode($response);
    exit;
}

function ajax_epimaapi_create_product()
{
    epimaapi_checkSecure();
    if (!empty($_POST['productID'])) {
        if (!empty($_POST['variationID'])) {
            if (!empty($_POST['productName'])) {
                $pictureIDS = '';
                if (isset($_POST['pictureIDs'])) {
                    $pictureIDS = sanitize_text_field($_POST['pictureIDs']);
                }
                echo epimaapi_create_product(
                    sanitize_text_field($_POST['productID']),
                    sanitize_text_field($_POST['variationID']), sanitize_text_field($_POST['bulletText']),
                    sanitize_text_field($_POST['productName']),
                    $_POST['categoryIDs'],
                    $pictureIDS);
                exit;
            } else {
                echo 'Product Creation Failed - no Product Name supplied';
                exit;
            }
        } else {
            echo 'Product Creation Failed - no Variation ID supplied';
            exit;
        }
    } else {
        echo 'Product Creation Failed - no Product ID';
        exit;
    }

}

function ajax_update_epimaapi_branch_stock()
{
    epimaapi_checkSecure();
    if (!empty($_POST['ID'])) {
        if (!empty($_POST['VariationId'])) {
            if (!empty($_POST['Stock'])) {
                echo epimaapi_update_branch_stock(sanitize_text_field($_POST['ID']),
                    sanitize_text_field($_POST['VariationId']),
                    sanitize_text_field($_POST['Stock']));
            }
        }
    }
    exit;
}

function ajax_epimaapi_cat_image_link()
{
    epimaapi_checkSecure();
    linkCategoryImages();
    echo 'Category Images Linked';
    exit;
}

function ajax_epimaapi_product_image_link()
{
    epimaapi_checkSecure();
    echo epimaapi_linkProductImages();
    //linkVariationImages();
    //echo 'Product Images Linked';
    exit;
}

function ajax_epimaapi_product_group_image_link()
{
    epimaapi_checkSecure();
    if (!empty($_POST['productID'])) {
        echo linkProductGroupImages(sanitize_text_field($_POST['productID']));
    }

    exit;
}

function ajax_epimaapi_sort_categories()
{
    epimaapi_checkSecure();
    epimaapi_sort_categories();
    echo 'Categories Sorted';
    exit;
}

function ajax_epimaapi_import_picture()
{
    epimaapi_checkSecure();
    if (!empty($_POST['ID'])) {
        if (!empty($_POST['weblink'])) {
            echo epimaapi_importPicture(sanitize_text_field($_POST['ID']), sanitize_text_field($_POST['weblink']));
        }
    }
    exit;
}

function ajax_get_epimaapi_picture_web_link()
{
    epimaapi_checkSecure();
    $response = '';
    if (!empty($_POST['ID'])) {
        $response = get_epimaapi_picture(sanitize_text_field($_POST['ID']));
    }
    header("Content-Type: application/json");
    echo json_encode($response);
    exit;
}

function ajax_get_epimaapi_all_categories()
{
    epimaapi_checkSecure();
    $jsonResponse = get_epimaapi_all_categories();
    $response = $jsonResponse;
    header("Content-Type: application/json");
    echo json_encode($response);
    exit;
}

function ajax_get_epimaapi_all_branches()
{
    epimaapi_checkSecure();
    $jsonResponse = get_epimaapi_all_branches();
    $response = $jsonResponse;
    header("Content-Type: application/json");
    echo json_encode($response);
    exit;
}

function ajax_get_epimaapi_all_attributes()
{
    epimaapi_checkSecure();
    $jsonResponse = get_epimaapi_all_attributes();
    $response = $jsonResponse;
    header("Content-Type: application/json");
    echo json_encode($response);
    exit;
}

function ajax_get_epimaapi_all_products()
{
    //error_log('getting products..');
    epimaapi_checkSecure();
    $jsonResponse = get_epimaapi_all_products();
    $response = json_decode($jsonResponse);
    //header( "Content-Type: application/json" );
    echo json_encode($response->Results);
    exit;
}

function ajax_get_epimaapi_all_changed_products_since()
{
    epimaapi_checkSecure();
    if (!empty($_POST['timeCode'])) {
        $jsonResponse = get_epimaapi_all_changed_products_since($_POST['timeCode']);
        $response = json_decode($jsonResponse);
        //header( "Content-Type: application/json" );
        echo json_encode($response);
    }
    exit;
}

function ajax_get_epimaapi_all_changed_products_since_starting()
{
    //error_log('working');
    epimaapi_checkSecure();
    if (!empty($_POST['timeCode'])) {
        if (!empty($_POST['start'])) {
            //error_log('start = '.$_POST['start']);
            $jsonResponse = get_epimaapi_all_changed_products_since_starting($_POST['start'], $_POST['timeCode']);
            $response = json_decode($jsonResponse);
            //header( "Content-Type: application/json" );
            echo json_encode($response);
        } else {
            //error_log('no start');
        }
    } else {
        error_log('no timeCode');
    }
    exit;
}


function ajax_get_epimaapi_category()
{
    epimaapi_checkSecure();
    if (!empty($_POST['ID'])) {

        $jsonResponse = get_api_category(sanitize_text_field($_POST['ID']));
        $response = $jsonResponse;
        header("Content-Type: application/json");
        echo json_encode($response);
    } else {
        echo 'error no ID supplied';
    }
    exit;
}

function ajax_get_epimaapi_picture()
{
    epimaapi_checkSecure();
    if (!empty($_POST['ID'])) {
        //error_log('Getting Picture: '.$_POST['ID']);
        $jsonResponse = get_epimaapi_picture(sanitize_text_field($_POST['ID']));
        $response = $jsonResponse;
        header("Content-Type: application/json");
        echo json_encode($response);
    } else {
        //error_log('error no ID supplied in ajax_get_epimaapi_picture');
    }
    exit;
}

function ajax_get_epimaapi_variation()
{
    epimaapi_checkSecure();
    if (!empty($_POST['ID'])) {
        $jsonResponse = get_epimaapi_variation(sanitize_text_field($_POST['ID']));
        $response = $jsonResponse;
        header("Content-Type: application/json");
        echo json_encode($response);
    } else {
        echo 'error no ID supplied';
    }
    exit;
}

function ajax_epimaapi_create_category()
{
    epimaapi_checkSecure();
    $response = 'Nothing Happened!!';
    if (!empty($_POST['ID'])) {
        if (!empty($_POST['name'])) {
            $WebPath = '';
            $Picture_ids = array();
            if (isset($_POST['WebPath'])) {
                $WebPath = sanitize_text_field($_POST['WebPath']);
            }
            if (isset($_POST['picture_ids'])) {
                if(is_array($_POST['picture_ids'])) {
                    foreach ($_POST['picture_ids'] as $picture_id) {
                        $Picture_ids[] = sanitize_text_field($picture_id);
                    }
                }
            }
            $a = '';
            if(!empty($_POST['alias'])) $a = sanitize_text_field($_POST['alias']);
            /*error_log('$_POST["picture_ids"] = '.print_r($_POST['picture_ids'],true));
            error_log('$Picture_ids = '.print_r($Picture_ids,true ));*/
            $response = epimaapi_create_category(
                sanitize_text_field($_POST['ID']),
                sanitize_text_field($_POST['name']),
                sanitize_text_field($_POST['ParentID']),
                $WebPath,
                $Picture_ids,$a);
        }
    }
    echo $response;
    exit;
}

function ajax_epimaapi_create_branch()
{
    epimaapi_checkSecure();
    $response = 'Nothing Happened!!';
    if (!empty($_POST['ID'])) {
        if (!empty($_POST['name'])) {
            $response = epimaapi_create_branch(
                sanitize_text_field($_POST['ID']),
                sanitize_text_field($_POST['name']),
                sanitize_text_field($_POST['Telephone']),
                sanitize_text_field($_POST['Email']),
                sanitize_textarea_field($_POST['Address']));
        }
    }
    echo $response;
    exit;
}