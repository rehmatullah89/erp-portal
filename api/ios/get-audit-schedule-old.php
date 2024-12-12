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

	$AuditCode  = IO::strValue('AuditCode');
	$UserID  = IO::strValue('User');
	$iAuditCode = intval(substr($AuditCode, 1));
	
	$FromDate    = IO::strValue('FromDate'); //smart search
	$ToDate      = IO::strValue('ToDate'); //smart search

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
		$sSQL = "SELECT audit_code,audit_stage,vendor_id,brand_id, report_id, line_id, audit_date, start_time, end_time, po_id, style_id, colors, total_gmts FROM tbl_qa_reports WHERE user_id='$UserID' and audit_date BETWEEN '$FromDate' and '$ToDate'";
		
		//print $sSQL; exit(0);
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "No QA Report Found!";
		}

		else
		{
		
			for($i=0; $i< $objDb->getCount( ); $i++){
			
			$sAuditCode     = $objDb->getField($i, "audit_code");
			$sAuditStage     = $objDb->getField($i, "audit_stage");
			$iVendor     = $objDb->getField($i, "vendor_id");
			$iReport     = $objDb->getField($i, "report_id");
			$iLine       = $objDb->getField($i, "line_id");
			$sAuditDate  = $objDb->getField($i, "audit_date");
			$sStartTime  = $objDb->getField($i, "start_time");
			$sEndTime    = $objDb->getField($i, "end_time");
			$iPoId       = $objDb->getField($i, "po_id");
			$iStyleId    = $objDb->getField($i, "style_id");
			$iBrandId    = $objDb->getField($i, "brand_id");
			$sColors     = $objDb->getField($i, "colors");
			$iSampleSize = (int)$objDb->getField($i, "total_gmts");
			
			$sAuditStage = $sAuditStage == "NULL"?"NA":$sAuditStage;
			$sAuditStage = empty($sAuditStage)?"NA":$sAuditStage;
			

			@list($iYear, $sMonth, $sDay) = @explode("-", $sAuditDate);
			@list($sStartHr, $sStartMin)  = @explode(":", $sStartTime);
			@list($sEndHr, $sEndMin)      = @explode(":", $sEndTime);
			
			
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
			$sEndTime     = (str_pad($iEndHour, 2, '0', STR_PAD_LEFT).":".str_pad($iEndMinutes, 2, '0', STR_PAD_LEFT)." ".$sEndAmPm);
					

			$sOrderNo = (($iPoId > 0) ? getDbValue("order_no", "tbl_po", "id='$iPoId'") : "");
			$sStyleNo = (($iStyleId > 0) ? getDbValue("style", "tbl_styles", "id='$iStyleId'") : "");
			
			$sEtdRequired = getDbValue("etd_required", "tbl_po_colors", "style_id='$iStyleId' and po_id = '$iPoId'");
			
			$sBrandId = getDbValue("sub_brand_id", "tbl_styles", "id='$iStyleId'");
			//print_r($sBrandId);exit;
			$sSeasonId = getDbValue("sub_season_id", "tbl_styles", "id='$iStyleId'");
			
			$sBrandName = getDbValue("brand", "tbl_brands", "id='$sBrandId'");
			$sSeasonName = getDbValue("season", "tbl_seasons", "id='$sSeasonId'");
			//empty($sEtdRequired) ?
			//print_r($sBrandId);
			//print_r($sSeasonId);exit;
			
			
			/*
			
			data redundancy in objDbGlobal , objDb2;
			
			Audit Progress Screen: skipped shown data on the device.
			
			Defects Category and Defect Areas, Defect count Redundant if added here.
			
			*/
			
			$sSQL = "SELECT user_id, st.from,nature,date, st.comments, picture, name FROM tbl_style_comments as st, tbl_users as u WHERE u.id=st.user_id and st.style_id='$iStyleId' order by date desc";


	//$sSQL = "SELECT * FROM tbl_style_comments WHERE style_id='$iStyleId' order by date desc";
			$objDbGlobal->query($sSQL);
			
 
			
			//print $sSQL; exit;
			
			for($j=0; $j< $objDbGlobal->getCount( ); $j++){
			
				$dComment=array();
				$sFrom            = $objDbGlobal->getField($j, "st.from");
				
//				echo $sFrom;
				
			//	print_r();
				
				if($sFrom =="Buyer"){
			

					$dComment["comment_str"]     = $objDbGlobal->getField($j, "st.comments");
//					$dComment["user_id"]     = $objDbGlobal->getField($j, "user_id");
					$dComment["user_name"]     = "Buyer";
					$dComment["picture"]     = SITE_URL.'images/users/thumbs/default.jpg';
					$dComment["date"]     = $objDbGlobal->getField($j, "date");
					
					//if($objDbGlobal->getField($j, "nature") == "0") $dComment["nature"]= "Default";
					//else $dComment["nature"]     = $objDbGlobal->getField($j, "nature");					

					//$dComment["designation"] = getDbValue("designation", "tbl_designations", "id='$iDesignation'");

				
				}else{
				
					$dComment["comment_str"]     = $objDbGlobal->getField($j, "st.comments");
//					$dComment["user_id"]     = $objDbGlobal->getField($j, "st.user_id");
					$dComment["user_name"]     = $objDbGlobal->getField($j, "u.name");
					$dComment["date"]     = $objDbGlobal->getField($j, "st.date");
//					$dComment["nature"]     = $objDbGlobal->getField($j, "st.nature");

					//if($objDbGlobal->getField($j, "nature") == "0") $dComment["nature"]= "Default";
					//else $dComment["nature"]     = $objDbGlobal->getField($j, "nature");					

					$dComment["picture"]     = SITE_URL."images/users/thumbs/".$objDbGlobal->getField($j, "u.picture");

					//$dComment["designation"] = getDbValue("designation", "tbl_designations", "id='$iDesignation'");
					
					//print_r($dComment);exit();

				}	
				
				//print_r($dComment); exit(0);		
//				$userid     = $objDbGlobal->getField($j, "user_id");
				
				$aComments[] = $dComment;	
			}

			$sSQL = "SELECT  sample_type_id, status,created_by as user ,created FROM tbl_merchandisings WHERE style_id='$iStyleId' order by created desc";
			$objDb2->query($sSQL);
			
			for($k=0; $k< $objDb2->getCount( ); $k++){
			
				$uid = $objDb2->getField($k, "user");
				$Pic = getDbValue("picture", "tbl_users", "id ='$uid'");
			
				$dHistory["picture"]     = SITE_URL."images/users/thumbs/".$Pic;
			
				$dHistory["user_id"]     = $objDb2->getField($k, "user");
				$dHistory["status_str"]     = $objDb2->getField($k, "status");
				$dHistory["sample_type_id"]     = $objDb2->getField($k, "sample_type_id");
				$dHistory["created"]     = $objDb2->getField($k, "created");
				$aHistory[] = $dHistory;	
			}


			$Planned = getDbValue("count(*)", "tbl_qa_reports", "audit_date ='$sAuditDate'");
			$Completed = getDbValue("count(*)", "tbl_qa_reports", "audit_date ='$sAuditDate' and audit_stage is NOT NULL and audit_result is NOT NULL");
			$Pending = getDbValue("count(*)", "tbl_qa_reports", "audit_date ='$sAuditDate' and audit_stage is NULL and audit_result is NULL");
			
			$FinalCount = getDbValue("count(*)", "tbl_qa_reports", "audit_date ='$sAuditDate' and audit_stage ='F' ");
			$BatchCount = getDbValue("count(*)", "tbl_qa_reports", "audit_date ='$sAuditDate' and audit_stage ='B' ");
			$OutputCount = getDbValue("count(*)", "tbl_qa_reports", "audit_date ='$sAuditDate' and audit_stage ='O' ");

//			$aComments = (($iStyleId > 0) ? getDbValue("qa_comments", "tbl_qa_reports", "style_id=$iStyleId") : "");
			$sStyle_image = getDbValue("sketch_file", "tbl_styles", "id='$iStyleId'");
			
			if($sStyle_image == "NULL") $sStyle_image = "defualt_image.jpg";
			
//print $iStyleId."- ";
//print $sStyle_image; exit(0);
			//$sLinesList = getList("tbl_lines", "id", "line", "vendor_id='$iVendor' AND line!=''", "line");
			//$sLines     = array( );

			//$sLines[] = "0||Select Line";

			//foreach ($sLinesList as $sKey => $sValue)
			//	$sLines[] = "{$sKey}||{$sValue}";

			$aResponse['Status']   = "OK";
			
			$dDataObject["aggregate"]["planned_audits_count"] = $Planned;
			$dDataObject["aggregate"]["completed_audits_count"] = $Completed;
			$dDataObject["aggregate"]["pending_report_uploads_count"] = $Pending;
			
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
			
			
			
//			{$sOrderNo}|-|{$sStyleNo}|-|{$sColors}|-|{$iSampleSize}|-|");
			//date wise list of DataObjects
			
//			$dDataObjectList["$iYear-$sMonth-$sDay"][] = $dDataObject;
			
//			$aResponse['Audits']["$iYear-$sMonth-$sDay"][]   = $dDataObject;
			$aResponse['Audits']["$sAuditDate"][]   = $dDataObject;
			
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