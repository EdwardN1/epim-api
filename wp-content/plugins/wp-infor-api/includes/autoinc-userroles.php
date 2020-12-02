<?php

defined( 'ABSPATH' ) or exit;

$customer_role_set = get_role( 'customer' )->capabilities;
$role = 'inactive_customer';
$display_name = 'Inactive Customer';

add_role( $role, $display_name, $customer_role_set );