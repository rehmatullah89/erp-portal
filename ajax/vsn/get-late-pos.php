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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	$Mode    = IO::strValue("Mode");
	$Region  = IO::intValue("Region");
	$Year    = IO::strValue("Year");
	$Month   = IO::strValue("Month");
	$Vendors = IO::strValue("Vendors");
	$Brands  = IO::strValue("Brands");
	$PoType  = IO::strValue("PoType");


	$iDays     = @cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
	$sFromDate = ($Year."-".str_pad($Month, 2, '0', STR_PAD_LEFT)."-01");
	$sToDate   = ($Year."-".str_pad($Month, 2, '0', STR_PAD_LEFT)."-".$iDays);


	$sConditions = " WHERE FIND_IN_SET(vendor_id, '$Vendors') AND FIND_IN_SET(brand_id, '$Brands') ";

	if ($Region > 0)
		$sConditions .= " AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";


	$sSQL = "SELECT DISTINCT(pc.po_id)
	         FROM tbl_po_colors pc, tbl_styles s
	         WHERE pc.style_id=s.id AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate') AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$sPos   = "";

	for ($i = 0; $i < $iCount; $i ++)
		$sPos .= (",".$objDb->getField($i, 0));

	if ($sPos != "")
	{
		$sPos = substr($sPos, 1);

		$sConditions .= " AND id IN ($sPos) ";
	}


	$sSQL = "SELECT DISTINCT(pc.po_id) AS _Po
	         FROM tbl_pre_shipment_detail psd, tbl_po_colors pc, tbl_po po, tbl_styles s
	         WHERE pc.po_id=psd.po_id AND pc.style_id=s.id AND po.id=pc.po_id AND pc.etd_required < psd.handover_to_forwarder AND psd.handover_to_forwarder != '0000-00-00' AND NOT ISNULL(psd.handover_to_forwarder) AND po.order_nature='B'
	               AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
	               AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate') AND FIND_IN_SET(po.vendor_id, '$Vendors') AND FIND_IN_SET(po.brand_id, '$Brands')";

	if ($PoType != "")
		$sSQL .= " AND po.order_type='$PoType' ";

	if ($Region > 0)
		$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	$sSQL .= "UNION

	          SELECT DISTINCT(po.id) AS _Po
	          FROM tbl_po po, tbl_po_colors pc, tbl_styles s
	          WHERE po.id=pc.po_id AND pc.style_id=s.id AND pc.etd_required <= CURDATE( ) AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate') AND po.order_nature='B'
	                AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
	                AND FIND_IN_SET(po.vendor_id, '$Vendors') AND FIND_IN_SET(po.brand_id, '$Brands')
	                AND po.id NOT IN (SELECT DISTINCT(po_id) FROM tbl_pre_shipment_detail WHERE handover_to_forwarder!='0000-00-00' AND NOT ISNULL(handover_to_forwarder))";


	if ($PoType != "")
		$sSQL .= " AND po.order_type='$PoType' ";

	if ($Region > 0)
		$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$sPos   = "";

	for ($i = 0; $i < $iCount; $i ++)
		$sPos .= (",".$objDb->getField($i, 0));

	if ($sPos != "")
		$sPos = substr($sPos, 1);

	$sConditions .= " AND id IN ($sPos) ";



	$sSQL = "SELECT id, CONCAT(order_no, ' ', order_status) AS _Po, vendor_id FROM tbl_po $sConditions ORDER BY id DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
?>
			    <div class="tblSheet" style="margin:15px 10px 0px 10px;">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="7%">#</td>
				      <td width="41%">Order No</td>
				      <td width="40%">Vendor</td>
				      <td width="12%">Details</td>
				    </tr>
<?
	$iIndex       = 0;
	$sClass       = array("evenRow", "oddRow");
	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");


	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId = $objDb->getField($i, 'id');


		$sSQL = "SELECT SUM(pq.quantity), pc.etd_required
		         FROM tbl_po_colors pc, tbl_po_quantities pq, tbl_styles s
		         WHERE pc.po_id=pq.po_id AND pc.po_id='$iId' AND pc.id=pq.color_id AND pc.style_id=s.id
		               AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
		         GROUP BY pc.etd_required";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );
		$bFlag   = false;

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iOrderQty = $objDb2->getField($j, 0);
			$sEtdReq   = $objDb2->getField($j, 1);


			$sSQL = "SELECT SUM(psq.quantity)
					 FROM tbl_po_colors pc, tbl_styles s, tbl_pre_shipment_quantities psq, tbl_pre_shipment_detail psd
					 WHERE pc.po_id=psq.po_id AND pc.po_id=psd.po_id AND pc.po_id='$iId' AND pc.id=psq.color_id AND pc.style_id=s.id AND
					       AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
					       psd.id=psq.ship_id AND psd.handover_to_forwarder<='$sEtdReq'";
			$objDb3->query($sSQL);

			if ((float)$objDb3->getField(0, 0) < $iOrderQty)
				$bFlag = true;


			if ($bFlag == true)
				break;
		}

		if ($bFlag == false)
			continue;

?>

				    <tr class="<?= $sClass[($iIndex % 2)] ?>" valign="top">
				      <td><?= ++ $iIndex ?></td>
<?
		if (checkUserRights("view-purchase-order.php", "Data Entry", "view"))
		{
?>
				      <td><a href="data/view-purchase-order.php?Id=<?= $iId ?>" class="lightview sheetLink" rel="iframe" title="PO # <?= $objDb->getField($i, '_Po') ?> :: :: width: 800, height: 550"><?= $objDb->getField($i, '_Po') ?></a></td>
<?
		}

		else
		{
?>
				      <td><?= $objDb->getField($i, '_Po') ?></td>
<?
		}
?>
				      <td><?= $sVendorsList[$objDb->getField($i, 'vendor_id')] ?></td>

				      <td class="center">
<?
		if (checkUserRights("view-pre-shipment-detail.php", "Shipping", "view") && getDbValue("COUNT(*)", "tbl_pre_shipment_detail", "po_id='$iId'") > 0)
		{
?>
				        <a href="shipping/view-pre-shipment-detail.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="PO # <?= $objDb->getField($i, '_Po') ?> :: :: width: 700, height: 550"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a>
<?
		}
?>
				      </td>
				    </tr>
<?
	}

	if ($iCount == 0)
	{
?>
				    <tr>
				      <td colspan="4">No PO Found!</td>
				    </tr>
<?
	}
?>
			      </table>
			    </div>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>