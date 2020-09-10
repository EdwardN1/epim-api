<?php

if ( ! defined( 'ABSPATH' ) )
	exit;

function epimaapi_checkSecure() {
    if ( ! check_ajax_referer( 'epim-security-nonce', 'security' ) ) {
        wp_send_json_error( 'Invalid security token sent.' );
        wp_die();
    }
}

$log = true;


/**
 * ========================== Actions ==============================
 */


add_action( 'wp_ajax_get_all_categories', 'ajax_get_epimaapi_all_categories' );
add_action( 'wp_ajax_get_all_branches', 'ajax_get_epimaapi_all_branches' );
add_action( 'wp_ajax_update_branch_stock', 'ajax_update_epimaapi_branch_stock' );
add_action( 'wp_ajax_get_branch_stock', 'ajax_get_epimaapi_branch_stock' );
add_action( 'wp_ajax_get_all_attributes', 'ajax_get_epimaapi_all_attributes' );
add_action( 'wp_ajax_get_all_products', 'ajax_get_epimaapi_all_products' );
add_action( 'wp_ajax_get_all_changed_products_since', 'ajax_get_epimaapi_all_changed_products_since' );
add_action( 'wp_ajax_get_product', 'ajax_get_epimaapi_product' );
add_action( 'wp_ajax_get_category', 'ajax_get_epimaapi_category' );
add_action( 'wp_ajax_get_picture', 'ajax_get_epimaapi_picture' );
add_action( 'wp_ajax_get_variation', 'ajax_get_epimaapi_variation' );
add_action( 'wp_ajax_create_category', 'ajax_epimaapi_create_category' );
add_action( 'wp_ajax_create_branch', 'ajax_epimaapi_create_branch' );
add_action( 'wp_ajax_get_category_images', 'ajax_epimaapi_get_category_images' );
add_action( 'wp_ajax_get_picture_web_link', 'ajax_get_epimaapi_picture_web_link' );
add_action( 'wp_ajax_import_picture', 'ajax_epimaapi_import_picture' );
add_action( 'wp_ajax_sort_categories', 'ajax_epimaapi_sort_categories' );
add_action( 'wp_ajax_cat_image_link', 'ajax_epimaapi_cat_image_link' );
add_action( 'wp_ajax_product_image_link', 'ajax_epimaapi_product_image_link' );
add_action( 'wp_ajax_product_group_image_link', 'ajax_epimaapi_product_group_image_link' );
add_action( 'wp_ajax_create_product', 'ajax_epimaapi_create_product' );
add_action( 'wp_ajax_get_product_images', 'ajax_get_epimaapi_product_images' );
add_action( 'wp_ajax_product_ID_code', 'ajax_epimaapi_product_ID_from_code' );
add_action( 'wp_ajax_get_single_product_images', 'ajax_get_epimaapi_single_product_images' );
add_action( 'wp_ajax_import_single_product_images', 'ajax_epimaapi_import_single_product_images' );
add_action( 'wp_ajax_image_imported', 'ajax_epimaapi_image_imported' );
add_action( 'wp_ajax_delete_attributes', 'ajax_epimaapi_delete_attributes' );
add_action( 'wp_ajax_get_deleted_entities_count', 'ajax_get_epimaapi_deleted_entities_count' );
add_action( 'wp_ajax_get_deleted_entities_variations', 'ajax_get_epimaapi_deleted_entities_variations' );
add_action( 'wp_ajax_delete_variation', 'ajax_epimaapi_delete_variation' );
add_action( 'wp_ajax_delete_categories', 'ajax_epimaapi_delete_categories' );
add_action( 'wp_ajax_delete_epim_images', 'ajax_epimaapi_delete_epim_images' );
add_action( 'wp_ajax_delete_products', 'ajax_epimaapi_delete_products' );

add_action( 'wp_ajax_fast_create', 'ajax_epimaapi_fast_create' );

function ajax_epimaapi_fast_create() {
	epimaapi_checkSecure();
	echo 'fast_create_function_not_done_yet';
	exit;
}

