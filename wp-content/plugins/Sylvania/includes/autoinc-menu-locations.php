<?php
//wp_nav_menu(array('theme_location' => 'consumer-menu', 'container_class' => 'consumer_menu_class'));

function syl_consumer_menu() {
    register_nav_menu('syl_consumer_menu',__( 'Consumer Menu' ));
}
add_action( 'init', 'syl_consumer_menu' );