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

	$User       = IO::strValue('User');
	$AuditCode  = IO::strValue('AuditCode');


	$iAuditCode = intval(substr($AuditCode, 1));


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";

	if ($User == "" || $AuditCode == "" || $iAuditCode == 0 || $AuditCode{0} != "S")
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, status, vendors, brands, style_categories FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else
		{
			$iUser            = $objDb->getField(0, "id");
			$sName            = $objDb->getField(0, "name");
			$sBrands          = $objDb->getField(0, "brands");
			$sVendors         = $objDb->getField(0, "vendors");
			$sStyleCategories = $objDb->getField(0, "style_categories");


			$sSQL = "SELECT group_id, report_id, brand_id, vendor_id, unit_id, line_id, cutting_lot_no, audit_stage, audit_date, start_time, end_time, po_id, additional_pos, style_id, colors, sizes, total_gmts, aql, inspection_level, check_level, audit_quantity
			         FROM tbl_qa_reports
			         WHERE id='$iAuditCode'";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 0)
				$aResponse["Message"] = "No Audit Code Found!";

			else
			{
				$iGroup           = $objDb->getField(0, "group_id");
				$iBrand           = $objDb->getField(0, "brand_id");
				$iVendor          = $objDb->getField(0, "vendor_id");
				$iUnit            = $objDb->getField(0, "unit_id");
				$iReportType      = $objDb->getField(0, "report_id");
				$iLine            = $objDb->getField(0, "line_id");
				$sLotNo           = $objDb->getField(0, "cutting_lot_no");
				$sAuditDate       = $objDb->getField(0, "audit_date");
				$sStartTime       = $objDb->getField(0, "start_time");
				$sEndTime         = $objDb->getField(0, "end_time");
				$iPo              = $objDb->getField(0, "po_id");
				$sAdditionalPos   = $objDb->getField(0, "additional_pos");
				$iStyle           = $objDb->getField(0, "style_id");
				$sColors          = $objDb->getField(0, "colors");
				$sSizes           = $objDb->getField(0, "sizes");
				$sAuditStage      = $objDb->getField(0, "audit_stage");
				$iSampleSize      = $objDb->getField(0, "total_gmts");
				$fAql             = $objDb->getField(0, "aql");
				$iInspectionLevel = $objDb->getField(0, "inspection_level");
				$iQuantity        = $objDb->getField(0, "audit_quantity");
				$iCheckLevel      = $objDb->getField(0, "check_level");

			
				$iCountry   = getDbValue("country_id", "tbl_vendors", "id='$iVendor'");
				$iHours     = getDbValue("hours", "tbl_countries", "id='$iCountry'");
				$iStartTime = (strtotime($sStartTime) + ($iHours * 3600));
				$sStartTime = date("H:i", $iStartTime);
				$iEndTime   = (strtotime($sEndTime) + ($iHours * 3600));
				$sEndTime   = date("H:i", $iEndTime);
				
					
				if (@in_array($sAuditStage, array("DS", "DT")))
				{
					$sPoIds      = $iStyle;
					$sPosList    = getList("tbl_styles", "id", "style", "sub_brand_id='$iBrand'");
					$sStylesList = array( );
					$sSizesList  = array( );
					$sColorsList = array( );
					$sSizes      = "";
					$iStyle      = 0;
				}

				else if ($iReportType == 26 || $iReportType == 30)
				{
					$sPoIds      = $iStyle;
					$sPosList    = getList("tbl_styles", "id", "style", "sub_brand_id='$iBrand'");
					$sStylesList = getList("tbl_po po, tbl_po_colors pc", "DISTINCT(po.id)", "TRIM(CONCAT(po.order_no, ' ', po.order_status))", "po.id=pc.po_id AND pc.style_id='$iStyle' AND po.vendor_id='$iVendor' AND po.brand_id='$iBrand'", "po.order_no");
					$sSizesList  = array( );
					$sColorsList = array( );
					$sSizes      = "";
					$iStyle      = $iPo;


					$sSQL = "SELECT id, color FROM tbl_po_colors WHERE style_id='$sPoIds' GROUP BY color ORDER BY color";
					$objDb->query($sSQL);

					$iCount = $objDb->getCount( );

					for ($i = 0; $i < $iCount; $i ++)
						$sColorsList[$objDb->getField($i, 0)] = $objDb->getField($i, 1);
				}

				else
				{
					$sFromDate   = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 30), date("Y")));
					$sToDate     = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") + 90), date("Y")));

					$sPoIds      = ($iPo.(($sAdditionalPos != "") ? ",{$sAdditionalPos}" : ""));
					$sPosList    = getList("tbl_po po, tbl_po_colors pc", "DISTINCT(po.id)", "CONCAT(order_no, IF(order_status!='', CONCAT(' ', order_status), '')) AS _Po", "po.id=pc.po_id AND po.vendor_id='$iVendor' AND (po.brand_id='$iBrand' OR ('$iBrand'='0' AND FIND_IN_SET(po.brand_id, '$sBrands'))) AND ( (po.status!='C' AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate')) OR po.id IN ($sPoIds))", "_Po");

					$sStylesList = getList("tbl_styles s, tbl_po_colors pc", "DISTINCT(s.id)", "s.style", "s.id=pc.style_id AND FIND_IN_SET(s.category_id, '$sStyleCategories') AND FIND_IN_SET(pc.po_id, '$sPoIds') AND FIND_IN_SET(s.sub_brand_id, '$sBrands')", "s.style");

					$sSizesList  = getDbValue("GROUP_CONCAT(DISTINCT(size_id) SEPARATOR ',')", "tbl_po_quantities", "FIND_IN_SET(po_id, '$sPoIds')");
					$sSizesList  = getList("tbl_sizes", "id", "size", "FIND_IN_SET(id, '$sSizesList')", "size");

					$sSizes      = getDbValue("GROUP_CONCAT(DISTINCT(size) SEPARATOR ',')", "tbl_sizes", "FIND_IN_SET(id, '$sSizes')");
					$sColorsList = array( );
					$sStyleColors = array( );

					
					$sSQL = "SELECT id, style_id, color FROM tbl_po_colors WHERE po_id IN ($sPoIds) GROUP BY style_id, color ORDER BY color";
					$objDb->query($sSQL);

					$iCount = $objDb->getCount( );

					for ($i = 0; $i < $iCount; $i ++)
					{
						if ($objDb->getField($i, 1) == $iStyle)
							$sColorsList[$objDb->getField($i, 0)] = str_replace(",", " - ", $objDb->getField($i, 2));
					}
					
					for ($i = 0; $i < $iCount; $i ++)
						$sStyleColors[$objDb->getField($i, 1)."-".$objDb->getField($i, 0)] = str_replace(",", " - ", $objDb->getField($i, 2));
				}


				$sLinesList  = getList("tbl_lines", "id", "line", "vendor_id='$iVendor' AND unit_id='$iUnit' AND line!=''", "line");


				$sSchedule = array("ReportType"      => $iReportType,
				                   "AuditStage"      => $sAuditStage,
				                   "Brand"           => $iBrand,
				                   "Vendor"          => $iVendor,
				                   "Unit"            => $iUnit,
				                   "Pos"             => "{$sPoIds},",
				                   "Style"           => $iStyle,
				                   "Colors"          => "{$sColors},",
				                   "SampleSize"      => $iSampleSize,
				                   "Sizes"           => "{$sSizes},",
				                   "Line"            => $iLine,
								   "LotNo"           => $sLotNo,
				                   "AuditDate"       => $sAuditDate,
				                   "StartTime"       => $sStartTime,
								   "EndTime"         => $sEndTime,
				                   "Group"           => $iGroup,
				                   "InspectionLevel" => $iInspectionLevel,
								   "Aql"             => $fAql,
								   "CheckLevel"      => $iCheckLevel,
				                   "Quantity"        => $iQuantity,
				                   "PosList"         => $sPosList,
				                   "StylesList"      => $sStylesList,
				                   "ColorsList"      => $sColorsList,
								   "StyleColors"     => $sStyleColors,
				                   "SizesList"       => $sSizesList,
				                   "LinesList"       => $sLinesList);

				$aResponse['Status']   = "OK";
				$aResponse['Schedule'] = $sSchedule;
			}
		}
	}


	print @json_encode($aResponse);


/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Schedule Audit";
	$objEmail->Body    = @json_encode($aResponse).$sMessage;

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>
