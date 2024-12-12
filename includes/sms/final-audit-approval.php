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

	if (!$objSms)
		$objSms = new Sms( );


	$sSQL = "SELECT user_id, alert_types
	         FROM tbl_notifications
	         WHERE trigger_id='3' AND (vendor_id='0' OR vendor_id='$iVendorId') AND (brand_id='0' OR brand_id='$iBrandId') AND status='A' AND user_id IN (SELECT id FROM tbl_users WHERE status='A' AND email_alerts='Y')";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iUserId     = $objDb->getField($i, 0);
		$iAlertTypes = @explode(",", $objDb->getField($i, 1));


		$sSQL = "SELECT name, email, mobile FROM tbl_users WHERE id='$iUserId'";
		$objDb2->query($sSQL);

		$sName   = $objDb2->getField(0, 'name');
		$sEmail  = $objDb2->getField(0, 'email');
		$sMobile = $objDb2->getField(0, 'mobile');


		$sSubject = "*** Final Audit ***";
		$sBody    = "Audit Code: {$sAuditCode}";

		if ($sVendor != "")
			$sBody .= "\r\nVendor: $sVendor";

		if ($sBrand != "")
			$sBody .= "\r\nBrand: $sBrand";

		if ($sStyle != "")
			$sBody .= "\r\nStyle: $sStyle";

		if ($sPO != "")
			$sBody .= "\r\nPO: $sPO";

		if ($iShipQty > 0)
			$sBody .= "\r\nShip Qty: $iShipQty";


		// SMS
		if (@in_array(1, $iAlertTypes))
			$objSms->send($sMobile, $sName, $sBody, $sSubject);

		// Email
		if (@in_array(2, $iAlertTypes))
		{
			$objEmail = new PHPMailer( );
			$objEmail->IsSMTP( );
			$objEmail->SMTPAuth = true;
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
?>