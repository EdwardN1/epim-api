<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Takes an image url as an argument and upload image to wordpress and returns the media id, later we will use this id to assign the image to product.
function uploadMedia( $image_url ) {
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/media.php' );
    //error_log('Downloading - '.$image_url);
    try {
        $tmp  = download_url( $image_url );
        $file = array(
            'name'     => basename( $image_url ),
            'tmp_name' => $tmp
        );

        if ( is_wp_error( $tmp ) ) {
            $error_string = $tmp->get_error_message();
            //@unlink( $file['tmp_name'] );
            //error_log( $image_url . ' | ' . $error_string );
            throw new Exception( $error_string );

            return false;
        } else {
            $media = media_handle_sideload( $file, 0 );
            if ( is_wp_error( $media ) ) {
                $error_string = $media->get_error_message();
                @unlink( $file['tmp_name'] );
                //error_log( $image_url . ' | ' . $error_string );
                throw new Exception( $error_string );
            } else {
                return $media;
            }
        }
    } catch ( Exception $exception ) {
        error_log( $exception->getMessage() );
    }

    return false;
}

function epimaapi_wooUpdateProduct( $pid, $productArray ) {
    return epimaapi_wooCreateProduct_ex( $pid, $productArray );
}

function epimaapi_wooCreateProduct( $productArray ) {
    $pid =  epimaapi_wooCreateProduct_ex( 0, $productArray );
    /*if($pid) {
        sleep(1);
        epimaapi_wooCreateProduct_ex( $pid, $productArray );
    }*/
    return $pid;
}

