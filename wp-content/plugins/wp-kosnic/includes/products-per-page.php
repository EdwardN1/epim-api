<?php
// Change the Number of WooCommerce Products Displayed Per Page
add_filter( 'loop_shop_per_page', create_function( '$products', 'return 12;' ), 30 );