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

	$User     = IO::intValue("User");
	$Region   = IO::intValue("Region");
	$Vendor   = IO::intValue("Vendor");
	$Brand    = IO::intValue("Brand");
	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");
	$Season   = IO::intValue("Season");

	if ($FromDate == "" || $ToDate == "")
	{
		$FromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 30), date("Y")));
		$ToDate   = date("Y-m-d");
	}

	if (@strpos($Vendor, ",") !== FALSE)
		$Vendor = 0;

	if (@strpos($Brand, ",") !== FALSE)
		$Brand = 0;


	$sUserVendors = getDbValue("vendors", "tbl_users", "id='$User'");
	$sUserBrands  = getDbValue("brands", "tbl_users", "id='$User'");


	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ($sUserVendors) AND parent_id='0'");
	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id>'0' AND id IN ($sUserBrands)");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/fusion-charts/FusionCharts.js"></script>
</head>

<body style="background:#ffffff; padding:15px;">

<?
	$iOrderQty  = array( );
	$iOnTimeQty = array( );
	$sFromDate  = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 30), date("Y")));
	$sToDate    = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y")));


	$sSQL = "SELECT po.brand_id, SUM(pq.quantity)
			 FROM tbl_po po, tbl_po_quantities pq, tbl_po_colors pc
			 WHERE po.id=pq.po_id AND po.id=pc.po_id AND pc.id=pq.color_id";

	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSQL .= " AND po.vendor_id IN ($sUserVendors) ";


	if ($Region > 0)
		$sSQL .= "AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0') ";


	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' AND po.brand_id NOT IN (57,43,63) ";

	else
		$sSQL .= " AND po.brand_id IN ($sUserBrands) AND po.brand_id NOT IN (57,43,63) ";


	if ($Season > 0)
		$sSQL .= " AND pc.style_id IN (SELECT id FROM tbl_styles WHERE sub_season_id='$Season') ";


	$sSQL .= " AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate')
			   GROUP BY po.brand_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrandId  = $objDb->getField($i, 0);
		$iQuantity = $objDb->getField($i, 1);

		$iOrderQty[$iBrandId] = $iQuantity;
	}


	$sSQL = "SELECT po.brand_id, SUM(pq.quantity)
			 FROM tbl_po po, tbl_po_quantities pq, tbl_po_colors pc, tbl_vsr vsr
			 WHERE po.id=pq.po_id AND po.id=pc.po_id AND pc.id=pq.color_id AND po.id=vsr.po_id";

	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSQL .= " AND po.vendor_id IN ($sUserVendors) ";


	if ($Region > 0)
		$sSQL .= "AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0') ";


	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' AND po.brand_id NOT IN (57,43,63) ";

	else
		$sSQL .= " AND po.brand_id IN ($sUserBrands) AND po.brand_id NOT IN (57,43,63) ";


	if ($Season > 0)
		$sSQL .= " AND pc.style_id IN (SELECT id FROM tbl_styles WHERE sub_season_id='$Season') ";


	$sSQL .= " AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate')
	           AND vsr.final_audit_date != '0000-00-00' AND NOT ISNULL(vsr.final_audit_date)
	           AND IF ( po.brand_id='32', (DATE_ADD(pc.etd_required, INTERVAL 2 DAY) >= vsr.final_audit_date),  (pc.etd_required >= vsr.final_audit_date) )
			   GROUP BY po.brand_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrandId  = $objDb->getField($i, 0);
		$iQuantity = $objDb->getField($i, 1);

		$iOnTimeQty[$iBrandId] = $iQuantity;
	}


	$fOtp = @round(( (@array_sum($iOnTimeQty) / @array_sum($iOrderQty)) * 100), 2);