function epimaapi_wooCreateProduct_ex( $pid, $productArray ) {
    //error_log('function start');
    $product_id = false;
    try {
        $import_options = get_option( 'epim_no_price_or_stocks' );
        $set_stock      = false;
        if(!$import_options) {
            $set_stock = true;
        } else {
            if ( is_array( $import_options ) ) {
                if ($import_options['checkbox_value'] != 1) {
                    $set_stock = true;
                }
            }
        }
        if ( array_key_exists( 'productTitle', $productArray ) ) {
            $productTitle = $productArray['productTitle'];
            if ( $productTitle == '' ) {
                error_log( 'epimaapi_wooCreatProduct - blank product title' );

                return $product_id;
            }
        } else {
            error_log( 'epimaapi_wooCreatProduct - blank product title' );

            return $product_id;
        }

        if ( array_key_exists( 'status', $productArray ) ) {
            $status = $productArray['status'];
        } else {
            $status = 'publish';
        }
        if ( array_key_exists( 'catalogueVisibility', $productArray ) ) {
            $catalogueVisibility = $productArray['catalogueVisibility'];
        } else {
            $catalogueVisibility = 'visible';
        }
        if ( array_key_exists( 'isVariable', $productArray ) ) {
            $isVariable = $productArray['isVariable'];
        } else {
            $isVariable = false;
        }
        if ( array_key_exists( 'productDescription', $productArray ) ) {
            $productDescription = $productArray['productDescription'];
        } else {
            $productDescription = '';
        }
        if ( array_key_exists( 'productShortDescription', $productArray ) ) {
            $productShortDescription = $productArray['productShortDescription'];
        } else {
            $productShortDescription = '';
        }
        if ( array_key_exists( 'productSKU', $productArray ) ) {
            $productSKU = $productArray['productSKU'];
        } else {
            $productSKU = '';
        }
        if ( array_key_exists( 'price', $productArray ) ) {
            $price = $productArray['price'];
        } else {
            $price = 1;
        }
        if ( array_key_exists( 'regularPrice', $productArray ) ) {
            $regularPrice = $productArray['regularPrice'];
        } else {
            $regularPrice = $price;
        }
        if ( $set_stock ) {
            if ( array_key_exists( 'manageStock', $productArray ) ) {
                $manageStock = $productArray['manageStock'];
            } else {
                $manageStock = false;
            }
            if ( array_key_exists( 'stockQuantity', $productArray ) ) {
                $stockQuantity = $productArray['stockQuantity'];
            } else {
                $stockQuantity = 1;
            }
            if ( array_key_exists( 'stockStatus', $productArray ) ) {
                $stockStatus = $productArray['stockStatus'];
            } else {
                $stockStatus = 'instock';
            }
        } else {
            if(get_option('wpmai_url')) {
                $manageStock = true;
            }
        }
        if ( array_key_exists( 'backorders', $productArray ) ) {
            $backorders = $productArray['backorders'];
        } else {
            $backorders = 'no';
            if(get_option('wpmai_url')) {
                $backorders = 'notify';
            }
        }
        if ( array_key_exists( 'reviewsAllowed', $productArray ) ) {
            $reviewsAllowed = $productArray['reviewsAllowed'];
        } else {
            $reviewsAllowed = true;
        }
        if ( array_key_exists( 'soldIndividually', $productArray ) ) {
            $soldIndividually = $productArray['soldIndividually'];
        } else {
            $soldIndividually = false;
        }
        if ( array_key_exists( 'images', $productArray ) ) {
            $images = $productArray['images'];
        } else {
            $images = array();
        }
        if ( array_key_exists( 'imageAttachmentIDS', $productArray ) ) {
            $imageAttachmentIDS = $productArray['imageAttachmentIDS'];
        } else {
            $imageAttachmentIDS = array();
        }
        if ( array_key_exists( 'attributes', $productArray ) ) {
            $attributes = $productArray['attributes'];
        } else {
            $attributes = array();
        }
        if ( array_key_exists( 'variations', $productArray ) ) {
            $variations = $productArray['variations'];
        } else {
            $variations = array();
        }
        if ( array_key_exists( 'metaData', $productArray ) ) {
            $metaData = $productArray['metaData'];
        } else {
            $metaData = array();
        }

        //error_log('Set Properties');


        /**
         *
         * Attributes array looks like this:
         *
         * $attributes = array(
         * array("name"=>"Size","options"=>array("S","L","XL","XXL"),"position"=>1,"visible"=>1,"variation"=>1),
         * array("name"=>"Color","options"=>array("Red","Blue","Black","White"),"position"=>2,"visible"=>1,"variation"=>1)
         * );*/


        /**
         *
         * Variations array looks like this:
         *
         * $variations = array(
         * array("regular_price"=>10.11,"price"=>10.11,"sku"=>"ABC1","attributes"=>array(array("name"=>"Size","option"=>"L"),array("name"=>"Color","option"=>"Red")),"manage_stock"=>1,"stock_quantity"=>10),
         * array("regular_price"=>10.11,"price"=>10.11,"sku"=>"ABC2","attributes"=>array(array("name"=>"Size","option"=>"XL"),array("name"=>"Color","option"=>"Red")),"manage_stock"=>1,"stock_quantity"=>10)
         *
         * );*/

        /**
         *
         * metaData array looks like this:
         *
         * $metaData = array(array("meta_key"=>"epim_API_ID","meta_data"=>"12345"),array("meta_key"=>"epim_product_group_name","meta_data"=>"Product Group Name"),array("meta_key"=>"epim_variation_ID","meta_data"=>"12345"))
         */

        if ( array_key_exists( 'categoryIDS', $productArray ) ) {
            $categoryIDS = $productArray['categoryIDS'];
            if ( ! is_array( $categoryIDS ) ) {
                error_log( 'epimaapi_wooCreatProduct - Category IDS in unexpected format for ' . $pid );

                return $product_id;
            }
            if ( count( $categoryIDS ) == 0 ) {
                error_log( 'epimaapi_wooCreatProduct - No Category IDS for ' . $pid );

                return $product_id;
            }
        } else {
            error_log( 'epimaapi_wooCreatProduct - No Category IDS supplied for ' . $pid );

            return $product_id;
        }


        /*if ($isVariable) {
            $objProduct = new WC_Product_Variable();
        } else {
            $objProduct = new WC_Product();
        }*/

        if ( $pid > 0 ) {

            $objProduct = wc_get_product( $pid );
            $objProduct->set_image_id( null );
            $objProduct->set_gallery_image_ids( null );
        } else {
            if ( $isVariable ) {
                $objProduct = new WC_Product_Variable();
            } else {
                $objProduct = new WC_Product();
            }
        }

        if ( ! $objProduct ) {
            if ( $isVariable ) {
                $objProduct = new WC_Product_Variable();
            } else {
                $objProduct = new WC_Product();
            }
        }

        $objProduct->set_name( $productTitle );
        $objProduct->set_status( $status );  // can be publish,draft or any wordpress post status
        $objProduct->set_catalog_visibility( $catalogueVisibility ); // add the product visibility status
        $objProduct->set_description( $productDescription );
        $objProduct->set_short_description( $productShortDescription );
        $objProduct->set_sku( $productSKU ); //can be blank in case you don't have sku, but You can't add duplicate sku's
        $objProduct->set_manage_stock( $manageStock ); // true or false
        $objProduct->set_stock_status( $status ); // in stock or out of stock value
        $objProduct->set_backorders( $backorders );
        $objProduct->set_reviews_allowed( $reviewsAllowed );
        $objProduct->set_sold_individually( $soldIndividually );
        $objProduct->set_category_ids( $categoryIDS ); // array of category ids, You can get category id from WooCommerce Product Category Section of Wordpress Admin

        if ( $set_stock ) {
            $objProduct->set_price( $price ); // set product price
            $objProduct->set_regular_price( $regularPrice ); // set product regular price
            $objProduct->set_stock_quantity( $stockQuantity );
        } else {
            //error_log('Stock not set');
        }

        $productImagesIDs = array(); // define an array to store the media ids.
        //$images = array("image1 url","image2 url","image3 url"); // images url array of product
        foreach ( $imageAttachmentIDS as $image_attachment_ID ) {
            $productImagesIDs[] = $image_attachment_ID;
        }
        foreach ( $images as $image ) {
            $mediaID = uploadMedia( $image ); // calling the uploadMedia function and passing image url to get the uploaded media id
            if ( $mediaID ) {
                $productImagesIDs[] = $mediaID;
            } // storing media ids in a array.
        }

        if ( $productImagesIDs ) {
            $objProduct->set_image_id( $productImagesIDs[0] ); // set the first image as primary image of the product

            //in case we have more than 1 image, then add them to product gallery.
            if ( count( $productImagesIDs ) > 1 ) {
                //shift array down
                $productGallery = array();
                $i = 0;
                foreach ($productImagesIDs as $productImagesID) {
                    if($i != 0) {
                        $productGallery[] = $productImagesID;
                    }
                    $i++;
                }
                $objProduct->set_gallery_image_ids( $productGallery );
            }
        }

        $product_id = $objProduct->save(); // it will save the product and return the generated product id

        //error_log('Product saved with ID = '.$product_id);

        /*$WC_attribute = stdClass Object,
            (
                [id] => 42838,
                [name] => Width,
                [slug] => pa_epim-width,
                [type] => select,
                [order_by] => menu_order,
                [has_archives] => ,
            )*/


        if ( $attributes ) {

            //Create terms

            $attr_data = array();

            foreach ($attributes as $attribute) {
                $order = 0;
                $termToAdd = false;
                $attr_rec = array();
                foreach ($attribute['options'] as $option) {
                    $o_slug = sanitize_title(stripslashes( str_replace(' ','-',$option)));
                    //$o_name = str_replace('-',' ',wc_sanitize_taxonomy_name($option));
                    $o_name = $option;
                    if(strlen($o_name)>200) {
                        $o_name = substr($o_name,0,199);
                    }
                    if(strlen($o_slug) > 28) {
                        $o_slug = substr($o_slug,0,27);
                    }
                    $termToAdd = epim_createTerm($o_name,$o_slug,$attribute['slug'],$order);
                    if($termToAdd) {
                        $attr_rec['term_ids'][] = $termToAdd->term_id;
                    } else {
                        error_log('Attribute term creation error: $o_name = '.$o_name. ' ¦ $o_slug = '.$o_slug.' ¦ $attribute[slug] = '. $attribute['slug']. ' ¦ $order ='.$order);
                    }
                    $order++;
                }
                if($termToAdd) {
                    $attr_rec['slug'] = $attribute['slug'];
                    $attr_rec['options'] = $attribute['options'];
                    $attr_rec["position"] = $attribute["position"];
                    $attr_rec["visible"] = $attribute["visible"];
                    $attr_rec["variation"] = $attribute["variation"];
                    $attr_data[] = $attr_rec;
                    //error_log(print_r($attr_data,true));
                }

            }

            $productAttributes = array();

            //Clear current term relationships
            foreach ($attr_data as $attr_datum) {
                //error_log(print_r($attr_datum,true));
                wp_remove_object_terms( $product_id, $attr_datum['term_ids'], $attr_datum['slug'] );
            }

            //Update Product Attributes
            update_post_meta( $product_id, '_product_attributes', $productAttributes );

            //Create New Term Relationships

            foreach ($attr_data as $attr_datum) {
                wp_set_object_terms( $product_id, $attr_datum['term_ids'], 'pa_'.$attr_datum['slug'] );
            }

            foreach ($attr_data as $attr_datum) {
                $productAttributes[ $attr_datum['slug'] ] = array(
                    'name'         => 'pa_'.$attr_datum['slug'],
                    'value'        => $attr_datum["options"],
                    'position'     => $attr_datum["position"],
                    'is_visible'   => $attr_datum["visible"],
                    'is_variation' => $attr_datum["variation"],
                    'is_taxonomy'  => '1'
                );
            }

            //error_log(print_r($productAttributes,true));

            update_post_meta( $product_id, '_product_attributes', $productAttributes );

        }


        /*if ( $variations ) {
            try {
                foreach ( $variations as $variation ) {
                    $objVariation = new WC_Product_Variation();
                    $objVariation->set_price( $variation["price"] );
                    $objVariation->set_regular_price( $variation["regular_price"] );
                    $objVariation->set_parent_id( $product_id );
                    if ( isset( $variation["sku"] ) && $variation["sku"] ) {
                        $objVariation->set_sku( $variation["sku"] );
                    }
                    $objVariation->set_manage_stock( $variation["manage_stock"] );
                    $objVariation->set_stock_quantity( $variation["stock_quantity"] );
                    $objVariation->set_stock_status( 'instock' ); // in stock or out of stock value
                    $var_attributes = array();
                    foreach ( $variation["attributes"] as $vattribute ) {
                        $taxonomy                    = "pa_" . wc_sanitize_taxonomy_name( stripslashes( $vattribute["name"] ) ); // name of variant attribute should be same as the name used for creating product attributes
                        $attr_val_slug               = wc_sanitize_taxonomy_name( stripslashes( $vattribute["option"] ) );
                        $var_attributes[ $taxonomy ] = $attr_val_slug;
                    }
                    $objVariation->set_attributes( $var_attributes );
                    $objVariation->save();
                }
            } catch ( Exception $e ) {
                error_log( 'line 474 epimaapi_wooCreateProduct_ex error: ' . $e->getMessage() );
            }
        }*/

        if ( $metaData ) {
            foreach ( $metaData as $data ) {
                if ( is_array( $data ) ) {
                    if ( array_key_exists( 'meta_key', $data ) ) {
                        if ( array_key_exists( 'meta_data', $data ) ) {
                            update_post_meta( $product_id, $data['meta_key'], $data['meta_data'] );
                        }
                    }
                }

            }
        }
    } catch ( Exception $e ) {
        error_log( 'line 491 epimaapi_wooCreateProduct_ex error: ' . $e->getMessage() . ' for ' . $pid );
    }

    return $product_id;

}

