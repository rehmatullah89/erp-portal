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
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	$User         = IO::strValue('User');
	$RequestCode  = IO::strValue('RequestCode');
	$Merchant     = IO::strValue("Merchant");
	$Specs        = IO::strValue("Specs");
	$Construction = IO::strValue("Construction");
	$Fitting      = IO::strValue("Fitting");
	$Suggestions  = IO::strValue("Suggestions");
	$Status       = IO::strValue("Status");
	$iRequestCode = intval(substr($RequestCode, 1));

	$aResponse = array( );


	if ($User == 0)
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid User ID";
	}

	else if ($iRequestCode == 0 || strlen($RequestCode) == 0 || $RequestCode{0} != "M")
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid Request Code";
	}

	else
	{
		$sUser = getDbValue("name", "tbl_users", "id='$User'");


		$sSQL = "SELECT * FROM tbl_comment_sheets WHERE merchandising_id='$iRequestCode'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "No Report Request Found!";
		}

		else
		{
			$iStyleId   = getDbValue("style_id", "tbl_merchandisings", "id='$iRequestCode'");
			$iBrandId   = getDbValue("sub_brand_id", "tbl_styles", "id='$iStyleId'");
			$sOldStatus = getDbValue("status", "tbl_merchandisings", "id='$iRequestCode'");


			$objDb->execute("BEGIN", true, $User, $sUser);

			$sSQL = "UPDATE tbl_comment_sheets SET merch_comments   = '$Merchant',
	                                               spec_comments    = '$Specs',
	                                               other_comments   = '$Construction',
	                                               fitting_comments = '$Fitting',
	                                               note_suggestions = '$Suggestions'
	                 WHERE merchandising_id='$iRequestCode'";
			$bFlag = $objDb->execute($sSQL, true, $User, $sUser);

			if ($bFlag == true)
			{
				$sSQL  = "UPDATE tbl_merchandisings SET status='$Status' WHERE id='$iRequestCode'";
				$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
			}

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_ms_sampling WHERE merchandising_id='$iRequestCode'";
				$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
			}

			if ($bFlag == true && $iBrandId == 124)
			{
				$sSQL = "INSERT INTO tbl_ms_sampling SET merchandising_id      = '$iRequestCode',
														 department            = '',
														 stroke_no             = '',
														 description           = '',
														 block_ref             = '',
														 amendments            = '',
														 models                = '',
														 supplier              = '',
														 fabric_quality        = '',
														 bulk_cut_date         = '',
														 factory               = '',
														 supplier_technologist = '',
														 ms_technologist       = ''";
				$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
			}

			if ($bFlag == true && $Merchant != "")
			{
				$sSQL = ("SELECT * FROM tbl_style_comments WHERE style_id='$iStyleId' AND merchandising_id='$iRequestCode' AND stage='Tech Pack' AND `from`='Merchandiser' AND nature='Merchant Comments' AND comments LIKE '{$Merchant}'");
				$objDb->query($sSQL);

				if ($objDb->getCount( ) == 0)
				{
					$iId = getNextId("tbl_style_comments");

					$sSQL  = ("INSERT INTO tbl_style_comments (id, style_id, merchandising_id, stage, `from`, `date`, nature, comments, user_id, date_time)
													   VALUES ('$iId', '$iStyleId', '$iRequestCode', 'Tech Pack', 'Merchandiser', NOW( ), 'Merchant Comments', '{$Merchant}', '$User', NOW( ))");
					$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
				}
			}

			if ($bFlag == true && $Specs != "")
			{
				$sSQL = ("SELECT * FROM tbl_style_comments WHERE style_id='$iStyleId' AND merchandising_id='$iRequestCode' AND stage='Tech Pack' AND `from`='Sampling Technician' AND nature='Spec Comments' AND comments LIKE '{$Specs}'");
				$objDb->query($sSQL);

				if ($objDb->getCount( ) == 0)
				{
					$iId = getNextId("tbl_style_comments");

					$sSQL  = ("INSERT INTO tbl_style_comments (id, style_id, merchandising_id, stage, `from`, `date`, nature, comments, user_id, date_time)
													   VALUES ('$iId', '$iStyleId', '$iRequestCode', 'Tech Pack', 'Sampling Technician', NOW( ), 'Spec Comments', '{$Specs}', '$User', NOW( ))");
					$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
				}
			}

			if ($bFlag == true && $Construction != "")
			{
				$sSQL = ("SELECT * FROM tbl_style_comments WHERE style_id='$iStyleId' AND merchandising_id='$iRequestCode' AND stage='Tech Pack' AND `from`='Sampling Technician' AND nature='Constructions/Quality/Workmanship' AND comments LIKE '{$Construction}'");
				$objDb->query($sSQL);

				if ($objDb->getCount( ) == 0)
				{
					$iId = getNextId("tbl_style_comments");

					$sSQL  = ("INSERT INTO tbl_style_comments (id, style_id, merchandising_id, stage, `from`, `date`, nature, comments, user_id, date_time)
													   VALUES ('$iId', '$iStyleId', '$iRequestCode', 'Tech Pack', 'Sampling Technician', NOW( ), 'Constructions/Quality/Workmanship', '{$Construction}', '$User', NOW( ))");
					$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
				}
			}

			if ($bFlag == true && $Fitting != "")
			{
				$sSQL = ("SELECT * FROM tbl_style_comments WHERE style_id='$iStyleId' AND merchandising_id='$iRequestCode' AND stage='Tech Pack' AND `from`='Sampling Technician' AND nature='Fitting Comments' AND comments LIKE '{$Fitting}'");
				$objDb->query($sSQL);

				if ($objDb->getCount( ) == 0)
				{
					$iId = getNextId("tbl_style_comments");

					$sSQL  = ("INSERT INTO tbl_style_comments (id, style_id, merchandising_id, stage, `from`, `date`, nature, comments, user_id, date_time)
													   VALUES ('$iId', '$iStyleId', '$iRequestCode', 'Tech Pack', 'Sampling Technician', NOW( ), 'Fitting Comments', '{$Fitting}', '$User', NOW( ))");
					$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
				}
			}

			if ($bFlag == true && $Suggestions != "")
			{
				$sSQL = ("SELECT * FROM tbl_style_comments WHERE style_id='$iStyleId' AND merchandising_id='$iRequestCode' AND stage='Tech Pack' AND `from`='Sampling Technician' AND nature='Note/Suggestions' AND comments LIKE '{$Suggestions}'");
				$objDb->query($sSQL);

				if ($objDb->getCount( ) == 0)
				{
					$iId = getNextId("tbl_style_comments");

					$sSQL  = ("INSERT INTO tbl_style_comments (id, style_id, merchandising_id, stage, `from`, `date`, nature, comments, user_id, date_time)
													   VALUES ('$iId', '$iStyleId', '$iRequestCode', 'Tech Pack', 'Sampling Technician', NOW( ), 'Note/Suggestions', '{$Suggestions}', '$User', NOW( ))");
					$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
				}
			}

			if ($bFlag == true)
			{
				$objDb->execute("COMMIT", true, $User, $sUser);


				// Sampling Audit
				if ($sOldStatus != $Status && $Status != "W")
				{
					$Id = $iRequestCode;

					@include($sBaseDir."includes/sampling/sampling-audit-notification.php");
				}


				$aResponse['Status']  = "OK";
				$aResponse["Message"] = "Report Saved Successfully!";
			}

			else
			{
				$objDb->execute("ROLLBACK", true, $User, $sUser);

				$aResponse['Status'] = "ERROR";
				$aResponse["Error"]  = "Unable to Save the Report";
			}

		}
	}


	print @json_encode($aResponse);

/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = $sSQL;

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/

	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>