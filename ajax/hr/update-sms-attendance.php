<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2008-15 (C) Triple Tree                                                        **
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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/session.php");

	if ($sUserRights['Edit'] != "Y")
	{
		print "ERROR|-|You havn\'t enough Rights to modify the selected Data.\n";
		exit( );
	}

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id      = IO::intValue("Id");
	$User    = IO::intValue("User");
	$Date    = IO::strValue("Date");
	$InOut   = IO::strValue("InOut");
	$TimeHr  = IO::strValue("TimeHr");
	$TimeMin = IO::strValue("TimeMin");
	$Status  = IO::strValue("Status");
	$Remarks = IO::strValue("Remarks");

	$sTime = ("$TimeHr:$TimeMin:00");

	$sSQL  = "SELECT * FROM tbl_sms_attendance WHERE id='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 1)
		{
			$sSQL = "UPDATE tbl_sms_attendance SET `date`='$Date', in_out='$InOut', `time`='$sTime', remarks='$Remarks', status='$Status' WHERE id='$Id'";

			if ($objDb->execute($sSQL) == true)
			{
				$sSQL = "SELECT mobile FROM tbl_users WHERE id='$User'";
				$objDb->query($sSQL);

				$sMobile = $objDb->getField(0, 0);
				$sBody   = "";

				if ($Status == "A")
				{
					$sSQL = "SELECT * FROM tbl_attendance WHERE `date`='$Date' AND user_id='$User'";
					$objDb->query($sSQL);

					if ($objDb->getCount( ) == 1)
					{
						$sSQL = ("UPDATE tbl_attendance SET time_".(($InOut == "I") ? "in" : "out")."='$sTime' WHERE `date`='$Date' AND user_id='$User'");
						$objDb->execute($sSQL);
					}

					else
					{
						$sTimeIn  = "00:00:00";
						$sTimeOut = "00:00:00";

						if ($InOut == "I")
							$sTimeIn = $sTime;

						else
							$sTimeOut = $sTime;

						$sSQL = "INSERT INTO tbl_attendance (`date`, user_id, time_in, time_out) VALUES ('$Date', '$User', '$sTimeIn', '$sTimeOut')";
						$objDb->execute($sSQL);
					}

					$sBody = "Your attendance entry has been approved.";
				}

				else if ($Status == "R")
					$sBody  = "Your attendance entry has been rejected.";

				if ($Status != "P")
				{
					if ($Remarks != "")
						$sBody .= "\r\n\r\nRemarks: $Remarks";

					$objSms = new Sms( );
					$objSms->send($sMobile, "", $sBody, "*** SMS Attendance ***");
					$objSms->close( );
				}

				switch ($Status)
				{
					case "P" : $sStatus = "Pending"; break;
					case "A" : $sStatus = "Approved"; break;
					case "R" : $sStatus = "Rejected"; break;
				}

				print ("OK|-|$Id|-|<div>The selected SMS Attendance Record has been Updated successfully.</div>|-|".formatDate($Date)."|-|".(($InOut == "O") ? "Out" : "In")."|-|".formatTime($sTime)."|-|$sStatus|-|$Remarks");
			}

			else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|Invalid Attendance Record. Please selected a valid Attendance Record to Modify.";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>