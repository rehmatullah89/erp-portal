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

	$objSms = new Sms( );


	$sSQL = "SELECT CONCAT(order_no, ' ', order_status) AS _Po, quantity, vendor_id, brand_id,
					(SELECT brand FROM tbl_brands WHERE id=tbl_po.brand_id) AS _Brand,
					(SELECT vendor FROM tbl_vendors WHERE id=tbl_po.vendor_id) AS _Vendor,
					(SELECT GROUP_CONCAT(style SEPARATOR ', ') FROM tbl_styles WHERE FIND_IN_SET(id, tbl_po.styles)) AS _Styles,
					(SELECT sub_season_id FROM tbl_styles WHERE id IN (tbl_po.styles) ORDER BY id LIMIT 1) AS _Season
			 FROM tbl_po
			 WHERE id='$iPoId'";
	$objDb->query($sSQL);

	$sOrderNo  = $objDb->getField(0, '_Po');
	$sBrand    = $objDb->getField(0, '_Brand');
	$iQuantity = $objDb->getField(0, 'quantity');
	$sVendor   = $objDb->getField(0, '_Vendor');
	$sStyles   = $objDb->getField(0, '_Styles');
	$sSeason   = $objDb->getField(0, '_Season');
	$iBrandId  = $objDb->getField(0, 'brand_id');
	$iVendorId = $objDb->getField(0, 'vendor_id');


	$sSubject = "*** New PO Alert (PO: {$sOrderNo}) ***";
	$sBody    = ("Order No: {$sOrderNo}");
	$sBody   .= ("\r\nVendor: {$sVendor}");
	$sBody   .= ("\r\nBrand: {$sBrand}");
	$sBody   .= ("\r\nStyle: {$sStyles}");
	$sBody   .= ("\r\nSeason: {$sSeason}");
	$sBody   .= ("\r\nQuantity: {$iQuantity}");



	$sSQL = "SELECT user_id, alert_types FROM tbl_notifications WHERE trigger_id='8' AND (vendor_id='0' OR vendor_id='$iVendorId') AND (brand_id='0' OR brand_id='$iBrandId') AND status='A' AND user_id IN (SELECT id FROM tbl_users WHERE status='A' AND email_alerts='Y')";
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


	$objSms->close( );
?>