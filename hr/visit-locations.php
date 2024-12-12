<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  MATRIX Customer Portal                                                                   **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.apparelco.com                                                              **
	**                                                                                           **
	**  Copyright 2008-15 (C) Matrix Sourcing                                                    **
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

	$PageId   = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Location = IO::strValue("Location");
	$Country  = IO::strValue("Country");
	$PostId   = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Location = IO::strValue("Location");
		$Code     = IO::strValue("Code");
		$City     = IO::strValue("City");
		$Country  = IO::intValue("Country");
	}

	$sCountriesList = getList("tbl_countries", "id", "country");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/hr/visit-locations.js"></script>
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
			    <h1>Visit Locations</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="hr/save-visit-location.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Visit Location</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="60">Location<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="Location" value="<?= $Location ?>" maxlength="50" size="30" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Code<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Code" value="<?= $Code ?>" maxlength="5" size="10" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>City<span class="mandatory">*</span></td>
				    <td align="center">:</td>
				    <td><input type="text" name="City" value="<?= $City ?>" size="30" maxlength="100" class="textbox" /></td>
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
			          <td width="70">Location</td>
			          <td width="200"><input type="text" name="Location" value="<?= $Location ?>" class="textbox" maxlength="50" size="20" /></td>
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
	$sColor      = array(EVEN_ROW_COLOR, ODD_ROW_COLOR);
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";

	if ($Location != "")
		$sConditions = " WHERE location LIKE '%$Location%' ";

	if ($Country > 0)
		$sConditions .= " AND country_id='$Country' ";

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_visit_locations", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_visit_locations $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
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
				      <td width="30%">Location</td>
				      <td width="14%">Location Code</td>
				      <td width="18%">City</td>
				      <td width="20%">Country</td>
				      <td width="10%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId       = $objDb->getField($i, 'id');
		$sLocation = $objDb->getField($i, 'location');
		$sCode     = $objDb->getField($i, 'code');
		$sCity     = $objDb->getField($i, 'city');
		$iCountry  = $objDb->getField($i, 'country_id');
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="8%"><?= ($iStart + $i + 1) ?></td>
				      <td width="30%"><span id="Location<?= $iId ?>"><?= $sLocation ?></span></td>
				      <td width="14%"><span id="Code<?= $iId ?>"><?= $sCode ?></span></td>
				      <td width="18%"><span id="City<?= $iId ?>"><?= $sCity ?></span></td>
				      <td width="20%"><span id="Country<?= $iId ?>"><?= $sCountriesList[$iCountry] ?></span></td>

				      <td width="10%" class="center">
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
				        <a href="hr/delete-visit-location.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Visit Location?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
<?
		}
?>
				  </table>

				  <div id="Edit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" onsubmit="return false;" class="frmInlineEdit">
					  <input type="hidden" name="Id" value="<?= $iId ?>" />

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="40">Location<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="Location" value="<?= $sLocation ?>" maxlength="50" size="30" class="textbox" /></td>
						</tr>

						<tr>
						  <td>Code<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="Code" value="<?= $sCode ?>" maxlength="5" size="10" class="textbox" /></td>
						</tr>

					    <tr>
						  <td>City<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="City" value="<?= $sCity ?>" size="30" maxlength="100" class="textbox" /></td>
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
				      <td class="noRecord">No Visit Location Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Location={$Location}&Country={$Country}");
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