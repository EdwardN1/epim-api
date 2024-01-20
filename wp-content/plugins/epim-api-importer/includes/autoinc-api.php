<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *
 *
 ************************************ API Calls*****************************************
 *
 */

function epimaapi_make_curl_call( $url ) {

	if ( function_exists( 'curl_init' ) ) {
		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_URL, $url );

		$headers   = array();
		$headers[] = "Ocp-Apim-Subscription-Key: " . get_option( 'epim_key' );

		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

		$apiCall = curl_exec( $ch );

		curl_close( $ch );

		return $apiCall;
	} else {
		$opts    = array(
			'http' => array(
				'method' => "GET",
				'header' => "Ocp-Apim-Subscription-Key: " . get_option( 'epim_key' )
			)
		);
		$context = stream_context_create( $opts );
		$apiCall = file_get_contents( $url, false, $context );

		return $apiCall;
	}

}

function epimaapi_make_api_call( $url ) {
	$response = null;
	$method   = get_option( 'epim_api_retrieval_method' );
	$epim_url = get_option( 'epim_url' );
	if ( substr( $epim_url, - 1 != '/' ) ) {
		$epim_url .= '/';
	}
	$epim_url .= 'api/';
	if ( $method == 'curl' ) {
		return epimaapi_make_curl_call( $epim_url . $url );
	} else {

		$apiCall = false;

		$args = array(
			'headers' => array(
				'Ocp-Apim-Subscription-Key' => get_option( 'epim_key' )
			)
		);

		$response = wp_safe_remote_get( $epim_url . $url, $args );

		if ( is_array( $response ) && ! is_wp_error( $response ) ) {
			$apiCall = $response['body'];
		} else {
			if ( is_wp_error( $response ) ) {
				//error_log($response->get_error_message());
				//error_log('URL called: '.$epim_url . $url);
				$apiCall = epimaapi_make_curl_call( $epim_url . $url );
			}
		}

		return $apiCall;
	}

}

function get_epimaapi_all_categories() {
	return epimaapi_make_api_call( 'Categories' );
}

function get_epimaapi_all_branches() {
	return epimaapi_make_api_call( 'Branches' );
}

function get_epimaapi_branch_stock( $id ) {
	return epimaapi_make_api_call( 'StockForBranch/' . $id );
}

function get_epimaapi_deleted_entities_count() {
	$apiCall                = epimaapi_make_api_call( 'DeletedEntities?limit=0' );
	$deleted_entities_count = json_decode( $apiCall );
	$TotalResults           = $deleted_entities_count->TotalResults;

	return $TotalResults;
}

function get_epimaapi_deleted_entities_variations( $limit ) {
	$apiCall     = epimaapi_make_api_call( 'DeletedEntities?limit=' . $limit );
	$allEntities = json_decode( $apiCall );
	$results     = $allEntities->Results;
	$res         = array();
	foreach ( $results as $result ) {
		if ( $result->EntityType == 'SKU_Product_Mapping' ) {
			$ele                = array();
			$ele['variationID'] = $result->EntityId;
			$res[]              = $ele;
		}
	}

	return json_encode( $res );
}

function epimaapi_delete_variation( $variationID ) {
	$productID = epimaapi_getProductFromVariationID( $variationID );
	if ( $productID ) {
		if ( wp_delete_post( $productID ) ) {
			echo 'Variation Deleted: ' . $variationID;
		} else {
			echo 'Variation cannot be deleted: ' . $variationID;
		}
	} else {
		echo 'Variation not found: ' . $variationID;
	}
}

function get_epimaapi_picture( $id ) {
	$res = epimaapi_make_api_call( 'Pictures/' . $id );
	if ( $id == '64746' ) {
		//error_log($res);
	}

	return $res;
}

function get_epimaapi_all_products_count() {
    $apiCall      = epimaapi_make_api_call( 'Products/?showUnApproved=true&showArchived=true' );
    $allProducts  = json_decode( $apiCall );
    return $allProducts->TotalResults;
}

function get_epimaapi_all_products() {
    /*$apiCall      = epimaapi_make_api_call( 'Products/' );
    $allProducts  = json_decode( $apiCall );*/
    $TotalResults = get_epimaapi_all_products_count();
    //error_log('$TotalResults = '. $TotalResults);
    $res = epimaapi_make_api_call( 'Products/?limit=' . $TotalResults.'&showUnApproved=true&showArchived=true' );

    //error_log('$res->TotalResults = '. $res->TotalResults);
    return $res;
}

function get_epimaapi_some_products($limit, $start) {
    return epimaapi_make_api_call( 'Products/?start='.$start.'&limit=' . $limit.'&showUnApproved=true&showArchived=true' );
}

function get_epimaapi_variation( $id ) {
	$epim_always_include_epim_attributes = get_option( 'epim_always_include_epim_attributes' );
	$epim_exclude_luckins_data           = get_option( 'epim_exclude_luckins_data' );
	//error_log('$epim_always_include_epim_attributes = '.print_r($epim_always_include_epim_attributes,true));
	$url       = 'Variations/' . $id;
	$queryChar = '?';
	if ( is_array( $epim_always_include_epim_attributes ) ) {
		if ( $epim_always_include_epim_attributes['checkbox_value'] == 1 ) {
			$url       .= '?alwaysIncludeEpimAttributes=true';
			$queryChar = '&';
		}
	}
	if ( is_array( $epim_exclude_luckins_data ) ) {
		if ( $epim_exclude_luckins_data['checkbox_value'] == 1 ) {
			$url .= $queryChar . 'includeLuckins=false';
		}
	}
    $url .= $queryChar . 'sanitizeNames=true'. $queryChar.'showUnApproved=true&showArchived=true';
	//error_log('$url = '.$url);
	return epimaapi_make_api_call( $url );
}