?>

						<div id="LastMonthOtpChart">loading...</div>

						<script type="text/javascript">
						<!--

						FusionCharts.setCurrentRenderer('javascript');

						var objChart = new FusionCharts("scripts/fusion-charts/widgets/AngularGauge.swf", "LastMonthOtp", "100%", "280", "0", "1");

						objChart.setXMLData("<chart caption='OTP of Last 30 Days' manageResize='1' origW='400' origH='280' manageValueOverlapping='1' autoAlignTickValues='1'  bgColor='AEC0CA,FFFFFF' fillAngle='45' upperLimit='100' lowerLimit='0' majorTMNumber='10' majorTMHeight='8' showGaugeBorder='0' gaugeOuterRadius='140' gaugeOriginX='205' gaugeOriginY='236' gaugeInnerRadius='2' formatNumberScale='1' numberPrefix='' numberSuffix='%' decmials='2' tickMarkDecimals='1' pivotRadius='17' showPivotBorder='1' pivotBorderColor='000000' pivotBorderThickness='5' pivotFillMix='FFFFFF,000000' tickValueDistance='10' >" +
											"<colorRange>" +
											"<color minValue='0' maxValue='50' code='B41527'/>" +
											"<color minValue='50' maxValue='80' code='E48739'/>" +
											"<color minValue='80' maxValue='100' code='399E38'/>" +
											"</colorRange>" +

											"<dials>" +
											"<dial value='<?= $fOtp ?>' showValue='1' toolText='' borderAlpha='0' bgColor='000000' baseWidth='28' topWidth='1' radius='130'/>" +
											"</dials>" +

											"<annotations>" +
											"<annotationGroup x='205' y='237.5'>" +
											"<annotation type='circle' x='0' y='2.5' radius='150' startAngle='0' endAngle='180' fillPattern='linear' fillAsGradient='1' fillColor='dddddd,666666' fillAlpha='100,100'  fillRatio='50,50' fillAngle='0' showBorder='1' borderColor='444444' borderThickness='2'/>" +
											"<annotation type='circle' x='0' y='0' radius='145' startAngle='0' endAngle='180' fillPattern='linear' fillAsGradient='1' fillColor='666666,ffffff' fillAlpha='100,100'  fillRatio='50,50' fillAngle='0' />" +
											"</annotationGroup>" +
											"</annotations>" +

										    "</chart>");


						objChart.render("LastMonthOtpChart");
						-->
						</script>

						<hr />




<?
	$iOrderQty  = array( );
	$iOnTimeQty = array( );
	$sFromDate  = date("Y-m-d");
	$sToDate    = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") + 14), date("Y")));


	$sSQL = "SELECT po.brand_id, SUM(pq.quantity)
			 FROM tbl_po po, tbl_po_quantities pq, tbl_po_colors pc
			 WHERE po.id=pq.po_id AND po.id=pc.po_id AND pc.id=pq.color_id";

	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSQL .= " AND po.vendor_id IN ($sUserVendors) ";


	if ($Region > 0)
		$sSQL .= "AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0') ";


	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' AND po.brand_id NOT IN (57,43,63) ";

	else
		$sSQL .= " AND po.brand_id IN ($sUserBrands) AND po.brand_id NOT IN (57,43,63) ";


	if ($Season > 0)
		$sSQL .= " AND pc.style_id IN (SELECT id FROM tbl_styles WHERE sub_season_id='$Season') ";


	$sSQL .= " AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate')
			   GROUP BY po.brand_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrandId  = $objDb->getField($i, 0);
		$iQuantity = $objDb->getField($i, 1);

		$iOrderQty[$iBrandId] = $iQuantity;
	}


	$sSQL = "SELECT po.brand_id, SUM(pq.quantity)
			 FROM tbl_po po, tbl_po_quantities pq, tbl_po_colors pc, tbl_vsr vsr
			 WHERE po.id=pq.po_id AND po.id=pc.po_id AND pc.id=pq.color_id AND po.id=vsr.po_id";

	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSQL .= " AND po.vendor_id IN ($sUserVendors) ";


	if ($Region > 0)
		$sSQL .= "AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0') ";


	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' AND po.brand_id NOT IN (57,43,63) ";

	else
		$sSQL .= " AND po.brand_id IN ($sUserBrands) AND po.brand_id NOT IN (57,43,63) ";


	if ($Season > 0)
		$sSQL .= " AND pc.style_id IN (SELECT id FROM tbl_styles WHERE sub_season_id='$Season') ";


	$sSQL .= " AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate')
	           AND vsr.final_audit_date != '0000-00-00' AND NOT ISNULL(vsr.final_audit_date)
	           AND IF ( po.brand_id='32', (DATE_ADD(pc.etd_required, INTERVAL 2 DAY) >= vsr.final_audit_date), (pc.etd_required >= vsr.final_audit_date) )
			   GROUP BY po.brand_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrandId  = $objDb->getField($i, 0);
		$iQuantity = $objDb->getField($i, 1);

		$iOnTimeQty[$iBrandId] = $iQuantity;
	}


	$fOtp = @round(( (@array_sum($iOnTimeQty) / @array_sum($iOrderQty)) * 100), 2);
