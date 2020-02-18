<?php
function displayJSON($apiCall)
{
    $allAttributes = new RecursiveIteratorIterator(new RecursiveArrayIterator(json_decode($apiCall, true)), RecursiveIteratorIterator::SELF_FIRST);
    echo '<div style="padding-bottom: 10px; padding-top: 10px; border-bottom: 1px solid black">';
    echo '<p>';
    //$indent = 1;
    foreach ($allAttributes as $key => $val) {
        if (is_array($val)) {
            echo "</p><p>$key:<br>";
        } else {
            echo "<span style='padding-left: 1em;'> $key => $val</span><br>";
        }
    }
    echo '</p>';
    echo '</div>';
}

function make_api_call($url)
{
    $method = get_option('epim_api_retrieval_method');
    $epim_url = get_option('epim_url');
    if(substr($epim_url,-1 != '/')) {
        $epim_url .= '/';
    }
    $epim_url .= 'api/';
    if ($method == 'curl') {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $epim_url.$url);

        $headers = array();
        $headers[] = "Ocp-Apim-Subscription-Key: ".get_option('epim_key');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $apiCall = curl_exec($ch);

        curl_close($ch);

        return $apiCall;
    } else {
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => "Ocp-Apim-Subscription-Key: ".get_option('epim_key')
            )
        );
        $context = stream_context_create($opts);
        $apiCall = file_get_contents($epim_url.$url, false, $context);

        return $apiCall;
    }

}

function get_api_all_categories()
{
    return make_api_call('Categories');
}

function getTermFromID($id, $terms)
{
    $res = false;
    foreach ($terms as $term) {
        $apiID = get_term_meta($term->term_id, 'epim_api_id', true);
        //$apiID = get_field('api_id', $term);
        if ($apiID == $id) {
            $res = $term;
            break;
        }
    }

    return $res;
}

function create_category($id, $name, $ParentID, $picture_webpath, $picture_ids)
{
    $terms = get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
    ]);
    $term = getTermFromID($id, $terms);
    if ($term) {
        wp_update_term($term->term_id, 'product_cat', array('name' => $name));
        update_term_meta($term->term_id, 'epim_api_id', $id);
        update_term_meta($term->term_id, 'epim_api_picture_link', $picture_webpath);
        update_term_meta($term->term_id, 'epim_api_parent_id', $ParentID);
        $pSuffix = '';
        $pField = '';
        if ($picture_ids) {
            foreach ($picture_ids as $picture_id) {
                $pField .= $pSuffix;
                $pSuffix = ',';
                $pField .= $picture_id;
            }
        }
        update_term_meta($term->term_id, 'epim_api_picture_ids', $pField);
        $response = $name . ' Category Updated ';
    } else {
        $newTerm = wp_insert_term($name, 'product_cat');
        if (is_wp_error($newTerm)) {
            $response = $newTerm->get_error_message() . ' Creating API_ID=' . $id . ' Name=' . $name;
        } else {
            update_term_meta($newTerm['term_id'], 'epim_api_id', $id);
            update_term_meta($newTerm['term_id'], 'epim_api_parent_id', $ParentID);
            $pSuffix = '';
            $pField = '';
            if ($picture_ids) {
                foreach ($picture_ids as $picture_id) {
                    $pField .= $pSuffix;
                    $pSuffix = ',';
                    $pField .= $picture_id;
                }
            }
            update_term_meta($newTerm['term_id'], 'epim_api_picture_ids', $pField);
            $response = $name . ' Category Created';
        }
    }
    return $response;
}

function sort_categories()
{
    $terms = get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
    ]);
    foreach ($terms as $term) {
        $api_parents = get_term_meta($term->term_id, 'epim_api_parent_id', true);
        if ($api_parents != '') {
            $parent = getTermFromID($api_parents, $terms);
            if ($parent) {
                $term_id =$term->term_id;

                $epim_api_id = get_term_meta($term_id, 'epim_api_id', true);
                $epim_api_parent_id = get_term_meta($term_id, 'epim_api_parent_id', true);
                $epim_api_picture_ids = get_term_meta($term_id, 'epim_api_picture_ids', true);
                $epim_api_picture_link = get_term_meta($term_id, 'epim_api_picture_link', true);

                wp_update_term($term_id, 'product_cat', array('parent' => $parent->term_id));

                update_term_meta($term_id, 'epim_api_id', $epim_api_id);
                update_term_meta($term_id, 'epim_api_parent_id', $epim_api_parent_id);
                update_term_meta($term_id, 'epim_api_picture_ids', $epim_api_picture_ids);
                update_term_meta($term_id, 'epim_api_picture_link', $epim_api_picture_link);
            }
        }
    }
}

