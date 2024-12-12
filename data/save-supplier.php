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

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$sSQL  = ("SELECT * FROM tbl_suppliers WHERE (supplier LIKE '".IO::strValue("Supplier")."' OR ('".IO::strValue("Code")."'!='' AND code LIKE '".IO::strValue("Code")."'))");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_suppliers");

		$sSQL = ("INSERT INTO tbl_suppliers (id, supplier, code, city, address, country_id, latitude, longitude, profile, port_required, email, phone, fax, contact_person, person_phone, person_email, person_fax, created_by, created_at, updated_by, updated_at)
		                           VALUES ('$iId', '".IO::strValue("Supplier")."', '".IO::strValue("Code")."', '".IO::strValue("City")."', '".@utf8_encode(IO::strValue("Address"))."', '".IO::intValue("Country")."', '".IO::strValue("Latitude")."', '".IO::strValue("Longitude")."', '".@utf8_encode(IO::strValue("Profile"))."', '".IO::strValue("PortRequired")."', '".IO::strValue("Email")."', '".IO::strValue("Phone")."', '".IO::strValue("Fax")."', '".IO::strValue("PersonName")."', '".IO::strValue("PersonPhone")."', '".IO::strValue("PersonEmail")."', '".IO::strValue("PersonFax")."', '".$_SESSION['UserId']."', NOW(), '".$_SESSION['UserId']."', NOW())");

		if ($objDb->execute($sSQL) == true)
			redirect($_SERVER['HTTP_REFERER'], "SUPPLIER_ADDED");

		else{
                    
                        echo $sSQL; exit;
			$_SESSION['Flag'] = "DB_ERROR";
                        
                }
	}

	else
		$_SESSION['Flag'] = "SUPPLIER_EXISTS";


	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>