<?php
	@require_once("../../requires/session.php");
	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2       = new Database( );

	$User   	= IO::intValue('User');
	$Employee   = IO::intValue('Employee');
	$AirwayBill = IO::strValue("AirwayBill");
	$CourierID 	= IO::strValue("CourierID");
	$Type 		= IO::strValue("Type");
	$Company 	= IO::strValue("Company");
	$CountryID 	= IO::intValue("CountryID");
	$Address 	= IO::strValue("Address");
	$Date 		= IO::strValue("Date");

	$aResponse = array( );

	$sSQL = "SELECT * FROM tbl_couriers WHERE id ='$CourierID' ";



	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if($iCount == 1){


		$sSQL = "UPDATE tbl_couriers  SET awb_no='$AirwayBill',type = '$Type',user_id ='$Employee' ,company = '$Company'
		,country_id = '$CountryID',address = '$Address',date = '$Date',modified = NOW(),modified_by ='$User' Where id = '$CourierID' ";


		$bFlag = $objDb->query($sSQL);

		if($bFlag == true){

			$aResponse['Status'] = 'OK';
			$aResponse['Message'] = 'Record has been updated';

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