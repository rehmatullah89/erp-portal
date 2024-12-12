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

	$Id       = IO::intValue("Id");
	$Employee = @implode(",", IO::getArray("Employee"));
	$Title    = IO::strValue("Title");
	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");
	$Details  = IO::strValue("Details");
	$Private  = IO::strValue("Private");
	$sError   = "";
	$sUsers   = "";

	$sSQL = "SELECT id FROM tbl_calendar WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Calendar Entry ID. Please select the proper Calendar Entry to Edit.\n";
		exit( );
	}

	if ($Title == "")
		$sError .= "- Invalid Entry Title\n";

	if ($FromDate == "")
		$sError .= "- Invalid From Date\n";

	if ($ToDate == "")
		$sError .= "- Invalid To Date\n";

	if ($Employee != "")
	{
		$sSQL = "SELECT CONCAT(name, ' (', COALESCE((SELECT designation FROM tbl_designations WHERE id=tbl_users.designation_id), 'N/A'), ')') FROM tbl_users WHERE id IN ($Employee)";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sUsers .= (", ".$objDb->getField($i, 0));

		if ($sUsers != "")
			$sUsers = substr($sUsers, 2);

		else
			$sError .= "- Invalid Employee\n";
	}

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}


	$sDates = (formatDate($FromDate)." <b>to</b> ".formatDate($ToDate));


	$sSQL = "UPDATE tbl_calendar SET users='$Employee', title='$Title', from_date='$FromDate', to_date='$ToDate', details='$Details', private='$Private', modified=NOW( ), modified_by='{$_SESSION['UserId']}' WHERE id='$Id'";

	if ($objDb->execute($sSQL) == true)
		print ("OK|-|$Id|-|<div>The selected Calendar Entry has been Updated successfully.</div>|-|$sUsers|-|$Title|-|$sDates");

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>