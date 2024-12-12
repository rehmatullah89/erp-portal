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
					<td>Audit Status</td>
					<td align="center">:</td>

					<td>
					  <select name="AuditStatus">
						<option value=""></option>
						<option value="1st">1st</option>
						<option value="2nd">2nd</option>
						<option value="3rd">3rd</option>
						<option value="4th">4th</option>
						<option value="5th">5th</option>
						<option value="6th">6th</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.AuditStatus.value = "<?= $AuditStatus ?>";
					  -->
					  </script>
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
					<td>Approved Sample</td>
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

				  <tr>
					<td>Qty of lots</td>
					<td align="center">:</td>
					<td><input type="text" name="QtyOfLots" value="<?= $QtyOfLots ?>" size="10" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Qty per lot</td>
					<td align="center">:</td>
					<td><input type="text" name="QtyPerLot" value="<?= $QtyPerLot ?>" size="10" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Inspection Status</td>
					<td align="center">:</td>
					<td><input type="text" name="InspectionStatus" value="<?= $InspectionStatus ?>" size="20" maxlength="100" class="textbox" /></td>
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
					<td>Shipping Marks</td>

					<td align="center">
					  <select name="ShippingMarks">
						<option value=""></option>
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
						<option value=""></option>
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
						<option value=""></option>
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
						<option value=""></option>
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
					<td><span style="padding-left:80px;">Export Carton Packing</span></td>

					<td align="center">
					  <select name="ExportCartonPacking">
						<option value=""></option>
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
						<option value=""></option>
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
						<option value=""></option>
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
						<option value=""></option>
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
						<option value=""></option>
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
						<option value=""></option>
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
						<option value=""></option>
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
						<option value=""></option>
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
						<option value=""></option>
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
						<option value=""></option>
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
						<option value=""></option>
						<option value="P"<?= (($WorkmanshipFinishing == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($WorkmanshipFinishing == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="WorkmanshipFinishingRemarks" value="<?= $WorkmanshipFinishingRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
					<td>Measurement</td>

					<td align="center">
					  <select name="Measurement">
						<option value=""></option>
						<option value="P"<?= (($Measurement == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($Measurement == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="MeasurementRemarks" value="<?= $MeasurementRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
					<td>Fabric Weight</td>

					<td align="center">
					  <select name="FabricWeight">
						<option value=""></option>
						<option value="P"<?= (($FabricWeight == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($FabricWeight == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="FabricWeightRemarks" value="<?= $FabricWeightRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
					<td>Calibrated Scales</td>

					<td align="center">
					  <select name="CalibratedScales">
						<option value=""></option>
						<option value="Y"<?= (($CalibratedScales == "Y") ? " selected" : "") ?>>Yes</option>
						<option value="N"<?= (($CalibratedScales == "N") ? " selected" : "") ?>>No</option>
					  </select>
					</td>

					<td></td>
				  </tr>

				  <tr>
					<td>Cords norm / Others</td>

					<td align="center">
					  <select name="CordNorm">
						<option value=""></option>
						<option value="P"<?= (($CordNorm == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($CordNorm == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="CordNormRemarks" value="<?= $CordNormRemarks ?>" class="textbox" style="width:99%;" /></td>
				  </tr>

				  <tr>
					<td>Inspection Conditions</td>

					<td align="center">
					  <select name="InspectionConditions">
						<option value=""></option>
						<option value="P"<?= (($InspectionConditions == "P") ? " selected" : "") ?>>Pass</option>
						<option value="F"<?= (($InspectionConditions == "F") ? " selected" : "") ?>>Fail</option>
					  </select>
					</td>

					<td><input type="text" name="InspectionConditionsRemarks" value="<?= $InspectionConditionsRemarks ?>" class="textbox" style="width:99%;" /></td>
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

				<br />
				<h2 style="margin:0px;">Quantity</h2>

				<div style=" padding:3px;">
				  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr bgcolor="#e6e6e6">
					  <td width="20%" colspan="2" align="center"><b>Shipment Quantity</b></td>
					  <td width="20%" rowspan="2" align="center"><b>Presented Quantity for Inspection</b></td>
					  <td width="20%" align="center"><b>Unites packed in cartons</b></td>
					  <td width="20%" align="center"><b>Units Finished not packed</b></td>
					  <td width="20%" align="center"><b>Units not finished</b></td>
				    </tr>

				    <tr bgcolor="#eeeeee">
					  <td width="10%" align="center">Units</td>
					  <td width="10%" align="center">Ctns</td>
					  <td width="20%" align="center">Qty</td>
					  <td width="20%" align="center">Qty</td>
					  <td width="20%" align="center">Qty</td>
				    </tr>

				    <tr bgcolor="#f6f6f6">
					  <td align="center"><input type="text" name="ShipmentQtyUnits" value="<?= $ShipmentQtyUnits ?>" size="6" maxlength="6" class="textbox" /></td>
					  <td align="center"><input type="text" name="ShipmentQtyCtns" value="<?= $ShipmentQtyCtns ?>" size="6" maxlength="6" class="textbox" /></td>
					  <td align="center"><input type="text" name="PresentedQty" value="<?= $PresentedQty ?>" size="6" maxlength="6" class="textbox" /></td>
					  <td align="center"><input type="text" name="UnitsPackedQty" value="<?= $UnitsPackedQty ?>" size="6" maxlength="6" class="textbox" /></td>
					  <td align="center"><input type="text" name="UnitsFinishedQty" value="<?= $UnitsFinishedQty ?>" size="6" maxlength="6" class="textbox" /></td>
					  <td align="center"><input type="text" name="UnitsNotFinishedQty" value="<?= $UnitsNotFinishedQty ?>" size="6" maxlength="6" class="textbox" /></td>
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

				<h3>Qty of collected samples per size / col</h3>

				<div style=" padding:3px;">
				  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr bgcolor="#f6f6f6">
					  <td width="15%"><b>Size / col.</b></td>
<?
	$sSQL = "SELECT size_color, size_qty, sample_qty FROM tbl_arcadia_samples_per_size WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	for ($i = 1; $i <= 10; $i ++)
	{
		$sSizeColor = $objDb->getField(($i - 1), "size_color");
?>
					  <td width="8.5%" align="center"><input type="text" name="SizeColor<?= $i ?>" value="<?= $sSizeColor ?>" size="7" maxlength="50" class="textbox" /></td>
<?
	}
?>
				    </tr>

				    <tr bgcolor="#f6f6f6">
					  <td><b>Size Qty.</b></td>
<?
	for ($i = 1; $i <= 10; $i ++)
	{
		$iSizeQty = $objDb->getField(($i - 1), "size_qty");
?>
					  <td align="center"><input type="text" name="SizeQty<?= $i ?>" value="<?= $iSizeQty ?>" size="7" maxlength="10" class="textbox" /></td>
<?
	}
?>
				    </tr>

				    <tr bgcolor="#f6f6f6">
					  <td><b>Sample Qty.</b></td>
<?
	for ($i = 1; $i <= 10; $i ++)
	{
		$iSampleQty = $objDb->getField(($i - 1), "sample_qty");
?>
					  <td align="center"><input type="text" name="SampleQty<?= $i ?>" value="<?= $iSampleQty ?>" size="7" maxlength="10" class="textbox" /></td>
<?
	}
?>
				    </tr>
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
					<td width="80" align="center"><b>Defects</b></td>
					<td width="80" align="center"><b>Sample #</b></td>
					<td width="170" align="center"><b>Area</b></td>
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

                                                <td width="80" align="center"><input type="text" id="Defects<?= $i ?>" name="Defects<?= $i ?>" value="<?= $objDb->getField($i, 'defects') ?>" maxlength="3" size="3" class="textbox defectsCount" onchange="$('Sms').value='1';" required=""/></td>
                                                <td width="80" align="center"><input type="text" id="SampleNo<?= $i ?>" name="SampleNo<?= $i ?>" value="<?= $objDb->getField($i, 'sample_no') ?>" maxlength="3" size="3" class="textbox sampleNos" onblur="getMaxAllowed(<?=$i?>,<?=$TotalGmts?>);" onchange="$('Sms').value='1';" /></td>
                                                
						<td width="170" align="center">
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
					  <td width="90" align="center"><b>Specs</b></td>
					  <td width="90" align="center"><b>Tolerance</b></td>
					  <td width="60" align="center"><b>1</b></td>
					  <td width="60" align="center"><b>2</b></td>
					  <td width="60" align="center"><b>3</b></td>
					  <td width="60" align="center"><b>4</b></td>
					  <td width="60" align="center"><b>5</b></td>
					</tr>
<?
			$sSQL = "SELECT point_id, specs,
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
				$sPoint     = $objDb->getField($i, '_Point');
				$sTolerance = $objDb->getField($i, '_Tolerance');
?>

					<tr class="sdRowColor">
					  <td align="center"><?= ($i + 1) ?></td>
					  <td><?= $sPoint ?></td>
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

					  <h2>Assortment</h2>

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="140">Total Cartons Inspected</td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="TotalCartons" value="<?= $TotalCartons ?>" size="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td># of Cartons Rejected</td>
						  <td align="center">:</td>
						  <td><input type="text" name="CartonsRejected" value="<?= $CartonsRejected ?>" size="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>% Defective</td>
						  <td align="center">:</td>
						  <td><input type="text" name="PercentDecfective" value="<?= $PercentDecfective ?>" size="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Acceptable Standard</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Standard" value="<?= $Standard ?>" size="10" class="textbox" /> %</td>
					    </tr>

					    <tr>
						  <td>D.H.U</td>
						  <td align="center">:</td>
						  <td><?= @round((($CartonsRejected / $TotalCartons) * 100), 2) ?>%</td>
					    </tr>
					  </table>

					</td>
				  </tr>
				</table>

				<br />
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
				    <td width="140">Total Cartons Required</td>
				    <td width="20" align="center">:</td>
				    <td><input type="text" name="CartonsRequired" value="<?= $CartonsRequired ?>" size="10" class="textbox" /></td>
 			      </tr>

			      <tr>
				    <td>Ship Qty</td>
				    <td align="center">:</td>
				    <td><input type="text" name="ShipQty" value="<?= $ShipQty ?>" size="10" class="textbox" /></td>
				    <td>Total Cartons Shipped</td>
				    <td align="center">:</td>
				    <td><input type="text" name="CartonsShipped" value="<?= $CartonsShipped ?>" size="10" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Re-Screen Qty</td>
				    <td align="center">:</td>
				    <td><input type="text" name="ReScreenQty" value="<?= $ReScreenQty ?>" size="10" class="textbox" /></td>
				    <td>Deviation</td>
				    <td align="center">:</td>
				    <td><?= @round((($CartonsShipped / $CartonsRequired) * 100), 2) ?>%</td>
			      </tr>

			      <tr>
				    <td>Deviation</td>
				    <td align="center">:</td>
				    <td colspan="4"><?= @round((($ShipQty / $iOrderQty) * 100), 2) ?>%</td>
			      </tr>
				</table>

				<br />
				<h2>Status & Comments</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="140">Carton Size</td>
					<td width="20" align="center">:</td>

					<td>
					  <input type="text" name="Length" value="<?= $Length ?>" size="3" maxlength="5" class="textbox" />
					  x
					  <input type="text" name="Width" value="<?= $Width ?>" size="3" maxlength="5" class="textbox" />
					  x
					  <input type="text" name="Height" value="<?= $Height ?>" size="3" maxlength="5" class="textbox" />
					  &nbsp;
					  <select name="Unit">
						<option value="in">Inches</option>
						<option value="cm">Centimeters</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.Unit.value = "<?= $Unit ?>";
					  -->
					  </script>
					</td>
				  </tr>

			      <tr>
				    <td>Knitted (%)</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Knitted" value="<?= $Knitted ?>" size="8" maxlength="10" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Dyed (%)</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Dyed" value="<?= $Dyed ?>" size="8" maxlength="10" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Cutting</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Cutting" value="<?= $Cutting ?>" size="8" maxlength="10" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Sewing</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Sewing" value="<?= $Sewing ?>" size="8" maxlength="10" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Finishing</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Finishing" value="<?= $Finishing ?>" size="8" maxlength="10" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Packing</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Packing" value="<?= $Packing ?>" size="8" maxlength="10" class="textbox" /></td>
			      </tr>

				  <tr>
				    <td>Final Audit Date</td>
				    <td align="center">:</td>

				    <td>

					  <table border="0" cellpadding="0" cellspacing="0" width="116">
					    <tr>
					  	  <td width="82"><input type="text" name="FinalAuditDate" id="FinalAuditDate" value="<?= (($FinalAuditDate != "0000-00-00") ? $FinalAuditDate : "") ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FinalAuditDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FinalAuditDate'), 'yyyy-mm-dd', this);" /></td>
					    </tr>
					  </table>

				    </td>
				  </tr>

				  <tr valign="top">
					<td>QA Comments</td>
					<td align="center">:</td>
					<td><textarea name="Comments" class="textarea" style="width:98%; height:80px;"><?= $Comments ?></textarea></td>
				  </tr>
				</table>
