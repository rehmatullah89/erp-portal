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

	$User      = IO::intValue('User');
	$AuditCode = IO::strValue("AuditCode");
	$AuditResult = IO::strValue("AuditResult");

	$iAuditCode = intval(substr($AuditCode, 1));


	$aResponse = array( );


	if ($User == 0)
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid User ID";
	}

	else if ($iAuditCode == 0 || strlen($AuditCode) == 0 || $AuditCode{0} != "S")
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid Audit Code";
	}

	else
	{
		$sStatus = getDbValue("status", "tbl_users", "id='$User'");

		if ($sStatus != "A")
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "User Account is Disabled";
		}

		else
		{
			$sUser = getDbValue("name", "tbl_users", "id='$User'");

			$sSQL="";
			$today = date("Y-m-d H:i:s");

			if(empty($AuditResult)){

				$sSQL = "UPDATE tbl_qa_reports  SET start_date_time = '$today' WHERE id = '$iAuditCode'";

			}else{

				$sSQL = "UPDATE  tbl_qa_reports SET end_date_time = '$today' , audit_result= '$AuditResult'
								WHERE id = '$iAuditCode'";
			}

			//print $sSQL; exit;
			$bFlag = $objDb->execute($sSQL, true, $User, $sUser);

			//$bFlag = $objDb->execute($sSQL);


			if ($bFlag == true)
			{
			$aResponse['Status'] = "OK";
			$aResponse["Message"]  = "Audit Report Updated";

			}else{

			$aResponse['Status'] = "ERROR";
			$aResponse["Message"]  = "Can not update audit Report";
			}

		}
	}


	print @json_encode($aResponse);

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>