?>
						<div id="FortnightOtpChart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/widgets/AngularGauge.swf", "FortnightOtp", "100%", "280", "0", "1");

						objChart.setXMLData("<chart caption='OTP of Next Fortnight' manageResize='1' origW='400' origH='280' manageValueOverlapping='1' autoAlignTickValues='1'  bgColor='AEC0CA,FFFFFF' fillAngle='45' upperLimit='100' lowerLimit='0' majorTMNumber='10' majorTMHeight='8' showGaugeBorder='0' gaugeOuterRadius='140' gaugeOriginX='205' gaugeOriginY='236' gaugeInnerRadius='2' formatNumberScale='1' numberPrefix='' numberSuffix='%' decmials='2' tickMarkDecimals='1' pivotRadius='17' showPivotBorder='1' pivotBorderColor='000000' pivotBorderThickness='5' pivotFillMix='FFFFFF,000000' tickValueDistance='10' >" +
											"<colorRange>" +
											"<color minValue='0' maxValue='50' code='B41527'/>" +
											"<color minValue='50' maxValue='80' code='E48739'/>" +
											"<color minValue='80' maxValue='100' code='399E38'/>" +
											"</colorRange>" +

											"<dials>" +
											"<dial value='<?= $fOtp ?>' showValue='1' toolText='' borderAlpha='0' bgColor='000000' baseWidth='28' topWidth='1' radius='130'/>" +
											"</dials>" +

											"<annotations>" +
											"<annotationGroup x='205' y='237.5'>" +
											"<annotation type='circle' x='0' y='2.5' radius='150' startAngle='0' endAngle='180' fillPattern='linear' fillAsGradient='1' fillColor='dddddd,666666' fillAlpha='100,100'  fillRatio='50,50' fillAngle='0' showBorder='1' borderColor='444444' borderThickness='2'/>" +
											"<annotation type='circle' x='0' y='0' radius='145' startAngle='0' endAngle='180' fillPattern='linear' fillAsGradient='1' fillColor='666666,ffffff' fillAlpha='100,100'  fillRatio='50,50' fillAngle='0' />" +
											"</annotationGroup>" +
											"</annotations>" +

										    "</chart>");

						objChart.render("FortnightOtpChart");
						-->
						</script>

						<hr />

