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

	$sSQL = "SELECT SUM(quantity) FROM tbl_po_quantities WHERE po_id='$Id'";
	$objDb->query($sSQL);

	$iQuantity = $objDb->getField(0, 0);

	if ($iSubBrand == 67 || $iSubBrand == 75)
	{
		$sForm[$iIndex]['Label']    = "GT Number";
		$sForm[$iIndex]['Field']    = "GtNumber";
		$sForm[$iIndex]['Value']    = $sGtNumber;
		$iIndex ++;
	}

	$sForm[$iIndex]['Label']    = "Quantity";
	$sForm[$iIndex]['Field']    = "Quantity";
	$sForm[$iIndex]['Value']    = formatNumber($iQuantity, false);
	$sForm[$iIndex]['Type']     = "READONLY";
	$iIndex ++;

	$sForm[$iIndex]['Label']    = "Price";
	$sForm[$iIndex]['Field']    = "Price";
	$sForm[$iIndex]['Value']    = formatNumber($fPrice);
	$iIndex ++;

	$sForm[$iIndex]['Label']    = "Variable";
	$sForm[$iIndex]['Field']    = "Variable";
	$sForm[$iIndex]['Value']    = $sVariable;
	$sForm[$iIndex]['Type']     = "DROPDOWN";
	$sForm[$iIndex]['Values']   = array("Y", "N");
	$sForm[$iIndex]['Labels']   = array("Yes", "No");
	$iIndex ++;

	$sForm[$iIndex]['Label']    = "Programme";
	$sForm[$iIndex]['Field']    = "Programme";
	$sForm[$iIndex]['Value']    = $sProgramme;
	$iIndex ++;

	$sForm[$iIndex]['Label']    = "PO Received Date";
	$sForm[$iIndex]['Field']    = "PoReceivedDate";
	$sForm[$iIndex]['Value']    = $sPoReceivedDate;
	$sForm[$iIndex]['Type']     = "DATE";
	$iIndex ++;

	$sForm[$iIndex]['Label']    = "Factory Work Order";
	$sForm[$iIndex]['Field']    = "FactoryWorkOrder";
	$sForm[$iIndex]['Value']    = $sFactoryWorkOrder;
	$iIndex ++;

	$sForm[$iIndex]['Label']    = "Material/Fabric";
	$sForm[$iIndex]['Field']    = "MaterialFabric";
	$sForm[$iIndex]['Value']    = $sMaterialFabric;
	$iIndex ++;

	$sForm[$iIndex]['Label']    = "Finish";
	$sForm[$iIndex]['Field']    = "Finish";
	$sForm[$iIndex]['Value']    = $sFinish;
	$iIndex ++;


	$sSQL = "SELECT etd_required FROM tbl_po_colors WHERE po_id='$Id' AND style_id='$iStyleId' LIMIT 1";
	$objDb->query($sSQL);

	$sEtdRequired = $objDb->getField(0, 0);

	$sForm[$iIndex]['Label']  = "Original ETD";
	$sForm[$iIndex]['Field']  = "EtdRequired";
	$sForm[$iIndex]['Value']  = formatDate($sEtdRequired);
	$sForm[$iIndex]['Type']   = "READONLY";
	$iIndex ++;

	$sForm[$iIndex]['Label'] = "Revised ETD";
	$sForm[$iIndex]['Field'] = "RevisedEtd";
	$sForm[$iIndex]['Value'] = $sRevisedEtd;
	$sForm[$iIndex]['Type']  = "DATE";
	$iIndex ++;

	if ($iSubBrand == 67 || $iSubBrand == 75)
	{
		$sForm[$iIndex]['Label']    = "Customer Number";
		$sForm[$iIndex]['Field']    = "CustomerNumber";
		$sForm[$iIndex]['Value']    = $sCustomerNo;
		$iIndex ++;

		$sForm[$iIndex]['Label']    = "Article Number";
		$sForm[$iIndex]['Field']    = "ArticleNumber";
		$sForm[$iIndex]['Value']    = $sArticleNo;
		$iIndex ++;
	}

	$sForm[$iIndex]['Label'] = "Mode";
	$sForm[$iIndex]['Field'] = "Mode";
	$sForm[$iIndex]['Value'] = $sMode;
	$iIndex ++;

	$sForm[$iIndex]['Label'] = "Trims";
	$sForm[$iIndex]['Field'] = "Trims";
	$sForm[$iIndex]['Value'] = $sTrims;
	$iIndex ++;

	$sForm[$iIndex]['Label'] = "Yarn/Fabric";
	$sForm[$iIndex]['Field'] = "YarnFabric";
	$sForm[$iIndex]['Value'] = $sYarnFabric;
	$iIndex ++;

	$sForm[$iIndex]['Label'] = "QRS Submit Date";
	$sForm[$iIndex]['Field'] = "QrsSubmitDate";
	$sForm[$iIndex]['Value'] = $sQrsSubmitDate;
	$sForm[$iIndex]['Type']   = "DATE";
	$iIndex ++;

	if ($sCategories['knitting'] > 0)
	{
		$sForm[$iIndex]['Label'] = "Knitting";
		$sForm[$iIndex]['Field'] = "Knitting";
		$sForm[$iIndex]['Value'] = $iKnitting;
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Knitting Start Date";
		$sForm[$iIndex]['Field'] = "KnittingStartDate";
		$sForm[$iIndex]['Value'] = $sKnittingStartDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Knitting End Date";
		$sForm[$iIndex]['Field'] = "KnittingEndDate";
		$sForm[$iIndex]['Value'] = $sKnittingEndDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;
	}

	if ($sCategories['linking'] > 0)
	{
		$sForm[$iIndex]['Label'] = "Linking";
		$sForm[$iIndex]['Field'] = "Linking";
		$sForm[$iIndex]['Value'] = $iLinking;
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Linking Start Date";
		$sForm[$iIndex]['Field'] = "LinkingStartDate";
		$sForm[$iIndex]['Value'] = $sLinkingStartDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Linking End Date";
		$sForm[$iIndex]['Field'] = "LinkingEndDate";
		$sForm[$iIndex]['Value'] = $sLinkingEndDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;
	}

	if ($sCategories['yarn'] > 0)
	{
		$sForm[$iIndex]['Label'] = "Yarn";
		$sForm[$iIndex]['Field'] = "Yarn";
		$sForm[$iIndex]['Value'] = $iYarn;
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Yarn Start Date";
		$sForm[$iIndex]['Field'] = "YarnStartDate";
		$sForm[$iIndex]['Value'] = $sYarnStartDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Yarn End Date";
		$sForm[$iIndex]['Field'] = "YarnEndDate";
		$sForm[$iIndex]['Value'] = $sYarnEndDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;
	}

	if ($sCategories['sizing'] > 0)
	{
		$sForm[$iIndex]['Label'] = "Sizing";
		$sForm[$iIndex]['Field'] = "Sizing";
		$sForm[$iIndex]['Value'] = $iSizing;
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Sizing Start Date";
		$sForm[$iIndex]['Field'] = "SizingStartDate";
		$sForm[$iIndex]['Value'] = $sSizingStartDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Sizing End Date";
		$sForm[$iIndex]['Field'] = "SizingEndDate";
		$sForm[$iIndex]['Value'] = $sSizingEndDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;
	}

	if ($sCategories['weaving'] > 0)
	{
		$sForm[$iIndex]['Label'] = "Weaving";
		$sForm[$iIndex]['Field'] = "Weaving";
		$sForm[$iIndex]['Value'] = $iWeaving;
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Weaving Start Date";
		$sForm[$iIndex]['Field'] = "WeavingStartDate";
		$sForm[$iIndex]['Value'] = $sWeavingStartDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Weaving End Date";
		$sForm[$iIndex]['Field'] = "WeavingEndDate";
		$sForm[$iIndex]['Value'] = $sWeavingEndDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;
	}

	if ($sCategories['leather_import'] > 0)
	{
		$sForm[$iIndex]['Label'] = "Leather Import";
		$sForm[$iIndex]['Field'] = "LeatherImport";
		$sForm[$iIndex]['Value'] = $iLeatherImport;
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Leather Import Start Date";
		$sForm[$iIndex]['Field'] = "LeatherImportStartDate";
		$sForm[$iIndex]['Value'] = $sLeatherImportStartDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Leather Import End Date";
		$sForm[$iIndex]['Field'] = "LeatherImportEndDate";
		$sForm[$iIndex]['Value'] = $sLeatherImportEndDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;
	}

	if ($sCategories['dyeing'] > 0)
	{
		$sForm[$iIndex]['Label'] = "Dyeing";
		$sForm[$iIndex]['Field'] = "Dyeing";
		$sForm[$iIndex]['Value'] = $iDyeing;
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Dyeing Start Date";
		$sForm[$iIndex]['Field'] = "DyeingStartDate";
		$sForm[$iIndex]['Value'] = $sDyeingStartDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Dyeing End Date";
		$sForm[$iIndex]['Field'] = "DyeingEndDate";
		$sForm[$iIndex]['Value'] = $sDyeingEndDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;
	}

	if ($sCategories['leather_inspection'] > 0)
	{
		$sForm[$iIndex]['Label'] = "Leather Inspection";
		$sForm[$iIndex]['Field'] = "LeatherInspection";
		$sForm[$iIndex]['Value'] = $iLeatherInspection;
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Leather Inspection Start Date";
		$sForm[$iIndex]['Field'] = "LeatherInspectionStartDate";
		$sForm[$iIndex]['Value'] = $sLeatherInspectionStartDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Leather Inspection End Date";
		$sForm[$iIndex]['Field'] = "LeatherInspectionEndDate";
		$sForm[$iIndex]['Value'] = $sLeatherInspectionEndDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;
	}

	if ($sCategories['lamination'] > 0)
	{
		$sForm[$iIndex]['Label'] = "Lamination";
		$sForm[$iIndex]['Field'] = "Lamination";
		$sForm[$iIndex]['Value'] = $iLamination;
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Lamination Start Date";
		$sForm[$iIndex]['Field'] = "LaminationStartDate";
		$sForm[$iIndex]['Value'] = $sLaminationStartDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Lamination End Date";
		$sForm[$iIndex]['Field'] = "LaminationEndDate";
		$sForm[$iIndex]['Value'] = $sLaminationEndDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;
	}

	if ($sCategories['cutting'] > 0)
	{
		$sForm[$iIndex]['Label'] = "Cutting";
		$sForm[$iIndex]['Field'] = "Cutting";
		$sForm[$iIndex]['Value'] = $iCutting;
		$iIndex ++;

		if ($iSubBrand == 67 || $iSubBrand == 75)
		{
			$sForm[$iIndex]['Label'] = "Cutting Pieces";
			$sForm[$iIndex]['Field'] = "CuttingPieces";
			$sForm[$iIndex]['Value'] = $iCuttingPieces;
			$iIndex ++;
		}

		$sForm[$iIndex]['Label'] = "Cutting Start Date";
		$sForm[$iIndex]['Field'] = "CuttingStartDate";
		$sForm[$iIndex]['Value'] = $sCuttingStartDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Cutting End Date";
		$sForm[$iIndex]['Field'] = "CuttingEndDate";
		$sForm[$iIndex]['Value'] = $sCuttingEndDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;
	}

	if ($iSubBrand == 67 || $iSubBrand == 75)
	{
		$sForm[$iIndex]['Label']    = "Sewing Line Number / Identification";
		$sForm[$iIndex]['Field']    = "SewingLine";
		$sForm[$iIndex]['Value']    = $sSewingLine;
		$iIndex ++;

		$sForm[$iIndex]['Label']    = "Per Line Productivity";
		$sForm[$iIndex]['Field']    = "PerLineProductivity";
		$sForm[$iIndex]['Value']    = $sPerLineProductivity;
		$iIndex ++;
	}

	if ($sCategories['print_embroidery'] > 0)
	{
		$sForm[$iIndex]['Label'] = "Print/Embroidery";
		$sForm[$iIndex]['Field'] = "PrintEmbroidery";
		$sForm[$iIndex]['Value'] = $iPrintEmbroidery;
		$iIndex ++;

		if ($iSubBrand == 67 || $iSubBrand == 75)
		{
			$sForm[$iIndex]['Label'] = "Print/Embroidery Pieces";
			$sForm[$iIndex]['Field'] = "PrintEmbroideryPieces";
			$sForm[$iIndex]['Value'] = $iPrintEmbroideryPieces;
			$iIndex ++;
		}

		$sForm[$iIndex]['Label'] = "Print/Embroidery Start Date";
		$sForm[$iIndex]['Field'] = "PrintEmbroideryStartDate";
		$sForm[$iIndex]['Value'] = $sPrintEmbroideryStartDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Print/Embroidery End Date";
		$sForm[$iIndex]['Field'] = "PrintEmbroideryEndDate";
		$sForm[$iIndex]['Value'] = $sPrintEmbroideryEndDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;
	}

	if ($sCategories['sorting'] > 0)
	{
		$sForm[$iIndex]['Label'] = "Sorting";
		$sForm[$iIndex]['Field'] = "Sorting";
		$sForm[$iIndex]['Value'] = $iSorting;
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Sorting Start Date";
		$sForm[$iIndex]['Field'] = "SortingStartDate";
		$sForm[$iIndex]['Value'] = $sSortingStartDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Sorting End Date";
		$sForm[$iIndex]['Field'] = "SortingEndDate";
		$sForm[$iIndex]['Value'] = $sSortingEndDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;
	}

	if ($sCategories['bladder_attachment'] > 0)
	{
		$sForm[$iIndex]['Label'] = "Bladder Attachment";
		$sForm[$iIndex]['Field'] = "BladderAttachment";
		$sForm[$iIndex]['Value'] = $iBladderAttachment;
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Bladder Attachment Start Date";
		$sForm[$iIndex]['Field'] = "BladderAttachmentStartDate";
		$sForm[$iIndex]['Value'] = $sBladderAttachmentStartDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Bladder Attachment End Date";
		$sForm[$iIndex]['Field'] = "BladderAttachmentEndDate";
		$sForm[$iIndex]['Value'] = $sBladderAttachmentEndDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;
	}

	if ($sCategories['stitching'] > 0)
	{
		$sForm[$iIndex]['Label'] = "Stitching";
		$sForm[$iIndex]['Field'] = "Stitching";
		$sForm[$iIndex]['Value'] = $iStitching;
		$iIndex ++;

		if ($iSubBrand == 67 || $iSubBrand == 75)
		{
			$sForm[$iIndex]['Label'] = "Stitching Pieces";
			$sForm[$iIndex]['Field'] = "StitchingPieces";
			$sForm[$iIndex]['Value'] = $iStitchingPieces;
			$iIndex ++;
		}

		$sForm[$iIndex]['Label'] = "Stitching Start Date";
		$sForm[$iIndex]['Field'] = "StitchingStartDate";
		$sForm[$iIndex]['Value'] = $sStitchingStartDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Stitching End Date";
		$sForm[$iIndex]['Field'] = "StitchingEndDate";
		$sForm[$iIndex]['Value'] = $sStitchingEndDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;
	}

	if ($sCategories['washing'] > 0)
	{
		$sForm[$iIndex]['Label'] = "Washing";
		$sForm[$iIndex]['Field'] = "Washing";
		$sForm[$iIndex]['Value'] = $iWashing;
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Washing Start Date";
		$sForm[$iIndex]['Field'] = "WashingStartDate";
		$sForm[$iIndex]['Value'] = $sWashingStartDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Washing End Date";
		$sForm[$iIndex]['Field'] = "WashingEndDate";
		$sForm[$iIndex]['Value'] = $sWashingEndDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;
	}

	if ($sCategories['finishing'] > 0)
	{
		$sForm[$iIndex]['Label'] = "Finishing";
		$sForm[$iIndex]['Field'] = "Finishing";
		$sForm[$iIndex]['Value'] = $iFinishing;
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Finishing Start Date";
		$sForm[$iIndex]['Field'] = "FinishingStartDate";
		$sForm[$iIndex]['Value'] = $sFinishingStartDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Finishing End Date";
		$sForm[$iIndex]['Field'] = "FinishingEndDate";
		$sForm[$iIndex]['Value'] = $sFinishingEndDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;
	}

	if ($sCategories['lab_testing'] > 0)
	{
		$sForm[$iIndex]['Label'] = "Lab Testing";
		$sForm[$iIndex]['Field'] = "LabTesting";
		$sForm[$iIndex]['Value'] = $iLabTesting;
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Lab Testing Start Date";
		$sForm[$iIndex]['Field'] = "LabTestingStartDate";
		$sForm[$iIndex]['Value'] = $sLabTestingStartDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Lab Testing End Date";
		$sForm[$iIndex]['Field'] = "LabTestingEndDate";
		$sForm[$iIndex]['Value'] = $sLabTestingEndDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;
	}

	if ($sCategories['quality'] > 0)
	{
		$sForm[$iIndex]['Label'] = "Quality";
		$sForm[$iIndex]['Field'] = "Quality";
		$sForm[$iIndex]['Value'] = $iQuality;
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Quality Start Date";
		$sForm[$iIndex]['Field'] = "QualityStartDate";
		$sForm[$iIndex]['Value'] = $sQualityStartDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Quality End Date";
		$sForm[$iIndex]['Field'] = "QualityEndDate";
		$sForm[$iIndex]['Value'] = $sQualityEndDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;
	}

	if ($sCategories['packing'] > 0)
	{
		$sForm[$iIndex]['Label'] = "Packing";
		$sForm[$iIndex]['Field'] = "Packing";
		$sForm[$iIndex]['Value'] = $iPacking;
		$iIndex ++;

		if ($iSubBrand == 67 || $iSubBrand == 75)
		{
			$sForm[$iIndex]['Label'] = "Packing Pieces";
			$sForm[$iIndex]['Field'] = "PackingPieces";
			$sForm[$iIndex]['Value'] = $iPackingPieces;
			$iIndex ++;
		}

		$sForm[$iIndex]['Label'] = "Packing Start Date";
		$sForm[$iIndex]['Field'] = "PackingStartDate";
		$sForm[$iIndex]['Value'] = $sPackingStartDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;

		$sForm[$iIndex]['Label'] = "Packing End Date";
		$sForm[$iIndex]['Field'] = "PackingEndDate";
		$sForm[$iIndex]['Value'] = $sPackingEndDate;
		$sForm[$iIndex]['Type']  = "DATE";
		$iIndex ++;
	}

	$sForm[$iIndex]['Label'] = "Cut Off Date";
	$sForm[$iIndex]['Field'] = "CutOffDate";
	$sForm[$iIndex]['Value'] = $sCutOffDate;
	$sForm[$iIndex]['Type']  = "DATE";
	$iIndex ++;

	$sForm[$iIndex]['Label'] = "Final Audit Date";
	$sForm[$iIndex]['Field'] = "FinalAuditDate";
	$sForm[$iIndex]['Value'] = $sFinalAuditDate;
	$sForm[$iIndex]['Type']  = "DATE";
	$iIndex ++;

	$sForm[$iIndex]['Label'] = "Production Status";
	$sForm[$iIndex]['Field'] = "ProductionStatus";
	$sForm[$iIndex]['Value'] = $sProductionStatus;
	$iIndex ++;

	$sForm[$iIndex]['Label'] = "ETD CTG/ZIA";
	$sForm[$iIndex]['Field'] = "EtdCtgZia";
	$sForm[$iIndex]['Value'] = $sEtdCtgZia;
	$sForm[$iIndex]['Type']  = "DATE";
	$iIndex ++;

	$sForm[$iIndex]['Label'] = "ETA Denmark";
	$sForm[$iIndex]['Field'] = "EtaDenmark";
	$sForm[$iIndex]['Value'] = $sEtaDenmark;
	$sForm[$iIndex]['Type']  = "DATE";
	$iIndex ++;

	$iDestinations = array( );
	$sDestinations = array( );

	$sSQL = "SELECT id, destination FROM tbl_destinations WHERE id IN (SELECT destination_id FROM tbl_po_colors WHERE po_id='$Id')";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iDestinations[] = $objDb->getField($i, 0);
		$sDestinations[] = $objDb->getField($i, 1);
	}

	$sForm[$iIndex]['Label']   = "Destination";
	$sForm[$iIndex]['Field']   = "Destination";
	$sForm[$iIndex]['Value']   = $iDestinationId;
	$sForm[$iIndex]['Type']    = "DROPDOWN";
	$sForm[$iIndex]['Values']  = $iDestinations;
	$sForm[$iIndex]['Labels']  = $sDestinations;
	$iIndex ++;

	$sForm[$iIndex]['Label']   = "Remarks";
	$sForm[$iIndex]['Field']   = "Remarks";
	$sForm[$iIndex]['Value']   = $sRemarks;
	$sForm[$iIndex]['Type']    = "TEXTAREA";
	$sForm[$iIndex]['Rows']    = "5";
	$sForm[$iIndex]['Columns'] = "110";
?>