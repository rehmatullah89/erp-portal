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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/session.php");

	if ($sUserRights['Edit'] != "Y")
	{
		print "ERROR|-|You havn\'t enough Rights to modify the selected Data.\n";
		exit( );
	}

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$AuditCode = IO::intValue("AuditCode");

	$sSQL = "SELECT audit_code FROM tbl_qa_reports WHERE id='$AuditCode'";
	$objDb->query($sSQL);

	if ($AuditCode == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Audit ID. Please select the proper Audit to Edit.\n";
		exit( );
	}


	$sSQL = "UPDATE tbl_qa_reports SET approved='Y' WHERE id='$AuditCode'";

	if ($objDb->execute($sSQL) == true)
	{
		$sSQL = "SELECT * FROM tbl_qa_reports WHERE id='$AuditCode'";
		$objDb->query($sSQL);

		$sAuditCode = $objDb->getField(0, "audit_code");
		$sAuditDate = $objDb->getField(0, "audit_date");
		$iVendor    = $objDb->getField(0, "vendor_id");
		$iLine      = $objDb->getField(0, "line_id");
		$iUser      = $objDb->getField(0, "user_id");
		$iGroup     = $objDb->getField(0, "group_id");
		$sStartTime = formatTime($objDb->getField(0, "start_time"));
		$sEndTime   = formatTime($objDb->getField(0, "end_time"));


		$sSQL = "SELECT * FROM tbl_users WHERE id='$iUser'";
		$objDb->query($sSQL);

		$sName   = $objDb->getField(0, "name");
		$sEmail  = $objDb->getField(0, "email");
		$sMobile = $objDb->getField(0, "mobile");


		if ($iGroup > 0)
		{
			$sSQL = "SELECT users FROM tbl_auditor_groups WHERE id='$iGroup'";
			$objDb->query($sSQL);

			$sGroup = $objDb->getField(0, 0);
		}


		$sVendor = getDbValue("vendor", "tbl_vendors", "id='$iVendor'");
		$sLine   = getDbValue("line", "tbl_lines", "id='$iLine'");

		$sBody    = "$sAuditCode is in $sVendor Line $sLine from $sStartTime to $sEndTime on $sAuditDate";
		$sSubject = "*** New Audit Job ***";


		// email + sms to auditor
		$objEmail = new PHPMailer( );

		$objEmail->Subject = $sSubject;
		$objEmail->Body    = $sBody;

		$objEmail->IsHTML(false);

		if ($sEmail != "")
		{
			$objEmail->AddAddress($sEmail, $sName);

			if ($sGroup != "")
			{
				$sSQL = "SELECT name, email FROM tbl_users WHERE id IN ($sGroup) AND id!='$iUser' AND status='A' AND email_alerts='Y'";
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
		}


		$objSms = new Sms( );
		$objSms->send($sMobile, "", $sBody, $sSubject);
		$objSms->close( );


		print "OK|-|$AuditCode";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>