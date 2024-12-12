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

	@require_once("requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Vendor = IO::intValue("Vendor");
	$Year   = IO::intValue("Year");
	$Month  = IO::intValue("Month");

	if ($Year == 0)
		$Year = date("Y");

	if ($Month == 0)
		$Month = date("n");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
  <title>Triple Tree Customer Portal</title>
</head>

<body style="margin:0px;">

<style type="text/css">
<!--

#MainDiv
{
  background  :  #ffffff;
}

#MainDiv table
{
  border-collapse  :  collapse;
  border-spacing   :  0;
  table-layout     :  fixed;

  font-family      :  verdana, arial, sans-serif;
  font-size        :  12px;
  color            :  #333333;
}

#MainDiv td, #MainDiv div
{
  font-family      :  verdana, arial, sans-serif;
  font-size        :  12px;
  color            :  #333333;
}

#MainDiv h1
{
  font-family     :  arial, verdana, sans-serif;
  font-weight     :  bold;
  font-size       :  24px;
  color           :  #ffffff;

  padding         :  0px;
  margin          :  0px;
  background      :  #b6e500;
}

#MainDiv #Header
{
  background  :  #494949;
}

#MainDiv #Footer
{
  border-top  :  solid 2px #666666;
  background  :  #f0f0f0;
}
-->
</style>

<div style="background:#aaaaaa;">
  <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#aaaaaa">
  <tr>
  <td width="100%" align="center">
    <br />


	<table border="0" cellpadding="10" cellspacing="0" width="870" bgcolor="#ffffff">
	<tr>
	<td width="100%" align="center">

	    <div id="MainDiv">
		  <table border="0" cellpadding="0" cellspacing="0" width="850" bgcolor="#ffffff">
		    <tr>
  			  <td width="100%">

<!--  Header Section Starts Here  -->
				<div id="Header">
				  <table border="0" cellpadding="15" cellspacing="0" width="100%" bgcolor="#494949">
					<tr>
					  <td><a href="<?= SITE_URL ?>" target="_blank"><img src="<?= SITE_URL ?>images/matrix-customer-portal.jpg" width="521" height="55" border="0" alt="" title="" /></a></td>
					  <td width="240"><img src="<?= SITE_URL ?>images/quote.jpg" width="233" height="25" alt="" title="" /></td>
					</tr>
				  </table>
				</div>
<!--  Header Section Ends Here  -->


<!--  Body Section Starts Here  -->
				<div>

<?
	$sVendorsList = array( );


	$sSQL = "SELECT DISTINCT(po.vendor_id) AS _VendorId,
	                (SELECT vendor FROM tbl_vendors WHERE id=po.vendor_id) AS _Vendor
	         FROM tbl_po po, tbl_po_colors pc
	         WHERE po.id=pc.po_id AND po.order_nature='B' AND FIND_IN_SET(po.brand_id, '67,75')
	         ORDER BY _Vendor";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$sVendorsList[$objDb->getField($i, "_VendorId")] = $objDb->getField($i, "_Vendor");
?>
				  <table border="0" cellpadding="10" cellspacing="0" width="100%" bgcolor="#b6e500">
					<tr>
					  <td width="100%"><h1>ADIDAS/REEBOK SDP</h1></td>
					</tr>
				  </table>

			      <form name="frmSearch" id="frmSearch" method="post" action="<?= $_SERVER['PHP_SELF'] ?>" style="margin:2px 0px 2px 0px; border:solid 1px #aaaaaa; background:#f6f6f6; padding:5px;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="52">Vendor</td>

			          <td width="180">
			            <select name="Vendor">
			              <option value="">All Vendors</option>
<?
	foreach ($sVendorsList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Vendor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
			            </select>
			          </td>


					  <td width="35">Year</td>

					  <td width="100">
					    <select name="Year">
<?
	for ($i = 2008; $i <= date("Y"); $i ++)
	{
?>
	  	        		  <option value="<?= $i ?>"<?= (($i == $Year) ? " selected" : "") ?>><?= $i ?></option>
<?
	}
?>
					    </select>
					  </td>

					  <td width="45">Month</td>

					  <td width="120">
					    <select name="Month">
<?
	for ($i = 1; $i <= 12; $i ++)
	{
?>
	  	        		  <option value="<?= $i ?>"<?= (($i == $Month) ? " selected" : "") ?>><?= date("F", mktime(0, 0, 0, $i, 1, date("Y"))) ?></option>
<?
	}
?>
					    </select>
					  </td>

					  <td><input type="submit" value="Filter" /></td>
			        </tr>
			      </table>
			      </form>

<?
	foreach ($sVendorsList as $iVendor => $sVendor)
	{
		if ($Vendor > 0 && $iVendor != $Vendor)
			continue;


		$sFromDate = date("Y-m-01", mktime(0, 0, 0, $Month, 1, $Year));
		$sToDate   = date("Y-m-t", mktime(0, 0, 0, $Month, 1, $Year));

		$iOrderQty  = getDbValue("SUM(pc.order_qty)", "tbl_po po, tbl_po_colors pc", "po.id=pc.po_id AND po.order_nature='B' AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate') AND FIND_IN_SET(po.brand_id, '67,75') AND po.vendor_id='$iVendor'");
		$iShipQty   = getDbValue("COALESCE(SUM(psq.quantity), 0)", "tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq", "po.id=pc.po_id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id AND po.order_nature='B' AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate') AND FIND_IN_SET(po.brand_id, '67,75') AND po.vendor_id='$iVendor'");
		$iLateQty   = getDbValue("COALESCE(SUM(psq.quantity), 0)", "tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq", "po.id=pc.po_id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id AND po.order_nature='B' AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate') AND FIND_IN_SET(po.brand_id, '67,75') AND po.vendor_id='$iVendor' AND psd.handover_to_forwarder > pc.etd_required");
		$iOnTimeQty = ($iShipQty - $iLateQty);
		$iUnShipped = ($iOrderQty - $iShipQty);
		$iTotalPos  = getDbValue("COUNT(DISTINCT(po.id))", "tbl_po po, tbl_po_colors pc", "po.id=pc.po_id AND po.order_nature='B' AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate') AND FIND_IN_SET(po.brand_id, '67,75') AND po.vendor_id='$iVendor'");
		$iShipPos   = getDbValue("COUNT(DISTINCT(po.id))", "tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq", "po.id=pc.po_id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id AND po.order_nature='B' AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate') AND FIND_IN_SET(po.brand_id, '67,75') AND po.vendor_id='$iVendor'");
		$iLatePos   = getDbValue("COUNT(DISTINCT(po.id))", "tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq", "po.id=pc.po_id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id AND po.order_nature='B' AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate') AND FIND_IN_SET(po.brand_id, '67,75') AND po.vendor_id='$iVendor' AND psd.handover_to_forwarder > pc.etd_required");
		$iUnShipPos = ($iTotalPos - $iShipPos);
		$iOnTimePos = ($iShipPos - $iLatePos);
		$sLatePos   = getDbValue("GROUP_CONCAT(DISTINCT(po.order_no) SEPARATOR ', ')", "tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq", "po.id=pc.po_id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id AND po.order_nature='B' AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate') AND FIND_IN_SET(po.brand_id, '67,75') AND po.vendor_id='$iVendor' AND psd.handover_to_forwarder > pc.etd_required");
		$sUnShipPos = getDbValue("GROUP_CONCAT(DISTINCT(po.order_no) SEPARATOR ', ')", "tbl_po po, tbl_po_colors pc", "po.id=pc.po_id AND po.order_nature='B' AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate') AND FIND_IN_SET(po.brand_id, '67,75') AND po.vendor_id='$iVendor' AND NOT po.id IN (SELECT DISTINCT(po_id) FROM tbl_pre_shipment_quantities)");

		$fOTP = @round((($iOnTimeQty / $iOrderQty) * 100), 2);


		if ($Vendor == 0 && $iOrderQty == 0)
			continue;
?>
				  <h1 style="background:#444444; padding:10px; margin-bottom:2px;"><?= $sVendor ?></h1>

				  <div style="background:#888888; margin-bottom:3px;"><h1 style="background:none; padding:5px;"><?= date("M Y", strtotime($sFromDate)) ?></h1></div>

				  <table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
					  <td width="49.5%" bgcolor="#bbbbbb"><h1 style="background:none; padding:5px;">PODD</h1></td>
					  <td width="1%"></td>
					  <td width="49.5%" bgcolor="#bbbbbb"><h1 style="background:none; padding:5px;">POSDD</h1></td>
					</tr>

					<tr>
					  <td colspan="3" height="4"></td>
					</tr>

					<tr valign="top">
					  <td>

						<div style="background:#dddddd; border:solid 1px #aaaaaa; padding:5px; margin-bottom:3px; text-align:center;"><b>OTP : <?= formatNumber($fOTP) ?>%</b></div>

						<table border="1" bordercolor="#aaaaaa" cellspacing="0" cellpadding="5" width="100%">
						  <tr bgcolor="#dddddd">
						    <td width="100%" colspan="2"><b>Quantities</b></td>
						  </tr>

						  <tr>
							<td width="50%"><b>Order</b></td>
							<td width="50%"><?= formatNumber($iOrderQty, false) ?></td>
						  </tr>

						  <tr>
							<td width="50%"><b>Shipped</b></td>
							<td width="50%"><?= formatNumber($iShipQty, false) ?></td>
						  </tr>

						  <tr>
							<td width="50%"><b>On-Time</b></td>
							<td width="50%"><?= formatNumber($iOnTimeQty, false) ?> (<?= formatNumber((($iOnTimeQty / $iOrderQty) * 100)) ?>%)</td>
						  </tr>

						  <tr>
							<td width="50%"><b>Late</b></td>
							<td width="50%"><?= formatNumber($iLateQty, false) ?> (<?= formatNumber((($iLateQty / $iOrderQty) * 100)) ?>%)</td>
						  </tr>

						  <tr>
							<td width="50%"><b>Un-Shipped</b></td>
							<td width="50%"><?= formatNumber($iUnShipped, false) ?> (<?= formatNumber((($iUnShipped / $iOrderQty) * 100)) ?>%)</td>
						  </tr>
						</table>

						<br />

						<table border="1" bordercolor="#aaaaaa" cellspacing="0" cellpadding="5" width="100%">
						  <tr bgcolor="#dddddd">
						    <td width="100%" colspan="2"><b>POs</b></td>
						  </tr>

						  <tr>
							<td width="50%"><b>Order</b></td>
							<td width="50%"><?= formatNumber($iTotalPos, false) ?></td>
						  </tr>

						  <tr>
							<td width="50%"><b>Shipped</b></td>
							<td width="50%"><?= formatNumber($iShipPos, false) ?></td>
						  </tr>

						  <tr>
							<td width="50%"><b>On-Time</b></td>
							<td width="50%"><?= formatNumber($iOnTimePos, false) ?></td>
						  </tr>

						  <tr>
							<td width="50%"><b>Late</b></td>
							<td width="50%"><?= formatNumber($iLatePos, false) ?></td>
						  </tr>

						  <tr>
							<td width="50%"><b>Un-Shipped</b></td>
							<td width="50%"><?= formatNumber($iUnShipPos, false) ?></td>
						  </tr>
						<table>

						<br />

						<table border="1" bordercolor="#aaaaaa" cellspacing="0" cellpadding="5" width="100%">
						  <tr bgcolor="#dddddd">
						    <td width="100%"><b>Late POs</b></td>
						  </tr>

						  <tr>
							<td><?= (($sLatePos == "") ? "None" : $sLatePos) ?></td>
						  </tr>
						<table>

						<br />

						<table border="1" bordercolor="#aaaaaa" cellspacing="0" cellpadding="5" width="100%">
						  <tr bgcolor="#dddddd">
						    <td width="100%"><b>Un-Shipped POs</b></td>
						  </tr>

						  <tr>
							<td><?= (($sUnShipPos == "") ? "None" : $sUnShipPos) ?></td>
						  </tr>
						</table>
					  </td>

					  <td></td>

					  <td>
<?
		$iOrderQty  = getDbValue("SUM(pc.order_qty)", "tbl_po po, tbl_po_colors pc", "po.id=pc.po_id AND po.order_nature='B' AND (pc.posdd BETWEEN '$sFromDate' AND '$sToDate') AND FIND_IN_SET(po.brand_id, '67,75') AND po.vendor_id='$iVendor'");
		$iShipQty   = getDbValue("COALESCE(SUM(psq.quantity), 0)", "tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq", "po.id=pc.po_id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id AND po.order_nature='B' AND (pc.posdd BETWEEN '$sFromDate' AND '$sToDate') AND FIND_IN_SET(po.brand_id, '67,75') AND po.vendor_id='$iVendor'");
		$iLateQty   = getDbValue("COALESCE(SUM(psq.quantity), 0)", "tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq", "po.id=pc.po_id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id AND po.order_nature='B' AND (pc.posdd BETWEEN '$sFromDate' AND '$sToDate') AND FIND_IN_SET(po.brand_id, '67,75') AND po.vendor_id='$iVendor' AND psd.handover_to_forwarder > pc.posdd");
		$iOnTimeQty = ($iShipQty - $iLateQty);
		$iUnShipped = ($iOrderQty - $iShipQty);
		$iTotalPos  = getDbValue("COUNT(DISTINCT(po.id))", "tbl_po po, tbl_po_colors pc", "po.id=pc.po_id AND po.order_nature='B' AND (pc.posdd BETWEEN '$sFromDate' AND '$sToDate') AND FIND_IN_SET(po.brand_id, '67,75') AND po.vendor_id='$iVendor'");
		$iShipPos   = getDbValue("COUNT(DISTINCT(po.id))", "tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq", "po.id=pc.po_id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id AND po.order_nature='B' AND (pc.posdd BETWEEN '$sFromDate' AND '$sToDate') AND FIND_IN_SET(po.brand_id, '67,75') AND po.vendor_id='$iVendor'");
		$iLatePos   = getDbValue("COUNT(DISTINCT(po.id))", "tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq", "po.id=pc.po_id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id AND po.order_nature='B' AND (pc.posdd BETWEEN '$sFromDate' AND '$sToDate') AND FIND_IN_SET(po.brand_id, '67,75') AND po.vendor_id='$iVendor' AND psd.handover_to_forwarder > pc.posdd");
		$iUnShipPos = ($iTotalPos - $iShipPos);
		$iOnTimePos = ($iShipPos - $iLatePos);
		$sLatePos   = getDbValue("GROUP_CONCAT(DISTINCT(po.order_no) SEPARATOR ', ')", "tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq", "po.id=pc.po_id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id AND po.order_nature='B' AND (pc.posdd BETWEEN '$sFromDate' AND '$sToDate') AND FIND_IN_SET(po.brand_id, '67,75') AND po.vendor_id='$iVendor' AND psd.handover_to_forwarder > pc.posdd");
		$sUnShipPos = getDbValue("GROUP_CONCAT(DISTINCT(po.order_no) SEPARATOR ', ')", "tbl_po po, tbl_po_colors pc", "po.id=pc.po_id AND po.order_nature='B' AND (pc.posdd BETWEEN '$sFromDate' AND '$sToDate') AND FIND_IN_SET(po.brand_id, '67,75') AND po.vendor_id='$iVendor' AND NOT po.id IN (SELECT DISTINCT(po_id) FROM tbl_pre_shipment_quantities)");

		$fOTP = @round((($iOnTimeQty / $iOrderQty) * 100), 2);
?>
						<div style="background:#dddddd; border:solid 1px #aaaaaa; padding:5px; margin-bottom:3px; text-align:center;"><b>OTP : <?= formatNumber($fOTP) ?>%</b></div>

						<table border="1" bordercolor="#aaaaaa" cellspacing="0" cellpadding="5" width="100%">
						  <tr bgcolor="#dddddd">
						    <td width="100%" colspan="2"><b>Quantities</b></td>
						  </tr>

						  <tr>
							<td width="50%"><b>Order</b></td>
							<td width="50%"><?= formatNumber($iOrderQty, false) ?></td>
						  </tr>

						  <tr>
							<td width="50%"><b>Shipped</b></td>
							<td width="50%"><?= formatNumber($iShipQty, false) ?></td>
						  </tr>

						  <tr>
							<td width="50%"><b>On-Time</b></td>
							<td width="50%"><?= formatNumber($iOnTimeQty, false) ?> (<?= formatNumber((($iOnTimeQty / $iOrderQty) * 100)) ?>%)</td>
						  </tr>

						  <tr>
							<td width="50%"><b>Late</b></td>
							<td width="50%"><?= formatNumber($iLateQty, false) ?> (<?= formatNumber((($iLateQty / $iOrderQty) * 100)) ?>%)</td>
						  </tr>

						  <tr>
							<td width="50%"><b>Un-Shipped</b></td>
							<td width="50%"><?= formatNumber($iUnShipped, false) ?> (<?= formatNumber((($iUnShipped / $iOrderQty) * 100)) ?>%)</td>
						  </tr>
						</table>

						<br />

						<table border="1" bordercolor="#aaaaaa" cellspacing="0" cellpadding="5" width="100%">
						  <tr bgcolor="#dddddd">
						    <td width="100%" colspan="2"><b>POs</b></td>
						  </tr>

						  <tr>
							<td width="50%"><b>Order</b></td>
							<td width="50%"><?= formatNumber($iTotalPos, false) ?></td>
						  </tr>

						  <tr>
							<td width="50%"><b>Shipped</b></td>
							<td width="50%"><?= formatNumber($iShipPos, false) ?></td>
						  </tr>

						  <tr>
							<td width="50%"><b>On-Time</b></td>
							<td width="50%"><?= formatNumber($iOnTimePos, false) ?></td>
						  </tr>

						  <tr>
							<td width="50%"><b>Late</b></td>
							<td width="50%"><?= formatNumber($iLatePos, false) ?></td>
						  </tr>

						  <tr>
							<td width="50%"><b>Un-Shipped</b></td>
							<td width="50%"><?= formatNumber($iUnShipPos, false) ?></td>
						  </tr>
						<table>

						<br />

						<table border="1" bordercolor="#aaaaaa" cellspacing="0" cellpadding="5" width="100%">
						  <tr bgcolor="#dddddd">
						    <td width="100%"><b>Late POs</b></td>
						  </tr>

						  <tr>
							<td><?= (($sLatePos == "") ? "None" : $sLatePos) ?></td>
						  </tr>
						<table>

						<br />

						<table border="1" bordercolor="#aaaaaa" cellspacing="0" cellpadding="5" width="100%">
						  <tr bgcolor="#dddddd">
						    <td width="100%"><b>Un-Shipped POs</b></td>
						  </tr>

						  <tr>
							<td><?= (($sUnShipPos == "") ? "None" : $sUnShipPos) ?></td>
						  </tr>
						</table>
					  </td>
					</tr>
				  </table>

				  <br />
<?
	}
?>

				</div>
<!--  Body Section Ends Here  -->


				<br />


<!--  Footer Section Starts Here  -->
				<div id="Footer">
				  <table border="0" cellpadding="10" cellspacing="0" width="100%" bgcolor="#f0f0f0">
					<tr>
					  <td width="100%">Copyright <?= date("Y") ?> &copy; Triple Tree</td>
					</tr>
				  </table>
				</div>
<!--  Footer Section Ends Here  -->

			  </td>
		    </tr>
		  </table>
	    </div>

    </td>
    </tr>
    </table>


    <br />
  </td>
  </tr>
  </table>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>