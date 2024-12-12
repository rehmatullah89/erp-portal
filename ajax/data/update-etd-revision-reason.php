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

	$Id     = IO::intValue("Id");
	$Code   = IO::strValue("Code");
	$Reason = IO::strValue("Reason");
	$Parent = IO::intValue("Parent");
	$sError = "";

	$sSQL = "SELECT id FROM tbl_etd_revision_reasons WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Reason ID. Please select the proper Reason to Edit.\n";
		exit( );
	}

	if ($Parent > 0)
	{
		$sSQL = "SELECT parent_id, code, reason FROM tbl_etd_revision_reasons WHERE id='$Parent'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Parent\n";

		else
		{
			$iParent = $objDb->getField(0, 0);
			$sCode   = $objDb->getField(0, 1);
			$sParent = $objDb->getField(0, 2);

			if ($iParent > 0)
			{
				$sSQL = "SELECT reason, code FROM tbl_etd_revision_reasons WHERE id='$iParent'";
				$objDb->query($sSQL);

				$sParent = ($objDb->getField(0, 0).@utf8_encode(' » ').$sParent);
				$sCode   = ($objDb->getField(0, 1).$sCode);
			}
		}
	}

	if ($Reason == "")
		$sError .= "- Invalid Reason\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}

	$sCode .= $Code;


	$sSQL = "SELECT * FROM tbl_etd_revision_reasons WHERE code LIKE '$Code' AND parent_id='$Parent' AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
			$sSQL = "UPDATE tbl_etd_revision_reasons SET parent_id='$Parent', code='$Code', reason='$Reason' WHERE id='$Id'";

			if ($objDb->execute($sSQL) == true)
				print "OK|-|$Id|-|<div>The selected Reason has been Updated successfully.</div>|-|$sCode|-|$Reason|-|$sParent";

			else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified Reason Code (with same Parent) already exists in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>