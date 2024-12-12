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
	$UserID  = IO::strValue('User');
	$iAuditCode = intval(substr($AuditCode, 1));


	$aResponse = array( );


	//if ($iAuditCode == 0 || strlen($AuditCode) == 0 || $AuditCode{0} != "S")
	if ($UserID == 0)
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid Audit Code";
	}

	else
	{
//		$sSQL = "SELECT vendor_id, report_id, line_id, audit_date, start_time, end_time, po_id, style_id, colors, total_gmts FROM tbl_qa_reports WHERE id='$iAuditCode'";
/*		$sSQL = "SELECT r.*,d.* FROM 
		tbl_qa_reports as r, tbl_qa_report_defects as d WHERE d.audit_id=r.id and r.user_id='$UserID'";
*/		
		$sSQL = "Select * from tbl_users";
		//print $sSQL; exit(0);
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "No QA Report Found!";
		}

		else
		{
		
			for($i=0; $i<= $objDb->getCount( ); $i++){
			
			
//			$sUserId     = $objDb->getField($i, "r.user_id");
			$sUserId     = $objDb->getField($i, "id");
			
			/*$sSQL = "SELECT (r.end_time-r.start_time) as total_tentative_time, r.total_gmts, (actual_end_time-actual_start_time) as total_actual_time, d.* FROM 
		tbl_qa_reports as r, tbl_qa_report_defects as d WHERE d.audit_id=r.id and r.user_id='$UserID'";
			//print $sSQL; exit(0);
			$objDb->query($sSQL);
*/



/*$sSQL = "SELECT count(distinct(d.sample_number)) as sample_num ,(r.end_time-r.start_time) as total_tentative_time, r.total_gmts, (actual_end_time-actual_start_time) as total_actual_time, d.* FROM 
		tbl_qa_reports as r, tbl_qa_report_defects as d WHERE d.audit_id=r.id group by audit_id,sample_number";
*/			
			/*
			$sAuditCode     = $objDb->getField($i, "audit_code");
			$iVendor     = $objDb->getField($i, "vendor_id");
			$iReport     = $objDb->getField($i, "report_id");
			$iLine       = $objDb->getField($i, "line_id");
			$sAuditDate  = $objDb->getField($i, "audit_date");
			$sStartTime  = $objDb->getField($i, "start_time");
			$sEndTime    = $objDb->getField($i, "end_time");
			$iPoId       = $objDb->getField($i, "po_id");
			$iStyleId    = $objDb->getField($i, "style_id");
			$sColors     = $objDb->getField($i, "colors");
			$iSampleSize = (int)$objDb->getField($i, "total_gmts");

			@list($iYear, $sMonth, $sDay) = @explode("-", $sAuditDate);
			@list($sStartHr, $sStartMin)  = @explode(":", $sStartTime);
			@list($sEndHr, $sEndMin)      = @explode(":", $sEndTime);

			$sOrderNo = (($iPoId > 0) ? getDbValue("order_no", "tbl_po", "id='$iPoId'") : "");
			$sStyleNo = (($iStyleId > 0) ? getDbValue("style", "tbl_styles", "id='$iStyleId'") : "");
			*/
			
			/*
			
			data redundancy in objDbGlobal , objDb2;
			
			Audit Progress Screen: skipped shown data on the device.
			
			Defects Category and Defect Areas, Defect count Redundant if added here.
			
			*/
			
			
			$aResponse['Status']   = "OK";
			
			
			// add style comment
			$dDataObject = "";
//			$dDataObject["performance"]["comments"] = $aComments;
			$dDataObject["$sUserId"]["status"] = "Idle";
			$dDataObject["$sUserId"]["effeciency_index"] = "Idle"; 
			$dDataObject["$sUserId"]["active_audit_time"] = "Idle"; // current audit time
			$dDataObject["$sUserId"]["average_time_per_garment"] = "Idle"; // im mins
			$dDataObject["$sUserId"]["std_evaluation_time"] = "Idle"; // im mins
			$dDataObject["$sUserId"]["location_coordinates_long"] = 200.0;
			$dDataObject["$sUserId"]["location_coordinates_lat"] = -235.67;
			
//			{$sOrderNo}|-|{$sStyleNo}|-|{$sColors}|-|{$iSampleSize}|-|");
			//date wise list of DataObjects
			
//			$dDataObjectList["$iYear-$sMonth-$sDay"][] = $dDataObject;
			
			$aResponse['Users'][]   = $dDataObject; //Travelling, conducting audit, idle
			
			//$aResponse['Comments']["$iYear-$sMonth-$sDay"][]   = $aComments;
			
			}
			
//			$aResponse['Schedule'] = ("{$iVendor}|-|{$iReport}|-|{$iLine}|-|{$iYear}|-|{$sMonth}|-|{$sDay}|-|{$sStartHr}|-|{$sStartMin}|-|{$sEndHr}|-|{$sEndMin}|-|{$sOrderNo}|-|{$sStyleNo}|-|{$sColors}|-|{$iSampleSize}|-|".@implode("|--|", $sLines));
			//$aResponse['Schedule'] = ("{$iVendor}|-|{$iReport}|-|{$iLine}|-|{$iYear}|-|{$sMonth}|-|{$sDay}|-|{$sStartHr}|-|{$sStartMin}|-|{$sEndHr}|-|{$sEndMin}|-|{$sOrderNo}|-|{$sStyleNo}|-|{$sColors}|-|{$iSampleSize}|-|");
		}
	}


	//print_r($aResponse);
	//exit(0);
	
	print @json_encode($aResponse);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>