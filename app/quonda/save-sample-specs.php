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
	$AuditCode = IO::strValue("AuditCode");
	$Size      = IO::intValue('Size');
	$Color     = IO::strValue('Color');
	$DateTime  = IO::strValue('DateTime');


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "" || $AuditCode == "" || $AuditCode{0} != "S" || $Size == 0)
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


			$iAuditCode    = (int)substr($AuditCode, 1);
			$DateTime      = (($DateTime == "") ? date("Y-m-d H:i:s") : $DateTime);
			$sSize         = getDbValue("size", "tbl_sizes", "id='$Size'");
			$iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '$sSize'");


			if (getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$iAuditCode' AND size_id='$iSamplingSize' AND color LIKE '$Color' AND date_time='$DateTime'") == 0)
			{
				$iStyle      = getDbValue("style_id", "tbl_qa_reports", "id='$iAuditCode'");
				$sPointsList = getList("tbl_style_specs", "point_id", "point_id", "style_id='$iStyle' AND size_id='$iSamplingSize' AND version='0'");
				$iSampleNo   = (getDbValue("COALESCE(MAX(sample_no), '0')", "tbl_qa_report_samples", "audit_id='$iAuditCode' AND size_id='$iSamplingSize' AND color LIKE '$Color'") + 1);


				$objDb->execute("BEGIN", true, $iUser, $sName);

				$iSampleId  = getNextId("tbl_qa_report_samples");

				$sSQL  = "INSERT INTO tbl_qa_report_samples (id, audit_id, size_id, color, sample_no, date_time) VALUES ('$iSampleId', '$iAuditCode', '$iSamplingSize', '$Color', '$iSampleNo', '$DateTime')";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);

				if ($bFlag == true)
				{
					foreach ($sPointsList as $iPoint)
					{
						$sFindings = IO::strValue("Point{$iPoint}");

						if (trim($sFindings) == "")
							$sFindings = "ok";


						$sSQL  = "INSERT INTO tbl_qa_report_sample_specs (sample_id, point_id, findings) VALUES ('$iSampleId', '$iPoint', '$sFindings')";
						$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);

						if ($bFlag == false)
							break;
					}
				}

				if ($bFlag == true)
				{
					$objDb->execute("COMMIT", true, $iUser, $sName);

					$aResponse['Status']  = "OK";
					$aResponse["Message"] = "Measurement Specs Saved Successfully!";
				}

				else
				{
					$objDb->execute("ROLLBACK", true, $iUser, $sName);

					$aResponse["Message"] = "An ERROR occured, please try again.";
				}
			}

			else
			{
				$aResponse['Status']  = "OK";
				$aResponse["Message"] = "Measurement Specs Saved Successfully!";
			}
		}
	}

	print @json_encode($aResponse);

/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = $Cap."\n\n".@json_encode($aResponse)."<bR>".$sSQL;

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>