<?
	$iOrderQty = array( );
	$iShipQty  = array( );


	$sSQL = "SELECT po.brand_id, SUM(pq.quantity)
			 FROM tbl_po po, tbl_po_quantities pq, tbl_po_colors pc
			 WHERE po.id=pq.po_id AND po.id=pc.po_id AND pc.id=pq.color_id AND po.status='C'";

	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSQL .= " AND po.vendor_id IN ($sUserVendors) ";


	if ($Region > 0)
		$sSQL .= "AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0') ";


	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' ";

	else
		$sSQL .= " AND po.brand_id IN ($sUserBrands) ";


	if ($Season > 0)
		$sSQL .= " AND pc.style_id IN (SELECT id FROM tbl_styles WHERE sub_season_id='$Season') ";


	$sSQL .= " AND (pc.etd_required BETWEEN '$FromDate' AND '$ToDate') AND pc.etd_required <= CURDATE( )
			   GROUP BY po.brand_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrandId  = $objDb->getField($i, 0);
		$iQuantity = $objDb->getField($i, 1);

		$iOrderQty[$iBrandId] = $iQuantity;
	}



	$sSQL = "SELECT po.brand_id, SUM(psq.quantity)
			 FROM tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq
			 WHERE po.id=pc.po_id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id AND po.status='C'";

	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSQL .= " AND po.vendor_id IN ($sUserVendors) ";


	if ($Region > 0)
		$sSQL .= "AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0') ";


	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' ";

	else
		$sSQL .= " AND po.brand_id IN ($sUserBrands) ";


	if ($Season > 0)
		$sSQL .= " AND pc.style_id IN (SELECT id FROM tbl_styles WHERE sub_season_id='$Season') ";


	$sSQL .= " AND (pc.etd_required BETWEEN '$FromDate' AND '$ToDate') AND pc.etd_required <= CURDATE( )
			   GROUP BY po.brand_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrandId  = $objDb->getField($i, 0);
		$iQuantity = $objDb->getField($i, 1);

		$iShipQty[$iBrandId] = $iQuantity;
	}


	$fDeviation = (@((@array_sum($iShipQty) / @array_sum($iOrderQty)) * 100) - 100);
?>
						<div id="DeviationChart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/widgets/AngularGauge.swf", "Deviation", "100%", "280", "0", "1");

						objChart.setXMLData("<chart caption='Deviation in Last 30 Days' manageResize='1' origW='400' origH='280' manageValueOverlapping='1' autoAlignTickValues='1'  bgColor='AEC0CA,FFFFFF' fillAngle='45' upperLimit='50' lowerLimit='-50' majorTMNumber='10' majorTMHeight='8' showGaugeBorder='0' gaugeOuterRadius='140' gaugeOriginX='205' gaugeOriginY='236' gaugeInnerRadius='2' formatNumberScale='1' numberPrefix='' numberSuffix='%' decmials='2' tickMarkDecimals='1' pivotRadius='17' showPivotBorder='1' pivotBorderColor='000000' pivotBorderThickness='5' pivotFillMix='FFFFFF,000000' tickValueDistance='10' >" +
											"<colorRange>" +
											"<color minValue='-50' maxValue='-5' code='B41527'/>" +
											"<color minValue='-5' maxValue='5' code='E48739'/>" +
											"<color minValue='5' maxValue='50' code='399E38'/>" +
											"</colorRange>" +

											"<dials>" +
											"<dial value='<?= $fDeviation ?>' showValue='1' toolText='' borderAlpha='0' bgColor='000000' baseWidth='28' topWidth='1' radius='130'/>" +
											"</dials>" +

											"<annotations>" +
											"<annotationGroup x='205' y='237.5'>" +
											"<annotation type='circle' x='0' y='2.5' radius='150' startAngle='0' endAngle='180' fillPattern='linear' fillAsGradient='1' fillColor='dddddd,666666' fillAlpha='100,100'  fillRatio='50,50' fillAngle='0' showBorder='1' borderColor='444444' borderThickness='2'/>" +
											"<annotation type='circle' x='0' y='0' radius='145' startAngle='0' endAngle='180' fillPattern='linear' fillAsGradient='1' fillColor='666666,ffffff' fillAlpha='100,100'  fillRatio='50,50' fillAngle='0' />" +
											"</annotationGroup>" +
											"</annotations>" +

										    "</chart>");

						objChart.render("DeviationChart");
						-->
						</script>

						<hr />


