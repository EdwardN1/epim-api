<?php
/**
 *======================================= User Account =====================================
 */
function wpam_accounts_useraccount_meta_box() {
	add_meta_box(
		'wpam_accounts_user',
		__( 'User Account', 'wpamaccounts' ),
		'wpam_accounts_useraccount_meta_box_callback',
		'wpam_accounts'
	);
}

function wpam_accounts_useraccount_meta_box_callback( $post ) {

	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'wpam_accounts_useraccount_nonce', 'wpam_accounts_useraccount_nonce' );

	$value = get_post_meta( $post->ID, '_wpam_accounts_useraccount', true );

	echo '<select id="wpam_accounts_useraccount" name="wpam_accounts_useraccount"><option value="">Not Assigned to User Account</option>';
	$args  = array(
		'role'    => 'account_customer',
		'orderby' => 'user_nicename',
		'order'   => 'ASC'
	);
	$users = get_users( $args );
	foreach ( $users as $user ) {
		$selected = '';
		if ( $value == $user->ID ) {
			$selected = ' selected';
		}
		echo '<option value="' . $user->ID . '"' . $selected . '>' . $user->display_name . ' - ' . $user->user_email . '</option>';
	}
	echo '</select>';

	//echo '<input type="text" style="width:100%" id="wpam_accounts_useraccount" name="wpam_accounts_useraccount" value="' . esc_attr( $value ).'">';
}

add_action( 'add_meta_boxes', 'wpam_accounts_useraccount_meta_box' );

