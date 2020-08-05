<?php
add_action( 'show_user_profile', 'wpoa_user_profile_fields' );
add_action( 'edit_user_profile', 'wpoa_user_profile_fields' );

function wpoa_user_profile_fields( $user ) { ?>
    <h3><?php _e("Customer account information", "blank"); ?></h3>

    <table class="form-table">
        <tr>
            <th><label for="address"><?php _e("Credit Limit"); ?></label></th>
            <td>
                <input type="text" name="credit_limit" id="credit_limit" value="<?php echo esc_attr( get_the_author_meta( 'credit_limit', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description"><?php _e("Please enter the credit limit for this customer."); ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="Account Balance"><?php _e("Account Balance"); ?></label></th>
            <td>
                <input type="text" name="account_balance" id="account_balance" value="<?php echo esc_attr( get_the_author_meta( 'account_balance', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description"><?php _e("Please enter an account balance."); ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="CSD_ID"><?php _e("CSD ID"); ?></label></th>
            <td>
                <input type="text" name="CSD_ID" id="CSD_ID" value="<?php echo esc_attr( get_the_author_meta( 'CSD_ID', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description"><?php _e("Please enter the CSD ID."); ?></span>
            </td>
        </tr>
    </table>
<?php }

add_action( 'personal_options_update', 'save_wpoa_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_wpoa_user_profile_fields' );

function save_wpoa_user_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }
    update_user_meta( $user_id, 'credit_limit', $_POST['credit_limit'] );
    update_user_meta( $user_id, 'account_balance', $_POST['account_balance'] );
	update_user_meta( $user_id, 'CSD_ID', $_POST['CSD_ID'] );
}