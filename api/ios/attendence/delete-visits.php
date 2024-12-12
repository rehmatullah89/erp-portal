<?php
	@require_once("../../requires/session.php");
	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$VisitID = IO::intValue('VisitID');


	$Date    	= IO::strValue('Date');

	$aResponse = array( );

	$sSQL = "SELECT * FROM tbl_user_visits WHERE id ='$VisitID' ";



	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if($iCount > 0){


		$sSQL = "DELETE FROM tbl_user_visits  Where id = '$VisitID' ";


		$bFlag = $objDb->query($sSQL);

		if($bFlag == true){

			$aResponse['Status'] = 'OK';
			$aResponse['Message'] = 'Record has been deleted';

		}else{

			$aResponse['Status'] = 'ERROR';
			$aResponse['Message'] = 'Error has been occured';

		}

	}else{

			$aResponse['Status'] = 'ERROR';
			$aResponse['Message'] = 'Record does not exists';

	}

	print json_encode($aResponse);
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
