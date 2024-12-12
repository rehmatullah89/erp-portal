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

	$sSQL = "SELECT * FROM tbl_user_visits WHERE date = '$Date'";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if($iCount > 0){

			for ($i = 0; $i < $iCount; $i ++)
			{

				$visitsData = array();

				$visitsData["visitID"] 	= $objDb->getField($i, 'id');
				$visitsData["userID"] 	= $objDb->getField($i, 'user_id');

				$visitsData["date"] 	= $objDb->getField($i, 'date');

				$visitsData["visitType"] 	= $objDb->getField($i, 'type');

				$timeIn = $objDb->getField($i, 'time_in');
				$timeOut = $objDb->getField($i, 'time_out');

				if($timeIn =="00:00:00" ){
					$visitsData["timeIn"]		=$timeIn;

				}else{
					$visitsData["timeIn"]		=date('h:i A', strtotime($objDb->getField($i, 'time_in')));
				}

				if($timeOut  =="00:00:00" ){
					$visitsData["timeOut"]		= $timeOut;

				}else{
					$visitsData["timeOut"]		=date('h:i A', strtotime($objDb->getField($i, 'time_out')));
				}


				$locs  =  explode("," , $objDb->getField($i, 'locations'));

				foreach($locs as $val){

					$locname = getDBValue("location","tbl_visit_locations","id='$val'");

					$data = array();
					$data[$val] = $locname;

					$visitsData["locations"][] = $data;
				}


				$ID 	= $objDb->getField($i, 'user_id');
//				$CID	= $objDb->getField($i, 'country_id');

				$visitsData["userName"]   	= getDBValue("name","tbl_users","id='$ID'");
				$aResponse[] = $visitsData;
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