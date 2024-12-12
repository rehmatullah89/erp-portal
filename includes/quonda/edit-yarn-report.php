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

	$StyleName                 = $objDb->getField(0, "style_name");
	$YarnContent               = $objDb->getField(0, "yarn_content");

	$StyleConformity           = $objDb->getField(0, "style_conformity");
	$MaterialConformity        = $objDb->getField(0, "material_conformity");
	$ShadeConformity           = $objDb->getField(0, "shade_conformity");

	$PcYarnCount               = $objDb->getField(0, "pc_yarn_count");
	$CartonPallet              = $objDb->getField(0, "carton_pallet");
	$AvgGrossWeightG           = $objDb->getField(0, "avg_gross_weight_g");
	$AvgGrossWeightKg          = $objDb->getField(0, "avg_gross_weight_kg");
	$AvgGrossWeightLb          = $objDb->getField(0, "avg_gross_weight_lb");
	$TareWeightG               = $objDb->getField(0, "tare_weight_g");
	$TareWeightKg              = $objDb->getField(0, "tare_weight_kg");
	$TareWeightLb              = $objDb->getField(0, "tare_weight_lb");
	$NetWeightG                = $objDb->getField(0, "net_weight_g");
	$NetWeightKg               = $objDb->getField(0, "net_weight_kg");
	$NetWeightLb               = $objDb->getField(0, "net_weight_lb");
	$RefSampleAvailable        = $objDb->getField(0, "ref_sample_available");
	$SampleAvailable           = $objDb->getField(0, "sample_available");
	$PcOther                   = $objDb->getField(0, "pc_other");
	$PcReservations            = $objDb->getField(0, "pc_reservations");

	$AffReservations           = $objDb->getField(0, "aff_reservations");

	$QuantitiesSubmitted       = $objDb->getField(0, "quantities_submitted");
	$MeasurementsFieldTests    = $objDb->getField(0, "measurements_field_tests");
	$StyleMaterialColor        = $objDb->getField(0, "style_material_color");
	$AppearanceFunctioning     = $objDb->getField(0, "appearance_functioning");
	$Packing                   = $objDb->getField(0, "packing");
	$MarkingLabel              = $objDb->getField(0, "marking_label");
	$FactoryComments           = $objDb->getField(0, "factory_comments");
	$ExternalLabTesting        = $objDb->getField(0, "external_lab_testing");
	$ForRecordClient           = $objDb->getField(0, "for_record_client");
	$SealedAtFactory           = $objDb->getField(0, "sealed_at_factory");
	$ReasonForNotTakingSamples = $objDb->getField(0, "reason_for_not_taking_samples");
	$TotalCartonsSelected      = $objDb->getField(0, "total_cartons_selected");
	$ConesInspected            = $objDb->getField(0, "cones_inspected");
	$DataMeasurementTest       = $objDb->getField(0, "data_measurement_test");
?>
				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="110"><b>Audit Code</b></td>
					<td width="20" align="center">:</td>
					<td><b><?= $AuditCode ?></b></td>
				  </tr>

				  <tr>
					<td>Vendor</td>
					<td align="center">:</td>
					<td><?= $sVendor ?></td>
				  </tr>

				  <tr>
					<td>Auditor</td>
					<td align="center">:</td>
					<td><?= $sAuditor ?></td>
				  </tr>

				  <tr>
				    <td>Group</td>
				    <td align="center">:</td>

				    <td>
					  <select name="Group">
						<option value=""></option>
<?
	$sSQL = "SELECT id, name FROM tbl_auditor_groups ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, 0);
		$sValue = $objDb->getField($i, 1);
?>
	  	        		<option value="<?= $sKey ?>"<?= (($sKey == $Group) ? ' selected' : '') ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr valign="top">
					<td>PO<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="PO" id="PO">
						<option value=""></option>
<?
	$sSQL = "SELECT po.id, po.order_no, po.order_status, s.style
	         FROM tbl_po po, tbl_po_colors pc, tbl_styles s
	         WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.vendor_id='$Vendor' AND FIND_IN_SET(po.brand_id, '{$_SESSION['Brands']}')
	         GROUP BY po.id
	         ORDER BY po.order_no";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey    = $objDb->getField($i, 0);
		$sValue  = $objDb->getField($i, 1);
		$sStatus = $objDb->getField($i, 2);
		$sStyle  = $objDb->getField($i, 3);
?>
	  	        		<option value="<?= $sKey ?>"<?= (($sKey == $PO) ? ' selected' : '') ?>><?= $sValue ?> <?= $sStatus ?> (<?= $sStyle ?>)</option>
<?
	}
?>
					  </select>

					  &nbsp; ( <a href="#" onclick="Effect.toggle('POs', 'slide'); return false;">More POs</a> )<br />

					  <div id="POs" style="padding-top:5px; display:<?= (($AdditionalPos == '') ? 'none' : 'block') ?>;">
					    <div>
					  	  <select id="AdditionalPos" name="AdditionalPos[]" multiple size="10">
<?
	$AdditionalPos = @explode(",", $AdditionalPos);

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId     = $objDb->getField($i, 0);
		$sValue  = $objDb->getField($i, 1);
		$sStatus = $objDb->getField($i, 2);
		$sStyle  = $objDb->getField($i, 3);

		if ($iId == $PO)
			continue;
?>
	  	        			<option value="<?= $iId ?>" <?= ((@in_array($iId, $AdditionalPos)) ? 'selected' : '') ?>><?= $sValue ?> <?= $sStatus ?> (<?= $sStyle ?>)</option>
<?
	}
?>
					  	  </select>
					    </div>
					  </div>
					</td>
				  </tr>

				  <tr>
					<td>Style Name/Count</td>
					<td align="center">:</td>
					<td><input type="text" name="StyleName" value="<?= $StyleName ?>" size="20" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Yarn Content</td>
					<td align="center">:</td>
					<td><input type="text" name="YarnContent" value="<?= $YarnContent ?>" size="20" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Audit Stage<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="AuditStage" onchange="$('Sms').value='1';">
						<option value=""></option>
<?
	foreach ($sAuditStagesList as $sKey => $sValue)
	{
		if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE && $sValue == "Final")
			$sValue = "Firewall";

		if ( (@strpos($_SESSION["Email"], "pelknit.com") !== FALSE || @strpos($_SESSION["Email"], "fencepostproductions.com") !== FALSE) &&
			 !@in_array($sKey, array("B", "C", "O", "F")) )
			continue;
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $AuditStage) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Stock Status</td>
					<td align="center">:</td>
					<td><input type="text" name="StockStatus" value="<?= $StockStatus ?>" size="20" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Color Name</td>
					<td align="center">:</td>
					<td><input type="text" name="Colors" value="<?= $Colors ?>" size="20" width="250" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Description</td>
					<td align="center">:</td>
					<td><input type="text" name="Description" value="<?= $Description ?>" size="20" maxlength="250" class="textbox" /></td>
				  </tr>

