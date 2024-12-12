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
			    <td width="180">Vendor</td>
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

			  <tr>
			   <td>Color</td>
			    <td align="center">:</td>
			    <td><?= $sColors ?></td>
			  </tr>

			  <tr>
				<td>Description</td>
				<td align="center">:</td>
				<td><?= $sDescription ?></td>
			  </tr>

			  <tr>
			    <td>Batch Size</td>
			    <td align="center">:</td>
			    <td><?= $sBatchSize ?></td>
			  </tr>

<?
	$sSQL = "SELECT * FROM tbl_ms_qa_reports WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$sSeries        = $objDb->getField(0, 'series');
	$sDepartment    = $objDb->getField(0, 'department');
	$iBigProducts   = $objDb->getField(0, 'big_products');
	$sBigSize       = $objDb->getField(0, 'big_size');
	$iSmallProducts = $objDb->getField(0, 'small_products');
	$sSmallSize     = $objDb->getField(0, 'small_size');
	$sAction        = $objDb->getField(0, 'action');
?>
			  <tr>
			    <td>Series</td>
			    <td align="center">:</td>
			    <td><?= $sSeries ?></td>
			  </tr>

			  <tr>
			    <td>Department</td>
			    <td align="center">:</td>
			    <td><?= $sDepartment ?></td>
			  </tr>

			  <tr>
			    <td>% of Packed</td>
			    <td align="center">:</td>
			    <td><?= $fPackedPercent ?></td>
			  </tr>

			  <tr>
			    <td>Sample Size</td>
			    <td align="center">:</td>
			    <td><?= $iTotalGmts ?></td>
			  </tr>

			  <tr>
			    <td>Total Faulty Products</td>
			    <td align="center">:</td>
			    <td><?= $iGmtsDefective ?></td>
			  </tr>

			  <tr>
			    <td>Faulty Products Allowed (AQL)</td>
			    <td align="center">:</td>
			    <td><?= $iMaxDefects ?></td>
			  </tr>

			  <tr>
				<td>Re-Screen Qty</td>
				<td align="center">:</td>
				<td><?= $iReScreenQty ?></td>
			  </tr>
		    </table>

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
				  <td width="70"><b>Nature</b></td>
			    </tr>

<?
	$sSQL = "SELECT * FROM tbl_qa_report_defects WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for($i = 0; $i < $iCount; $i ++)
	{
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
				  <td><?= $sNature ?></td>
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

			<br />

			<table border="1" bordercolor="#ffffff" cellpadding="4" cellspacing="0" width="100%">
			  <tr class="sdRowHeader">
				<td width="50%" align="center"><b>Measurements</b></td>
				<td width="20%" align="center"><b>Number of Products</b></td>
				<td width="30%" align="center"><b>Size affected</b></td>
			  </tr>

			  <tr class="sdRowColor">
				<td>M+ &nbsp; Critical measurements out of tolerance, too big</td>
				<td align="center"><?= $iBigProducts ?></td>
				<td><?= $sBigSize ?></td>
			  </tr>

			  <tr class="sdRowColor">
				<td>M- &nbsp; Critical measurements out of tolerance, too small</td>
				<td align="center"><?= $iSmallProducts ?></td>
				<td><?= $sSmallSize ?></td>
			  </tr>
			</table>

		    <br />
		    <h2>Status & Comments</h2>

		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
<?
	switch ($sAuditResult)
	{
		case "P" : $sAuditResult = "Pass"; break;
		case "F" : $sAuditResult = "Fail"; break;
		case "H" : $sAuditResult = "Hold"; break;
	}
?>
			  <tr>
			    <td width="120">Audit Result</td>
			    <td width="20" align="center">:</td>
			    <td><?= $sAuditResult ?></td>
			  </tr>

			  <tr>
			    <td>Cartons Shipped</td>
			    <td align="center">:</td>
			    <td><?= $fCartonsShipped ?></td>
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

			  <tr valign="top">
			    <td>Action</td>
			    <td align="center">:</td>
			    <td><?= nl2br($sAction) ?></td>
			  </tr>
		    </table>
