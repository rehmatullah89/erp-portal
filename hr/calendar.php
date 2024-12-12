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

	$PageId   = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Title    = IO::strValue("Title");
	$ToDate   = IO::strValue("ToDate");
	$FromDate = IO::strValue("FromDate");
	$Employee = IO::intValue("Employee");
	$PostId   = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Employee = IO::getArray("Employee");
		$Title    = IO::strValue("Title");
		$FromDate = IO::strValue("FromDate");
		$ToDate   = IO::strValue("ToDate");
		$Details  = IO::strValue("Details");
	}

	$sEmployeesList    = getList("tbl_users", "id", "CONCAT(name, ' (', COALESCE((SELECT designation FROM tbl_designations WHERE id=tbl_users.designation_id), 'N/A'), ')')", "(email LIKE '%@apparelco.com' OR email LIKE '%@3-tree.com' OR email LIKE '%@lulusar.com') AND status='A'");
	$sAllEmployeesList = getList("tbl_users", "id", "CONCAT(name, ' (', COALESCE((SELECT designation FROM tbl_designations WHERE id=tbl_users.designation_id), 'N/A'), ')')", "(email LIKE '%@apparelco.com' OR email LIKE '%@3-tree.com' OR email LIKE '%@lulusar.com')");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/hr/calendar.js"></script>
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
			    <h1>Calendar</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="hr/save-calendar-entry.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Calendar Entry</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr valign="top">
					<td width="70">Employee</td>
					<td width="20" align="center">:</td>

					<td>
			          <select name="Employee[]" id="Employee" multiple size="8">
<?
		foreach ($sEmployeesList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Employee)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
		              </select>
					</td>
				  </tr>

				  <tr>
					<td>Title<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Title" value="<?= $Title ?>" size="30" maxlength="200" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>From Date<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>

					  <table border="0" cellpadding="0" cellspacing="0" width="116">
						<tr>
						  <td width="82"><input type="text" name="FromDate" id="FromDate" value="<?= (($FromDate == "") ? date("Y-m-d") : $FromDate) ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
						</tr>
					  </table>

					</td>
			      </tr>

				  <tr>
					<td>To Date<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>

					  <table border="0" cellpadding="0" cellspacing="0" width="116">
						<tr>
						  <td width="82"><input type="text" name="ToDate" id="ToDate" value="<?= (($ToDate == "") ? date("Y-m-d") : $ToDate) ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
						</tr>
					  </table>

					</td>
			      </tr>

				  <tr valign="top">
					<td>Details</td>
					<td align="center">:</td>
					<td><textarea name="Details" rows="4" cols="30"><?= $Details ?></textarea></td>
				  </tr>

				  <tr>
					<td align="right"><input type="checkbox" name="Private" id="Private" value="Y" <?= ((IO::strValue("Private") == "Y") ? "checked" : "") ?> /></td>
					<td align="center">:</td>
					<td><label for="Private">Mark this as Private Entry</label></td>
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
			          <td width="40">Title</td>
			          <td width="165"><input type="text" name="Title" value="<?= $Title ?>" class="textbox" maxlength="50" size="20" /></td>
			          <td width="70">Employee</td>

			          <td width="240">
			            <select name="Employee" style="width:230px;">
			              <option value="">All Employees</option>
