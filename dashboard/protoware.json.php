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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$PageId   = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Type     = IO::intValue("Type");
	$Brand    = IO::intValue("Brand");
	$Style    = IO::intValue("Style");
	$Season   = IO::intValue("Season");
	$FromDate = IO::strValue('FromDate');
	$ToDate   = IO::strValue('ToDate');
	$Status   = IO::strValue('Status');
	$Category = IO::intValue("Category");

	$ToDate = str_replace("?callback=onJSONP_Data", "", $ToDate);


	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id > 0 AND id IN ({$_SESSION['Brands']})");
	$sSeasonsList = getList("tbl_seasons", "id", "season", "parent_id>'0'");
	$sTypesList   = getList("tbl_sampling_types", "id", "type");


	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = " WHERE m.style_id=s.id AND m.id=c.merchandising_id AND DATE_FORMAT(c.created, '%Y-%m-%d')<'2013-12-06' ";


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_comment_sheets c, tbl_merchandisings m, tbl_styles s", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT c.created,
	                m.id, m.style_id, m.sample_type_id, m.wash_id, m.status, m.created,
	                s.style, s.sub_brand_id, s.sub_season_id,
	                (SELECT COUNT(*) FROM tbl_style_comments WHERE style_id=s.id) AS _Comments
	         FROM tbl_comment_sheets c, tbl_merchandisings m, tbl_styles s
	         $sConditions
	         ORDER BY m.created DESC
	         LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	$sReportDate = $objDb->getField(($iCount - 1), "c.created");
