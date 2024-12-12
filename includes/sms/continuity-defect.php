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

	$sStage = getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'");


	$sSQL = "SELECT code_id, SUM(defects) FROM tbl_qa_report_defects WHERE audit_id='$iAuditId' GROUP BY code_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iCodeId  = $objDb->getField($i, 0);
		$iDefects = $objDb->getField($i, 1);

		if ($iDefects >= 3)
		{
			$sSQL = "SELECT CONCAT(code, ' - ', defect) FROM tbl_defect_codes WHERE id='$iCodeId'";
			$objDb2->query($sSQL);

			$sDefect = $objDb2->getField(0, 0);


			$sSQL = "SELECT user_id, alert_types FROM tbl_notifications WHERE trigger_id='2' AND (vendor_id='0' OR vendor_id='$iVendorId') AND (brand_id='0' OR brand_id='$iBrandId') AND status='A' AND user_id IN (SELECT id FROM tbl_users WHERE status='A' AND email_alerts='Y')";
			$objDb2->query($sSQL);

			$iCount2 = $objDb2->getCount( );

			for ($j = 0; $j < $iCount2; $j ++)
			{
				$iUserId     = $objDb2->getField($j, 0);
				$iAlertTypes = @explode(",", $objDb2->getField($j, 1));


				$sSQL = "SELECT name, email, mobile FROM tbl_users WHERE id='$iUserId'";
				$objDb3->query($sSQL);

				$sName   = $objDb3->getField(0, 'name');
				$sEmail  = $objDb3->getField(0, 'email');
				$sMobile = $objDb3->getField(0, 'mobile');


				$sSubject = "*** Continuity Defect ***";
				$sBody    = "Audit Code: {$sAuditCode}\r\nVendor: {$sVendor}";

				if ($sBrand != "")
					$sBody .= "\r\nBrand: $sBrand";

				if ($sStyle != "")
					$sBody .= "\r\nStyle: $sStyle";

				if ($sLine != "")
					$sBody .= "\r\nLine: $sLine";

				if ($sStage != "")
					$sBody .= "\r\nAudit Stage: $sStage";

				$sBody .= "\r\nDefect: $sDefect ({$iDefects})";


				// SMS
				if (@in_array(1, $iAlertTypes))
					$objSms->send($sMobile, $sName, $sBody, $sSubject);

				// Email
				if (@in_array(2, $iAlertTypes))
				{
					$objEmail = new PHPMailer( );
//					$objEmail->IsSMTP( );
//					$objEmail->SMTPAuth = true;
					$objEmail->IsHTML(false);
					$objEmail->Subject = $sSubject;
					$objEmail->Body = $sBody;
					$objEmail->AddAddress($sEmail, $sName);
					$objEmail->Send( );
				}

				// Portal
				if (@in_array(3, $iAlertTypes))
				{
					$iId = getNextId("tbl_user_notifications");

					$sSQL = "INSERT INTO tbl_user_notifications (id, user_id, subject, body, date_time, status) VALUES ('$iId', '$iUserId', '$sSubject', '$sBody', NOW( ), 'N')";
					$objDb3->execute($sSQL);
				}
			}
		}
	}
?>