<?
	$sSQL = "SELECT quantity FROM tbl_po WHERE id='$PO'";
	$objDb->query($sSQL);

	$iOrderQty = $objDb->getField(0, 0);

	if (count($AdditionalPos) > 0)
	{
		$AdditionalPos = @implode(",", $AdditionalPos);

		$sSQL = "SELECT SUM(quantity) FROM tbl_po WHERE id IN ($AdditionalPos)";
		$objDb->query($sSQL);

		$iOrderQty += $objDb->getField(0, 0);
	}
?>
			      <tr>
				    <td>Required Quantity</td>
				    <td align="center">:</td>
				    <td><?= $iOrderQty ?></td>
 			      </tr>

			      <tr>
				    <td>Shipped Quantity</td>
				    <td align="center">:</td>
				    <td><input type="text" name="ShipQty" value="<?= $ShipQty ?>" size="10" class="textbox" /></td>
			      </tr>
				</table>

				<br />
				<h2>1. Product Specific Checks</h2>

<?
	$sSQL = "SELECT * FROM tbl_yarn_product_checks WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$YarnCount        = $objDb->getField(0, "yarn_count");
	$ActualCountR     = $objDb->getField(0, "actual_count_r");
	$ActualCountG     = $objDb->getField(0, "actual_count_g");
	$ActualCountAc    = $objDb->getField(0, "actual_count_ac");
	$ActualCountP     = $objDb->getField(0, "actual_count_p");
	$ThinR            = $objDb->getField(0, "thin_r");
	$ThinG            = $objDb->getField(0, "thin_g");
	$ThinAc           = $objDb->getField(0, "thin_ac");
	$ThinP            = $objDb->getField(0, "thin_p");
	$CvCountR         = $objDb->getField(0, "cv_count_r");
	$CvCountG         = $objDb->getField(0, "cv_count_g");
	$CvCountAc        = $objDb->getField(0, "cv_count_ac");
	$CvCountP         = $objDb->getField(0, "cv_count_p");
	$ThickR           = $objDb->getField(0, "thick_r");
	$ThickG           = $objDb->getField(0, "thick_g");
	$ThickAc          = $objDb->getField(0, "thick_ac");
	$ThickP           = $objDb->getField(0, "thick_p");
	$LeaStrengthR     = $objDb->getField(0, "lea_strength_r");
	$LeaStrengthG     = $objDb->getField(0, "lea_strength_g");
	$LeaStrengthAc    = $objDb->getField(0, "lea_strength_ac");
	$LeaStrengthP     = $objDb->getField(0, "lea_strength_p");
	$NepsR            = $objDb->getField(0, "neps_r");
	$NepsG            = $objDb->getField(0, "neps_g");
	$NepsAc           = $objDb->getField(0, "neps_ac");
	$NepsP            = $objDb->getField(0, "neps_p");
	$CvStrengthR      = $objDb->getField(0, "cv_strength_r");
	$CvStrengthG      = $objDb->getField(0, "cv_strength_g");
	$CvStrengthAc     = $objDb->getField(0, "cv_strength_ac");
	$CvStrengthP      = $objDb->getField(0, "cv_strength_p");
	$IpiValueR        = $objDb->getField(0, "ipi_value_r");
	$IpiValueG        = $objDb->getField(0, "ipi_value_g");
	$IpiValueAc       = $objDb->getField(0, "ipi_value_ac");
	$IpiValueP        = $objDb->getField(0, "ipi_value_p");
	$ClspR            = $objDb->getField(0, "clsp_r");
	$ClspG            = $objDb->getField(0, "clsp_g");
	$ClspAc           = $objDb->getField(0, "clsp_ac");
	$ClspP            = $objDb->getField(0, "clsp_p");
	$CvBuR            = $objDb->getField(0, "cv_bu_r");
	$CvBuG            = $objDb->getField(0, "cv_bu_g");
	$CvBuAc           = $objDb->getField(0, "cv_bu_ac");
	$CvBuP            = $objDb->getField(0, "cv_bu_p");
	$MinClspR         = $objDb->getField(0, "min_clsp_r");
	$MinClspG         = $objDb->getField(0, "min_clsp_g");
	$MinClspAc        = $objDb->getField(0, "min_clsp_ac");
	$MinClspP         = $objDb->getField(0, "min_clsp_p");
	$HairinessR       = $objDb->getField(0, "hairiness_r");
	$HairinessG       = $objDb->getField(0, "hairiness_g");
	$HairinessAc      = $objDb->getField(0, "hairiness_ac");
	$HairinessP       = $objDb->getField(0, "hairiness_p");
	$RkmR             = $objDb->getField(0, "rkm_r");
	$RkmG             = $objDb->getField(0, "rkm_g");
	$RkmAc            = $objDb->getField(0, "rkm_ac");
	$RkmP             = $objDb->getField(0, "rkm_p");
	$TpiR             = $objDb->getField(0, "tpi_r");
	$TpiG             = $objDb->getField(0, "tpi_g");
	$TpiAc            = $objDb->getField(0, "tpi_ac");
	$TpiP             = $objDb->getField(0, "tpi_p");
	$SyStrR           = $objDb->getField(0, "sy_str_r");
	$SyStrG           = $objDb->getField(0, "sy_str_g");
	$SyStrAc          = $objDb->getField(0, "sy_str_ac");
	$SyStrP           = $objDb->getField(0, "sy_str_p");
	$CvR              = $objDb->getField(0, "cv_r");
	$CvG              = $objDb->getField(0, "cv_g");
	$CvAc             = $objDb->getField(0, "cv_ac");
	$CvP              = $objDb->getField(0, "cv_p");
	$CountMaxR        = $objDb->getField(0, "count_max_r");
	$CountMaxG        = $objDb->getField(0, "count_max_g");
	$CountMaxAc       = $objDb->getField(0, "count_max_ac");
	$CountMaxP        = $objDb->getField(0, "count_max_p");
	$ElongationR      = $objDb->getField(0, "elongation_r");
	$ElongationG      = $objDb->getField(0, "elongation_g");
	$ElongationAc     = $objDb->getField(0, "elongation_ac");
	$ElongationP      = $objDb->getField(0, "elongation_p");
	$CountMinR        = $objDb->getField(0, "count_min_r");
	$CountMinG        = $objDb->getField(0, "count_min_g");
	$CountMinAc       = $objDb->getField(0, "count_min_ac");
	$CountMinP        = $objDb->getField(0, "count_min_p");
	$ElongationCvR    = $objDb->getField(0, "elongation_cv_r");
	$ElongationCvG    = $objDb->getField(0, "elongation_cv_g");
	$ElongationCvAc   = $objDb->getField(0, "elongation_cv_ac");
	$ElongationCvP    = $objDb->getField(0, "elongation_cv_p");
	$ConeMoistureR    = $objDb->getField(0, "cone_moisture_r");
	$ConeMoistureG    = $objDb->getField(0, "cone_moisture_g");
	$ConeMoistureAc   = $objDb->getField(0, "cone_moisture_ac");
	$ConeMoistureP    = $objDb->getField(0, "cone_moisture_p");
	$UcvmR            = $objDb->getField(0, "ucvm_r");
	$UcvmG            = $objDb->getField(0, "ucvm_g");
	$UcvmAc           = $objDb->getField(0, "ucvm_ac");
	$UcvmP            = $objDb->getField(0, "ucvm_p");
	$ComberNoilR      = $objDb->getField(0, "comber_noil_r");
	$ComberNoilG      = $objDb->getField(0, "comber_noil_g");
	$ComberNoilAc     = $objDb->getField(0, "comber_noil_ac");
	$ComberNoilP      = $objDb->getField(0, "comber_noil_p");
	$Cvm10mR          = $objDb->getField(0, "cvm_10m_r");
	$Cvm10mG          = $objDb->getField(0, "cvm_10m_g");
	$Cvm10mAc         = $objDb->getField(0, "cvm_10m_ac");
	$Cvm10mP          = $objDb->getField(0, "cvm_10m_p");
	$TpiCvR           = $objDb->getField(0, "tpi_cv_r");
	$TpiCvG           = $objDb->getField(0, "tpi_cv_g");
	$TpiCvAc          = $objDb->getField(0, "tpi_cv_ac");
	$TpiCvP           = $objDb->getField(0, "tpi_cv_p");

	$FcLength         = $objDb->getField(0, "fc_length");
	$FcUiUr           = $objDb->getField(0, "fc_ui_ur");
	$FcFfiSfi         = $objDb->getField(0, "fc_ffi_sfi");
	$FcStrength       = $objDb->getField(0, "fc_strength");
	$FcMicValue       = $objDb->getField(0, "fc_mic_value");
	$FcMicRange       = $objDb->getField(0, "fc_mic_range");
	$FcColorGrade     = $objDb->getField(0, "fc_color_grade");
	$FcNoOfLots       = $objDb->getField(0, "fc_no_of_lots");
	$FcCottonStock    = $objDb->getField(0, "fc_cotton_stock");
	$FcTrash          = $objDb->getField(0, "fc_trash");
	$FcColor          = $objDb->getField(0, "fc_color");
	$FcMoisture       = $objDb->getField(0, "fc_moisture");
	$FcContamination  = $objDb->getField(0, "fc_contamination");

	$FrLength         = $objDb->getField(0, "fr_length");
	$FrDenier         = $objDb->getField(0, "fr_denier");
	$FrColor          = $objDb->getField(0, "fr_color");
	$FrPolyester      = $objDb->getField(0, "fr_polyester");
	$FrCotton         = $objDb->getField(0, "fr_cotton");
	$PrLength         = $objDb->getField(0, "pr_length");
	$PrDenier         = $objDb->getField(0, "pr_denier");
	$PrColor          = $objDb->getField(0, "pr_color");
	$PrPolyester      = $objDb->getField(0, "pr_polyester");
	$PrCotton         = $objDb->getField(0, "pr_cotton");

	$AcsN             = $objDb->getField(0, "acs_n");
	$AcsSds           = $objDb->getField(0, "acs_sds");
	$AcsLls           = $objDb->getField(0, "acs_lls");
	$AcsTdl           = $objDb->getField(0, "acs_tdl");
	$AcsFdd           = $objDb->getField(0, "acs_fdd");
	$AcsL             = $objDb->getField(0, "acs_l");
	$AcsYf            = $objDb->getField(0, "acs_yf");

	$AutoConeSpeed    = $objDb->getField(0, "auto_cone_speed");
	$ConeLength       = $objDb->getField(0, "cone_length");
	$ConeWeight       = $objDb->getField(0, "cone_weight");
