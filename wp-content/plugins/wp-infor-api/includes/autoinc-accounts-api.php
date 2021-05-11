<?php

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
	$request  = createInvoicesRequest( $organizationID, $start_date, $end_date );
	$request = str_replace('[','',$request);
	$request = str_replace(']','',$request);
	//error_log($request);
	$url      = get_option( 'wpiai_invoices_url' );
	$response = json_decode( wpiai_get_infor_api_response( $url, $request ), true );
	if ( is_array( $response ) ) {
		//error_log(print_r($response, true));
		if(is_array($response['response']['tArtransV3']['t-artransV3'])) {
			$invoices = $response['response']['tArtransV3']['t-artransV3'];
			//error_log(print_r($invoices,true));
			$res = array();
			foreach ($invoices as $invoice) {
				$i = array();
				$keys = array_keys($invoice);
				foreach ($keys as $key) {
					$i_key = get_invoice_codes($key);
					//error_log($i_key);
					if($i_key) {
						if($i_key=='transaction_type') {
							if($invoice[$key]=='IN') {
								$i[$i_key]='Invoice';
							} else {
								if($invoice[$key]=='UC') {
									$i[$i_key]='Unapplied Cash';
								} else {
									if($invoice[$key]=='MC') {
										$i[$i_key]='Micellaneous Credit';
									} else {
										if($invoice[$key]=='CK') {
											$i[$i_key]='Payment';
										} else {
											$i[$i_key] = $invoice[$key];
										}
									}
								}
							}
						} else {
							$i[$i_key] = trim(trim($invoice[$key],'-'));
						}

					}
				}
				if(!empty($i)) {
					$res[] = $i;
				}
			}
			if(!empty($res)) {
				//error_log(print_r($res,true));
				return $res;
			}
		}
	}
	return false;
}