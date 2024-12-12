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

	$Id      = IO::intValue('Id');
	$Referer = IO::strValue("Referer");

	if ($Referer == "")
		$Referer = $_SERVER['HTTP_REFERER'];

	$sSQL = "SELECT * FROM tbl_styles WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		redirect($Referer, "DB_ERROR");

	$iCategory    = $objDb->getField(0, 'category_id');
	$sStyle       = $objDb->getField(0, 'style');
	$sStyleName   = $objDb->getField(0, 'style_name');
	$sReference   = $objDb->getField(0, 'reference');
	$iBrand       = $objDb->getField(0, 'brand_id');
	$iSubBrand    = $objDb->getField(0, 'sub_brand_id');
	$iSeason      = $objDb->getField(0, 'season_id');
	$iSubSeason   = $objDb->getField(0, 'sub_season_id');
	$DesignNo     = $objDb->getField(0, "design_no");
	$DesignName   = $objDb->getField(0, "design_name");
	$BlockNo      = $objDb->getField(0, "block_no");
	$Division     = $objDb->getField(0, "division");
	$iFabricWidth = $objDb->getField(0, 'fabric_width');
	$sSpecsFile   = $objDb->getField(0, 'specs_file');
	$sSketchFile  = $objDb->getField(0, 'sketch_file');
	$iCarryOver   = $objDb->getField(0, 'carry_over_id');
	$iProgram     = $objDb->getField(0, 'program_id');


	if (@strpos($_SESSION["Email"], "@apparelco.com") === FALSE && @strpos($_SESSION["Email"], "@3-tree.com") === FALSE)
		$sBrandsList = getList("tbl_brands", "id", "brand", "parent_id='0' AND id IN (SELECT DISTINCT(b.parent_id) FROM tbl_brands b WHERE b.id IN ({$_SESSION['Brands']}))");

	else
		$sBrandsList = getList("tbl_brands", "id", "brand", "parent_id='0'");


	$sCategoriesList = getList("tbl_style_categories", "id", "category");
	$sSeasonsList    = getList("tbl_seasons", "id", "season", "brand_id='$iBrand' AND parent_id='0'");
	$sProgramsList   = getList("tbl_programs", "id", "program");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/edit-style.js"></script>
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
			    <h1><img src="images/h1/data/styles-listing.jpg" width="196" height="20" vspace="10" alt="" title="" /></h1>

			    <form name="frmData" id="frmData" method="post" action="data/update-style.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disable( );">
			    <input type="hidden" name="MAX_FILE_SIZE" value="104857600" />
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Referer" value="<?= $Referer ?>" />
			    <input type="hidden" name="OldSpecsFile" value="<?= $sSpecsFile ?>" />
			    <input type="hidden" name="OldSketchFile" value="<?= $sSketchFile ?>" />

			    <h2>Edit Style</h2>
			    <table width="98%" cellspacing="0" cellpadding="3" border="0" align="center">
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
	            	    <option value="<?= $sKey ?>"<?= (($sKey == $iCategory) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>
				    </td>
				  </tr>

				  <tr>
				    <td>Style<span class="mandatory">*</span></td>
				    <td align="center">:</td>
				    <td><input type="text" name="Style" value="<?= $sStyle ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Style Name</td>
				    <td align="center">:</td>
				    <td><input type="text" name="StyleName" value="<?= $sStyleName ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Reference</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Reference" value="<?= $sReference ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Brand<span class="mandatory">*</span></td>
				    <td align="center">:</td>

				    <td>
					  <select id="Brand" name="Brand" onchange="getListValues('Brand', 'SubBrand', 'SubBrands');">
					    <option value=""></option>
<?
	foreach ($sBrandsList as $sKey => $sValue)
	{
?>
	            	    <option value="<?= $sKey ?>"<?= (($sKey == $iBrand) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>

					  <select id="SubBrand" name="SubBrand" onchange="getStylesList( );">
					    <option value=""></option>
<?
	$sSQL = "SELECT id, brand FROM tbl_brands WHERE parent_id='$iBrand' AND parent_id>'0' AND id IN ({$_SESSION['Brands']}) ORDER BY brand";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, 0);
		$sValue = $objDb->getField($i, 1);
?>
        			    <option value="<?= $sKey ?>"<?= (($sKey == $iSubBrand) ? " selected" : "") ?>><?= $sValue ?></option>
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
	            	    <option value="<?= $sKey ?>"<?= (($sKey == $iSeason) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>

					  <select id="SubSeason" name="SubSeason">
					    <option value=""></option>
<?
	$sSQL = "SELECT id, season FROM tbl_seasons WHERE parent_id='$iSeason' AND parent_id>'0' ORDER BY season";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, 0);
		$sValue = $objDb->getField($i, 1);
?>
        			    <option value="<?= $sKey ?>"<?= (($sKey == $iSubSeason) ? " selected" : "") ?>><?= $sValue ?></option>
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
			            <option value="<?= $sKey ?>"<?= (($sKey == $iProgram) ? " selected" : "") ?>><?= $sValue ?></option>
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
<?
	$sSQL = "SELECT id, style,
					(SELECT season FROM tbl_seasons WHERE id=tbl_styles.sub_season_id) AS _Season
			 FROM tbl_styles
			 WHERE sub_brand_id='$iSubBrand'
			 ORDER BY style";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iStyle  = $objDb->getField($i, 0);
		$sStyle  = $objDb->getField($i, 1);
		$sSeason = $objDb->getField($i, 2);
?>
					    <option value="<?= $iStyle ?>"<?= (($iStyle == $iCarryOver) ? " selected" : "") ?>><?= $sStyle ?> (<?= $sSeason ?>)</option>
<?
	}
?>
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
				    <td><input type="text" name="FabricWidth" value="<?= $iFabricWidth ?>" size="15" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Tech Pack</td>
				    <td align="center">:</td>

				    <td>
				      <input type="file" name="SpecsFile" value="" size="30" class="file" />
<?
	if ($sSpecsFile != "")
	{
?>
				      <a href="<?= STYLES_SPECS_DIR.$sSpecsFile ?>" target="_blank">(<?= substr($sSpecsFile, (strpos($sSpecsFile, "{$Id}-") + strlen("{$Id}-"))) ?></a>)
<?
	}
?>
				    </td>
				  </tr>

				  <tr>
				    <td>Sketch / Image</td>
				    <td align="center">:</td>

				    <td>
				      <input type="file" name="SketchFile" value="" size="30" class="file" />
<?
	if ($sSketchFile != "")
	{
?>
				      (<a href="<?= STYLES_SKETCH_DIR.$sSketchFile ?>" class="lightview"><?= substr($sSketchFile, (strpos($sSketchFile, "{$Id}-") + strlen("{$Id}-"))) ?></a>)
<?
	}
?>
				    </td>
				  </tr>
				</table>

				<br />

				<h2>Update Reason</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="99.5%" align="center">
				  <tr>
					<td width="90">User</td>
					<td width="20" align="center">:</td>
					<td><?= $_SESSION['Name'] ?></td>
				  </tr>

				  <tr>
					<td>Reason</td>
					<td align="center">:</td>

					<td>

					  <table border="0" cellpadding="0" cellspacing="0" width="100%">
					    <tr valign="top">
					      <td width="25"><input type="radio" name="Reason" value="R" /></td>
					      <td width="125">Specs Revision</td>
					      <td width="25"><input type="radio" name="Reason" value="D" /></td>
					      <td>Other reason (Data/Sketch/etc)</td>
					    </tr>
					  </table>

					</td>
				  </tr>

				  <tr>
					<td>Remarks</td>
					<td align="center">:</td>
					<td><input type="text" name="Remarks" value="" size="30" maxlength="200" class="textbox" /></td>
				  </tr>
				</table>

				<br />

			    <div class="buttonsBar">
			      <input type="submit" id="BtnSave" value="" class="btnSave" onclick="return validateForm( );" />
			      <input type="button" value="" class="btnBack" onclick="document.location='<?= $Referer ?>';" />
			    </div>
			    </form>

			    <br />
			    <b>Note:</b> Fields marked with an asterisk (*) are required.<br/>
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