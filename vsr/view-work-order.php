<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Id = IO::intValue('Id');

	$sSQL = "SELECT * FROM tbl_vsr2 WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sWorkOrder = $objDb->getField(0, "work_order_no");
		$iVendor    = $objDb->getField(0, "vendor_id");
		$iBrand     = $objDb->getField(0, "brand_id");
		$iSeason    = $objDb->getField(0, "season_id");
		$sPos       = $objDb->getField(0, "pos");
		$sStyles    = $objDb->getField(0, "styles");
		$sColors    = $objDb->getField(0, "colors");

		$iPos    = @explode(",", $sPos);
		$iStyles = @explode(",", $sStyles);
		$iColors = @explode(",", $sColors);

		$sPosList     = getList("tbl_po po, tbl_po_colors pc", "po.id", "CONCAT(po.order_no, ' ', po.order_status)", "po.id=pc.po_id AND po.vendor_id='$iVendor' AND po.brand_id='$iBrand' AND po.status!='C' AND po.order_nature='B' AND pc.style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id='$iBrand' AND sub_season_id='$iSeason')");
		$sStylesList  = getList("tbl_styles", "id", "style", "sub_brand_id='$iBrand' AND sub_season_id='$iSeason'");

		$sAllPos    = "";
		$sAllStyles = "";

		for ($i = 0; $i < count($iPos); $i ++)
			$sAllPos .= ((($i > 0) ? ", " : "").trim($sPosList[$iPos[$i]]));

		for ($i = 0; $i < count($iStyles); $i ++)
			$sAllStyles .= ((($i > 0) ? ", " : "").$sStylesList[$iStyles[$i]]);


		$iParentBrand      = getDbValue("parent_id", "tbl_brands", "id='$iBrand'");
		$sEtdRequired      = getDbValue("MIN(vsr_etd_required)", "tbl_po_colors", "FIND_IN_SET(id, '$sColors')");
		$iQuantity         = getDbValue("SUM(quantity)", "tbl_po_quantities", "FIND_IN_SET(color_id, '$sColors')");
		$sDestinationsList = getList("tbl_destinations", "id", "destination", "brand_id='$iParentBrand'");
	}


	$iCategory = getDbValue("category_id", "tbl_styles", "id='{$iStyles[0]}'");
	$sStages   = getDbValue("stages", "tbl_style_categories", "id='$iCategory'");
	$iStages   = @explode(",", $sStages);

	$sStagesList = getList("tbl_production_stages", "id", "title", "", "position");
	$sStagesType = getList("tbl_production_stages", "id", "type", "", "position");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
</head>

<body>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body">
	  <h2>Vendor Status Report</h2>

	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	    <tr bgcolor="#ffffff">
		  <td width="100%">

			<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
				<td width="90">Vendor</td>
				<td width="20" align="center">:</td>
				<td><?= getDbValue("vendor", "tbl_vendors", "id='$iVendor'") ?></td>
			  </tr>

			  <tr>
				<td>Brand</td>
				<td align="center">:</td>
				<td><?= getDbValue("brand", "tbl_brands", "id='$iBrand'") ?></td>
			  </tr>

			  <tr>
				<td>Season</td>
				<td align="center">:</td>
				<td><?= getDbValue("season", "tbl_seasons", "id='$iSeason'") ?></td>
			  </tr>

			  <tr>
				<td>Work Order</td>
				<td align="center">:</td>
				<td><?= $sWorkOrder ?></td>
			  </tr>

			  <tr>
				<td>POs</td>
				<td align="center">:</td>
				<td><?= $sAllPos ?></td>
			  </tr>

			  <tr>
				<td>Styles</td>
				<td align="center">:</td>
				<td><?= $sAllStyles ?></td>
			  </tr>

			  <tr>
				<td>ETD Required</td>
				<td align="center">:</td>
				<td><?= formatDate($sEtdRequired) ?></td>
			  </tr>

			  <tr>
				<td>Quantity</td>
				<td align="center">:</td>
				<td><?= formatNumber($iQuantity, false) ?></td>
			  </tr>
			</table>

			<br />
			<h2 style="margin-bottom:0px;">Work Order Details</h2>

			<div style="overflow:auto; height:352px;">
			  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				<tr class="sdRowHeader">
				  <td width="140" rowspan="2"><b>PO</b></td>
				  <td width="140" rowspan="2"><b>Style</b></td>
				  <td width="250" rowspan="2"><b>Color</b></td>
				  <td width="80" rowspan="2" align="center"><b>Price ($)</b></td>
				  <td width="110" rowspan="2" align="center"><b>ETD Required</b></td>
				  <td width="180" rowspan="2"><b>Destination</b></td>
				  <td width="100" rowspan="2"><b>PO Ref</b></td>
				  <td width="150" rowspan="2"><b>Fabric</b></td>
				  <td width="100" rowspan="2"><b>VSL Date</b></td>
				  <td width="100" rowspan="2"><b>Po Issue Date</b></td>
				  <td width="150" rowspan="2"><b>Notes</b></td>
