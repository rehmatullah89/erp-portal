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

	$Vendor   	= IO::intValue("Vendor");
	$Brand    	= IO::intValue("Brand");
	$Region   	= IO::intValue("Region");
	$Season   	= IO::intValue("Season");
	$Department = IO::intValue("Department");

	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");

	$Week_Start_Date = IO::strValue("Week_Start_Date");
	$Week_End_Date   = IO::strValue("Week_End_Date");

	if($Week_End_Date  == '' && $Week_End_Date ==''){

		$Week_Start_Date = $arrDates[1];
		$Week_End_Date 	= $arrDates[2];

	}


	$CurrentDate   = IO::strValue("CurrentDate");



	if ($FromDate == "" || $ToDate == "")
	{
		$FromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 30), date("Y")));
		$ToDate   = date("Y-m-d");
	}

	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id>'0' AND id IN ({$_SESSION['Brands']})");
	$sSeasonsList = getList("tbl_seasons", "id", "season", "parent_id>'0'");


	if (@strpos($_SESSION["Brands"], ",") === FALSE)
		$Brand = $_SESSION["Brands"];

	if ($Brand > 0)
	{
		$iParent      = getDbValue("parent_id", "tbl_brands", "id='$Brand'");
		$sSeasonsList = getList("tbl_seasons", "id", "season", "brand_id='$iParent' AND parent_id>'0'");
	}

	//All Dates

	$CurrentDay = date('w', $CurrentDate==''?date('Y-m-d'):strtotime($CurrentDate));

