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
	$objDb2      = new Database( );

	$Title      = IO::strValue("Title");
	$Color      = IO::strValue("Color");
	$Background = IO::strValue("Background");
	$Left       = IO::strValue("Left");
	$Top        = IO::strValue("Top");
	$Width      = IO::strValue("Width");
	$Products   = IO::getArray("SelectedProducts");
	$Users      = IO::getArray("SelectedUsers");


	$sSQL = "SELECT * FROM tbl_flipbooks WHERE title like '$Title'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iFlipbook = getNextId("tbl_flipbooks");


		if ($_FILES['FrontPicture']['name'] != "")
		{
			$sFrontPicture = ($iFlipbook."-front-".IO::getFileName($_FILES['FrontPicture']['name']));

			if (!@move_uploaded_file($_FILES['FrontPicture']['tmp_name'], ($sBaseDir.MDL_PRODUCTS_DIR.$sFrontPicture)))
				$sFrontPicture = "";
		}

		if ($_FILES['BackPicture']['name'] != "")
		{
			$sBackPicture = ($iFlipbook."-back-".IO::getFileName($_FILES['BackPicture']['name']));

			if (!@move_uploaded_file($_FILES['BackPicture']['tmp_name'], ($sBaseDir.MDL_PRODUCTS_DIR.$sBackPicture)))
				$sBackPicture = "";
		}



		$sSQL = ("INSERT INTO tbl_flipbooks (id, title, products, users, color, background, `left`, `top`, `width`, front_picture, back_picture, created, created_by, modified, modified_by)
		                             VALUES ('$iFlipbook', '$Title', '".@implode(",", $Products)."', '".@implode(",", $Users)."', '$Color', '$Background', '$Left', '$Top', '$Width', '$sFrontPicture', '$sBackPicture', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')");

		if ($objDb->execute($sSQL) == true)
			redirect("flipbooks.php", "MDL_FLIPBOOK_ADDED");

		else
		{
			@unlink($sBaseDir.MDL_PRODUCTS_DIR.$sFrontPicture);
			@unlink($sBaseDir.MDL_PRODUCTS_DIR.$sBackPicture);

			$_SESSION['Flag'] = "DB_ERROR";
		}
	}

	else
		$_SESSION['Flag'] = "MDL_FLIPBOOK_EXISTS";


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>