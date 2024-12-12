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


	$sSQL = "SELECT * FROM tbl_brands WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$Parent        = $objDb->getField(0, 'parent_id');
		$Brand         = $objDb->getField(0, 'brand');
		$Code          = $objDb->getField(0, 'code');
		$AQL           = $objDb->getField(0, 'aql');
                $AQLMinor      = $objDb->getField(0, 'aql_minor');
		$Manager       = $objDb->getField(0, 'manager');
		$Merchandisers = $objDb->getField(0, 'merchandisers');
		$Vendors       = $objDb->getField(0, 'vendors');
                $Categories    = explode(",", $objDb->getField(0, 'categories'));
		$Stages        = $objDb->getField(0, 'stages');
		$Qmip          = $objDb->getField(0, 'qmip');
		$Type          = $objDb->getField(0, 'type');
		$Regular       = $objDb->getField(0, 'regular');
		$Logo          = $objDb->getField(0, 'logo');
		$LogoPng       = $objDb->getField(0, 'logo_png');
		$LogoJpg       = $objDb->getField(0, 'logo_jpg');
		$LogoSvg       = $objDb->getField(0, 'logo_svg');
		$Level         = $objDb->getField(0, 'inspection_level');

		$Vendors       = @explode(",", $Vendors);
		$Stages        = @explode(",", $Stages);
		$Merchandisers = @explode(",", $Merchandisers);
	}

	else
		redirect($Referer, "DB_ERROR");


	$sBrandsList    = getList("tbl_brands", "id", "brand", "parent_id='0'");
	$sEmployeesList = getList("tbl_users", "id", "name", "(email LIKE '%@apparelco.com' OR email LIKE '%@3-tree.com' OR email LIKE '%@matrixsourcings.com') AND status='A'");
	$sVendorsList   = getList("tbl_vendors", "id", "vendor", "parent_id='0' AND sourcing='Y'");
	$sStagesList    = getList("tbl_production_stages", "id", "title", "", "position");
        $sCategoriesList= getList("tbl_categories", "id", "category", "", "category");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/edit-brand.js"></script>
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
			    <h1>brands listing</h1>

				<form name="frmData" id="frmData" method="post" action="data/update-brand.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;" enctype="multipart/form-data">
				<input type="hidden" name="MAX_FILE_SIZE" value="52428800" />
				<input type="hidden" name="OldLogo" value="<?= $Logo ?>" />
				<input type="hidden" name="OldLogoPng" value="<?= $LogoPng ?>" />
				<input type="hidden" name="OldLogoJpg" value="<?= $LogoJpg ?>" />
				<input type="hidden" name="OldLogoSvg" value="<?= $LogoSvg ?>" />
			    <input type="hidden" name="Id" value="<?= $Id ?>" />

				<h2>Edit Brand</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="150">Brand<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="Brand" value="<?= $Brand ?>" maxlength="50" size="30" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Parent</td>
					<td align="center">:</td>

					<td>
					  <select name="Parent" onchange="if (this.value!='') { $('Type').disabled=true; } else { $('Type').disabled=false; }">
						<option value=""></option>
<?
	foreach ($sBrandsList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Parent) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Code</td>
					<td align="center">:</td>
					<td><input type="text" name="Code" value="<?= $Code ?>" maxlength="5" size="10" class="textbox" /></td>
				  </tr>
<?
	$inspectionLevel = array("1"=>"I","2"=>"II","3"=>"III");
?>
				  <tr>
					<td>General Inspection Levels<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td>
						<select name="Level" style="width: 90px;">
							<?
								foreach ($inspectionLevel as $key => $value) {
							?>
								<option value="<?=$key?>" <?=($key==$Level)?'selected':''?> ><?=$value?></option>
							<?
								}
							?>
						</select>
					</td>
				  </tr>				  
				  <tr>
					<td>AQL Major*</td>
					<td align="center">:</td>
					<td><input type="text" name="AQL" value="<?= $AQL ?>" maxlength="5" size="10" class="textbox" /></td>
				  </tr>				  

                                  <tr>
					<td>AQL Minor</td>
					<td align="center">:</td>
					<td><input type="text" name="AQLMinor" value="<?= $AQLMinor ?>" maxlength="5" size="10" class="textbox" /></td>
				  </tr>	
                                  
				  <tr>
					<td>Manager*</td>
					<td align="center">:</td>

					<td>
					  <select name="Manager">
						<option value=""></option>
