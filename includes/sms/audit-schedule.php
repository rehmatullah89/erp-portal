<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  MATRIX Customer Portal                                                                   **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.apparelco.com                                                              **
	**                                                                                           **
	**  Copyright 2008-15 (C) Matrix Sourcing                                                    **
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

	$iSmsHr = date("G");

	if ($bDebug == true)
	{
		print ("<b>*** Audit Schedule SMS ***</b><br />");
		print ($sSms."<br /><br />");
	}


	$sMobile = str_replace("+92", "", $sSender);
	$sMobile = str_replace("0092", "", $sMobile);
	$sMobile = str_replace("+88", "", $sMobile);
	$sMobile = str_replace("0088", "", $sMobile);

	$sSQL = "SELECT id, name, email FROM tbl_users WHERE mobile LIKE '%$sMobile' AND status='A'";
	$objDb->query($sSQL);

	if ($bDebug == true)
		print ('<span style="color:#666666;">'.$sSQL.'</span><br /><br />');

	if ($objDb->getCount( ) == 1)
	{
		$iAuditorId    = $objDb->getField(0, 'id');
		$sAuditorEmail = $objDb->getField(0, 'email');
		$sAuditorName  = $objDb->getField(0, 'name');


		$sSms            = trim(@substr($sSms, 7));
		$sSms            = str_replace('\n', " ", $sSms);
		$sAuditRequests  = @explode(" ", $sSms);
		$iLastLocationId = 0;
		$iGroupId        = 0;
		$iDepartmentId   = 0;
		$bLunch          = false;
		$bFlag           = true;
		$sAuditSms       = array( );

//		if (@strlen($sAuditRequests[0]) <= 5 && @strpos($sAuditRequests[0], ":") === FALSE)
		{
			$iDepartmentId = getDbValue("id", "tbl_departments", "`code` LIKE '{$sAuditRequests[0]}'");

			@array_shift($sAuditRequests);
		}

		if ($iDepartmentId == 0)
		{
			$sBody = "Invalid Department";
			$bFlag = false;
		}


		@list($sStartTime, $sGroup) = @explode(",", $sAuditRequests[0]);
		@list($sHours, $sMinutes)   = @explode(":", @trim($sStartTime));

		$sGroup     = @trim($sGroup);
		$iHours     = (int)$sHours;
		$iMinutes   = (int)$sMinutes;
		$iTime      = (($iHours * 60) + $iMinutes);
		$sAuditDate = date("Y-m-d");

		if ($iTime == 0)
		{
			$sBody = "Invalid Audit Start Time";
			$bFlag = false;
		}

		if ($iTime >= 720)
			$bLunch = true;

		if ($bFlag == true && $sGroup != "")
		{
			$sSQL = "SELECT id FROM tbl_auditor_groups WHERE code LIKE '$sGroup' LIMIT 1";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 1)
				$iGroupId = $objDb->getField(0, 0);
		}


		$objDb->execute("BEGIN");

		for ($i = 1; ($i < count($sAuditRequests) && $bFlag == true) ; $i ++)
		{
			if ($bDebug == true)
				print ('<b>'.$sAuditRequests[$i].'</b><br />');


			@list($sVendorCode, $sReportCode, $sLine, $iQuantity, $sAuditStage) = @explode(",", $sAuditRequests[$i]);

			$sVendorCode = trim($sVendorCode);
			$sReportCode = trim($sReportCode);
			$sLine       = trim($sLine);
			$iQuantity   = intval(trim($iQuantity));
			$sAuditStage = strtoupper(trim($sAuditStage));


			if ($sLine == "")
			{
				$sBody = "Invalid Audit Line, Review your SMS";
				$bFlag = false;

				break;
			}


			if ($iQuantity == 0)
			{
				$sBody = "Invalid Audit Quantity, Review your SMS";
				$bFlag = false;

				break;
			}


			if ($sAuditStage == "" || !@in_array($sAuditStage, $sAuditStages))
			{
				$sBody = "Invalid Audit Stage, Review your SMS";
				$bFlag = false;

				break;
			}


			$sSQL = "SELECT id, vendor, unit_audit_time FROM tbl_vendors WHERE code LIKE '$sVendorCode' AND parent_id='0' AND sourcing='Y'";
			$objDb->query($sSQL);

			if ($bDebug == true)
				print ('<span style="color:#666666;">'.$sSQL.'</span><br />');

			if ($objDb->getCount( ) == 0)
			{
				$sBody = "Invalid Vendor Code: $sVendorCode";
				$bFlag = false;

				break;
			}

			$iVendorId = $objDb->getField(0, 'id');
			$sVendor   = $objDb->getField(0, 'vendor');
			$iUnitTime = $objDb->getField(0, 'unit_audit_time');


			// Location Travel Time Adjustment
			$sSQL = "SELECT id FROM tbl_visit_location WHERE code LIKE '$sVendorCode'";
			$objDb->query($sSQL);

			if ($bDebug == true)
				print ('<span style="color:#666666;">'.$sSQL.'</span><br />');

			if ($objDb->getCount( ) == 1)
				$iLocationId = $objDb->getField(0, 0);

			else
				$iLocationId = -1;


			if ($iLastLocationId != 0)
			{
				if ($iLastLocationId == $iLocationId)
					$iTime += 5;

				else if ($iLastLocationId == -1 || $iLocationId == -1)
					$iTime += 60;

				else
				{
					$sSQL = "SELECT distance FROM tbl_visit_locations_distance WHERE from_location_id='$iLastLocationId' AND to_location_id='$iLocationId'";
					$objDb->query($sSQL);

					if ($bDebug == true)
						print ('<span style="color:#666666;">'.$sSQL.'</span><br />');

					if ($objDb->getCount( ) == 1)
					{
						$fDistance = $objDb->getField(0, 0);

						$iTime += ($fDistance * 5);
					}

					else
						$iTime += 60;
				}
			}


			// Lunch Time Adjustment
			if ($bLunch == false && $iTime >= 780 && $iTime <= 1080)
			{
				$iTime += 60;
				$bLunch = true;
			}


			$sSQL = "SELECT id FROM tbl_reports WHERE code LIKE '$sReportCode'";
			$objDb->query($sSQL);

			if ($bDebug == true)
				print ('<span style="color:#666666;">'.$sSQL.'</span><br />');

			if ($objDb->getCount( ) == 0)
			{
				$sBody = "Invalid Report Type Code: $sReportCode";
				$bFlag = false;

				break;
			}

			$iReportId = $objDb->getField(0, 'id');


			$sSQL = "SELECT id FROM tbl_lines WHERE vendor_id='$iVendorId' AND line LIKE '$sLine'";
			$objDb->query($sSQL);

			if ($bDebug == true)
				print ('<span style="color:#666666;">'.$sSQL.'</span><br />');

			if ($objDb->getCount( ) == 0)
			{
				$iLineId = getNextId("tbl_lines");

				$sSQL = "INSERT INTO tbl_lines (id, vendor_id, line) VALUES ('$iLineId', '$iVendorId', '$sLine')";

				if ($bDebug == true)
					print ('<span style="color:#666666;">'.$sSQL.'</span><br />');

				if ($objDb->execute($sSQL) == false)
				{
					$sBody = "Line not found. An ERROR occured while Creating a new Line: $sLine";
					$bFlag = false;

					break;
				}
			}

			else
				$iLineId = $objDb->getField(0, 'id');



			$iAuditId = getNextId("tbl_qa_reports");


			if ($iTime > 1440)
			{
				$iTime     -= 1440;
				$sAuditDate = date("Y-m-d", (strtotime($sAuditDate) + 86400));
			}


			// Locking Table
			$objDb->execute("LOCK TABLE tbl_qa_reports READ, tbl_qa_reports AS tmp_qa_reports WRITE");

			$sAuditCode = ("S".str_pad($iAuditId, 4, 0, STR_PAD_LEFT));
			$sHours     = @str_pad(floor($iTime / 60), 2, '0', STR_PAD_LEFT);
			$sMinutes   = @str_pad(floor($iTime % 60), 2, '0', STR_PAD_LEFT);
			$sStartTime = "$sHours:$sMinutes:00";

			if ($iTime > 719)
			{
				$sHours = @str_pad(floor(($iTime - 720) / 60), 2, '0', STR_PAD_LEFT);

				if ($sHours == "00")
					$sHours = "12";

				$sSmsStartTime = "$sHours:$sMinutes PM";
			}

			else
				$sSmsStartTime = "$sHours:$sMinutes AM";


			if ($iUnitTime == 0)
				$iUnitTime = 2;

			$iAuditTime = @ceil($iQuantity * $iUnitTime);

			$iTime += $iAuditTime;

			$sHours   = @str_pad(floor($iTime / 60), 2, '0', STR_PAD_LEFT);
			$sMinutes = @str_pad(floor($iTime % 60), 2, '0', STR_PAD_LEFT);
			$sEndTime = "$sHours:$sMinutes:00";

			if ($iTime > 719)
			{
				$sHours = @str_pad(floor(($iTime - 720) / 60), 2, '0', STR_PAD_LEFT);

				if ($sHours == "00")
					$sHours = "12";

				$sSmsEndTime = "$sHours:$sMinutes PM";
			}

			else
				$sSmsEndTime = "$sHours:$sMinutes AM";


			// Releasing Locks
			$objDb->execute("UNLOCK TABLES");


			$sApproved = "Y"; //(($iSmsHr >= 10 && $iSmsHr <= 17) ? "N" : "Y");


			$sSQL  = "INSERT INTO tbl_qa_reports (id, audit_code, user_id, group_id, department_id, vendor_id, report_id, line_id, audit_stage, audit_date, start_time, end_time, total_gmts, approved, created_at, date_time, status)
			                              VALUES ('$iAuditId', '$sAuditCode', '$iAuditorId', '$iGroupId', '$iDepartmentId', '$iVendorId', '$iReportId', '$iLineId', '$sAuditStage', '$sAuditDate', '$sStartTime', '$sEndTime', '$iQuantity', '$sApproved', NOW( ), NOW( ), '')";
			$bFlag = $objDb->execute($sSQL);

			if ($bDebug == true)
				print ('<span style="color:#666666;">'.$sSQL.'</span><br /><br />');

			if ($bFlag == false)
				break;

			$iLastLocationId = $iLocationId;


			$sAuditSms[] = "$sAuditCode is in $sVendor Line $sLine from $sSmsStartTime to $sSmsEndTime on $sAuditDate";
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");


			if ($iSmsHr >= 10 && $iSmsHr <= 17)
				$sBody = "Late Schedule. Please contact your Manager for Audits Schedule Approval.";

			else
				$sBody = "Your Audits have been Scheduled successfully.";
		}

		else
			$objDb->execute("ROLLBACK");
	}

	else
		$sBody = "No Auditor found in the System.";


	$sStatus = $objSms->send($sSender, $sAuditorName, $sBody, "*** Audit Schedule SMS ***", true);


	if ($bDebug == true)
	{
		print ("<b>SMS Results:</b> ".$sBody."<br />");
		print ("Mobile: ".$sSender."<br />");
		print ("Mail Status: ".$sStatus."<br />");
	}


	if ($bFlag == true && ($iSmsHr < 10 || $iSmsHr > 17))
	{
		for ($i = 0; $i < count($sAuditSms); $i ++)
		{
			$objSms->send($sSender, $sAuditorName, $sAuditSms[$i], "*** New Audit Job ***", true);


			$objEmail = new PHPMailer( );

			$objEmail->Subject = "New Audit Job";
			$objEmail->Body    = $sAuditSms[$i];

			$objEmail->IsHTML(false);
			$objEmail->AddAddress($sAuditorEmail, $sAuditorName);

			$objEmail->Send( );
		}
	}
?>