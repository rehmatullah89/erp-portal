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

	$Region   = IO::intValue('Region');
	$Brands   = IO::getArray('Brands');
	$FromDate = IO::strValue('FromDate');
	$ToDate   = IO::strValue('ToDate');


	$sExcelFile = ($sBaseDir.TEMP_DIR."kpis-report.xls");

	set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');

	@require_once 'PHPExcel.php';
	@require_once 'PHPExcel/RichText.php';
	@require_once 'PHPExcel/IOFactory.php';


	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	$objReader = PHPExcel_IOFactory::createReader('Excel2007');
	$objPHPExcel = $objReader->load($sBaseDir."templates/kpis.xlsx");


	// Set properties
	$objPHPExcel->getProperties()->setCreator("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setLastModifiedBy("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setTitle("KPI's Report");
	$objPHPExcel->getProperties()->setSubject("");
	$objPHPExcel->getProperties()->setDescription("KPI's Report");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("Reports");


	// Create a first sheet
	$objPHPExcel->setActiveSheetIndex(0);


	$sSQL = "SELECT country FROM tbl_countries WHERE id='$Region'";
	$objDb->query($sSQL);

	$sRegion = $objDb->getField(0, 0);

	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 2, "{$sRegion} KPI's");



	$iMonths = (intval(substr($ToDate, 5, 2)) - intval(substr($FromDate, 5, 2)));
	$iYears  = (intval(substr($ToDate, 0, 4)) - intval(substr($FromDate, 0, 4)));

	$iMonths += ($iYears * 12);
	$iMonths ++;


	$iRow            = 6;
	$iTermCount      = 0;
	$sTerm           = "";
	$iTermPlacements = 0;
	$iTermShipments  = 0;
	$iTermDefects    = 0;
	$iTermQaQty      = 0;
	$sBrands         = @implode(",", $Brands);

	$sBrandsList = getList("tbl_brands", "id", "brand", "id IN ($sBrands)");

	for ($i = 0; $i < $iMonths; $i ++)
	{
		@list($iYear, $iMonth) = @explode("-", $ToDate);

		$sMonth     = date("M Y", mktime(0, 0, 0, ($iMonth - $i), 1, $iYear));
		$sStartDate = date("Y-m-01", mktime(0, 0, 0, ($iMonth - $i), 1, $iYear));
		$sEndDate   = date(("Y-m-".cal_days_in_month(CAL_GREGORIAN, ($iMonth - $i), $iYear)), mktime(0, 0, 0, ($iMonth - $i), 1, $iYear));

		if ($sTerm != "")
			$sTerm .= " - ";

		$sTerm .= date("M y", mktime(0, 0, 0, ($iMonth - $i), 1, $iYear));


		$iOrders          = array( );
		$iOrderQty        = array( );
		$iShippedQty      = array( );
		$iOnTimeQty       = array( );
		$fOtp             = array( );
		$iTotalAudits     = array( );
		$iPassAudits      = array( );
		$iEtdRevisions    = array( );
		$iWeek1Revisions  = array( );
		$iWeek12Revisions = array( );
		$iWeek23Revisions = array( );
		$iWeek3pRevisions = array( );
		$iTotalDefects    = array( );
		$iTotalQaQty        = array( );


		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $iRow, $sMonth);


		$sSQL = "SELECT po.brand_id, COALESCE(SUM(pq.quantity), 0)
				 FROM tbl_po po, tbl_po_quantities pq, tbl_po_colors pc
				 WHERE po.id=pq.po_id AND po.id=pc.po_id AND pc.id=pq.color_id AND po.brand_id IN ($sBrands) AND (pc.etd_required BETWEEN '$sStartDate' AND '$sEndDate')
				       AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y')
				 GROUP BY po.brand_id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($j = 0; $j < $iCount; $j ++)
		{
			$iBrandId  = $objDb->getField($j, 0);
			$iQuantity = $objDb->getField($j, 1);

			$iOrderQty[$iBrandId] = $iQuantity;
		}


		$sSQL = "SELECT po.brand_id, COALESCE(SUM(psq.quantity), 0)
				 FROM tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq
				 WHERE po.id=pc.po_id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id AND po.brand_id IN ($sBrands)
				       AND (pc.etd_required BETWEEN '$sStartDate' AND '$sEndDate') AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y')
		         GROUP BY po.brand_id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($j = 0; $j < $iCount; $j ++)
		{
			$iBrandId  = $objDb->getField($j, 0);
			$iQuantity = $objDb->getField($j, 1);

			$iShippedQty[$iBrandId] = $iQuantity;
		}


		$sSQL = "SELECT po.brand_id, COALESCE(SUM(psq.quantity), 0)
				 FROM tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq
				 WHERE po.id=pc.po_id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id
				       AND po.brand_id IN ($sBrands) AND (pc.etd_required BETWEEN '$sStartDate' AND '$sEndDate') AND pc.etd_required <= CURDATE( )
				       AND psd.handover_to_forwarder != '0000-00-00' AND NOT ISNULL(psd.handover_to_forwarder)
				       AND IF ( po.brand_id='32', (psd.handover_to_forwarder <= DATE_ADD(pc.etd_required, INTERVAL 2 DAY)),  (psd.handover_to_forwarder <= pc.etd_required) )
				        AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y')
		         GROUP BY po.brand_id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($j = 0; $j < $iCount; $j ++)
		{
			$iBrandId  = $objDb->getField($j, 0);
			$iQuantity = $objDb->getField($j, 1);

			$iOnTimeQty[$iBrandId] = $iQuantity;

			$fTemp = @round((($iQuantity / $iOrderQty[$iBrandId]) * 100), 2);
			$fTemp = (($fTemp > 100) ? 100 : $fTemp);

			$fOtp[$iBrandId] = $fTemp;
		}


		$sSQL = "SELECT po.brand_id, COUNT(*) AS _Total, SUM(IF(qa.audit_result='P', 1, 0)) AS _Pass
				 FROM tbl_qa_reports qa, tbl_po po
				 WHERE po.id=qa.po_id AND qa.audit_result!='' AND qa.audit_stage='F' AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y')
		               AND (qa.audit_date BETWEEN '$sStartDate' AND '$sEndDate') AND po.brand_id IN ($sBrands)
		         GROUP BY po.brand_id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($j = 0; $j < $iCount; $j ++)
		{
			$iBrandId = $objDb->getField($j, 0);
			$iTotal   = $objDb->getField($j, 1);
			$iPass    = $objDb->getField($j, 2);

			$iTotalAudits[$iBrandId] = $iTotal;
			$iPassAudits[$iBrandId]  = $iPass;
		}


		$sSQL = "SELECT po.brand_id, COUNT(*)
				 FROM tbl_po po, tbl_po_colors pc
				 WHERE po.id=pc.po_id AND po.brand_id IN ($sBrands) AND (pc.etd_required BETWEEN '$sStartDate' AND '$sEndDate')
				       AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y')
				 GROUP BY po.brand_id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($j = 0; $j < $iCount; $j ++)
		{
			$iBrandId = $objDb->getField($j, 0);
			$iTotal   = $objDb->getField($j, 1);

			$iOrders[$iBrandId] = $iTotal;
		}


		$sSQL = "SELECT po.brand_id, DATEDIFF(MAX(etd.revised), MIN(etd.original)) AS _Difference
				 FROM tbl_po po, tbl_po_colors pc, tbl_etd_revisions etd
				 WHERE po.id=pc.po_id AND po.id=etd.po_id AND po.brand_id IN ($sBrands) AND (pc.etd_required BETWEEN '$sStartDate' AND '$sEndDate')
				       AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y')
				 GROUP BY etd.po_id
				 HAVING DATEDIFF(MAX(etd.revised), MIN(etd.original)) > 0";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($j = 0; $j < $iCount; $j ++)
		{
			$iBrandId    = $objDb->getField($j, 0);
			$iDifference = $objDb->getField($j, 1);


			$iEtdRevisions[$iBrandId] ++;

			if ($iDifference >= 0 && $iDifference <= 7)
				$iWeek1Revisions[$iBrandId] ++;

			else if ($iDifference >= 8 && $iDifference <= 14)
				$iWeek12Revisions[$iBrandId] ++;

			else if ($iDifference >= 15 && $iDifference <= 21)
				$iWeek23Revisions[$iBrandId] ++;

			else if ($iDifference >= 22)
				$iWeek3pRevisions[$iBrandId] ++;
		}


		$sSQL = "SELECT po.brand_id, COALESCE(SUM(qa.total_gmts), 0),
		                SUM( (SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id) )
				 FROM tbl_po po, tbl_qa_reports qa
				 WHERE po.id=qa.po_id AND po.brand_id IN ($sBrands) AND (qa.audit_date BETWEEN '$sStartDate' AND '$sEndDate')
				       AND qa.audit_stage!='' AND report_id!='6' AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y')
				 GROUP BY po.brand_id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($j = 0; $j < $iCount; $j ++)
		{
			$iBrandId  = (int)$objDb->getField($j, 0);
			$iQuantity = $objDb->getField($j, 1);
			$iDefects  = $objDb->getField($j, 2);

			$iTotalQaQty[$iBrandId]   = $iQuantity;
			$iTotalDefects[$iBrandId] = $iDefects;
		}



		$iBrandCount = 0;

		foreach ($sBrandsList as $iBrandId => $sBrand)
		{
			if ($iOrderQty[$iBrandId] == 0)
				continue;

			$iBrandCount ++;
			$iTermCount ++;

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $iRow, $sBrand);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $iRow, formatNumber($iOrderQty[$iBrandId], false));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $iRow, formatNumber($iOnTimeQty[$iBrandId], false));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $iRow, formatNumber($iTotalAudits[$iBrandId], false));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $iRow, formatNumber($iPassAudits[$iBrandId], false));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $iRow, formatNumber($fOtp[$iBrandId])."%");


			$fDeviation  = @((($iOnTimeQty[$iBrandId] / $iOrderQty[$iBrandId]) * 100) - 100);
			$fDefectRate = @(($iTotalDefects[$iBrandId] / $iTotalQaQty[$iBrandId]) * 100);

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $iRow, formatNumber($fDefectRate)."%");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $iRow, formatNumber($fDeviation)."%");


			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $iRow, formatNumber($iOrders[$iBrandId], false));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $iRow, formatNumber($iEtdRevisions[$iBrandId], false));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $iRow, formatNumber($iWeek1Revisions[$iBrandId], false));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20, $iRow, formatNumber($iWeek12Revisions[$iBrandId], false));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21, $iRow, formatNumber($iWeek23Revisions[$iBrandId], false));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22, $iRow, formatNumber($iWeek3pRevisions[$iBrandId], false));


			for ($j = 3; $j < 22; $j ++)
			{
				$objPHPExcel->getActiveSheet()->duplicateStyleArray(
						array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN) ),
						      'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER) ),
						(getExcelCol($j + 64).$iRow.':'.getExcelCol($j + 65).$iRow) );
			}

			$objPHPExcel->getActiveSheet()->getStyle('D'.$iRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

			$iRow ++;
		}


		$fOtp = @((@array_sum($iOnTimeQty) / @array_sum($iOrderQty)) * 100);
		$fOtp = (($fOtp > 100) ? 100 : $fOtp);

		$fDeviation  = @(((@array_sum($iOnTimeQty) / @array_sum($iOrderQty)) * 100) - 100);
		$fDefectRate = @((@array_sum($iTotalDefects) / @array_sum($iTotalQaQty)) * 100);

		$iTermPlacements += @array_sum($iOrderQty);
		$iTermShipments  += @array_sum($iOnTimeQty);
		$iTermDefects    += @array_sum($iTotalDefects);
		$iTermQaQty      += @array_sum($iTotalQaQty);


		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $iRow, "Total");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $iRow, formatNumber(@array_sum($iOrderQty), false));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $iRow, formatNumber(@array_sum($iShippedQty), false));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $iRow, formatNumber(@array_sum($iTotalAudits), false));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $iRow, formatNumber(@array_sum($iPassAudits), false));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, ($iRow - $iBrandCount), formatNumber($fOtp)."%");

		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, ($iRow - $iBrandCount), formatNumber($fDefectRate)."%");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, ($iRow - $iBrandCount), formatNumber($fDeviation)."%");

		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $iRow, formatNumber(@array_sum($iOrders), false));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $iRow, formatNumber(@array_sum($iEtdRevisions), false));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $iRow, formatNumber(@array_sum($iWeek1Revisions), false));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20, $iRow, formatNumber(@array_sum($iWeek12Revisions), false));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21, $iRow, formatNumber(@array_sum($iWeek23Revisions), false));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22, $iRow, formatNumber(@array_sum($iWeek3pRevisions), false));


		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
				array('font' => array('bold' => true, 'size' => 11, 'color' => array('rgb' => 'FFFFFF')),
					  'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '000000') ) ),
				     ('D'.$iRow.':H'.$iRow) );

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
				array('font' => array('bold' => true, 'size' => 11, 'color' => array('rgb' => 'FFFFFF')),
					  'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '000000') ) ),
				     ('R'.$iRow.':W'.$iRow) );

		$objPHPExcel->getActiveSheet()->mergeCells('C'.($iRow - $iBrandCount).':C'.$iRow);
		$objPHPExcel->getActiveSheet()->mergeCells('J'.($iRow - $iBrandCount).':J'.$iRow);
		$objPHPExcel->getActiveSheet()->mergeCells('M'.($iRow - $iBrandCount).':M'.$iRow);
		$objPHPExcel->getActiveSheet()->mergeCells('P'.($iRow - $iBrandCount).':P'.$iRow);

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
				array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THICK) ),
				      'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER) ),
				('C'.($iRow - $iBrandCount).':C'.$iRow) );

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
				array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THICK) ),
				      'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER) ),
				('J'.($iRow - $iBrandCount).':J'.$iRow) );

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
				array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THICK) ),
				      'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER) ),
				('M'.($iRow - $iBrandCount).':M'.$iRow) );

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
				array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THICK)),
				      'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER) ),
				('P'.($iRow - $iBrandCount).':P'.$iRow) );



		if ( (($i + 1) % 3) == 0 || $i == ($iMonths - 1))
		{
			$fOtp = @(($iTermShipments / $iTermPlacements) * 100);
			$fOtp = (($fOtp > 100) ? 100 : $fOtp);

			$fDeviation  = @((($iTermShipments / $iTermPlacements) * 100) - 100);
			$fDefectRate = @(($iTermDefects / $iTermQaQty) * 100);


			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, ($iRow - $iTermCount), "$sTerm ");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, ($iRow - $iTermCount), formatNumber($fOtp)."%");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, ($iRow - $iTermCount), formatNumber($fDefectRate)."%");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, ($iRow - $iTermCount), formatNumber($fDeviation)."%");

			$objPHPExcel->getActiveSheet()->mergeCells('B'.($iRow - $iTermCount).':B'.$iRow);
			$objPHPExcel->getActiveSheet()->mergeCells('K'.($iRow - $iTermCount).':K'.$iRow);
			$objPHPExcel->getActiveSheet()->mergeCells('N'.($iRow - $iTermCount).':N'.$iRow);
			$objPHPExcel->getActiveSheet()->mergeCells('Q'.($iRow - $iTermCount).':Q'.$iRow);

			$objPHPExcel->getActiveSheet()->duplicateStyleArray(
					array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THICK) ),
					      'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER) ),
					('B'.($iRow - $iTermCount).':B'.$iRow) );

			$objPHPExcel->getActiveSheet()->duplicateStyleArray(
					array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THICK) ),
					      'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER) ),
					('K'.($iRow - $iTermCount).':K'.$iRow) );

			$objPHPExcel->getActiveSheet()->duplicateStyleArray(
					array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THICK) ),
					      'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER) ),
					('N'.($iRow - $iTermCount).':N'.$iRow) );

			$objPHPExcel->getActiveSheet()->duplicateStyleArray(
					array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THICK) ),
					      'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER) ),
					('Q'.($iRow - $iTermCount).':Q'.$iRow) );


			$iTermPlacements = 0;
			$iTermShipments  = 0;
			$iTermDefects    = 0;
			$iTermQaQty      = 0;
			$iTermCount      = -1;
			$sTerm           = "";
		}


		$iTermCount ++;
		$iRow ++;
	}


	$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THICK) ) ), ('B6:W'.($iRow - 1)) );
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "");


	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B &RPrinted on &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle("KPI's Report");


	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save($sExcelFile);

	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

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

	@ob_end_flush( );
?>