?>
{
	"timeline":
	{
		"headline":"Protoware",
		"type":"default",
		"text":"Styles Development Timeline",
		"startDate":"<?= date("Y,m,d", strtotime($sReportDate)) ?>",

		"asset":
		{
			"media":"http://portal.3-tree.com/images/logo.png",
			"credit":"-",
			"caption":"-"
		},

		"date":
		[
<?
	$iSlides = array( );

	for ($i = 0; $i < $iCount; $i ++)
		$iSlides[$objDb->getField($i, 'id')] = ($iCount - $i);



	$sInfo    = array( );
	$sSeasons = array( );


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
			case "A" : $sFinalStatus = "Accepted"; break;
			case "R" : $sFinalStatus = "Rejected"; break;
			case "W" : $sFinalStatus = "Working"; break;
		}


		@list($sYear, $sMonth, $sDay) = @explode("-", substr($sRequestDate, 0, 10));

		$sCode = ("M".str_pad($iId, 6, '0', STR_PAD_LEFT));
		$sDir  = ($sBaseDir.SAMPLING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");


		if (@file_exists($sBaseDir.SAMPLING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sCode."_front.jpg"))
		{
			$sPicture = (SITE_URL.SAMPLING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sCode."_front.jpg");

			@mkdir(($sDir.'22x22/'), "0x777");

			if (!@file_exists($sDir.'22x22/'.@basename($sPicture)))
				createImage(($sDir.@basename($sPicture)), ($sDir.'22x22/'.@basename($sPicture)), 22, 22);

			$sThumbnail = (SITE_URL.SAMPLING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/22x22/".$sCode."_front.jpg");
		}

		else if (@file_exists($sBaseDir.SAMPLING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sCode."_back.jpg"))
		{
			$sPicture = (SITE_URL.SAMPLING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sCode."_back.jpg");

			@mkdir(($sDir.'22x22/'), "0x777");

			if (!@file_exists($sDir.'22x22/'.@basename($sPicture)))
				createImage(($sDir.@basename($sPicture)), ($sDir.'22x22/'.@basename($sPicture)), 22, 22);

			$sThumbnail = (SITE_URL.SAMPLING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/22x22/".$sCode."_back.jpg");
		}

		else
		{
			$sPictures = @glob($sDir.$sCode."_*.*");
			$sPictures = @array_map("strtoupper", $sPictures);
			$sPictures = @array_unique($sPictures);


			if (strtotime($sReportDate) <= strtotime(date("2012-12-18 23:59:59")) && count($sPictures) == 0)
			{
				@list($sYear, $sMonth, $sDay) = @explode("-", substr($sReportDate, 0, 10));

				$sMorePictures = @glob($sBaseDir.SAMPLING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sCode."_*.*");
				$sMorePictures = @array_map("strtoupper", $sMorePictures);
				$sMorePictures = @array_unique($sMorePictures);

				$sPictures = array_merge($sPictures, $sMorePictures);
				$sPictures = @array_unique($sPictures);
			}


			if (count($sPictures) > 0)
			{
				$sPicture = (SITE_URL.str_replace($sBaseDir, "", $sPictures[0]));

				@mkdir(($sDir.'22x22/'), "0x777");

				if (!@file_exists($sDir.'22x22/'.@basename($sPictures[0])))
					createImage(($sDir.@basename($sPictures[0])), ($sDir.'22x22/'.@basename($sPictures[0])), 22, 22);

				$sThumbnail = ($sDir.'22x22/'.@basename($sPictures[0]));
				$sThumbnail = (SITE_URL.str_replace($sBaseDir, "", $sThumbnail));
			}

			else
			{
				$sPicture   = (SITE_URL.STYLES_SKETCH_DIR."default.jpg");
				$sThumbnail = $sPicture;
			}
		}


		if (!@in_array("{$iBrand}-{$iSeason}", $sInfo))
		{
			$sInfo["{$iBrand}-{$iSeason}"]["Total"]    = (int)getDbValue("COUNT(*)", "tbl_styles", "sub_brand_id='$iBrand' AND sub_season_id='$iSeason' AND ('$Category'='0' OR category_id='$Category')");
			$sInfo["{$iBrand}-{$iSeason}"]["Approved"] = (int)getDbValue("COUNT(DISTINCT(s.id))", "tbl_styles s, tbl_merchandisings m, tbl_sampling_types t", "s.id=m.style_id AND s.sub_brand_id='$iBrand' AND s.sub_season_id='$iSeason' AND m.status='A' AND t.id=m.sample_type_id AND t.final='Y'");
		}

		if (!@in_array("{$iBrand}-{$iSeason}", $sSeasons))
		{
			$sSeasons["{$iBrand}-{$iSeason}"] = array( );

			$sSQL = "SELECT id FROM tbl_styles WHERE sub_brand_id='$iBrand' AND sub_season_id='$iSeason' ORDER BY id";
			$objDb2->query($sSQL);

			$iCount2 = $objDb2->getCount( );

			for ($j = 0; $j < $iCount2; $j ++)
				$sSeasons["{$iBrand}-{$iSeason}"][] = $objDb2->getField($j, 'id');
		}

		$iStylePosition = (@array_search($iStyle, $sSeasons["{$iBrand}-{$iSeason}"]) + 1);


		$sSamples = ('<span class="'.strtolower($sFinalStatus).' big">'.$sTypesList[$iSampleType].'</span><br /><br />');


		$sSQL = "SELECT m.id, m.sample_type_id, m.status, c.created
				 FROM tbl_comment_sheets c, tbl_merchandisings m, tbl_styles s
				 WHERE m.style_id=s.id AND m.id=c.merchandising_id AND m.style_id='$iStyle' AND m.id<'$iId'
				 ORDER BY m.created DESC";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iId          = $objDb2->getField($j, 'id');
			$iSampleType  = $objDb2->getField($j, "sample_type_id");
			$sStatus      = $objDb2->getField($j, "status");
			$sDate        = $objDb2->getField($j, "created");


			switch ($sStatus)
			{
				case "A" : $sStatusText = "Accepted"; break;
				case "R" : $sStatusText = "Rejected"; break;
				case "W" : $sStatusText = "Working"; break;
			}


			$sUrl = "sampling/dashboard.php";

			if ($Brand > 0 || $Type > 0 || $Season > 0 || $Status != "" || $Category > 0 || $FromDate != "" || $ToDate != "")
				$sUrl = "dashboard/protoware.php?Brand={$Brand}&Type={$Type}&Season={$Season}&Status={$Status}&Category={$Category}&FromDate={$FromDate}&ToDate={$ToDate}";


			if ($iSlides[$iId] > 0)
				$sSamples .= ('<span class="'.strtolower($sStatusText).'">&raquo; <a href="'.$sUrl.'#'.$iSlides[$iId].'">'.$sTypesList[$iSampleType].'</a> <span>('.formatDate($sDate).')</span></span><br />');

			else
				$sSamples .= ('<span class="'.strtolower($sStatusText).'">&raquo; <a href="'.$sUrl.((@strpos($sUrl, '?') !== FALSE) ? '&' : '?').'Style='.$iStyle.'" class="style" rel="'.$iStyle.'">'.$sTypesList[$iSampleType].'</a> <span>('.formatDate($sDate).')</span></span><br />');
//				$sSamples .= ('<span class="'.strtolower($sStatusText).'">&bull; '.$sTypesList[$iSampleType].' <span>('.formatDate($sDate).')</span></span><br />');
		}
?>
			{
				"startDate":"<?= formatDate($sRequestDate, "Y,m,j,G,i:s") ?>",
				"endDate":"<?= formatDate($sReportDate, "Y,m,j,G,i,s") ?>",
				"headline":"<?= $sStyle ?><span class='comments' rel='<?= $iStyle ?>'><?= $iComments ?></span>",
				"tag":"",
				"text":"<p style='overflow:auto; height:250px;<?= (($iCount2 > 10) ? ' border:solid 1px #cccccc; padding:10px; width:200px;' : 'width:220px;') ?>'><?= addslashes($sSamples) ?></p>",
				"asset":
				{
					"media":"<?= $sPicture ?>",
					"thumbnail":"<?= $sThumbnail ?>",
					"credit":"<?= $sBrandsList[$iBrand] ?>, <?= $sSeasonsList[$iSeason] ?>",
					"caption":"<?= $sFinalStatus ?>|<?= $sSeasonsList[$iSeason] ?>|<?= $iStylePosition ?>|<?= $sInfo["{$iBrand}-{$iSeason}"]["Total"] ?>|<?= $sInfo["{$iBrand}-{$iSeason}"]["Approved"] ?>"
				}
			}<?= (($i < ($iCount - 1)) ? "," : "") ?>
<?
	}
?>
		]
	}
}
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>