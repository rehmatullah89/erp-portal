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
?>
		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
			    <td width="90">Vendor</td>
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
        $AllPos = $iPoId.($sAdditionalPos != ""?",".$sAdditionalPos:"");
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
			    <td>Category</td>
			    <td align="center">:</td>
			    <td><?= getDbValue("category", "tbl_po", "id = '$iPoId'") ?></td>
			  </tr>
                          
                          <tr>
			    <td>Product Code</td>
			    <td align="center">:</td>
			    <td><?= getDbValue("GROUP_CONCAT(product_code SEPARATOR ',')", "tbl_po", "id IN ($AllPos)") ?></td>
			  </tr>
                          
                          <tr>
			    <td>Audit Type</td>
			    <td align="center">:</td>
			    <td><?= getDbValue("type", "tbl_audit_types", "id = '$iAuditTypeId'") ?></td>
			  </tr>
                          
			  <tr>
			    <td>Audit Stage</td>
			    <td align="center">:</td>
			    <td><?= ($iAuditTypeId == 2 && $iTotalGmts == 32)?"Validation":$sAuditStagesList[$sAuditStage] ?></td>
			  </tr>


<?
	switch ($sAuditResult)
	{
		case "P" : $sAuditResult = "Pass"; break;
		case "F" : $sAuditResult = "Fail"; break;
                case "H" : $sAuditResult = "Hold"; break;
		case "N" : $sAuditResult = "Fail-NV"; break;
                case "E" : $sAuditResult = "Exception"; break;
                case "R" : $sAuditResult = "Rescreen"; break;
	}
?>
			  <tr>
			    <td>Audit Result</td>
			    <td align="center">:</td>
			    <td><?= $sAuditResult ?></td>
			  </tr>

			  <tr>
			   <td>Colors</td>
			    <td align="center">:</td>
			    <td><?= $sColors ?></td>
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

		    <br />
		    <h2 style="margin:0px;">Defects Details</h2>

		    <div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
			  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			    <tr class="sdRowHeader">
				  <td width="60" align="center"><b>#</b></td>
				  <td><b>Code - Check Points</b></td>
				  <td width="100" align="center"><b>Defects</b></td>
				  <td width="200"><b>Area</b></td>
				  <td width="70"><b>Nature</b></td>
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
?>

			    <tr class="sdRowColor">
				  <td align="center"><?= ($i + 1) ?></td>
				  <td><?= $objDb2->getField(0, 0) ?> - <?= $objDb2->getField(0, 1) ?></td>
				  <td align="center"><?= $objDb->getField($i, 'defects') ?></td>
				  <td><?= $objDb3->getField(0, 0) ?></td>
				  <td><?= (($objDb->getField($i, 'nature') == 1) ? "MAJOR" : "minor") ?></td>
			    </tr>
<?
		if($objDb->getField($i, 'remarks') != "")
		{
?>
                <tr class="sdRowColor">
				  <td align="center"><b>Remarks</b></td>
				  <td colspan="4"><?= $objDb->getField($i, 'remarks') ?></td>
			    </tr>
<?
		}
	}

	if ($iCount == 0)
	{
?>

			    <tr class="sdRowColor">
				  <td colspan="5" align="center">No Defect Found!</td>
			    </tr>
<?
	}

//	if ($iGmtsDefective == 0)
//		$iGmtsDefective = $iDefects;
        
?>
			  </table>
