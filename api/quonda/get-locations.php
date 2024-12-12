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


	$aResponse = array( );
	$sUsers    = array( );


	$sSQL = "SELECT name, latitude, longitude, location_time, location_address FROM tbl_users WHERE TIME_TO_SEC(TIMEDIFF(NOW( ), location_time)) <= '43200' AND status='A' ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sName      = $objDb->getField($i, "name");
		$sLatitude  = $objDb->getField($i, "latitude");
		$sLongitude = $objDb->getField($i, "longitude");
		$sDateTime  = $objDb->getField($i, "location_time");
		$sAddress   = $objDb->getField($i, "location_address");

		$sDateTime = formatDate($sDateTime, "h:i A");

		$sUsers[] = "{$sName}||{$sLatitude}||{$sLongitude}||{$sDateTime}||{$sAddress} ";
	}


	$aResponse['Status'] = "OK";
	$aResponse['Users']  = @implode("|-|", $sUsers);

	print @json_encode($aResponse);



	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>