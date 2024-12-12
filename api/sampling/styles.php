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

	$User     = IO::intValue("User");
	$Style    = IO::strValue("Style");
	$Brand    = IO::strValue("Brand");
	$Season   = IO::intValue("Season");
	$Category = IO::strValue("Category");
	$SortBy   = IO::strValue("SortBy");
	$SortBy   = (($SortBy == "") ? "Etd" : $SortBy);


	if (IO::strValue("StyleNo") != "")
		$Style = IO::strValue("StyleNo");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");
?>
  <script type="text/javascript" src="api/sampling/scripts/jquery.dropdown.js"></script>
  <script type="text/javascript" src="api/sampling/scripts/styles.js"></script>
</head>

<body>

<div id="MainDiv">
  <input type="hidden" id="User" value="<?= $User ?>" />

<?
	$sConditions = "";

	if ($Style != "")
		$sConditions .= " AND (style LIKE '%$Style%' OR style_name LIKE '%$Style%') ";

	if ($Brand > 0)
		$sConditions .= " AND sub_brand_id='$Brand' ";

	else
	{
		$sUserBrands = getDbValue("brands", "tbl_users", "id='$User'");

		$sConditions .= " AND sub_brand_id IN ($sUserBrands) ";
	}

	if ($Category > 0)
		$sConditions .= " AND category_id='$Category' ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));


	$sStyles  = "0";
	$sSeasons = "0";

	$sSQL = "SELECT id, sub_season_id FROM tbl_styles $sConditions ORDER BY id DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iSeason = $objDb->getField($i, 'sub_season_id');

		if ($Season == 0 || $Season == $iSeason)
			$sStyles  .= (",".$objDb->getField($i, 'id'));

		$sSeasons .= ",{$iSeason}";
	}


	$sSQL = "SELECT COUNT(DISTINCT(style_id)) FROM tbl_po_colors WHERE FIND_IN_SET(style_id, '$sStyles')";
	$objDb->query($sSQL);

	$iProduction = $objDb->getField(0, 0);



	$sSQL = "SELECT id, style, sketch_file, modified,
	                (SELECT COUNT(*) FROM tbl_style_comments WHERE style_id=tbl_styles.id) AS _Comments
	         FROM tbl_styles
	         WHERE FIND_IN_SET(id, '$sStyles')";

	if ($SortBy == "Etd")
		$sSQL .= "";

	else if ($SortBy == "Updated")
		$sSQL .= " ORDER BY modified DESC ";

	else if ($SortBy == "Delayed")
		$sSQL .= "";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );



	$sPageUrl = (SITE_URL.substr($_SERVER['REQUEST_URI'], 1));
	$sPageUrl = str_replace("Season=&", "Season=0&", $sPageUrl);

	$sSeasonsList = getList("tbl_seasons", "id", "season", "parent_id > 0 AND FIND_IN_SET(id, '$sSeasons')");
?>
  <table border="0" cellspacing="0" cellpadding="0" width="102%">
    <tr valign="top">
      <td bgcolor="#a60800"><h1><?= (($Brand > 0) ? getDbValue("brand", "tbl_brands", "id='$Brand'") : "Styles Listing") ?></h1></td>

      <td width="170" bgcolor="#ffffff">
		<div id="Seasons" class="dropdown">
		  <a class="dropdown-button button"><?= (($Season > 0) ? $sSeasonsList[$Season] : "Seasons") ?></a>

		  <div class="dropdown-panel">
			<ul>
<?
	foreach ($sSeasonsList as $iSeason => $sSeason)
	{
?>
			  <li><a href="<?= str_replace("Season={$Season}", "Season={$iSeason}", $sPageUrl) ?>"><?= $sSeason ?></a></li>
<?
	}
?>
			</ul>
		  </div>
		</div>
      </td>
    </tr>
  </table>


  <div id="StyleInfo">
    Total No of Styles<?= (($Season > 0) ? " '<b>{$sSeasonsList[$Season]}</b>' " : "") ?>: <?= $iCount ?><br />
    Approved for Production: <?= $iProduction ?><br />
    Pending Approvals: <?= ($iCount - $iProduction) ?><br />
  </div>


  <table border="0" cellspacing="0" cellpadding="0" width="102%">
    <tr valign="top">
      <td width="80" bgcolor="#ffffff"><h1 class="white medium">Sort By</h1></td>

