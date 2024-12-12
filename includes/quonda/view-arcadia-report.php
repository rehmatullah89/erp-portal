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
		$iQtyOfLots                   = $objDb->getField(0, "qty_of_lots");
		$iQtyPerLot                   = $objDb->getField(0, "qty_per_lot");
		$sInspectionStatus            = $objDb->getField(0, "inspection_status");
		$sShippingMarks               = $objDb->getField(0, "shipping_marks");
		$sShippingMarksRemarks        = $objDb->getField(0, "shipping_marks_remarks");
		$sMaterialConformity          = $objDb->getField(0, "material_conformity");
		$sMaterialConformityRemarks   = $objDb->getField(0, "material_conformity_remarks");
		$sProductStyle                = $objDb->getField(0, "style");
		$sProductStyleRemarks         = $objDb->getField(0, "style_remarks");
		$sProductColour               = $objDb->getField(0, "colour");
		$sProductColourRemarks        = $objDb->getField(0, "colour_remarks");
		$sExportCartonPacking         = $objDb->getField(0, "export_carton_packing");
		$sExportCartonPackingRemarks  = $objDb->getField(0, "export_carton_packing_remarks");
		$sInnerCartonPacking          = $objDb->getField(0, "inner_carton_packing");
		$sInnerCartonPackingRemarks   = $objDb->getField(0, "inner_carton_packing_remarks");
		$sProductPackaging            = $objDb->getField(0, "product_packaging");
		$sProductPackagingRemarks     = $objDb->getField(0, "product_packaging_remarks");
		$sAssortment                  = $objDb->getField(0, "assortment");
		$sAssortmentRemarks           = $objDb->getField(0, "assortment_remarks");
		$sLabeling                    = $objDb->getField(0, "labeling");
		$sLabelingRemarks             = $objDb->getField(0, "labeling_remarks");
		$sMarkings                    = $objDb->getField(0, "markings");
		$sMarkingsRemarks             = $objDb->getField(0, "markings_remarks");
		$sWorkmanship                 = $objDb->getField(0, "workmanship");
		$sWorkmanshipRemarks          = $objDb->getField(0, "workmanship_remarks");
		$sAppearance                  = $objDb->getField(0, "appearance");
		$sAppearanceRemarks           = $objDb->getField(0, "appearance_remarks");
		$sFunction                    = $objDb->getField(0, "function");
		$sFunctionRemarks             = $objDb->getField(0, "function_remarks");
		$sPrintedMaterials            = $objDb->getField(0, "printed_materials");
		$sPrintedMaterialsRemarks     = $objDb->getField(0, "printed_materials_remarks");
		$sWorkmanshipFinishing        = $objDb->getField(0, "finishing");
		$sWorkmanshipFinishingRemarks = $objDb->getField(0, "finishing_remarks");
		$sMeasurement                 = $objDb->getField(0, "measurement");
		$sMeasurementRemarks          = $objDb->getField(0, "measurement_remarks");
		$sFabricWeight                = $objDb->getField(0, "fabric_weight");
		$sFabricWeightRemarks         = $objDb->getField(0, "fabric_weight_remarks");
		$sCalibratedScales            = $objDb->getField(0, "calibrated_scales");
		$sCalibratedScalesRemarks     = $objDb->getField(0, "calibrated_scales_remarks");
		$sCordNorm                    = $objDb->getField(0, "cords_norm");
		$sCordNormRemarks             = $objDb->getField(0, "cords_norm_remarks");
		$sInspectionConditions        = $objDb->getField(0, "inspection_conditions");
		$sInspectionConditionsRemarks = $objDb->getField(0, "inspection_conditions_remarks");
		$sRemarks1                    = $objDb->getField(0, "remarks_1");
		$sRemarks2                    = $objDb->getField(0, "remarks_2");
		$sRemarks3                    = $objDb->getField(0, "remarks_3");
		$sRemarks4                    = $objDb->getField(0, "remarks_4");

		$sCartonNos                   = $objDb->getField(0, "carton_nos");
		$iShipmentQtyUnits            = $objDb->getField(0, "shipment_units");
		$iShipmentQtyCtns             = $objDb->getField(0, "shipment_ctns");
		$iPresentedQty                = $objDb->getField(0, "presented_qty");
		$iUnitsPackedQty              = $objDb->getField(0, "packed_qty");
		$fUnitsPackedPercent          = $objDb->getField(0, "packed_percent");
		$iUnitsFinishedQty            = $objDb->getField(0, "finished_qty");
		$fUnitsFinishedPercent        = $objDb->getField(0, "finished_percent");
		$iUnitsNotFinishedQty         = $objDb->getField(0, "not_finished_qty");
		$fUnitsNotFinishedPercent     = $objDb->getField(0, "not_finished_percent");
		$sMeasurementResult           = $objDb->getField(0, "measurement_result");
		$sMeasurementComments         = $objDb->getField(0, "measurement_overall_remarks");
	}
