<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  MATRIX Customer Portal                                                                   **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.apparelco.com                                                              **
	**                                                                                           **
	**  Copyright 2008-15 (C) Matrix Sourcing                                                    **
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
			    <td width="150">Vendor</td>
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

			  <tr valign="top">
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

			  <tr>
				<td>Dye Lot #</td>
				<td align="center">:</td>
				<td><?= $sDyeLotNo ?></td>
			  </tr>

			  <tr>
				<td>Acceptable Points Woven</td>
				<td align="center">:</td>
				<td><?= $sAcceptablePointsWoven ?></td>
			  </tr>

			  <tr>
				<td>Cutable Fabric Width</td>
				<td align="center">:</td>
				<td><?= $sCutableFabricWidth ?></td>
			  </tr>

			  <tr>
			    <td>Stock Status</td>
			    <td align="center">:</td>
			    <td><?= $sStockStatus ?></td>
			  </tr>

			  <tr>
				<td>Rolls Inspected</td>
				<td align="center">:</td>
				<td><?= $iRollsInspected ?></td>
			  </tr>
		    </table>

			<br />
			<h2 style="margin-bottom:0px;">Rolls / Panel Information</h2>

			<table border="1" bordercolor="#ffffff" cellpadding="4" cellspacing="0" width="100%">
			  <tr class="sdRowHeader">
				<td width="20"><b>#</b></td>
				<td><b>Roll No</b></td>
				<td width="50"><b>Ref-1</b></td>
				<td width="50"><b>Given</b></td>
				<td width="60"><b>Actual</b></td>
				<td width="50"><b>Ref-2</b></td>
				<td width="50"><b>Given</b></td>
				<td width="60"><b>Actual</b></td>
				<td width="50"><b>Ref-3</b></td>
				<td width="50"><b>Given</b></td>
				<td width="50"><b>Actual</b></td>
			  </tr>
<?
	$sSQL = "SELECT * FROM tbl_gf_rolls_info WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
?>

			  <tr class="sdRowColor">
				<td><?= ($i + 1) ?></td>
				<td><?= $objDb->getField($i,  'roll_no') ?></td>
				<td><?= $objDb->getField($i,  'ref_1') ?></td>
				<td><?= $objDb->getField($i,  'given_1') ?></td>
				<td><?= $objDb->getField($i,  'actual_1') ?></td>
				<td><?= $objDb->getField($i,  'ref_2') ?></td>
				<td><?= $objDb->getField($i,  'given_2') ?></td>
				<td><?= $objDb->getField($i,  'actual_2') ?></td>
				<td><?= $objDb->getField($i,  'ref_3') ?></td>
				<td><?= $objDb->getField($i,  'given_3') ?></td>
				<td><?= $objDb->getField($i,  'actual_3') ?></td>
			  </tr>
<?
	}
?>
			</table>

		    <h2 style="margin:0px;">Defects Details</h2>

		    <div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
			  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			    <tr class="sdRowHeader">
				  <td width="50" align="center"><b>#</b></td>
				  <td width="80" align="center"><b>Roll #</b></td>
				  <td width="80" align="center"><b>Panel #</b></td>
				  <td><b>Code - Check Points</b></td>
				  <td width="80" align="center"><b>Grade</b></td>
				  <td width="100" align="center"><b>Defects</b></td>
			    </tr>

<?
	$iDefects = 0;

	$sSQL = "SELECT * FROM tbl_gf_report_defects WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for($i = 0; $i < $iCount; $i ++)
	{
		$iDefects += $objDb->getField($i, 'defects');

		$sSQL = ("SELECT code, defect FROM tbl_defect_codes WHERE id='".$objDb->getField($i, 'code_id')."'");
		$objDb2->query($sSQL);
?>

			    <tr class="sdRowColor">
				  <td align="center"><?= ($i + 1) ?></td>
				  <td align="center"><?= $objDb->getField($i, 'roll') ?></td>
				  <td align="center"><?= $objDb->getField($i, 'panel') ?></td>
				  <td><?= $objDb2->getField(0, 0) ?> - <?= $objDb2->getField(0, 1) ?></td>
				  <td align="center"><?= $objDb->getField($i, 'grade') ?></td>
				  <td align="center"><?= $objDb->getField($i, 'defects') ?></td>
			    </tr>
<?
	}

	if ($iCount == 0)
	{
?>

			    <tr class="sdRowColor">
				  <td colspan="4" align="center">No Defect Found!</td>
			    </tr>
<?
	}
