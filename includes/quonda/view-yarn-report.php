<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  MATRIX Customer Portal                                                                   **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.apparelco.com                                                              **
	**                                                                                           **
	**  Copyright 2008-15 (C) Matrix Sourcing                                                    **
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
?>
		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
			    <td width="110">Order No</td>
			    <td width="20" align="center">:</td>
			    <td><?= $sPO ?></td>
			  </tr>

			  <tr>
			    <td>Auditor</td>
			    <td align="center">:</td>
			    <td><?= $sAuditor ?></td>
			  </tr>

			  <tr>
			    <td>Group</td>
			    <td align="center">:</td>
			    <td><?= $sGroup ?></td>
			  </tr>

<?
	$sPos = "";

	$sSQL = "SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id IN ($sAdditionalPos) ORDER BY order_no";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (", ".$objDb->getField($i, 0));
	}
?>
			  <tr valign="top">
			    <td>PO(s)</td>
			    <td align="center">:</td>
			    <td><?= ($sPO.$sPos) ?></td>
			  </tr>

			  <tr valign="top">
			    <td>Style</td>
			    <td align="center">:</td>
			    <td><?= getDbValue("style", "tbl_styles", "id='$iStyle'") ?></td>
			  </tr>

			  <tr>
				<td>Style Name/Count</td>
				<td align="center">:</td>
				<td><?= $sStyleName ?></td>
			  </tr>

			  <tr>
				<td>Yarn Content</td>
				<td align="center">:</td>
				<td><?= $sYarnContent ?></td>
			  </tr>

			  <tr>
			    <td>Audit Date</td>
			    <td align="center">:</td>
			    <td><?= formatDate($sAuditDate) ?></td>
			  </tr>

			  <tr>
			    <td>Audit Stage</td>
			    <td align="center">:</td>
			    <td><?= $sAuditStagesList[$sAuditStage] ?></td>
			  </tr>

			  <tr>
				<td>Stock Status</td>
				<td align="center">:</td>
				<td><?= $sStockStatus ?></td>
			  </tr>

			  <tr>
				<td>Color Name</td>
				<td align="center">:</td>
				<td><?= $sColors ?></td>
			  </tr>

			  <tr>
				<td>Description</td>
				<td align="center">:</td>
				<td><?= $sDescription ?></td>
			  </tr>

<?
	$sSQL = "SELECT quantity FROM tbl_po WHERE id='$iPoId'";
	$objDb->query($sSQL);

	$iOrderQty = $objDb->getField(0, 0);

	if ($sAdditionalPos != "")
	{
		$sSQL = "SELECT SUM(quantity) FROM tbl_po WHERE id IN ($sAdditionalPos)";
		$objDb->query($sSQL);

		$iOrderQty += $objDb->getField(0, 0);
	}
?>
			  <tr>
				<td>Required Quantity</td>
				<td align="center">:</td>
				<td><?= formatNumber($iOrderQty, false) ?></td>
			  </tr>

			  <tr>
				<td>Shipped Quantity</td>
				<td align="center">:</td>
				<td><?= (($iShipQty > 0) ? formatNumber($iShipQty, false) : "") ?></td>
			  </tr>
		    </table>

		    <br />
			<h2>1. Product Specific Checks</h2>