<?
	$sBrandSql  = "";
	$sSeasonSql = "";

	if ($Brand > 0)
		$sBrandSql = " sub_brand_id='$Brand' ";

	else
		$sBrandSql = " sub_brand_id IN ($sUserBrands) ";

	if ($Season > 0)
		$sSeasonSql = " AND sub_season_id='$Season' ";


	$sSQL = "SELECT COUNT(DISTINCT(style_id))
	         FROM tbl_merchandisings
	         WHERE (sent_2_sampling BETWEEN '$FromDate' AND '$ToDate') AND style_id IN (SELECT id FROM tbl_styles WHERE $sBrandSql $sSeasonSql)";
	$objDb->query($sSQL);

	$iTotalSamples  = $objDb->getField(0, 0);



	$sSQL = "SELECT COUNT(DISTINCT(pc.style_id))
	         FROM tbl_styles s, tbl_po_colors pc, tbl_merchandisings m
	         WHERE s.id=pc.style_id AND s.id=m.style_id AND (m.sent_2_sampling BETWEEN '$FromDate' AND '$ToDate')";

	if ($Brand > 0)
		$sSQL .= " AND s.sub_brand_id='$Brand' ";

	else
		$sSQL .= " AND s.sub_brand_id IN ($sUserBrands) ";

	if ($Season > 0)
		$sSQL .= " AND s.sub_season_id='$Season' ";

	$objDb->query($sSQL);

	$iSampleOrders  = $objDb->getField(0, 0);


	$fPerformance = @(($iSampleOrders / $iTotalSamples) * 100);
?>
						<div id="ConfirmationChart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/widgets/AngularGauge.swf", "Confirmation", "100%", "280", "0", "1");

						objChart.setXMLData("<chart caption='Order Confirmation Performance' manageResize='1' origW='400' origH='280' manageValueOverlapping='1' autoAlignTickValues='1'  bgColor='AEC0CA,FFFFFF' fillAngle='45' upperLimit='100' lowerLimit='0' majorTMNumber='10' majorTMHeight='8' showGaugeBorder='0' gaugeOuterRadius='140' gaugeOriginX='205' gaugeOriginY='236' gaugeInnerRadius='2' formatNumberScale='1' numberPrefix='' numberSuffix='%' decmials='2' tickMarkDecimals='1' pivotRadius='17' showPivotBorder='1' pivotBorderColor='000000' pivotBorderThickness='5' pivotFillMix='FFFFFF,000000' tickValueDistance='10' >" +
											"<colorRange>" +
											"<color minValue='0' maxValue='50' code='B41527'/>" +
											"<color minValue='50' maxValue='75' code='E48739'/>" +
											"<color minValue='75' maxValue='100' code='399E38'/>" +
											"</colorRange>" +

											"<dials>" +
											"<dial value='<?= $fPerformance ?>' showValue='1' toolText='' borderAlpha='0' bgColor='000000' baseWidth='28' topWidth='1' radius='130'/>" +
											"</dials>" +

											"<annotations>" +
											"<annotationGroup x='205' y='237.5'>" +
											"<annotation type='circle' x='0' y='2.5' radius='150' startAngle='0' endAngle='180' fillPattern='linear' fillAsGradient='1' fillColor='dddddd,666666' fillAlpha='100,100'  fillRatio='50,50' fillAngle='0' showBorder='1' borderColor='444444' borderThickness='2'/>" +
											"<annotation type='circle' x='0' y='0' radius='145' startAngle='0' endAngle='180' fillPattern='linear' fillAsGradient='1' fillColor='666666,ffffff' fillAlpha='100,100'  fillRatio='50,50' fillAngle='0' />" +
											"</annotationGroup>" +
											"</annotations>" +

										    "</chart>");


						objChart.render("ConfirmationChart");
						-->
						</script>

						<hr />

