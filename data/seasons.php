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
	$Season = IO::strValue("Season");
	$Parent = IO::strValue("Parent");
	$PostId = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Brand     = IO::strValue("Brand");
		$Parent    = IO::strValue("Parent");
		$Season    = IO::strValue("Season");
		$StartDate = IO::strValue("StartDate");
		$EndDate   = IO::strValue("EndDate");
	}


	if (@strpos($_SESSION["Brands"], ",") === FALSE)
		$Brand = $_SESSION["Brands"];

	if (@strpos($_SESSION["Email"], "@apparelco.com") === FALSE && @strpos($_SESSION["Email"], "@3-tree.com") === FALSE)
		$sBrandsList = getList("tbl_brands", "id", "brand", "parent_id='0' AND id IN (SELECT DISTINCT(b.parent_id) FROM tbl_brands b WHERE b.id IN ({$_SESSION['Brands']}))");

	else
		$sBrandsList = getList("tbl_brands", "id", "brand", "parent_id='0'");


	$sSeasonsList    = getList("tbl_seasons", "id", "season", "brand_id='$Brand' AND parent_id='0'");
	$sAllSeasonsList = getList("tbl_seasons", "id", "season");
	$sSamplingTypes  = getList("tbl_sampling_types", "id", "type");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/seasons.js"></script>
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
			    <h1>Seasons Listing</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="data/save-season.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Season</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="70">Brand</td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Brand" id="Brand0" onchange="getListValues('Brand0', 'Parent0', 'Seasons');">
						<option value=""></option>
<?
		foreach ($sBrandsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Brand) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Parent</td>
					<td align="center">:</td>

					<td>
					  <select name="Parent" id="Parent0">
						<option value=""></option>
<?
		foreach ($sSeasonsList as $sKey => $sValue)
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
					<td>Season<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Season" value="<?= $Season ?>" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr valign="top">
					<td>Start Date*</td>
					<td align="center">:</td>

					<td>

					  <table border="0" cellpadding="0" cellspacing="0" width="116">
						<tr>
						  <td width="82"><input type="text" name="StartDate" id="StartDate" value="<?= $StartDate ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('StartDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('StartDate'), 'yyyy-mm-dd', this);" /></td>
						</tr>
					  </table>

					</td>
				  </tr>

				  <tr valign="top">
					<td>End Date*</td>
					<td align="center">:</td>

					<td>

					  <table border="0" cellpadding="0" cellspacing="0" width="116">
						<tr>
						  <td width="82"><input type="text" name="EndDate" id="EndDate" value="<?= $EndDate ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('EndDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('EndDate'), 'yyyy-mm-dd', this);" /></td>
						</tr>
					  </table>

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
			          <td width="45">Brand</td>
			          <td width="150">
					    <select name="Brand" id="Brand" onchange="getListValues('Brand', 'Parent', 'Seasons');">
						  <option value="">All Brands</option>
<?
	foreach ($sBrandsList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Brand) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
			          </td>

			          <td width="50">Parent</td>

			          <td width="150">
					    <select name="Parent" id="Parent">
						  <option value="">All Seasons</option>
<?
	foreach ($sSeasonsList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Parent) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
			          </td>

			          <td width="55">Season</td>
			          <td width="160"><input type="text" name="Season" value="<?= $Season ?>" class="textbox" maxlength="50" /></td>
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

	if ($Brand > 0)
		$sConditions .= " AND brand_id='$Brand' ";

	else if (@strpos($_SESSION["Email"], "@apparelco.com") === FALSE && @strpos($_SESSION["Email"], "@3-tree.com") === FALSE)
		$sConditions .= " AND brand_id IN (SELECT DISTINCT(b.parent_id) FROM tbl_brands b WHERE b.id IN ({$_SESSION['Brands']})) ";

	if ($Parent > 0)
		$sConditions .= " AND parent_id='$Parent' ";

	if ($Season != "")
		$sConditions .= " AND season LIKE '%$Season%' ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_seasons", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_seasons $sConditions ORDER BY position LIMIT $iStart, $iPageSize";
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
				      <td width="16%">Season</td>
				      <td width="17%">Parent</td>
				      <td width="20%">Brand</td>
				      <td width="12%">Start Date</td>
				      <td width="12%">End Date</td>
				      <td width="15%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId        = $objDb->getField($i, 'id');
		$iBrand     = $objDb->getField($i, 'brand_id');
		$iParent    = $objDb->getField($i, 'parent_id');
		$sSeason    = $objDb->getField($i, 'season');
		$sStartDate = $objDb->getField($i, 'start_date');
		$sEndDate   = $objDb->getField($i, 'end_date');
		$iPosition  = $objDb->getField($i, 'position');

		$iNextId     = $objDb->getField(($i + 1), 'id');
		$iPreviousId = $objDb->getField(($i - 1), 'id');

		$iNextPosition     = $objDb->getField(($i + 1), 'position');
		$iPreviousPosition = $objDb->getField(($i - 1), 'position');
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="8%"><?= ($iStart + $i + 1) ?></td>
				      <td width="16%"><span id="Season<?= $iId ?>"><?= $sSeason ?></span></td>
				      <td width="17%"><span id="Parent_<?= $iId ?>"><?= $sAllSeasonsList[$iParent] ?></span></td>
				      <td width="20%"><span id="Brand_<?= $iId ?>"><?= $sBrandsList[$iBrand] ?></span></td>
				      <td width="12%"><span id="StartDate_<?= $iId ?>"><?= formatDate($sStartDate) ?></span></td>
				      <td width="12%"><span id="EndDate_<?= $iId ?>"><?= formatDate($sEndDate) ?></span></td>

				      <td width="15%" class="center">
