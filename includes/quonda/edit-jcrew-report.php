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

	$sSQL = "SELECT * FROM tbl_arcadia_inspection_summary WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$QtyOfLots                   = $objDb->getField(0, "qty_of_lots");
		$QtyPerLot                   = $objDb->getField(0, "qty_per_lot");
		$InspectionStatus            = $objDb->getField(0, "inspection_status");
		$ShippingMarks               = $objDb->getField(0, "shipping_marks");
		$ShippingMarksRemarks        = $objDb->getField(0, "shipping_marks_remarks");
		$MaterialConformity          = $objDb->getField(0, "material_conformity");
		$MaterialConformityRemarks   = $objDb->getField(0, "material_conformity_remarks");
		$ProductStyle                = $objDb->getField(0, "style");
		$ProductStyleRemarks         = $objDb->getField(0, "style_remarks");
		$ProductColour               = $objDb->getField(0, "colour");
		$ProductColourRemarks        = $objDb->getField(0, "colour_remarks");
		$ExportCartonPacking         = $objDb->getField(0, "export_carton_packing");
		$ExportCartonPackingRemarks  = $objDb->getField(0, "export_carton_packing_remarks");
		$InnerCartonPacking          = $objDb->getField(0, "inner_carton_packing");
		$InnerCartonPackingRemarks   = $objDb->getField(0, "inner_carton_packing_remarks");
		$ProductPackaging            = $objDb->getField(0, "product_packaging");
		$ProductPackagingRemarks     = $objDb->getField(0, "product_packaging_remarks");
		$Assortment                  = $objDb->getField(0, "assortment");
		$AssortmentRemarks           = $objDb->getField(0, "assortment_remarks");
		$Labeling                    = $objDb->getField(0, "labeling");
		$LabelingRemarks             = $objDb->getField(0, "labeling_remarks");
		$Markings                    = $objDb->getField(0, "markings");
		$MarkingsRemarks             = $objDb->getField(0, "markings_remarks");
		$Workmanship                 = $objDb->getField(0, "workmanship");
		$WorkmanshipRemarks          = $objDb->getField(0, "workmanship_remarks");
		$Appearance                  = $objDb->getField(0, "appearance");
		$AppearanceRemarks           = $objDb->getField(0, "appearance_remarks");
		$Function                    = $objDb->getField(0, "function");
		$FunctionRemarks             = $objDb->getField(0, "function_remarks");
		$PrintedMaterials            = $objDb->getField(0, "printed_materials");
		$PrintedMaterialsRemarks     = $objDb->getField(0, "printed_materials_remarks");
		$WorkmanshipFinishing        = $objDb->getField(0, "finishing");
		$WorkmanshipFinishingRemarks = $objDb->getField(0, "finishing_remarks");
		$Measurement                 = $objDb->getField(0, "measurement");
		$MeasurementRemarks          = $objDb->getField(0, "measurement_remarks");
		$FabricWeight                = $objDb->getField(0, "fabric_weight");
		$FabricWeightRemarks         = $objDb->getField(0, "fabric_weight_remarks");
                $WovenWeight                 = $objDb->getField(0, "woven_weight");
                $WovenWeightRemarks          = $objDb->getField(0, "woven_weight_remarks");
		$CalibratedScales            = $objDb->getField(0, "calibrated_scales");
		$CalibratedScalesRemarks     = $objDb->getField(0, "calibrated_scales_remarks");
		$CordNorm                    = $objDb->getField(0, "cords_norm");
		$CordNormRemarks             = $objDb->getField(0, "cords_norm_remarks");
		$InspectionConditions        = $objDb->getField(0, "inspection_conditions");
		$InspectionConditionsRemarks = $objDb->getField(0, "inspection_conditions_remarks");
		$Remarks1                    = $objDb->getField(0, "remarks_1");
		$Remarks2                    = $objDb->getField(0, "remarks_2");
		$Remarks3                    = $objDb->getField(0, "remarks_3");
		$Remarks4                    = $objDb->getField(0, "remarks_4");
		$CartonNos                   = $objDb->getField(0, "carton_nos");
		$ShipmentQtyUnits            = $objDb->getField(0, "shipment_units");
		$ShipmentQtyCtns             = $objDb->getField(0, "shipment_ctns");
		$PresentedQty                = $objDb->getField(0, "presented_qty");
		$UnitsPackedQty              = $objDb->getField(0, "packed_qty");
		$UnitsPackedPercent          = $objDb->getField(0, "packed_percent");
		$UnitsFinishedQty            = $objDb->getField(0, "finished_qty");
		$UnitsFinishedPercent        = $objDb->getField(0, "finished_percent");
		$UnitsNotFinishedQty         = $objDb->getField(0, "not_finished_qty");
		$UnitsNotFinishedPercent     = $objDb->getField(0, "not_finished_percent");
		$MeasurementResult           = $objDb->getField(0, "measurement_result");
		$MeasurementComments         = $objDb->getField(0, "measurement_overall_remarks");
                $Fitting                     = $objDb->getField(0, "fitting");
                $FittingRemarks              = $objDb->getField(0, "fitting_remarks");
                $CartonSticker               = $objDb->getField(0, "carton_sticker");
                $CartonStickerRemarks        = $objDb->getField(0, "carton_sticker_remarks");
                
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
					<td><?= $Parent ?></td>
				  </tr>
                                  
                                  <tr>
					<td>Factory</td>
					<td align="center">:</td>
					<td><?= $sVendor ?></td>
				  </tr>

				  <tr>
					<td>Auditor</td>
					<td align="center">:</td>
					<td><?= $sAuditor ?></td>
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
                                  
                                  <tr valign="top">
					<td>PO<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="PO" id="PO" value="" class="textbox" size="30" maxlength="200" /></td>
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
					<td>Approved PP Sample</td>
					<td align="center">:</td>

					<td>
					  <select name="ApprovedSample">
						<option value=""></option>
						<option value="Yes">Yes</option>
						<option value="No">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.ApprovedSample.value = "<?= (($ApprovedSample == "Y") ? "Yes" : (($ApprovedSample == "N") ? "No" : $ApprovedSample)) ?>";
					  -->
					  </script>
					</td>
				  </tr>

				  <tr>
					<td>Approved Trim Card</td>
					<td align="center">:</td>

					<td>
					  <select name="ApprovedTrims">
						<option value=""></option>
						<option value="Y">Yes</option>
						<option value="N">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.ApprovedTrims.value = "<?= $ApprovedTrims ?>";
					  -->
					  </script>
					</td>
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
					<td>Shipping Marks</td>

					<td align="center">
					  <select name="ShippingMarks">
						<option value="">N/A</option>
						<option value="P"<?= (($ShippingMarks == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($ShippingMarks == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="ShippingMarksRemarks" value="<?= $ShippingMarksRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
					<td>Material Conformity</td>

					<td align="center">
					  <select name="MaterialConformity">
						<option value="">N/A</option>
						<option value="P"<?= (($MaterialConformity == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($MaterialConformity == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="MaterialConformityRemarks" value="<?= $MaterialConformityRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
				    <td colspan="3">Product Conformity</td>
				  </tr>

				  <tr>
					<td><span style="padding-left:80px;">Style</span></td>

					<td align="center">
					  <select name="ProductStyle">
						<option value="">N/A</option>
						<option value="P"<?= (($ProductStyle == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($ProductStyle == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="ProductStyleRemarks" value="<?= $ProductStyleRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
					<td><span style="padding-left:80px;">Colour</span></td>

					<td align="center">
					  <select name="ProductColour">
						<option value="">N/A</option>
						<option value="P"<?= (($ProductColour == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($ProductColour == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="ProductColourRemarks" value="<?= $ProductColourRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
				    <td colspan="3">Packing & Assortment</td>
				  </tr>

                       		<tr>
					<td><span style="padding-left:80px;">Carton Sticker</span></td>

					<td align="center">
					  <select name="CartonSticker">
						<option value="">N/A</option>
						<option value="P"<?= (($CartonSticker == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($CartonSticker == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="CartonStickerRemarks" value="<?= $CartonStickerRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>
				  <tr>
					<td><span style="padding-left:80px;">Export Carton Packing</span></td>

					<td align="center">
					  <select name="ExportCartonPacking">
						<option value="">N/A</option>
						<option value="P"<?= (($ExportCartonPacking == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($ExportCartonPacking == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="ExportCartonPackingRemarks" value="<?= $ExportCartonPackingRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
					<td><span style="padding-left:80px;">Inner Carton Packing</span></td>

					<td align="center">
					  <select name="InnerCartonPacking">
						<option value="">N/A</option>
						<option value="P"<?= (($InnerCartonPacking == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($InnerCartonPacking == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="InnerCartonPackingRemarks" value="<?= $InnerCartonPackingRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
					<td><span style="padding-left:80px;">Product Packaging</span></td>

					<td align="center">
					  <select name="ProductPackaging">
						<option value="">N/A</option>
						<option value="P"<?= (($ProductPackaging == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($ProductPackaging == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="ProductPackagingRemarks" value="<?= $ProductPackagingRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
					<td><span style="padding-left:80px;">Assortment (colour/style/size)</span></td>

					<td align="center">
					  <select name="Assortment">
						<option value="">N/A</option>
						<option value="P"<?= (($Assortment == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($Assortment == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="AssortmentRemarks" value="<?= $AssortmentRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
				    <td colspan="3">Labeling, Markings</td>
				  </tr>

				  <tr>
					<td><span style="padding-left:80px;">Labeling</span></td>

					<td align="center">
					  <select name="Labeling">
						<option value="">N/A</option>
						<option value="P"<?= (($Labeling == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($Labeling == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="LabelingRemarks" value="<?= $LabelingRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
					<td><span style="padding-left:80px;">Markings</span></td>

					<td align="center">
					  <select name="Markings">
						<option value="">N/A</option>
						<option value="P"<?= (($Markings == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($Markings == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="MarkingsRemarks" value="<?= $MarkingsRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
				    <td colspan="3">Workmanship</td>
				  </tr>

				  <tr>
					<td><span style="padding-left:80px;">Workmanship</span></td>

					<td align="center">
					  <select name="Workmanship">
						<option value="">N/A</option>
						<option value="P"<?= (($Workmanship == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($Workmanship == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="WorkmanshipRemarks" value="<?= $WorkmanshipRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
					<td><span style="padding-left:80px;">Appearance</span></td>

					<td align="center">
					  <select name="Appearance">
						<option value="">N/A</option>
						<option value="P"<?= (($Appearance == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($Appearance == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="AppearanceRemarks" value="<?= $AppearanceRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
					<td><span style="padding-left:80px;">Function</span></td>

					<td align="center">
					  <select name="Function">
						<option value="">N/A</option>
						<option value="P"<?= (($Function == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($Function == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="FunctionRemarks" value="<?= $FunctionRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
					<td><span style="padding-left:80px;">Printed Materials</span></td>

					<td align="center">
					  <select name="PrintedMaterials">
						<option value="">N/A</option>
						<option value="P"<?= (($PrintedMaterials == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($PrintedMaterials == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="PrintedMaterialsRemarks" value="<?= $PrintedMaterialsRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
					<td><span style="padding-left:80px;">Finishing</span></td>

					<td align="center">
					  <select name="WorkmanshipFinishing">
						<option value="">N/A</option>
						<option value="P"<?= (($WorkmanshipFinishing == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($WorkmanshipFinishing == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="WorkmanshipFinishingRemarks" value="<?= $WorkmanshipFinishingRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>
                                   <tr>
					<td><span style="padding-left:80px;">Fitting</span></td>

					<td align="center">
					  <select name="Fitting">
						<option value="">N/A</option>
						<option value="P"<?= (($Fitting == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($Fitting == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="FittingRemarks" value="<?= $FittingRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>
				  <tr>
					<td>Measurement</td>

					<td align="center">
					  <select name="Measurement">
						<option value="">N/A</option>
						<option value="P"<?= (($Measurement == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($Measurement == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="MeasurementRemarks" value="<?= $MeasurementRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
					<td>Knit Weight</td>

					<td align="center">
					  <select name="FabricWeight">
						<option value="">N/A</option>
						<option value="P"<?= (($FabricWeight == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($FabricWeight == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="FabricWeightRemarks" value="<?= $FabricWeightRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>
                                  
                                  <tr>
					<td>Woven Weight</td>

					<td align="center">
					  <select name="WovenWeight">
						<option value="">N/A</option>
						<option value="P"<?= (($WovenWeight == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($WovenWeight == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="WovenWeightRemarks" value="<?= $WovenWeightRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				</table>
				</div>

				<br />
				<h2>Remarks</h2>

				<div style="padding:2px;">
				<table border="0" cellpadding="5" cellspacing="0" width="100%">
				  <tr>
					<td width="20" align="center">1.</td>
				    <td><input type="text" name="Remarks1" value="<?= $Remarks1 ?>" class="textbox" style="width:98%;" /></td>
			      </tr>

				  <tr>
					<td align="center">2.</td>
				    <td><input type="text" name="Remarks2" value="<?= $Remarks2 ?>" class="textbox" style="width:98%;" /></td>
			      </tr>

				  <tr>
					<td align="center">3.</td>
				    <td><input type="text" name="Remarks3" value="<?= $Remarks3 ?>" class="textbox" style="width:98%;" /></td>
			      </tr>

				  <tr>
					<td align="center">4.</td>
				    <td><input type="text" name="Remarks4" value="<?= $Remarks4 ?>" class="textbox" style="width:98%;" /></td>
			      </tr>
				</table>
				</div>

				<h3>List of Export Carton Numbers Opened</h3>

				<div style=" padding:3px;">
				  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	$sCartonNos = @explode(",", $CartonNos);

	for ($i = 1, $iIndex = 0; $i <= 7; $i ++)
	{
?>
				    <tr bgcolor="#f6f6f6">
<?
		for ($j = 1; $j <= 10; $j ++)
		{
?>
					  <td width="10%" align="center"><input type="text" name="CartonNos[]" value="<?= $sCartonNos[$iIndex ++] ?>" size="9" maxlength="10" class="textbox" /></td>
<?
		}
?>
				    </tr>
<?
	}
?>
				  </table>
				</div>

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
                                                            <select id="Area<?= $i ?>" name="Area<?= $i ?>" class="defectArea" onchange="$('Sms').value='1';" style="width:200px;" required="">
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
	$sColors = @explode(",", $Colors);
	$iColor  = 0;
?>
                                <h2 style="margin:0px;">Measurement Specs</h2>
                                <table id="MeasurementSpecsTable" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                                <tr class="sdRowHeader">
                                      <td width="40" align="center"><b>#</b></td>
                                      <td width="200"><b>Color</b></td>
                                      <td width="150"><b>Size</b></td>
                                      <td width="120" align="center"><b>Sample No</b></td>
                                      <td width="94" align="center"><b>Options</b></td>
                                </tr>
<?
                              $sSQL = "SELECT tbl_qa_report_samples.*, 
                                        (SELECT size from tbl_sampling_sizes WHERE id=tbl_qa_report_samples.size_id) as _Size
                                        FROM tbl_qa_report_samples WHERE audit_id='$Id' ORDER BY color, size_id, sample_no";
                              $objDb->query($sSQL);

                              $iCount = $objDb->getCount( );

                              for ($i = 0; $i < $iCount; $i ++)
                              {
                                      $iSample   = $objDb->getField($i, "id");
                                      $iSize     = $objDb->getField($i, "size_id");
                                      $sColor    = $objDb->getField($i, "color");
                                      $sNature   = $objDb->getField($i, "nature");
                                      $sResult   = $objDb->getField($i, "result");
                                      $iSampleNo = $objDb->getField($i, "sample_no");
                                      $sSize     = $objDb->getField($i, "_Size");
?>
                                  <tr bgcolor="<?= ((($i % 2) == 0) ? "#f0f0f0" : "#e6e6e6") ?>">
                                        <td align="center"><?= ($i + 1) ?></td>
                                        <td><?= $sColor ?></td>
                                        <td><?= $sSize ?></td>
                                        <td align="center"><?= $iSampleNo ?></td>
                                        <td align="center">
                                        <a href="includes/quonda/edit-measurement-specs.php?QaSampleId=<?=$iSample?>&SizeId=<?=$iSize?>&Size=<?=$sSize?>&AuditId=<?=$Id?>&Color=<?=$sColor?>&Style=<?=$Style?>&SampleNo=<?=$iSampleNo?>" class="lightview" rel="iframe" title="Measurement Specs for Audit#: <?= $Id ?> :: :: width: 900, height: 650"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit Measurement Specs" title="Edit Measurement Specs" /></a>&nbsp;
<?
                                        if ($sUserRights['Delete'] == "Y")
                                        {
?>
                                                <img src="images/icons/delete.gif" onclick='DeleteMeasurementTableRow("<?=$Id?>", "<?=$iSample?>", "<?=$i+1?>")'  width="16" height="16" alt="Delete Measurement Specs" title="Delete Measurement Specs" style="cursor:pointer;" />&nbsp;
<?
                                        }
?>
                                        </td>                                       
                                  </tr>
<?
                              }
?>
                              </table>
<?
                            if($iCount < $TotalGmts)
                            {
?>
                                <div class="qaMeasurementButtons">
                                    <a href="includes/quonda/add-measurement-specs.php?Sizes=<?= $Sizes ?>&Colors=<?=$Colors?>&AuditId=<?=$Id?>&Style=<?=$Style?>" class="lightview" rel="iframe" title="Audit#: <?= $Id ?> :: :: width: 350, height: 260"><span class="btnAddMeasurement"></span></a>
				</div>
<?
                            }
?>
                                                                <br/>
                                
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
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.MeasurementResult.value = "<?= $MeasurementResult ?>";
					  -->
					  </script>
					</td>
				  </tr>

				  <tr valign="top">
					<td>Remarks</td>
					<td align="center">:</td>
					<td><textarea name="MeasurementComments" class="textarea" style="width:98%; height:80px;"><?= $MeasurementComments ?></textarea></td>
				  </tr>
				</table>

				<br />

				<table border="1" bordercolor="#ffffff" cellpadding="0" cellspacing="0" width="100%">
				  <tr valign="top">
					<td width="50%">

					  <h2>Workmanship</h2>

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
						  <td>Max Allowable Defects</td>
						  <td align="center">:</td>
						  <td><input type="text" name="MaxDefects" value="<?= $MaxDefects ?>" size="10" class="textbox" readonly /></td>
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
				  
                              <tr>
				    <td width="140">Packing</td>
				    <td width="20" align="center">:</td>
                                    <td><input type="text" name="Packing" id="packing" onchange="CheckPackingPercent();" value="<?= $Packing ?>" size="8" maxlength="10" class="textbox" /></td>
			      </tr>
                              
			      <tr>
				    <td>Cutting</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Cutting" id="cutting" value="<?= $Cutting ?>" size="8" maxlength="10" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Sewing</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Sewing" id="sewing" value="<?= $Sewing ?>" size="8" maxlength="10" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Finishing</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Finishing" id="finishing" value="<?= $Finishing ?>" size="8" maxlength="10" class="textbox" /></td>
			      </tr>

				  <tr valign="top">
					<td>QA Comments</td>
					<td align="center">:</td>
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
</script>