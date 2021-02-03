<?php
if ( ! defined( 'ABSPATH' ) )
    exit;
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
						<table class="form-table">
                            <tr>
                                <th>
                                    <input type="hidden" name="contact_CONTACT_ID[]" value="<?php if ( $field['contact_CONTACT_ID'] != '' ) {
                                        echo esc_attr( $field['contact_CONTACT_ID'] );
                                    } else {echo uniqid();}?>">
                                    <label for="contact_CSD_ID[]">CSD contact ID:</label>
                                </th>
                                <td>
                                    <input type="text" class="regular-text" name="contact_CSD_ID[]" value="<?php if ( $field['contact_CSD_ID'] != '' ) {
                                        echo esc_attr( $field['contact_CSD_ID'] );
                                    } ?>" />
                                </td>

                            </tr>
                            <tr>
                                <th>
                                    <label for="contact_status_code[]">Status Code:</label>
                                </th>
                                <td>
                                    <input type="text" class="regular-text" name="contact_status_code[]" value="<?php if ( $field['contact_status_code'] != '' ) {
                                        echo esc_attr( $field['contact_status_code'] );
                                    } ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="contact_first_name[]">First Name:</label>
                                </th>
                                <td>
                                    <input type="text" class="regular-text" name="contact_first_name[]" value="<?php if ( $field['contact_first_name'] != '' ) {
                                        echo esc_attr( $field['contact_first_name'] );
                                    } ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="contact_last_name[]">Last Name:</label>
                                </th>
                                <td>
                                    <input type="text" class="regular-text" name="contact_last_name[]" value="<?php if ( $field['contact_last_name'] != '' ) {
                                        echo esc_attr( $field['contact_last_name'] );
                                    } ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="contact_job_title[]">Job Title:</label>
                                </th>
                                <td>
                                    <input type="text" class="regular-text" name="contact_job_title[]" value="<?php if ( $field['contact_job_title'] != '' ) {
                                        echo esc_attr( $field['contact_job_title'] );
                                    } ?>"/>
                                </td>
                            </tr>
							<tr>
								<th>
									<label for="contact_address_1[]">Address 1:</label>
								</th>
                                <td>
                                    <input type="text" class="regular-text" name="contact_address_1[]" value="<?php if ( $field['contact_address_1'] != '' ) {
                                        echo esc_attr( $field['contact_address_1'] );
                                    } ?>"/>
                                </td>
							</tr>
                            <tr>
                                <th>
                                    <label for="contact_address_2[]">Address 2:</label>
                                </th>
                                <td>
                                    <input type="text" class="regular-text" name="contact_address_2[]" value="<?php if ( $field['contact_address_2'] != '' ) {
                                        echo esc_attr( $field['contact_address_2'] );
                                    } ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="contact_address_3[]">Address 3:</label>
                                </th>
                                <td>
                                    <input type="text" class="regular-text" name="contact_address_3[]" value="<?php if ( $field['contact_address_3'] != '' ) {
                                        echo esc_attr( $field['contact_address_3'] );
                                    } ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="contact_address_4[]">Address 4:</label>
                                </th>
                                <td>
                                    <input type="text" class="regular-text" name="contact_address_4[]" value="<?php if ( $field['contact_address_4'] != '' ) {
                                        echo esc_attr( $field['contact_address_4'] );
                                    } ?>"/>
                                </td>
                            </tr>
							<tr>
								<th>
									<label for="contact_postcode[]">Postcode:</label>
								</th>
                                <td>
                                    <input type="text" class="regular-text" name="contact_postcode[]" value="<?php if ( $field['contact_postcode'] != '' ) {
                                        echo esc_attr( $field['contact_postcode'] );
                                    } ?>"/>
                                </td>
							</tr>
							<tr>
								<th>
									<label for="contact_phone[]">Phone:</label>
								</th>
                                <td>
                                    <input type="text" class="regular-text" name="contact_phone[]" value="<?php if ( $field['contact_phone'] != '' ) {
                                        echo esc_attr( $field['contact_phone'] );
                                    } ?>"/>
                                </td>
							</tr>
							<tr>
								<th>
									<label for="contact_email[]">Email address:</label>
								</th>
                                <td>
                                    <input type="email" class="regular-text" name="contact_email[]" value="<?php if ( $field['contact_email'] != '' ) {
                                        echo esc_attr( $field['contact_email'] );
                                    } ?>"/>
                                </td>
							</tr>
                            <tr>
                                <th>
                                    <label for="contact_type[]">Contact Type:</label>
                                </th>
                                <td>
                                    <input type="text" class="regular-text" name="contact_type[]" value="<?php if ( $field['contact_type'] != '' ) {
                                        echo esc_attr( $field['contact_type'] );
                                    } ?>"/>
                                </td>
                            </tr>
							<tr>
								<td colspan="2"><br><a class="button remove-row" href="#">Remove Contact</a></td>
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
	<table class="contact-blank-row" style="display: none;">
		<tr class="repeater-row">
			<td>
				<table class="form-table">
					<tr>
                        <th>
                            <label for="contact_CSD_ID[]">CSD contact ID:</label>
                        </th>
                        <td>
                            <input disabled type="text" class="regular-text" name="contact_CSD_ID[]" value=""/>
                        </td>
					</tr>
                    <tr>
                        <th>
                            <label for="contact_CSD_ID[]">Status Code:</label>
                        </th>
                        <td>
                            <input disabled type="text" class="regular-text" name="contact_status_code[]" value=""/>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="contact_first_name[]">First Name:</label>
                        </th>
                        <td>
                            <input disabled type="text" class="regular-text" name="contact_first_name[]" value=""/>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="contact_last_name[]">Last Name:</label>
                        </th>
                        <td>
                            <input disabled type="text" class="regular-text" name="contact_last_name[]" value=""/>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="contact_job_title[]">Job Title:</label>
                        </th>
                        <td>
                            <input disabled type="text" class="regular-text" name="contact_job_title[]" value=""/>
                        </td>
                    </tr>
					<tr>
						<th>
							<label for="contact_address_1[]">Address 1:</label>
						</th>
                        <td>
                            <input disabled type="text" class="regular-text" name="contact_address_1[]" value=""/>
                        </td>
					</tr>
                    <tr>
                        <th>
                            <label for="contact_address_2[]">Address 2:</label>
                        </th>
                        <td>
                            <input disabled type="text" class="regular-text" name="contact_address_2[]" value=""/>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="contact_address_3[]">Address 3:</label>
                        </th>
                        <td>
                            <input disabled type="text" class="regular-text" name="contact_address_3[]" value=""/>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="contact_address_4[]">Address 4:</label>
                        </th>
                        <td>
                            <input disabled type="text" class="regular-text" name="contact_address_4[]" value=""/>
                        </td>
                    </tr>
					<tr>
						<th>
							<label for="contact_postcode[]">Postcode:</label>
						</th>
                        <td>
                            <input disabled type="text" class="regular-text" name="contact_postcode[]" value=""/>
                        </td>
					</tr>
					<tr>
						<th>
							<label for="contact_phone[]">Phone:</label>
						</th>
                        <td>
                            <input disabled type="text" class="regular-text" name="contact_phone[]" value=""/>
                        </td>
					</tr>
					<tr>
						<th>
							<label for="contact_mail[]">Email address:</label>
						</th>
                        <td>
                            <input disabled type="email" class="regular-text" name="contact_email[]" value=""/>
                        </td>
					</tr>
                    <tr>
                        <th>
                            <label for="contact_type[]">Contact Type:</label>
                        </th>
                        <td>
                            <input disabled type="text" class="regular-text" name="contact_type[]" value=""/>
                        </td>
                    </tr>

					<tr>
						<td colspan="2"><a class="button remove-row" href="#">Cancel New Contact</a></td>
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
    $contact_status_code      = isset( $_POST['contact_status_code'] ) ? (array) $_POST['contact_status_code'] : array();
	$contact_first_name      = isset( $_POST['contact_first_name'] ) ? (array) $_POST['contact_first_name'] : array();
    $contact_last_name      = isset( $_POST['contact_last_name'] ) ? (array) $_POST['contact_last_name'] : array();
    $contact_job_title      = isset( $_POST['contact_job_title'] ) ? (array) $_POST['contact_job_title'] : array();
	$contact_address_1      = isset( $_POST['contact_address_1'] ) ? (array) $_POST['contact_address_1'] : array();
	$contact_address_2      = isset( $_POST['contact_address_2'] ) ? (array) $_POST['contact_address_2'] : array();
	$contact_address_3      = isset( $_POST['contact_address_3'] ) ? (array) $_POST['contact_address_3'] : array();
	$contact_address_4      = isset( $_POST['contact_address_4'] ) ? (array) $_POST['contact_address_4'] : array();
	$contact_postcode      = isset( $_POST['contact_postcode'] ) ? (array) $_POST['contact_postcode'] : array();
	$contact_email      = isset( $_POST['contact_email'] ) ? (array) $_POST['contact_email'] : array();
	$contact_phone      = isset( $_POST['contact_phone'] ) ? (array) $_POST['contact_phone'] : array();
	$contact_type      = isset( $_POST['contact_type'] ) ? (array) $_POST['contact_type'] : array();
	$contact_CONTACT_ID = isset( $_POST['contact_CONTACT_ID'] ) ? (array) $_POST['contact_CONTACT_ID'] : array();

	$count = count( $contact_email );

	for ( $i = 0; $i < $count; $i ++ ) {

		if ( $contact_CSD_ID[ $i ] != '' ) :
			$new[ $i ]['contact_CSD_ID'] = sanitize_text_field( $contact_CSD_ID[ $i ] );
		endif;

        if ( $contact_status_code[ $i ] != '' ) :
            $new[ $i ]['contact_status_code'] = sanitize_text_field( $contact_status_code[ $i ] );
        endif;

		if ( $contact_first_name[ $i ] != '' ) :
			$new[ $i ]['contact_first_name'] = sanitize_text_field( $contact_first_name[ $i ] );
		endif;

        if ( $contact_last_name[ $i ] != '' ) :
            $new[ $i ]['contact_last_name'] = sanitize_text_field( $contact_last_name[ $i ] );
        endif;

        if ( $contact_job_title[ $i ] != '' ) :
            $new[ $i ]['contact_job_title'] = sanitize_text_field( $contact_job_title[ $i ] );
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

		if ( $contact_CONTACT_ID[ $i ] != '' ) :
			$new[ $i ]['contact_CONTACT_ID'] = sanitize_text_field( $contact_CONTACT_ID[ $i ] );
		endif;

		/*if ( $contact_CONTACT_ID[ $i ] == '' ) :
			$new[ $i ]['contact_CONTACT_ID'] = uniqid();
		endif;*/

	}


	if ( ! empty( $new ) && $new != $old ) {
		update_user_meta( $user_id, 'wpiai_contacts', $new );
	} elseif ( empty( $new ) && $old ) {
		delete_user_meta( $user_id,'wpiai_contacts',$old);
	}
}

add_action( 'personal_options_update', 'wpiai_contacts_repeatable_meta_box_save' );
add_action( 'edit_user_profile_update', 'wpiai_contacts_repeatable_meta_box_save' );
