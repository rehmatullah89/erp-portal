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

	@session_start( );

	@ini_set('display_errors', 0);
	@ini_set('log_errors', 0);
	@error_reporting(0);

	@ini_set("max_execution_time", 0);
	@ini_set("mysql.connect_timeout", -1);


	$sBaseDir = "C:/wamp/www/portal/";

	@require_once($sBaseDir."requires/configs.php");
	@require_once($sBaseDir."requires/db.class.php");
	@require_once($sBaseDir."requires/io.class.php");
	@require_once($sBaseDir."requires/PHPMailer/class.phpmailer.php");
	@require_once($sBaseDir."requires/common-functions.php");
	@require_once($sBaseDir."requires/chart.php");


	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$sVendorsList = getList("tbl_vendors", "id", "vendor", "parent_id='0' AND sourcing='Y'");
	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id!='0'");


	$sRestrictedVendors = "194";

	$sRestrictedBrands  = "130,77,167,256,260";
	$sRestrictedBrands .= (",".getDbValue("GROUP_CONCAT(id SEPARATOR ',')", "tbl_brands", "qmip='Y' AND parent_id>'0'"));


	$sFromDate = date("Y-m-d");
	$sToDate   = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") + 14), date("Y")));
	$sDir      = date("d-M-Y");

	@mkdir("{$sBaseDir}newsletter/{$sDir}");


	$iLimit = IO::intValue("Limit");

	/************************************  Pakistan  ********************************************/

	$iPos         = array( );
	$iOnTimePos   = array( );
	$iOrderQty    = array( );
	$iOnTimeQty   = array( );
	$iFinalAudits = array( );
	$iPakistanPos = array( );
	$iPakistanQty = array( );


	$sSQL = "SELECT po.brand_id, COUNT(DISTINCT(po.id)), SUM(pc.order_qty)
			 FROM tbl_po po, tbl_po_colors pc
			 WHERE po.id=pc.po_id AND po.order_nature='B' AND po.status!='C'
		           AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='162' AND parent_id='0' AND sourcing='Y')
		           AND (pc.etd_required BETWEEN DATE_SUB(CURDATE( ), INTERVAL 45 DAY) AND CURDATE( ))
		           AND po.id NOT IN (SELECT DISTINCT(po_id) FROM tbl_pre_shipment_detail WHERE NOT ISNULL(quantity) AND quantity > '0')
		           AND NOT FIND_IN_SET(po.brand_id, '$sRestrictedBrands')
		           AND NOT FIND_IN_SET(po.vendor_id, '$sRestrictedVendors')
			 GROUP BY po.brand_id";
	$objDb->query($sSQL);


	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrandId  = $objDb->getField($i, 0);
		$iQuantity = $objDb->getField($i, 2);

		$iPakistanPos[$iBrandId] = $objDb->getField($i, 1);
		$iPakistanQty[$iBrandId] = $iQuantity;
	}


	$sSQL = "SELECT po.brand_id, COUNT(DISTINCT(po.id)), SUM(pc.order_qty)
			 FROM tbl_po po, tbl_po_colors pc
			 WHERE po.id=pc.po_id AND po.order_nature='B'
		           AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='162' AND parent_id='0' AND sourcing='Y')
		           AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate')
		           AND NOT FIND_IN_SET(po.brand_id, '$sRestrictedBrands')
		           AND NOT FIND_IN_SET(po.vendor_id, '$sRestrictedVendors')
			 GROUP BY po.brand_id";
	$objDb->query($sSQL);


	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrandId  = $objDb->getField($i, 0);
		$iQuantity = $objDb->getField($i, 2);

		$iPos[$iBrandId]      = $objDb->getField($i, 1);
		$iOrderQty[$iBrandId] = $iQuantity;
	}



	$sSQL = "SELECT po.brand_id, COUNT(DISTINCT(po.id)), SUM(pc.order_qty)
			 FROM tbl_po po, tbl_po_colors pc, tbl_vsr vsr
			 WHERE po.id=pc.po_id AND po.id=vsr.po_id AND po.order_nature='B'
			       AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='162' AND parent_id='0' AND sourcing='Y')
			       AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate')
	               AND vsr.final_audit_date != '0000-00-00' AND NOT ISNULL(vsr.final_audit_date)
	               AND pc.etd_required >= vsr.final_audit_date
	               AND NOT FIND_IN_SET(po.brand_id, '$sRestrictedBrands')
	               AND NOT FIND_IN_SET(po.vendor_id, '$sRestrictedVendors')
			 GROUP BY po.brand_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrandId  = $objDb->getField($i, 0);
		$iQuantity = $objDb->getField($i, 2);

		$iOnTimePos[$iBrandId]   = $objDb->getField($i, 1);
		$iOnTimeQty[$iBrandId]   = $iQuantity;
		$iFinalAudits[$iBrandId] = 0;
	}



	$sSQL = "SELECT po.brand_id, COUNT(DISTINCT(po.id))
			 FROM tbl_po po, tbl_po_colors pc, tbl_vsr vsr
			 WHERE po.id=pc.po_id AND po.id=vsr.po_id AND po.order_nature='B'
			       AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='162' AND parent_id='0' AND sourcing='Y')
			       AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate')
	               AND vsr.final_audit_date != '0000-00-00' AND NOT ISNULL(vsr.final_audit_date)
	               AND NOT FIND_IN_SET(po.brand_id, '$sRestrictedBrands')
	               AND NOT FIND_IN_SET(po.vendor_id, '$sRestrictedVendors')
			 GROUP BY po.brand_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrandId                = $objDb->getField($i, 0);
		$iFinalAudits[$iBrandId] = $objDb->getField($i, 1);

		$iFinalAudits[$iBrandId] = (($iFinalAudits[$iBrandId] > $iPos[$iBrandId]) ? $iPos[$iBrandId] : $iFinalAudits[$iBrandId]);
	}


	$fOtp = @round(( (@array_sum($iOnTimeQty) / @array_sum($iOrderQty)) * 100), 2);



	// OTP Chart
	$objChart = new AngularMeter(276, 231);

	$objChart->setMeter(138, 128, 80, -135, 135);
	$objChart->setScale(0, 100, 10, 5, 1);
	$objChart->setLineWidth(0, 2, 1);

	$objChart->addRing(0, 85, metalColor(0xcccccc));
	$objChart->addRing(83, 85, 0x888888);

	$objChart->addZone(0, 50, 0xff3333);
	$objChart->addZone(50, 80, 0xffff00);
	$objChart->addZone(80, 100, 0x99ff99);

	$objChart->addText(140, 175, "OTP", "arialbd.ttf", 14, 0x555555, Center);
	$objChart->addText(2, 212, ("Order Qty = ".formatNumber(@array_sum($iOrderQty), false)), "tahoma.ttf", 8, 0x777777);
	$objChart->addText(150, 212, ("OnTime Qty  = ".formatNumber(@array_sum($iOnTimeQty), false)), "tahoma.ttf", 8, 0x777777);

	$objTextbox = $objChart->addText(140, 195, $objChart->formatValue($fOtp, "2"), "verdana", 9, 0xffffff, Center);
	$objTextbox->setBackground(0x000000, 0x000000, 0);

	$objChart->addTitle("Pakistan", "verdana.ttf", 15);
	$objChart->addPointer($fOtp, 0x40333399);


	// saving chart
	$sChart = "{$sBaseDir}newsletter/{$sDir}/pakistan-otp.png";

	$hChart = @fopen($sChart, "w");
	@fwrite($hChart, $objChart->makeChart2(PNG));
	@fclose($hChart);


	// Stats Chart
	$sBrands   = array( );
	$iTotalPos = array( );
	$iLatePos  = array( );
	$iNoFaPos  = array( );
	$fOtps     = array( );

	foreach ($iOrderQty AS $iBrandId => $iQuantity)
	{
		$sBrands[]   = $sBrandsList[$iBrandId];
		$iTotalPos[] = $iPos[$iBrandId];
		$iLatePos[]  = ($iPos[$iBrandId] - $iOnTimePos[$iBrandId]);
		$iNoFaPos[]  = ($iPos[$iBrandId] - $iFinalAudits[$iBrandId]);

		$fOtps[]     = @round(( ($iOnTimeQty[$iBrandId] / $iOrderQty[$iBrandId]) * 100), 2);
	}


	@array_multisort($fOtps, SORT_DESC,
					 $iTotalPos, SORT_DESC,
					 $iLatePos,
					 $iNoFaPos,
					 $sBrands);


	$objChart = new XYChart(850, 350);

	$objChart->setPlotArea(80, 40, 730, 220, 0xffffff, 0xf8f8f8, Transparent, $objChart->dashLineColor( 0xcccccc, DotLine), $objChart->dashLineColor(0xcccccc, DotLine));

	$objLegend = $objChart->addLegend(65, 5, false, "verdanab.ttf", 10);
	$objLegend->setBackground(Transparent);

	$objChart->xAxis->setLabels($sBrands);
	$objChart->xAxis->setTickOffset(0.5);

	$objYaxisLabel = $objChart->xAxis->setLabelStyle("tahoma.ttf", 8, 0x000000);
	$objYaxisLabel->setFontAngle(45);

	$objChart->yAxis->setTitle("OTP", "verdana.ttf", 15);
	$objChart->yAxis->setLabelFormat("{value|1}%");

	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);


	$objLineLayer = $objChart->addLineLayer2( );
	$objLineLayer->setLineWidth(2);

	$objDataSet = $objLineLayer->addDataSet($fOtps, 0xff00ff, "OTP");
	$objDataSet->setDataSymbol(SquareSymbol, 5);

	$objLineLayer->setDataLabelFormat("{value|1}%");


	$objBarLayer = $objChart->addBarLayer2(Side);
	$objBarLayer->addDataSet($iTotalPos, 0x00ff00, "Total POs");
	$objBarLayer->addDataSet($iLatePos, 0xff0000, "Late POs");
	$objBarLayer->addDataSet($iNoFaPos, 0x0000ff, "POs without F/A");

	$objBarLayer->setAggregateLabelFormat("{value}");
	$objBarLayer->setAggregateLabelStyle("tahoma.ttf", 7);

	$objBarLayer->setBarShape(CircleShape);
	$objBarLayer->setBarGap(0.2, TouchBar);


	// saving chart
	$sChart = "{$sBaseDir}newsletter/{$sDir}/pakistan-stats.png";

	$hChart = @fopen($sChart, "w");
	@fwrite($hChart, $objChart->makeChart2(PNG));
	@fclose($hChart);



	// stats table
	$sData  = array( );
	$iIndex = 0;

	foreach ($iOrderQty AS $iBrandId => $iQuantity)
	{
		$sData['Brand'][$iIndex]       = $sBrandsList[$iBrandId];
		$sData['Pos'][$iIndex]         = $iPos[$iBrandId];
		$sData['OnTimePos'][$iIndex]   = $iOnTimePos[$iBrandId];
		$sData['OrderQty'][$iIndex]    = $iQuantity;
		$sData['OnTimeQty'][$iIndex]   = $iOnTimeQty[$iBrandId];
		$sData['FinalAudits'][$iIndex] = $iFinalAudits[$iBrandId];
		$sData['OTP'][$iIndex]         = @round(( ($sData['OnTimeQty'][$iIndex] / $sData['OrderQty'][$iIndex]) * 100), 2);

		$iIndex ++;
	}

	@array_multisort($sData['OTP'], SORT_DESC,
					 $sData['Brand'],
					 $sData['OrderQty'],
					 $sData['OnTimeQty'],
					 $sData['Pos'],
					 $sData['OnTimePos'],
					 $sData['FinalAudits']);

	$sPakistan = $sData;






	/************************************  Bangladesh  ********************************************/

	$iPos           = array( );
	$iOnTimePos     = array( );
	$iOrderQty      = array( );
	$iOnTimeQty     = array( );
	$iFinalAudits   = array( );
	$iBangladeshPos = array( );
	$iBangladeshQty = array( );


	$sSQL = "SELECT po.brand_id, COUNT(DISTINCT(po.id)), SUM(pc.order_qty)
			 FROM tbl_po po, tbl_po_colors pc
			 WHERE po.id=pc.po_id AND po.order_nature='B' AND po.status!='C'
		           AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='18' AND parent_id='0' AND sourcing='Y')
		           AND (pc.etd_required BETWEEN DATE_SUB(CURDATE( ), INTERVAL 45 DAY) AND CURDATE( ))
		           AND po.id NOT IN (SELECT DISTINCT(po_id) FROM tbl_pre_shipment_detail WHERE NOT ISNULL(quantity) AND quantity > '0')
		           AND NOT FIND_IN_SET(po.brand_id, '$sRestrictedBrands')
		           AND NOT FIND_IN_SET(po.vendor_id, '$sRestrictedVendors')
			 GROUP BY po.brand_id";
	$objDb->query($sSQL);


	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrandId  = $objDb->getField($i, 0);
		$iQuantity = $objDb->getField($i, 2);

		$iBangladeshPos[$iBrandId] = $objDb->getField($i, 1);
		$iBangladeshQty[$iBrandId] = $iQuantity;
	}


	$sSQL = "SELECT po.brand_id, COUNT(DISTINCT(po.id)), SUM(pc.order_qty)
			 FROM tbl_po po, tbl_po_colors pc
			 WHERE po.id=pc.po_id AND po.order_nature='B'
		           AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='18' AND parent_id='0' AND sourcing='Y')
		           AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate')
		           AND NOT FIND_IN_SET(po.brand_id, '$sRestrictedBrands')
		           AND NOT FIND_IN_SET(po.vendor_id, '$sRestrictedVendors')
			 GROUP BY po.brand_id";
	$objDb->query($sSQL);


	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrandId  = $objDb->getField($i, 0);
		$iQuantity = $objDb->getField($i, 2);

		$iPos[$iBrandId]      = $objDb->getField($i, 1);
		$iOrderQty[$iBrandId] = $iQuantity;
	}



	$sSQL = "SELECT po.brand_id, COUNT(DISTINCT(po.id)), SUM(pc.order_qty)
			 FROM tbl_po po, tbl_po_colors pc, tbl_vsr vsr
			 WHERE po.id=pc.po_id AND po.id=vsr.po_id AND po.order_nature='B'
			       AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='18' AND parent_id='0' AND sourcing='Y')
			       AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate')
	               AND vsr.final_audit_date != '0000-00-00' AND NOT ISNULL(vsr.final_audit_date)
	               AND pc.etd_required >= vsr.final_audit_date
	               AND NOT FIND_IN_SET(po.brand_id, '$sRestrictedBrands')
	               AND NOT FIND_IN_SET(po.vendor_id, '$sRestrictedVendors')
			 GROUP BY po.brand_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrandId  = $objDb->getField($i, 0);
		$iQuantity = $objDb->getField($i, 2);

		$iOnTimePos[$iBrandId]   = $objDb->getField($i, 1);
		$iOnTimeQty[$iBrandId]   = $iQuantity;
		$iFinalAudits[$iBrandId] = 0;
	}



	$sSQL = "SELECT po.brand_id, COUNT(DISTINCT(po.id))
			 FROM tbl_po po, tbl_po_colors pc, tbl_vsr vsr
			 WHERE po.id=pc.po_id AND po.id=vsr.po_id AND po.order_nature='B'
			       AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='18' AND parent_id='0' AND sourcing='Y')
			       AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate')
	               AND vsr.final_audit_date != '0000-00-00' AND NOT ISNULL(vsr.final_audit_date)
	               AND NOT FIND_IN_SET(po.brand_id, '$sRestrictedBrands')
	               AND NOT FIND_IN_SET(po.vendor_id, '$sRestrictedVendors')
			 GROUP BY po.brand_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrandId                = $objDb->getField($i, 0);
		$iFinalAudits[$iBrandId] = $objDb->getField($i, 1);

		$iFinalAudits[$iBrandId] = (($iFinalAudits[$iBrandId] > $iPos[$iBrandId]) ? $iPos[$iBrandId] : $iFinalAudits[$iBrandId]);
	}


	$fOtp = @round(( (@array_sum($iOnTimeQty) / @array_sum($iOrderQty)) * 100), 2);



	// OTP Chart
	$objChart = new AngularMeter(276, 231);

	$objChart->setMeter(138, 128, 80, -135, 135);
	$objChart->setScale(0, 100, 10, 5, 1);
	$objChart->setLineWidth(0, 2, 1);

	$objChart->addRing(0, 85, metalColor(0xcccccc));
	$objChart->addRing(83, 85, 0x888888);

	$objChart->addZone(0, 50, 0xff3333);
	$objChart->addZone(50, 80, 0xffff00);
	$objChart->addZone(80, 100, 0x99ff99);

	$objChart->addText(140, 175, "OTP", "arialbd.ttf", 14, 0x555555, Center);
	$objChart->addText(2, 212, ("Order Qty = ".formatNumber(@array_sum($iOrderQty), false)), "tahoma.ttf", 8, 0x777777);
	$objChart->addText(150, 212, ("OnTime Qty  = ".formatNumber(@array_sum($iOnTimeQty), false)), "tahoma.ttf", 8, 0x777777);

	$objTextbox = $objChart->addText(140, 195, $objChart->formatValue($fOtp, "2"), "verdana", 9, 0xffffff, Center);
	$objTextbox->setBackground(0x000000, 0x000000, 0);

	$objChart->addTitle("Bangladesh", "verdana.ttf", 15);
	$objChart->addPointer($fOtp, 0x40333399);


	// saving chart
	$sChart = "{$sBaseDir}newsletter/{$sDir}/bangladesh-otp.png";

	$hChart = @fopen($sChart, "w");
	@fwrite($hChart, $objChart->makeChart2(PNG));
	@fclose($hChart);


	// Stats Chart
	$sBrands   = array( );
	$iTotalPos = array( );
	$iLatePos  = array( );
	$iNoFaPos  = array( );
	$fOtps     = array( );

	foreach ($iOrderQty AS $iBrandId => $iQuantity)
	{
		$sBrands[]   = $sBrandsList[$iBrandId];
		$iTotalPos[] = $iPos[$iBrandId];
		$iLatePos[]  = ($iPos[$iBrandId] - $iOnTimePos[$iBrandId]);
		$iNoFaPos[]  = ($iPos[$iBrandId] - $iFinalAudits[$iBrandId]);

		$fOtps[]     = @round(( ($iOnTimeQty[$iBrandId] / $iOrderQty[$iBrandId]) * 100), 2);
	}


	@array_multisort($fOtps, SORT_DESC,
					 $iTotalPos, SORT_DESC,
					 $iLatePos,
					 $iNoFaPos,
					 $sBrands);



	$objChart = new XYChart(850, 350);

	$objChart->setPlotArea(80, 40, 730, 220, 0xffffff, 0xf8f8f8, Transparent, $objChart->dashLineColor( 0xcccccc, DotLine), $objChart->dashLineColor(0xcccccc, DotLine));

	$objLegend = $objChart->addLegend(65, 5, false, "verdanab.ttf", 10);
	$objLegend->setBackground(Transparent);

	$objChart->xAxis->setLabels($sBrands);
	$objChart->xAxis->setTickOffset(0.5);

	$objYaxisLabel = $objChart->xAxis->setLabelStyle("tahoma.ttf", 8, 0x000000);
	$objYaxisLabel->setFontAngle(45);

	$objChart->yAxis->setTitle("OTP", "verdana.ttf", 15);
	$objChart->yAxis->setLabelFormat("{value|1}%");

	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);


	$objLineLayer = $objChart->addLineLayer2( );
	$objLineLayer->setLineWidth(2);

	$objDataSet = $objLineLayer->addDataSet($fOtps, 0xff00ff, "OTP");
	$objDataSet->setDataSymbol(SquareSymbol, 5);

	$objLineLayer->setDataLabelFormat("{value|1}%");


	$objBarLayer = $objChart->addBarLayer2(Side);
	$objBarLayer->addDataSet($iTotalPos, 0x00ff00, "Total POs");
	$objBarLayer->addDataSet($iLatePos, 0xff0000, "Late POs");
	$objBarLayer->addDataSet($iNoFaPos, 0x0000ff, "POs without F/A");

	$objBarLayer->setAggregateLabelFormat("{value}");
	$objBarLayer->setAggregateLabelStyle("tahoma.ttf", 7);

	$objBarLayer->setBarShape(CircleShape);
	$objBarLayer->setBarGap(0.2, TouchBar);


	// saving chart
	$sChart = "{$sBaseDir}newsletter/{$sDir}/bangladesh-stats.png";

	$hChart = @fopen($sChart, "w");
	@fwrite($hChart, $objChart->makeChart2(PNG));
	@fclose($hChart);



	// stats table
	$sData  = array( );
	$iIndex = 0;

	foreach ($iOrderQty AS $iBrandId => $iQuantity)
	{
		$sData['Brand'][$iIndex]       = $sBrandsList[$iBrandId];
		$sData['Pos'][$iIndex]         = $iPos[$iBrandId];
		$sData['OnTimePos'][$iIndex]   = $iOnTimePos[$iBrandId];
		$sData['OrderQty'][$iIndex]    = $iQuantity;
		$sData['OnTimeQty'][$iIndex]   = $iOnTimeQty[$iBrandId];
		$sData['FinalAudits'][$iIndex] = $iFinalAudits[$iBrandId];
		$sData['OTP'][$iIndex]         = @round(( ($sData['OnTimeQty'][$iIndex] / $sData['OrderQty'][$iIndex]) * 100), 2);

		$iIndex ++;
	}

	@array_multisort($sData['OTP'], SORT_DESC,
					 $sData['Brand'],
					 $sData['OrderQty'],
					 $sData['OnTimeQty'],
					 $sData['Pos'],
					 $sData['OnTimePos'],
					 $sData['FinalAudits']);

	$sBangladesh = $sData;



	/************************************  Matrix  ********************************************/

	$iPos         = array( );
	$iOnTimePos   = array( );
	$iOrderQty    = array( );
	$iOnTimeQty   = array( );
	$iFinalAudits = array( );
	$iMatrixPos   = array( );
	$iMatrixQty   = array( );


	$sSQL = "SELECT po.brand_id, COUNT(DISTINCT(po.id)), SUM(pc.order_qty)
			 FROM tbl_po po, tbl_po_colors pc
			 WHERE po.id=pc.po_id AND po.order_nature='B' AND po.status!='C'
		           AND (pc.etd_required BETWEEN DATE_SUB(CURDATE( ), INTERVAL 45 DAY) AND CURDATE( ))
		           AND po.id NOT IN (SELECT DISTINCT(po_id) FROM tbl_pre_shipment_detail WHERE NOT ISNULL(quantity) AND quantity > '0')
		           AND NOT FIND_IN_SET(po.brand_id, '$sRestrictedBrands')
		           AND NOT FIND_IN_SET(po.vendor_id, '$sRestrictedVendors')
			 GROUP BY po.brand_id";
	$objDb->query($sSQL);


	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrandId  = $objDb->getField($i, 0);
		$iQuantity = $objDb->getField($i, 2);

		$iMatrixPos[$iBrandId] = $objDb->getField($i, 1);
		$iMatrixQty[$iBrandId] = $iQuantity;
	}



	$sSQL = "SELECT po.brand_id, COUNT(DISTINCT(po.id)), SUM(pc.order_qty)
			 FROM tbl_po po, tbl_po_colors pc
			 WHERE po.id=pc.po_id AND po.order_nature='B'
		           AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate')
		           AND NOT FIND_IN_SET(po.brand_id, '$sRestrictedBrands')
		           AND NOT FIND_IN_SET(po.vendor_id, '$sRestrictedVendors')
			 GROUP BY po.brand_id";
	$objDb->query($sSQL);


	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrandId  = $objDb->getField($i, 0);
		$iQuantity = $objDb->getField($i, 2);

		$iPos[$iBrandId]      = $objDb->getField($i, 1);
		$iOrderQty[$iBrandId] = $iQuantity;
	}



	$sSQL = "SELECT po.brand_id, COUNT(DISTINCT(po.id)), SUM(pc.order_qty)
			 FROM tbl_po po, tbl_po_colors pc, tbl_vsr vsr
			 WHERE po.id=pc.po_id AND po.id=vsr.po_id AND po.order_nature='B'
			       AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate')
	               AND vsr.final_audit_date != '0000-00-00' AND NOT ISNULL(vsr.final_audit_date)
	               AND pc.etd_required >= vsr.final_audit_date
	               AND NOT FIND_IN_SET(po.brand_id, '$sRestrictedBrands')
	               AND NOT FIND_IN_SET(po.vendor_id, '$sRestrictedVendors')
			 GROUP BY po.brand_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrandId  = $objDb->getField($i, 0);
		$iQuantity = $objDb->getField($i, 2);

		$iOnTimePos[$iBrandId]   = $objDb->getField($i, 1);
		$iOnTimeQty[$iBrandId]   = $iQuantity;
		$iFinalAudits[$iBrandId] = 0;
	}



	$sSQL = "SELECT po.brand_id, COUNT(DISTINCT(po.id))
			 FROM tbl_po po, tbl_po_colors pc, tbl_vsr vsr
			 WHERE po.id=pc.po_id AND po.id=vsr.po_id AND po.order_nature='B'
			       AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate')
	               AND vsr.final_audit_date != '0000-00-00' AND NOT ISNULL(vsr.final_audit_date)
	               AND NOT FIND_IN_SET(po.brand_id, '$sRestrictedBrands')
	               AND NOT FIND_IN_SET(po.vendor_id, '$sRestrictedVendors')
			 GROUP BY po.brand_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrandId                = $objDb->getField($i, 0);
		$iFinalAudits[$iBrandId] = $objDb->getField($i, 1);

		$iFinalAudits[$iBrandId] = (($iFinalAudits[$iBrandId] > $iPos[$iBrandId]) ? $iPos[$iBrandId] : $iFinalAudits[$iBrandId]);
	}


	$fOtp = @round(( (@array_sum($iOnTimeQty) / @array_sum($iOrderQty)) * 100), 2);



	// OTP Chart
	$objChart = new AngularMeter(276, 231);

	$objChart->setMeter(138, 128, 80, -135, 135);
	$objChart->setScale(0, 100, 10, 5, 1);
	$objChart->setLineWidth(0, 2, 1);

	$objChart->addRing(0, 85, metalColor(0xcccccc));
	$objChart->addRing(83, 85, 0x888888);

	$objChart->addZone(0, 50, 0xff3333);
	$objChart->addZone(50, 80, 0xffff00);
	$objChart->addZone(80, 100, 0x99ff99);

	$objChart->addText(140, 175, "OTP", "arialbd.ttf", 14, 0x555555, Center);
	$objChart->addText(2, 212, ("Order Qty = ".formatNumber(@array_sum($iOrderQty), false)), "tahoma.ttf", 8, 0x777777);
	$objChart->addText(150, 212, ("OnTime Qty  = ".formatNumber(@array_sum($iOnTimeQty), false)), "tahoma.ttf", 8, 0x777777);

	$objTextbox = $objChart->addText(140, 195, $objChart->formatValue($fOtp, "2"), "verdana", 9, 0xffffff, Center);
	$objTextbox->setBackground(0x000000, 0x000000, 0);

	$objChart->addTitle("Matrix", "verdana.ttf", 15);
	$objChart->addPointer($fOtp, 0x40333399);


	// saving chart
	$sChart = "{$sBaseDir}newsletter/{$sDir}/matrix-otp.png";

	$hChart = @fopen($sChart, "w");
	@fwrite($hChart, $objChart->makeChart2(PNG));
	@fclose($hChart);


	// Stats Chart
	$sBrands   = array( );
	$iTotalPos = array( );
	$iLatePos  = array( );
	$iNoFaPos  = array( );
	$fOtps     = array( );

	foreach ($iOrderQty AS $iBrandId => $iQuantity)
	{
		$sBrands[]   = $sBrandsList[$iBrandId];
		$iTotalPos[] = $iPos[$iBrandId];
		$iLatePos[]  = ($iPos[$iBrandId] - $iOnTimePos[$iBrandId]);
		$iNoFaPos[]  = ($iPos[$iBrandId] - $iFinalAudits[$iBrandId]);

		$fOtps[]     = @round(( ($iOnTimeQty[$iBrandId] / $iOrderQty[$iBrandId]) * 100), 2);
	}


	@array_multisort($fOtps, SORT_DESC,
					 $iTotalPos, SORT_DESC,
					 $iLatePos,
					 $iNoFaPos,
					 $sBrands);



	$objChart = new XYChart(850, 350);

	$objChart->setPlotArea(80, 40, 730, 220, 0xffffff, 0xf8f8f8, Transparent, $objChart->dashLineColor( 0xcccccc, DotLine), $objChart->dashLineColor(0xcccccc, DotLine));

	$objLegend = $objChart->addLegend(65, 5, false, "verdanab.ttf", 10);
	$objLegend->setBackground(Transparent);

	$objChart->xAxis->setLabels($sBrands);
	$objChart->xAxis->setTickOffset(0.5);

	$objYaxisLabel = $objChart->xAxis->setLabelStyle("tahoma.ttf", 8, 0x000000);
	$objYaxisLabel->setFontAngle(45);

	$objChart->yAxis->setTitle("OTP", "verdana.ttf", 15);
	$objChart->yAxis->setLabelFormat("{value|1}%");

	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);


	$objLineLayer = $objChart->addLineLayer2( );
	$objLineLayer->setLineWidth(2);

	$objDataSet = $objLineLayer->addDataSet($fOtps, 0xff00ff, "OTP");
	$objDataSet->setDataSymbol(SquareSymbol, 5);

	$objLineLayer->setDataLabelFormat("{value|1}%");


	$objBarLayer = $objChart->addBarLayer2(Side);
	$objBarLayer->addDataSet($iTotalPos, 0x00ff00, "Total POs");
	$objBarLayer->addDataSet($iLatePos, 0xff0000, "Late POs");
	$objBarLayer->addDataSet($iNoFaPos, 0x0000ff, "POs without F/A");

	$objBarLayer->setAggregateLabelFormat("{value}");
	$objBarLayer->setAggregateLabelStyle("tahoma.ttf", 7);

	$objBarLayer->setBarShape(CircleShape);
	$objBarLayer->setBarGap(0.2, TouchBar);


	// saving chart
	$sChart = "{$sBaseDir}newsletter/{$sDir}/matrix-stats.png";

	$hChart = @fopen($sChart, "w");
	@fwrite($hChart, $objChart->makeChart2(PNG));
	@fclose($hChart);



	// stats table
	$sData  = array( );
	$iIndex = 0;

	foreach ($iOrderQty AS $iBrandId => $iQuantity)
	{
		$sData['Brand'][$iIndex]       = $sBrandsList[$iBrandId];
		$sData['Pos'][$iIndex]         = $iPos[$iBrandId];
		$sData['OnTimePos'][$iIndex]   = $iOnTimePos[$iBrandId];
		$sData['OrderQty'][$iIndex]    = $iQuantity;
		$sData['OnTimeQty'][$iIndex]   = $iOnTimeQty[$iBrandId];
		$sData['FinalAudits'][$iIndex] = $iFinalAudits[$iBrandId];
		$sData['OTP'][$iIndex]         = @round(( ($sData['OnTimeQty'][$iIndex] / $sData['OrderQty'][$iIndex]) * 100), 2);

		$iIndex ++;
	}

	@array_multisort($sData['OTP'], SORT_DESC,
					 $sData['Brand'],
					 $sData['OrderQty'],
					 $sData['OnTimeQty'],
					 $sData['Pos'],
					 $sData['OnTimePos'],
					 $sData['FinalAudits']);

	$sMatrix = $sData;
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
  <title>Triple Tree Customer Portal</title>
