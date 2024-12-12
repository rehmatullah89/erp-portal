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
	**  Software Engineer:                                                                       **
	**                                                                                           **
	**      Name  :  Rehmat Ullah                                                                **
	**      Email :  rehmatullah@3-tree.com                                                      **
	**      Phone :  +92 344 404 3675                                                            **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
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

	$PageId     = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Category   = IO::intValue("Category");
	$Section    = IO::intValue("Section");
        $Brands     = IO::getArray("Brands");
        $AuditTypes = IO::getArray("AuditTypes");
	$Nature     = IO::strValue("Nature");
	$Point      = IO::strValue("Point");
        $Comments   = IO::strValue("Comments");
        $PointNo    = IO::strValue("PointNo");
	$PostId     = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Category   = IO::intValue("Category");
		$Section    = IO::intValue("Section");
                $Brands     = IO::getArray("Brands");
                $AuditTypes = IO::getArray("AuditTypes");
		$Nature     = IO::strValue("Nature");
		$Point      = IO::strValue("Point");
                $Comments   = IO::strValue("Comments");
                $PointNo    = IO::strValue("PointNo");
	}


	$sSectionList           = getList("tbl_tnc_sections", "id", "section");
        $sParentSectionsList    = getList("tbl_tnc_sections", "id", "section", "parent_id='0'");
	$sCategoriesList        = getList("tbl_tnc_categories", "id", "category", (($Section > 0) ? "section_id='$Section'" : ""));
        $sBrandsList            = getList("tbl_brands", "id", "brand", "id IN ({$_SESSION['Brands']})");
        $sAuditTypes            = getList("tbl_crc_audit_types", "id", "type");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/crc/tnc-points.js"></script>
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
			    <h1>CRC Points</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="crc/save-tnc-point.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Point</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="500px">
				  <tr>
				    <td width="60">Section</td>
				    <td width="20" align="center">:</td>

				    <td width="420">
                                        <select name="Section" id="Section" style="width: 450px !important;" onchange="getCategories(this.value, 'Category')">
				        <option value=""></option>
<?
		foreach ($sParentSectionsList as $sLabelKey => $sLabel)
		{
                    $sSectionsList = getList("tbl_tnc_sections", "id", "section", "parent_id=$sLabelKey");
?>
                        <optgroup label="<?php echo $sLabel; ?>">
                        <?
                            foreach ($sSectionsList as $sKey => $sValue)
                            {?>
                                <option value="<?= $sKey ?>"<?= (($sKey == $Section) ? " selected" : "") ?>><?= $sValue ?></option>
<?                          }
                        ?>        
                        </optgroup>                            
			            
<?
		}
?>
					  </select>
                    </td>
				  </tr>

				  <tr>
					<td>Category</td>
					<td align="center">:</td>

					<td>
					  <select name="Category" id="Category" style="width: 450px !important;">
						<option value=""></option>
<?
		if ($Section > 0)
		{
			foreach ($sCategoriesList as $sKey => $sValue)
			{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Category) ? " selected" : "") ?>><?= $sValue ?></option>
<?
			}
		}
?>
					  </select>
					</td>
				  </tr>
                                    
                                   <tr>
					<td>Brand</td>
					<td align="center">:</td>

					<td>
					  <select name="Brand[]" id="Brand" style="height: 150px;" multiple>
						<option value=""></option>
<?
			foreach ($sBrandsList as $iBrand => $sBrand)
			{
?>
                                                <option value="<?= $iBrand ?>"<?= ((@in_array($iBrand, $Brands)) ? " selected" : "") ?>><?= $sBrand ?></option>
<?
			}
?>
					  </select>
					</td>
				  </tr>  

                                    <tr>
					<td>Audit Type</td>
					<td align="center">:</td>

					<td>
					  <select name="AuditType[]" id="AuditType" style="height: 120px; width: 207px;" multiple>
						<option value=""></option>
<?
			foreach ($sAuditTypes as $iAuditType => $sAuditType)
			{
?>
                                                <option value="<?= $iAuditType ?>"<?= ((@in_array($iAuditType, $AuditTypes)) ? " selected" : "") ?>><?= $sAuditType ?></option>
<?
			}
?>
					  </select>
					</td>
				  </tr> 
                                    
				  <tr>
					<td>Nature</td>
					<td align="center">:</td>

					<td>
					  <select name="Nature">
					    <option value="S"<?= (($Nature == 'S') ? " selected" : "") ?>>Standard</option>
					    <option value="C"<?= (($Nature == 'C') ? " selected" : "") ?>>Critical</option>
					    <option value="Z"<?= (($Nature == 'Z') ? " selected" : "") ?>>Zero Tolerance</option>
					  </select>
					</td>
				  </tr>
                                  <tr valign="top">
					<td>Point #</td>
					<td align="center">:</td>
                                        <td><input type="text" name="PointNo" value="<?= $PointNo ?>" maxlength="100" size="14" class="textbox" /></td>
				   </tr>  
				  <tr valign="top">
					<td>Point</td>
					<td align="center">:</td>
					<td><textarea name="Point" rows="4" cols="60" value="<?= $Point ?>"></textarea></td>
				   </tr>
                                <tr valign="top">
                                    <td>Notes</td>
                                    <td align="center">:</td>
                                    <td><textarea name="Comments" rows="4" cols="60" value="<?= $Comments ?>"></textarea></td>
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
			          <td width="40">Point</td>
			          <td width="170"><input type="text" name="Point" value="<?= $Point ?>" class="textbox" maxlength="50" /></td>

			          <td width="55">Section</td>
			          <td width="150">
					    <select name="Section" id="Section_" onchange="getCategories(this.value, 'Category_')">
						  <option value="">All Sections</option>
