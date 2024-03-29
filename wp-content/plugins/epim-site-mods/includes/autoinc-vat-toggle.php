<?php

add_action('woocommerce_before_calculate_totals','epsm_before_calculate_totals',9999);

function epsm_before_calculate_totals($cart) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;
    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) return;

    $epim_use_qty_price_breaks = get_option('epim_use_qty_price_breaks');
    if(!(is_array($epim_use_qty_price_breaks)&&($epim_use_qty_price_breaks['checkbox_value']=='1'))) return;

    $user = wp_get_current_user();
    $price_customer_1 = in_array('price_customer_1', $user->roles);
    $price_customer_2 = in_array('price_customer_2', $user->roles);
    $price_customer_3 = in_array('price_customer_3', $user->roles);

    if((!$price_customer_1)&&(!$price_customer_2)&&(!$price_customer_3)&&(! wc_current_user_has_role( 'customer' ))) return;

    foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
        $product = $cart_item['data'];
        if ($price_customer_1) {
            $p1 = get_post_meta($product->get_id(), 'epim_Qty_Price_1', true);
            if ($p1) {
                $cart_item['data']->set_price( $p1 );
            }
        }
        if ($price_customer_2) {
            $p2 = get_post_meta($product->get_id(), 'epim_Qty_Price_2', true);
            if ($p2) {
                $cart_item['data']->set_price( $p2 );
            }
        }
        if ($price_customer_3) {
            $p3 = get_post_meta($product->get_id(), 'epim_Qty_Price_3', true);
            if ($p3) {
                $cart_item['data']->set_price( $p3 );
            }
        }
    }

}

add_filter( 'woocommerce_get_price_html', 'epsm_get_price_html_override', 100, 2 );

function epsm_get_price_html_override( $price, $product ) {
	$is_in_stock = true;

	$currentTaxIDs = $product->get_category_ids();
	//$POACats = get_field('poa_categories','option');
	/*if($POACats) {
		foreach ($POACats as $POACat) {
			foreach ($currentTaxIDs as $currentTaxID) {
				if ($currentTaxID == $POACat) $is_in_stock = false;
			}
		}
	}*/

    $price_excl_tax = wc_get_price_excluding_tax( $product);
    $price_incl_tax = wc_get_price_including_tax( $product );

    $epim_use_qty_price_breaks = get_option('epim_use_qty_price_breaks');

    if(is_array($epim_use_qty_price_breaks)&&($epim_use_qty_price_breaks['checkbox_value']=='1')) {
        $user = wp_get_current_user();
        $price_customer_1 = in_array('price_customer_1', $user->roles);
        $price_customer_2 = in_array('price_customer_2', $user->roles);
        $price_customer_3 = in_array('price_customer_3', $user->roles);
        if ($price_customer_1) {
            $p1 = get_post_meta(get_the_ID(), 'epim_Qty_Price_1', true);
            if ($p1) {
                $a1 = array(
                    'qty' => '',
                    'price' => $p1,
                );
                $price_excl_tax = wc_get_price_excluding_tax($product, $a1);
                $price_incl_tax = wc_get_price_including_tax($product, $a1);
            }
        }
        if ($price_customer_2) {
            $p2 = get_post_meta(get_the_ID(), 'epim_Qty_Price_2', true);
            if ($p2) {
                $a2 = array(
                    'qty' => '',
                    'price' => $p2,
                );
                $price_excl_tax = wc_get_price_excluding_tax($product, $a2);
                $price_incl_tax = wc_get_price_including_tax($product, $a2);
            }
        }
        if ($price_customer_3) {
            $p3 = get_post_meta(get_the_ID(), 'epim_Qty_Price_3', true);
            if ($p3) {
                $a3 = array(
                    'qty' => '',
                    'price' => $p3,
                );
                $price_excl_tax = wc_get_price_excluding_tax($product, $a3);
                $price_incl_tax = wc_get_price_including_tax($product, $a3);
            }
        }
    }

	if ( ! $price_excl_tax ) {
		$price_excl_tax = 0;
	}
	if ( ! $price_incl_tax ) {
		$price_incl_tax = 0;
	}
	if ( ! $is_in_stock ) {
		$price_excl_tax = 0;
	}

	if ( $price_excl_tax == 0 ) {
		ob_start();
		?>
        <span class="woocommerce-Price-amount amount">
        <span>
            <span class="ex-tax" style="display: none;">POA</span>
            <span class="inc-tax" style="display: none;">POA</span>
        </span>
    </span>
		<?php
		$output = ob_get_clean();

	} else {


		ob_start();
		?>
        <span class="woocommerce-Price-amount amount">
        <span>
            <span class="woocommerce-Price-currencySymbol"><?php echo get_woocommerce_currency_symbol(); ?></span>
            <span class="ex-tax" style="display: none;"><?php echo number_format( $price_excl_tax, 2 ); ?> ex. VAT</span>
            <span class="inc-tax" style="display: none;"><?php echo number_format( $price_incl_tax, 2 ); ?> inc. VAT</span>
        </span>
    </span>
		<?php
		$output = ob_get_clean();
	}

	return $output;
}

