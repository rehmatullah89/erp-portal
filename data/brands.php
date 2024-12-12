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

	$PageId = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Brand  = IO::strValue("Brand");
	$Parent = IO::strValue("Parent");
	$PostId = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Brand         = IO::strValue("Brand");
		$AQL           = IO::strValue("AQL");
                $AQLMinor      = IO::strValue("AQLMinor");
		$Parent        = IO::strValue("Parent");
		$Code          = IO::strValue("Code");
		$Manager       = IO::strValue("Manager");
		$Merchandisers = IO::getArray("Merchandisers");
		$Vendors       = IO::getArray("Vendors");
		$Stages        = IO::getArray("Stages");
                $Categories    = IO::getArray("Categories");
		$Qmip          = IO::strValue("Qmip");
		$Type          = IO::strValue("Type");
		$Regular       = IO::strValue("Regular");
		$Level         = IO::strValue("Level");
	}

	if (@strpos($_SESSION["Brands"], ",") === FALSE)
		$Brand = $_SESSION["Brands"];

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
  <script type="text/javascript" src="scripts/data/brands.js"></script>
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
			    <h1>Brands Listing</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="data/save-brand.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;" enctype="multipart/form-data">
				<input type="hidden" name="MAX_FILE_SIZE" value="52428800" />
				<h2>Add Brand</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="160">Brand<span class="mandatory">*</span></td>
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
							<option></option>
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
					<td><input type="file" name="Logo" value="" size="30" class="file" /></td>
				  </tr>
				  
				  <tr>
					<td>Logo (PNG)</td>
					<td align="center">:</td>
					<td><input type="file" name="LogoPng" value="" size="30" class="file" /></td>
				  </tr>
				  
				  <tr>
					<td>Logo (JPG)</td>
					<td align="center">:</td>
					<td><input type="file" name="LogoJpg" value="" size="30" class="file" /></td>
				  </tr>
				  
				  <tr>
					<td>Logo (SVG)</td>
					<td align="center">:</td>
					<td><input type="file" name="LogoSvg" value="" size="30" class="file" /></td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar"><input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" /></div>
			    </form>

			    <hr />
<?
	}
?>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="45">Brand</td>
			          <td width="200"><input type="text" name="Brand" value="<?= $Brand ?>" class="textbox" maxlength="50" /></td>

			          <td width="55">Parent</td>
			          <td width="200">
					    <select name="Parent">
						  <option value="">All Brands</option>
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


			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </form>
			    </div>

			    <div class="tblSheet">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";

	if ($Brand != "")
		$sConditions .= " AND (brand LIKE '%$Brand%' OR code LIKE '$Brand') ";

	if ($Parent != "")
		$sConditions .= " AND parent_id='$Parent' ";

	if(@strpos($_SESSION["Email"], "@3-tree.com") !== False && $_SESSION['Brands'] != "")
        {
            $sParentBrands = implode(",", getList("tbl_brands", "parent_id", "parent_id", "id IN ({$_SESSION['Brands']})"));
            
            $sConditions .= " AND id IN ({$_SESSION['Brands']}) OR id IN ($sParentBrands) "; 
        }
	else if (@strpos($_SESSION["Email"], "@apparelco.com") === FALSE && @strpos($_SESSION["Email"], "@matrixsourcings.com") === FALSE)
		$sConditions .= " AND id IN ({$_SESSION['Brands']}) ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_brands", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_brands $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow">
				      <td width="3%">#</td>
				      <td width="20%">Brand</td>
				      <td width="17%">Parent Brand</td>
				      <td width="7%">AQL(MJ)</td>
                                      <td width="7%">AQL(MI)</td>
				      <td width="18%">Brand Manager</td>
				      <td width="20%">Merchandisers</td>
				      <td width="8%" class="center">Options</td>
				    </tr>
<?
		}

		$iId            = $objDb->getField($i, 'id');
		$iParent        = $objDb->getField($i, 'parent_id');
		$sBrand         = $objDb->getField($i, 'brand');
		$sCode          = $objDb->getField($i, 'code');
		$fAQL           = $objDb->getField($i, 'aql');
                $fAQLMinor      = $objDb->getField($i, 'aql_minor');
		$iManager       = $objDb->getField($i, 'manager');
		$sMerchandisers = $objDb->getField($i, 'merchandisers');
		$sVendors       = $objDb->getField($i, 'vendors');
		$sStages        = $objDb->getField($i, 'stages');
                $sCategories    = $objDb->getField($i, 'categories');

		$iVendors       = @explode(",", $sVendors);
		$iStages        = @explode(",", $sStages);
		$iMerchandisers = @explode(",", $sMerchandisers);
		$sMerchandisers = "";

		for ($j = 0; $j < count($iMerchandisers); $j ++)
			$sMerchandisers .= ("- ".$sEmployeesList[$iMerchandisers[$j]]."<br />");
?>

				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td><?= ($iStart + $i + 1) ?></td>
				      <td><?= $sBrand ?><?= (($sCode != "") ? " ({$sCode})" : "") ?></td>
				      <td><?= $sBrandsList[$iParent] ?></td>
				      <td><?= formatNumber($fAQL) ?></td>
                                      <td><?= formatNumber($fAQLMinor) ?></td>
				      <td><?= $sEmployeesList[$iManager] ?></td>
				      <td><?= $sMerchandisers ?></td>

				      <td class="center">
<?
		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="data/edit-brand.php?Id=<?= $iId ?>"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
                                        <a href="data/add-category-stages.php?Id=<?= $iId ?>&Categories=<?=$sCategories?>" class="lightview sheetLink" rel="iframe" title="Add Category Stages for Brand: <?= $sBrand ?> :: :: width: 850, height: 550"><img src="images/icons/deviation.gif" title="Add Category Stages" /></a>
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="data/delete-brand.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Brand?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
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
				      <td class="noRecord">No Brand Record Found!</td>
				    </tr>
<?
	}
?>
			      </table>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Brand={$Brand}&Parent={$Parent}");
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