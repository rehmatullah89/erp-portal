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
?>
		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
		 	  <tr>
			    <td width="175">GT Number</td>
			    <td width="20" align="center">:</td>
			    <td><?= $sGtNumber ?></td>
			  </tr>

		 	  <tr>
			    <td>Vendor</td>
			    <td align="center">:</td>
			    <td><?= $sVendor ?></td>
			  </tr>

		 	  <tr>
			    <td>Brand</td>
			    <td align="center">:</td>
			    <td><?= $sBrand ?></td>
			  </tr>

		 	  <tr>
			    <td>Style #</td>
			    <td align="center">:</td>
			    <td><?= $sStyle ?></td>
			  </tr>

		 	  <tr>
			    <td>Season</td>
			    <td align="center">:</td>
			    <td><?= $sSeason ?></td>
			  </tr>

		 	  <tr>
			    <td>Quantity</td>
			    <td align="center">:</td>
			    <td><?= formatNumber($iQuantity, false) ?></td>
			  </tr>

		 	  <tr>
			    <td>Price</td>
			    <td align="center">:</td>
			    <td><?= formatNumber($fPrice) ?></td>
			  </tr>

		 	  <tr>
			    <td>Price Variable</td>
			    <td align="center">:</td>
			    <td><?= (($sVariable == "Y") ? "Yes" : "No") ?></td>
			  </tr>

		 	  <tr>
			    <td>Programme</td>
			    <td align="center">:</td>
			    <td><?= $sProgramme ?></td>
			  </tr>

		 	  <tr>
			    <td>PO Received Date</td>
			    <td align="center">:</td>
			    <td><?= $sPoReceivedDate ?></td>
			  </tr>

		 	  <tr>
			    <td>Factory Work Order</td>
			    <td align="center">:</td>
			    <td><?= $sFactoryWorkOrder ?></td>
			  </tr>

		 	  <tr>
			    <td>Material/Fabric</td>
			    <td align="center">:</td>
			    <td><?= $sMaterialFabric ?></td>
			  </tr>

		 	  <tr>
			    <td>Finish</td>
			    <td align="center">:</td>
			    <td><?= $sFinish ?></td>
			  </tr>

		 	  <tr>
			    <td>Original ETD</td>
			    <td align="center">:</td>
			    <td><?= $sEtdRequired ?></td>
			  </tr>
<!--
		 	  <tr>
			    <td>Revised ETD</td>
			    <td align="center">:</td>
			    <td><?= $sRevisedEtd ?></td>
			  </tr>
-->
		 	  <tr>
			    <td>Customer Number</td>
			    <td align="center">:</td>
			    <td><?= $sCustomerNo ?></td>
			  </tr>

		 	  <tr>
			    <td>Article Number</td>
			    <td align="center">:</td>
			    <td><?= $sArticleNo ?></td>
			  </tr>

			  <tr>
			    <td>Mode</td>
			    <td align="center">:</td>
			    <td><?= $sMode ?></td>
			  </tr>

			  <tr>
			    <td>Trims</td>
			    <td align="center">:</td>
			    <td><?= $sTrims ?></td>
			  </tr>

			  <tr valign="top">
			    <td>Yarn/Fabric</td>
			    <td align="center">:</td>
			    <td><?= $sYarnFabric ?></td>
			  </tr>

			  <tr>
			    <td>QRS Submit Date</td>
			    <td align="center">:</td>
			    <td><?= $sQrsSubmitDate ?></td>
			  </tr>
