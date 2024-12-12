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
	@require_once($sBaseDir."requires/fpdf/fpdf.php");
	@require_once($sBaseDir."requires/fpdi/fpdi.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	$Id = IO::strValue('Id');


	$sSQL = "SELECT *,
	                (SELECT vendor FROM tbl_vendors WHERE id=tbl_po.vendor_id) AS _Vendor,
	                (SELECT brand FROM tbl_brands WHERE id=tbl_po.brand_id) AS _Brand
			 FROM tbl_po
			 WHERE id='$Id'";
	$objDb->query($sSQL);

	$iVendor              = $objDb->getField(0, "vendor_id");
	$sVendor              = $objDb->getField(0, "_Vendor");
	$sBrand               = $objDb->getField(0, "_Brand");
	$sOrderNo             = $objDb->getField(0, "order_no");
	$sTermsOfDelivery     = $objDb->getField(0, "terms_of_delivery");
	$sPlaceOfDeparture    = $objDb->getField(0, "place_of_departure");
	$sWayOfDispatch       = $objDb->getField(0, "way_of_dispatch");
	$sTermsOfPayment      = $objDb->getField(0, "terms_of_payment");
	$sNote                = $objDb->getField(0, "note");
	$sCustomer            = $objDb->getField(0, "customer");
	$sCurrency            = $objDb->getField(0, "currency");
	$sShippingAddress     = $objDb->getField(0, "shipping_address");
	$sBankDetails         = $objDb->getField(0, "bank_details");
	$sPoTerms             = $objDb->getField(0, "po_terms");
	$sItemNo              = $objDb->getField(0, "item_number");
	$sProductGroup        = $objDb->getField(0, "product_group");
	$sQuality             = $objDb->getField(0, "quality");
	$sSinglePacking       = $objDb->getField(0, "single_packing");
	$iPackagingSize       = $objDb->getField(0, "packing_size");
	$iPackagingColour     = $objDb->getField(0, "packing_color");
	$iPackagingCarton     = $objDb->getField(0, "packing_carton");
	$sHangingPacking      = $objDb->getField(0, "hanging_packing");
	$sSizes               = $objDb->getField(0, "sizes");

	$sCurrency = str_replace("USD", "$", $sCurrency);
	$sCurrency = str_replace("GBP", "£", $sCurrency);
	$sCurrency = str_replace("EUR", "€", $sCurrency);


	$sSQL = "SELECT MIN(etd_required) AS _Etd, style_id, destination_id FROM tbl_po_colors WHERE po_id='$Id' LIMIT 1";
	$objDb->query($sSQL);

	$sEtdRequired = $objDb->getField(0, "_Etd");
	$iStyle       = $objDb->getField(0, "style_id");
	$iDestination = $objDb->getField(0, "destination_id");

	$sStyle         = getDbValue("style", "tbl_styles", "id='$iStyle'");
	$iSeason        = getDbValue("sub_season_id", "tbl_styles", "id='$iStyle'");
	$sSeason        = getDbValue("season", "tbl_seasons", "id='$iSeason'");
	$sDestination   = getDbValue("destination", "tbl_destinations", "id='$iDestination'");
	$sVendorAddress = getDbValue("address", "tbl_vendors", "id='$iVendor'");



	$objPdf = new FPDI( );

	$iPageCount  = $objPdf->setSourceFile($sBaseDir."templates/po.pdf");
	$iTemplateId = $objPdf->importPage(1, '/MediaBox');

	$objPdf->addPage("P", "A4");
	$objPdf->useTemplate($iTemplateId, 0, 0);


	$objPdf->SetFont('Arial', '', 9);
	$objPdf->SetTextColor(50, 50, 50);

	$objPdf->Text(22, 33.8, $sOrderNo);
	$objPdf->Text(152, 33.8, date("d-M-Y"));

	///////////////////////// COL 1

	$objPdf->Text(35, 47.0, "Matrix Sourcing (HK) Ltd");

	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetXY(34, 51.0);
	$objPdf->MultiCell(48, 3.4, "Room 3309, 33rd Floor\nTower A, Southmark\n11 Yip Hing Street\nWong Chuk Hang\nHong Kong", 0, "L");


	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(35, 73.5, $sEtdRequired);
	$objPdf->Text(35, 86.8, $sDestination);

	///////////////////////// COL 2

	$objPdf->Text(99, 47.0, $sCustomer);

	$objPdf->SetFont('Arial', '', 7);

	$objPdf->SetXY(98, 51.0);
	$objPdf->MultiCell(46, 3.0, $sShippingAddress, 0, "L");


	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(99, 73.5, $sPlaceOfDeparture);

	///////////////////////// COL 3

	$objPdf->Text(163, 47.0, $sVendor);

	$objPdf->SetFont('Arial', '', 7);
	$objPdf->SetXY(162, 51);
	$objPdf->MultiCell(38, 3.4, $sVendorAddress, 0, "L");


	$objPdf->SetXY(162, 69.0);
	$objPdf->MultiCell(45, 3.4, $sBankDetails, 0, "L");

	/////////////////////////

	$objPdf->SetFont('Arial', '', 9);

	$objPdf->SetXY(30, 96);
	$objPdf->MultiCell(170, 6.2, $sNote, 0, "L");

	$objPdf->Text(33, 107.6, $sItemNo);
	$objPdf->Text(99, 107.6, $sStyle);

	$objPdf->Text(36, 114.8, $sProductGroup);
	$objPdf->Text(99, 114.8, $sSeason);

	$objPdf->SetXY(23, 117.5);
	$objPdf->MultiCell(130, 4.2, $sQuality, 0, "L");

	/////////////////////////

	$objPdf->SetXY(28, 196.5);
	$objPdf->MultiCell(38, 4.0, $sTermsOfDelivery, 0, "L");

	$objPdf->SetXY(97, 196.5);
	$objPdf->MultiCell(40, 4.0, $sTermsOfPayment, 0, "L");

	$objPdf->Text(163, 199.2, $sWayOfDispatch);

	/////////////////////////

	if ($sSinglePacking == "Y")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 167, 103, 4);

	if ($iPackagingSize > 0)
		$objPdf->Text(166, 110.6, $iPackagingSize);

	if ($iPackagingColour > 0)
		$objPdf->Text(166, 115.5, $iPackagingColour);

	if ($iPackagingCarton > 0)
		$objPdf->Text(166, 120.5, $iPackagingCarton);

	if ($sHangingPacking == "Y")
		$objPdf->Image(($sBaseDir.'images/icons/tick.gif'), 167, 123.7, 4);

	/////////////////////////

	$objPdf->SetFont('Arial', '', 7.5);

	$sPoSizes  = array( );
	$iSubTotal = 0;
	$iTotal    = 0;
	$fTotal    = 0;

	$sSQL = "SELECT id, size FROM tbl_sizes WHERE id IN ($sSizes) ORDER BY position";
	$objDb->query($sSQL);

	$iPoSizesCount = $objDb->getCount( );

	for ($i = 0; ($i < $iPoSizesCount && $i < 6); $i ++)
	{
		$sPoSizes[$i][0] = $objDb->getField($i, 0);
		$sPoSizes[$i][1] = $objDb->getField($i, 1);

		$objPdf->Text((79 + ($i * 17.8)) , 141.5, $sPoSizes[$i][1]);
	}


	$sSQL = "SELECT id, color, price FROM tbl_po_colors WHERE po_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iPoColorsCount = $objDb->getCount( );


	for ($i = 0; $i < $iPoColorsCount; $i ++)
	{
		$iColor = $objDb->getField($i, 'id');
		$sColor = $objDb->getField($i, 'color');
		$fPrice = $objDb->getField($i, "price");

		$objPdf->Text(11, (146.1 + ($i * 4.2)), $sColor);
		$objPdf->Text(64, (146.1 + ($i * 4.2)), ($sCurrency.formatNumber($fPrice)));


		$iSubTotal = 0;

		for ($j = 0; ($j < $iPoSizesCount && $j < 6); $j ++)
		{
			$iQuantity  = getDbValue("quantity", "tbl_po_quantities", "po_id='$Id' AND color_id='$iColor' AND size_id='{$sPoSizes[$j][0]}'");
			$iSubTotal += $iQuantity;

			$objPdf->Text((79.3 + ($j * 17.8)) , (146.1 + ($i * 4.2)), $iQuantity);
		}

		$objPdf->Text(186 , (146.1 + ($i * 4.2)), formatNumber($iSubTotal, false));


		$iTotal += $iSubTotal;
		$fTotal += ($iSubTotal * $fPrice);
	}

	$objPdf->SetFont('Arial', '', 9);
	$objPdf->Text(186, 189.5, formatNumber($iTotal, false));

	/////////////////////////

	$objPdf->Text(22, 222.5, $sOrderNo);

	$objPdf->Text(12, 236.8, $sVendor);
	$objPdf->Text(63, 236.8, $sStyle);
	$objPdf->Text(99, 236.8, formatNumber($iTotal, false));
	$objPdf->Text(128, 236.8, $sEtdRequired);
	$objPdf->Text(159, 236.8, "-");
	$objPdf->Text(181, 236.8, ($sCurrency.formatNumber($fTotal)));


	if ($sPoTerms != "")
	{
		$objPdf->SetFont('Arial', 'B', 10);
		$objPdf->SetTextColor(80, 80, 80);
		$objPdf->Text(9, 246, "TERMS");


		$objPdf->SetFont('Arial', '', 8);
		$objPdf->SetTextColor(50, 50, 50);

		$objPdf->SetXY(9, 247);
		$objPdf->MultiCell(190, 4.0, $sPoTerms, 0, "L");
	}

	$objPdf->Output("{$sOrderNo}.pdf", 'D');


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>