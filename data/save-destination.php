<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

        $Flag        = false;
	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$sSQL  = ("SELECT * FROM tbl_destinations WHERE destination LIKE '".IO::strValue("Destination")."' AND region='".IO::strValue("Region")."' AND brand_id='".IO::intValue("Brand")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_destinations");

		$sSQL = ("INSERT INTO tbl_destinations (id, brand_id, region, destination, type, port_id, block_no) VALUES ('$iId', '".IO::strValue("Brand")."', '".IO::strValue("Region")."', '".IO::strValue("Destination")."', '".IO::strValue("Type")."', '".IO::intValue("Port")."', '".IO::intValue("BlockNo")."')");
                $Flag = $objDb->execute($sSQL);
                
                if ($Flag == true)
			redirect($_SERVER['HTTP_REFERER'], "DESTINATION_ADDED");
		else
			$_SESSION['Flag'] = "DB_ERROR";
	}

	else
		$_SESSION['Flag'] = "DESTINATION_EXISTS";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>