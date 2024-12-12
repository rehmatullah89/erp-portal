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

	@require_once("../requires/Excel/reader.php");

	$objExcel = new Spreadsheet_Excel_Reader( );

	$objExcel->setOutputEncoding('CP1251');
	$objExcel->read($sBaseDir.TEMP_DIR.$sVsrFile);

	$iRows    = $objExcel->sheets[0]['numRows'];
	$iColumns = $objExcel->sheets[0]['numCols'];

	if ($iColumns < 27 || $iRows < 11)
	{
		@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

		redirect("vsr-data.php", "INVALID_VSR_FILE");
	}


	$iRow    = 10;
	$iColumn = 1;

	if ($objExcel->sheets[0]['cells'][$iRow][$iColumn] != "GT Number" ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 1)] != "Vendor" ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 2)] != "Brand" ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 3)] != "Order No" ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 4)] != "Style" ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 5)] != "Season" ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 6)] != "Quantity" ||
		$objExcel->sheets[0]['cells'][$iRow][($iColumn + 7)] != "Programme" ||
		$objExcel->sheets[0]['cells'][$iRow][($iColumn + 8)] != "PO Received Date" ||
		$objExcel->sheets[0]['cells'][$iRow][($iColumn + 9)] != "Factory Work Order" ||
		$objExcel->sheets[0]['cells'][$iRow][($iColumn + 10)] !=  "Material/Fabric" ||
		$objExcel->sheets[0]['cells'][$iRow][($iColumn + 11)] != "Finish" ||
		$objExcel->sheets[0]['cells'][$iRow][($iColumn + 12)] != "Original ETD" ||
		$objExcel->sheets[0]['cells'][$iRow][($iColumn + 13)] != "Revised ETD" ||
		$objExcel->sheets[0]['cells'][$iRow][($iColumn + 14)] != "Customer Number" ||
		$objExcel->sheets[0]['cells'][$iRow][($iColumn + 15)] != "Article Number" ||
		$objExcel->sheets[0]['cells'][$iRow][($iColumn + 16)] != "Price" ||
		$objExcel->sheets[0]['cells'][$iRow][($iColumn + 17)] != "Variable" ||
		$objExcel->sheets[0]['cells'][$iRow][($iColumn + 18)] != "Mode" ||
		$objExcel->sheets[0]['cells'][$iRow][($iColumn + 19)] != "Trims" ||
		$objExcel->sheets[0]['cells'][$iRow][($iColumn + 20)] != "Yarn/Fabric" ||
		$objExcel->sheets[0]['cells'][$iRow][($iColumn + 21)] != "QRS Submit Date")
	{
		@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

		redirect("vsr-data.php", "INVALID_VSR_FILE");
	}

	$iColumn += 21;


	if ($sCategories['knitting'] > 0)
	{
	    if ($objExcel->sheets[0]['cells'][$iRow][($iColumn + 1)] != "Knitting" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 2)] != "Knitting Start Date" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 3)] != "Knitting End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['linking'] > 0)
	{
	    if ($objExcel->sheets[0]['cells'][$iRow][($iColumn + 1)] != "Linking" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 2)] != "Linking Start Date" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 3)] != "Linking End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['yarn'] > 0)
	{
	    if ($objExcel->sheets[0]['cells'][$iRow][($iColumn + 1)] != "Yarn" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 2)] != "Yarn Start Date" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 3)] != "Yarn End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['sizing'] > 0)
	{
	    if ($objExcel->sheets[0]['cells'][$iRow][($iColumn + 1)] != "Sizing" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 2)] != "Sizing Start Date" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 3)] != "Sizing End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['weaving'] > 0)
	{
	    if ($objExcel->sheets[0]['cells'][$iRow][($iColumn + 1)] != "Weaving" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 2)] != "Weaving Start Date" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 3)] != "Weaving End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['leather_import'] > 0)
	{
	    if ($objExcel->sheets[0]['cells'][$iRow][($iColumn + 1)] != "Leather Import" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 2)] != "Leather Import Start Date" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 3)] != "Leather Import End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['dyeing'] > 0)
	{
	    if ($objExcel->sheets[0]['cells'][$iRow][($iColumn + 1)] != "Dyeing" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 2)] != "Dyeing Start Date" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 3)] != "Dyeing End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['leather_inspection'] > 0)
	{
	    if ($objExcel->sheets[0]['cells'][$iRow][($iColumn + 1)] != "Leather Inspection" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 2)] != "Leather Inspection Start Date" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 3)] != "Leather Inspection End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['lamination'] > 0)
	{
	    if ($objExcel->sheets[0]['cells'][$iRow][($iColumn + 1)] != "Lamination" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 2)] != "Lamination Start Date" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 3)] != "Lamination End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['cutting'] > 0)
	{
	    if ($objExcel->sheets[0]['cells'][$iRow][($iColumn + 1)] != "Cutting" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 2)] != "Knitting Pieces" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 3)] != "Cutting Start Date" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 4)] != "Cutting End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 4;
	}


	if ($objExcel->sheets[0]['cells'][$iRow][($iColumn + 1)] != "Sewing Line Number / Identification" ||
		$objExcel->sheets[0]['cells'][$iRow][($iColumn + 2)] != "Per Line Productivity")
	{
		@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

		redirect("vsr-data.php", "INVALID_VSR_FILE");
	}

	$iColumn += 2;


	if ($sCategories['print_embroidery'] > 0)
	{
	    if ($objExcel->sheets[0]['cells'][$iRow][($iColumn + 1)] != "Print/Embroidery" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 2)] != "Print/Embroidery Pieces" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 3)] != "Print/Embroidery Start Date" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 4)] != "Print/Embroidery End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 4;
	}


	if ($sCategories['sorting'] > 0)
	{
	    if ($objExcel->sheets[0]['cells'][$iRow][($iColumn + 1)] != "Sorting" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 2)] != "Sorting Start Date" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 3)] != "Sorting End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['bladder_attachment'] > 0)
	{
	    if ($objExcel->sheets[0]['cells'][$iRow][($iColumn + 1)] != "Bladder Attachment" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 2)] != "Bladder Attachment Start Date" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 3)] != "Bladder Attachment End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['stitching'] > 0)
	{
	    if ($objExcel->sheets[0]['cells'][$iRow][($iColumn + 1)] != "Stitching" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 2)] != "Stitching Pieces" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 3)] != "Stitching Start Date" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 4)] != "Stitching End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 4;
	}


	if ($sCategories['washing'] > 0)
	{
	    if ($objExcel->sheets[0]['cells'][$iRow][($iColumn + 1)] != "Washing" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 2)] != "Washing Start Date" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 3)] != "Washing End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['finishing'] > 0)
	{
	    if ($objExcel->sheets[0]['cells'][$iRow][($iColumn + 1)] != "Finishing" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 2)] != "Finishing Start Date" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 3)] != "Finishing End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['lab_testing'] > 0)
	{
	    if ($objExcel->sheets[0]['cells'][$iRow][($iColumn + 1)] != "Lab Testing" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 2)] != "Lab Testing Start Date" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 3)] != "Lab Testing End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['quality'] > 0)
	{
	    if ($objExcel->sheets[0]['cells'][$iRow][($iColumn + 1)] != "Quality" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 2)] != "Quality Start Date" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 3)] != "Quality End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['packing'] > 0)
	{
	    if ($objExcel->sheets[0]['cells'][$iRow][($iColumn + 1)] != "Packing" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 2)] != "Packing Pieces" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 3)] != "Packing Start Date" ||
	    	$objExcel->sheets[0]['cells'][$iRow][($iColumn + 4)] != "Packing End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 4;
	}


	if ($objExcel->sheets[0]['cells'][$iRow][($iColumn + 1)] != "Cut Off Date" ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 2)] != "Final Audit Date" ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 3)] != "Production Status" ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 4)] != "ETD CTG/ZIA" ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 5)] != "ETA Denmark" ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 6)] != "Destination" ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 7)] != "Remarks" ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 8)] != "Portal Comments")
	{
		@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

		redirect("vsr-data.php", "INVALID_VSR_FILE");
	}


	$sMerchandiser = addslashes($objExcel->sheets[0]['cells'][6][2]);
	$iUserId       = $_SESSION['UserId'];

	$sSQL = "SELECT id FROM tbl_users WHERE name='$sMerchandiser' LIMIT 1";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
		$iUserId = $objDb->getField(0, 0);


	$sOutput     = "";
	$sClass      = array("evenRow", "oddRow");
	$sDelayStats = array( );
	$iDelayStats = 0;
	$sShortStats = array( );
	$iShortStats = 0;

	for ($i = ($iRow + 1), $iIndex = 1; $i <= $iRows; $i++, $iIndex ++)
	{
		$iColumn     = 1;
		$bDelayStats = false;
		$bShortStats = false;

		$sGtNumber         = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 0)]));
		$sVendor           = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 1)]));
		$sBrand            = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]));
		$sOrderNo          = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]));
		$sStyle            = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 4)]));
		$sSeason           = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 5)]));
		$sQuantity         = intval($objExcel->sheets[0]['cells'][$i][($iColumn + 6)]);
		$sProgramme        = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 7)]));
		$sPoReceivedDate   = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 8)]);
		$sFactoryWorkOrder = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 9)]));
		$sMaterialFabric   = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 10)]));
		$sFinish           = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 11)]));
		$sOriginalEtd      = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 12)]);
		$sRevisedEtd       = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 13)]);
		$sCustomerNo       = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 14)]));
		$sArticleNo        = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 15)]));
		$fPrice            = floatval($objExcel->sheets[0]['cells'][$i][($iColumn + 16)]);
		$sVariable         = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 17)]));
		$sMode             = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 18)]));
		$sTrims            = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 19)]));
		$sYarnFabric       = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 20)]));
		$sQrsSubmitDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 21)]);

		$iColumn += 21;

		$sCutOffDate       = parseDate($objExcel->sheets[0]['cells'][$i][($iColumns - 7)]);
		$sFinalAuditDate   = parseDate($objExcel->sheets[0]['cells'][$i][($iColumns - 6)]);
		$sProductionStatus = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumns - 5)]));
		$sEtdCtgZia        = parseDate($objExcel->sheets[0]['cells'][$i][($iColumns - 4)]);
		$sEtaDenmark       = parseDate($objExcel->sheets[0]['cells'][$i][($iColumns - 3)]);
		$sDestination      = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumns - 2)]));
		$sRemarks          = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumns - 1)]));
		$sComments         = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumns - 0)]));

		$sVariable = (($sVariable == "Y") ? "Y" : "N");


		$sOutput .= "<tr class=\"{$sClass[($i % 2)]}\">
		               <td class=\"center\">{$iIndex}</td>
		               <td>{$sVendor}</td>
		               <td>{$sBrand}</td>
		               <td>{$sOrderNo}</td>
		               <td>{$sStyle}</td>
		               <td>[Result]</td>
		             </tr>
		            ";


		$sSQL = "SELECT id FROM tbl_po WHERE order_no LIKE '$sOrderNo'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
		{
			$sOutput = str_replace("[Result]", '<span class="error">PO not exists in the Portal</span>', $sOutput);

			continue;
		}


		$sSQL = "SELECT id FROM tbl_vendors WHERE vendor LIKE '$sVendor' AND parent_id='0' AND sourcing='Y'";
		$objDb->query($sSQL);

		$iVendorId = (int)$objDb->getField(0, 0);

		if ($iVendorId == 0)
		{
			$sOutput = str_replace("[Result]", '<span class="error">Invalid Vendor Name</span>', $sOutput);

			continue;
		}


		$sSQL = "SELECT id, parent_id FROM tbl_brands WHERE brand LIKE '$sBrand' AND parent_id > '0'";
		$objDb->query($sSQL);

		$iBrandId  = (int)$objDb->getField(0, 0);
		$iParentId = (int)$objDb->getField(0, 1);

		if ($iBrandId == 0)
			$sOutput = str_replace("[Result]", '<span class="warning">- Invalid Brand Name</span><br />[Result]', $sOutput);


		$sSQL = "SELECT id FROM tbl_seasons WHERE brand_id='$iParentId' AND season LIKE '$sSeason' AND parent_id > '0'";
		$objDb->query($sSQL);

		$iSeasonId = (int)$objDb->getField(0, 0);

		if ($iSeasonId == 0)
			$sOutput = str_replace("[Result]", '<span class="warning">- Invalid Season Name</span><br />[Result]', $sOutput);


		$sSQL = "SELECT id FROM tbl_styles WHERE style LIKE '$sStyle' AND sub_brand_id='$iBrandId' AND sub_season_id='$iSeasonId'";
		$objDb->query($sSQL);

		$iStyleId = (int)$objDb->getField(0, 0);

		if ($iStyleId == 0)
			$sOutput = str_replace("[Result]", '<span class="warning">- Invalid Style No</span><br />[Result]', $sOutput);


		$sSQL = "SELECT id FROM tbl_destinations WHERE destination LIKE '$sDestination' AND brand_id=(SELECT parent_id FROM tbl_brands WHERE id='$iBrandId')";
		$objDb->query($sSQL);

		$iDestinationId = (int)$objDb->getField(0, 0);

		if ($iDestinationId == 0)
			$sOutput = str_replace("[Result]", '<span class="warning">- Invalid Destination Name</span><br />[Result]', $sOutput);


		$sSQL = "SELECT id, quantity FROM tbl_po WHERE order_no LIKE '$sOrderNo' AND vendor_id='$iVendorId' AND id IN (SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE style_id='$iStyleId')";
		$objDb->query($sSQL);

		$iPoId     = (int)$objDb->getField(0, 0);
		$iQuantity = $objDb->getField(0, 1);

		if ($iPoId == 0)
		{
			$sOutput = str_replace("[Result]", '<span class="error">Invalid PO Number (not matching with Vendor)</span>', $sOutput);

			continue;
		}


		$sSQL = "SELECT po_id FROM tbl_po_colors WHERE po_id='$iPoId' AND etd_required='$sOriginalEtd'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$sOutput = str_replace("[Result]", '<span class="warning">- Invalid Original ETD</span><br />[Result]', $sOutput);


		$sSQL = "SELECT id FROM tbl_styles WHERE style='$sStyle' AND sub_brand_id='$iBrandId' AND sub_season_id='$iSeasonId' AND id IN (SELECT DISTINCT(style_id) FROM tbl_po_colors WHERE po_id='$iPoId' AND destination_id='$iDestinationId' AND etd_required='$sOriginalEtd')";
		$objDb->query($sSQL);

		$iStyleId = (int)$objDb->getField(0, 0);

		if ($iStyleId == 0)
			$sOutput = str_replace("[Result]", '<span class="warning">- PO Data not matching with Portal PO Record</span><br />[Result]', $sOutput);


		$sSQL = "SELECT po_id FROM tbl_vsr WHERE po_id='$iPoId'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
		{
			$sFields = "INSERT INTO tbl_vsr (po_id, style_id, gt_no, price, variable, programme, po_received_date, factory_work_order, material_fabric, finish, revised_etd, customer_no, article_no, mode, trims, yarn_fabric, qrs_submit_date";
			$sValues = " VALUES ('$iPoId', '$iStyleId', '$sGtNumber', '$fPrice', '$sVariable', '$sProgramme', '$sPoReceivedDate', '$sFactoryWorkOrder', '$sMaterialFabric', '$sFinish', '$sRevisedEtd', '$sCustomerNo', '$sArticleNo', '$sMode', '$sTrims', '$sYarnFabric', '$sQrsSubmitDate'";


			if ($sCategories['knitting'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sFields .= ", knitting, knitting_start_date, knitting_end_date";
				$sValues .= ", '$iPercentage', '$sStartDate', '$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['linking'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sFields .= ", linking, linking_start_date, linking_end_date";
				$sValues .= ", '$iPercentage', '$sStartDate', '$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['yarn'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sFields .= ", yarn, yarn_start_date, yarn_end_date";
				$sValues .= ", '$iPercentage', '$sStartDate', '$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['sizing'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sFields .= ", sizing, sizing_start_date, sizing_end_date";
				$sValues .= ", '$iPercentage', '$sStartDate', '$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['weaving'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sFields .= ", weaving, weaving_start_date, weaving_end_date";
				$sValues .= ", '$iPercentage', '$sStartDate', '$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['leather_import'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sFields .= ", leather_import, leather_import_start_date, leather_import_end_date";
				$sValues .= ", '$iPercentage', '$sStartDate', '$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['dyeing'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sFields .= ", dyeing, dyeing_start_date, dyeing_end_date";
				$sValues .= ", '$iPercentage', '$sStartDate', '$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['leather_inspection'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sFields .= ", leather_inspection, leather_inspection_start_date, leather_inspection_end_date";
				$sValues .= ", '$iPercentage', '$sStartDate', '$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['lamination'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sFields .= ", lamination, lamination_start_date, lamination_end_date";
				$sValues .= ", '$iPercentage', '$sStartDate', '$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['cutting'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$iPieces     = intval($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 4)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sFields .= ", cutting, cutting_pieces, cutting_start_date, cutting_end_date";
				$sValues .= ", '$iPercentage', '$iPieces', '$sStartDate', '$sEndDate'";

				$iColumn += 4;


				if ($bDelayStats == false && $iPercentage < 100 && strtotime(date("Y-m-d")) >= strtotime($sEndDate))
				{
					$sDelayStats['StyleId'][$iDelayStats]  = $iStyleId;
					$sDelayStats['StyleNo'][$iDelayStats]  = $sStyle;
					$sDelayStats['PoNo'][$iDelayStats]     = $sOrderNo;
					$sDelayStats['EndDate'][$iDelayStats]  = $sEndDate;
					$sDelayStats['Reason'][$iDelayStats]   = "Cutting";

					$iDelayStats ++;
					$bDelayStats = true;
				}

				if ($bShortStats == false && $iPieces < $iQuantity)
				{
					$sShortStats['StyleId'][$iShortStats]  = $iStyleId;
					$sShortStats['StyleNo'][$iShortStats]  = $sStyle;
					$sShortStats['PoNo'][$iShortStats]     = $sOrderNo;
					$sShortStats['Pieces'][$iShortStats]   = $iPieces;
					$sShortStats['Quantity'][$iShortStats] = $iQuantity;
					$sShortStats['Reason'][$iShortStats]   = "Cutting";

					$iShortStats ++;
					$bShortStats = true;
				}
			}


			$sSewingLine          = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 1)]));
			$sPerLineProductivity = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]));

			$sFields .= ", sewing_line, per_line_productivity";
			$sValues .= ", '$sSewingLine', '$sPerLineProductivity'";

			$iColumn += 2;


			if ($sCategories['print_embroidery'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$iPieces     = intval($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 4)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sFields .= ", print_embroidery, print_embroidery_pieces, print_embroidery_start_date, print_embroidery_end_date";
				$sValues .= ", '$iPercentage', '$iPieces', '$sStartDate', '$sEndDate'";

				$iColumn += 4;

				if ($bDelayStats == false && $iPercentage < 100 && strtotime(date("Y-m-d")) >= strtotime($sEndDate))
				{
					$sDelayStats['StyleId'][$iDelayStats]  = $iStyleId;
					$sDelayStats['StyleNo'][$iDelayStats]  = $sStyle;
					$sDelayStats['PoNo'][$iDelayStats]     = $sOrderNo;
					$sDelayStats['EndDate'][$iDelayStats]  = $sEndDate;
					$sDelayStats['Reason'][$iDelayStats]   = "Print/Embroidery";

					$iDelayStats ++;
					$bDelayStats = true;
				}

				if ($bShortStats == false && $iPieces < $iQuantity)
				{
					$sShortStats['StyleId'][$iShortStats]  = $iStyleId;
					$sShortStats['StyleNo'][$iShortStats]  = $sStyle;
					$sShortStats['PoNo'][$iShortStats]     = $sOrderNo;
					$sShortStats['Pieces'][$iShortStats]   = $iPieces;
					$sShortStats['Quantity'][$iShortStats] = $iQuantity;
					$sShortStats['Reason'][$iShortStats]   = "Print/Embroidery";

					$iShortStats ++;
					$bShortStats = true;
				}
			}


			if ($sCategories['sorting'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sFields .= ", sorting, sorting_start_date, sorting_end_date";
				$sValues .= ", '$iPercentage', '$sStartDate', '$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['bladder_attachment'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sFields .= ", bladder_attachment, bladder_attachment_start_date, bladder_attachment_end_date";
				$sValues .= ", '$iPercentage', '$sStartDate', '$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['stitching'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$iPieces     = intval($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 4)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sFields .= ", stitching, stitching_pieces, stitching_start_date, stitching_end_date";
				$sValues .= ", '$iPercentage', '$iPieces', '$sStartDate', '$sEndDate'";

				$iColumn += 4;


				if ($bDelayStats == false && $iPercentage < 100 && strtotime(date("Y-m-d")) >= strtotime($sEndDate))
				{
					$sDelayStats['StyleId'][$iDelayStats]  = $iStyleId;
					$sDelayStats['StyleNo'][$iDelayStats]  = $sStyle;
					$sDelayStats['PoNo'][$iDelayStats]     = $sOrderNo;
					$sDelayStats['EndDate'][$iDelayStats]  = $sEndDate;
					$sDelayStats['Reason'][$iDelayStats]   = "Stitching";

					$iDelayStats ++;
					$bDelayStats = true;
				}

				if ($bShortStats == false && $iPieces < $iQuantity)
				{
					$sShortStats['StyleId'][$iShortStats]  = $iStyleId;
					$sShortStats['StyleNo'][$iShortStats]  = $sStyle;
					$sShortStats['PoNo'][$iShortStats]     = $sOrderNo;
					$sShortStats['Pieces'][$iShortStats]   = $iPieces;
					$sShortStats['Quantity'][$iShortStats] = $iQuantity;
					$sShortStats['Reason'][$iShortStats]   = "Stitching";

					$iShortStats ++;
					$bShortStats = true;
				}
			}


			if ($sCategories['washing'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sFields .= ", washing, washing_start_date, washing_end_date";
				$sValues .= ", '$iPercentage', '$sStartDate', '$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['finishing'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sFields .= ", finishing, finishing_start_date, finishing_end_date";
				$sValues .= ", '$iPercentage', '$sStartDate', '$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['lab_testing'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sFields .= ", lab_testing, lab_testing_start_date, lab_testing_end_date";
				$sValues .= ", '$iPercentage', '$sStartDate', '$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['quality'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sFields .= ", quality, quality_start_date, quality_end_date";
				$sValues .= ", '$iPercentage', '$sStartDate', '$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['packing'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$iPieces     = intval($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 4)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sFields .= ", packing, packing_pieces, packing_start_date, packing_end_date";
				$sValues .= ", '$iPercentage', '$iPieces', '$sStartDate', '$sEndDate'";

				$iColumn += 4;


				if ($bDelayStats == false && $iPercentage < 100 && strtotime(date("Y-m-d")) >= strtotime($sEndDate))
				{
					$sDelayStats['StyleId'][$iDelayStats]  = $iStyleId;
					$sDelayStats['StyleNo'][$iDelayStats]  = $sStyle;
					$sDelayStats['PoNo'][$iDelayStats]     = $sOrderNo;
					$sDelayStats['EndDate'][$iDelayStats]  = $sEndDate;
					$sDelayStats['Reason'][$iDelayStats]   = "Packing";

					$iDelayStats ++;
					$bDelayStats = true;
				}

				if ($bShortStats == false && $iPieces < $iQuantity)
				{
					$sShortStats['StyleId'][$iShortStats]  = $iStyleId;
					$sShortStats['StyleNo'][$iShortStats]  = $sStyle;
					$sShortStats['PoNo'][$iShortStats]     = $sOrderNo;
					$sShortStats['Pieces'][$iShortStats]   = $iPieces;
					$sShortStats['Quantity'][$iShortStats] = $iQuantity;
					$sShortStats['Reason'][$iShortStats]   = "Packing";

					$iShortStats ++;
					$bShortStats = true;
				}
			}


			$sFields .= ", cut_off_date, final_audit_date, production_status, etd_ctg_zia, eta_denmark, destination_id, remarks, created, created_by, modified, modified_by)";
			$sValues .= ", '$sCutOffDate', '$sFinalAuditDate', '$sProductionStatus', '$sEtdCtgZia', '$sEtaDenmark', '$iDestinationId', '$sRemarks', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')";


			$sSQL = ($sFields.$sValues);

			if ($objDb->execute($sSQL) == false)
				$sOutput = str_replace("[Result]", ('<span class="error">Record Insertion Failed ['.$objDb->error( ).']</span>'), $sOutput);

			else
			{
				$sOutput = str_replace("[Result]", '<span class="ok">Record Added successfully</span>', $sOutput);

				if ($sRemarks != "")
				{
					$iId = getNextId("tbl_vsr_remarks");

					$sSQL = "INSERT INTO tbl_vsr_remarks (id, po_id, user_id, remarks, date_time) VALUES ('$iId', '$iPoId', '$iUserId', '$sRemarks', NOW( ))";
					$objDb->execute($sSQL);
				}
			}
		}

		else
		{
			$sSQL = "SELECT final_audit_date FROM tbl_vsr WHERE po_id='$iPoId'";
			$objDb->query($sSQL);

			$sOriginalFad = $objDb->getField(0, 0);


			$sSQL = "UPDATE tbl_vsr SET modified=NOW( ), modified_by='{$_SESSION['UserId']}' ";

			$sSQL .= ", gt_no='$sGtNumber'";
			$sSQL .= ", style_id='$iStyleId'";
//			$sSQL .= ", price='$fPrice'";
			$sSQL .= ", variable='$sVariable'";
			$sSQL .= ", programme='$sProgramme'";
			$sSQL .= ", po_received_date='$sPoReceivedDate'";
			$sSQL .= ", factory_work_order='$sFactoryWorkOrder'";
			$sSQL .= ", material_fabric='$sMaterialFabric'";
			$sSQL .= ", finish='$sFinish'";
			$sSQL .= ", revised_etd='$sRevisedEtd'";
			$sSQL .= ", customer_no='$sCustomerNo'";
			$sSQL .= ", article_no='$sArticleNo'";
			$sSQL .= ", mode='$sMode'";
			$sSQL .= ", trims='$sTrims'";
			$sSQL .= ", yarn_fabric='$sYarnFabric'";
			$sSQL .= ", qrs_submit_date='$sQrsSubmitDate'";
			$sSQL .= ", cut_off_date='$sCutOffDate'";
			$sSQL .= ", production_status='$sProductionStatus'";
			$sSQL .= ", etd_ctg_zia='$sEtdCtgZia'";
			$sSQL .= ", eta_denmark='$sEtaDenmark'";
			$sSQL .= ", destination_id='$iDestinationId'";
			$sSQL .= ", remarks='$sRemarks'";

			if ($sFinalAuditDate != "" && $sFinalAuditDate != "0000-00-00")
				$sSQL .= ", final_audit_date='$sFinalAuditDate'";

			if ($sCategories['knitting'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sSQL .= ", knitting='$iPercentage', knitting_start_date='$sStartDate', knitting_end_date='$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['linking'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sSQL .= ", linking='$iPercentage', linking_start_date='$sStartDate', linking_end_date='$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['yarn'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sSQL .= ", yarn='$iPercentage', yarn_start_date='$sStartDate', yarn_end_date='$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['sizing'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sSQL .= ", sizing='$iPercentage', sizing_start_date='$sStartDate', sizing_end_date='$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['weaving'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sSQL .= ", weaving='$iPercentage', weaving_start_date='$sStartDate', weaving_end_date='$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['leather_import'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sSQL .= ", leather_import='$iPercentage', leather_import_start_date='$sStartDate', leather_import_end_date='$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['dyeing'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sSQL .= ", dyeing='$iPercentage', dyeing_start_date='$sStartDate', dyeing_end_date='$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['leather_inspection'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sSQL .= ", leather_inspection='$iPercentage', leather_inspection_start_date='$sStartDate', leather_inspection_end_date='$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['lamination'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sSQL .= ", lamination='$iPercentage', lamination_start_date='$sStartDate', lamination_end_date='$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['cutting'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$iPieces     = intval($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 4)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sSQL .= ", cutting='$iPercentage', cutting_pieces='$iPieces', cutting_start_date='$sStartDate', cutting_end_date='$sEndDate'";

				$iColumn += 4;


				if ($bDelayStats == false && $iPercentage < 100 && strtotime(date("Y-m-d")) >= strtotime($sEndDate))
				{
					$sDelayStats['StyleId'][$iDelayStats]  = $iStyleId;
					$sDelayStats['StyleNo'][$iDelayStats]  = $sStyle;
					$sDelayStats['PoNo'][$iDelayStats]     = $sOrderNo;
					$sDelayStats['EndDate'][$iDelayStats]  = $sEndDate;
					$sDelayStats['Reason'][$iDelayStats]   = "Cutting";

					$iDelayStats ++;
					$bDelayStats = true;
				}

				if ($bShortStats == false && $iPieces < $iQuantity)
				{
					$sShortStats['StyleId'][$iShortStats]  = $iStyleId;
					$sShortStats['StyleNo'][$iShortStats]  = $sStyle;
					$sShortStats['PoNo'][$iShortStats]     = $sOrderNo;
					$sShortStats['Pieces'][$iShortStats]   = $iPieces;
					$sShortStats['Quantity'][$iShortStats] = $iQuantity;
					$sShortStats['Reason'][$iShortStats]   = "Cutting";

					$iShortStats ++;
					$bShortStats = true;
				}
			}


			$sSewingLine          = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 1)]));
			$sPerLineProductivity = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]));

			$sSQL .= ", sewing_line='$sSewingLine', per_line_productivity='$sPerLineProductivity'";

			$iColumn += 2;


			if ($sCategories['print_embroidery'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$iPieces     = intval($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 4)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sSQL .= ", print_embroidery='$iPercentage', print_embroidery_pieces='$iPieces', print_embroidery_start_date='$sStartDate', print_embroidery_end_date='$sEndDate'";

				$iColumn += 4;


				if ($bDelayStats == false && $iPercentage < 100 && strtotime(date("Y-m-d")) >= strtotime($sEndDate))
				{
					$sDelayStats['StyleId'][$iDelayStats]  = $iStyleId;
					$sDelayStats['StyleNo'][$iDelayStats]  = $sStyle;
					$sDelayStats['PoNo'][$iDelayStats]     = $sOrderNo;
					$sDelayStats['EndDate'][$iDelayStats]  = $sEndDate;
					$sDelayStats['Reason'][$iDelayStats]   = "Print/Embroidery";

					$iDelayStats ++;
					$bDelayStats = true;
				}

				if ($bShortStats == false && $iPieces < $iQuantity)
				{
					$sShortStats['StyleId'][$iShortStats]  = $iStyleId;
					$sShortStats['StyleNo'][$iShortStats]  = $sStyle;
					$sShortStats['PoNo'][$iShortStats]     = $sOrderNo;
					$sShortStats['Pieces'][$iShortStats]   = $iPieces;
					$sShortStats['Quantity'][$iShortStats] = $iQuantity;
					$sShortStats['Reason'][$iShortStats]   = "Print/Embroidery";

					$iShortStats ++;
					$bShortStats = true;
				}
			}


			if ($sCategories['sorting'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sSQL .= ", sorting='$iPercentage', sorting_start_date='$sStartDate', sorting_end_date='$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['bladder_attachment'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sSQL .= ", bladder_attachment='$iPercentage', bladder_attachment_start_date='$sStartDate', bladder_attachment_end_date='$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['stitching'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$iPieces     = intval($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 4)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sSQL .= ", stitching='$iPercentage', stitching_pieces='$iPieces', stitching_start_date='$sStartDate', stitching_end_date='$sEndDate'";

				$iColumn += 4;


				if ($bDelayStats == false && $iPercentage < 100 && strtotime(date("Y-m-d")) >= strtotime($sEndDate))
				{
					$sDelayStats['StyleId'][$iDelayStats]  = $iStyleId;
					$sDelayStats['StyleNo'][$iDelayStats]  = $sStyle;
					$sDelayStats['PoNo'][$iDelayStats]     = $sOrderNo;
					$sDelayStats['EndDate'][$iDelayStats]  = $sEndDate;
					$sDelayStats['Reason'][$iDelayStats]   = "Stitching";

					$iDelayStats ++;
					$bDelayStats = true;
				}

				if ($bShortStats == false && $iPieces < $iQuantity)
				{
					$sShortStats['StyleId'][$iShortStats]  = $iStyleId;
					$sShortStats['StyleNo'][$iShortStats]  = $sStyle;
					$sShortStats['PoNo'][$iShortStats]     = $sOrderNo;
					$sShortStats['Pieces'][$iShortStats]   = $iPieces;
					$sShortStats['Quantity'][$iShortStats] = $iQuantity;
					$sShortStats['Reason'][$iShortStats]   = "Stitching";

					$iShortStats ++;
					$bShortStats = true;
				}
			}


			if ($sCategories['washing'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sSQL .= ", washing='$iPercentage', washing_start_date='$sStartDate', washing_end_date='$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['finishing'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sSQL .= ", finishing='$iPercentage', finishing_start_date='$sStartDate', finishing_end_date='$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['lab_testing'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sSQL .= ", lab_testing='$iPercentage', lab_testing_start_date='$sStartDate', lab_testing_end_date='$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['quality'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sSQL .= ", quality='$iPercentage', quality_start_date='$sStartDate', quality_end_date='$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['packing'] > 0)
			{
				$iPercentage = $objExcel->sheets[0]['cells'][$i][($iColumn + 1)];
				$iPieces     = intval($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]);
				$sStartDate  = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]);
				$sEndDate    = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 4)]);

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sSQL .= ", packing='$iPercentage', packing_pieces='$iPieces', packing_start_date='$sStartDate', packing_end_date='$sEndDate'";

				$iColumn += 4;


				if ($bDelayStats == false && $iPercentage < 100 && strtotime(date("Y-m-d")) >= strtotime($sEndDate))
				{
					$sDelayStats['StyleId'][$iDelayStats]  = $iStyleId;
					$sDelayStats['StyleNo'][$iDelayStats]  = $sStyle;
					$sDelayStats['PoNo'][$iDelayStats]     = $sOrderNo;
					$sDelayStats['EndDate'][$iDelayStats]  = $sEndDate;
					$sDelayStats['Reason'][$iDelayStats]   = "Packing";

					$iDelayStats ++;
					$bDelayStats = true;
				}

				if ($bShortStats == false && $iPieces < $iQuantity)
				{
					$sShortStats['StyleId'][$iShortStats]  = $iStyleId;
					$sShortStats['StyleNo'][$iShortStats]  = $sStyle;
					$sShortStats['PoNo'][$iShortStats]     = $sOrderNo;
					$sShortStats['Pieces'][$iShortStats]   = $iPieces;
					$sShortStats['Quantity'][$iShortStats] = $iQuantity;
					$sShortStats['Reason'][$iShortStats]   = "Packing";

					$iShortStats ++;
					$bShortStats = true;
				}
			}


			$sSQL .= " WHERE po_id='$iPoId'";

			if ($objDb->execute($sSQL) == false)
				$sOutput = str_replace("[Result]", ('<span class="error">Record Updation Failed ['.$objDb->error( ).']</span>'), $sOutput);

			else
			{
				$sOutput = str_replace("[Result]", '<span class="ok">Record Updated successfully</span>', $sOutput);

				if ($sFinalAuditDate != "" && $sFinalAuditDate != "0000-00-00" && $sOriginalFad != $sFinalAuditDate)
				{
					$iId = getNextId("tbl_fad_revisions");

					$sSQL  = "INSERT INTO tbl_fad_revisions (id, po_id, original, revised, user_id, date_time) VALUES ('$iId', '$Id', '$sOriginalFad', '$sFinalAuditDate', '{$_SESSION['UserId']}', NOW( ))";
					$bFlag = $objDb->execute($sSQL);
				}

				if ($sRemarks != "")
				{
					$iId = getNextId("tbl_vsr_remarks");

					$sSQL = "INSERT INTO tbl_vsr_remarks (id, po_id, user_id, remarks, date_time) VALUES ('$iId', '$iPoId', '$iUserId', '$sRemarks', NOW( ))";
					$objDb->execute($sSQL);
				}
			}


			$sOutput = str_replace("<br />[Result]", "", $sOutput);
		}
	}


	if ($Notify == "Y")
	{
		$sStyles   = @array_unique($sDelayStats['StyleId']);
		$sMsgDelay = "";

		foreach ($sStyles as $iIndex => $iStyleId)
		{
			$sMsgDelay .= "<b>Style: {$sDelayStats['StyleNo'][$iIndex]}</b><br />";

			for ($i = 0; $i < $iDelayStats; $i ++)
			{
				if ($sDelayStats['StyleId'][$i] != $iStyleId)
					continue;

				$sEndDate = formatDate($sDelayStats['EndDate'][$i]);
				$sEndDate = (($sEndDate == "") ? "--<i>Date Not Available</i>--" : $sEndDate);

				$sMsgDelay .= "{$sDelayStats['PoNo'][$i]} {$sDelayStats['Reason'][$i]} not expected to close on {$sEndDate}<br />";
			}

			$sMsgDelay .= "<br /><br />";
		}

		if ($sMsgDelay != "")
			$sMsgDelay = ("<h2>Expected Delayed POs</h2>Below mentioned POs expected to have production delays:<br /><br />".$sMsgDelay);



		$sStyles   = @array_unique($sShortStats['StyleId']);
		$sMsgShort = "";

		foreach ($sStyles as $iIndex => $iStyleId)
		{
			$sMsgShort .= "<b>Style: {$sShortStats['StyleNo'][$iIndex]}</b><br />";

			for ($i = 0; $i < $iShortStats; $i ++)
			{
				if ($sShortStats['StyleId'][$i] != $iStyleId)
					continue;

				$iShortFall = ($sShortStats['Quantity'][$i] - $sShortStats['Pieces'][$i]);

				$sMsgShort .= "{$sShortStats['PoNo'][$i]} expected to have a shortfall of {$iShortFall} pieces at {$sShortStats['Reason'][$i]} (Required:{$sShortStats['Quantity'][$i]} , Available:{$sShortStats['Pieces'][$i]})<br />";
			}

			$sMsgShort .= "<br /><br />";
		}

		if ($sMsgShort != "")
			$sMsgShort = ("<h2>Expected Shortfalls</h2>Below mentioned POs expected to have shortfalls:<br /><br />".$sMsgShort);


		if ($sMsgDelay != "" || $sMsgShort != "")
		{
			$sSQL = "SELECT user_id, alert_types FROM tbl_notifications WHERE trigger_id='4' AND (brand_id='67' OR brand_id='75') AND status='A' AND user_id IN (SELECT id FROM tbl_users WHERE status='A')";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iUserId     = $objDb->getField($i, 0);
				$iAlertTypes = @explode(",", $objDb->getField($i, 1));


				$sSQL = "SELECT name, email, mobile FROM tbl_users WHERE id='$iUserId'";
				$objDb2->query($sSQL);

				$sName   = $objDb2->getField(0, 'name');
				$sEmail  = $objDb2->getField(0, 'email');
				$sMobile = $objDb2->getField(0, 'mobile');


				$sSubject = "*** Production Delays & Shortfalls ***";
				$sBody    = ($sMsgDelay."<br /><br />".$sMsgShort);


				// SMS
				//if (@in_array(1, $iAlertTypes))
				//	$objSms->send($sMobile, $sName, $sBody, $sSubject);

				// Email
				if (@in_array(2, $iAlertTypes))
				{
					$objEmail = new PHPMailer( );
					$objEmail->IsSMTP( );
					$objEmail->SMTPAuth = true;
					$objEmail->IsHTML(true);
					$objEmail->Subject = $sSubject;
					$objEmail->Body = $sBody;
					$objEmail->AddAddress($sEmail, $sName);
					$objEmail->Send( );
				}

				// Portal
				if (@in_array(3, $iAlertTypes))
				{
					$iId = getNextId("tbl_user_notifications");

					$sSQL = "INSERT INTO tbl_user_notifications (id, user_id, subject, body, date_time, status) VALUES ('$iId', '$iUserId', '$sSubject', '$sBody', NOW( ), 'N')";
					$objDb2->execute($sSQL);
				}
			}
		}
	}
?>