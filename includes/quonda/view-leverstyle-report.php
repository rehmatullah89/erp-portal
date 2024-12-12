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
	switch ($sAuditResult)
	{
		case "P" : $sAuditResult = "Pass"; break;
		case "F" : $sAuditResult = "Fail"; break;
		case "H" : $sAuditResult = "Hold"; break;
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
		if ($objDb->getField($i, 'nature') == 0)
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
				  <td><?= (($objDb->getField($i, 'nature') == 1) ?"Major" : ($objDb->getField($i, 'nature') == 0?"Minor":"Critical")) ?></td>
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
<h2>Product Description, Style & Colour</h2>
<?
$sSQL = "SELECT * FROM tbl_leverstyle_inspections WHERE audit_id='$Id'";
$objDb->query($sSQL);

$CApprovedSample    = $objDb->getField(0, "approved_sample");
$PrintingSwatche   = $objDb->getField(0, "printing_swatche");
$ProductLabels     = $objDb->getField(0, "product_labels");
$FitApprovedSample = $objDb->getField(0, "fit_approved_sample");
$MeasurementSheet  = $objDb->getField(0, "measurement_sheet");
$ProductRemarks    = $objDb->getField(0, "product_remarks");

?>
<table border="0" cellpadding="3" cellspacing="0" width="100%">
    <tr>
        <td width="230">Conform to Approved PP Sample</td>
        <td width="20" align="center">:</td>
        <td><?=$CApprovedSample?></td>
    </tr>
    <tr>
        <td width="230">Conform of Printing/Colour Swatche</td>
        <td width="20" align="center">:</td>
        <td><?=$PrintingSwatche?></td>
    </tr>
    <tr>
        <td width="230">Conform of all Sewn-in/Product Labels</td>
        <td width="20" align="center">:</td>
        <td><?=$ProductLabels?></td>
    </tr>
    <tr>
        <td width="230">Fit conform to Approved PP sample</td>
        <td width="20" align="center">:</td>
        <td><?=$FitApprovedSample?></td>
    </tr>
    <tr valign="top">
        <td width="230">Remarks</td>
        <td width="20" align="center">:</td>
        <td><?= $ProductRemarks ?></td>
    </tr>
</table>                                
		    <br />
		    <h2>Status & Comments</h2>

		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
			    <td width="140">Approved Sample</td>
			    <td width="20" align="center">:</td>
			    <td><?= $sApprovedSample ?></td>
			  </tr>

			  <tr>
			    <td>Shipping Mark</td>
			    <td align="center">:</td>
			    <td><?= (($sShippingMark == "Y") ? "Yes" : "No") ?></td>
			  </tr>

			  <tr>
			    <td>Packing Check</td>
			    <td align="center">:</td>
			    <td><?= (($sPackingCheck == "Y") ? "Yes" : "No") ?></td>
			  </tr>

			  <tr>
			    <td>Carton Size</td>
			    <td align="center">:</td>
			    <td><?= (float)$iLength ?> x <?= (float)$iWidth ?> x <?= (float)$iHeight ?> <?= $sUnit ?></td>
			  </tr>
<?
	if ($iReportId != 12)
	{
?>
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
<?
	}
?>

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
