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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$User       = IO::intValue('User');
	$Vendor     = IO::intValue("Vendor");
	$OrderNo    = IO::strValue("OrderNo");
	$StyleNo    = IO::strValue("StyleNo");
	$Colors     = IO::strValue("Colors");
	$SampleSize = IO::intValue("SampleSize");
	$Report     = IO::intValue("Report");
	$Line       = IO::strValue("Line");
	$AuditDate  = IO::strValue("AuditDate");
	$StartTime  = IO::strValue("StartTime");
	$EndTime    = IO::strValue("EndTime");
	$Department = IO::intValue("Department");
	$AuditStage = IO::strValue("AuditStage");


	$aResponse = array( );


	if ($User == 0)
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid User ID";
	}

	else
	{
		$sStatus = getDbValue("status", "tbl_users", "id='$User'");

		if ($sStatus != "A")
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "User Account is Disabled";
		}

		else if ($Department == 0 || $Vendor == 0 || $SampleSize == 0 || $Report == 0 || $Line == "" || $AuditDate == "" || $StartTime == "" || $EndTime == "" || $AuditStage == "")
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "Invalid Audit Scheduling Request";
		}

		else if ($Report != 6)
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "Please use Quonda v2 for Audit Scheduling";
		}

		else if ((int)getDbValue("COUNT(1)", "tbl_qa_reports", "user_id='$User' AND audit_date='$AuditDate' AND (('$StartTime' BETWEEN start_time AND end_time) OR ('$EndTime' BETWEEN start_time AND end_time) OR (start_time BETWEEN '$StartTime' AND '$EndTime') OR (end_time BETWEEN '$StartTime' AND '$EndTime'))") > 0)
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "Invalid Audit Time, Time is overlapping with another Audit Schedule.";
		}

		else if ((int)getDbValue("COUNT(1)", "tbl_user_schedule", "user_id='$User' AND ('$AuditDate' BETWEEN from_date AND to_date) AND (('$StartTime' BETWEEN start_time AND end_time) OR ('$EndTime' BETWEEN start_time AND end_time) OR (start_time BETWEEN '$StartTime' AND '$EndTime') OR (end_time BETWEEN '$StartTime' AND '$EndTime'))") > 0)
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "Invalid Time, Start/End Time is overlapping with another Schedule Entry.";
		}

		else if (strtotime("{$AuditDate} {$EndTime}") <= strtotime("{$AuditDate} {$StartTime}"))
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "Invalid End Time, End Time should be greater than Audit Start Time.";
		}

		else
		{
			$sUser    = getDbValue("name", "tbl_users", "id='$User'");
			$bFlag    = true;
			$sMessage = "";
			$iPoId    = 0;
			$iStyleId = 0;
			$sOrders  = array( );


			if ($OrderNo != "")
			{
				// PO validation
				if (@strpos($OrderNo, ",") !== FALSE)
				{
					$sPos = @explode(",", $OrderNo);

					for ($i = 0; $i < count($sPos); $i ++)
					{
						$sSQL = "SELECT id, styles FROM tbl_po WHERE (order_no='{$sPos{$i}}' OR CONCAT(order_no, ' ', order_status)='{$sPos{$i}}') AND vendor_id='$Vendor'";

						if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
						{
							if ($i == 0)
							{
								$sOrders[0]['Po']     = $objDb->getField($i, 0);
								$sOrders[0]['Styles'] = $objDb->getField($i, 1);
							}

							else
							{
								if ($sOrders[0]['Additional'] != "")
									$sOrders[0]['Additional'] .= ",";

								$sOrders[0]['Additional'] .= $objDb->getField(0, 0);
							}
						}

						else
						{
							$bFlag    = false;
							$sMessage = (($objDb->getCount( ) > 1) ? "Multiple POs with same Order No" : "Invalid PO No");

							break;
						}
					}
				}

				else
				{
					$sSQL = "SELECT id, styles FROM tbl_po WHERE (order_no='{$OrderNo}' OR CONCAT(order_no, ' ', order_status)='{$OrderNo}') AND vendor_id='$Vendor'";
					$objDb->query($sSQL);

					$iCount = $objDb->getCount( );

					for ($i = 0; $i < $iCount; $i ++)
					{
						$sOrders[$i]['Po']         = $objDb->getField($i, 0);
						$sOrders[$i]['Styles']     = $objDb->getField($i, 1);
						$sOrders[$i]['Additional'] = "";
					}

					if ($iCount == 0)
					{
						$bFlag    = false;
						$sMessage = "Invalid PO No";
					}
				}
			}



			if ($bFlag == true)
			{
				if ($StyleNo != "" && count($sOrders) > 0)
				{
					$iStyles = array( );


					$sSQL = "SELECT id FROM tbl_styles WHERE style='{$StyleNo}'";
					$objDb->query($sSQL);

					$iCount = $objDb->getCount( );

					for ($i = 0; $i < $iCount; $i ++)
						$iStyles[$i] = $objDb->getField($i, 0);

					if ($iCount == 0)
					{
						$bFlag    = false;
						$sMessage = "Invalid Style No";
					}

					else
					{
						for ($i = 0; $i < count($sOrders); $i ++)
						{
							for ($j = 0; $j < count($iStyles); $j ++)
							{
								$sSQL = "SELECT * FROM tbl_po_colors WHERE style_id='{$iStyles[$j]}' AND FIND_IN_SET(style_id, '{$sOrders[$i]['Styles']}') AND po_id='{$sOrders[$i]['Po']}'";
								$objDb->query($sSQL);

								if ($objDb->getCount( ) > 0)
								{
									$iPoId          = $sOrders[$i]['Po'];
									$sAdditionalPos = $sOrders[$i]['Additional'];
									$iStyleId       = $iStyles[$j];

									break;
								}
							}

							if ($iPoId > 0)
								break;
						}

						if ($iPoId == 0)
						{
							$bFlag    = false;
							$sMessage = "Invalid PO - Style No Combination";
						}
					}
				}

				else if (count($sOrders) == 1)
				{
					$iPoId          = $sOrders[0]['Po'];
					$sAdditionalPos = $sOrders[0]['Additional'];
				}
			}


			if ($bFlag == true)
			{
				if (@substr($Line, 0, 2) == "0_")
				{
					$sLine = @substr($Line, 2);


					$sSQL  = "SELECT id FROM tbl_lines WHERE line LIKE '$sLine' AND vendor_id='$Vendor'";
					$objDb->query($sSQL);

					if ($objDb->getCount( ) == 0)
					{
						$iLine = getNextId("tbl_lines");

						$sSQL = "INSERT INTO tbl_lines (id, vendor_id, line) VALUES ('$iLine', '$Vendor', '$sLine')";
						$objDb->execute($sSQL, true, $User, $sUser);
					}

					else
						$iLine = $objDb->getField(0, 0);
				}

				else
					$iLine = $Line;



				$sApproved = "Y"; //((date("G") >= 10 && date("G") <= 17) ? "N" : "Y");
				$iBrand    = getDbValue("sub_brand_id", "tbl_styles", "$iStyleId");


				$bFlag = $objDb->execute("BEGIN", true, $User, $sName);

				$sSQL = "INSERT INTO tbl_qa_reports (user_id, department_id, vendor_id, brand_id, po_id, additional_pos, style_id, colors, total_gmts, audit_stage, report_id, line_id, audit_date, start_time, end_time, approved, created_at, date_time, status)
											 VALUES ('$User', '$Department', '$Vendor', '$iBrand', '$iPoId', '$sAdditionalPos', '$iStyleId', '$Colors', '$SampleSize', '$AuditStage', '$Report', '$iLine', '$AuditDate', '$StartTime', '$EndTime', '$sApproved', NOW( ), NOW( ), '')";
				$bFlag = $objDb->execute($sSQL, true, $User, $sName);

				if ($bFlag == true)
				{
					$iId        = $objDb->getAutoNumber( );
					$sAuditCode = ("S".str_pad($iId, 4, 0, STR_PAD_LEFT));

					$sSQL  = "UPDATE tbl_qa_reports SET audit_code='$sAuditCode' WHERE id='$iId'";
					$bFlag = $objDb->execute($sSQL, true, $User, $sName);
				}

				if ($bFlag == true)
				{
					$objDb->execute("COMMIT", true, $User, $sName);


					$sSQL = "SELECT * FROM tbl_users WHERE id='$User'";
					$objDb->query($sSQL);

					$sName   = $objDb->getField(0, "name");
					$sEmail  = $objDb->getField(0, "email");
					$sMobile = $objDb->getField(0, "mobile");


					if ($sApproved == "Y")
					{
						$sSQL = "SELECT vendor FROM tbl_vendors WHERE id='$Vendor'";
						$objDb->query($sSQL);

						$sVendor = $objDb->getField(0, 0);


						$sSQL = "SELECT line FROM tbl_lines WHERE id='$iLine'";
						$objDb->query($sSQL);

						$sLine = $objDb->getField(0, 0);


						$sBody = "{$sAuditCode} is in {$sVendor} Line {$sLine} from {$StartTime} to {$EndTime} on {$AuditDate}";
					}

					else
						$sBody = "Late Schedule. Please contact your Manager for Audits Schedule Approval.";


					$sSubject  = "New Audit Job";
					$sSmsEmail = ($sMobile."@sms.apparelco.com");

					// email + sms to auditor
					$objEmail = new PHPMailer( );

					$objEmail->Subject = $sSubject;
					$objEmail->Body    = $sBody;

					$objEmail->IsHTML(false);

					if ($sEmail != "")
					{
						$objEmail->AddAddress($sEmail, $sName);
						$objEmail->Send( );
					}


					$objSms = new Sms( );
					$objSms->send($sMobile, "", $sBody, $sSubject);
					$objSms->close( );


					$aResponse['Status']  = "OK";
					$aResponse['Message'] = "Audit Scheduled Successfully!";
				}

				else
				{
					$objDb->execute("ROLLBACK", true, $User, $sName);


					$aResponse['Status'] = "ERROR";
					$aResponse['Error']  = $sMessage;
				}
			}

			else
			{
				$aResponse['Status'] = "ERROR";
				$aResponse['Error']  = (($sMessage != "") ? $sMessage : "An ERROR occured while processing your request. Please try again.");
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
	$objDbGlobal->close( );

	@ob_end_flush( );
?>