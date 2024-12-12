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

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Vendor   = IO::intValue("Vendor");
	$Brand    = IO::intValue("Brand");
	$Region   = IO::intValue("Region");
	$Season   = IO::intValue("Season");
	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");
	$VesselDate   = IO::strValue("VesselDate");
	$User   	= IO::intValue("User");

	//print_r($_POST);

	if ($FromDate == "" || $ToDate == "")
	{
		$FromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 30), date("Y")));
		$ToDate   = date("Y-m-d");
	}

	//echo getDbValue("vendors", "tbl_users", "id='$User'");

	$vendors = getDBValue("vendors","tbl_users","id='$User'");
	$brands = getDBValue("brands","tbl_users","id='$User'");
	//print_r($brands); exit;

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

	//All Dates

	$TerminalDate 			=  date('Y-m-d',strtotime('-3 Monday'));
	$LastMonday  			= date('Y-m-d',strtotime('last Monday'));
	$PreviousToLastMonday 	= date('Y-m-d',strtotime('-2 Monday'));
	$NextMonday 			= date('Y-m-d',strtotime('+1 Monday'));
	$NextToNextMonday 		= date('Y-m-d',strtotime('+2 Monday'));


	//Assign in chronological order

	$arrDates[] = $TerminalDate;
	$arrDates[] = $PreviousToLastMonday;
	$arrDates[] = $LastMonday;

	$arrDates[] = $NextMonday;
	$arrDates[] = $NextToNextMonday;
	$arrDates[] = date('Y-m-d',strtotime('+3 Monday'));

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

		$sSQL = "SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE style_id IN ($sBrandStyles) AND (etd_required BETWEEN '$FromDate' AND '$ToDate')";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sBrandPos .= (",".$objDb->getField($i, 0));

		if ($sBrandPos != "")
			$sBrandPos = substr($sBrandPos, 1);


	$ShortShipped 	= array();
	$VesselTotal 	= array();

	//fetch short shipped pos and shortquantity

	$SQL =	"SELECT distinct(sd.po_id) , (sum(pod.order_qty) - sd.quantity) as shortshipped ,sd.shipping_date FROM `tbl_pre_shipment_detail` as sd , `tbl_po_colors` as pod
		where sd.po_id in ($sBrandPos) and pod.po_id=sd.po_id and pod.etd_required >= '$TerminalDate' and  pod.etd_required <'$PreviousToLastMonday' group by sd.po_id, pod.po_id order by sd.shipping_date Desc";

	$objDb->query($SQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$short = $objDb->getField($i, 'shortshipped');

		if($short > 0)
			$ShortShipped[$objDb->getField($i, 'po_id')] = $short;

	}
	if($VesselDate !="")
	$sSQL =  "SELECT distinct(po_id) ,tbl_po.brand_id,tbl_styles.style,tbl_styles.sketch_file, etd_required, (sum(order_qty) - sum(ontime_qty)) as delta , Sum(order_qty) as total_order,sum(ontime_qty) as total_ontime , YEARWEEK(etd_required) as vessel FROM tbl_po_colors,tbl_po,tbl_styles WHERE tbl_po.id=tbl_po_colors.po_id and tbl_po_colors.style_id=tbl_styles.id and po_id in ($sBrandPos)  and YEARWEEK(etd_required) in ( '$VesselDate') group by po_id order by vessel Asc, delta Desc,order_qty Desc";
	else
	$sSQL =  "SELECT distinct(po_id) , Sum(order_qty) as total_order,sum(ontime_qty) as total_ontime , YEARWEEK(etd_required) as vessel FROM tbl_po_colors WHERE po_id in ($sBrandPos) and YEARWEEK(etd_required) in ( YEARWEEK('$TerminalDate') , YEARWEEK('$PreviousToLastMonday') , YEARWEEK('$LastMonday') , YEARWEEK('$NextMonday') , YEARWEEK('$NextToNextMonday')) group by po_id order by vessel Asc";

	//print $sSQL; exit;
	$objDb->query($sSQL);
	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{

		$po_id = $objDb->getField($i, 'po_id');

		$backLog = 0;

		$VesselTotal[$objDb->getField($i, 'vessel')]['POs'] += 1;
		$VesselTotal[$objDb->getField($i, 'vessel')]['TotalOrderPerPO'] += $objDb->getField($i, 'total_order');
		$VesselTotal[$objDb->getField($i, 'vessel')]['TotalOnTimePerPO'] += $objDb->getField($i, 'total_ontime');

	}

	for($i=0; $i<$iCount; $i++){
		$delta = $objDb->getField($i, 'delta');
		$Po_id = $objDb->getField($i, 'po_id');
		$style = $objDb->getField($i, 'style');
		$sSketchFile = $objDb->getField($i, 'sketch_file');
		$etd_required = $objDb->getField($i, 'etd_required');
		$brand_id = $objDb->getField($i, 'brand_id');
		$total_order = $objDb->getField($i, 'total_order');
		$total_ontime = $objDb->getField($i, 'total_ontime');
		$ratio = 1-($total_ontime/$total_order);

		if($delta <1) continue;

		$brandName = getDBValue("brand","tbl_brands","id='$brand_id'");
		//echo $sBaseDir.STYLES_SKETCH_DIR.$sSketchFile; exit;


		if ($sSketchFile == "" || !@file_exists("../".$sBaseDir.STYLES_SKETCH_DIR.$sSketchFile))
		$sSketchFile = (STYLES_SKETCH_DIR."default.jpg");

		else
		{
		if (!@file_exists("../".$sBaseDir.STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile))
			createImage(("../".$sBaseDir.STYLES_SKETCH_DIR.$sSketchFile), ("../".$sBaseDir.STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile), 160, 160);

		$sSketchFile = ("../".$sBaseDir.STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile);
		}
		//print $ratio;
	?>

	<div id="row<?=$i?>" onClick="rowClicked('rowHead<?=$i?>','detail<?=$i?>');" >

		<div id="rowHead<?=$i?>" style="position:relative;background-color:#cccccc; margin:10 10 10 10px; width:100% height:25px; ">
			<span style="padding:10 5 5 5px; width:33.33%;float:left;"><b><?=$brandName;?></b> <i>Po# <?=$Po_id?></i></span>
			<span style="margin:5 5 5 30px; background-color:red; width:<?=$ratio*33.33;?>%;float:left;">&nbsp;</span><?=number_format($ratio*100,2)."%";?>
			<div style="clear:both;width:0px;"> </div>
		</div>

		<div id="detail<?=$i?>" style="display:none;position:relative;background-color:#cccccc; margin:10 10 10 10px; width:100%; ">
			<span style="padding:10 5 5 5px;width:33.33%;float:left;"><b><?=$brandName;?></b> <i>Po# <?=$Po_id?></i><br>
			<p >Style: <i><?=$style;?></i><br/>
			Etd required: <i><?=$etd_required;?></i><br/>
			</p> </span>

			<span style="margin:5 50 50 30px; background-color:red; width:<?=$ratio*33.33;?>%;float:left;">&nbsp;</span><?=number_format($ratio*100,2)."%";?>

			<span style="margin:5 10 10 10px;width:15.33%;float:right;"><img src="<?=$sSketchFile;?>" width="30" height="50"  /></span>

			<div style="clear:both;width:0px;"> </div>
		</div>

	</div> <!--row--> <br />

	<?
	}

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>