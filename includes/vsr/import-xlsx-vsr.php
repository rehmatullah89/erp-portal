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

	@set_include_path(get_include_path() . PATH_SEPARATOR . '../requires/');
	@include 'PHPExcel/IOFactory.php';

	$objExcelReader = PHPExcel_IOFactory::createReader('Excel2007');
	$objExcelReader->setReadDataOnly(true);

	$objExcel = $objExcelReader->load($sBaseDir.TEMP_DIR.$sVsrFile);
	$objSheet = $objExcel->getSheet(0);

	$iRows    = $objSheet->getHighestRow();
	$sColumns = $objSheet->getHighestColumn();
	$iColumns = PHPExcel_Cell::columnIndexFromString($sColumns);

	if ($iColumns < 27 || $iRows < 11)
	{
		@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

		redirect("vsr-data.php", "INVALID_VSR_FILE");
	}


	$iRow    = 10;
	$iColumn = 0;

	if ($objSheet->getCellByColumnAndRow($iColumn, $iRow)->getValue( ) != "Vendor" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 1), $iRow)->getValue( ) != "Brand" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 2), $iRow)->getValue( ) != "Order No" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 3), $iRow)->getValue( ) != "Style" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 4), $iRow)->getValue( ) != "Season" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 5), $iRow)->getValue( ) != "Quantity" ||
		$objSheet->getCellByColumnAndRow(($iColumn + 6), $iRow)->getValue( ) != "Programme" ||
		$objSheet->getCellByColumnAndRow(($iColumn + 7), $iRow)->getValue( ) != "PO Received Date" ||
		$objSheet->getCellByColumnAndRow(($iColumn + 8), $iRow)->getValue( ) != "Factory Work Order" ||
		$objSheet->getCellByColumnAndRow(($iColumn + 9), $iRow)->getValue( ) !=  "Material/Fabric" ||
		$objSheet->getCellByColumnAndRow(($iColumn + 10), $iRow)->getValue( ) != "Finish" ||
		$objSheet->getCellByColumnAndRow(($iColumn + 11), $iRow)->getValue( ) != "Original ETD" ||
		$objSheet->getCellByColumnAndRow(($iColumn + 12), $iRow)->getValue( ) != "Revised ETD" ||
		$objSheet->getCellByColumnAndRow(($iColumn + 13), $iRow)->getValue( ) != "Price" ||
		$objSheet->getCellByColumnAndRow(($iColumn + 14), $iRow)->getValue( ) != "Variable" ||
		$objSheet->getCellByColumnAndRow(($iColumn + 15), $iRow)->getValue( ) != "Mode" ||
		$objSheet->getCellByColumnAndRow(($iColumn + 16), $iRow)->getValue( ) != "Trims" ||
		$objSheet->getCellByColumnAndRow(($iColumn + 17), $iRow)->getValue( ) != "Yarn/Fabric" ||
		$objSheet->getCellByColumnAndRow(($iColumn + 18), $iRow)->getValue( ) != "QRS Submit Date")
	{
		@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

		redirect("vsr-data.php", "INVALID_VSR_FILE");
	}

	$iColumn += 18;


	if ($sCategories['knitting'] > 0)
	{
	    if ($objSheet->getCellByColumnAndRow(($iColumn + 1), $iRow)->getValue( ) != "Knitting" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 2), $iRow)->getValue( ) != "Knitting Start Date" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 3), $iRow)->getValue( ) != "Knitting End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['linking'] > 0)
	{
	    if ($objSheet->getCellByColumnAndRow(($iColumn + 1), $iRow)->getValue( ) != "Linking" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 2), $iRow)->getValue( ) != "Linking Start Date" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 3), $iRow)->getValue( ) != "Linking End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['yarn'] > 0)
	{
	    if ($objSheet->getCellByColumnAndRow(($iColumn + 1), $iRow)->getValue( ) != "Yarn" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 2), $iRow)->getValue( ) != "Yarn Start Date" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 3), $iRow)->getValue( ) != "Yarn End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['sizing'] > 0)
	{
	    if ($objSheet->getCellByColumnAndRow(($iColumn + 1), $iRow)->getValue( ) != "Sizing" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 2), $iRow)->getValue( ) != "Sizing Start Date" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 3), $iRow)->getValue( ) != "Sizing End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['weaving'] > 0)
	{
	    if ($objSheet->getCellByColumnAndRow(($iColumn + 1), $iRow)->getValue( ) != "Weaving" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 2), $iRow)->getValue( ) != "Weaving Start Date" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 3), $iRow)->getValue( ) != "Weaving End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['leather_import'] > 0)
	{
	    if ($objSheet->getCellByColumnAndRow(($iColumn + 1), $iRow)->getValue( ) != "Leather Import" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 2), $iRow)->getValue( ) != "Leather Import Start Date" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 3), $iRow)->getValue( ) != "Leather Import End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['dyeing'] > 0)
	{
	    if ($objSheet->getCellByColumnAndRow(($iColumn + 1), $iRow)->getValue( ) != "Dyeing" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 2), $iRow)->getValue( ) != "Dyeing Start Date" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 3), $iRow)->getValue( ) != "Dyeing End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['leather_inspection'] > 0)
	{
	    if ($objSheet->getCellByColumnAndRow(($iColumn + 1), $iRow)->getValue( ) != "Leather Inspection" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 2), $iRow)->getValue( ) != "Leather Inspection Start Date" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 3), $iRow)->getValue( ) != "Leather Inspection End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['lamination'] > 0)
	{
	    if ($objSheet->getCellByColumnAndRow(($iColumn + 1), $iRow)->getValue( ) != "Lamination" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 2), $iRow)->getValue( ) != "Lamination Start Date" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 3), $iRow)->getValue( ) != "Lamination End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['cutting'] > 0)
	{
	    if ($objSheet->getCellByColumnAndRow(($iColumn + 1), $iRow)->getValue( ) != "Cutting" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 2), $iRow)->getValue( ) != "Cutting Start Date" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 3), $iRow)->getValue( ) != "Cutting End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['print_embroidery'] > 0)
	{
	    if ($objSheet->getCellByColumnAndRow(($iColumn + 1), $iRow)->getValue( ) != "Print/Embroidery" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 2), $iRow)->getValue( ) != "Print/Embroidery Start Date" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 3), $iRow)->getValue( ) != "Print/Embroidery End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['sorting'] > 0)
	{
	    if ($objSheet->getCellByColumnAndRow(($iColumn + 1), $iRow)->getValue( ) != "Sorting" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 2), $iRow)->getValue( ) != "Sorting Start Date" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 3), $iRow)->getValue( ) != "Sorting End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['bladder_attachment'] > 0)
	{
	    if ($objSheet->getCellByColumnAndRow(($iColumn + 1), $iRow)->getValue( ) != "Bladder Attachment" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 2), $iRow)->getValue( ) != "Bladder Attachment Start Date" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 3), $iRow)->getValue( ) != "Bladder Attachment End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['stitching'] > 0)
	{
	    if ($objSheet->getCellByColumnAndRow(($iColumn + 1), $iRow)->getValue( ) != "Stitching" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 2), $iRow)->getValue( ) != "Stitching Start Date" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 3), $iRow)->getValue( ) != "Stitching End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['washing'] > 0)
	{
	    if ($objSheet->getCellByColumnAndRow(($iColumn + 1), $iRow)->getValue( ) != "Washing" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 2), $iRow)->getValue( ) != "Washing Start Date" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 3), $iRow)->getValue( ) != "Washing End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['finishing'] > 0)
	{
	    if ($objSheet->getCellByColumnAndRow(($iColumn + 1), $iRow)->getValue( ) != "Finishing" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 2), $iRow)->getValue( ) != "Finishing Start Date" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 3), $iRow)->getValue( ) != "Finishing End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['lab_testing'] > 0)
	{
	    if ($objSheet->getCellByColumnAndRow(($iColumn + 1), $iRow)->getValue( ) != "Lab Testing" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 2), $iRow)->getValue( ) != "Lab Testing Start Date" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 3), $iRow)->getValue( ) != "Lab Testing End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['quality'] > 0)
	{
	    if ($objSheet->getCellByColumnAndRow(($iColumn + 1), $iRow)->getValue( ) != "Quality" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 2), $iRow)->getValue( ) != "Quality Start Date" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 3), $iRow)->getValue( ) != "Quality End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($sCategories['packing'] > 0)
	{
	    if ($objSheet->getCellByColumnAndRow(($iColumn + 1), $iRow)->getValue( ) != "Packing" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 2), $iRow)->getValue( ) != "Packing Start Date" ||
	    	$objSheet->getCellByColumnAndRow(($iColumn + 3), $iRow)->getValue( ) != "Packing End Date")
		{
			@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

			redirect("vsr-data.php", "INVALID_VSR_FILE");
		}

		$iColumn += 3;
	}


	if ($objSheet->getCellByColumnAndRow(($iColumn + 1), $iRow)->getValue( ) != "Cut Off Date" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 2), $iRow)->getValue( ) != "Final Audit Date" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 3), $iRow)->getValue( ) != "Production Status" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 4), $iRow)->getValue( ) != "ETD CTG/ZIA" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 5), $iRow)->getValue( ) != "ETA Denmark" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 6), $iRow)->getValue( ) != "Destination" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 7), $iRow)->getValue( ) != "Remarks" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 8), $iRow)->getValue( ) != "Portal Comments")
	{
		@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

		redirect("vsr-data.php", "INVALID_VSR_FILE");
	}


	$sMerchandiser = addslashes($objSheet->getCellByColumnAndRow(1, 6)->getValue( ));
	$iUserId       = $_SESSION['UserId'];

	$sSQL = "SELECT id FROM tbl_users WHERE name='$sMerchandiser' LIMIT 1";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
		$iUserId = $objDb->getField(0, 0);

	$sOutput = "";
	$sClass  = array("evenRow", "oddRow");

	for ($i = ($iRow + 1), $iIndex = 1; $i <= $iRows; $i++, $iIndex ++)
	{
		$iColumn = 0;

		$sVendor           = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumn + 0), $i)->getValue( )));
		$sBrand            = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( )));
		$sOrderNo          = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( )));
		$sStyle            = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( )));
		$sSeason           = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumn + 4), $i)->getValue( )));
		$sQuantity         = intval($objSheet->getCellByColumnAndRow(($iColumn + 5), $i)->getValue( ));
		$sProgramme        = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumn + 6), $i)->getValue( )));
		$sPoReceivedDate   = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 7), $i)->getValue( ));
		$sFactoryWorkOrder = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumn + 8), $i)->getValue( )));
		$sMaterialFabric   = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumn + 9), $i)->getValue( )));
		$sFinish           = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumn + 10), $i)->getValue( )));
		$sOriginalEtd      = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 11), $i)->getValue( ));
		$sRevisedEtd       = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 12), $i)->getValue( ));
		$fPrice            = floatval($objSheet->getCellByColumnAndRow(($iColumn + 13), $i)->getValue( ));
		$sVariable         = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumn + 14), $i)->getValue( )));
		$sMode             = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumn + 15), $i)->getValue( )));
		$sTrims            = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumn + 16), $i)->getValue( )));
		$sYarnFabric       = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumn + 17), $i)->getValue( )));
		$sQrsSubmitDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 18), $i)->getValue( ));

		$iColumn += 18;

		$sCutOffDate       = parseDate($objSheet->getCellByColumnAndRow(($iColumns - 8), $i)->getValue( ));
		$sFinalAuditDate   = parseDate($objSheet->getCellByColumnAndRow(($iColumns - 7), $i)->getValue( ));
		$sProductionStatus = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumns - 6), $i)->getValue( )));
		$sEtdCtgZia        = parseDate($objSheet->getCellByColumnAndRow(($iColumns - 5), $i)->getValue( ));
		$sEtaDenmark       = parseDate($objSheet->getCellByColumnAndRow(($iColumns - 4), $i)->getValue( ));
		$sDestination      = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumns - 3), $i)->getValue( )));
		$sRemarks          = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumns - 2), $i)->getValue( )));
		$sComments         = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumns - 1), $i)->getValue( )));

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


		$sSQL = "SELECT id FROM tbl_seasons WHERE brand_id='$iParentId' AND season LIKE '$sSeason' AND parent_id > '0' LIMIT 1";
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


		$sSQL = "SELECT id FROM tbl_po WHERE order_no LIKE '$sOrderNo' AND vendor_id='$iVendorId' AND id IN (SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE style_id='$iStyleId')";
		$objDb->query($sSQL);

		$iPoId = (int)$objDb->getField(0, 0);

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
			$sFields = "INSERT INTO tbl_vsr (po_id, style_id, price, variable, programme, po_received_date, factory_work_order, material_fabric, finish, revised_etd, mode, trims, yarn_fabric, qrs_submit_date";
			$sValues = " VALUES ('$iPoId', '$iStyleId', '$fPrice', '$sVariable', '$sProgramme', '$sPoReceivedDate', '$sFactoryWorkOrder', '$sMaterialFabric', '$sFinish', '$sRevisedEtd', '$sMode', '$sTrims', '$sYarnFabric', '$sQrsSubmitDate'";


			if ($sCategories['knitting'] > 0)
			{
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

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
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

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
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

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
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

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
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

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
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

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
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

				if ($iPercentage == "NA" && $objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ) == "NA" && $objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ) == "NA")
				{
					$sFields .= ", dyeing, dyeing_start_date, dyeing_end_date";
					$sValues .= ", '0', '0000-00-00', '0000-00-00'";
				}

				else
				{
					if (@strpos($iPercentage, "%") !== FALSE)
						$iPercentage = intval($iPercentage);

					else
						$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

					$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

					$sFields .= ", dyeing, dyeing_start_date, dyeing_end_date";
					$sValues .= ", '$iPercentage', '$sStartDate', '$sEndDate'";
				}

				$iColumn += 3;
			}


			if ($sCategories['leather_inspection'] > 0)
			{
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

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
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

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
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sFields .= ", cutting, cutting_start_date, cutting_end_date";
				$sValues .= ", '$iPercentage', '$sStartDate', '$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['print_embroidery'] > 0)
			{
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sFields .= ", print_embroidery, print_embroidery_start_date, print_embroidery_end_date";
				$sValues .= ", '$iPercentage', '$sStartDate', '$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['sorting'] > 0)
			{
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

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
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

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
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sFields .= ", stitching, stitching_start_date, stitching_end_date";
				$sValues .= ", '$iPercentage', '$sStartDate', '$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['washing'] > 0)
			{
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

				if ($iPercentage == "NA" && $objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ) == "NA" && $objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ) == "NA")
				{
					$sFields .= ", washing, washing_start_date, washing_end_date";
					$sValues .= ", '0', '0000-00-00', '0000-00-00'";
				}

				else
				{
					if (@strpos($iPercentage, "%") !== FALSE)
						$iPercentage = intval($iPercentage);

					else
						$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

					$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

					$sFields .= ", washing, washing_start_date, washing_end_date";
					$sValues .= ", '$iPercentage', '$sStartDate', '$sEndDate'";
				}

				$iColumn += 3;
			}


			if ($sCategories['finishing'] > 0)
			{
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

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
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

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
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

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
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sFields .= ", packing, packing_start_date, packing_end_date";
				$sValues .= ", '$iPercentage', '$sStartDate', '$sEndDate'";

				$iColumn += 3;
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

			$sSQL .= ", style_id='$iStyleId'";
//			$sSQL .= ", price='$fPrice'";
			$sSQL .= ", variable='$sVariable'";
			$sSQL .= ", programme='$sProgramme'";
			$sSQL .= ", po_received_date='$sPoReceivedDate'";
			$sSQL .= ", factory_work_order='$sFactoryWorkOrder'";
			$sSQL .= ", material_fabric='$sMaterialFabric'";
			$sSQL .= ", finish='$sFinish'";
			$sSQL .= ", revised_etd='$sRevisedEtd'";
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
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

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
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

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
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

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
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

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
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

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
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

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
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

				if ($iPercentage == "NA" && $objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ) == "NA" && $objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ) == "NA")
					$sSQL .= ", dyeing='0', dyeing_start_date='0000-00-00', dyeing_end_date='0000-00-00'";

				else
				{
					if (@strpos($iPercentage, "%") !== FALSE)
						$iPercentage = intval($iPercentage);

					else
						$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

					$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

					$sSQL .= ", dyeing='$iPercentage', dyeing_start_date='$sStartDate', dyeing_end_date='$sEndDate'";
				}

				$iColumn += 3;
			}


			if ($sCategories['leather_inspection'] > 0)
			{
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

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
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

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
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sSQL .= ", cutting='$iPercentage', cutting_start_date='$sStartDate', cutting_end_date='$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['print_embroidery'] > 0)
			{
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sSQL .= ", print_embroidery='$iPercentage', print_embroidery_start_date='$sStartDate', print_embroidery_end_date='$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['sorting'] > 0)
			{
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

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
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

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
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sSQL .= ", stitching='$iPercentage', stitching_start_date='$sStartDate', stitching_end_date='$sEndDate'";

				$iColumn += 3;
			}


			if ($sCategories['washing'] > 0)
			{
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

				if ($iPercentage == "NA" && $objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ) == "NA" && $objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ) == "NA")
					$sSQL .= ", washing='0', washing_start_date='0000-00-00', washing_end_date='0000-00-00'";

				else
				{
					if (@strpos($iPercentage, "%") !== FALSE)
						$iPercentage = intval($iPercentage);

					else
						$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

					$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

					$sSQL .= ", washing='$iPercentage', washing_start_date='$sStartDate', washing_end_date='$sEndDate'";
				}

				$iColumn += 3;
			}


			if ($sCategories['finishing'] > 0)
			{
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

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
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

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
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

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
				$iPercentage = $objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( );
				$sStartDate  = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( ));
				$sEndDate    = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( ));

				if (@strpos($iPercentage, "%") !== FALSE)
					$iPercentage = intval($iPercentage);

				else
					$iPercentage = (($iPercentage > 0 && $iPercentage <= 1) ? ($iPercentage * 100) : $iPercentage);

				$iPercentage = (($iPercentage > 100) ? 100 : (($iPercentage < 0) ? 0 : $iPercentage));

				$sSQL .= ", packing='$iPercentage', packing_start_date='$sStartDate', packing_end_date='$sEndDate'";

				$iColumn += 3;
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
?>