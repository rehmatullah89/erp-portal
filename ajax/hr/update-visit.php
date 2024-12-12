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
	$TimeInHr   = IO::strValue("TimeInHr");
	$TimeInMin  = IO::strValue("TimeInMin");
	$TimeOutHr  = IO::strValue("TimeOutHr");
	$TimeOutMin = IO::strValue("TimeOutMin");
	$VisitType  = IO::strValue("VisitType");

	$sTimeIn  = ("$TimeInHr:$TimeInMin:00");
	$sTimeOut = ("$TimeOutHr:$TimeOutMin:00");

    $sDetail    = "";
    $sLocations = "";

    if ($VisitType == "Visit")
    {
		$sLocations = "67";

		for ($i = 1; $i <= 8; $i ++)
		{
			if (IO::intValue("Location".$i) > 0)
			{
				$sLocations .= (",".IO::intValue("Location".$i));

				$sSQL = ("SELECT location FROM tbl_visit_locations WHERE id='".IO::intValue("Location".$i)."'");
				$objDb->query($sSQL);

				$sDetail .= (", ".$objDb->getField(0, 0));
			}
		}

		$sDetail = substr($sDetail, 1);
	}

	else
		$sDetail = "Lunch";


	$sSQL  = "SELECT * FROM tbl_user_visits WHERE id='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 1)
		{
			$sSQL = "UPDATE tbl_user_visits SET time_in='$sTimeIn', time_out='$sTimeOut', type='$VisitType', locations='$sLocations', date_time=NOW( ) WHERE id='$Id'";

			if ($objDb->execute($sSQL) == true)
				print ("OK|-|$Id|-|<div>The selected Attendance Record has been Updated successfully.</div>|-|".formatTime($sTimeIn)."|-|".formatTime($sTimeOut)."|-|$sDetail");

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