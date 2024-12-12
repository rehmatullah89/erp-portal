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

	$Invoice = urldecode(IO::strValue("Invoice"));
	$PoId    = IO::strValue("PoId");

	$sExcelFile = ($sBaseDir."temp/inspection-certificate.xlsx");

	set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');

	require_once 'PHPExcel.php';
	require_once 'PHPExcel/RichText.php';

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set properties
	$objPHPExcel->getProperties()->setCreator("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setLastModifiedBy("Triple Tree Solutions");
	$objPHPExcel->getProperties()->setTitle("Inspection Report");
	$objPHPExcel->getProperties()->setSubject("");
	$objPHPExcel->getProperties()->setDescription("Inspection Report");
	$objPHPExcel->getProperties()->setKeywords("");
	$objPHPExcel->getProperties()->setCategory("Reports");

	// Create a first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	$objPHPExcel->getActiveSheet()->setCellValue('A1', " ");
	$objPHPExcel->getActiveSheet()->mergeCells('A1:K1');
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(60);


	$objPHPExcel->getActiveSheet()->setCellValue('A5', "I N S P E C T I O N    C E R T I F I C A T E");
	$objPHPExcel->getActiveSheet()->mergeCells('A5:K5');
	$objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setSize(28);
	$objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objPHPExcel->getActiveSheet()->setCellValue('A6', ("MS/".getIcNo($Invoice)));

	$objPHPExcel->getActiveSheet()->setCellValue('J6', "DATED: ".date("m/d/Y"));
	$objPHPExcel->getActiveSheet()->mergeCells('J6:K6');
	$objPHPExcel->getActiveSheet()->getStyle('J6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


	if ($PoId == "")
	{
		$sSQL = "SELECT po_id FROM tbl_pre_shipment_detail WHERE invoice_no='$Invoice' LIMIT 1";
		$objDb->query($sSQL);

		$PoId = $objDb->getField(0, 0);
	}


	$sSQL = "SELECT brand FROM tbl_brands WHERE id=(SELECT brand_id FROM tbl_styles WHERE id IN (SELECT style_id FROM tbl_po_colors WHERE po_id='$PoId')) LIMIT 1";
	$objDb->query($sSQL);

	$sBrand = $objDb->getField(0, 0);


	$sSQL = "SELECT lading_airway_bill FROM tbl_pre_shipment_detail WHERE invoice_no='$Invoice' LIMIT 1";
	$objDb->query($sSQL);

	$sAwbBl = $objDb->getField(0, 0);


	$sSQL = "SELECT terms_of_payment,
	                (SELECT terms FROM tbl_terms_of_delivery WHERE id=tbl_pre_shipment_detail.terms_of_delivery_id) AS _TermsOfDelivery,
	                (SELECT vendor FROM tbl_vendors WHERE id=(SELECT vendor_id FROM tbl_po WHERE id=tbl_pre_shipment_detail.po_id)) AS _Vendor,
	                shipping_date,
	                cartons
			FROM tbl_pre_shipment_detail WHERE invoice_no='$Invoice' LIMIT 1";
	$objDb->query($sSQL);

	$sVendor  = $objDb->getField(0, "_Vendor");
	$sShipped = "AIR";

	if (@strpos(strtolower($objDb->getField(0, "_TermsOfDelivery")), "sea") !== FALSE)
		$sShipped = "SEA";


	$objPHPExcel->getActiveSheet()->setCellValue('A8', "TO WHOM IT MAY CONCERN");
	$objPHPExcel->getActiveSheet()->mergeCells('A8:J8');
	$objPHPExcel->getActiveSheet()->getStyle('A8')->getFont()->setSize(20);
	$objPHPExcel->getActiveSheet()->getStyle('A8')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objPHPExcel->getActiveSheet()->setCellValue('D9', "L/C # ".$objDb->getField(0, "terms_of_payment"));
	$objPHPExcel->getActiveSheet()->mergeCells('D9:H9');
	$objPHPExcel->getActiveSheet()->getStyle('D9')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('D9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objRichText = new PHPExcel_RichText( $objPHPExcel->getActiveSheet()->getCell('A11') );
	$objRichText->createText("THIS IS TO CERTIFY THAT THE FOLLOWING STYLE BEING SHIPPED BY M/S {$sVendor} ON ACCOUNT OF MS {$sBrand} WIDE AWB # AS UNDER");

	$objPHPExcel->getActiveSheet()->mergeCells('A11:K12');
	$objPHPExcel->getActiveSheet()->getStyle('A11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
	$objPHPExcel->getActiveSheet()->getStyle('A11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

	$objPHPExcel->getActiveSheet()->setCellValue('A14', "AWB/BL");
	$objPHPExcel->getActiveSheet()->setCellValue('B14', $sAwbBl);
	$objPHPExcel->getActiveSheet()->getStyle('B14')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->mergeCells('B14:C14');

	$objPHPExcel->getActiveSheet()->setCellValue('I14', "DATED:");
	$objPHPExcel->getActiveSheet()->setCellValue('J14', formatDate($objDb->getField(0, "shipping_date"), "m/d/Y"));
    $objPHPExcel->getActiveSheet()->getStyle('J14')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->mergeCells('J14:K14');
    $objPHPExcel->getActiveSheet()->getStyle('I14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel->getActiveSheet()->getStyle('J14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

	$objRichText = new PHPExcel_RichText( $objPHPExcel->getActiveSheet()->getCell('A16') );
	$objRichText->createText('HAS BEEN ON RANDOM INSPECTION FOUND ACCEPTABLE FOR SHIPMENT DETAIL AS UNDER');
	$objPHPExcel->getActiveSheet()->mergeCells('A16:K16');

	$objPHPExcel->getActiveSheet()->setCellValue('A18', "INVOICE NO:");
	$objPHPExcel->getActiveSheet()->setCellValue('C18', $Invoice);
	$objPHPExcel->getActiveSheet()->getStyle('C18')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->mergeCells('A18:B18');
	$objPHPExcel->getActiveSheet()->mergeCells('C18:D18');

	$objPHPExcel->getActiveSheet()->setCellValue('H18', "SHIPPED BY:");
	$objPHPExcel->getActiveSheet()->setCellValue('J18', $sShipped);
    $objPHPExcel->getActiveSheet()->getStyle('J18')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->mergeCells('H18:I18');
    $objPHPExcel->getActiveSheet()->mergeCells('J18:K18');
    $objPHPExcel->getActiveSheet()->getStyle('H18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel->getActiveSheet()->getStyle('J18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

	$objPHPExcel->getActiveSheet()->setCellValue('A20', "NO OF CARTONS:");
	$objPHPExcel->getActiveSheet()->setCellValue('C20', "");
    $objPHPExcel->getActiveSheet()->getStyle('C20')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->mergeCells('A20:B20');
    $objPHPExcel->getActiveSheet()->mergeCells('C20:D20');
    $objPHPExcel->getActiveSheet()->getStyle('A20')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getStyle('C20')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

	$objPHPExcel->getActiveSheet()->setCellValue('A22', "IC DESCRIPTION:");
	$objPHPExcel->getActiveSheet()->setCellValue('C22', "");
	$objPHPExcel->getActiveSheet()->getStyle('C22')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->mergeCells('A22:B22');
	$objPHPExcel->getActiveSheet()->mergeCells('C22:K22');
	$objPHPExcel->getActiveSheet()->setCellValue('C22', ("AS PER INVOICE NO. ".$Invoice));



	$objPHPExcel->getActiveSheet()->setCellValue('A24', "PO NO");
	$objPHPExcel->getActiveSheet()->setCellValue('C24', "STYLE NO");
	$objPHPExcel->getActiveSheet()->setCellValue('E24', "STYLE DESCRIPTION");
	$objPHPExcel->getActiveSheet()->setCellValue('J24', "QUANTITY");

	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'font'    => array(
					'bold'      => true
				),
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				),
				'borders' => array(
					'top'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'left'  => array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
				)
			),
			'A24:K24'
	);

	$objPHPExcel->getActiveSheet()->mergeCells('A24:B24');
	$objPHPExcel->getActiveSheet()->mergeCells('C24:D24');
	$objPHPExcel->getActiveSheet()->mergeCells('E24:I24');
	$objPHPExcel->getActiveSheet()->mergeCells('J24:K24');


	$sSQL = "SELECT id, po_id, cartons FROM tbl_pre_shipment_detail WHERE invoice_no='$Invoice' ORDER BY po_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	$iRow     = 25;
	$iTotal   = 0;
	$iCartons = 0;

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iShipId   = $objDb->getField($i, "id");
		$iPoId     = $objDb->getField($i, "po_id");
		$iCartons += $objDb->getField($i, "cartons");


		$sSQL = "SELECT CONCAT(order_no, ' ', order_status) AS _PO FROM tbl_po WHERE id='$iPoId'";
		$objDb2->query($sSQL);

		$sPo = $objDb2->getField(0, 0);


		$sSQL = "SELECT s.style, s.style_name, SUM(psq.quantity)
		         FROM tbl_pre_shipment_quantities psq, tbl_styles s, tbl_po_colors pc
		         WHERE s.id=pc.style_id AND psq.color_id=pc.id AND psq.ship_id='$iShipId' AND psq.po_id='$iPoId' AND psq.quantity>'0'
		         GROUP BY s.id";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$sStyle     = $objDb2->getField($j, 0);
			$sReference = $objDb2->getField($j, 1);
			$iQuantity  = $objDb2->getField($j, 2);


			$objPHPExcel->getActiveSheet()->setCellValue("A{$iRow}", $sPo);
			$objPHPExcel->getActiveSheet()->setCellValue("C{$iRow}", $sStyle);
			$objPHPExcel->getActiveSheet()->setCellValue("E{$iRow}", $sReference);
			$objPHPExcel->getActiveSheet()->setCellValue("J{$iRow}", formatNumber($iQuantity, false));

			$objPHPExcel->getActiveSheet()->duplicateStyleArray(
					array(
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
					),
						'borders' => array(
							'top'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
							'left'  => array('style' => PHPExcel_Style_Border::BORDER_THIN),
							'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
							'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
						)
					),
					"A{$iRow}:K{$iRow}"
			);

			$objPHPExcel->getActiveSheet()->mergeCells("A{$iRow}:B{$iRow}");
			$objPHPExcel->getActiveSheet()->mergeCells("C{$iRow}:D{$iRow}");
			$objPHPExcel->getActiveSheet()->mergeCells("E{$iRow}:I{$iRow}");
			$objPHPExcel->getActiveSheet()->mergeCells("J{$iRow}:K{$iRow}");

			$objPHPExcel->getActiveSheet()->getStyle("J{$iRow}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);


			$iTotal += $iQuantity;

			$iRow ++;
		}
	}


	$objPHPExcel->getActiveSheet()->setCellValue('C20', formatNumber($iCartons, false));

	$objPHPExcel->getActiveSheet()->setCellValue("A{$iRow}", "Total Qty : ");
	$objPHPExcel->getActiveSheet()->setCellValue("J{$iRow}", formatNumber($iTotal, false));

	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'font'    => array(
					'bold'      => true
				),
				'borders' => array(
					'top'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'left'  => array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
				)
			),
			"A{$iRow}:K{$iRow}"
	);

	$objPHPExcel->getActiveSheet()->mergeCells("A{$iRow}:I{$iRow}");
	$objPHPExcel->getActiveSheet()->mergeCells("J{$iRow}:K{$iRow}");

	$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle("J{$iRow}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

	$iRow += 2;

	$objRichText = new PHPExcel_RichText( $objPHPExcel->getActiveSheet()->getCell("A{$iRow}") );
	$objRichText->createText("MATRIX SOURCING HAS CONDUCTED A RANDOM AUDIT BASED ON {$sBrand} QUALITY AUDIT PROTOCOLS. MS. {$sVendor} WILL HOWEVER BE RESPONSIBLE FOR DEFECTIVE / SHORT MERCHANDIZE RECEIVED UPON ARRIVAL AT DESTINATION.");

	$objPHPExcel->getActiveSheet()->mergeCells("A{$iRow}:K".($iRow + 2));
	$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
	$objPHPExcel->getActiveSheet()->getStyle("A{$iRow}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

	$iRow += 5;

	$objPHPExcel->getActiveSheet()->setCellValue("A{$iRow}", "FOR MATRIX SOURCING GROUP");
	$objPHPExcel->getActiveSheet()->mergeCells("A{$iRow}:K{$iRow}");


	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setScale(100);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0);


	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('Inspection Certificate');

	include 'PHPExcel/IOFactory.php';

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
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