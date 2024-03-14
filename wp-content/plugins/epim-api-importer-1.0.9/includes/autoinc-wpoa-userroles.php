<?php

defined( 'ABSPATH' ) or exit;

$customer_role_set = get_role( 'customer' )->capabilities;
$role = 'account_customer';
$display_name = 'Account Customer';

add_role( $role, $display_name, $customer_role_set );