<?
		if ($i > 0 && $sUserRights['Edit'] == "Y")
		{
?>
						<a href="data/update-season-position.php?CurId=<?= $iId ?>&CurOrder=<?= $iPosition ?>&NewId=<?= $iPreviousId ?>&NewOrder=<?= $iPreviousPosition ?>"><img src="images/icons/up.gif" width="16" height="16" alt="Up" title="Up" border="0" align="absmiddle"></a>
						&nbsp;
<?
		}

		if ($i < ($iCount - 1) && $sUserRights['Edit'] == "Y")
		{
?>
						<a href="data/update-season-position.php?CurId=<?= $iId ?>&CurOrder=<?= $iPosition ?>&NewId=<?= $iNextId ?>&NewOrder=<?= $iNextPosition ?>"><img src="images/icons/down.gif" width="16" height="16" alt="Down" title="Down" border="0" align="absmiddle"></a>
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
				        <a href="data/delete-season.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Season?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
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
					    <tr valign="top">
						  <td width="70">Brand<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>

						  <td>
						    <select name="Brand" id="Brand<?= $iId ?>" onchange="getListValues('Brand<?= $iId ?>', 'Parent<?= $iId ?>', 'Seasons');">
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
						  </td>

<?
		if ($iParent > 0)
		{
			$sSQL = "SELECT id, type_id, start_date, end_date FROM tbl_sampling_cutoff_dates WHERE season_id='$iId' ORDER BY start_date";
			$objDb2->query($sSQL);

			$iCount2 = $objDb2->getCount( );

			if ($iCount2 > 0)
			{
?>
						  <td rowspan="6" width="450">

						    <table border="0" cellpadding="2" cellspacing="0" width="90%">
						      <tr>
						        <td><b>Sampling Type</b></td>
						        <td width="76"><b>Start Date</b></td>
						        <td width="46"></td>
						        <td width="76"><b>End Date</b></td>
						        <td width="34"></td>
						      </tr>

<?
				for ($j = 0; $j < $iCount2; $j ++)
				{
					$iDateId          = $objDb2->getField($j, "id");
					$iTypeId          = $objDb2->getField($j, "type_id");
					$sCutOffStartDate = $objDb2->getField($j, "start_date");
					$sCutOffEndDate   = $objDb2->getField($j, "end_date");
?>
						      <tr>
							    <td><?= $sSamplingTypes[$iTypeId] ?></td>
							    <td><input type="text" name="CutOffStartDate<?= $iDateId ?>" id="CutOffStartDate<?= $iDateId ?>" value="<?= (($sCutOffStartDate == "0000-00-00") ? "" : $sCutOffStartDate) ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('CutOffStartDate<?= $iDateId ?>'), 'yyyy-mm-dd', this);" /></td>
							    <td><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('CutOffStartDate<?= $iDateId ?>'), 'yyyy-mm-dd', this);" /></td>
							    <td><input type="text" name="CutOffEndDate<?= $iDateId ?>" id="CutOffEndDate<?= $iDateId ?>" value="<?= (($sCutOffEndDate == "0000-00-00") ? "" : $sCutOffEndDate) ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('CutOffEndDate<?= $iDateId ?>'), 'yyyy-mm-dd', this);" /></td>
							    <td><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('CutOffEndDate<?= $iDateId ?>'), 'yyyy-mm-dd', this);" /></td>
						      </tr>
<?
				}
?>
						    </table>

						  </td>
<?
			}
		}
?>
					    </tr>

						<tr>
						  <td>Parent</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Parent" id="Parent<?= $iId ?>">
						      <option value=""></option>
<?
		$sSeasonsList = getList("tbl_seasons", "id", "season", "brand_id='$iBrand' AND parent_id='0'");

		foreach ($sSeasonsList as $sKey => $sValue)
		{
?>
			                  <option value="<?= $sKey ?>"<?= (($sKey == $iParent) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
						</tr>

					    <tr>
						  <td>Season<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="Season" value="<?= $sSeason ?>" maxlength="50" class="textbox" /></td>
						</tr>

					    <tr valign="top">
						  <td>Start Date*</td>
						  <td align="center">:</td>

						  <td>

						    <table border="0" cellpadding="0" cellspacing="0" width="116">
							  <tr>
							    <td width="82"><input type="text" name="StartDate" id="StartDate<?= $iId ?>" value="<?= (($sStartDate == "0000-00-00") ? "" : $sStartDate) ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('StartDate<?= $iId ?>'), 'yyyy-mm-dd', this);" /></td>
							    <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('StartDate<?= $iId ?>'), 'yyyy-mm-dd', this);" /></td>
							  </tr>
 						    </table>

						  </td>
					    </tr>

					    <tr valign="top">
						  <td>End Date*</td>
						  <td align="center">:</td>

						  <td>

						    <table border="0" cellpadding="0" cellspacing="0" width="116">
							  <tr>
							    <td width="82"><input type="text" name="EndDate" id="EndDate<?= $iId ?>" value="<?= (($sEndDate == "0000-00-00") ? "" : $sEndDate) ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('EndDate<?= $iId ?>'), 'yyyy-mm-dd', this);" /></td>
							    <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('EndDate<?= $iId ?>'), 'yyyy-mm-dd', this);" /></td>
							  </tr>
						    </table>

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
				      <td class="noRecord">No Season Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Brand={$Brand}&Season={$Season}&Parent={$Parent}");
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