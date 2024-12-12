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

	$Style         	= IO::strValue("Style");
	$Color     		= IO::strValue("Color");
	$Gender 		= IO::strValue("Gender");
	$Fabric         = IO::strValue("Fabric");
	$FabricContents	= IO::strValue("FabricContents");
	$Weight   	    = IO::strValue("Weight");
	$Wash   	    = IO::strValue("Wash");
	$Price   	    = IO::strValue("Price");
	$Description   	= IO::strValue("Description");


	$sSQL = "SELECT * FROM tbl_fb_products WHERE style LIKE '$Style' AND color LIKE '$Color'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iProduct = getNextId("tbl_fb_products");


		if ($_FILES['PictureLeft']['name'] != "")
		{
			$sPictureLeft = ($iProduct."-left-".IO::getFileName($_FILES['PictureLeft']['name']));

			if (!@move_uploaded_file($_FILES['PictureLeft']['tmp_name'], ($sBaseDir.MDL_PRODUCTS_DIR.$sPictureLeft)))
				$sPictureLeft = "";
		}

		if ($_FILES['PictureRight']['name'] != "")
		{
			$sPictureRight = ($iProduct."-left-".IO::getFileName($_FILES['PictureRight']['name']));

			if (!@move_uploaded_file($_FILES['PictureRight']['tmp_name'], ($sBaseDir.MDL_PRODUCTS_DIR.$sPictureRight)))
				$sPictureRight = "";
		}



		$sSQL  = ("INSERT INTO tbl_fb_products (id, style, color, gender, fabric, fabric_contents, wash, weight, price, description, picture_left, picture_right, created, created_by, modified, modified_by)
		                                 VALUES ('$iProduct', '$Style', '$Color', '$Gender', '$Fabric', '$FabricContents', '$Wash', '$Weight', '$Price', '$Description', '$sPictureLeft', '$sPictureRight', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')");

		if ($objDb->execute($sSQL) == true)
			redirect("products.php", "MDL_PRODUCT_ADDED");

		else
		{
			@unlink($sBaseDir.MDL_PRODUCTS_DIR.$sPictureLeft);
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