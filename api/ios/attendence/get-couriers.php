<?php
	@require_once("../../requires/session.php");
	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2       = new Database( );

	$User   = IO::intValue('User');
	$Vendor = IO::intValue("Vendor");

	$Date 	= IO::strValue("Date");

	if($Date == ""){

		$Date = date();

	}

	$aResponse = array( );


	$sSQL = "SELECT * FROM tbl_couriers WHERE date = '$Date'";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if($iCount > 0){

			for ($i = 0; $i < $iCount; $i ++)
			{

				$courierData = array();


				$courierData["courierID"] = $objDb->getField($i, 'id');
				$courierData["userID"] 	= $objDb->getField($i, 'user_id');

				$ID 	= $objDb->getField($i, 'user_id');
				$CID	= $objDb->getField($i, 'country_id');

				$courierData["userName"] 	= getDBValue("name","tbl_users","id='$ID'");

				$courierData["airwayBill"]   = $objDb->getField($i, 'awb_no');

				$courierData["address"]  	= $objDb->getField($i, 'address');
				$courierData["courierType"] = $objDb->getField($i, 'type');


				$courierData["countryID"]   = $CID;
				$courierData["country"]   	= getDBValue("country","tbl_countries","id='$CID'");

				$courierData["company"]   	= $objDb->getField($i, 'company');

				$courierData["date"]   		= $objDb->getField($i, 'date');


				$aResponse[] = $courierData;
			}

		}else {

				$aResponse['Status'] = 'OK';
				$aResponse['Message'] = "No Records found.";


		}

	print json_encode($aResponse);
	$objDb2->close( );
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>