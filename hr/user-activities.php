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

	$PostId   = IO::strValue("PostId");
	$PageId   = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Employee = IO::intValue("Employee");
	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");
	$Activity = IO::intValue("Activity");

	if ($FromDate == "" && $ToDate == "")
	{
		$FromDate = date("Y-m-d");
		$ToDate   = date("Y-m-d");
	}

	$sActivitiesList = getList("tbl_activities", "id", "name");
	$sEmployeesList  = getList("tbl_users", "id", "name", "(email LIKE '%@apparelco.com' OR email LIKE '%@3-tree.com' OR email LIKE '%@lulusar.com') AND status='A'");

	if ($_SESSION['CountryId'] == 18)
		$sEmployeesList = getList("tbl_users", "id", "name", "(email LIKE '%@apparelco.com' OR email LIKE '%@3-tree.com' OR email LIKE '%@lulusar.com') AND status='A' AND country_id='18'");
	
	
	
	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Date     = IO::strValue("Date");
		$TimeHr   = IO::strValue("TimeHr");
		$TimeMin  = IO::strValue("TimeMin");
		$Employee = IO::intValue("Employee");
		$Activity = IO::strValue("Activity");
		$Details  = IO::strValue("Details");
	}	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/hr/user-activities.js"></script>
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
			    <h1>User Activities</h1>
				
				
<?
	if ($sUserRights['Add'] == "Y" && @in_array($_SESSION["UserId"], array(1,2,3,552,343,721)))
	{
?>
			    <form name="frmData" id="frmData" method="post" action="hr/save-user-activity.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Activity Record</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="80">Date<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td>

					  <table border="0" cellpadding="0" cellspacing="0" width="116">
						<tr>
						  <td width="82"><input type="text" name="Date" id="Date" value="<?= (($Date == "") ? date("Y-m-d") : $Date) ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('Date'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('Date'), 'yyyy-mm-dd', this);" /></td>
						</tr>
					  </table>

					</td>
			      </tr>

				  <tr>
					<td>Time<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
			          <select name="TimeHr">
<?
		for ($i = 0; $i < 24; $i ++)
		{
			$sHr = str_pad($i, 2, '0', STR_PAD_LEFT);
?>
			            <option value="<?= $sHr ?>"<?= (($sHr == $TimeHr) ? " selected" : "") ?>><?= $sHr ?></option>
<?
		}
?>
		              </select>

			          <select name="TimeMin">
<?
		for ($i = 0; $i < 60; $i ++)
		{
			$sMin = str_pad($i, 2, '0', STR_PAD_LEFT);
?>
			            <option value="<?= $sMin ?>"<?= (($sMin == $TimeMin) ? " selected" : "") ?>><?= $sMin ?></option>
<?
		}
?>
		              </select>
					</td>
				  </tr>

				  <tr>
					<td>Employee<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
			          <select name="Employee">
			            <option value=""></option>
<?
		foreach ($sEmployeesList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Employee) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
		              </select>
					</td>
				  </tr>

				  <tr>
				    <td>Activity<span class="mandatory">*</span></td>
				    <td align="center">:</td>

				    <td>
					  <select name="Activity">
					    <option value=""></option>
<?
		foreach ($sActivitiesList as $sKey => $sValue)
		{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == IO::intValue("Activity")) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
				    </td>
				  </tr>
		   
				  <tr valign="top">
				    <td>Details</td>
				    <td align="center">:</td>
				    <td><textarea name="Details" rows="4" cols="60"><?= $Details ?></textarea></td>
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
			          <td width="70">Employee</td>

			          <td width="240">
			            <select name="Employee">
			              <option value="">All Employees</option>
<?
	foreach ($sEmployeesList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Employee) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
			            </select>
			          </td>

			          <td width="60">Activity</td>

			          <td width="150">
			            <select name="Activity">
			              <option value="">All Activities</option>
<?
	foreach ($sActivitiesList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Activity) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
			            </select>
			          </td>

					  <td width="40">From</td>
					  <td width="78"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30" align="center">To</td>
					  <td width="78"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
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
	$sConditions = " WHERE (`date` BETWEEN '$FromDate' AND '$ToDate') ";

	if ($Employee > 0)
		$sConditions .= " AND user_id='$Employee' ";

	if ($_SESSION['CountryId'] == 18)
		$sConditions .= " AND user_id IN (SELECT id FROM tbl_users WHERE country_id='18') ";

	if ($Activity > 0)
		$sConditions .= " AND activity_id='Activity' ";

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_user_activities", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_user_activities $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
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
				      <td width="10%">Date</td>
				      <td width="24%">Employee</td>
				      <td width="16%">Activity</td>
				      <td width="8%">Time</td>
				      <td width="28%">Detail</td>
				      <td width="9%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId        = $objDb->getField($i, 'id');
		$iUserId    = $objDb->getField($i, 'user_id');
		$sDate      = $objDb->getField($i, 'date');
		$sTime      = $objDb->getField($i, 'time');
		$iActivity  = $objDb->getField($i, 'activity_id');
		$sDetails   = $objDb->getField($i, 'details');

		@list($sTimeHr, $sTimeMin)   = @explode(":", $sTime);
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>" valign="top">
				      <td width="5%"><?= ($iStart + $i + 1) ?></td>
				      <td width="10%"><?= formatDate($sDate) ?></td>
				      <td width="24%"><?= $sEmployeesList[$iUserId] ?></td>
				      <td width="16%"><span id="Activity<?= $iId ?>"><?= $sActivitiesList[$iActivity] ?></span></td>
				      <td width="8%"><span id="Time<?= $iId ?>"><?= formatTime($sTime) ?></span></td>
				      <td width="28%"><span id="Details<?= $iId ?>"><?= nl2br($sDetails) ?></span></td>

				      <td width="9%" class="center">
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
				        <a href="hr/delete-user-activity.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this User Activity?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
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
						  <td width="80">Employee</td>
						  <td width="20" align="center">:</td>
						  <td><b><?= $sEmployeesList[$iUserId] ?></b></td>
					    </tr>

					    <tr>
						  <td>Date</td>
						  <td align="center">:</td>
						  <td><b><?= formatDate($sDate) ?></b></td>
					    </tr>

					    <tr>
						  <td>Time<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select name="TimeHr">
<?
		for ($j = 0; $j < 24; $j ++)
		{
			$sHr = str_pad($j, 2, '0', STR_PAD_LEFT);
?>
			            	  <option value="<?= $sHr ?>"<?= (($sHr == $sTimeHr) ? " selected" : "") ?>><?= $sHr ?></option>
<?
		}
?>
						    </select>

						    <select name="TimeMin">
<?
		for ($j = 0; $j < 60; $j ++)
		{
			$sMin = str_pad($j, 2, '0', STR_PAD_LEFT);
?>
			            	  <option value="<?= $sMin ?>"<?= (($sMin == $sTimeMin) ? " selected" : "") ?>><?= $sMin ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>Activity<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select name="Activity">
<?
		foreach ($sActivitiesList as $sKey => $sValue)
		{
?>
			              	    <option value="<?= $sKey ?>"<?= (($sKey == $iActivity) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>
						
					    <tr valign="top">
						  <td>Details</td>
						  <td align="center">:</td>
						  <td><textarea name="Details" rows="4" cols="60"><?= $sDetails ?></textarea></td>
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
				      <td class="noRecord">No User Activity Record Found!</td>
				    </tr>
				  </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Employee={$Employee}&FromDate={$FromDate}&ToDate={$ToDate}&Activity={$Activity}");
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