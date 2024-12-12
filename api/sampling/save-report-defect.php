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


	$User        = IO::intValue('User');
	$RequestCode = IO::strValue("RequestCode");
	$DefectCode  = IO::strValue("DefectCode");
	$DefectArea  = IO::strValue("DefectArea");
	$Defects     = IO::intValue("Defects");

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
		$sStatus = getDbValue("status", "tbl_users", "id='$User'");

		if ($sStatus != "A")
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "User Account is Disabled";
		}

		else if ($DefectCode == 0 || $Defects == 0)
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "Invalid Defect Saving Request";
		}

		else
		{
			$iId = getNextId("tbl_sampling_report_defects");


			$sSQL = "INSERT INTO tbl_sampling_report_defects (id, merchandising_id, code_id, defects, area_id) VALUES ('$iId', '$iRequestCode', '$DefectCode', '$Defects', '$DefectArea')";

			if ($objDb->execute($sSQL, true, $User, getDbValue("name", "tbl_users", "id='$User'")) == true)
			{
				$aResponse['Status']  = "OK";
				$aResponse["Message"] = "Defect Saved Successfully!";
			}

			else
			{
				$aResponse['Status'] = "ERROR";
				$aResponse["Error"]  = "An ERROR occured, please try again.";
			}
		}
	}

	print @json_encode($aResponse);


/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = @json_encode($aResponse)." - {$iSampleSize} - {$iDefects} <br><br>".$sSQL;

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>