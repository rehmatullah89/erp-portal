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

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$sCategoriesList = getList("tbl_style_categories", "id", "category");
	$sBrandsList     = getList("tbl_brands", "id", "brand", "parent_id>'0'");
	$sSeasonsList    = getList("tbl_seasons", "id", "season", "parent_id>'0'");
	$sProgramsList   = getList("tbl_programs", "id", "program", "", "id");

	$Style     = IO::strValue("Style");
	$Brand     = IO::strValue("Brand");
	$SubBrand  = IO::strValue("SubBrand");
	$Season    = IO::strValue("Season");
	$SubSeason = IO::strValue("SubSeason");
	$Sampling  = IO::strValue("Sampling");


	$sExcelFile = ($sBaseDir."temp/styles.csv");

	$hFile = @fopen($sExcelFile, 'w');
	@fwrite($hFile, ('"Style #","Style Name","Category","Brand","Season","Program","Design No","Design Name"'."\n"));


	$sConditions = "";

	if ($Style != "")
		$sConditions .= " AND (style LIKE '%$Style%' OR style_name LIKE '%$Style%' OR design_no LIKE '%$Style%' OR design_name LIKE '%$Style%') ";

	if ($Brand > 0)
		$sConditions .= " AND brand_id='$Brand' ";

	if ($SubBrand > 0)
		$sConditions .= " AND sub_brand_id='$SubBrand' ";

	else
		$sConditions .= " AND FIND_IN_SET(sub_brand_id, '{$_SESSION['Brands']}') ";

	if ($Season > 0)
		$sConditions .= " AND season_id='$Season' ";

	if ($SubSeason > 0)
		$sConditions .= " AND sub_season_id='$SubSeason' ";

	if ($Sampling == "Y")
	{
		$sConditions .= " AND id NOT IN (SELECT DISTINCT(m.style_id)
		                                 FROM tbl_merchandisings m, tbl_styles s
		                                 WHERE m.style_id=s.id";

		if ($SubBrand > 0)
			$sConditions .= " AND s.sub_brand_id='$SubBrand' ";

		else
			$sConditions .= " AND FIND_IN_SET(s.sub_brand_id, '{$_SESSION['Brands']}') ";

		if ($Season > 0)
			$sConditions .= " AND s.season_id='$Season' ";

		if ($SubSeason > 0)
			$sConditions .= " AND s.sub_season_id='$SubSeason' ";

		$sConditions .= " )";
	}

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));


	$sSQL = "SELECT * FROM tbl_styles $sConditions ORDER BY style";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sStyle      = $objDb->getField($i, 'style');
		$sStyleName  = $objDb->getField($i, 'style_name');
		$iCategory   = $objDb->getField($i, 'category_id');
		$iBrand      = $objDb->getField($i, 'sub_brand_id');
		$iSeason     = $objDb->getField($i, 'sub_season_id');
		$iProgram    = $objDb->getField($i, 'program_id');
		$sDesignNo   = $objDb->getField($i, 'design_no');
		$sDesignName = $objDb->getField($i, 'design_name');


		$sLine = ('"'.
		          $sStyle.'","'.
		          $sStyleName.'","'.
				  $sCategoriesList[$iCategory].'","'.
				  $sBrandsList[$iBrand].'","'.
				  $sSeasonsList[$iSeason].'","'.
				  $sProgramsList[$iProgram].'","'.
				  $sDesignNo.'","'.
				  $sDesignName.'"'.
				"\n");

		@fwrite($hFile, $sLine);
	}


	@fclose($hFile);

	$objDb->close( );
	$objDbGlobal->close( );


	// forcing csv file to download
	$iSize = @filesize($sExcelFile);

	if(ini_get('zlib.output_compression'))
		@ini_set('zlib.output_compression', 'Off');

	header('Content-Description: File Transfer');
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private", false);
	header('Content-Type: application/force-download');
	header("Content-Type: application/download");
	header("Content-Type: text/csv");
	header("Content-Disposition: attachment; filename=\"".basename($sExcelFile)."\";");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: $iSize");

	@readfile($sExcelFile);
	@unlink($sExcelFile);

	@ob_end_flush( );
?>