<?
		foreach ($sEmployeesList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Manager) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr valign="top">
					<td>Merchandiser(s)</td>
					<td align="center">:</td>

					<td>
					  <select name="Merchandisers[]" multiple size="10" style="width:220px;">
<?
		foreach ($sEmployeesList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Merchandisers)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr valign="top">
					<td>Recommended Vendor(s)</td>
					<td align="center">:</td>

					<td>
					  <select name="Vendors[]" multiple size="10" style="width:220px;">
<?
		foreach ($sVendorsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Vendors)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr valign="top">
					<td>Stage(s)</td>
					<td align="center">:</td>

					<td>
					  <select name="Stages[]" id="Stages" multiple size="8" style="width:220px;">
<?
		foreach ($sStagesList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Stages)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>
                                    
                                    <tr valign="top">
					<td>Categories(s)</td>
					<td align="center">:</td>

					<td>
					  <select name="Categories[]" id="Categories" multiple size="8" style="width:220px;">
                                              <option value=""></option>
<?
		foreach ($sCategoriesList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Categories)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Type</td>
					<td align="center">:</td>

					<td>
					  <select name="Type" id="Type" style="width:220px;" <?= (($Parent > 0) ? 'disabled' : '') ?>>
			            <option value="F"<?= (($Type == "F") ? " selected" : "") ?>>Full Service</option>
			            <option value="Q"<?= (($Type == "Q") ? " selected" : "") ?>>Quality Only</option>
			            <option value="P"<?= (($Type == "P") ? " selected" : "") ?>>Pilot</option>
					  </select>
					</td>
				  </tr>

				  <tr>
				    <td>Part of QMIP</td>
				    <td align="center">:</td>
				    <td><input type="checkbox" name="Qmip" value="Y" <?= (($Qmip == "Y") ? "checked" : "") ?> /></td>
				  </tr>
				  
				  <tr>
				    <td>Regular Customer</td>
				    <td align="center">:</td>
				    <td><input type="checkbox" name="Regular" value="Y" <?= (($Regular == "Y") ? "checked" : "") ?> /></td>
				  </tr>
				  
				  <tr>
				    <td>Logo (Source)</td>
				    <td align="center">:</td>

				    <td>
				      <input type="file" name="Logo" value="" size="30" class="file" />
<?
	if ($Logo != "")
	{
?>
				      (<a href="<?= BRANDS_IMG_DIR.'source/'.$Logo ?>" download><?= substr($Logo, (strpos($Logo, "{$Id}-") + strlen("{$Id}-"))) ?></a>)
<?
	}
?>
				    </td>
				  </tr>
				  
				  <tr>
				    <td>Logo (PNG)</td>
				    <td align="center">:</td>

				    <td>
				      <input type="file" name="LogoPng" value="" size="30" class="file" />
<?
	if ($LogoPng != "")
	{
?>
				      (<a href="<?= BRANDS_IMG_DIR.'png/'.$LogoPng ?>" class="lightview"><?= substr($LogoPng, (strpos($LogoPng, "{$Id}-") + strlen("{$Id}-"))) ?></a>)
<?
	}
?>
				    </td>
				  </tr>
				  
				  <tr>
				    <td>Logo (JPG)</td>
				    <td align="center">:</td>

				    <td>
				      <input type="file" name="LogoJpg" value="" size="30" class="file" />
<?
	if ($LogoJpg != "")
	{
?>
				      (<a href="<?= BRANDS_IMG_DIR.'jpg/'.$LogoJpg ?>" class="lightview"><?= substr($LogoJpg, (strpos($LogoJpg, "{$Id}-") + strlen("{$Id}-"))) ?></a>)
<?
	}
?>
				    </td>
				  </tr>
				  
				  <tr>
				    <td>Logo (SVG)</td>
				    <td align="center">:</td>

				    <td>
				      <input type="file" name="LogoSvg" value="" size="30" class="file" />
<?
	if ($LogoSvg != "")
	{
?>
				      (<a href="<?= BRANDS_IMG_DIR.'svg/'.$LogoSvg ?>" class="lightview"><?= substr($LogoSvg, (strpos($LogoSvg, "{$Id}-") + strlen("{$Id}-"))) ?></a>)
<?
	}
?>
				    </td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar">
				  <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" />
				  <input type="button" value="" class="btnBack" onclick="document.location='<?= $Referer ?>';" />
				</div>
			    </form>

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