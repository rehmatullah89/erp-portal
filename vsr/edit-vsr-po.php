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

	if ($sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Id      = IO::intValue('Id');
	$Referer = urldecode(IO::strValue("Referer"));

	if ($Referer == "")
		$Referer = $_SERVER['HTTP_REFERER'];

	$sSQL = "SELECT * FROM tbl_vsr WHERE po_id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		redirect($_SERVER['HTTP_REFERER'], "DB_ERROR");

	else
	{
		$iGtNumber                   = $objDb->getField(0, 'gt_no');
		$iStyleId                    = $objDb->getField(0, 'style_id');
		$sSubContractor              = $objDb->getField(0, 'sub_contractor');
		$sItem                       = $objDb->getField(0, 'item');
		$fPrice                      = $objDb->getField(0, 'price');
		$sVariable                   = $objDb->getField(0, 'variable');
		$sProgramme                  = $objDb->getField(0, 'programme');
		$sPoReceivedDate             = $objDb->getField(0, 'po_received_date');
		$sFactoryWorkOrder           = $objDb->getField(0, 'factory_work_order');
		$sMaterialFabric             = $objDb->getField(0, 'material_fabric');
		$sFinish                     = $objDb->getField(0, 'finish');
		$sRevisedEtd                 = $objDb->getField(0, 'revised_etd');
		$sCustomerNo                 = $objDb->getField(0, 'customer_no');
		$sArticleNo                  = $objDb->getField(0, 'article_no');
		$sMode                       = $objDb->getField(0, 'mode');
		$sTrims                      = $objDb->getField(0, 'trims');
		$sYarnFabric                 = $objDb->getField(0, 'yarn_fabric');
		$sQrsSubmitDate              = $objDb->getField(0, 'qrs_submit_date');
		$iKnitting                   = $objDb->getField(0, 'knitting');
		$sKnittingStartDate          = $objDb->getField(0, 'knitting_start_date');
		$sKnittingEndDate            = $objDb->getField(0, 'knitting_end_date');
		$iLinking                    = $objDb->getField(0, 'linking');
		$sLinkingStartDate           = $objDb->getField(0, 'linking_start_date');
		$sLinkingEndDate             = $objDb->getField(0, 'linking_end_date');
		$iYarn                       = $objDb->getField(0, 'yarn');
		$sYarnStartDate              = $objDb->getField(0, 'yarn_start_date');
		$sYarnEndDate                = $objDb->getField(0, 'yarn_end_date');
		$iSizing                     = $objDb->getField(0, 'sizing');
		$sSizingStartDate            = $objDb->getField(0, 'sizing_start_date');
		$sSizingEndDate              = $objDb->getField(0, 'sizing_end_date');
		$iWeavingPercentage          = $objDb->getField(0, 'weaving');
		$sWeavingStartDate           = $objDb->getField(0, 'weaving_start_date');
		$sWeavingEndDate             = $objDb->getField(0, 'weaving_end_date');
		$iLeatherImport              = $objDb->getField(0, 'leather_import');
		$sLeatherImportStartDate     = $objDb->getField(0, 'leather_import_start_date');
		$sLeatherImportEndDate       = $objDb->getField(0, 'leather_import_end_date');
		$iDyeing                     = $objDb->getField(0, 'dyeing');
		$sDyeingStartDate            = $objDb->getField(0, 'dyeing_start_date');
		$sDyeingEndDate              = $objDb->getField(0, 'dyeing_end_date');
		$iLeatherInspection          = $objDb->getField(0, 'leather_inspection');
		$sLeatherInspectionStartDate = $objDb->getField(0, 'leather_inspection_start_date');
		$sLeatherInspectionEndDate   = $objDb->getField(0, 'leather_inspection_end_date');
		$iLamination                 = $objDb->getField(0, 'lamination');
		$sLaminationStartDate        = $objDb->getField(0, 'lamination_start_date');
		$sLaminationEndDate          = $objDb->getField(0, 'lamination_end_date');
		$iCutting                    = $objDb->getField(0, 'cutting');
		$iCuttingPieces              = $objDb->getField(0, 'cutting_pieces');
		$sCuttingStartDate           = $objDb->getField(0, 'cutting_end_date');
		$sCuttingEndDate             = $objDb->getField(0, 'cutting_end_date');
		$sSewingLine                 = $objDb->getField(0, 'sewing_line');
		$sPerLineProductivity        = $objDb->getField(0, 'per_line_productivity');
		$iPrintEmbroidery            = $objDb->getField(0, 'print_embroidery');
		$iPrintEmbroideryPieces      = $objDb->getField(0, 'print_embroidery_pieces');
		$sPrintEmbroideryStartDate   = $objDb->getField(0, 'print_embroidery_start_date');
		$sPrintEmbroideryEndDate     = $objDb->getField(0, 'print_embroidery_end_date');
		$iSorting                    = $objDb->getField(0, 'sorting');
		$sSortingStartDate           = $objDb->getField(0, 'sorting_start_date');
		$sSortingEndDate             = $objDb->getField(0, 'sorting_end_date');
		$iBladderAttachment          = $objDb->getField(0, 'bladder_attachment');
		$sBladderAttachmentStartDate = $objDb->getField(0, 'bladder_attachment_start_date');
		$sBladderAttachmentEndDate   = $objDb->getField(0, 'bladder_attachment_end_date');
		$iStitching                  = $objDb->getField(0, 'stitching');
		$iStitchingPieces            = $objDb->getField(0, 'stitching_pieces');
		$sStitchingStartDate         = $objDb->getField(0, 'stitching_start_date');
		$sStitchingEndDate           = $objDb->getField(0, 'stitching_end_date');
		$iWashing                    = $objDb->getField(0, 'washing');
		$sWashingStartDate           = $objDb->getField(0, 'washing_start_date');
		$sWashingEndDate             = $objDb->getField(0, 'washing_end_date');
		$iFinishing                  = $objDb->getField(0, 'finishing');
		$sFinishingStartDate         = $objDb->getField(0, 'finishing_start_date');
		$sFinishingEndDate           = $objDb->getField(0, 'finishing_end_date');
		$iLabTesting                 = $objDb->getField(0, 'lab_testing');
		$sLabTestingStartDate        = $objDb->getField(0, 'lab_testing_start_date');
		$sLabTestingEndDate          = $objDb->getField(0, 'lab_testing_end_date');
		$iQuality                    = $objDb->getField(0, 'quality');
		$sQualityStartDate           = $objDb->getField(0, 'quality_start_date');
		$sQualityEndDate             = $objDb->getField(0, 'quality_end_date');
		$iPacking                    = $objDb->getField(0, 'packing');
		$iPackingPieces              = $objDb->getField(0, 'packing_pieces');
		$sPackingStartDate           = $objDb->getField(0, 'packing_start_date');
		$sPackingEndDate             = $objDb->getField(0, 'packing_end_date');
		$sCutOffDate                 = $objDb->getField(0, 'cut_off_date');
		$sFinalAuditDate             = $objDb->getField(0, 'final_audit_date');
		$sProductionStatus           = $objDb->getField(0, 'production_status');
		$sEtdCtgZia                  = $objDb->getField(0, 'etd_ctg_zia');
		$sEtaDenmark                 = $objDb->getField(0, 'eta_denmark');
		$iDestinationId              = $objDb->getField(0, 'destination_id');
		$sRemarks                    = $objDb->getField(0, 'remarks');
	}


	$sSQL = "SELECT sub_brand_id FROM tbl_styles WHERE id='$iStyleId'";
	$objDb->query($sSQL);

	$iSubBrand = $objDb->getField(0, 0);


	$sForm  = array( );
	$iIndex = 0;

	$sForm[$iIndex]['Label'] = "<b>Purchase Order</b>";
	$sForm[$iIndex]['Field'] = "PO";
	$sForm[$iIndex]['Value'] = ("<b>".IO::strValue("PO")."</b>");
	$sForm[$iIndex]['Type']  = "READONLY";

	$iIndex ++;

	$sStyles = array( );
	$iStyles = array( );

	$sSQL = "SELECT id, style, (SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand, (SELECT season FROM tbl_seasons WHERE id=tbl_styles.sub_season_id) AS _Season FROM tbl_styles WHERE id IN (SELECT style_id FROM tbl_po_colors WHERE po_id='$Id')";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iStyles[] = $objDb->getField($i, 0);
		$sStyles[] = ($objDb->getField($i, 1)." (".$objDb->getField($i, 2).", ".$objDb->getField($i, 3).")");
	}


	$sForm[$iIndex]['Label']    = "Style #";
	$sForm[$iIndex]['Field']    = "Style";
	$sForm[$iIndex]['Value']    = $iStyleId;
	$sForm[$iIndex]['Type']     = "DROPDOWN";
	$sForm[$iIndex]['Values']   = $iStyles;
	$sForm[$iIndex]['Labels']   = $sStyles;
	$iIndex ++;

	$sSQL = "SELECT vendor, category_id, btx_division FROM tbl_vendors WHERE id=(SELECT vendor_id FROM tbl_po WHERE id='$Id') AND parent_id='0' AND sourcing='Y'";
	$objDb->query($sSQL);

	$sVendor      = $objDb->getField(0, 0);
	$iCategory    = $objDb->getField(0, 1);
	$sBtxDivision = $objDb->getField(0, 2);

	$sForm[$iIndex]['Label']    = "Vendor";
	$sForm[$iIndex]['Field']    = "Vendor";
	$sForm[$iIndex]['Value']    = $sVendor;
	$sForm[$iIndex]['Type']     = "READONLY";
	$iIndex ++;


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


	if ($sBtxDivision == "Y")
		@include($sBaseDir."includes/vsr/edit-btx-vsr-po.php");

	else
		@include($sBaseDir."includes/vsr/edit-vsr-po.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
</head>

<body>

<div id="MainDiv">
  <div id="PageLeftBorder">
    <div id="PageRightBorder">

<!--  Message Section Starts Here  -->
<?
	@include($sBaseDir."includes/messages.php");
?>
<!--  Message Section Ends Here  -->

      <div id="PageContents">

<!--  Header Section Starts Here  -->
<?
	@include($sBaseDir."includes/header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Navigation Section Starts Here  -->
<?
	@include($sBaseDir."includes/navigation.php");
?>
<!--  Navigation Section Ends Here  -->


<!--  Body Section Starts Here  -->
	    <div id="Body">
		  <table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr valign="top">
			  <td width="100%">
			    <h1><img src="images/h1/vsr/vsr-data.jpg" width="121" height="20" vspace="10" alt="" title="" /></h1>

			    <form name="frmData" id="frmData" method="post" action="vsr/save-vsr-po.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="PO" value="<?= IO::strValue('PO') ?>" />
			    <input type="hidden" name="Referer" value="<?= $Referer ?>" />

				<h2>Vendor Status Report</h2>

<?
	showForm($sForm, "", 175);
?>

				<div class="buttonsBar">
				  <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" />
				  <input type="button" value="" class="btnBack" title="Back" onclick="document.location='<?= $Referer ?>';" />
				</div>
			    </form>

			  </td>
			</tr>
		  </table>

<?
	@include($sBaseDir."includes/my-profile.php");
?>
        </div>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include($sBaseDir."includes/footer.php");
?>
<!--  Footer Section Ends Here  -->

      </div>
    </div>
  </div>
</div>

<!--  Bottom Bar Section Starts Here  -->
<?
	@include($sBaseDir."includes/bottom-bar.php");
?>
<!--  Bottom Bar Section Ends Here  -->

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>