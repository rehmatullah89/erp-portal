<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree QUONDA App                                                                   **
	**  Version 3.0                                                                              **
	**                                                                                           **
	**  http://app.3-tree.com                                                                    **
	**                                                                                           **
	**  Copyright 2008-17 (C) Triple Tree                                                        **
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
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$User            = IO::strValue('User');
	$ReportType      = IO::intValue("ReportType");
	$AuditStage      = IO::strValue("AuditStage");
	$Brand           = IO::intValue("Brand");
	$Vendor          = IO::intValue("Vendor");
	$Unit            = IO::intValue("Unit");
	$Pos             = IO::strValue("Pos");
	$Style           = IO::intValue("Style");
	$Colors          = IO::strValue("Colors");
	$SampleSize      = IO::intValue("SampleSize");
	$Sizes           = IO::strValue("Sizes");
	$Line            = IO::strValue("Line");
	$AuditDate       = IO::strValue("AuditDate");
	$AuditTime       = IO::strValue("AuditTime");
	$EndTime         = IO::strValue("EndTime");
	$Group           = IO::intValue("Group");
	$LotNo           = IO::strValue("LotNo");
	$InspectionLevel = IO::intValue("InspectionLevel");
	$CheckLevel      = IO::intValue("CheckLevel");
	$Aql             = IO::floatValue("Aql");
	$Quantity        = IO::intValue("Quantity");
	$DeviceId        = IO::strValue("DeviceId");


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";

	if ($User == "" || $ReportType == 0 || $AuditStage == "")
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, email, mobile, status, email_alerts, user_type, report_types, guest FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else
		{
			$iUser        = $objDb->getField(0, "id");
			$sName        = $objDb->getField(0, "name");
			$sEmail       = $objDb->getField(0, "email");
			$sMobile      = $objDb->getField(0, "mobile");
			$sAlerts      = $objDb->getField(0, "email_alerts");
			$sUserType    = $objDb->getField(0, "user_type");
			$sReportTypes = $objDb->getField(0, "report_types");
			$sGuest       = $objDb->getField(0, "guest");


			$iReportTypes = @explode(",", $sReportTypes);
			$sStartTime   = "{$AuditTime}:00";
			$iCountry     = getDbValue("country_id", "tbl_vendors", "id='$Vendor'");
			$iHours       = getDbValue("hours", "tbl_countries", "id='$iCountry'");
			
			if ($iHours != 0)
				$sStartTime = date("H:i:s", (strtotime($sStartTime) - ($iHours * 3600)));

			
			$sEndTime       = date("H:i:s", (strtotime("{$AuditDate} {$sStartTime}") + ((($SampleSize == 0) ? 15 : $SampleSize) * 2 * 60)));
			$iGroupAuditors = array( );

			if ($Group > 0)
				$iGroupAuditors = @explode(",", getDbValue("users", "tbl_auditor_groups", "id='$Group'"));
			
			if (@in_array($ReportType, array(14, 34, 28, 37, 38)) && $EndTime != "")
				$sEndTime = date("H:i:s", (strtotime("{$EndTime}:00") - ($iHours * 3600)));

			
			if (!@in_array($AuditStage, array("DS", "DT")) && !@in_array($ReportType, array(26, 30)) && ($AuditStage == "" || $Brand == 0 || $Vendor == 0 || $Pos == "" || $Pos == "0" || $Style == 0 || $Colors == "" || $Sizes == "" || $SampleSize < 0 || (!@in_array($ReportType, array(14, 34, 28, 31, 36, 37, 38)) && $Line == "") || $AuditDate == "" || $AuditTime == ""))
				$aResponse["Message"] = "Invalid Audit Scheduling Request";

			else if (@in_array($AuditStage, array("DS", "DT")) && ($AuditStage == "" || $Brand == 0 || $Vendor == 0 || $Style == "" || $SampleSize < 0 || (!@in_array($ReportType, array(14, 34, 28, 31, 36, 37, 38)) && $Line == "") || $AuditDate == "" || $AuditTime == ""))
				$aResponse["Message"] = "Invalid Audit Scheduling Request";
			
			else if ($SampleSize > 1250)
				$aResponse["Message"] = "Invalid Sample Size";

			else if ((@in_array($AuditStage, array("DS", "DT")) || @in_array($ReportType, array(26, 30))) && getDbValue("id", "tbl_styles", "id='$Style' AND sub_brand_id='$Brand'") == 0)
				$aResponse["Message"] = "Invalid Style No";
			
			else if (!@in_array($ReportType, $iReportTypes))
				$aResponse["Message"] = "Invalid Report Type, You don't have permissions for this report";

			else if ($Group > 0 && (count($iGroupAuditors) == 0 || !@in_array($iUser, $iGroupAuditors)))
				$aResponse["Message"] = "Invalid Auditors Group, Please re-login to Continue.";

			else if ($Brand != 368 && !@in_array($ReportType, array(28,37,38)) && (int)getDbValue("COUNT(1)", "tbl_qa_reports", "user_id='$iUser' AND audit_date='$AuditDate' AND (('$sStartTime' BETWEEN start_time AND end_time) OR ('$sEndTime' BETWEEN start_time AND end_time) OR (start_time BETWEEN '$sStartTime' AND '$sEndTime') OR (end_time BETWEEN '$sStartTime' AND '$sEndTime'))") > 0)
				$aResponse["Message"] = "Invalid Audit Time, Time is overlapping with another Audit Schedule.";

			else if ($Brand != 368 && !@in_array($ReportType, array(28,37,38)) && (int)getDbValue("COUNT(1)", "tbl_user_schedule", "user_id='$iUser' AND ('$AuditDate' BETWEEN from_date AND to_date) AND (('$sStartTime' BETWEEN start_time AND end_time) OR ('$sEndTime' BETWEEN start_time AND end_time) OR (start_time BETWEEN '$sStartTime' AND '$sEndTime') OR (end_time BETWEEN '$sStartTime' AND '$sEndTime'))") > 0)
				$aResponse["Message"] = "Invalid Time, Start/End Time is overlapping with another Schedule Entry.";

			else if (($Vendor == 13 || $Vendor == 229) && @substr($Line, 0, 2) == "0_")
				$aResponse["Message"] = "Sorry, You cannot create a new Line in the selected Vendor.";

			else if (!@in_array($ReportType, array(14, 34, 28, 31, 36, 37, 38)) && @substr($Line, 0, 2) != "0_" && getDbValue("COUNT(1)", "tbl_lines", "vendor_id='$Vendor' AND unit_id='$Unit' AND id='$Line'") == 0)
				$aResponse["Message"] = "Wrong Line selection, please re-select the Vendor/Unit/Line.";
			
			else if (@in_array($ReportType, array(28,31,36,37, 38)) && $Quantity == 0)
				$aResponse["Message"] = "Invalid Audit Quantity";
			
			else if (@in_array($ReportType, array(28,31,36,37)) && $InspectionLevel == 0)
				$aResponse["Message"] = "Invalid Inspection Level";			

			else
			{
				$bFlag          = true;
				$sPos           = @explode(",", $Pos);
				$sAdditionalPos = "";
				$iPoId          = $sPos[0];

				if (count($sPos) > 1)
					$sAdditionalPos = @implode(",", array_slice($sPos, 1));


				if (@substr($Line, 0, 2) == "0_")
				{
					$sLine = @substr($Line, 2);


					$sSQL  = "SELECT id FROM tbl_lines WHERE line LIKE '$sLine' AND vendor_id='$Vendor' AND unit_id='$Unit'";
					$objDb->query($sSQL);

					if ($objDb->getCount( ) == 0)
					{
						$iLine = getNextId("tbl_lines");

						$sSQL = "INSERT INTO tbl_lines (id, vendor_id, unit_id, line) VALUES ('$iLine', '$Vendor', '$Unit', '$sLine')";
						$objDb->execute($sSQL, true, $iUser, $sName);
					}

					else
						$iLine = $objDb->getField(0, 0);
				}

				else
					$iLine = intval($Line);


				if (@in_array($AuditStage, array("DS", "DT")))
					$iPoId = 0;


				if ($Aql == 0)
				{
					$Aql = getDbValue("aql", "tbl_brands", "id='$Brand'");
					
					if ($Aql == 0)
					{
						$iParent = getDbValue("parent_id", "tbl_brands", "id='$Brand'");
						$Aql     = getDbValue("aql", "tbl_brands", "id='$iParent'");
					}
				}

				
				$bFlag = $objDb->execute("BEGIN", true, $iUser, $sName);

				
				$InspectionLevel = (($InspectionLevel == 0) ? 2 : $InspectionLevel);
				$Aql             = (($Aql == 0) ? 2.5 : $Aql);
				$CheckLevel     = (($CheckLevel == 0) ? 1 : $CheckLevel);
				$sApproved       = "Y"; //((date("G") >= 10 && date("G") <= 17) ? "N" : "Y");
				$iDepartment     = (int)getDbValue("id", "tbl_departments", "FIND_IN_SET('$Brand', brands)");
				$sCustomSample   = (($SampleSize == 0) ? "Y" : "N");
				$sInspectionType = (($SampleSize == 0) ? "F" : "V");
				$sPublished      = "Y"; //(($ReportType == 14) ? "N" : "Y");
				$iMasterId       = 0;
				
				
				if ($ReportType == 14 || $ReportType == 34)
				{
					$sTable = (($ReportType == 34) ? "_test" : "");
					$aPos   = array( );
					$aPos[] = $iPoId;
					
					if ($sAdditionalPos != "")
						$aPos = @array_merge($aPos, @explode(",", $sAdditionalPos));
					
					
					$sPos   = @implode(",", $aPos);
					$aDates = getList("tbl_po_colors", "DISTINCT(etd_required)", "etd_required", "FIND_IN_SET(po_id, '$sPos')");
					$sDates = @implode(",", $aDates);

					
					if (getDbValue("COUNT(1)", "tbl_mgf{$sTable}_master_ids", "vendor_id='$Vendor' AND style_id='$Style'") > 0)
					{
						$sSQL = "SELECT m.id
								 FROM tbl_mgf{$sTable}_master_ids m, tbl_mgf{$sTable}_master_pos po, tbl_mgf{$sTable}_master_dates d
								 WHERE m.id=po.master_id AND m.id=d.master_id AND po.master_id=d.master_id
									   AND m.vendor_id='$Vendor' AND m.style_id='$Style'
									   AND (FIND_IN_SET(po.po_id, '$sPos') OR FIND_IN_SET(d.etd_required, '$sDates'))";
						$objDb->query($sSQL);
						
						if ($objDb->getCount( ) >= 1)
							$iMasterId = $objDb->getField(0, 0);
					}
					
					
					if ($iMasterId == 0)
					{
						$iMasterId = getNextId("tbl_mgf{$sTable}_master_ids");
						
						$sSQL  = "INSERT INTO tbl_mgf{$sTable}_master_ids (id, vendor_id, style_id) VALUES ('$iMasterId', '$Vendor', '$Style')";
						$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
					}

					if ($bFlag == true)
					{
						foreach ($aPos as $iPo)
						{
							if (getDbValue("COUNT(1)", "tbl_mgf{$sTable}_master_pos", "master_id='$iMasterId' AND po_id='$iPo'") == 0)
							{
								$sSQL  = "INSERT INTO tbl_mgf{$sTable}_master_pos (master_id, po_id) VALUES ('$iMasterId', '$iPo')";
								$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);						
							}
						}
					}
					
					if ($bFlag == true)
					{
						foreach ($aDates as $sDate)
						{
							if (getDbValue("COUNT(1)", "tbl_mgf{$sTable}_master_dates", "master_id='$iMasterId' AND etd_required='$sDate'") == 0)
							{
								$sSQL  = "INSERT INTO tbl_mgf{$sTable}_master_dates (master_id, etd_required) VALUES ('$iMasterId', '$sDate')";
								$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);						
							}
						}
					}
				}

				
				if ($Quantity > 0)
					$SampleSize = getSampleSize($Quantity, $ReportType, $InspectionLevel, $CheckLevel);


				if ($bFlag == true)
				{
					$sSQL  = "INSERT INTO tbl_qa_reports (user_id, group_id, vendor_id, unit_id, brand_id, po_id, additional_pos, style_id, master_id, department_id, colors, audit_stage, inspection_level, check_level, aql, audit_quantity, total_gmts, custom_sample, report_id, line_id, cutting_lot_no, sizes, audit_date, start_time, end_time, approved, commission_type, created_at, date_time, status, device_id, published, created_by, modified_by)
												  VALUES ('$iUser', '$Group', '$Vendor', '$Unit', '$Brand', '$iPoId', '$sAdditionalPos', '$Style', '$iMasterId', '$iDepartment', '$Colors', '$AuditStage', '$InspectionLevel', '$CheckLevel', '$Aql', '$Quantity', '$SampleSize', '$sCustomSample', '$ReportType', '$iLine', '$LotNo', '$Sizes', '$AuditDate', '$sStartTime', '$sEndTime', '$sApproved', '$sInspectionType', NOW( ), NOW( ), '', '$DeviceId', '$sPublished', '$iUser', '$iUser')";
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}

				if ($bFlag == true)
				{
					$iId        = $objDb->getAutoNumber( );
					$sAuditCode = ("S".str_pad($iId, 4, 0, STR_PAD_LEFT));

					$sSQL  = "UPDATE tbl_qa_reports SET audit_code='$sAuditCode' WHERE id='$iId'";
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}

				if ($bFlag == true)
				{
					$objDb->execute("COMMIT", true, $iUser, $sName);


					$sVendor  = getDbValue("vendor", "tbl_vendors", "id='$Vendor'");
					$sMessage = "";
					
					
					// Deviation Alert
					if ($ReportType == 37)
					{
						$aPos   = array( );
						$aPos[] = $iPoId;
						
						if ($sAdditionalPos != "")
							$aPos = @array_merge($aPos, @explode(",", $sAdditionalPos));
						
						
						$sPos       = @implode(",", $aPos);					
						$iOrderQty  = getDbValue("SUM(order_qty)", "tbl_po_colors", "po_id IN ($sPos) AND style_id='$Style' AND FIND_IN_SET(color, '$Colors')");
						$fDeviation = (@round((($Quantity / $iOrderQty) * 100), 2) - 100);
						
						if ($fDeviation < -5 || $fDeviation > 5)
						{
							$sOrderNos    = getDbValue("GROUP_CONCAT(order_no SEPARATOR ', ')", "tbl_po", "id IN ($sPos)");
							$sStyleNo     = getDbValue("style", "tbl_styles", "id='$Style'");
							$sEtdRequired = getDbValue("MIN(etd_required)", "tbl_po_colors", "po_id IN ($sPos)");
							$sColors      = str_replace(",", ", ", $Colors);
							$sQuantities  = "";
							
							foreach (@explode(",", $Colors) as $sColor)
							{
								if ($sQuantities != "")
									$sQuantities .= ", ";
								
								$sQuantities .= getDbValue("SUM(order_qty)", "tbl_po_colors", "po_id IN ($sPos) AND style_id='$Style' AND color='$sColor'");
							}
							
						
							$sBody = ("Dear Reader,<br /><br />
									   An Audit has been scheduled for Armed Angels where the quantity offered for inspection is deviant from the quantity in the original order.<br /><br />
									   The details are as below:<br />
									   <ol type='i'>
									   <li>Purchase Order No: {$sOrderNos}</li>
									   <li>Article No: {$sStyleNo}</li>
									   <li>ETA: ".formatDate($sEtdRequired, "jS F, Y")."</li>
									   <li>Colors Selected: {$sColors}</li>
									   <li>Order Quantity Against Each Color: {$sQuantities}</li>
									   <li>Total Order Quantity: {$iOrderQty}</li>
									   <li>Offered Quantity for Inspection: {$Quantity}</li>
									   <li>Deviation in terms of Percentage: {$fDeviation}%</li>
									   </ol>
									   <br />
									   <b>".SITE_TITLE."</b><br />
									   <a href='".SITE_URL."'>".SITE_URL."</a>");

									   
							$objEmail = new PHPMailer( );

							$objEmail->Subject = "Deviation Found in POs ({$sOrderNos}) at {$sVendor}";
							$objEmail->Body    = $sBody;
							$objEmail->IsHTML(true);

							$objEmail->AddAddress($sEmail, $sName);
							$objEmail->AddAddress("esra@control-ist.com", "Esra Caglarer");
							$objEmail->AddAddress("omer@3-tree.com", "Omer rauf");							

							$objEmail->Send( );
							
							
							$sMessage = "The offered quantity for inspections differs from the Order Quantity and the Deviation is beyond 5%";
						}
					}


					if ($sApproved == "Y")
					{
						$sLine = getDbValue("line", "tbl_lines", "id='$iLine'");
						
						if ($iHours != 0)
						{
							$sStartTime = date("H:i A", (strtotime($sStartTime) + ($iHours * 3600)));		
							$sEndTime   = date("H:i A", (strtotime($sEndTime) + ($iHours * 3600)));
						}


						if (@in_array($ReportType, array(14, 34, 28, 37, 38)))
							$sBody = "{$sAuditCode} is in {$sVendor} from {$sStartTime} to {$sEndTime} on {$AuditDate}";
						
						else
							$sBody = "{$sAuditCode} is in {$sVendor} Line {$sLine} from {$sStartTime} to {$sEndTime} on {$AuditDate}";
					}

					else
						$sBody = "Late Schedule. Please contact your Manager for Audits Schedule Approval.";


					$sSubject  = "New Audit Job";
					$sAuditors = array( );

					$sAuditors[] = array("Name" => $sName, "Email" => $sEmail, "Alerts" => $sAlerts, "Mobile" => $sMobile);


					if ($Group > 0)
					{
						$sUsers = getDbValue("users", "tbl_auditor_groups", "id='$Group'");


						$sSQL = "SELECT name, email, mobile, email_alerts FROM tbl_users WHERE status='A' AND FIND_IN_SET(id, '$susers') AND id!='$iUser'";
						$objDb->query($sSQL);

						$iCount = $objDb->getCount( );

						for ($i = 0; $i < $iCount; $i ++)
						{
							$sName   = $objDb->getField($i, "name");
							$sEmail  = $objDb->getField($i, "email");
							$sMobile = $objDb->getField($i, "mobile");
							$sAlerts = $objDb->getField($i, "email_alerts");

							$sAuditors[] = array("Name" => $sName, "Email" => $sEmail, "Alerts" => $sAlerts, "Mobile" => $sMobile);
						}
					}


					// email + sms to auditor
					$objEmail = new PHPMailer( );

					$objEmail->Subject = $sSubject;
					$objEmail->Body    = $sBody;

					$objEmail->IsHTML(false);

					if (count($sAuditors) > 0)
					{
						$bRecipients = false;

						foreach ($sAuditors as $sAuditor)
						{
							if ($sAuditor['Alerts'] == "Y")
							{
								$objEmail->AddAddress($sAuditor['Email'], $sAuditor['Name']);

								$bRecipients = true;
							}
						}

						if ($bRecipients == true)
							$objEmail->Send( );
					}


					$objSms = new Sms( );

					foreach ($sAuditors as $sAuditor)
						$objSms->send($sAuditor['Mobile'], "", $sBody, $sSubject);

					$objSms->close( );


					$aResponse['Status']  = "OK";
					$aResponse['Message'] = (($sMessage != "") ? $sMessage : "Audit Scheduled Successfully!");
				}

				else
				{
					$aResponse['Message'] = "An ERROR occured, please re-try.";

					$objDb->execute("ROLLBACK", true, $iUser, $sName);
				}
			}
		}
	}



/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Schedule Audit";
	$objEmail->Body    = @json_encode($aResponse).$sMessage;

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/

	print @json_encode($aResponse);


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>