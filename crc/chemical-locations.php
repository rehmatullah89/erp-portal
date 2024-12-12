<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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
	$Type      = IO::intValue("Type");
	$Location  = IO::strValue("Location");
    $Address   = IO::strValue("Address");
	$Person    = IO::strValue("Person");
	$ContactNo = IO::strValue("ContactNo");
	$PostId    = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Type      = IO::intValue("Type");
		$Location  = IO::strValue("Location");
		$Address   = IO::strValue("Address");
		$Person    = IO::strValue("Person");
		$ContactNo = IO::strValue("ContactNo");
	}

	$sTypeList = getList("tbl_chemical_location_types", "id", "type");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/crc/chemical-locations.js"></script>
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
			    <h1>Chemical Locations</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="crc/save-chemical-location.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Chemical Location</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="100">Location Type<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Type">
					    <option value="">Select Location Type</option>
<?
		foreach ($sTypeList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Type) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>
  
				  <tr>
					<td>Location Title<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Location" value="<?= $Location ?>" maxlength="100" size="30" class="textbox" /></td>
				  </tr>
				  
				  <tr valign="top">
					<td>Address</td>
					<td align="center">:</td>
					<td><textarea name="Address" rows="3" cols="50"><?= $Address ?></textarea></td>
				  </tr>
  
				  <tr>
					<td>Contact Person</td>
					<td align="center">:</td>
					<td><input type="text" name="Person" value="<?= $Person ?>" maxlength="100" size="30" class="textbox" /></td>
				  </tr>  
				  
				  <tr>
					<td>Contact No</td>
					<td align="center">:</td>
					<td><input type="text" name="ContactNo" value="<?= $ContactNo ?>" maxlength="20" size="30" class="textbox" /></td>
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
			          <td width="85">Location</td>
			          <td width="200"><input type="text" name="Location" value="<?= $Location ?>" class="textbox" maxlength="50" /></td>				  
			          <td width="40">Type</td>
					  
			          <td width="200">
					    <select name="Type">
						  <option value="">All Types</option>
<?
	foreach ($sTypeList as $sKey => $sValue)
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
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";

	if ($Location != "")
		$sConditions .= " AND (location LIKE '%$Location%' OR address LIKE '%$Address%' OR person LIKE '%$Address%' OR contact_no LIKE '%$Address%') ";

	if ($Type != "")
		$sConditions .= " AND type_id='$Type' ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_chemical_locations", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_chemical_locations $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
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
				      <td width="25%">Location</td>
				      <td width="35%">Address</td>
					  <td width="20%">Location Type</td>					  
				      <td width="12%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId        = $objDb->getField($i, 'id');
		$iType      = $objDb->getField($i, 'type_id');
		$sLocation  = $objDb->getField($i, 'location');
        $sAddress   = $objDb->getField($i, 'address');
		$sPerson    = $objDb->getField($i, 'person');
		$sContactNo = $objDb->getField($i, 'contact_no');
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="8%"><?= ($iStart + $i + 1) ?></td>
				      <td width="25%"><span id="Location<?= $iId ?>"><?= $sLocation ?></span></td>
                      <td width="35%"><span id="Address<?= $iId ?>"><?= $sAddress ?></span></td>
				      <td width="20%"><span id="Type<?= $iId ?>"><?= $sTypeList[$iType] ?></span></td>					  
				      <td width="12%" class="right">
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
						<a href="crc/delete-chemical-location.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Location?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
<?
		}
?>
				      <a href="crc/view-chemical-location.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="Chemical Location :: :: width: 400, height: 350"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a>
					  </td>
				    </tr>
				  </table>

				  <div id="Edit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" onsubmit="return false;" class="frmInlineEdit">
					  <input type="hidden" name="Id" value="<?= $iId ?>" />

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
                        <tr>
						  <td width="100">Location Type<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>

						  <td>
						    <select name="Type">
						      <option value="">Select Location Type</option>
<?
		foreach ($sTypeList as $sKey => $sValue)
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
						  <td>Location Title<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="Location" value="<?= $sLocation ?>" maxlength="100" size="30" class="textbox" /></td>
						</tr>
					  
					    <tr valign="top">
						  <td>Address</td>
						  <td align="center">:</td>
						  <td><textarea name="Address" rows="3" cols="50"><?= $sAddress ?></textarea></td>
					    </tr>
	  
					    <tr>
						  <td>Contact Person</td>
						  <td align="center">:</td>
						 <td><input type="text" name="Person" value="<?= $sPerson ?>" maxlength="100" size="30" class="textbox" /></td>
					    </tr>  
					  
					    <tr>
						  <td>Contact No</td>
						  <td align="center">:</td>
						  <td><input type="text" name="ContactNo" value="<?= $sContactNo ?>" maxlength="20" size="30" class="textbox" /></td>
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
				      <td class="noRecord">No Location Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Location={$Location}&Type={$Type}");
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