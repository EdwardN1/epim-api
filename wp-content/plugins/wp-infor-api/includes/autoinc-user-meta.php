<?php
add_action( 'show_user_profile', 'wpiai_user_profile_fields' );
add_action( 'edit_user_profile', 'wpiai_user_profile_fields' );
add_action( 'user_new_form', 'wpiai_user_profile_register_fields' );

function wpiai_user_profile_register_fields( $user ) { ?>
    <h3><?php _e( "Additional CSD Required Fields", "blank" ); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="wpiai_billing_company"><?php _e( "Company Name" ); ?></label></th>
            <td>
                <input type="text" name="wpiai_billing_company" id="wpiai_billing_company" value="" class="regular-text"/><br/>
                <span class="description"><?php _e( "Please enter the Company Name." ); ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="wpiai_billing_address_1"><?php _e( "Address" ); ?></label></th>
            <td>
                <input type="text" name="wpiai_billing_address_1" id="wpiai_billing_address_1" value="" class="regular-text"/><br/>
                <span class="description"><?php _e( "Please enter an Address." ); ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="wpiai_billing_address_2"><?php _e( "Address" ); ?></label></th>
            <td>
                <input type="text" name="wpiai_billing_address_2" id="wpiai_billing_address_2" value="" class="regular-text"/><br/>
                <span class="description"><?php _e( "Please enter an Address." ); ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="wpiai_billing_city"><?php _e( "City" ); ?></label></th>
            <td>
                <input type="text" name="wpiai_billing_city" id="wpiai_billing_city" value="" class="regular-text"/><br/>
                <span class="description"><?php _e( "Please enter a City." ); ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="wpiai_billing_state"><?php _e( "State" ); ?></label></th>
            <td>
                <input type="text" name="wpiai_billing_state" id="wpiai_billing_state" value="" class="regular-text"/><br/>
                <span class="description"><?php _e( "Please enter a State." ); ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="wpiai_billing_postcode"><?php _e( "Postcode" ); ?></label></th>
            <td>
                <input type="text" name="wpiai_billing_postcode" id="wpiai_billing_postcode" value="" class="regular-text"/><br/>
                <span class="description"><?php _e( "Please enter a Postcode." ); ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="wpiai_billing_phone"><?php _e( "Phone" ); ?></label></th>
            <td>
                <input type="text" name="wpiai_billing_phone" id="wpiai_billing_phone" value="" class="regular-text"/><br/>
                <span class="description"><?php _e( "Please enter a Phone Number." ); ?></span>
            </td>
        </tr>
    </table>
<?php }