<?
	foreach ($sAllEmployeesList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Employee) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
			            </select>
			          </td>

					  <td width="40">From</td>
					  <td width="78"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="From_Date" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('From_Date'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('From_Date'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30" align="center">To</td>
					  <td width="78"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="To_Date" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('To_Date'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('To_Date'), 'yyyy-mm-dd', this);" /></td>
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

	if ($Title != "")
		$sConditions = " AND title LIKE '%$Title%' ";

	if ($Employee != "")
		$sConditions .= " AND '$Employee' IN (users)";

	if ($FromDate != "" AND $ToDate != "")
		$sConditions .= " AND ((from_date BETWEEN '$FromDate' AND '$ToDate') OR (to_date BETWEEN '$FromDate' AND '$ToDate')) ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_calendar", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_calendar $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
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
				      <td width="35%">Title</td>
				      <td width="30%">Employee(s)</td>
				      <td width="20%">Dates</td>
				      <td width="10%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId       = $objDb->getField($i, 'id');
		$iUsers    = @explode(",", $objDb->getField($i, 'users'));
		$sTitle    = $objDb->getField($i, 'title');
		$sFromDate = $objDb->getField($i, 'from_date');
		$sToDate   = $objDb->getField($i, 'to_date');
		$sDetails  = $objDb->getField($i, 'details');
		$sPrivate  = $objDb->getField($i, 'private');

		$sUsers = "";

		for ($j = 0; $j < count($iUsers); $j ++)
			$sUsers .= ($sAllEmployeesList[$iUsers[$j]]."<br />");
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>" valign="top">
				      <td width="5%"><?= ($iStart + $i + 1) ?></td>
				      <td width="35%"><span id="Title<?= $iId ?>"><?= $sTitle ?></span></td>
				      <td width="30%"><span id="Employee<?= $iId ?>"><?= $sUsers ?></span></td>
				      <td width="20%"><span id="Dates<?= $iId ?>"><?= formatDate($sFromDate) ?> <b>to</b> <?= formatDate($sToDate) ?></span></td>

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
				        <a href="hr/delete-calendar-entry.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Calendar Entry?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
				        &nbsp;
<?
		}
?>
				        <a href="hr/view-calendar-entry.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="Calendar Entry # <?= $iId ?> :: ::"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a>
				      </td>
				    </tr>
				  </table>

				  <div id="Edit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" onsubmit="return false;" class="frmInlineEdit">
					  <input type="hidden" name="Id" value="<?= $iId ?>" />

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr valign="top">
						  <td width="70">Employee</td>
						  <td width="20" align="center">:</td>

						  <td>
						    <select name="Employee[]" id="Employee<?= $iId ?>" multiple size="8">
<?
		foreach ($sEmployeesList as $sKey => $sValue)
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
						  <td>Title<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="Title" value="<?= $sTitle ?>" size="30" maxlength="200" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>From Date<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>

						    <table border="0" cellpadding="0" cellspacing="0" width="116">
							  <tr>
							    <td width="82"><input type="text" name="FromDate" id="FromDate<?= $iId ?>" value="<?= $sFromDate ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate<?= $iId ?>'), 'yyyy-mm-dd', this);" /></td>
							    <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate<?= $iId ?>'), 'yyyy-mm-dd', this);" /></td>
							  </tr>
						    </table>

						  </td>
					    </tr>

					    <tr>
						  <td>To Date<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>

						    <table border="0" cellpadding="0" cellspacing="0" width="116">
							  <tr>
							    <td width="82"><input type="text" name="ToDate" id="ToDate<?= $iId ?>" value="<?= $sToDate ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate<?= $iId ?>'), 'yyyy-mm-dd', this);" /></td>
							    <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate<?= $iId ?>'), 'yyyy-mm-dd', this);" /></td>
						  	  </tr>
						    </table>

						  </td>
					    </tr>

					    <tr valign="top">
						  <td>Details</td>
						  <td align="center">:</td>
						  <td><textarea name="Details" rows="4" cols="30"><?= $sDetails ?></textarea></td>
					    </tr>

					    <tr>
						  <td align="right"><input type="checkbox" name="Private" id="Private<?= $iId ?>" value="Y" <?= (($sPrivate == "Y") ? "checked" : "") ?> /></td>
						  <td align="center">:</td>
						  <td><label for="Private<?= $iId ?>">Mark this as Private Entry</label></td>
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
				      <td class="noRecord">No Calendar Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Title={$Title}&Employee={$Employee}&FromDate={$FromDate}&ToDate={$ToDate}");
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