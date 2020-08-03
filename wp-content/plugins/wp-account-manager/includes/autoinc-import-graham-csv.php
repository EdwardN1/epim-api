<?php
add_shortcode('wpam-import', 'wpam_import_shortcode');

function wpam_import_shortcode($params=array()) {
	$row = 0;
	$account = new Account;
	if (is_array($params)) {
		if (array_key_exists('name', $params)) {
			if (($handle = fopen($params['name'], "r")) !== FALSE) {
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					if($row>=451) break;
					$num = count($data);
					if($num==2) {
						try {
							$account->addAccount(trim($data[0]),'u4saf3pa8sW0r#',trim($data[1]));
							echo $data[0].' | '.$data[1]. ' imported OK<br>';
							$row++;
						}
						catch (Exception $e) {
							echo $data[0].' | '.$data[1].' ! '.$e->getMessage().'<br>';
							error_log($data[0].' | '.$data[1].' ! '.$e->getMessage());
						}
					}
				}
				fclose($handle);
			}
		}
	}
}