function get_epimaapi_all_attributes() {
	return epimaapi_make_api_call( 'Attributes' );
}

function get_epimaapi_product( $id ) {
	return epimaapi_make_api_call( 'Products/' . $id.'?showUnApproved=true&showArchived=true' );
}

function get_epimaapi_all_changed_products_since( $datetime = '2002-10-02T10:00:00-00:00' ) {
	$xdatetime = substr( $datetime, 0, 10 ) . 'T10:00:00-00:00';
	//error_log('ProductsUpdatedSince?ChangedSinceUTC=' . $xdatetime);
	$i         = epimaapi_make_api_call( 'ProductsUpdatedSince?ChangedSinceUTC=' . $xdatetime .'&showUnApproved=true&showArchived=true' );

	return $i;
}

function get_epimaapi_all_changed_products_since_starting( $start, $datetime = '2002-10-02T10:00:00-00:00' ) {
	$xdatetime = substr( $datetime, 0, 10 ) . 'T10:00:00-00:00';
	$i         = epimaapi_make_api_call( 'ProductsUpdatedSince?ChangedSinceUTC=' . $xdatetime . '&start=' . $start .'&showUnApproved=true&showArchived=true' );

	return $i;
}

function get_epimaapi_get_branch_stock_since( $branch, $datetime ) {
	$r = epimaapi_make_api_call( 'StockForBranchUpdatedSince/' . $branch . '?ChangedSinceUTC=' . $datetime );

	//$r = epimaapi_make_api_call('https://epim.azure-api.net/Grahams/api/ProductsUpdatedSince?ChangedSinceUTC=' . '2020-01-06T10:00:00-00:00');
	return $r;
}

/**
 *
 * *****************************Helpers*********************************************
 *
 */

function epimaapi_background_import_products_from( $timecode ) {
	update_option( '_epim_update_running', 'Getting Products to Import' );
	cron_log( 'Getting Products to Import' );
	$allProductsResponse = json_decode( get_epimaapi_all_changed_products_since( $timecode ), true );
	$variations          = array();
	if ( json_last_error() == JSON_ERROR_NONE ) {
        if(is_array($allProductsResponse)) {
            if ( array_key_exists( 'Results', $allProductsResponse ) ) {
                foreach ( $allProductsResponse['Results'] as $Product ) {
                    if ( get_option( '_epim_update_running' ) == '' ) {
                        exit;
                    }
                    $categories = array();
                    $pictures   = array();
                    if(is_array($Product)) {
                        if ( array_key_exists( 'CategoryIds', $Product ) ) {
                            $categories = $Product['CategoryIds'];
                        }
                        if ( array_key_exists( 'PictureIds', $Product ) ) {
                            $pictures = $Product['PictureIds'];
                        }
                        if ( array_key_exists( 'VariationIds', $Product ) ) {
                            if ( is_array( $Product['VariationIds'] ) ) {
                                foreach ( $Product['VariationIds'] as $variation_id ) {
                                    if ( get_option( '_epim_update_running' ) == '' ) {
                                        exit;
                                    }
                                    $variation                      = array();
                                    $variation['productID']         = $Product['Id'];
                                    $variation['variationID']       = $variation_id;
                                    $variation['productBulletText'] = $Product['BulletText'];
                                    $variation['productName']       = $Product['Name'];
                                    $variation['categoryIds']       = $categories;
                                    $variation['pictureIds']        = $pictures;
                                    $variations[]                   = $variation;
                                }
                            }

                        }
                    }
                }
            }
        }
	} else {
		cron_log( 'ePim is not returning valid JSON, getting products.' );
	}
	update_option( '_epim_background_process_data', $variations );
	update_option( '_epim_update_running', 'Preparing to import products' );
	cron_log( 'Found ' . count( $variations ) . ' products to import' );
	cron_log( 'Preparing to import products' );
}

function epimaapi_background_import_all_start() {
	$jsonResponse = get_epimaapi_all_categories();
	$response     = json_decode( $jsonResponse, true );
	if ( json_last_error() == JSON_ERROR_NONE ) {
		$epim_update_running = 'Preparing to process ePim categories';
		update_option( '_epim_update_running', $epim_update_running );
		update_option( '_epim_background_process_data', $response );

		return $epim_update_running;
	} else {
		return 'ePim is not returning valid JSON, getting all categories.';
	}
}

function epim_api_background_remove_epim_images_start() {
    $args = array(
        'post_type' => 'attachment',
        'numberposts' => -1,
        'post_status' => null,
        'post_parent' => null, // any parent
    );
    $attachments = get_posts($args);
    if ($attachments) {
        $epim_update_running = 'Preparing to delete ePim images';
        update_option( '_epim_update_running', $epim_update_running );
        update_option( '_epim_background_process_data', $attachments );
        return $epim_update_running;
    } else {
        return 'No images found to delete';
    }

}

