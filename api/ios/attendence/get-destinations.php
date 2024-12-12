<?php
	@require_once("../../requires/session.php");
	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2       = new Database( );

	$User   = IO::intValue('User');
	$Vendor = IO::intValue("Vendor");


	$aResponse = array( );


	$sSQL = "SELECT * FROM tbl_visit_locations  ORDER BY location";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{

			$locationData = array();


			$locationData["locationName"] = $objDb->getField($i, 'location');
			$locationData["locationID"]   = $objDb->getField($i, 'id');
			$aResponse[] = $locationData;
		}

	print json_encode($aResponse);
	$objDb2->close( );
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>