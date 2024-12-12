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
                $sWovenWeight                 = $objDb->getField(0, "woven_weight");
                $sWovenWeightRemarks          = $objDb->getField(0, "woven_weight_remarks");
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
			    <td><?= $sParent ?></td>
			  </tr>
                          
                          <tr>
			    <td width="120">Factory</td>
			    <td width="20" align="center">:</td>
			    <td><?= $sVendor ?></td>
			  </tr>

			  <tr>
			    <td>Auditor</td>
			    <td align="center">:</td>
			    <td><?= $sAuditor ?></td>
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
				<td>Knit Weight</td>
				<td align="center"><?= (($sFabricWeight == "P") ? "Pass" : (($sFabricWeight == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sFabricWeightRemarks ?></td>
			  </tr>
                          
                           <tr>
				<td>Woven Weight</td>
				<td align="center"><?= (($sWovenWeight == "P") ? "Pass" : (($sWovenWeight == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sWovenWeightRemarks ?></td>
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

		    <br />
		    <h2 style="margin:0px;">Defects Details</h2>

		    <div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
			  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			    <tr class="sdRowHeader">
				  <td width="50" align="center"><b>#</b></td>
				  <td><b>Code - Check Points</b></td>
				  <td width="100" align="center"><b>Defects</b></td>
				  <td width="200"><b>Area</b></td>
				  <td width="100" align="center"><b>Nature</b></td>
			    </tr>

<?
	$iDefects = 0;

	$sSQL = "SELECT * FROM tbl_qa_report_defects WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for($i = 0; $i < $iCount; $i ++)
	{
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
				  <td><?= $objDb3->getField(0, 0) ?></td>
				  <td align="center"><?= $sNature ?></td>
			    </tr>
<?
	}

	if ($iCount == 0)
	{
?>

			    <tr class="sdRowColor">
				  <td colspan="5" align="center">No Defect Found!</td>
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
                                          </tr>

                                          <tr>
                                            <td>Ship Qty</td>
                                            <td align="center">:</td>
                                            <td><?= $iShipQty ?></td>
                                          </tr>

                                          <tr>
                                            <td>Deviation</td>
                                            <td align="center">:</td>
                                            <td ><?= @round(( ($iShipQty / $iOrderQty) * 100), 2) ?>%</td>
                                          </tr>
                                    </table>

				  </td>
			    </tr>
			  </table>
		    </div>

		    <br />
		    <h2>Status & Comments</h2>

		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
                          <tr>
				<td width="140">Packing</td>
				<td width="20" align="center">:</td>
				<td><?= (($iPacking == 0) ? "Not Provided" : $iPacking) ?></td>
			  </tr>
                          
			  <tr>
				<td>Cutting</td>
				<td  align="center">:</td>
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

			  <tr valign="top">
			    <td>QA Comments</td>
			    <td align="center">:</td>
			    <td><?= nl2br($sComments) ?></td>
			  </tr>
		    </table>