function getCategoryImages($id)
{
    $term = getCategoryFromId($id);
    $res = array();
    if ($term) {
        $term_id =$term->term_id;
        $api_picture_ids = get_term_meta($term_id, 'epim_api_picture_ids', true);
        $res = str_getcsv($api_picture_ids);
        //error_log($term->name.': picture IDS - '.print_r($res,true));
    } else {
        //error_log('Term not found for ID: '.$id);
    }
    return json_encode($res);
}

function getCategoryFromId($id)
{
    $res = false;
    $terms = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
    ));
    foreach ($terms as $term) {
        $term_id =$term->term_id;
        $api_id = get_term_meta($term_id, 'epim_api_id', true);
        if ($api_id == $id) {
            return $term;
        }
    }
    return $res;
}

function get_api_picture($id)
{
    $res = make_api_call('Pictures/' . $id);
    if($id == '64746') {
        //error_log($res);
    }
    return $res;
}

function linkCategoryImages()
{
    //error_log('Link Category Images Started');
    $terms = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
    ));
    foreach ($terms as $term) {
        $term_id =$term->term_id;
        $api_id = get_term_meta($term_id, 'epim_api_picture_ids', true);
        $attachmentID = imageIDfromAPIID($api_id);
        if ($attachmentID) {
            //error_log('linking image to '.$term->name);
            update_term_meta( $term_id, 'thumbnail_id', absint( $attachmentID ) );
            //update_field('image', $attachmentID, $term);
        }
    }
    //error_log('Link Category Images Ended');
}

function imageIDfromAPIID($id)
{
    $res = false;
    $args = array(
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'orderby' => 'post_date',
        'order' => 'desc',
        'posts_per_page' => '-1',
        'post_status' => 'inherit',
        'meta_key' => 'epim_api_id',
        'meta_value' => $id
    );
    $loop = new WP_Query($args);
    if ($loop->have_posts()) :
        while ($loop->have_posts()) : $loop->the_post();
            $res = get_the_ID();
            break;
        endwhile;
    endif;

    /*$loop = get_posts($args);

    foreach ($loop as $post): setup_postdata($post);
        $res = get_the_ID();
        break;
    endforeach;*/

    wp_reset_postdata();
    return $res;
}

function get_api_all_products()
{
    $apiCall = make_api_call('Products/');
    $allProducts = json_decode($apiCall);
    $TotalResults = $allProducts->TotalResults;

    return make_api_call('Products/?limit=' . $TotalResults);
}

function importPicture($id, $webpath)
{

	$res = 'Picture Import Error';
	if (!imageImported($id)) {
		$attachment_ID = uploadMedia( $webpath );
		if($attachment_ID) {
			error_log( '$attachment_ID: ' . $attachment_ID );
			update_post_meta( $attachment_ID, 'epim_api_id', $id );
			$res = 'Image Imported Sucessfully';
		}
	} else {
		$res = 'Image Already Imported';
	}
	return $res;
}

function imageImported($id)
{
    $args = array(
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'orderby' => 'post_date',
        'order' => 'desc',
        'posts_per_page' => '-1',
        'post_status' => 'inherit',
        'meta_key' => 'epim_api_id',
        'meta_value' => $id
    );
    $loop = new WP_Query($args);

    return $loop->have_posts();

}

function get_image_file($url)
{

    $method = get_option('epim_api_retrieval_method');
    //error_log('get_image_file method: ' . $method);
    if ($method == 'cUrl') {
        //error_log('Getting Remote File using cUrl - ' . $url);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        $apiCall = curl_exec($ch);
        curl_close($ch);
        return $apiCall;
    } else {
        //error_log('Getting Remote File using file_get_contents - ' . $url);
        return file_get_contents($url);
    }
}