<?php

function createSingleInvoiceRequest( $order_number, $order_suffix = 0 ) {
	$request = json_decode( get_option( 'wpiai_single_invoice_request' ), true );
	if ( is_array( $request ) ) {
		if ( array_key_exists( 'request', $request ) ) {
			$body = $request['request'];
			if ( array_key_exists( 'orderNumber', $body ) ) {
				$body['orderNumber'] = $order_number;
				if(array_key_exists('orderSuffix',$body)) {
					$body['orderSuffix'] = $order_suffix;
				}
				$return              = array();
				$return['request']   = array();
				$return['request'][] = $body;

				return json_encode( $return );
			}
		}
	}

	return false;
}

function getSingleInvoice( $order_number, $order_suffix ) {
	$request = createSingleInvoiceRequest( $order_number, $order_suffix );
	$request = str_replace( '[', '', $request );
	$request = str_replace( ']', '', $request );
	//error_log($request);
	if ( $request ) {
		$wpiai_single_invoice_url = get_option( 'wpiai_single_invoice_url' );
		$order                    = json_decode( wpiai_get_infor_api_response( $wpiai_single_invoice_url, $request ), true );
		//error_log(print_r($order,true));
		if(is_array($order)) {
			if(array_key_exists('response',$order)) {
				$order_response = $order['response'];
				if ( array_key_exists( 'cErrorMessage', $order_response ) ) {
					if ( $order_response['cErrorMessage'] != '' ) {
						return $order_response['cErrorMessage'];
					} else {
						$return                     = array();
						$header                     = array();
						$header['ship_to']          = array();
						$header['customer_details'] = array();
						$order_lines                = array();
						if ( array_key_exists( 'tFieldlist', $order_response ) ) {
							if ( is_array( $order_response['tFieldlist'] ) ) {
								if ( array_key_exists( 't-fieldlist', $order_response['tFieldlist'] ) ) {
									$order_fields = $order_response['tFieldlist']['t-fieldlist'];
									if ( is_array( $order_fields ) ) {
										foreach ( $order_fields as $order_field ) {
											if ( $order_field['fieldName'] == 'orderno' ) {
												$header['CSD_order_number'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'custno' ) {
												$header['CSD_customer_number'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'stage' ) {
												$header['order_stage'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'whse' ) {
												$header['warehouse'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'orderdisp' ) {
												$header['disposition'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'name' ) {
												$header['CSD_account_name'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'shipviatydesc' ) {
												$header['ship_via'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'shiptonm' ) {
												$header['ship_to']['name'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'shiptoaddr1' ) {
												$header['ship_to']['addr1'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'shiptoaddr2' ) {
												$header['ship_to']['addr2'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'shiptocity' ) {
												$header['ship_to']['city'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'shiptozip' ) {
												$header['ship_to']['postcode'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'custpo' ) {
												$header['customer_PO_number'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'contactid' ) {
												$header['contact_id'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'contactnm' ) {
												$header['contact_name'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'soldtonm' ) {
												$header['customer_details']['name'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'soldtoaddr1' ) {
												$header['customer_details']['addr1'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'soldtoaddr2' ) {
												$header['customer_details']['addr2'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'soldtocity' ) {
												$header['customer_details']['city'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'soldtozipcd' ) {
												$header['customer_details']['postcode'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'user4' ) {
												$header['woocommerce_order_id'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'enterdt' ) {
												$header['order_entered_date'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'invoicedt' ) {
												$header['order_invoice_date'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'pickeddt' ) {
												$header['order_picked_date'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'taxamt' ) {
												$header['order_VAT_total'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'termstypedesc' ) {
												$header['order_terms'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'totinvamt' ) {
												$header['order_total_inc_VAT'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'totlineamt' ) {
												$header['order_total_ex_VAT'] = trim($order_field['fieldValue']);
											}
											if ( $order_field['fieldName'] == 'addonnet1' ) {
												$header['order_delivery_ex_VAT'] = trim($order_field['fieldValue']);
											}
										}
										$return['header'] = $header;
									}
								}
							}
						}
						if(array_key_exists('tOelineitemV3',$order_response)) {
							if(is_array($order_response['tOelineitemV3'])) {
								if(array_key_exists('t-oelineitemV3',$order_response['tOelineitemV3'])) {
									$order_items = $order_response['tOelineitemV3']['t-oelineitemV3'];
									foreach ($order_items as $order_item) {
										$oi = array();
										$oi['SKU'] = $order_item['prod'];
										$oi['desc1'] = $order_item['desc1'];
										$oi['desc2'] = $order_item['desc2'];
										$oi['order_unit'] = $order_item['unit'];
										$oi['quantity_ordered'] = $order_item['qtyOrd'];
										$oi['quantity_shipped'] = $order_item['qtyShip'];
										$oi['total_line_ex_VAT'] = $order_item['netAmt'];
										$order_lines[] = $oi;
									}
									$return['lines'] = $order_lines;
								}
							}
						}
						return $return;
					}
				}
			}
		}
	}
	return false;
}

function createAccountsRequest( $cusNum ) {
	if ( $cusNum <> '' ) {
		$response = '{"request": {"companyNumber": 1,"operatorInit": "WOO","customerNumber": ' . $cusNum . '}}';

		return $response;
	}

	return false;
}

function getAccountBalances( $custNum ) {
	$request = createAccountsRequest( $custNum );
	if ( $request ) {
		$balances_url = get_option( 'wpiai_accounts_customer_balance_url' );
		$balances     = json_decode( wpiai_get_infor_api_response( $balances_url, $request ), true );
		if ( is_array( $balances ) ) {
			if ( array_key_exists( 'response', $balances ) ) {
				$balance_response = $balances['response'];
				if ( array_key_exists( 'cErrorMessage', $balance_response ) ) {
					if ( $balance_response['cErrorMessage'] != '' ) {
						return $balance_response['cErrorMessage'];
					} else {
						$return = array();

						if ( array_key_exists( 'futureBalance', $balance_response ) ) {
							$return['future_balance'] = $balance_response['futureBalance'];
						} else {
							$return['future_balance'] = 0;
						}

						$return['due_balance'] = 0;

						if ( array_key_exists( 'period1Balance', $balance_response ) ) {
							$return['due_balance'] = $return['due_balance'] + (float) $balance_response['period1Balance'];
						}

						if ( array_key_exists( 'period2Balance', $balance_response ) ) {
							$return['due_balance'] = $return['due_balance'] + (float) $balance_response['period2Balance'];
						}

						if ( array_key_exists( 'period3Balance', $balance_response ) ) {
							$return['due_balance'] = $return['due_balance'] + (float) $balance_response['period3Balance'];
						}

						if ( array_key_exists( 'period4Balance', $balance_response ) ) {
							$return['due_balance'] = $return['due_balance'] + (float) $balance_response['period4Balance'];
						}

						if ( array_key_exists( 'period5Balance', $balance_response ) ) {
							$return['due_balance'] = $return['due_balance'] + (float) $balance_response['period5Balance'];
						}

						if ( array_key_exists( 'total4Balance', $balance_response ) ) {
							$return['due_balance'] = $return['due_balance'] + (float) $balance_response['total4Balance'];
						}
						$return['available_credit'] = 0;
						if ( array_key_exists( 'total3Balance', $balance_response ) ) {
							$return['available_credit'] = $return['available_credit'] + (float) $balance_response['total3Balance'];
							$data_credit_url            = get_option( 'wpiai_accounts_customer_data_credit_url' );
							$data_credit                = json_decode( wpiai_get_infor_api_response( $data_credit_url, $request ), true );
							if ( is_array( $data_credit ) ) {
								if ( array_key_exists( 'response', $data_credit ) ) {
									$data_credit_body = $data_credit['response'];

									if ( array_key_exists( 'orderBalance', $data_credit_body ) ) {
										$return['available_credit'] = $return['available_credit'] + (float) $data_credit_body['orderBalance'];
									}

									if ( array_key_exists( 'creditLimit', $data_credit_body ) ) {
										$return['available_credit'] = $return['available_credit'] - (float) $data_credit_body['creditLimit'];
									}
								}
							}
						}

						return $return;
					}
				}
			}

		}

	}

	return false;
}

function get_invoice_codes( $code ) {
	if ( $code == 'invdt' ) {
		return 'date';
	}
	if ( $code == 'invno' ) {
		return 'invoice_number';
	}
	if ( $code == 'statustype' ) {
		return 'status';
	}
	if ( $code == 'transcd' ) {
		return 'transaction_type';
	}
	if ( $code == 'amountx' ) {
		return 'amount';
	}
	if ( $code == 'amtduex' ) {
		return 'amount_due';
	}
	if ( $code == 'duedt' ) {
		return 'due_date';
	}
	if ( $code == 'custpo' ) {
		return 'customer_po_reference';
	}

	return false;

}

function createInvoicesRequest( $organizationID, $start_date, $end_date ) {
	$request = json_decode( get_option( 'wpiai_invoices_request' ), true );
	if ( is_array( $request ) ) {
		if ( array_key_exists( 'request', $request ) ) {
			$body = $request['request'];
			if ( array_key_exists( 'customerNumber', $body ) ) {
				$body['customerNumber'] = $organizationID;
				if ( array_key_exists( 'startDate', $body ) ) {
					$body['startDate'] = $start_date;
					if ( array_key_exists( 'endDate', $body ) ) {
						$body['endDate']     = $end_date;
						$return              = array();
						$return['request']   = array();
						$return['request'][] = $body;

						return json_encode( $return );
					}
				}
			}
		}
	}

	return false;
}

function get_customer_invoices( $organizationID, $start_date, $end_date ) {
	$request = createInvoicesRequest( $organizationID, $start_date, $end_date );
	$request = str_replace( '[', '', $request );
	$request = str_replace( ']', '', $request );
	//error_log($request);
	$url      = get_option( 'wpiai_invoices_url' );
	$response = json_decode( wpiai_get_infor_api_response( $url, $request ), true );
	if ( is_array( $response ) ) {
		//error_log(print_r($response, true));
		if ( is_array( $response['response']['tArtransV3']['t-artransV3'] ) ) {
			$invoices = $response['response']['tArtransV3']['t-artransV3'];
			//error_log(print_r($invoices,true));
			$res = array();
			foreach ( $invoices as $invoice ) {
				$i    = array();
				$keys = array_keys( $invoice );
				foreach ( $keys as $key ) {
					$i_key = get_invoice_codes( $key );
					//error_log($i_key);
					if ( $i_key ) {
						if ( $i_key == 'transaction_type' ) {
							if ( $invoice[ $key ] == 'IN' ) {
								$i[ $i_key ] = 'Invoice';
							} else {
								if ( $invoice[ $key ] == 'UC' ) {
									$i[ $i_key ] = 'Unapplied Cash';
								} else {
									if ( $invoice[ $key ] == 'MC' ) {
										$i[ $i_key ] = 'Micellaneous Credit';
									} else {
										if ( $invoice[ $key ] == 'CK' ) {
											$i[ $i_key ] = 'Payment';
										} else {
											$i[ $i_key ] = $invoice[ $key ];
										}
									}
								}
							}
						} else {
							$i[ $i_key ] = trim( trim( $invoice[ $key ], '-' ) );
						}

					}
				}
				if ( ! empty( $i ) ) {
					$res[] = $i;
				}
			}
			if ( ! empty( $res ) ) {
				//error_log(print_r($res,true));
				return $res;
			}
		}
	}

	return false;
}