function epimaapi_getAPIIDFromCode( $code ) {
	$res = false;

	$productID = wc_get_product_id_by_sku( $code );

	if ( $productID ) {
		$APIID = get_post_meta( $productID, 'epim_API_ID', true );
		if ( $APIID ) {
			return $APIID;
		}
	}

	return $res;
}

function epimaapi_getCategoryImages( $id ) {
	$term = epimaapi_getCategoryFromId( $id );
	$res  = array();
	if ( $term ) {
		$term_id         = $term->term_id;
		$api_picture_ids = get_term_meta( $term_id, 'epim_api_picture_ids', true );
		$res             = str_getcsv( $api_picture_ids );
		//error_log($term->name.': picture IDS - '.print_r($res,true));
	} else {
		//error_log('Term not found for ID: '.$id);
	}

	return json_encode( $res );
}

function epimaapi_getCategoryFromId( $id ) {
	$res   = false;
	$terms = get_terms( array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
	) );
	foreach ( $terms as $term ) {
		$term_id = $term->term_id;
		$api_id  = get_term_meta( $term_id, 'epim_api_id', true );
		if ( $api_id == $id ) {
			return $term;
		}
	}

	return $res;
}

function epimaapi_getTermFromID( $id, $terms ) {
	$res = false;
	foreach ( $terms as $term ) {
		$apiID = get_term_meta( $term->term_id, 'epim_api_id', true );
		//$apiID = get_field('api_id', $term);
		if ( $apiID == $id ) {
			$res = $term;
			break;
		}
	}

	return $res;
}

function epimaapi_getAttributeNameFromID( $id, $attributes ) {
	$res = 'Name Not Found';
	foreach ( $attributes as $attribute ) {
		if ( $attribute->Id == $id ) {
			$res = $attribute->Name;
			break;
		}
	}

	return $res;
}

function epimaapi_imageIDfromAPIID( $id ) {
	$res = false;

	if ( $id != '' ):
		$args = array(
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'orderby'        => 'post_date',
			'order'          => 'desc',
			'posts_per_page' => '-1',
			'post_status'    => 'inherit',
			'meta_key'       => 'epim_api_id',
			'meta_value'     => $id
		);
		$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) :
			while ( $loop->have_posts() ) : $loop->the_post();
				$res = get_the_ID();
				break;
			endwhile;
		endif;

		wp_reset_postdata();
	endif;

	return $res;
}

function epimaapi_imageImported( $id ) {
	$args = array(
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'orderby'        => 'post_date',
		'order'          => 'desc',
		'posts_per_page' => '-1',
		'post_status'    => 'inherit',
		'meta_key'       => 'epim_api_id',
		'meta_value'     => $id
	);
	$loop = new WP_Query( $args );

	return $loop->have_posts();

}

function epimaapi_image_url_imported( $url ) {
	$args = array(
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'orderby'        => 'post_date',
		'order'          => 'desc',
		'posts_per_page' => '-1',
		'post_status'    => 'inherit',
		'meta_key'       => 'epim_luckins_path',
		'meta_value'     => $url
	);
	$loop = new WP_Query( $args );

	if ( $loop->have_posts() ) {
		return $loop->posts[0]->ID;
	} else {
		return false;
	}

}

function epimaapi_getBranchID( $id ) {
	$res  = false;
	$args = array(
		'post_type'      => 'cac_branches',
		'posts_per_page' => '-1',
		'post_status'    => 'publish',
		/*'meta_key' => '_branch_epim_id',
		'meta_value' => $id*/
	);
	$loop = new WP_Query( $args );

	if ( $loop->have_posts() ):

		while ( $loop->have_posts() ) : $loop->the_post();
			$ePim_ID = get_post_meta( get_the_ID(), '_branch_epim_id', true );
			//error_log('$id = ' . $id . ' $epim_id = ' . $ePim_ID);
			if ( $id == $ePim_ID ) {
				//error_log('$id = ' . $id . ' $epim_id = ' . $ePim_ID);
				$res = get_the_ID();
				break;
			}
		endwhile;
	endif;

	wp_reset_postdata();

	if ( ! $res ) {
		error_log( 'could not find a branch with ePim ID of: ' . $id );
	}

	return $res;
}

function epimaapi_create_branch( $id, $name, $telephone, $email, $address ) {
	$response = '';
	$postID   = epimaapi_getBranchID( $id );
	//error_log('$id = '.$id.' $postID = '.$postID. ' $name = '. $name.' $email = '.$email);
	if ( $postID ) {
		$post_update = array(
			'ID'          => $postID,
			'post_title'  => $name,
			'post_status' => 'publish'
		);
		wp_update_post( $post_update );
		update_post_meta( $postID, '_branch_email', $email );
		update_post_meta( $postID, '_branch_phone', $telephone );
		update_post_meta( $postID, '_branch_address', $address );
		$response = 'Branch ' . $name . ' Updated.';
	} else {
		$post_new = array(
			'post_title'  => $name,
			'post_type'   => 'cac_branches',
			'post_status' => 'publish'
		);
		$postID   = wp_insert_post( $post_new, true );
		if ( ! is_wp_error( $postID ) ) {
			add_post_meta( $postID, '_branch_email', $email );
			add_post_meta( $postID, '_branch_phone', $telephone );
			add_post_meta( $postID, '_branch_address', $address );
			add_post_meta( $postID, '_branch_epim_id', $id );
			$response = 'Branch ' . $name . ' Created.';
		} else {
			$response = $postID->get_error_message();
		}

	}

	return $response;
}

