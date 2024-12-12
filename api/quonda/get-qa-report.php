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

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	$AuditCode  = IO::strValue('AuditCode');
	$iAuditCode = intval(substr($AuditCode, 1));


	$aResponse = array( );


	if ($iAuditCode == 0 || strlen($AuditCode) == 0 || $AuditCode{0} != "S")
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid Audit Code";
	}

	else
	{
		$sSQL = "SELECT *,
						(SELECT order_no FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _Po,
						(SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _Auditor,
						(SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor,
						(SELECT line FROM tbl_lines WHERE id=tbl_qa_reports.line_id) AS _Line,
						(SELECT report FROM tbl_reports WHERE id=tbl_qa_reports.report_id) AS _ReportType
				 FROM tbl_qa_reports
				 WHERE id='$iAuditCode'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "No QA Report Found!";
		}

		else
		{
			$iReportId          = $objDb->getField(0, "report_id");
			$sReport            = $objDb->getField(0, "_ReportType");
			$sVendor            = $objDb->getField(0, "_Vendor");
			$sLine              = $objDb->getField(0, "_Line");
			$sAuditor           = $objDb->getField(0, "_Auditor");
			$iPoId              = $objDb->getField(0, "po_id");
			$sPo                = $objDb->getField(0, "_Po");
			$sAdditionalPos     = $objDb->getField(0, "additional_pos");
			$iStyle             = $objDb->getField(0, "style_id");
			$sColors            = $objDb->getField(0, 'colors');
			$sSizes             = $objDb->getField(0, 'sizes');
			$sAuditStatus       = $objDb->getField(0, 'audit_status');
			$sAuditDate         = $objDb->getField(0, "audit_date");
			$sStartTime         = $objDb->getField(0, "start_time");
			$sEndTime           = $objDb->getField(0, "end_time");
			$sAuditStage        = $objDb->getField(0, "audit_stage");
			$sAuditResult       = $objDb->getField(0, "audit_result");
			$iSampleSize        = $objDb->getField(0, "total_gmts");
			$iMaxDefects        = $objDb->getField(0, "max_defects");
			$iBeautifulProducts = (int)$objDb->getField(0, "beautiful_products");
			$iShipQty           = $objDb->getField(0, "ship_qty");
			$fKnitted           = (float)$objDb->getField(0, "knitted");
			$fDyed              = (float)$objDb->getField(0, "dyed");
			$iCutting           = (int)$objDb->getField(0, "cutting");
			$iSewing            = (int)$objDb->getField(0, "sewing");
			$iFinishing         = (int)$objDb->getField(0, "finishing");
			$iPacking           = (int)$objDb->getField(0, "packing");
			$sFinalAuditDate    = $objDb->getField(0, "final_audit_date");
			$iReScreenQty       = $objDb->getField(0, "re_screen_qty");
			$sApprovedSample    = $objDb->getField(0, "approved_sample");
			$sShippingMark      = $objDb->getField(0, "shipping_mark");
			$sPackingCheck	    = $objDb->getField(0, "packing_check");
			$iCartonsInspected  = $objDb->getField(0, "total_cartons");
			$fCartonsShipped    = $objDb->getField(0, "cartons_shipped");
			$sCartonSize        = $objDb->getField(0, "carton_size");
			$sComments          = $objDb->getField(0, "qa_comments");


			$sApprovedSample = (($sApprovedSample == "Yes") ? "Y" : "N");
			$sShippingMark   = (($sShippingMark != "Y") ? "N" : "Y");
			$sPackingCheck   = (($sPackingCheck != "Y") ? "N" : "Y");
			$sCartonSize     = (($sCartonSize == "xxxin") ? "" : $sCartonSize);
			$sSizeTitles     = "";

			$sSQL = "SELECT size FROM tbl_sizes WHERE id IN ($sSizes) ORDER BY position";

			if ($objDb->query($sSQL) == true)
			{
				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
					$sSizeTitles .= (", ".$objDb->getField($i, 0));

				$sSizeTitles = substr($sSizeTitles, 2);
			}


			if ($iReportId == 7)
			{
				$sSQL = "SELECT * FROM tbl_ar_inspection_checklist WHERE audit_id='$iAuditCode'";
				$objDb->query($sSQL);

				$sModelName          = $objDb->getField(0, "model_name");
				$sWorkingNo          = $objDb->getField(0, "working_no");
				$sFabricApproval     = $objDb->getField(0, "fabric_approval");
				$sCounterSampleAppr  = $objDb->getField(0, "counter_sample_appr");
				$sGarmentWashingTest = $objDb->getField(0, "garment_washing_test");
				$sColorShade         = $objDb->getField(0, "color_shade");
				$sAppearance         = $objDb->getField(0, "appearance");
				$sHandfeel           = $objDb->getField(0, "handfeel");
				$sPrinting           = $objDb->getField(0, "printing");
				$sEmbridery          = $objDb->getField(0, "embridery");
				$sFibreContent       = $objDb->getField(0, "fibre_content");
				$sCountryOfOrigin    = $objDb->getField(0, "country_of_origin");
				$sCareInstruction    = $objDb->getField(0, "care_instruction");
				$sSizeKey            = $objDb->getField(0, "size_key");
				$sAdiComp            = $objDb->getField(0, "adi_comp");
				$sColourSizeQty      = $objDb->getField(0, "colour_size_qty");
				$sPolybag            = $objDb->getField(0, "polybag");
				$sHangtag            = $objDb->getField(0, "hangtag");
				$sOclUpc             = $objDb->getField(0, "ocl_upc");
				$sCartonNoChecked    = $objDb->getField(0, "carton_no_checked");


				$sFabricApproval     = (($sFabricApproval != "Y") ? "N" : "Y");
				$sCounterSampleAppr  = (($sCounterSampleAppr != "Y") ? "N" : "Y");
				$sGarmentWashingTest = (($sGarmentWashingTest != "Y") ? "N" : "Y");
				$sColorShade         = (($sColorShade != "Y") ? "N" : "Y");
				$sAppearance         = (($sAppearance != "Y") ? "N" : "Y");
				$sHandfeel           = (($sHandfeel != "Y") ? "N" : "Y");
				$sPrinting           = (($sPrinting != "Y") ? "N" : "Y");
				$sEmbridery          = (($sEmbridery != "Y") ? "N" : "Y");
				$sFibreContent       = (($sFibreContent != "Y") ? "N" : "Y");
				$sCountryOfOrigin    = (($sCountryOfOrigin != "Y") ? "N" : "Y");
				$sCareInstruction    = (($sCareInstruction != "Y") ? "N" : "Y");
				$sSizeKey            = (($sSizeKey != "Y") ? "N" : "Y");
				$sAdiComp            = (($sAdiComp != "Y") ? "N" : "Y");
				$sColourSizeQty      = (($sColourSizeQty != "Y") ? "N" : "Y");
				$sPolybag            = (($sPolybag != "Y") ? "N" : "Y");
				$sHangtag            = (($sHangtag != "Y") ? "N" : "Y");
				$sOclUpc             = (($sOclUpc != "Y") ? "N" : "Y");
			}

			else if ($iReportId == 11)
			{
				$sDescription   = $objDb->getField(0, "description");
				$sBatchSize     = $objDb->getField(0, "batch_size");
				$fPackedPercent = $objDb->getField(0, "packed_percent");
				$iGmtsDefective = $objDb->getField(0, "defective_gmts");


				$sSQL = "SELECT * FROM tbl_ms_qa_reports WHERE audit_id='$iAuditCode'";
				$objDb->query($sSQL);

				$sSeries        = $objDb->getField(0, 'series');
				$sDepartment    = $objDb->getField(0, 'department');
				$sBigProducts   = $objDb->getField(0, 'big_products');
				$sBigSize       = $objDb->getField(0, 'big_size');
				$sSmallProducts = $objDb->getField(0, 'small_products');
				$sSmallSize     = $objDb->getField(0, 'small_size');
				$sAction        = $objDb->getField(0, 'action');
			}


			$sSQL = "SELECT order_no FROM tbl_po WHERE id IN ($sAdditionalPos) ORDER BY order_no";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
				$sPo .= (",".$objDb->getField($i, 0));


			if ($iStyle > 0)
				$sSQL = "SELECT style FROM tbl_styles WHERE id='$iStyle'";

			else
				$sSQL = "SELECT style FROM tbl_styles WHERE id=(SELECT style_id FROM tbl_po_colors WHERE po_id='$iPoId' LIMIT 1)";

			$objDb->query($sSQL);

			$sStyle = $objDb->getField(0, 0);


			@list($iStartHour, $iStartMinutes) = @explode(":", $sStartTime);
			@list($iEndHour, $iEndMinutes)     = @explode(":", $sEndTime);

			if ($iStartHour >= 12)
			{
				if ($iStartHour > 12)
					$iStartHour -= 12;

				$sStartAmPm  = "PM";
			}

			else
				$sStartAmPm = "AM";


			if ($iEndHour >= 12)
			{
				if ($iEndHour > 12)
					$iEndHour -= 12;

				$sEndAmPm  = "PM";
			}

			else
				$sEndAmPm = "AM";

			$sAuditDate = formatDate($sAuditDate);
			$sStartTime = (str_pad($iStartHour, 2, '0', STR_PAD_LEFT).":".str_pad($iStartMinutes, 2, '0', STR_PAD_LEFT)." ".$sStartAmPm);
			$sEndTime   = (str_pad($iEndHour, 2, '0', STR_PAD_LEFT).":".str_pad($iEndMinutes, 2, '0', STR_PAD_LEFT)." ".$sEndAmPm);
			$sAuditStage = getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'");


			switch ($sAuditResult)
			{
				case "P" : $sAuditResult = "Pass"; break;
				case "F" : $sAuditResult = "Fail"; break;
				case "H" : $sAuditResult = "Hold"; break;
				default  : $sAuditResult = "Grade {$sAuditResult}";
			}


			$sSQL = "SELECT quantity FROM tbl_po WHERE id='$iPoId'";
			$objDb->query($sSQL);

			$iOrderQty = $objDb->getField(0, 0);

			if ($sAdditionalPos != "")
			{
				$sSQL = "SELECT SUM(quantity) FROM tbl_po WHERE id IN ($sAdditionalPos)";
				$objDb->query($sSQL);

				$iOrderQty += $objDb->getField(0, 0);
			}

			$sOrderQty = formatNumber($iOrderQty, false);


			$sComments = (($sComments == "") ? "No comments given" : $sComments);
			$iDefects  = 0;
			$sDetails  = "";

			if ($iReportId == 6)
			{
				$sSQL = "SELECT * FROM tbl_gf_report_defects WHERE audit_id='$iAuditCode' ORDER BY id";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for($i = 0; $i < $iCount; $i ++)
				{
					$iDefects += $objDb->getField($i, 'defects');


					$sSQL = ("SELECT code, defect FROM tbl_defect_codes WHERE id='".$objDb->getField($i, 'code_id')."'");
					$objDb2->query($sSQL);

					$sDetails .= ($i + 1);
					$sDetails .= " ||";
					$sDetails .= $objDb2->getField(0, 'defect');
					$sDetails .= " ||";
					$sDetails .= ($objDb->getField($i, 'roll').'/'.$objDb->getField($i, 'panel'));
					$sDetails .= " ||";
					$sDetails .= $objDb->getField($i, 'defects');
					$sDetails .= " ||";
					$sDetails .= intval($objDb->getField($i, 'grade'));
					$sDetails .= " ||";
					$sDetails .= $objDb2->getField(0, 'code');
					$sDetails .= " ";


					if ($i < ($iCount - 1))
						$sDetails .= " |--|";
				}
			}

			else
			{
				$sSQL = "SELECT * FROM tbl_qa_report_defects WHERE audit_id='$iAuditCode' ORDER BY id";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for($i = 0; $i < $iCount; $i ++)
				{
					$iDefects += $objDb->getField($i, 'defects');


					$sSQL = ("SELECT code, defect FROM tbl_defect_codes WHERE id='".$objDb->getField($i, 'code_id')."'");
					$objDb2->query($sSQL);

					$sSQL = ("SELECT id, area FROM tbl_defect_areas WHERE id='".$objDb->getField($i, 'area_id')."'");
					$objDb3->query($sSQL);

					$sNature = "";


					if ($iReportId == 10)
					{
						switch ($objDb->getField($i, "nature"))
						{
							case 1   :  $sNature = "Major"; break;
							default  :  $sNature = "Minor"; break;
						}
					}

					else if ($iReportId == 11)
					{
						switch ($objDb->getField($i, "nature"))
						{
							case 2.5 : $sNature = "Major"; break;
							case 4   : $sNature = "Minor"; break;
							default  : $sNature = "Critical"; break;
						}
					}


					$sDetails .= ($i + 1);
					$sDetails .= " ||";
					$sDetails .= $objDb2->getField(0, 'defect');
					$sDetails .= " ||";
					$sDetails .= $objDb3->getField(0, 'area');
					$sDetails .= " ||";
					$sDetails .= intval($objDb->getField($i, 'defects'));
					$sDetails .= " ||";
					$sDetails .= $sNature;
					$sDetails .= " ||";
					$sDetails .= $objDb2->getField(0, 'code');
					$sDetails .= " ";


					if ($i < ($iCount - 1))
						$sDetails .= "|--|";
				}
			}


			$aResponse['Status'] = "OK";

			if ($iReportId == 7)
				$aResponse['Report'] = "{$sAuditor}|-|{$sVendor}|-|{$sLine}|-|{$sReport}|-|{$sStyle}|-|{$sPo}|-|{$sColors}|-|{$sAuditStatus}|-|{$sAuditDate}|-|{$sStartTime}|-|{$sEndTime}|-|{$sAuditStage}|-|{$sAuditResult}|-|{$sOrderQty}|-|{$iSampleSize}|-|{$iDefects}|-|{$iShipQty}|-|{$iReScreenQty}|-|{$iCartonsInspected}|-|{$fCartonsShipped}|-|{$sComments}|-|{$sDetails}|-|{$iReportId}|-|{$sSizeTitles} |-|{$sModelName} |-|{$sWorkingNo} |-|{$sFabricApproval}|-|{$sCounterSampleAppr}|-|{$sGarmentWashingTest}|-|{$sColorShade}|-|{$sAppearance}|-|{$sHandfeel}|-|{$sPrinting}|-|{$sEmbridery}|-|{$sFibreContent}|-|{$sCountryOfOrigin}|-|{$sCareInstruction}|-|{$sSizeKey}|-|{$sAdiComp}|-|{$sShippingMark}|-|{$sColourSizeQty}|-|{$sPolybag}|-|{$sHangtag}|-|{$sOclUpc}|-|{$iCutting}|-|{$iSewing}|-|{$iFinishing}|-|{$iPacking}|-|{$sCartonNoChecked} |-|{$iBeautifulProducts} ";

			else if ($iReportId == 11)
				$aResponse['Report'] = "{$sAuditor}|-|{$sVendor}|-|{$sLine}|-|{$sReport}|-|{$sStyle}|-|{$sPo}|-|{$sColors}|-|{$sAuditStatus}|-|{$sAuditDate}|-|{$sStartTime}|-|{$sEndTime}|-|{$sAuditStage}|-|{$sAuditResult}|-|{$sOrderQty}|-|{$iSampleSize}|-|{$iDefects}|-|{$iShipQty}|-|{$iReScreenQty}|-|{$iCartonsInspected}|-|{$fCartonsShipped}|-|{$sComments}|-|{$sDetails}|-|{$iReportId}|-|{$sSizeTitles} |-|{$sDescription} |-|{$sBatchSize} |-|{$fPackedPercent} |-|{$iGmtsDefective}|-|{$iMaxDefects}|-|{$sSeries} |-|{$sDepartment} |-|{$sBigProducts} |-|{$sBigSize} |-|{$sSmallProducts} |-|{$sSmallSize} |-|{$sAction} ";

			else
				$aResponse['Report'] = "{$sAuditor}|-|{$sVendor}|-|{$sLine}|-|{$sReport}|-|{$sStyle}|-|{$sPo}|-|{$sColors}|-|{$sAuditStatus}|-|{$sAuditDate}|-|{$sStartTime}|-|{$sEndTime}|-|{$sAuditStage}|-|{$sAuditResult}|-|{$sOrderQty}|-|{$iSampleSize}|-|{$iDefects}|-|{$iShipQty}|-|{$iReScreenQty}|-|{$iCartonsInspected}|-|{$fCartonsShipped}|-|{$sComments}|-|{$sDetails}|-|{$iReportId}|-|{$sSizeTitles} |-|{$sCartonSize}|-|{$iMaxDefects}|-|{$sApprovedSample}|-|{$sShippingMark}|-|{$sPackingCheck}";
		}
	}


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
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>