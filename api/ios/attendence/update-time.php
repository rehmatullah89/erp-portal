<?php
	@require_once("../../requires/session.php");
	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$User   	= IO::intValue('User');

	$Date    	= IO::strValue('Date');
	$TimeIn    	= IO::strValue('TimeIn');
	$TimeOut   	= IO::strValue('TimeOut');

	$aResponse = array( );


	$sSQL = "Update tbl_attendance set date = '$Date', time_in = '$TimeIn' ,time_out = '$TimeOut' WHERE user_id = '$User' and date = '$Date'";

	//$sSQL = "UPDATE tbl_attendance (`date`, user_id, time_in, time_out, remarks) VALUES ('".IO::strValue("Date")."', '".IO::intValue("User")."', '".IO::strValue("TimeIn")."', '".IO::strValue("TimeOut")."', '".IO::strValue("Remarks")."')";

	//print $sSQL; exit;

	if ($objDb->execute($sSQL) == true){

		$aResponse['status'] = "OK";
		$aResponse['message'] = "Attendance Updated";

	}else{

		$aResponse['status'] = "ERROR";
		$aResponse['message'] = "Record can not be updated";

	}

	print json_encode($aResponse);
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>