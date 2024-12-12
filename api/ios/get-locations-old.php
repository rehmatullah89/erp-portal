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
	
	if($Type == "all"){
	
	$sSQLuser = "SELECT user_id  FROM  tbl_qa_reports WHERE audit_date = '$AuditDate'";
	
//	$sLinesList = getList("tbl_qa_reports", "user_id", "audit_date > '2013-06-01'");
	
	
	}else if($Type == "fail"){

	$sSQLuser = "SELECT user_id  FROM  tbl_qa_reports WHERE audit_date = '$AuditDate' AND audit_result!='F'";
//	$sLinesList = getList("tbl_qa_reports", "user_id", "audit_result!='F'");
	
	
	}else if($Type == "final"){
	
	$sSQLuser = "SELECT user_id  FROM  tbl_qa_reports WHERE audit_date = '$AuditDate' AND audit_stage='F'";
//		$sLinesList = getList("tbl_qa_reports", "user_id","audit_stage='F'");
	
	
	}else if($ype =="ongoing"){
	
	$sSQLuser = "SELECT user_id  FROM  tbl_qa_reports WHERE audit_date = '$AuditDate' AND audit_stage!='F'";
	
//		$sLinesList = getList("tbl_qa_reports", "user_id", "audit_stage!='F'");  // not final yet	
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
	$sSQL = "SELECT id ,name, latitude, longitude, location_time, location_address FROM tbl_users WHERE latitude is not NULL AND status='A' AND id IN ( $userList) ORDER BY name";
//echo $sSQL; exit(0);
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

	$aResponse['Status'] = "OK";
//	$aResponse['Users']  = @implode("|-|", $sUsers);

	print @json_encode($aResponse);



	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>