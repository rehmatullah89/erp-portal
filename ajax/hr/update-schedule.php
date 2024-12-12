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

	$Id        = IO::intValue("Id");
	$Location  = IO::intValue("Location");
	$FromDate  = IO::strValue("FromDate");
	$ToDate    = IO::strValue("ToDate");
	$StartTime = (IO::strValue("StartHour").":".IO::strValue("StartMinutes").":00");
	$EndTime   = (IO::strValue("EndHour").":".IO::strValue("EndMinutes").":00");
	$Details   = IO::strValue("Details");
	$sError    = "";

	$sSQL = "SELECT id FROM tbl_user_schedule WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Schedule ID. Please select the proper User Schedule to Edit.\n";
		exit( );
	}

	if ($Location > 0)
	{
		$sSQL = "SELECT location FROM tbl_visit_locations WHERE id='$Location'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Location\n";

		else
			$sLocation = $objDb->getField(0, 0);
	}

	if (strtotime("{$ToDate} {$EndTime}") <= strtotime("{$FromDate} {$StartTime}"))
	{
		print "ERROR|-|Invalid End Date/Time, End Date/Time should be greater than the Start Date/Time.|-|{$Id}";
		exit( );
	}

	if ((int)getDbValue("COUNT(1)", "tbl_qa_reports", "user_id='{$_SESSION['UserId']}' AND (audit_date BETWEEN '$FromDate' AND '$ToDate') AND (('$StartTime' BETWEEN start_time AND end_time) OR ('$EndTime' BETWEEN start_time AND end_time))") > 0)
	{
		print "ERROR|-|Invalid Time, Start/End Time is overlapping with an Audit Entry.|-|{$Id}";
		exit( );
	}

	if ((int)getDbValue("COUNT(1)", "tbl_user_schedule", "id!='$Id' AND user_id='{$_SESSION['UserId']}' AND (('$FromDate' BETWEEN from_date AND to_date) OR ('$ToDate' BETWEEN from_date AND to_date)) AND (('$StartTime' BETWEEN start_time AND end_time) OR ('$EndTime' BETWEEN start_time AND end_time))") > 0)
	{
		print "ERROR|-|Invalid Time, Start/End Time is overlapping with another Schedule Entry.|-|{$Id}";
		exit( );
	}

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError|-|{$Id}";
		exit( );
	}

	$sSQL = "UPDATE tbl_user_schedule SET location_id='$Location', from_date='$FromDate', to_date='$ToDate', start_time='$StartTime', end_time='$EndTime', details='$Details' WHERE id='$Id'";

	if ($objDb->execute($sSQL) == true)
		print ("OK|-|$Id|-|<div>The selected Schedule Task has been Updated successfully.</div>|-|$sLocation|-|".nl2br($Details)."|-|".formatDate($FromDate)."<br />-<br />".formatDate($ToDate)."|-|".formatTime($StartTime)."<br />-<br />".formatTime($EndTime)."|-|");

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>