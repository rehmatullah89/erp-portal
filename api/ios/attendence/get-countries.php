<?php
	@require_once("../../requires/session.php");
	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2       = new Database( );

	$User   = IO::intValue('User');
	$Vendor = IO::intValue("Vendor");
	$Today = date();

	$aResponse = array( );


		$sSQL = "SELECT * FROM tbl_countries ORDER BY country Asc";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if($iCount > 0){

			for ($i = 0; $i < $iCount; $i ++)
			{

				$countryData = array();


				$countryData["ID"] = $objDb->getField($i, 'id');
				$countryData["Name"] = $objDb->getField($i, 'country');

				$aResponse[] = $countryData;
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