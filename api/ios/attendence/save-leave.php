<?php
	@require_once("../../requires/session.php");
	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$User   	= IO::intValue('User');

	$Date    	= IO::strValue('Date');
	$TimeIn    	= IO::strValue('TimeIn');
	$TimeOut   	= IO::strValue('TimeOut');

	$aResponse = array( );


		$sSQL  = ("SELECT * FROM tbl_user_leaves WHERE user_id='".IO::intValue("Employee")."' AND from_date='".IO::strValue("FromDate")."' AND to_date='".IO::strValue("ToDate")."'");
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
		{
			$iId = getNextId("tbl_user_leaves");


			$sSQL = ("INSERT INTO tbl_user_leaves (id, user_id, leave_type_id, from_date, to_date, details, leave_app, created, created_by, modified, modified_by) VALUES ('$iId', '".IO::intValue("Employee")."', '".IO::intValue("LeaveType")."', '".IO::strValue("FromDate")."', '".IO::strValue("ToDate")."', '".IO::strValue("Details")."', '$sLeaveApp', NOW( ), '$User', NOW( ), '$User')");

			//print $sSQL; exit;

			if ($objDb->execute($sSQL) == true){
				$aResponse['status'] = "OK";
				$aResponse['message'] = "Leave Saved";
			}else
			{
				$aResponse['status'] = "OK";
				$aResponse['message'] = "Leave can not be saved";
			}
		}

		else{

			$aResponse['status'] = "OK";
			$aResponse['message'] = "Leave already exists";
		}



	print json_encode($aResponse);
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>