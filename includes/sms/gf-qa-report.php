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

	$objDb->execute("BEGIN");

	if (strtoupper($sFields[2]) == "BF")
	{
		$sAuditCode = strtoupper($sFields[1]);


		// audit code validation
		$sSQL = "SELECT id, po_id, report_id, vendor_id, fabric_width,
		                (SELECT line FROM tbl_lines WHERE id=tbl_qa_reports.line_id) AS _Line
		         FROM tbl_qa_reports
		         WHERE audit_code='{$sAuditCode}'";

		if ($bDebug == true)
			print ('<span style="color:#666666;">'.$sSQL.'</span><br />');

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
		{
			$iAuditId     = $objDb->getField(0, 0);
			$iPoId        = $objDb->getField(0, 1);
			$iReportId    = $objDb->getField(0, 2);
			$iVendorId    = $objDb->getField(0, 3);
			$iFabricWidth = $objDb->getField(0, 4);
			$sLine        = $objDb->getField(0, 5);
		}

		else
		{
			$bFlag    = false;
			$sSubject = "--- Invalid Audit Code ---";

			if ($bDebug == true)
				print ('<span style="color:#ff0000;">ERROR: Invalid Audit Code</span><br />');
		}


		if ($bFlag == true)
		{
			$sAuditDate = getDbValue("audit_date", "tbl_qa_reports", "audit_code='$sAuditCode'");

			if (strtotime(date("Y-m-d")) > (strtotime($sAuditDate) + 172800))  // 2 Days Check
			{
				$sSubject = "--- Report Locked ---";

				$bFlag = false;
			}
		}


		if ($bFlag == true)
		{
			$iIndex = 3;

			while (strtolower($sFields[$iIndex]) != "ef" && $iIndex < $iCount)
			{
				$sRecord = str_replace("  ", " ", $sFields[$iIndex]);

				@list($sCode, $sDefects) = @explode(" ", $sRecord);

				$iDefects = intval($sDefects);

				if ($sCode == "" || $iDefects == 0)
				{
					if ($sCode == "")
						$sSubject = "--- Invalid Defect Code: $sCode ---";

					if ($iDefects == 0)
						$sSubject = "--- Invalid Defects Numbers ---";

					$bFlag = false;
				}


				if ($bFlag == true)
				{
					// validating defect code
					$sSQL = "SELECT id FROM tbl_defect_codes WHERE report_id='$iReportId' AND code='$sCode'";

					if ($bDebug == true)
						print ('<span style="color:#cccccc;">'.$sSQL.'</span><br />');

					if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
						$iCodeId = $objDb->getField(0, 0);

					else
					{
						$sSubject = "--- Invalid Defect Code - $sCode ---";
						$bFlag    = false;
					}
				}


				if ($bFlag == true)
				{
					$iId = getNextId("tbl_gf_report_defects");

					$sSQL  = ("INSERT INTO tbl_gf_report_defects (id, audit_id, roll, panel, code_id, grade, defects) VALUES ('$iId', '$iAuditId', '1', '1', '$iCodeId', '1', '$iDefects')");
					$bFlag = $objDb->execute($sSQL);

					if ($bDebug == true || $bFlag == false)
						print ('<span style="color:#999999;">'.$sSQL.'</span><br />');
				}


				if ($bFlag == false)
					break;

				$iIndex ++;
			}


			if (strtolower($sFields[$iIndex]) != "ef" && $bFlag == true)
			{
				$sSubject = "--- End Flag (ef) Not Found ---";
				$bFlag    = false;
			}
		}


		if ($bFlag == true)
		{
			$sSQL = "SELECT given_1 FROM tbl_gf_rolls_info WHERE audit_id='$iAuditId'";
			$objDb->query($sSQL);

			$iGivenQty = $objDb->getField(0, 0);


			$sSQL = "SELECT COALESCE(SUM(defects), 0) FROM tbl_gf_report_defects WHERE audit_id='$iAuditId'";
			$objDb->query($sSQL);

			$iDefects = $objDb->getField(0, 0);


			$fDhu = @round(((($iDefects * 3600) / $iGivenQty) / $iFabricWidth), 2);


			$sSQL  = "UPDATE tbl_qa_reports SET dhu='$fDhu' WHERE id='$iAuditId'";
			$bFlag = $objDb->execute($sSQL);

			if ($bDebug == true || $bFlag == false)
				print ('<span style="color:#999999;">'.$sSQL.'</span><br />');
		}


		if ($bFlag == true)
		{
			$sSQL = "SELECT vendor FROM tbl_vendors WHERE id='$iVendorId'";
			$objDb->query($sSQL);

			$sVendor = $objDb->getField(0, 0);


			$sSQL = "SELECT style_id FROM tbl_po_colors WHERE po_id='$iPoId' LIMIT 1";
			$objDb->query($sSQL);

			$iStyleId = $objDb->getField(0, 0);


			$sSQL = "SELECT style, sub_brand_id FROM tbl_styles WHERE id='$iStyleId'";
			$objDb->query($sSQL);

			$sStyle   = $objDb->getField(0, 0);
			$iBrandId = (int)$objDb->getField(0, 1);


			$sSQL = "SELECT brand FROM tbl_brands WHERE id='$iBrandId'";
			$objDb->query($sSQL);

			$sBrand = $objDb->getField(0, 0);


			// DR Above Target Line
			@include($sBaseDir."includes/sms/dr-above-target-line.php");


			// Continuity Defect
			@include($sBaseDir."includes/sms/continuity-defect.php");


			$sSubject = "*** Audit Defects posted successfully ***";

			$objDb->execute("COMMIT");
		}

		else
			$objDb->execute("ROLLBACK");
	}




	else
	{
		$sAuditCode      = strtoupper($sFields[1]);
		$sPO             = strtoupper($sFields[2]);
		$sAuditStage     = strtoupper($sFields[3]);
		$sAuditResult    = strtoupper($sFields[4]);
		$iRollsInspected = strtoupper($sFields[5]);
		$sDyeLotNo       = strtoupper($sFields[6]);
		$sStockStatus    = strtoupper($sFields[7]);
		$sRollNo         = strtolower($sFields[8]);
		$iGivenQty       = intval($sFields[9]);
		$iActualQty      = intval($sFields[10]);
		$sColorMatch     = strtoupper($sFields[11]);
		$sShading        = strtoupper($sFields[12]);
		$sHandFeel       = strtoupper($sFields[13]);
		$sLabTesting     = strtoupper($sFields[14]);
		$iFabricWidth    = intval($sFields[15]);
		$iShipQty        = intval($sFields[16]);
		$iNoOfRolls      = intval($sFields[17]);
		$sApprovedSample = strtoupper($sFields[18]);
		$sComments       = $sFields[19];

		if (strtolower(substr($sComments, 0, 4)) == "qac ")
			$sComments = substr($sComments, 4);


		if ($sAuditCode == "" || $sPO == "" || !@in_array($sAuditStage, $sAuditStages) || $iRollsInspected == 0 ||
		    $sDyeLotNo == "" || $sRollNo == "" || $iGivenQty == 0 || $iActualQty == 0 || $iFabricWidth == 0 || $iShipQty == 0 || $iNoOfRolls == 0 || $sStockStatus == "" ||
		    !@in_array($sColorMatch, array("A", "R", "N")) || !@in_array($sShading, array("A", "R", "N")) || !@in_array($sHandFeel, array("A", "R", "N")) ||
		    !@in_array($sLabTesting, array("A", "R", "P")) || !@in_array($sApprovedSample, array("Y", "N")) || !@in_array($sAuditResult, array("F", "H", "P")) ||
		    $sComments == "")
		{
			if (!@in_array($sAuditStage, $sAuditStages))
				$sSubject = "--- Invalid Audit Stage ---";

			if (!@in_array($sAuditResult, array("F", "H", "P")))
				$sSubject = "--- Invalid Audit Result ---";

			if (!@in_array($sColorMatch, array("A", "R", "N")))
				$sSubject = "--- Invalid Color Match ---";

			if (!@in_array($sShading, array("A", "R", "N")))
				$sSubject = "--- Invalid Shading ---";

			if (!@in_array($sHandFeel, array("A", "R", "N")))
				$sSubject = "--- Invalid Hand Feel ---";

			if (!@in_array($sApprovedSample, array("Y", "N")))
				$sSubject = "--- Invalid Approved Sample ---";

			if (!@in_array($sLabTesting, array("A", "R", "P")))
				$sSubject = "--- Invalid Lab Testing ---";

			if ($sPO == "")
				$sSubject = "--- Invalid PO Number ---";

			if ($sAuditCode == "")
				$sSubject = "--- Invalid Audit Code ---";

			if ($iRollsInspected == 0)
				$sSubject = "--- Invalid Roll Inspected ---";

			if ($sDyeLotNo == "")
				$sSubject = "--- Invalid Dye Lot No ---";

			if ($sRollNo == "")
				$sSubject = "--- Invalid Roll No ---";

			if ($iGivenQty == 0)
				$sSubject = "--- Invalid Given Quantity ---";

			if ($iActualQty == 0)
				$sSubject = "--- Invalid Actual Quantity ---";

			if ($iFabricWidth == 0)
				$sSubject = "--- Invalid Fabric Width ---";

			if ($iShipQty == 0)
				$sSubject = "--- Invalid Ship Quantity ---";

			if ($iNoOfRolls == 0)
				$sSubject = "--- Invalid Number of Rolls ---";

			if ($sStockStatus == "")
				$sSubject = "--- Invalid Stock Status ---";

			if ($sComments == "")
				$sSubject = "--- Invalid QA Comments ---";

			$bFlag = false;
		}


		if ($bFlag == true)
		{
			$sAuditDate = getDbValue("audit_date", "tbl_qa_reports", "audit_code='$sAuditCode'");

			if (strtotime(date("Y-m-d")) > (strtotime($sAuditDate) + 172800))  // 2 Days Check
			{
				$sSubject = "--- Report Locked ---";

				$bFlag = false;
			}
		}


		if ($bFlag == true)
		{
			// audit code validation
			$sSQL = "SELECT id, vendor_id FROM tbl_qa_reports WHERE audit_code='{$sAuditCode}'";

			if ($bDebug == true)
				print ('<span style="color:#666666;">'.$sSQL.'</span><br />');

			if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			{
				$iAuditId  = $objDb->getField(0, 0);
				$iVendorId = $objDb->getField(0, 1);
			}

			else
			{
				$bFlag    = false;
				$sSubject = "--- Invalid Audit Code ---";

				if ($bDebug == true)
					print ('<span style="color:#ff0000;">ERROR: Invalid Audit Code</span><br />');
			}
		}


		if ($bFlag == true)
		{
			// PO validation
			$sSQL = "SELECT id FROM tbl_po WHERE order_no='{$sPO}' AND vendor_id='$iVendorId'";

			if ($bDebug == true)
				print ('<span style="color:#666666;">'.$sSQL.'</span><br />');

			if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
				$iPoId = $objDb->getField(0, 0);

			else
			{
				$bFlag    = false;
				$sSubject = "--- Invalid PO ---";

				if ($bDebug == true)
					print ('<span style="color:#ff0000;">ERROR: Invalid PO</span><br />');
			}
		}



		if ($bFlag == true)
		{
			$sApprovedSample = (($sApprovedSample == "Y") ? "Yes" : "No");

			if ($sAuditResult == "P")
				$sSubSQL = ", re_screen_qty='0', ship_qty='$iShipQty' ";

			else
				$sSubSQL = ", ship_qty='0', re_screen_qty='$iShipQty' ";


			$iStyleId = getDbValue("style_id", "tbl_po_colors", "po_id='$iPoId'");


			$sSQL  = "UPDATE tbl_qa_reports SET po_id='$iPoId', style_id='$iStyleId', audit_stage='$sAuditStage', audit_result='$sAuditResult', dye_lot_no='$sDyeLotNo', rolls_inspected='$iRollsInspected', stock_status='$sStockStatus', no_of_rolls='$iNoOfRolls', fabric_width='$iFabricWidth', approved_sample='$sApprovedSample', qa_comments='$sComments', dhu='0', date_time=NOW( ), audit_mode='3' $sSubSQL WHERE id='$iAuditId'";
			$bFlag = $objDb->execute($sSQL);

			if ($bDebug == true || $bFlag == false)
				print ('<span style="color:#0000ff;">- '.$sSQL.'</span><br />');
		}


		if ($bFlag == true)
		{
			$sSQL = "SELECT audit_id FROM tbl_gf_inspection_checklist WHERE audit_id='$iAuditId'";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 0)
				$sSQL  = ("INSERT INTO tbl_gf_inspection_checklist (audit_id, color_match, shading, hand_feel, lab_testing) VALUES ('$iAuditId', '$sColorMatch', '$sShading', '$sHandFeel', '$sLabTesting')");

			else
				$sSQL  = ("UPDATE tbl_gf_inspection_checklist SET color_match='$sColorMatch', shading='$sShading', hand_feel='$sHandFeel', lab_testing='$sLabTesting' WHERE audit_id='$iAuditId'");

			$bFlag = $objDb->execute($sSQL);

			if ($bDebug == true || $bFlag == false)
				print ('<span style="color:#0000ff;">- '.$sSQL.'</span><br />');
		}


		if ($bFlag == true)
		{
			$sSQL  = "DELETE FROM tbl_gf_rolls_info WHERE audit_id='$iAuditId'";
			$bFlag = $objDb->execute($sSQL);

			if ($bDebug == true || $bFlag == false)
				print ('<span style="color:#0000ff;">- '.$sSQL.'</span><br />');
		}


		if ($bFlag == true)
		{
			$iRollId = getNextId("tbl_gf_rolls_info");

			$sSQL  = ("INSERT INTO tbl_gf_rolls_info (id, audit_id, roll_no, ref_1, given_1, actual_1, ref_2, given_2, actual_2, ref_3, given_3, actual_3) VALUES ('$iRollId', '$iAuditId', '$sRollNo', '1', '$iGivenQty', '$iActualQty', '0', '0', '0', '0', '0', '0')");
			$bFlag = $objDb->execute($sSQL);

			if ($bDebug == true || $bFlag == false)
				print ('<span style="color:#0000ff;">- '.$sSQL.'</span><br />');
		}


		if ($bFlag == true && $sAuditStage == "F" && $sAuditResult == "P")
		{
			$sSQL  = "UPDATE tbl_qa_reports SET approved_sample='Yes', shipping_mark='Y', packing_check='Y' WHERE id='$iAuditId'";
			$bFlag = $objDb->execute($sSQL);
		}


		if ($bFlag == true && ($sAuditStage == "F" || $sAuditResult == "P" || $sAuditResult == "A" || $sAuditResult == "B"))
		{
			$sSQL  = "UPDATE tbl_qa_reports SET status='$sAuditResult' WHERE id='$iAuditId' AND status=''";
			$bFlag = $objDb->execute($sSQL);
		}


		if ($bFlag == true)
		{
			$sSQL  = "DELETE FROM tbl_gf_report_defects WHERE audit_id='$iAuditId'";
			$bFlag = $objDb->execute($sSQL);

			if ($bDebug == true || $bFlag == false)
				print ('<span style="color:#cccccc;">- '.$sSQL.'</span><br />');
		}


		if ($bFlag == true)
		{
			$sSQL = "SELECT vendor FROM tbl_vendors WHERE id='$iVendorId'";
			$objDb->query($sSQL);

			$sVendor = $objDb->getField(0, 0);


			$sSQL = "SELECT style_id FROM tbl_po_colors WHERE po_id='$iPoId' LIMIT 1";
			$objDb->query($sSQL);

			$iStyleId = $objDb->getField(0, 0);


			$sSQL = "SELECT style, sub_brand_id FROM tbl_styles WHERE id='$iStyleId'";
			$objDb->query($sSQL);

			$sStyle   = $objDb->getField(0, 0);
			$iBrandId = (int)$objDb->getField(0, 1);


			$sSQL = "SELECT brand FROM tbl_brands WHERE id='$iBrandId'";
			$objDb->query($sSQL);

			$sBrand = $objDb->getField(0, 0);


			// Final Audit Conducted
			if ($sAuditStage == "F")
				@include($sBaseDir."includes/sms/final-audit.php");

			else if (!@in_array($sAuditResult, array("P", "A", "B")))
				@include($sBaseDir."includes/sms/inline-audit.php");


			// Final Audit Approval
			if ($sAuditStage == "F" && $sAuditResult == "P")
				@include($sBaseDir."includes/sms/final-audit-approval.php");


			// Updating VSR
			$sSQL  = "SELECT audit_date, po_id, additional_pos FROM tbl_qa_reports WHERE id='$iAuditId' AND audit_stage='F' AND audit_result='P'";
			$objDb->query($sSQL);

			if ($bDebug == true || $bFlag == false)
				print ('<span style="color:#999999;">'.$sSQL.'</span><br />');

			if ($objDb->getCount( ) == 1)
			{
				$sDate       = $objDb->getField(0, 0);
				$sPos        = $objDb->getField(0, 1);
				$sAdditional = $objDb->getField(0, 2);

				if ($sAdditional != "")
					$sPos .= (",".$sAdditional);


				$sSQL = "UPDATE tbl_vsr SET final_audit_date='$sDate' WHERE po_id IN ($sPos)";
				$objDb->execute($sSQL);

				if ($bDebug == true || $bFlag == false)
					print ('<span style="color:#999999;">'.$sSQL.'</span><br />');
			}


			$sSubject = "*** Audit Entry posted successfully ***";
		}
	}


	if ($bFlag == true)
		$objDb->execute("COMMIT");

	else
		$objDb->execute("ROLLBACK");


	if ($bDebug == true)
		print "<br />";


	if (@strlen($sSender) >= 10)
	{
		$sStatus = $objSms->send($sSender, "", $sSms, $sSubject, true);

		if ($bDebug == true)
		{
			print ("SMS Status: ".$sSubject."<br />");
			print ("Mobile: ".$sSender."<br />");
			print ("Mail Status: ".$sStatus."<br />");
		}
	}
?>