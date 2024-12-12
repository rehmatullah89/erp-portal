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

	$PageId  = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Title   = IO::strValue("Title");
	$PostId  = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Title     = IO::strValue("Title");
		$Purpose   = IO::strValue("Purpose");
		$Employees = IO::getArray("Employees");
		$FromDate  = IO::strValue("FromDate");
		$ToDate    = IO::strValue("ToDate");
	}

	$sDepartmentsList = getList("tbl_departments", "id", "department");
	$sEmployeesList   = getList("tbl_users", "id", "name", "(email LIKE '%@apparelco.com' OR email LIKE '%@3-tree.com' OR email LIKE '%@lulusar.com') AND status='A'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/hr/surveys.js"></script>
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
			    <h1>Surveys</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="hr/save-survey.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Survey</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="70">Title<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="Title" value="<?= $Title ?>" size="33" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr valign="top">
					<td>Purpose</td>
					<td align="center">:</td>
					<td><textarea name="Purpose" rows="4" cols="30"><?= $Purpose ?></textarea></td>
				  </tr>

				  <tr valign="top">
					<td>Employees<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select id="Employees" name="Employees[]" multiple size="10" style="width:220px;">
<?
		foreach ($sDepartmentsList as $sKey => $sValue)
		{
?>
	                    <optgroup label="<?= $sValue ?>">
<?
			$sSQL = "SELECT id, name FROM tbl_users WHERE designation_id IN (SELECT id FROM tbl_designations WHERE department_id='$sKey') AND status='A' ORDER BY name";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iId   = $objDb->getField($i, 0);
				$sName = $objDb->getField($i, 1);
?>
					    <option value="<?= $iId ?>"<?= ((@in_array($iId, $Employees)) ? ' selected' : '') ?>><?= $sName ?></option>
<?
			}
?>
					    </optgroup>
<?
		}
?>
					  </select>
					</td>
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
			          <td width="55">Survey</td>
			          <td width="165"><input type="text" name="Title" value="<?= $Title ?>" class="textbox" maxlength="50" size="20" /></td>
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
	$sConditions = " WHERE (user_id='{$_SESSION['UserId']}' OR '{$_SESSION['SurveyAdmin']}'='Y') ";

	if ($Title != "")
		$sConditions = " AND title LIKE '%$Title%' ";

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_surveys", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_surveys $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
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
				      <td width="27%">Title</td>
				      <td width="12%">From</td>
				      <td width="12%">To</td>
				      <td width="22%">Employees</td>
				      <td width="22%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId        = $objDb->getField($i, 'id');
		$sTitle     = $objDb->getField($i, 'title');
		$sPurpose   = $objDb->getField($i, 'purpose');
		$iEmployees = @explode(",", $objDb->getField($i, 'users'));
		$sStatus    = $objDb->getField($i, 'status');
		$sFromDate  = $objDb->getField($i, 'from_date');
		$sToDate    = $objDb->getField($i, 'to_date');
		$sEmployees = "";

		for ($j = 0; $j < count($iEmployees); $j ++)
			$sEmployees .= ("- ".$sEmployeesList[$iEmployees[$j]]."<br />");
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>" valign="top">
				      <td width="5%"><?= ($iStart + $i + 1) ?></td>
				      <td width="27%"><span id="Title<?= $iId ?>"><?= $sTitle ?></span></td>
				      <td width="12%"><span id="FromDate_<?= $iId ?>"><?= formatDate($sFromDate) ?></span></td>
				      <td width="12%"><span id="ToDate_<?= $iId ?>"><?= formatDate($sToDate) ?></span></td>
				      <td width="22%"><span id="Employees<?= $iId ?>"><?= $sEmployees ?></span></td>

				      <td width="22%" class="center">
<?
		if ($sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y")
		{
?>
				        <a href="hr/toggle-survey-status.php?Id=<?= $iId ?>&Status=<?= (($sStatus == 'A') ? 'I' : 'A') ?>"><img src="images/icons/<?= (($sStatus == 'A') ? 'yes' : 'no') ?>.png" width="16" height="16" alt="Toggle Status" title="Toggle Status" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Add'] == "Y")
		{
?>
				        <a href="hr/duplicate-survey.php?Id=<?= $iId ?>"><img src="images/icons/duplicate.gif" width="16" height="16" alt="Duplicate" title="Duplicate" /></a>
				        &nbsp;
				        <a href="hr/survey-manager.php?Id=<?= $iId ?>"><img src="images/icons/form.gif" width="16" height="16" alt="Survey Manager" title="Survey Manager" /></a>
				        &nbsp;
<?
		}
?>
				        <a href="hr/survey-feedback.php?Id=<?= $iId ?>"><img src="images/icons/deviation.gif" width="16" height="16" alt="Survey Feedback" title="Survey Feedback" /></a>
				        &nbsp;
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
				        <a href="hr/delete-survey.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Survey?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
				        &nbsp;
<?
		}
?>
				        <a href="hr/view-survey.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="Survey # <?= $iId ?> :: :: width: 700, height: 550"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a>
				      </td>
				    </tr>
				  </table>

				  <div id="Edit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" onsubmit="return false;" class="frmInlineEdit">
					  <input type="hidden" name="Id" value="<?= $iId ?>" />

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="70">Title<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="Title" value="<?= $sTitle ?>" size="33" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr valign="top">
						  <td>Purpose</td>
						  <td align="center">:</td>
						  <td><textarea name="Purpose" rows="4" cols="30"><?= $sPurpose ?></textarea></td>
					    </tr>

					    <tr>
						  <td>Exmployees<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
					        <select id="Employees<?= $iId ?>" name="Employees[]" multiple size="10" style="width:220px;">
<?
		foreach ($sDepartmentsList as $sKey => $sValue)
		{
?>
	                          <optgroup label="<?= $sValue ?>">
<?
			$sSQL = "SELECT id, name FROM tbl_users WHERE designation_id IN (SELECT id FROM tbl_designations WHERE department_id='$sKey') AND status='A' ORDER BY name";
			$objDb2->query($sSQL);

			$iCount2 = $objDb2->getCount( );

			for ($j = 0; $j < $iCount2; $j ++)
			{
				$iUserId = $objDb2->getField($j, 0);
				$sName   = $objDb2->getField($j, 1);
?>
					            <option value="<?= $iUserId ?>"<?= ((@in_array($iUserId, $iEmployees)) ? ' selected' : '') ?>><?= $sName ?></option>
<?
			}
?>
					          </optgroup>
<?
		}
?>
					        </select>
						  </td>
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
				      <td class="noRecord">No Survey Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Title={$Title}");
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