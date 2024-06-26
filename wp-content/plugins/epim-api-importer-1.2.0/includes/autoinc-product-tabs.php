<?php
/**
 * Add a custom product data tab
 */

//Filter Bad Attribute Label

add_filter( 'woocommerce_attribute_label', 'custom_attribute_label', 10, 3 );
function custom_attribute_label( $label, $name, $product ) {
	// For "pa_farge" attribute taxonomy on single product pages.
	$needle = 'Bad attribute ';
	if(is_product()) {
		if(strpos($label,$needle)!==false) {
			$new_label = str_replace($needle,'',$label);
			$label = __($new_label,'woocomerce');
		}
	}
	return $label;
}


add_filter('woocommerce_product_tabs', 'epimaapi_product_tab');
function epimaapi_product_tab($tabs)
{

    // Adds the new tab

    $datasheets = get_post_meta(get_the_ID(), '_epim_data_sheets', true);
    $datasheets_tab_name = get_option('epim_dynamic_data_sheets_tab_name');
    if(!$datasheets_tab_name) $datasheets_tab_name = 'Data Sheets';

    if ($datasheets) {
        if (is_array($datasheets)) {
            $tabs['epimaapi_tab'] = array(
                'title' => __($datasheets_tab_name, 'epimaapi'),
                'priority' => 50,
                'callback' => 'epimaapi_product_tab_content'
            );
        }
    } else {
        $use_dynamic_data_sheets = get_option('epim_use_dynamic_data_sheets');
        if(is_array($use_dynamic_data_sheets)) {
            if (array_key_exists('checkbox_value', $use_dynamic_data_sheets)) {
                if ($use_dynamic_data_sheets['checkbox_value'] == '1') {
                    $tabs['epimaapi_tab'] = array(
                        'title' => __($datasheets_tab_name, 'epimaapi'),
                        'priority' => 50,
                        'callback' => 'epimaapi_product_tab_content'
                    );
                }
            }
        }
    }

    return $tabs;

}

function epimaapi_product_tab_content()
{
    global $product;
    $use_dynamic_data_sheets = get_option('epim_use_dynamic_data_sheets');
    $udds = false;

    if(is_array($use_dynamic_data_sheets)) {
        if(array_key_exists('checkbox_value',$use_dynamic_data_sheets)) {
            $udds = true;
        }
    }

    if ($udds) {
        $dynamic_data_sheets_url = get_option('epim_dynamic_data_sheets_url');
        $epim_dynamic_data_sheets_templates = get_option('epim_dynamic_data_sheets_templates');
        $epim_dynamic_data_sheets_names = get_option('epim_dynamic_data_sheets_names');
        $dynamic_data_sheets_templates = false;
        $dynamic_data_sheets_names = false;
        if($epim_dynamic_data_sheets_templates) {
            $dynamic_data_sheets_templates = preg_split('/\r\n|[\r\n]/', $epim_dynamic_data_sheets_templates);
        }
        if ($epim_dynamic_data_sheets_names) {
            $dynamic_data_sheets_names = preg_split('/\r\n|[\r\n]/', $epim_dynamic_data_sheets_names);
        }

        if ($dynamic_data_sheets_url && $dynamic_data_sheets_templates && $dynamic_data_sheets_names) {
            ?>
            <table class="woocommerce-product-attributes shop_attributes">
                <?php
                if (is_array($dynamic_data_sheets_templates)) {
                    $i = 0;
                    foreach ($dynamic_data_sheets_templates as $data_sheets_template) {
                        $data_sheet_name = 'Data Sheet';
                        if ($dynamic_data_sheets_names[$i]) {
                            $data_sheet_name = $dynamic_data_sheets_names[$i];
                        }
                        ?>
                        <tr>
                            <td>
                                <a href="<?php echo $dynamic_data_sheets_url . '?part=' . get_post_meta( $product->get_id(), '_sku', true ) . '&template=' . $data_sheets_template; ?>"
                                   target="_blank"><?php echo $data_sheet_name; ?></a></td>
                        </tr>
                        <?php
                    }
                    $i++;
                }
                if (in_array('kosnic_flipbook_page', get_post_custom_keys($product->get_id()))) {
                    $kosnic_flipbook_page = get_post_meta($product->get_id(), 'kosnic_flipbook_page', true);
                    if ($kosnic_flipbook_page) {
                        ?>
                        <tr>
                            <td>
                                <a href="https://flipbooks.making.me.uk/kosnic#p=<?php echo $kosnic_flipbook_page;?>"
                                   target="_blank">View in Catalogue</a></td>
                        </tr>
                        <?php
                    }
                }
                $datasheets = get_post_meta(get_the_ID(), '_epim_data_sheets', true);

                if ($datasheets) {
                    if (is_array($datasheets)) {
                        ?>
                            <?php
                            foreach ($datasheets as $datasheet) {
                                ?>
                                <tr>
                                    <td><a href="<?php echo $datasheet['URL']; ?>"
                                           target="_blank"><?php echo $datasheet['Name']; ?></a></td>
                                </tr>
                                <?php
                            }
                            ?>
                        <?php
                    }
                }
                ?>

            </table>
            <?php
        }
    } else {
        $datasheets = get_post_meta(get_the_ID(), '_epim_data_sheets', true);

        if ($datasheets) {
            if (is_array($datasheets)) {
                ?>
                <table class="woocommerce-product-attributes shop_attributes">
                    <?php
                    foreach ($datasheets as $datasheet) {
                        ?>
                        <tr>
                            <td><a href="<?php echo $datasheet['URL']; ?>"
                                   target="_blank"><?php echo $datasheet['Name']; ?></a></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <?php
            }
        }
    }

}