?>
			    <table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="70">Yarn Count</td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="YarnCount">
					    <option value=""></option>
					    <option value="7.1/1 Carded Slub (88)"<?= (($YarnCount == '7.1/1 Carded Slub (88)') ? ' selected' : '') ?>>7.1/1 Carded Slub (88)</option>
					    <option value="14.17/1 Combed 70-D Lycra"<?= (($YarnCount == '14.17/1 Combed 70-D Lycra') ? ' selected' : '') ?>>14.17/1 Combed 70-D Lycra</option>
					    <option value="9.45/1 Carded 70-D Lycra"<?= (($YarnCount == '9.45/1 Carded 70-D Lycra') ? ' selected' : '') ?>>9.45/1 Carded 70-D Lycra</option>
					    <option value="7.1/1 Carded Slub (22)"<?= (($YarnCount == '7.1/1 Carded Slub (22)') ? ' selected' : '') ?>>7.1/1 Carded Slub (22)</option>
					  </select>
					</td>
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
				    <td align="center"><input type="text" name="ActualCountR" value="<?= $ActualCountR ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="ActualCountG" value="<?= $ActualCountG ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="ActualCountAc" value="<?= $ActualCountAc ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="ActualCountP" value="<?= $ActualCountP ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center">CVm (10m)</td>
				    <td align="center"><input type="text" name="Cvm10mR" value="<?= $Cvm10mR ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="Cvm10mG" value="<?= $Cvm10mG ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="Cvm10mAc" value="<?= $Cvm10mAc ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="Cvm10mP" value="<?= $Cvm10mP ?>" maxlength="10" size="7" class="textbox" /></td>
				  </tr>

				  <tr class="sdRowColor">
				    <td align="center">CV% Count</td>
				    <td align="center"><input type="text" name="CvCountR" value="<?= $CvCountR ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="CvCountG" value="<?= $CvCountG ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="CvCountAc" value="<?= $CvCountAc ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="CvCountP" value="<?= $CvCountP ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center">Thin</td>
				    <td align="center"><input type="text" name="ThinR" value="<?= $ThinR ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="ThinG" value="<?= $ThinG ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="ThinAc" value="<?= $ThinAc ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="ThinP" value="<?= $ThinP ?>" maxlength="10" size="7" class="textbox" /></td>
				  </tr>

				  <tr class="sdRowColor">
				    <td align="center">Lea Strength</td>
				    <td align="center"><input type="text" name="LeaStrengthR" value="<?= $LeaStrengthR ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="LeaStrengthG" value="<?= $LeaStrengthG ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="LeaStrengthAc" value="<?= $LeaStrengthAc ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="LeaStrengthP" value="<?= $LeaStrengthP ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center">Thick</td>
				    <td align="center"><input type="text" name="ThickR" value="<?= $ThickR ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="ThickG" value="<?= $ThickG ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="ThickAc" value="<?= $ThickAc ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="ThickP" value="<?= $ThickP ?>" maxlength="10" size="7" class="textbox" /></td>
				  </tr>

				  <tr class="sdRowColor">
				    <td align="center">CV% Strength</td>
				    <td align="center"><input type="text" name="CvStrengthR" value="<?= $CvStrengthR ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="CvStrengthG" value="<?= $CvStrengthG ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="CvStrengthAc" value="<?= $CvStrengthAc ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="CvStrengthP" value="<?= $CvStrengthP ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center">Neps</td>
				    <td align="center"><input type="text" name="NepsR" value="<?= $NepsR ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="NepsG" value="<?= $NepsG ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="NepsAc" value="<?= $NepsAc ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="NepsP" value="<?= $NepsP ?>" maxlength="10" size="7" class="textbox" /></td>
				  </tr>

				  <tr class="sdRowColor">
				    <td align="center">C.L.S.P</td>
				    <td align="center"><input type="text" name="ClspR" value="<?= $ClspR ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="ClspG" value="<?= $ClspG ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="ClspAc" value="<?= $ClspAc ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="ClspP" value="<?= $ClspP ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center">IPI Value</td>
				    <td align="center"><input type="text" name="IpiValueR" value="<?= $IpiValueR ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="IpiValueG" value="<?= $IpiValueG ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="IpiValueAc" value="<?= $IpiValueAc ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="IpiValueP" value="<?= $IpiValueP ?>" maxlength="10" size="7" class="textbox" /></td>
				  </tr>

				  <tr class="sdRowColor">
				    <td align="center">Min C.L.S.P</td>
				    <td align="center"><input type="text" name="MinClspR" value="<?= $MinClspR ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="MinClspG" value="<?= $MinClspG ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="MinClspAc" value="<?= $MinClspAc ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="MinClspP" value="<?= $MinClspP ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center">CV B.U%</td>
				    <td align="center"><input type="text" name="CvBuR" value="<?= $CvBuR ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="CvBuG" value="<?= $CvBuG ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="CvBuAc" value="<?= $CvBuAc ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="CvBuP" value="<?= $CvBuP ?>" maxlength="10" size="7" class="textbox" /></td>
				  </tr>

				  <tr class="sdRowColor">
				    <td align="center">RKM</td>
				    <td align="center"><input type="text" name="RkmR" value="<?= $RkmR ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="RkmG" value="<?= $RkmG ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="RkmAc" value="<?= $RkmAc ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="RkmP" value="<?= $RkmP ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center">Hairiness</td>
				    <td align="center"><input type="text" name="HairinessR" value="<?= $HairinessR ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="HairinessG" value="<?= $HairinessG ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="HairinessAc" value="<?= $HairinessAc ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="HairinessP" value="<?= $HairinessP ?>" maxlength="10" size="7" class="textbox" /></td>
				  </tr>

				  <tr class="sdRowColor">
				    <td align="center">S.Y.STR</td>
				    <td align="center"><input type="text" name="SyStrR" value="<?= $SyStrR ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="SyStrG" value="<?= $SyStrG ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="SyStrAc" value="<?= $SyStrAc ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="SyStrP" value="<?= $SyStrP ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center">TPI</td>
				    <td align="center"><input type="text" name="TpiR" value="<?= $TpiR ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="TpiG" value="<?= $TpiG ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="TpiAc" value="<?= $TpiAc ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="TpiP" value="<?= $TpiP ?>" maxlength="10" size="7" class="textbox" /></td>
				  </tr>

				  <tr class="sdRowColor">
				    <td align="center">CV%</td>
				    <td align="center"><input type="text" name="CvR" value="<?= $CvR ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="CvG" value="<?= $CvG ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="CvAc" value="<?= $CvAc ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="CvP" value="<?= $CvP ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center">Count Max</td>
				    <td align="center"><input type="text" name="CountMaxR" value="<?= $CountMaxR ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="CountMaxG" value="<?= $CountMaxG ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="CountMaxAc" value="<?= $CountMaxAc ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="CountMaxP" value="<?= $CountMaxP ?>" maxlength="10" size="7" class="textbox" /></td>
				  </tr>

				  <tr class="sdRowColor">
				    <td align="center">Elongation</td>
				    <td align="center"><input type="text" name="ElongationR" value="<?= $ElongationR ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="ElongationG" value="<?= $ElongationG ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="ElongationAc" value="<?= $ElongationAc ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="ElongationP" value="<?= $ElongationP ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center">Count Min</td>
				    <td align="center"><input type="text" name="CountMinR" value="<?= $CountMinR ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="CountMinG" value="<?= $CountMinG ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="CountMinAc" value="<?= $CountMinAc ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="CountMinP" value="<?= $CountMinP ?>" maxlength="10" size="7" class="textbox" /></td>
				  </tr>

				  <tr class="sdRowColor">
				    <td align="center">Elongation CV%</td>
				    <td align="center"><input type="text" name="ElongationCvR" value="<?= $ElongationCvR ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="ElongationCvG" value="<?= $ElongationCvG ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="ElongationCvAc" value="<?= $ElongationCvAc ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="ElongationCvP" value="<?= $ElongationCvP ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center">Cone Moisture</td>
				    <td align="center"><input type="text" name="ConeMoistureR" value="<?= $ConeMoistureR ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="ConeMoistureG" value="<?= $ConeMoistureG ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="ConeMoistureAc" value="<?= $ConeMoistureAc ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="ConeMoistureP" value="<?= $ConeMoistureP ?>" maxlength="10" size="7" class="textbox" /></td>
				  </tr>

				  <tr class="sdRowColor">
				    <td align="center">U% / CVm</td>
				    <td align="center"><input type="text" name="UcvmR" value="<?= $UcvmR ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="UcvmG" value="<?= $UcvmG ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="UcvmAc" value="<?= $UcvmAc ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="UcvmP" value="<?= $UcvmP ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center">Comber Noil</td>
				    <td align="center"><input type="text" name="ComberNoilR" value="<?= $ComberNoilR ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="ComberNoilG" value="<?= $ComberNoilG ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="ComberNoilAc" value="<?= $ComberNoilAc ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="ComberNoilP" value="<?= $ComberNoilP ?>" maxlength="10" size="7" class="textbox" /></td>
				  </tr>

				  <tr class="sdRowColor">
				    <td align="center"></td>
				    <td align="center"></td>
				    <td align="center"></td>
				    <td align="center"></td>
				    <td align="center"></td>
				    <td align="center">TPI CV%</td>
				    <td align="center"><input type="text" name="TpiCvR" value="<?= $TpiCvR ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="TpiCvG" value="<?= $TpiCvG ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="TpiCvAc" value="<?= $TpiCvAc ?>" maxlength="10" size="7" class="textbox" /></td>
				    <td align="center"><input type="text" name="TpiCvP" value="<?= $TpiCvP ?>" maxlength="10" size="7" class="textbox" /></td>
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
						  <td><input type="text" name="FcLength" value="<?= $FcLength ?>" size="20" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr class="sdRowColor">
						  <td>UI/UR</td>
						  <td><input type="text" name="FcUiUr" value="<?= $FcUiUr ?>" size="20" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr class="sdRowColor">
						  <td>FFI/SFI</td>
						  <td><input type="text" name="FcFfiSfi" value="<?= $FcFfiSfi ?>" size="20" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr class="sdRowColor">
						  <td>Strength</td>
						  <td><input type="text" name="FcStrength" value="<?= $FcStrength ?>" size="20" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr class="sdRowColor">
						  <td>MIC Value</td>
						  <td><input type="text" name="FcMicValue" value="<?= $FcMicValue ?>" size="20" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr class="sdRowColor">
						  <td>MIC Range</td>
						  <td><input type="text" name="FcMicRange" value="<?= $FcMicRange ?>" size="20" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr class="sdRowColor">
						  <td>Color Grade</td>
						  <td><input type="text" name="FcColorGrade" value="<?= $FcColorGrade ?>" size="20" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr class="sdRowColor">
						  <td>No of Lots</td>
						  <td><input type="text" name="FcNoOfLots" value="<?= $FcNoOfLots ?>" size="20" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr class="sdRowColor">
						  <td>Cotton Stock</td>
						  <td><input type="text" name="FcCottonStock" value="<?= $FcCottonStock ?>" size="20" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr class="sdRowColor">
						  <td>Trash</td>
						  <td><input type="text" name="FcTrash" value="<?= $FcTrash ?>" size="20" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr class="sdRowColor">
						  <td>Color</td>
						  <td><input type="text" name="FcColor" value="<?= $FcColor ?>" size="20" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr class="sdRowColor">
						  <td>Moisture</td>
						  <td><input type="text" name="FcMoisture" value="<?= $FcMoisture ?>" size="20" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr class="sdRowColor">
						  <td>Contamination/Bale</td>
						  <td><input type="text" name="FcContamination" value="<?= $FcContamination ?>" size="20" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr class="sdRowColor">
						  <td colspan="2" height="74"></td>
					    </tr>
					  </table>

			        </td>

			        <td width="50%">

					  <table border="1" bordercolor="#ffffff" cellpadding="4" cellspacing="0" width="100%">
					    <tr class="sdRowHeader">
						  <td width="40%" align="center"></td>
						  <td width="30%" align="center"><b>Lycra</b></td>
						  <td width="30%" align="center"><b>Slub</b></td>
					    </tr>

					    <tr class="sdRowColor">
						  <td>Length</td>
						  <td><input type="text" name="FrLength" value="<?= $FrLength ?>" size="15" maxlength="50" class="textbox" /></td>
						  <td><input type="text" name="PrLength" value="<?= $PrLength ?>" size="15" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr class="sdRowColor">
						  <td>Denier</td>
						  <td><input type="text" name="FrDenier" value="<?= $FrDenier ?>" size="15" maxlength="50" class="textbox" /></td>
						  <td><input type="text" name="PrDenier" value="<?= $PrDenier ?>" size="15" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr class="sdRowColor">
						  <td>Color</td>
						  <td><input type="text" name="FrColor" value="<?= $FrColor ?>" size="15" maxlength="50" class="textbox" /></td>
						  <td><input type="text" name="PrColor" value="<?= $PrColor ?>" size="15" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr class="sdRowColor">
						  <td>Polyester %</td>
						  <td><input type="text" name="FrPolyester" value="<?= $FrPolyester ?>" size="15" maxlength="50" class="textbox" /></td>
						  <td><input type="text" name="PrPolyester" value="<?= $PrPolyester ?>" size="15" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr class="sdRowColor">
						  <td>Cotton %</td>
						  <td><input type="text" name="FrCotton" value="<?= $FrCotton ?>" size="15" maxlength="50" class="textbox" /></td>
						  <td><input type="text" name="PrCotton" value="<?= $PrCotton ?>" size="15" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr class="sdRowHeader">
						  <td colspan="3" align="center"><b>Auto Cone Setting</b></td>
					    </tr>

					    <tr class="sdRowColor">
						  <td>N</td>
						  <td colspan="2"><input type="text" name="AcsN" value="<?= $AcsN ?>" size="20" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr class="sdRowColor">
						  <td>S/DS</td>
						  <td colspan="2"><input type="text" name="AcsSds" value="<?= $AcsSds ?>" size="20" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr class="sdRowColor">
						  <td>L/LS</td>
						  <td colspan="2"><input type="text" name="AcsLls" value="<?= $AcsLls ?>" size="20" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr class="sdRowColor">
						  <td>T/DL</td>
						  <td colspan="2"><input type="text" name="AcsTdl" value="<?= $AcsTdl ?>" size="20" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr class="sdRowColor">
						  <td>FD/-D</td>
						  <td colspan="2"><input type="text" name="AcsFdd" value="<?= $AcsFdd ?>" size="20" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr class="sdRowColor">
						  <td>-L</td>
						  <td colspan="2"><input type="text" name="AcsL" value="<?= $AcsL ?>" size="20" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr class="sdRowColor">
						  <td>YF</td>
						  <td colspan="2"><input type="text" name="AcsYf" value="<?= $AcsYf ?>" size="20" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr class="sdRowColor">
						  <td>Auto Cone Speed</td>
						  <td colspan="2"><input type="text" name="AutoConeSpeed" value="<?= $AutoConeSpeed ?>" size="20" maxlength="10" class="textbox" /> m/m</td>
					    </tr>

					    <tr class="sdRowColor">
						  <td>Cone Length</td>
						  <td colspan="2"><input type="text" name="ConeLength" value="<?= $ConeLength ?>" size="20" maxlength="10" class="textbox" /> m</td>
					    </tr>

					    <tr class="sdRowColor">
						  <td>Cone Weight</td>
						  <td colspan="2"><input type="text" name="ConeWeight" value="<?= $ConeWeight ?>" size="20" maxlength="10" class="textbox" /> kg</td>
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
				    <td><input type="radio" name="StyleConformity" value="Y" <?= (($StyleConformity == "Y") ? "checked" : "") ?> />Yes &nbsp; &nbsp;<input type="radio" name="StyleConformity" value="N" <?= (($StyleConformity == "N") ? "checked" : "") ?> />No &nbsp; &nbsp;<input type="radio" name="StyleConformity" value="N/A" <?= (($StyleConformity == "N/A") ? "checked" : "") ?> />No Ref sample/Specification available</td>
				  </tr>

				  <tr>
				    <td>2.2 Material Conformity</td>
				    <td align="center">:</td>
				    <td><input type="radio" name="MaterialConformity" value="Y" <?= (($MaterialConformity == "Y") ? "checked" : "") ?> />Yes &nbsp; &nbsp;<input type="radio" name="MaterialConformity" value="N" <?= (($MaterialConformity == "N") ? "checked" : "") ?> />No &nbsp; &nbsp;<input type="radio" name="MaterialConformity" value="N/A" <?= (($MaterialConformity == "N/A") ? "checked" : "") ?> />No Ref sample/Specification available</td>
				  </tr>

				  <tr>
				    <td>2.3 Shade Conformity</td>
				    <td align="center">:</td>
				    <td><input type="radio" name="ShadeConformity" value="Y" <?= (($ShadeConformity == "Y") ? "checked" : "") ?> />Yes &nbsp; &nbsp;<input type="radio" name="ShadeConformity" value="N" <?= (($ShadeConformity == "N") ? "checked" : "") ?> />No &nbsp; &nbsp;<input type="radio" name="ShadeConformity" value="N/A" <?= (($ShadeConformity == "N/A") ? "checked" : "") ?> />No Ref sample/Specification available</td>
				  </tr>
				</table>

				<br />
				<h3>2.4 Weight</h3>

			    <table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="80">Yarn Count</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="PcYarnCount" value="<?= $PcYarnCount ?>" size="20" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Carton/Pallet</td>
					<td align="center">:</td>

					<td>
					  <select name="CartonPallet">
					    <option value="Carton"<?= (($CartonPallet == "Carton") ? "selected" : "") ?>>Carton</option>
					    <option value="Pallet"<?= (($CartonPallet == "Pallet") ? "selected" : "") ?>>Pallet</option>
					  </select>
					</td>
				  </tr>
				</table>

				<br />

			    <table border="1" bordercolor="#ffffff" cellpadding="4" cellspacing="0" width="100%">
				  <tr class="sdRowHeader">
				    <td width="10%" rowspan="2" align="center"><b>Sr #</b></td>
				    <td width="18%" rowspan="2" align="center"><b>Carton/Pallet </b></td>
				    <td width="18%" rowspan="2" align="center"><b>Gross Weight per Carton</b></td>
				    <td width="54%" colspan="3" align="center"><b>Gross Weight per Cone</b></td>
				  </tr>

				  <tr class="sdRowHeader">
				    <td width="18%" align="center"><b>1</b></td>
				    <td width="18%" align="center"><b>2</b></td>
				    <td width="18%" align="center"><b>3</b></td>
				  </tr>
