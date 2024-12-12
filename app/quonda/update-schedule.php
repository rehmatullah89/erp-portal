<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree QUONDA App                                                                   **
	**  Version 3.0                                                                              **
	**                                                                                           **
	**  http://app.3-tree.com                                                                    **
	**                                                                                           **
	**  Copyright 2008-17 (C) Triple Tree                                                        **
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
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$User       = IO::strValue('User');
	$AuditCode  = IO::strValue("AuditCode");
	$Location   = IO::intValue("Location");
	$Date       = IO::strValue("Date");
	$StartTime  = IO::strValue("StartTime");
	$EndTime    = IO::strValue("EndTime");
	$Details    = IO::strValue("Details");
	$iAuditCode = intval(substr($AuditCode, 1));

	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";

	if ($User == "" || $AuditCode == "" || $iAuditCode == 0 || $AuditCode{0} != "T")
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, status, guest FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else
		{
			$iUser  = $objDb->getField(0, "id");
			$sName  = $objDb->getField(0, "name");
			$sGuest = $objDb->getField(0, "guest");


			$sStartTime = "{$StartTime}:00";
			$sEndTime   = "{$EndTime}:00";

			if ($Location == 0 || $Date == "" || $StartTime == "" || $EndTime == "" || $Details == "")
				$aResponse["Message"] = "Invalid Audit Scheduling Request";

			else if (strtotime($sEndTime) <= strtotime($sStartTime))
				$aResponse["Message"] = "Invalid Audit End Time";

			else if ((int)getDbValue("COUNT(1)", "tbl_qa_reports", "user_id='$iUser' AND audit_date='$Date' AND (('$sStartTime' BETWEEN start_time AND end_time) OR ('$sEndTime' BETWEEN start_time AND end_time) OR (start_time BETWEEN '$sStartTime' AND '$sEndTime') OR (end_time BETWEEN '$sStartTime' AND '$sEndTime'))") > 0)
				$aResponse["Message"] = "Invalid Time, Start/End Time is overlapping with an Audit Entry.";

			else if ((int)getDbValue("COUNT(1)", "tbl_user_schedule", "id!='$iAuditCode' AND user_id='$iUser' AND ('$Date' BETWEEN from_date AND to_date) AND (('$sStartTime' BETWEEN start_time AND end_time) OR ('$sEndTime' BETWEEN start_time AND end_time) OR (start_time BETWEEN '$sStartTime' AND '$sEndTime') OR (end_time BETWEEN '$sStartTime' AND '$sEndTime'))") > 0)
				$aResponse["Message"] = "Invalid Time, Start/End Time is overlapping with another Schedule Entry.";

			else
			{
				$sSQL = "UPDATE tbl_user_schedule SET location_id = '$Location',
				                                      from_date   = '$Date',
				                                      to_date     = '$Date',
				                                      start_time  = '$sStartTime',
				                                      end_time    = '$sEndTime',
				                                      details     = '$Details'
						 WHERE user_id='$iUser' AND id='$iAuditCode'";

				if ($objDb->execute($sSQL, true, $iUser, $sName) == true)
				{
					$aResponse['Status']  = "OK";
					$aResponse['Message'] = "Audit Re-Scheduled Successfully!";
				}

				else
					$aResponse['Message'] = "An ERROR occured, please re-try.";
			}
		}
	}



/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Schedule Audit";
	$objEmail->Body    = @json_encode($aResponse).$sMessage;

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/

	print @json_encode($aResponse);


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>