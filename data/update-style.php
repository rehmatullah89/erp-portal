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

	if ($sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$Id            = IO::intValue("Id");
	$OldSpecsFile  = IO::strValue("OldSpecsFile");
	$OldSketchFile = IO::strValue("OldSketchFile");
	$Referer       = urlencode(IO::strValue("Referer"));


	$sSQL  = ("SELECT * FROM tbl_styles WHERE style LIKE '".IO::strValue("Style")."' AND brand_id='".IO::intValue("Brand")."' AND sub_brand_id='".IO::intValue("SubBrand")."' AND season_id='".IO::intValue("Season")."' AND sub_season_id='".IO::intValue("SubSeason")."' AND id!='$Id'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$sSpecsFileSql  = "";
		$sSketchFileSql = "";


		$objDb->execute("BEGIN");

		if ($_FILES['SpecsFile']['name'] != "")
		{
			$sSpecsFile    = ($Id."-".IO::getFileName($_FILES['SpecsFile']['name']));
			$sOldSpecsFile = $OldSpecsFile;

			if ($sSpecsFile == $sOldSpecsFile)
				$sSpecsFile = ($Id."-".date("YmdHis")."-".IO::getFileName($_FILES['SpecsFile']['name']));


			if (@move_uploaded_file($_FILES['SpecsFile']['tmp_name'], ($sBaseDir.STYLES_SPECS_DIR.$sSpecsFile)))
				$sSpecsFileSql = ", specs_file='$sSpecsFile' ";
		}

		if ($_FILES['SketchFile']['name'] != "")
		{
			$sSketchFile = ($Id."-".IO::getFileName($_FILES['SketchFile']['name']));

			if (@move_uploaded_file($_FILES['SketchFile']['tmp_name'], ($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile)))
				$sSketchFileSql = ", sketch_file='$sSketchFile' ";
		}


		$iOldCarryOver = getDbValue("carry_over_id", "tbl_styles", "id='$Id'");
		$iCarryOver    = IO::intValue("CarryOver");


		$sSQL  = ("UPDATE tbl_styles SET category_id   = '".IO::intValue("Category")."',
		                                 style         = '".IO::strValue("Style")."',
		                                 style_name    = '".IO::strValue("StyleName")."',
		                                 reference     = '".IO::strValue("Reference")."',
		                                 brand_id      = '".IO::intValue("Brand")."',
		                                 sub_brand_id  = '".IO::intValue("SubBrand")."',
		                                 season_id     = '".IO::intValue("Season")."',
		                                 sub_season_id = '".IO::intValue("SubSeason")."',
		                                 program_id    = '".IO::intValue("Program")."',
		                                 design_no     = '".IO::strValue("DesignNo")."',
		                                 design_name   = '".IO::strValue("DesignName")."',
										 block_no      = '".IO::strValue("BlockNo")."',
										 division      = '".IO::strValue("Division")."',
		                                 carry_over_id = '$iCarryOver',
		                                 fabric_width  = '".IO::intValue("FabricWidth")."',
		                                 modified      = NOW( ),
		                                 modified_by   = '{$_SESSION['UserId']}'
		                                 $sSpecsFileSql
		                                 $sSketchFileSql
		           WHERE id='$Id'");
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
		{
			$sSQL  = "UPDATE tbl_po SET brand_id='".IO::intValue("SubBrand")."' WHERE '$Id' IN (styles)";
			$bFlag = $objDb->execute($sSQL);
		}


		if ($bFlag == true && $iOldCarryOver != $iCarryOver && $iCarryOver > 0)
		{
			$sSQL  = "DELETE FROM tbl_style_specs WHERE style_id='$Id'";
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == true)
			{
				$sSQL = "SELECT specs_file, sketch_file, measurement_points, sizes FROM tbl_styles WHERE id='$iCarryOver'";
				$objDb->query($sSQL);

				$sSpecsFile  = $objDb->getField(0, 'specs_file');
				$sSketchFile = $objDb->getField(0, 'sketch_file');
				$sMPs        = $objDb->getField(0, 'measurement_points');
				$sSizes      = $objDb->getField(0, 'sizes');

				$sNewSpecsFile  = "{$Id}-{$sSpecsFile}";
				$sNewSketchFile = "{$Id}-{$sSketchFile}";


				@copy(($sBaseDir.STYLES_SPECS_DIR.$sSpecsFile), ($sBaseDir.STYLES_SPECS_DIR.$sNewSpecsFile));
				@copy(($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile), ($sBaseDir.STYLES_SKETCH_DIR.$sNewSketchFile));


				$sSQL  = "UPDATE tbl_styles SET specs_file='$sNewSpecsFile', sketch_file='$sNewSketchFile', measurement_points='$sMPs', sizes='$sSizes' WHERE id='$Id'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = "INSERT INTO tbl_style_specs (id, style_id, point_id, size_id, specs, version) (SELECT id, '$Id', point_id, size_id, specs, '0' FROM tbl_style_specs WHERE style_id='$iCarryOver' AND version='0' ORDER BY id)";
				$bFlag = $objDb->execute($sSQL);
			}
		}


		if ($bFlag == true)
		{
			$iLogId = getNextId("tbl_style_log");

			$sSQL  = ("INSERT INTO tbl_style_log (id, style_id, user_id, date_time, reason, remarks, specs_file) VALUES ('$iLogId', '$Id', '{$_SESSION['UserId']}', NOW( ), '".IO::strValue("Reason")."', '".IO::strValue("Remarks")."', '$sSpecsFile')");
			$bFlag = $objDb->execute($sSQL);
		}


		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			if ($sSketchFile != "" && $OldSketchFile != "" && $sSketchFile != $OldSketchFile)
				@unlink($sBaseDir.STYLES_SKETCH_DIR.$OldSketchFile);


			if ($sSketchFileSql != "")
			{
				@unlink(($sBaseDir.STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile));
				createImage(($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile), ($sBaseDir.STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile), 160, 160);

				redirect(("resize-sketch-file.php?Id={$Id}&Referer=".urldecode($Referer)), "STYLE_UPDATED");
			}

			else
				redirect(urldecode($Referer), "STYLE_UPDATED");
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION['Flag'] = "DB_ERROR";

			if ($sSpecsFile != "" && $sSpecsFile != $OldSpecsFile)
				@unlink($sBaseDir.STYLES_SPECS_DIR.$sSpecsFile);

			if ($sSketchFile != "" && $sSketchFile != $OldSketchFile)
				@unlink($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile);
		}
	}

	else
		$_SESSION['Flag'] = "STYLE_EXISTS";


	header("Location: edit-style.php?Id={$Id}&Referer={$Referer}");


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>