<?
	$sSQL = "SELECT * FROM tbl_yarn_product_conformity WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < 25; $i ++)
	{
?>

				  <tr class="sdRowColor">
				    <td align="center"><?= ($i + 1) ?></td>
				    <td align="center"><input type="text" name="CartonNo<?= $i ?>" value="<?= $objDb->getField($i,  'carton_no') ?>" maxlength="10" size="10" class="textbox" /></td>
				    <td align="center"><input type="text" name="CartonWeight<?= $i ?>" value="<?= $objDb->getField($i,  'carton_weight') ?>" maxlength="10" size="10" class="textbox" /></td>
				    <td align="center"><input type="text" name="Cone1Weight<?= $i ?>" value="<?= $objDb->getField($i,  'cone_1_weight') ?>" maxlength="10" size="10" class="textbox" /></td>
				    <td align="center"><input type="text" name="Cone2Weight<?= $i ?>" value="<?= $objDb->getField($i,  'cone_2_weight') ?>" maxlength="10" size="10" class="textbox" /></td>
				    <td align="center"><input type="text" name="Cone3Weight<?= $i ?>" value="<?= $objDb->getField($i,  'cone_3_weight') ?>" maxlength="10" size="10" class="textbox" /></td>
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

				    <td>
				      <input type="text" name="AvgGrossWeightG" value="<?= $AvgGrossWeightG ?>" size="10" maxlength="10" class="textbox" /> g &nbsp;
				      <input type="text" name="AvgGrossWeightKg" value="<?= $AvgGrossWeightKg ?>" size="10" maxlength="10" class="textbox" /> kg &nbsp;
				      <input type="text" name="AvgGrossWeightLb" value="<?= $AvgGrossWeightLb ?>" size="10" maxlength="10" class="textbox" /> lb
				    </td>
				  </tr>

				  <tr>
				    <td>(2) Tare Weight per Carton/Cone</td>
				    <td align="center">:</td>

				    <td>
				      <input type="text" name="TareWeightG" value="<?= $TareWeightG ?>" size="10" maxlength="10" class="textbox" /> g &nbsp;
				      <input type="text" name="TareWeightKg" value="<?= $TareWeightKg ?>" size="10" maxlength="10" class="textbox" /> kg &nbsp;
				      <input type="text" name="TareWeightLb" value="<?= $TareWeightLb ?>" size="10" maxlength="10" class="textbox" /> lb &nbsp;
				    </td>
				  </tr>

				  <tr>
				    <td>(3) Net Weight per Carton/Cone</td>
				    <td align="center">:</td>

				    <td>
				      <input type="text" name="NetWeightG" value="<?= $NetWeightG ?>" size="10" maxlength="10" class="textbox" /> g &nbsp;
				      <input type="text" name="NetWeightKg" value="<?= $NetWeightKg ?>" size="10" maxlength="10" class="textbox" /> kg &nbsp;
				      <input type="text" name="NetWeightLb" value="<?= $NetWeightLb ?>" size="10" maxlength="10" class="textbox" /> lb
				    </td>
				  </tr>

				  <tr>
				    <td>(4) Ref Sample Available</td>
				    <td align="center">:</td>
				    <td><input type="text" name="SampleAvailable" value="<?= $SampleAvailable ?>" size="20" maxlength="50" class="textbox" /> &nbsp; <input type="radio" name="RefSampleAvailable" value="Y" <?= (($RefSampleAvailable == "Y") ? "checked" : "") ?> />Yes &nbsp; &nbsp;<input type="radio" name="RefSampleAvailable" value="N" <?= (($RefSampleAvailable == "N") ? "checked" : "") ?> />No</td>
				  </tr>

				  <tr>
				    <td>Other</td>
				    <td align="center">:</td>
				    <td><input type="text" name="PcOther" value="<?= $PcOther ?>" size="20" class="textbox" /></td>
				  </tr>

				  <tr valign="top">
					<td>Reservations</td>
					<td align="center">:</td>
					<td><textarea name="PcReservations" class="textarea" style="width:98%; height:80px;"><?= $PcReservations ?></textarea></td>
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

	for ($i = 0; $i < 6; $i ++)
	{
?>

				  <tr class="sdRowColor">
				    <td><input type="text" name="Defect<?= $i ?>" value="<?= $objDb->getField($i,  'defect') ?>" maxlength="100" size="65" class="textbox" /></td>
				    <td><input type="text" name="Major<?= $i ?>" value="<?= $objDb->getField($i,  'major') ?>" maxlength="10" size="30" class="textbox" /></td>
				    <td><input type="text" name="Minor<?= $i ?>" value="<?= $objDb->getField($i,  'minor') ?>" maxlength="10" size="30" class="textbox" /></td>
				  </tr>
<?
	}
?>
				</table>

				<br />
				<input type="hidden" name="MaxDefects" id="MaxDefects" value="<?= $MaxDefects ?>" />

			    <table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
				    <td width="200">Sample Size<span class="mandatory">*</span></td>
				    <td width="20" align="center">:</td>
				    <td><input type="text" name="TotalGmts" value="<?= $TotalGmts ?>" size="10" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>No of Defect Allowed</td>
				    <td align="center">:</td>
				    <td><input type="text" name="MaxDefects" value="<?= $MaxDefects ?>" size="10" class="textbox" readonly /></td>
				  </tr>

				  <tr>
				    <td>No of Defect Found</td>
				    <td align="center">:</td>
				    <td><input type="text" name="GmtsDefective" value="<?= $GmtsDefective ?>" size="10" class="textbox" /></td>
				  </tr>

				  <tr valign="top">
					<td>Reservations</td>
					<td align="center">:</td>
					<td><textarea name="AffReservations" class="textarea" style="width:98%; height:30px;"><?= $AffReservations ?></textarea></td>
				  </tr>
				</table>


				<br />
				<h2>4. Packing</h2>

<?
	$sSQL = "SELECT * FROM tbl_yarn_packing WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$PackingDetail                = $objDb->getField(0, "packing_detail");
	$CartonDimension1             = $objDb->getField(0, "carton_dimension1");
	$CartonDimension2             = $objDb->getField(0, "carton_dimension2");
	$IndividualPackingConformity1 = $objDb->getField(0, "individual_packing_conformity1");
	$IndividualPackingConformity2 = $objDb->getField(0, "individual_packing_conformity2");
	$PaperConeConformity1         = $objDb->getField(0, "paper_cone_conformity1");
	$PaperConeConformity2         = $objDb->getField(0, "paper_cone_conformity2");
	$InnerPackingConformity1      = $objDb->getField(0, "inner_packing_conformity1");
	$InnerPackingConformity2      = $objDb->getField(0, "inner_packing_conformity2");
	$AssortmentFoundCorrect1      = $objDb->getField(0, "assortment_found_correct1");
	$AssortmentFoundCorrect2      = $objDb->getField(0, "assortment_found_correct2");
?>
			    <table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr valign="top">
					<td width="200">4.1 Packing Detail</td>
					<td width="20" align="center">:</td>
					<td><textarea name="PackingDetail" class="textarea" style="width:98%; height:80px;"><?= $PackingDetail ?></textarea></td>
				  </tr>
				</table>

			    <table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
				    <td width="200">4.2 Carton Dimension</td>
				    <td width="20" align="center">:</td>
				    <td width="120"><input type="text" name="CartonDimension1" value="<?= $CartonDimension1 ?>" size="12" maxlength="50" class="textbox" /> &nbsp;&amp;</td>
				    <td><input type="text" name="CartonDimension2" value="<?= $CartonDimension2 ?>" size="12" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>4.3 Individual Packing Conformity</td>
				    <td align="center">:</td>
				    <td><input type="radio" name="IndividualPackingConformity1" value="Y" <?= (($IndividualPackingConformity1 == "Y") ? "checked" : "") ?> />Yes &nbsp; &nbsp;<input type="radio" name="IndividualPackingConformity1" value="N" <?= (($IndividualPackingConformity1 == "N") ? "checked" : "") ?> />No</td>
				    <td><input type="text" name="IndividualPackingConformity2" value="<?= $IndividualPackingConformity2 ?>" size="30" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>4.4 Paper Cone Conformity</td>
				    <td align="center">:</td>
				    <td><input type="radio" name="PaperConeConformity1" value="Y" <?= (($PaperConeConformity1 == "Y") ? "checked" : "") ?> />Yes &nbsp; &nbsp;<input type="radio" name="PaperConeConformity1" value="N" <?= (($PaperConeConformity1 == "N") ? "checked" : "") ?> />No</td>
				    <td><input type="text" name="PaperConeConformity2" value="<?= $PaperConeConformity2 ?>" size="30" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>4.5 Inner Packing Conformity</td>
				    <td align="center">:</td>
				    <td><input type="radio" name="InnerPackingConformity1" value="Y" <?= (($InnerPackingConformity1 == "Y") ? "checked" : "") ?> />Yes &nbsp; &nbsp;<input type="radio" name="InnerPackingConformity1" value="N" <?= (($InnerPackingConformity1 == "N") ? "checked" : "") ?> />No</td>
				    <td><input type="text" name="InnerPackingConformity2" value="<?= $InnerPackingConformity2 ?>" size="30" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>4.6 Assortment Found Correct</td>
				    <td align="center">:</td>
				    <td><input type="radio" name="AssortmentFoundCorrect1" value="Y" <?= (($AssortmentFoundCorrect1 == "Y") ? "checked" : "") ?> />Yes &nbsp; &nbsp;<input type="radio" name="AssortmentFoundCorrect1" value="N" <?= (($AssortmentFoundCorrect1 == "N") ? "checked" : "") ?> />No</td>
				    <td><input type="text" name="AssortmentFoundCorrect2" value="<?= $AssortmentFoundCorrect2 ?>" size="30" maxlength="50" class="textbox" /></td>
				  </tr>
				</table>


				<br />
				<h2>5. Marking/Label</h2>
<?
	$sSQL = "SELECT * FROM tbl_yarn_marking_label WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$BarCodeConformity1      = $objDb->getField(0, "bar_code_conformity1");
	$BarCodeConformity2      = $objDb->getField(0, "bar_code_conformity2");
	$ShippingMarkConformity1 = $objDb->getField(0, "shipping_mark_conformity1");
	$ShippingMarkConformity2 = $objDb->getField(0, "shipping_mark_conformity2");
	$OtherMarks1             = $objDb->getField(0, "other_marks1");
	$OtherMarks2             = $objDb->getField(0, "other_marks2");
	$SideMarkConformity1     = $objDb->getField(0, "side_mark_conformity1");
	$SideMarkConformity2     = $objDb->getField(0, "side_mark_conformity2");
	$CountLabel1             = $objDb->getField(0, "count_label1");
	$CountLabel2             = $objDb->getField(0, "count_label2");
	$BalingStrip1            = $objDb->getField(0, "baling_strip1");
	$BalingStrip2            = $objDb->getField(0, "baling_strip2");
	$BrandName1              = $objDb->getField(0, "brand_name1");
	$BrandName2              = $objDb->getField(0, "brand_name2");
	$Other1                  = $objDb->getField(0, "other1");
	$Other2                  = $objDb->getField(0, "other2");
?>
			    <table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
				    <td width="200">5.1 Bar Code Conformity</td>
				    <td width="20" align="center">:</td>
				    <td width="120"><input type="radio" name="BarCodeConformity1" value="Y" <?= (($BarCodeConformity1 == "Y") ? "checked" : "") ?> />Yes &nbsp; &nbsp;<input type="radio" name="BarCodeConformity1" value="N" <?= (($BarCodeConformity1 == "N") ? "checked" : "") ?> />No</td>
				    <td><input type="text" name="BarCodeConformity2" value="<?= $BarCodeConformity2 ?>" size="30" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>5.2 Shipping Mark Conformity</td>
				    <td align="center">:</td>
				    <td><input type="radio" name="ShippingMarkConformity1" value="Y" <?= (($ShippingMarkConformity1 == "Y") ? "checked" : "") ?> />Yes &nbsp; &nbsp;<input type="radio" name="ShippingMarkConformity1" value="N" <?= (($ShippingMarkConformity1 == "N") ? "checked" : "") ?> />No</td>
				    <td><input type="text" name="ShippingMarkConformity2" value="<?= $ShippingMarkConformity2 ?>" size="30" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>5.3 Other Marks</td>
				    <td align="center">:</td>
				    <td><input type="radio" name="OtherMarks1" value="Y" <?= (($OtherMarks1 == "Y") ? "checked" : "") ?> />Yes &nbsp; &nbsp;<input type="radio" name="OtherMarks1" value="N" <?= (($OtherMarks1 == "N") ? "checked" : "") ?> />No</td>
				    <td><input type="text" name="OtherMarks2" value="<?= $OtherMarks2 ?>" size="30" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>5.3.1 Side Mark Conformity</td>
				    <td align="center">:</td>
				    <td><input type="radio" name="SideMarkConformity1" value="Y" <?= (($SideMarkConformity1 == "Y") ? "checked" : "") ?> />Yes &nbsp; &nbsp;<input type="radio" name="SideMarkConformity1" value="N" <?= (($SideMarkConformity1 == "N") ? "checked" : "") ?> />No</td>
				    <td><input type="text" name="SideMarkConformity2" value="<?= $SideMarkConformity2 ?>" size="30" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>5.3.2 Count Label</td>
				    <td align="center">:</td>
				    <td><input type="radio" name="CountLabel1" value="Y" <?= (($CountLabel1 == "Y") ? "checked" : "") ?> />Yes &nbsp; &nbsp;<input type="radio" name="CountLabel1" value="N" <?= (($CountLabel1 == "N") ? "checked" : "") ?> />No</td>
				    <td><input type="text" name="CountLabel2" value="<?= $CountLabel2 ?>" size="30" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>5.3.3 Baling Strip</td>
				    <td align="center">:</td>
				    <td><input type="radio" name="BalingStrip1" value="Y" <?= (($BalingStrip1 == "Y") ? "checked" : "") ?> />Yes &nbsp; &nbsp;<input type="radio" name="BalingStrip1" value="N" <?= (($BalingStrip1 == "N") ? "checked" : "") ?> />No</td>
				    <td><input type="text" name="BalingStrip2" value="<?= $BalingStrip2 ?>" size="30" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>5.3.4 Brand Name</td>
				    <td align="center">:</td>
				    <td><input type="radio" name="BrandName1" value="Y" <?= (($BrandName1 == "Y") ? "checked" : "") ?> />Yes &nbsp; &nbsp;<input type="radio" name="BrandName1" value="N" <?= (($BrandName1 == "N") ? "checked" : "") ?> />No</td>
				    <td><input type="text" name="BrandName2" value="<?= $BrandName2 ?>" size="30" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>5.3.5 Other</td>
				    <td align="center">:</td>
				    <td><input type="radio" name="Other1" value="Y" <?= (($Other1 == "Y") ? "checked" : "") ?> />Yes &nbsp; &nbsp;<input type="radio" name="Other1" value="N" <?= (($Other1 == "N") ? "checked" : "") ?> />No</td>
				    <td><input type="text" name="Other2" value="<?= $Other2 ?>" size="30" maxlength="50" class="textbox" /></td>
				  </tr>
				</table>


				<br />
				<h2>Inspection Conclusion</h2>

			    <table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
				    <td width="260">Quantities Submitted For Inspection</td>
				    <td width="20" align="center">:</td>
				    <td><input type="radio" name="QuantitiesSubmitted" value="Y" <?= (($QuantitiesSubmitted == "Y") ? "checked" : "") ?> />Conform &nbsp; &nbsp;<input type="radio" name="QuantitiesSubmitted" value="N" <?= (($QuantitiesSubmitted == "N") ? "checked" : "") ?> />Not Conform</td>
				  </tr>

				  <tr>
				    <td>Measurements/field Tests</td>
				    <td align="center">:</td>
				    <td><input type="radio" name="MeasurementsFieldTests" value="Y" <?= (($MeasurementsFieldTests == "Y") ? "checked" : "") ?> />Conform &nbsp; &nbsp;<input type="radio" name="MeasurementsFieldTests" value="N" <?= (($MeasurementsFieldTests == "N") ? "checked" : "") ?> />Not Conform</td>
				  </tr>

				  <tr>
				    <td>Style, Material, Color</td>
				    <td align="center">:</td>
				    <td><input type="radio" name="StyleMaterialColor" value="Y" <?= (($StyleMaterialColor == "Y") ? "checked" : "") ?> />Conform &nbsp; &nbsp;<input type="radio" name="StyleMaterialColor" value="N" <?= (($StyleMaterialColor == "N") ? "checked" : "") ?> />Not Conform</td>
				  </tr>

				  <tr>
				    <td>Appearance/functioning</td>
				    <td align="center">:</td>
				    <td><input type="radio" name="AppearanceFunctioning" value="Y" <?= (($AppearanceFunctioning == "Y") ? "checked" : "") ?> />Conform &nbsp; &nbsp;<input type="radio" name="AppearanceFunctioning" value="N" <?= (($AppearanceFunctioning == "N") ? "checked" : "") ?> />Not Conform</td>
				  </tr>

				  <tr>
				    <td>Packing</td>
				    <td align="center">:</td>
				    <td><input type="radio" name="Packing" value="Y" <?= (($Packing == "Y") ? "checked" : "") ?> />Conform &nbsp; &nbsp;<input type="radio" name="Packing" value="N" <?= (($Packing == "N") ? "checked" : "") ?> />Not Conform</td>
				  </tr>

				  <tr>
				    <td>Marking/label</td>
				    <td align="center">:</td>
				    <td><input type="radio" name="MarkingLabel" value="Y" <?= (($MarkingLabel == "Y") ? "checked" : "") ?> />Conform &nbsp; &nbsp;<input type="radio" name="MarkingLabel" value="N" <?= (($MarkingLabel == "N") ? "checked" : "") ?> />Not Conform</td>
				  </tr>

				  <tr valign="top">
					<td><b>General Observation</b></td>
					<td align="center">:</td>
					<td><textarea name="Comments" class="textarea" style="width:98%; height:80px;"><?= $Comments ?></textarea></td>
				  </tr>

				  <tr valign="top">
					<td><b>Factory Representative Comments</b></td>
					<td align="center">:</td>
					<td><textarea name="FactoryComments" class="textarea" style="width:98%; height:80px;"><?= $FactoryComments ?></textarea></td>
				  </tr>

				  <tr>
					<td>For External Lab testing</td>
					<td align="center">:</td>
					<td><input type="text" name="ExternalLabTesting" value="<?= $ExternalLabTesting ?>" size="20" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>For Record/Client</td>
					<td align="center">:</td>
					<td><input type="text" name="ForRecordClient" value="<?= $ForRecordClient ?>" size="20" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Sealed at Factory</td>
					<td align="center">:</td>
					<td><input type="text" name="SealedAtFactory" value="<?= $SealedAtFactory ?>" size="20" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Reasons for not taking samples</td>
					<td align="center">:</td>
					<td><input type="text" name="ReasonForNotTakingSamples" value="<?= $ReasonForNotTakingSamples ?>" size="20" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Overall Inspection Conclusion<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="AuditResult" onchange="$('Sms').value='1';">
						<option value=""></option>
						<option value="P">Pass</option>
						<option value="F">Fail</option>
						<option value="H">Hold</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.AuditResult.value = "<?= $AuditResult ?>";
					  -->
					  </script>
					</td>
				  </tr>

				  <tr>
					<td>Total No of Cartons Pallets/Selected</td>
					<td align="center">:</td>
					<td><input type="text" name="TotalCartonsSelected" value="<?= $TotalCartonsSelected ?>" size="20" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>No of Cones Inspected</td>
					<td align="center">:</td>
					<td><input type="text" name="ConesInspected" value="<?= $ConesInspected ?>" size="20" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Data Measurement/Field test on sub-samples</td>
					<td align="center">:</td>
					<td><input type="text" name="DataMeasurementTest" value="<?= $DataMeasurementTest ?>" size="20" maxlength="100" class="textbox" /></td>
				  </tr>
				</table>

				<br />
