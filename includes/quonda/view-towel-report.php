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

			 <tr>
			    <td>Inspect Type</td>
			    <td align="center">:</td>
			    <td><?= ($sInspectionType == 'G')?'GREIGE':($sInspectionType == 'P')?'DYED / PRINTED':'OTHER' ?></td>
			  </tr>

		     <tr>
			   <td>Maker</td>
			    <td align="center">:</td>
			    <td><?= $sMaker ?></td>
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
			   <td>Sizes / Ranges</td>
			    <td align="center">:</td>
			    <td><?= $sSizeTitles ?></td>
			  </tr>
		    </table>

		    <br />
		    <h2 style="margin:0px;">Defects Details</h2>

		    <div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
			  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			    <tr class="sdRowHeader">
					<td width="20" align="center" rowspan="2"><b>#</b></td>
					<td width="50" align="center" rowspan="2"><b>Lot No</b></td>
					<td width="50" align="center" rowspan="2"><b>Roll No</b></td>
					<td width="50" align="center" rowspan="2"><b>Pcs Width</b></td>
					<td width="50" align="center" rowspan="2"><b>Ticket Pcs</b></td>
					<td width="50" align="center" rowspan="2"><b>Actual Pcs</b></td>
                                        <td width="50" align="center" rowspan="2"><b>Sampled Pcs</b></td>
                                        <td width="250" colspan="5" align="center"><b><i>Defects</i></b></td>
                                        <td width="50" align="center" rowspan="2"><b>Allowed Defects</b></td>
					<td width="50" align="center" rowspan="2"><b>Defective Pcs</b></td>
					<td width="50" align="center" rowspan="2"><b>Result</b></td>
			      </tr>
				  
				  <tr class="sdRowHeader">
					<td width="50" align="center"><b>Holes</b></td>
					<td width="50" align="center"><b>Slubs</b></td>
					<td width="50" align="center"><b>Stains</b></td>
					<td width="50" align="center"><b>Fly</b></td>
					<td width="50" align="center"><b>Other</b></td>
				   </tr>

<?
	$iDefects = 0;
    $sClass   = array("evenRow", "oddRow");
	
	$sSQL = "SELECT * FROM tbl_towel_report_defects WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for($i = 0; $i < $iCount; $i ++)
	{
                $iActualPcs = $objDb->getField($i, 'actual_meters');
                $iSampleSize     = 0;
                $iAllowedDefects = 0;
                
                if( $iActualPcs >= 2 && $iActualPcs <= 8 ){
                    $iSampleSize     = 2;
                    $iAllowedDefects = 0;
                }
                else if( $iActualPcs >= 9 && $iActualPcs <= 15 ){
                    $iSampleSize     = 3;
                    $iAllowedDefects = 0;
                }
                else if( $iActualPcs >= 16 && $iActualPcs <= 25 ){
                    $iSampleSize     = 5;
                    $iAllowedDefects = 0;
                }
                else if( $iActualPcs >= 26 && $iActualPcs <= 50 ){
                    $iSampleSize     = 8;
                    $iAllowedDefects = 0;
                }
                else if( $iActualPcs >= 51 && $iActualPcs <= 90 ){
                    $iSampleSize     = 13;
                    $iAllowedDefects = 0;
                }
                else if( $iActualPcs >= 91 && $iActualPcs <= 150 ){
                    $iSampleSize     = 20;
                    $iAllowedDefects = 1;
                }
                else if( $iActualPcs >= 151 && $iActualPcs <= 280 ){
                    $iSampleSize     = 32;
                    $iAllowedDefects = 2;
                }
                else if( $iActualPcs >= 281 && $iActualPcs <= 500 ){
                    $iSampleSize     = 50;
                    $iAllowedDefects = 3;
                }
                else if( $iActualPcs >= 501 && $iActualPcs <= 1200 ){
                    $iSampleSize     = 80;
                    $iAllowedDefects = 5;
                }
                else if( $iActualPcs >= 1201 && $iActualPcs <= 3200 ){
                    $iSampleSize     = 125;
                    $iAllowedDefects = 7;
                }
                else if( $iActualPcs >= 3201 && $iActualPcs <= 10000 ){
                    $iSampleSize     = 200;
                    $iAllowedDefects = 10;
                }
                else if( $iActualPcs >= 10001 && $iActualPcs <= 35000 ){
                    $iSampleSize     = 315;
                    $iAllowedDefects = 14;
                }
                else if( $iActualPcs >= 35001 && $iActualPcs <= 150000 ){
                    $iSampleSize     = 500;
                    $iAllowedDefects = 21;
                }
                else if( $iActualPcs >= 150001 && $iActualPcs <= 500000 ){
                    $iSampleSize     = 800;
                    $iAllowedDefects = 21;
                }
                else if( $iActualPcs >= 500000 ){
                    $iSampleSize     = 1250;
                    $iAllowedDefects = 21;
                }
                
                $iSubTotal      = $objDb->getField($i, 'holes') + $objDb->getField($i, 'slubs') + $objDb->getField($i, 'stains') + $objDb->getField($i, 'fly') + $objDb->getField($i, 'other');
		$iSubTotal      = ceil(($iSubTotal*3600)/($objDb->getField($i, 'width') * $objDb->getField($i, 'ticket_meters')));
		
                $Result = 'Pass';
                $iDefectivePcs = $objDb->getField($i, 'allowable_defects');
                if($iDefectivePcs > $iAllowedDefects)
                    $Result = 'Fail';
?>

			  <tr class="<?= $sClass[($i % 2)] ?>">
				<td width="20" align="center" class="serial"><?= ($i + 1) ?></td>
                                <td width="50" align="center"><?= $objDb->getField($i, 'lot_no') ?></td>
				<td width="50" align="center"><?= $objDb->getField($i, 'roll_no') ?></td>
				<td width="50" align="center"><?= $objDb->getField($i, 'width') ?></td>
				<td width="50" align="center"><?= $objDb->getField($i, 'ticket_meters') ?></td>
				<td width="50" align="center"><?= $objDb->getField($i, 'actual_meters') ?></td>
                                <td width="50" align="center"><?=  $iSampleSize ?></td>
                                <td width="50" align="center"><?= $objDb->getField($i, 'holes') ?></td>
				<td width="50" align="center"><?= $objDb->getField($i, 'slubs') ?></td>
				<td width="50" align="center"><?= $objDb->getField($i, 'stains') ?></td>
				<td width="50" align="center"><?= $objDb->getField($i, 'fly') ?></td>
				<td width="50" align="center"><?= $objDb->getField($i, 'other') ?></td>
				<td width="50" align="center"><?=  $iAllowedDefects ?></td>
                                <td width="50" align="center"><?=  $iDefectivePcs ?></td>
                                <td width="50" align="center"><?= $Result ?></td>
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

//	if ($iGmtsDefective == 0)
//		$iGmtsDefective = $iDefects;
?>
			  </table>
		    </div>

		    <br />
		    <h2>Status & Comments</h2>

		    <table border="0" cellpadding="3" cellspacing="0">
			  <tr valign="top">
			    <td>QA Comments</td>
			    <td align="center">:</td>
			    <td><?= nl2br($sComments) ?></td>
			  </tr>
		    </table>
