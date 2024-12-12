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

	if ($sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Id         	 = IO::intValue("Id");
	$Style         	 = IO::strValue("Style");
	$Color     		 = IO::strValue("Color");
	$Gender 		 = IO::strValue("Gender");
	$Fabric          = IO::strValue("Fabric");
	$FabricContents	 = IO::strValue("FabricContents");
	$Wash   	     = IO::strValue("Wash");
	$Weight   	     = IO::strValue("Weight");
	$Price   	     = IO::strValue("Price");
	$Description   	 = IO::strValue("Description");
	$OldPictureLeft  = IO::strValue("PictureLeft");
	$OldPictureRight = IO::strValue("PictureRight");


	$sSQL = "SELECT * FROM tbl_fb_products WHERE style LIKE '$sStyle' AND color LIKE'$sColor' AND id!='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$sPictureLeftSql  = "";
		$sPictureRightSql = "";

		if ($_FILES['PictureLeft']['name'] != "")
		{
			$sPictureLeft = ($Id."-left-".IO::getFileName($_FILES['PictureLeft']['name']));

			if (@move_uploaded_file($_FILES['PictureLeft']['tmp_name'], ($sBaseDir.MDL_PRODUCTS_DIR.$sPictureLeft)))
				$sPictureLeftSql = ", picture_left='$sPictureLeft' ";
		}

		if ($_FILES['PictureRight']['name'] != "")
		{
			$sPictureRight = ($Id."-right-".IO::getFileName($_FILES['PictureRight']['name']));

			if (@move_uploaded_file($_FILES['PictureRight']['tmp_name'], ($sBaseDir.MDL_PRODUCTS_DIR.$sPictureRight)))
				$sPictureRightSql = ", picture_right='$sPictureRight' ";
		}


		$sSQL = "UPDATE tbl_fb_products SET style='$Style', color='$Color', gender='$Gender', fabric='$Fabric', fabric_contents='$FabricContents',
		                                    wash='$Wash', weight='$Weight', price='$Price', description='$Description', modified=NOW( ), modified_by='{$_SESSION['UserId']}'
		                                    $sPictureLeftSql
		                                    $sPictureRightSql
		         WHERE id='$Id'";

		if ($objDb->execute($sSQL) == true)
		{
			if ($sPictureLeft != "" && $OldPictureLeft != "" && $sPictureLeft != $OldPictureLeft)
				@unlink($sBaseDir.MDL_PRODUCTS_DIR.$OldPictureLeft);

			if ($sPictureRight != "" && $OldPictureRight != "" && $sPictureRight != $OldPictureRight)
				@unlink($sBaseDir.MDL_PRODUCTS_DIR.$OldPictureRight);

			redirect("products.php", "MDL_PRODUCT_UPDATED");
		}

		else
		{
			if ($sPictureLeft != "" && $sPictureLeft != $OldPictureLeft)
				@unlink($sBaseDir.MDL_PRODUCTS_DIR.$sPictureLeft);

			if ($sPictureRight != "" && $sPictureRight != $OldPictureRight)
				@unlink($sBaseDir.MDL_PRODUCTS_DIR.$sPictureRight);

			$_SESSION['Flag'] = "DB_ERROR";
		}
	}

	else
		$_SESSION['Flag'] = "MDL_PRODUCT_EXISTS";


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>