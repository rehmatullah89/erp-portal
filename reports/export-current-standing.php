<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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
	$objDb4      = new Database( );


	$Country  = IO::intValue("Country");
	$Vendor   = IO::intValue("Vendor");
	$Brand    = IO::intValue("Brand");
	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");
	$Filter   = IO::strValue("Filter");

	if ($FromDate == "" || $ToDate == "")
	{
		$FromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 0), date("Y")));
		$ToDate   = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") + 15), date("Y")));
	}

	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ({$_SESSION['Brands']})");

	$sVendorsSql = "";
	$sBrandsSql  = "";

	if ($Vendor > 0)
		$sVendorsSql = " AND id='$Vendor' ";

	else
		$sVendorsSql = " AND id IN ({$_SESSION['Vendors']}) ";


	if ($Brand > 0)
		$sBrandsSql = " WHERE id='$Brand' ";

	else
		$sBrandsSql = " WHERE id IN ({$_SESSION['Brands']}) ";


	$sExcelFile = ($sBaseDir."temp/current-standing.csv");
	$hFile      = @fopen($sExcelFile, 'w');


	if ($Country > 0)
		$sSQL = "SELECT id, country FROM tbl_countries WHERE matrix='Y' AND id='$Country' ORDER BY country";

	else
		$sSQL = "SELECT id, country FROM tbl_countries WHERE matrix='Y' ORDER BY country";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iCountryId = $objDb->getField($i, "id");
		$sCountry   = $objDb->getField($i, "country");


		$sSQL = "SELECT id FROM tbl_vendors WHERE country_id='$iCountryId' AND parent_id='0' AND sourcing='Y' $sVendorsSql";
		$objDb2->query($sSQL);

		$iCount2  = $objDb2->getCount( );
		$sVendors = "";

		for ($j = 0; $j < $iCount2; $j ++)
			$sVendors .= (",".$objDb2->getField($j, 0));

		if ($sVendors != "")
			$sVendors = substr($sVendors, 1);

		// Country Row
		@fwrite($hFile, ('"'.$sCountry.'","","","",""'."\n"));


		$bAnyRecord = false;

		$sSQL = "SELECT id, brand FROM tbl_brands $sBrandsSql ORDER BY brand";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iBrandId = $objDb2->getField($j, "id");
			$sBrand   = $objDb2->getField($j, "brand");


			$sSQL = "SELECT id FROM tbl_styles WHERE sub_brand_id='$iBrandId'";
			$objDb3->query($sSQL);

			$iCount3      = $objDb3->getCount( );
			$sBrandStyles = "";

			for ($k = 0; $k < $iCount3; $k ++)
				$sBrandStyles .= (",".$objDb3->getField($k, 0));

			if ($sBrandStyles != "")
				$sBrandStyles = substr($sBrandStyles, 1);


			$sSQL = "SELECT DISTINCT(po.id) FROM tbl_po po, tbl_po_colors pc WHERE po.id=pc.po_id AND po.vendor_id IN ($sVendors) AND pc.style_id IN ($sBrandStyles) AND (etd_required BETWEEN '$FromDate' AND '$ToDate') ORDER BY po.id";
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );

			if ($iCount3 == 0)
				continue;

			$bAnyRecord = true;


			@fwrite($hFile, ('"'.$sBrand.'","","","",""'."\n"));
			@fwrite($hFile, ('"PO","Final Audit Date","ETD Required","Order Qty"'."\n"));


			for ($k = 0; $k < $iCount3; $k ++)
			{
				$iPoId = $objDb3->getField($k, 0);


				$sOrderNo        = "";
				$sFinalAuditDate = "";
				$sEtdRequired    = "";

				$sSQL = "SELECT CONCAT(order_no, ' ', order_status), quantity FROM tbl_po WHERE id='$iPoId'";
				$objDb4->query($sSQL);

				$sOrderNo  = $objDb4->getField(0, 0);
				$iOrderQty = $objDb4->getField(0, 1);
				$sOrderQty = formatNumber($iOrderQty, false);


				$sSQL = "SELECT etd_required FROM tbl_po_colors WHERE po_id='$iPoId' ORDER BY etd_required LIMIT 1";
				$objDb4->query($sSQL);

				$sEtdRequired = $objDb4->getField(0, 0);


				$sSQL = "SELECT final_audit_date FROM tbl_vsr WHERE po_id='$iPoId'";
				$objDb4->query($sSQL);

				$sFinalAuditDate = $objDb4->getField(0, 0);
				$sFinalAuditDate = formatDate($sFinalAuditDate);


				if ($Filter == "WithFAD" && $sFinalAuditDate == "")
					continue;

				else if ($Filter == "WithoutFAD" && $sFinalAuditDate != "")
					continue;

/*
				$sSQL = "SELECT SUM(ship_qty) FROM tbl_qa_reports WHERE audit_stage='F' AND audit_result='P' AND (po_id='$iPoId' OR FIND_IN_SET('$iPoId', additional_pos))";
				$objDb4->query($sSQL);

				$iShipQty = $objDb4->getField(0, 0);
				$sShipQty = formatNumber($iShipQty, false);
*/

				@fwrite($hFile, ('"'.$sOrderNo.'","'.$sFinalAuditDate.'","'.$sEtdRequired.'","'.$sOrderQty.'"'."\n"));
			}

			@fwrite($hFile, ('"","","","",""'."\n"));
		}
	}


	@fclose($hFile);


	if ($Country > 0 && $Brand > 0)
	{
		$sSQL = "SELECT manager FROM tbl_brands WHERE id='$Brand'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
		{
			$iManager = $objDb->getField(0, 0);


			$sSQL = "SELECT name, email FROM tbl_users WHERE id='$iManager'";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 1)
			{
				$sName    = $objDb->getField(0, 0);
				$sEmail   = $objDb->getField(0, 1);
				$sSubject = "";


				$sBody = @file_get_contents("../emails/current-standing.txt");
				$sBody = @str_replace("[Name]", $sName, $sBody);
				$sBody = @str_replace("[Email]", $sEmail, $sBody);
				$sBody = @str_replace("[SiteTitle]", SITE_TITLE, $sBody);
				$sBody = @str_replace("[SiteUrl]", SITE_URL, $sBody);
				$sBody = @str_replace("[SenderName]", $_SESSION['Name'], $sBody);
				$sBody = @str_replace("[SenderEmail]", $_SESSION['Email'], $sBody);


				$objEmail = new PHPMailer( );

//				$objEmail->Username = "khalidch";
//				$objEmail->Password = "marketing";
///				$objEmail->From     = "khalidch@apparelco.com";
//				$objEmail->FromName = "Muhammad Khalid";
				$objEmail->Subject  = "POs Current Standing";

				$objEmail->MsgHTML($sBody);
				$objEmail->AddAddress($sEmail, $sName);
//				$objEmail->AddAddress("khalidch@apparelco.com", "Muhammad Khalid");
				$objEmail->AddAddress("islam@apparelco.com", "Muhammad Islam");
				$objEmail->AddAttachment($sExcelFile, 'CurrentStanding.csv');
				$objEmail->Send( );
			}
		}
	}

	else
	{
		// forcing csv file to download
		$iSize = @filesize($sExcelFile);

		if(ini_get('zlib.output_compression'))
			@ini_set('zlib.output_compression', 'Off');

		header('Content-Description: File Transfer');
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private", false);
		header('Content-Type: application/force-download');
		header("Content-Type: application/download");
		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=\"".basename($sExcelFile)."\";");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: $iSize");

		@readfile($sExcelFile);
	}

	@unlink($sExcelFile);

	if ($Country > 0 && $Brand > 0)
		redirect($_SERVER['HTTP_REFERER'], "CURRENT_STANDING_SENT");

	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDb4->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>