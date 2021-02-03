<?php
/**
 *======================================= Delivery Address Repeater =====================================
 */

add_action( 'show_user_profile', 'wpiai_accounts_delivery_repeater_meta_box' );
add_action( 'edit_user_profile', 'wpiai_accounts_delivery_repeater_meta_box' );

function wpiai_accounts_delivery_repeater_meta_box($user) {
	global $post;

	$repeatable_fields = get_the_author_meta( 'wpiai_delivery_addresses', $user->ID );
	$countries         = wpiai_get_country_options();

	?>
	<script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('#delivery-add-row').on('click', function () {
                var row = $('.delivery-blank-row .repeater-row').clone(true);
                $('#delivery-repeatable-fieldset-one').append(row);
                $('#delivery-repeatable-fieldset-one *').prop("disabled", false);
                return false;
            });

            $('.remove-row').on('click', function () {
                $(this).parents('tr').remove();
                return false;
            });
        });
	</script>

    <h3><?php _e("Delivery Addresses", "blank"); ?></h3>

	<table id="delivery-repeatable-fieldset-one" width="100%">

		<tbody>
		<?php

		if ( $repeatable_fields ) :

			foreach ( $repeatable_fields as $field ) {
				?>
				<tr class="repeater-row">
					<td>
                        <input type="hidden" name="delivery_UNIQUE_ID[]" value="<?php if ( $field['delivery_UNIQUE_ID'] != '' ) {
							echo esc_attr( $field['delivery_UNIQUE_ID'] );
						} else {echo uniqid();}?>">
						<table class="form-table">
                            <tr>
                                <th>
                                    <label for="delivery-first-name[]">First Name:</label>
                                </th>
                                <td>
                                    <input type="text" class="regular-text" name="delivery-first-name[]" value="<?php if ( $field['delivery-first-name'] != '' ) {
		                                echo esc_attr( $field['delivery-first-name'] );
	                                } ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="delivery-last-name[]">Last Name:</label>
                                </th>
                                <td>
                                    <input type="text" class="regular-text" name="delivery-last-name[]" value="<?php if ( $field['delivery-last-name'] != '' ) {
		                                echo esc_attr( $field['delivery-last-name'] );
	                                } ?>"/>
                                </td>
                            </tr>
							<tr>
								<th>
									<label for="delivery-company-name[]">Company Name:</label>
								</th>
                                <td>
                                    <input type="text" class="regular-text" name="delivery-company-name[]" value="<?php if ( $field['delivery-company-name'] != '' ) {
		                                echo esc_attr( $field['delivery-company-name'] );
	                                } ?>"/>
                                </td>
							</tr>
							<tr>
                                <th>
                                    <label for="delivery-country[]">Country:</label>
                                </th>
								<td>
									<select name="delivery-country[]">
										<?php foreach ( $countries as $label => $value ) : ?>
											<option value="<?php echo $value; ?>"<?php selected( $field['delivery-country'], $value ); ?>><?php echo $label; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
							<tr>
								<th>
									<label for="delivery-street-address-1[]">Street Address 1:</label>
								</th>
                                <td>
                                    <input type="text" class="regular-text" name="delivery-street-address-1[]" style="margin-bottom: 5px;" value="<?php if ( $field['delivery-street-address-1'] != '' ) {
		                                echo esc_attr( $field['delivery-street-address-1'] );
	                                } ?>"/>
                                </td>
							</tr>
                            <tr>
                                <th>
                                    <label for="delivery-street-address-2[]">Street Address 2:</label>
                                </th>
                                <td>
                                    <input type="text" class="regular-text top-pad" name="delivery-street-address-2[]" value="<?php if ( $field['delivery-street-address-2'] != '' ) {
		                                echo esc_attr( $field['delivery-street-address-2'] );
	                                } ?>"/>
                                </td>
                            </tr>
							<tr>
								<th>
									<label for="delivery-town-city[]">Town / City:</label>
								</th>
                                <td>
                                    <input type="text" class="regular-text" name="delivery-town-city[]" value="<?php if ( $field['delivery-town-city'] != '' ) {
		                                echo esc_attr( $field['delivery-town-city'] );
	                                } ?>"/>
                                </td>
							</tr>
							<tr>
								<th>
									<label for="delivery-county[]">County:</label>
								</th>
                                <td>
                                    <input type="text" class="regular-text" name="delivery-county[]" value="<?php if ( $field['delivery-county'] != '' ) {
		                                echo esc_attr( $field['delivery-county'] );
	                                } ?>"/>
                                </td>
							</tr>
							<tr>
								<th>
									<label for="delivery-postcode[]">Postcode:</label>
								</th>
                                <td>
                                    <input type="text" class="regular-text" name="delivery-postcode[]" value="<?php if ( $field['delivery-postcode'] != '' ) {
		                                echo esc_attr( $field['delivery-postcode'] );
	                                } ?>"/>
                                </td>
							</tr>
							<tr>
								<th>
									<label for="delivery-phone[]">Phone:</label>
								</th>
                                <td>
                                    <input type="text" class="regular-text" name="delivery-phone[]" value="<?php if ( $field['delivery-phone'] != '' ) {
		                                echo esc_attr( $field['delivery-phone'] );
	                                } ?>"/>
                                </td>
							</tr>
							<tr>
								<th>
									<label for="delivery-email[]">Email address:</label>
								</th>
                                <td>
                                    <input type="email" class="regular-text" name="delivery-email[]" value="<?php if ( $field['delivery-email'] != '' ) {
		                                echo esc_attr( $field['delivery-email'] );
	                                } ?>"/>
                                </td>
							</tr>

                            <tr>
                                <th>
                                    <label for="delivery-CSD-ID[]">CSD ID:</label>
                                </th>
                                <td>
                                    <input type="text" class="regular-text" name="delivery-CSD-ID[]" value="<?php if ( $field['delivery-CSD-ID'] != '' ) {
		                                echo esc_attr( $field['delivery-CSD-ID'] );
	                                } ?>"/>
                                </td>
                            </tr>

							<tr>
								<td colspan="2"><br><a class="button remove-row" href="#">Remove Address</a></td>
							</tr>
							<tr>
								<td colspan="2">
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
	<table class="delivery-blank-row" style="display: none;">
		<tr class="repeater-row">
			<td>
				<table class="form-table">
					<tr>
                        <th>
                            <label for="delivery-first-name[]">First Name:</label>
                        </th>
                        <td>
                            <input disabled type="text" class="regular-text" name="delivery-first-name[]" value=""/>
                        </td>
					</tr>
                    <tr>
                        <th>
                            <label for="delivery-last-name[]">Last Name:</label>
                        </th>
                        <td>
                            <input disabled type="text" class="regular-text" name="delivery-last-name[]" value=""/>
                        </td>
                    </tr>
					<tr>
						<th>
							<label for="delivery-company-name[]">Company Name:</label>
						</th>
                        <td>
                            <input disabled type="text" class="regular-text" name="delivery-company-name[]" value=""/>
                        </td>
					</tr>
					<tr>
                        <th>
                            <label for="delivery-country[]">Country:</label>
                        </th>
						<td>
							<select disabled name="delivery-country[]">
								<?php foreach ( $countries as $label => $value ) : ?>
									<option value="<?php echo $value; ?>" <?php if ( $value == 'GB' ) {
										echo ' selected';
									} ?>><?php echo $label; ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th>
							<label for="delivery-street-address-1[]">Street Address:</label>
						</th>
                        <td>
                            <input disabled type="text" class="regular-text" name="delivery-street-address-1[]" value=""/><br>
                        </td>
					</tr>
                    <tr>
                        <th>
                            <label for="delivery-street-address-2[]">Street Address:</label>
                        </th>
                        <td>
                            <input disabled type="text" class="regular-text" name="delivery-street-address-2[]" value=""/>
                        </td>
                    </tr>
					<tr>
						<th>
							<label for="delivery-town-city[]">Town / City:</label>
						</th>
                        <td>
                            <input disabled type="text" class="regular-text" name="delivery-town-city[]" value=""/>
                        </td>
					</tr>
					<tr>
						<th>
							<label for="delivery-county[]">County:</label>
						</th>
                        <td>
                            <input disabled type="text" class="regular-text" name="delivery-county[]" value=""/>
                        </td>
					</tr>
					<tr>
						<th>
							<label for="delivery-postcode[]">Postcode:</label>
						</th>
                        <td>
                            <input disabled type="text" class="regular-text" name="delivery-postcode[]" value=""/>
                        </td>
					</tr>
					<tr>
						<th>
							<label for="delivery-phone[]">Phone:</label>
						</th>
                        <td>
                            <input disabled type="text" class="regular-text" name="delivery-phone[]" value=""/>
                        </td>
					</tr>
					<tr>
						<th>
							<label for="delivery-email[]">Email address:</label>
						</th>
                        <td>
                            <input disabled type="email" class="regular-text" name="delivery-email[]" value=""/>
                        </td>
					</tr>
                    <tr>
                        <th>
                            <label for="delivery-CSD-ID[]">CSD ID:</label>
                        </th>
                        <td>
                            <input type="text" class="regular-text" name="delivery-CSD-ID[]" value=""/>
                        </td>
                    </tr>

					<tr>
						<td colspan="2"><br><a class="button remove-row" href="#">Remove Address</a></td>
					</tr>
					<tr>
						<td colspan="2">
							<hr>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<p><a id="delivery-add-row" class="button" href="#">Add Address</a></p>
	<?php
}

