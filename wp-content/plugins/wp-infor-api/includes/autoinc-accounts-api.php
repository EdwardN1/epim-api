<?php

function createAccountsRequest($cusNum) {
	if($cusNum<>'') {
		$response = '{"request": {"companyNumber": 1,"operatorInit": "WOO","customerNumber": '.$cusNum.'}}';
		return $response;
	}
	return false;
}

function getAccountBalances($custNum) {
	$request = createAccountsRequest($custNum);
	if($request) {
		$balances_url = get_option('wpiai_accounts_customer_balance_url');
		$balances = json_decode(wpiai_get_infor_api_response($balances_url,$request), true);
		if(is_array($balances)) {
			if(array_key_exists('response',$balances)) {
				$balance_response = $balances['response'];
				if(array_key_exists('cErrorMessage', $balance_response)) {
					if($balance_response['cErrorMessage']!='') {
						return $balance_response['cErrorMessage'];
					} else {
						$return = array();

						if(array_key_exists('futureBalance', $balance_response)) {
							$return['future_balance'] = $balance_response['futureBalance'];
						} else {
							$return['future_balance'] = 0;
						}

						$return['due_balance'] = 0;

						if(array_key_exists('period1Balance', $balance_response)) {
							$return['due_balance'] = $return['due_balance'] + (float) $balance_response['period1Balance'];
						}

						if(array_key_exists('period2Balance', $balance_response)) {
							$return['due_balance'] = $return['due_balance'] + (float) $balance_response['period2Balance'];
						}

						if(array_key_exists('period3Balance', $balance_response)) {
							$return['due_balance'] = $return['due_balance'] + (float) $balance_response['period3Balance'];
						}

						if(array_key_exists('period4Balance', $balance_response)) {
							$return['due_balance'] = $return['due_balance'] + (float) $balance_response['period4Balance'];
						}

						if(array_key_exists('period5Balance', $balance_response)) {
							$return['due_balance'] = $return['due_balance'] + (float) $balance_response['period5Balance'];
						}

						if(array_key_exists('total4Balance', $balance_response)) {
							$return['due_balance'] = $return['due_balance'] + (float) $balance_response['total4Balance'];
						}
						$return['available_credit'] = 0;
						if(array_key_exists('total3Balance',$balance_response)) {
							$return['available_credit'] = $return['available_credit'] + (float) $balance_response['total3Balance'];
							$data_credit_url = get_option('wpiai_accounts_customer_data_credit_url');
							$data_credit = json_decode(wpiai_get_infor_api_response($data_credit_url,$request), true);
							if(is_array($data_credit)) {
								if(array_key_exists('response',$data_credit)) {
									$data_credit_body = $data_credit['response'];

									if(array_key_exists('orderBalance', $data_credit_body)) {
										$return['available_credit'] = $return['available_credit'] + (float) $data_credit_body['orderBalance'];
									}

									if(array_key_exists('creditLimit', $data_credit_body)) {
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