function epimaapi_update_branch_stock( $id, $variation_id, $stock_level ) {
	$res      = '$id: ' . $id . ' | ' . ' $variation_id: ' . $variation_id . ' update stock level failed';
	$branchID = epimaapi_getBranchID( $id );
	if ( $branchID ) {
		$productID = epimaapi_getProductFromVariationID( $variation_id );
		if ( $productID ) {
			if ( update_post_meta( $productID, 'cac_BRANCH_STOCK_' . $branchID, $stock_level ) ) {
				update_post_meta( $productID, 'cac_USE_BRANCH_STOCK', 'yes' );
				$res = '$id: ' . $id . ' | ' . ' $variation_id: ' . $variation_id . ' update stock level succeeded';
			} else {
				$previous_val = get_post_meta( $productID, 'cac_BRANCH_STOCK_' . $branchID, true );
				if ( $previous_val == $stock_level ) {
					update_post_meta( $productID, 'cac_USE_BRANCH_STOCK', 'yes' );
					$res = '$id: ' . $id . ' | ' . ' $variation_id: ' . $variation_id . ' update stock level succeeded';
				} else {
					$res .= ': stock level mod error';
				}

			}
		} else {
			$res .= ': no Product Found';
		}
	} else {
		$res .= ': no branch Found';
	}

	return $res;
}

function epimaapi_getProductFromVariationID( $variationID ) {
	$res = false;

	$args = array(
		'posts_per_page' => - 1,
		'post_type'      => 'product',
		'meta_key'       => 'epim_variation_ID',
		'meta_value'     => $variationID
	);

	$loop = new WP_Query( $args );
	if ( $loop->have_posts() ):
		while ( $loop->have_posts() ) : $loop->the_post();
			$res = get_the_ID();
			//error_log('post ID for '.$productID. ' / '.$variationID. ' is '.$res);
			break;
		endwhile;
	endif;

	wp_reset_postdata();

	return $res;
}


function epimaapi_getProductFromID( $productID, $variationID ) {
	$res = false;

	$args = array(
		'posts_per_page' => - 1,
		'post_type'      => 'product',
		'meta_query'     => array(
			'relation' => 'AND',
			array(
				'key'   => 'epim_API_ID',
				'value' => $productID
			),
			array(
				'key'   => 'epim_variation_ID',
				'value' => $variationID
			)
		)
	);

	$loop = new WP_Query( $args );
	if ( $loop->have_posts() ):
		while ( $loop->have_posts() ) : $loop->the_post();
			$res = get_the_ID();
			//error_log('post ID for '.$productID. ' / '.$variationID. ' is '.$res);
			break;
		endwhile;
	endif;

	wp_reset_postdata();

	return $res;
}

/**
 *
 *
 *==============================Core Functions=================================
 *
 */


/**
 * @param $id
 * @param $name
 * @param $ParentID
 * @param $picture_webpath
 * @param $picture_ids
 *
 * @return string
 *
 * Create a Category
 *
 */
function epimaapi_create_category( $id, $name, $ParentID, $picture_webpath, $picture_ids ) {
	$response = '';
	$terms    = get_terms( [
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
	] );
	$term     = epimaapi_getTermFromID( $id, $terms );
	if ( $term ) {
		wp_update_term( $term->term_id, 'product_cat', array( 'name' => $name ) );
		update_term_meta( $term->term_id, 'epim_api_id', $id );
		update_term_meta( $term->term_id, 'epim_api_picture_link', $picture_webpath );
		update_term_meta( $term->term_id, 'epim_api_parent_id', $ParentID );
		$pSuffix = '';
		$pField  = '';
		if ( $picture_ids ) {
			foreach ( $picture_ids as $picture_id ) {
				$pField  .= $pSuffix;
				$pSuffix = ',';
				$pField  .= $picture_id;

			}
			if ( $picture_ids[0] ) {
				$jsonPicture  = get_epimaapi_picture( $picture_ids[0] );
				$picture      = json_decode( $jsonPicture );
				$response     .= epimaapi_importPicture( $picture->Id, $picture->WebPath ) . '<br>';
				$attachmentID = epimaapi_imageIDfromAPIID( $picture->Id );
				if ( $attachmentID ) {
					update_term_meta( $term->term_id, 'thumbnail_id', absint( $attachmentID ) );
				}
			}
		}
		update_term_meta( $term->term_id, 'epim_api_picture_ids', $pField );
		$response .= $name . ' Category Updated ';
	} else {
		$newTerm = wp_insert_term( $name, 'product_cat' );
		if ( is_wp_error( $newTerm ) ) {
			$response = $newTerm->get_error_message() . ' Creating API_ID=' . $id . ' Name=' . $name;
		} else {
			update_term_meta( $newTerm['term_id'], 'epim_api_id', $id );
			update_term_meta( $newTerm['term_id'], 'epim_api_parent_id', $ParentID );
			$pSuffix = '';
			$pField  = '';
			if ( $picture_ids ) {
				foreach ( $picture_ids as $picture_id ) {
					$pField  .= $pSuffix;
					$pSuffix = ',';
					$pField  .= $picture_id;
				}
				if ( $picture_ids[0] ) {
					$jsonPicture  = get_epimaapi_picture( $picture_ids[0] );
					$picture      = json_decode( $jsonPicture );
					$response     .= epimaapi_importPicture( $picture->Id, $picture->WebPath ) . '<br>';
					$attachmentID = epimaapi_imageIDfromAPIID( $picture->Id );
					if ( $attachmentID ) {
						update_term_meta( $newTerm['term_id'], 'thumbnail_id', absint( $attachmentID ) );
					}
				}
			}
			update_term_meta( $newTerm['term_id'], 'epim_api_picture_ids', $pField );
			$response .= $name . ' Category Created';
		}
	}

	return $response;
}

