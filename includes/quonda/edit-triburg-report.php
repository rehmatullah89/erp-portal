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

        function getCartonSampleSize($iCartons)
        {
            $iCartonSampleSize = 0;
            
            if($iCartons > 2 && $iCartons < 16)
                $iCartonSampleSize = 3;
            else if($iCartons >= 16 && $iCartons < 26)
                $iCartonSampleSize = 5;
            else if($iCartons >= 26 && $iCartons < 51)
                $iCartonSampleSize = 8;
            else if($iCartons >= 51 && $iCartons < 91)
                $iCartonSampleSize = 13;
            else if($iCartons >= 91 && $iCartons < 152)
                $iCartonSampleSize = 20;
            else if($iCartons >= 152 && $iCartons < 281)
                $iCartonSampleSize = 32;
            else if($iCartons >= 281 && $iCartons < 501)
                $iCartonSampleSize = 50;
            else if($iCartons >= 501 && $iCartons < 1201)
                $iCartonSampleSize = 80;
            else if($iCartons >= 1201)
                $iCartonSampleSize = 125;
            
            return $iCartonSampleSize;
        }

	$sSQL = "SELECT * FROM tbl_triburg_inspection_summary WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sInspectionStatus              = $objDb->getField(0, "inspection_status");
                $sVisualAudit                   = $objDb->getField(0, "visual_audit");
                $sVisualAuditRemarks            = $objDb->getField(0, "visual_audit_remarks");
                $sShippingMarks                 = $objDb->getField(0, "shipping_marks");
                $sShippingMarksRemarks          = $objDb->getField(0, "shipping_marks_remarks");
                $sMaterialConformity            = $objDb->getField(0, "material_conformity");
                $sMaterialConformityRemarks     = $objDb->getField(0, "material_conformity_remarks");
                $sProductAppearance             = $objDb->getField(0, "product_apperance");
                $sProductAppearanceRemarks      = $objDb->getField(0, "product_apperance_remarks");
                $sProductColor                  = $objDb->getField(0, "product_color");
                $sProductColorRemarks           = $objDb->getField(0, "product_color_remarks");
                $sHandFeel                      = $objDb->getField(0, "hand_feel");
                $sHandFeelRemarks               = $objDb->getField(0, "hand_feel_remark");
                $sWearerTest                    = $objDb->getField(0, "wearer_test");
                $sWearerTestRemarks             = $objDb->getField(0, "wearer_test_remarks");
                $sPackingCount                  = $objDb->getField(0, "packing_count");
                $sPackingCountRemarks           = $objDb->getField(0, "packing_count_remarks");
                $sPackingFtp                    = $objDb->getField(0, "packing_ftp");
                $sPackingFtpRemarks             = $objDb->getField(0, "packing_ftp_remarks");
                $sPackingGtp                    = $objDb->getField(0, "packing_gtp");
                $sPackingGtpRemarks             = $objDb->getField(0, "packing_gtp_remarks");
                $sPacking                       = $objDb->getField(0, "packing");
                $sPackingRemarks                = $objDb->getField(0, "packing_remarks");
                $sCartonDropTest                = $objDb->getField(0, "carton_drop_test");
                $sCartonDropTestRemarks         = $objDb->getField(0, "carton_drop_remarks");
                $sShadeBand                     = $objDb->getField(0, "shade_band");
                $sShadeBandRemarks              = $objDb->getField(0, "shade_band_remarks");
                $sCartonQuality                 = $objDb->getField(0, "carton_quality");
                $sCartonQualityRemarks          = $objDb->getField(0, "carton_quality_remarks");
                $sCartonWeight                  = $objDb->getField(0, "carton_weight");
                $sCartonWeightRemarks           = $objDb->getField(0, "carton_weight_remarks");
                $sCartonDimension               = $objDb->getField(0, "carton_dimension");
                $sCartonDimensionRemarks        = $objDb->getField(0, "carton_dimension_remarks");
                $sBarcodeVerification           = $objDb->getField(0, "barcode_verification");
                $sBarcodeVerificationRemarks    = $objDb->getField(0, "barcode_verification_remarks");
                $sLabeling                      = $objDb->getField(0, "labeling");
                $sLabelingRemarks               = $objDb->getField(0, "labeling_remarks");
                $sMarkings                      = $objDb->getField(0, "markings");
                $sMarkingsRemarks               = $objDb->getField(0, "markings_remarks");
                $sWorkmanship                   = $objDb->getField(0, "workmanship");
                $sWorkmanshipRemarks            = $objDb->getField(0, "workmanship_remarks");
                $sAppearance                    = $objDb->getField(0, "appearance");
                $sAppearanceRemarks             = $objDb->getField(0, "appearance_remarks");
                $sFunction                      = $objDb->getField(0, "function");
                $sFunctionRemarks               = $objDb->getField(0, "function_remarks");
                $sPrintedMaterials              = $objDb->getField(0, "printed_materials");
                $sPrintedMaterialsRemarks       = $objDb->getField(0, "printed_materials_remarks");
                $sFinishing                     = $objDb->getField(0, "finishing");
                $sFinishingRemarks              = $objDb->getField(0, "finishing_remarks");
                $sFitting                       = $objDb->getField(0, "fitting");
                $sFittingRemarks                = $objDb->getField(0, "fitting_remarks");
                $sPpSample                      = $objDb->getField(0, "pp_sample");
                $sPpSampleRemarks               = $objDb->getField(0, "pp_sample_remarks");
                $sMetalDetectionTest            = $objDb->getField(0, "metal_detection_test");
                $sMetalDetectionTestRemarks     = $objDb->getField(0, "metal_detection_test_remarks");
                $sMeasurementResult             = $objDb->getField(0, "measurement_result");
                $sMeasurementResultRemarks      = $objDb->getField(0, "measurement_result_remarks");
                $sGarmentWeight                 = $objDb->getField(0, "garment_weight");
                $sGarmentWeightRemarks          = $objDb->getField(0, "garment_weight_remarks");
                $sCordNorm                      = $objDb->getField(0, "cords_norm");
                $sCordNormRemarks               = $objDb->getField(0, "cords_norm_remarks");
                $sInspectionConditions          = $objDb->getField(0, "inspection_conditions");
                $sInspectionConditionsRemarks   = $objDb->getField(0, "inspection_conditions_remarks");
                $sShipmentAudit                 = $objDb->getField(0, "shipment_audit");
                $sShipmentAuditRemarks          = $objDb->getField(0, "shipment_audit_remarks");
                $sRemarks                       = $objDb->getField(0, "remarks");
                $sCartonNos                     = $objDb->getField(0, "carton_numbers");

                
	}
