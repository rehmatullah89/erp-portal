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
	$objDb2       = new Database( );

	$AuditDate  = IO::strValue('AuditDate');
	$AuditCode  = IO::strValue('AuditCode');
	$UserID  = IO::strValue('User');

	$sVendorID  = IO::strValue('Vendor');  //zero for all
	$sAuditorID  = IO::strValue('Auditor'); //zero for all

	$VendorID  = intval(IO::strValue('Vendor'));  //zero for all
	$AuditorID  = intval(IO::strValue('Auditor')); //zero for all

	$iAuditCode = intval(substr($AuditCode, 1));

	$FromDate    = IO::strValue('FromDate'); //smart search
	$ToDate      = IO::strValue('ToDate'); //smart search

	$aResponse = array( );

	//samplesize aql value for level 1

	$AqlForSample['0.65']['2']=0;
	$AqlForSample['0.65']['5']=0;
	$AqlForSample['0.65']['8']=0;
	$AqlForSample['0.65']['13']=0;
	$AqlForSample['0.65']['20']=0;
	$AqlForSample['0.65']['32']=0;
	$AqlForSample['0.65']['50']=0;
	$AqlForSample['0.65']['80']=1;
	$AqlForSample['0.65']['125']=2;
	$AqlForSample['0.65']['200']=3;
	$AqlForSample['0.65']['315']=5;
	$AqlForSample['0.65']['500']=7;

	$AqlForSample['1.0']['2']=0;
	$AqlForSample['1.0']['5']=0;
	$AqlForSample['1.0']['8']=0;
	$AqlForSample['1.0']['13']=0;
	$AqlForSample['1.0']['20']=0;
	$AqlForSample['1.0']['32']=0;
	$AqlForSample['1.0']['50']=1;
	$AqlForSample['1.0']['80']=2;
	$AqlForSample['1.0']['125']=3;
	$AqlForSample['1.0']['200']=5;
	$AqlForSample['1.0']['315']=7;
	$AqlForSample['1.0']['500']=10;

	$AqlForSample['1.5']['2']=0;
	$AqlForSample['1.5']['5']=0;
	$AqlForSample['1.5']['8']=0;
	$AqlForSample['1.5']['13']=0;
	$AqlForSample['1.5']['20']=0;
	$AqlForSample['1.5']['32']=1;
	$AqlForSample['1.5']['50']=2;
	$AqlForSample['1.5']['80']=3;
	$AqlForSample['1.5']['125']=5;
	$AqlForSample['1.5']['200']=7;
	$AqlForSample['1.5']['315']=10;
	$AqlForSample['1.5']['500']=14;

	$AqlForSample['2.5']['2']=0;
	$AqlForSample['2.5']['5']=0;
	$AqlForSample['2.5']['8']=0;
	$AqlForSample['2.5']['13']=0;
	$AqlForSample['2.5']['20']=1;
	$AqlForSample['2.5']['32']=2;
	$AqlForSample['2.5']['50']=3;
	$AqlForSample['2.5']['80']=5;
	$AqlForSample['2.5']['125']=7;
	$AqlForSample['2.5']['200']=10;
	$AqlForSample['2.5']['315']=14;
	$AqlForSample['2.5']['500']=21;

	$AqlForSample['4.0']['2']=0;
	$AqlForSample['4.0']['5']=0;
	$AqlForSample['4.0']['8']=0;
	$AqlForSample['4.0']['13']=1;
	$AqlForSample['4.0']['20']=2;
	$AqlForSample['4.0']['32']=3;
	$AqlForSample['4.0']['50']=5;
	$AqlForSample['4.0']['80']=7;
	$AqlForSample['4.0']['125']=10;
	$AqlForSample['4.0']['200']=14;
	$AqlForSample['4.0']['315']=21;
	$AqlForSample['4.0']['500']=21;



	//if ($iAuditCode == 0 || strlen($AuditCode) == 0 || $AuditCode{0} != "S")
	if ($UserID == 0)
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid Audit Code";
	}

	else
	{
//		$sSQL = "SELECT vendor_id, report_id, line_id, audit_date, start_time, end_time, po_id, style_id, colors, total_gmts FROM tbl_qa_reports WHERE id='$iAuditCode'";
		$sSQL = "SELECT audit_code,audit_stage,vendor_id,brand_id, report_id, line_id, audit_date, start_time, end_time, po_id, style_id, colors, total_gmts FROM tbl_qa_reports WHERE user_id='$UserID' and audit_date BETWEEN '$FromDate' and '$ToDate'";

		if($VendorID == 0  && $AuditorID == 0){

//			$sSQL = "SELECT start_time,audit_code, audit_stage, COUNT(start_time) AS freq,vendor_id,brand_id  FROM tbl_qa_reports WHERE audit_date ='$AuditDate'  GROUP BY audit_date, start_time,vendor_id";
			$sSQL = "SELECT start_time,audit_code, audit_stage, vendor_id,brand_id  FROM tbl_qa_reports WHERE audit_date ='$AuditDate' ORDER BY start_time Asc";

			$Planned = getDbValue("count(*)", "tbl_qa_reports", "audit_date ='$AuditDate'");
			$Completed = getDbValue("count(*)", "tbl_qa_reports", "audit_date ='$AuditDate' and audit_stage is NOT NULL and audit_result is NOT NULL");
			$Pending = getDbValue("count(*)", "tbl_qa_reports", "audit_date ='$AuditDate' and audit_stage is NULL and audit_result is NULL");

}

		if($VendorID > 0){
//			$sSQL = "SELECT start_time, audit_code, audit_stage, COUNT(start_time) AS freq,vendor_id,brand_id  FROM tbl_qa_reports WHERE audit_date ='$AuditDate' and vendor_id = '$sVendorID'  GROUP BY audit_date, start_time,vendor_id";
			$sSQL = "SELECT start_time, audit_code, audit_stage,vendor_id,brand_id  FROM tbl_qa_reports WHERE audit_date ='$AuditDate' and vendor_id = '$sVendorID' ORDER BY start_time Asc";

			$Planned = getDbValue("count(*)", "tbl_qa_reports", "audit_date ='$AuditDate' and vendor_id = '$VendorID' ");
			$Completed = getDbValue("count(*)", "tbl_qa_reports", "audit_date ='$AuditDate' and vendor_id = '$VendorID'  and audit_stage is NOT NULL and audit_result is NOT NULL");
			$Pending = getDbValue("count(*)", "tbl_qa_reports", "audit_date ='$AuditDate' and vendor_id = '$VendorID'  and audit_stage is NULL and audit_result is NULL");


		}
		if($AuditorID > 0){
//			$sSQL = "SELECT start_time, audit_code, audit_stage, COUNT(start_time) AS freq,vendor_id,brand_id  FROM tbl_qa_reports WHERE audit_date ='$AuditDate' and user_id = '$AuditorID'  GROUP BY audit_date, start_time,vendor_id";
			$sSQL = "SELECT start_time, audit_code, audit_stage, vendor_id,brand_id  FROM tbl_qa_reports WHERE audit_date ='$AuditDate' and user_id = '$AuditorID' ORDER BY start_time Asc ";

			$Planned = getDbValue("count(*)", "tbl_qa_reports", "audit_date ='$AuditDate' and user_id = '$AuditorID' ");
			$Completed = getDbValue("count(*)", "tbl_qa_reports", "audit_date ='$AuditDate' and user_id = '$AuditorID'  and audit_stage is NOT NULL and audit_result is NOT NULL");
			$Pending = getDbValue("count(*)", "tbl_qa_reports", "audit_date ='$AuditDate' and user_id = '$AuditorID'  and audit_stage is NULL and audit_result is NULL");

		}

		if($AuditorID > 0 && $VendorID > 0){
//			$sSQL = "SELECT start_time, audit_code, audit_stage, COUNT(start_time) AS freq,vendor_id,brand_id  FROM tbl_qa_reports WHERE audit_date ='$AuditDate' and  vendor_id ='$sVendorID' and user_id = '$AuditorID'  GROUP BY audit_date, start_time,vendor_id";
			$sSQL = "SELECT start_time, audit_code, audit_stage, vendor_id,brand_id  FROM tbl_qa_reports WHERE audit_date ='$AuditDate' and  vendor_id ='$sVendorID' and user_id = '$AuditorID' ORDER BY start_time Asc";

			$Planned = getDbValue("count(*)", "tbl_qa_reports", "audit_date ='$AuditDate' and user_id = '$AuditorID' and vendor_id = '$VendorID'");
			$Completed = getDbValue("count(*)", "tbl_qa_reports", "audit_date ='$AuditDate' and user_id = '$AuditorID' and vendor_id = '$VendorID' and audit_stage is NOT NULL and audit_result is NOT NULL");
			$Pending = getDbValue("count(*)", "tbl_qa_reports", "audit_date ='$AuditDate' and user_id = '$AuditorID' and vendor_id = '$VendorID' and audit_stage is NULL and audit_result is NULL");

		}
		//print $sSQL; exit(0);
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "No Audit Found!";

		}

		else
		{

			for($i=0; $i< $objDb->getCount( ); $i++){

			$sAuditCode     = $objDb->getField($i, "audit_code");
			$sAuditStage     = $objDb->getField($i, "audit_stage");
			$iVendor     = $objDb->getField($i, "vendor_id");
			/*
			$iReport     = $objDb->getField($i, "report_id");
			$iLine       = $objDb->getField($i, "line_id");
			$sAuditDate  = $objDb->getField($i, "audit_date");
			*/
			$sStartTime  = $objDb->getField($i, "start_time");
			/*
			$sEndTime    = $objDb->getField($i, "end_time");
			$iPoId       = $objDb->getField($i, "po_id");
			$iStyleId    = $objDb->getField($i, "style_id");
			$iBrandId    = $objDb->getField($i, "brand_id");
			$sColors     = $objDb->getField($i, "colors");
			$iSampleSize = (int)$objDb->getField($i, "total_gmts");
			*/

//			$iTotal_Audits = (int)$objDb->getField($i, "freq");
			$iTotal_Audits = 2;

			$sAuditStage = $sAuditStage == "NULL"?"NA":$sAuditStage;
			$sAuditStage = empty($sAuditStage)?"NA":$sAuditStage;


			//@list($iYear, $sMonth, $sDay) = @explode("-", $sAuditDate);
			@list($sStartHr, $sStartMin)  = @explode(":", $sStartTime);
			//@list($sEndHr, $sEndMin)      = @explode(":", $sEndTime);


			@list($iStartHour, $iStartMinutes) = @explode(":", $sStartTime);
			@list($iEndHour, $iEndMinutes)     = @explode(":", $sEndTime);

					if ($iStartHour >= 12)
					{
						if ($iStartHour > 12)
							$iStartHour -= 12;

						$sStartAmPm  = "PM";
					}

					else
						$sStartAmPm = "AM";


					if ($iEndHour >= 12)
					{
						if ($iEndHour > 12)
							$iEndHour -= 12;

						$sEndAmPm  = "PM";
					}

					else
						$sEndAmPm = "AM";

			$sStartTime   = (str_pad($iStartHour, 2, '0', STR_PAD_LEFT).":".str_pad($iStartMinutes, 2, '0', STR_PAD_LEFT)." ".$sStartAmPm);
			//$sEndTime     = (str_pad($iEndHour, 2, '0', STR_PAD_LEFT).":".str_pad($iEndMinutes, 2, '0', STR_PAD_LEFT)." ".$sEndAmPm);



			//$Planned = getDbValue("count(*)", "tbl_qa_reports", "audit_date ='$AuditDate'");
			//$Completed = getDbValue("count(*)", "tbl_qa_reports", "audit_date ='$AuditDate' and audit_stage is NOT NULL and audit_result is NOT NULL");
			//$Pending = getDbValue("count(*)", "tbl_qa_reports", "audit_date ='$AuditDate' and audit_stage is NULL and audit_result is NULL");
/*
			$FinalCount = getDbValue("count(*)", "tbl_qa_reports", "audit_date ='$sAuditDate' and audit_stage ='F' ");
			$BatchCount = getDbValue("count(*)", "tbl_qa_reports", "audit_date ='$sAuditDate' and audit_stage ='B' ");
			$OutputCount = getDbValue("count(*)", "tbl_qa_reports", "audit_date ='$sAuditDate' and audit_stage ='O' ");

			//$Aql = getDbValue("aql", "tbl_qa_reports", "audit_date ='$sAuditDate' and audit_stage ='O' ");

//			$aComments = (($iStyleId > 0) ? getDbValue("qa_comments", "tbl_qa_reports", "style_id=$iStyleId") : "");
			$sStyle_image = getDbValue("sketch_file", "tbl_styles", "id='$iStyleId'");

			if($sStyle_image == "NULL") $sStyle_image = "defualt_image.jpg";
*/

			$aResponse['Status']   = "OK";

			$dDataObject["aggregate"]["planned_audits_count"] = $Planned;
			$dDataObject["aggregate"]["completed_audits_count"] = $Completed;
			$dDataObject["aggregate"]["pending_report_uploads_count"] = $Pending;

			$dDataObject["audit"][$i][$sStartTime][$iVendor]["freq"] = $iTotal_Audits;
			$dDataObject["audit"][$i][$sStartTime][$iVendor]["audit_stage_str"]= $sAuditStage;

			$dDataObject["audit"][$i][$sStartTime][$iVendor]["audit_code_str"]= $sAuditCode ;

			/*
			$dDataObject["aggregate"]["final_audits_count"] = $FinalCount;
			$dDataObject["aggregate"]["batch_audits_count"] = $BatchCount;
			$dDataObject["aggregate"]["output_audits_count"] = $OutputCount;


			$dDataObject["audit"]["audit_code"] = $sAuditCode;
			$dDataObject["audit"]["etd_required"] = $sEtdRequired;
			$dDataObject["audit"]["audit_stage_str"] = $sAuditStage;
			$dDataObject["audit"]["vendor_id"] = $iVendor;
			$dDataObject["audit"]["report_id"] = $iReport;
			$dDataObject["audit"]["line_id"] = $iLine;
//			$dDataObject["audit"]["start_time"] = $sStartHr.":".$sStartMin;
//			$dDataObject["audit"]["end_time"] = $sEndHr.":".$sEndMin;
			$dDataObject["audit"]["start_time"] = $sStartTime;
			$dDataObject["audit"]["end_time"] = $sEndTime;
			$dDataObject["audit"]["aql"] = $defectRate;
			$dDataObject["audit"]["max_defect_allowed"] = 1;



			$dDataObject["audit"]["po_str"] = $sOrderNo;
			$dDataObject["audit"]["style_str"] = $sStyleNo;
			$dDataObject["audit"]["style_image_url"] = SITE_URL.STYLES_SKETCH_DIR.$sStyle_image;
			$dDataObject["audit"]["colors_str"] = $sColors;
			$dDataObject["audit"]["sample_size"] = $iSampleSize;

			//$dDataObject["audit"]["brand_str"] = "Addidas";
			$dDataObject["audit"]["brand_str"] = $sBrandName;
			//$dDataObject["audit"]["etd_required"] = "31st August, 2013";
			$dDataObject["audit"]["season_str"] = $sSeasonName;


			// add style comment

			$dDataObject["audit"]["comments"] = $aComments;
			$dDataObject["audit"]["history"] = $aHistory;

*/

//			{$sOrderNo}|-|{$sStyleNo}|-|{$sColors}|-|{$iSampleSize}|-|");
			//date wise list of DataObjects

//			$dDataObjectList["$iYear-$sMonth-$sDay"][] = $dDataObject;

//			$aResponse['Audits']["$iYear-$sMonth-$sDay"][]   = $dDataObject;
			$aResponse['Audits']   = $dDataObject;


			//$aResponse['Comments']["$iYear-$sMonth-$sDay"][]   = $aComments;

			}

		}
	}


	for($i=1; $i<7; $i++){

		$yesterday = date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d")-$i, date("Y")));

		$FinalCount = getDbValue("count(*)", "tbl_qa_reports", "audit_date ='$yesterday' and audit_stage ='F' ");
		$BatchCount = getDbValue("count(*)", "tbl_qa_reports", "audit_date ='$yesterday' and audit_stage ='B' ");
		$OutputCount = getDbValue("count(*)", "tbl_qa_reports", "audit_date ='$yesterday' and audit_stage ='O' ");

		$dbAggregate['FinalCount'] = $FinalCount;
		$dbAggregate['BatchCount'] = $BatchCount;
		$dbAggregate['OutputCount'] = $OutputCount;
		$dbAggregate['date'] = $yesterday;

		$aResponse['Audits']["day_aggregate"][$i] = $dbAggregate;


	}


//print_r($aResponse);
//exit(0);
	print @json_encode($aResponse);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>