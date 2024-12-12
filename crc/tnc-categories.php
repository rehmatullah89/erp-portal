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

	$PageId   = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Category = IO::strValue("Category");
	$Section  = IO::intValue("Section");
	$PostId   = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Category = IO::strValue("Category");
		$Section  = IO::intValue("Section");
	}


	$sAllSectionsList = getList("tbl_tnc_sections", "id", "section");
        $sParentSectionsList = getList("tbl_tnc_sections", "id", "section", "parent_id='0'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/crc/tnc-categories.js"></script>
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
			    <h1>CRC Categories</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="crc/save-tnc-category.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Category</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
                    <td width="60">Section</td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Section">
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
					<td><input type="text" name="Category" value="<?= $Category ?>" maxlength="100" size="30" class="textbox" /></td>
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
			          <td width="65">Category</td>
			          <td width="180"><input type="text" name="Category" value="<?= $Category ?>" class="textbox" maxlength="50" /></td>

			          <td width="55">Section</td>

			          <td width="150">
					    <select name="Section">
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

	if ($Category != "")
		$sConditions .= " AND category LIKE '%$Category%' ";

	if ($Section > 0)
		$sConditions .= " AND section_id='$Section' ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_tnc_categories", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_tnc_categories $sConditions ORDER BY position LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="6%">#</td>
				      <td width="40%">Category</td>
				      <td width="40%">Section</td>
				      <td width="14%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId       = $objDb->getField($i, 'id');
		$iSection  = $objDb->getField($i, 'section_id');
		$sCategory = $objDb->getField($i, 'category');
		$iPosition = $objDb->getField($i, 'position');

		$iNextId     = $objDb->getField(($i + 1), 'id');
		$iPreviousId = $objDb->getField(($i - 1), 'id');

		$iNextPosition     = $objDb->getField(($i + 1), 'position');
		$iPreviousPosition = $objDb->getField(($i - 1), 'position');
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="6%"><?= ($iStart + $i + 1) ?></td>
				      <td width="40%"><span id="Category<?= $iId ?>"><?= $sCategory ?></span></td>
				      <td width="40%"><span id="Section<?= $iId ?>"><?= $sAllSectionsList[$iSection] ?></span></td>

				      <td width="14%" class="right">
<?
		if ($i > 0 && $sUserRights['Edit'] == "Y")
		{
?>
						<a href="crc/update-tnc-category-position.php?CurId=<?= $iId ?>&CurOrder=<?= $iPosition ?>&NewId=<?= $iPreviousId ?>&NewOrder=<?= $iPreviousPosition ?>"><img src="images/icons/up.gif" width="16" height="16" alt="Up" title="Up" border="0" align="absmiddle"></a>
						&nbsp;
<?
		}

		if ($i < ($iCount - 1) && $sUserRights['Edit'] == "Y")
		{
?>
						<a href="crc/update-tnc-category-position.php?CurId=<?= $iId ?>&CurOrder=<?= $iPosition ?>&NewId=<?= $iNextId ?>&NewOrder=<?= $iNextPosition ?>"><img src="images/icons/down.gif" width="16" height="16" alt="Down" title="Down" border="0" align="absmiddle"></a>
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
				        <a href="crc/delete-tnc-category.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Category?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
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

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
						<tr>
						  <td width="60">Section</td>
						  <td width="20" align="center">:</td>

						  <td>
						    <select name="Section">
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
						  <td><input type="text" name="Category" value="<?= $sCategory ?>" maxlength="100" size="30" class="textbox" /></td>
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