function save_wpam_accounts_useraccount_meta_box_data( $post_id ) {

	// Check if our nonce is set.
	if ( ! isset( $_POST['wpam_accounts_useraccount_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['wpam_accounts_useraccount_nonce'], 'wpam_accounts_useraccount_nonce' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* OK, it's safe for us to save the data now. */

	// Make sure that it is set.
	if ( ! isset( $_POST['wpam_accounts_useraccount'] ) ) {
		return;
	}

	// Sanitize user input.
	$my_data = sanitize_text_field( $_POST['wpam_accounts_useraccount'] );

	// Update the meta field in the database.
	update_post_meta( $post_id, '_wpam_accounts_useraccount', $my_data );
}

add_action( 'save_post', 'save_wpam_accounts_useraccount_meta_box_data' );


function wpam_get_country_options() {
	$options = array(
		'Åland Islands'                             => 'AX',
		'Afghanistan'                               => 'AF',
		'Albania'                                   => 'AL',
		'DZ',
		'Algeria'                                   => 'DZ',
		'American Samoa'                            => 'AS',
		'Andorra'                                   => 'AD',
		'Angola'                                    => 'AO',
		'Anguilla'                                  => 'AI',
		'Antarctica'                                => 'AQ',
		'AG',
		'Antigua and Barbuda'                       => 'AG',
		'Argentina'                                 => 'AR',
		'Armenia'                                   => 'AM',
		'Aruba'                                     => 'AW',
		'Australia'                                 => 'AU',
		'Austria'                                   => 'AT',
		'Azerbaijan'                                => 'AZ',
		'Bahamas'                                   => 'BS',
		'Bahrain'                                   => 'BH',
		'Bangladesh'                                => 'BD',
		'Barbados'                                  => 'BB',
		'Belarus'                                   => 'BY',
		'Belau'                                     => 'PW',
		'Belgium'                                   => 'BE',
		'Belize'                                    => 'BZ',
		'Benin'                                     => 'BJ',
		'Bermuda'                                   => 'BM',
		'Bhutan'                                    => 'BT',
		'Bolivia'                                   => 'BO',
		'Bonaire, Saint Eustatius and Saba'         => 'BQ',
		'Bosnia and Herzegovina'                    => 'BA',
		'Botswana'                                  => 'BW',
		'Bouvet Island'                             => 'BV',
		'Brazil'                                    => 'BR',
		'British Indian Ocean Territory'            => 'IO',
		'Brunei'                                    => 'BN',
		'Bulgaria'                                  => 'BG',
		'Burkina Faso'                              => 'BF',
		'Burundi'                                   => 'BI',
		'Cambodia'                                  => 'KH',
		'Cameroon'                                  => 'CM',
		'Canada'                                    => 'CA',
		'Cape Verde'                                => 'CV',
		'Cayman Islands'                            => 'KY',
		'Central African Republic'                  => 'CF',
		'Chad'                                      => 'TD',
		'Chile'                                     => 'CL',
		'China'                                     => 'CN',
		'Christmas Island'                          => 'CX',
		'Cocos (Keeling) Islands'                   => 'CC',
		'Colombia'                                  => 'CO',
		'Comoros'                                   => 'KM',
		'Congo (Brazzaville)'                       => 'CG',
		'Congo (Kinshasa)'                          => 'CD',
		'Cook Islands'                              => 'CK',
		'Costa Rica'                                => 'CR',
		'Croatia'                                   => 'HR',
		'Cuba'                                      => 'CU',
		'Curaçao'                                   => 'CW',
		'Cyprus'                                    => 'CY',
		'Czech Republic'                            => 'CZ',
		'Denmark'                                   => 'DK',
		'Djibouti'                                  => 'DJ',
		'Dominica'                                  => 'DM',
		'Dominican Republic'                        => 'DO',
		'Ecuador'                                   => 'EC',
		'Egypt'                                     => 'EG',
		'El Salvador'                               => 'SV',
		'Equatorial Guinea'                         => 'GQ',
		'Eritrea'                                   => 'ER',
		'Estonia'                                   => 'EE',
		'Ethiopia'                                  => 'ET',
		'Falkland Islands'                          => 'FK',
		'Faroe Islands'                             => 'FO',
		'Fiji'                                      => 'FJ',
		'Finland'                                   => 'FI',
		'France'                                    => 'FR',
		'French Guiana'                             => 'GF',
		'French Polynesia'                          => 'PF',
		'French Southern Territories'               => 'TF',
		'Gabon'                                     => 'GA',
		'Gambia'                                    => 'GM',
		'Georgia'                                   => 'GE',
		'Germany'                                   => 'DE',
		'Ghana'                                     => 'GH',
		'Gibraltar'                                 => 'GI',
		'Greece'                                    => 'GR',
		'Greenland'                                 => 'GL',
		'Grenada'                                   => 'GD',
		'Guadeloupe'                                => 'GP',
		'Guam'                                      => 'GU',
		'Guatemala'                                 => 'GT',
		'Guernsey'                                  => 'GG',
		'Guinea'                                    => 'GN',
		'Guinea-Bissau'                             => 'GW',
		'Guyana'                                    => 'GY',
		'Haiti'                                     => 'HT',
		'Heard Island and McDonald Islands'         => 'HM',
		'Honduras'                                  => 'HN',
		'Hong Kong'                                 => 'HK',
		'Hungary'                                   => 'HU',
		'Iceland'                                   => 'IS',
		'India'                                     => 'IN',
		'Indonesia'                                 => 'ID',
		'Iran'                                      => 'IR',
		'Iraq'                                      => 'IQ',
		'Ireland'                                   => 'IE',
		'Isle of Man'                               => 'IM',
		'Israel'                                    => 'IL',
		'Italy'                                     => 'IT',
		'Ivory Coast'                               => 'CI',
		'Jamaica'                                   => 'JM',
		'Japan'                                     => 'JP',
		'Jersey'                                    => 'JE',
		'Jordan'                                    => 'JO',
		'Kazakhstan'                                => 'KZ',
		'Kenya'                                     => 'KE',
		'Kiribati'                                  => 'KI',
		'Kuwait'                                    => 'KW',
		'Kyrgyzstan'                                => 'KG',
		'Laos'                                      => 'LA',
		'Latvia'                                    => 'LV',
		'Lebanon'                                   => 'LB',
		'Lesotho'                                   => 'LS',
		'Liberia'                                   => 'LR',
		'Libya'                                     => 'LY',
		'Liechtenstein'                             => 'LI',
		'Lithuania'                                 => 'LT',
		'Luxembourg'                                => 'LU',
		'Macao'                                     => 'MO',
		'Madagascar'                                => 'MG',
		'Malawi'                                    => 'MW',
		'Malaysia'                                  => 'MY',
		'Maldives'                                  => 'MV',
		'Mali'                                      => 'ML',
		'Malta'                                     => 'MT',
		'Marshall Islands'                          => 'MH',
		'Martinique'                                => 'MQ',
		'Mauritania'                                => 'MR',
		'Mauritius'                                 => 'MU',
		'Mayotte'                                   => 'YT',
		'Mexico'                                    => 'MX',
		'Micronesia'                                => 'FM',
		'Moldova'                                   => 'MD',
		'Monaco'                                    => 'MC',
		'Mongolia'                                  => 'MN',
		'Montenegro'                                => 'ME',
		'Montserrat'                                => 'MS',
		'Morocco'                                   => 'MA',
		'Mozambique'                                => 'MZ',
		'Myanmar'                                   => 'MM',
		'Namibia'                                   => 'NA',
		'Nauru'                                     => 'NR',
		'Nepal'                                     => 'NP',
		'Netherlands'                               => 'NL',
		'New Caledonia'                             => 'NC',
		'New Zealand'                               => 'NZ',
		'Nicaragua'                                 => 'NI',
		'Niger'                                     => 'NE',
		'Nigeria'                                   => 'NG',
		'Niue'                                      => 'NU',
		'Norfolk Island'                            => 'NF',
		'North Korea'                               => 'KP',
		'North Macedonia'                           => 'MK',
		'Northern Mariana Islands'                  => 'MP',
		'Norway'                                    => 'NO',
		'Oman'                                      => 'OM',
		'Pakistan'                                  => 'PK',
		'Palestinian Territory'                     => 'PS',
		'Panama'                                    => 'PA',
		'Papua New Guinea'                          => 'PG',
		'Paraguay'                                  => 'PY',
		'Peru'                                      => 'PE',
		'Philippines'                               => 'PH',
		'Pitcairn'                                  => 'PN',
		'Poland'                                    => 'PL',
		'Portugal'                                  => 'PT',
		'Puerto Rico'                               => 'PR',
		'Qatar'                                     => 'QA',
		'Reunion'                                   => 'RE',
		'Romania'                                   => 'RO',
		'Russia'                                    => 'RU',
		'Rwanda'                                    => 'RW',
		'São Tomé and Príncipe'                     => 'ST',
		'Saint Barthélemy'                          => 'BL',
		'Saint Helena'                              => 'SH',
		'Saint Kitts and Nevis'                     => 'KN',
		'Saint Lucia'                               => 'LC',
		'Saint Martin (Dutch part)'                 => 'SX',
		'Saint Martin (French part)'                => 'MF',
		'Saint Pierre and Miquelon'                 => 'PM',
		'Saint Vincent and the Grenadines'          => 'VC',
		'Samoa'                                     => 'WS',
		'San Marino'                                => 'SM',
		'Saudi Arabia'                              => 'SA',
		'Senegal'                                   => 'SN',
		'Serbia'                                    => 'RS',
		'Seychelles'                                => 'SC',
		'Sierra Leone'                              => 'SL',
		'Singapore'                                 => 'SG',
		'Slovakia'                                  => 'SK',
		'Slovenia'                                  => 'SI',
		'Solomon Islands'                           => 'SB',
		'Somalia'                                   => 'SO',
		'South Africa'                              => 'ZA',
		'South Georgia/Sandwich Islands'            => 'GS',
		'South Korea'                               => 'KR',
		'South Sudan'                               => 'SS',
		'Spain'                                     => 'ES',
		'Sri Lanka'                                 => 'LK',
		'Sudan'                                     => 'SD',
		'Suriname'                                  => 'SR',
		'Svalbard and Jan Mayen'                    => 'SJ',
		'Swaziland'                                 => 'SZ',
		'Sweden'                                    => 'SE',
		'Switzerland'                               => 'CH',
		'Syria'                                     => 'SY',
		'Taiwan'                                    => 'TW',
		'Tajikistan'                                => 'TJ',
		'Tanzania'                                  => 'TZ',
		'Thailand'                                  => 'TH',
		'Timor-Leste'                               => 'TL',
		'Togo'                                      => 'TG',
		'Tokelau'                                   => 'TK',
		'Tonga'                                     => 'TO',
		'Trinidad and Tobago'                       => 'TT',
		'Tunisia'                                   => 'TN',
		'Turkey'                                    => 'TR',
		'Turkmenistan'                              => 'TM',
		'Turks and Caicos Islands'                  => 'TC',
		'Tuvalu'                                    => 'TV',
		'Uganda'                                    => 'UG',
		'Ukraine'                                   => 'UA',
		'United Arab Emirates'                      => 'AE',
		'United Kingdom (UK)'                       => 'GB',
		'United States (US)'                        => 'US',
		'United States (US) Minor Outlying Islands' => 'UM',
		'Uruguay'                                   => 'UY',
		'Uzbekistan'                                => 'UZ',
		'Vanuatu'                                   => 'VU',
		'Vatican'                                   => 'VA',
		'Venezuela'                                 => 'VE',
		'Vietnam'                                   => 'VN',
		'Virgin Islands (British)'                  => 'VG',
		'Virgin Islands (US)'                       => 'VI',
		'Wallis and Futuna'                         => 'WF',
		'Western Sahara'                            => 'EH',
		'Yemen'                                     => 'YE',
		'Zambia'                                    => 'ZM',
		'Zimbabwe'                                  => 'ZW',
	);

	return $options;
}

/**
 *======================================= Test Repeater =====================================
 */

function wpam_accounts_billing_repeater_meta_box() {
	add_meta_box(
		'wpam_accounts_user',
		__( 'Billing Addresses', 'wpamaccounts' ),
		'wpam_accounts_repeater_billing_meta_box_callback',
		'wpam_accounts'
	);
}

function wpam_accounts_repeater_billing_meta_box_callback() {
	global $post;

	$repeatable_fields = get_post_meta( $post->ID, '_wpam_billing_repeater_fields', true );
	$countries         = wpam_get_country_options();

	wp_nonce_field( 'wpam_repeatable_billing_meta_box_nonce', 'wpam_repeatable_billing_meta_box_nonce' );
	?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('#billing-add-row').on('click', function () {
                var row = $('.billing-blank-row .repeater-row').clone(true);
                $('#billing-repeatable-fieldset-one').append(row);
                $('#billing-repeatable-fieldset-one *').prop("disabled", false);
                return false;
            });

            $('.remove-row').on('click', function () {
                $(this).parents('tr').remove();
                return false;
            });
        });
    </script>

    <style>
        .repeater-row table {
            border-spacing: 0;
        }

        .repeater-row .remove-row, .repeater-row select, .repeater-row input.top-pad {
            margin-top: 0.5em;
        }

    </style>


    <table id="billing-repeatable-fieldset-one" width="100%">

        <tbody>
		<?php

		if ( $repeatable_fields ) :

			foreach ( $repeatable_fields as $field ) {
				?>
                <tr class="repeater-row">
                    <td>
                        <table>
                            <tr>
                                <td>
                                    <table>
                                        <tr>
                                            <td>
                                                <label for="billing-first-name[]">First Name:
                                                    <input type="text" class="widefat" name="billing-first-name[]" value="<?php if ( $field['billing-first-name'] != '' ) {
														echo esc_attr( $field['billing-first-name'] );
													} ?>"/>
                                                </label>
                                            </td>
                                            <td>
                                                <label for="billing-last-name[]">Last Name:
                                                    <input type="text" class="widefat" name="billing-last-name[]" value="<?php if ( $field['billing-last-name'] != '' ) {
														echo esc_attr( $field['billing-last-name'] );
													} ?>"/>
                                                </label>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="billing-company-name[]">Company Name:
                                        <input type="text" class="widefat" name="billing-company-name[]" value="<?php if ( $field['billing-company-name'] != '' ) {
											echo esc_attr( $field['billing-company-name'] );
										} ?>"/>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <select name="billing-country[]">
										<?php foreach ( $countries as $label => $value ) : ?>
                                            <option value="<?php echo $value; ?>"<?php selected( $field['billing-country'], $value ); ?>><?php echo $label; ?></option>
										<?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="billing-street-address-1[]">Street Address:
                                        <input type="text" class="widefat" name="billing-street-address-1[]" value="<?php if ( $field['billing-Street-Address-1'] != '' ) {
					                        echo esc_attr( $field['billing-street-address-1'] );
				                        } ?>"/><br>
                                        <input type="text" class="widefat" name="billing-street-address-2[]" value="<?php if ( $field['billing-street-address-2'] != '' ) {
		                                    echo esc_attr( $field['billing-street-address-1'] );
	                                    } ?>" class="top-pad"/>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="billing-town-city[]">Town / City:
                                        <input type="text" class="widefat" name="billing-town-city[]" value="<?php if ( $field['billing-town-city'] != '' ) {
					                        echo esc_attr( $field['billing-town-city'] );
				                        } ?>"/>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="billing-county[]">County:
                                        <input type="text" class="widefat" name="billing-county[]" value="<?php if ( $field['billing-county'] != '' ) {
					                        echo esc_attr( $field['billing-county'] );
				                        } ?>"/>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="billing-postcode[]">Postcode:
                                        <input type="text" class="widefat" name="billing-postcode[]" value="<?php if ( $field['billing-postcode'] != '' ) {
					                        echo esc_attr( $field['billing-postcode'] );
				                        } ?>"/>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="billing-phone[]">Phone:
                                        <input type="text" class="widefat" name="billing-phone[]" value="<?php if ( $field['billing-phone'] != '' ) {
					                        echo esc_attr( $field['billing-phone'] );
				                        } ?>"/>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="billing-email[]">Email address:
                                        <input type="email" class="widefat" name="billing-email[]" value="<?php if ( $field['billing-email'] != '' ) {
					                        echo esc_attr( $field['billing-email'] );
				                        } ?>"/>
                                    </label>
                                </td>
                            </tr>

                            <tr>
                                <td><a class="button remove-row" href="#">Remove Address</a></td>
                            </tr>
                            <tr>
                                <td>
                                    <hr>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
				<?php
			}

		endif; ?>


        </tbody>
    </table>

    <!-- empty hidden one for jQuery -->
    <table class="billing-blank-row" style="display: none;">
        <tr class="repeater-row">
            <td>
                <table>
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td>
                                        <label for="billing-first-name[]">First Name:
                                            <input disabled type="text" class="widefat" name="billing-first-name[]" value=""/>
                                        </label>
                                    </td>
                                    <td>
                                        <label for="billing-last-name[]">Last Name:
                                            <input disabled type="text" class="widefat" name="billing-last-name[]" value=""/>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="billing-company-name[]">Company Name:
                                <input disabled type="text" class="widefat" name="billing-company-name[]" value=""/>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <select disabled name="billing-country[]">
								<?php foreach ( $countries as $label => $value ) : ?>
                                    <option value="<?php echo $value; ?>" <?php if ( $value == 'GB' ) {
										echo ' selected';
									} ?>><?php echo $label; ?></option>
								<?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="billing-street-address-1[]">Street Address:
                                <input disabled type="text" class="widefat" name="billing-street-address-1[]" value=""/><br>
                                <input disabled type="text" class="widefat" name="billing-street-address-2[]" value="" class="top-pad"/>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="billing-town-city[]">Town / City:
                                <input disabled type="text" class="widefat" name="billing-town-city[]" value=""/>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="billing-county[]">County:
                                <input disabled type="text" class="widefat" name="billing-county[]" value=""/>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="billing-postcode[]">Postcode:
                                <input disabled type="text" class="widefat" name="billing-postcode[]" value=""/>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="billing-phone[]">Phone:
                                <input disabled type="text" class="widefat" name="billing-phone[]" value=""/>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="billing-email[]">Email address:
                                <input disabled type="email" class="widefat" name="billing-email[]" value=""/>
                            </label>
                        </td>
                    </tr>

                    <tr>
                        <td><a class="button remove-row" href="#">Remove Address</a></td>
                    </tr>
                    <tr>
                        <td>
                            <hr>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <p><a id="billing-add-row" class="button" href="#">Add Address</a></p>
	<?php
}

