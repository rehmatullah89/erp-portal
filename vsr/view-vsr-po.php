<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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

	$Id = IO::intValue('Id');

	$sSQL = "SELECT * FROM tbl_vsr WHERE po_id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sGtNumber                   = $objDb->getField(0, 'gt_no');
		$iStyleId                    = $objDb->getField(0, 'style_id');
		$sSubContractor              = $objDb->getField(0, 'sub_contractor');
		$sItem                       = $objDb->getField(0, 'item');
		$fPrice                      = $objDb->getField(0, 'price');
		$sVariable                   = $objDb->getField(0, 'variable');
		$sProgramme                  = $objDb->getField(0, 'programme');
		$sPoReceivedDate             = formatDate($objDb->getField(0, 'po_received_date'));
		$sFactoryWorkOrder           = $objDb->getField(0, 'factory_work_order');
		$sMaterialFabric             = $objDb->getField(0, 'material_fabric');
		$sFinish                     = $objDb->getField(0, 'finish');
		$sRevisedEtd                 = formatDate($objDb->getField(0, 'revised_etd'));
		$sCustomerNo                 = $objDb->getField(0, 'customer_no');
		$sArticleNo                  = $objDb->getField(0, 'article_no');
		$sMode                       = $objDb->getField(0, 'mode');
		$sTrims                      = $objDb->getField(0, 'trims');
		$sYarnFabric                 = $objDb->getField(0, 'yarn_fabric');
		$sQrsSubmitDate              = formatDate($objDb->getField(0, 'qrs_submit_date'));
		$iKnitting                   = $objDb->getField(0, 'knitting');
		$sKnittingStartDate          = formatDate($objDb->getField(0, 'knitting_start_date'));
		$sKnittingEndDate            = formatDate($objDb->getField(0, 'knitting_end_date'));
		$iLinking                    = $objDb->getField(0, 'linking');
		$sLinkingStartDate           = formatDate($objDb->getField(0, 'linking_start_date'));
		$sLinkingEndDate             = formatDate($objDb->getField(0, 'linking_end_date'));
		$iYarn                       = $objDb->getField(0, 'yarn');
		$sYarnStartDate              = formatDate($objDb->getField(0, 'yarn_start_date'));
		$sYarnEndDate                = formatDate($objDb->getField(0, 'yarn_end_date'));
		$iSizing                     = $objDb->getField(0, 'sizing');
		$sSizingStartDate            = formatDate($objDb->getField(0, 'sizing_start_date'));
		$sSizingEndDate              = formatDate($objDb->getField(0, 'sizing_end_date'));
		$iWeavingPercentage          = $objDb->getField(0, 'weaving');
		$sWeavingStartDate           = formatDate($objDb->getField(0, 'weaving_start_date'));
		$sWeavingEndDate             = formatDate($objDb->getField(0, 'weaving_end_date'));
		$iLeatherImport              = $objDb->getField(0, 'leather_import');
		$sLeatherImportStartDate     = formatDate($objDb->getField(0, 'leather_import_start_date'));
		$sLeatherImportEndDate       = formatDate($objDb->getField(0, 'leather_import_end_date'));
		$iDyeing                     = $objDb->getField(0, 'dyeing');
		$sDyeingStartDate            = formatDate($objDb->getField(0, 'dyeing_start_date'));
		$sDyeingEndDate              = formatDate($objDb->getField(0, 'dyeing_end_date'));
		$iLeatherInspection          = $objDb->getField(0, 'leather_inspection');
		$sLeatherInspectionStartDate = formatDate($objDb->getField(0, 'leather_inspection_start_date'));
		$sLeatherInspectionEndDate   = formatDate($objDb->getField(0, 'leather_inspection_end_date'));
		$iLamination                 = $objDb->getField(0, 'lamination');
		$sLaminationStartDate        = formatDate($objDb->getField(0, 'lamination_start_date'));
		$sLaminationEndDate          = formatDate($objDb->getField(0, 'lamination_end_date'));
		$iCutting                    = $objDb->getField(0, 'cutting');
		$iCuttingPieces              = formatNumber($objDb->getField(0, 'cutting_pieces'), false);
		$sCuttingStartDate           = formatDate($objDb->getField(0, 'cutting_start_date'));
		$sCuttingEndDate             = formatDate($objDb->getField(0, 'cutting_end_date'));
		$sSewingLine                 = $objDb->getField(0, 'sewing_line');
		$sPerLineProductivity        = $objDb->getField(0, 'per_line_productivity');
		$iPrintEmbroidery            = $objDb->getField(0, 'print_embroidery');
		$iPrintEmbroideryPieces      = formatNumber($objDb->getField(0, 'print_embroidery_pieces'), false);
		$sPrintEmbroideryStartDate   = formatDate($objDb->getField(0, 'print_embroidery_start_date'));
		$sPrintEmbroideryEndDate     = formatDate($objDb->getField(0, 'print_embroidery_end_date'));
		$iSorting                    = $objDb->getField(0, 'sorting');
		$sSortingStartDate           = formatDate($objDb->getField(0, 'sorting_start_date'));
		$sSortingEndDate             = formatDate($objDb->getField(0, 'sorting_end_date'));
		$iBladderAttachment          = $objDb->getField(0, 'bladder_attachment');
		$sBladderAttachmentStartDate = formatDate($objDb->getField(0, 'bladder_attachment_start_date'));
		$sBladderAttachmentEndDate   = formatDate($objDb->getField(0, 'bladder_attachment_end_date'));
		$iStitching                  = $objDb->getField(0, 'stitching');
		$iStitchingPieces            = formatNumber($objDb->getField(0, 'stitching_pieces'), false);
		$sStitchingStartDate         = formatDate($objDb->getField(0, 'stitching_start_date'));
		$sStitchingEndDate           = formatDate($objDb->getField(0, 'stitching_end_date'));
		$iWashing                    = $objDb->getField(0, 'washing');
		$sWashingStartDate           = formatDate($objDb->getField(0, 'washing_start_date'));
		$sWashingEndDate             = formatDate($objDb->getField(0, 'washing_end_date'));
		$iFinishing                  = $objDb->getField(0, 'finishing');
		$sFinishingStartDate         = formatDate($objDb->getField(0, 'finishing_start_date'));
		$sFinishingEndDate           = formatDate($objDb->getField(0, 'finishing_end_date'));
		$iLabTesting                 = $objDb->getField(0, 'lab_testing');
		$sLabTestingStartDate        = formatDate($objDb->getField(0, 'lab_testing_start_date'));
		$sLabTestingEndDate          = formatDate($objDb->getField(0, 'lab_testing_end_date'));
		$iQuality                    = $objDb->getField(0, 'quality');
		$sQualityStartDate           = formatDate($objDb->getField(0, 'quality_start_date'));
		$sQualityEndDate             = formatDate($objDb->getField(0, 'quality_end_date'));
		$iPacking                    = $objDb->getField(0, 'packing');
		$iPackingPieces              = formatNumber($objDb->getField(0, 'packing_pieces'), false);
		$sPackingStartDate           = formatDate($objDb->getField(0, 'packing_start_date'));
		$sPackingEndDate             = formatDate($objDb->getField(0, 'packing_end_date'));
		$sCutOffDate                 = formatDate($objDb->getField(0, 'cut_off_date'));
		$sFinalAuditDate             = formatDate($objDb->getField(0, 'final_audit_date'));
		$sProductionStatus           = $objDb->getField(0, 'production_status');
		$sEtdCtgZia                  = formatDate($objDb->getField(0, 'etd_ctg_zia'));
		$sEtaDenmark                 = formatDate($objDb->getField(0, 'eta_denmark'));
		$iDestinationId              = $objDb->getField(0, 'destination_id');
		$sRemarks                    = $objDb->getField(0, 'remarks');


		$sSQL = "SELECT SUM(quantity) FROM tbl_po_quantities WHERE po_id='$Id'";
		$objDb->query($sSQL);

		$iQuantity = $objDb->getField(0, 0);


		if ($iStyleId == 0)
			$sSQL = "SELECT LEFT(shipping_dates, 10) FROM tbl_po WHERE id='$Id'";

		else
			$sSQL = "SELECT etd_required FROM tbl_po_colors WHERE po_id='$Id' AND style_id='$iStyleId' LIMIT 1";

		$objDb->query($sSQL);

		$sEtdRequired = formatDate($objDb->getField(0, 0));


		$sSQL = "SELECT SUM(quantity) FROM tbl_pre_shipment_quantities WHERE po_id='$Id'";
		$objDb->query($sSQL);

		$iShippedQty = $objDb->getField(0, 0);


		$sSQL = "SELECT vendor, category_id, btx_division FROM tbl_vendors WHERE id=(SELECT vendor_id FROM tbl_po WHERE id='$Id') AND parent_id='0' AND sourcing='Y'";
		$objDb->query($sSQL);

		$sVendor      = $objDb->getField(0, "vendor");
		$iCategory    = $objDb->getField(0, "category_id");
		$sBtxDivision = $objDb->getField(0, "btx_division");


		if ($iStyleId == 0)
		{
			$sSQL = "SELECT style_id FROM tbl_po_colors WHERE po_id='$Id' LIMIT 1";
			$objDb->query($sSQL);

			$iStyleId = $objDb->getField(0, 0);
		}


		$sSQL = "SELECT style, style_name,
		                (SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand,
		                (SELECT season FROM tbl_seasons WHERE id=tbl_styles.sub_season_id) AS _Season,
		                sub_brand_id
		         FROM tbl_styles WHERE id='$iStyleId'";
		$objDb->query($sSQL);

		$sStyle     = $objDb->getField(0, "style");
		$sStyleName = $objDb->getField(0, "style_name");
		$sBrand     = $objDb->getField(0, "_Brand");
		$sSeason    = $objDb->getField(0, "_Season");
		$iSubBrand  = $objDb->getField(0, "sub_season_id");

		if ($iDestinationId == 0)
		{
			$sSQL = "SELECT destination_id FROM tbl_po_colors WHERE po_id='$Id' LIMIT 1";
			$objDb->query($sSQL);

			$iDestinationId = $objDb->getField(0, 0);
		}

		$sSQL = "SELECT destination FROM tbl_destinations WHERE id='$iDestinationId'";
		$objDb->query($sSQL);

		$sDestination = $objDb->getField(0, 0);


		$sCategories = array( );

		$sSQL = "SELECT * FROM tbl_categories WHERE id='$iCategory'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
		{
			$sCategories['knitting']           = $objDb->getField(0, 'knitting');
			$sCategories['linking']            = $objDb->getField(0, 'linking');
			$sCategories['yarn']               = $objDb->getField(0, 'yarn');
			$sCategories['sizing']             = $objDb->getField(0, 'sizing');
			$sCategories['weaving']            = $objDb->getField(0, 'weaving');
			$sCategories['leather_import']     = $objDb->getField(0, 'leather_import');
			$sCategories['dyeing']             = $objDb->getField(0, 'dyeing');
			$sCategories['leather_inspection'] = $objDb->getField(0, 'leather_inspection');
			$sCategories['lamination']         = $objDb->getField(0, 'lamination');
			$sCategories['cutting']            = $objDb->getField(0, 'cutting');
			$sCategories['print_embroidery']   = $objDb->getField(0, 'print_embroidery');
			$sCategories['sorting']            = $objDb->getField(0, 'sorting');
			$sCategories['bladder_attachment'] = $objDb->getField(0, 'bladder_attachment');
			$sCategories['stitching']          = $objDb->getField(0, 'stitching');
			$sCategories['washing']            = $objDb->getField(0, 'washing');
			$sCategories['finishing']          = $objDb->getField(0, 'finishing');
			$sCategories['lab_testing']        = $objDb->getField(0, 'lab_testing');
			$sCategories['quality']            = $objDb->getField(0, 'quality');
			$sCategories['packing']            = $objDb->getField(0, 'packing');
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
</head>

<body>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body">
	  <h2>Vendor Status Report</h2>

	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	    <tr bgcolor="#ffffff">
		  <td width="100%">

<?
	if ($sBtxDivision == "Y")
		@include($sBaseDir."includes/vsr/view-btx-vsr-po.php");

	else
		@include($sBaseDir."includes/vsr/view-vsr-po.php");
?>

		  </td>
	    </tr>
	  </table>

	  <br style="line-height:2px;" />
    </div>
<!--  Body Section Ends Here  -->

  </div>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>