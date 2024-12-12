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

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$sSQL  = ("SELECT * FROM tbl_yarn_inquiries WHERE date LIKE '".IO::strValue("Date")."' AND style_id='".IO::intValue("Style")."' AND quantity='".IO::strValue("Quantity")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$objDb->execute("BEGIN");


		$iId = getNextId("tbl_yarn_inquiries");

		$sSQL = ("INSERT INTO tbl_yarn_inquiries (id, date, style_id, quantity, types, created, created_by, modified, modified_by)
		                                  VALUES ('$iId', '".IO::strValue("Date")."', '".IO::intValue("Style")."', '".IO::strValue("Quantity")."', '".@implode(",", IO::getArray("Types"))."', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')");
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
		{
			$iBrand   = getDbValue("sub_brand_id", "tbl_styles", "id='".IO::intValue("Style")."'");
			$sVendors = getDbValue("vendors", "tbl_brands", "id='$iBrand'");
			$iVendors = @explode(",", $sVendors);

			foreach ($iVendors as $iVendor)
			{
				$sSQL = ("INSERT INTO tbl_yarn_inquiry_details (inquiry_id, vendor_id, pxp_price, uxu_price, pxu_price, uxp_price, response_time, shipment_date)
												        VALUES ('$iId', '$iVendor', '0', '0', '0', '0', '', '')");
				$bFlag = $objDb->execute($sSQL);

				if ($bFlag == false)
					break;
			}
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			redirect($_SERVER['HTTP_REFERER'], "INQUIRY_ADDED");
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION['Flag'] = "DB_ERROR";
		}
	}

	else
		$_SESSION['Flag'] = "INQUIRY_EXISTS";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>