<?
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
?>
			<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
				<td width="70">Yarn Count</td>
				<td width="20" align="center">:</td>
				<td><?= $sYarnCount ?></td>
			  </tr>
			</table>

			<br />

			<table border="1" bordercolor="#ffffff" cellpadding="4" cellspacing="0" width="100%">
			  <tr class="sdRowHeader">
				<td width="14%" align="center"><b>Particular</b></td>
				<td width="9%" align="center"><b>Required</b></td>
				<td width="9%" align="center"><b>Ring</b></td>
				<td width="9%" align="center"><b>Auto Cone</b></td>
				<td width="9%" align="center"><b>Packing</b></td>
				<td width="14%" align="center"><b>Particular</b></td>
				<td width="9%" align="center"><b>Required</b></td>
				<td width="9%" align="center"><b>Ring</b></td>
				<td width="9%" align="center"><b>Auto Cone</b></td>
				<td width="9%" align="center"><b>Packing</b></td>
			  </tr>

			  <tr class="sdRowColor">
				<td align="center">Actual Count</td>
				<td align="center"><?= $sActualCountR ?></td>
				<td align="center"><?= $sActualCountG ?></td>
				<td align="center"><?= $sActualCountAc ?></td>
				<td align="center"><?= $sActualCountP ?></td>
				<td align="center">CVm (10m)</td>
				<td align="center"><?= $sCvm10mR ?></td>
				<td align="center"><?= $sCvm10mG ?></td>
				<td align="center"><?= $sCvm10mAc ?></td>
				<td align="center"><?= $sCvm10mP ?></td>
			  </tr>

			  <tr class="sdRowColor">
				<td align="center">CV% Count</td>
				<td align="center"><?= $sCvCountR ?></td>
				<td align="center"><?= $sCvCountG ?></td>
				<td align="center"><?= $sCvCountAc ?></td>
				<td align="center"><?= $sCvCountP ?></td>
				<td align="center">Thin</td>
				<td align="center"><?= $sThinR ?></td>
				<td align="center"><?= $sThinG ?></td>
				<td align="center"><?= $sThinAc ?></td>
				<td align="center"><?= $sThinP ?></td>
			  </tr>

			  <tr class="sdRowColor">
				<td align="center">Lea Strength</td>
				<td align="center"><?= $sLeaStrengthR ?></td>
				<td align="center"><?= $sLeaStrengthG ?></td>
				<td align="center"><?= $sLeaStrengthAc ?></td>
				<td align="center"><?= $sLeaStrengthP ?></td>
				<td align="center">Thick</td>
				<td align="center"><?= $sThickR ?></td>
				<td align="center"><?= $sThickG ?></td>
				<td align="center"><?= $sThickAc ?></td>
				<td align="center"><?= $sThickP ?></td>
			  </tr>

			  <tr class="sdRowColor">
				<td align="center">CV% Strength</td>
				<td align="center"><?= $sCvStrengthR ?></td>
				<td align="center"><?= $sCvStrengthG ?></td>
				<td align="center"><?= $sCvStrengthAc ?></td>
				<td align="center"><?= $sCvStrengthP ?></td>
				<td align="center">Neps</td>
				<td align="center"><?= $sNepsR ?></td>
				<td align="center"><?= $sNepsG ?></td>
				<td align="center"><?= $sNepsAc ?></td>
				<td align="center"><?= $sNepsP ?></td>
			  </tr>

			  <tr class="sdRowColor">
				<td align="center">C.L.S.P</td>
				<td align="center"><?= $sClspR ?></td>
				<td align="center"><?= $sClspG ?></td>
				<td align="center"><?= $sClspAc ?></td>
				<td align="center"><?= $sClspP ?></td>
				<td align="center">IPI Value</td>
				<td align="center"><?= $sIpiValueR ?></td>
				<td align="center"><?= $sIpiValueG ?></td>
				<td align="center"><?= $sIpiValueAc ?></td>
				<td align="center"><?= $sIpiValueP ?></td>
			  </tr>

			  <tr class="sdRowColor">
				<td align="center">Min C.L.S.P</td>
				<td align="center"><?= $sMinClspR ?></td>
				<td align="center"><?= $sMinClspG ?></td>
				<td align="center"><?= $sMinClspAc ?></td>
				<td align="center"><?= $sMinClspP ?></td>
				<td align="center">CV B.U%</td>
				<td align="center"><?= $sCvBuR ?></td>
				<td align="center"><?= $sCvBuG ?></td>
				<td align="center"><?= $sCvBuAc ?></td>
				<td align="center"><?= $sCvBuP ?></td>
			  </tr>

			  <tr class="sdRowColor">
				<td align="center">RKM</td>
				<td align="center"><?= $sRkmR ?></td>
				<td align="center"><?= $sRkmG ?></td>
				<td align="center"><?= $sRkmAc ?></td>
				<td align="center"><?= $sRkmP ?></td>
				<td align="center">Hairiness</td>
				<td align="center"><?= $sHairinessR ?></td>
				<td align="center"><?= $sHairinessG ?></td>
				<td align="center"><?= $sHairinessAc ?></td>
				<td align="center"><?= $sHairinessP ?></td>
			  </tr>

			  <tr class="sdRowColor">
				<td align="center">S.Y.STR</td>
				<td align="center"><?= $sSyStrR ?></td>
				<td align="center"><?= $sSyStrG ?></td>
				<td align="center"><?= $sSyStrAc ?></td>
				<td align="center"><?= $sSyStrP ?></td>
				<td align="center">TPI</td>
				<td align="center"><?= $sTpiR ?></td>
				<td align="center"><?= $sTpiG ?></td>
				<td align="center"><?= $sTpiAc ?></td>
				<td align="center"><?= $sTpiP ?></td>
			  </tr>

			  <tr class="sdRowColor">
				<td align="center">CV%</td>
				<td align="center"><?= $sCvR ?></td>
				<td align="center"><?= $sCvG ?></td>
				<td align="center"><?= $sCvAc ?></td>
				<td align="center"><?= $sCvP ?></td>
				<td align="center">Count Max</td>
				<td align="center"><?= $sCountMaxR ?></td>
				<td align="center"><?= $sCountMaxG ?></td>
				<td align="center"><?= $sCountMaxAc ?></td>
				<td align="center"><?= $sCountMaxP ?></td>
			  </tr>

			  <tr class="sdRowColor">
				<td align="center">Elongation</td>
				<td align="center"><?= $sElongationR ?></td>
				<td align="center"><?= $sElongationG ?></td>
				<td align="center"><?= $sElongationAc ?></td>
				<td align="center"><?= $sElongationP ?></td>
				<td align="center">Count Min</td>
				<td align="center"><?= $sCountMinR ?></td>
				<td align="center"><?= $sCountMinG ?></td>
				<td align="center"><?= $sCountMinAc ?></td>
				<td align="center"><?= $sCountMinP ?></td>
			  </tr>

			  <tr class="sdRowColor">
				<td align="center">Elongation CV%</td>
				<td align="center"><?= $sElongationCvR ?></td>
				<td align="center"><?= $sElongationCvG ?></td>
				<td align="center"><?= $sElongationCvAc ?></td>
				<td align="center"><?= $sElongationCvP ?></td>
				<td align="center">Cone Moisture</td>
				<td align="center"><?= $sConeMoistureR ?></td>
				<td align="center"><?= $sConeMoistureG ?></td>
				<td align="center"><?= $sConeMoistureAc ?></td>
				<td align="center"><?= $sConeMoistureP ?></td>
			  </tr>

			  <tr class="sdRowColor">
				<td align="center">U% / CVm</td>
				<td align="center"><?= $sUcvmR ?></td>
				<td align="center"><?= $sUcvmG ?></td>
				<td align="center"><?= $sUcvmAc ?></td>
				<td align="center"><?= $sUcvmP ?></td>
				<td align="center">Comber Noil</td>
				<td align="center"><?= $sComberNoilR ?></td>
				<td align="center"><?= $sComberNoilG ?></td>
				<td align="center"><?= $sComberNoilAc ?></td>
				<td align="center"><?= $sComberNoilP ?></td>
			  </tr>

			  <tr class="sdRowColor">
				<td align="center"></td>
				<td align="center"></td>
				<td align="center"></td>
				<td align="center"></td>
				<td align="center"></td>
				<td align="center">TPI CV%</td>
				<td align="center"><?= $sTpiCvR ?></td>
				<td align="center"><?= $sTpiCvG ?></td>
				<td align="center"><?= $sTpiCvAc ?></td>
				<td align="center"><?= $sTpiCvP ?></td>
			  </tr>
			</table>

			<br />

			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			  <tr valign="top">
				<td width="50%">

				  <table border="1" bordercolor="#ffffff" cellpadding="4" cellspacing="0" width="100%">
					<tr class="sdRowHeader">
					  <td width="50%" align="center"><b>Fiber</b></td>
					  <td width="50%" align="center"><b>Cotton</b></td>
					</tr>

					<tr class="sdRowColor">
					  <td>S. Length</td>
					  <td><?= $sFcLength ?></td>
					</tr>

					<tr class="sdRowColor">
					  <td>UI/UR</td>
					  <td><?= $sFcUiUr ?></td>
					</tr>

					<tr class="sdRowColor">
					  <td>FFI/SFI</td>
					  <td><?= $sFcFfiSfi ?></td>
					</tr>

					<tr class="sdRowColor">
					  <td>Strength</td>
					  <td><?= $sFcStrength ?></td>
					</tr>

					<tr class="sdRowColor">
					  <td>MIC Value</td>
					  <td><?= $sFcMicValue ?></td>
					</tr>

					<tr class="sdRowColor">
					  <td>MIC Range</td>
					  <td><?= $sFcMicRange ?></td>
					</tr>

					<tr class="sdRowColor">
					  <td>Color Grade</td>
					  <td><?= $sFcColorGrade ?></td>
					</tr>

					<tr class="sdRowColor">
					  <td>No of Lots</td>
					  <td><?= $sFcNoOfLots ?></td>
					</tr>

					<tr class="sdRowColor">
					  <td>Cotton Stock</td>
					  <td><?= $sFcCottonStock ?></td>
					</tr>

					<tr class="sdRowColor">
					  <td>Trash</td>
					  <td><?= $sFcTrash ?></td>
					</tr>

					<tr class="sdRowColor">
					  <td>Color</td>
					  <td><?= $sFcColor ?></td>
					</tr>

					<tr class="sdRowColor">
					  <td>Moisture</td>
					  <td><?= $sFcMoisture ?></td>
					</tr>

					<tr class="sdRowColor">
					  <td>Contamination/Bale</td>
					  <td><?= $sFcContamination ?></td>
					</tr>

					<tr class="sdRowColor">
					  <td colspan="2" height="74"></td>
					</tr>
				  </table>

				</td>

				<td width="50%">

				  <table border="1" bordercolor="#ffffff" cellpadding="4" cellspacing="0" width="100%">
					<tr class="sdRowHeader">
					  <td width="30%"></td>
					  <td width="30%" align="center"><b>Lycra</b></td>
					  <td width="30%" align="center"><b>Slub</b></td>
					</tr>

					<tr class="sdRowColor">
					  <td>Length</td>
					  <td><?= $sFrLength ?></td>
					  <td><?= $sPrLength ?></td>
					</tr>

					<tr class="sdRowColor">
					  <td>Denier</td>
					  <td><?= $sFrDenier ?></td>
					  <td><?= $sPrDenier ?></td>
					</tr>

					<tr class="sdRowColor">
					  <td>Color</td>
					  <td><?= $sFrColor ?></td>
					  <td><?= $sPrColor ?></td>
					</tr>

					<tr class="sdRowColor">
					  <td>Polyester %</td>
					  <td><?= $sFrPolyester ?></td>
					  <td><?= $sPrPolyester ?></td>
					</tr>

					<tr class="sdRowColor">
					  <td>Cotton %</td>
					  <td><?= $sFrCotton ?></td>
					  <td><?= $sPrCotton ?></td>
					</tr>

					<tr class="sdRowHeader">
					  <td colspan="3" align="center"><b>Auto Cone Setting</b></td>
					</tr>

					<tr class="sdRowColor">
					  <td>N</td>
					  <td colspan="2"><?= $sAcsN ?></td>
					</tr>

					<tr class="sdRowColor">
					  <td>S/DS</td>
					  <td colspan="2"><?= $sAcsSds ?></td>
					</tr>

					<tr class="sdRowColor">
					  <td>L/LS</td>
					  <td colspan="2"><?= $sAcsLls ?></td>
					</tr>

					<tr class="sdRowColor">
					  <td>T/DL</td>
					  <td colspan="2"><?= $sAcsTdl ?></td>
					</tr>

					<tr class="sdRowColor">
					  <td>FD/-D</td>
					  <td colspan="2"><?= $sAcsFdd ?></td>
					</tr>

					<tr class="sdRowColor">
					  <td>-L</td>
					  <td colspan="2"><?= $sAcsL ?></td>
					</tr>

					<tr class="sdRowColor">
					  <td>YF</td>
					  <td colspan="2"><?= $sAcsYf ?></td>
					</tr>

					<tr class="sdRowColor">
					  <td>Auto Cone Speed</td>
					  <td colspan="2"><?= $fAutoConeSpeed ?> m/m</td>
					</tr>

					<tr class="sdRowColor">
					  <td>Cone Length</td>
					  <td colspan="2"><?= $fConeLength ?> m</td>
					</tr>

					<tr class="sdRowColor">
					  <td>Cone Weight</td>
					  <td colspan="2"><?= $fConeWeight ?> kg</td>
					</tr>
				  </table>

				</td>
			  </tr>
			</table>

			<br />
			<h2>2. Product Conformity</h2>

			<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
				<td width="200">2.1 Style Conformity</td>
				<td width="20" align="center">:</td>
				<td><?= (($sStyleConformity == "Y") ? "Yes" : (($sStyleConformity == "N") ? "No" : "No Ref sample/Specification available")) ?></td>
			  </tr>

			  <tr>
				<td>2.2 Material Conformity</td>
				<td align="center">:</td>
				<td><?= (($sMaterialConformity == "Y") ? "Yes" : (($sMaterialConformity == "N") ? "No" : "No Ref sample/Specification available")) ?></td>
			  </tr>

			  <tr>
				<td>2.3 Shade Conformity</td>
				<td align="center">:</td>
				<td><?= (($sShadeConformity == "Y") ? "Yes" : (($sShadeConformity == "N") ? "No" : "No Ref sample/Specification available")) ?></td>
			  </tr>
			</table>

			<br />
			<h3>2.4 Weight</h3>

			<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
				<td width="90">Yarn Count</td>
				<td width="20" align="center">:</td>
				<td><?= $sPcYarnCount ?></td>
			  </tr>

			  <tr>
				<td>Carton / Pallet</td>
				<td align="center">:</td>
				<td><?= $sCartonPallet ?></td>
			  </tr>
			</table>

			<br />

			<table border="1" bordercolor="#ffffff" cellpadding="4" cellspacing="0" width="100%">
			  <tr class="sdRowHeader">
				<td width="10%" rowspan="2" align="center"><b>Sr #</b></td>
				<td width="18%" rowspan="2" align="center"><b>Carton / Pallet</b></td>
				<td width="27%" rowspan="2" align="center"><b>Gross Weight per Carton</b></td>
				<td width="45%" colspan="3" align="center"><b>Gross Weight per Cone</b></td>
			  </tr>

			  <tr class="sdRowHeader">
				<td width="15%" align="center"><b>1</b></td>
				<td width="15%" align="center"><b>2</b></td>
				<td width="15%" align="center"><b>3</b></td>
			  </tr>