<?
        $sSQL = "SELECT * FROM tbl_qa_levis_reports WHERE audit_id = '$Id'";
	$objDb->query($sSQL);

        $iSafety            = $objDb->getField(0, "safety");
        $iCriticalFailure   = $objDb->getField(0, "critical_failure");
        $iSewing            = $objDb->getField(0, "sewing");
        $iAppearance        = $objDb->getField(0, "appearance");
        $iMeasurements      = $objDb->getField(0, "measurements");
        $iSundriseMissing   = $objDb->getField(0, "sundries_missing");
        $iSundriesBroken    = $objDb->getField(0, "sundries_broken");
        $iAccuracy          = $objDb->getField(0, "accuracy");
        $iPhysicals         = $objDb->getField(0, "physicals");
        $iOther             = $objDb->getField(0, "other");
        $iCartonsSampled    = $objDb->getField(0, "cartons_sampled");
        $iCartonsInError    = $objDb->getField(0, "cartons_in_error");
        $iUnitsSampled      = $objDb->getField(0, "units_sampled");
        $iUnitsInErrors     = $objDb->getField(0, "units_in_errors");
        $iOverage           = $objDb->getField(0, "overage");
        $iShortage          = $objDb->getField(0, "shortage");
        $iWrongSize         = $objDb->getField(0, "wrong_size");
        $iWrongPC           = $objDb->getField(0, "wrong_pc");
        $iIrregulars        = $objDb->getField(0, "irregulars");
        $iWrongSundries     = $objDb->getField(0, "wrong_sundries");
