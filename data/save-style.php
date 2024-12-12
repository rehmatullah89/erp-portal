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


	$sSQL  = ("SELECT * FROM tbl_styles WHERE style LIKE '".IO::strValue("Style")."' AND brand_id='".IO::intValue("Brand")."' AND sub_brand_id='".IO::intValue("SubBrand")."' AND season_id='".IO::intValue("Season")."' AND sub_season_id='".IO::intValue("SubSeason")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_styles");

		if ($_FILES['SpecsFile']['name'] != "")
		{
			$sSpecsFile = ($iId."-".IO::getFileName($_FILES['SpecsFile']['name']));

			if (!@move_uploaded_file($_FILES['SpecsFile']['tmp_name'], ($sBaseDir.STYLES_SPECS_DIR.$sSpecsFile)))
					$sSpecsFile = "";
		}

		if ($_FILES['SketchFile']['name'] != "")
		{
			$sSketchFile = ($iId."-".IO::getFileName($_FILES['SketchFile']['name']));

			if (!@move_uploaded_file($_FILES['SketchFile']['tmp_name'], ($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile)))
					$sSketchFile = "";
		}



		$iCarryOver = IO::intValue("CarryOver");


		$objDb->execute("BEGIN");

		$sSQL  = ("INSERT INTO tbl_styles (id, category_id, style, style_name, reference, brand_id, sub_brand_id, season_id, sub_season_id, program_id, design_no, design_name, block_no, division, carry_over_id, fabric_width, specs_file, sketch_file, measurement_points, sizes, created, created_by, modified, modified_by)
	                               VALUES ('$iId', '".IO::intValue("Category")."', '".IO::strValue("Style")."', '".IO::strValue("StyleName")."', '".IO::strValue("Reference")."', '".IO::intValue("Brand")."', '".IO::intValue("SubBrand")."', '".IO::intValue("Season")."', '".IO::intValue("SubSeason")."', '".IO::intValue("Program")."', '".IO::strValue("DesignNo")."', '".IO::strValue("DesignName")."', '".IO::strValue("BlockNo")."', '".IO::strValue("Division")."', '$iCarryOver', '".IO::intValue("FabricWidth")."', '$sSpecsFile', '$sSketchFile', '', '', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')");
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
		{
			$iLogId = getNextId("tbl_style_log");

			$sSQL  = ("INSERT INTO tbl_style_log (id, style_id, user_id, date_time, reason, remarks, specs_file) VALUES ('$iLogId', '$iId', '{$_SESSION['UserId']}', NOW( ), 'D', 'Style Entry', '$sSpecsFile')");
			$bFlag = $objDb->execute($sSQL);
		}

		if ($bFlag == true && $iCarryOver > 0)
		{
			$sSQL = "SELECT specs_file, sketch_file, measurement_points, sizes FROM tbl_styles WHERE id='$iCarryOver'";
			$objDb->query($sSQL);

			$sCarrySpecsFile  = $objDb->getField(0, 'specs_file');
			$sCarrySketchFile = $objDb->getField(0, 'sketch_file');
			$sMPs             = $objDb->getField(0, 'measurement_points');
			$sSizes           = $objDb->getField(0, 'sizes');


			$sNewSpecsFile  = "{$iId}-{$sCarrySpecsFile}";
			$sNewSketchFile = "{$iId}-{$sCarrySketchFile}";


			@copy(($sBaseDir.STYLES_SPECS_DIR.$sCarrySpecsFile), ($sBaseDir.STYLES_SPECS_DIR.$sNewSpecsFile));
			@copy(($sBaseDir.STYLES_SKETCH_DIR.$sCarrySketchFile), ($sBaseDir.STYLES_SKETCH_DIR.$sNewSketchFile));


			$sSQL  = "UPDATE tbl_styles SET specs_file='$sNewSpecsFile', sketch_file='$sNewSketchFile', measurement_points='$sMPs', sizes='$sSizes' WHERE id='$iId'";
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == true)
			{
				$sSQL  = "INSERT INTO tbl_style_specs (id, style_id, point_id, size_id, specs, version)
				                               (SELECT id, '$iId', point_id, size_id, specs, '0' FROM tbl_style_specs WHERE style_id='$iCarryOver' AND version='0' ORDER BY id)";
				$bFlag = $objDb->execute($sSQL);
			}
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			if ($sSketchFile != "")
			{
				createImage(($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile), ($sBaseDir.STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile), 160, 160);

				redirect("resize-sketch-file.php?Id={$iId}&Referer={$_SERVER['HTTP_REFERER']}", "STYLE_ADDED");
			}

			else
				redirect($_SERVER['HTTP_REFERER'], "STYLE_ADDED");
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION['Flag'] = "DB_ERROR";

			@unlink($sBaseDir.STYLES_SPECS_DIR.$sSpecsFile);
			@unlink($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile);
		}
	}

	else
		$_SESSION['Flag'] = "STYLE_EXISTS";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>