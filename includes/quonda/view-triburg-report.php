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
			    <td>Sampling Plan</td>
			    <td align="center">:</td>
			    <td><?= ($CheckLevel == 1)?'Single':($CheckLevel == 2?'Double':'')?></td>
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
				<td>Visual Audit</td>
				<td align="center"><?= (($sVisualAudit == "P") ? "Pass" : (($sVisualAudit == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sVisualAuditRemarks ?></td>
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
                              <td><span style="padding-left:80px;">General Appearance</span></td>
				<td align="center"><?= (($sProductAppearance == "P") ? "Pass" : (($sProductAppearance == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sProductAppearanceRemarks ?></td>
			  </tr>

			  <tr>
				<td><span style="padding-left:80px;">Color</span></td>
				<td align="center"><?= (($sExportCartonPacking == "P") ? "Pass" : (($sExportCartonPacking == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sExportCartonPackingRemarks ?></td>
			  </tr>

			  <tr>
				<td><span style="padding-left:80px;">Hand Feel</span></td>
				<td align="center"><?= (($sHandFeel == "P") ? "Pass" : (($sHandFeel == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sHandFeelRemarks ?></td>
			  </tr>

			  <tr>
				<td><span style="padding-left:80px;">Wearer Test</span></td>
				<td align="center"><?= (($sWearerTest == "P") ? "Pass" : (($sWearerTest == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sWearerTestRemarks ?></td>
			  </tr>
                            <tr>
				<td colspan="3">Packing & Assortment</td>
			  </tr>
			  <tr>
				<td><span style="padding-left:80px;">Count Accuracy</span></td>
				<td align="center"><?= (($sPackingCount == "P") ? "Pass" : (($sPackingCount == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sPackingCountRemarks ?></td>
			  </tr>

			  <tr>
				<td><span style="padding-left:80px;">Ftp</span></td>
				<td align="center"><?= (($sPackingFtp == "P") ? "Pass" : (($sPackingFtp == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sPackingFtpRemarks ?></td>
			  </tr>

			  <tr>
				<td><span style="padding-left:80px;">Gtp</span></td>
				<td align="center"><?= (($sPackingGtp == "P") ? "Pass" : (($sPackingGtp == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sPackingGtpRemarks ?></td>
			  </tr>
                            <tr>
				<td><span style="padding-left:80px;">Packing</span></td>
				<td align="center"><?= (($sPacking == "P") ? "Pass" : (($sPacking == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sPackingRemarks ?></td>
			  </tr>
                          <tr>
				<td><span style="padding-left:80px;">Carton Drop Test</span></td>
				<td align="center"><?= (($sCartonDropTest == "P") ? "Pass" : (($sCartonDropTest == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sCartonDropTestRemarks ?></td>
			  </tr>
                            <tr>
				<td><span style="padding-left:80px;">Shade Band</span></td>
				<td align="center"><?= (($sShadeBand == "P") ? "Pass" : (($sShadeBand == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sShadeBandRemarks ?></td>
			  </tr>
                          <tr>
				<td><span style="padding-left:80px;">Carton Quality</span></td>
				<td align="center"><?= (($sCartonQuality == "P") ? "Pass" : (($sCartonQuality == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sCartonQualityRemarks ?></td>
			  </tr>
                          <tr>
				<td><span style="padding-left:80px;">Carton Weight</span></td>
				<td align="center"><?= (($sCartonWeight == "P") ? "Pass" : (($sCartonWeight == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sCartonWeightRemarks ?></td>
			  </tr>
                          <tr>
				<td><span style="padding-left:80px;">Carton Dimension</span></td>
				<td align="center"><?= (($sCartonDimension == "P") ? "Pass" : (($sCartonDimension == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sCartonDimensionRemarks ?></td>
			  </tr>

			  <tr>
				<td colspan="3">Labelling, Marking</td>
			  </tr>
                         <tr>
				<td><span style="padding-left:80px;">Barcode Verification</span></td>
				<td align="center"><?= (($sBarcodeVerification == "P") ? "Pass" : (($sBarcodeVerification == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sBarcodeVerificationRemarks ?></td>
			  </tr>
                          <tr>
				<td><span style="padding-left:80px;">Labelling</span></td>
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
				<td align="center"><?= (($sFinishing == "P") ? "Pass" : (($sFinishing == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sFinishingRemarks ?></td>
			  </tr>
                          <tr>
				<td><span style="padding-left:80px;">Fitting</span></td>
				<td align="center"><?= (($sFitting == "P") ? "Pass" : (($sFitting == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sFittingRemarks ?></td>
			  </tr>

			  <tr>
				<td>PP Sample</td>
				<td align="center"><?= (($sPpSample == "P") ? "Pass" : (($sPpSample == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sPpSampleRemarks ?></td>
			  </tr>

			  <tr>
				<td>Onsite Test for Metal Detection</td>
				<td align="center"><?= (($sMetalDetectionTest == "P") ? "Pass" : (($sMetalDetectionTest == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sMetalDetectionTestRemarks ?></td>
			  </tr>
                          
                           <tr>
				<td>Shipment Result</td>
				<td align="center"><?= (($sShipmentAudit == "P") ? "Pass" : (($sShipmentAudit == "F") ? "Fail" : "-")) ?></td>
				<td><?= $sShipmentAuditRemarks ?></td>
			  </tr>
			</table>
			</div>

			<br />
			<h2>Remarks</h2>

			<div style="padding:5px;">
			<table border="0" cellpadding="5" cellspacing="0" width="100%">
			  <tr>
				<td><?= $sRemarks ?></td>
			  </tr>
			</table>
			</div>
<?
                        if($iTotalCartons > 2)
                        {
?>
				<h2>List of Carton Numbers Opened</h2>

				<div style=" padding:3px;">
                                    
                                    <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                                        <tr>
<?
                                    $iCartonsSampleSize = getCartonSampleSize($iTotalCartons);                                    
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
                                          <td style="width:35%;"><?=$sCartonNo?></td>
                                          <td style="width:55%;"><?=($sResult == 'P')?'Approved':($sResult == 'F'?'Not-Approved':'N/A')?></td>
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
			WHERE po.id=pc.po_id AND pc.po_id=pq.po_id AND pq.size_id=s.id AND pc.style_id='$iStyle' AND (pc.po_id='$iPoId' OR FIND_IN_SET(pc.po_id, '$sAdditionalPos'))
				  AND pq.quantity>'0' AND FIND_IN_SET(s.id, '$sSizes') AND FIND_IN_SET(pc.color, '$sColors')
			GROUP BY po.id, pc.color, s.id
			ORDER BY po.id, pc.color, s.position";
	$objDb->query($sSQL);

	$iCount          = $objDb->getCount( );
	$sLastPo         = "";
	$sLastColor      = "";
	$iTotalTQunatity = 0;
	$iTotalPQuantity = 0;
	
	
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
                                              <td width="100"><b>Order Qty</b></td>
											  <td width="100"><b>Presented Qty</b></td>
											  <td width="100"><b>Percentage</b></td>
											  <td width="100"><b>Deviation</b></td>
                                    </tr>
<?
		}

		
		if ($sTColor != $sLastColor && $i > 0)
		{	
?>
	
                                    <tr bgcolor="#f0f0f0">
                                        <td colspan="2"><b>TOTAL</b></td>
                                        <td><b><?= formatNumber($iTotalTQunatity, false) ?></b></td>
                                        <td><b><?= formatNumber($iTotalPQuantity, false) ?></b></td>
										<td><b><?= formatNumber(($iTotalPQuantity / $iTotalTQunatity)*100) ?> %</b></td>
										<td><b><?= formatNumber(($iTotalPQuantity - $iTotalTQunatity), false) ?></b></td>
                                    </tr>
<?
		}
		
		
		$iPQuantity       = $sQaQuantitiesList["{$iTPoId}-{$iTSize}-{$sTColor}"];
		
		$sLastPo          = $sTOrderNo;
		$sLastColor       = $sTColor;
		$iTotalTQunatity += $iTQunatity;
		$iTotalPQuantity += $iPQuantity;
?>
	
                                    <tr>
                                        <td><?= $sTColor ?></td>
                                        <td><?= $sTSize ?></td>
                                        <td><?= formatNumber($iTQunatity, false) ?></td>
                                        <td><?= formatNumber($iPQuantity, false) ?></td>
										<td><?= formatNumber(($iPQuantity / $iTQunatity)*100) ?> %</td>
										<td><?= formatNumber(($iPQuantity - $iTQunatity), false) ?></td>
                                    </tr>
<?
	}
?>

                                    <tr bgcolor="#f0f0f0">
                                        <td colspan="2"><b>TOTAL</b></td>
                                        <td><b><?= formatNumber($iTotalTQunatity, false) ?></b></td>
                                        <td><b><?= formatNumber($iTotalPQuantity, false) ?></b></td>
										<td><b><?= formatNumber(($iTotalPQuantity / $iTotalTQunatity)*100) ?> %</b></td>
										<td><b><?= formatNumber(($iTotalPQuantity - $iTotalTQunatity), false) ?></b></td>
                                    </tr>
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
        
$sAllPOs = $iPoId;
$iCounter = 1;

if($sAdditionalPos != "")
    $sAllPOs = $sAllPOs.",".$sAdditionalPos;

$iAllPOs = explode(",", $sAllPOs);
foreach ($iAllPOs as $iPo)
{
    $sPoNo = getDbValue("order_no", "tbl_po", "id='$iPo'");
?>
                                <h3>Order No: <?=$sPoNo?><input type="hidden" name="PosArr[]" value="<?=$iPo?>"></h3>
                                <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                                    <tr class="sdRowHeader">
                                              <td width="20" align="center"><b>#</b></td>
                                              <td width="80"><b>Size</b></td>
                                              <td width="200"><b>Color</b></td>
                                              <td><b>Quantity</b></td>
                                    </tr>
<?
                                        $Colors = @explode(",", $sColors);
                                        $iSizes  = @explode(",", $sSizes);
        
                                        foreach ($Colors as $sColor)
                                        {
                                            $sSpecialColor = str_replace(["'",",",'"',"&"," "], "", $sColor);
                                                foreach ($iSizes as $iSize)
                                                {
?>
                                    <tr>
                                        <td><?=$iCounter++?></td>
                                        <td><input type="hidden" name="SizesArr<?=$iPo?>_<?=$iSize?>_<?=$sSpecialColor?>" value="<?=$iSize?>"><?=getDbValue("size", "tbl_sampling_sizes", "id='$iSize'")?></td>
                                        <td><input type="hidden" name="ColorsArr<?=$iPo?>_<?=$iSize?>_<?=$sSpecialColor?>" value="<?=$Colors?>"><?=$sColor?></td>
                                        <td><?=$iQuantitiesList[$iPo][$iSize]["{$sColor}"]?></td>
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
                                if(getCartonSampleSize($iTotalCartons) > 2)
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
			      </tr>
			    </table>
                                <?
                            $sPackagingDefectsList = getList("tbl_packaging_defects", "id", "CONCAT(code,' - ',defect)", "", "id");
?>
  
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
                                        <td><?=$sPackagingDefectsList[$iDefectCodeId]?></td>
					<td width="100" align="center"><?=$iSampleNumber?></td>
                                        <td width="200" align="center">
<?
                                                if ($sDefectPicture != "" && @file_exists($sPackagingDir.$sDefectPicture))
                                                {
?>
                                            <br/><span>(<a href="<?= $sPkPicsDir ?><?= $sDefectPicture ?>" class="lightview"><?= $sDefectPicture ?></a>)&nbsp;</span>
						  <input type="hidden" name="PrevPicture[]" value="<?= $sDefectPicture ?>">
<?
                                                } 
?>
                                        </td>
			      </tr>
<?
                                }
                        }
?>
			    </table>
                                </div>                               
<?
                                }
?>

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
			    <td><?= nl2br($sMeasurementResultRemarks) ?></td>
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