function ajax_epimaapi_delete_categories() {
	epimaapi_checkSecure();
	include_once(ABSPATH."wp-config.php");
	include_once(ABSPATH."wp-includes/wp-db.php");
	global $wpdb;
	$sql = "DELETE a,c FROM wp_terms AS a LEFT JOIN wp_term_taxonomy AS c ON a.term_id = c.term_id LEFT JOIN wp_term_relationships AS b ON b.term_taxonomy_id = c.term_taxonomy_id WHERE c.taxonomy = 'product_cat'";
	$results = $wpdb->get_results($sql);
	echo json_encode($results);
	exit;
}

function ajax_epimaapi_delete_epim_images() {
	epimaapi_checkSecure();
	echo 'delete_images_function_not_done_yet';
	exit;
}

function ajax_epimaapi_delete_products() {
	epimaapi_checkSecure();
	include_once(ABSPATH."wp-config.php");
	include_once(ABSPATH."wp-includes/wp-db.php");
	global $wpdb;
	$sql = "DELETE relations.*, taxes.*, terms.* FROM wp_term_relationships AS relations INNER JOIN wp_term_taxonomy AS taxes ON relations.term_taxonomy_id=taxes.term_taxonomy_id INNER JOIN wp_terms AS terms ON taxes.term_id=terms.term_id WHERE object_id IN (SELECT ID FROM wp_posts WHERE post_type='product')";
	$results1 = $wpdb->get_results($sql);
	$sql = "DELETE FROM wp_postmeta WHERE post_id IN (SELECT ID FROM wp_posts WHERE post_type = 'product')";
	$results2 = $wpdb->get_results($sql);
	$sql = "DELETE FROM wp_posts WHERE post_type = 'product'";
	$results3 = $wpdb->get_results($sql);
	echo '<pre>'.json_encode($results1).'</pre>'.'<pre>'.json_encode($results2).'</pre>'.'<pre>'.json_encode($results3).'</pre>';
	exit;
}

function ajax_get_epimaapi_deleted_entities_count() {
    epimaapi_checkSecure();
    echo get_epimaapi_deleted_entities_count();
    exit;
}

function ajax_get_epimaapi_deleted_entities_variations() {
    epimaapi_checkSecure();
    if (! empty($_POST['TotalResults'])) {
        echo get_epimaapi_deleted_entities_variations(sanitize_text_field($_POST['TotalResults']));
    } else {
        echo 'Need a Limit Please...';
    }

}

function ajax_epimaapi_delete_variation() {
    epimaapi_checkSecure();
    if (! empty($_POST['variationID'])) {
        echo epimaapi_delete_variation(sanitize_text_field($_POST['variationID']));
    } else {
        echo 'No Variation Supplied...';
    }
}

function ajax_epimaapi_delete_attributes() {
    epimaapi_checkSecure();
	epimaapi_delete_attributes();
    echo 'All attributes removed';
    exit;
}


function ajax_epimaapi_image_imported() {
    epimaapi_checkSecure();
    if ( ! empty( $_POST['ID'] ) ) {
        if(epimaapi_imageImported(sanitize_text_field($_POST['ID']))) {
            echo 'Image Imported';
        } else {
            echo 'Image not Imported';
        }
    }
    exit;
}

