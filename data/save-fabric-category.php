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

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$sSQL  = ("SELECT * FROM tbl_fabric_categories WHERE category LIKE '".IO::strValue("Category")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_fabric_categories");

		if ($_FILES['Picture']['name'] != "")
		{
			$sPicture = ($iId."-".IO::getFileName($_FILES['Picture']['name']));

			if (!@move_uploaded_file($_FILES['Picture']['tmp_name'], ($sBaseDir.FABRIC_CATEGORIES_IMG_PATH.$sPicture)))
					$sPicture = "";
		}

		$sSQL = ("INSERT INTO tbl_fabric_categories (id, parent_id, category, description, picture) VALUES ('$iId', '".IO::intValue("Parent")."', '".IO::strValue("Category")."', '".IO::strValue("Description")."', '$sPicture')");

		if ($objDb->execute($sSQL) == true)
			redirect($_SERVER['HTTP_REFERER'], "FABRIC_CATEGORY_ADDED");

		else
		{
			$_SESSION['Flag'] = "DB_ERROR";

			@unlink($sBaseDir.FABRIC_CATEGORIES_IMG_PATH.$sPicture);
		}
	}

	else
		$_SESSION['Flag'] = "FABRIC_CATEGORY_EXISTS";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>