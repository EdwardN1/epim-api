<?php

function wpiai_get_files_to_import() {
	$files_imported = get_option( 'wpiai_price_files_imported' );
	if ( ! is_array( $files_imported ) ) {
		$files_imported = array();
	}

	$dir = WP_PLUGIN_DIR . '/wp-infor-api/price_imports';

	$files_to_import = false;

	if ( file_exists( $dir ) && is_dir( $dir ) ) {

		// Get the files of the directory as an array
		$scan_arr  = scandir( $dir );
		$files_arr = array_diff( $scan_arr, array( '.', '..' ) );
		// echo "<pre>"; print_r( $files_arr ); echo "</pre>";
		// Get each files of our directory with line break
		foreach ( $files_arr as $file ) {
			//Get the file path
			$file_path = $dir . '/' . $file;
			// Get the file extension
			$file_ext = pathinfo( $file_path, PATHINFO_EXTENSION );
			if ( $file_ext == "csv" ) {
				if ( ! in_array( $file_path, $files_imported ) ) {
					if ( ! is_array( $files_to_import ) ) {
						$files_to_import = array();
					}
					$files_to_import[] = $file_path;
				}
			}
		}

		return $files_to_import;
	}

	return false;
}

function wpiai_import_price_file( $file ) {
	$row = 1;
	error_log( 'Currently Importing ' . $file );
	$handle = fopen( $file, 'r' );
	if ( $handle !== false ) {
		while ( ! feof( $handle ) ) {
			$data = fgetcsv( $handle, 1000, ',' );
			if ( is_array( $data ) ) {
				if ( count( $data ) === 3 ) {
					$CSD_customer_id = $data[0];
					$SKU             = $data[1];
					$price           = $data[2];
					if ( (string) $CSD_customer_id == '99' ) {
						$id = wc_get_product_id_by_sku( $SKU );
						if ( $id > 0 ) {
							$p = wc_get_product( $id );
							if ( $p ) {
								$p->set_price( $price );
								$p->set_regular_price( $price );
								$p->save();
								//error_log( $SKU . ' price updated' );
							} else {
								//error_log( 'Cannot find product ' . $id );
							}
						} else {
							//error_log( 'Cant find product ' . $SKU );
						}
					} else {
						$user_id = get_organization_id( $CSD_customer_id );
						if ( $user_id ) {
							$product_id = wc_get_product_id_by_sku( $SKU );
							if ( $product_id ) {
								update_post_meta( $product_id, 'wpiai_customer_price_' . $user_id, $price );
								//error_log( '$product = ' . $product_id . ' $user = ' . $user_id . ' $price = ' . $price );
							}
						} else {
							//error_log( $CSD_customer_id . ' user not found' );
						}
					}
				}
			}
		}
		fclose( $handle );
		$files_imported = get_option( 'wpiai_price_files_imported' );
		if ( ! is_array( $files_imported ) ) {
			$files_imported = array();
		}
		$files_imported[] = $file;
		update_option( 'wpiai_price_files_imported', $files_imported );
		error_log('Finished Importing '. $file);
	}
}

function wpiai_import_all_files( $max = 1 ) {
	$file_list = wpiai_get_files_to_import();
	if ( $file_list ) {
		$i = 1;
		foreach ( $file_list as $file ) {
			if ( $i <= $max ) {
				wpiai_import_price_file( $file );
			}
			$i ++;
		}
	}
	//error_log( 'Finished' );
}