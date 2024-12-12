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

	@require_once("../requires/Excel/reader.php");

	$objExcel = new Spreadsheet_Excel_Reader( );

	$objExcel->setOutputEncoding('CP1251');
	$objExcel->read($sBaseDir.TEMP_DIR.$sVsrFile);

	$iRows    = $objExcel->sheets[0]['numRows'];
	$iColumns = $objExcel->sheets[0]['numCols'];

	if ($iColumns != 27 || $iRows < 11)
	{
		@unlink($sBaseDir.TEMP_DIR.$sVsrFile);

		redirect("vsr-data.php", "INVALID_VSR_FILE");
	}


	$iRow    = 10;
	$iColumn = 1;

	if ($objExcel->sheets[0]['cells'][$iRow][($iColumn + 0)] != "Factory" ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 1)] != "S/cont. Fac." ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 2)] != "Label" ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 3)] != "Order" ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 4)] != "Style" ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 5)] != "Style Name" ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 6)] != "Season" ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 7)] != "Total Pcs" ||
		$objExcel->sheets[0]['cells'][$iRow][($iColumn + 8)] != "Item" ||
		$objExcel->sheets[0]['cells'][$iRow][($iColumn + 9)] != "ETD" ||
		$objExcel->sheets[0]['cells'][$iRow][($iColumn + 10)] != "Revised ETD" ||
		$objExcel->sheets[0]['cells'][$iRow][($iColumn + 11)] != "Price" ||
		$objExcel->sheets[0]['cells'][$iRow][($iColumn + 12)] != "Mode" ||
		$objExcel->sheets[0]['cells'][$iRow][($iColumn + 13)] != "Trims" ||
		$objExcel->sheets[0]['cells'][$iRow][($iColumn + 14)] != "Yarn/Fabric" ||
		$objExcel->sheets[0]['cells'][$iRow][($iColumn + 15)] != "Knitting" ||
		$objExcel->sheets[0]['cells'][$iRow][($iColumn + 16)] != "Dyeing" ||
		$objExcel->sheets[0]['cells'][$iRow][($iColumn + 17)] != "Cutting" ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 18)] != "Print/Embroidery" ||
		$objExcel->sheets[0]['cells'][$iRow][($iColumn + 19)] != "Sewing/Linking" ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 20)] != "Washing" ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 21)] != "Packing" ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 22)] != "Final Audit" ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 23)] != "Status" ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 24)] != "Shipped Qty" ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 25)] != "Remarks" ||
	    $objExcel->sheets[0]['cells'][$iRow][($iColumn + 26)] != "Portal Comments")
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


	$sOutput = "";
	$sClass  = array("evenRow", "oddRow");

	for ($i = ($iRow + 1), $iIndex = 1; $i <= $iRows; $i++, $iIndex ++)
	{
		$iColumn = 1;

		$sVendor           = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 0)]));
		$sSubContractor    = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 1)]));
		$sBrand            = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 2)]));
		$sOrderNo          = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 3)]));
		$sStyle            = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 4)]));
		$sStyleName        = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 5)]));
		$sSeason           = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 6)]));
		$sQuantity         = intval($objExcel->sheets[0]['cells'][$i][($iColumn + 7)]);
		$sItem             = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 8)]));
		$sOriginalEtd      = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 9)]);
		$sRevisedEtd       = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 10)]);
		$fPrice            = floatval($objExcel->sheets[0]['cells'][$i][($iColumn + 11)]);
		$sMode             = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 12)]));
		$sTrims            = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 13)]));
		$sYarnFabric       = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 14)]));
		$sKnitting         = getBtxVsrPercentage($objExcel->sheets[0]['cells'][$i][($iColumn + 15)]);
		$sDyeing           = getBtxVsrPercentage($objExcel->sheets[0]['cells'][$i][($iColumn + 16)]);
		$sCutting          = getBtxVsrPercentage($objExcel->sheets[0]['cells'][$i][($iColumn + 17)]);
		$sPrintEmbroidery  = getBtxVsrPercentage($objExcel->sheets[0]['cells'][$i][($iColumn + 18)]);
		$sLinking          = getBtxVsrPercentage($objExcel->sheets[0]['cells'][$i][($iColumn + 19)]);
		$sWashing          = getBtxVsrPercentage($objExcel->sheets[0]['cells'][$i][($iColumn + 20)]);
		$sPacking          = getBtxVsrPercentage($objExcel->sheets[0]['cells'][$i][($iColumn + 21)]);
		$sFinalAuditDate   = parseDate($objExcel->sheets[0]['cells'][$i][($iColumn + 22)]);
		$sProductionStatus = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 23)]));
		$iShippedQty       = trim(intval($objExcel->sheets[0]['cells'][$i][($iColumn + 24)]));
		$sRemarks          = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 25)]));
		$sComments         = trim(addslashes($objExcel->sheets[0]['cells'][$i][($iColumn + 26)]));


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