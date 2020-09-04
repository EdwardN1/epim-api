<?php

function wpamvc_isPayOnAccountUser() {
	$args        = array(
		'role'    => 'account_customer',
		'orderby' => 'user_nicename',
		'order'   => 'ASC'
	);
	$users       = get_users( $args );
	$currentUser = wp_get_current_user();
	foreach ( $users as $user ) {
		if ( $currentUser->ID == $user->ID ) {
			return true;
		}
	}

	return false;
}

add_filter( 'woocommerce_account_menu_items', 'wpamvc_user_accounts_link' );
function wpamvc_user_accounts_link( $menu_links ) {

	if ( wpamvc_isPayOnAccountUser() ) {

		$menu_links = array_slice( $menu_links, 0, 5, true )
		              + array( 'sub-user-accounts' => 'User Accounts' )
		              + array_slice( $menu_links, 5, null, true );
	}

	return $menu_links;


}

add_action( 'init', 'wpamvc_user_accounts_link_add_endpoint' );
function wpamvc_user_accounts_link_add_endpoint() {

	// WP_Rewrite is my Achilles' heel, so please do not ask me for detailed explanation
	add_rewrite_endpoint( 'sub-user-accounts', EP_PAGES );

}

add_action( 'woocommerce_account_sub-user-accounts_endpoint', 'wpamvc_my_account_sub_user_accounts_endpoint_content' );
function wpamvc_my_account_sub_user_accounts_endpoint_content() {


	$currentUser = wp_get_current_user();
	$addErrors   = '';

	if ( wp_verify_nonce( sanitize_text_field( $_POST['wpamvc_verify_new'] ), 'add_user_details') ) {
		if ( wpamvc_isPayOnAccountUser() ) {
			$newAccount = new Account();
			if ( isset( $_POST['wpam_name_new'] ) ) {
				$accname = sanitize_text_field( $_POST['wpam_name_new'] );
				if ( isset( $_POST['wpam_email_new'] ) ) {
					$accemail = sanitize_email( $_POST['wpam_email_new'] );
					if ( isset( $_POST['wpam_password_new'] ) ) {
						$accpassword = sanitize_text_field( $_POST['wpam_password_new'] );
						try {
							$newPostID = $newAccount->addAccount( $accname, $accpassword, $accemail );
						} catch ( exception $e ) {
							$addErrors = '<div style="color: red;">' . $e->getMessage() . '</div>';
						}
						if(!is_wp_error($newPostID)) {
							update_post_meta( $newPostID, '_wpam_accounts_useraccount', $currentUser->ID );
                        } else {
							$error_code = array_key_first( $newPostID->errors );
							$error_message = $newPostID->errors[$error_code][0];
							$addErrors = '<div style="color: red;">' . $error_message . '</div>';
                        }
					} else {
						$addErrors = '<div style="color: red;">Password is Missing. Please enter a Password.</div>';
					}
				} else {
					$addErrors = '<div style="color: red;">Email is Missing. Please enter an Email Address.</div>';
				}
			} else {
				$addErrors = '<div style="color: red;">Name is Missing. Please enter a Name.</div>';
			}
		}
	} else {
		$update = new WP_Query(
			array(
				'post_type'      => 'wpam_accounts',
				'posts_per_page' => - 1,
				'meta_key'       => '_wpam_accounts_useraccount',
				'meta_value'     => $currentUser->ID,
			)
		);

		if ( $update->have_posts() ):
			while ( $update->have_posts() ) :$update->the_post();
				if ( isset( $_POST[ 'wpamvc_verify_' . get_the_ID() ] ) ) {
					if ( wp_verify_nonce( sanitize_text_field( $_POST[ 'wpamvc_verify_' . get_the_ID() ] ), 'update_user_details_' . get_the_ID() ) ) {
						$postArray = array(
							'ID'         => get_the_ID(),
							'post_title' => $_POST[ 'wpam_name_' . get_the_ID() ],
						);
						wp_update_post( $postArray, true );
						if ( is_wp_error( get_the_ID() ) ) {
							$errors = get_the_ID()->get_error_messages();
							foreach ( $errors as $error ) {
								error_log( $error );
							}
						} else {
							update_post_meta( get_the_ID(), '_wpam_accounts_email', sanitize_text_field( $_POST[ 'wpam_email_' . get_the_ID() ] ) );
							if ( isset( $_POST[ 'wpam_enabled_' . get_the_ID() ] ) ) {
								update_post_meta( get_the_ID(), '_wpam_accounts_account_enabled', 'yes' );
							} else {
								update_post_meta( get_the_ID(), '_wpam_accounts_account_enabled', 'no' );
							}
						}
					}
				}
			endwhile;
		endif;

		wp_reset_postdata();
	}


	$loop = new WP_Query(
		array(
			'post_type'      => 'wpam_accounts',
			'posts_per_page' => - 1,
			'meta_key'       => '_wpam_accounts_useraccount',
			'meta_value'     => $currentUser->ID,
		)
	);
	if ( $loop->have_posts() ):
		?>
        <form action="" method="post">
            <table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
                <tr>
                    <th class="woocommerce table my_account_orders">Name</th>
                    <th class="woocommerce table my_account_orders">Email Address</th>
                    <th class="woocommerce table my_account_orders">Account Enabled</th>
                </tr>
				<?php
				while ( $loop->have_posts() ) : $loop->the_post();


					?>

                    <tr>
						<?php
						$account_enabled = get_post_meta( get_the_ID(), '_wpam_accounts_account_enabled', true );
						$checked         = '';
						if ( $account_enabled == 'yes' ) {
							$checked = ' checked';
						}
						?>

                        <td><?php wp_nonce_field( 'update_user_details_' . get_the_ID(), 'wpamvc_verify_' . get_the_ID() ); ?><input type="text" value="<?php echo get_the_title(); ?>"
                                                                                                                                     name="wpam_name_<?php echo get_the_ID(); ?>"/></td>
                        <td><input type="email" value="<?php echo get_post_meta( get_the_ID(), '_wpam_accounts_email', true ); ?>" name="wpam_email_<?php echo get_the_ID(); ?>"/></td>
                        <td><input type="checkbox" name="wpam_enabled_<?php echo get_the_ID(); ?>" value="yes" <?php echo $checked ?>></td>


                    </tr>

				<?php endwhile;
				?>
            </table>
            <input type="submit" value="Update" class="button"/>
        </form>
        <hr>
		<?php
		if ( $addErrors != '' ) {
            echo $addErrors;
		}
		?>
        <form action="" method="post">
			<?php wp_nonce_field( 'add_user_details', 'wpamvc_verify_new' ); ?>
            <table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
                <tr>
                    <th class="woocommerce table my_account_orders">Name</th>
                    <th class="woocommerce table my_account_orders">Email Address</th>
                    <th class="woocommerce table my_account_orders">Password</th>
                </tr>
                <tr>
                    <td><input type="text" value="<?php if ( isset( $_POST['wpam_name_new'] ) ) {
							echo sanitize_text_field( $_POST['wpam_name_new'] );
						} ?>" name="wpam_name_new"/></td>
                    <td><input type="email" value="<?php if ( isset( $_POST['wpam_name_new'] ) ) {
							echo sanitize_email( $_POST['wpam_email_new'] );
						} ?>" name="wpam_email_new"/></td>
                    <td><input type="password" name="wpam_password_new"></td>
                </tr>
            </table>
            <input type="submit" value="Add" class="button"/>
        </form>
	<?php
	endif;
	wp_reset_postdata();

}