<?php
	@require_once("../../requires/session.php");
	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$User   	= IO::intValue('User');

	$Date    	= IO::strValue('Date');
	$TimeIn    	= IO::strValue('TimeIn');
	$TimeOut   	= IO::strValue('TimeOut');

	$Locations   = IO::strValue('Locations');

	//$Employee   = IO::strValue('Employee');

	$VisitType = IO::strValue('VisitType');


	$Date    	= IO::strValue('Date');

	//$TimeOut = date("H:i:s");

	$TimeIn = date('H:i:s', strtotime($TimeIn));
	$TimeOut = date('H:i:s', strtotime($TimeOut));

	$aResponse = array( );

	$iId = getNextId("tbl_user_visits");

	$sSQL = ("INSERT INTO tbl_user_visits (id, user_id, `date`, time_out, time_in, type, locations) VALUES ('$iId', '$User', '$Date', '$TimeOut', '$TimeIn', '$VisitType', '$Locations')");

	//print $sSQL; exit;

	if ($objDb->execute($sSQL) == true){

		$aResponse['status'] = "OK";
		$aResponse['message'] = "Visit Saved";

	}else{

		$aResponse['status'] = "ERROR";
		$aResponse['message'] = "Record can not be updated";

	}

	print json_encode($aResponse);
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
