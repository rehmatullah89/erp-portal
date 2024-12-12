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

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$User      = IO::strValue('User');
	$AuditCode = IO::strValue("AuditCode");
	$Defect    = IO::intValue("Defect");

	$iAuditCode = intval(substr($AuditCode, 1));


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "" || $AuditCode == "" || $iAuditCode == 0 || $AuditCode{0} != "S" || $Defect == 0)
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, status FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else
		{
			$iUser = $objDb->getField(0, "id");
			$sName = $objDb->getField(0, "name");


			$sAuditDate = getDbValue("audit_date", "tbl_qa_reports", "id='$iAuditCode'");
			
			$sSQL = "SELECT area_id,
			                (SELECT code FROM tbl_defect_codes WHERE id=tbl_qa_report_defects.code_id) AS _Code
					 FROM tbl_qa_report_defects
					 WHERE audit_id='$iAuditCode' AND id='$Defect'";
			$objDb->query($sSQL);
			
			if ($objDb->getCount( ) == 1)
			{
				$iDefectArea = $objDb->getField(0, "area_id");
				$sDefectCode = $objDb->getField(0, "_Code");
				
				
				$sSQL = "DELETE FROM tbl_qa_report_defects WHERE audit_id='$iAuditCode' AND id='$Defect'";
				
				if ($objDb->execute($sSQL, true, $iUser, $sName) == true)
				{
					@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);
					
					$sQuondaDir = (ABSOLUTE_PATH.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");
					$sAreaCode  = str_pad($iDefectArea, 2, '0', STR_PAD_LEFT);
					
					$sAuditPictures = @glob("{$sQuondaDir}?".substr($AuditCode, 1)."_{$sDefectCode}_{$sAreaCode}*.*");
		
					foreach ($sAuditPictures as $sPicture)
					{					
						$sPicture = @basename($sPicture);
						
						
						@unlink($sQuondaDir.$sPicture);
						@unlink($sQuondaDir."thumbs/".$sPicture);
					}
					
					
					$aResponse['Status']  = "OK";
					$aResponse['Message'] = "The selected Defect has been Deleted successfully.";
				}
				
				else
					$aResponse['Message'] = "An ERROR occured while processing your request.";
			}
			
			else
				$aResponse['Message'] = "Request Failed, No Defect Found.";
		}
	}


	print @json_encode($aResponse);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>