/*	if($CurrentDay != 1)
		$CurrentMonday = date('Y-m-d',strtotime("last monday"));
	else
		$CurrentMonday = date('Y-m-d');
*/

	if($CurrentDay != 1)
		$CurrentMonday = date('Y-m-d',strtotime('last monday '.$CurrentDate));
	else
		$CurrentMonday = date('Y-m-d',strtotime($CurrentDate));

	if($CurrentMonday == date('Y-m-d',strtotime('last monday '.date('Y-m-d'))) ){
		$previousMonday = date('Y-m-d',strtotime($CurrentMonday.' -7 days '));
		$nextMonday = date('Y-m-d',strtotime($CurrentMonday.' +7 days '));
	}else{

		$previousMonday = date('Y-m-d',strtotime($CurrentMonday.' -7 days '));
		$nextMonday = date('Y-m-d',strtotime($CurrentMonday.' +7 days '));
	}

	$CurrentEnd = date('Y-m-d',strtotime($CurrentMonday." + 7 days"));

	$StartDate = date('Y-m-d',strtotime($CurrentMonday." - 7 days"));

	$EndDate = date('Y-m-d',strtotime($CurrentMonday." + 28 days"));


	//	$TerminalDate 			=  date('Y-m-d',strtotime($CurrentMonday." - 14 days"));
	$LastMonday  			= $CurrentMonday;
	$PreviousToLastMonday 	= date('Y-m-d',strtotime($CurrentMonday."  -7 days"));
	$NextMonday 			= date('Y-m-d',strtotime($CurrentMonday." + 7 days"));
	$NextToNextMonday 		= date('Y-m-d',strtotime($CurrentMonday." + 14 days"));

	//Assign in chronological order

	//$arrDates[] = $TerminalDate;
	//$arrDates[] = $PreviousToLastMonday;
	$arrDates[] = $LastMonday;

	$arrDates[] = $NextMonday;
	$arrDates[] = $NextToNextMonday;
	$arrDates[] = date('Y-m-d',strtotime($CurrentMonday." + 21 days"));

	$arrDates[] = date('Y-m-d',strtotime($CurrentMonday." + 27 days"));

	//$arrDates[] = date('Y-m-d',strtotime($CurrentMonday." + 35 days"));


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

		//$sSQL = "SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE style_id IN ($sBrandStyles) AND (etd_required BETWEEN '$FromDate' AND '$ToDate')";
		$sSQL = "SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE style_id IN ($sBrandStyles) AND (etd_required BETWEEN '$StartDate' AND '$EndDate')";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sBrandPos .= (",".$objDb->getField($i, 0));

		if ($sBrandPos != "")
			$sBrandPos = substr($sBrandPos, 1);


	$ShortShipped 	= array();
	$VesselWeeks 	= array();

	$sSQL =  "SELECT count(Distinct(po_id)) as total_pos ,sum(order_qty) as total_order, DATE_FORMAT(pc.etd_required,'%u') as week FROM tbl_po_colors as pc, tbl_po as po WHERE pc.etd_required between '$StartDate' and '$EndDate'  and po.id in ($sBrandPos) and po.id=pc.po_id group by DATE_FORMAT(pc.etd_required,'%u') Order by DATE_FORMAT(pc.etd_required,'%u') ";

	//print $sSQL; exit;
	$objDb->query($sSQL);
	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{

		$po_id = $objDb->getField($i, 'po_id');

		$backLog = 0;


		$VesselWeeks[$objDb->getField($i, 'week')]["ORDERS"] = $objDb->getField($i, 'total_order');
		$VesselWeeks[$objDb->getField($i, 'week')]["POS"] = $objDb->getField($i, 'total_pos');

	}

	//print_r($VesselWeeks);

	$sSQL =  "SELECT count(distinct(pc.po_id)) as total_ontime_pos, sum(order_qty) as total_ontime_order, DATE_FORMAT(pc.etd_required,'%u') as week FROM tbl_po_colors as pc, tbl_po as po, tbl_vsr_details as vsr WHERE pc.etd_required between '$StartDate' and '$EndDate' and pc.po_id in ($sBrandPos)  and po.id=pc.po_id and pc.po_id=vsr.po_id and ( vsr.final_date <= pc.etd_required )  group by DATE_FORMAT(pc.etd_required,'%u') Order by DATE_FORMAT(pc.etd_required,'%u') ";

	//print $sSQL; exit;
	$objDb->query($sSQL);
	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{

		$po_id = $objDb->getField($i, 'po_id');

		$backLog = 0;

		$VesselWeeks[$objDb->getField($i, 'week')]["ontime_ORDERS"] = $objDb->getField($i, 'total_ontime_order');
		$VesselWeeks[$objDb->getField($i, 'week')]["ontime_POS"] = $objDb->getField($i, 'total_ontime_pos');

		$VesselWeeks[$objDb->getField($i, 'week')]["Late_POS"] = $VesselWeeks[$objDb->getField($i, 'week')]["POS"]-$VesselWeeks[$objDb->getField($i, 'week')]["ontime_POS"];
		$VesselWeeks[$objDb->getField($i, 'week')]["Late_ORDERS"] = $VesselWeeks[$objDb->getField($i, 'week')]["ORDERS"]-$VesselWeeks[$objDb->getField($i, 'week')]["ontime_ORDERS"];

	}

	//$BL_Start_Date = date('Y-m-d',strtotime($StartDate." -7 days"));

	$sSQL =  "SELECT count(distinct(pc.po_id)) as total_bl_pos, sum(order_qty) as total_bl_order, DATE_FORMAT(pc.etd_required,'%u') as week FROM tbl_po_colors as pc, tbl_po as po, tbl_vsr_details as vsr WHERE pc.etd_required between '$StartDate' and '$EndDate' and pc.po_id in ($sBrandPos)  and po.id=pc.po_id and pc.po_id=vsr.po_id and ( vsr.final_date > pc.etd_required OR vsr.final_date = NULL OR vsr.final_date = '0000-00-00' )  group by DATE_FORMAT(pc.etd_required,'%u') Order by DATE_FORMAT(pc.etd_required,'%u') ";

	//print $sSQL; exit;
	$objDb->query($sSQL);
	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{

		$po_id = $objDb->getField($i, 'po_id');

		$backLog = 0;

		$VesselWeeks[$objDb->getField($i, 'week')+1]["BL_POS"] = $objDb->getField($i, 'total_bl_pos');
		$VesselWeeks[$objDb->getField($i, 'week')+1]["BL_ORDERS"] = $objDb->getField($i, 'total_bl_order');

		//$VesselWeeks[$objDb->getField($i, 'week')]["BL_POS"] = $VesselWeeks[$objDb->getField($i, 'week')]["POS"]-$VesselWeeks[$objDb->getField($i, 'week')]["ontime_POS"];
		//$VesselWeeks[$objDb->getField($i, 'week')]["BL_ORDERS"] = $VesselWeeks[$objDb->getField($i, 'week')]["ORDERS"]-$VesselWeeks[$objDb->getField($i, 'week')]["ontime_ORDERS"];

	}



	?>

	<table border="0" width="100%">
		<tr>
			<td>

				<div style="max-width:1900px; overflow:hidden ; white-space:nowrap;">