<?
	$sData = array( );

	$sSQL = "SELECT po.brand_id, DATEDIFF(MAX(etd.revised), MIN(etd.original))
			 FROM tbl_po po, tbl_po_colors pc, tbl_etd_revisions etd
			 WHERE po.id=pc.po_id AND po.id=etd.po_id AND (pc.etd_required BETWEEN '$FromDate' AND '$ToDate')";

	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSQL .= " AND po.vendor_id IN ($sUserVendors) ";


	if ($Region > 0)
		$sSQL .= "AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0') ";


	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' ";

	else
		$sSQL .= " AND po.brand_id IN ($sUserBrands) ";


	if ($Season > 0)
		$sSQL .= " AND pc.style_id IN (SELECT id FROM tbl_styles WHERE sub_season_id='$Season') ";

	$sSQL .= "GROUP BY etd.po_id
	          HAVING DATEDIFF(MAX(etd.revised), MIN(etd.original)) > 0";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrandId    = $objDb->getField($i, 0);
		$iDifference = $objDb->getField($i, 1);

		if (!isset($sData['Week1'][$iBrandId]) && !isset($sData['Week2'][$iBrandId]) && !isset($sData['Week3'][$iBrandId]) && !isset($sData['Week3p'][$iBrandId]))
		{
			$sData['Brands'][$iBrandId] = $sBrandsList[$iBrandId];
			$sData['Week1'][$iBrandId]  = 0;
			$sData['Week2'][$iBrandId]  = 0;
			$sData['Week3'][$iBrandId]  = 0;
			$sData['Week3p'][$iBrandId] = 0;
		}


		if ($iDifference >= 1 && $iDifference <= 7)
			$sData['Week1'][$iBrandId] ++;

		else if ($iDifference >= 8 && $iDifference <= 14)
			$sData['Week2'][$iBrandId] ++;

		else if ($iDifference >= 15 && $iDifference <= 21)
			$sData['Week3'][$iBrandId] ++;

		else if ($iDifference >= 22)
			$sData['Week3p'][$iBrandId] ++;
	}


	$sBrands = array( );
	$iWeek1  = array( );
	$iWeek2  = array( );
	$iWeek3  = array( );
	$iWeek3p = array( );
	$iIndex  = 0;

	foreach ($sData['Brands'] as $iBrandId => $sBrand)
	{
		if ($sData['Week1'][$iBrandId] > 0 || $sData['Week2'][$iBrandId] > 0 || $sData['Week3'][$iBrandId] > 0 || $sData['Week3p'][$iBrandId] > 0)
		{
			$sBrands[$iIndex] = $sBrand;
			$iWeek1[$iIndex]  = $sData['Week1'][$iBrandId];
			$iWeek2[$iIndex]  = $sData['Week2'][$iBrandId];
			$iWeek3[$iIndex]  = $sData['Week3'][$iBrandId];
			$iWeek3p[$iIndex] = $sData['Week3p'][$iBrandId];

			$iIndex ++;
		}
	}
