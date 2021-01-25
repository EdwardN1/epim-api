<?php
add_action( 'show_user_profile', 'wpiai_user_profile_fields' );
add_action( 'edit_user_profile', 'wpiai_user_profile_fields' );
add_action( 'user_new_form', 'wpiai_user_profile_fields' );

function wpiai_user_profile_fields( $user ) { ?>
	<h3><?php _e("Additional CSD Required Fields", "blank"); ?></h3>
	<table class="form-table">
		<tr>
			<th><label for="CSD_ID"><?php _e("CSD ID"); ?></label></th>
			<td>
				<input type="text" name="CSD_ID" id="CSD_ID" value="<?php echo esc_attr( get_the_author_meta( 'CSD_ID', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e("Please enter the CSD ID."); ?></span>
			</td>
		</tr>
        <tr>
            <th><label for="wpiai_termstype"><?php _e("Terms Type"); ?></label></th>
            <td>
                <input type="text" name="wpiai_termstype" id="wpiai_termstype" value="<?php echo esc_attr( get_the_author_meta( 'wpiai_termstype', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description"><?php _e("Please enter the Terms Type."); ?><br>
                <?php
                if(wpiai_get_termstype_name(get_the_author_meta( 'wpiai_termstype', $user->ID ))) {
                    _e('Terms Type Name: '.wpiai_get_termstype_name(get_the_author_meta( 'wpiai_termstype', $user->ID )));
                } else {
                    _e( 'Unknown Terms Type');
                }
                    ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="wpiai_custtype"><?php _e("Customer Type"); ?></label></th>
            <td>
                <input type="text" name="wpiai_custtype" id="wpiai_custtype" value="<?php echo esc_attr( get_the_author_meta( 'wpiai_custtype', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description"><?php _e("Please enter the Customer Type."); ?><br>
                <?php
                if(wpiai_get_customertype_name(get_the_author_meta( 'wpiai_custtype', $user->ID ))) {
	                _e('Customer Type Name: '.wpiai_get_customertype_name(get_the_author_meta( 'wpiai_custtype', $user->ID )));
                } else {
	                _e( 'Unknown Customer Type');
                }
                ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="wpiai_whse"><?php _e("Warehouse"); ?></label></th>
            <td>
                <input type="text" name="wpiai_whse" id="wpiai_whse" value="<?php echo esc_attr( get_the_author_meta( 'wpiai_whse', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description"><?php _e("Please enter Warehouse."); ?><br>
                <?php
                if(wpiai_get_whse_name(get_the_author_meta( 'wpiai_whse', $user->ID ))) {
	                _e('Warehouse Name: '.wpiai_get_whse_name(get_the_author_meta( 'wpiai_whse', $user->ID )));
                } else {
	                _e( 'Unknown Warehouse');
                }
                ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="wpiai_pricetype"><?php _e("Price Type"); ?></label></th>
            <td>
                <input type="text" name="wpiai_pricetype" id="wpiai_pricetype" value="<?php echo esc_attr( get_the_author_meta( 'wpiai_pricetype', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description"><?php _e("Please enter a Price Type."); ?><br>
                <?php
                if(wpiai_get_pricetype_name(get_the_author_meta( 'wpiai_pricetype', $user->ID ))) {
	                _e('Price Type Name: '.wpiai_get_pricetype_name(get_the_author_meta( 'wpiai_pricetype', $user->ID )));
                } else {
	                _e( 'Unknown Price Type');
                }
                ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="wpiai_selltype"><?php _e("Sell Type"); ?></label></th>
            <td>
                <input type="text" name="wpiai_selltype" id="wpiai_selltype" value="<?php echo esc_attr( get_the_author_meta( 'wpiai_selltype', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description"><?php _e("Please enter a Sell Type."); ?><br>
                <?php
                if(wpiai_get_selltype_name(get_the_author_meta( 'wpiai_selltype', $user->ID ))) {
	                _e('Sell Type Name: '.wpiai_get_selltype_name(get_the_author_meta( 'wpiai_selltype', $user->ID )));
                } else {
	                _e( 'Unknown Sell Type');
                }
                ?></span>
            </td>
        </tr>
	</table>
<?php }

add_action( 'personal_options_update', 'save_wpiai_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_wpiai_user_profile_fields' );
add_action( 'user_register', 'save_wpiai_user_profile_fields' );

function save_wpiai_user_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}
	update_user_meta( $user_id, 'CSD_ID', $_POST['CSD_ID'] );
	update_user_meta( $user_id, 'wpiai_termstype', $_POST['wpiai_termstype'] );
	update_user_meta( $user_id, 'wpiai_custtype', $_POST['wpiai_custtype'] );
	update_user_meta( $user_id, 'wpiai_whse', $_POST['wpiai_whse'] );
	update_user_meta( $user_id, 'wpiai_pricetype', $_POST['wpiai_pricetype'] );
	update_user_meta( $user_id, 'wpiai_selltype', $_POST['wpiai_selltype'] );
}