<?
		foreach ($sParentSectionsList as $sLabelKey => $sLabel)
		{
                    $sSectionsList = getList("tbl_tnc_sections", "id", "section", "parent_id=$sLabelKey");
?>
                        <optgroup label="<?php echo $sLabel; ?>">
                        <?
                            foreach ($sSectionsList as $sKey => $sValue)
                            {?>
                                <option value="<?= $sKey ?>"<?= (($sKey == $Section) ? " selected" : "") ?>><?= $sValue ?></option>
<?                          }
                        ?>        
                        </optgroup>                            
			            
<?
		}
?>
					    </select>
			          </td>

			          <td width="65">Category</td>

               		  <td width="130">
               		    <select name="Category" id="Category_">
               		      <option value="">All Categories</option>
<?
	if ($Section > 0)
	{
		foreach ($sCategoriesList as $sKey => $sValue)
		{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Category) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
	}
?>
         				</select>
         			  </td>

					  <td width="50">Nature</td>

					  <td width="100">
						<select name="Nature">
						  <option value="">Any</option>
						  <option value="S"<?= (($Nature == 'S') ? " selected" : "") ?>>Standard</option>
						  <option value="C"<?= (($Nature == 'C') ? " selected" : "") ?>>Critical</option>
						  <option value="Z"<?= (($Nature == 'Z') ? " selected" : "") ?>>Zero Tolerance</option>
						</select>
					  </td>

				      <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </form>
			    </div>

			    <div class="tblSheet">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";

	if ($Point != "")
		$sConditions .= " AND point LIKE '%$Point%' ";
        if ($Nature != "")
		$sConditions .= " AND nature LIKE '%$Nature%' ";

	if ($Category > 0)
		$sConditions .= " AND category_id='$Category' ";

	if ($Section > 0)
		$sConditions .= " AND section_id='$Section' ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_tnc_points", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_tnc_points $sConditions ORDER BY position LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="4%">#</td>
				      <td width="36%">Point</td>
                                      <td width="20%">Category</td>
  				      <td width="18%">Section</td>
                                      <td width="10%">Nature</td>
				      <td width="12%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}


		$iId            = $objDb->getField($i, 'id');
		$iSection       = $objDb->getField($i, 'section_id');
		$iCategory      = $objDb->getField($i, 'category_id');
		$sPoint         = $objDb->getField($i, 'point');
                $sComments      = $objDb->getField($i, 'comments');
                $sBrands        = $objDb->getField($i, 'brands');
                $AuditTypesStr  = $objDb->getField($i, 'audit_types');
                $iPointNo       = $objDb->getField($i, 'point_no');
		$sNature        = $objDb->getField($i, 'nature');
		$iPosition      = $objDb->getField($i, 'position');

		$iNextId     = $objDb->getField(($i + 1), 'id');
		$iPreviousId = $objDb->getField(($i - 1), 'id');

		$iNextPosition     = $objDb->getField(($i + 1), 'position');
		$iPreviousPosition = $objDb->getField(($i - 1), 'position');

		switch ($sNature)
		{
			case "C" : $sNatureText = "Critical"; break;
			case "Z" : $sNatureText = "Zero Tolerance"; break;
			default  : $sNatureText = "Standard"; break;
		}
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="4%"><?= ($iStart + $i + 1) ?></td>
				      <td width="36%"><span id="Point<?= $iId ?>"><?= $sPoint ?></span></td>
					  <td width="20%"><span id="DCategory<?= $iId ?>"><?= $sCategoriesList[$iCategory] ?></span></td>
					  <td width="18%"><span id="Section<?= $iId ?>"><?= $sSectionList[$iSection] ?></span></td>
                      <td width="10%"><span id="Nature<?= $iId ?>"><?= $sNatureText ?></span></td>
				      <td width="12%" class="right">
