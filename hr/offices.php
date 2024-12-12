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

	$PageId  = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Office  = IO::strValue("Office");
	$Country = IO::strValue("Country");
	$PostId  = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Office  = IO::strValue("Office");
		$Phone   = IO::strValue("Phone");
		$Fax     = IO::strValue("Fax");
		$Address = IO::strValue("Address");
		$Country = IO::strValue("Country");
	}

	$sCountriesList = getList("tbl_countries", "id", "country", "matrix='Y'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/hr/offices.js"></script>
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
			    <h1>Offices</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="hr/save-office.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add MATRIX Office</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="60">Office<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="Office" value="<?= $Office ?>" size="22" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Phone<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Phone" value="<?= $Phone ?>" size="22" maxlength="25" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Fax</td>
					<td align="center">:</td>
					<td><input type="text" name="Fax" value="<?= $Fax ?>" size="22" maxlength="25" class="textbox" /></td>
				  </tr>

				  <tr valign="top">
					<td>Address</td>
					<td align="center">:</td>
					<td><textarea name="Address" rows="4" cols="30"><?= $Address ?></textarea></td>
				  </tr>

				  <tr>
					<td>Country<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Country">
						<option value=""></option>
<?
		foreach ($sCountriesList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Country) ? " selected" : "") ?>><?= $sValue ?></option>
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
			          <td width="70">Office</td>
			          <td width="165"><input type="text" name="Office" value="<?= $Office ?>" class="textbox" maxlength="50" size="20" /></td>
			          <td width="60">Country</td>

			          <td width="150">
					    <select name="Country">
						  <option value="">All Countries</option>
<?
	foreach ($sCountriesList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Country) ? " selected" : "") ?>><?= $sValue ?></option>
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

	if ($Office != "")
		$sConditions = " AND office LIKE '%$Office%' ";

	if ($Country != "")
		$sConditions .= " AND country_id='$Country' ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_offices", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_offices $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="5%">#</td>
				      <td width="15%">Office</td>
				      <td width="16%">Phone</td>
				      <td width="16%">Fax</td>
				      <td width="28%">Address</td>
				      <td width="12%">Country</td>
				      <td width="8%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId      = $objDb->getField($i, 'id');
		$sOffice  = $objDb->getField($i, 'office');
		$sPhone   = $objDb->getField($i, 'phone');
		$sFax     = $objDb->getField($i, 'fax');
		$sAddress = $objDb->getField($i, 'address');
		$iCountry = $objDb->getField($i, 'country_id');

?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="5%"><?= ($iStart + $i + 1) ?></td>
				      <td width="15%"><span id="Office<?= $iId ?>"><?= $sOffice ?></span></td>
				      <td width="16%"><span id="Phone<?= $iId ?>"><?= $sPhone ?></span></td>
				      <td width="16%"><span id="Fax<?= $iId ?>"><?= $sFax ?></span></td>
				      <td width="28%"><span id="Address<?= $iId ?>"><?= $sAddress ?></span></td>
				      <td width="12%"><span id="Country<?= $iId ?>"><?= $sCountriesList[$iCountry] ?></span></td>

				      <td width="8%" class="center">
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
				        <a href="hr/delete-office.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Office?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
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
						  <td width="60">Office<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="Office" value="<?= $sOffice ?>" size="22" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Phone<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="Phone" value="<?= $sPhone ?>" size="22" maxlength="25" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Fax</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Fax" value="<?= $sFax ?>" size="22" maxlength="25" class="textbox" /></td>
					    </tr>

					    <tr valign="top">
						  <td>Address</td>
						  <td align="center">:</td>
						  <td><textarea name="Address" rows="4" cols="30"><?= $sAddress ?></textarea></td>
					    </tr>

					    <tr>
						  <td>Country<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select name="Country">
<?
		foreach ($sCountriesList as $sKey => $sValue)
		{
?>
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $iCountry) ? " selected" : "") ?>><?= $sValue ?></option>
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
				      <td class="noRecord">No Office Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Office={$Office}&Country={$Country}");
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