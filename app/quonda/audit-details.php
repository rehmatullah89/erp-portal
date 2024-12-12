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

	@require_once("../requires/session.php");

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
		$sSQL = "SELECT id, name, app_skip_image, status, guest FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else
		{
			$iUser      = $objDb->getField(0, "id");
			$sName      = $objDb->getField(0, "name");
			$sSkipImage = $objDb->getField(0, "app_skip_image");
			$sGuest     = $objDb->getField(0, "guest");


			$sSQL = "SELECT * FROM tbl_qa_reports WHERE audit_code='$AuditCode'";
			$objDb->query($sSQL);

			$iAuditorId     = $objDb->getField(0, 'user_id');
			$iStyleId       = $objDb->getField(0, 'style_id');
			$iPoId          = $objDb->getField(0, 'po_id');
			$sAdditionalPos = $objDb->getField(0, 'additional_pos');
			$iReportId      = $objDb->getField(0, 'report_id');
			$iVendorId      = $objDb->getField(0, 'vendor_id');
			$sSizes         = $objDb->getField(0, 'sizes');
			$iSampleSize    = $objDb->getField(0, 'total_gmts');
			$iSampleChecked = $objDb->getField(0, 'checked_gmts');
			$sAuditStage    = $objDb->getField(0, 'audit_stage');
			$iOfferedQty    = $objDb->getField(0, 'audit_quantity');
			$fAql           = $objDb->getField(0, 'aql');
			$fDr            = $objDb->getField(0, 'dhu');
			$sColors        = $objDb->getField(0, 'colors');
			$sStartDateTime = $objDb->getField(0, 'start_date_time');
			$sAuditDate     = $objDb->getField(0, 'audit_date');
			$sAuditResult   = $objDb->getField(0, 'audit_result');
			$sComments      = $objDb->getField(0, 'qa_comments');

			if ($iStyleId == 0 && $iPoId > 0)
				$iStyleId = getDbValue("style_id", "tbl_po_colors", "po_id='$iPoId'");


			$sSQL = "SELECT style, sketch_file, brand_id,
							(SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand,
							(SELECT season FROM tbl_seasons WHERE id=tbl_styles.sub_season_id) AS _Season
					 FROM tbl_styles
					 WHERE id='$iStyleId'";
			$objDb->query($sSQL);

			$sStyle   = $objDb->getField(0, 'style');
			$sBrand   = $objDb->getField(0, '_Brand');
			$iBrand   = $objDb->getField(0, 'brand_id');
			$sSeason  = $objDb->getField(0, '_Season');
			$sPicture = $objDb->getField(0, 'sketch_file');


			if ($iPoId == 0)
			{
				$sPo          = "N/A";
				$sEtdRequired = "";
				$sPos         = "";
				$sSizes       = array( );
			}

			else
			{
				$sSizes       = getList("tbl_sizes", "id", "size", "FIND_IN_SET(id, '$sSizes')", "size");
				$sPo          = getDbValue("GROUP_CONCAT(TRIM(CONCAT(order_no, ' ', order_status)) SEPARATOR ', ')", "tbl_po", "id='$iPoId' OR ('$sAdditionalPos'!='' AND FIND_IN_SET(id, '$sAdditionalPos'))");
				$sEtdRequired = getDbValue("etd_required", "tbl_po_colors", "po_id='$iPoId'", "etd_required");
				$sPos         = getDbValue("GROUP_CONCAT(order_no SEPARATOR ',')", "tbl_po", "id='$iPoId' OR FIND_IN_SET(id, '$sAdditionalPos')", "id");
			}


			$fAql = getDbValue("aql", "tbl_brands", "id='$iBrand'");

			if ($sPicture == "" || !@file_exists(ABSOLUTE_PATH.STYLES_SKETCH_DIR.$sPicture))
				$sPicture = (SITE_URL.STYLES_SKETCH_DIR."default.jpg");

			else
			{
				if (!@file_exists(ABSOLUTE_PATH.STYLES_SKETCH_DIR.'thumbs/'.$sPicture))
					createImage((ABSOLUTE_PATH.STYLES_SKETCH_DIR.$sPicture), (ABSOLUTE_PATH.STYLES_SKETCH_DIR.'thumbs/'.$sPicture), 160, 160);

				$sPicture = (SITE_URL.STYLES_SKETCH_DIR.'thumbs/'.$sPicture);
			}


			$sSQL = "SELECT color, stage FROM tbl_audit_stages WHERE code='$sAuditStage'";
			$objDb->query($sSQL);

			$sColor      = $objDb->getField(0, 'color');
			$sAuditStage = $objDb->getField(0, 'stage');

			if ($sColor == "")
				$sColor = "#cccccc";

			if ($sAuditResult == "")
				$sAuditResult = "N/A";

			
			$sQuantities = array( );
			$iOrderQty   = 0;
			
			$sSQL = "SELECT po.id, po.order_no, SUM(pc.order_qty) AS _Quantity
					 FROM tbl_po po, tbl_po_colors pc
					 WHERE po.id=pc.po_id AND (po.id='$iPoId' OR FIND_IN_SET(po.id, '$sAdditionalPos')) AND FIND_IN_SET(pc.color, '$sColors') AND pc.style_id='$iStyleId'
					 GROUP BY po.id";
			$objDb->query($sSQL);
			
			$iCount = $objDb->getCount( );
			
			for ($i = 0; $i < $iCount; $i ++)
			{
				$iPo       = $objDb->getField($i, "id");
				$sOrderNo  = $objDb->getField($i, "order_no");
				$iQuantity = $objDb->getField($i, "_Quantity");
				
				$iOrderQty    += $iQuantity;
				$sQuantities[] = array("PoId" => $iPo, "PoNo" => $sOrderNo, "OrderQty" => $iQuantity);
			}

		
			$sColors         = @explode(",", $sColors);
			$fAql            = (($fAql == 0) ? 2.5 : $fAql);
			$iDefectsAllowed = 0;
			$fDeviation      = 0;

			if (@isset($iAqlChart["{$iSampleSize}"]["{$fAql}"]))
				$iDefectsAllowed = $iAqlChart["{$iSampleSize}"]["{$fAql}"];
			
			if (@in_array($iReportId, array(28, 37)))
				$fDeviation = (@round((($iOfferedQty / $iOrderQty) * 100), 2) - 100);
		


			$sAudit = array("Auditor"        => md5($iAuditorId),
							"AuditDate"      => $sAuditDate,
						    "Style"          => $sStyle,
							"Po"             => $sPo,
							"Pos"            => $sPos,
						    "BrandId"        => $iBrand,
						    "Brand"          => $sBrand,
						    "Season"         => $sSeason,
						    "EtdRequired"    => (($sEtdRequired == "") ? "N/A" : formatDate($sEtdRequired)),
						    "ReportId"       => $iReportId,
							"Vendor"         => getDbValue("vendor", "tbl_vendors", "id='$iVendorId'"),							
							"Colors"         => $sColors,
						    "Sizes"          => $sSizes,
							"Quantities"     => $sQuantities,
							"OfferedQty"     => $iOfferedQty,
							"OrderQty"       => $iOrderQty,
							"Deviation"      => $fDeviation,
						    "SampleSize"     => $iSampleSize,
						    "SampleChecked"  => $iSampleChecked,
						    "Started"        => ((($sStartDateTime == "" || $sStartDateTime == "0000-00-00 00:00:00" || strtotime($sStartDateTime) < strtotime("2013-01-01 00:00:00")) && $iSampleChecked == 0) ? "N" : "Y"),
						    "Comments"       => (($sComments == "") ? "N" : "Y"),
						    "AuditResult"    => $sAuditResult,
						    "AuditStage"     => $sAuditStage,
						    "Aql"            => $fAql,
						    "DefectsAllowed" => $iDefectsAllowed,
						    "Dr"             => $fDr,
						    "Color"          => $sColor,
						    "Picture"        => $sPicture,
						    "SkipImage"      => (($sSkipImage == "Y") ? "Y" : "N"));

			$aResponse['Status'] = "OK";
			$aResponse['Audit']  = $sAudit;
		}
	}


	print @json_encode($aResponse);


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>