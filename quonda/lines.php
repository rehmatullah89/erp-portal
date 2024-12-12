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
	$Line   = IO::strValue("Line");
	$Vendor = IO::intValue("Vendor");
	$PostId = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Line   = IO::strValue("Line");
		$Vendor = IO::strValue("Vendor");
		$Unit   = IO::strValue("Unit");
		$Floor  = IO::intValue("Floor");
		$Type   = IO::strValue("Type");
	}

	$sVendorsList    = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sSubVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id>'0' AND sourcing='Y'");
	$sLineTypesList  = getList("tbl_line_types", "id", "type");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/quonda/lines.js"></script>
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
			    <h1>lines</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="quonda/save-line.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Line</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="50">Line<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="Line" value="<?= $Line ?>" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Vendor<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Vendor" id="Vendor" onchange="getListValues('Vendor', 'Unit', 'VendorUnits'); getListValues('Vendor', 'Line', 'Lines'); getListValues('Vendor', 'Floor', 'VendorFloors');">
						<option value=""></option>
<?
		foreach ($sVendorsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Vendor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Unit</td>
					<td align="center">:</td>

					<td>
					  <select name="Unit" id="Unit" onchange="getListValues('Unit', 'Floor', 'UnitFloors');">
						<option value=""></option>
<?
		$sUnitsList = array( );

		if ($Vendor > 0)
			$sUnitsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='$Vendor' AND sourcing='Y'");

		foreach ($sUnitsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Unit) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Floor</td>
					<td align="center">:</td>

					<td>
					  <select name="Floor" id="Floor">
						<option value=""></option>
<?
		$sFloorsList = array( );

		if ($Vendor > 0 && $Unit > 0)
			$sFloorsList = getList("tbl_floors", "id", "floor", "vendor_id='$Vendor' AND unit_id='$Unit'");

		else if ($Vendor > 0)
			$sFloorsList = getList("tbl_floors", "id", "floor", "vendor_id='$Vendor' AND unit_id='0'");

		foreach ($sFloorsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Floor) ? " selected" : "") ?>><?= $sValue ?></option>
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
					  <select name="Type" id="Type">
						<option value=""></option>
<?
		foreach ($sLineTypesList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Type) ? " selected" : "") ?>><?= $sValue ?></option>
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
			          <td width="45">Line</td>
			          <td width="180"><input type="text" name="Line" value="<?= $Line ?>" class="textbox" maxlength="50" line="20" /></td>

			          <td width="55">Vendor</td>
			          <td width="200">
					    <select name="Vendor">
						  <option value="">All Vendors</option>
<?
	foreach ($sVendorsList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Vendor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
			          </td>

			          <td width="45">Type</td>
			          <td width="200">
					    <select name="Type">
						  <option value="">All Types</option>
<?
	foreach ($sLineTypeList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Type) ? " selected" : "") ?>><?= $sValue ?></option>
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
	$sAllUnitsList  = getList("tbl_vendors", "id", "vendor");
	$sAllFloorsList = getList("tbl_floors", "id", "floor");


	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";

	if ($Line != "")
		$sConditions .= " AND line LIKE '%$Line%' ";

	if ($Vendor > 0)
		$sConditions .= " AND vendor_id='$Vendor' ";

	if ($Type > 0)
		$sConditions .= " AND type_id='$Type' ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_lines", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT id, vendor_id, unit_id, floor_id, type_id, line FROM tbl_lines $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
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
				      <td width="15%">Line</td>
				      <td width="15%">Type</td>
				      <td width="12%">Floor</td>
				      <td width="18%">Unit</td>
				      <td width="20%">Vendor</td>
				      <td width="12%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId     = $objDb->getField($i, 'id');
		$iVendor = $objDb->getField($i, 'vendor_id');
		$iUnit   = $objDb->getField($i, 'unit_id');
		$iType   = $objDb->getField($i, 'type_id');
		$iFloor  = $objDb->getField($i, 'floor_id');
		$sLine   = $objDb->getField($i, 'line');
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="8%"><?= ($iStart + $i + 1) ?></td>
				      <td width="15%"><span id="Line_<?= $iId ?>"><?= $sLine ?></span></td>
				      <td width="15%"><span id="Type_<?= $iId ?>"><?= $sLineTypesList[$iType] ?></span></td>
				      <td width="12%"><span id="Floor_<?= $iId ?>"><?= $sAllFloorsList[$iFloor] ?></span></td>
				      <td width="18%"><span id="Unit_<?= $iId ?>"><?= $sAllUnitsList[$iUnit] ?></span></td>
				      <td width="20%"><span id="Vendor_<?= $iId ?>"><?= $sVendorsList[$iVendor] ?></span></td>

				      <td width="12%" class="center">
<?
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
				        <a href="quonda/delete-line.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Line?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
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
						  <td width="35">Line<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="Line" value="<?= $sLine ?>" maxlength="50" class="textbox" /></td>
						</tr>

						<tr>
						  <td>Vendor</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Vendor" id="Vendor<?= $iId ?>" onchange="getListValues('Vendor<?= $iId ?>', 'Unit<?= $iId ?>', 'VendorUnits');">
						      <option value=""></option>
<?
		foreach ($sVendorsList as $sKey => $sValue)
		{
?>
			                  <option value="<?= $sKey ?>"<?= (($sKey == $iVendor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
						</tr>

						<tr>
						  <td>Unit</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Unit" id="Unit<?= $iId ?>">
						      <option value=""></option>
<?
		$sUnitsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='$iVendor' AND sourcing='Y'");

		foreach ($sUnitsList as $sKey => $sValue)
		{
?>
			                  <option value="<?= $sKey ?>"<?= (($sKey == $iUnit) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
						</tr>

						<tr>
						  <td>Floor</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Floor" id="Floor<?= $iId ?>">
						      <option value=""></option>
<?
		$sFloorsList = array( );

		if ($iVendor > 0 && $iUnit > 0)
			$sFloorsList = getList("tbl_floors", "id", "floor", "vendor_id='$iVendor' AND unit_id='$iUnit'");

		else if ($iVendor > 0)
			$sFloorsList = getList("tbl_floors", "id", "floor", "vendor_id='$iVendor' AND unit_id='0'");

		foreach ($sFloorsList as $sKey => $sValue)
		{
?>
			                  <option value="<?= $sKey ?>"<?= (($sKey == $iFloor) ? " selected" : "") ?>><?= $sValue ?></option>
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
						    <select name="Type" id="Type<?= $iId ?>">
						      <option value=""></option>
<?
		foreach ($sLineTypesList as $sKey => $sValue)
		{
?>
			                  <option value="<?= $sKey ?>"<?= (($sKey == $iType) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
						</tr>

						<tr>
						  <td colspan="2"></td>

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
				      <td class="noRecord">No Line Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Line={$Line}&Vendor={$Vendor}&Type={$Type}");
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