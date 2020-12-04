<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wpiai_get_termstype_name( $option ) {
	$res = false;
	if ( $option == '01' ) {
		$res = 'E30 Nett';
	}
	if ( $option == '02' ) {
		$res = 'E35 Nett';
	}
	if ( $option == '03' ) {
		$res = 'E37 Nett';
	}
	if ( $option == '04' ) {
		$res = 'E45 Nett';
	}
	if ( $option == '05' ) {
		$res = 'E60 Nett';
	}
	if ( $option == '06' ) {
		$res = 'E65 Nett';
	}
	if ( $option == '07' ) {
		$res = 'E30 2.5 Sett';
	}
	if ( $option == '08' ) {
		$res = 'E35 2.5 Sett';
	}
	if ( $option == '09' ) {
		$res = 'E45 2.5 Sett';
	}
	if ( $option == '10' ) {
		$res = 'E60 2.5 Sett';
	}
	if ( $option == '11' ) {
		$res = 'E65 2.5 Sett';
	}
	if ( $option == '12' ) {
		$res = 'I7 Nett';
	}
	if ( $option == '13' ) {
		$res = 'I7 3.0 Sett';
	}
	if ( $option == '14' ) {
		$res = 'E7 Nett';
	}
	if ( $option == '15' ) {
		$res = 'I10 Nett';
	}
	if ( $option == '16' ) {
		$res = 'I10 2.5 Sett';
	}
	if ( $option == '17' ) {
		$res = 'E14 Nett';
	}
	if ( $option == '18' ) {
		$res = 'E14 4.0 Sett';
	}
	if ( $option == '19' ) {
		$res = 'I14 2.0 Sett';
	}
	if ( $option == '20' ) {
		$res = 'I14 2.5 Sett';
	}
	if ( $option == '21' ) {
		$res = 'I14 4.0 Sett';
	}
	if ( $option == '22' ) {
		$res = 'I14 15.0 Set';
	}
	if ( $option == '24' ) {
		$res = 'E30 2.0 Sett';
	}
	if ( $option == '25' ) {
		$res = 'E30 3.0 Sett';
	}
	if ( $option == '26' ) {
		$res = 'E30 3.75 Set';
	}
	if ( $option == '27' ) {
		$res = 'E30 4.25 Set';
	}
	if ( $option == '28' ) {
		$res = 'E30 5.0 Sett';
	}
	if ( $option == '29' ) {
		$res = 'E35 1.5 Sett';
	}
	if ( $option == '30' ) {
		$res = 'E35 2.0 Sett';
	}
	if ( $option == '31' ) {
		$res = 'E37 2.0 Sett';
	}
	if ( $option == '32' ) {
		$res = 'E37 2.5 Sett';
	}
	if ( $option == '33' ) {
		$res = 'E45 1.25 Set';
	}
	if ( $option == '34' ) {
		$res = 'E45 2.0 Sett';
	}
	if ( $option == '35' ) {
		$res = 'E45 3.0 Sett';
	}
	if ( $option == '36' ) {
		$res = 'E45 3.75 Set';
	}
	if ( $option == '37' ) {
		$res = 'E45 5.0 Sett';
	}
	if ( $option == '38' ) {
		$res = 'E60 2.0 Sett';
	}
	if ( $option == '39' ) {
		$res = 'E60 3.0 Sett';
	}
	if ( $option == '40' ) {
		$res = 'E60 3.75 Set';
	}
	if ( $option == '41' ) {
		$res = 'E60 5.0 Sett';
	}
	if ( $option == '42' ) {
		$res = 'E90 Nett';
	}
	if ( $option == '43' ) {
		$res = 'E60 1.75 Set';
	}
	if ( $option == '44' ) {
		$res = 'E60 3.5 Sett';
	}
	if ( $option == '45' ) {
		$res = 'I30 Nett';
	}
	if ( $option == '46' ) {
		$res = 'E60 4.0 Sett';
	}
	if ( $option == '47' ) {
		$res = 'E75 Nett';
	}
	if ( $option == '48' ) {
		$res = 'E30 4.0 Sett';
	}
	if ( $option == '49' ) {
		$res = 'E45 4.0 Sett';
	}
	if ( $option == '50' ) {
		$res = 'E90 2.5 Sett';
	}
	if ( $option == '51' ) {
		$res = 'E7 2.0 Sett';
	}
	if ( $option == '52' ) {
		$res = 'E60 LED GRP';
	}
	if ( $option == '99' ) {
		$res = 'ERRORS';
	}

	return $res;
}