</head>

<body style="margin:0px;">

<?
	@ob_start( );
?>
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

#MainDiv h2
{
  font-family     :  arial, verdana, sans-serif;
  font-weight     :  bold;
  font-size       :  21px;
  color           :  #ffffff;

  padding         :  0px;
  margin          :  0px;
  background      :  #777777;
}

#MainDiv h3
{
  font-family     :  arial, verdana, sans-serif;
  font-weight     :  normal;
  font-size       :  15px;
  color           :  #ffffff;

  padding         :  5px;
  margin          :  0px;
  background      :  #aaaaaa;
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
				  <table border="0" cellpadding="15" cellspacing="0" width="100%" bgcolor="#2a2a2a">
					<tr>
					  <td width="100%"><a href="<?= SITE_URL ?>" target="_blank"><img src="<?= SITE_URL ?>images/customer-portal.jpg" width="440" height="68" border="0" alt="" title="" /></a></td>
					</tr>
				  </table>
				</div>
<!--  Header Section Ends Here  -->


<!--  Body Section Starts Here  -->
				<div>
				  <table border="0" cellpadding="10" cellspacing="0" width="100%" bgcolor="#b6e500">
					<tr>
					  <td width="100%"><h1>Projected OTP of Next Fortnight (Based on current VSRs)</h1></td>
					</tr>
				  </table>

				  <br />

				  <table border="3" bordercolor="#b6e500" cellpadding="2" cellspacing="0" width="100%">
					<tr valign="top">
					  <td width="33.3%"><img src="<?= SITE_URL ?>newsletter/<?= $sDir ?>/pakistan-otp.png" border="0" alt="" title="" /></td>
					  <td width="33.4%" align="center"><img src="<?= SITE_URL ?>newsletter/<?= $sDir ?>/bangladesh-otp.png" border="0" alt="" title="" /></td>
					  <td width="33.3%" align="right"><img src="<?= SITE_URL ?>newsletter/<?= $sDir ?>/matrix-otp.png" border="0" alt="" title="" /></td>
					</tr>
				  </table>


				  <br />

				  <table border="0" cellpadding="10" cellspacing="0" width="100%" bgcolor="#b6e500">
					<tr>
					  <td width="100%"><h1>Pakistan</h1></td>
					</tr>
				  </table>

				  <br />

				  <div><img src="<?= SITE_URL ?>newsletter/<?= $sDir ?>/pakistan-stats.png" border="0" alt="" title="" /></div>

				  <table border="1" bordercolor="#e6e6e6" cellpadding="4" cellspacing="0" width="100%">
					<tr bgcolor="#d6d6d6">
					  <td width="20%"><b>Brand</b></td>
					  <td width="10%"><b>POs</b></td>
					  <td width="10%"><b>Late POs</b></td>
					  <td width="15%"><b>Qty</b></td>
					  <td width="15%"><b>On-Time</b></td>
					  <td width="15%"><b>POs without F/A</b></td>
					  <td width="15%"><b>OTP</b></td>
					</tr>
<?
	for ($i = 0; $i < count($sPakistan['Brand']); $i ++)
	{
		if ($sPakistan['OTP'][$i] > 80 && $sPakistan['OTP'][$i] <= 100)
			$sColor = "#99ff99";

		else if ($sPakistan['OTP'][$i] > 50 && $sPakistan['OTP'][$i] <= 80)
			$sColor = "#ffff00";

		else
			$sColor = "#ff3333";
?>
					<tr bgcolor="<?= $sColor ?>">
					  <td><?= $sPakistan['Brand'][$i] ?></td>
					  <td><?= formatNumber($sPakistan['Pos'][$i], false) ?></td>
					  <td><?= formatNumber( (intval($sPakistan['Pos'][$i]) - intval($sPakistan['OnTimePos'][$i])), false) ?></td>
					  <td><?= formatNumber($sPakistan['OrderQty'][$i], false) ?></td>
					  <td><?= formatNumber($sPakistan['OnTimeQty'][$i], false) ?></td>
					  <td><?= formatNumber( (intval($sPakistan['Pos'][$i]) - intval($sPakistan['FinalAudits'][$i])), false) ?></td>
					  <td><?= formatNumber($sPakistan['OTP'][$i]) ?>%</td>
					</tr>

<?
	}
?>
					<tr bgcolor="#dddddd">
					  <td><b>Total</b></td>
					  <td><b><?= formatNumber(@array_sum($sPakistan['Pos']), false) ?></b></td>
					  <td><b><?= formatNumber( (intval(@array_sum($sPakistan['Pos'])) - intval(@array_sum($sPakistan['OnTimePos']))), false) ?></b></td>
					  <td><b><?= formatNumber(@array_sum($sPakistan['OrderQty']), false) ?></b></td>
					  <td><b><?= formatNumber(@array_sum($sPakistan['OnTimeQty']), false) ?></b></td>
					  <td><b><?= formatNumber( (intval(@array_sum($sPakistan['Pos'])) - intval(@array_sum($sPakistan['FinalAudits']))), false) ?></b></td>
					  <td><b><?= @round(( (@array_sum($sPakistan['OnTimeQty']) / @array_sum($sPakistan['OrderQty'])) * 100), 2) ?>%</b></td>
					</tr>
				  </table>
<?
	if (count($iPakistanPos) > 0)
	{
?>
				  <br />

				  <table border="0" cellpadding="5" cellspacing="0" width="100%" bgcolor="#777777">
					<tr>
					  <td width="100%"><h2>POs in Escrow &nbsp; (Incomplete Data)</h2></td>
					</tr>

					<tr>
					  <td><h3>POs with ETD Range in Last 45 Days</h3></td>
					</tr>
				  </table>

				  <table border="1" bordercolor="#e6e6e6" cellpadding="4" cellspacing="0" width="100%">
					<tr bgcolor="#d6d6d6">
					  <td width="20%"><b>Brand</b></td>
					  <td width="15%"><b>POs</b></td>
					  <td width="65%"><b>Qty</b></td>
					</tr>

<?
		foreach($iPakistanPos as $iBrand => $iCount)
		{
?>
					<tr bgcolor="#f3f3f3">
					  <td><?= $sBrandsList[$iBrand] ?></td>
					  <td><a href="<?= SITE_URL ?>crons/export-unshipped-pos.php?Brand=<?= $iBrand ?>&Country=162"><?= formatNumber($iCount, false) ?></a></td>
					  <td><?= formatNumber($iPakistanQty[$iBrand], false) ?></td>

					</tr>

<?
		}
?>
					<tr bgcolor="#dddddd">
					  <td><b>Total</b></td>
					  <td><b><?= formatNumber(@array_sum($iPakistanPos), false) ?></b></td>
					  <td><b><?= formatNumber(@array_sum($iPakistanQty), false) ?></b></td>
					</tr>
				  </table>
<?
	}
?>
				  <br />
				  <br />

				  <table border="0" cellpadding="10" cellspacing="0" width="100%" bgcolor="#b6e500">
					<tr>
					  <td width="100%"><h1>Bangladesh</h1></td>
					</tr>
				  </table>

				  <br />

				  <div><img src="<?= SITE_URL ?>newsletter/<?= $sDir ?>/bangladesh-stats.png" border="0" alt="" title="" /></div>

				  <table border="1" bordercolor="#e6e6e6" cellpadding="4" cellspacing="0" width="100%">
					<tr bgcolor="#d6d6d6">
					  <td width="20%"><b>Brand</b></td>
					  <td width="10%"><b>POs</b></td>
					  <td width="10%"><b>Late POs</b></td>
					  <td width="15%"><b>Qty</b></td>
					  <td width="15%"><b>On-Time</b></td>
					  <td width="15%"><b>POs without F/A</b></td>
					  <td width="15%"><b>OTP</b></td>
					</tr>
<?
	for ($i = 0; $i < count($sBangladesh['Brand']); $i ++)
	{
		if ($sBangladesh['OTP'][$i] > 80 && $sBangladesh['OTP'][$i] <= 100)
			$sColor = "#99ff99";

		else if ($sBangladesh['OTP'][$i] > 50 && $sBangladesh['OTP'][$i] <= 80)
			$sColor = "#ffff00";

		else
			$sColor = "#ff3333";
?>
					<tr bgcolor="<?= $sColor ?>">
					  <td><?= $sBangladesh['Brand'][$i] ?></td>
					  <td><?= formatNumber($sBangladesh['Pos'][$i], false) ?></td>
					  <td><?= formatNumber( (intval($sBangladesh['Pos'][$i]) - intval($sBangladesh['OnTimePos'][$i])), false) ?></td>
					  <td><?= formatNumber($sBangladesh['OrderQty'][$i], false) ?></td>
					  <td><?= formatNumber($sBangladesh['OnTimeQty'][$i], false) ?></td>
					  <td><?= formatNumber( (intval($sBangladesh['Pos'][$i]) - intval($sBangladesh['FinalAudits'][$i])), false) ?></td>
					  <td><?= formatNumber($sBangladesh['OTP'][$i]) ?>%</td>
					</tr>

<?
	}
?>
					<tr bgcolor="#dddddd">
					  <td><b>Total</b></td>
					  <td><b><?= formatNumber(@array_sum($sBangladesh['Pos']), false) ?></b></td>
					  <td><b><?= formatNumber( (intval(@array_sum($sBangladesh['Pos'])) - intval(@array_sum($sBangladesh['OnTimePos']))), false) ?></b></td>
					  <td><b><?= formatNumber(@array_sum($sBangladesh['OrderQty']), false) ?></b></td>
					  <td><b><?= formatNumber(@array_sum($sBangladesh['OnTimeQty']), false) ?></b></td>
					  <td><b><?= formatNumber( (intval(@array_sum($sBangladesh['Pos'])) - intval(@array_sum($sBangladesh['FinalAudits']))), false) ?></b></td>
					  <td><b><?= @round(( (@array_sum($sBangladesh['OnTimeQty']) / @array_sum($sBangladesh['OrderQty'])) * 100), 2) ?>%</b></td>
					</tr>
				  </table>
<?
	if (count($iBangladeshPos) > 0)
	{
?>
				  <br />

				  <table border="0" cellpadding="5" cellspacing="0" width="100%" bgcolor="#777777">
					<tr>
					  <td width="100%"><h2>POs in Escrow &nbsp; (Incomplete Data)</h2></td>
					</tr>

					<tr>
					  <td><h3>POs with ETD Range in Last 45 Days</h3></td>
					</tr>
				  </table>

				  <table border="1" bordercolor="#e6e6e6" cellpadding="4" cellspacing="0" width="100%">
					<tr bgcolor="#d6d6d6">
					  <td width="20%"><b>Brand</b></td>
					  <td width="15%"><b>POs</b></td>
					  <td width="65%"><b>Qty</b></td>
					</tr>

<?
		foreach($iBangladeshPos as $iBrand => $iCount)
		{
?>
					<tr bgcolor="#f3f3f3">
					  <td><?= $sBrandsList[$iBrand] ?></td>
					  <td><a href="<?= SITE_URL ?>crons/export-unshipped-pos.php?Brand=<?= $iBrand ?>&Country=18"><?= formatNumber($iCount, false) ?></a></td>
					  <td><?= formatNumber($iBangladeshQty[$iBrand], false) ?></td>

					</tr>

<?
		}
?>
					<tr bgcolor="#dddddd">
					  <td><b>Total</b></td>
					  <td><b><?= formatNumber(@array_sum($iBangladeshPos), false) ?></b></td>
					  <td><b><?= formatNumber(@array_sum($iBangladeshQty), false) ?></b></td>
					</tr>
				  </table>
<?
	}
?>

				  <br />
				  <br />

				  <table border="0" cellpadding="10" cellspacing="0" width="100%" bgcolor="#b6e500">
					<tr>
					  <td width="100%"><h1>MATRIX</h1></td>
					</tr>
				  </table>

				  <br />

				  <div><img src="<?= SITE_URL ?>newsletter/<?= $sDir ?>/matrix-stats.png" border="0" alt="" title="" /></div>

				  <table border="1" bordercolor="#e6e6e6" cellpadding="4" cellspacing="0" width="100%">
					<tr bgcolor="#d6d6d6">
					  <td width="20%"><b>Brand</b></td>
					  <td width="10%"><b>POs</b></td>
					  <td width="10%"><b>Late POs</b></td>
					  <td width="15%"><b>Qty</b></td>
					  <td width="15%"><b>On-Time</b></td>
					  <td width="15%"><b>POs without F/A</b></td>
					  <td width="15%"><b>OTP</b></td>
					</tr>
<?
	for ($i = 0; $i < count($sMatrix['Brand']); $i ++)
	{
		if ($sMatrix['OTP'][$i] > 80 && $sMatrix['OTP'][$i] <= 100)
			$sColor = "#99ff99";

		else if ($sMatrix['OTP'][$i] > 50 && $sMatrix['OTP'][$i] <= 80)
			$sColor = "#ffff00";

		else
			$sColor = "#ff3333";
?>
					<tr bgcolor="<?= $sColor ?>">
					  <td><?= $sMatrix['Brand'][$i] ?></td>
					  <td><?= formatNumber($sMatrix['Pos'][$i], false) ?></td>
					  <td><?= formatNumber( (intval($sMatrix['Pos'][$i]) - intval($sMatrix['OnTimePos'][$i])), false) ?></td>
					  <td><?= formatNumber($sMatrix['OrderQty'][$i], false) ?></td>
					  <td><?= formatNumber($sMatrix['OnTimeQty'][$i], false) ?></td>
					  <td><?= formatNumber( (intval($sMatrix['Pos'][$i]) - intval($sMatrix['FinalAudits'][$i])), false) ?></td>
					  <td><?= formatNumber($sMatrix['OTP'][$i]) ?>%</td>
					</tr>

<?
	}
?>
					<tr bgcolor="#dddddd">
					  <td><b>Total</b></td>
					  <td><b><?= formatNumber(@array_sum($sMatrix['Pos']), false) ?></b></td>
					  <td><b><?= formatNumber( (intval(@array_sum($sMatrix['Pos'])) - intval(@array_sum($sMatrix['OnTimePos']))), false) ?></b></td>
					  <td><b><?= formatNumber(@array_sum($sMatrix['OrderQty']), false) ?></b></td>
					  <td><b><?= formatNumber(@array_sum($sMatrix['OnTimeQty']), false) ?></b></td>
					  <td><b><?= formatNumber( (intval(@array_sum($sMatrix['Pos'])) - intval(@array_sum($sMatrix['FinalAudits']))), false) ?></b></td>
					  <td><b><?= @round(( (@array_sum($sMatrix['OnTimeQty']) / @array_sum($sMatrix['OrderQty'])) * 100), 2) ?>%</b></td>
					</tr>
				  </table>

<?
	if (count($iMatrixPos) > 0)
	{
?>
				  <br />

				  <table border="0" cellpadding="5" cellspacing="0" width="100%" bgcolor="#777777">
					<tr>
					  <td width="100%"><h2>POs in Escrow &nbsp; (Incomplete Data)</h2></td>
					</tr>

					<tr>
					  <td><h3>POs with ETD Range in Last 45 Days</h3></td>
					</tr>
				  </table>

				  <table border="1" bordercolor="#e6e6e6" cellpadding="4" cellspacing="0" width="100%">
					<tr bgcolor="#d6d6d6">
					  <td width="20%"><b>Brand</b></td>
					  <td width="15%"><b>POs</b></td>
					  <td width="65%"><b>Qty</b></td>
					</tr>

<?
		foreach($iMatrixPos as $iBrand => $iCount)
		{
?>
					<tr bgcolor="#f3f3f3">
					  <td><?= $sBrandsList[$iBrand] ?></td>
					  <td><a href="<?= SITE_URL ?>crons/export-unshipped-pos.php?Brand=<?= $iBrand ?>"><?= formatNumber($iCount, false) ?></a></td>
					  <td><?= formatNumber($iMatrixQty[$iBrand], false) ?></td>

					</tr>

<?
		}
?>
					<tr bgcolor="#dddddd">
					  <td><b>Total</b></td>
					  <td><b><?= formatNumber(@array_sum($iMatrixPos), false) ?></b></td>
					  <td><b><?= formatNumber(@array_sum($iMatrixQty), false) ?></b></td>
					</tr>
				  </table>
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
					  <td width="100%">Copyright <?= date("Y") ?> &copy; Matrix Sourcing</td>
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

<?
	$sNewsletter = @ob_get_flush( );


	$sChart = "{$sBaseDir}newsletter/{$sDir}/newsletter.html";

	$hChart = @fopen($sChart, "w");
	@fwrite($hChart, $sNewsletter);
	@fclose($hChart);
?>

</body>
</html>
<?
	// Emailing newsletter
	$objEmail = new PHPMailer( );

	$objEmail->Subject  = "Projected OTP for next Fortnight";

	$objEmail->MsgHTML($sNewsletter);
	$objEmail->AddAddress("portal@apparelco.com", "Portal Users");
	$objEmail->Send( );

/*
	$sSQL = "SELECT name, email FROM tbl_users WHERE status='A' AND email_alerts='Y' AND (email LIKE '%@apparelco.com' OR email LIKE '%@3-tree.com') ORDER BY name LIMIT {$iLimit}, 30";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sName  = $objDb->getField($i, "name");
		$sEmail = $objDb->getField($i, "email");


		$objEmail = new PHPMailer( );

//		$objEmail->From     = SENDER_EMAIL;
//		$objEmail->FromName = SENDER_NAME;
		$objEmail->Subject  = "Projected OTP for next Fortnight";

		$objEmail->MsgHTML($sNewsletter);
		$objEmail->AddAddress($sEmail, $sName);
		$objEmail->Send( );
	}


	if ($iLimit == 0)
	{
		$objEmail = new PHPMailer( );
		$objEmail->Subject  = "Projected OTP for next Fortnight";
		$objEmail->MsgHTML($sNewsletter);
		$objEmail->AddAddress("deniz@atics.biz", "Deniz Thiede");
		$objEmail->Send( );
	}
*/

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>