<?
	$sSQL = "SELECT DISTINCT(pq.size_id), s.size
			 FROM tbl_po_colors pc, tbl_po_quantities pq, tbl_sizes s, tbl_vsr_details vd
			 WHERE pc.id=pq.color_id AND pc.po_id=pq.po_id AND vd.po_id=pc.po_id AND vd.color_id=pc.id AND vd.work_order_id='$Id' AND s.id=pq.size_id
			 ORDER BY s.position";
	$objDb->query($sSQL);

	$iCount     = $objDb->getCount( );
	$sSizesList =  array( );

	for ($i = 0; $i < $iCount; $i ++)
		$sSizesList[$objDb->getField($i, 0)] = $objDb->getField($i, 1);


	foreach ($sSizesList as $iSize => $sSize)
	{
?>
				  <td align="center" rowspan="2" width="50"><b><?= $sSize ?></b></td>
<?
	}
?>
				  <td width="50" rowspan="2" align="center"><b>Total</b></td>
<?
	foreach ($sStagesList as $iStage => $sStage)
	{
?>
				  <td width="300" colspan="3" align="center"><b><?= $sStage ?></b></td>
<?
	}
?>
				  <td width="100" rowspan="2" align="center"><b>Final Audit</b></td>
				  <td width="70" rowspan="2" align="center"><b>Ship Qty</b></td>
				  <td width="70" rowspan="2" align="center"><b>Balance</b></td>
				  <td width="300" rowspan="2"><b>Comments</b></td>
				</tr>

				<tr class="sdRowHeader">
<?
	foreach ($sStagesList as $iStage => $sStage)
	{
?>
				  <td width="100" align="center">Start Date</td>
				  <td width="100" align="center">End Date</td>
				  <td width="100" align="center">Completed</td>
<?
	}
?>
				</tr>

<?
	$sSQL = "SELECT pc.*, vd.*
	         FROM tbl_po_colors pc, tbl_vsr_details vd
	         WHERE pc.po_id=vd.po_id AND pc.style_id=vd.style_id AND pc.id=vd.color_id AND vd.work_order_id='$Id'
	         ORDER BY pc.po_id, pc.vsr_etd_required";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iColor       = $objDb->getField($i, 'vd.color_id');
		$iPo          = $objDb->getField($i, 'vd.po_id');
		$sColor       = $objDb->getField($i, 'pc.color');
		$fPrice       = $objDb->getField($i, 'pc.vsr_price');
		$iStyle       = $objDb->getField($i, 'pc.style_id');
		$iDestination = $objDb->getField($i, 'pc.destination_id');
		$sEtdRequired = $objDb->getField($i, 'pc.vsr_etd_required');
		$sFinalAudit  = $objDb->getField($i, 'vd.final_date');
		$iShipQty     = $objDb->getField($i, 'vd.ship_qty');
		$sComments    = $objDb->getField($i, 'vd.comments');
		$sPoRef       = $objDb->getField($i, 'vd.po_ref_no');
		$sFabric      = $objDb->getField($i, 'vd.fabric');
		$sVslDate     = $objDb->getField($i, 'vd.vsl_date');
		$sPoIssueDate = $objDb->getField($i, 'vd.po_issue_date');
		$sNotes       = $objDb->getField($i, 'vd.notes');
?>
				<tr class="sdRowColor" valign="top">
				  <td><?= $sPosList[$iPo] ?></td>
				  <td><?= $sStylesList[$iStyle] ?></td>
				  <td><?= $sColor ?></td>
				  <td align="center"><?= formatNumber($fPrice) ?></td>
				  <td align="center"><?= formatDate($sEtdRequired) ?></td>
				  <td><?= $sDestinationsList[$iDestination] ?></td>
				  <td><?= $sPoRef ?></td>
				  <td><?= $sFabric ?></td>
				  <td align="center"><?= formatDate($sVslDate) ?></td>
				  <td align="center"><?= formatDate($sPoIssueDate) ?></td>
				  <td><?= $sNotes ?></td>
<?
		$iSubTotal = 0;

		foreach ($sSizesList as $iSize => $sSize)
		{
			$iQuantity = getDbValue("quantity", "tbl_po_quantities", "po_id='$iPo' AND color_id='$iColor' AND size_id='$iSize'");
?>
				  <td align="center"><?= (float)$iQuantity ?></td>
<?
			$iSubTotal += $iQuantity;
		}
?>
		         <td align="center"><?= formatNumber($iSubTotal, false) ?></td>
<?
		foreach ($sStagesList as $iStage => $sStage)
		{
			$sSQL = "SELECT start_date, end_date, completed FROM tbl_vsr_data WHERE work_order_id='$Id' AND color_id='$iColor' AND stage_id='$iStage'";
			$objDb2->query($sSQL);

			$sStartDate = $objDb2->getField(0, "start_date");
			$sEndDate   = $objDb2->getField(0, "end_date");
			$iCompleted = $objDb2->getField(0, "completed");
?>
				  <td align="center"><?= formatDate($sStartDate) ?></td>
				  <td align="center"><?= formatDate($sEndDate) ?></td>
				  <td align="center"><?= formatNumber($iCompleted, false) ?> <?= (($sStagesType[$iStage] == "P") ? "%" : "Pcs") ?></td>
<?
		}
?>
				  <td align="center"><?= formatDate($sFinalAudit) ?></td>
				  <td align="center"><?= formatNumber($iShipQty, false) ?></td>
				  <td align="center"><?= formatNumber(($iSubTotal - $iShipQty), false) ?></td>
				  <td><?= $sComments ?></td>
				</tr>
<?
	}
?>
			  </table>
			</div>

		  </td>
	    </tr>
	  </table>

	  <br style="line-height:2px;" />
    </div>
<!--  Body Section Ends Here  -->

  </div>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>
