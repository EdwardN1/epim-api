<?php

function isPayOnAccountUser() {
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

	if ( isPayOnAccountUser() ) {

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

	$update = new WP_Query(
		array(
			'post_type'      => 'wpam_accounts',
			'posts_per_page' => -1,
			'meta_key'       => '_wpam_accounts_useraccount',
			'meta_value'     => $currentUser->ID,
		)
	);

	if ( $update->have_posts() ):
		while ( $update->have_posts() ) :$update->the_post();
			if ( isset( $_POST[ 'wpamvc_verify_' . get_the_ID() ] ) ) {
				if ( wp_verify_nonce( $_POST[ 'update_user_details_' . get_the_ID() ], 'wpamvc_verify_' . get_the_ID() ) ) {
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
						update_post_meta( get_the_ID(), '_wpam_accounts_email', $_POST[ 'wpam_email_' . get_the_ID() ] );
					}
				}
			}
		endwhile;
	endif;

	wp_reset_postdata();

	$loop = new WP_Query(
		array(
			'post_type'      => 'wpam_accounts',
			'posts_per_page' => -1,
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
                    <th class="woocommerce table my_account_orders">Delete</th>
                </tr>
				<?php
				while ( $loop->have_posts() ) : $loop->the_post();


					?>

                    <tr>


                        <td><?php wp_nonce_field( 'update_user_details_'. get_the_ID(), 'wpamvc_verify_' . get_the_ID() ); ?><input type="text" value="<?php echo get_the_title(); ?>" name="wpam_name_<?php echo get_the_ID(); ?>"/></td>
                        <td><input type="email" value="<?php echo get_post_meta( get_the_ID(), '_wpam_accounts_email', true ); ?>" name="wpam_email_<?php echo get_the_ID(); ?>"/></td>
                        <td><?php echo get_post_meta( get_the_ID(), '_wpam_accounts_account_enabled', true ); ?></td>
                        <td>?</td>

                    </tr>

				<?php endwhile;
				?>
            </table>
	        <input type="submit" value="Update" class="button"/>
        </form>
	<?php
	endif;
	wp_reset_postdata();

}