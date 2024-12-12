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


	$Type     = IO::strValue('type');
	$AuditDate = IO::strValue('auditdate');

	if($AuditDate ==""){

	 return;
	}

/*
	$Vendor    = IO::intValue('Vendor');
	$Audits    = IO::strValue('Audits');
	$DateRange = IO::strValue('DateRange');
*/

	$aResponse = array( );
	$sUsers    = array( );


	$sSQLuser = "";
	$TopMarquee = "";

	if($Type == "all"){

	$sSQLuser = "SELECT user_id  FROM  tbl_qa_reports WHERE audit_date = '$AuditDate'";

	$sSQL = "Select count(audit_code) as allcode, country FROM tbl_qa_reports as qa, tbl_vendors as v, tbl_countries as c WHERE qa.vendor_id=v.id AND  v.country_id=c.id AND qa.audit_date = '$AuditDate' group by (c.id)";

	//echo $sSQL; exit(0);
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	$allTotal=0;


	for ($i = 0; $i < $iCount; $i ++)
	{

		$allTotal+=	$objDb->getField($i, "allcode");
		$TopMarquee.= " ".$objDb->getField($i, "allcode").' Audits in'.$objDb->getField($i, "country")." -";
	}

	$TopMarquee = " Total ".$allTotal." Audits ".$TopMarquee;


//	$sLinesList = getList("tbl_qa_reports", "user_id", "audit_date > '2013-06-01'");


	}else if($Type == "fail"){

	$sSQLuser = "SELECT user_id  FROM  tbl_qa_reports WHERE audit_date = '$AuditDate' AND audit_result='F'";
//	$sLinesList = getList("tbl_qa_reports", "user_id", "audit_result!='F'");

	$sSQL = "Select count(audit_result) as allcode,audit_result,  country FROM tbl_qa_reports as qa, tbl_vendors as v, tbl_countries as c WHERE qa.audit_result='F' AND qa.vendor_id=v.id AND v.country_id=c.id AND qa.audit_date = '$AuditDate' group by (c.id)";

	//echo $sSQL; exit(0);
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	$failTotal=0;

	for ($i = 0; $i < $iCount; $i ++)
	{
		$failTotal+=	$objDb->getField($i, "allcode");
		$TopMarquee.= " ".$objDb->getField($i, "allcode").' Audits in'.$objDb->getField($i, "country")." -";
	}

		$TopMarquee = " Total ".$failTotal." Audits ".$TopMarquee;

	}else if($Type == "final"){

	$sSQLuser = "SELECT user_id  FROM  tbl_qa_reports WHERE audit_date = '$AuditDate' AND audit_stage='F'";
//		$sLinesList = getList("tbl_qa_reports", "user_id","audit_stage='F'");

	$sSQL = "Select count(audit_stage) as allcode, audit_stage, country FROM tbl_qa_reports as qa, tbl_vendors as v, tbl_countries as c WHERE qa.audit_stage='F' AND qa.vendor_id=v.id AND  v.country_id=c.id AND qa.audit_date = '$AuditDate' group by (c.id)";

	//echo $sSQL; exit(0);
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	$finalTotal=0;

	for ($i = 0; $i < $iCount; $i ++)
	{
		$finalTotal+=	$objDb->getField($i, "allcode");
		$TopMarquee.= " ".$objDb->getField($i, "allcode").' Audits in'.$objDb->getField($i, "country")." -";
	}

	$TopMarquee = " Total ".$finalTotal." Audits ".$TopMarquee;



	}else if($Type =="ongoing"){

	$sSQLuser = "SELECT user_id  FROM  tbl_qa_reports WHERE audit_date = '$AuditDate' AND audit_stage!='F'";

//		$sLinesList = getList("tbl_qa_reports", "user_id", "audit_stage!='F'");  // not final yet

	$sSQL = "Select count(audit_code) as ongoing, country FROM tbl_qa_reports as qa, tbl_vendors as v, tbl_countries as c WHERE qa.vendor_id=v.id AND v.country_id= c.id AND CURTIME() BETWEEN qa.start_time and qa.end_time and qa.audit_date = '$AuditDate' group by (c.id)";

	//echo $sSQL; exit(0);
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	$ongoingTotal=0;

	for ($i = 0; $i < $iCount; $i ++)
	{

		$ongoingTotal+=	$objDb->getField($i, "ongoing");
		$TopMarquee.= " ".$objDb->getField($i, "ongoing").' Audits in '.$objDb->getField($i, "country")." -";
	}

	$TopMarquee = " Total ".$ongoingTotal." Audits ".$TopMarquee;

	}

	$objDb->query($sSQLuser);

	$iCount = $objDb->getCount( );

	$aUserID= array();

	for ($i = 0; $i < $iCount; $i ++)
	{
		$aUserID[]      = $objDb->getField($i, "user_id");
	}

	//print_r($aUserID);

	$userList = implode(array_unique($aUserID),",");