?>
			  </table>
			</div>

			<h2>Fabric Inspection Standard Check-List</h2>

<?
	$sSQL = "SELECT * FROM tbl_gf_inspection_checklist WHERE audit_id='$Id'";
	$objDb->query($sSQL);
?>
			<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
				<td width="140">Color Match to Standard</td>
				<td width="20" align="center">:</td>
				<td width="100"><?= (($objDb->getField(0, 'color_match') == "A") ? "Accepted" : (($objDb->getField(0, 'color_match') == "N") ? "Not Applicable" : "Rejected")) ?></td>
				<td width="50">Remarks</td>
				<td width="20" align="center">:</td>
				<td><?= $objDb->getField(0, 'color_match_remarks') ?></td>
			  </tr>

			  <tr>
				<td>Shading</td>
				<td align="center">:</td>
				<td><?= (($objDb->getField(0, 'shading') == "A") ? "Accepted" : (($objDb->getField(0, 'shading') == "N") ? "Not Applicable" : "Rejected")) ?></td>
				<td>Remarks</td>
				<td align="center">:</td>
				<td><?= $objDb->getField(0, 'shading_remarks') ?></td>
			  </tr>

			  <tr>
				<td>Hand Feel</td>
				<td align="center">:</td>
				<td><?= (($objDb->getField(0, 'hand_feel') == "A") ? "Accepted" : (($objDb->getField(0, 'hand_feel') == "N") ? "Not Applicable" : "Rejected")) ?></td>
				<td>Remarks</td>
				<td align="center">:</td>
				<td><?= $objDb->getField(0, 'hand_feel_remarks') ?></td>
			  </tr>

			  <tr>
				<td>Lab Testing</td>
				<td align="center">:</td>
				<td><?= (($objDb->getField(0, 'lab_testing') == "A") ? "Accepted" : (($objDb->getField(0, 'lab_testing') == "P") ? "Pending" : "Rejected")) ?></td>
				<td>Remarks</td>
				<td align="center">:</td>
				<td><?= $objDb->getField(0, 'lab_testing_remarks') ?></td>
			  </tr>

			  <tr>
				<td>Fabric Width</td>
				<td align="center">:</td>
				<td colspan="4"><?= $iFabricWidth ?></td>
			  </tr>
			</table>

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
			  </tr>

			  <tr>
				<td>Ship Qty</td>
				<td align="center">:</td>
				<td><?= $iShipQty ?></td>
			  </tr>

			  <tr>
				<td>Re-Screen Qty</td>
				<td align="center">:</td>
				<td><?= $iReScreenQty ?></td>
			  </tr>

			  <tr>
				<td>No of Rolls</td>
				<td align="center">:</td>
				<td><?= $iRolls ?></td>
			  </tr>

			  <tr>
				<td>Deviation</td>
				<td align="center">:</td>
				<td><?= @round(( ($iShipQty / $iOrderQty) * 100), 2) ?>%</td>
			  </tr>

			  <tr>
				<td>DHU</td>
				<td align="center">:</td>
				<td><?= formatNumber($fDhu) ?>%</td>
			  </tr>
			</table>

		    <br />
		    <h2>Status & Comments</h2>

		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
				<td width="140">Knitted (%)</td>
				<td width="20" align="center">:</td>
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
			    <td width="140">QA Comments</td>
			    <td width="20" align="center">:</td>
			    <td><?= nl2br($sComments) ?></td>
			  </tr>
		    </table>
