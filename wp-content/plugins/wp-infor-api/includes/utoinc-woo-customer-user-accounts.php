v<?php
/**
*======================================= Customer Users Repeater =====================================
 */

add_action( 'show_user_profile', 'wpiai_contacts_repeater_meta_box' );
add_action( 'edit_user_profile', 'wpiai_contacts_repeater_meta_box' );



function wpiai_contacts_repeater_meta_box($user) {
	global $post;

	$repeatable_fields = get_the_author_meta( 'wpiai_contacts', $user->ID );

	?>
	<script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('#contact-add-row').on('click', function () {
                var row = $('.contact-blank-row .repeater-row').clone(true);
                $('#contact-repeatable-fieldset-one').append(row);
                $('#contact-repeatable-fieldset-one *').prop("disabled", false);
                return false;
            });

            $('.remove-row').on('click', function () {
                $(this).parents('tr').remove();
                return false;
            });
        });
	</script>

	<h3><?php _e("Contacts", "blank"); ?></h3>

	<table id="contact-repeatable-fieldset-one" width="100%">

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
												<label for="contact_CSD_ID[]">CSD ID:
													<input type="text" class="widefat" name="contact_CSD_ID[]" value="<?php if ( $field['contact_CSD_ID'] != '' ) {
														echo esc_attr( $field['contact_CSD_ID'] );
													} ?>"/>
												</label>
											</td>
											<td>
												<label for="contact_name[]">Name:
													<input type="text" class="widefat" name="contact_name[]" value="<?php if ( $field['contact_name'] != '' ) {
														echo esc_attr( $field['contact_name'] );
													} ?>"/>
												</label>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<label for="contact_address_1[]">Address 1:
										<input type="text" class="widefat" name="contact_address_1[]" value="<?php if ( $field['contact_address_1'] != '' ) {
											echo esc_attr( $field['contact_address_1'] );
										} ?>"/>
									</label>
								</td>
							</tr>
                            <tr>
                                <td>
                                    <label for="contact_address_2[]">Address 2:
                                        <input type="text" class="widefat" name="contact_address_2[]" value="<?php if ( $field['contact_address_2'] != '' ) {
											echo esc_attr( $field['contact_address_2'] );
										} ?>"/>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="contact_address_3[]">Address 3:
                                        <input type="text" class="widefat" name="contact_address_3[]" value="<?php if ( $field['contact_address_3'] != '' ) {
											echo esc_attr( $field['contact_address_3'] );
										} ?>"/>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="contact_address_4[]">Address 4:
                                        <input type="text" class="widefat" name="contact_address_4[]" value="<?php if ( $field['contact_address_4'] != '' ) {
											echo esc_attr( $field['contact_address_4'] );
										} ?>"/>
                                    </label>
                                </td>
                            </tr>
							<tr>
								<td>
									<label for="contact_postcode[]">Postcode:
										<input type="text" class="widefat" name="contact_postcode[]" value="<?php if ( $field['contact_postcode'] != '' ) {
											echo esc_attr( $field['contact_postcode'] );
										} ?>"/>
									</label>
								</td>
							</tr>
							<tr>
								<td>
									<label for="contact_phone[]">Phone:
										<input type="text" class="widefat" name="contact_phone[]" value="<?php if ( $field['contact_phone'] != '' ) {
											echo esc_attr( $field['contact_phone'] );
										} ?>"/>
									</label>
								</td>
							</tr>
							<tr>
								<td>
									<label for="contact_email[]">Email address:
										<input type="email" class="widefat" name="contact_email[]" value="<?php if ( $field['contact_email'] != '' ) {
											echo esc_attr( $field['contact_email'] );
										} ?>"/>
									</label>
								</td>
                                <td>
                                    <label for="contact_type[]">Contact Type:
                                        <input type="text" class="widefat" name="contact_type[]" value="<?php if ( $field['contact_type'] != '' ) {
											echo esc_attr( $field['contact_type'] );
										} ?>"/>
                                    </label>
                                </td>
							</tr>

							<tr>
								<td><a class="button remove-row" href="#">Remove Contact</a></td>
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
	<table class="contact-blank-row" style="display: none;">
		<tr class="repeater-row">
			<td>
				<table>
					<tr>
						<td>
							<table>
								<tr>
									<td>
										<label for="contact_CSD_ID[]">CSD ID:
											<input disabled type="text" class="widefat" name="contact_CSD_ID[]" value=""/>
										</label>
									</td>
									<td>
										<label for="contact_name[]">Name:
											<input disabled type="text" class="widefat" name="contact_name[]" value=""/>
										</label>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<label for="contact_address_1[]">Address 1:
								<input disabled type="text" class="widefat" name="contact_address_1[]" value=""/>
							</label>
						</td>
					</tr>
                    <tr>
                        <td>
                            <label for="contact_address_2[]">Address 2:
                                <input disabled type="text" class="widefat" name="contact_address_2[]" value=""/>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="contact_address_3[]">Address 3:
                                <input disabled type="text" class="widefat" name="contact_address_3[]" value=""/>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="contact_address_4[]">Address 4:
                                <input disabled type="text" class="widefat" name="contact_address_4[]" value=""/>
                            </label>
                        </td>
                    </tr>
					<tr>
						<td>
							<label for="contact_postcode[]">Postcode:
								<input disabled type="text" class="widefat" name="contact_postcode[]" value=""/>
							</label>
						</td>
					</tr>
					<tr>
						<td>
							<label for="contact_phone[]">Phone:
								<input disabled type="text" class="widefat" name="contact_phone[]" value=""/>
							</label>
						</td>
					</tr>
					<tr>
						<td>
							<label for="contact_mail[]">Email address:
								<input disabled type="email" class="widefat" name="contact_email[]" value=""/>
							</label>
						</td>
					</tr>
                    <tr>
                        <td>
                            <label for="contact_type[]">Contact Type:
                                <input disabled type="text" class="widefat" name="contact_type[]" value=""/>
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

	<p><a id="contact-add-row" class="button" href="#">Add Contact</a></p>
	<?php
}

