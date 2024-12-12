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

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$OtFile = "";
	$Vendor = IO::intValue("Vendor");
	$Month  = IO::intValue("Month");
	$Year   = IO::intValue("Year");

	if ($_FILES['OtFile']['name'] != "")
	{
		$OtFile = IO::getFileName($_FILES['OtFile']['name']);

		if (!@move_uploaded_file($_FILES['OtFile']['tmp_name'], ($sBaseDir.TEMP_DIR.$OtFile)))
				$OtFile = "";
	}

	if ($OtFile == "")
		redirect("ot-data.php", "NO_OT_FILE");


	if (substr($OtFile, -5) == ".xlsx")
	{
		$iCategoryId = getDbValue("category_id", "tbl_vendors", "id='$Vendor'");


		if ($iCategoryId == 5)
			@include($sBaseDir."includes/crc/import-equipment-ot-data.php");

		else if ($iCategoryId == 4)
			@include($sBaseDir."includes/crc/import-apparel-ot-data.php");
	}

	else
		$_SESSION["Flag"] = "INVALID_OT_FILE";


	@unlink($sBaseDir.TEMP_DIR.$OtFile);


	redirect($_SERVER['HTTP_REFERER']);


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>