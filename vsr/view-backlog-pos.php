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

	$Vendor   = IO::intValue("Vendor");
	$Brand    = IO::intValue("Brand");
	$Region   = IO::intValue("Region");
	$Season   = IO::intValue("Season");
	$StartDate = IO::strValue("StartDate");
	$EndDate   = IO::strValue("EndDate");

	$Week   = IO::intValue("Week");


	$User   	= IO::intValue("User");

	if ($FromDate == "" || $ToDate == "")
	{
		$FromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 30), date("Y")));
		$ToDate   = date("Y-m-d");
	}

	$vendors = getDBValue("vendors","tbl_users","id='$User'");
	$brands = getDBValue("brands","tbl_users","id='$User'");

	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ($vendors) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id>'0' AND id IN ($brands)");
	$sSeasonsList = getList("tbl_seasons", "id", "season", "parent_id>'0'");


	if (@strpos($_SESSION["Brands"], ",") === FALSE)
		$Brand = $_SESSION["Brands"];

	if ($Brand > 0)
	{
		$iParent      = getDbValue("parent_id", "tbl_brands", "id='$Brand'");
		$sSeasonsList = getList("tbl_seasons", "id", "season", "brand_id='$iParent' AND parent_id>'0'");
	}

	//// Queries

	$sVendorPos   = "";
	$sBrandStyles = "";
	$sBrandPos    = "";
	$sVendorsSql  = "";
	$sSeasonSql   = "";
	$sRegionSql   = "";

		if ($Region > 0)
			{
				$sSQL = "SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y'";
				$objDb->query($sSQL);

				$iCount   = $objDb->getCount( );
				$sVendors = "";

				for ($i = 0; $i < $iCount; $i ++)
					$sVendors .= (",".$objDb->getField($i, 0));

				if ($sVendors != "")
					$sVendors = substr($sVendors, 1);

				$sVendorsSql = " AND vendor_id IN ($sVendors) ";
		}

		if ($Vendor > 0)
			$sSQL = "SELECT id FROM tbl_po WHERE vendor_id='$Vendor' $sVendorsSql";

		else
			$sSQL = "SELECT id FROM tbl_po WHERE vendor_id IN ({$_SESSION['Vendors']}) $sVendorsSql";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sVendorPos .= (",".$objDb->getField($i, 0));

		if ($sVendorPos != "")
			$sVendorPos = substr($sVendorPos, 1);

		if ($Season > 0)
			$sSeasonSql = " AND sub_season_id='$Season' ";

		if ($Brand > 0)
			$sSQL = "SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand' AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}') $sSeasonSql";

		else
			$sSQL = "SELECT id FROM tbl_styles WHERE sub_brand_id IN ({$_SESSION['Brands']}) AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}') $sSeasonSql";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sBrandStyles .= (",".$objDb->getField($i, 0));

		if ($sBrandStyles != "")
			$sBrandStyles = substr($sBrandStyles, 1);

		$sSQL = "SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE style_id IN ($sBrandStyles) AND (etd_required BETWEEN '$StartDate' AND '$EndDate')";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sBrandPos .= (",".$objDb->getField($i, 0));

		if ($sBrandPos != "")
			$sBrandPos = substr($sBrandPos, 1);



	//$sSQL =  "SELECT  distinct(po.id) as backlog_pos, po.order_no, po.styles as style_id, pc.etd_required, sum(order_qty) as total_order, DATE_FORMAT(pc.etd_required,'%u') as week FROM tbl_po_colors as pc, tbl_po as po, tbl_vsr_details as vsr WHERE pc.po_id in ($sBrandPos)  and po.id=pc.po_id and pc.po_id=vsr.po_id and ( vsr.final_date > pc.etd_required OR vsr.final_date IS NULL OR vsr.final_date = '0000-00-00') and DATE_FORMAT(pc.etd_required,'%u') = '$Week' group by vsr.po_id Order by DATE_FORMAT(pc.etd_required,'%u') ";

		$sSQL =  "SELECT  distinct(po.id) as backlog_pos, po.order_no, po.styles as style_id, pc.etd_required, sum(order_qty) as total_order, DATE_FORMAT(pc.etd_required,'%u') as week FROM tbl_po_colors as pc, tbl_po as po, tbl_vsr_details as vsr
		WHERE pc.po_id in ($sBrandPos) and pc.etd_required between '$StartDate' and '$EndDate' and po.id=pc.po_id and pc.po_id=vsr.po_id and ( vsr.final_date > pc.etd_required OR vsr.final_date IS NULL OR vsr.final_date = '0000-00-00') and DATE_FORMAT(pc.etd_required,'%u') = '$Week' group by vsr.po_id Order by total_order DESC ";


	$objDb->query($sSQL);
	$iCount = $objDb->getCount( );


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
	<div id="Body" style="min-height:544px; height:544px;">
	  <h2>Back Log Po's</h2>

	  <table border="0" cellpadding="5" cellspacing="0" width="100%">
	    <tr bgcolor="#ffffff" style="font-weight:bold;">
		  <td width="8%">PO</td>
		  <td width="8%">Style</td>
		  <td width="8%">Qty</td>
		  <td width="8%">Etd</td>
	    </tr>

	  	<?

	for ($i = 0; $i < $iCount; $i ++)
	{

		$order_no = $objDb->getField($i, 'po.order_no');
		$style_id = $objDb->getField($i, 'style_id');
		$total_order = $objDb->getField($i, 'total_order');
		$etd_required = $objDb->getField($i, 'etd_required');


		?>

	    <tr bgcolor="#ffffff">
		  <td width="8%"><?=$order_no;?></td>
		  <td width="8%"><?=$style_id;?></td>
		  <td width="8%"><?=$total_order;?></td>
		  <td width="8%"><?=$etd_required;?></td>
	    </tr>

	    <?
	    }
	    ?>

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