add_shortcode( 'epimVatSwitch', 'epsm_vat_switch' );
function epsm_vat_switch( $atts ) {
	$settings = shortcode_atts( array(
		'background' => '#ffffff',
		'width'      => '240px',
		'float'      => 'right',
	), $atts );
	ob_start(); ?>
    <style>
        .tax-display-setting .outer {
            background-color: <?php echo $settings['background'];?>;
            width: <?php echo $settings['width'];?>;
            max-width: <?php echo $settings['width'];?>;
            float: <?php echo $settings['float'];?>;
            padding-top: 0.5rem;
            position: relative;
        }

        .tax-display-setting .outer .grid-x {
            display: flex;
            flex-flow: row wrap;
            justify-content: normal;
        }

        .tax-display-setting .outer .grid-x .cell {
            padding-left: 1em;
            padding-right: 1em;
            flex: 0 0 auto;
            min-height: 0px;
            min-width: 0px;
            width: 100%;
        }

        .tax-display-setting .outer .grid-x>.shrink {
            width: auto;
        }

        @media print, screen and (min-width: 60em) {
            .tax-display-setting .outer .grid-x > .large-shrink {
                width: auto;
                padding: 0;
            }
        }

        .tax-display-setting .outer .grid-x .cell .switch {
            position: relative;
            margin-bottom: 1rem;
            outline: 0;
            font-size: 1rem;
            font-weight: bold;
            color: #fefefe;
            user-select: none;
            height: 1.75rem;
            width: 3.5rem;
            height: 1.75rem;
        }

        .tax-display-setting .outer .grid-x .cell .switch .switch-input {
            position: absolute;
            margin-bottom: 0;
            opacity: 0;
        }

        .tax-display-setting .outer .grid-x .cell .switch-paddle {
            transition: all .25s ease-out;
            font-weight: inherit;
            color: inherit;
            position: relative;
            display: inline-block;
            vertical-align: baseline;
            margin: 0;
            cursor: pointer;
            width: 3.5rem;
            height: 1.75rem;
            font-size: .8571428571rem;
            border-radius: 15px;
        }

        .tax-display-setting .outer .grid-x .cell .switch-paddle .show-for-sr {
            position: absolute !important;
            width: 1px !important;
            height: 1px !important;
            padding: 0 !important;
            overflow: hidden !important;
            clip: rect(0, 0, 0, 0) !important;
            white-space: nowrap !important;
            border: 0 !important;
        }

        .tax-display-setting .outer .grid-x .cell .switch-paddle:after {
            background: #fefefe;
            transition: all .25s ease-out;
            content: "";
            transform: translate3d(0, 0, 0);
            position: absolute;
            width: 1.25rem;
            height: 1.25rem;
            top: 0.25rem;
            left: 0.25rem;
            border-radius: 15px;
        }

        .tax-display-setting .outer .grid-x .cell .switch.small input:checked ~ .switch-paddle::after {
            left: 2rem;
        }
    </style>
    <div class="tax-display-setting secondary-colour">
        <div class="outer">
            <div class="grid-x">
                <div class="cell small-auto large-shrink secondary-colour"></div>
                <div class="cell shrink eBold" style="padding-top: 0.2em;">ex VAT</div>
                <div class="cell shrink">
                    <div class="switch small">
                        <input class="switch-input" id="vatSwitch" type="checkbox" name="vatSwitch">
                        <label class="switch-paddle primary-background" for="vatSwitch">
                            <span class="show-for-sr">VAT switch</span>
                        </label>
                    </div>
                </div>
                <div class="cell shrink iBold" style="padding-top: 0.2em;">inc VAT</div>
            </div>
        </div>
        <div style="width: 100%; height: 0; clear: both;"></div>
    </div>
    <script>
        jQuery(document).ready(function (e) {
            let vc = Cookies.get('vCook')
            if (vc) {
                if (vc == 'inc') {
                    jQuery('#vatSwitch').prop('checked', true);
                    jQuery('.ex-tax').hide();
                    jQuery('.inc-tax').show();
                    jQuery('.iBold').css('font-weight', 'bold');
                    jQuery('.eBold').css('font-weight', 'normal');
                } else {
                    jQuery('#vatSwitch').prop('checked', false);
                    jQuery('.inc-tax').hide();
                    jQuery('.ex-tax').show();
                    jQuery('.iBold').css('font-weight', 'normal');
                    jQuery('.eBold').css('font-weight', 'bold');
                }
            } else {
                Cookies.set('vCook', 'ex')
            }
            jQuery('#vatSwitch').on('change', function (e) {
                let vCook = Cookies.get('vCook');
                if (vCook == 'inc') {
                    jQuery('#vatSwitch').prop('checked', false);
                    Cookies.set('vCook', 'ex');
                    jQuery('.inc-tax').hide();
                    jQuery('.ex-tax').show();
                    jQuery('.iBold').css('font-weight', 'normal');
                    jQuery('.eBold').css('font-weight', 'bold');
                    //window.console.log('set to ex');
                } else {
                    jQuery('#vatSwitch').prop('checked', true);
                    Cookies.set('vCook', 'inc');
                    jQuery('.ex-tax').hide();
                    jQuery('.inc-tax').show();
                    jQuery('.iBold').css('font-weight', 'bold');
                    jQuery('.eBold').css('font-weight', 'normal');
                    //window.console.log('set to inc');

                }
            });
        });
    </script>
	<?php
	$content = ob_get_contents();
	ob_end_clean();

	//return $content;
	return $content;
}

