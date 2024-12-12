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
	$RevisedEtd   = IO::strValue("RevisedEtd");
	$Reason       = IO::intValue("Reason");
	$Merchandiser = IO::intValue("Merchandiser");
	$sError       = "";

	$sSQL = "SELECT date_time FROM tbl_etd_revision_requests WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Request ID. Please select the proper ETD Request to Edit.\n";
		exit( );
	}

	else
		$sDateTime = $objDb->getField(0, 0);

	if ($RevisedEtd == "")
		$sError .= "- Invalid Revised ETD\n";

	if ($Merchandiser > 0)
	{
		$sSQL = "SELECT name FROM tbl_users WHERE id='$Merchandiser'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Merchandiser\n";

		else
			$sMerchandiser = $objDb->getField(0, 0);
	}

	if ($Reason > 0)
	{
		$sSQL = "SELECT parent_id, code, reason FROM tbl_etd_revision_reasons WHERE id='$Reason'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Reason\n";

		else
		{
			$iParentId = $objDb->getField(0, 'parent_id');
			$sCode     = $objDb->getField(0, 'code');
			$sReason   = $objDb->getField(0, 'reason');


			$sSQL = "SELECT parent_id, code, reason FROM tbl_etd_revision_reasons WHERE id='$iParentId'";
			$objDb->query($sSQL);

			$iParentId     = $objDb->getField(0, 'parent_id');
			$sParentCode   = $objDb->getField(0, 'code');
			$sParentReason = $objDb->getField(0, 'reason');


			$sSQL = "SELECT code, reason FROM tbl_etd_revision_reasons WHERE id='$iParentId'";
			$objDb->query($sSQL);

			$sSuperParentCode   = $objDb->getField(0, 'code');
			$sSuperParentReason = $objDb->getField(0, 'reason');


			$sReasonTip  = "<b>Merchandiser</b><br />";
			$sReasonTip .= ($sMerchandiser."<br /><br />");
			$sReasonTip .= "<b>Reason</b><br />";
			$sReasonTip .= ($sSuperParentCode.$sParentCode.$sCode." - ".$sSuperParentReason.@utf8_encode(" <b>»</b> ").$sParentReason.@utf8_encode(" <b>»</b> ").$sReason."<br /><br />");
			$sReasonTip .= "<b>Request made on:</b><br />";
			$sReasonTip .= formatDate($sDateTime);
		}
	}

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}


	$sSQL = "UPDATE tbl_etd_revision_requests SET revised_etd='$RevisedEtd', reason_id='$Reason', user_id='$Merchandiser' WHERE id='$Id'";

	if ($objDb->execute($sSQL) == true)
		print ("OK|-|$Id|-|<div>The selected ETD Revision Request has been Updated successfully.</div>|-|".formatDate($RevisedEtd)."|-|$sReasonTip");

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>