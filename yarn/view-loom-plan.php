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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Id = IO::intValue('Id');

	$sSQL = "SELECT * FROM tbl_loom_plan WHERE po_id='$Id'";
	$objDb->query($sSQL);

	$sFromDate = $objDb->getField(0, 'from_date');
	$sToDate   = $objDb->getField(0, 'to_date');
	$sLooms    = $objDb->getField(0, 'looms');

	$iLooms     = @explode(",", $sLooms);
	$iVendor    = getDbValue("vendor_id", "tbl_po", "id='$Id'");
	$sLoomsList = getList("tbl_looms", "id", "loom", "vendor_id='$iVendor'");


	$iOrderQty = getDbValue("quantity", "tbl_po", "id='$Id'");
	$iPlanned  = getDbValue("SUM(production)", "tbl_loom_plan_details", "po_id='$Id'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
</head>

<body>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body" style="min-height:594px; height:594px;">
	  <h2>Loom Plan</h2>

	  <table border="0" cellpadding="3" cellspacing="0" width="100%" bgcolor="#ffffff">
	    <tr>
		  <td width="60">PO #</td>
		  <td width="20" align="center">:</td>
		  <td><?= getDbValue("CONCAT(order_no, ' ', order_status)", "tbl_po", "id='$Id'") ?></td>
	    </tr>

	    <tr>
		  <td>D #</td>
		  <td align="center">:</td>
		  <td><?= getDbValue("style", "tbl_styles", "id IN (SELECT styles FROM tbl_po WHERE id='$Id')") ?></td>
	    </tr>

	    <tr>
		  <td>Vendor</td>
		  <td align="center">:</td>
		  <td><?= getDbValue("vendor", "tbl_vendors", "id='$iVendor'") ?></td>
	    </tr>

	    <tr>
		  <td>Quantity</td>
		  <td align="center">:</td>
		  <td><?= formatNumber($iOrderQty, false) ?> &nbsp; <?= (($iPlanned < $iOrderQty) ? "<span style='color:#ff0000;'>(Incomplete Loom Plan - Short Qty: ".formatNumber(($iOrderQty - $iPlanned), false).")</span>" : "") ?></td>
	    </tr>

	    <tr>
		  <td colspan="3"><h3 style="margin-top:15px;">Loom Plan</h3></td>
	    </tr>

	    <tr>
		  <td colspan="3">

		    <div style="width:790px; height:425px; overflow:auto;">
		    <table border="1" bordercolor="#cccccc" cellpadding="5" cellspacing="0" width="100%">
			  <tr bgcolor="#e6e6e6">
			    <td width="40" rowspan="2" align="center"><b>#</b></td>
			    <td width="100" rowspan="2" align="center"><b>Date</b></td>
			    <td width="60" rowspan="2" align="center"><b>No of Looms</b></td>
			    <td width="<?= (count($iLooms) * 80) ?>" align="center" colspan="<?= count($iLooms) ?>"><b>Assigned Looms</b> (Production/Loom)</td>
			    <td width="90" rowspan="2" align="center"><b>Day<br />Production</b></td>
			    <td width="90" rowspan="2" align="center"><b>Total<br />Production</b></td>
			  </tr>

			  <tr bgcolor="#eeeeee">
<?
	foreach ($iLooms as $iLoom)
	{
?>
			    <td width="80" align="center"><b><?= $sLoomsList[$iLoom] ?></b></td>
<?
	}
?>
			  </tr>

<?
	$iIndex    = 1;
	$iTotal    = 0;
	$iFromDate = strtotime($sFromDate);
	$iToDate   = strtotime($sToDate);

	do
	{
		$sDate     = date("Y-m-d", $iFromDate);
		$iSubTotal = 0;
?>
			  <tr bgcolor="#f6f6f6">
			    <td align="center"><?= $iIndex ++ ?></td>
			    <td align="center"><?= formatDate($sDate) ?></td>
			    <td align="center"><?= getDbValue("COUNT(*)", "tbl_loom_plan_details", "po_id='$Id' AND `date`='$sDate' AND production>'0'") ?></td>
<?
		foreach ($iLooms as $iLoom)
		{
			$iProduction = getDbValue("production", "tbl_loom_plan_details", "po_id='$Id' AND `date`='$sDate' AND loom_id='$iLoom'");
?>
			    <td align="center"><?= (($iProduction == 0) ? "-" : formatNumber($iProduction, false)) ?></td>
<?
			$iSubTotal += $iProduction;
			$iTotal    += $iProduction;
		}
?>
			    <td align="center"><?= formatNumber($iSubTotal, false) ?></td>
			    <td align="center"><?= formatNumber($iTotal, false) ?></td>
			  </tr>
<?
		$iFromDate += 86400;
	}
	while ($iFromDate <= $iToDate);
?>
		    </table>
		    </div>

		  </td>
	    </tr>
	  </table>
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