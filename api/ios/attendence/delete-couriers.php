<?php
	@require_once("../../requires/session.php");
	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2       = new Database( );

	$CourierID 	= IO::strValue("CourierID");


	$aResponse = array( );

	$sSQL = "SELECT * FROM tbl_couriers WHERE id ='$CourierID' ";


	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if($iCount > 0 ){


		$sSQL = "DELETE FROM tbl_couriers  Where id = '$CourierID' ";


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
	$objDb2->close( );
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>