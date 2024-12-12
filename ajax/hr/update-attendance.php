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

	$Id         = IO::intValue("Id");
	$Date       = IO::strValue("Date");
	$UserId     = IO::intValue("UserId");
	$Entry      = IO::intValue("Entry");
	$TimeInHr   = IO::strValue("TimeInHr");
	$TimeInMin  = IO::strValue("TimeInMin");
	$TimeOutHr  = IO::strValue("TimeOutHr");
	$TimeOutMin = IO::strValue("TimeOutMin");
	$Remarks    = IO::strValue("Remarks");

	$sTimeIn  = ("$TimeInHr:$TimeInMin:00");
	$sTimeOut = ("$TimeOutHr:$TimeOutMin:00");


	$sSQL  = "SELECT * FROM tbl_attendance WHERE `date`='$Date' AND user_id='$UserId' AND `entry`='$Entry'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 1)
		{
			$sLocationIn  = $objDb->getField(0, 'location_in');
			$sLocationOut = $objDb->getField(0, 'location_out');

			$sRemarksLocation = $Remarks;

			if ($sLocationIn != "" && @strpos($sLocationIn, "Address not found") === FALSE)
				$sRemarksLocation .= ((($sRemarksLocation != "") ? "<hr />" : "")."<b>IN:</b> {$sLocationIn}");

			if ($sLocationOut != "" && @strpos($sLocationOut, "Address not found") === FALSE)
				$sRemarksLocation .= ((($sRemarksLocation != "") ? "<hr />" : "")."<b>OUT:</b> {$sLocationOut}");

			$sRemarksLocation = str_replace(array("\r\n", "\n"), ", ", $sRemarksLocation);
			$sRemarksLocation = str_replace(", , ", ",", $sRemarksLocation);
			$sRemarksLocation = str_replace(array(",,,,", ",,,", ",,"), "<br />", $sRemarksLocation);
			$sRemarksLocation = str_replace(",(", "<br />(", $sRemarksLocation);



			$sSQL = "UPDATE tbl_attendance SET time_in='$sTimeIn', time_out='$sTimeOut', remarks='$Remarks', date_time=NOW( ) WHERE `date`='$Date' AND user_id='$UserId' AND `entry`='$Entry'";

			if ($objDb->execute($sSQL) == true)
				print ("OK|-|$Id|-|<div>The selected Attendance Record has been Updated successfully.</div>|-|".formatTime($sTimeIn)."|-|".formatTime($sTimeOut)."|-|$sRemarksLocation");

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