/**
 *
 * Sort Categories
 *
 */
function epimaapi_sort_categories() {
	$terms = get_terms( [
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
	] );
	foreach ( $terms as $term ) {
		$api_parents = get_term_meta( $term->term_id, 'epim_api_parent_id', true );
		if ( $api_parents != '' ) {
			$parent = epimaapi_getTermFromID( $api_parents, $terms );
			if ( $parent ) {
				$term_id = $term->term_id;

				$epim_api_id           = get_term_meta( $term_id, 'epim_api_id', true );
				$epim_api_parent_id    = get_term_meta( $term_id, 'epim_api_parent_id', true );
				$epim_api_picture_ids  = get_term_meta( $term_id, 'epim_api_picture_ids', true );
				$epim_api_picture_link = get_term_meta( $term_id, 'epim_api_picture_link', true );

				wp_update_term( $term_id, 'product_cat', array( 'parent' => $parent->term_id ) );

				update_term_meta( $term_id, 'epim_api_id', $epim_api_id );
				update_term_meta( $term_id, 'epim_api_parent_id', $epim_api_parent_id );
				update_term_meta( $term_id, 'epim_api_picture_ids', $epim_api_picture_ids );
				update_term_meta( $term_id, 'epim_api_picture_link', $epim_api_picture_link );
			}
		}
	}
}

/**
 *
 * Link Categories to Images
 *
 */

function epimaapi_linkCategoryImages() {
	//error_log('Link Category Images Started');
	$terms = get_terms( array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
	) );
	foreach ( $terms as $term ) {
		$term_id      = $term->term_id;
		$api_id       = get_term_meta( $term_id, 'epim_api_picture_ids', true );
		$attachmentID = epimaapi_imageIDfromAPIID( $api_id );
		if ( $attachmentID ) {
			//error_log('linking image to '.$term->name);
			update_term_meta( $term_id, 'thumbnail_id', absint( $attachmentID ) );
			//update_field('image', $attachmentID, $term);
		}
	}
	//error_log('Link Category Images Ended');
}

/**
 * @param $id
 * @param $webpath
 *
 * @return string
 *
 * Import a picture
 *
 */
function epimaapi_importPicture( $id, $webpath ) {

	$res = 'Image: ' . $id . ' - Import Error';
	try {
		if ( ! epimaapi_imageImported( $id ) ) {
			$attachment_ID = uploadMedia( $webpath );
			if ( $attachment_ID ) {
				//error_log('$attachment_ID: ' . $attachment_ID);
				update_post_meta( $attachment_ID, 'epim_api_id', $id );
				$res = 'Image: ' . $id . ' - Imported Successfully';
			}
		} else {
			$res = 'Image: ' . $id . ' - Already Imported';
		}
	} catch ( Exception $e ) {
		$res = $e->getMessage();
	}

	$res .= ' - ' . $webpath;

	return $res;
}

function epimaapi_import_url_Picture( $url ) {

	$res_Message   = 'Image: ' . $url . ' - Import Error';
	$attachment_ID = 0;
	$res           = array();
	try {
		$get_a_id = epimaapi_image_url_imported( $url );
		if ( ! $get_a_id ) {
			$attachment_ID = uploadMedia( $url );
			if ( $attachment_ID ) {
				//error_log('$attachment_ID: ' . $attachment_ID);
				update_post_meta( $attachment_ID, 'epim_luckins_path', $url );
				$res_Message = 'Image: ' . $url . ' - Imported Successfully';
			} else {
				$res_Message = 'Image: ' . $url . ' - Import Failure';
			}
		} else {
			$res_Message   = 'Image: ' . $url . ' - Already Imported';
			$attachment_ID = $get_a_id;
		}
	} catch ( Exception $e ) {
		$res_Message = $e->getMessage();
	}

	$res['Message'] = $res_Message . '</br>';
	$res['ID']      = $attachment_ID;

	return $res;
}


/**
 * @param $productID
 * @param $variationID
 * @param $productBulletText
 * @param $productName
 * @param $categoryIds
 * @param $pictureIds
 *
 * @return string
 *
 * Create a Product
 */

function epimaapi_delete_attributes() {
	$attribute_taxonomies = wc_get_attribute_taxonomies();
	foreach ( $attribute_taxonomies as $attribute_taxonomy ) {
		$taxID = wc_attribute_taxonomy_id_by_name( $attribute_taxonomy->attribute_name );
		wc_delete_attribute( $taxID );
	}
}

