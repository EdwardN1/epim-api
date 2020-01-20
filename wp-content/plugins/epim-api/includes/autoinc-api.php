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