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
		$sSQL = "SELECT department_id, vendor_id, report_id, line_id, audit_date, start_time, end_time, po_id, style_id, colors, total_gmts FROM tbl_qa_reports WHERE id='$iAuditCode'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "No QA Report Found!";
		}

		else
		{
			$iDepartment = $objDb->getField(0, "department_id");
			$iVendor     = $objDb->getField(0, "vendor_id");
			$iReport     = $objDb->getField(0, "report_id");
			$iLine       = $objDb->getField(0, "line_id");
			$sAuditDate  = $objDb->getField(0, "audit_date");
			$sStartTime  = $objDb->getField(0, "start_time");
			$sEndTime    = $objDb->getField(0, "end_time");
			$iPoId       = $objDb->getField(0, "po_id");
			$iStyleId    = $objDb->getField(0, "style_id");
			$sColors     = $objDb->getField(0, "colors");
			$iSampleSize = (int)$objDb->getField(0, "total_gmts");

			@list($iYear, $sMonth, $sDay) = @explode("-", $sAuditDate);
			@list($sStartHr, $sStartMin)  = @explode(":", $sStartTime);
			@list($sEndHr, $sEndMin)      = @explode(":", $sEndTime);

			$sOrderNo = (($iPoId > 0) ? getDbValue("order_no", "tbl_po", "id='$iPoId'") : "");
			$sStyleNo = (($iStyleId > 0) ? getDbValue("style", "tbl_styles", "id='$iStyleId'") : "");


			$sLinesList = getList("tbl_lines", "id", "line", "vendor_id='$iVendor' AND line!=''", "line");
			$sLines     = array( );

			$sLines[] = "0||Select Line";

			foreach ($sLinesList as $sKey => $sValue)
				$sLines[] = "{$sKey}||{$sValue}";

			$aResponse['Status']   = "OK";
			$aResponse['Schedule'] = ("{$iVendor}|-|{$iReport}|-|{$iLine}|-|{$iYear}|-|{$sMonth}|-|{$sDay}|-|{$sStartHr}|-|{$sStartMin}|-|{$sEndHr}|-|{$sEndMin}|-|{$sOrderNo}|-|{$sStyleNo}|-|{$sColors}|-|{$iSampleSize}|-|".@implode("|--|", $sLines)."|-|{$iVendor}");
		}
	}


	print @json_encode($aResponse);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>