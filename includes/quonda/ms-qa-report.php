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

	$objDb->execute("BEGIN");

	$sAuditCode   = strtoupper($sFields[1]);
	$sPO          = strtoupper($sFields[2]);
	$iSampleSize  = intval($sFields[3]);
	$sAuditStage  = strtoupper($sFields[4]);
	$sColors      = strtoupper($sFields[5]);
	$iBatchSize   = intval($sFields[$iCount - 3]);
	$sAuditResult = strtoupper($sFields[$iCount - 2]);
	$sComments    = $sFields[$iCount - 1];
	$fDhu         = 0;

	if (strtolower(substr($sComments, 0, 4)) == "qac ")
		$sComments = substr($sComments, 4);


	if ($sAuditCode == "" || $sPO == "" || !@in_array($sAuditStage, array("B", "C", "F", "O")) ||
	    $iSampleSize == 0 || $sColors == "" || $iBatchSize == 0 || !@in_array($sAuditResult, array("P", "F", "H"))  || $sComments == "")
	{
		if (!@in_array($sAuditStage, array("B", "C", "F", "O")))
			$sSubject = "--- Invalid Audit Stage ---";

		if (!@in_array($sAuditResult, array("P", "F", "H")))
			$sSubject = "--- Invalid Audit Result ---";

		if ($sPO == "")
			$sSubject = "--- Invalid PO Number ---";

		if ($sAuditCode == "")
			$sSubject = "--- Invalid Audit Code ---";

		if ($iSampleSize == 0)
			$sSubject = "--- Invalid Test Qty ---";

		if ($sColors == "")
			$sSubject = "--- Invalid Color ---";

		if ($iBatchSize == 0)
			$sSubject = "--- Invalid Batch Size ---";

		if ($sComments == "")
			$sSubject = "--- Invalid QA Comments ---";

		$bFlag = false;
	}


	if ($bFlag == true)
	{
		// audit code validation
		$sSQL = "SELECT id, report_id, vendor_id, (SELECT line FROM tbl_lines WHERE id=tbl_qa_reports.line_id) AS _Line FROM tbl_qa_reports WHERE audit_code='{$sAuditCode}'";

		if ($bDebug == true)
			print ('<span style="color:#666666;">'.$sSQL.'</span><br />');


		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
		{
			$iAuditId  = $objDb->getField(0, 0);
			$iReportId = $objDb->getField(0, 1);
			$iVendorId = $objDb->getField(0, 2);
			$sLine     = $objDb->getField(0, 3);
		}

		else
		{
			$bFlag    = false;
			$sSubject = "--- Invalid Audit Code ---";

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

			print ('<span style="color:#ff0000;">ERROR: Invalid PO</span><br />');
		}
	}



	if ($bFlag == true)
	{
		$sSQL  = "UPDATE tbl_qa_reports SET po_id='$iPoId', audit_stage='$sAuditStage', total_gmts='$iSampleSize', colors='$sColors', batch_size='$iBatchSize', audit_result='$sAuditResult', qa_comments='$sComments', date_time=NOW( ) WHERE id='$iAuditId'";
		$bFlag = $objDb->execute($sSQL);

		if ($bDebug == true || $bFlag == false)
			print ('<span style="color:#0000ff;">- '.$sSQL.'</span><br />');
	}


	if ($bFlag == true)
	{
		$iIndex = 6;

		if (strtolower($sFields[$iIndex]) == "bf")
		{
			if ( ($sFields[($iIndex + 1)] == "0 0 0 0" && strtolower($sFields[($iIndex + 2)]) == "ef") || strtolower($sFields[($iIndex + 1)]) == "ef")
			{
				// no defect
			}

			else
			{
				if ($bFlag == true)
				{
					$iIndex ++;

					while (strtolower($sFields[$iIndex]) != "ef" && $iIndex < $iCount)
					{
						$sRecord = str_replace("  ", " ", $sFields[$iIndex]);

						@list($sCode, $sDefects, $sArea, $sNature) = @explode(" ", $sRecord);

						$iDefects = intval($sDefects);
						$iAreaId  = intval($sArea);

						switch (strtolower($sNature))
						{
							case "cr"  :  $iNature = 2; break;
							case "mj"  :  $iNature = 1; break;
							default    :  $iNature = 0; break;
						}


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


						if ($iAreaId > 0 && $bFlag == true)
						{
							// validating defect code
							$sSQL = "SELECT * FROM tbl_defect_areas WHERE id='$iAreaId'";

							if ($bDebug == true)
								print ('<span style="color:#cccccc;">'.$sSQL.'</span><br />');

							if ($objDb->query($sSQL) == false || $objDb->getCount( ) == 0)
							{
								$sSubject = "--- Invalid Defect Area Code - $sArea ---";
								$bFlag    = false;
							}
						}


						if ($bFlag == true)
						{
							$iId = getNextId("tbl_qa_report_defects");

							$sSQL  = "INSERT INTO tbl_qa_report_defects (id, audit_id, code_id, defects, area_id, nature) VALUES ('$iId', '$iAuditId', '$iCodeId', '$iDefects', '$iAreaId', '$iNature')";
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
			}
		}

		else
		{
			$bFlag    = false;
			$sSubject = "--- Begin Flag (bf) Not Found ---";

			print ('<span style="color:#ff0000;">ERROR: '.$objDb->error( ).'</span><br />');
		}
	}


	if ($bFlag == true)
	{
		$sSQL = "SELECT total_gmts FROM tbl_qa_reports WHERE id='$iAuditId'";
		$objDb->query($sSQL);

		$iTotalGmts = $objDb->getField(0, 0);


		$sSQL = "SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id='$iAuditId' GROUP BY audit_id";
		$objDb->query($sSQL);

		$iDefects = $objDb->getField(0, 0);


		$fDhu = @round((($iDefects / $iTotalGmts) * 100), 2);


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


		// Final Audit Approval
		if ($sAuditStage == "F" && $sAuditResult == "P")
			@include($sBaseDir."includes/sms/final-audit-approval.php");


		$sSubject = "*** Audit Entry posted successfully ***";

		$objDb->execute("COMMIT");
	}

	else
		$objDb->execute("ROLLBACK");

	print "<br />";


	if (@strlen($sSender) >= 10)
	{
		$sStatus = $objSms->send($sSender, "", $sSms, $sSubject, true);

		print ("SMS Status: ".$sSubject."<br />");
		print ("Mobile: ".$sSender."<br />");
		print ("Mail Status: ".$sStatus."<br />");
	}
?>