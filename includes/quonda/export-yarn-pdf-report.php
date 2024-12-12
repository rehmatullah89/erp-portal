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

	$sSQL = "SELECT *,
	                (SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _Po,
	                (SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _Auditor,
	                (SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor,
	                (SELECT name FROM tbl_auditor_groups WHERE id=tbl_qa_reports.group_id) AS _Group
	         FROM tbl_qa_reports WHERE id='$Id'";
	$objDb->query($sSQL);

	$sAuditCode     = $objDb->getField(0, "audit_code");
	$iReportId      = $objDb->getField(0, "report_id");
	$sVendor        = $objDb->getField(0, "_Vendor");
	$sAuditor       = $objDb->getField(0, "_Auditor");
	$sGroup         = $objDb->getField(0, "_Group");
	$iPo            = $objDb->getField(0, "po_id");
	$iAdditionalPos = $objDb->getField(0, "additional_pos");
	$sPo            = $objDb->getField(0, "_Po");
	$iStyle         = $objDb->getField(0, "style_id");
	$sAuditDate     = $objDb->getField(0, "audit_date");
	$sAuditStage    = $objDb->getField(0, "audit_stage");
	$sAuditResult   = $objDb->getField(0, "audit_result");
	$sColors        = $objDb->getField(0, "colors");
	$sDescription   = $objDb->getField(0, "description");
	$sStockStatus   = $objDb->getField(0, "stock_status");
	$sCustomSample  = $objDb->getField(0, "custom_sample");
	$iTotalGmts     = $objDb->getField(0, "total_gmts");
	$iGmtsDefective = $objDb->getField(0, "defective_gmts");
	$iMaxDefects    = $objDb->getField(0, "max_defects");
	$sComments      = $objDb->getField(0, "qa_comments");
	$iShipQty       = $objDb->getField(0, "ship_qty");
	$sSpecsSheet1   = $objDb->getField(0, 'specs_sheet_1');
	$sSpecsSheet2   = $objDb->getField(0, 'specs_sheet_2');
	$sSpecsSheet3   = $objDb->getField(0, 'specs_sheet_3');
	$sSpecsSheet3   = $objDb->getField(0, 'specs_sheet_3');
	$sSpecsSheet4   = $objDb->getField(0, 'specs_sheet_4');
	$sSpecsSheet5   = $objDb->getField(0, 'specs_sheet_5');
	$sSpecsSheet6   = $objDb->getField(0, 'specs_sheet_6');
	$sSpecsSheet7   = $objDb->getField(0, 'specs_sheet_7');
	$sSpecsSheet8   = $objDb->getField(0, 'specs_sheet_8');
	$sSpecsSheet9   = $objDb->getField(0, 'specs_sheet_9');
	$sSpecsSheet10  = $objDb->getField(0, 'specs_sheet_10');
	$iLine          = $objDb->getField(0, "line_id");
	$fDhu           = $objDb->getField(0, "dhu");

	$sAuditStage = getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'");

	switch ($sAuditResult)
	{
		case "P" : $sAuditResult = "Pass"; break;
		case "F" : $sAuditResult = "Fail"; break;
		case "H" : $sAuditResult = "Hold"; break;
	}


	$iQuantity      = getDbValue("SUM(quantity)", "tbl_po_quantities", "po_id='$iPo'");
	$sAdditionalPos = "";


	$sSQL = "SELECT CONCAT(order_no, ' ', order_status), quantity FROM tbl_po WHERE id IN ($iAdditionalPos) ORDER BY order_no";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sAdditionalPos .= (",".$objDb->getField($i, 0));
		$iQuantity      += $objDb->getField(0, 1);
	}


	$sSQL = "SELECT style, sub_brand_id, (SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand FROM tbl_styles WHERE id='$iStyle'";
	$objDb->query($sSQL);

	$sStyle = $objDb->getField(0, "style");
	$iBrand = $objDb->getField(0, "sub_brand_id");
	$sBrand = $objDb->getField(0, "_Brand");


	$sSQL = "SELECT * FROM tbl_yarn_qa_reports WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$sStyleName                 = $objDb->getField(0, "style_name");
	$sYarnContent               = $objDb->getField(0, "yarn_content");

	$sStyleConformity           = $objDb->getField(0, "style_conformity");
	$sMaterialConformity        = $objDb->getField(0, "material_conformity");
	$sShadeConformity           = $objDb->getField(0, "shade_conformity");

	$sPcYarnCount               = $objDb->getField(0, "pc_yarn_count");
	$sCartonPallet              = $objDb->getField(0, "carton_pallet");
	$fAvgGrossWeightG           = $objDb->getField(0, "avg_gross_weight_g");
	$fAvgGrossWeightKg          = $objDb->getField(0, "avg_gross_weight_kg");
	$fAvgGrossWeightLb          = $objDb->getField(0, "avg_gross_weight_lb");
	$fTareWeightG               = $objDb->getField(0, "tare_weight_g");
	$fTareWeightKg              = $objDb->getField(0, "tare_weight_kg");
	$fTareWeightLb              = $objDb->getField(0, "tare_weight_lb");
	$fNetWeightG                = $objDb->getField(0, "net_weight_g");
	$fNetWeightKg               = $objDb->getField(0, "net_weight_kg");
	$fNetWeightLb               = $objDb->getField(0, "net_weight_lb");
	$sRefSampleAvailable        = $objDb->getField(0, "ref_sample_available");
	$sSampleAvailable           = $objDb->getField(0, "sample_available");
	$sPcOther                   = $objDb->getField(0, "pc_other");
	$sPcReservations            = $objDb->getField(0, "pc_reservations");

	$sAffReservations           = $objDb->getField(0, "aff_reservations");

	$sQuantitiesSubmitted       = $objDb->getField(0, "quantities_submitted");
	$sMeasurementsFieldTests    = $objDb->getField(0, "measurements_field_tests");
	$sStyleMaterialColor        = $objDb->getField(0, "style_material_color");
	$sAppearanceFunctioning     = $objDb->getField(0, "appearance_functioning");
	$sPacking                   = $objDb->getField(0, "packing");
	$sMarkingLabel              = $objDb->getField(0, "marking_label");
	$sFactoryComments           = $objDb->getField(0, "factory_comments");
	$sExternalLabTesting        = $objDb->getField(0, "external_lab_testing");
	$sForRecordClient           = $objDb->getField(0, "for_record_client");
	$sSealedAtFactory           = $objDb->getField(0, "sealed_at_factory");
	$sReasonForNotTakingSamples = $objDb->getField(0, "reason_for_not_taking_samples");
	$iTotalCartonsSelected      = $objDb->getField(0, "total_cartons_selected");
	$iConesInspected            = $objDb->getField(0, "cones_inspected");
	$sDataMeasurementTest       = $objDb->getField(0, "data_measurement_test");



	$sSQL = "SELECT * FROM tbl_yarn_product_checks WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$sYarnCount        = $objDb->getField(0, "yarn_count");
	$sActualCountR     = $objDb->getField(0, "actual_count_r");
	$sActualCountG     = $objDb->getField(0, "actual_count_g");
	$sActualCountAc    = $objDb->getField(0, "actual_count_ac");
	$sActualCountP     = $objDb->getField(0, "actual_count_p");
	$sThinR            = $objDb->getField(0, "thin_r");
	$sThinG            = $objDb->getField(0, "thin_g");
	$sThinAc           = $objDb->getField(0, "thin_ac");
	$sThinP            = $objDb->getField(0, "thin_p");
	$sCvCountR         = $objDb->getField(0, "cv_count_r");
	$sCvCountG         = $objDb->getField(0, "cv_count_g");
	$sCvCountAc        = $objDb->getField(0, "cv_count_ac");
	$sCvCountP         = $objDb->getField(0, "cv_count_p");
	$sThickR           = $objDb->getField(0, "thick_r");
	$sThickG           = $objDb->getField(0, "thick_g");
	$sThickAc          = $objDb->getField(0, "thick_ac");
	$sThickP           = $objDb->getField(0, "thick_p");
	$sLeaStrengthR     = $objDb->getField(0, "lea_strength_r");
	$sLeaStrengthG     = $objDb->getField(0, "lea_strength_g");
	$sLeaStrengthAc    = $objDb->getField(0, "lea_strength_ac");
	$sLeaStrengthP     = $objDb->getField(0, "lea_strength_p");
	$sNepsR            = $objDb->getField(0, "neps_r");
	$sNepsG            = $objDb->getField(0, "neps_g");
	$sNepsAc           = $objDb->getField(0, "neps_ac");
	$sNepsP            = $objDb->getField(0, "neps_p");
	$sCvStrengthR      = $objDb->getField(0, "cv_strength_r");
	$sCvStrengthG      = $objDb->getField(0, "cv_strength_g");
	$sCvStrengthAc     = $objDb->getField(0, "cv_strength_ac");
	$sCvStrengthP      = $objDb->getField(0, "cv_strength_p");
	$sIpiValueR        = $objDb->getField(0, "ipi_value_r");
	$sIpiValueG        = $objDb->getField(0, "ipi_value_g");
	$sIpiValueAc       = $objDb->getField(0, "ipi_value_ac");
	$sIpiValueP        = $objDb->getField(0, "ipi_value_p");
	$sClspR            = $objDb->getField(0, "clsp_r");
	$sClspG            = $objDb->getField(0, "clsp_g");
	$sClspAc           = $objDb->getField(0, "clsp_ac");
	$sClspP            = $objDb->getField(0, "clsp_p");
	$sCvBuR            = $objDb->getField(0, "cv_bu_r");
	$sCvBuG            = $objDb->getField(0, "cv_bu_g");
	$sCvBuAc           = $objDb->getField(0, "cv_bu_ac");
	$sCvBuP            = $objDb->getField(0, "cv_bu_p");
	$sMinClspR         = $objDb->getField(0, "min_clsp_r");
	$sMinClspG         = $objDb->getField(0, "min_clsp_g");
	$sMinClspAc        = $objDb->getField(0, "min_clsp_ac");
	$sMinClspP         = $objDb->getField(0, "min_clsp_p");
	$sHairinessR       = $objDb->getField(0, "hairiness_r");
	$sHairinessG       = $objDb->getField(0, "hairiness_g");
	$sHairinessAc      = $objDb->getField(0, "hairiness_ac");
	$sHairinessP       = $objDb->getField(0, "hairiness_p");
	$sRkmR             = $objDb->getField(0, "rkm_r");
	$sRkmG             = $objDb->getField(0, "rkm_g");
	$sRkmAc            = $objDb->getField(0, "rkm_ac");
	$sRkmP             = $objDb->getField(0, "rkm_p");
	$sTpiR             = $objDb->getField(0, "tpi_r");
	$sTpiG             = $objDb->getField(0, "tpi_g");
	$sTpiAc            = $objDb->getField(0, "tpi_ac");
	$sTpiP             = $objDb->getField(0, "tpi_p");
	$sSyStrR           = $objDb->getField(0, "sy_str_r");
	$sSyStrG           = $objDb->getField(0, "sy_str_g");
	$sSyStrAc          = $objDb->getField(0, "sy_str_ac");
	$sSyStrP           = $objDb->getField(0, "sy_str_p");
	$sCvR              = $objDb->getField(0, "cv_r");
	$sCvG              = $objDb->getField(0, "cv_g");
	$sCvAc             = $objDb->getField(0, "cv_ac");
	$sCvP              = $objDb->getField(0, "cv_p");
	$sCountMaxR        = $objDb->getField(0, "count_max_r");
	$sCountMaxG        = $objDb->getField(0, "count_max_g");
	$sCountMaxAc       = $objDb->getField(0, "count_max_ac");
	$sCountMaxP        = $objDb->getField(0, "count_max_p");
	$sElongationR      = $objDb->getField(0, "elongation_r");
	$sElongationG      = $objDb->getField(0, "elongation_g");
	$sElongationAc     = $objDb->getField(0, "elongation_ac");
	$sElongationP      = $objDb->getField(0, "elongation_p");
	$sCountMinR        = $objDb->getField(0, "count_min_r");
	$sCountMinG        = $objDb->getField(0, "count_min_g");
	$sCountMinAc       = $objDb->getField(0, "count_min_ac");
	$sCountMinP        = $objDb->getField(0, "count_min_p");
	$sElongationCvR    = $objDb->getField(0, "elongation_cv_r");
	$sElongationCvG    = $objDb->getField(0, "elongation_cv_g");
	$sElongationCvAc   = $objDb->getField(0, "elongation_cv_ac");
	$sElongationCvP    = $objDb->getField(0, "elongation_cv_p");
	$sConeMoistureR    = $objDb->getField(0, "cone_moisture_r");
	$sConeMoistureG    = $objDb->getField(0, "cone_moisture_g");
	$sConeMoistureAc   = $objDb->getField(0, "cone_moisture_ac");
	$sConeMoistureP    = $objDb->getField(0, "cone_moisture_p");
	$sUcvmR            = $objDb->getField(0, "ucvm_r");
	$sUcvmG            = $objDb->getField(0, "ucvm_g");
	$sUcvmAc           = $objDb->getField(0, "ucvm_ac");
	$sUcvmP            = $objDb->getField(0, "ucvm_p");
	$sComberNoilR      = $objDb->getField(0, "comber_noil_r");
	$sComberNoilG      = $objDb->getField(0, "comber_noil_g");
	$sComberNoilAc     = $objDb->getField(0, "comber_noil_ac");
	$sComberNoilP      = $objDb->getField(0, "comber_noil_p");
	$sCvm10mR          = $objDb->getField(0, "cvm_10m_r");
	$sCvm10mG          = $objDb->getField(0, "cvm_10m_g");
	$sCvm10mAc         = $objDb->getField(0, "cvm_10m_ac");
	$sCvm10mP          = $objDb->getField(0, "cvm_10m_p");
	$sTpiCvR           = $objDb->getField(0, "tpi_cv_r");
	$sTpiCvG           = $objDb->getField(0, "tpi_cv_g");
	$sTpiCvAc          = $objDb->getField(0, "tpi_cv_ac");
	$sTpiCvP           = $objDb->getField(0, "tpi_cv_p");

	$sFcLength         = $objDb->getField(0, "fc_length");
	$sFcUiUr           = $objDb->getField(0, "fc_ui_ur");
	$sFcFfiSfi         = $objDb->getField(0, "fc_ffi_sfi");
	$sFcStrength       = $objDb->getField(0, "fc_strength");
	$sFcMicValue       = $objDb->getField(0, "fc_mic_value");
	$sFcMicRange       = $objDb->getField(0, "fc_mic_range");
	$sFcColorGrade     = $objDb->getField(0, "fc_color_grade");
	$sFcNoOfLots       = $objDb->getField(0, "fc_no_of_lots");
	$sFcCottonStock    = $objDb->getField(0, "fc_cotton_stock");
	$sFcTrash          = $objDb->getField(0, "fc_trash");
	$sFcColor          = $objDb->getField(0, "fc_color");
	$sFcMoisture       = $objDb->getField(0, "fc_moisture");
	$sFcContamination  = $objDb->getField(0, "fc_contamination");

	$sFrLength         = $objDb->getField(0, "fr_length");
	$sFrDenier         = $objDb->getField(0, "fr_denier");
	$sFrColor          = $objDb->getField(0, "fr_color");
	$sFrPolyester      = $objDb->getField(0, "fr_polyester");
	$sFrCotton         = $objDb->getField(0, "fr_cotton");
	$sPrLength         = $objDb->getField(0, "pr_length");
	$sPrDenier         = $objDb->getField(0, "pr_denier");
	$sPrColor          = $objDb->getField(0, "pr_color");
	$sPrPolyester      = $objDb->getField(0, "pr_polyester");
	$sPrCotton         = $objDb->getField(0, "pr_cotton");

	$sAcsN             = $objDb->getField(0, "acs_n");
	$sAcsSds           = $objDb->getField(0, "acs_sds");
	$sAcsLls           = $objDb->getField(0, "acs_lls");
	$sAcsTdl           = $objDb->getField(0, "acs_tdl");
	$sAcsFdd           = $objDb->getField(0, "acs_fdd");
	$sAcsL             = $objDb->getField(0, "acs_l");
	$sAcsYf            = $objDb->getField(0, "acs_yf");

	$fAutoConeSpeed    = $objDb->getField(0, "auto_cone_speed");
	$fConeLength       = $objDb->getField(0, "cone_length");
	$fConeWeight       = $objDb->getField(0, "cone_weight");



	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/yarn-p1.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage( );
	$objPdf->useTemplate($iTemplateId, 0, 0);

                // QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));
        $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 165, 2, 20);
        $objPdf->SetFont('Arial', '', 5);
	$objPdf->SetTextColor(50, 50, 50);
        $objPdf->Text(164, 22, "Audit Code: {$sAuditCode}");
        
	$objPdf->SetAutoPageBreak(FALSE, 0);
	$objPdf->Text(55, 31.6, $sPo);
	$objPdf->Text(55, 36.2, $sAuditor);
	$objPdf->Text(55, 40.8, $sAuditDate);
	$objPdf->Text(55, 45.4, $sYarnContent);
	$objPdf->Text(55, 50.0, $sStyleName);
	$objPdf->Text(55, 54.6, $sDescription);

	$objPdf->Text(130, 31.6, $sAuditStage);
	$objPdf->Text(130, 36.2, $sStockStatus);
        $objPdf->SetXY(127.5, 38);
	$objPdf->MultiCell(52, 2, $sColors);
	$objPdf->Text(130, 45.4, formatNumber($iQuantity, false));
	$objPdf->Text(130, 50.0, formatNumber($iShipQty, false));

	$objPdf->Text(45, 68.1, $sYarnCount);


	$objPdf->Text(67, 80.8, $sActualCountR);
	$objPdf->Text(101, 80.8, $sActualCountG);
	$objPdf->Text(131, 80.8, $sActualCountAc);
	$objPdf->Text(164, 80.8, $sActualCountP);

	$objPdf->Text(67, 85.3, $sCvCountR);
	$objPdf->Text(101, 85.3, $sCvCountG);
	$objPdf->Text(131, 85.3, $sCvCountAc);
	$objPdf->Text(164, 85.3, $sCvCountP);

	$objPdf->Text(67, 89.7, $sLeaStrengthR);
	$objPdf->Text(101, 89.7, $sLeaStrengthG);
	$objPdf->Text(131, 89.7, $sLeaStrengthAc);
	$objPdf->Text(164, 89.7, $sLeaStrengthP);

	$objPdf->Text(67, 94.1, $sCvStrengthR);
	$objPdf->Text(101, 94.1, $sCvStrengthG);
	$objPdf->Text(131, 94.1, $sCvStrengthAc);
	$objPdf->Text(164, 94.1, $sCvStrengthP);

	$objPdf->Text(67, 98.5, $sClspR);
	$objPdf->Text(101, 98.5, $sClspG);
	$objPdf->Text(131, 98.5, $sClspAc);
	$objPdf->Text(164, 98.5, $sClspP);

	$objPdf->Text(67, 102.8, $sMinClspR);
	$objPdf->Text(101, 102.8, $sMinClspG);
	$objPdf->Text(131, 102.8, $sMinClspAc);
	$objPdf->Text(164, 102.8, $sMinClspP);

	$objPdf->Text(67, 107.2, $sRkmR);
	$objPdf->Text(101, 107.2, $sRkmG);
	$objPdf->Text(135, 107.2, $sRkmAc);
	$objPdf->Text(164, 107.2, $sRkmP);

	$objPdf->Text(67, 111.5, $sSyStrR);
	$objPdf->Text(101, 111.5, $sSyStrG);
	$objPdf->Text(131, 111.5, $sSyStrAc);
	$objPdf->Text(164, 111.5, $sSyStrP);

	$objPdf->Text(67, 115.8, $sCvR);
	$objPdf->Text(101, 115.8, $sCvG);
	$objPdf->Text(131, 115.8, $sCvAc);
	$objPdf->Text(164, 115.8, $sCvP);

	$objPdf->Text(67, 120.2, $sElongationR);
	$objPdf->Text(101, 120.2, $sElongationG);
	$objPdf->Text(131, 120.2, $sElongationAc);
	$objPdf->Text(164, 120.2, $sElongationP);

	$objPdf->Text(67, 124.6, $sElongationCvR);
	$objPdf->Text(101, 124.6, $sElongationCvG);
	$objPdf->Text(131, 124.6, $sElongationCvAc);
	$objPdf->Text(164, 124.6, $sElongationCvP);

	$objPdf->Text(67, 129.0, $sUcvmR);
	$objPdf->Text(101, 129.0, $sUcvmG);
	$objPdf->Text(131, 129.0, $sUcvmAc);
	$objPdf->Text(164, 129.0, $sUcvmP);

	$objPdf->Text(67, 133.4, $sCvm10mR);
	$objPdf->Text(101, 133.4, $sCvm10mG);
	$objPdf->Text(131, 133.4, $sCvm10mAc);
	$objPdf->Text(164, 133.4, $sCvm10mP);

	$objPdf->Text(67, 137.7, $sThinR);
	$objPdf->Text(101, 137.7, $sThinG);
	$objPdf->Text(131, 137.7, $sThinAc);
	$objPdf->Text(164, 137.7, $sThinP);

	$objPdf->Text(67, 141.9, $sThickR);
	$objPdf->Text(101, 141.9, $sThickG);
	$objPdf->Text(131, 141.9, $sThickAc);
	$objPdf->Text(164, 141.9, $sThickP);

	$objPdf->Text(67, 146.1, $sNepsR);
	$objPdf->Text(101, 146.1, $sNepsG);
	$objPdf->Text(131, 146.1, $sNepsAc);
	$objPdf->Text(164, 146.1, $sNepsP);

	$objPdf->Text(67, 150.5, $sIpiValueR);
	$objPdf->Text(101, 150.5, $sIpiValueG);
	$objPdf->Text(131, 150.5, $sIpiValueAc);
	$objPdf->Text(164, 150.5, $sIpiValueP);

	$objPdf->Text(67, 154.9, $sCvBuR);
	$objPdf->Text(101, 154.9, $sCvBuG);
	$objPdf->Text(131, 154.9, $sCvBuAc);
	$objPdf->Text(164, 154.9, $sCvBuP);

	$objPdf->Text(67, 159.3, $sHairinessR);
	$objPdf->Text(101, 159.3, $sHairinessG);
	$objPdf->Text(131, 159.3, $sHairinessAc);
	$objPdf->Text(164, 159.3, $sHairinessP);

	$objPdf->Text(67, 163.7, $sTpiR);
	$objPdf->Text(101, 163.7, $sTpiG);
	$objPdf->Text(131, 163.7, $sTpiAc);
	$objPdf->Text(164, 163.7, $sTpiP);

	$objPdf->Text(67, 168.1, $sCountMaxR);
	$objPdf->Text(101, 168.1, $sCountMaxG);
	$objPdf->Text(131, 168.1, $sCountMaxAc);
	$objPdf->Text(164, 168.1, $sCountMaxP);

	$objPdf->Text(67, 172.4, $sCountMinR);
	$objPdf->Text(101, 172.4, $sCountMinG);
	$objPdf->Text(131, 172.4, $sCountMinAc);
	$objPdf->Text(164, 172.4, $sCountMinP);

	$objPdf->Text(67, 176.8, $sConeMoistureR);
	$objPdf->Text(101, 176.8, $sConeMoistureG);
	$objPdf->Text(131, 176.8, $sConeMoistureAc);
	$objPdf->Text(164, 176.8, $sConeMoistureP);

	$objPdf->Text(67, 181.2, $sComberNoilR);
	$objPdf->Text(101, 181.2, $sComberNoilG);
	$objPdf->Text(131, 181.2, $sComberNoilAc);
	$objPdf->Text(164, 181.2, $sComberNoilP);

	$objPdf->Text(67, 185.6, $sTpiCvR);
	$objPdf->Text(101, 185.6, $sTpiCvG);
	$objPdf->Text(131, 185.6, $sTpiCvAc);
	$objPdf->Text(164, 185.6, $sTpiCvP);


	$objPdf->Text(57, 195.4, $sFcLength);
	$objPdf->Text(57, 199.2, $sFcUiUr);
	$objPdf->Text(57, 203.0, $sFcFfiSfi);
	$objPdf->Text(57, 206.8, $sFcStrength);
	$objPdf->Text(57, 210.5, $sFcMicValue);
	$objPdf->Text(57, 214.2, $sFcMicRange);
	$objPdf->Text(57, 218.0, $sFcColorGrade);
	$objPdf->Text(57, 221.7, $sFcNoOfLots);
	$objPdf->Text(57, 225.3, $sFcCottonStock);
	$objPdf->Text(57, 229.1, $sFcTrash);
	$objPdf->Text(57, 232.8, $sFcColor);
	$objPdf->Text(57, 236.7, $sFcMoisture);
	$objPdf->Text(57, 240.5, $sFcContamination);


	$objPdf->Text(110, 195.4, $sFrLength);
	$objPdf->Text(110, 199.2, $sFrDenier);
	$objPdf->Text(110, 203.0, $sFrColor);
	$objPdf->Text(110, 206.8, $sFrPolyester);
	$objPdf->Text(110, 210.6, $sFrCotton);

	$objPdf->Text(148, 195.4, $sPrLength);
	$objPdf->Text(148, 199.2, $sPrDenier);
	$objPdf->Text(148, 203.0, $sPrColor);
	$objPdf->Text(148, 206.8, $sPrPolyester);
	$objPdf->Text(148, 210.6, $sPrCotton);

	$objPdf->Text(110, 225.5, $sAcsN);
	$objPdf->Text(110, 229.3, $sAcsSds);
	$objPdf->Text(110, 233.0, $sAcsLls);
	$objPdf->Text(110, 236.7, $sAcsTdl);
	$objPdf->Text(110, 240.5, $sAcsFdd);
	$objPdf->Text(110, 244.2, $sAcsL);
	$objPdf->Text(110, 247.9, $sAcsYf);
	$objPdf->Text(110, 251.6, "{$fAutoConeSpeed} m/m");
	$objPdf->Text(110, 255.3, "{$fConeLength} m");
	$objPdf->Text(110, 259.0, "{$fConeWeight} kg");




	//////////// Page 2  //////////////

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/yarn-p2.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage( );
	$objPdf->useTemplate($iTemplateId, 0, 0);

                // QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));
        $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 178, 2, 21);
        $objPdf->SetFont('Arial', '', 5);
	$objPdf->SetTextColor(50, 50, 50);
        $objPdf->Text(179, 23.5, "Audit Code: {$sAuditCode}");
        
	$objPdf->SetAutoPageBreak(FALSE, 0);
	$objPdf->SetFont('Arial', '', 5);
	$objPdf->SetTextColor(50, 50, 50);


	$objPdf->Text(62, 31.2, (($sStyleConformity == "Y") ? "Yes" : (($sStyleConformity == "N") ? "No" : "No Ref sample/Specification available")));
	$objPdf->Text(62, 35.4, (($sMaterialConformity == "Y") ? "Yes" : (($sMaterialConformity == "N") ? "No" : "No Ref sample/Specification available")));
	$objPdf->Text(62, 38.8, (($sShadeConformity == "Y") ? "Yes" : (($sShadeConformity == "N") ? "No" : "No Ref sample/Specification available")));

	$objPdf->Text(60, 47.2, $sPcYarnCount);
	$objPdf->Text(60, 49.9, $sCartonPallet);


	$sSQL = "SELECT * FROM tbl_yarn_product_conformity WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$iTop   = 65.0;

	for ($i = 0; $i < $iCount; $i ++)
	{
		$objPdf->Text(33, ($iTop + ($i * 3.45)), ($i + 1));
		$objPdf->Text(62, ($iTop + ($i * 3.45)), $objDb->getField($i,  'carton_no'));
		$objPdf->Text(118, ($iTop + ($i * 3.45)), $objDb->getField($i,  'carton_weight'));
		$objPdf->Text(154, ($iTop + ($i * 3.45)), $objDb->getField($i,  'cone_1_weight'));
		$objPdf->Text(172, ($iTop + ($i * 3.45)), $objDb->getField($i,  'cone_2_weight'));
		$objPdf->Text(189, ($iTop + ($i * 3.45)), $objDb->getField($i,  'cone_3_weight'));
	}


	$objPdf->Text(62, 151.7, "{$fAvgGrossWeightG} g / {$fAvgGrossWeightKg} kg / {$fAvgGrossWeightLb} lb");
	$objPdf->Text(62, 155.1, "{$fTareWeightG} g / {$fTareWeightKg} kg / {$fTareWeightLb} lb");
	$objPdf->Text(62, 158.8, "{$fNetWeightG} g / {$fNetWeightKg} kg / {$fNetWeightLb} lb");
	$objPdf->Text(62, 162.5, ((($sRefSampleAvailable == "Y") ? "Yes" : (($sRefSampleAvailable == "N") ? "No" : ""))." - {$sSampleAvailable}"));

	$objPdf->SetXY(60.5, 164.6);
	$objPdf->MultiCell(135, 3.5, $sPcOther);

	$objPdf->SetXY(60.5, 177.6);
	$objPdf->MultiCell(135, 3.5, $sPcReservations);




	//////////// Page 3  //////////////

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/yarn-p3.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage( );
	$objPdf->useTemplate($iTemplateId, 0, 0);

                // QR Code
	QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));
        $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 180, 2, 18);
        $objPdf->SetFont('Arial', '', 5);
	$objPdf->SetTextColor(50, 50, 50);
        $objPdf->Text(179, 19.8, "Audit Code: {$sAuditCode}");
        
	$objPdf->SetAutoPageBreak(FALSE, 0);
	$objPdf->SetFont('Arial', '', 5);
	$objPdf->SetTextColor(50, 50, 50);


	$sSQL = "SELECT * FROM tbl_yarn_appearance WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$iTop   = 32.8;

	for ($i = 0; $i < $iCount; $i ++)
	{
		$objPdf->Text(26, ($iTop + ($i * 3.6)), $objDb->getField($i, 'defect'));
		$objPdf->Text(132, ($iTop + ($i * 3.6)), $objDb->getField($i, 'major'));
		$objPdf->Text(181, ($iTop + ($i * 3.6)), $objDb->getField($i, 'minor'));
	}

	$objPdf->Text(67, 55.0, (($sCustomSample == "Y") ? "CUSTOM" : $iTotalGmts));
	$objPdf->Text(67, 59.8, (($iMaxDefects > 0) ? $iMaxDefects : ""));
	$objPdf->Text(67, 64.6, $iGmtsDefective);

	$objPdf->SetXY(65, 66.5);
	$objPdf->MultiCell(135, 3.5, $sAffReservations);



	$sSQL = "SELECT * FROM tbl_yarn_packing WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$sPackingDetail                = $objDb->getField(0, "packing_detail");
	$sCartonDimension1             = $objDb->getField(0, "carton_dimension1");
	$sCartonDimension2             = $objDb->getField(0, "carton_dimension2");
	$sIndividualPackingConformity1 = $objDb->getField(0, "individual_packing_conformity1");
	$sIndividualPackingConformity2 = $objDb->getField(0, "individual_packing_conformity2");
	$sPaperConeConformity1         = $objDb->getField(0, "paper_cone_conformity1");
	$sPaperConeConformity2         = $objDb->getField(0, "paper_cone_conformity2");
	$sInnerPackingConformity1      = $objDb->getField(0, "inner_packing_conformity1");
	$sInnerPackingConformity2      = $objDb->getField(0, "inner_packing_conformity2");
	$sAssortmentFoundCorrect1      = $objDb->getField(0, "assortment_found_correct1");
	$sAssortmentFoundCorrect2      = $objDb->getField(0, "assortment_found_correct2");


	$objPdf->SetXY(65, 80);
	$objPdf->MultiCell(135, 3.5, $sPackingDetail);

	$objPdf->Text(67, 89.5, ($sCartonDimension1.' '.$sCartonDimension2));
	$objPdf->Text(67, 93.0, ("(".(($sIndividualPackingConformity1 == "Y") ? "Yes" : (($sIndividualPackingConformity1 == "N") ? "No" : "")).")  ".$sIndividualPackingConformity2));
	$objPdf->Text(67, 96.5, ("(".(($sPaperConeConformity1 == "Y") ? "Yes" : (($sPaperConeConformity1 == "N") ? "No" : "")).")  ".$sPaperConeConformity2));
	$objPdf->Text(67, 100, ("(".(($sInnerPackingConformity1 == "Y") ? "Yes" : (($sInnerPackingConformity1 == "N") ? "No" : "")).")  ".$sInnerPackingConformity2));
	$objPdf->Text(67, 103.5, ("(".(($sAssortmentFoundCorrect1 == "Y") ? "Yes" : (($sAssortmentFoundCorrect1 == "N") ? "No" : "")).")  ".$sAssortmentFoundCorrect2));



	$sSQL = "SELECT * FROM tbl_yarn_marking_label WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$sBarCodeConformity1      = $objDb->getField(0, "bar_code_conformity1");
	$sBarCodeConformity2      = $objDb->getField(0, "bar_code_conformity2");
	$sShippingMarkConformity1 = $objDb->getField(0, "shipping_mark_conformity1");
	$sShippingMarkConformity2 = $objDb->getField(0, "shipping_mark_conformity2");
	$sOtherMarks1             = $objDb->getField(0, "other_marks1");
	$sOtherMarks2             = $objDb->getField(0, "other_marks2");
	$sSideMarkConformity1     = $objDb->getField(0, "side_mark_conformity1");
	$sSideMarkConformity2     = $objDb->getField(0, "side_mark_conformity2");
	$sCountLabel1             = $objDb->getField(0, "count_label1");
	$sCountLabel2             = $objDb->getField(0, "count_label2");
	$sBalingStrip1            = $objDb->getField(0, "baling_strip1");
	$sBalingStrip2            = $objDb->getField(0, "baling_strip2");
	$sBrandName1              = $objDb->getField(0, "brand_name1");
	$sBrandName2              = $objDb->getField(0, "brand_name2");
	$sOther1                  = $objDb->getField(0, "other1");
	$sOther2                  = $objDb->getField(0, "other2");


	$objPdf->Text(67, 115.2, ("(".(($sBarCodeConformity1 == "Y") ? "Yes" : (($sBarCodeConformity1 == "N") ? "No" : "")).")  ".$sBarCodeConformity2));
	$objPdf->Text(67, 118.7, ("(".(($sShippingMarkConformity1 == "Y") ? "Yes" : (($sShippingMarkConformity1 == "N") ? "No" : "")).")  ".$sShippingMarkConformity2));
	$objPdf->Text(67, 122.2, ("(".(($sOtherMarks1 == "Y") ? "Yes" : (($sOtherMarks1 == "N") ? "No" : "")).")  ".$sOtherMarks2));
	$objPdf->Text(67, 125.7, ("(".(($sSideMarkConformity1 == "Y") ? "Yes" : (($sSideMarkConformity1 == "N") ? "No" : "")).")  ".$sSideMarkConformity2));
	$objPdf->Text(67, 129.2, ("(".(($sCountLabel1 == "Y") ? "Yes" : (($sCountLabel1 == "N") ? "No" : "")).")  ".$sCountLabel2));
	$objPdf->Text(67, 132.7, ("(".(($sBalingStrip1 == "Y") ? "Yes" : (($sBalingStrip1 == "N") ? "No" : "")).")  ".$sBalingStrip2));
	$objPdf->Text(67, 136.3, ("(".(($sBrandName1 == "Y") ? "Yes" : (($sBrandName1 == "N") ? "No" : "")).")  ".$sBrandName2));
	$objPdf->Text(67, 139.8, ("(".(($sOther1 == "Y") ? "Yes" : (($sOther1 == "N") ? "No" : "")).")  ".$sOther2));

	$objPdf->Text(67, 151.2, (($sQuantitiesSubmitted == "Y") ? "Conform" : (($sQuantitiesSubmitted == "N") ? "Not Conform" : "")));
	$objPdf->Text(67, 154.4, (($sMeasurementsFieldTests == "Y") ? "Conform" : (($sMeasurementsFieldTests == "N") ? "Not Conform" : "")));
	$objPdf->Text(67, 157.7, (($sStyleMaterialColor == "Y") ? "Conform" : (($sStyleMaterialColor == "N") ? "Not Conform" : "")));
	$objPdf->Text(67, 160.8, (($sAppearanceFunctioning == "Y") ? "Conform" : (($sAppearanceFunctioning == "N") ? "Not Conform" : "")));
	$objPdf->Text(67, 164.1, (($sPacking == "Y") ? "Conform" : (($sPacking == "N") ? "Not Conform" : "")));
	$objPdf->Text(67, 167.4, (($sMarkingLabel == "Y") ? "Conform" : (($sMarkingLabel == "N") ? "Not Conform" : "")));


	$objPdf->SetFont('Arial', 'B', 5);

	$objPdf->SetXY(65, 169);
	$objPdf->MultiCell(135, 3.5, $sComments);

	$objPdf->SetXY(65, 178.0);
	$objPdf->MultiCell(135, 3.5, $sFactoryComments);


	$objPdf->SetFont('Arial', '', 5);

	$objPdf->Text(67, 189.4, $sExternalLabTesting);
	$objPdf->Text(67, 192.5, $sForRecordClient);
	$objPdf->Text(67, 195.8, $sSealedAtFactory);
	$objPdf->Text(67, 199.0, $sReasonForNotTakingSamples);

	$objPdf->SetFont('Arial', 'B', 5);

	$objPdf->Text(67, 202.2, $sAuditResult);


	$objPdf->SetFont('Arial', '', 5);

	$objPdf->Text(67, 205.4, (($iTotalCartonsSelected > 0) ? $iTotalCartonsSelected : ""));
	$objPdf->Text(67, 208.5, (($iConesInspected > 0) ? $iConesInspected : ""));
	$objPdf->Text(67, 211.9, $sDataMeasurementTest);



	if ($sSpecsSheet1 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet1))
	{
		$objPdf->addPage( );
                
                // QR Code
                QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));
                $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 180, 2, 18);
                $objPdf->SetFont('Arial', '', 6);
                $objPdf->SetTextColor(50, 50, 50);
                $objPdf->Text(179, 19.8, "Audit Code: {$sAuditCode}");
        
		$objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet1), 10, 10, 190);
	}

	if ($sSpecsSheet2 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet2))
	{
		$objPdf->addPage( );
                
                // QR Code
                QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));
                $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 180, 2, 18);
                $objPdf->SetFont('Arial', '', 6);
                $objPdf->SetTextColor(50, 50, 50);
                $objPdf->Text(179, 19.8, "Audit Code: {$sAuditCode}");
                
		$objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet2), 10, 10, 190);
	}

	if ($sSpecsSheet3 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet3))
	{
		$objPdf->addPage( );
                
                // QR Code
                QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));
                $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 180, 2, 18);
                $objPdf->SetFont('Arial', '', 6);
                $objPdf->SetTextColor(50, 50, 50);
                $objPdf->Text(179, 19.8, "Audit Code: {$sAuditCode}");
                
		$objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet3), 10, 10, 190);
	}

	if ($sSpecsSheet4 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet4))
	{
		$objPdf->addPage( );
                
                // QR Code
                QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));
                $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 180, 2, 18);
                $objPdf->SetFont('Arial', '', 6);
                $objPdf->SetTextColor(50, 50, 50);
                $objPdf->Text(179, 19.8, "Audit Code: {$sAuditCode}");
                
		$objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet4), 10, 10, 190);
	}

	if ($sSpecsSheet5 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet5))
	{
		$objPdf->addPage( );
		
                // QR Code
                QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));
                $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 180, 2, 18);
                $objPdf->SetFont('Arial', '', 6);
                $objPdf->SetTextColor(50, 50, 50);
                $objPdf->Text(179, 19.8, "Audit Code: {$sAuditCode}");
                
                $objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet5), 10, 10, 190);
	}

	if ($sSpecsSheet6 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet6))
	{
		$objPdf->addPage( );
		
                // QR Code
                QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));
                $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 180, 2, 18);
                $objPdf->SetFont('Arial', '', 6);
                $objPdf->SetTextColor(50, 50, 50);
                $objPdf->Text(179, 19.8, "Audit Code: {$sAuditCode}");
                
                $objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet6), 10, 10, 190);
	}

	if ($sSpecsSheet7 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet7))
	{
		$objPdf->addPage( );
		
                // QR Code
                QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));
                $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 180, 2, 18);
                $objPdf->SetFont('Arial', '', 6);
                $objPdf->SetTextColor(50, 50, 50);
                $objPdf->Text(179, 19.8, "Audit Code: {$sAuditCode}");
                
                $objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet7), 10, 10, 190);
	}

	if ($sSpecsSheet8 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet8))
	{
		$objPdf->addPage( );
		
                // QR Code
                QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));
                $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 180, 2, 18);
                $objPdf->SetFont('Arial', '', 6);
                $objPdf->SetTextColor(50, 50, 50);
                $objPdf->Text(179, 19.8, "Audit Code: {$sAuditCode}");
                
                $objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet8), 10, 10, 190);
	}

	if ($sSpecsSheet9 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet9))
	{
		$objPdf->addPage( );
		
                // QR Code
                QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));
                $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 180, 2, 18);
                $objPdf->SetFont('Arial', '', 6);
                $objPdf->SetTextColor(50, 50, 50);
                $objPdf->Text(179, 19.8, "Audit Code: {$sAuditCode}");
                
                $objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet9), 10, 10, 190);
	}

	if ($sSpecsSheet10 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet10))
	{
		$objPdf->addPage( );
		
                // QR Code
                QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));
                $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 180, 2, 18);
                $objPdf->SetFont('Arial', '', 6);
                $objPdf->SetTextColor(50, 50, 50);
                $objPdf->Text(179, 19.8, "Audit Code: {$sAuditCode}");
                
                $objPdf->Image(($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet10), 10, 10, 190);
	}



	// QA Pictures
    @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

	$sPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_001_*.*");
	$sPictures = @array_merge($sPictures, @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_*.*"));
	$sPictures = @array_map("strtoupper", $sPictures);
	$sPictures = @array_unique($sPictures);

	$sTemp = array( );

	foreach ($sPictures as $sPicture)
		$sTemp[] = $sPicture;

	$sPictures = $sTemp;



	$objPdf->SetFillColor(255, 255, 0);
	$objPdf->SetFont('Arial', '', 7);

	for ($i = 0; $i < count($sPictures); $i ++)
	{
		$sName  = @strtoupper($sPictures[$i]);
		$sName  = @basename($sName, ".JPG");
		$sParts = @explode("_", $sName);

		$sDefectCode = $sParts[1];
		$sAreaCode   = $sParts[2];


		$sSQL = "SELECT defect, code,
						(SELECT type FROM tbl_defect_types WHERE id=dc.type_id) AS _Type
				 FROM tbl_defect_codes dc
				 WHERE code='$sDefectCode' AND report_id='$iReportId'";

		$objDb->query($sSQL);

		$sDefect     = $objDb->getField(0, 0);
		$sDefectCode = $objDb->getField(0, 1);
		$sType       = $objDb->getField(0, 2);


		if (($i % 6) == 0){
                    $objPdf->addPage( );

                    // QR Code
                    QRcode::png("http://portal.3-tree.com/kpis/{$sAuditCode}/", ($sBaseDir.TEMP_DIR."{$sAuditCode}.png"));
                    $objPdf->Image(($sBaseDir.TEMP_DIR."{$sAuditCode}.png"), 180, 2, 18);
                    $objPdf->SetFont('Arial', '', 6);
                    $objPdf->SetTextColor(50, 50, 50);
                    $objPdf->Text(179, 19.8, "Audit Code: {$sAuditCode}");
                
                }

		$iLeft = 5;
		$iTop  = 20;

		if (($i % 6) == 1 || ($i % 6) == 3 || ($i % 6) == 5)
			$iLeft = 107;

		if (($i % 6) == 2 || ($i % 6) == 3)
			$iTop = 115;

		else if (($i % 6) == 4 || ($i % 6) == 5)
			$iTop = 210;


		$objPdf->Image($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sPictures[$i]), $iLeft, $iTop, 98);
	}
?>