<?
	$sPageUrl = (SITE_URL.substr($_SERVER['REQUEST_URI'], 1));

	if (@strpos($sPageUrl, "&SortBy=") !== FALSE)
	{
		$sPageUrl = str_replace("&SortBy=Etd", "&SortBy=", $sPageUrl);
		$sPageUrl = str_replace("&SortBy=Updated", "&SortBy=", $sPageUrl);
		$sPageUrl = str_replace("&SortBy=Delayed", "&SortBy=", $sPageUrl);
	}

	else
		$sPageUrl .= "&SortBy=";
?>
      <td bgcolor="#a60800">
        <table border="0" cellspacing="0" cellpadding="0" width="100%">
          <tr>
            <td width="5"></td>
            <td width="15"><input type="radio" class="sortby" name="SortBy" value="<?= $sPageUrl ?>Etd" <?= (($SortBy == "Etd") ? "checked" : "") ?> style="margin:0px; padding:0px;" /></td>
            <td width="46"><h2 style="padding-bottom:5px;"><a href="<?= $sPageUrl ?>Etd" style="font-size:12px;">ETD</a></h2></td>
            <td width="15"><input type="radio" class="sortby" name="SortBy" value="<?= $sPageUrl ?>Updated" <?= (($SortBy == "Updated") ? "checked" : "") ?> style="margin:0px; padding:0px;" /></td>
            <td width="135"><h2 style="padding-bottom:5px;"><a href="<?= $sPageUrl ?>Updated" style="font-size:12px;">Recently Updated</a></h2></td>
            <td width="15"><input type="radio" class="sortby" name="SortBy" value="<?= $sPageUrl ?>Delayed" <?= (($SortBy == "Delayed") ? "checked" : "") ?> style="margin:0px; padding:0px;" /></td>
            <td><h2 style="padding-bottom:5px;"><a href="<?= $sPageUrl ?>Delayed" style="font-size:12px;">Delayed</a></h2></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>


  <div id="StylesListing">
    <div id="Scroller">
      <ul>
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId         = $objDb->getField($i, 'id');
		$sStyle      = $objDb->getField($i, 'style');
		$sSketchFile = $objDb->getField($i, 'sketch_file');
		$iComments   = $objDb->getField($i, '_Comments');
		$sDateTime   = $objDb->getField($i, 'modified');

		if ($sSketchFile == "" || !@file_exists($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile))
			$sSketchFile = (SITE_URL.STYLES_SKETCH_DIR."default.jpg");

		else
		{
			if (!@file_exists($sBaseDir.STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile))
				createImage(($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile), ($sBaseDir.STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile), 160, 160);

			$sSketchFile = (SITE_URL.STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile);
		}

		$iSelected = getDbValue("COUNT(*)", "tbl_basket", "user_id='$User' AND style_id='$iId'");
?>
	    <li>
	      <a href="api/sampling/style-details.php?Id=<?= $iId ?>&User=<?= $User ?>&Brand=<?= $Brand ?>&Category=<?= $Category ?>&Season=<?= $Season ?>&Style=<?= $Style ?>"><img src="<?= $sSketchFile ?>" width="90" alt="" title="" /></a><br />
	      <b><?= $sStyle ?></b>
	      <span>Updated <?= showRelativeTime($sDateTime, "F d, Y") ?></span>
<?
		if ($iComments > 0)
		{
?>
	      <div class="bubble"><?= $iComments ?></div>
<?
		}
?>
	    </li>
<?
	}

	if ($iCount == 0)
	{
?>
	    <li style="width:100%; font-size:13px;">No Style Record Found!</li>
<?
	}
?>
      </ul>
    </div>
  </div>


  <div id="ProtoWare">
    <div>
      <a href="<?= SITE_URL.substr($_SERVER['REQUEST_URI'], 1) ?>">www.3-tree.com</a>
      ProtoWare&reg;
    </div>
  </div>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>