<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  MATRIX Customer Portal                                                                   **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.apparelco.com                                                              **
	**                                                                                           **
	**  Copyright 2008-15 (C) Matrix Sourcing                                                    **
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
	$Name     = IO::strValue("Name");
	$Code     = IO::strValue("Code");
	$Auditors = @explode(",", IO::strValue("Auditors"));
	$sError   = "";

	$sSQL = "SELECT id FROM tbl_auditor_groups WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Group ID. Please select the proper Group to Edit.\n";
		exit( );
	}

	if ($Name == "")
		$sError .= "- Invalid Group Name\n";

	if ($Code == "")
		$sError .= "- Invalid Group Code\n";

	if (count($Auditors) < 2)
		$sError .= "- Invalid Group Members\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}



	$sSQL  = "SELECT * FROM tbl_auditor_groups WHERE (name LIKE '$Name' OR code LIKE '$Code') AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
			$sSQL = ("UPDATE tbl_auditor_groups SET name='$Name', code='$Code', users='".@implode(",", $Auditors)."' WHERE id='$Id'");

			if ($objDb->execute($sSQL) == true)
			{
				$sAuditors     = "";
				$sAuditorsList = getList("tbl_users", "id", "name", "status='A' AND auditor='Y'");

				for ($i = 0; $i < count($Auditors); $i ++)
					$sAuditors .= ($sAuditorsList[$Auditors[$i]]."<br />");

				print "OK|-|$Id|-|<div>The selected Auditor Group has been Updated successfully.</div>|-|$Name|-|$Code|-|$sAuditors";
			}

			else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified Auditor Group already exists in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>