?>
		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
			    <td width="120">Vendor</td>
			    <td width="20" align="center">:</td>
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
			    <td><?= $sGroup ?></td>
			  </tr>

			  <tr>
				<td>Style</td>
				<td align="center">:</td>
				<td><?= getDbValue("style", "tbl_styles", "id='$iStyle'") ?></td>
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

			  <tr>
			    <td>Audit Stage</td>
			    <td align="center">:</td>
			    <td><?= $sAuditStagesList[$sAuditStage] ?></td>
			  </tr>

<?
	switch ($sAuditStatus)
	{
		case "1st" : $sAuditStatus = "1st"; break;
		case "2nd" : $sAuditStatus = "2nd"; break;
		case "3rd" : $sAuditStatus = "3rd"; break;
		case "4th" : $sAuditStatus = "4th"; break;
		case "5th" : $sAuditStatus = "5th"; break;
		case "6th" : $sAuditStatus = "6th"; break;
	}
?>
			  <tr>
			    <td>Audit Status</td>
			    <td align="center">:</td>
			    <td><?= $sAuditStatus ?></td>
			  </tr>

<?
	switch ($sAuditType)
	{
		case "B"  : $sAuditType  = "Bulk"; break;
		case "BG" : $sAuditType = "B-Grade"; break;
		case "SS" : $sAuditType = "Sales Sample"; break;
	}
?>
			  <tr>
			    <td>QA Type</td>
			    <td align="center">:</td>
			    <td><?= $sAuditType ?></td>
			  </tr>

			  <tr>
			   <td>Colors</td>
			    <td align="center">:</td>
			    <td><?= $sColors ?></td>
			  </tr>

			  <tr>
				<td>Approved Sample</td>
				<td align="center">:</td>
				<td><?= $sApprovedSample ?></td>
			  </tr>

			  <tr>
				<td>Approved Trim Card</td>
				<td align="center">:</td>
				<td><?= (($sApprovedTrims == "Y") ? "Yes" : "No") ?></td>
			  </tr>

			  <tr>
				<td>Qty of lots</td>
				<td align="center">:</td>
				<td><?= $iQtyOfLots ?></td>
			  </tr>

			  <tr>
				<td>Qty per lot</td>
				<td align="center">:</td>
				<td><?= $iQtyPerLot ?></td>
			  </tr>

			  <tr>
				<td>Inspection Status</td>
				<td align="center">:</td>
				<td><?= $sInspectionStatus ?></td>
			  </tr>

<?
	$sSizeTitles = "";

	$sSQL = "SELECT size FROM tbl_sizes WHERE id IN ($sSizes) ORDER BY position";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sSizeTitles .= (", ".$objDb->getField($i, 0));

		$sSizeTitles = substr($sSizeTitles, 2);
	}
?>
			  <tr>
			   <td><?= (($iReportId != 8) ? 'Sizes' : 'Range') ?></td>
			    <td align="center">:</td>
			    <td><?= $sSizeTitles ?></td>
			  </tr>
		    </table>
