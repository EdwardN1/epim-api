<?php
/**
 * Add a custom product data tab
 */
add_filter('woocommerce_product_tabs', 'epimaapi_product_tab');
function epimaapi_product_tab($tabs)
{

    // Adds the new tab

    $datasheets = get_post_meta(get_the_ID(), '_epim_data_sheets', true);

    if ($datasheets) {
        if (is_array($datasheets)) {
            $tabs['epimaapi_tab'] = array(
                'title' => __('Data Sheets', 'epimaapi'),
                'priority' => 50,
                'callback' => 'epimaapi_product_tab_content'
            );
        }
    }

    return $tabs;

}

function epimaapi_product_tab_content()
{

    $datasheets = get_post_meta(get_the_ID(), '_epim_data_sheets', true);

    if ($datasheets) {
        if (is_array($datasheets)) {
            ?>
            <table class="woocommerce-product-attributes shop_attributes">
                <?php
                foreach ($datasheets as $datasheet) {
                    ?>
                    <tr>
                        <td><a href="<?php echo $datasheet['URL']; ?>" target="_blank"><?php echo $datasheet['Name']; ?></a> </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <?php
        }
    }
}
