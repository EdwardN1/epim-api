<?php
// Add the custom field "favorite_color"
add_action( 'woocommerce_edit_account_form_start', 'add_favorite_color_to_edit_account_form' );
function add_favorite_color_to_edit_account_form() {
	$user = wp_get_current_user();
	$account_customer = in_array( 'account_customer', $user->roles );
	if($account_customer) {
		$id = $user->ID;
		$account_balance = get_user_meta($id,'account_balance',true);
		$credit_limit = get_user_meta($id,'credit_limit',true);
		?>
		<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
			Credit Limit: <?php echo $credit_limit; ?>
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
			Account Balance: <?php echo $account_balance;?>
		</p>
		<?php
	}

}