function epimaapi_create_product( $productID, $variationID, $productBulletText, $productName, $categoryIds, $pictureIds ) {
	$res = '';
	/*
	 * Get Variation Details
	 */
    $jsonProductGroup = get_epimaapi_product($productID);

    if (!$jsonProductGroup) {
        return $productID . ' returns no product group result from API';
    }

    $ProductGroup = json_decode($jsonProductGroup);

    /*$res = $ProductGroup->IsApprovedForPublishing;
    return $res;*/

    $IsPGApprovedForPublishing = $ProductGroup->IsApprovedForPublishing;

    $productArray  = array();
    $jsonVariation = get_epimaapi_variation( $variationID );
    $variation     = json_decode( $jsonVariation );

    if ( ! $variation ) {
        epimaapi_delete_variation( $variationID );
        return $variationID . ' returns no variation result from API. Variation Deleted if it exists in WooCommerce.';
    }

    if($IsPGApprovedForPublishing === true) {

        $varIsApprovedForPublishing = $variation->IsApprovedForPublishing;

        if ( $varIsApprovedForPublishing === true ) {
            $IsArchived = $variation->IsArchived;
            if ( ($IsArchived === true)) {
                $res = $variationID . ' ' . $productName . ' is archived removing product from WooCommerce';
                epimaapi_delete_variation( $variationID );

                return $res;
            }
        } else {
            $res = $variationID . ' ' . $productName . ' is not approved for publishing removing product from WooCommerce';
            epimaapi_delete_variation( $variationID );

            return $res;
        }
    } else {
        $res = $productID . ' Product Group is not approved for publishing. Removing variation '.$variationID. ' ' . $productName. ' $IsPGApprovedForPublishing = '. $IsPGApprovedForPublishing;
        epimaapi_delete_variation( $variationID );
        return $res;
    }




	/*
	 * Get Woo Categories
	 */
	$terms  = get_terms( [
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
	] );
	$catIds = array();
	foreach ( $categoryIds as $category_id ) {
		$realCatID = epimaapi_getTermFromID( $category_id, $terms );
		if ( $realCatID ) {
			$catIds[] = $realCatID->term_id;
		}
	}
	$productArray['categoryIDS'] = $catIds;

	/*
	 * Other product Fields
	 */
	$productArray['productTitle']            = $variation->Name;
	$productArray['productSKU']              = $variation->SKU;
	$productArray['price']                   = $variation->Price;
	$Qty_Price_1                             = $variation->Qty_Price_1;
	$Qty_Price_2                             = $variation->Qty_Price_2;
	$Qty_Price_3                             = $variation->Qty_Price_3;
	$Qty_Break_1                             = $variation->Qty_Break_1;
	$Qty_Break_2                             = $variation->Qty_Break_2;
	$Qty_Break_3                             = $variation->Qty_Break_3;
	$productArray['productDescription']      = (string)$variation->Table_Heading;
	$productArray['productShortDescription'] = (string)$productBulletText;

	if(!$productArray['productTitle'])       $productArray['productTitle'] = $variationID . ' Null Name Provided by API';
	if(!$productArray['productSKU'])       $productArray['productSKU'] = $variationID . ' Null SKU Provided by API';


	if ( ( $productArray['price'] == '' ) || ( $productArray['price'] == 0 ) ) {
		$productArray['price'] = $Qty_Price_1;
	}

	if ( ( $productArray['price'] == '' ) || ( $productArray['price'] == 0 ) ) {
		$productArray['price'] = $Qty_Price_2;
	}

	if ( ( $productArray['price'] == '' ) || ( $productArray['price'] == 0 ) ) {
		$productArray['price'] = $Qty_Price_3;
	}

	//error_log('Price for '.$productArray['productSKU'].','.$variationID.' = '.$productArray['price']);

	/*
	* Set the product Meta Data
	*/
	$epim_API_ID             = array( "meta_key" => "epim_API_ID", "meta_data" => $productID );
	$epim_product_group_name = array( "meta_key" => "epim_product_group_name", "meta_data" => $productName );
	$epim_variation_ID       = array( "meta_key" => "epim_variation_ID", "meta_data" => $variationID );
	$epim_Qty_Break_1        = array( "meta_key" => "epim_Qty_Break_1", "meta_data" => $Qty_Break_1 );
	$epim_Qty_Break_2        = array( "meta_key" => "epim_Qty_Break_2", "meta_data" => $Qty_Break_2 );
	$epim_Qty_Break_3        = array( "meta_key" => "epim_Qty_Break_3", "meta_data" => $Qty_Break_3 );
	$epim_Qty_Price_1        = array( "meta_key" => "epim_Qty_Price_1", "meta_data" => $Qty_Price_1 );
	$epim_Qty_Price_2        = array( "meta_key" => "epim_Qty_Price_2", "meta_data" => $Qty_Price_2 );
	$epim_Qty_Price_3        = array( "meta_key" => "epim_Qty_Price_3", "meta_data" => $Qty_Price_3 );

	$productArray['metaData'] = array(
		$epim_API_ID,
		$epim_product_group_name,
		$epim_variation_ID,
		$epim_Qty_Break_1,
		$epim_Qty_Break_2,
		$epim_Qty_Break_3,
		$epim_Qty_Price_1,
		$epim_Qty_Price_2,
		$epim_Qty_Price_3
	);


	/*
	 * Attributes
	 */
	$aCounter          = 1;
	$productAttributes = array();
	if ( $variation->AttributeValues ) {
		$attribute_taxonomies = wc_get_attribute_taxonomies();
		$currentAttributes    = array();

		foreach ( $attribute_taxonomies as $attribute_taxonomy ) {

			$watName = $attribute_taxonomy->attribute_label;
			if ( ! in_array( $watName, $currentAttributes ) ) {
				//error_log($atName);
				$currentAttributes[] = $watName;
			}
		}
		foreach ( $variation->AttributeValues as $attribute_value ) {
			$atName = $attribute_value->AttributeHeaderName;
			if ( $atName == 'Type' ) {
				$atName = 'Bad attribute Type';
			}
			if ( $atName == 'Product' ) {
				$atName = 'Bad attribute Product';
			}
			if ( $atName ) {
				//error_log($atName);
				$slugName = substr( $atName, 0, 27 );
				if ( ! wc_check_if_attribute_name_is_reserved( $atName ) ) {
					if ( ! in_array( $atName, $currentAttributes ) ) {
						$attribute_id = wc_attribute_taxonomy_id_by_name( $atName );
						if ( ! $attribute_id ) {
							if ( $atName != '' ) {
								$attribute_id = wc_create_attribute(
									array(
										'name' => $atName,
										'slug' => $slugName,
									)
								);
								if ( is_wp_error( $attribute_id ) ) {
									$error_string = $attribute_id->get_error_message();
									error_log( $error_string );
								} else {
									$productAttributes[] = array( "name" => $atName, "options" => array( $attribute_value->Value ), "position" => $aCounter, "visible" => 1, "variation" => 1 );
									$aCounter ++;
								}
							}
						}
					} else {
						$productAttributes[] = array( "name" => $atName, "options" => array( $attribute_value->Value ), "position" => $aCounter, "visible" => 1, "variation" => 1 );
						$aCounter ++;
					}
				}
			} else {
				error_log('No $atName');
			}
		}
	}
	$productArray['attributes'] = $productAttributes;
	//error_log(print_r($productAttributes,true));

	/*
	 * Image processing
	 */

	$imageAttachmentIDS = array();

	$dataSheets = array();

	$epim_prioritise_epim_images = get_option( 'epim_prioritise_epim_images' );

	$epimFirst = false;

	if ( is_array( $epim_prioritise_epim_images ) ) {
		if ( $epim_prioritise_epim_images['checkbox_value'] == 1 ) {
			$epimFirst = true;
		}
	}
    $chk_picture_ids = array();
	if ( $epimFirst ) {

		if ( $pictureIds ) {
			foreach ( $pictureIds as $pictureId ) {
                if(!in_array($pictureId, $chk_picture_ids)) {
                    $jsonPicture = get_epimaapi_picture($pictureId);
                    $picture = json_decode($jsonPicture);
                    $res .= epimaapi_importPicture($picture->Id, $picture->WebPath) . '<br>';
                    $attachmentID = epimaapi_imageIDfromAPIID($picture->Id);
                    if ($attachmentID) {
                        if (!in_array($attachmentID, $imageAttachmentIDS)) {
                            $imageAttachmentIDS[] = $attachmentID;
                        }
                    }
                    $chk_picture_ids[] = $pictureId;
                }
			}
		}

        $variationPictureIds = array();

        $PictureIdsGroupedImages = $variation->PictureIdsGrouped->Image;
        if(is_array($PictureIdsGroupedImages)) {
            foreach ($PictureIdsGroupedImages as $pictureIdsGroupedImage) {
                $variationPictureIds[] = $pictureIdsGroupedImage;
            }
        } else {
            if ( $variation->PictureIds ) {
                foreach ( $variation->PictureIds as $pictureId ) {
                    $variationPictureIds[] = $pictureId;
                }
            }
        }

        $PictureIdsGrouped = $variation->PictureIdsGrouped;

        if($PictureIdsGrouped) {
            if(is_object($PictureIdsGrouped)) {
                foreach ($PictureIdsGrouped as $PictureIdsGroupedKey => $PictureIdsGroupedValue) {
                    if(is_array($PictureIdsGroupedValue)) {
                        if($PictureIdsGroupedKey != 'Image') {
                            foreach ($PictureIdsGroupedValue as $pictureId) {
                                $jsonPicture = get_epimaapi_picture($pictureId);
                                $picture = json_decode($jsonPicture);
                                $dataSheet = array();
                                $dataSheet['Name'] = str_replace('_',' ',$PictureIdsGroupedKey);
                                $dataSheet['URL'] = $picture->Path;
                                $dataSheets[] = $dataSheet;
                            }

                        }
                    }
                }
            }
        }
        //error_log(print_r($dataSheets,true));

		if ( $variationPictureIds ) {

			foreach ( $variationPictureIds as $pictureId ) {

                if(!in_array($pictureId, $chk_picture_ids)) {
                    $jsonPicture = get_epimaapi_picture($pictureId);
                    $picture = json_decode($jsonPicture);
                    $res .= epimaapi_importPicture($picture->Id, $picture->WebPath) . '<br>';
                    $attachmentID = epimaapi_imageIDfromAPIID($picture->Id);
                    if ($attachmentID) {
                        if (!in_array($attachmentID, $imageAttachmentIDS)) {
                            $imageAttachmentIDS[] = $attachmentID;
                        }
                    }
                    $chk_picture_ids[] = $pictureId;
                }
			}
		}


		if ( count( $imageAttachmentIDS ) == 0 ) {
			$LuckinsAssets = $variation->LuckinsAssets;
			if ( is_array( $LuckinsAssets ) ) {
				foreach ( $LuckinsAssets as $luckins_asset ) {
					if ( $luckins_asset->Tag == 'hi-res' ) {
						$importResult = epimaapi_import_url_Picture( $luckins_asset->URL );
						$res          .= $importResult['Message'];
						if ( $importResult['ID'] != 0 ) {
							$imageAttachmentIDS[] = $importResult['ID'];
							$res                  .= '</br> Attachment ID ' . $importResult['ID'] . ' returned.';
						} else {
							$res .= '</br> No Attachment ID returned.';
						}
					}
				}
			}
		}


	} else {
		$LuckinsAssets = $variation->LuckinsAssets;

		if ( is_array( $LuckinsAssets ) ) {
			foreach ( $LuckinsAssets as $luckins_asset ) {
				if ( $luckins_asset->Tag == 'hi-res' ) {
					$importResult = epimaapi_import_url_Picture( $luckins_asset->URL );
					$res          .= $importResult['Message'];
					if ( $importResult['ID'] != 0 ) {
						$imageAttachmentIDS[] = $importResult['ID'];
						$res                  .= '</br> Attachment ID ' . $importResult['ID'] . ' returned.';
					} else {
						$res .= '</br> No Attachment ID returned.';
					}
				} else {
                    if ($luckins_asset->FileType == 'FILETYPE_PDF') {
                        $dataSheet = array();
                        $dataSheet['Name'] = $luckins_asset->AdditionalInfo;
                        $dataSheet['URL'] = $luckins_asset->URL;
                        $dataSheets[] = $dataSheet;
                    }
                }
			}
		}

		if ( count( $imageAttachmentIDS ) == 0 ) {
			if ( $pictureIds ) {
				foreach ( $pictureIds as $pictureId ) {
                    if(!in_array($pictureId, $chk_picture_ids)) {
                        $jsonPicture = get_epimaapi_picture($pictureId);
                        $picture = json_decode($jsonPicture);
                        $res .= epimaapi_importPicture($picture->Id, $picture->WebPath) . '<br>';
                        $attachmentID = epimaapi_imageIDfromAPIID($picture->Id);
                        if ($attachmentID) {
                            if (!in_array($attachmentID, $imageAttachmentIDS)) {
                                $imageAttachmentIDS[] = $attachmentID;
                            }
                        }
                        $chk_picture_ids[] = $pictureId;
                    }
				}
			}
		}

		if ( count( $imageAttachmentIDS ) == 0 ) {
			if ( $variation->PictureIds ) {
				foreach ( $variation->PictureIds as $pictureId ) {
                    if(!in_array($pictureId, $chk_picture_ids)) {
                        $jsonPicture = get_epimaapi_picture($pictureId);
                        $picture = json_decode($jsonPicture);
                        $res .= epimaapi_importPicture($picture->Id, $picture->WebPath) . '<br>';
                        $attachmentID = epimaapi_imageIDfromAPIID($picture->Id);
                        if ($attachmentID) {
                            if (!in_array($attachmentID, $imageAttachmentIDS)) {
                                $imageAttachmentIDS[] = $attachmentID;
                            }
                        }
                        $chk_picture_ids[] = $pictureId;
                    }
				}
			}
		}
	}


	$productArray['imageAttachmentIDS'] = $imageAttachmentIDS;

	$id = epimaapi_getProductFromID( $productID, $variation->Id );

	//error_log('$id = '.$id);

	if ( ! $id ) {
        $newProductID = epimaapi_wooCreateProduct( $productArray );
		if ( $newProductID ) {
		    if($dataSheets) {
                //error_log(print_r($dataSheets,true));
		        update_post_meta($newProductID,'_epim_data_sheets',$dataSheets);
            } else {
                //error_log($newProductID.' no datasheets found');
            }
			$res .= $variation->name . ' (' . $variation->SKU . ') Created<br>';
		} else {
			$res .= 'There was a problem creating productID: ' . $productID . ' variationID: ' . $variationID . '<br>';
		}
	} else {
		$blank_attributes = array();
		update_post_meta( $id, '_product_attributes', $blank_attributes);
		//error_log(print_r(get_post_meta($id,'_product_attributes',true),true));
		if ( epimaapi_wooUpdateProduct( $id, $productArray ) ) {
            if($dataSheets) {
                update_post_meta($id,'_epim_data_sheets',$dataSheets);
            } else {
            }
			$res .= $variation->name . ' (' . $variation->SKU . ') Created<br>';
		} else {
			$res .= 'There was a problem updating productID: ' . $productID . ' variationID: ' . $variationID . '<br>';
		}
	}

	return $res;

}