?>
				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="120"><b>Audit Code</b></td>
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

				  <tr valign="top">
					<td>PO<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="PO" id="PO" value="" class="textbox" size="30" maxlength="200" /></td>
				  </tr>

				  <tr valign="top">
					<td>Style<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Style" id="Style">
						<option value=""></option>
<?
	$sStyles = getList("tbl_styles s, tbl_po_colors pc", "DISTINCT(s.id)", "s.style", "s.id=pc.style_id AND FIND_IN_SET(pc.po_id, '$sSelectedPos')", "s.style");

	foreach ($sStyles as $sKey => $sValue)
	{
?>
	  	        		<option value="<?= $sKey ?>"<?= (($sKey == $Style) ? ' selected' : '') ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
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
					<td>QA Type<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="AuditType">
						<option value="B">Bulk</option>
						<option value="BG">B-Grade</option>
						<option value="SS">Sales Sample</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.AuditType.value = "<?= $AuditType ?>";
					  -->
					  </script>
					</td>
				  </tr>
                                  
                                  <tr>
					<td>Sampling Plan</td>
					<td align="center">:</td>

					<td>
					  <select name="SamplingPlan">
						<option value="1">Single</option>
						<option value="2">Double</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.SamplingPlan.value = "<?= $CheckLevel ?>";
					  -->
					  </script>
					</td>
				  </tr>
                                  
                                  <tr>
					<td>Total No. of Cartons</td>
					<td align="center">:</td>
                                        <td><input type="text" class="textbox" name="TotalCartons" value="<?=$TotalCartons?>" /></td>
				  </tr>
				</table>

				<br />
				<h2>Overall Result Summary</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="120">Audit Result<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

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
				</table>

				<div style="padding:10px;">
				<table border="1" bordercolor="#dddddd" cellpadding="5" cellspacing="0" width="100%">
				  <tr bgcolor="#eeeeee">
					<td></td>
					<td width="100" align="center">Pass / Fail</td>
					<td width="500" align="center">Remarks</td>
				  </tr>

				  <tr>
					<td>Visual Audit</td>

					<td align="center">
					  <select name="VisualAudit">
						<option value="">N/A</option>
						<option value="P"<?= (($sVisualAudit == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sVisualAudit == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="VisualAuditRemarks" value="<?= $sVisualAuditRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
					<td>Shipping Marks</td>

					<td align="center">
					  <select name="ShippingMarks">
						<option value="">N/A</option>
						<option value="P"<?= (($sShippingMarks == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sShippingMarks == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="ShippingMarksRemarks" value="<?= $sShippingMarksRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>
                                  
                                  <tr>
					<td><span>Material Conformity</span></td>

					<td align="center">
					  <select name="MaterialConformity">
						<option value="">N/A</option>
						<option value="P"<?= (($sMaterialConformity == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sMaterialConformity == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="MaterialConformityRemarks" value="<?= $sMaterialConformityRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>


  				  <tr>
				    <td colspan="3">Product Conformity</td>
				  </tr>

				  <tr>
					<td><span style="padding-left:80px;">General Apperance</span></td>

					<td align="center">
					  <select name="GeneralApperance">
						<option value="">N/A</option>
						<option value="P"<?= (($sProductAppearance == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sProductAppearance == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="GeneralApperanceRemarks" value="<?= $sProductAppearanceRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

                       		<tr>
					<td><span style="padding-left:80px;">Color</span></td>

					<td align="center">
					  <select name="ProductColor">
						<option value="">N/A</option>
						<option value="P"<?= (($sProductColor == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sProductColor == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="PrductColorRemarks" value="<?= $sProductColorRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>
				  <tr>
					<td><span style="padding-left:80px;">Hand Feel</span></td>

					<td align="center">
					  <select name="HandFeel">
						<option value="">N/A</option>
						<option value="P"<?= (($sHandFeel == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sHandFeel == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="HandFeelRemarks" value="<?= $sHandFeelRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
					<td><span style="padding-left:80px;">Wearer Test</span></td>

					<td align="center">
					  <select name="WearerTest">
						<option value="">N/A</option>
						<option value="P"<?= (($sWearerTest == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sWearerTest == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="WearerTestRemarks" value="<?= $sWearerTestRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>
  				  <tr>
				    <td colspan="3">Packagin & Assortment</td>
				  </tr>

				  <tr>
					<td><span style="padding-left:80px;">Count Accuracy</span></td>

					<td align="center">
					  <select name="CountAccuracy">
						<option value="">N/A</option>
						<option value="P"<?= (($sPackingCount == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sPackingCount == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="CountAccuracyRemarks" value="<?= $sPackingCountRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
					<td><span style="padding-left:80px;">FTP</span></td>

					<td align="center">
					  <select name="PackingFtp">
						<option value="">N/A</option>
						<option value="P"<?= (($sPackingFtp == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sPackingFtp == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="PackingFtpRemarks" value="<?= $sPackingFtpRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
					<td><span style="padding-left:80px;">Gtp</span></td>

					<td align="center">
					  <select name="PackingGtp">
						<option value="">N/A</option>
						<option value="P"<?= (($sPackingGtp == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sPackingGtp == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="PackingGtpRemarks" value="<?= $sPackingGtpRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
					<td><span style="padding-left:80px;">Packing</span></td>

					<td align="center">
					  <select name="Packing">
						<option value="">N/A</option>
						<option value="P"<?= (($sPacking == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sPacking == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="PackingRemakrs" value="<?= $sPackingRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
					<td><span style="padding-left:80px;">Carton Drop Test</span></td>

					<td align="center">
					  <select name="CartonDropTest">
						<option value="">N/A</option>
						<option value="P"<?= (($sCartonDropTest == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sCartonDropTest == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="CartonDropTestRemarks" value="<?= $sCartonDropTestRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
					<td><span style="padding-left:80px;">Shade Band</span></td>

					<td align="center">
					  <select name="ShadeBand">
						<option value="">N/A</option>
						<option value="P"<?= (($sShadeBand == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sShadeBand == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="ShadeBandRemarks" value="<?= $sShadeBandRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
					<td><span style="padding-left:80px;">Carton Quality</span></td>

					<td align="center">
					  <select name="CartonQuality">
						<option value="">N/A</option>
						<option value="P"<?= (($sCartonQuality == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sCartonQuality == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="CartonQualityRemarks" value="<?= $sCartonQualityRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
					<td><span style="padding-left:80px;">Carton Weight</span></td>

					<td align="center">
					  <select name="CartonWeight">
						<option value="">N/A</option>
						<option value="P"<?= (($sCartonWeight == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sCartonWeight == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="CartonWeightRemarks" value="<?= $sCartonWeightRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>
                                  
                                   <tr>
					<td><span style="padding-left:80px;">Carton Dimension</span></td>

					<td align="center">
					  <select name="CartonDimension">
						<option value="">N/A</option>
						<option value="P"<?= (($sCartonDimension == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sCartonDimension == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="CartonDimensionRemarks" value="<?= $sCartonDimensionRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>
  				  <tr>
				    <td colspan="3">Labelling, Marks</td>
				  </tr>

                                  <tr>
					<td><span style="padding-left:80px;">Bar Code Verification</span></td>

					<td align="center">
					  <select name="BarCodeVerification">
						<option value="">N/A</option>
						<option value="P"<?= (($sBarcodeVerification == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sBarcodeVerification == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="BarCodeVerificationRemarks" value="<?= $sBarcodeVerificationRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>
				  <tr>
					<td><span style="padding-left:80px;">Labelling</span></td>

					<td align="center">
					  <select name="Labelling">
						<option value="">N/A</option>
						<option value="P"<?= (($sLabeling == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sLabeling == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="LabellingRemarks" value="<?= $sLabelingRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>
                                  
                                  				  <tr>
                                    <td><span style="padding-left:80px;">Markings</span></td>

					<td align="center">
					  <select name="Markings">
						<option value="">N/A</option>
						<option value="P"<?= (($sMarkings == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sMarkings == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="MarkingsRemarks" value="<?= $sMarkingsRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>
  				  <tr>
				    <td colspan="3">Workmanship</td>
				  </tr>

				  <tr>
					<td><span style="padding-left:80px;">Workmanship</span></td>

					<td align="center">
					  <select name="Workmanship">
						<option value="">N/A</option>
						<option value="P"<?= (($sWorkmanship == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sWorkmanship == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="WorkmanshipRemarks" value="<?= $sWorkmanshipRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>
                                    <tr>
					<td><span style="padding-left:80px;">Appearance</span></td>

					<td align="center">
					  <select name="Appearance">
						<option value="">N/A</option>
						<option value="P"<?= (($sAppearance == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sAppearance == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="AppearanceRemarks" value="<?= $sAppearanceRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

                                  <tr>
					<td><span style="padding-left:80px;">Function</span></td>

					<td align="center">
					  <select name="Function">
						<option value="">N/A</option>
						<option value="P"<?= (($sFunction == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sFunction == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="FunctionRemarks" value="<?= $sFunctionRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

                                  <tr>
					<td><span style="padding-left:80px;">Printed Material</span></td>

					<td align="center">
					  <select name="PrintedMaterial">
						<option value="">N/A</option>
						<option value="P"<?= (($sPrintedMaterials == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sPrintedMaterials == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="PrintedMaterialRemarks" value="<?= $sPrintedMaterialsRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

                                <tr>
					<td><span style="padding-left:80px;">Finishing</span></td>

					<td align="center">
					  <select name="WorkmanshipFinishing">
						<option value="">N/A</option>
						<option value="P"<?= (($sFinishing == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sFinishing == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="WorkmanshipFinishingRemarks" value="<?= $sFinishingRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

                                   <tr>
					<td><span style="padding-left:80px;">Fitting</span></td>

					<td align="center">
					  <select name="Fitting">
						<option value="">N/A</option>
						<option value="P"<?= (($sFitting == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sFitting == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="FittingRemarks" value="<?= $sFittingRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>
				  <tr>
					<td>PP Sample</td>

					<td align="center">
					  <select name="PPSample">
						<option value="">N/A</option>
						<option value="P"<?= (($sPpSample == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sPpSample == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="PpSampleRemarks" value="<?= $sPpSampleRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
					<td>Onsite Test for Metal Detection</td>

					<td align="center">
					  <select name="MetalDetection">
						<option value="">N/A</option>
						<option value="P"<?= (($sMetalDetectionTest == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sMetalDetectionTest == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="MetalDetectionRemarks" value="<?= $sMetalDetectionTestRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>
                                  
                                  <tr>
					<td>Shipment Result</td>

					<td align="center">
					  <select name="ShipmentResult">
						<option value="">N/A</option>
						<option value="P"<?= (($sShipmentAudit == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($sShipmentAudit == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="ShipmentResultRemarks" value="<?= $sShipmentAuditRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				</table>
				</div>

				<br />
				<h3>Remarks</h3>

				<div style="padding:2px;">
				<table border="0" cellpadding="5" cellspacing="0" width="100%">
				  <tr>
                                      <td><textarea name="Remarks" rows="5" style="width: 98%;"><?= $sRemarks ?></textarea></td>
                                   </tr>
				</table>
				</div>
<?
                        if($TotalCartons > 2)
                        {
?>
				<h2>List of Carton Numbers Opened</h2>

				<div style=" padding:3px;">
                                    
                                    <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                                        <tr>
<?
                                    $iCartonsSampleSize = getCartonSampleSize($TotalCartons);                                    
                                    $TotalRows = floor($iCartonsSampleSize)/3;
                                    $TotalCols = floor($iCartonsSampleSize)%3;
                                    
                                    if($TotalCols > 0)
                                        $TotalRows += 1;
                                    
                                    $sSQL = "SELECT sample_no, carton_no, result FROM tbl_qa_packaging_details where audit_id='$Id'";
                                    $objDb->query($sSQL);

                                    $sSamplesList = array();
                                    $iCount = $objDb->getCount( );
                                    
                                    for($k=0; $k<$iCount; $k++)
                                    {
                                        $iSampleNum = $objDb->getField($k, 'sample_no');
                                        $sResult    = $objDb->getField($k, 'result');
                                        $sCartonNo  = $objDb->getField($k, 'carton_no');
                                                        
                                        $sSamplesList[$iSampleNum] = array('result'=>$sResult, 'carton_no'=>$sCartonNo);
                                    }

                                        $iSampleNum = 1;            
                                        
                                        for($i=0; $i<3; $i++)
                                        {
?>
                                            <td valign="top">
				  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                                      <tr class="sdRowHeader">
                                      <th style="width:25%;">Sample#</th>
                                      <th style="width:35%;">Carton No</th>
                                      <th style="width:40%;">Status</th>
                                      </tr>
<?
                                            for($j=0; $j<$TotalRows; $j ++)
                                            {
                                                
                                                    $sResult    = $sSamplesList[$iSampleNum]['result'];
                                                    $sCartonNo  = $sSamplesList[$iSampleNum]['carton_no'];
                                                    
?>
                                      <tr>
                                          <td style="width:10%;"><?=$iSampleNum?><input type="hidden" value="<?=$iSampleNum?>" name="SampleNos[]"></td>
                                          <td style="width:35%;"><input type="text" style="width:80px;" class="text" name="CartonNo[]" id="CartonNo<?=$iSampleNum?>" value="<?=$sCartonNo?>"></td>
                                          <td style="width:55%;">
                                              <select name="CartonStatus[]" style="width:90px;">
                                                  <option value="">N/A</option>
                                                  <option value="P" <?=($sResult == 'P'?'selected':'')?>>Approved</option>
                                                  <option value="F" <?=($sResult == 'F'?'selected':'')?>>Not Approved</option>
                                               </select>
                                          </td>
                                      </tr>
<?
                                               $iSampleNum++;
                                                        
                                               if($iSampleNum == ($iCartonsSampleSize-1))
                                                    break;
                                            }
?>
				  </table>
                                            </td>
<?
                                        }
?>   
                                        </tr>
                                    </table>
				</div>
<?
                        }
?>
                                <br />
				<h2 style="margin-bottom:0px;">PO's Color & Size wise Quantities</h2>                                
<?
	$sQaQuantitiesList = getList("tbl_qa_report_quantities", "CONCAT(po_id, '-', size_id, '-', color)", "quantity", "audit_id='$Id'");

	
	$sSQL = "SELECT po.id, pc.color, po.order_no,
				   s.id as _iSize, s.size,
				   SUM(pq.quantity) AS _Quantity
			FROM tbl_po po, tbl_po_colors pc, tbl_po_quantities pq, tbl_sizes s
			WHERE po.id=pc.po_id AND pc.po_id=pq.po_id AND pq.size_id=s.id AND pc.style_id='$Style' AND (pc.po_id='$PO' OR FIND_IN_SET(pc.po_id, '$AdditionalPos'))
				  AND pq.quantity>'0' AND FIND_IN_SET(s.id, '$Sizes') AND FIND_IN_SET(pc.color, '$Colors')
			GROUP BY po.id, pc.color, s.id
			ORDER BY po.id, pc.color, s.position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$sLastPo         = "";
	$sLastColor      = "";

	for($i = 0; $i < $iCount; $i ++)
	{
		$iTPoId     = $objDb->getField($i, 'po.id');
		$sTOrderNo  = $objDb->getField($i, 'order_no');
		$sTColor    = $objDb->getField($i, 'color');
		$iTSize     = $objDb->getField($i, '_iSize');
		$sTSize     = $objDb->getField($i, 'size');
		$iTQunatity = $objDb->getField($i, '_Quantity');

		
		if ($sTOrderNo != $sLastPo)
		{
			if ($i > 0)
			{
?>
                                </table>
<?
			}
?>
                                <h3>Order No: <?= $sTOrderNo ?></h3>
								
                                <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                                    <tr class="sdRowHeader">
                                              <td><b>Color</b></td>
                                              <td width="80"><b>Size</b></td>
                                              <td width="150"><b>Order Qty</b></td>
											  <td width="150"><b>Presented Qty</b></td>
                                    </tr>
<?
		}
		
		
		if ($sTColor != $sLastColor && $i > 0)
		{
?>
                                    <tr bgcolor="#f0f0f0">
                                              <td colspan="4">&nbsp;</td>
                                    </tr>
<?
		}

		
		$sLastPo          = $sTOrderNo;
		$sLastColor       = $sTColor;
		$iPQuantity = $sQaQuantitiesList["{$iTPoId}-{$iTSize}-{$sTColor}"];
?>
	
                                    <tr>
                                        <td><?= $sTColor ?></td>
                                        <td><?= $sTSize ?></td>
                                        <td><?= formatNumber($iTQunatity, false) ?></td>
                                        <td><input type="text" name="Qty_<?= $iTPoId ?>_<?= $iTSize ?>_<?= md5($sTColor) ?>" value="<?= $iPQuantity ?>" class="textbox" size="10" maxlength="10" /></td>
                                    </tr>
<?
	}
?>
                                </table>
<?
/*
        $iQuantitiesList = array();
        
        $sSQL = "SELECT *
			FROM tbl_qa_report_quantities
			WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	
	for($i = 0; $i < $iCount; $i ++)
        {
                $iMSize      = $objDb->getField($i, 'size_id');
                $iMPoId      = $objDb->getField($i, 'po_id');
                $sMColor     = $objDb->getField($i, 'color');
                $iMQunatity  = $objDb->getField($i, 'quantity');
                
                $iQuantitiesList[$iMPoId][$iMSize]["{$sMColor}"] = $iMQunatity;
        }
        
$sAllPOs = $PO;
$iCounter = 1;

if($AdditionalPos != "")
    $sAllPOs = $sAllPOs.",".$AdditionalPos;

$iAllPOs = explode(",", $sAllPOs);
foreach ($iAllPOs as $iPoId)
{
    $sPoNo = getDbValue("order_no", "tbl_po", "id='$iPoId'");
?>
                                <h3>Order No: <?=$sPoNo?><input type="hidden" name="PosArr[]" value="<?=$iPoId?>"></h3>
                                <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                                    <tr class="sdRowHeader">
                                              <td width="20" align="center"><b>#</b></td>
                                              <td width="80"><b>Size</b></td>
                                              <td width="200"><b>Color</b></td>
                                              <td><b>Quantity</b></td>
                                    </tr>
<?
                                        $sColors = @explode(",", $Colors);
                                        foreach ($sColors as $sColor)
                                        {
                                            $sSpecialColor = str_replace(["'",",",'"',"&"," "], "", $sColor);
                                                foreach ($iSizes as $iSize)
                                                {
?>
                                    <tr>
                                        <td><?=$iCounter++?></td>
                                        <td><input type="hidden" name="SizesArr<?=$iPoId?>_<?=$iSize?>_<?=$sSpecialColor?>" value="<?=$iSize?>"><?=getDbValue("size", "tbl_sampling_sizes", "id='$iSize'")?></td>
                                        <td><input type="hidden" name="ColorsArr<?=$iPoId?>_<?=$iSize?>_<?=$sSpecialColor?>" value="<?=$Colors?>"><?=$Colors?></td>
                                        <td><input type="text" name="QuantitiesArr<?=$iPoId?>_<?=$iSize?>_<?=$sSpecialColor?>" value="<?=$iQuantitiesList[$iPoId][$iSize]["{$Colors}"]?>"></td>
                                    </tr>
<?
                                                }
                                        }
?>
                                  </table>
    
<?
}
*/
?>
				<br />
				<h2 id="DefectDetails" style="margin-bottom:0px;">Defects Details</h2>
<?
	$iDefects = 0;

	$sSQL = "SELECT * FROM tbl_qa_report_defects WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	$sSQL = "SELECT DISTINCT(type_id), (SELECT type FROM tbl_defect_types WHERE id=tbl_defect_codes.type_id) FROM tbl_defect_codes WHERE report_id='$ReportId' ORDER BY type_id";
	$objDb2->query($sSQL);

	$iCount2 = $objDb2->getCount( );


	$sSQL = "SELECT * FROM tbl_defect_areas WHERE status='A' ORDER BY area";
	$objDb4->query($sSQL);

	$iCount4 = $objDb4->getCount( );
?>
				<input type="hidden" id="Count" name="Count" value="<?= $iCount ?>" />

			    <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			      <tr class="sdRowHeader">
					<td width="50" align="center"><b>#</b></td>
					<td><b>Code - Check Points</b></td>
					<td width="100" align="center"><b>Defects</b></td>
					<td width="200" align="center"><b>Area</b></td>
					<td width="100" align="center"><b>Nature</b></td>
					<td width="50" align="center"><b>Delete</b></td>
			      </tr>
			    </table>

			    <div id="QaDefects">
<?
	$sDefectCode = "";
	$sDefectArea = "";

	for($i = 0; $i < $iCount; $i ++)
	{
		if ($objDb->getField($i, 'nature') > 0)
			$iDefects += $objDb->getField($i, 'defects');
?>

				<div id="DefectRecord<?= $i ?>" class="defectRecords">
				  <div>
				    <input type="hidden" id="DefectId<?= $i ?>" name="DefectId<?= $i ?>" value="<?= $objDb->getField($i, 'id') ?>" class="defectId" />

					<table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
					  <tr class="sdRowColor" valign="top">
						<td width="50" align="center" class="serial"><?= ($i + 1) ?></td>

						<td>
                                                    <select id="Code<?= $i ?>" name="Code<?= $i ?>" class="defectCode" onchange="$('Sms').value='1';" required="">
							<option value=""></option>
<?
		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iTypeId = $objDb2->getField($j, 0);
			$sType   = $objDb2->getField($j, 1);
?>
		        			<optgroup label="<?= $sType ?>">
<?
			$sSQL = "SELECT id, code, defect FROM tbl_defect_codes WHERE report_id='$ReportId' AND type_id='$iTypeId' ORDER BY code";
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );

			for ($k = 0; $k < $iCount3; $k ++)
			{
				$iCodeId = $objDb3->getField($k, 0);
				$sCode   = $objDb3->getField($k, 1);
				$sDefect = $objDb3->getField($k, 2);

?>
		        			  <option value="<?= $iCodeId ?>"><?= $sCode ?> - <?= $sDefect ?></option>
<?
				if ($iCodeId == $objDb->getField($i, 'code_id'))
					$sDefectCode = $sCode;
			}
?>
		        			</optgroup>
<?
		}
?>
						  </select>

						  <script type="text/javascript">
						  <!--
							document.frmData.Code<?= $i ?>.value = "<?= $objDb->getField($i, 'code_id') ?>";
						  -->
						  </script>
						</td>

                                                <td width="100" align="center"><input type="text" id="Defects<?= $i ?>" name="Defects<?= $i ?>" value="<?= $objDb->getField($i, 'defects') ?>" maxlength="3" size="3" class="textbox defectsCount" onchange="$('Sms').value='1';" required=""/></td>

						<td width="200" align="center">
						  <select id="Area<?= $i ?>" name="Area<?= $i ?>" class="defectArea" onchange="$('Sms').value='1';" style="width:200px;">
							<option value=""></option>
<?
		for ($j = 0; $j < $iCount4; $j ++)
		{
			$iAreaId = $objDb4->getField($j, 0);
			$sArea   = $objDb4->getField($j, 1);

			$sAreaId = str_pad($iAreaId, 2, '0', STR_PAD_LEFT);

?>
		        			<option value="<?= $sAreaId ?>"><?= $sArea ?></option>
<?
			if ($iAreaId == $objDb->getField($i, 'area_id'))
				$sDefectArea = $sAreaId;
		}
?>
						  </select>

						  <script type="text/javascript">
						  <!--
							document.frmData.Area<?= $i ?>.value = "<?= str_pad($objDb->getField($i, 'area_id'), 2, '0', STR_PAD_LEFT); ?>";
						  -->
						  </script>
						</td>

						<td width="100" align="center">
                                                    <select id="Nature<?= $i ?>" name="Nature<?= $i ?>" class="defectNature" onchange="$('Sms').value='1';" required="">
                                                        <option value=""></option>
                                                        <option value="2">Critical</option>
                                                        <option value="1">Major</option>
                                                        <option value="0">Minor</option>
                                                    </select>

						  <script type="text/javascript">
						  <!--
							document.frmData.Nature<?= $i ?>.value = "<?= $objDb->getField($i, 'nature') ?>";
						  -->
						  </script>
						</td>

						<td width="50" align="center"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" style="cursor:pointer;" class="deleteDefect" rel="<?= $i ?>" /></td>
					  </tr>

					  <tr>
					    <td align="center"><img src="images/icons/pictures.gif" width="16" height="16" alt="Defect Picture" title="Defect Picture" /></td>

					    <td colspan="5">
					      <input type="file" id="Picture<?= $i ?>" name="Picture<?= $i ?>" value="" class="textbox defectPicture" size="30" />
<?
		$sPicture = $objDb->getField($i, 'picture');
		
		if ($sPicture != "" && @file_exists($sQuondaDir.$sPicture))
		{
?>
						  <span>&bull; (<a href="<?= $sPicsDir ?><?= $sPicture ?>" class="lightview"><?= $sPicture ?></a>)&nbsp;</span>
						  <input type="hidden" name="PrevPicture<?= $i ?>" value="<?= $sPicture ?>">
<?
		}
		
		else if ($sPicture != "")
		{
?>
						  <span>&bull; (<?= $sPicture ?>)&nbsp;</span>
<?
		}
?>
					    </td>
					  </tr>
					</table>
				  </div>
				</div>
<?
	}

	if ($GmtsDefective == 0)
		$GmtsDefective = $iDefects;
?>
				</div>

				<div class="qaButtons">
				  <input type="button" value="" class="btnAdd" title="Add Defect" onclick="addDefect( );" />
				  <!--<input type="button" value="" class="btnDelete" title="Delete Defect" onclick="deleteDefect( );" />-->
				</div>
                                
<?
                                if(getCartonSampleSize($TotalCartons) > 2)
                                {
                                    
                                    $sSQL = "SELECT *
                                            FROM tbl_qa_packaging_defects
                                            WHERE audit_id='$Id'";
                                    $objDb->query($sSQL);

                                    $iPackagingCount = $objDb->getCount( );
                                   
?>
                                <div id="PackaginDefects">
                                    <h2 style="margin-bottom:0px;">Packagin Defects</h2>
				<input type="hidden" id="CountRows" name="CountRows" value="<?= $iPackagingCount ?>" />

			    <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			      <tr class="sdRowHeader">
					<td width="50" align="center"><b>#</b></td>
					<td><b>Defect</b></td>
					<td width="100" align="center"><b>Sample No</b></td>
					<td width="200" align="center"><b>Picture</b></td>
					<td width="50" align="center"><b>Delete</b></td>
			      </tr>
			    </table>
                                <?
                            $sPackagingDefectsList = getList("tbl_packaging_defects", "id", "CONCAT(code,' - ',defect)", "", "id");
?>
                                 <div id="PDefect" style="display:none;">
                                                <option value=""></option>
<?
                                               foreach($sPackagingDefectsList as $iPId => $sPDefect)
                                               {
?>
                                                <option value="<?=$iPId?>"><?=$sPDefect?></option> 
<?
                                               }
?>
                                            </div>
                                 <div id="PSamples" style="display:none;">
                                                <option value=""></option>
<?
                                             for($i=1; $i<=getCartonSampleSize($TotalCartons); $i++)               
                                             {
?>
                                                <option value="<?=$i?>"><?=$i?></option>
<?
                                             }
?>
                                            </div>
                            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="PackaginDefectsTable">
<?
                        if($iPackagingCount > 0)
                        {
                                @list($sPkYear, $sPkMonth, $sPkDay) = @explode("-", $AuditDate);
                                $sPkPicsDir   = (SITE_URL.PACKAGING_PICS_DIR.$sPkYear."/".$sPkMonth."/".$sPkDay."/");
                                $sPackagingDir = ($sBaseDir.PACKAGING_PICS_DIR.$sPkYear."/".$sPkMonth."/".$sPkDay."/");
                
                                for($i = 0; $i < $iPackagingCount; $i ++)
                                {
                                    $iTableId      = $objDb->getField($i, 'id');
                                    $iDefectCodeId = $objDb->getField($i, 'defect_code_id');
                                    $iSampleNumber = $objDb->getField($i, 'sample_no');
                                    $sDefectPicture= $objDb->getField($i, 'picture');
?>
                                    <tr id="RowNo<?=$i+1?>">
                                        <td width="50" align="center"><b><?=$i+1?></b><input type="hidden" name="PackagingDefectRows[]" value='0'></td>
                                        <td>
                                            <select name="PDefect[]" required="">
                                                <option value=""></option>
<?
                                               foreach($sPackagingDefectsList as $iPId => $sPDefect)
                                               {
?>
                                                <option value="<?=$iPId?>" <?=($iDefectCodeId == $iPId)?'selected':''?>><?=$sPDefect?></option> 
<?
                                               }
?>
                                            </select>
                                        </td>
					<td width="100" align="center">
                                            <select name="PDSamples[]" required="">
                                                <option value=""></option>
<?
                                             for($j=1; $j<=getCartonSampleSize($TotalCartons); $j++)               
                                             {
?>
                                                <option value="<?=$j?>" <?=($iSampleNumber == $j)?'selected':''?>><?=$j?></option>
<?
                                             }
?>
                                            </select>
                                        </td>
                                        <td width="200" align="center">
                                            <input type="file" name="PackaginImage[]">
<?
                                                if ($sDefectPicture != "" && @file_exists($sPackagingDir.$sDefectPicture))
                                                {
?>
                                            <br/><span>(<a href="<?= $sPkPicsDir ?><?= $sDefectPicture ?>" class="lightview"><?= $sDefectPicture ?></a>)&nbsp;</span>
						  <input type="hidden" name="PrevPicture[]" value="<?= $sDefectPicture ?>">
<?
                                                } else {
?>
                                                  <input type="hidden" name="PrevPicture[]" value="">
<?
                                                }
?>
                                        </td>
                                        <td width="50" align="center"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" style="cursor:pointer;" onclick="DeletePackaginDefect(<?=$i+1?>,<?=$iTableId?>)" /></td>
			      </tr>
<?
                                }
                        }
                        else
                        {
?>
                                <tr style="line-height:0px;">
                                    <td width="50" align="center">&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td width="100" align="center">&nbsp;</td>
                                    <td width="200" align="center">&nbsp;</td>
                                    <td width="50" align="center">&nbsp;</td>
                                </tr>
<?
                        }
?>
			    </table>
                                </div>
                                <div class="qaButtons">
				  <input type="button" value="" class="btnAdd" title="Add Packaging Defect" onclick="addPackagingDefect( );" />
				</div>
<?
                                }
?>
                                <!-- Sample Size --->
<?
	$sColors = @explode(",", $Colors);
	$iColor  = 0;

	foreach ($sColors as $sColor)
	{
		foreach ($iSizes as $iSize)
		{
			$sSize         = getDbValue("size", "tbl_sizes", "id='$iSize'");
			$iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '$sSize'");


			$sSQL = "SELECT qrs.sample_no, qrss.point_id, qrss.findings
					 FROM tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss
					 WHERE qrs.audit_id='$Id' AND qrs.id=qrss.sample_id AND qrs.size_id='$iSamplingSize' AND qrs.color='$sColor'
					 ORDER BY qrs.sample_no, qrss.point_id";
			$objDb->query($sSQL);

			$iCount        = $objDb->getCount( );
			$sSizeFindings = array( );

			for($i = 0; $i < $iCount; $i ++)
			{
				$iSampleNo = $objDb->getField($i, 'sample_no');
				$iPoint    = $objDb->getField($i, 'point_id');
				$sFindings = $objDb->getField($i, 'findings');

				$sSizeFindings["{$iSampleNo}-{$iPoint}"] = $sFindings;
			}
?>
				<h2 style="margin:0px;">Measurement Sheet (Size: <?= $sSize ?>, Color: <?= $sColor ?>)</h2>

				<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
				  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
					<tr class="sdRowHeader">
					  <td width="40" align="center"><b>#</b></td>
					  <td><b>Measurement Point</b></td>
                                          <td width="50" align="center"><b>Nature</b></td>
					  <td width="90" align="center"><b>Specs</b></td>
					  <td width="90" align="center"><b>Tolerance</b></td>                                          
					  <td width="50" align="center"><b>1</b></td>
					  <td width="50" align="center"><b>2</b></td>
					  <td width="50" align="center"><b>3</b></td>
					  <td width="50" align="center"><b>4</b></td>
					  <td width="50" align="center"><b>5</b></td>
					</tr>
<?
			$sSQL = "SELECT point_id, specs, nature,
							(SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
							(SELECT point FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Point
					 FROM tbl_style_specs
					 WHERE style_id='$Style' AND size_id='$iSamplingSize' AND version='0' AND specs!='0' AND specs!=''
					 ORDER BY id";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for($i = 0; $i < $iCount; $i ++)
			{
				$iPoint     = $objDb->getField($i, 'point_id');
				$sSpecs     = $objDb->getField($i, 'specs');
                                $sNature    = $objDb->getField($i, 'nature');
				$sPoint     = $objDb->getField($i, '_Point');
				$sTolerance = $objDb->getField($i, '_Tolerance');
?>

                                <tr class="sdRowColor">
                                    <td align="center"><?= ($i + 1) ?></td>
                                    <td><?= $sPoint ?></td>
                                    <td align="center"><?= $sNature ?></td>
                                    <td align="center"><?= $sSpecs ?></td>
                                    <td align="center"><?= $sTolerance ?></td>                                    
<?
				for ($j = 1; $j <= 5; $j ++)
				{
?>
				  	<td align="center"><input type="text" name="Specs<?= $iSamplingSize ?>_<?= $iColor ?>_<?= $iPoint ?>_<?= $j ?>" value="<?= $sSizeFindings["{$j}-{$iPoint}"] ?>" size="5" maxlength="10" class="textbox" /></td>
<?
				}
?>
			    	</tr>
<?
			}
?>
				</table>
				</div>
<?
		}


		$iColor ++;
	}
?>
				<h2>Measurement Result</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="120">Result<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="MeasurementResult">
						<option value=""></option>
						<option value="P">Pass</option>
						<option value="F">Fail</option>
						<option value="H">Pending</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.MeasurementResult.value = "<?= $sMeasurementResult ?>";
					  -->
					  </script>
					</td>
				  </tr>

				  <tr valign="top">
					<td>Remarks</td>
					<td align="center">:</td>
					<td><textarea name="MeasurementComments" class="textarea" style="width:98%; height:80px;"><?= $sMeasurementResultRemarks ?></textarea></td>
				  </tr>
				</table>

				<br />

				<table border="1" bordercolor="#ffffff" cellpadding="0" cellspacing="0" width="100%">
				  <tr valign="top">
					<td width="50%">

					  <h2>Work-ManShip</h2>

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="140">Total GMTS Inspected<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="TotalGmts" value="<?= $TotalGmts ?>" size="10" class="textbox" /> (Pcs)</td>
					    </tr>

					    <tr>
						  <td># of GMTS Defective</td>
						  <td align="center">:</td>
						  <td><input type="text" name="GmtsDefective" value="<?= $GmtsDefective ?>" size="10" class="textbox" /> (Pcs)</td>
					    </tr>

					    <tr>
						  <td>Number of Defects</td>
						  <td align="center">:</td>
						  <td><?= (int)$iDefects ?></td>
					    </tr>

					    <tr>
						  <td>D.H.U</td>
						  <td align="center">:</td>
						  <td><?= @round((($iDefects / $TotalGmts) * 100), 2) ?>%</td>
					    </tr>
					  </table>

					</td>

					<td width="50%">

					  <h2>Quantities</h2>
<?
	$sSQL = "SELECT quantity FROM tbl_po WHERE id='$PO'";
	$objDb->query($sSQL);

	$iOrderQty = $objDb->getField(0, 0);

	if (count($AdditionalPos) > 0)
	{
		$sSQL = "SELECT SUM(quantity) FROM tbl_po WHERE id IN ($AdditionalPos)";
		$objDb->query($sSQL);

		$iOrderQty += $objDb->getField(0, 0);
	}
?>
					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
                                              <tr>
                                                    <td width="140">Order Qty</td>
                                                    <td width="20" align="center">:</td>
                                                    <td><?= $iOrderQty ?></td>
                                              </tr>

                                              <tr>
                                                    <td>Ship Qty</td>
                                                    <td align="center">:</td>
                                                    <td><input type="text" name="ShipQty" value="<?= $ShipQty ?>" size="10" class="textbox" /></td>
                                              </tr>

                                              <tr>
                                                    <td>Deviation</td>
                                                    <td align="center">:</td>
                                                    <td><?= @round((($ShipQty / $iOrderQty) * 100), 2) ?>%</td>
                                              </tr>
					  </table>

					</td>
				  </tr>
				</table>

				<br />
				<h2>Status & Comments</h2>

			<table border="0" cellpadding="3" cellspacing="0" width="100%">			

				  <tr valign="top">
					<td width="140">QA Comments</td>
					<td width="20" align="center">:</td>
					<td><textarea name="Comments" class="textarea" style="width:98%; height:80px;"><?= $Comments ?></textarea></td>
				  </tr>
				</table>
                                
<script>
    function CheckPackingPercent()
    {
        var packing = document.getElementById("packing").value;
        
        if(packing == 100)
        {  
            document.getElementById("cutting").value = 100;
            document.getElementById("sewing").value = 100;
            document.getElementById("finishing").value = 100;
        }
    }
    
    var i= parseInt(document.getElementById("CountRows").value)+parseInt(1);
    
    function addPackagingDefect()
    {
        var table = document.getElementById("PackaginDefectsTable");
        var rowCount = table.rows.length;
        var row = table.insertRow(rowCount);
        row.id = "RowNo"+i;
        var cell1  = row.insertCell(0);
        var cell2  = row.insertCell(1);
        var cell3  = row.insertCell(2);
        var cell4  = row.insertCell(3);
        var cell5  = row.insertCell(4);

        cell1.innerHTML = "<b>"+i+"</b><input type='hidden' name='PackagingDefectRows[]' value='0'>";
        cell1.style = 'text-align:center;';
        cell2.innerHTML = "<select style='text-align:center;' name='PDefect[]' required>"+document.getElementById("PDefect").innerHTML+"</select>";
        cell3.innerHTML = "<select style='text-align:center;' name='PDSamples[]' required>"+document.getElementById("PSamples").innerHTML+"</select>";
        cell3.style = 'text-align:center;';
        cell4.innerHTML = "<input style='text-align:center;' type='file' class='textbox' name='PackaginImage[]' value=''  style='width:95%;'/><input type='hidden' name='PrevPicture[]' value=''>";
        cell4.style = 'text-align:center;';
        cell5.innerHTML = "<img style='text-align:center;' src='images/icons/delete.gif' width='16' height='16' alt='Delete' title='Delete' style='cursor:pointer;' onclick='DeletePackaginDefect("+i+", 0)' />";
        cell5.style = 'text-align:center;';
        
        i++;
        document.getElementById("CountRows").value = i;
    }
    
    function DeletePackaginDefect(Num, Id) 
    {
        var result = confirm("Are you sure, you want to delete this record permanenetly?");
        
        if (result) 
        {
            var table = document.getElementById("PackaginDefectsTable");
            var rowCount = table.rows.length;

            var element = document.getElementById("RowNo"+Num);
            element.parentNode.removeChild(element);

            if(rowCount == (parseInt(Num)-parseInt(1)))
            {
               i--;
               document.getElementById("CountRows").value = i;        
            }
            
            if(Id > 0)
            {
                jQuery.post("ajax/quonda/delete-packaging-defect.php",
                    { DefectId:Id },

                    function (sResponse)
                    {
                            if (sResponse == 'SUCCESS')
                            {
                                    alert("Record Deleted Successfully!");
                            }
                    },

                "text");
            }
        }        
    }
</script>