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
					<td width="50" align="center" rowspan="2"><b>Width</b></td>
					<td width="50" align="center" rowspan="2"><b>Ticket Yards</b></td>
					<td width="50" align="center" rowspan="2"><b>Actual Yards</b></td>
					<td width="250" colspan="5" align="center"><b><i>Defects</i></b></td>
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
	
	$sSQL = "SELECT * FROM tbl_tnc_report_defects WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for($i = 0; $i < $iCount; $i ++)
	{
?>

			  <tr class="<?= $sClass[($i % 2)] ?>">
				<td width="20" align="center" class="serial"><?= ($i + 1) ?></td>
                <td width="50" align="center"><?= $objDb->getField($i, 'lot_no') ?></td>
				<td width="50" align="center"><?= $objDb->getField($i, 'roll_no') ?></td>
				<td width="50" align="center"><?= $objDb->getField($i, 'width') ?></td>
				<td width="50" align="center"><?= $objDb->getField($i, 'ticket_meters') ?></td>
				<td width="50" align="center"><?= $objDb->getField($i, 'actual_meters') ?></td>
				<td width="50" align="center"><?= $objDb->getField($i, 'holes') ?></td>
				<td width="50" align="center"><?= $objDb->getField($i, 'slubs') ?></td>
				<td width="50" align="center"><?= $objDb->getField($i, 'stains') ?></td>
				<td width="50" align="center"><?= $objDb->getField($i, 'fly') ?></td>
				<td width="50" align="center"><?= $objDb->getField($i, 'other') ?></td>
				<td width="50" align="center"><?= ($objDb->getField($i, 'result') == 'P')?'Pass':'Fail' ?></td>
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