<?
	if ($sCategories['knitting'] > 0)
	{
?>

			  <tr>
			    <td>Knitting</td>
			    <td align="center">:</td>
			    <td><?= $iKnitting ?>%</td>
			  </tr>

			  <tr>
			    <td>Knitting Start Date</td>
			    <td align="center">:</td>
			    <td><?= $sKnittingStartDate ?></td>
			  </tr>

			  <tr>
			    <td>Knitting End Date</td>
			    <td align="center">:</td>
			    <td><?= $sKnittingEndDate ?></td>
			  </tr>
<?
	}

	if ($sCategories['linking'] > 0)
	{
?>

			  <tr>
			    <td>Linking</td>
			    <td align="center">:</td>
			    <td><?= $iLinking ?>%</td>
			  </tr>

			  <tr>
			    <td>Linking Start Date</td>
			    <td align="center">:</td>
			    <td><?= $sLinkingStartDate ?></td>
			  </tr>

			  <tr>
			    <td>Linking End Date</td>
			    <td align="center">:</td>
			    <td><?= $sLinkingEndDate ?></td>
			  </tr>
<?
	}

	if ($sCategories['yarn'] > 0)
	{
?>

			  <tr>
			    <td>Yarn</td>
			    <td align="center">:</td>
			    <td><?= $iYarn ?>%</td>
			  </tr>

			  <tr>
			    <td>Yarn Start Date</td>
			    <td align="center">:</td>
			    <td><?= $sYarnStartDate ?></td>
			  </tr>

			  <tr>
			    <td>Yarn End Date</td>
			    <td align="center">:</td>
			    <td><?= $sYarnEndDate ?></td>
			  </tr>
<?
	}

	if ($sCategories['sizing'] > 0)
	{
?>

			  <tr>
			    <td>Sizing</td>
			    <td align="center">:</td>
			    <td><?= $iSizing ?>%</td>
			  </tr>

			  <tr>
			    <td>Sizing Start Date</td>
			    <td align="center">:</td>
			    <td><?= $sSizingStartDate ?></td>
			  </tr>

			  <tr>
			    <td>Sizing End Date</td>
			    <td align="center">:</td>
			    <td><?= $sSizingEndDate ?></td>
			  </tr>
<?
	}

	if ($sCategories['weaving'] > 0)
	{
?>
			  <tr>
			    <td>Weaving</td>
			    <td align="center">:</td>
			    <td><?= $iWeaving ?>%</td>
			  </tr>

			  <tr>
			    <td>Weaving Start Date</td>
			    <td align="center">:</td>
			    <td><?= $sWeavingStartDate ?></td>
			  </tr>

			  <tr>
			    <td>Weaving End Date</td>
			    <td align="center">:</td>
			    <td><?= $sWeavingEndDate ?></td>
			  </tr>
<?
	}

	if ($sCategories['leather_import'] > 0)
	{
?>

			  <tr>
			    <td>Leather Import</td>
			    <td align="center">:</td>
			    <td><?= $iLeatherImport ?>%</td>
			  </tr>

			  <tr>
			    <td>Leather Import Start Date</td>
			    <td align="center">:</td>
			    <td><?= $sLeatherImportStartDate ?></td>
			  </tr>

			  <tr>
			    <td>Leather Import End Date</td>
			    <td align="center">:</td>
			    <td><?= $sLeatherImportEndDate ?></td>
			  </tr>
<?
	}

	if ($sCategories['dyeing'] > 0)
	{
		if ($iDyeing == 0 && ($sDyeingStartDate == "" || $sDyeingStartDate == "0000-00-00") && ($sDyeingEndDate == "" || $sDyeingEndDate == "0000-00-00"))
		{
?>

			  <tr>
			    <td>Dyeing</td>
			    <td align="center">:</td>
			    <td>NA</td>
			  </tr>

			  <tr>
			    <td>Dyeing Start Date</td>
			    <td align="center">:</td>
			    <td>NA</td>
			  </tr>

			  <tr>
			    <td>Dyeing End Date</td>
			    <td align="center">:</td>
			    <td>NA</td>
			  </tr>
<?
		}

		else
		{
?>

			  <tr>
			    <td>Dyeing</td>
			    <td align="center">:</td>
			    <td><?= $iDyeing ?>%</td>
			  </tr>

			  <tr>
			    <td>Dyeing Start Date</td>
			    <td align="center">:</td>
			    <td><?= $sDyeingStartDate ?></td>
			  </tr>

			  <tr>
			    <td>Dyeing End Date</td>
			    <td align="center">:</td>
			    <td><?= $sDyeingEndDate ?></td>
			  </tr>
<?
		}
	}

	if ($sCategories['leather_inspection'] > 0)
	{
?>
			  <tr>
			    <td>Leather Inspection</td>
			    <td align="center">:</td>
			    <td><?= $iLeatherInspection ?>%</td>
			  </tr>

			  <tr>
			    <td>Leather Inspection Start Date</td>
			    <td align="center">:</td>
			    <td><?= $sLeatherInspectionStartDate ?></td>
			  </tr>

			  <tr>
			    <td>Leather Inspection End Date</td>
			    <td align="center">:</td>
			    <td><?= $sLeatherInspectionEndDate ?></td>
			  </tr>
<?
	}

	if ($sCategories['lamination'] > 0)
	{
?>
			  <tr>
			    <td>Lamination</td>
			    <td align="center">:</td>
			    <td><?= $iLamination ?>%</td>
			  </tr>

			  <tr>
			    <td>Lamination Start Date</td>
			    <td align="center">:</td>
			    <td><?= $sLaminationStartDate ?></td>
			  </tr>

			  <tr>
			    <td>Lamination End Date</td>
			    <td align="center">:</td>
			    <td><?= $sLaminationEndDate ?></td>
			  </tr>
<?
	}

	if ($sCategories['cutting'] > 0)
	{
?>

			  <tr>
			    <td>Cutting</td>
			    <td align="center">:</td>
			    <td><?= $iCutting ?>%</td>
			  </tr>
<?
		if ($iSubBrand == 67 || $iSubBrand == 75)
		{
?>

			  <tr>
			    <td>Cutting Pieces</td>
			    <td align="center">:</td>
			    <td><?= $iCuttingPieces ?></td>
			  </tr>
<?
		}
?>

			  <tr>
			    <td>Cutting Start Date</td>
			    <td align="center">:</td>
			    <td><?= $sCuttingStartDate ?></td>
			  </tr>

			  <tr>
			    <td>Cutting End Date</td>
			    <td align="center">:</td>
			    <td><?= $sCuttingEndDate ?></td>
			  </tr>
<?
	}


	if ($iSubBrand == 67 || $iSubBrand == 75)
	{
?>
			  <tr>
			    <td>Sewing Line Number / Identification</td>
			    <td align="center">:</td>
			    <td><?= $sSewingLine ?></td>
			  </tr>

			  <tr>
			    <td>Per Line Productivity</td>
			    <td align="center">:</td>
			    <td><?= $sPerLineProductivity ?></td>
			  </tr>
<?
	}


	if ($sCategories['print_embroidery'] > 0)
	{
?>

			  <tr>
			    <td>Print/Embroidery</td>
			    <td align="center">:</td>
			    <td><?= $iPrintEmbroidery ?>%</td>
			  </tr>
<?
		if ($iSubBrand == 67 || $iSubBrand == 75)
		{
?>


			  <tr>
			    <td>Print/Embroidery Pieces</td>
			    <td align="center">:</td>
			    <td><?= $iPrintEmbroideryPieces ?></td>
			  </tr>
<?
		}
?>
			  <tr>
			    <td>Print/Embroidery Start Date</td>
			    <td align="center">:</td>
			    <td><?= $sPrintEmbroideryStartDate ?></td>
			  </tr>

			  <tr>
			    <td>Print/Embroidery End Date</td>
			    <td align="center">:</td>
			    <td><?= $sPrintEmbroideryEndDate ?></td>
			  </tr>
<?
	}

	if ($sCategories['sorting'] > 0)
	{
?>
			  <tr>
			    <td>Sorting</td>
			    <td align="center">:</td>
			    <td><?= $iSorting ?>%</td>
			  </tr>

			  <tr>
			    <td>Sorting Start Date</td>
			    <td align="center">:</td>
			    <td><?= $sSortingStartDate ?></td>
			  </tr>

			  <tr>
			    <td>Sorting End Date</td>
			    <td align="center">:</td>
			    <td><?= $sSortingEndDate ?></td>
			  </tr>
<?
	}

	if ($sCategories['bladder_attachment'] > 0)
	{
?>
			  <tr>
			    <td>Bladder Attachment</td>
			    <td align="center">:</td>
			    <td><?= $iBladderAttachment ?>%</td>
			  </tr>

			  <tr>
			    <td>Bladder Attachment Start Date</td>
			    <td align="center">:</td>
			    <td><?= $sBladderAttachmentStartDate ?></td>
			  </tr>

			  <tr>
			    <td>Bladder Attachment End Date</td>
			    <td align="center">:</td>
			    <td><?= $sBladderAttachmentEndDate ?></td>
			  </tr>
<?
	}

	if ($sCategories['stitching'] > 0)
	{
?>

			  <tr>
			    <td>Stitching</td>
			    <td align="center">:</td>
			    <td><?= $iStitching ?>%</td>
			  </tr>
<?
		if ($iSubBrand == 67 || $iSubBrand == 75)
		{
?>


			  <tr>
			    <td>Stitching Pieces</td>
			    <td align="center">:</td>
			    <td><?= $iStitchingPieces ?></td>
			  </tr>
<?
		}
?>
			  <tr>
			    <td>Stitching Start Date</td>
			    <td align="center">:</td>
			    <td><?= $sStitchingStartDate ?></td>
			  </tr>

			  <tr>
			    <td>Stitching End Date</td>
			    <td align="center">:</td>
			    <td><?= $sStitchingEndDate ?></td>
			  </tr>
<?
	}

	if ($sCategories['washing'] > 0)
	{
		if ($iWashing == 0 && ($sWashingStartDate == "" || $sWashingStartDate == "0000-00-00") && ($sWashingEndDate == "" || $sWashingEndDate == "0000-00-00"))
		{
?>

			  <tr>
			    <td>Washing</td>
			    <td align="center">:</td>
			    <td>NA</td>
			  </tr>

			  <tr>
			    <td>Washing Start Date</td>
			    <td align="center">:</td>
			    <td>NA</td>
			  </tr>

			  <tr>
			    <td>Washing End Date</td>
			    <td align="center">:</td>
			    <td>NA</td>
			  </tr>
<?
		}

		else
		{
?>

			  <tr>
			    <td>Washing</td>
			    <td align="center">:</td>
			    <td><?= $iWashing ?>%</td>
			  </tr>

			  <tr>
			    <td>Washing Start Date</td>
			    <td align="center">:</td>
			    <td><?= $sWashingStartDate ?></td>
			  </tr>

			  <tr>
			    <td>Washing End Date</td>
			    <td align="center">:</td>
			    <td><?= $sWashingEndDate ?></td>
			  </tr>
<?
		}
	}

	if ($sCategories['finishing'] > 0)
	{
?>
			  <tr>
			    <td>Finishing</td>
			    <td align="center">:</td>
			    <td><?= $iFinishing ?>%</td>
			  </tr>

			  <tr>
			    <td>Finishing Start Date</td>
			    <td align="center">:</td>
			    <td><?= $sFinishingStartDate ?></td>
			  </tr>

			  <tr>
			    <td>Finishing End Date</td>
			    <td align="center">:</td>
			    <td><?= $sFinishingEndDate ?></td>
			  </tr>
<?
	}

	if ($sCategories['lab_testing'] > 0)
	{
?>
			  <tr>
			    <td>Lab Testing</td>
			    <td align="center">:</td>
			    <td><?= $iLabTesting ?>%</td>
			  </tr>

			  <tr>
			    <td>Lab Testing Start Date</td>
			    <td align="center">:</td>
			    <td><?= $sLabTestingStartDate ?></td>
			  </tr>

			  <tr>
			    <td>Lab Testing End Date</td>
			    <td align="center">:</td>
			    <td><?= $sLabTestingEndDate ?></td>
			  </tr>
<?
	}

	if ($sCategories['quality'] > 0)
	{
?>

			  <tr>
			    <td>Quality</td>
			    <td align="center">:</td>
			    <td><?= $iQuality ?>%</td>
			  </tr>

			  <tr>
			    <td>Quality Start Date</td>
			    <td align="center">:</td>
			    <td><?= $sQualityStartDate ?></td>
			  </tr>

			  <tr>
			    <td>Quality End Date</td>
			    <td align="center">:</td>
			    <td><?= $sQualityEndDate ?></td>
			  </tr>
<?
	}

	if ($sCategories['packing'] > 0)
	{
?>

			  <tr>
			    <td>Packing</td>
			    <td align="center">:</td>
			    <td><?= $iPacking ?>%</td>
			  </tr>
<?
		if ($iSubBrand == 67 || $iSubBrand == 75)
		{
?>


			  <tr>
			    <td>Packing Pieces</td>
			    <td align="center">:</td>
			    <td><?= $iPackingPieces ?></td>
			  </tr>
<?
		}
?>
			  <tr>
			    <td>Packing Start Date</td>
			    <td align="center">:</td>
			    <td><?= $sPackingStartDate ?></td>
			  </tr>

			  <tr>
			    <td>Packing End Date</td>
			    <td align="center">:</td>
			    <td><?= $sPackingEndDate ?></td>
			  </tr>
<?
	}