<?
	$sSQL = "SELECT * FROM tbl_yarn_product_conformity WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
?>

			  <tr class="sdRowColor">
				<td align="center"><?= ($i + 1) ?></td>
				<td align="center"><?= $objDb->getField($i,  'carton_no') ?></td>
				<td align="center"><?= $objDb->getField($i,  'carton_weight') ?></td>
				<td align="center"><?= $objDb->getField($i,  'cone_1_weight') ?></td>
				<td align="center"><?= $objDb->getField($i,  'cone_2_weight') ?></td>
				<td align="center"><?= $objDb->getField($i,  'cone_3_weight') ?></td>
			  </tr>
<?
	}
?>
			</table>

			<br />

			<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
				<td width="230">(1) Avrg Gross Weight per Carton/Cone</td>
				<td width="20" align="center">:</td>
				<td><?= $fAvgGrossWeightG ?> g &nbsp; / &nbsp; <?= $fAvgGrossWeightKg ?> kg &nbsp; / &nbsp; <?= $fAvgGrossWeightLb ?> lb</td>
			  </tr>

			  <tr>
				<td>(2) Tare Weight per Carton/Cone</td>
				<td align="center">:</td>
				<td><?= $fTareWeightG ?> g &nbsp; / &nbsp; <?= $fTareWeightKg ?> kg &nbsp; / &nbsp; <?= $fTareWeightLb ?> lb</td>
			  </tr>

			  <tr>
				<td>(3) Net Weight per Carton/Cone</td>
				<td align="center">:</td>
				<td><?= $fNetWeightG ?> g &nbsp; / &nbsp; <?= $fNetWeightKg ?> kg &nbsp; / &nbsp; <?= $fNetWeightLb ?> lb</td>
			  </tr>

			  <tr>
				<td>(4) Ref Sample Available</td>
				<td align="center">:</td>
				<td><?= (($sRefSampleAvailable == "Y") ? "Yes" : (($sRefSampleAvailable == "N") ? "No" : "")) ?> - <?= $sSampleAvailable ?></td>
			  </tr>

			  <tr>
				<td>Other</td>
				<td align="center">:</td>
				<td><?= $sPcOther ?></td>
			  </tr>

			  <tr valign="top">
				<td>Reservations</td>
				<td align="center">:</td>
				<td><?= nl2br($sPcReservations) ?></td>
			  </tr>
			</table>


			<br />
			<h2 style="margin-bottom:0px;">3. Appearance/function Findings</h2>

			<table border="1" bordercolor="#ffffff" cellpadding="4" cellspacing="0" width="100%">
			  <tr class="sdRowHeader">
				<td width="50%"><b>Defect</b></td>
				<td width="25%"><b>Major</b></td>
				<td width="25%"><b>Minor</b></td>
			  </tr>
