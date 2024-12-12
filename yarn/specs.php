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

	$PageId    = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Style     = IO::strValue("Style");
	$SubBrand  = IO::intValue("SubBrand");
	$SubSeason = IO::intValue("SubSeason");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
</head>

<body>

<div id="MainDiv">
  <div id="PageLeftBorder">
    <div id="PageRightBorder">

<!--  Message Section Starts Here  -->
<?
	@include($sBaseDir."includes/messages.php");
?>
<!--  Message Section Ends Here  -->

      <div id="PageContents">

<!--  Header Section Starts Here  -->
<?
	@include($sBaseDir."includes/header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Navigation Section Starts Here  -->
<?
	@include($sBaseDir."includes/navigation.php");
?>
<!--  Navigation Section Ends Here  -->


<!--  Body Section Starts Here  -->
	    <div id="Body">
		  <table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr valign="top">
			  <td width="100%">
			    <h1><img src="images/h1/yarn/specs.jpg" width="124" height="20" vspace="10" alt="" title="" /></h1>
<?
	$iBrand = getDbValue("parent_id", "tbl_brands", "id='$SubBrand'");

	$sSubBrandsList  = getList("tbl_brands", "id", "brand", "parent_id>'0' AND id IN ({$_SESSION['Brands']})");
	$sSeasonsList    = getList("tbl_seasons", "id", "season", "brand_id='$iBrand' AND parent_id='0'");
	$sSubSeasonsList = getList("tbl_seasons", "id", "season", "brand_id='$iBrand' AND parent_id>'0'");
?>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="50">Style #</td>
			          <td width="130"><input type="text" name="Style" value="<?= $Style ?>" class="textbox" size="15" maxlength="50" /></td>
			          <td width="45">Brand</td>

			          <td width="180">
					    <select name="SubBrand" id="SubBrands" onchange="getListValues('SubBrands', 'SubSeasons', 'BrandSeasons');">
						  <option value="">All Brands</option>
<?
	foreach ($sSubBrandsList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $SubBrand) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
			          </td>

			          <td width="55">Season</td>

			          <td width="150">
					    <select name="SubSeason" id="SubSeasons">
						  <option value="">All Sub-Seasons</option>
<?
	foreach ($sSubSeasonsList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $SubSeason) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
			          </td>

			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </form>
			    </div>

			    <div class="tblSheet">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	if ($SubSeason == 0)
		$sSubSeasonsList = getList("tbl_seasons", "id", "season", "parent_id>'0'");


	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "WHERE category_id='14'";

	if ($Style != "")
		$sConditions .= " AND (style LIKE '%$Style%' OR style_name LIKE '%$Style%') ";

	if ($SubBrand > 0)
		$sConditions .= " AND sub_brand_id='$SubBrand' ";

	else
		$sConditions .= " AND FIND_IN_SET(sub_brand_id, '{$_SESSION['Brands']}') ";

	if ($SubSeason > 0)
		$sConditions .= " AND sub_season_id='$SubSeason' ";

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_styles", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_styles $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow">
				      <td width="8%">#</td>
				      <td width="18%">Style #</td>
				      <td width="24%">Style Name</td>
				      <td width="18%">Brand</td>
				      <td width="17%">Season</td>
				      <td width="15%" class="center">Options</td>
				    </tr>
<?
		}

		$iId          = $objDb->getField($i, 'id');
		$sStyle       = $objDb->getField($i, 'style');
		$sStyleName   = $objDb->getField($i, 'style_name');
		$iSubBrand    = $objDb->getField($i, 'sub_brand_id');
		$iSubSeason   = $objDb->getField($i, 'sub_season_id');
		$sSpecsFile   = $objDb->getField($i, 'specs_file');
		$sSketchFile  = $objDb->getField($i, 'sketch_file');
?>

				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td><?= ($iStart + $i + 1) ?></td>
				      <td><?= $sStyle ?></td>
				      <td><?= $sStyleName ?></td>
				      <td><?= $sSubBrandsList[$iSubBrand] ?></td>
				      <td><?= $sSubSeasonsList[$iSubSeason] ?></td>

				      <td class="center">
<?
		if ($sSpecsFile != "" && @file_exists($sBaseDir.STYLES_SPECS_DIR.$sSpecsFile))
		{
?>
				        <a href="<?= STYLES_SPECS_DIR.$sSpecsFile ?>" target="_blank"><img src="images/icons/pdf.gif" width="16" height="16" hspace="2" alt="Specs File" title="Specs File" /></a>
<?
		}

		if ($sSketchFile != "" && @file_exists($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile))
		{
?>
				        <a href="<?= STYLES_SKETCH_DIR.$sSketchFile ?>" class="lightview"><img src="images/icons/thumb.gif" width="16" height="16" hspace="2" alt="Sketch File" title="Sketch File" /></a>
<?
		}

		if (getDbValue("COUNT(*)", "tbl_gf_specs", "style_id='$iId'") == 1)
		{
			if ($sUserRights['Edit'] == "Y")
			{
?>
				        <a href="yarn/edit-specs.php?Id=<?= $iId ?>"><img src="images/icons/edit.gif" width="16" height="16" hspace="2" alt="Edit" title="Edit" /></a>
<?
			}

			if ($sUserRights['Delete'] == "Y")
			{
?>
				        <a href="yarn/delete-specs.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Style Specs?');"><img src="images/icons/delete.gif" width="16" height="16" hspace="2" alt="Delete" title="Delete" /></a>
<?
			}
?>
				        <a href="yarn/view-specs.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="Style : <?= $sStyle ?> :: :: width: 700, height: 550"><img src="images/icons/view.gif" width="16" height="16" hspace="2" alt="View" title="View" /></a>
<?
		}

		else if ($sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y")
		{
?>
				        <a href="yarn/save-specs.php?Id=<?= $iId ?>"><img src="images/icons/hand.gif" width="16" height="16" hspace="2" alt="Add" title="Add" /></a>
<?
		}
?>
				      </td>
				    </tr>
<?
	}

	if ($iCount == 0)
	{
?>
				    <tr>
				      <td class="noRecord">No Specs Record Found!</td>
				    </tr>
<?
	}
?>
			      </table>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Style={$Style}&SubBrand={$SubBrand}&SubSeason={$SubSeason}");
?>
			  </td>
			</tr>
		  </table>

<?
	@include($sBaseDir."includes/my-profile.php");
?>
        </div>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include($sBaseDir."includes/footer.php");
?>
<!--  Footer Section Ends Here  -->

      </div>
    </div>
  </div>
</div>

<!--  Bottom Bar Section Starts Here  -->
<?
	@include($sBaseDir."includes/bottom-bar.php");
?>
<!--  Bottom Bar Section Ends Here  -->

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>