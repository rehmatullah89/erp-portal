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

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$objSms = new Sms( );


	$User      = IO::strValue('User');
	$AuditCode = IO::strValue("AuditCode");

	$iAuditCode = intval(substr($AuditCode, 1));


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "" || $AuditCode == "" || $iAuditCode == 0 || $AuditCode{0} != "S")
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, status FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else
		{
			$iUser = $objDb->getField(0, "id");
			$sName = $objDb->getField(0, "name");


			$sSQL = "SELECT style_id, audit_stage, start_date_time, (TIME_TO_SEC(end_time) - TIME_TO_SEC(start_time)) AS _AuditTime,
							(SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _PO,
							(SELECT style FROM tbl_styles WHERE id=tbl_qa_reports.style_id) AS _Style,
							(SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor
					 FROM tbl_qa_reports
					 WHERE id='$iAuditCode'";
			$objDb->query($sSQL);

			$iStyle         = $objDb->getField(0, "style_id");
			$sVendor        = $objDb->getField(0, "_Vendor");
			$sPO            = $objDb->getField(0, "_PO");
			$sStyle         = $objDb->getField(0, "_Style");
			$sAuditStage    = $objDb->getField(0, "audit_stage");
			$iAuditTime     = $objDb->getField(0, "_AuditTime");
			$sStartDateTime = $objDb->getField(0, 'start_date_time');


			$sAuditStage = getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'");

			if ($sStartDateTime == "" || $sStartDateTime == "0000-00-00 00:00:00" || strtotime($sStartDateTime) < strtotime("2013-01-01 00:00:00"))
			{
				$sSQL = "UPDATE tbl_qa_reports SET start_date_time=NOW( ), start_time=CURTIME( ), end_time=SEC_TO_TIME(TIME_TO_SEC(CURTIME( )) + '$iAuditTime'), audit_mode='2' WHERE id='$iAuditCode'";
				$objDb->execute($sSQL, true, $iUser, $sName);


				// Alter to Azfar
				$iBrand = getDbValue("sub_brand_id", "tbl_styles", "id='$iStyle'");

				if (@in_array($iBrand, array(67, 75, 242, 244, 260)))
				{
					$sBrand   = getDbValue("brand", "tbl_brands", "id='$iBrand'");
					$sMessage = "An Audit for Style No: {$sStyle}, PO No: {$sPO} has just begun at {$sVendor} against {$sBrand}.";

					if ($iBrand == 242 || $iBrand == 244)
					{
//						$objSms->send("+923214300069", "Omer Rauf", "", $sMessage);
						$objSms->send("+923008445912", "Azfar Hasan", "", $sMessage);
					}

					if ($iBrand == 242)
					{
						$objSms->send("+491732370864", "Adrian", "", $sMessage);
						$objSms->send("+491726206900", "Rainer", "", $sMessage);
					}

					else if ($iBrand == 244)
						$objSms->send("+491728876474", "Monika", "", $sMessage);

					else if ($iBrand == 260)
					{
//						$objSms->send("+923214300069", "Omer Rauf", "", $sMessage);
						$objSms->send("+94773827977", "Sanjaya Anuk Fernando", "", $sMessage);
						$objSms->send("+94773156490", "Udaya Kaviratne", "", $sMessage);
						$objSms->send("+94773902956", "Manoj Balachandran", "", $sMessage);
					}
/*
					else if ($iBrand == 67 || $iBrand == 75)
					{
						$objSms->send("+919810228115", "Franklin Benjamin", "", $sMessage);
						$objSms->send("+919873677723", "Avneesh Kumar", "", $sMessage);
					}
*/



					$sMessage = "Click the below link to see the Live Audit Progress: http://portal.3-tree.com/dashboard/progress.php?AuditCode={$AuditCode}";

					if ($iBrand == 242 || $iBrand == 244)
					{
//						$objSms->send("+923214300069", "Omer Rauf", "", $sMessage);
						$objSms->send("+923008445912", "Azfar Hasan", "", $sMessage);
					}

					if ($iBrand == 242)
					{
						$objSms->send("+491732370864", "Adrian", "", $sMessage);
						$objSms->send("+491726206900", "Rainer", "", $sMessage);
					}

					else if ($iBrand == 244)
						$objSms->send("+491728876474", "Monika", "", $sMessage);

					else if ($iBrand == 260)
					{
//						$objSms->send("+923214300069", "Omer Rauf", "", $sMessage);
						$objSms->send("+94773827977", "Sanjaya Anuk Fernando", "", $sMessage);
						$objSms->send("+94773156490", "Udaya Kaviratne", "", $sMessage);
						$objSms->send("+94773902956", "Manoj Balachandran", "", $sMessage);
					}
/*
					else if ($iBrand == 67 || $iBrand == 75)
					{
						$objSms->send("+919810228115", "Franklin Benjamin", "", $sMessage);
						$objSms->send("+919873677723", "Avneesh Kumar", "", $sMessage);
					}
*/
				}


				$sSubject = "*** Audit Start Alert ***";
				$sBody    = "Audit Code: $AuditCode\r\nVendor: $sVendor";

				$sBody .= "\r\nPO: $sPO";
				$sBody .= "\r\nStyle: $sStyle";
				$sBody .= "\r\nAudit Stage: $sAuditStage";
				$sBody .= ("\r\nLive View: ".SITE_URL."dashboard/progress.php?AuditCode={$AuditCode}");


				$sSQL = "SELECT user_id, alerts FROM tbl_audit_subscriptions WHERE audit_id='$iAuditCode'";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
				{
					$iUserId     = $objDb->getField($i, 0);
					$sAlertTypes = @explode(",", $objDb->getField($i, 1));


					$sSQL = "SELECT name, email, mobile FROM tbl_users WHERE id='$iUserId'";
					$objDb2->query($sSQL);

					$sName   = $objDb2->getField(0, 'name');
					$sEmail  = $objDb2->getField(0, 'email');
					$sMobile = $objDb2->getField(0, 'mobile');


					// SMS
					if (@in_array("SMS", $sAlertTypes))
						$objSms->send($sMobile, $sName, $sBody, $sSubject);

					// Email
					if (@in_array("Email", $sAlertTypes))
					{
						$objEmail = new PHPMailer( );
						$objEmail->IsHTML(false);
						$objEmail->Subject = $sSubject;
						$objEmail->Body = $sBody;
						$objEmail->AddAddress($sEmail, $sName);
						$objEmail->Send( );
					}
				}


				$sSQL = "DELETE FROM tbl_audit_subscriptions WHERE audit_id='$iAuditCode'";
				$objDb->execute($sSQL, true, $iUser, $sName);
			}


			$aResponse['Status']  = "OK";
			$aResponse['Message'] = "OK";
		}
	}


	print @json_encode($aResponse);



	$objSms->close( );

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>