add_shortcode( 'epimMobileVatSwitch', 'epsm_mobile_vat_switch' );
function epsm_mobile_vat_switch( $atts ) {
    $settings = shortcode_atts( array(
        'background' => get_option("epim_divi_primary_color"),
        'width'      => '240px',
        'float'      => 'right',
        'color' => '#ffffff',
    ), $atts );
    ob_start(); ?>
    <style>
        .tax-display-setting.mobile .outer {
            background-color: <?php echo $settings['background'];?>;
            width: <?php echo $settings['width'];?>;
            max-width: <?php echo $settings['width'];?>;
            float: <?php echo $settings['float'];?>;
            padding-top: 0.5rem;
            position: relative;
            color: <?php echo $settings['color'];?>;
        }

        .tax-display-setting.mobile .outer .grid-x {
            display: flex;
            flex-flow: row wrap;
            justify-content: normal;
        }

        .tax-display-setting.mobile .outer .grid-x .cell {
            padding-left: 1em;
            padding-right: 1em;
            flex: 0 0 auto;
            min-height: 0px;
            min-width: 0px;
            width: 100%;
        }

        .tax-display-setting.mobile .outer .grid-x>.shrink {
            width: auto;
        }

        @media print, screen and (min-width: 60em) {
            .tax-display-setting.mobile .outer .grid-x > .large-shrink {
                width: auto;
                padding: 0;
            }
        }

        .tax-display-setting.mobile .outer .grid-x .cell .switch {
            position: relative;
            margin-bottom: 1rem;
            outline: 0;
            font-size: 1rem;
            font-weight: bold;
            color: #fefefe;
            user-select: none;
            height: 1.75rem;
            width: 3.5rem;
            height: 1.75rem;
        }

        .tax-display-setting.mobile .outer .grid-x .cell .switch .switch-input {
            position: absolute;
            margin-bottom: 0;
            opacity: 0;
        }

        .tax-display-setting.mobile .outer .grid-x .cell .switch-paddle {
            transition: all .25s ease-out;
            font-weight: inherit;
            color: inherit;
            position: relative;
            display: inline-block;
            vertical-align: baseline;
            margin: 0;
            cursor: pointer;
            width: 3.5rem;
            height: 1.75rem;
            font-size: .8571428571rem;
            border-radius: 15px;
        }

        .tax-display-setting.mobile .outer .grid-x .cell .switch-paddle .show-for-sr {
            position: absolute !important;
            width: 1px !important;
            height: 1px !important;
            padding: 0 !important;
            overflow: hidden !important;
            clip: rect(0, 0, 0, 0) !important;
            white-space: nowrap !important;
            border: 0 !important;
        }

        .tax-display-setting.mobile .outer .grid-x .cell .switch-paddle:after {
            background: #fefefe;
            transition: all .25s ease-out;
            content: "";
            transform: translate3d(0, 0, 0);
            position: absolute;
            width: 1.25rem;
            height: 1.25rem;
            top: 0.25rem;
            left: 0.25rem;
            border-radius: 15px;
        }

        .tax-display-setting.mobile .outer .grid-x .cell .switch.small input:checked ~ .switch-paddle::after {
            left: 2rem;
        }
    </style>
    <div class="tax-display-setting mobile">
        <div class="outer">
            <div class="grid-x">
                <div class="cell small-auto large-shrink secondary-colour"></div>
                <div class="cell shrink eBold" style="padding-top: 0.2em;">ex VAT</div>
                <div class="cell shrink">
                    <div class="switch small">
                        <input class="switch-input" id="vatSwitchMobile" type="checkbox" name="vatSwitchMobile">
                        <label class="switch-paddle secondary-background" for="vatSwitchMobile">
                            <span class="show-for-sr">VAT switch</span>
                        </label>
                    </div>
                </div>
                <div class="cell shrink iBold" style="padding-top: 0.2em;">inc VAT</div>
            </div>
        </div>
        <div style="width: 100%; height: 0; clear: both;"></div>
    </div>
    <script>
        jQuery(document).ready(function (e) {
            let vc = Cookies.get('vCook')
            if (vc) {
                if (vc == 'inc') {
                    jQuery('#vatSwitchMobile').prop('checked', true);
                    jQuery('.ex-tax').hide();
                    jQuery('.inc-tax').show();
                    jQuery('.iBold').css('font-weight', 'bold');
                    jQuery('.eBold').css('font-weight', 'normal');
                } else {
                    jQuery('#vatSwitch').prop('checked', false);
                    jQuery('.inc-tax').hide();
                    jQuery('.ex-tax').show();
                    jQuery('.iBold').css('font-weight', 'normal');
                    jQuery('.eBold').css('font-weight', 'bold');
                }
            } else {
                Cookies.set('vCook', 'ex')
            }
            jQuery('#vatSwitchMobile').on('change', function (e) {
                let vCook = Cookies.get('vCook');
                if (vCook == 'inc') {
                    jQuery('#vatSwitchMobile').prop('checked', false);
                    Cookies.set('vCook', 'ex');
                    jQuery('.inc-tax').hide();
                    jQuery('.ex-tax').show();
                    jQuery('.iBold').css('font-weight', 'normal');
                    jQuery('.eBold').css('font-weight', 'bold');
                    //window.console.log('set to ex');
                } else {
                    jQuery('#vatSwitchMobile').prop('checked', true);
                    Cookies.set('vCook', 'inc');
                    jQuery('.ex-tax').hide();
                    jQuery('.inc-tax').show();
                    jQuery('.iBold').css('font-weight', 'bold');
                    jQuery('.eBold').css('font-weight', 'normal');
                    //window.console.log('set to inc');

                }
            });
        });
    </script>
    <?php
    $content = ob_get_contents();
    ob_end_clean();

    //return $content;
    return $content;
}