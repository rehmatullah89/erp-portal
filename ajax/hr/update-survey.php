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
	$Title     = IO::strValue("Title");
	$Purpose   = IO::strValue("Purpose");
	$Employees = IO::getArray("Employees");
	$FromDate  = IO::strValue("FromDate");
	$ToDate    = IO::strValue("ToDate");
	$sError    = "";

	$sSQL = "SELECT id FROM tbl_surveys WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Survey ID. Please select the proper Survey to Edit.\n";
		exit( );
	}

	if ($Title == "")
		$sError .= "- Invalid Title\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}



	$sSQL  = "SELECT * FROM tbl_surveys WHERE user_id='{$_SESSION['UserId']}' AND title LIKE '$Title' AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
			$sSQL = ("UPDATE tbl_surveys SET title='$Title', purpose='$Purpose', users='".@implode(",", $Employees)."', from_date='$FromDate', to_date='$ToDate' WHERE id='$Id'");

			if ($objDb->execute($sSQL) == true)
			{
				$sEmployees     = "";
				$sEmployeesList = getList("tbl_users", "id", "name", "(email LIKE '%@apparelco.com%' OR email LIKE '%@3-tree.com%') AND status='A'");

				for ($i = 0; $i < count($Employees); $i ++)
					$sEmployees .= ("- ".$sEmployeesList[$Employees[$i]]."<br />");

				print ("OK|-|$Id|-|<div>The selected Survey has been Updated successfully.</div>|-|$Title|-|".formatDate($FromDate)."|-|".formatDate($ToDate)."|-|$sEmployees");
			}

			else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified Survey already exists in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>