<?
		//$departmentList = getList("tbl_departments", "id", "department", "department Like '%Merchandising%' ");
		//$brandList = getList("tbl_departments", "id", "brands", "department Like '%Merchandising%' ");

	//$Week_Start_Date = $arrDates[0];
	//$Week_End_Date = $arrDates[1];

		/*
		$sSQL = "SELECT po.brand_id,b.brand, (Sum(poc.order_qty) - Sum(if(vd.final_date <=poc.etd_required,poc.ontime_qty,0))) as shortQty,Sum(poc.order_qty) as total_order,sum(poc.ontime_qty) as total_ontime
				FROM tbl_po_colors as poc, tbl_vsr_details as vd, tbl_po as po ,tbl_departments as d, tbl_brands as b
				WHERE po.brand_id =b.id and poc.po_id=po.id and poc.po_id=vd.po_id and FIND_IN_SET(po.brand_id,d.brands) and poc.po_id in
		($sBrandPos)
		and vd.final_date <= poc.etd_required and poc.etd_required between '$Week_Start_Date' and '$Week_End_Date' and d.id = $Department  group by po.brand_id order by shortQty DESC";
		*/

	$sSQL = 	"SELECT po.brand_id,b.brand,
 COUNT( DISTINCT CASE WHEN (vd.final_date > poc.etd_required OR vd.final_date IS NULL OR vd.final_date = '0000-00-00') THEN poc.po_id END) AS shortQty,
COUNT( DISTINCT CASE WHEN (vd.final_date <= poc.etd_required AND vd.final_date IS NOT NULL AND  vd.final_date != '0000-00-00') THEN poc.po_id END) AS ontime,
COUNT(DISTINCT(poc.po_id)) AS total_order
 FROM
 tbl_po_colors AS poc LEFT JOIN tbl_vsr_details AS vd ON poc.po_id=vd.po_id
 LEFT JOIN tbl_po AS po ON poc.po_id= po.id
 LEFT JOIN tbl_departments AS d ON FIND_IN_SET(po.brand_id,d.brands)
 LEFT JOIN tbl_brands AS b ON po.brand_id=b.id
  WHERE
  poc.etd_required BETWEEN '$Week_Start_Date' and '$Week_End_Date' and d.id = $Department  GROUP BY po.brand_id ORDER BY shortQty DESC";


$sSQL = "SELECT b.id, b.brand,
 		SUM(IF(vsr.production_status =2,pc.order_qty,0)) AS _late,
 		SUM(IF(vsr.production_status =1,pc.order_qty,0)) AS _ready,
		SUM(IF(vsr.production_status =3,pc.order_qty,0)) AS _orange,
	 	SUM(IF(vsr.production_status =0,pc.order_qty,0)) AS _nostatus,
		SUM(pc.order_qty) AS total
FROM tbl_po po, tbl_po_colors pc, tbl_vsr_details vsr, tbl_departments d, tbl_brands b
WHERE po.id=pc.po_id AND (pc.etd_required BETWEEN '$Week_Start_Date' and '$Week_End_Date')
AND vsr.color_id=pc.id AND vsr.po_id=po.id AND FIND_IN_SET(po.brand_id, d.brands)
AND b.id = po.brand_id AND d.id =$Department
GROUP BY po.brand_id
HAVING total > 0
ORDER BY total DESC";

