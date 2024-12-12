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

	@require_once($sBaseDir."requires/tcpdf/config/lang/eng.php");
	@require_once($sBaseDir."requires/tcpdf/tcpdf.php");

	$objPdf = new TCPDF("L", "pt", "A4", true, 'UTF-8', false);

	$objPdf->SetCreator("Matrix Sourcing");
	$objPdf->SetAuthor('MT Shahzad');
	$objPdf->SetTitle('VSR Report');
	$objPdf->SetSubject('Actual VSR Report');
	$objPdf->SetKeywords('Matrix Sourcing,VSR Report');

	$objPdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	$objPdf->setPrintHeader(false);
	$objPdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	$objPdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$objPdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$objPdf->SetFooterMargin(PDF_MARGIN_FOOTER);

	$objPdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	$objPdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	$objPdf->setLanguageArray($l);

	$objPdf->SetFont("arialunicid0", "", 7);
	$objPdf->AddPage( );

	$objPdf->Image($sBaseDir."images/reports/actual-vsr.jpg", 10, 10, 815, 0, '', 'http://portal.3-tree.com');
	$objPdf->Ln(75);

	$sHtml = '
<table border="1" cellpadding="5" cellspacing="0" width="100%">
  <tr bgcolor="#eeeeee">
    <td width="5%"><b>Factory</b></td>
    <td width="4%"><b>Label</b></td>
    <td width="4%"><b>Order</b></td>
    <td width="4%"><b>Style</b></td>
    <td width="4%"><b>Season</b></td>
    <td width="4%"><b>Total Pcs</b></td>
    <td width="6%"><b>ETD</b></td>
    <td width="4%"><b>Price</b></td>
    <td width="4%"><b>Mode</b></td>
    <td width="4%"><b>Trims</b></td>
    <td width="5%"><b>Yarn / Fabric</b></td>
    <td width="4%"><b>Knitting</b></td>
    <td width="4%"><b>Dyeing</b></td>
    <td width="4%"><b>Cutting</b></td>
    <td width="5%"><b>Print / Embroidery</b></td>
    <td width="4%"><b>Sewing / Linking</b></td>
    <td width="4%"><b>Washing</b></td>
    <td width="4%"><b>Packing</b></td>
    <td width="4%"><b>Final Audit</b></td>
    <td width="4%"><b>Status</b></td>
    <td width="4%"><b>Shipped Qty</b></td>
    <td width="10%"><b>Remarks</b></td>
  </tr>';


	$sSQL = "SELECT id, order_no, vendor_id, shipping_dates, quantity, styles FROM tbl_po WHERE id IN ($POs) ORDER BY LEFT(shipping_dates, 10)";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++, $iRow ++)
	{
		$iPoId = $objDb->getField($i, 'id');

		@list($iStyleId)     = explode(",", $objDb->getField($i, 'styles'));
		@list($sEtdRequired) = explode(",", $objDb->getField($i, 'shipping_dates'));

		if ($iStyleId == 0)
		{
			$sSQL = "SELECT style_id, price FROM tbl_po_colors WHERE po_id='$iPoId' LIMIT 1";
			$objDb2->query($sSQL);

			$iStyleId = $objDb2->getField(0, 0);
			$fPrice   = $objDb2->getField(0, 1);
		}

		else
			$fPrice = getDbValue("price", "tbl_po_colors", "po_id='$iPoId' AND style_id='$iStyleId'");


		$sSQL = "SELECT style, sub_brand_id, sub_season_id FROM tbl_styles WHERE id='$iStyleId'";
		$objDb2->query($sSQL);

		$sStyle  = $objDb2->getField(0, 0);
		$iBrand  = $objDb2->getField(0, 1);
		$iSeason = $objDb2->getField(0, 2);


		$sSQL = "SELECT SUM(quantity) FROM tbl_pre_shipment_quantities WHERE po_id='$iPoId'";
		$objDb2->query($sSQL);

		$iShippedQty = $objDb2->getField(0, 0);


		$sSQL = "SELECT * FROM tbl_vsr WHERE po_id='$iPoId'";
		$objDb2->query($sSQL);

		$sHtml .= '
  <tr>
    <td width="5%">'.$sVendorsList[$objDb->getField($i, 'vendor_id')].'</td>
    <td width="4%">'.$sBrandsList[$iBrand].'</td>
    <td width="4%">'.$objDb->getField($i, 'order_no').'</td>
    <td width="4%">'.$sStyle.'</td>
    <td width="4%">'.utf8_encode($sSeasonsList[$iSeason]).'</td>
    <td width="4%">'.formatNumber($objDb->getField($i, 'quantity'), false).'</td>
    <td width="6%">'.formatDate($sEtdRequired).'</td>
    <td width="4%">'.formatNumber($fPrice).'</td>
    <td width="4%">'.utf8_encode($objDb2->getField(0, 'mode')).'</td>
    <td width="4%">'.utf8_encode($objDb2->getField(0, 'trims')).'</td>
    <td width="5%">'.utf8_encode($objDb2->getField(0, 'yarn_fabric')).'</td>
    <td width="4%">'.getBtxVsrValue($objDb2->getField(0, 'knitting')).'</td>
    <td width="4%">'.getBtxVsrValue($objDb2->getField(0, 'dyeing')).'</td>
    <td width="4%">'.getBtxVsrValue($objDb2->getField(0, 'cutting')).'</td>
    <td width="5%">'.getBtxVsrValue($objDb2->getField(0, 'print_embroidery')).'</td>
    <td width="4%">'.getBtxVsrValue($objDb2->getField(0, 'linking')).'</td>
    <td width="4%">'.getBtxVsrValue($objDb2->getField(0, 'washing')).'</td>
    <td width="4%">'.getBtxVsrValue($objDb2->getField(0, 'packing')).'</td>
    <td width="4%">'.formatDate($objDb2->getField(0, 'final_audit_date')).'</td>
    <td width="4%">'.utf8_encode($objDb2->getField(0, 'production_status')).'</td>
    <td width="4%">'.formatNumber($iShippedQty, false).'</td>
    <td width="10%">'.utf8_encode($objDb2->getField(0, 'remarks')).'</td>
  </tr>';
	}

	$sHtml .= '</table>';

	$objPdf->writeHTML($sHtml);

	$objPdf->Output("actual-btx-vsr.pdf", "D");
?>