?>

			  <tr>
			    <td>Cut Off Date</td>
			    <td align="center">:</td>
			    <td><?= $sCutOffDate ?></td>
			  </tr>

			  <tr>
			    <td>Final Audit Date</td>
			    <td align="center">:</td>
			    <td><?= $sFinalAuditDate ?></td>
			  </tr>

			  <tr>
			    <td>Production Status</td>
			    <td align="center">:</td>
			    <td><?= $sProductionStatus ?></td>
			  </tr>

			  <tr>
			    <td>Shipped Qty</td>
			    <td align="center">:</td>
			    <td><?= formatNumber($iShippedQty, false) ?></td>
			  </tr>

			  <tr>
			    <td>ETD CTG/ZIA</td>
			    <td align="center">:</td>
			    <td><?= $sEtdCtgZia ?></td>
			  </tr>

			  <tr>
			    <td>ETA Denmark</td>
			    <td align="center">:</td>
			    <td><?= $sEtaDenmark ?></td>
			  </tr>

			  <tr>
			    <td>Destination</td>
			    <td align="center">:</td>
			    <td><?= $sDestination ?></td>
			  </tr>

			  <tr valign="top">
			    <td>Remarks</td>
			    <td align="center">:</td>
			    <td><?= nl2br($sRemarks) ?></td>
			  </tr>
		    </table>
