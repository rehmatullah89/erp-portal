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

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$Id = IO::intValue("Id");


	$sSQL = "SELECT * FROM tbl_audit_schedules WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		header("Location: {$_SERVER['HTTP_REFERER']}");
		exit( );
	}


	$iAuditor    = $objDb->getField(0, 'user_id');
	$iGroup      = $objDb->getField(0, 'group_id');
	$iVendor     = $objDb->getField(0, 'vendor_id');
	$iReport     = $objDb->getField(0, 'report_id');
	$iLine       = $objDb->getField(0, 'line_id');
	$sAuditDate  = $objDb->getField(0, 'audit_date');
	$sStartTime  = $objDb->getField(0, 'start_time');
	$sEndTime    = $objDb->getField(0, 'end_time');
	$iPoId       = $objDb->getField(0, 'po_id');
	$iStyleId    = $objDb->getField(0, 'style_id');
	$sColors     = $objDb->getField(0, 'colors');
	$iSampleSize = $objDb->getField(0, 'sample_size');



	$iId = getNextId("tbl_qa_reports");

	$sAuditCode = ("S".str_pad($iId, 4, 0, STR_PAD_LEFT));




	$sSQL = "INSERT INTO tbl_qa_reports (id, audit_code, user_id, group_id, vendor_id, po_id, style_id, colors, total_gmts, report_id, line_id, audit_date, start_time, end_time, approved, date_time)
	                             VALUES ('$iId', '$sAuditCode', '$iAuditor', '$iGroup', '$iVendor', '$iPoId', '$iStyleId', '$sColors', '$iSampleSize', '$iReport', '$iLine', '$sAuditDate', '$sStartTime', '$sEndTime', 'Y', NOW( ))";

	if ($objDb->execute($sSQL) == true)
	{
		$sSQL = "UPDATE tbl_audit_schedules SET status='A' WHERE id='$Id'";
		$objDb->execute($sSQL);


		$_SESSION['Flag'] = "AUDIT_SCHEDULE_CONFIRMED";


		$sSQL = "SELECT * FROM tbl_users WHERE id='$iAuditor'";
		$objDb->query($sSQL);

		$sName   = $objDb->getField(0, "name");
		$sEmail  = $objDb->getField(0, "email");
		$sMobile = $objDb->getField(0, "mobile");


		$sSQL = "SELECT vendor FROM tbl_vendors WHERE id='$iVendor'";
		$objDb->query($sSQL);

		$sVendor = $objDb->getField(0, 0);


		$sSQL = "SELECT line FROM tbl_lines WHERE id='$iLine'";
		$objDb->query($sSQL);

		$sLine = $objDb->getField(0, 0);

		$sStartTime = formatTime($sStartTime);
		$sEndTime   = formatTime($sEndTime);


		$sBody    = ("$sAuditCode is in $sVendor Line $sLine from $sStartTime to $sEndTime on ".formatDate($sAuditDate));
		$sSubject = "New Audit Job";

		// email + sms to auditor
		$objEmail = new PHPMailer( );

		$objEmail->Subject = $sSubject;
		$objEmail->Body    = $sBody;

		$objEmail->IsHTML(false);

		if ($sEmail != "")
		{
			$objEmail->AddAddress($sEmail, $sName);

			if ($iGroup > 0)
			{
				$sSQL = "SELECT users FROM tbl_auditor_groups WHERE id='$iGroup'";
				$objDb->query($sSQL);

				$sGroup = $objDb->getField(0, 0);


				$sSQL = "SELECT name, email FROM tbl_users WHERE id IN ($sGroup) AND id!='$iAuditor' AND status='A' AND email_alerts='Y'";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
				{
					$sMemberName  = $objDb->getField($i, "name");
					$sMemberEmail = $objDb->getField($i, "email");

					$objEmail->AddAddress($sMemberEmail, $sMemberName);
				}
			}

			$objEmail->Send( );



			$objSms = new Sms( );
			$objSms->send($sMobile, "", $sBody, $sSubject);
			$objSms->close( );

			header("Location: {$_SERVER['HTTP_REFERER']}");
			exit( );
		}

		else
			$_SESSION['Flag'] = "DB_ERROR";
	}


	header("Location: {$_SERVER['HTTP_REFERER']}");


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>