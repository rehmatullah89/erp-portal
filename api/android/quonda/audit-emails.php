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

	$User      = IO::strValue("User");
	$AuditCode = IO::strValue("AuditCode");


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "" || $AuditCode == "" || $AuditCode{0} != "S")
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, email, status FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else
		{
			$iUser  = $objDb->getField(0, "id");
			$sName  = $objDb->getField(0, "name");
			$sEmail = $objDb->getField(0, "email");


			$sSQL = "SELECT style_id, po_id, vendor_id, report_id, audit_result, audit_stage, audit_date FROM tbl_qa_reports WHERE audit_code='$AuditCode'";
			$objDb->query($sSQL);

			$iStyleId     = $objDb->getField(0, 'style_id');
			$iPoId        = $objDb->getField(0, 'po_id');
			$iReportId    = $objDb->getField(0, 'report_id');
			$iVendorId    = $objDb->getField(0, 'vendor_id');
			$sAuditResult = $objDb->getField(0, 'audit_result');
			$sAuditStage  = $objDb->getField(0, 'audit_stage');
			$sAuditDate   = $objDb->getField(0, 'audit_date');

			if ($iStyleId == 0)
				$iStyleId = getDbValue("style_id", "tbl_po_colors", "po_id='$iPoId'");


			$sSQL = "SELECT style, sketch_file,
							(SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand
					 FROM tbl_styles
					 WHERE id='$iStyleId'";
			$objDb->query($sSQL);

			$sStyle      = $objDb->getField(0, 'style');
			$sBrand      = $objDb->getField(0, '_Brand');
			$sSketchFile = $objDb->getField(0, 'sketch_file');


			$sPo = getDbValue("CONCAT(order_no, ' ', order_status)", "tbl_po", "id='$iPoId'");


			@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

			$sPicture       = "";
			$sQuondaDir     = (ABSOLUTE_PATH.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");
			$sAuditPictures = @glob($sQuondaDir."?".substr($sAuditCode, 1)."_*.*");

			if (count($sAuditPictures) > 0)
			{
				$sPictures = array( );
				$iLength   = strlen($sAuditCode);

				foreach ($sAuditPictures as $sDefectPicture)
				{
					if (substr(@basename($sDefectPicture), 0, ($iLength + 4)) == "{$sAuditCode}_001" ||
						substr(@basename($sDefectPicture), 0, ($iLength + 4)) == "{$sAuditCode}_00_" ||
						strlen(@basename($sDefectPicture)) < ($iLength + 6) || @strpos($sDefectPicture, "_pack_") !== FALSE)
						continue;

					$sParts = @explode("_", $sDefectPicture);
					$sCode  = trim($sParts[1]);

					if (@array_key_exists($sCode, $sPictures))
						$sPictures[$sCode] ++;

					else
						$sPictures[$sCode] = 1;
				}

				@arsort($sPictures);

				$sCode = "";

				foreach ($sPictures as $sDefectCode => $iDefectCount)
				{
					if ($sCode == "")
						$sCode = $sDefectCode;
				}

				foreach ($sAuditPictures as $sDefectPicture)
				{
					if (substr(@basename($sDefectPicture), 0, ($iLength + 4)) == "{$sAuditCode}_001" ||
						substr(@basename($sDefectPicture), 0, ($iLength + 4)) == "{$sAuditCode}_00_" ||
						strlen(@basename($sDefectPicture)) < ($iLength + 6) || @strpos($sDefectPicture, "_pack_") !== FALSE)
						continue;


					if ($sPicture == "" && @strpos($sDefectPicture, "_{$sCode}_") !== FALSE)
						$sPicture = $sDefectPicture;
				}


				if ($sPicture != "")
				{
					if (!@file_exists($sQuondaDir.'thumbs/'.@basename($sPicture)))
					{
						@mkdir($sQuondaDir.'thumbs/');

						createImage(($sQuondaDir.@basename($sPicture)), ($sQuondaDir.'thumbs/'.@basename($sPicture)), 240, 180);
					}


					$sPicture = (SITE_URL.str_ireplace(ABSOLUTE_PATH, "", $sQuondaDir).'thumbs/'.@basename($sPicture));
				}
			}

			if ($sPicture == "" && $sSketchFile != "")
			{
				if ($sSketchFile == "" || !@file_exists(ABSOLUTE_PATH.STYLES_SKETCH_DIR.$sSketchFile))
					$sPicture = (SITE_URL.STYLES_SKETCH_DIR."default.jpg");

				else
				{
					if (!@file_exists(ABSOLUTE_PATH.STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile))
						createImage((ABSOLUTE_PATH.STYLES_SKETCH_DIR.$sSketchFile), (ABSOLUTE_PATH.STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile), 160, 160);

					$sPicture = (SITE_URL.STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile);
				}
			}


			if ($sPicture == "")
				$sSketch = (SITE_URL.STYLES_SKETCH_DIR."default.jpg");


			$sAudit = array("Style"       => $sStyle,
						    "Style"       => $sStyle,
						    "Po"          => $sPo,
						    "Brand"       => $sBrand,
						    "Vendor"      => getDbValue("vendor", "tbl_vendors", "id='$iVendorId'"),
						    "AuditResult" => $sAuditResult,
						    "Picture"     => $sPicture);


			$sSQL = "SELECT id, name, email
					 FROM tbl_qa_emails
					 WHERE FIND_IN_SET('$iVendorId', vendors) AND (audit_stages='' OR FIND_IN_SET('$sAuditStage', audit_stages)) AND (audit_results='' OR FIND_IN_SET('$sAuditResult', audit_results))
					 ORDER BY name";
			$objDb->query($sSQL);

			$iCount  = $objDb->getCount( );
			$sEmails = array( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iEmail = $objDb->getField($i, "id");
				$sName  = $objDb->getField($i, "name");
				$sEmail = $objDb->getField($i, "email");

				$sEmails[] = array("Id" => $iEmail, "Name" => "{$sName} <{$sEmail}>");
			}

			if ($iCount == 0)
				$sEmails[] = array("Id" => "0", "Name" => "{$sName} <{$sEmail}>");



			$aResponse['Status'] = "OK";
			$aResponse['Audit']  = $sAudit;
			$aResponse['Emails'] = $sEmails;
		}
	}


	print @json_encode($aResponse);


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>