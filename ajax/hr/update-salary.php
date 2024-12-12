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
	$Month    = IO::intValue("Month");
	$Year     = IO::intValue("Year");
	$Salary   = IO::floatValue("Salary");
	$Comments = IO::strValue("Comments");

	$sMonthsList = array('January','February','March','April','May','June','July','August','September','October','November','December');

	$sSQL  = "SELECT * FROM tbl_user_salaries WHERE id='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 1)
		{
			$sSQL = "UPDATE tbl_user_salaries SET month='$Month', year='$Year', salary='$Salary', comments='$Comments' WHERE id='$Id'";

			if ($objDb->execute($sSQL) == true)
				print ("OK|-|$Id|-|<div>The selected Salary Record has been Updated successfully.</div>|-|$Year|-|".$sMonthsList[$Month - 1]."|-|".formatNumber($Salary, false)."|-|$Comments");

			else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|Invalid Salary Record. Please selected a valid Salary Record to Modify.";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>