?>
                    <table border="1" bordercolor="#ffffff" cellpadding="0" cellspacing="0" width="100%">
				  <tr valign="top">
                                  <tr><td colspan="2"> <h2>Critical Defects</h2></td></tr>
					<td width="50%">
					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="140">Safety</td>
						  <td width="20" align="center">:</td>
						  <td><?= $iSafety ?></td>
					    </tr>

					    <tr>
						  <td>Sewing</td>
						  <td align="center">:</td>
						  <td><?= $iSewing ?></td>
					    </tr>

					    <tr>
						  <td>Measurements</td>
						  <td align="center">:</td>
						  <td><?= $iMeasurements ?></td>
					    </tr>

					    <tr>
						  <td>Accuracy</td>
						  <td align="center">:</td>
						  <td><?= $iAccuracy ?></td>
					    </tr>
                                            
                                            <tr>
						  <td>Other</td>
						  <td align="center">:</td>
						  <td><?= $iOther ?></td>
					    </tr>
					  </table>

					</td>

					<td width="50%">

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="140">Critical Failure</td>
						  <td width="20" align="center">:</td>
						  <td><?= $iCriticalFailure ?></td>
					    </tr>

					    <tr>
						  <td>Appearance</td>
						  <td align="center">:</td>
						  <td><?= $iAppearance ?></td>
					    </tr>

					    <tr>
						  <td>Sundries Broken</td>
						  <td align="center">:</td>
						  <td><?= $iSundriesBroken ?></td>
					    </tr>

					    <tr>
						  <td>Physicals</td>
						  <td align="center">:</td>
						  <td><?= $iPhysicals ?></td>
					    </tr>

					    <tr>
                                                <td colspan="3">&nbsp;</td>
					    </tr>
					  </table>

					</td>
				  </tr>
				</table>

				<br />
                                <table border="1" bordercolor="#ffffff" cellpadding="0" cellspacing="0" width="100%">
				  <tr valign="top">
                                  <tr><td colspan="2"> <h2>Accuracy Information</h2></td></tr>
					<td width="50%">
					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="140">Cartons Sampled</td>
						  <td width="20" align="center">:</td>
						  <td><?= $iCartonsSampled ?></td>
					    </tr>

					    <tr>
						  <td>Units sampled</td>
						  <td align="center">:</td>
						  <td><?= $iUnitsSampled ?></td>
					    </tr>

					    <tr>
						  <td>Overage</td>
						  <td align="center">:</td>
						  <td><?= $iOverage ?></td>
					    </tr>

					    <tr>
						  <td>Wrong Size</td>
						  <td align="center">:</td>
						  <td><?= $iWrongSize ?></td>
					    </tr>
                                            
                                            <tr>
						  <td>Irregulars</td>
						  <td align="center">:</td>
						  <td><?= $iIrregulars ?></td>
					    </tr>
					  </table>

					</td>

					<td width="50%">

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="140">Cartons in Error</td>
						  <td width="20" align="center">:</td>
						  <td><?= $iCartonsInError ?></td>
					    </tr>

					    <tr>
						  <td>Units in Errors</td>
						  <td align="center">:</td>
						  <td><?= $iUnitsInErrors ?></td>
					    </tr>
                                            
                                            <tr>
						  <td>Shortage</td>
						  <td align="center">:</td>
						  <td><?= $iShortage ?></td>
					    </tr>
                                            
					    <tr>
						  <td>Wrong PC</td>
						  <td align="center">:</td>
						  <td><?= $iWrongPC ?></td>
					    </tr>

					    <tr>
						  <td>Wrong Sundries</td>
						  <td align="center">:</td>
						  <td><?= $iWrongSundries ?></td>
					    </tr>
					  </table>

					</td>
				  </tr>
				</table>

				<br />
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
<br/>
<?
    // Measurement Sheet starts here
        $sColors = @explode(",", $sColors);
        $iSizes  = @explode(",", $Sizes);
	$iColor  = 0;
        
        $sSizesList   = getList("tbl_sizes", "id", "size", "FIND_IN_SET(id, '$Sizes')", "size");
         
	foreach ($sColors as $sColor)
	{
		foreach ($iSizes as $iSize)
		{
                    if(!empty($sColor) && !empty($iSize))
                    { 
			$sSize         = getDbValue("size", "tbl_sizes", "id='$iSize'");
			$iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '$sSize'");

                        if ($iSamplingSize == 0 && strpos($sSize, " ") !== FALSE)
                        {
                            @list($sWaist, $sInseenLength) = @explode(" ", $sSize);

                            $iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '$sWaist'");
                        }

			$sSQL = "SELECT qrs.sample_no, qrss.point_id, qrss.findings, qrss.specs
					 FROM tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss
					 WHERE qrs.audit_id='$Id' AND qrs.id=qrss.sample_id AND qrs.size_id='$iSamplingSize' AND qrs.color='$sColor' AND (qrs.size='' OR qrs.size='$sSize')
					 ORDER BY qrs.sample_no, qrss.point_id";
			$objDb->query($sSQL);

			$iCount         = (int)$objDb->getCount( );
			$sSizeFindings  = array( );
                        $sSizeSpecs     = array( );
                        $sValuePoints   = array( );

                        if($iCount == 0)
                            continue;
                        
			for($i = 0; $i < $iCount; $i ++)
			{
				$iSampleNo = $objDb->getField($i, 'sample_no');
				$iPoint    = $objDb->getField($i, 'point_id');
				$sFindings = $objDb->getField($i, 'findings');
                                $sSizeSpec = $objDb->getField($i, 'specs');

				$sSizeFindings["{$iSampleNo}-{$iPoint}"] = (($sFindings == '' || $sFindings == '0' || strtolower($sFindings) == 'ok')?'-':$sFindings);
                                $sSizeSpecs["{$iPoint}"] = $sSizeSpec;
                                
                                if($sFindings < 0 || $sFindings > 0)
                                    $sValuePoints["{$iPoint}"] = 1;
			}
?>
				<h2 style="margin:0px;">Measurement Sheet (Size: <?= $sSize ?>, Color: <?= $sColor ?>)</h2>

				<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
				  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
					<tr class="sdRowHeader">
					  <td width="25" align="center"><b>#</b></td>
                                          <td width="50" align="center"><b>POM</b></td>
					  <td><b>Measurement Point</b></td>
					  <td width="80" align="center"><b>Specs</b></td>
					  <td width="80" align="center"><b>Tolerance</b></td>
					  <td width="45" align="center"><b>1</b></td>
					  <td width="45" align="center"><b>2</b></td>
					  <td width="45" align="center"><b>3</b></td>
					  <td width="45" align="center"><b>4</b></td>
					  <td width="45" align="center"><b>5</b></td>
                                          <td width="45" align="center"><b>6</b></td>
					</tr>
<?
			$sSQL = "SELECT point_id, specs, nature,
							(SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
							(SELECT point FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Point,
                                                        (SELECT point_id FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _PointId
					 FROM tbl_style_specs
					 WHERE style_id='$Style' AND size_id='$iSamplingSize' AND version='0'
					 ORDER BY FIELD(nature, 'C') DESC";
                        //AND specs!='0' AND specs!=''
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );
                        
						
                        if ($iCount == 0 && $sSizesList[$iSize] == "XXL")
                        {
                                $iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '2XL'");

                                if ($iSamplingSize > 0)
                                {
                                        $sSQL = "SELECT point_id, specs, nature,
							(SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
							(SELECT point FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Point,
                                                        (SELECT point_id FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _PointId
					 FROM tbl_style_specs
					 WHERE style_id='$Style' AND size_id='$iSamplingSize' AND version='0'
					 ORDER BY FIELD(nature, 'C') DESC";
                                        $objDb->query($sSQL);

                                        $iCount = $objDb->getCount( );
                                }
                        }
                        
                        if ($iCount == 0 && strpos($sSizesList[$iSize], " ") !== FALSE)
                        {
                                $sSize         = str_replace(" ", "", $sSizesList[$iSize]);
                                $iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '$sSize'");

                                if ($iSamplingSize == 0 && substr($sSizesList[$iSize], -2) == " S")
                                {
                                        $sSize         = str_replace(" S", "W", $sSizesList[$iSize]);
                                        $iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '$sSize'");
                                }

                                if ($iSamplingSize > 0)
                                {
                                        $sSQL = "SELECT point_id, specs, nature,
							(SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
							(SELECT point FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Point,
                                                        (SELECT point_id FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _PointId
					 FROM tbl_style_specs
					 WHERE style_id='$Style' AND size_id='$iSamplingSize' AND version='0'
					 ORDER BY FIELD(nature, 'C') DESC";
                                        $objDb->query($sSQL);

                                        $iCount = $objDb->getCount( );
                                }
                        }
                        
                        
                        $iCounter = 1;
                        
			for($i = 0; $i < $iCount; $i ++)
			{
				$iPoint     = $objDb->getField($i, 'point_id');
                                $sNature    = $objDb->getField($i, 'nature');
				$sSpecs     = $objDb->getField($i, 'specs');
				$sPoint     = $objDb->getField($i, '_Point');
                                $iPointId   = $objDb->getField($i, '_PointId');
				$sTolerance = $objDb->getField($i, '_Tolerance');
                                
                               //if(@$sValuePoints[$iPoint] != 1)
                                //   continue;
?>
                                <tr class="sdRowColor">
					  <td align="center"><?= $iCounter++ ?></td>
                                          <td <?=(strtolower($sNature) == 'c'?'style="color:red;"':'')?>><?=$iPointId?></td>
					  <td <?=(strtolower($sNature) == 'c'?'style="color:red;"':'')?>><?= $sPoint ?></td>
					  <td align="center">
                                              <?
                                                if(@in_array($iPointId, array("INS1","INSEC")))
                                                {
                                                    echo (@$sSizeSpecs[$iPoint] != ""?$sSizeSpecs[$iPoint]:$sSpecs);
                                                }
                                                else
                                                    echo $sSpecs;
                                                ?>
                                          </td>
					  <td align="center"><?= $sTolerance ?></td>
<?
				for ($j = 1; $j <= 6; $j ++)
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


		$iColor ++;
	}
?>
		    <br />
		    <h2>Status & Comments</h2>

		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
			    <td width="140">QA Comments</td>
			    <td width="20" align="center">:</td>
			    <td><?= nl2br($sComments) ?></td>
			  </tr>
		    </table>