<?
	$sSQL = "SELECT * FROM tbl_yarn_appearance WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
?>

			  <tr class="sdRowColor">
				<td><?= $objDb->getField($i, 'defect') ?></td>
				<td><?= $objDb->getField($i, 'major') ?></td>
				<td><?= $objDb->getField($i, 'minor') ?></td>
			  </tr>
<?
	}
?>
			</table>

			<br />

			<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
				<td width="200">Sample Size</td>
				<td width="20" align="center">:</td>
				<td><?= (($iTotalGmts > 0) ? $iTotalGmts : "") ?></td>
			  </tr>

			  <tr>
				<td>No of Defect Allowed</td>
				<td align="center">:</td>
				<td><?= (($iMaxDefects > 0) ? $iMaxDefects : "") ?></td>
			  </tr>

			  <tr>
				<td>No of Defect Found</td>
				<td align="center">:</td>
				<td><?= $iGmtsDefective ?></td>
			  </tr>

			  <tr valign="top">
				<td>Reservations</td>
				<td align="center">:</td>
				<td><?= nl2br($sAffReservations) ?></td>
			  </tr>
			</table>


			<br />
			<h2>4. Packing</h2>

<?
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
?>
			<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr valign="top">
				<td width="200">4.1 Packing Detail</td>
				<td width="20" align="center">:</td>
				<td><?= nl2br($sPackingDetail) ?></td>
			  </tr>
			</table>

			<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
				<td width="200">4.2 Carton Dimension</td>
				<td width="20" align="center">:</td>
				<td width="120"><?= $sCartonDimension1.(($sCartonDimension1 != '') ? ' &amp; ' : '').$sCartonDimension2 ?></td>
				<td></td>
			  </tr>

			  <tr>
				<td>4.3 Individual Packing Conformity</td>
				<td align="center">:</td>
				<td><?= (($sIndividualPackingConformity1 == "Y") ? "Yes" : (($sIndividualPackingConformity1 == "N") ? "No" : "")) ?></td>
				<td><?= $sIndividualPackingConformity2 ?></td>
			  </tr>

			  <tr>
				<td>4.4 Paper Cone Conformity</td>
				<td align="center">:</td>
				<td><?= (($sPaperConeConformity1 == "Y") ? "Yes" : (($sPaperConeConformity1 == "N") ? "No" : "")) ?></td>
				<td><?= $sPaperConeConformity2 ?></td>
			  </tr>

			  <tr>
				<td>4.5 Inner Packing Conformity</td>
				<td align="center">:</td>
				<td><?= (($sInnerPackingConformity1 == "Y") ? "Yes" : (($sInnerPackingConformity1 == "N") ? "No" : "")) ?></td>
				<td><?= $sInnerPackingConformity2 ?></td>
			  </tr>

			  <tr>
				<td>4.6 Assortment Found Correct</td>
				<td align="center">:</td>
				<td><?= (($sAssortmentFoundCorrect1 == "Y") ? "Yes" : (($sAssortmentFoundCorrect1 == "N") ? "No" : "")) ?></td>
				<td><?= $sAssortmentFoundCorrect2 ?></td>
			  </tr>
			</table>


			<br />
			<h2>5. Marking/Label</h2>
