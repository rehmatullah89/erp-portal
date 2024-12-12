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
	$objDb2      = new Database( );

	$User      = IO::strValue("User");
	$AuditCode = IO::strValue("AuditCode");


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "" || $AuditCode == "" || $AuditCode{0} != "S")
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



			$iAuditCode = (int)substr($AuditCode, 1);
			$iReportId  = getDbValue("report_id", "tbl_qa_reports", "id='$iAuditCode'");
			$sOthers    = "";


			$sSQL = "SELECT code_id, id, MIN(nature) AS _Nature, SUM(defects) AS _Defects, cap FROM tbl_qa_report_defects WHERE audit_id='$iAuditCode' GROUP BY code_id ORDER BY id";
			$objDb->query($sSQL);

			$iCount   = $objDb->getCount( );
			$sDefects = array( );

			for($i = 0; $i < $iCount; $i ++)
			{
				$iDefect  = $objDb->getField($i, 'id');
				$iDefects = $objDb->getField($i, '_Defects');
				$iNature  = $objDb->getField($i, '_Nature');
				$iCode    = $objDb->getField($i, 'code_id');
				$sCap     = $objDb->getField($i, 'cap');
				
				if (!@isset($sCap) || $sCap == NULL)
					$sCap = "";

				switch ($iNature)
				{
					case 0 : $sNature = "Minor"; break;					
					case 1 : $sNature = "Major"; break;
					case 2 : $sNature = "Critical"; break;
				}


				$sDefects[] = array("Id"     => $iDefect,
				                    "Defect" => getDbValue("defect", "tbl_defect_codes", "id='$iCode'"),
				                    "Type"   => getDbValue("type", "tbl_defect_types", "id=(SELECT type_id FROM tbl_defect_codes WHERE id='$iCode')"),
				                    "Nature" => $sNature,
				                    "Count"  => $iDefects,
									"CAP"    => $sCap);
			}
			
			
			if ($iReportId == 14 || $iReportId == 34)
				$sOthers = getDbValue("cap_others", "tbl_mgf_reports", "audit_id='$iAuditCode'");
			
			if (!@isset($sOthers) || $sOthers == NULL)
				$sOthers = "";


			$aResponse['Status']  = "OK";
			$aResponse['Defects'] = $sDefects;
			$aResponse['Others']  = $sOthers;
		}
	}


	print @json_encode($aResponse);


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>