<?
		if ($i > 0 && $sUserRights['Edit'] == "Y")
		{
?>
						<a href="crc/update-tnc-point-position.php?CurId=<?= $iId ?>&CurOrder=<?= $iPosition ?>&NewId=<?= $iPreviousId ?>&NewOrder=<?= $iPreviousPosition ?>"><img src="images/icons/up.gif" width="16" height="16" alt="Up" title="Up" border="0" align="absmiddle"></a>
						&nbsp;
<?
		}

		if ($i < ($iCount - 1) && $sUserRights['Edit'] == "Y")
		{
?>
						<a href="crc/update-tnc-point-position.php?CurId=<?= $iId ?>&CurOrder=<?= $iPosition ?>&NewId=<?= $iNextId ?>&NewOrder=<?= $iNextPosition ?>"><img src="images/icons/down.gif" width="16" height="16" alt="Down" title="Down" border="0" align="absmiddle"></a>
						&nbsp;
<?
		}

		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="./" onclick="Effect.SlideDown('Edit<?= $iId ?>'); return false;"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="crc/delete-tnc-point.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Point?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
<?
		}
?>
				      </td>
				    </tr>
				  </table>

				  <div id="Edit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" onsubmit="return false;" class="frmInlineEdit">
					  <input type="hidden" name="Id" value="<?= $iId ?>" />

					  <table border="0" cellpadding="3" cellspacing="0" width="320px">
					    <tr>
						  <td width="90">Section</td>
						  <td width="20" align="center">:</td>

						  <td>
						    <select name="Section" onchange="getCategories(this.value, 'Category<?= $iId ?>')">
						      <option value=""></option>
<?
		foreach ($sParentSectionsList as $sLabelKey => $sLabel)
		{
                    $sSectionsList = getList("tbl_tnc_sections", "id", "section", "parent_id=$sLabelKey");
?>
                        <optgroup label="<?php echo $sLabel; ?>">
                        <?
                            foreach ($sSectionsList as $sKey => $sValue)
                            {?>
                                <option value="<?= $sKey ?>"<?= (($sKey == $iSection) ? " selected" : "") ?>><?= $sValue ?></option>
<?                          }
                        ?>        
                        </optgroup>                            
			            
<?
		}
?>
						    </select>
						  </td>
						</tr>

						<tr>
						  <td>Category</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Category" id="Category<?= $iId ?>">
						      <option value=""></option>
<?
		$sSectionCategoriesList = getList("tbl_tnc_categories", "id", "category", "section_id='$iSection'");

		foreach ($sSectionCategoriesList as $sKey => $sValue)
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
						  <td>Brand</td>
						  <td align="center">:</td>

						  <td>
                                                      <select name="Brand[]" id="Brand<?= $iId ?>" multiple>
						      <option value=""></option>
<?
		foreach ($sBrandsList as $iBrand => $sBrand)
		{
?>
                                                      <option value="<?= $iBrand ?>"<?= (@in_array($iBrand, explode(",", $sBrands)) ? " selected" : "") ?>><?= $sBrand ?></option>
<?
		}
?>
						    </select>
						  </td>
						</tr>
                                              
                                        <tr>
					<td>Audit Type</td>
					<td align="center">:</td>

					<td>
					  <select name="AuditType[]" id="AuditType" style="height: 120px; width: 200px;" multiple>
						<option value=""></option>
<?
			foreach ($sAuditTypes as $iAuditType => $sAuditType)
			{
?>
                                                <option value="<?= $iAuditType ?>"<?= ((@in_array($iAuditType, explode(",", $AuditTypesStr))) ? " selected" : "") ?>><?= $sAuditType ?></option>
<?
			}
?>
					  </select>
					</td>
				  </tr> 

						<tr>
						  <td>Nature</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Nature">
							  <option value=""></option>
							  <option value="S"<?= (($sNature == 'S') ? " selected" : "") ?>>Standard</option>
							  <option value="C"<?= (($sNature == 'C') ? " selected" : "") ?>>Critical</option>
							  <option value="Z"<?= (($sNature == 'Z') ? " selected" : "") ?>>Zero Tolerance</option>
						    </select>
						  </td>
						 </tr>
                                               <tr valign="top">
                                                    <td>Point #</td>
                                                    <td align="center">:</td>
                                                    <td><input type="text" name="PointNo" value="<?= $iPointNo ?>" maxlength="100" size="14" class="textbox" /></td>
                                               </tr>  
						<tr valign="top">
						  <td>Point</td>
						  <td align="center">:</td>
						  <td><textarea name="Point" rows="4" cols="60" ><?= $sPoint ?></textarea></td>
						</tr>
                                              
                                               <tr valign="top">
						  <td>Notes</td>
						  <td align="center">:</td>
						  <td><textarea name="Comments" rows="4" cols="60" ><?= $sComments ?></textarea></td>
						</tr>
                                              
						<tr>
						  <td></td>
						  <td></td>

						  <td>
						    <input type="submit" value="SAVE" class="btnSmall" onclick="validateEditForm(<?= $iId ?>);" />
						    <input type="button" value="CANCEL" class="btnSmall" onclick="Effect.SlideUp('Edit<?= $iId ?>');" />
						  </td>
					    </tr>
					  </table>
					  </form>

				    </div>
				  </div>

				  <div id="Msg<?= $iId ?>" class="msgOk" style="display:none;"></div>

<?
	}

	if ($iCount == 0)
	{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr>
				      <td class="noRecord">No Category Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Category={$Category}&Section={$Section}");
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