<?php

defined( 'ABSPATH' ) or exit;

$customer_role_set = get_role( 'customer' )->capabilities;
$role = 'account_customer';
$display_name = 'Account Customer';

add_role( $role, $display_name, $customer_role_set );

$role = 'price_customer_1';
$display_name = 'Customer Price 1';
add_role( $role, $display_name, $customer_role_set );
$role = 'price_customer_2';
$display_name = 'Customer Price 2';
add_role( $role, $display_name, $customer_role_set );
$role = 'price_customer_3';
$display_name = 'Customer Price 3';
add_role( $role, $display_name, $customer_role_set );