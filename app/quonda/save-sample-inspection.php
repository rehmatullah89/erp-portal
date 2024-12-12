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


	$User      = IO::strValue('User');
	$AuditCode = IO::strValue('AuditCode');

	if (@is_array($_REQUEST["SampleNo"]))
	{
		$SampleNo    = IO::getArray('SampleNo');
		$SampleColor = IO::getArray('SampleColor');
		$DateTime    = IO::getArray('DateTime');
	}

	else
	{
		$SampleNo    = IO::intValue('SampleNo');
		$SampleColor = IO::strValue('SampleColor');
		$DateTime    = IO::strValue('DateTime');
	}


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "" || $AuditCode == "" || $AuditCode{0} != "S" || (!@is_array($SampleNo) && ($SampleNo == 0 || $DateTime == "")))
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, status, guest FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else if ((int)getDbValue("COUNT(1)", "tbl_qa_reports", "audit_code='$AuditCode'") == 0)
			$aResponse["Message"] = "Invalid Request, The selected Audit Code has been Deleted.";

		else
		{
			$iUser  = $objDb->getField(0, "id");
			$sName  = $objDb->getField(0, "name");
			$sGuest = $objDb->getField(0, "guest");


			$iAuditCode = (int)substr($AuditCode, 1);


			$bFlag = $objDb->execute("BEGIN", false, $iUser, $sName);;

			if (@is_array($SampleNo))
			{
				for ($i = 0; $i < count($SampleNo); $i ++)
				{
					if ($SampleNo[$i] <= 1)
					{
						$sStartTime = substr($DateTime[$i], 11);


						$sSQL  = "UPDATE tbl_qa_reports SET start_time='$sStartTime', start_date_time='{$DateTime[$i]}' WHERE id='$iAuditCode' AND (start_date_time='0000-00-00 00:00:00' OR start_date_time>'{$DateTime[$i]}')";
						$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
					}

					if ($bFlag == true)
					{
						if (getDbValue("COUNT(1)", "tbl_qa_report_progress", "audit_id='$iAuditCode' AND sample_no='{$SampleNo[$i]}'") == 0)
						{
							$sSQL  = "INSERT INTO tbl_qa_report_progress SET audit_id  = '$iAuditCode',
																			 sample_no = '{$SampleNo[$i]}',
																			 color     = '{$SampleColor[$i]}',
																			 date_time = '{$DateTime[$i]}'";
						}

						else
							$sSQL  = "UPDATE tbl_qa_report_progress SET date_time='{$DateTime[$i]}' WHERE audit_id='$iAuditCode' AND sample_no='{$SampleNo[$i]}'";

						$bFlag = $objDb->execute($sSQL, false, $iUser, $sName);
					}


					if ($bFlag == false)
						break;
				}
			}

			else
			{
				if ($SampleNo <= 1)
				{
					$sStartTime = substr($DateTime, 11);


					$sSQL  = "UPDATE tbl_qa_reports SET start_time='$sStartTime', start_date_time='$DateTime' WHERE id='$iAuditCode' AND (start_date_time='0000-00-00 00:00:00' OR start_date_time>'$DateTime')";
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}

				if ($bFlag == true)
				{
					if (getDbValue("COUNT(1)", "tbl_qa_report_progress", "audit_id='$iAuditCode' AND sample_no='$SampleNo'") == 0)
					{
						$sSQL  = "INSERT INTO tbl_qa_report_progress SET audit_id  = '$iAuditCode',
																		 sample_no = '$SampleNo',
																		 color     = '$SampleColor',
																		 date_time = '$DateTime'";
					}

					else
						$sSQL  = "UPDATE tbl_qa_report_progress SET date_time='$DateTime' WHERE audit_id='$iAuditCode' AND sample_no='$SampleNo'";

					$bFlag = $objDb->execute($sSQL, false, $iUser, $sName);
				}
			}

			if ($bFlag == true)
			{
				$objDb->execute("COMMIT", false, $iUser, $sName);

				$aResponse['Status']  = "OK";
				$aResponse['Message'] = "Sample Inspection Saved successfully.";
			}

			else
			{
				$aResponse['Message'] = mysql_error(); //"An ERROR occured, please re-try";
				
				$objDb->execute("ROLLBACK", false, $iUser, $sName);
			}
		}
	}


	print @json_encode($aResponse);


/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = @json_encode($aResponse)."\n\n".$sSQL."\n\n".mysql_error();

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>