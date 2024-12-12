<?php
	@require_once("../../requires/session.php");
	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2       = new Database( );

	$User   	= IO::intValue('User');
	$Employee   	= IO::intValue('Employee');
	$AirwayBill = IO::strValue("AirwayBill");
	$Type = IO::strValue("Type");
	$Company = IO::strValue("Company");
	$CountryID = IO::intValue("CountryID");
	$Address = IO::strValue("Address");
	$Date = IO::strValue("Date");


	$aResponse = array( );


	$sSQL = "SELECT * FROM tbl_couriers WHERE awb_no ='$AirwayBill' ";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if($iCount == 0){

		$iId = getNextId("tbl_couriers");
		$sSQL = "INSERT INTO tbl_couriers (id,awb_no,type,user_id,company,country_id,address,date,created,created_by,modified,modified_by)
		Values ($iId,'$AirwayBill','$Type','$Employee','$Company','$CountryID','$Address','$Date',NOW( ), '$User', NOW( ), '$User') ";

		//print $sSQL; exit;

		$bFlag = $objDb->query($sSQL);

		if($bFlag == true){

			$aResponse['Status'] = 'OK';
			$aResponse['Message'] = 'Record has been saved';

		}else{

			$aResponse['Status'] = 'ERROR';
			$aResponse['Message'] = 'Error has been occured';

		}




	}else{

		$aResponse['Status'] = 'ERROR';
		$aResponse['Message'] = 'Record already exists';

	}

	print json_encode($aResponse);
	$objDb2->close( );
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>