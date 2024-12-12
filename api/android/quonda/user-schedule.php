<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  MATRIX Customer Portal                                                                   **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.apparelco.com                                                              **
	**                                                                                           **
	**  Copyright 2008-15 (C) Matrix Sourcing                                                    **
	**                                                                                           **
	**  ***************************************************************************************  **
	**                                                                                           **
	**  Project Manager:                                                                         **
	**                                                                                           **
	**      Name  :  Muhammad Tahir Shahzad                                                      **
	**      Email :  mtahirshahzad@hotmail.com                                                   **
	**      Phone :  +92 333 456 0482                                                            **
	**      URL   :  http://www.mtshahzad.com                                                    **
	**                                                                                           **
	**  ***************************************************************************************  **
	**                                                                                           **
	**                                                                                           **
	**                                                                                           **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$User       = IO::strValue('User');
	$AuditCode  = IO::strValue('AuditCode');


	$iAuditCode = intval(substr($AuditCode, 1));


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";

	if ($User == "" || $AuditCode == "" || $iAuditCode == 0 || $AuditCode{0} != "T")
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, status FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else
		{
			$iUser = $objDb->getField(0, "id");
			$sName = $objDb->getField(0, "name");


			$sSQL = "SELECT * FROM tbl_user_schedule WHERE id='$iAuditCode'";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 0)
				$aResponse["Message"] = "No Schedule Found!";

			else
			{
				$iLocation  = $objDb->getField(0, 'location_id');
				$sFromDate  = $objDb->getField(0, 'from_date');
				$sToDate    = $objDb->getField(0, 'to_date');
				$sDetails   = $objDb->getField(0, 'details');
				$sStartTime = $objDb->getField(0, 'start_time');
				$sEndTime   = $objDb->getField(0, 'end_time');


				$sSchedule = array("Location"  => $iLocation,
				                   "Date"      => $sFromDate,
				                   "Details"   => $sDetails,
				                   "StartTime" => substr($sStartTime, 0, 5),
				                   "EndTime"   => substr($sEndTime, 0, 5));

				$aResponse['Status']   = "OK";
				$aResponse['Schedule'] = $sSchedule;
			}
		}
	}


	print @json_encode($aResponse);


/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Schedule Audit";
	$objEmail->Body    = @json_encode($aResponse).$sMessage;

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>