//	exit(0);

	//$sSQL = "SELECT id ,name, latitude, longitude, location_time, location_address FROM tbl_users WHERE TIME_TO_SEC(TIMEDIFF(NOW( ), location_time)) <= '43200' AND status='A' ORDER BY name";
//	$sSQL = "SELECT id ,name, latitude, longitude, location_time, location_address FROM tbl_users WHERE latitude is not NULL AND status='A' AND id IN ( $userList) ORDER BY name";
//echo $sSQL; exit(0);


/*

	ongoing aggregate


	$sSQL = "Select count(audit_code) as ongoing, country FROM tbl_qa_reports as qa, tbl_vendors as v, tbl_countries as c WHERE qa.vendor_id=v.id AND v.country_id= c.id AND CURTIME() BETWEEN qa.start_time and qa.end_time and qa.audit_date = CURDATE() group by (v.country_id)";

	//echo $sSQL; exit(0);
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	$ongoingTotal=0;

	for ($i = 0; $i < $iCount; $i ++)
	{

		$ongoingTotal+=	$objDb->getField($i, "ongoing");
		$onGoing.= " ".$objDb->getField($i, "ongoing").' Audits in'.$objDb->getField($i, "country")." -";
	}

	$onGoing = " Total ".$ongoingTotal." Audits ".$onGoing;

	*/

	/*

		ongoing marquee



	$sSQL = "Select audit_code, vendor FROM tbl_qa_reports as qa, tbl_vendors as v WHERE qa.vendor_id=v.id and CURTIME() BETWEEN qa.start_time and qa.end_time and qa.audit_date = CURDATE()";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	$marquee = "";

	for ($i = 0; $i < $iCount; $i ++)
	{

		$marquee.= " ".$objDb->getField($i, "audit_code").' is in progress at '.$objDb->getField($i, "vendor")." - ";

	}



	$sSQL = "Select audit_code, audit_result, vendor FROM tbl_qa_reports as qa, tbl_vendors as v WHERE qa.vendor_id=v.id and qa.audit_result='F' and CURTIME() NOT BETWEEN qa.start_time and qa.end_time and qa.audit_date = CURDATE()";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	$done = $iCount;

	for ($i = 0; $i < $iCount; $i ++)
	{

		$marquee.= " ".$objDb->getField($i, "audit_code").' Failed at '.$objDb->getField($i, "vendor")." - ";

	}
	*/


	/*
	$sSQL = "SELECT id ,name, latitude, longitude, location_time, location_address FROM tbl_users WHERE latitude is not NULL AND status='A' AND id IN ( $userList) ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sUserID      = $objDb->getField($i, "id");
		$sName      = $objDb->getField($i, "name");
		$sLatitude  = $objDb->getField($i, "latitude");
		$sLongitude = $objDb->getField($i, "longitude");
		$sDateTime  = $objDb->getField($i, "location_time");
		$sAddress   = $objDb->getField($i, "location_address");

		//$sAddress   = $objDb->getField($i, "location_address");



		$sDateTime = formatDate($sDateTime, "h:i A");

//		$sUsers[] = "{$sName}||{$sLatitude}||{$sLongitude}||{$sDateTime}||{$sAddress} ";

		$sUsers["username"] = $sName;
		$sUsers["userid"] = $sUserID;
		$sUsers["latitude"] = $sLatitude;
		$sUsers["longitude"] = $sLongitude;
		$sUsers["datetime"] = $sDateTime;
		$sUsers["address"] = $sAddress;

		$aResponse['Users'][]  = $sUsers;
	}

	*/

	if($Type=='all'){

		//show all users with locations

			$sSQL = "SELECT id ,name, latitude, longitude, location_time, location_address FROM tbl_users WHERE latitude is not NULL AND status='A' AND id IN ( $userList) ORDER BY name";

				//print $sSQL; exit;
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
				{
					$sUserID      = $objDb->getField($i, "id");
					$sName      = $objDb->getField($i, "name");
					$sLatitude  = $objDb->getField($i, "latitude");
					$sLongitude = $objDb->getField($i, "longitude");
					$sDateTime  = $objDb->getField($i, "location_time");
					$sAddress   = $objDb->getField($i, "location_address");

					//$sAddress   = $objDb->getField($i, "location_address");



					$sDateTime = formatDate($sDateTime, "h:i A");

			//		$sUsers[] = "{$sName}||{$sLatitude}||{$sLongitude}||{$sDateTime}||{$sAddress} ";

					$sUsers["username"] = $sName;
					$sUsers["userid"] = $sUserID;
					$sUsers["latitude"] = $sLatitude;
					$sUsers["longitude"] = $sLongitude;
					$sUsers["datetime"] = $sDateTime;
					$sUsers["address"] = $sAddress;

					$aResponse['Users'][]  = $sUsers;
				}



	}else {


		//show all vendors with a list of audit codes

			$VendorsData = array();
			$sSQL = "SELECT qa.audit_code,qa.user_id,qa.vendor_id,qa.brand_id,ven.latitude,ven.longitude,qa.audit_result,qa.audit_stage FROM tbl_qa_reports AS qa, tbl_vendors AS ven WHERE qa.vendor_id=ven.id AND qa.audit_date=CURDATE()";

//print $sSQL; exit;
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			$indexContainer=array();

			for ($i = 0; $i < $iCount; $i ++)
			{
				$sAuditCode      = $objDb->getField($i, "audit_code");
				$iUserID      = $objDb->getField($i, "user_id");
				$iVendorID      = $objDb->getField($i, "vendor_id");
				$iVendorLong     = $objDb->getField($i, "longitude");
				$iVendorLat     = $objDb->getField($i, "latitude");
				$sAuditResult     = $objDb->getField($i, "audit_result");
				$sAuditStage     = $objDb->getField($i, "audit_stage");

				$iBrandID     = $objDb->getField($i, "brand_id");


				$VendorsData[$iVendorID]['location']['lat'] = $iVendorLat;

				$VendorsData[$iVendorID]['location']['long'] = $iVendorLong;

				if(!in_array($iVendorID ,array_keys($indexContainer))){

					$indexContainer[$iVendorID]=0;

				}else{

					$indexContainer[$iVendorID]+=1;

				}

				$sAuditResult = $sAuditResult==""?"NA":$sAuditResult;

				$sAuditStage = $sAuditStage==""?"NA":$sAuditStage;


				$VendorsData[$iVendorID]['Audits'][$indexContainer[$iVendorID]]['audit_code'] = $sAuditCode;

				$VendorsData[$iVendorID]['Audits'][$indexContainer[$iVendorID]]['audit_result'] = $sAuditResult;

				$VendorsData[$iVendorID]['Audits'][$indexContainer[$iVendorID]]['audit_stage'] = $sAuditStage;

				$VendorsData[$iVendorID]['Audits'][$indexContainer[$iVendorID]]['brand_id'] = $iBrandID;


			}

			$aResponse['Vendors']  = $VendorsData;

	}

//print_r($aResponse);
//exit;
	$aResponse['Status'] = "OK";
	//$aResponse['Ongoing'] = $onGoing;

	$aResponse['TopMarquee'] = $TopMarquee;
	$aResponse['BottomMarquee'] = $TopMarquee;
//	$aResponse['Users']  = @implode("|-|", $sUsers);

	print @json_encode($aResponse);



	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>