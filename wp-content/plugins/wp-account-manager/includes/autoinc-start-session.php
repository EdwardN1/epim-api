<?php
function register_wpam_session()
{
    if( !session_id() )
    {
        session_start();
    }
}

add_action('init', 'register_wpam_session');