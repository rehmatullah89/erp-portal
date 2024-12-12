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
	$objDb3      = new Database( );


	$WorkOrder = IO::strValue("WorkOrder");
	$Vendor    = IO::intValue("Vendor");
	$Brand     = IO::intValue("Brand");
	$Season    = IO::intValue("Season");
	$FromDate  = IO::strValue("FromDate");
	$ToDate    = IO::strValue("ToDate");
	$POs       = @implode(",", IO::getArray("Po"));

	$sVendorsList      = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList       = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ({$_SESSION['Brands']})");
	$sSeasonsList      = getList("tbl_seasons", "id", "season", "parent_id>'0'");
	$sDestinationsList = getList("tbl_destinations", "id", "destination");

	$sPrefix = "";

	if (@strpos($_SESSION["Email"], "@apparelco.com") === FALSE && @strpos($_SESSION["Email"], "@3-tree.com") === FALSE)
		$sPrefix = "vsr_";


	$sConditions = "WHERE po.id=pc.po_id AND pc.po_id=vd.po_id AND pc.style_id=vd.style_id AND pc.id=vd.color_id AND vsr.id=vd.work_order_id AND po.status!='C' AND po.accepted='Y' AND po.order_nature='B'";

	if ($Vendor > 0)
		$sConditions .= " AND po.vendor_id='$Vendor' ";

	else
		$sConditions .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Brand > 0)
		$sConditions .= " AND po.brand_id='$Brand' ";

	else
		$sConditions .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

	if ($Season > 0)
		$sConditions .= " AND pc.style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand' AND sub_season_id='$Season') ";

	if ($WorkOrder != "")
		$sConditions .= " AND vsr.work_order_no LIKE '%$WorkOrder%' ";

	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND (pc.{$sPrefix}etd_required BETWEEN '$FromDate' AND '$ToDate') ";

	if ($POs == "")
	{
		$sSQL = "SELECT DISTINCT(po.id) FROM tbl_po po, tbl_po_colors pc, tbl_vsr_details vd, tbl_vsr2 vsr $sConditions";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sPos   = "0";

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (",".$objDb->getField($i, 0));
	}

	else
		$sPos = $POs;


	$sSQL = "SELECT DISTINCT(pc.style_id) FROM tbl_po po, tbl_po_colors pc, tbl_vsr_details vd, tbl_vsr2 vsr $sConditions";
	$objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	$sStyles = "0";

	for ($i = 0; $i < $iCount; $i ++)
		$sStyles .= (",".$objDb->getField($i, 0));

	if ($sStyles == "0")
	{
		$sSqlConditions = "WHERE po.id=pc.po_id AND po.status!='C' AND po.order_nature='B'";

		if ($Vendor > 0)
			$sSqlConditions .= " AND po.vendor_id='$Vendor' ";

		else
			$sSqlConditions .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";

		if ($Brand > 0)
			$sSqlConditions .= " AND po.brand_id='$Brand' ";

		else
			$sSqlConditions .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

		if ($Season > 0)
			$sSqlConditions .= " AND pc.style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand' AND sub_season_id='$Season') ";

		if ($FromDate != "" && $ToDate != "")
			$sSqlConditions .= " AND (pc.{$sPrefix}etd_required BETWEEN '$FromDate' AND '$ToDate') ";


		$sSQL = "SELECT DISTINCT(pc.style_id) FROM tbl_po po, tbl_po_colors pc $sSqlConditions";
		$objDb->query($sSQL);

		$iCount  = $objDb->getCount( );
		$sStyles = "0";

		for ($i = 0; $i < $iCount; $i ++)
			$sStyles .= (",".$objDb->getField($i, 0));


		if ($POs == "")
		{
			$sSQL = "SELECT DISTINCT(po.id) FROM tbl_po po, tbl_po_colors pc $sSqlConditions";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );
			$sPos   = "0";

			for ($i = 0; $i < $iCount; $i ++)
				$sPos .= (",".$objDb->getField($i, 0));
		}
	}


	$sSQL = "SELECT DISTINCT(category_id) FROM tbl_styles WHERE FIND_IN_SET(id, '$sStyles')";
	$objDb->query($sSQL);

	$iCount      = $objDb->getCount( );
	$sCategories = "0";

	for ($i = 0; $i < $iCount; $i ++)
		$sCategories .= (",".$objDb->getField($i, 0));


	if ($Brand == 0)
	{
		$sSQL = "SELECT DISTINCT(sub_brand_id) FROM tbl_styles WHERE FIND_IN_SET(id, '$sStyles')";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
			$Brand = $objDb->getField(0, 0);
	}


	$sStages      = getDbValue("GROUP_CONCAT(stages SEPARATOR ',')", "tbl_style_categories", "FIND_IN_SET(id, '$sCategories')");
	$sBrandStages = "";

	if ($Brand > 0)
		$sBrandStages = (" AND FIND_IN_SET(id, '".getDbValue("stages", "tbl_brands", "id='$Brand'")."') ");


	$sSQL = "SELECT DISTINCT(id) FROM tbl_production_stages WHERE FIND_IN_SET(id, '$sStages') $sBrandStages ORDER BY position";
	$objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	$iStages = array( );

	for ($i = 0; $i < $iCount; $i ++)
		$iStages[] = $objDb->getField($i, 0);


	$sSQL = "SELECT DISTINCT(pq.size_id), s.size
			 FROM tbl_po_colors pc, tbl_po_quantities pq, tbl_sizes s
			 WHERE pc.id=pq.color_id AND pc.po_id=pq.po_id AND FIND_IN_SET(pc.po_id, '$sPos') AND FIND_IN_SET(pc.style_id, '$sStyles')
			 AND s.id=pq.size_id
			 ORDER BY s.position";
	$objDb->query($sSQL);

	$iCount     = $objDb->getCount( );
	$sSizesList =  array( );

	for ($i = 0; $i < $iCount; $i ++)
		$sSizesList[$objDb->getField($i, 0)] = $objDb->getField($i, 1);



	$sStagesList = getList("tbl_production_stages", "id", "title", "", "position");
	$sStagesType = getList("tbl_production_stages", "id", "type", "", "position");




	$sExcelFile = "Work-Order-Details.xlsx";

	set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');

	require_once 'PHPExcel.php';
	require_once 'PHPExcel/RichText.php';

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set properties
	$objPHPExcel->getProperties()->setCreator("Matrix Sourcing");
	$objPHPExcel->getProperties()->setLastModifiedBy("Matrix Sourcing");
	$objPHPExcel->getProperties()->setTitle("Work Order Details");
	$objPHPExcel->getProperties()->setSubject("");
	$objPHPExcel->getProperties()->setDescription("Work Order Details");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("Reports");

	// Create a first sheet
	$objPHPExcel->setActiveSheetIndex(0);


	$objPHPExcel->getActiveSheet()->setCellValue('A2', "M A T R I X    S O U R C I N G");
	$objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(28);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objPHPExcel->getActiveSheet()->setCellValue('A3', "Work Order Details");
	$objPHPExcel->getActiveSheet()->mergeCells('A3:G3');
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setSize(18);
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objPHPExcel->getActiveSheet()->mergeCells('A5:A6');
	$objPHPExcel->getActiveSheet()->setCellValue('A5', "Brand");

	$objPHPExcel->getActiveSheet()->mergeCells('B5:B6');
	$objPHPExcel->getActiveSheet()->setCellValue('B5', "Customer");

	$objPHPExcel->getActiveSheet()->mergeCells('C5:C6');
	$objPHPExcel->getActiveSheet()->setCellValue('C5', "Season");

	$objPHPExcel->getActiveSheet()->mergeCells('D5:D6');
	$objPHPExcel->getActiveSheet()->setCellValue('D5', "Style #");

	$objPHPExcel->getActiveSheet()->mergeCells('E5:E6');
	$objPHPExcel->getActiveSheet()->setCellValue('E5', "Style Description");

	$objPHPExcel->getActiveSheet()->mergeCells('F5:F6');
	$objPHPExcel->getActiveSheet()->setCellValue('F5', "Destination");

	$objPHPExcel->getActiveSheet()->mergeCells('G5:G6');
	$objPHPExcel->getActiveSheet()->setCellValue('G5', "Ship To");

	$objPHPExcel->getActiveSheet()->mergeCells('H5:H6');
	$objPHPExcel->getActiveSheet()->setCellValue('H5', "Factory WO#");

	$objPHPExcel->getActiveSheet()->mergeCells('I5:I6');
	$objPHPExcel->getActiveSheet()->setCellValue('I5', "PO REF NO");

	$objPHPExcel->getActiveSheet()->mergeCells('J5:J6');
	$objPHPExcel->getActiveSheet()->setCellValue('J5', "PO Number");

	$objPHPExcel->getActiveSheet()->mergeCells('K5:K6');
	$objPHPExcel->getActiveSheet()->setCellValue('K5', "Price");

	$objPHPExcel->getActiveSheet()->mergeCells('L5:L6');
	$objPHPExcel->getActiveSheet()->setCellValue('L5', "PO Issue Date");

	$objPHPExcel->getActiveSheet()->mergeCells('M5:M6');
	$objPHPExcel->getActiveSheet()->setCellValue('M5', "Vendor");

	$objPHPExcel->getActiveSheet()->mergeCells('N5:N6');
	$objPHPExcel->getActiveSheet()->setCellValue('N5', "ETD Required");

	$objPHPExcel->getActiveSheet()->mergeCells('O5:O6');
	$objPHPExcel->getActiveSheet()->setCellValue('O5', "Notes");

	$objPHPExcel->getActiveSheet()->mergeCells('P5:P6');
	$objPHPExcel->getActiveSheet()->setCellValue('P5', "Fabric");

	$objPHPExcel->getActiveSheet()->mergeCells('Q5:Q6');
	$objPHPExcel->getActiveSheet()->setCellValue('Q5', "Color");


	$iColumn = (65 + 17);


	foreach($sSizesList as $iSize => $sSize)
	{
		$objPHPExcel->getActiveSheet()->mergeCells(getExcelCol($iColumn).'5:'.getExcelCol($iColumn).'6');
		$objPHPExcel->getActiveSheet()->setCellValue((getExcelCol($iColumn).'5'), $sSize);

		$iColumn ++;
	}


	$objPHPExcel->getActiveSheet()->mergeCells(getExcelCol($iColumn).'5:'.getExcelCol($iColumn).'6');
	$objPHPExcel->getActiveSheet()->setCellValue((getExcelCol($iColumn).'5'), "TTL QTY");
	$iColumn++;

	foreach ($iStages as $iStage)
	{
		$objPHPExcel->getActiveSheet()->setCellValue((getExcelCol($iColumn).'5'), $sStagesList[$iStage]);

		$objPHPExcel->getActiveSheet()->setCellValue((getExcelCol($iColumn).'6'), "Start Date");
		$objPHPExcel->getActiveSheet()->setCellValue((getExcelCol($iColumn + 1).'6'), "End Date");
		$objPHPExcel->getActiveSheet()->setCellValue((getExcelCol($iColumn + 2).'6'), "Completed");

		$objPHPExcel->getActiveSheet()->mergeCells(getExcelCol($iColumn).'5:'.getExcelCol($iColumn + 2).'5');

		$iColumn += 3;
	}


	$objPHPExcel->getActiveSheet()->mergeCells(getExcelCol($iColumn).'5:'.getExcelCol($iColumn).'6');
	$objPHPExcel->getActiveSheet()->setCellValue((getExcelCol($iColumn).'5'), "Final Audit");

	$iColumn ++;

	$objPHPExcel->getActiveSheet()->mergeCells(getExcelCol($iColumn).'5:'.getExcelCol($iColumn).'6');
	$objPHPExcel->getActiveSheet()->setCellValue((getExcelCol($iColumn).'5'), "Ship Qty");

	$iColumn ++;

	$objPHPExcel->getActiveSheet()->mergeCells(getExcelCol($iColumn).'5:'.getExcelCol($iColumn).'6');
	$objPHPExcel->getActiveSheet()->setCellValue((getExcelCol($iColumn).'5'), "Balance");

	$iColumn ++;

	$objPHPExcel->getActiveSheet()->mergeCells(getExcelCol($iColumn).'5:'.getExcelCol($iColumn).'6');
	$objPHPExcel->getActiveSheet()->setCellValue((getExcelCol($iColumn).'5'), "Comments");


	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'font'    => array(
					'bold'      => true,
					'size' => 11
				),
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				),
				'borders' => array(
					'top'     => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN
					)
				),
				'fill' => array(
					'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
					'rotation'   => 90,
					'startcolor' => array(
						'argb' => 'FFA6A6A6'
					),
					'endcolor'   => array(
						'argb' => 'FFFFFFFF'
					)
				)
			),
			('A5:'.getExcelCol($iColumn).'6')
	);



	$sSQL = "SELECT po.vendor_id, po.customer, po.customer_ship, po.brand_id, CONCAT(po.order_no, ' ', po.order_status) AS _OrderNo, vsr.work_order_no, vsr.season_id, pc.*, vd.*,
					(SELECT style FROM tbl_styles WHERE id=pc.style_id) AS _Style,
					(SELECT style_name FROM tbl_styles WHERE id=pc.style_id) AS _StyleName
			 FROM tbl_po po, tbl_po_colors pc, tbl_vsr_details vd, tbl_vsr2 vsr
			 $sConditions
			 ORDER BY vsr.work_order_no, _Style, _OrderNo";

	$objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	$iRow    = 7;
	$iColors = array( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iWorkOrder   = $objDb->getField($i, 'vd.work_order_id');
		$sWorkOrder   = $objDb->getField($i, 'vsr.work_order_no');
		$iBrand       = $objDb->getField($i, 'po.brand_id');
		$iVendor      = $objDb->getField($i, 'po.vendor_id');
		$sCustomer    = $objDb->getField($i, 'po.customer');
		$sCustomerShip= $objDb->getField($i, 'po.customer_ship');
		$iSeason      = $objDb->getField($i, 'vsr.season_id');
		$iColor       = $objDb->getField($i, 'vd.color_id');
		$iPo          = $objDb->getField($i, 'vd.po_id');
		$sPo          = $objDb->getField($i, '_OrderNo');
		$sStyle       = $objDb->getField($i, '_Style');
		$sStyleName   = $objDb->getField($i, '_StyleName');
		$sColor       = $objDb->getField($i, 'pc.color');
		$fPrice       = $objDb->getField($i, "pc.{$sPrefix}price");
		$iDestination = $objDb->getField($i, 'pc.destination_id');
		$sEtdRequired = $objDb->getField($i, "pc.{$sPrefix}etd_required");
		$sFinalAudit  = $objDb->getField($i, 'vd.final_date');
		$iShipQty     = $objDb->getField($i, 'vd.ship_qty');

		$sComments    = $objDb->getField($i, 'vd.comments');
		$sPoRef       = $objDb->getField($i, 'vd.po_ref_no');
		$sFabric      = $objDb->getField($i, 'vd.fabric');
		$sVslDate     = $objDb->getField($i, 'vd.vsl_date');
		$sPoIssueDate = $objDb->getField($i, 'vd.po_issue_date');
		$sNotes       = $objDb->getField($i, 'vd.notes');


		if (!@in_array($iColor, $iColors))
			$iColors[] = $iColor;


		$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, $sBrandsList[$iBrand]);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, $sCustomer);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, $sSeasonsList[$iSeason]);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, $sStyle);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$iRow, $sStyleName);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$iRow, $sCustomerShip);
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$iRow, $sDestinationsList[$iDestination]);
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$iRow, $sWorkOrder);
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$iRow, $sPoRef);
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$iRow, $sPo);

		$objPHPExcel->getActiveSheet()->setCellValue('K'.$iRow, formatNumber($fPrice));
		$objPHPExcel->getActiveSheet()->setCellValue('L'.$iRow, formatDate($sPoIssueDate, "m/d/Y"));
		$objPHPExcel->getActiveSheet()->setCellValue('M'.$iRow, $sVendorsList[$iVendor]);
		$objPHPExcel->getActiveSheet()->setCellValue('N'.$iRow, formatDate($sEtdRequired));
		$objPHPExcel->getActiveSheet()->setCellValue('O'.$iRow, $sNotes);
		$objPHPExcel->getActiveSheet()->setCellValue('P'.$iRow, $sFabric);
		$objPHPExcel->getActiveSheet()->setCellValue('Q'.$iRow, $sColor);



		$iSubTotal = 0;
		$iColumn   = (65 + 17);

		foreach ($sSizesList as $iSize => $sSize)
		{
			$iQuantity  = getDbValue("quantity", "tbl_po_quantities", "po_id='$iPo' AND color_id='$iColor' AND size_id='$iSize'");
			$iSubTotal += $iQuantity;

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($iQuantity, false, 0, false));
		}

		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($iSubTotal, false, 0, false));


		foreach ($iStages as $iStage)
		{
			$sSQL = "SELECT start_date, end_date, completed FROM tbl_vsr_data WHERE work_order_id='$iWorkOrder' AND color_id='$iColor' AND stage_id='$iStage'";
			$objDb2->query($sSQL);

			$sStartDate = $objDb2->getField(0, "start_date");
			$sEndDate   = $objDb2->getField(0, "end_date");
			$iCompleted = $objDb2->getField(0, "completed");


			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sStartDate, "m/d/Y"));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sEndDate, "m/d/Y"));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($iCompleted, false).(($sStagesType[$iStage] == "P") ? " %" : " Pcs"));
		}


		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sFinalAudit, "m/d/Y"));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($iShipQty, false));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber(($iShipQty - $iSubTotal ), false));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn).$iRow, $sComments);

		$iRow ++;
	}



	$sConditions = "WHERE po.id=pc.po_id AND po.status!='C' AND po.order_nature='B' ";  // AND po.accepted='Y'

	if ($Vendor > 0)
		$sConditions .= " AND po.vendor_id='$Vendor' ";

	else
		$sConditions .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Brand > 0)
		$sConditions .= " AND po.brand_id='$Brand' ";

	else
		$sConditions .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

	if ($Season > 0)
		$sConditions .= " AND pc.style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand' AND sub_season_id='$Season') ";

	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND (pc.{$sPrefix}etd_required BETWEEN '$FromDate' AND '$ToDate') ";

	if (count($iColors) > 0)
		$sConditions .= (" AND pc.id NOT IN (".@implode(",", $iColors).") ");



	$sSQL = "SELECT po.vendor_id, po.customer, po.customer_ship, po.brand_id, CONCAT(po.order_no, ' ', po.order_status) AS _OrderNo, pc.*,
					(SELECT sub_season_id FROM tbl_styles WHERE id=pc.style_id) AS _Season,
					(SELECT style FROM tbl_styles WHERE id=pc.style_id) AS _Style,
					(SELECT style_name FROM tbl_styles WHERE id=pc.style_id) AS _StyleName
			 FROM tbl_po po, tbl_po_colors pc
			 $sConditions
			 ORDER BY _Style, _OrderNo";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrand       = $objDb->getField($i, 'po.brand_id');
		$iVendor      = $objDb->getField($i, 'po.vendor_id');
		$sCustomer    = $objDb->getField($i, 'po.customer');
		$sCustomerShip= $objDb->getField($i, 'po.customer_ship');
		$iSeason      = $objDb->getField($i, '_Season');
		$iColor       = $objDb->getField($i, 'pc.id');
		$iPo          = $objDb->getField($i, 'pc.po_id');
		$sPo          = $objDb->getField($i, '_OrderNo');
		$sStyle       = $objDb->getField($i, '_Style');
		$sStyleName   = $objDb->getField($i, '_StyleName');
		$sColor       = $objDb->getField($i, 'pc.color');
		$fPrice       = $objDb->getField($i, "pc.{$sPrefix}price");
		$iDestination = $objDb->getField($i, 'pc.destination_id');
		$sEtdRequired = $objDb->getField($i, "pc.{$sPrefix}etd_required");


		$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, $sBrandsList[$iBrand]);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$iRow, $sCustomer);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$iRow, $sSeasonsList[$iSeason]);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$iRow, $sStyle);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$iRow, $sStyleName);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$iRow, $sCustomerShip);
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$iRow, $sDestinationsList[$iDestination]);
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$iRow, "");
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$iRow, "");
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$iRow, $sPo);

		$objPHPExcel->getActiveSheet()->setCellValue('K'.$iRow, formatNumber($fPrice));
		$objPHPExcel->getActiveSheet()->setCellValue('L'.$iRow, "");
		$objPHPExcel->getActiveSheet()->setCellValue('M'.$iRow, $sVendorsList[$iVendor]);
		$objPHPExcel->getActiveSheet()->setCellValue('N'.$iRow, formatDate($sEtdRequired));
		$objPHPExcel->getActiveSheet()->setCellValue('O'.$iRow, "");
		$objPHPExcel->getActiveSheet()->setCellValue('P'.$iRow, "");
		$objPHPExcel->getActiveSheet()->setCellValue('Q'.$iRow, $sColor);


		$iSubTotal = 0;
		$iColumn   = (65 + 17);

		foreach ($sSizesList as $iSize => $sSize)
		{
			$iQuantity  = getDbValue("quantity", "tbl_po_quantities", "po_id='$iPo' AND color_id='$iColor' AND size_id='$iSize'");
			$iSubTotal += $iQuantity;

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($iQuantity, false, 0, false));
		}

		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($iSubTotal, false, 0, false));


		foreach ($iStages as $iStage)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, "");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, "");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, "");
		}


		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, "");
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, "0");
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($iSubTotal, false));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn).$iRow, "");

		$iRow ++;
	}



	// Set column widths
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);

	$iColumn = (65 + 17);

	foreach ($sSizesList as $iSize => $sSize)
	{
		$objPHPExcel->getActiveSheet()->getColumnDimension(getExcelCol($iColumn ++))->setWidth(15);
	}

	$objPHPExcel->getActiveSheet()->getColumnDimension(getExcelCol($iColumn ++))->setWidth(15);

	foreach ($iStages as $iStage)
	{
		$objPHPExcel->getActiveSheet()->getColumnDimension(getExcelCol($iColumn ++))->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension(getExcelCol($iColumn ++))->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension(getExcelCol($iColumn ++))->setWidth(16);
	}


	$objPHPExcel->getActiveSheet()->getColumnDimension(getExcelCol($iColumn ++))->setWidth(18);
	$objPHPExcel->getActiveSheet()->getColumnDimension(getExcelCol($iColumn ++))->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension(getExcelCol($iColumn ++))->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension(getExcelCol($iColumn))->setWidth(50);



	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('Work Order Details');


	include 'PHPExcel/IOFactory.php';


	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;filename=\"{$sExcelFile}\"");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save("php://output");



	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>