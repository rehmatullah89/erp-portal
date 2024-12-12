<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree QUONDA App                                                                   **
	**  Version 3.0                                                                              **
	**                                                                                           **
	**  http://app.3-tree.com                                                                    **
	**                                                                                           **
	**  Copyright 2008-17 (C) Triple Tree                                                        **
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
	***********************************************************************************************
	\*********************************************************************************************/

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$User       = IO::strValue('User');
	$AuditCode  = IO::strValue('AuditCode');
	$Brand      = IO::intValue('Brand');
	$Vendor     = IO::intValue('Vendor');
	$Audits     = IO::strValue('Audits');
	$DateRange  = IO::strValue('DateRange');
	$DefectType = IO::intValue('DefectType');


	@list($FromDate, $ToDate) = @explode(":", $DateRange);


	if ($User == "")
		die("Invalid Request");


	$sSQL = "SELECT id, vendors, brands, style_categories, report_types, audit_stages, status, guest FROM tbl_users WHERE MD5(id)='$User'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		die("Invalid User");

	else if ($objDb->getField(0, "status") != "A")
		die("User Account is Disabled");


	$sBrands          = $objDb->getField(0, "brands");
	$sVendors         = $objDb->getField(0, "vendors");
	$sStyleCategories = $objDb->getField(0, "style_categories");
	$sReportTypes     = $objDb->getField(0, "report_types");
	$sAuditStages     = $objDb->getField(0, "audit_stages");
	$sGuest           = $objDb->getField(0, "guest");


	$sConditions = " AND qa.audit_type='B' AND qa.audit_result!='' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') AND FIND_IN_SET(qa.report_id, '$sReportTypes') ";

	if ($AuditCode != "")
		$sConditions .= " AND qa.audit_code='$AuditCode' ";

	if ($Audits == "F")
		$sConditions .= " AND qa.audit_stage='F' ";

	else if ($Audits == "I")
		$sConditions .= " AND qa.audit_stage!='F' ";

	if ($Vendor > 0)
		$sConditions .= " AND qa.vendor_id='$Vendor' ";

	else
		$sConditions .= " AND qa.vendor_id IN ($sVendors) ";


	if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE)
	{
		$sConditions .= " AND qa.style_id IN (SELECT id FROM tbl_styles WHERE  ";

		if ($Brand > 0)
			$sConditions .= " sub_brand_id='$Brand' ";

		else
			$sConditions .= " FIND_IN_SET(sub_brand_id, '$sBrands') ";

		$sConditions .= "  AND FIND_IN_SET(category_id, '$sStyleCategories')) ";
	}

	else
	{
		$sConditions .= " AND (qa.style_id='0' OR qa.style_id IN (SELECT id FROM tbl_styles WHERE ";

		if ($Brand > 0)
			$sConditions .= " sub_brand_id='$Brand' ";

		else
			$sConditions .= " FIND_IN_SET(sub_brand_id, '$sBrands') ";

		$sConditions .= "  AND FIND_IN_SET(category_id, '$sStyleCategories'))) ";
	}


	$sDefectCodes = array( );

	$sSQL = "SELECT code FROM tbl_defect_codes WHERE type_id='$DefectType'";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$sDefectCodes[] = $objDb->getField($i, 0);


	$sSQL = "SELECT DISTINCT(qa.audit_code), audit_date
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id $sConditions
			 ORDER BY qa.id";
	$objDb->query($sSQL);

	$iCount    = $objDb->getCount( );
	$sPictures = array( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sAuditCode = $objDb->getField($i, 0);
		$sAuditDate = $objDb->getField($i, 1);

		@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

		$sQuondaDir = (ABSOLUTE_PATH.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");

		$sAuditPictures = @glob("{$sQuondaDir}?".substr($sAuditCode, 1)."_*.*");
		$sAuditPictures = @array_map("strtoupper", $sAuditPictures);
		$sAuditPictures = @array_unique($sAuditPictures);



		$sTemp   = array( );
		$iLength = strlen($sAuditCode);

		foreach ($sAuditPictures as $sPicture)
		{
			if (substr(@basename($sPicture), 0, ($iLength + 4)) == "{$sAuditCode}_001" ||
				strlen(@basename($sPicture)) < ($iLength + 6) || @strpos($sDefectPicture, "_pack_") !== FALSE)
				continue;

			$sTemp[] = $sPicture;
		}

		$sAuditPictures = $sTemp;


		for ($j = 0; $j < count($sAuditPictures); $j ++)
		{
			$sName  = @strtoupper($sAuditPictures[$j]);
			$sName  = @basename($sName, ".JPG");
			$sName  = @basename($sName, ".GIF");
			$sName  = @basename($sName, ".PNG");
			$sName  = @basename($sName, ".BMP");

			$sParts   = @explode("_", $sName);
			$sPicture = @basename($sAuditPictures[$j]);

			if (!@file_exists($sQuondaDir.'thumbs/'.$sPicture))
			{
				@mkdir($sQuondaDir.'thumbs/');

				createImage(($sQuondaDir.$sPicture), ($sQuondaDir.'thumbs/'.$sPicture), 240, 180);
			}


			$sUrl = (SITE_URL.str_ireplace(ABSOLUTE_PATH, "", $sQuondaDir)."thumbs/".$sPicture);

			if ($DefectType == 0 || ($DefectType > 0 && @in_array($sParts[1], $sDefectCodes)))
			{
				$sDefectCode = $sParts[1];
				$sAreaCode   = $sParts[2];
				$sTitle      = "";


				$sSQL = "SELECT report_id, po_id, style_id,
								(SELECT vendor FROM tbl_vendors WHERE id=qa.vendor_id) AS _Vendor,
								(SELECT order_no FROM tbl_po WHERE id=qa.po_id) AS _PO
						 FROM tbl_qa_reports qa
						 WHERE audit_code='$sAuditCode'";

				if ($objDb2->query($sSQL) == true)
				{
					$iReport = $objDb2->getField(0, "report_id");
					$sVendor = $objDb2->getField(0, "_Vendor");
					$sPo     = $objDb2->getField(0, "_PO");
					$iPo     = $objDb2->getField(0, 'po_id');
					$iStyle  = $objDb2->getField(0, "style_id");

					if ($iStyle == 0)
						$iStyle = getDbValue("style_id", "tbl_po_colors", "po_id='$iPo'");


					$sSQL = "SELECT style,
									(SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand
							 FROM tbl_styles
							 WHERE id='$iStyle'";
					$objDb2->query($sSQL);

					$sStyle = $objDb2->getField(0, 'style');
					$sBrand = $objDb2->getField(0, '_Brand');


					$sTitle  = "<b>Vendor:</b> {$sVendor} &nbsp; &nbsp; &nbsp; ";
					$sTitle .= "<b>PO #</b> {$sPo}<br />";
					$sTitle .= "<b>Style #</b> {$sStyle} &nbsp; &nbsp; &nbsp; ";
					$sTitle .= "<b>Brand #</b> {$sBrand}<br />";


					$sSQL = "SELECT defect,
									(SELECT type FROM tbl_defect_types WHERE id=dc.type_id) AS _Type
							 FROM tbl_defect_codes dc
							 WHERE code='$sDefectCode' AND report_id='$iReport'";

					if ($objDb2->query($sSQL) == true && $objDb2->getCount( ) == 1)
					{
						$sDefect = $objDb2->getField(0, 0);

						$sTitle .= ("<b>Defect Type:</b> ".$objDb2->getField(0, 1)."<br />");

						if ($iReport != 4 && $iReport != 6)
						{
							$sSQL = "SELECT area FROM tbl_defect_areas WHERE id='$sAreaCode'";

							if ($objDb2->query($sSQL) == true && $objDb2->getCount( ) == 1)
								$sTitle .= (" <b>Defect Area:</b> ".$objDb2->getField(0, 0)."<br />");
						}

						$sTitle .= (" <b>Defect:</b> ".$sDefect);
					}
				}


				$sPictures[] = array("AuditCode" => $sAuditCode,
									 "AuditDate" => $sAuditDate,
									 "Title"     => $sTitle,
									 "URL"       => $sUrl);
			}

			else
				continue;


			if (count($sPictures) >= 30)
				break;
		}


		if (count($sPictures) >= 30)
			break;
	}


	$aResponse             = array( );
	$aResponse['Status']   = "OK";
	$aResponse['Pictures'] = $sPictures;


	print @json_encode($aResponse);

/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = @json_encode($aResponse);

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>