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
	$objDb3      = new Database( );

	$objSms = new Sms( );


	$Id      = IO::intValue('Id');
	$Referer = urldecode(IO::strValue('Referer'));


	$sSQL  = "SELECT audit_code, vendor_id, po_id, style_id, audit_stage, audit_result, ship_qty, dhu, (SELECT line FROM tbl_lines WHERE id=tbl_qa_reports.line_id) AS _Line FROM tbl_qa_reports WHERE id='$Id'";
	$objDb->query($sSQL);

	$sAuditCode   = $objDb->getField(0, "audit_code");
	$iVendorId    = $objDb->getField(0, "vendor_id");
	$iPoId        = $objDb->getField(0, "po_id");
	$iStyleId     = $objDb->getField(0, "style_id");
	$sLine        = $objDb->getField(0, "_Line");
	$sAuditStage  = $objDb->getField(0, "audit_stage");
	$sAuditResult = $objDb->getField(0, "audit_result");
	$fDhu         = $objDb->getField(0, "dhu");
	$iShipQty     = $objDb->getField(0, "ship_qty");


	$iAuditId = $Id;
	$sPO      = getDbValue("order_no", "tbl_po", "id='$iPoId'");


	$sSQL = "SELECT style, sub_brand_id FROM tbl_styles WHERE id='$iStyleId'";
	$objDb->query($sSQL);

	$sStyle   = $objDb->getField(0, 0);
	$iBrandId = (int)$objDb->getField(0, 1);


	$sSQL = "SELECT brand FROM tbl_brands WHERE id='$iBrandId'";
	$objDb->query($sSQL);

	$sBrand = $objDb->getField(0, 0);


	$sSQL = "SELECT vendor FROM tbl_vendors WHERE id='$iVendorId'";
	$objDb->query($sSQL);

	$sVendor = $objDb->getField(0, 0);



	// Alter to Azfar
	if (@in_array($iBrandId, array(67, 75, 242, 244, 260)))
	{
		$sResult  = (($sAuditResult == "A" || $sAuditResult == "B" || $sAuditResult == "P") ? "Pass" : (($sAuditResult == "F" || $sAuditResult == "C") ? "Fail" : "Hold"));
		$sMessage = "A Report for Style No: {$sStyle}, PO No: {$sPO} at {$sVendor} against {$sBrand} has just been published on the Customer Portal. (Audit Result: {$sResult})";


		if ($iBrandId == 242 || $iBrandId == 244)
		{
//			$objSms->send("+923214300069", "Omer Rauf", "", $sMessage);
			$objSms->send("+923008445912", "Azfar Hasan", "", $sMessage);
		}

		if ((($sAuditResult == "F" || $sAuditResult == "C") && ($sAuditStage == "F" || $fDhu > 10)) && ($iBrandId == 67 || $iBrandId == 75))
		{
			$objSms->send("+919810228115", "Franklin Benjamin", "", $sSmsMessage);
			$objSms->send("+919873677723", "Avneesh Kumar", "", $sSmsMessage);
		}

		else if ($iBrandId == 242)
		{
			$objSms->send("+491732370864", "Adrian", "", $sMessage);
			$objSms->send("+491726206900", "Rainer", "", $sMessage);
		}

		else if ($iBrandId == 244)
			$objSms->send("+491728876474", "Monika", "", $sMessage);

		else if ($iBrandId == 260)
		{
//			$objSms->send("+923214300069", "Omer Rauf", "", $sMessage);
			$objSms->send("+94773827977", "Sanjaya Anuk Fernando", "", $sMessage);
			$objSms->send("+94773156490", "Udaya Kaviratne", "", $sMessage);
			$objSms->send("+94773902956", "Manoj Balachandran", "", $sMessage);
		}


		$sMessage = ("Click the below link to download the Audit Report: http://portal.3-tree.com/get-qa-report.php?Id=".@md5($sAuditCode)."&AuditCode={$sAuditCode}");

		if ($iBrandId == 242 || $iBrandId == 244)
		{
			$objSms->send("+923214300069", "Omer Rauf", "", $sMessage);
			$objSms->send("+923008445912", "Azfar Hasan", "", $sMessage);
		}

		if ((($sAuditResult == "F" || $sAuditResult == "C") && ($sAuditStage == "F" || $fDhu > 10)) && ($iBrandId == 67 || $iBrandId == 75))
		{
			$objSms->send("+919810228115", "Franklin Benjamin", "", $sSmsMessage);
			$objSms->send("+919873677723", "Avneesh Kumar", "", $sSmsMessage);
		}

		else if ($iBrandId == 242)
		{
			$objSms->send("+491732370864", "Adrian", "", $sMessage);
			$objSms->send("+491726206900", "Rainer", "", $sMessage);
		}

		else if ($iBrandId == 244)
			$objSms->send("+491728876474", "Monika", "", $sMessage);

		else if ($iBrandId == 260)
		{
//			$objSms->send("+923214300069", "Omer Rauf", "", $sMessage);
			$objSms->send("+94773827977", "Sanjaya Anuk Fernando", "", $sMessage);
			$objSms->send("+94773156490", "Udaya Kaviratne", "", $sMessage);
			$objSms->send("+94773902956", "Manoj Balachandran", "", $sMessage);

/*
			$sStage = getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'");
			$sLink  = ("http://portal.3-tree.com/get-qa-report.php?Id=".@md5($sAuditCode)."&AuditCode={$sAuditCode}");

			$sBody = "Dear User,<br /><br />
					  Please <a href='{$sLink}'>click here</a> for a PDF version of the Audit Report for:<br />
					  Audit Code: {$sAuditCode}<br />
					  PO Number: {$sPO}<br />
					  Vendor: {$sVendor}<br />
					  Brand: {$sBrand}<br />
					  Style: {$sStyle}<br />
					  Line: {$sLine}<br />
					  Audit Stage: {$sStage}<br />
					  Result: <b>{$sResult}</b><br />
					  <br />
					  <br />
					  Triple Tree Customer Portal";


			$objEmail = new PHPMailer( );

			$objEmail->Subject  = "Triple Tree Audit (Purchase Order No: {$sPO} Style No: {$sStyle}) Conducted for '{$sBrand}' at '{$sVendor}' - Result - '{$sResult}'";
			$objEmail->MsgHTML($sBody);

//			$objEmail->AddAddress("omer@3-tree.com", "Omer Rauf");
			$objEmail->AddAddress("sanfernando@mgfsourcing.com", "Sanjaya Anuk Fernando");
			$objEmail->AddAddress("ukaviratne@mgfsourcing.com", "Udaya Kaviratne");
			$objEmail->AddAddress("Balachandran@mgfsourcing.com", "Manoj Balachandran");
			$objEmail->addBCC("darho@mgfsourcing.com", "Ho, Darren");
			$objEmail->addBCC("STan@MGFSourcing.com", "Tan, Samuel");
			$objEmail->addBCC("switharana@mgfsourcing.com", "Shehan Witharana");
			$objEmail->addBCC("ASumanadasa@MGFSourcing.com", "Aruna Sumanadasa");
//			$objEmail->addBCC("rstephen@mgfsourcing.com", "Stephen Ranga");
			$objEmail->addBCC("Jjeyaram@mgfsourcing.com", "Jeyaram, Jeyadinesh");

			$objEmail->Send( );
*/
		}
	}



	// DR Above Target Line
	@include($sBaseDir."includes/sms/dr-above-target-line.php");


	// Continuity Defect
	@include($sBaseDir."includes/sms/continuity-defect.php");


	if ($sAuditStage == "F")
	{
		// Final Audit Conducted
		@include($sBaseDir."includes/sms/final-audit.php");


		// Final Audit Approval
		if ($sAuditResult == "P")
			@include($sBaseDir."includes/sms/final-audit-approval.php");
	}
	
	// Inline Audit Alerts
	if ($sAuditStage == "I" || $sAuditStage == "IL" || $sAuditStage == "B")
		@include($sBaseDir."includes/sms/inline-audit-alert.php");




	redirect($Referer, "QA_REPORT_NOTIFICATIONS_SENT");


	$objSms->close( );

	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>