<?
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
?>
			<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
				<td width="200">5.1 Bar Code Conformity</td>
				<td width="20" align="center">:</td>
				<td width="120"><?= (($sBarCodeConformity1 == "Y") ? "Yes" : (($sBarCodeConformity1 == "N") ? "No" : "")) ?></td>
				<td><?= $sBarCodeConformity2 ?></td>
			  </tr>

			  <tr>
				<td>5.2 Shipping Mark Conformity</td>
				<td align="center">:</td>
				<td><?= (($sShippingMarkConformity1 == "Y") ? "Yes" : (($sShippingMarkConformity1 == "N") ? "No" : "")) ?></td>
				<td><?= $sShippingMarkConformity2 ?></td>
			  </tr>

			  <tr>
				<td>5.3 Other Marks</td>
				<td align="center">:</td>
				<td><?= (($sOtherMarks1 == "Y") ? "Yes" : (($sOtherMarks1 == "N") ? "No" : "")) ?></td>
				<td><?= $sOtherMarks2 ?></td>
			  </tr>

			  <tr>
				<td>5.3.1 Side Mark Conformity</td>
				<td align="center">:</td>
				<td><?= (($sSideMarkConformity1 == "Y") ? "Yes" : (($sSideMarkConformity1 == "N") ? "No" : "")) ?></td>
				<td><?= $sSideMarkConformity2 ?></td>
			  </tr>

			  <tr>
				<td>5.3.2 Count Label</td>
				<td align="center">:</td>
				<td><?= (($sCountLabel1 == "Y") ? "Yes" : (($sCountLabel1 == "N") ? "No" : "")) ?></td>
				<td><?= $sCountLabel2 ?></td>
			  </tr>

			  <tr>
				<td>5.3.3 Baling Strip</td>
				<td align="center">:</td>
				<td><?= (($sBalingStrip1 == "Y") ? "Yes" : (($sBalingStrip1 == "N") ? "No" : "")) ?></td>
				<td><?= $sBalingStrip2 ?></td>
			  </tr>

			  <tr>
				<td>5.3.4 Brand Name</td>
				<td align="center">:</td>
				<td><?= (($sBrandName1 == "Y") ? "Yes" : (($sBrandName1 == "N") ? "No" : "")) ?></td>
				<td><?= $sBrandName2 ?></td>
			  </tr>

			  <tr>
				<td>5.3.5 Other</td>
				<td align="center">:</td>
				<td><?= (($sOther1 == "Y") ? "Yes" : (($sOther1 == "N") ? "No" : "")) ?></td>
				<td><?= $sOther2 ?></td>
			  </tr>
			</table>


			<br />
			<h2>Inspection Conclusion</h2>

			<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
				<td width="260">Quantities Submitted For Inspection</td>
				<td width="20" align="center">:</td>
				<td><?= (($sQuantitiesSubmitted == "Y") ? "Conform" : (($sQuantitiesSubmitted == "N") ? "Not Conform" : "")) ?></td>
			  </tr>

			  <tr>
				<td>Measurements/field Tests</td>
				<td align="center">:</td>
				<td><?= (($sMeasurementsFieldTests == "Y") ? "Conform" : (($sMeasurementsFieldTests == "N") ? "Not Conform" : "")) ?></td>
			  </tr>

			  <tr>
				<td>Style, Material, Color</td>
				<td align="center">:</td>
				<td><?= (($sStyleMaterialColor == "Y") ? "Conform" : (($sStyleMaterialColor == "N") ? "Not Conform" : "")) ?></td>
			  </tr>

			  <tr>
				<td>Appearance/functioning</td>
				<td align="center">:</td>
				<td><?= (($sAppearanceFunctioning == "Y") ? "Conform" : (($sAppearanceFunctioning == "Y") ? "Not Conform" : "")) ?></td>
			  </tr>

			  <tr>
				<td>Packing</td>
				<td align="center">:</td>
				<td><?= (($sPacking == "Y") ? "Conform" : (($sPacking == "N") ? "Not Conform" : "")) ?></td>
			  </tr>

			  <tr>
				<td>Marking/label</td>
				<td align="center">:</td>
				<td><?= (($sMarkingLabel == "Y") ? "Conform" : (($sMarkingLabel == "N") ? "Not Conform" : "")) ?></td>
			  </tr>

			  <tr valign="top">
				<td><b>General Observation</b></td>
				<td align="center">:</td>
				<td><b><?= nl2br($sComments) ?></b></td>
			  </tr>

			  <tr valign="top">
				<td><b>Factory Representative Comments</b></td>
				<td align="center">:</td>
				<td><b><?= nl2br($sFactoryComments) ?></b></td>
			  </tr>

			  <tr>
				<td>For External Lab testing</td>
				<td align="center">:</td>
				<td><?= $sExternalLabTesting ?></td>
			  </tr>

			  <tr>
				<td>For Record/Client</td>
				<td align="center">:</td>
				<td><?= $sForRecordClient ?></td>
			  </tr>

			  <tr>
				<td>Sealed at Factory</td>
				<td align="center">:</td>
				<td><?= $sSealedAtFactory ?></td>
			  </tr>

			  <tr>
				<td>Reasons for not taking samples</td>
				<td align="center">:</td>
				<td><?= $sReasonForNotTakingSamples ?></td>
			  </tr>

<?
	switch ($sAuditResult)
	{
		case "P" : $sAuditResult = "Pass"; break;
		case "F" : $sAuditResult = "Fail"; break;
		case "H" : $sAuditResult = "Hold"; break;
	}
?>
			  <tr>
				<td>Overall Inspection Conclusion</td>
				<td align="center">:</td>
				<td><b><?= $sAuditResult ?></b></td>
			  </tr>

			  <tr>
				<td>Total No of Cartons Pallets/Selected</td>
				<td align="center">:</td>
				<td><?= (($iTotalCartonsSelected > 0) ? $iTotalCartonsSelected : "") ?></td>
			  </tr>

			  <tr>
				<td>No of Cones Inspected</td>
				<td align="center">:</td>
				<td><?= (($iConesInspected > 0) ? $iConesInspected : "") ?></td>
			  </tr>

			  <tr>
				<td>Data Measurement/Field test on sub-samples</td>
				<td align="center">:</td>
				<td><?= $sDataMeasurementTest ?></td>
			  </tr>
			</table>

