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

	$sSQL = "SELECT * FROM tbl_hybrid_apparel_reports WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$TotalCtns      = $objDb->getField(0, "total_ctns");
		$Fabric         = $objDb->getField(0, "fabric");
		$Content        = $objDb->getField(0, "content");
		$Weight         = $objDb->getField(0, "weight");
		$Rib            = $objDb->getField(0, "rib");
		$LabelSize      = $objDb->getField(0, "label_size");
		$Thread         = $objDb->getField(0, "thread");
		$MeasurementResult      = $objDb->getField(0, "measurement_result");
		$MeasurementRemarks     = $objDb->getField(0, "measurement_remarks");
	}
?>
		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
			    <td width="140">Vendor</td>
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
				<td>Style</td>
				<td align="center">:</td>
				<td><?= getDbValue("style", "tbl_styles", "id='$iStyle'") ?></td>
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
	switch ($sAuditResult)
	{
		case "P" : $sAuditResult = "Pass"; break;
		case "F" : $sAuditResult = "Fail"; break;
		case "H" : $sAuditResult = "Hold"; break;
		case "R" : $sAuditResult = "Re-Inspection"; break;
	}
?>
			  <tr>
			    <td>Audit Result</td>
			    <td align="center">:</td>
			    <td><?= $sAuditResult ?></td>
			  </tr>

<?
	switch ($sAuditType)
	{
		case "B"  : $sAuditType  = "Bulk"; break;
		case "BG" : $sAuditType = "B-Grade"; break;
		case "SS" : $sAuditType = "Sales Sample"; break;
	}


	if ($iReportId != 8)
	{
?>
			  <tr>
			    <td>QA Type</td>
			    <td align="center">:</td>
			    <td><?= $sAuditType ?></td>
			  </tr>
<?
	}
?>

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
			   <td>Sizes</td>
			    <td align="center">:</td>
			    <td><?= $sSizeTitles ?></td>
			  </tr>
		    </table>

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
			default : $sNature = "N/A";
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
		if ($objDb->getField($i, 'cap') != "")
		{
?>
			    <tr class="sdRowColor">
				  <td align="center">CAP</td>
				  <td colspan="4"><?= $objDb->getField($i, 'cap') ?></td>
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
<?
    // Measurement Sheet starts here
        $iSizes  = @explode(",", $Sizes);
        $sColors = @explode(",", $sColors);
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


		$iColor ++;
	}
?>
				<br />
                                <h2>Measurement Result</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="75">Result<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td>
                                            <?= ($MeasurementResult == 'P'?'Pass':($MeasurementResult == 'F'?'Fail':'Pending')) ?>
					</td>
				  </tr>
                                  
				  <tr valign="top">
					<td>Remarks</td>
					<td align="center">:</td>
					<td><?= $MeasurementRemarks ?></td>
				  </tr>
				</table>

				<br />
		    <br />
		    <h2>Status & Comments</h2>

		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
                            <tr>
                                <td width="140">Total Ctn</td>
                                <td width="20" align="center">:</td>
                                <td><?= $TotalCtns ?></td>
                            </tr>

                            <tr>
                                <td width="140">Fabric</td>
                                <td width="20" align="center">:</td>
                                <td><?= $Fabric ?></td>
                            </tr>

                            <tr>
                                <td width="140">Content</td>
                                <td width="20" align="center">:</td>
                                <td><?= $Content ?></td>
                            </tr>

                            <tr>
                                <td width="140">Weight</td>
                                <td width="20" align="center">:</td>
                                <td><?= $Weight ?></td>
                            </tr>

                            <tr>
                                <td width="140">Rib</td>
                                <td width="20" align="center">:</td>
                                <td><?= $Rib ?></td>
                            </tr>

                            <tr>
                                <td width="140">Label Size</td>
                                <td width="20" align="center">:</td>
                                <td><?= $LabelSize ?></td>
                            </tr>

                            <tr>
                                <td width="140">Thread</td>
                                <td width="20" align="center">:</td>
                                <td><?= $Thread ?></td>
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
