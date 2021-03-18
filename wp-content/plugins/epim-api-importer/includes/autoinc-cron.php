<?php
register_activation_hook(epimaapi_PLUGINFILE, 'epimaapi_cron_activation');

function epimaapi_cron_activation() {
	wp_schedule_event( strtotime('22:20:00'), 'daily', 'epimaapi_update_branch_stock_daily' );
}

function epimaapi_update_branch_stock_daily() {
	$yesterday = date('dMY',strtotime("-1 days"));
	$branches = json_decode(get_epimaapi_all_branches(),true);
	if(is_array($branches)) {
		foreach ($branches as $branch) {
			if(is_array($branch)) {
				if(array_key_exists('Id',$branch)) {
					$Id = $branch['Id'];
					$stockLevels = json_decode(get_epimaapi_get_branch_stock_since($Id,$yesterday),true);
					if(is_array($stockLevels)) {
						foreach ($stockLevels as $stock_level) {
							epimaapi_update_branch_stock($Id,$stock_level['VariationId'],$stock_level['Stock']);
						}
					} else {
						error_log('epim daily cron - No stock to update for Branch: '.$Id);
					}
				} else {
					error_log('epim daily cron - missing Id for branch');
				}
			} else {
				error_log('epim daily cron - No Branches returned');
			}
		}
	} else {
		error_log('epim daily cron - failed to get branches');
	}
}