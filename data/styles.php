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
	$Season    = IO::intValue("Season");
	$SubSeason = IO::intValue("SubSeason");
	$Sampling  = IO::strValue("Sampling");
	$PostId    = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Category    = IO::intValue("Category");
		$StyleNo     = IO::strValue("StyleNo");
		$StyleName   = IO::strValue("StyleName");
		$Reference   = IO::strValue("Reference");
		$Brand       = IO::intValue("Brand");
		$SubBrand    = IO::intValue("SubBrand");
		$Season      = IO::intValue("Season");
		$SubSeason   = IO::intValue("SubSeason");
		$Program     = IO::intValue("Program");
		$DesignNo    = IO::strValue("DesignNo");
		$DesignName  = IO::strValue("DesignName");
		$BlockNo     = IO::strValue("BlockNo");
		$Division    = IO::strValue("Division");
		$FabricWidth = IO::strValue("FabricWidth");
		$CarryOver   = IO::intValue("CarryOver");
	}

	if (@strpos($_SESSION["Brands"], ",") === FALSE)
		$Brand = $_SESSION["Brands"];

	if (@strpos($_SESSION["Email"], "@apparelco.com") === FALSE && @strpos($_SESSION["Email"], "@3-tree.com") === FALSE)
		$sBrandsList = getList("tbl_brands", "id", "brand", "parent_id='0' AND id IN (SELECT DISTINCT(b.parent_id) FROM tbl_brands b WHERE b.id IN ({$_SESSION['Brands']}))");

	else
		$sBrandsList = getList("tbl_brands", "id", "brand", "parent_id='0'");


	$sCategoriesList = getList("tbl_style_categories", "id", "category", "FIND_IN_SET(id, '{$_SESSION['StyleCategories']}')");
	$sSubBrandsList  = getList("tbl_brands", "id", "brand", "parent_id='$Brand' AND parent_id>'0'");
	$sSeasonsList    = getList("tbl_seasons", "id", "season", "brand_id='$Brand' AND parent_id='0'");
	$sSubSeasonsList = getList("tbl_seasons", "id", "season", "brand_id='$Brand' AND parent_id='$Season'");
	$sProgramsList   = getList("tbl_programs", "id", "program", "", "id");

	
	$sSamplingCategoriesList = array( );

	if ($SubBrand > 0)
		$sSamplingCategoriesList = getList("tbl_sampling_categories", "id", "category", "brand_id=(SELECT parent_id FROM tbl_brands WHERE id='$SubBrand')");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/styles.js"></script>
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
			    <h1>Styles Listing</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="data/save-style.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			    <input type="hidden" name="MAX_FILE_SIZE" value="104857600" />

				<h2>Add Style</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
				    <td width="90">Category<span class="mandatory">*</span></td>
				    <td width="20" align="center">:</td>

				    <td>
					  <select id="Category" name="Category">
					    <option value=""></option>
