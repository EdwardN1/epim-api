<?php
add_action( 'template_redirect', 'menus_redirect_page' );

function menus_redirect_page() {
    //error_log('checking for redirects');
    if (isset($_SERVER['HTTPS']) &&
        ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
        isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
        $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
        $protocol = 'https://';
    }
    else {
        $protocol = 'http://';
    }

    $currenturl = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $currenturl_relative = wp_make_link_relative($currenturl);

    //error_log($currenturl_relative);


    if (have_rows('restaurant_menus', 'option')) :
        while (have_rows('restaurant_menus', 'option')) : the_row();
            $redirect_address = get_sub_field('redirect_address');
            $menu_file = get_sub_field('menu_file');
            switch (trim($currenturl_relative,'/')) {

                case $redirect_address:
                    $urlto = $menu_file;
                    break;

                default:
                    return;

            }


        endwhile;
    endif;

    if ($currenturl != $urlto)
        exit( wp_redirect( $urlto ) );
        //error_log('$currenturl = '.$currenturl);
    //error_log('$urlto = '.$urlto);

}