function ajax_epimaapi_import_single_product_images() {
    epimaapi_checkSecure();
    if ( ! empty( $_POST['productID'] ) ) {
        if ( ! empty( $_POST['variationID'] ) ) {
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

function ajax_get_epimaapi_branch_stock() {
    epimaapi_checkSecure();
    if( ! empty( $_POST['ID'] ) ) {
        $response = get_epimaapi_branch_stock(  sanitize_text_field($_POST['ID']) );
        echo $response;
    }
    exit;
}


function ajax_get_epimaapi_single_product_images() {
    epimaapi_checkSecure();
    if ( ! empty( $_POST['ID'] ) ) {
        $response = getSingleProductImages(sanitize_text_field($_POST['ID']));
        header( "Content-Type: application/json charset=utf-8" );
        echo json_encode($response);
    } else {
        echo 'error no ID supplied';
    }
    exit;
}

function ajax_epimaapi_product_ID_from_code() {
    epimaapi_checkSecure();
    $response = 'Not Found';
    if ( ! empty( sanitize_text_field($_POST['CODE']) ) ) {
        $response = epimaapi_getAPIIDFromCode(sanitize_text_field($_POST['CODE']));
        //error_log('Code = '.$_POST['CODE'].' | API = '.$response);
    }
    echo $response;
    exit;
}

function ajax_get_epimaapi_product() {
    epimaapi_checkSecure();
    if ( ! empty( $_POST['ID'] ) ) {
        $jsonResponse = get_epimaapi_product( sanitize_text_field($_POST['ID'] ));
        $response     = $jsonResponse;
        header( "Content-Type: application/json" );
        echo json_encode( $response );
    } else {
        echo 'error no ID supplied';
    }
    exit;
}

function ajax_epimaapi_get_category_images() {
    epimaapi_checkSecure();
    if ( ! empty( $_POST['ID'] ) ) {
        header( "Content-Type: application/json" );
        echo epimaapi_getCategoryImages( sanitize_text_field($_POST['ID'] ));
    }
    exit;
}

function ajax_get_epimaapi_product_images() {
    epimaapi_checkSecure();
    $response = getProductImages();
    //error_log(json_encode($response));
    header( "Content-Type: application/json charset=utf-8" );
    echo json_encode($response);
    exit;
}

function ajax_epimaapi_create_product() {
    epimaapi_checkSecure();
    if ( ! empty( $_POST['productID'] ) ) {
        if ( ! empty( $_POST['variationID'] ) ) {
            if ( ! empty( $_POST['productName'] ) ) {
                $pictureIDS = '';
                if(isset($_POST['pictureIDs'])) {
                    $pictureIDS = sanitize_text_field($_POST['pictureIDs']);
                }
                echo epimaapi_create_product(
                	sanitize_text_field($_POST['productID']),
	                sanitize_text_field($_POST['variationID']), sanitize_text_field($_POST['bulletText']),
	                sanitize_text_field($_POST['productName']),
	                $_POST['categoryIDs'],
	                $pictureIDS );
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

function ajax_update_epimaapi_branch_stock() {
    epimaapi_checkSecure();
    if ( ! empty( $_POST['ID'] ) ) {
        if ( ! empty( $_POST['VariationId'] ) ) {
            if ( ! empty( $_POST['Stock'] ) ) {
                echo epimaapi_update_branch_stock(sanitize_text_field($_POST['ID']),
	                sanitize_text_field($_POST['VariationId']),
	                sanitize_text_field($_POST['Stock']));
            }
        }
    }
    exit;
}

function ajax_epimaapi_cat_image_link() {
    epimaapi_checkSecure();
    linkCategoryImages();
    echo 'Category Images Linked';
    exit;
}

function ajax_epimaapi_product_image_link() {
    epimaapi_checkSecure();
    echo epimaapi_linkProductImages();
    //linkVariationImages();
    //echo 'Product Images Linked';
    exit;
}

function ajax_epimaapi_product_group_image_link() {
    epimaapi_checkSecure();
    if ( ! empty( $_POST['productID'] ) ) {
        echo linkProductGroupImages(sanitize_text_field($_POST['productID']));
    }

    exit;
}

function ajax_epimaapi_sort_categories() {
    epimaapi_checkSecure();
	epimaapi_sort_categories();
    echo 'Categories Sorted';
    exit;
}

function ajax_epimaapi_import_picture() {
    epimaapi_checkSecure();
    if ( ! empty( $_POST['ID'] ) ) {
        if ( ! empty( $_POST['weblink'] ) ) {
            echo epimaapi_importPicture( sanitize_text_field($_POST['ID']), sanitize_text_field($_POST['weblink']) );
        }
    }
    exit;
}

function ajax_get_epimaapi_picture_web_link() {
    epimaapi_checkSecure();
    $response = '';
    if ( ! empty( $_POST['ID'] ) ) {
        $response = get_epimaapi_picture( sanitize_text_field($_POST['ID'] ));
    }
    header( "Content-Type: application/json" );
    echo json_encode( $response );
    exit;
}

function ajax_get_epimaapi_all_categories() {
    epimaapi_checkSecure();
    $jsonResponse = get_epimaapi_all_categories();
    $response     = $jsonResponse;
    header( "Content-Type: application/json" );
    echo json_encode( $response );
    exit;
}

function ajax_get_epimaapi_all_branches() {
	epimaapi_checkSecure();
	$jsonResponse = get_epimaapi_all_branches();
	$response     = $jsonResponse;
	header( "Content-Type: application/json" );
	echo json_encode( $response );
	exit;
}

function ajax_get_epimaapi_all_attributes() {
    epimaapi_checkSecure();
    $jsonResponse = get_epimaapi_all_attributes();
    $response     = $jsonResponse;
    header( "Content-Type: application/json" );
    echo json_encode( $response );
    exit;
}

function ajax_get_epimaapi_all_products() {
	//error_log('getting products..');
    epimaapi_checkSecure();
    $jsonResponse = get_epimaapi_all_products();
    $response     = json_decode( $jsonResponse );
    //header( "Content-Type: application/json" );
    echo json_encode( $response->Results );
    exit;
}

function ajax_get_epimaapi_all_changed_products_since() {
    epimaapi_checkSecure();
    if ( ! empty( $_POST['timeCode'] ) ) {
        $jsonResponse = get_epimaapi_all_changed_products_since($_POST['timeCode'] );
        $response = json_decode($jsonResponse);
        //header( "Content-Type: application/json" );
        echo json_encode($response);
    }
    exit;
}



function ajax_get_epimaapi_category() {
    epimaapi_checkSecure();
    if ( ! empty( $_POST['ID'] ) ) {

        $jsonResponse = get_api_category( sanitize_text_field($_POST['ID'] ));
        $response     = $jsonResponse;
        header( "Content-Type: application/json" );
        echo json_encode( $response );
    } else {
        echo 'error no ID supplied';
    }
    exit;
}

function ajax_get_epimaapi_picture() {
    epimaapi_checkSecure();
    if ( ! empty( $_POST['ID'] ) ) {
        //error_log('Getting Picture: '.$_POST['ID']);
        $jsonResponse = get_epimaapi_picture( sanitize_text_field($_POST['ID'] ));
        $response     = $jsonResponse;
        header( "Content-Type: application/json" );
        echo json_encode( $response );
    } else {
        //error_log('error no ID supplied in ajax_get_epimaapi_picture');
    }
    exit;
}

function ajax_get_epimaapi_variation() {
    epimaapi_checkSecure();
    if ( ! empty( $_POST['ID'] ) ) {
        $jsonResponse = get_epimaapi_variation( sanitize_text_field($_POST['ID'] ));
        $response     = $jsonResponse;
        header( "Content-Type: application/json" );
        echo json_encode( $response );
    } else {
        echo 'error no ID supplied';
    }
    exit;
}

function ajax_epimaapi_create_category() {
    epimaapi_checkSecure();
    $response = 'Nothing Happened!!';
    if ( ! empty( $_POST['ID'] ) ) {
        if ( ! empty( $_POST['name'] ) ) {
            $WebPath = '';
            $Picture_ids = '';
            if(isset($_POST['WebPath'])) {
                $WebPath = sanitize_text_field($_POST['WebPath']);
            }
            if(isset($_POST['picture_ids'])) {
                $Picture_ids= sanitize_text_field($_POST['picture_ids']);
            }
            $response = epimaapi_create_category(
	            sanitize_text_field($_POST['ID']),
	            sanitize_text_field($_POST['name']),
	            sanitize_text_field($_POST['ParentID']),
	            $WebPath,
	            $Picture_ids );
        }
    }
    echo $response;
    exit;
}

function ajax_epimaapi_create_branch() {
	epimaapi_checkSecure();
	$response = 'Nothing Happened!!';
	if ( ! empty( $_POST['ID'] ) ) {
        if ( ! empty( $_POST['name'] ) ) {
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