function wpiai_user_profile_fields( $user ) { ?>
    <h3><?php _e( "Additional CSD Required Fields", "blank" ); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="CSD_ID"><?php _e( "CSD ID" ); ?></label></th>
            <td>
                <input type="text" name="CSD_ID" id="CSD_ID" value="<?php echo esc_attr( get_the_author_meta( 'CSD_ID', $user->ID ) ); ?>" class="regular-text"/><br/>
                <span class="description"><?php _e( "Please enter the CSD ID." ); ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="wpiai_termstype"><?php _e( "Terms Type" ); ?></label></th>
            <td>
                <input type="text" name="wpiai_termstype" id="wpiai_termstype" value="<?php echo esc_attr( get_the_author_meta( 'wpiai_termstype', $user->ID ) ); ?>" class="regular-text"/><br/>
                <span class="description"><?php _e( "Please enter the Terms Type." ); ?><br>
                <?php
                if ( wpiai_get_termstype_name( get_the_author_meta( 'wpiai_termstype', $user->ID ) ) ) {
	                _e( 'Terms Type Name: ' . wpiai_get_termstype_name( get_the_author_meta( 'wpiai_termstype', $user->ID ) ) );
                } else {
	                _e( 'Unknown Terms Type' );
                }
                ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="wpiai_custtype"><?php _e( "Customer Type" ); ?></label></th>
            <td>
                <input type="text" name="wpiai_custtype" id="wpiai_custtype" value="<?php echo esc_attr( get_the_author_meta( 'wpiai_custtype', $user->ID ) ); ?>" class="regular-text"/><br/>
                <span class="description"><?php _e( "Please enter the Customer Type." ); ?><br>
                <?php
                if ( wpiai_get_customertype_name( get_the_author_meta( 'wpiai_custtype', $user->ID ) ) ) {
	                _e( 'Customer Type Name: ' . wpiai_get_customertype_name( get_the_author_meta( 'wpiai_custtype', $user->ID ) ) );
                } else {
	                _e( 'Unknown Customer Type' );
                }
                ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="wpiai_whse"><?php _e( "Warehouse" ); ?></label></th>
            <td>
                <input type="text" name="wpiai_whse" id="wpiai_whse" value="<?php echo esc_attr( get_the_author_meta( 'wpiai_whse', $user->ID ) ); ?>" class="regular-text"/><br/>
                <span class="description"><?php _e( "Please enter Warehouse." ); ?><br>
                <?php
                if ( wpiai_get_whse_name( get_the_author_meta( 'wpiai_whse', $user->ID ) ) ) {
	                _e( 'Warehouse Name: ' . wpiai_get_whse_name( get_the_author_meta( 'wpiai_whse', $user->ID ) ) );
                } else {
	                _e( 'Unknown Warehouse' );
                }
                ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="wpiai_pricetype"><?php _e( "Price Type" ); ?></label></th>
            <td>
                <input type="text" name="wpiai_pricetype" id="wpiai_pricetype" value="<?php echo esc_attr( get_the_author_meta( 'wpiai_pricetype', $user->ID ) ); ?>" class="regular-text"/><br/>
                <span class="description"><?php _e( "Please enter a Price Type." ); ?><br>
                <?php
                if ( wpiai_get_pricetype_name( get_the_author_meta( 'wpiai_pricetype', $user->ID ) ) ) {
	                _e( 'Price Type Name: ' . wpiai_get_pricetype_name( get_the_author_meta( 'wpiai_pricetype', $user->ID ) ) );
                } else {
	                _e( 'Unknown Price Type' );
                }
                ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="wpiai_selltype"><?php _e( "Sell Type" ); ?></label></th>
            <td>
                <input type="text" name="wpiai_selltype" id="wpiai_selltype" value="<?php echo esc_attr( get_the_author_meta( 'wpiai_selltype', $user->ID ) ); ?>" class="regular-text"/><br/>
                <span class="description"><?php _e( "Please enter a Sell Type." ); ?><br>
                <?php
                if ( wpiai_get_selltype_name( get_the_author_meta( 'wpiai_selltype', $user->ID ) ) ) {
	                _e( 'Sell Type Name: ' . wpiai_get_selltype_name( get_the_author_meta( 'wpiai_selltype', $user->ID ) ) );
                } else {
	                _e( 'Unknown Sell Type' );
                }
                ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="wpiai_user4"><?php _e( "User 4 Field" ); ?></label></th>
            <td>
                <input type="text" name="wpiai_user4" id="wpiai_user4" value="<?php echo esc_attr( get_the_author_meta( 'wpiai_user4', $user->ID ) ); ?>" class="regular-text"/><br/>
                <span class="description"><?php _e( "Please enter a User 4." ); ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="wpiai_force_update"><?php _e( "Force Update Field" ); ?></label></th>
            <td>
                <input type="text" name="wpiai_force_update" id="wpiai_force_update" value="<?php echo esc_attr( get_the_author_meta( 'wpiai_force_update', $user->ID ) ); ?>" class="regular-text"/><br/>
                <span class="description"><?php _e( "Please enter a Force Update." ); ?></span>
            </td>
        </tr>
    </table>
<?php }

add_action( 'personal_options_update', 'save_wpiai_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_wpiai_user_profile_fields' );
add_action( 'user_register', 'register_wpiai_user_profile_fields' );

function register_wpiai_user_profile_fields( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}
	$user = get_userdata( $user_id );
	if ( $user ) {
		$roles = $user->roles;
		if ( in_array( 'customer', $roles ) ) {
		    //error_log('Added a customer');
			update_user_meta( $user_id, 'billing_company', $_POST['wpiai_billing_company'] );
			update_user_meta( $user_id, 'billing_address_1', $_POST['wpiai_billing_address_1'] );
			update_user_meta( $user_id, 'billing_address_2', $_POST['wpiai_billing_address_2'] );
			update_user_meta( $user_id, 'billing_city', $_POST['wpiai_billing_city'] );
			update_user_meta( $user_id, 'billing_state', $_POST['wpiai_billing_state'] );
			update_user_meta( $user_id, 'billing_postcode', $_POST['wpiai_billing_postcode'] );
			update_user_meta( $user_id, 'billing_phone', $_POST['wpiai_billing_phone'] );
			update_user_meta( $user_id, 'billing_country', 'GB' );
		}
	}
}

function save_wpiai_user_profile_fields( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}
	update_user_meta( $user_id, 'CSD_ID', $_POST['CSD_ID'] );
	update_user_meta( $user_id, 'wpiai_termstype', $_POST['wpiai_termstype'] );
	update_user_meta( $user_id, 'wpiai_custtype', $_POST['wpiai_custtype'] );
	update_user_meta( $user_id, 'wpiai_whse', $_POST['wpiai_whse'] );
	update_user_meta( $user_id, 'wpiai_pricetype', $_POST['wpiai_pricetype'] );
	update_user_meta( $user_id, 'wpiai_selltype', $_POST['wpiai_selltype'] );
	update_user_meta( $user_id, 'wpiai_user4', $_POST['wpiai_user4'] );
	update_user_meta( $user_id, 'wpiai_force_update', $_POST['wpiai_force_update'] );
}