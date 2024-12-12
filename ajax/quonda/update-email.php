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

	$Id           = IO::intValue("Id");
	$Name         = IO::strValue("Name");
	$Email        = IO::strValue("Email");
        $Language     = IO::strValue("Language");
	$Vendors      = @implode(",", IO::getArray("Vendors"));
	$AuditStages  = @implode(",", IO::getArray("AuditStages"));
	$AuditResults = @implode(",", IO::getArray("AuditResults"));
	$sError       = "";

	$sSQL = "SELECT id FROM tbl_qa_emails WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid QA Email ID. Please select the proper QA Email to Edit.\n";
		exit( );
	}

	if ($Vendors == "")
		$sError .= "- Invalid Vendor\n";

	if ($Vendors != "")
	{
		$sVendors = "";

		$sSQL = "SELECT vendor FROM tbl_vendors WHERE FIND_IN_SET(id, '$Vendors')";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount == 0)
			$sError .= "- Invalid Vendor\n";

		else
		{
			for ($i = 0; $i < $iCount; $i ++)
			{
				$sVendors .= ("- ".$objDb->getField($i, 0)."<br />");

				if ($i == 15)
				{
					$sVendors .= "...";

					break;
				}
			}
		}
	}

	if ($AuditStages != "")
	{
		$sAuditStages = "";

		$sSQL = "SELECT stage FROM tbl_audit_stages WHERE FIND_IN_SET(code, '$AuditStages')";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount == 0)
			$sError .= "- Invalid Audit Stage\n";

		else
		{
			for ($i = 0; $i < $iCount; $i ++)
				$sAuditStages .= ("- ".$objDb->getField($i, 0)."<br />");
		}
	}

	if ($Name == "")
		$sError .= "- Invalid Name\n";

	if ($Email == "")
		$sError .= "- Invalid Email\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}


	$sSQL  = "SELECT * FROM tbl_qa_emails WHERE vendors='$Vendors' AND audit_stages='$AuditStages' AND email LIKE '$Email' AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
			$sSQL = "UPDATE tbl_qa_emails SET vendors='$Vendors', audit_stages='$AuditStages', audit_results='$AuditResults', name='$Name', email='$Email', language='$Language' WHERE id='$Id'";

			if ($objDb->execute($sSQL) == true)
				print ("OK|-|$Id|-|<div>The selected QA Email has been Updated successfully.</div>|-|$sVendors|-|$sAuditStages|-|$Name|-|$Email");

			else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified QA Email already exists in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>