function wpiai_accounts_delivery_repeatable_meta_box_save( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}

	$old     = get_the_author_meta( 'wpiai_delivery_addresses', $user_id );
	$new     = array();
	$options = wpiai_get_country_options();

    $delivery_UNIQUE_ID = isset( $_POST['delivery_UNIQUE_ID'] ) ? (array) $_POST['delivery_UNIQUE_ID'] : array();
	$delivery_first_names      = isset( $_POST['delivery-first-name'] ) ? (array) $_POST['delivery-first-name'] : array();
	$delivery_last_names       = isset( $_POST['delivery-last-name'] ) ? (array) $_POST['delivery-last-name'] : array();
	$delivery_company_names    = isset( $_POST['delivery-company-name'] ) ? (array) $_POST['delivery-company-name'] : array();
	$delivery_countries        = isset( $_POST['delivery-country'] ) ? (array) $_POST['delivery-country'] : array();
	$delivery_street_addresses_1  = isset( $_POST['delivery-street-address-1'] ) ? (array) $_POST['delivery-street-address-1'] : array();
	$delivery_street_addresses_2 = isset( $_POST['delivery-street-address-2'] ) ? (array) $_POST['delivery-street-address-2'] : array();
	$delivery_town_cities        = isset( $_POST['delivery-town-city'] ) ? (array) $_POST['delivery-town-city'] : array();
	$delivery_counties        = isset( $_POST['delivery-county'] ) ? (array) $_POST['delivery-county'] : array();
	$delivery_postcodes        = isset( $_POST['delivery-postcode'] ) ? (array) $_POST['delivery-postcode'] : array();
	$delivery_phones        = isset( $_POST['delivery-phone'] ) ? (array) $_POST['delivery-phone'] : array();
	$delivery_emails        = isset( $_POST['delivery-email'] ) ? (array) $_POST['delivery-email'] : array();
	$delivery_CSD_ID        = isset( $_POST['delivery-CSD-ID'] ) ? (array) $_POST['delivery-CSD-ID'] : array();


	$count = count( $delivery_first_names );

	for ( $i = 0; $i < $count; $i ++ ) {

		if ( $delivery_UNIQUE_ID[ $i ] != '' ) :
			$new[ $i ]['delivery_UNIQUE_ID'] = sanitize_text_field( $delivery_UNIQUE_ID[ $i ] );
		endif;

		if ( $delivery_first_names[ $i ] != '' ) :
			$new[ $i ]['delivery-first-name'] = sanitize_text_field( $delivery_first_names[ $i ] );
		endif;

		if ( $delivery_last_names[ $i ] != '' ) :
			$new[ $i ]['delivery-last-name'] = sanitize_text_field( $delivery_last_names[ $i ] );
		endif;

		if ( $delivery_company_names[ $i ] != '' ) :
			$new[ $i ]['delivery-company-name'] = sanitize_text_field( $delivery_company_names[ $i ] );
		endif;

		if ( in_array( $delivery_countries[ $i ], $options ) ) {
			$new[ $i ]['delivery-country'] = sanitize_text_field( $delivery_countries[ $i ] );
		} else {
			$new[ $i ]['delivery-country'] = '';
		}

		if ( $delivery_street_addresses_1[ $i ] != '' ) :
			$new[ $i ]['delivery-street-address-1'] = sanitize_text_field( $delivery_street_addresses_1[ $i ] );
		endif;

		if ( $delivery_street_addresses_2[ $i ] != '' ) :
			$new[ $i ]['delivery-street-address-2'] = sanitize_text_field( $delivery_street_addresses_2[ $i ] );
		endif;

		if ( $delivery_town_cities[ $i ] != '' ) :
			$new[ $i ]['delivery-town-city'] = sanitize_text_field( $delivery_town_cities[ $i ] );
		endif;

		if ( $delivery_counties[ $i ] != '' ) :
			$new[ $i ]['delivery-county'] = sanitize_text_field( $delivery_counties[ $i ] );
		endif;

		if ( $delivery_postcodes[ $i ] != '' ) :
			$new[ $i ]['delivery-postcode'] = sanitize_text_field( $delivery_postcodes[ $i ] );
		endif;

		if ( $delivery_phones[ $i ] != '' ) :
			$new[ $i ]['delivery-phone'] = sanitize_text_field( $delivery_phones[ $i ] );
		endif;

		if ( $delivery_emails[ $i ] != '' ) :
			$new[ $i ]['delivery-email'] = sanitize_text_field( $delivery_emails[ $i ] );
		endif;

		if ( $delivery_CSD_ID[ $i ] != '' ) :
			$new[ $i ]['delivery-CSD-ID'] = sanitize_text_field( $delivery_CSD_ID[ $i ] );
		endif;
	}


	if ( ! empty( $new ) && $new != $old ) {
		update_user_meta( $user_id, 'wpiai_delivery_addresses', $new );
	} elseif ( empty( $new ) && $old ) {
	    delete_user_meta( $user_id,'wpiai_delivery_addresses',$old);
	}
}

add_action( 'personal_options_update', 'wpiai_accounts_delivery_repeatable_meta_box_save' );
add_action( 'edit_user_profile_update', 'wpiai_accounts_delivery_repeatable_meta_box_save' );
