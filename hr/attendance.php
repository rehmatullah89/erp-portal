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

	if ($FromDate == "" && $ToDate == "")
	{
		$FromDate = date("Y-m-d");
		$ToDate   = date("Y-m-d");
	}


	$sEmployeesList = getList("tbl_users", "id", "CONCAT(name, '  (', card_id, ')')", "(email LIKE '%@apparelco.com' OR email LIKE '%@3-tree.com' OR email LIKE '%@lulusar.com') AND status='A'");

	if ($_SESSION['CountryId'] == 18)
		$sEmployeesList = getList("tbl_users", "id", "CONCAT(name, '  (', card_id, ')')", "(email LIKE '%@apparelco.com' OR email LIKE '%@3-tree.com' OR email LIKE '%@lulusar.com') AND status='A' AND country_id='18'");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Date       = IO::strValue("Date");
		$TimeInHr   = IO::strValue("TimeInHr");
		$TimeInMin  = IO::strValue("TimeInMin");
		$TimeOutHr  = IO::strValue("TimeOutHr");
		$TimeOutMin = IO::strValue("TimeOutMin");
		$Employee   = IO::intValue("Employee");
	}

	if ($TimeInHr == "")
		$TimeInHr = date("h");

	if ($TimeInMin == "")
		$TimeInMin = date("i");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/hr/attendance.js"></script>
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
			    <h1>Attendance</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="hr/save-attendance.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Attendance Record</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="70">Date<span class="mandatory">*</span></td>
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
					<td>Time-In<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
			          <select name="TimeInHr">
<?
		for ($i = 0; $i < 24; $i ++)
		{
			$sHr = str_pad($i, 2, '0', STR_PAD_LEFT);
?>
			            <option value="<?= $sHr ?>"<?= (($sHr == $TimeInHr) ? " selected" : "") ?>><?= $sHr ?></option>
<?
		}
?>
		              </select>

			          <select name="TimeInMin">
<?
		for ($i = 0; $i < 60; $i ++)
		{
			$sMin = str_pad($i, 2, '0', STR_PAD_LEFT);
?>
			            <option value="<?= $sMin ?>"<?= (($sMin == $TimeInMin) ? " selected" : "") ?>><?= $sMin ?></option>
<?
		}
?>
		              </select>
					</td>
				  </tr>

				  <tr>
					<td>Time-Out<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
			          <select name="TimeOutHr">
<?
		for ($i = 0; $i < 24; $i ++)
		{
			$sHr = str_pad($i, 2, '0', STR_PAD_LEFT);
?>
			            <option value="<?= $sHr ?>"<?= (($sHr == $TimeOutHr) ? " selected" : "") ?>><?= $sHr ?></option>
<?
		}
?>
		              </select>

			          <select name="TimeOutMin">
<?
		for ($i = 0; $i < 60; $i ++)
		{
			$sMin = str_pad($i, 2, '0', STR_PAD_LEFT);
?>
			            <option value="<?= $sMin ?>"<?= (($sMin == $TimeOutMin) ? " selected" : "") ?>><?= $sMin ?></option>
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
					<td>Remarks</td>
					<td align="center">:</td>
					<td><input type="text" name="Remarks" value="<?= $Remarks ?>" maxlength="100" size="30" class="textbox" /></td>
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

			          <td width="300">
			            <select name="Employee" style="width:90%;">
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

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_attendance", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT *,
	                (SELECT name FROM tbl_users WHERE id=tbl_attendance.user_id) AS _Name
	         FROM tbl_attendance
	         $sConditions
	         ORDER BY _Name, `date`, time_in
	         LIMIT $iStart, $iPageSize";
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
				      <td width="14%">Date</td>
				      <td width="26%">Employee</td>
				      <td width="9%">Time-In</td>
				      <td width="9%">Time-Out</td>
				      <td width="30%">Remarks</td>
				      <td width="7%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iUserId      = $objDb->getField($i, 'user_id');
		$sDate        = $objDb->getField($i, 'date');
		$iEntry       = $objDb->getField($i, 'entry');
		$sTimeIn      = $objDb->getField($i, 'time_in');
		$sTimeOut     = $objDb->getField($i, 'time_out');
		$sRemarks     = $objDb->getField($i, 'remarks');
		$sLocationIn  = $objDb->getField($i, 'location_in');
		$sLocationOut = $objDb->getField($i, 'location_out');

		@list($sTimeInHr, $sTimeInMin)   = @explode(":", $sTimeIn);
		@list($sTimeOutHr, $sTimeOutMin) = @explode(":", $sTimeOut);


		$sRemarksLocation = $sRemarks;

		if ($sLocationIn != "" && @strpos($sLocationIn, "Address not found") === FALSE)
			$sRemarksLocation .= ((($sRemarksLocation != "") ? "<hr />" : "")."<b>IN:</b> {$sLocationIn}");

		if ($sLocationOut != "" && @strpos($sLocationOut, "Address not found") === FALSE)
			$sRemarksLocation .= ((($sRemarksLocation != "") ? "<hr />" : "")."<b>OUT:</b> {$sLocationOut}");

		$sRemarksLocation = str_replace(array("\r\n", "\n"), ", ", $sRemarksLocation);
		$sRemarksLocation = str_replace(", , ", ",", $sRemarksLocation);
		$sRemarksLocation = str_replace(array(",,,,", ",,,", ",,"), "<br />", $sRemarksLocation);
		$sRemarksLocation = str_replace(",(", "<br />(", $sRemarksLocation);
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>" valign="top">
				      <td width="5%"><?= ($iStart + $i + 1) ?></td>
				      <td width="14%"><?= formatDate($sDate) ?><?= (($iEntry > 0) ? (" # ".($iEntry + 1)) : "") ?></td>
				      <td width="26%"><?= $sEmployeesList[$iUserId] ?></td>
				      <td width="9%"><span id="TimeIn<?= $i ?>"><?= formatTime($sTimeIn) ?></span></td>
				      <td width="9%"><span id="TimeOut<?= $i ?>"><?= formatTime($sTimeOut) ?></span></td>
				      <td width="30%"><span id="Remarks<?= $i ?>"><?= $sRemarksLocation ?></span></td>

				      <td width="7%" class="center">
<?
		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="./" onclick="Effect.SlideDown('Edit<?= $i ?>'); return false;"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="hr/delete-attendance.php?UserId=<?= $iUserId ?>&Date=<?= $sDate ?>&Entry=<?= $iEntry ?>" onclick="return confirm('Are you SURE, You want to Delete this Attendance Record?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
<?
		}
