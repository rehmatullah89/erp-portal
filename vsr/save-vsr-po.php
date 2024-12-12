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

	$Id      = IO::intValue('Id');
	$Po      = IO::strValue('PO');
	$Referer = IO::strValue('Referer');

	$sSQL = ("UPDATE tbl_vsr SET gt_no='".IO::strValue('GtNumber')."', style_id='".IO::intValue('Style')."', sub_contractor='".IO::strValue('SubContractor')."', item='".IO::strValue('Item')."', price='".IO::floatValue('Price')."', variable='".IO::strValue('Variable')."', programme='".IO::strValue('Programme')."', po_received_date='".((IO::strValue('PoReceivedDate') == "") ? '0000-00-00' : IO::strValue('PoReceivedDate'))."', factory_work_order='".IO::strValue('FactoryWorkOrder')."', material_fabric='".IO::strValue('MaterialFabric')."', finish='".IO::strValue('Finish')."', revised_etd='".((IO::strValue('RevisedEtd') == "") ? '0000-00-00' : IO::strValue('RevisedEtd'))."', customer_no='".IO::strValue('CustomerNumber')."', article_no='".IO::strValue('ArticleNumber')."', mode='".IO::strValue('Mode')."', trims='".IO::strValue('Trims')."', yarn_fabric='".IO::strValue('YarnFabric')."', qrs_submit_date='".((IO::strValue('QrsSubmitDate') == "") ? '0000-00-00' : IO::strValue('QrsSubmitDate'))."', knitting='".IO::intValue('Knitting')."', knitting_start_date='".((IO::strValue('KnittingStartDate') == "") ? '0000-00-00' : IO::strValue('KnittingStartDate'))."', knitting_end_date='".((IO::strValue('KnittingEndDate') == "") ? '0000-00-00' : IO::strValue('KnittingEndDate'))."', linking='".IO::intValue('Linking')."', linking_start_date='".((IO::strValue('LinkingStartDate') == "") ? '0000-00-00' : IO::strValue('LinkingStartDate'))."', linking_end_date='".((IO::strValue('LinkingEndDate') == "") ? '0000-00-00' : IO::strValue('LinkingEndDate'))."', yarn='".IO::intValue('Yarn')."', yarn_start_date='".((IO::strValue('YarnStartDate') == "") ? '0000-00-00' : IO::strValue('YarnStartDate'))."', yarn_end_date='".((IO::strValue('YarnEndDate') == "") ? '0000-00-00' : IO::strValue('YarnEndDate'))."', sizing='".IO::intValue('Sizing')."', sizing_start_date='".((IO::strValue('SizingStartDate') == "") ? '0000-00-00' : IO::strValue('SizingStartDate'))."', sizing_end_date='".((IO::strValue('SizingEndDate') == "") ? '0000-00-00' : IO::strValue('SizingEndDate'))."', weaving='".IO::intValue('Weaving')."', weaving_start_date='".((IO::strValue('WeavingStartDate') == "") ? '0000-00-00' : IO::strValue('WeavingStartDate'))."', weaving_end_date='".((IO::strValue('WeavingEndDate') == "") ? '0000-00-00' : IO::strValue('WeavingEndDate'))."', leather_import='".IO::intValue('LeatherImport')."', leather_import_start_date='".((IO::strValue('LeatherImportStartDate') == "") ? '0000-00-00' : IO::strValue('LeatherImportStartDate'))."', leather_import_end_date='".((IO::strValue('LeatherImportEndDate') == "") ? '0000-00-00' : IO::strValue('LeatherImportEndDate'))."', dyeing='".IO::intValue('Dyeing')."', dyeing_start_date='".((IO::strValue('DyeingStartDate') == "") ? '0000-00-00' : IO::strValue('DyeingStartDate'))."', dyeing_end_date='".((IO::strValue('DyeingEndDate') == "") ? '0000-00-00' : IO::strValue('DyeingEndDate'))."', leather_inspection='".IO::intValue('LeatherInspection')."', leather_inspection_start_date='".((IO::strValue('LeatherInspectionStartDate') == "") ? '0000-00-00' : IO::strValue('LeatherInspectionStartDate'))."', leather_inspection_end_date='".((IO::strValue('LeatherInspectionEndDate') == "") ? '0000-00-00' : IO::strValue('LeatherInspectionEndDate'))."', lamination='".IO::intValue('Lamination')."', lamination_start_date='".((IO::strValue('LaminationStartDate') == "") ? '0000-00-00' : IO::strValue('LaminationStartDate'))."', lamination_end_date='".((IO::strValue('LaminationEndDate') == "") ? '0000-00-00' : IO::strValue('LaminationEndDate'))."', cutting='".IO::intValue('Cutting')."', cutting_pieces='".IO::intValue('CuttingPieces')."', cutting_start_date='".((IO::strValue('CuttingStartDate') == "") ? '0000-00-00' : IO::strValue('CuttingStartDate'))."', cutting_end_date='".((IO::strValue('CuttingEndDate') == "") ? '0000-00-00' : IO::strValue('CuttingEndDate'))."', sewing_line='".IO::intValue('SewingLine')."', per_line_productivity='".IO::intValue('PerLineProductivity')."', print_embroidery='".IO::intValue('PrintEmbroidery')."', print_embroidery_pieces='".IO::intValue('PrintEmbroideryPieces')."', print_embroidery_start_date='".((IO::strValue('PrintEmbroideryStartDate') == "") ? '0000-00-00' : IO::strValue('PrintEmbroideryStartDate'))."', print_embroidery_end_date='".((IO::strValue('PrintEmbroideryEndDate') == "") ? '0000-00-00' : IO::strValue('PrintEmbroideryEndDate'))."', sorting='".IO::intValue('Sorting')."', sorting_start_date='".((IO::strValue('SortingStartDate') == "") ? '0000-00-00' : IO::strValue('SortingStartDate'))."', sorting_end_date='".((IO::strValue('SortingEndDate') == "") ? '0000-00-00' : IO::strValue('SortingEndDate'))."', bladder_attachment='".IO::intValue('BladderAttachment')."', bladder_attachment_start_date='".((IO::strValue('BladderAttachmentStartDate') == "") ? '0000-00-00' : IO::strValue('BladderAttachmentStartDate'))."', bladder_attachment_end_date='".((IO::strValue('BladderAttachmentEndDate') == "") ? '0000-00-00' : IO::strValue('BladderAttachmentEndDate'))."', stitching='".IO::intValue('Stitching')."', stitching_pieces='".IO::intValue('StitchingPieces')."', stitching_start_date='".((IO::strValue('StitchingStartDate') == "") ? '0000-00-00' : IO::strValue('StitchingStartDate'))."', stitching_end_date='".((IO::strValue('StitchingEndDate') == "") ? '0000-00-00' : IO::strValue('StitchingEndDate'))."', washing='".IO::intValue('Washing')."', washing_start_date='".((IO::strValue('WashingStartDate') == "") ? '0000-00-00' : IO::strValue('WashingStartDate'))."', washing_end_date='".((IO::strValue('WashingEndDate') == "") ? '0000-00-00' : IO::strValue('WashingEndDate'))."', finishing='".IO::intValue('Finishing')."', finishing_start_date='".((IO::strValue('FinishingStartDate') == "") ? '0000-00-00' : IO::strValue('FinishingStartDate'))."', finishing_end_date='".((IO::strValue('FinishingEndDate') == "") ? '0000-00-00' : IO::strValue('FinishingEndDate'))."', lab_testing='".IO::intValue('LabTesting')."', lab_testing_start_date='".((IO::strValue('LabTestingStartDate') == "") ? '0000-00-00' : IO::strValue('LabTestingStartDate'))."', lab_testing_end_date='".((IO::strValue('LabTestingEndDate') == "") ? '0000-00-00' : IO::strValue('LabTestingEndDate'))."', quality='".IO::intValue('Quality')."', quality_start_date='".((IO::strValue('QualityStartDate') == "") ? '0000-00-00' : IO::strValue('QualityStartDate'))."', quality_end_date='".((IO::strValue('QualityEndDate') == "") ? '0000-00-00' : IO::strValue('QualityEndDate'))."', packing_pieces='".IO::intValue('PackingPieces')."', packing='".IO::intValue('Packing')."', packing_start_date='".((IO::strValue('PackingStartDate') == "") ? '0000-00-00' : IO::strValue('PackingStartDate'))."', packing_end_date='".((IO::strValue('PackingEndDate') == "") ? '0000-00-00' : IO::strValue('PackingEndDate'))."', cut_off_date='".((IO::strValue('CutOffDate') == "") ? '0000-00-00' : IO::strValue('CutOffDate'))."', final_audit_date='".((IO::strValue('FinalAuditDate') == "") ? '0000-00-00' : IO::strValue('FinalAuditDate'))."', production_status='".IO::strValue('ProductionStatus')."', etd_ctg_zia='".((IO::strValue('EtdCtgZia') == "") ? '0000-00-00' : IO::strValue('EtdCtgZia'))."', eta_denmark='".((IO::strValue('EtaDenmark') == "") ? '0000-00-00' : IO::strValue('EtaDenmark'))."', destination_id='".IO::intValue('Destination')."', remarks='".IO::strValue('Remarks')."', modified=NOW( ), modified_by='{$_SESSION['UserId']}' WHERE po_id='$Id'");

	if ($objDb->execute($sSQL) == true)
		redirect(urldecode($Referer), "VSR_ENTRY_SAVED");

	$_SESSION['Flag'] = "DB_ERROR";

	header("Location: edit-vsr-po.php?Id={$Id}&PO={$Po}&Referer=".urlencode($Referer));

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>