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
	$Department = IO::strValue("Department");
	$Code       = IO::strValue("Code");
	$Brands     = @implode(",", IO::getArray("Brands"));
	$Users      = @implode(",", IO::getArray("Users"));
	$sError     = "";

	$sSQL = "SELECT id FROM tbl_departments WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Department ID. Please select the proper Department to Edit.\n";
		exit( );
	}

	if ($Department == "")
		$sError .= "- Invalid Department\n";

	if ($Code == "")
		$sError .= "- Invalid Code\n";

	if ($Brands != "")
	{
		$sSQL = "SELECT brand FROM tbl_brands WHERE id IN ($Brands) ORDER BY brand";
		$objDb->query($sSQL);

		$iCount  = $objDb->getCount( );
		$sBrands = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sBrands .= ("- ".$objDb->getField($i, 0)."<br />");
	}

	if ($Users != "")
	{
		$sSQL = "SELECT name FROM tbl_users WHERE id IN ($Users) ORDER BY name";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sUsers = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sUsers .= ("- ".$objDb->getField($i, 0)."<br />");
	}

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}


	$sSQL  = "SELECT * FROM tbl_departments WHERE (department LIKE '$Department' OR `code` LIKE '$Code') AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
			$sSQL = "UPDATE tbl_departments SET department='$Department', `code`='$Code', brands='$Brands', quality_managers='$Users' WHERE id='$Id'";

			if ($objDb->execute($sSQL) == true)
				print "OK|-|$Id|-|<div>The selected Department has been Updated successfully.</div>|-|$Department|-|$Code|-|$sBrands|-|$sUsers";

			else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified Department/Code already exists in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>