/*
$sSQL = "SELECT d.id, d.department,
 SUM(IF(vsr.production_status =2,pc.order_qty,0)) AS _late,
 SUM(IF(vsr.production_status =1,pc.order_qty,0)) AS _ready,
	 SUM(IF(vsr.production_status =3,pc.order_qty,0)) AS _orange,
	 SUM(IF(vsr.production_status =0,pc.order_qty,0)) AS _nostatus,
	SUM(pc.order_qty) AS total
FROM tbl_po po, tbl_po_colors pc, tbl_vsr_details vsr, tbl_departments d
WHERE po.id=pc.po_id AND (pc.etd_required BETWEEN '$Week_Start_Date' and '$Week_End_Date')
AND vsr.color_id=pc.id AND vsr.po_id=po.id AND FIND_IN_SET(po.brand_id, d.brands)
GROUP BY d.id
HAVING total >0
ORDER BY total DESC";
*/

 	$objDb->query($sSQL);
	$iCount = $objDb->getCount( );

	$VesselWeeks=array() ;

	for ($i = 0; $i < $iCount; $i ++)
	{
		$depart = $objDb->getField($i, 'id');

		$VesselWeeks[$depart]["name"] = $objDb->getField($i, 'brand');
		$VesselWeeks[$depart]["late"] = $objDb->getField($i, '_late');
		$VesselWeeks[$depart]["ready"] = $objDb->getField($i, '_ready');
		$VesselWeeks[$depart]["orange"] = $objDb->getField($i, '_orange');
		$VesselWeeks[$depart]["nostatus"] = $objDb->getField($i, '_nostatus');

	}


		$width = (100/count($VesselWeeks))-1;

		$Ordered_PO_STATUS = array();

		$late_pos = "";
		$strColor = "";


		foreach($VesselWeeks as $dep => $value){

			$total = $value["late"] +  $value["ready"] + $value["orange"]+ $value["nostatus"];

			$late 	= round(($value["late"]/$total)*100,2);
			$done 	= round(($value["ready"]/$total)*100,2);
			$progress = round(($value["orange"]/$total)*100,2);

?>
			<div style='font-size:9px;padding:1px;width:<?=$width;?>%;border-left:1px dotted #cccccc;height:280px;position:relative;float:left; overflow:hidden;'>

			<div class="graph" style="height: 200px;">
		        <div title="<?=$done."%"?>" onclick="fetchGates('<? print SITE_URL."ajax/vsr/fetch-gates.php?CurrentDate=$CurrentDate&Vendor=$Vendor&Brand=$Brand&Region=$Region&Season=$Season&Department=$dep&Week_Start_Date=$Week_Start_Date&Week_End_Date=$Week_End_Date"; ?>');" style="height:<?=$done;?>%;background-color:#7ea43d;text-align:center;" > <? echo $showGreen;?></div>
		        <div title="<?=$late."%"?>" onclick="fetchGates('<? print SITE_URL."ajax/vsr/fetch-gates.php?CurrentDate=$CurrentDate&Vendor=$Vendor&Brand=$Brand&Region=$Region&Season=$Season&Department=$dep&Week_Start_Date=$Week_Start_Date&Week_End_Date=$Week_End_Date"; ?>');" style="height:<?=$late;?>%;background-color:red;text-align:center;"> <? echo $showRed;?></div>
        		<div title="<?=$progress."%"?>" onclick="fetchGates('<? print SITE_URL."ajax/vsr/fetch-gates.php?CurrentDate=$CurrentDate&Vendor=$Vendor&Brand=$Brand&Region=$Region&Season=$Season&Department=$dep&Week_Start_Date=$Week_Start_Date&Week_End_Date=$Week_End_Date"; ?>');" style="height:<?=$progress;?>%;background-color:orange;text-align:center;"> <? echo $showRed;?></div>
			</div>

			<?= $value["name"] ?> <br />

			<font color="grey">Order Qty: <?=$total;?></font> <br />
			<font color="#7ea43d">On time: <?=$value["ready"]?$value["ready"]:0;?> Pcs</font> <br />
			<font color="#000000">no status: <?=$value["nostatus"]?$value["nostatus"]:0;?> Pcs</font> <br />
			<font color="red">Late: <?=number_format($value["late"]);?></font><br />
			<font color="orange">Risky: <?=($value["orange"]?number_format($value["orange"]):0);?> Pcs</font>

			</div>
<?
		}
?>

				</div>
			</td>
		</tr>

		</table>


	<?

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>