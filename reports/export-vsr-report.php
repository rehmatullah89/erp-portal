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

	$Region   = IO::intValue("Region");
	$Category = IO::intValue("Category");
	$Vendor   = IO::getArray("Vendor");
	$Brand    = IO::getArray("Brand");
	$Season   = IO::getArray("Season");
	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");

	$sVendorsList   = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList    = getList("tbl_brands", "id", "brand", "parent_id>'0'");
	$sSeasonsList   = getList("tbl_seasons", "id", "season", "parent_id>'0'");
	$sDivisionsList = getList("tbl_vendors", "id", "btx_division", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");


	$sExcelFile = ($sBaseDir."temp/vsr-report.xlsx");


	$sPos = "";

	$sSQL = "SELECT DISTINCT(po.id)
			 FROM tbl_po po, tbl_po_colors pc
			 WHERE po.id=pc.po_id";

	if ($Region > 0)
		$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	if ($Category > 0)
		$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE category_id='$Category' AND parent_id='0' AND sourcing='Y') ";

	if (count($Vendor) > 0)
	{
		$sVendors = @implode(",", $Vendor);

		$sSQL .= " AND po.vendor_id IN ($sVendors) ";
	}

	if (count($Brand) > 0)
	{
		$sBrands = @implode(",", $Brand);

		$sSQL .= " AND po.brand_id IN ($sBrands) ";
	}

	if (count($Season) > 0)
	{
		$sSeasons = @implode(",", $Season);

		$sSQL .= " AND pc.style_id IN (SELECT id FROM tbl_styles WHERE sub_season_id IN ($sSeasons)) ";
	}

	if ($FromDate != "" && $ToDate != "")
		$sSQL .= " AND (pc.etd_required BETWEEN '$FromDate' AND '$ToDate') ";

	$sSQL .= "ORDER BY id DESC";

	$objDb->query($sSQL);


	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$sPos .= (",".$objDb->getField($i, 'id'));

	if (strpos($sPos, ",") !== FALSE)
		$sPos = substr($sPos, 1);


	@set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');

	@require_once 'PHPExcel.php';
	@require_once 'PHPExcel/RichText.php';


	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set properties
	$objPHPExcel->getProperties()->setCreator("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setLastModifiedBy("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setTitle("VSR Report");
	$objPHPExcel->getProperties()->setSubject("");
	$objPHPExcel->getProperties()->setDescription("VSR Report");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("Reports");


	// Create a first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	// Add a drawing to the worksheet
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Logo');
	$objDrawing->setDescription('Logo');
	$objDrawing->setPath($sBaseDir.'images/reports/vs-report.jpg');
	$objDrawing->setCoordinates('A1');
	$objDrawing->setHeight(90);
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet( ));


	$objPHPExcel->getActiveSheet()->setCellValue('A8', 'From: '.formatDate($FromDate).'      To: '.formatDate($ToDate));
	$objPHPExcel->getActiveSheet()->mergeCells('A8:K8');


	$iColumn = 65;
	$iRow    = 10;

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Vendor');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Brand');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Order No');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Style');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Style Name');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Season');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Quantity');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Item');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Programme');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'PO Received Date');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Factory Work Order');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Material/Fabric');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Finish');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Original ETD');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'ETD Revisions');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Price');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Variable');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Mode');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Trims');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Yarn/Fabric');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'QRS Submit Date');

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Knitting');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Knitting Start Date');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Knitting End Date');

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Linking');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Linking Start Date');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Linking End Date');

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Yarn');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Yarn Start Date');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Yarn End Date');

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Sizing');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Sizing Start Date');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Sizing End Date');

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Weaving');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Weaving Start Date');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Weaving End Date');

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Leather Import');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Leather Import Start Date');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Leather Import End Date');

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Dyeing');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Dyeing Start Date');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Dyeing End Date');

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Leather Inspection');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Leather Inspection Start Date');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Leather Inspection End Date');

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Lamination');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Lamination Start Date');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Lamination End Date');

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Cutting');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Cutting Start Date');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Cutting End Date');

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Print/Embroidery');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Print/Embroidery Start Date');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Print/Embroidery End Date');

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Sorting');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Sorting Start Date');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Sorting End Date');

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Bladder Attachment');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Bladder Attachment Start Date');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Bladder Attachment End Date');

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Stitching');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Stitching Start Date');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Stitching End Date');

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Washing');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Washing Start Date');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Washing End Date');

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Finishing');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Finishing Start Date');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Finishing End Date');

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Lab Testing');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Lab Testing Start Date');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Lab Testing End Date');

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Quality');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Quality Start Date');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Quality End Date');

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Packing');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Packing Start Date');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Packing End Date');

	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Cut Off Date');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Final Audit Date');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Production Status');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'ETD CTG/ZIA');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'ETA Denmark');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Destination');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Shipped Qty');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, 'Remarks');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn).$iRow, 'Portal Comments');

	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'font'    => array(
					'bold'      => true,
					'size' => 10
				),
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
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
			(getExcelCol(65).$iRow.':'.getExcelCol($iColumn).$iRow)
	);

	$iRow ++;


	$sSQL = "SELECT id, order_no, vendor_id, shipping_dates, quantity, styles, destinations FROM tbl_po WHERE id IN ($sPos) ORDER BY LEFT(shipping_dates, 10)";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++, $iRow ++)
	{
		$iPoId   = $objDb->getField($i, 'id');
		$iVendor = $objDb->getField($i, 'vendor_id');

		@list($iStyleId)       = explode(",", $objDb->getField($i, 'styles'));
		@list($iDestinationId) = explode(",", $objDb->getField($i, 'destinations'));
		@list($sEtdRequired)   = explode(",", $objDb->getField($i, 'shipping_dates'));

		if ($iStyleId == 0)
		{
			$sSQL = "SELECT style_id, destination_id FROM tbl_po_colors WHERE po_id='$iPoId' LIMIT 1";
			$objDb2->query($sSQL);

			$iStyleId = $objDb2->getField(0, 0);

			if ($iDestinationId == 0)
				$iDestinationId = $objDb2->getField(0, 1);
		}


		$sSQL = "SELECT style, style_name, sub_brand_id, sub_season_id FROM tbl_styles WHERE id='$iStyleId'";
		$objDb2->query($sSQL);

		$sStyle     = $objDb2->getField(0, 0);
		$sStyleName = $objDb2->getField(0, 1);
		$iBrand     = $objDb2->getField(0, 2);
		$iSeason    = $objDb2->getField(0, 3);


		$sEtdRevisions = "";

		$sSQL = "SELECT original, revised FROM tbl_etd_revisions WHERE po_id='$iPoId' ORDER BY id DESC";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			if ($j == 0)
				$sEtdRevisions = formatDate($objDb2->getField($j, 0));

			$sEtdRevisions .= (",".formatDate($objDb2->getField($j, 1)));
		}


		$sSQL = "SELECT destination FROM tbl_destinations WHERE id='$iDestinationId'";

		if ($objDb2->query($sSQL) == true && $objDb2->getCount( ) == 1)
			$sDestination = $objDb2->getField(0, 0);

		else
			$sDestination = "";


		$sSQL = "SELECT SUM(quantity) FROM tbl_pre_shipment_quantities WHERE po_id='$iPoId'";
		$objDb2->query($sSQL);

		$iShippedQty = $objDb2->getField(0, 0);


		$sSQL = "SELECT * FROM tbl_vsr WHERE po_id='$iPoId'";
		$objDb2->query($sSQL);


		$iColumn = 65;

		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $sVendorsList[$iVendor]);
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $sBrandsList[$iBrand]);
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $objDb->getField($i, 'order_no'));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $sStyle);
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $sStyleName);
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $sSeasonsList[$iSeason]);
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($objDb->getField($i, 'quantity'), false));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($objDb2->getField(0, 'item')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($objDb2->getField(0, 'programme')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb->getField($i, 'po_received_date')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $objDb2->getField(0, 'factory_work_order'));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($objDb2->getField(0, 'material_fabric')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($objDb2->getField(0, 'finish')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($sEtdRequired));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $sEtdRevisions);
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($objDb2->getField(0, 'price')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, (($objDb2->getField(0, 'variable') == "Y") ? "Y" : "N"));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($objDb2->getField(0, 'mode')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($objDb2->getField(0, 'trims')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($objDb2->getField(0, 'yarn_fabric')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'qrs_submit_date')));


		if ($sDivisionsList[$iVendor] == "Y")
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, getBtxVsrValue($objDb2->getField(0, 'knitting')));
			$iColumn += 2;
		}

		else
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $objDb2->getField(0, 'knitting')."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'knitting_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'knitting_end_date')));
		}


		if ($sDivisionsList[$iVendor] == "Y")
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, getBtxVsrValue($objDb2->getField(0, 'linking')));
			$iColumn += 2;
		}

		else
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $objDb2->getField(0, 'linking')."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'linking_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'linking_end_date')));
		}

		if ($sDivisionsList[$iVendor] == "Y")
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($objDb2->getField(0, 'yarn_fabric')));
			$iColumn += 2;
		}

		else
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $objDb2->getField(0, 'yarn')."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'yarn_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'yarn_end_date')));
		}

		if ($sDivisionsList[$iVendor] == "Y")
			$iColumn += 9;

		else
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $objDb2->getField(0, 'sizing')."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'sizing_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'sizing_end_date')));

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $objDb2->getField(0, 'weaving')."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'weaving_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'weaving_end_date')));

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $objDb2->getField(0, 'leather_import')."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'leather_import_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'leather_import_end_date')));
		}

		if ($sDivisionsList[$iVendor] == "Y")
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, getBtxVsrValue($objDb2->getField(0, 'dyeing')));
			$iColumn += 2;
		}

		else
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $objDb2->getField(0, 'dyeing')."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'dyeing_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'dyeing_end_date')));
		}

		if ($sDivisionsList[$iVendor] == "Y")
			$iColumn += 6;

		else
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $objDb2->getField(0, 'leather_inspection')."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'leather_inspection_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'leather_inspection_end_date')));

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $objDb2->getField(0, 'lamination')."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'lamination_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'lamination_end_date')));
		}

		if ($sDivisionsList[$iVendor] == "Y")
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, getBtxVsrValue($objDb2->getField(0, 'cutting')));
			$iColumn += 2;
		}

		else
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $objDb2->getField(0, 'cutting')."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'cutting_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'cutting_end_date')));
		}

		if ($sDivisionsList[$iVendor] == "Y")
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, getBtxVsrValue($objDb2->getField(0, 'print_embroidery')));
			$iColumn += 2;
		}

		else
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $objDb2->getField(0, 'print_embroidery')."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'print_embroidery_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'print_embroidery_end_date')));
		}

		if ($sDivisionsList[$iVendor] == "Y")
			$iColumn += 9;

		else
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $objDb2->getField(0, 'sorting')."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'sorting_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'sorting_end_date')));

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $objDb2->getField(0, 'bladder_attachment')."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'bladder_attachment_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'bladder_attachment_end_date')));

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $objDb2->getField(0, 'stitching')."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'stitching_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'stitching_end_date')));
		}

		if ($sDivisionsList[$iVendor] == "Y")
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, getBtxVsrValue($objDb2->getField(0, 'washing')));
			$iColumn += 2;
		}

		else
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $objDb2->getField(0, 'washing')."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'washing_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'washing_end_date')));
		}

		if ($sDivisionsList[$iVendor] == "Y")
			$iColumn += 9;

		else
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $objDb2->getField(0, 'finishing')."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'finishing_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'finishing_end_date')));

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $objDb2->getField(0, 'lab_testing')."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'lab_testing_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'lab_testing_end_date')));

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $objDb2->getField(0, 'quality')."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'quality_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'quality_end_date')));
		}

		if ($sDivisionsList[$iVendor] == "Y")
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, getBtxVsrValue($objDb2->getField(0, 'packing')));
			$iColumn += 2;
		}

		else
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $objDb2->getField(0, 'packing')."%");
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'packing_start_date')));
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'packing_end_date')));
		}

		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'cut_off_date')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'final_audit_date')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($objDb2->getField(0, 'production_status')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'etd_ctg_zia')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatDate($objDb2->getField(0, 'eta_denmark')));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $sDestination);
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, formatNumber($iShippedQty, false));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, utf8_encode($objDb2->getField(0, 'remarks')));


		$sPortalComments = "";

		$sSQL = "SELECT comments, date_time, (SELECT name FROM tbl_users WHERE id=tbl_vsr_comments.user_id) AS _Name FROM tbl_vsr_comments WHERE po_id='$iPoId' ORDER BY id DESC LIMIT 1";
		$objDb2->query($sSQL);

		if ($objDb2->getCount( ) == 1)
		{
			$sName     = $objDb2->getField(0, "_Name");
			$sComments = $objDb2->getField(0, "comments");
			$sDateTime = $objDb2->getField(0, "date_time");

			$sPortalComments = utf8_encode($sDateTime." » ".$sName." » ".$sComments);
		}

		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColumn ++).$iRow, $sPortalComments);
		$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($iColumn - 1).$iRow)->getAlignment()->setWrapText(true);

		for ($j = 65; $j < $iColumn; $j ++)
			$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($j).$iRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	}

	// Set column widths
	for ($i = 65; $i < $iColumn; $i ++)
		$objPHPExcel->getActiveSheet()->getColumnDimension(getExcelCol($i))->setAutoSize(true);




	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('VSR Report');

	include 'PHPExcel/IOFactory.php';

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save($sExcelFile);


	// forcing csv file to download
	$iSize = @filesize($sExcelFile);

	if(ini_get('zlib.output_compression'))
		@ini_set('zlib.output_compression', 'Off');

	header('Content-Description: File Transfer');
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private", false);
	header('Content-Type: application/force-download');
	header("Content-Type: application/download");
	header("Content-Type: text/xlsx");
	header("Content-Disposition: attachment; filename=\"".basename($sExcelFile)."\";");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: $iSize");

	@readfile($sExcelFile);
	@unlink($sExcelFile);


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>