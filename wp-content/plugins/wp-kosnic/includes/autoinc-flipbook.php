<?php
if (!defined('ABSPATH'))
    exit;

add_filter('woocommerce_product_data_tabs', 'add_kosnic_flipbook_product_data_tab', 99, 1);
function add_kosnic_flipbook_product_data_tab($product_data_tabs)
{

    $product_data_tabs['kosnic-flipbook-tab'] = array(
        'label' => __('Flipbook', 'kosnic_text_domain'),
        'target' => 'kosnic_flipbook_product_data',
    );
    return $product_data_tabs;
}

add_action('woocommerce_product_data_panels', 'add_kosnic_flipbook_product_data_fields');
function add_kosnic_flipbook_product_data_fields($post_id)
{
    global $woocommerce, $post;
    ?>
    <!-- id below must match target registered in above add_epim_product_data_tab function -->
    <div id="kosnic_flipbook_product_data" class="panel woocommerce_options_panel">
        <?php
		woocommerce_wp_text_input( array(
			'id'            => 'kosnic_flipbook_page',
			'wrapper_class' => 'show_if_simple',
			'label'         => __( 'Flipbook Page Number', 'kosnic_text_domain' ),
			'description'   => __( 'The page in the flipbook this product is on', 'kosnic_text_domain' ),
			'default'       => '',
			'desc_tip'      => false,
		) );
        ?>
    </div>
    <?php
}

add_action( 'woocommerce_process_product_meta', 'kosnic_flipbook_process_product_meta_fields_save' );
function kosnic_flipbook_process_product_meta_fields_save( $post_id ){
    $kosnic_flipbook_page=  sanitize_text_field($_POST['kosnic_flipbook_page']);
    update_post_meta( $post_id, 'kosnic_flipbook_page', $kosnic_flipbook_page );
}

function kosnic_import_flipbook($fname) {
    $file_to_read = fopen($fname,'r');
    if($file_to_read !== FALSE){

        while(($data = fgetcsv($file_to_read, 100, ',')) !== FALSE){
            $x = 0;
            $sku = '';
            $page = '';
            //error_log(print_r($data,true));
            for($i = 0; $i < count($data); $i++) {
                if($x==0) $sku = $data[$i];
                if($x==1) $page = $data[$i];
                $x++;
                if(is_numeric($page)) {
                    if(is_int(0+$page)) {
                        if($sku) {
                            $ID = wc_get_product_id_by_sku($sku);
                            if($ID) {
                                update_post_meta( $ID, 'kosnic_flipbook_page', $page );
                                error_log('Updated '.$sku.' page = '.$page);
                            }
                        }
                    }
                }
            }
        }

        fclose($file_to_read);
    }
    return 'File Imported';
}