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

	$PageId     = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Department = IO::strValue("Department");
	$PostId     = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Department = IO::strValue("Department");
		$Code       = IO::getArray("Code");
		$Brands     = IO::getArray("Brands");
	}

	$sBrandsList          = getList("tbl_brands", "id", "brand", "parent_id>'0'");
	$sAllUsersList        = getList("tbl_users", "id", "name", "(email LIKE '%@apparelco.com' OR email LIKE '%@3-tree.com' OR email LIKE '%@lulusar.com')");
	$sQualityManagersList = getList("tbl_users", "id", "name", "designation_id IN (SELECT id FROM tbl_designations WHERE department_id='41') AND (email LIKE '%@apparelco.com' OR email LIKE '%@3-tree.com' OR email LIKE '%@lulusar.com') AND status='A'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/hr/departments.js"></script>
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
			    <h1>Departments</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="hr/save-department.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Department</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="75">Department<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="Department" value="<?= $Department ?>" maxlength="50" size="27" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Code<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Code" value="<?= $Code ?>" maxlength="5" size="10" class="textbox" /></td>
				  </tr>

				  <tr valign="top">
					<td>Brand(s)</td>
					<td align="center">:</td>

					<td>
					  <select name="Brands[]" multiple size="10" style="min-width:204px;">
<?
		foreach ($sBrandsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Brands)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
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
			          <td width="85">Department</td>
			          <td width="150"><input type="text" name="Department" value="<?= $Department ?>" class="textbox" maxlength="50" size="20" /></td>
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </form>
			    </div>

			    <div class="tblSheet">
<?
	$sClass      = array("evenRow", "oddRow");
	$sConditions = "";

	if ($Department != "")
		$sConditions = " WHERE (department LIKE '%$Department%' OR `code` LIKE '%$Department%') ";


	$sSQL = "SELECT * FROM tbl_departments $sConditions ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="8%">#</td>
				      <td width="24%">Department</td>
				      <td width="14%">Code</td>
				      <td width="20%">Brands</td>
				      <td width="20%">Quality Managers</td>
				      <td width="14%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId         = $objDb->getField($i, 'id');
		$sDepartment = $objDb->getField($i, 'department');
		$sCode       = $objDb->getField($i, 'code');
		$sBrands     = $objDb->getField($i, 'brands');
		$sUsers      = $objDb->getField($i, 'quality_managers');
		$iPosition   = $objDb->getField($i, 'position');

		$iBrands = @explode(",", $sBrands);
		$sBrands = "";

		for ($j = 0; $j < count($iBrands); $j ++)
			$sBrands .= ("- ".$sBrandsList[$iBrands[$j]]."<br />");


		$iUsers = @explode(",", $sUsers);
		$sUsers = "";

		for ($j = 0; $j < count($iUsers); $j ++)
			$sUsers .= ("- ".$sAllUsersList[$iUsers[$j]]."<br />");


		$iNextPosition     = $objDb->getField(($i + 1), 'position');
		$iPreviousPosition = $objDb->getField(($i - 1), 'position');
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>" valign="top">
				      <td width="8%"><?= ($iStart + $i + 1) ?></td>
				      <td width="24%"><span id="Department<?= $iId ?>"><?= $sDepartment ?></span></td>
				      <td width="14%"><span id="Code<?= $iId ?>"><?= $sCode ?></span></td>
				      <td width="20%"><span id="Brands<?= $iId ?>"><?= $sBrands ?></span></td>
				      <td width="20%"><span id="Users<?= $iId ?>"><?= $sUsers ?></span></td>

				      <td width="14%" class="center">
<?
		if ($i > 0 && $sUserRights['Edit'] == "Y")
		{
?>
						<a href="hr/update-department-position.php?Id=<?= $iId ?>&CurOrder=<?= $iPosition ?>&NewOrder=<?= $iPreviousPosition ?>"><img src="images/icons/up.gif" width="16" height="16" alt="Up" title="Up" border="0" align="absmiddle"></a>
						&nbsp;
<?
		}

		if ($i < ($iCount - 1) && $sUserRights['Edit'] == "Y")
		{
?>
						<a href="hr/update-department-position.php?Id=<?= $iId ?>&CurOrder=<?= $iPosition ?>&NewOrder=<?= $iNextPosition ?>"><img src="images/icons/down.gif" width="16" height="16" alt="Down" title="Down" border="0" align="absmiddle"></a>
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
				        <a href="hr/delete-department.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Department?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
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
						  <td width="115">Department<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="Department" value="<?= $sDepartment ?>" maxlength="50" size="30" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Code<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="Code" value="<?= $sCode ?>" maxlength="5" size="10" class="textbox" /></td>
					    </tr>

					    <tr valign="top">
						  <td>Brand(s)</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Brands[]" multiple size="10" style="min-width:204px;">
<?
		foreach ($sBrandsList as $sKey => $sValue)
		{
?>
			            	  <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $iBrands)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr valign="top">
						  <td>Quality Managers(s)</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Users[]" multiple size="10" style="min-width:204px;">
<?
		foreach ($sQualityManagersList as $sKey => $sValue)
		{
?>
			            	  <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $iUsers)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td></td>
						  <td></td>

						  <td>
						    <input type="submit" id="BtnSave<?= $i ?>" value="SAVE" class="btnSmall" onclick="return validateEditForm(<?= $iId ?>);" />
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
				      <td class="noRecord">No Department Record Found!</td>
				    </tr>
				  </table>
<?
	}
?>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Department={$Department}");
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