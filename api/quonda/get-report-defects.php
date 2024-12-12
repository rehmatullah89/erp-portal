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

	$AuditCode  = IO::strValue('AuditCode');
	$iAuditCode = intval(substr($AuditCode, 1));

	$aResponse = array( );


	if ($iAuditCode == 0 || strlen($AuditCode) == 0 || $AuditCode{0} != "S")
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid Audit Code";
	}

	else
	{
		$sSQL = "SELECT *,
						(SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _Po,
						(SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor
				 FROM tbl_qa_reports
				 WHERE id='$iAuditCode'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "No QA Report Found!";
		}

		else
		{
			$iReportId      = $objDb->getField(0, "report_id");
			$sVendor        = $objDb->getField(0, "_Vendor");
			$iPoId          = $objDb->getField(0, "po_id");
			$sPo            = $objDb->getField(0, "_Po");
			$sAdditionalPos = $objDb->getField(0, "additional_pos");
			$iStyle         = $objDb->getField(0, "style_id");
			$sAuditDate     = $objDb->getField(0, "audit_date");
			$sAuditStage    = $objDb->getField(0, "audit_stage");
			$sAuditResult   = $objDb->getField(0, "audit_result");


			$sSQL = "SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id IN ($sAdditionalPos) ORDER BY order_no";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
				$sPo .= (", ".$objDb->getField($i, 0));


			if ($iStyle > 0)
				$sSQL = "SELECT style FROM tbl_styles WHERE id='$iStyle'";

			else
				$sSQL = "SELECT style FROM tbl_styles WHERE id=(SELECT style_id FROM tbl_po_colors WHERE po_id='$iPoId' LIMIT 1)";

			$objDb->query($sSQL);

			$sStyle = $objDb->getField(0, 0);


			switch ($sAuditResult)
			{
				case "P" : $sAuditResult = "Pass"; break;
				case "F" : $sAuditResult = "Fail"; break;
				case "H" : $sAuditResult = "Hold"; break;
				default  : $sAuditResult = "Grade {$sAuditResult}";
			}


			$sAuditStage = getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'");
			$sAuditDate  = formatDate($sAuditDate);
			$sDetails    = "";


			if ($iReportId == 6)
			{
				$sSQL = "SELECT * FROM tbl_gf_report_defects WHERE audit_id='$iAuditCode' ORDER BY id";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for($i = 0; $i < $iCount; $i ++)
				{
					$sSQL = ("SELECT code, defect FROM tbl_defect_codes WHERE id='".$objDb->getField($i, 'code_id')."'");
					$objDb2->query($sSQL);


					$sDetails .= $objDb->getField($i, 'id');
					$sDetails .= "||";
					$sDetails .= ($objDb2->getField(0, 'code')." - ".$objDb2->getField(0, 'defect'));
					$sDetails .= "||";
					$sDetails .= ($objDb->getField(0, 'roll')."/".$objDb->getField(0, 'panel')."/".$objDb->getField(0, 'grade'));
					$sDetails .= "||";
					$sDetails .= intval($objDb->getField($i, 'defects'));
					$sDetails .= " ";

					if ($i < ($iCount - 1))
						$sDetails .= "|--|";
				}
			}

			else
			{
				$sSQL = "SELECT * FROM tbl_qa_report_defects WHERE audit_id='$iAuditCode' ORDER BY id";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for($i = 0; $i < $iCount; $i ++)
				{
					$sSQL = ("SELECT code, defect FROM tbl_defect_codes WHERE id='".$objDb->getField($i, 'code_id')."'");
					$objDb2->query($sSQL);

					$sSQL = ("SELECT id, area FROM tbl_defect_areas WHERE id='".$objDb->getField($i, 'area_id')."'");
					$objDb3->query($sSQL);


					$sDetails .= $objDb->getField($i, 'id');
					$sDetails .= "||";
					$sDetails .= ($objDb2->getField(0, 'code')." - ".$objDb2->getField(0, 'defect'));
					$sDetails .= "||";
					$sDetails .= $objDb3->getField(0, 'area');
					$sDetails .= " ||";
					$sDetails .= intval($objDb->getField($i, 'defects'));
					$sDetails .= " ";

					if ($i < ($iCount - 1))
						$sDetails .= "|--|";
				}
			}


			$aResponse['Status'] = "OK";
			$aResponse['Report'] = "{$sVendor}|-|{$sStyle}|-|{$sPo}|-|{$sAuditDate}|-|{$sAuditStage}|-|{$sAuditResult}|-|{$sDetails}";
		}
	}


	print @json_encode($aResponse);


	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>