?>
						<div id="EtdRevisionsChart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/StackedColumn3D.swf", "EtdRevisions", "100%", "400", "0", "1");

						objChart.setXMLData("<chart caption='ETD Revisions' numDivLines='10' formatNumberScale='0' showValues='0' showSum='1' showLabels='1' decimals='1' numberSuffix='' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='ROTATE' slantLabels='1'>" +

											"<categories>" +
<?
	foreach ($sData['Brands'] as $iBrandId => $sBrand)
	{
?>
											"<category label='<?= $sBrand ?>' />" +
<?
	}
?>
											"</categories>" +


											"<dataset seriesName='Week 1' color='99ff99'>" +
<?
	foreach ($sData['Brands'] as $iBrandId => $sBrand)
	{
?>
											"<set value='<?= formatNumber($sData['Week1'][$iBrandId], false) ?>' link='' />" +
<?
	}
?>
											"</dataset>" +

											"<dataset seriesName='Week 2' color='ffff00'>" +
<?
	foreach ($sData['Brands'] as $iBrandId => $sBrand)
	{
?>
											"<set value='<?= formatNumber($sData['Week2'][$iBrandId], false) ?>' link='' />" +
<?
	}
?>
											"</dataset>" +

											"<dataset seriesName='Week 3' color='eb8d8d'>" +
<?
	foreach ($sData['Brands'] as $iBrandId => $sBrand)
	{
?>
											"<set value='<?= formatNumber($sData['Week3'][$iBrandId], false) ?>' link='' />" +
<?
	}
?>
											"</dataset>" +

											"<dataset seriesName='Week 3+' color='ff3333'>" +
<?
	foreach ($sData['Brands'] as $iBrandId => $sBrand)
	{
?>
											"<set value='<?= formatNumber($sData['Week3p'][$iBrandId], false) ?>' link='' />" +
<?
	}
?>
											"</dataset>" +

										"</chart>");


						objChart.render("EtdRevisionsChart");
						-->
						</script>

						<hr />

<?
	$sData       = array( );
	$sLabels     = array( );
	$sReasons    = array( );
	$iReasons    = array( );
	$sConditions = "";


	if ($Vendor > 0)
		$sConditions .= " AND po.vendor_id='$Vendor' ";

	else
		$sConditions .= " AND po.vendor_id IN ($sUserVendors) ";


	if ($Region > 0)
		$sConditions .= "AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0') ";


	if ($Brand > 0)
		$sConditions .= " AND po.brand_id='$Brand' ";

	else
		$sConditions .= " AND po.brand_id IN ($sUserBrands) ";


	$sSQL = "SELECT err.reason_id, COUNT(*)
			 FROM tbl_etd_revision_requests err, tbl_po po
			 WHERE err.po_id=po.id AND (DATE_FORMAT(err.date_time, '%Y-%m-%d') BETWEEN '$FromDate' AND '$ToDate') AND err.reason_id>'0' $sConditions
			 GROUP BY err.reason_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iReasonId = $objDb->getField($i, 0);
		$iRequests = $objDb->getField($i, 1);


		$sSQL = "SELECT parent_id FROM tbl_etd_revision_reasons WHERE id=(SELECT parent_id FROM tbl_etd_revision_reasons WHERE id='$iReasonId')";
		$objDb2->query($sSQL);

		$iReasonId = $objDb2->getField(0, 0);


		$sReasons[$iReasonId] += $iRequests;
	}


	foreach ($sReasons as $iReasonId => $iRequests)
	{
		$sSQL = "SELECT reason FROM tbl_etd_revision_reasons WHERE id='$iReasonId'";
		$objDb->query($sSQL);

		$iReasons[] = $iReasonId;
		$sData[]    = $iRequests;
		$sLabels[]  = $objDb->getField(0, 0);
	}
?>
						<div id="EtdClassificationsChart">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/StackedColumn3D.swf", "EtdClassifications", "100%", "400", "0", "1");

						objChart.setXMLData("<chart caption='ETD Revision Classification' numDivLines='10' formatNumberScale='0' showValues='0' showSum='1' showLabels='1' decimals='1' numberSuffix='' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='AUTO'>" +

											"<categories>" +
<?
	for ($i = 0; $i < count($sLabels); $i ++)
	{
?>
											"<category label='<?= $sLabels[$i] ?>' />" +
<?
	}
?>
											"</categories>" +


											"<dataset>" +
<?
	for ($i = 0; $i < count($sData); $i ++)
	{
?>
											"<set value='<?= $sData[$i] ?>' link='' />" +
<?
	}
?>
											"</dataset>" +

										"</chart>");


						objChart.render("EtdClassificationsChart");
						-->
						</script>


</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>