function wpiai_contacts_repeatable_meta_box_save( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}

	$old     = get_the_author_meta( 'wpiai_contacts', $user_id );
	$new     = array();


	$contact_CSD_ID      = isset( $_POST['contact_CSD_ID'] ) ? (array) $_POST['contact_CSD_ID'] : array();
	$contact_name      = isset( $_POST['contact_name'] ) ? (array) $_POST['contact_name'] : array();
	$contact_address_1      = isset( $_POST['contact_address_1'] ) ? (array) $_POST['contact_address_1'] : array();
	$contact_address_2      = isset( $_POST['contact_address_2'] ) ? (array) $_POST['contact_address_2'] : array();
	$contact_address_3      = isset( $_POST['contact_address_3'] ) ? (array) $_POST['contact_address_3'] : array();
	$contact_address_4      = isset( $_POST['contact_address_4'] ) ? (array) $_POST['contact_address_4'] : array();
	$contact_postcode      = isset( $_POST['contact_postcode'] ) ? (array) $_POST['contact_postcode'] : array();
	$contact_email      = isset( $_POST['contact_email'] ) ? (array) $_POST['contact_email'] : array();
	$contact_phone      = isset( $_POST['contact_phone'] ) ? (array) $_POST['contact_phone'] : array();
	$contact_type      = isset( $_POST['contact_type'] ) ? (array) $_POST['contact_type'] : array();

	$count = count( $contact_email );

	for ( $i = 0; $i < $count; $i ++ ) {

		if ( $contact_CSD_ID[ $i ] != '' ) :
			$new[ $i ]['contact_CSD_ID'] = sanitize_text_field( $contact_CSD_ID[ $i ] );
		endif;

		if ( $contact_name[ $i ] != '' ) :
			$new[ $i ]['contact_name'] = sanitize_text_field( $contact_name[ $i ] );
		endif;

		if ( $contact_address_1[ $i ] != '' ) :
			$new[ $i ]['contact_address_1'] = sanitize_text_field( $contact_address_1[ $i ] );
		endif;

		if ( $contact_address_2[ $i ] != '' ) :
			$new[ $i ]['contact_address_2'] = sanitize_text_field( $contact_address_2[ $i ] );
		endif;

		if ( $contact_address_3[ $i ] != '' ) :
			$new[ $i ]['contact_address_3'] = sanitize_text_field( $contact_address_3[ $i ] );
		endif;

		if ( $contact_address_4[ $i ] != '' ) :
			$new[ $i ]['contact_address_4'] = sanitize_text_field( $contact_address_4[ $i ] );
		endif;

		if ( $contact_email[ $i ] != '' ) :
			$new[ $i ]['contact_email'] = sanitize_text_field( $contact_email[ $i ] );
		endif;

		if ( $contact_postcode[ $i ] != '' ) :
			$new[ $i ]['contact_postcode'] = sanitize_text_field( $contact_postcode[ $i ] );
		endif;

		if ( $contact_phone[ $i ] != '' ) :
			$new[ $i ]['contact_phone'] = sanitize_text_field( $contact_phone[ $i ] );
		endif;

		if ( $contact_type[ $i ] != '' ) :
			$new[ $i ]['contact_type'] = sanitize_text_field( $contact_type[ $i ] );
		endif;

	}


	if ( ! empty( $new ) && $new != $old ) {
		update_user_meta( $user_id, 'wpiai_contacts', $new );
	} elseif ( empty( $new ) && $old ) {
		delete_user_meta( $user_id,'wpiai_contacts',$old);
	}
}

add_action( 'personal_options_update', 'wpiai_contacts_repeatable_meta_box_save' );
add_action( 'edit_user_profile_update', 'wpiai_contacts_repeatable_meta_box_save' );
