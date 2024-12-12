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


	$Id              = IO::intValue("Id");
	$Title           = IO::strValue("Title");
	$Color           = IO::strValue("Color");
	$Background      = IO::strValue("Background");
	$Left            = IO::intValue("Left");
	$Top             = IO::intValue("Top");
	$Width           = IO::intValue("Width");
	$Products        = IO::getArray("SelectedProducts");
	$Users           = IO::getArray("SelectedUsers");
	$OldFrontPicture = IO::strValue("FrontPicture");
	$OldBackPicture  = IO::strValue("BackPicture");


	$sSQL = "SELECT * FROM tbl_flipbooks WHERE title like '$Title' AND id!='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$sFrontPictureSql = "";
		$sBackPictureSql  = "";

		if ($_FILES['FrontPicture']['name'] != "")
		{
			$sFrontPicture = ($Id."-front-".IO::getFileName($_FILES['FrontPicture']['name']));

			if (@move_uploaded_file($_FILES['FrontPicture']['tmp_name'], ($sBaseDir.MDL_PRODUCTS_DIR.$sFrontPicture)))
				$sFrontPictureSql = ", front_picture='$sFrontPicture' ";
		}

		if ($_FILES['BackPicture']['name'] != "")
		{
			$sBackPicture = ($Id."-back-".IO::getFileName($_FILES['BackPicture']['name']));

			if (@move_uploaded_file($_FILES['BackPicture']['tmp_name'], ($sBaseDir.MDL_PRODUCTS_DIR.$sBackPicture)))
				$sBackPictureSql = ", back_picture='$sBackPicture' ";
		}


		$sSQL = "UPDATE tbl_flipbooks SET title='$Title', products='".@implode(',', $Products)."', users='".@implode(',',$Users)."',
		                                  `left`='$Left', `top`='$Top', `width`='$Width',
		                                  color='$Color', background='$Background', modified=NOW( ), modified_by='{$_SESSION['UserId']}'
		                                  $sFrontPictureSql
		                                  $sBackPictureSql
		         WHERE id='$Id'";

		if ($objDb->execute($sSQL) == true)
		{
			if ($sFrontPicture != "" && $OldFrontPicture != "" && $sFrontPicture != $OldFrontPicture)
				@unlink($sBaseDir.MDL_PRODUCTS_DIR.$OldFrontPicture);

			if ($sBackPicture != "" && $OldBackPicture != "" && $sBackPicture != $OldBackPicture)
				@unlink($sBaseDir.MDL_PRODUCTS_DIR.$OldBackPicture);

			redirect("flipbooks.php", "MDL_FLIPBOOK_UPDATED");
		}

		else
		{
			if ($sFrontPicture != "" && $sFrontPicture != $OldFrontPicture)
				@unlink($sBaseDir.MDL_PRODUCTS_DIR.$sFrontPicture);

			if ($sBackPicture != "" && $sBackPicture != $OldBackPicture)
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