<?
		foreach ($sCategoriesList as $sKey => $sValue)
		{
?>
	            	    <option value="<?= $sKey ?>"<?= (($sKey == $Category) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
				    </td>
				  </tr>

				  <tr>
					<td>Style<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Style" value="<?= $Style ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Style Name</td>
					<td align="center">:</td>
					<td><input type="text" name="StyleName" value="<?= $StyleName ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Reference</td>
					<td align="center">:</td>
					<td><input type="text" name="Reference" value="<?= $Reference ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Brand<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select id="Brand" name="Brand" onchange="getListValues('Brand', 'SubBrand', 'SubBrands'); getListValues('Brand', 'Season', 'Seasons');">
						<option value=""></option>
<?
		foreach ($sBrandsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Brand) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>

					  <select id="SubBrand" name="SubBrand" onchange="getStylesList( );">
						<option value=""></option>
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
				  </tr>

				  <tr>
					<td>Season<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Season" id="Season" onchange="getListValues('Season', 'SubSeason', 'SubSeasons');">
						<option value=""></option>
<?
		foreach ($sSeasonsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Season) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>

					  <select id="SubSeason" name="SubSeason">
						<option value=""></option>
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
				  </tr>

				  <tr>
					<td>Program</td>
					<td align="center">:</td>

					<td>
					  <select id="Program" name="Program">
<?
		foreach ($sProgramsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Program) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Carry Over</td>
					<td align="center">:</td>

					<td>
					  <select name="CarryOver" id="CarryOver">
					    <option value="">No</option>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Design No</td>
					<td align="center">:</td>
					<td><input type="text" name="DesignNo" value="<?= $DesignNo ?>" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Design Name</td>
					<td align="center">:</td>
					<td><input type="text" name="DesignName" value="<?= $DesignName ?>" maxlength="100" class="textbox" /></td>
				  </tr>
				  
				  <tr>
					<td>Block No</td>
					<td align="center">:</td>
					<td><input type="text" name="BlockNo" value="<?= $BlockNo ?>" maxlength="50" class="textbox" /></td>
				  </tr>
				  
				  <tr>
					<td>Division</td>
					<td align="center">:</td>
					<td><input type="text" name="Division" value="<?= $Division ?>" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Fabric Width</td>
					<td align="center">:</td>
					<td><input type="text" name="FabricWidth" value="<?= $FabricWidth ?>" size="15" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Tech Pack</td>
					<td align="center">:</td>
					<td><input type="file" name="SpecsFile" value="" size="30" class="file" /></td>
				  </tr>

				  <tr>
					<td>Sketch / Image</td>
					<td align="center">:</td>
					<td><input type="file" name="SketchFile" value="" size="30" class="file" /></td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar"><input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" /></div>
			    </form>
<?
	}
	
	
	
	if ($sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y")
	{
?>
				<hr />    
				<form name="frmImport" id="frmImport" method="post" action="data/import-styles.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnImport').disabled=true;">
				<input type="hidden" name="MAX_FILE_SIZE" value="104857600" />
				<h2>Import Styles CSV</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">				
				<tr>
				    <td width="120">Style Category<span class="mandatory">*</span></td>
				    <td width="20" align="center">:</td>

				    <td>
					  <select id="Category" name="Category">
					    <option value=""></option>
<?
		foreach ($sCategoriesList as $sKey => $sValue)
		{
?>
	            	    <option value="<?= $sKey ?>"<?= (($sKey == $Category) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
				    </td>
				  </tr>
				
				<tr>
					<td>Program</td>
					<td align="center">:</td>

					<td>
					  <select id="Program" name="Program">
<?
		foreach ($sProgramsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Program) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>
				
				<tr>
					<td width="70">Brand<span class="mandatory">*</span></td>
					<td  width="20" align="center">:</td>
					<td>
						<select name="Brand" id="ImportBrand" onchange="getListValues('ImportBrand', 'ImportSubBrand', 'SubBrands'); getListValues('ImportBrand', 'ImportSeason', 'Seasons');">
						<option value=""></option>
<?
		foreach ($sBrandsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Brand) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
  
					<select name="SubBrand" id="ImportSubBrand" onchange="getListValues('ImportSubBrand', 'ImportSamplingCategory', 'SamplingCategories');">
						<option value=""></option>
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
				</tr>
			   
				<tr>
					<td>Sampling Category<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="SamplingCategory" id="ImportSamplingCategory">
						<option value=""></option>
<?
		foreach ($sSamplingCategoriesList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Category) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>
				
				<tr>
					<td width="70">Season<span class="mandatory">*</span></td>
					<td  width="20" align="center">:</td>
					<td>
						<select name="Season" id="ImportSeason" onchange="getListValues('ImportSeason', 'ImportSubSeason', 'SubSeasons');">
						<option value=""></option>
<?
		foreach ($sSeasonsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Season) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					  
  
					<select id="ImportSubSeason" name="SubSeason">
						<option value=""></option>
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
				</tr>
				
				<tr>
					<td width="70">CSV File<span class="mandatory">*</span></td>
					<td  width="20" align="center">:</td>
					<td><input type="file" name="CsvFile" id="CsvFile" /></td>
				  </tr>
				</table>

				<br />
				<div class="buttonsBar"><input type="submit" id="BtnImport" value="" class="btnImport" title="Import" onclick="return validateImportForm( );" /></div>
				</form> 
			    <hr />
<?
	}

	
	

	$iBrand = getDbValue("parent_id", "tbl_brands", "id='$SubBrand'");

	$sSubBrandsList  = getList("tbl_brands", "id", "brand", "parent_id>'0' AND id IN ({$_SESSION['Brands']})");
	$sSeasonsList    = getList("tbl_seasons", "id", "season", "brand_id='$iBrand' AND parent_id='0'");

	if ($Season > 0)
		$sSubSeasonsList = getList("tbl_seasons", "id", "season", "brand_id='$iBrand' AND parent_id='$Season' AND parent_id>'0'");

	else
		$sSubSeasonsList = getList("tbl_seasons", "id", "season", "brand_id='$iBrand' AND parent_id>'0'");
?>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="40">Style</td>
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

			          <td width="200"><input type="checkbox" name="Sampling" value="Y" <?= (($Sampling == "Y") ? "checked" : "") ?> /> Without Sampling Audit</td>

			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </form>
			    </div>
				

			    <div class="tblSheet">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	if ($Season == 0)
		$sSubSeasonsList = getList("tbl_seasons", "id", "season", "parent_id>'0'");


	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "WHERE FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}') ";

	if ($Style != "")
		$sConditions .= " AND (style LIKE '%$Style%' OR style_name LIKE '%$Style%' OR design_no LIKE '%$Style%' OR design_name LIKE '%$Style%') ";

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

//	if ($sConditions != "")
//		$sConditions = (" WHERE ".@substr($sConditions, 5));

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
		$iBrand       = $objDb->getField($i, 'brand_id');
		$iSubBrand    = $objDb->getField($i, 'sub_brand_id');
		$iSeason      = $objDb->getField($i, 'season_id');
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

		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="data/edit-style.php?Id=<?= $iId ?>"><img src="images/icons/edit.gif" width="16" height="16" hspace="2" alt="Edit" title="Edit" /></a>
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="data/delete-style.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Style?');"><img src="images/icons/delete.gif" width="16" height="16" hspace="2" alt="Delete" title="Delete" /></a>
<?
		}
?>
				        <a href="data/view-style.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="Style : <?= $sStyle ?> :: :: width: 800, height: 550"><img src="images/icons/view.gif" width="16" height="16" hspace="2" alt="View" title="View" /></a>
				      </td>
				    </tr>
<?
	}

	if ($iCount == 0)
	{
?>
				    <tr>
				      <td class="noRecord">No Style Record Found!</td>
				    </tr>
<?
	}
?>
			      </table>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Style={$Style}&SubBrand={$SubBrand}&SubSeason={$SubSeason}&Sampling={$Sampling}");

	if ($iCount > 0)
	{
?>
				<div class="buttonsBar" style="margin-top:4px;">
				  <input type="hidden" id="ExportUrl" name="ExportUrl" value="<?= (SITE_URL."data/export-styles.php?Style={$Style}&SubBrand={$SubBrand}&SubBrand={$SubBrand}&Season={$Season}&SubSeason={$SubSeason}&Sampling={$Sampling}") ?>" />
				  <input type="button" value="" id="BtnExport" class="btnExport" title="Export" onclick="exportReport( );" />
				</div>
<?
	}
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