add_action( 'add_meta_boxes', 'wpam_accounts_billing_repeater_meta_box' );

function wpam_accounts_billing_repeatable_meta_box_save( $post_id ) {
	if ( ! isset( $_POST['wpam_repeatable_billing_meta_box_nonce'] ) ||
	     ! wp_verify_nonce( $_POST['wpam_repeatable_billing_meta_box_nonce'], 'wpam_repeatable_billing_meta_box_nonce' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$old     = get_post_meta( $post_id, '_wpam_billing_repeater_fields', true );
	$new     = array();
	$options = wpam_get_country_options();


	$billing_first_names   = isset( $_POST['billing-first-name'] ) ? (array) $_POST['billing-first-name'] : array();
	$billing_last_names    = isset( $_POST['billing-last-name'] ) ? (array) $_POST['billing-last-name'] : array();
	$billing_company_names = isset( $_POST['billing-company-name'] ) ? (array) $_POST['billing-company-name'] : array();
	$billing_countries     = isset( $_POST['billing-country'] ) ? (array) $_POST['billing-country'] : array();

	$count = count( $billing_first_names );

	for ( $i = 0; $i < $count; $i ++ ) {

		if ( $billing_first_names[ $i ] != '' ) :
			$new[ $i ]['billing-first-name'] = sanitize_text_field( $billing_first_names[ $i ] );
		endif;

		if ( $billing_last_names[ $i ] != '' ) :
			$new[ $i ]['billing-last-name'] = sanitize_text_field( $billing_last_names[ $i ] );
		endif;

		if ( $billing_company_names[ $i ] != '' ) :
			$new[ $i ]['billing-company-name'] = sanitize_text_field( $billing_company_names[ $i ] );
		endif;

		if ( in_array( $billing_countries[ $i ], $options ) ) {
			$new[ $i ]['billing-country'] = sanitize_text_field( $billing_countries[ $i ] );
		} else {
			$new[ $i ]['billing-country'] = '';
		}
	}


	if ( ! empty( $new ) && $new != $old ) {
		update_post_meta( $post_id, '_wpam_billing_repeater_fields', $new );
	} elseif ( empty( $new ) && $old ) {
		delete_post_meta( $post_id, '_wpam_billing_repeater_fields', $old );
	}
}

add_action( 'save_post', 'wpam_accounts_billing_repeatable_meta_box_save' );