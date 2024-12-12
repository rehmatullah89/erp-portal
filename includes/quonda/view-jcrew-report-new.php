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
		$ProductStyle                   = $objDb->getField(0, "style");
		$ProductStyleRemarks            = $objDb->getField(0, "style_remarks");
		$ProductColour                  = $objDb->getField(0, "colour");
		$ProductColourRemarks           = $objDb->getField(0, "colour_remarks");
		$Assortment                     = $objDb->getField(0, "assortment");
		$AssortmentRemarks              = $objDb->getField(0, "assortment_remarks");
		$FabricGauge                    = $objDb->getField(0, "fabric_weight");
		$FabricGaugeRemarks             = $objDb->getField(0, "fabric_weight_remarks");
		$Lining                         = $objDb->getField(0, "lining");
		$LiningRemarks                  = $objDb->getField(0, "lining_remarks");
		$Labeling                       = $objDb->getField(0, "labeling_main");
		$LabelingRemarks                = $objDb->getField(0, "labeling_main_remarks");
		$LabelingOther                  = $objDb->getField(0, "labeling_others");
		$LabelingOtherRemarks           = $objDb->getField(0, "labeling_others_remarks");
		$HangTag                        = $objDb->getField(0, "hangtag_others");
		$HangTagRemarks                 = $objDb->getField(0, "hangtag_others_remarks");
		$PriceTicket                    = $objDb->getField(0, "price_ticket");
		$PriceTicketRemarks             = $objDb->getField(0, "price_ticket_remarks");
		$ExportCartonDimension          = $objDb->getField(0, "export_carton_packing");
		$ExportCartonDimensionRemarks   = $objDb->getField(0, "export_carton_packing_remarks");
		$AsnLabel                       = $objDb->getField(0, "ans_label");
		$AsnLabelRemarks                = $objDb->getField(0, "ans_label_remarks");
		$Packaging                      = $objDb->getField(0, "product_packaging");
		$PackagingRemarks               = $objDb->getField(0, "product_packaging_remarks");
		$InnerCartonAppearance          = $objDb->getField(0, "appearance");
		$InnerCartonAppearanceRemarks   = $objDb->getField(0, "appearance_remarks");
		$PolybagQuality                 = $objDb->getField(0, "polybag_quality_size");
		$PolybagQualityRemarks          = $objDb->getField(0, "polybag_quality_size_remarks");
		$PolybagSticker                 = $objDb->getField(0, "polybag_sticker");
		$PolybagStickerRemarks          = $objDb->getField(0, "polybag_sticker_remarks");
		$Hanger                         = $objDb->getField(0, "hanger");
		$HangerRemarks                  = $objDb->getField(0, "hanger_remarks");
		$Embroidery                     = $objDb->getField(0, "embroidery");
		$EmbroideryRemarks              = $objDb->getField(0, "embroidery_remarks");
		$Buttoning                      = $objDb->getField(0, "buttoning");
		$ButtoningRemarks               = $objDb->getField(0, "buttoning_remarks");
		$WashEffect                     = $objDb->getField(0, "wash_effect");
		$WashEffectRemarks              = $objDb->getField(0, "wash_effect_remarks");
		$FitDummy                       = $objDb->getField(0, "dummy_fit");
		$FitDummyRemarks                = $objDb->getField(0, "dummy_fit_remarks");
		$PullTesting                    = $objDb->getField(0, "product_safety");
		$PullTestingRemarks             = $objDb->getField(0, "product_safety_remarks");
		$sRemarks1                      = $objDb->getField(0, "remarks_1");
		$sRemarks2                      = $objDb->getField(0, "remarks_2");
		$sRemarks3                      = $objDb->getField(0, "remarks_3");
		$sRemarks4                      = $objDb->getField(0, "remarks_4");
		$sCartonNos                     = $objDb->getField(0, "carton_nos");
		$sMeasurementResult             = $objDb->getField(0, "measurement_result");
		$sMeasurementComments           = $objDb->getField(0, "measurement_overall_remarks");
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

        $sSizes = rtrim($sSizes, ",");
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
				<table border="1" bordercolor="#dddddd" cellpadding="5" cellspacing="0" width="100%">
                                    <tr bgcolor="#eeeeee">
                                          <td></td>
                                          <td width="100" align="center">Pass / Fail</td>
                                          <td width="500" align="center">Remarks</td>
                                    </tr>

                                    <tr>
                                      <td colspan="3">Conformity</td>
                                    </tr>

                                    <tr>
                                        <td><span style="padding-left:80px;">Style Construction</span></td>
                                        <td align="center"><?= (($ProductStyle == "P") ? "Pass" : (($ProductStyle == "F") ? "Fail" : "-")) ?></td>
                                        <td><?= $ProductStyleRemarks ?></td>
                                    </tr>

                                    <tr>
                                          <td><span style="padding-left:80px;">Color and Colorway</span></td>
                                          <td align="center"><?= (($ProductColour == "P") ? "Pass" : (($ProductColour == "F") ? "Fail" : "-")) ?></td>
                                          <td><?= $ProductColourRemarks ?></td>
                                    </tr>
                                    
                                    <tr>
					<td>Assortment (colour/ style/ size)</td>
					<td align="center"><?= (($Assortment == "P") ? "Pass" : (($Assortment == "F") ? "Fail" : "-")) ?></td>
					<td><?= $AssortmentRemarks ?></td>
				  </tr>
                                  
                                  <tr>
					<td>Fabric / Gauge / Weight</td>
					<td align="center"><?= (($FabricGauge == "P") ? "Pass" : (($FabricGauge == "F") ? "Fail" : "-")) ?></td>
					<td><?= $FabricGaugeRemarks ?></td>
				  </tr>
                                   <tr>
					<td>Lining</td>
					<td align="center"><?= (($Lining == "P") ? "Pass" : (($Lining == "F") ? "Fail" : "-")) ?></td>
					<td><?= $LiningRemarks ?></td>
				  </tr>
                                  <tr>
				    <td colspan="3">Labeling</td>
				  </tr>
                                  <tr>
                                      <td style="padding-left: 85px;">Main, C/O, Content and Care Construction</td>

					<td align="center"><?= (($Labeling == "P") ? "Pass" : (($Labeling == "F") ? "Fail" : "-")) ?></td>
					<td><?= $LabelingRemarks ?></td>
				  </tr>

				  <tr>
					<td><span style="padding-left:80px;">Others</span></td>

					<td align="center"><?= (($LabelingOther == "P") ? "Pass" : (($LabelingOther == "F") ? "Fail" : "-")) ?></td>
					<td><?= $LabelingOtherRemarks ?></td>
				  </tr>
                                  <tr>
					<td>Hangtag & Tags</td>

					<td align="center"><?= (($HangTag == "P") ? "Pass" : (($HangTag == "F") ? "Fail" : "-")) ?></td>
					<td><?= $HangTagRemarks ?></td>
				  </tr>

				  <tr>
					<td>Price Ticket</td>

					<td align="center"><?= (($PriceTicket == "P") ? "Pass" : (($PriceTicket == "F") ? "Fail" : "-")) ?></td>
					<td><?= $PriceTicketRemarks ?></td>
				  </tr>
                                  <tr>
					<td>Export Carton & Dimension</td>

					<td align="center"><?= (($ExportCartonDimension == "P") ? "Pass" : (($ExportCartonDimension == "F") ? "Fail" : "-")) ?></td>
					<td><?= $ExportCartonDimensionRemarks ?></td>
				  </tr>
                                  <tr>
					<td>ASN Label</td>

					<td align="center"><?= (($AsnLabel == "P") ? "Pass" : (($AsnLabel == "F") ? "Fail" : "-")) ?></td>
					<td><?= $AsnLabelRemarks ?></td>
				  </tr>
                                  <tr>
					<td>Packaging</td>
					<td align="center"><?= (($Packaging == "P") ? "Pass" : (($Packaging == "F") ? "Fail" : "-")) ?></td>
					<td><?= $PackagingRemarks ?></td>
				  </tr>
                                  <tr>
					<td>Product Appearance Inside the Carton</td>
					<td align="center"><?= (($InnerCartonAppearance == "P") ? "Pass" : (($InnerCartonAppearance == "F") ? "Fail" : "-")) ?></td>
					<td><?= $InnerCartonAppearanceRemarks ?></td>
				  </tr>
                                  <tr>
					<td>Polybag (Quality and size)</td>

					<td align="center"><?= (($PolybagQuality == "P") ? "Pass" : (($PolybagQuality == "F") ? "Fail" : "-")) ?></td>
					<td><?= $PolybagQualityRemarks ?></td>
				  </tr>
                                  
                                  <tr>
					<td>Polybag Sticker</td>

					<td align="center"><?= (($PolybagSticker == "P") ? "Pass" : (($PolybagSticker == "F") ? "Fail" : "-")) ?></td>
					<td><?= $PolybagStickerRemarks ?></td>
				  </tr>
                                  
                                  <tr>
					<td>Hanger</td>
					<td align="center"><?= (($Hanger == "P") ? "Pass" : (($Hanger == "F") ? "Fail" : "-")) ?></td>
					<td><?= $HangerRemarks ?></td>
				  </tr>
                                  
                                  <tr>
					<td>Embroidery / Printing / Beading</td>
					<td align="center"><?= (($Embroidery == "P") ? "Pass" : (($Embroidery == "F") ? "Fail" : "-")) ?></td>
					<td><?= $EmbroideryRemarks ?></td>
				  </tr>
                                  
                                  <tr>
					<td>Buttoning</td>
					<td align="center"><?= (($Buttoning == "P") ? "Pass" : (($Buttoning == "F") ? "Fail" : "-")) ?></td>
					<td><?= $ButtoningRemarks ?></td>
				  </tr>
                                  
                                  <tr>
					<td>Wash Effect</td>
					<td align="center"><?= (($WashEffect == "P") ? "Pass" : (($WashEffect == "F") ? "Fail" : "-")) ?></td>
					<td><?= $WashEffectRemarks ?></td>
				  </tr>
                                  
                                  <tr>
					<td>Fit on Dummy</td>
					<td align="center"><?= (($FitDummy == "P") ? "Pass" : (($FitDummy == "F") ? "Fail" : "-")) ?></td>
					<td><?= $FitDummyRemarks ?></td>
				  </tr>
                                 
                                  <tr>
					<td>Product Safety and Pull Testing</td>
					<td align="center"><?= (($PullTesting == "P") ? "Pass" : (($PullTesting == "F") ? "Fail" : "-")) ?></td>
					<td><?= $PullTestingRemarks ?></td>
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
