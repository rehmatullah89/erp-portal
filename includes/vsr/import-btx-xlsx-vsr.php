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

	if ($iColumns != 27 || $iRows < 11)
	{
		@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

		redirect("vsr-data.php", "INVALID_VSR_FILE");
	}

	$iRow    = 10;
	$iColumn = 0;

	if ($objSheet->getCellByColumnAndRow(($iColumn + 0), $iRow)->getValue( ) != "Factory" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 1), $iRow)->getValue( ) != "S/cont. Fac." ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 2), $iRow)->getValue( ) != "Label" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 3), $iRow)->getValue( ) != "Order" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 4), $iRow)->getValue( ) != "Style" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 5), $iRow)->getValue( ) != "Style Name" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 6), $iRow)->getValue( ) != "Season" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 7), $iRow)->getValue( ) != "Total Pcs" ||
		$objSheet->getCellByColumnAndRow(($iColumn + 8), $iRow)->getValue( ) != "Item" ||
		$objSheet->getCellByColumnAndRow(($iColumn + 9), $iRow)->getValue( ) != "ETD" ||
		$objSheet->getCellByColumnAndRow(($iColumn + 10), $iRow)->getValue( ) != "Revised ETD" ||
		$objSheet->getCellByColumnAndRow(($iColumn + 11), $iRow)->getValue( ) != "Price" ||
		$objSheet->getCellByColumnAndRow(($iColumn + 12), $iRow)->getValue( ) != "Mode" ||
		$objSheet->getCellByColumnAndRow(($iColumn + 13), $iRow)->getValue( ) != "Trims" ||
		$objSheet->getCellByColumnAndRow(($iColumn + 14), $iRow)->getValue( ) != "Yarn/Fabric" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 15), $iRow)->getValue( ) != "Knitting" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 16), $iRow)->getValue( ) != "Dyeing" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 17), $iRow)->getValue( ) != "Cutting" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 18), $iRow)->getValue( ) != "Print/Embroidery" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 19), $iRow)->getValue( ) != "Sewing/Linking" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 20), $iRow)->getValue( ) != "Washing" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 21), $iRow)->getValue( ) != "Packing" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 22), $iRow)->getValue( ) != "Final Audit" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 23), $iRow)->getValue( ) != "Status" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 24), $iRow)->getValue( ) != "Shipped Qty" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 25), $iRow)->getValue( ) != "Remarks" ||
	    $objSheet->getCellByColumnAndRow(($iColumn + 26), $iRow)->getValue( ) != "Portal Comments")
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
		$sSubContractor    = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumn + 1), $i)->getValue( )));
		$sBrand            = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumn + 2), $i)->getValue( )));
		$sOrderNo          = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumn + 3), $i)->getValue( )));
		$sStyle            = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumn + 4), $i)->getValue( )));
		$sStyleName        = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumn + 5), $i)->getValue( )));
		$sSeason           = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumn + 6), $i)->getValue( )));
		$sQuantity         = intval($objSheet->getCellByColumnAndRow(($iColumn + 7), $i)->getValue( ));
		$sItem             = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumn + 8), $i)->getValue( )));
		$sOriginalEtd      = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 9), $i)->getValue( ));
		$sRevisedEtd       = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 10), $i)->getValue( ));
		$fPrice            = floatval($objSheet->getCellByColumnAndRow(($iColumn + 11), $i)->getValue( ));
		$sMode             = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumn + 12), $i)->getValue( )));
		$sTrims            = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumn + 13), $i)->getValue( )));
		$sYarnFabric       = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumn + 14), $i)->getValue( )));
		$iKnitting         = getBtxVsrPercentage($objSheet->getCellByColumnAndRow(($iColumn + 15), $i)->getValue( ));
		$iDyeing           = getBtxVsrPercentage($objSheet->getCellByColumnAndRow(($iColumn + 16), $i)->getValue( ));
		$iCutting          = getBtxVsrPercentage($objSheet->getCellByColumnAndRow(($iColumn + 17), $i)->getValue( ));
		$iPrintEmbroidery  = getBtxVsrPercentage($objSheet->getCellByColumnAndRow(($iColumn + 18), $i)->getValue( ));
		$iLinking          = getBtxVsrPercentage($objSheet->getCellByColumnAndRow(($iColumn + 19), $i)->getValue( ));
		$iWashing          = getBtxVsrPercentage($objSheet->getCellByColumnAndRow(($iColumn + 20), $i)->getValue( ));
		$iPacking          = getBtxVsrPercentage($objSheet->getCellByColumnAndRow(($iColumn + 21), $i)->getValue( ));
		$sFinalAuditDate   = parseDate($objSheet->getCellByColumnAndRow(($iColumn + 22), $i)->getValue( ));
		$sProductionStatus = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumn + 23), $i)->getValue( )));
		$iShippedQty       = intval($objSheet->getCellByColumnAndRow(($iColumn + 24), $i)->getValue( ));
		$sRemarks          = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumn + 25), $i)->getValue( )));
		$sComments         = trim(addslashes($objSheet->getCellByColumnAndRow(($iColumn + 26), $i)->getValue( )));

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


		$sSQL = "SELECT id FROM tbl_styles WHERE style='$sStyle' AND sub_brand_id='$iBrandId' AND sub_season_id='$iSeasonId' AND id IN (SELECT DISTINCT(style_id) FROM tbl_po_colors WHERE po_id='$iPoId' AND etd_required='$sOriginalEtd')";
		$objDb->query($sSQL);

		$iStyleId = (int)$objDb->getField(0, 0);

		if ($iStyleId == 0)
			$sOutput = str_replace("[Result]", '<span class="warning">- PO Data not matching with Portal PO Record</span><br />[Result]', $sOutput);


		$sSQL = "SELECT po_id FROM tbl_vsr WHERE po_id='$iPoId'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
		{
			$sSQL = "INSERT INTO tbl_vsr (po_id, style_id, price, sub_contractor, item, revised_etd, mode, trims, yarn_fabric, knitting, dyeing, cutting, print_embroidery, linking, washing, packing, final_audit_date, production_status, remarks, created, created_by, modified, modified_by) VALUES ('$iPoId', '$iStyleId', '$fPrice', '$sSubContractor', '$sItem', '$sRevisedEtd', '$sMode', '$sTrims', '$sYarnFabric', '$iKnitting', '$iDyeing', '$iCutting', '$iPrintEmbroidery', '$iLinking', '$iWashing', '$iPacking', '$sFinalAuditDate', '$sProductionStatus', '$sRemarks', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')";

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


			// , price='$fPrice'

			$sSQL = "UPDATE tbl_vsr SET style_id='$iStyleId', sub_contractor='$sSubContractor', item='$sItem', revised_etd='$sRevisedEtd', mode='$sMode', trims='$sTrims', yarn_fabric='$sYarnFabric', knitting='$iKnitting', dyeing='$iDyeing', cutting='$iCutting', print_embroidery='$iPrintEmbroidery', linking='$iLinking', washing='$iWashing', packing='$iPacking'";

			if ($sFinalAuditDate != "" && $sFinalAuditDate != "0000-00-00")
				$sSQL .= ", final_audit_date='$sFinalAuditDate'";

			$sSQL .= ", production_status='$sProductionStatus', remarks='$sRemarks', modified=NOW( ), modified_by='{$_SESSION['UserId']}' WHERE po_id='$iPoId'";

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