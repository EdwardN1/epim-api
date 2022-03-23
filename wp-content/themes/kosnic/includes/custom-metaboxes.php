<?php
/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 *
 * Be sure to replace all instances of 'yourprefix_' with your project's prefix.
 * http://nacin.com/2010/05/11/in-wordpress-prefix-everything/
 *
 * @category Kosnic
 * @package  CMB2
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/WebDevStudios/CMB2
 */

if(file_exists(__DIR__ . '/CMB2/init.php')) {
  require_once __DIR__ . '/CMB2/init.php';
}

function cmb2_kos_metaboxes() {
  $prefix = CMB2_PREFIX;

  $cmb2_field_files = new DirectoryIterator(__DIR__ . '/custom-metaboxes');

  foreach($cmb2_field_files as $file) {
    if($file->isFile()) require_once($file->getPathname());
  }
}
add_action('cmb2_init', 'cmb2_kos_metaboxes');
