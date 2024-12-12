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

	$Id             = IO::intValue("Id");
	$Department     = IO::intValue("Department");
	$Designation    = IO::strValue("Designation");
	$ReportingTo    = IO::intValue("ReportingTo");
	$JobDescription = IO::strValue("JobDescription");
	$sError         = "";

	$sSQL = "SELECT id FROM tbl_designations WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Designation ID. Please select the proper Designation to Edit.\n";
		exit( );
	}

	if ($Designation == "")
		$sError .= "- Invalid Designation\n";

	if ($Department == 0)
		$sError .= "- Invalid Department\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}


	$sSQL  = "SELECT * FROM tbl_designations WHERE department_id='$Department' AND designation LIKE '$Designation' AND id!='$Id'";
	$objDb->query($sSQL);

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
			$sSQL = "UPDATE tbl_designations SET department_id='$Department', designation='$Designation', reporting_to='$ReportingTo', job_description='$JobDescription' WHERE id='$Id'";

			if ($objDb->execute($sSQL) == true)
			{
				$sDepartment  = getDbValue("department", "tbl_departments", "id='$Department'");
				$sReportingTo = getDbValue("designation", "tbl_designations", "id='$ReportingTo'");

				print "OK|-|$Id|-|<div>The selected Designation has been Updated successfully.</div>|-|$Designation|-|$sDepartment|-|$sReportingTo";
			}

			else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified Designation aready exists in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>