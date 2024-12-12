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

	$Style    = IO::strValue("Style");
	$Brand    = IO::intValue("Brand");
	$Season   = IO::intValue("Season");
	$Category = IO::intValue("Category");
	$Type     = IO::strValue('Type');
	$FromDate = IO::strValue('FromDate');
	$ToDate   = IO::strValue('ToDate');
	$Status   = IO::strValue('Status');
	$SortBy   = IO::strValue("SortBy");
	$SortBy   = (($SortBy == "") ? "Etd" : $SortBy);


	$sConditions = " WHERE ";

	if ($Brand > 0)
		$sConditions .= " sub_brand_id='$Brand' ";

	else
	{
		$sUserBrands = getDbValue("brands", "tbl_users", "id='$User'");

		$sConditions .= " (sub_brand_id IN ($sUserBrands) AND sub_brand_id!='43') ";
	}

	if ($Style != "")
		$sConditions .= " AND (style LIKE '%$Style%' OR style_name LIKE '%$Style%') ";

	if ($Category > 0)
		$sConditions .= " AND category_id='$Category' ";

	if ($Season > 0)
		$sConditions .= " AND sub_season_id='$Season' ";


	if ($Type > 0 || $Status != "" || ($FromDate != "" && $ToDate != ""))
	{
		$sSQL = "SELECT m.style_id
		         FROM tbl_comment_sheets c, tbl_merchandisings m, tbl_styles s
		         WHERE m.style_id=s.id AND m.id=c.merchandising_id ";

		if ($Brand > 0)
			$sSQL .= " AND s.sub_brand_id='$Brand' ";

		else
			$sSQL .= " AND s.sub_brand_id IN ($sUserBrands) ";

		if ($Type > 0)
			$sSQL .= " AND m.sample_type_id='$Type' ";

		if ($FromDate != "" && $ToDate != "")
			$sSQL .= " AND (DATE_FORMAT(c.created, '%Y-%m-%d') BETWEEN '$FromDate' AND '$ToDate') ";

		if ($Status != "")
			$sSQL .= " AND m.status='$Status' ";

		$objDb->query($sSQL);

		$iCount  = $objDb->getCount( );
		$sStyles = "0";

		for ($i = 0; $i < $iCount; $i ++)
			$sStyles .= (",".$objDb->getField($i, 'style_id'));

		$sConditions .= " AND FIND_IN_SET(id, '$sStyles') ";
	}



	$sSQL = "SELECT id, style, sketch_file, modified,
	                (SELECT COUNT(*) FROM tbl_style_comments WHERE style_id=tbl_styles.id) AS _Comments
	         FROM tbl_styles
	         $sConditions";

	if ($SortBy == "Etd")
		$sSQL .= "";

	else if ($SortBy == "Updated")
		$sSQL .= " ORDER BY modified DESC ";

	else if ($SortBy == "Delayed")
		$sSQL .= "";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$aData  = array( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iStyle      = $objDb->getField($i, 'id');
		$sStyle      = $objDb->getField($i, 'style');
		$sSketchFile = $objDb->getField($i, 'sketch_file');
		$iComments   = $objDb->getField($i, '_Comments');
		$sDateTime   = $objDb->getField($i, 'modified');
/*
		if ($sSketchFile != "" && @file_exists($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile))
			$sSketchFile = (SITE_URL.STYLES_SKETCH_DIR.$sSketchFile);

		else
			$sSketchFile = "";
*/
		if ($sSketchFile == "" || !@file_exists($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile))
			$sSketchFile = (SITE_URL.STYLES_SKETCH_DIR."default.jpg");

		else
		{
			if (!@file_exists($sBaseDir.STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile))
				createImage(($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile), ($sBaseDir.STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile), 160, 160);

			$sSketchFile = (SITE_URL.STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile);
		}


		$aData[] = @implode("|-|", array($iStyle, $sStyle, $sSketchFile, $iComments, ("Updated ".showRelativeTime($sDateTime, "F d, Y"))));
	}


	$sSQL = "SELECT COUNT(DISTINCT(style_id)) FROM tbl_po_colors WHERE style_id IN (SELECT id FROM tbl_styles $sConditions)";
	$objDb->query($sSQL);

	$iProduction = $objDb->getField(0, 0);


	$aResponse = array( );

	$aResponse['Status']     = "OK";
	$aResponse['Total']      = $iCount;
	$aResponse['Production'] = $iProduction;
	$aResponse['Styles']     = @implode("|--|", $aData);


	print @json_encode($aResponse);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>