function wpiai_get_customertype_name( $option ) {
	$res = false;
	if ( $option == 'CN' ) {
		$res = 'CASH / NON-TRADING ACC';
	}
	if ( $option == 'DI' ) {
		$res = 'DATA INSTALLAION';
	}
	if ( $option == 'ECL' ) {
		$res = 'ELEC INST COMMERCIAL LRG';
	}
	if ( $option == 'ECS' ) {
		$res = 'ELEC INST COMMERCIAL SML';
	}
	if ( $option == 'EIL' ) {
		$res = 'ELEC INST INDUSTRIAL LRG';
	}
	if ( $option == 'EIS' ) {
		$res = 'ELEC INST INDUSTRIAL SML';
	}
	if ( $option == 'ERF' ) {
		$res = 'ERF OWN USE ACCOUNTS';
	}
	if ( $option == 'ERL' ) {
		$res = 'ELEC INST RESIDENTIAL LG';
	}
	if ( $option == 'ERS' ) {
		$res = 'ELEC INST RESIDENTIAL SM';
	}
	if ( $option == 'FIA' ) {
		$res = 'FM INDUSTRIAL/AGRICULTUR';
	}
	if ( $option == 'FMC' ) {
		$res = 'FM COMMERCIAL';
	}
	if ( $option == 'FMR' ) {
		$res = 'FM RESIDENTIAL';
	}
	if ( $option == 'FSI' ) {
		$res = 'FIRE&SECURITY INSTALLATI';
	}
	if ( $option == 'LHE' ) {
		$res = 'LOCAL HEALTH EDUCAT AUTH';
	}
	if ( $option == 'MEC' ) {
		$res = 'MECH & ELECT CONTRACTOR';
	}
	if ( $option == 'PD' ) {
		$res = 'PROERTY DEVELOPER';
	}
	if ( $option == 'SF' ) {$res = 'SHOP FITTER';}

	return $res;

}

function wpiai_get_pricetype_name( $option ) {
	$res = false;
	if ( $option == 'CN1' ) {$res = 'Cash/NonTrd1';}
	if ( $option == 'CN2' ) {$res = 'Cash/NonTrd2';}
	if ( $option == 'DI1' ) {$res = 'DataInstall1';}
	if ( $option == 'DI2' ) {$res = 'DataInstall2';}
	if ( $option == 'ECL1' ) {$res = 'EICommerL1';}
	if ( $option == 'ECL2' ) {$res = 'EICommerL2';}
	if ( $option == 'ECS1' ) {$res = 'EICommerS1';}
	if ( $option == 'ECS2' ) {$res = 'EICommerS2';}
	if ( $option == 'EIL1' ) {$res = 'EIIndustrL1';}
	if ( $option == 'EIL2' ) {$res = 'EIIndustrL2';}
	if ( $option == 'EIS1' ) {$res = 'EIIndustrS1';}
	if ( $option == 'EIS2' ) {$res = 'EIIndustrS2';}
	if ( $option == 'ERL1' ) {$res = 'EIResidentL1';}
	if ( $option == 'ERL2' ) {$res = 'EIResidentL2';}
	if ( $option == 'ERS1' ) {$res = 'EIResidentS1';}
	if ( $option == 'ERS2' ) {$res = 'EIResidentS2';}
	if ( $option == 'FIA1' ) {$res = 'FMIndAgri1';}
	if ( $option == 'FIA2' ) {$res = 'FMIndAgri2';}
	if ( $option == 'FMC1' ) {$res = 'FMCommer1';}
	if ( $option == 'FMC2' ) {$res = 'FMCommer2';}
	if ( $option == 'FMR1' ) {$res = 'FMResid1';}
	if ( $option == 'FMR2' ) {$res = 'FMResid2';}
	if ( $option == 'FSI1' ) {$res = 'Fire&SecIns1';}
	if ( $option == 'FSI2' ) {$res = 'Fire&SecIns2';}
	if ( $option == 'LHE1' ) {$res = 'LocHlthEdu1';}
	if ( $option == 'LHE2' ) {$res = 'LocHlthEdu2';}
	if ( $option == 'MEC1' ) {$res = 'MechElecCon1';}
	if ( $option == 'MEC2' ) {$res = 'MechElecCon2';}
	if ( $option == 'PD1' ) {$res = 'PropertyDev1';}
	if ( $option == 'PD2' ) {$res = 'PropertyDev2';}
	if ( $option == 'SF1' ) {$res = 'Shopfitter1';}
	if ( $option == 'SF2' ) {$res = 'Shopfitter2';}


	return $res;

}

function wpiai_get_selltype_name( $option ) {
	$res = false;
	if ( $option == 'C' ) {$res = 'Cash Sale Only';}
	if ( $option == 'Y' ) {$res = 'Account Sales';}
	if ( $option == 'N' ) {$res = 'Hold - will allow cash transactions online';}

	return $res;

}

function wpiai_get_whse_name( $option ) {
	$res = false;
	if ( $option == 'BHM' ) {
		$res = 'BIRMINGHAM BRANCH';
	}
	if ( $option == 'BLY' ) {
		$res = 'BLABY BRANCH';
	}
	if ( $option == 'BUL' ) {
		$res = 'ANDOVER BRANCH';
	}
	if ( $option == 'CAN' ) {
		$res = 'CANNOCK BRANCH';
	}
	if ( $option == 'DER' ) {
		$res = 'DERBY BRANCH';
	}
	if ( $option == 'FAR' ) {
		$res = 'FARNBOROUGH BRANCH';
	}
	if ( $option == 'FCC' ) {
		$res = 'FCC ELECTRICAL WHOLESALERS';
	}
	if ( $option == 'ILK' ) {
		$res = 'ILKESTON BRANCH';
	}
	if ( $option == 'LEI' ) {
		$res = 'LEICESTER BRANCH';
	}
	if ( $option == 'NEW' ) {
		$res = 'NEWARK BRANCH';
	}
	if ( $option == 'NTM' ) {
		$res = 'NOTTINGHAM BRANCH';
	}
	if ( $option == 'NUN' ) {
		$res = 'NUNEATON BRANCH';
	}
	if ( $option == 'WOLC' ) {
		$res = 'WOLVERHAMPTON BRANCH';
	}
	return $res;
}