function epim_createAttribute(string $attributeName, string $attributeSlug): ?\stdClass {
    delete_transient('wc_attribute_taxonomies');
    \WC_Cache_Helper::invalidate_cache_group('woocommerce-attributes');

    $attributeLabels = wp_list_pluck(wc_get_attribute_taxonomies(), 'attribute_label', 'attribute_name');
    $attributeWCName = array_search($attributeSlug, $attributeLabels, TRUE);

    if (! $attributeWCName) {
        $attributeWCName = wc_sanitize_taxonomy_name($attributeSlug);
    }

    $attributeId = wc_attribute_taxonomy_id_by_name($attributeWCName);
    //error_log('$attributeId = '.$attributeId.' for slug '.$attributeSlug);
    if (! $attributeId) {
        $taxonomyName = wc_attribute_taxonomy_name($attributeWCName);
        unregister_taxonomy($taxonomyName);
        $attributeId = wc_create_attribute(array(
            'name' => $attributeName,
            'slug' => $attributeSlug,
            'type' => 'select',
            'order_by' => 'menu_order',
            'has_archives' => 0,
        ));

        register_taxonomy($taxonomyName, apply_filters('woocommerce_taxonomy_objects_' . $taxonomyName, array(
            'product'
        )), apply_filters('woocommerce_taxonomy_args_' . $taxonomyName, array(
            'labels' => array(
                'name' => $attributeSlug,
            ),
            'hierarchical' => FALSE,
            'show_ui' => FALSE,
            'query_var' => TRUE,
            'rewrite' => FALSE,
        )));
    }

    if(is_wp_error($attributeId)) {
        error_log($attributeId->get_error_message());
        return null;
    } else {
        return wc_get_attribute($attributeId);
    }


}