<?
	switch ($sAuditResult)
	{
		case "P" : $sAuditResult = "Pass"; break;
		case "F" : $sAuditResult = "Fail"; break;
		case "H" : $sAuditResult = "Hold"; break;
	}
?>
			<br />
			<h2>Overall Result Summary</h2>

			<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
				<td width="120">Audit Result</td>
				<td width="20" align="center">:</td>
				<td><?= $sAuditResult ?></td>
			  </tr>
			</table>

			<div style="padding:10px;">
			<table border="1" bordercolor="#dddddd" cellpadding="4" cellspacing="0" width="100%">
			  <tr bgcolor="#eeeeee">
				<td></td>
				<td width="100" align="center">Pass / Fail</td>
				<td width="380" align="center">Remarks</td>
			  </tr>

			  <tr>
				<td>Shipping Marks</td>
				<td align="center"><?= (($sShippingMarks == "P") ? "Pass" : (($sShippingMarks == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sShippingMarksRemarks ?></td>
			  </tr>

			  <tr>
				<td>Material Conformity</td>
				<td align="center"><?= (($sMaterialConformity == "P") ? "Pass" : (($sMaterialConformity == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sMaterialConformityRemarks ?></td>
			  </tr>

			  <tr>
				<td colspan="3">Product Conformity</td>
			  </tr>

			  <tr>
				<td><span style="padding-left:80px;">Style</span></td>
				<td align="center"><?= (($sProductStyle == "P") ? "Pass" : (($sProductStyle == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sProductStyleRemarks ?></td>
			  </tr>

			  <tr>
				<td><span style="padding-left:80px;">Colour</span></td>
				<td align="center"><?= (($sProductColour == "P") ? "Pass" : (($sProductColour == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sProductColourRemarks ?></td>
			  </tr>

			  <tr>
				<td colspan="3">Packing & Assortment</td>
			  </tr>

			  <tr>
				<td><span style="padding-left:80px;">Export Carton Packing</span></td>
				<td align="center"><?= (($sExportCartonPacking == "P") ? "Pass" : (($sExportCartonPacking == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sExportCartonPackingRemarks ?></td>
			  </tr>

			  <tr>
				<td><span style="padding-left:80px;">Inner Carton Packing</span></td>
				<td align="center"><?= (($sInnerCartonPacking == "P") ? "Pass" : (($sInnerCartonPacking == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sInnerCartonPackingRemarks ?></td>
			  </tr>

			  <tr>
				<td><span style="padding-left:80px;">Product Packaging</span></td>
				<td align="center"><?= (($sProductPackaging == "P") ? "Pass" : (($sProductPackaging == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sProductPackagingRemarks ?></td>
			  </tr>

			  <tr>
				<td><span style="padding-left:80px;">Assortment (colour/style/size)</span></td>
				<td align="center"><?= (($sAssortment == "P") ? "Pass" : (($sAssortment == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sAssortmentRemarks ?></td>
			  </tr>

			  <tr>
				<td colspan="3">Labeling, Markings</td>
			  </tr>

			  <tr>
				<td><span style="padding-left:80px;">Labeling</span></td>
				<td align="center"><?= (($sLabeling == "P") ? "Pass" : (($sLabeling == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sLabelingRemarks ?></td>
			  </tr>

			  <tr>
				<td><span style="padding-left:80px;">Markings</span></td>
				<td align="center"><?= (($sMarkings == "P") ? "Pass" : (($sMarkings == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sMarkingsRemarks ?></td>
			  </tr>

			  <tr>
				<td colspan="3">Workmanship</td>
			  </tr>

			  <tr>
				<td><span style="padding-left:80px;">Workmanship</span></td>
				<td align="center"><?= (($sWorkmanship == "P") ? "Pass" : (($sWorkmanship == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sWorkmanshipRemarks ?></td>
			  </tr>

			  <tr>
				<td><span style="padding-left:80px;">Appearance</span></td>
				<td align="center"><?= (($sAppearance == "P") ? "Pass" : (($sAppearance == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sAppearanceRemarks ?></td>
			  </tr>

			  <tr>
				<td><span style="padding-left:80px;">Function</span></td>
				<td align="center"><?= (($sFunction == "P") ? "Pass" : (($sFunction == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sFunctionRemarks ?></td>
			  </tr>

			  <tr>
				<td><span style="padding-left:80px;">Printed Materials</span></td>
				<td align="center"><?= (($sPrintedMaterials == "P") ? "Pass" : (($sPrintedMaterials == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sPrintedMaterialsRemarks ?></td>
			  </tr>

			  <tr>
				<td><span style="padding-left:80px;">Finishing</span></td>
				<td align="center"><?= (($sWorkmanshipFinishing == "P") ? "Pass" : (($sWorkmanshipFinishing == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sWorkmanshipFinishingRemarks ?></td>
			  </tr>

			  <tr>
				<td>Measurement</td>
				<td align="center"><?= (($sMeasurement == "P") ? "Pass" : (($sMeasurement == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sMeasurementRemarks ?></td>
			  </tr>

			  <tr>
				<td>Fabric Weight</td>
				<td align="center"><?= (($sFabricWeight == "P") ? "Pass" : (($sFabricWeight == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sFabricWeightRemarks ?></td>
			  </tr>

			  <tr>
				<td>Calibrated Scales</td>
				<td align="center"><?= (($sCalibratedScales == "Y") ? "Yes" : (($sCalibratedScales == "N") ? "No" : "")) ?></td>
				<td></td>
			  </tr>

			  <tr>
				<td>Cords norm / Others</td>
				<td align="center"><?= (($sCordNorm == "P") ? "Pass" : (($sCordNorm == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sCordNormRemarks ?></td>
			  </tr>

			  <tr>
				<td>Inspection Conditions</td>
				<td align="center"><?= (($sInspectionConditions == "P") ? "Pass" : (($sInspectionConditions == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sInspectionConditionsRemarks ?></td>
			  </tr>
			</table>
			</div>

			<br />
			<h2>Remarks</h2>

			<div style="padding:5px;">
			<table border="0" cellpadding="5" cellspacing="0" width="100%">
			  <tr>
				<td width="20" align="center">1.</td>
				<td><?= $sRemarks1 ?></td>
			  </tr>

			  <tr>
				<td align="center">2.</td>
				<td><?= $sRemarks2 ?></td>
			  </tr>

			  <tr>
				<td align="center">3.</td>
				<td><?= $sRemarks3 ?></td>
			  </tr>

			  <tr>
				<td align="center">4.</td>
				<td><?= $sRemarks4 ?></td>
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
				  <td width="20%" colspan="2" align="center"><b>Unites packed in cartons</b></td>
				  <td width="20%" colspan="2" align="center"><b>Units Finished not packed</b></td>
				  <td width="20%" colspan="2" align="center"><b>Units not finished</b></td>
				</tr>

				<tr bgcolor="#eeeeee">
				  <td width="10%" align="center">Units</td>
				  <td width="10%" align="center">Ctns</td>
				  <td width="10%" align="center">Qty</td>
				  <td width="10%" align="center">%</td>
				  <td width="10%" align="center">Qty</td>
				  <td width="10%" align="center">%</td>
				  <td width="10%" align="center">Qty</td>
				  <td width="10%" align="center">%</td>
				</tr>

				<tr bgcolor="#f6f6f6">
				  <td align="center"><?= $iShipmentQtyUnits ?></td>
				  <td align="center"><?= $iShipmentQtyCtns ?></td>
				  <td align="center">&nbsp;<?= $iPresentedQty ?></td>
				  <td align="center"><?= $iUnitsPackedQty ?></td>
				  <td align="center"><?= $fUnitsPackedPercent ?></td>
				  <td align="center"><?= $iUnitsFinishedQty ?></td>
				  <td align="center"><?= $fUnitsFinishedPercent ?></td>
				  <td align="center"><?= $iUnitsNotFinishedQty ?></td>
				  <td align="center"><?= $fUnitsNotFinishedPercent ?></td>
				</tr>
			  </table>
			</div>

			<h3>List of Export Carton Numbers Opened</h3>

			<div style=" padding:3px;">
			  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	$sCartonNos = @explode(",", $sCartonNos);

	for ($i = 1, $iIndex = 0; $i <= 7; $i ++)
	{
?>
				<tr bgcolor="#f6f6f6">
<?
		for ($j = 1; $j <= 10; $j ++)
		{
?>
				  <td width="10%" align="center">&nbsp;<?= $sCartonNos[$iIndex ++] ?></td>
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
				  <td width="8.5%" align="center"><?= $sSizeColor ?></td>
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
				  <td align="center"><?= $iSizeQty ?></td>
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
				  <td align="center"><?= $iSampleQty ?></td>
<?
	}
?>
				</tr>
			  </table>
			</div>

		    <br />
		    <h2 style="margin:0px;">Defects Details</h2>

		    <div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
			  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			    <tr class="sdRowHeader">
				  <td width="50" align="center"><b>#</b></td>
				  <td><b>Code - Check Points</b></td>
				  <td width="70" align="center"><b>Defects</b></td>
				  <td width="70" align="center"><b>Sample #</b></td>
				  <td width="180"><b>Area</b></td>
				  <td width="100" align="center"><b>Nature</b></td>
			    </tr>

<?
	$iDefects = 0;

	$sSQL = "SELECT * FROM tbl_qa_report_defects WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for($i = 0; $i < $iCount; $i ++)
	{
		if ($objDb->getField($i, 'nature') > 0)
			$iDefects += $objDb->getField($i, 'defects');

		$sSQL = ("SELECT code, defect FROM tbl_defect_codes WHERE id='".$objDb->getField($i, 'code_id')."'");
		$objDb2->query($sSQL);

		$sSQL = ("SELECT area FROM tbl_defect_areas WHERE id='".$objDb->getField($i, 'area_id')."'");
		$objDb3->query($sSQL);

		switch ($objDb->getField($i, "nature"))
		{
			case 1 : $sNature = "Major"; break;
			case 0 : $sNature = "Minor"; break;
			case 2 : $sNature = "Critical"; break;
		}
?>

			    <tr class="sdRowColor">
				  <td align="center"><?= ($i + 1) ?></td>
				  <td><?= $objDb2->getField(0, 0) ?> - <?= $objDb2->getField(0, 1) ?></td>
				  <td align="center"><?= $objDb->getField($i, 'defects') ?></td>
                                  <td align="center"><?= $objDb->getField($i, 'sample_no') ?></td>
				  <td><?= $objDb3->getField(0, 0) ?></td>
				  <td align="center"><?= $sNature ?></td>
			    </tr>
<?
	}

	if ($iCount == 0)
	{
?>

			    <tr class="sdRowColor">
				  <td colspan="6" align="center">No Defect Found!</td>
			    </tr>
<?
	}
?>
			  </table>
			</div>

<?
	$sColors = @explode(",", $sColors);
	$iSizes  = @explode(",", $sSizes);

	if ($sSizes != "" && $sColors != "")
	{
?>
			<br />
<?
		foreach ($sColors as $sColor)
		{
			foreach ($iSizes as $iSize)
			{
				$sSize         = getDbValue("size", "tbl_sizes", "id='$iSize'");
				$iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '$sSize'");


				$sSQL = "SELECT qrs.sample_no, qrss.point_id, qrss.findings
						 FROM tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss
						 WHERE qrs.audit_id='$Id' AND qrs.id=qrss.sample_id AND qrs.size_id='$iSamplingSize' AND (qrs.color='$sColor' OR qrs.color='')
						 ORDER BY qrs.sample_no, qrss.point_id";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				if ($iCount == 0)
					continue;


				$sSizeFindings = array( );

				for($i = 0; $i < $iCount; $i ++)
				{
					$iSampleNo = $objDb->getField($i, 'sample_no');
					$iPoint    = $objDb->getField($i, 'point_id');
					$sFindings = $objDb->getField($i, 'findings');

					$sSizeFindings["{$iSampleNo}-{$iPoint}"] = $sFindings;
				}
?>
		    <h2 style="margin:0px;">Measurement Sheet (Size: <?= $sSize ?>, Color: <?= (($sColor == "") ? $sColors : $sColor) ?>)</h2>

		    <div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
			  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			    <tr class="sdRowHeader">
				  <td width="40" align="center"><b>#</b></td>
				  <td><b>Measurement Point</b></td>
				  <td width="90" align="center"><b>Specs</b></td>
				  <td width="90" align="center"><b>Tolerance</b></td>
				  <td width="50" align="center"><b>1</b></td>
				  <td width="50" align="center"><b>2</b></td>
				  <td width="50" align="center"><b>3</b></td>
				  <td width="50" align="center"><b>4</b></td>
				  <td width="50" align="center"><b>5</b></td>
			    </tr>
<?
				$sSQL = "SELECT point_id, specs,
								(SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
								(SELECT point FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Point
						 FROM tbl_style_specs
						 WHERE style_id='$iStyle' AND size_id='$iSamplingSize' AND version='0' AND specs!='0' AND specs!=''
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
				  <td align="center"><?= $sSizeFindings["{$j}-{$iPoint}"] ?></td>
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
		}
?>
		    <br />
		    <h2>Measurement Result</h2>

		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
				<td width="140">Result</td>
				<td width="20" align="center">:</td>
				<td><?= (($sMeasurementResult == "P") ? "Pass" : (($sMeasurementResult == "F") ? "Fail" : "Pending")) ?></td>
			  </tr>

			  <tr valign="top">
			    <td>Remarks</td>
			    <td align="center">:</td>
			    <td><?= nl2br($sMeasurementComments) ?></td>
			  </tr>
			</table>
<?
	}
?>

			  <table border="1" bordercolor="#ffffff" cellpadding="0" cellspacing="0" width="100%">
			    <tr valign="top">
				  <td width="50%">

				    <h2>Work-ManShip</h2>

				    <table border="0" cellpadding="3" cellspacing="0" width="100%">
					  <tr>
					    <td width="140">Total GMTS Inspected</td>
					    <td width="20" align="center">:</td>
					    <td><?= $iTotalGmts ?> (Pcs)</td>
					  </tr>

					  <tr>
					    <td># of GMTS Defective</td>
					    <td align="center">:</td>
					    <td><?= $iGmtsDefective ?> (Pcs)</td>
					  </tr>

					  <tr>
					    <td>Max Allowable Defects</td>
					    <td align="center">:</td>
					    <td><?= $iMaxDefects ?></td>
					  </tr>

					  <tr>
					    <td>Number of Defects</td>
					    <td align="center">:</td>
					    <td><?= (int)$iDefects ?></td>
					  </tr>

					  <tr>
					    <td>D.H.U</td>
					    <td align="center">:</td>
					    <td><?= @round(( ($iDefects / $iTotalGmts) * 100), 2) ?>%</td>
					  </tr>
				    </table>

				  </td>

				  <td width="50%">

				    <h2>Assortment</h2>

				    <table border="0" cellpadding="3" cellspacing="0" width="100%">
					  <tr>
					    <td width="140">Total Cartons Inspected</td>
					    <td width="20" align="center">:</td>
					    <td><?= $iTotalCartons ?></td>
					  </tr>

					  <tr>
					    <td># of Cartons Rejected</td>
					    <td align="center">:</td>
					    <td><?= $iCartonsRejected ?></td>
					  </tr>

					  <tr>
					    <td>% Defective</td>
					    <td align="center">:</td>
					    <td><?= $fPercentDecfective ?></td>
					  </tr>

					  <tr>
					    <td>Acceptable Standard</td>
					    <td align="center">:</td>
					    <td><?= $fStandard ?> %</td>
					  </tr>

					  <tr>
					    <td>D.H.U</td>
					    <td align="center">:</td>
					    <td><?= @round(( ($fCartonsRejected / $fTotalCartons) * 100), 2) ?>%</td>
					  </tr>
				    </table>

				  </td>
			    </tr>
			  </table>
		    </div>

		    <br />
		    <h2>Quantities</h2>

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
		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
			    <td width="140">Order Qty</td>
			    <td width="20" align="center">:</td>
			    <td><?= $iOrderQty ?></td>
			    <td width="140">Total Cartons Required</td>
			    <td width="20" align="center">:</td>
			    <td><?= $fCartonsRequired ?></td>
			  </tr>

			  <tr>
			    <td>Ship Qty</td>
			    <td align="center">:</td>
			    <td><?= $iShipQty ?></td>
			    <td>Total Cartons Shipped</td>
			    <td align="center">:</td>
			    <td><?= $fCartonsShipped ?></td>
			  </tr>

			  <tr>
			    <td>Re-Screen Qty</td>
			    <td align="center">:</td>
			    <td><?= $iReScreenQty ?></td>
			    <td>Deviation</td>
			    <td align="center">:</td>
			    <td><?= @round(( ($fCartonsShipped / $fCartonsRequired) * 100), 2) ?>%</td>
			  </tr>

			  <tr>
			    <td>Deviation</td>
			    <td align="center">:</td>
			    <td colspan="4"><?= @round(( ($iShipQty / $iOrderQty) * 100), 2) ?>%</td>
			  </tr>
		    </table>

		    <br />
		    <h2>Status & Comments</h2>

		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
			    <td width="140">Carton Size</td>
			    <td width="20" align="center">:</td>
			    <td><?= (float)$iLength ?> x <?= (float)$iWidth ?> x <?= (float)$iHeight ?> <?= $sUnit ?></td>
			  </tr>

			  <tr>
				<td>Knitted (%)</td>
				<td align="center">:</td>
				<td><?= (($fKnitted == 0) ? "Not Provided" : $fKnitted) ?></td>
			  </tr>

			  <tr>
				<td>Dyed (%)</td>
				<td align="center">:</td>
				<td><?= (($fDyed == 0) ? "Not Provided" : $fDyed) ?></td>
			  </tr>

			  <tr>
				<td>Cutting</td>
				<td align="center">:</td>
				<td><?= (($iCutting == 0) ? "Not Provided" : $iCutting) ?></td>
			  </tr>

			  <tr>
				<td>Sewing</td>
				<td align="center">:</td>
				<td><?= (($iSewing == 0) ? "Not Provided" : $iSewing) ?></td>
			  </tr>

			  <tr>
				<td>Finishing</td>
				<td align="center">:</td>
				<td><?= (($iFinishing == 0) ? "Not Provided" : $iFinishing) ?></td>
			  </tr>

			  <tr>
				<td>Packing</td>
				<td align="center">:</td>
				<td><?= (($iPacking == 0) ? "Not Provided" : $iPacking) ?></td>
			  </tr>

			  <tr>
				<td>Final Audit Date</td>
				<td align="center">:</td>
				<td><?= (($sFinalAuditDate != "0000-00-00") ? formatDate($sFinalAuditDate) : "Not Provided") ?></td>
			  </tr>

			  <tr valign="top">
			    <td>QA Comments</td>
			    <td align="center">:</td>
			    <td><?= nl2br($sComments) ?></td>
			  </tr>
		    </table>
