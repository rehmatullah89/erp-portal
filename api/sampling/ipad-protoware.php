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
	$objDb2      = new Database( );

	$User     = IO::intValue("User");
	$Brand    = IO::intValue("Brand");
	$Season   = IO::intValue("Season");
	$Type     = IO::intValue("Type");
	$Category = IO::intValue("Category");
	$FromDate = IO::strValue('FromDate');
	$ToDate   = IO::strValue('ToDate');
	$Status   = IO::strValue('Status');
	$Style    = IO::intValue('Style');


	$sUserBrands = getDbValue("brands", "tbl_users", "id='$User'");

	if ($Type == 0 && $Brand == 0 && $Category == 0 && $Season == 0 && $FromDate == "" && $ToDate == "" && $Status == "")
	{
		$FromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 14), date("Y")));
		$ToDate   = date("Y-m-d");
	}


	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id > 0 AND id IN ($sUserBrands)");
	$sSeasonsList = getList("tbl_seasons", "id", "season", "parent_id>'0'");
	$sTypesList   = getList("tbl_sampling_types", "id", "type");


	$sConditions = " WHERE m.style_id=s.id AND m.id=c.merchandising_id";

	if ($Brand > 0)
		$sConditions .= " AND sub_brand_id='$Brand' ";

	else
		$sConditions .= " AND FIND_IN_SET(sub_brand_id, '$sUserBrands') ";

	if ($Type > 0)
		$sConditions .= " AND m.sample_type_id='$Type' ";

	if ($Season > 0)
		$sConditions .= " AND s.sub_season_id='$Season' ";

	if ($Style > 0)
		$sConditions .= " AND s.id='$Style' ";

	else if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND (DATE_FORMAT(c.created, '%Y-%m-%d') BETWEEN '$FromDate' AND '$ToDate') ";

	if ($Status != "")
		$sConditions .= " AND m.status='$Status' ";

	if ($Category > 0)
		$sConditions .= " AND s.category_id='$Category' ";



	$sSQL = "SELECT c.created,
	                m.id, m.style_id, m.sample_type_id, m.wash_id, m.status, m.created,
	                s.style, s.sub_brand_id, s.sub_season_id,
	                (SELECT COUNT(*) FROM tbl_style_comments WHERE style_id=s.id) AS _Comments
	         FROM tbl_comment_sheets c, tbl_merchandisings m, tbl_styles s
	         $sConditions
	         ORDER BY m.created DESC";
	$objDb->query($sSQL);

	$iCount   = $objDb->getCount( );
	$aData    = array( );
	$aInfo    = array( );
	$aSeasons = array( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId          = $objDb->getField($i, 'id');
		$sRequestDate = $objDb->getField($i, "m.created");
		$sReportDate  = $objDb->getField($i, "c.created");
		$iSampleType  = $objDb->getField($i, "sample_type_id");
		$iStyle       = $objDb->getField($i, 'style_id');
		$sStyle       = $objDb->getField($i, 'style');
		$iBrand       = $objDb->getField($i, 'sub_brand_id');
		$iSeason      = $objDb->getField($i, 'sub_season_id');
		$sStatus      = $objDb->getField($i, "status");
		$iComments    = $objDb->getField($i, "_Comments");


		switch ($sStatus)
		{
			case "A" : $sStatus = "Accepted"; break;
			case "R" : $sStatus = "Rejected"; break;
			case "W" : $sStatus = "Working"; break;
		}


		@list($sYear, $sMonth, $sDay) = @explode("-", substr($sRequestDate, 0, 10));

		$sCode = ("M".str_pad($iId, 6, '0', STR_PAD_LEFT));
		$sDir  = ($sBaseDir.SAMPLING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");

		$sPictures = @glob($sDir.$sCode."_*.*");
		$sPictures = @array_map("strtoupper", $sPictures);
		$sPictures = @array_unique($sPictures);

		if (strtotime($sReportDate) <= strtotime(date("2012-12-18 23:59:59")))
		{
			@list($sYear, $sMonth, $sDay) = @explode("-", substr($sReportDate, 0, 10));

			$sMorePictures = @glob($sBaseDir.SAMPLING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sCode."_*.*");
			$sMorePictures = @array_map("strtoupper", $sMorePictures);
			$sMorePictures = @array_unique($sMorePictures);

			$sPictures = array_merge($sPictures, $sMorePictures);
		}

		if (count($sPictures) > 0)
		{
			$sPicture = (SITE_URL.str_replace($sBaseDir, "", $sPictures[0]));

			@mkdir(($sDir.'22x22/'), "0x777");

			if (!@file_exists($sDir.'22x22/'.@basename($sPictures[0])))
				createImage(($sDir.basename($sPictures[0])), ($sDir.'22x22/'.basename($sPictures[0])), 22, 22);

			$sThumbnail = ($sDir.'22x22/'.@basename($sPictures[0]));
			$sThumbnail = (SITE_URL.str_replace($sBaseDir, "", $sThumbnail));
		}

		else
		{
			$sPicture   = (SITE_URL.STYLES_SKETCH_DIR."default.jpg");
			$sThumbnail = $sPicture;
		}


		if (!@in_array("{$iBrand}-{$iSeason}", $aInfo))
		{
			$aInfo["{$iBrand}-{$iSeason}"]["Total"]    = (int)getDbValue("COUNT(*)", "tbl_styles", "sub_brand_id='$iBrand' AND sub_season_id='$iSeason'");
			$aInfo["{$iBrand}-{$iSeason}"]["Approved"] = (int)getDbValue("COUNT(DISTINCT(s.id))", "tbl_styles s, tbl_merchandisings m, tbl_sampling_types t", "s.id=m.style_id AND s.sub_brand_id='$iBrand' AND s.sub_season_id='$iSeason' AND m.status='A' AND t.id=m.sample_type_id AND t.final='Y'");
		}


		if (!@in_array("{$iBrand}-{$iSeason}", $aSeasons))
		{
			$aSeasons["{$iBrand}-{$iSeason}"] = array( );

			$sSQL = "SELECT id FROM tbl_styles WHERE sub_brand_id='$iBrand' AND sub_season_id='$iSeason' ORDER BY id";
			$objDb2->query($sSQL);

			$iCount2 = $objDb2->getCount( );

			for ($j = 0; $j < $iCount2; $j ++)
				$aSeasons["{$iBrand}-{$iSeason}"][] = $objDb2->getField($j, 'id');
		}

		$iStylePosition = (@array_search($iStyle, $aSeasons["{$iBrand}-{$iSeason}"]) + 1);



		$aData[] = @implode("|-|", array($iId, $iStyle, $sStyle, $sBrandsList[$iBrand], $sSeasonsList[$iSeason], $sTypesList[$iSampleType], $sRequestDate, $sReportDate, $sStatus, $sThumbnail, $sPicture, $iComments, $iStylePosition, $aInfo["{$iBrand}-{$iSeason}"]["Total"], $aInfo["{$iBrand}-{$iSeason}"]["Approved"]));
	}


	$aLegends = array( );

	if ($Brand > 0)
	{
		$iParent = getDbValue("parent_id", "tbl_brands", "id='$Brand'");


		$sSQL = "SELECT type, color FROM tbl_sampling_types WHERE brand_id='$iParent' ORDER by position";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sType  = $objDb->getField($i, "type");
			$sColor = $objDb->getField($i, "color");

			$aLegends[] = @implode("|-|", array($sType, $sColor));
		}
	}



	$aResponse = array( );

	$aResponse['Status']  = "OK";
	$aResponse['Data']    = @implode("|--|", $aData);
	$aResponse['Legends'] = @implode("|--|", $aLegends);

	print @json_encode($aResponse);


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>