?>
				      </td>
				    </tr>
				  </table>

				  <div id="Edit<?= $i ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmData<?= $i ?>" id="frmData<?= $i ?>" onsubmit="return false;" class="frmInlineEdit">
					  <input type="hidden" name="Id" value="<?= $i ?>" />
					  <input type="hidden" name="Date" value="<?= $sDate ?>" />
					  <input type="hidden" name="UserId" value="<?= $iUserId ?>" />
					  <input type="hidden" name="Entry" value="<?= $iEntry ?>" />

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="70">Employee</td>
						  <td width="20" align="center">:</td>
						  <td><b><?= $sEmployeesList[$iUserId] ?></b></td>
					    </tr>

					    <tr>
						  <td>Date</td>
						  <td align="center">:</td>
						  <td><b><?= formatDate($sDate) ?></b></td>
					    </tr>

					    <tr>
						  <td>Time-In<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select name="TimeInHr">
<?
		for ($j = 0; $j < 24; $j ++)
		{
			$sHr = str_pad($j, 2, '0', STR_PAD_LEFT);
?>
			            	  <option value="<?= $sHr ?>"<?= (($sHr == $sTimeInHr) ? " selected" : "") ?>><?= $sHr ?></option>
<?
		}
?>
						    </select>

						    <select name="TimeInMin">
<?
		for ($j = 0; $j < 60; $j ++)
		{
			$sMin = str_pad($j, 2, '0', STR_PAD_LEFT);
?>
			            	  <option value="<?= $sMin ?>"<?= (($sMin == $sTimeInMin) ? " selected" : "") ?>><?= $sMin ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>Time-Out<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select name="TimeOutHr">
<?
		for ($j = 0; $j < 24; $j ++)
		{
			$sHr = str_pad($j, 2, '0', STR_PAD_LEFT);
?>
			            	  <option value="<?= $sHr ?>"<?= (($sHr == $sTimeOutHr) ? " selected" : "") ?>><?= $sHr ?></option>
<?
		}
?>
						    </select>

						    <select name="TimeOutMin">
<?
		for ($j = 0; $j < 60; $j ++)
		{
			$sMin = str_pad($j, 2, '0', STR_PAD_LEFT);
?>
			            	  <option value="<?= $sMin ?>"<?= (($sMin == $sTimeOutMin) ? " selected" : "") ?>><?= $sMin ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>Remarks</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Remarks" value="<?= $sRemarks ?>" size="30" maxlength="100" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td></td>
						  <td></td>

						  <td>
						    <input type="submit" value="SAVE" class="btnSmall" onclick="validateEditForm(<?= $i ?>);" />
						    <input type="button" value="CANCEL" class="btnSmall" onclick="Effect.SlideUp('Edit<?= $i ?>');" />
						  </td>
					    </tr>
					  </table>
					  </form>

				    </div>
				  </div>

				  <div id="Msg<?= $i ?>" class="msgOk" style="display:none;"></div>
<?
	}

	if ($iCount == 0)
	{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr>
				      <td class="noRecord">No Attendance Record Found!</td>
				    </tr>
				  </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Employee={$Employee}&FromDate={$FromDate}&ToDate={$ToDate}");
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