function epim_createTerm(string $termName, string $termSlug, string $taxonomy, int $order = 0): ?\WP_Term {
    $taxonomy = wc_attribute_taxonomy_name($taxonomy);

    if (! $term = get_term_by('slug', $termSlug, $taxonomy)) {
        $term = wp_insert_term($termName, $taxonomy, array(
            'slug' => $termSlug,
        ));
        if(is_wp_error($term)) {
            error_log('Error creating term: '.$termName.' ¦ $termSlug = '.$termSlug. ' ¦ $taxonomy = '.$taxonomy. ' ¦ msg = '.$term->get_error_message());
        } else {
            $term = get_term_by('id', $term['term_id'], $taxonomy);
            if ($term) {
                update_term_meta($term->term_id, 'order', $order);
            }
        }
    }

    if(is_wp_error($term)) {
        error_log($term->get_error_message());
        return null;
    }

    return $term;
}

/*$a_slug = 'epim-'.stripslashes( str_replace(' ','-',$attribute["name"]));
                if(strlen($a_slug) > 28) {
                    $a_slug = substr($a_slug,0,27);
                }*/
/*$WC_attribute = epim_createAttribute($attribute['name'],$a_slug);
if($WC_attribute) {
    $attr_rec = array();
    $attr_rec['slug'] = $WC_attribute->slug;
    $attr_rec['term_ids'] = array();
    $attr_rec['options'] = $attribute['options'];
    $attr_rec["position"] = $attribute["position"];
    $attr_rec["visible"] = $attribute["visible"];
    $attr_rec["variation"] = $attribute["variation"];
    $order = 0;
    foreach ($attribute['options'] as $option) {
        $o_slug = 'epim-'.stripslashes( str_replace(' ','-',$option));
        if(strlen($o_slug) > 28) {
            $o_slug = substr($o_slug,0,27);
        }
        $termToAdd = epim_createTerm($option,'epim-'.$option,$o_slug,$order);
        if($termToAdd) $attr_rec['term_ids'][] = $termToAdd->term_id;
        $order++;
    }
    $attr_data[] = $attr_rec;
}*/