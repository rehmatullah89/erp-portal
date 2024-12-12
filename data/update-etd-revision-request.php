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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Id          = IO::intValue("Id");
	$PoId        = IO::intValue("PoId");
	$UserId      = IO::intValue("UserId");
	$Status      = IO::strValue("Status");
	$OriginalEtd = IO::strValue("OriginalEtd");
	$RevisedEtd  = IO::strValue("RevisedEtd");


	$objDb->execute("BEGIN");

	$sSQL  = "UPDATE tbl_etd_revision_requests SET status='$Status' WHERE id='$Id'";
	$bFlag = $objDb->execute($sSQL);

	if ($bFlag == true && $Status == "A")
	{
		$sSQL  = "UPDATE tbl_po_colors SET etd_required='$RevisedEtd' WHERE po_id='$PoId'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true && $Status == "A")
	{
		$sSQL  = "UPDATE tbl_po SET shipping_dates='$RevisedEtd', modified=NOW( ), modified_by='{$_SESSION['UserId']}' WHERE id='$PoId'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true && $Status == "A")
	{
		$iId = getNextId("tbl_etd_revisions");

		$sSQL  = "INSERT INTO tbl_etd_revisions (id, po_id, original, revised, user_id, date_time) VALUES ('$iId', '$PoId', '$OriginalEtd', '$RevisedEtd', '{$_SESSION['UserId']}', NOW( ))";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true && $Status == 'A')
	{
		$iLogId = getNextId("tbl_po_log");

		$sSQL  = "INSERT INTO tbl_po_log (id, po_id, user_id, date_time, reason) VALUES ('$iLogId', '$PoId', '{$_SESSION['UserId']}', NOW( ), 'ETD Revision')";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true && $Status == 'A')
	{
		$sSQL = "SELECT id, etd_required FROM tbl_po_colors WHERE po_id='$PoId'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iColorId     = $objDb->getField($i, "id");
			$sEtdRequired = $objDb->getField($i, "etd_required");

			$iOrderQty  = getDbValue("COALESCE(SUM(quantity), 0)", "tbl_po_quantities", "po_id='$PoId' AND color_id='$iColorId'");
			$iOnTimeQty = getDbValue("COALESCE(SUM(psq.quantity), 0)", "tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq", "psd.po_id=psq.po_id AND psd.po_id='$PoId' AND psd.id=psq.ship_id AND psq.color_id='$iColorId' AND psd.handover_to_forwarder != '0000-00-00' AND NOT ISNULL(psd.handover_to_forwarder) AND psd.handover_to_forwarder<='$sEtdRequired'");

			$iOnTimeQty = (($iOnTimeQty > 0) ? $iOrderQty : 0);


			$sSQL  = "UPDATE tbl_po_colors SET order_qty='$iOrderQty', ontime_qty='$iOnTimeQty' WHERE id='$iColorId'";
			$bFlag = $objDb2->execute($sSQL);

			if ($bFlag == false)
				break;
		}
	}

	if ($bFlag == true)
	{
		$sSQL = "SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id='$PoId'";
		$objDb->query($sSQL);

		$sOrderNo = $objDb->getField(0, 0);


		$sSQL = "SELECT name, email FROM tbl_users WHERE id='$UserId' AND status='A' AND email_alerts='Y'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
		{
			$sName  = $objDb->getField(0, 0);
			$sEmail = $objDb->getField(0, 1);


			$sBody = @file_get_contents("../emails/etd-revision.txt");

			$sBody = @str_replace("[Name]", $sName, $sBody);
			$sBody = @str_replace("[Email]", $sEmail, $sBody);
			$sBody = @str_replace("[OrderNo]", $sOrderNo, $sBody);
			$sBody = @str_replace("[EtdRequired]", formatDate($OriginalEtd), $sBody);
			$sBody = @str_replace("[RevisedEtd]", formatDate($RevisedEtd), $sBody);
			$sBody = @str_replace("[Status]", (($Status == 'A') ? '<b>Approved</b>' : '<b style="color:#ff0000;">Rejected</b>'), $sBody);
			$sBody = @str_replace("[SiteTitle]", SITE_TITLE, $sBody);
			$sBody = @str_replace("[SiteUrl]", SITE_URL, $sBody);


			$objEmail = new PHPMailer( );

//			$objEmail->From     = $_SESSION['Email'];
//			$objEmail->FromName = $_SESSION['Name'];
			$objEmail->Subject  = "ETD Revision of PO # {$sOrderNo}";

			$objEmail->MsgHTML($sBody);
			$objEmail->AddAddress($sEmail, $sName);
			$objEmail->Send( );
		}


		$objDb->execute("COMMIT");

		$_SESSION['Flag'] = "ETD_REVISION_REQUEST_UPDATED";
	}

	else
	{
		$objDb->execute("ROLLBACK");

		$_SESSION['Flag'] = "DB_ERROR";
	}

	header("Location: {$_SERVER['HTTP_REFERER']}");

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>