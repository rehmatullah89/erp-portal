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
	$Month    = IO::strValue("Month");
	$Year     = IO::intValue("Year");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Employee = IO::intValue("Employee");
		$Month    = IO::strValue("Month");
		$Year     = IO::intValue("Year");
		$Salary   = IO::strValue("Salary");
		$Comments = IO::strValue("Comments");
	}

	$sMonthsList    = array('January','February','March','April','May','June','July','August','September','October','November','December');
	$sEmployeesList = getList("tbl_users", "id", "name", "(email LIKE '%@apparelco.com' OR email LIKE '%@3-tree.com' OR email LIKE '%@lulusar.com') AND status='A'");

	if ($_SESSION['CountryId'] == 18)
		$sEmployeesList = getList("tbl_users", "id", "name", "(email LIKE '%@apparelco.com' OR email LIKE '%@3-tree.com' OR email LIKE '%@lulusar.com') AND status='A' AND country_id='18'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/hr/salaries.js"></script>
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
			    <h1>Salaries</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="hr/save-salary.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Salary Record</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="60">Employee<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

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
					<td>Month<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Month">
						<option value=""></option>
<?
		for ($i = 1; $i <= 12; $i ++)
		{
?>
			            <option value="<?= $i ?>"<?= (($i == $Month) ? " selected" : "") ?>><?= $sMonthsList[($i - 1)] ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Year<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
			          <select name="Year">
			            <option value=""></option>
<?
		for ($i = 2000; $i <= date("Y"); $i ++)
		{
?>
			            <option value="<?= $i ?>"<?= (($i == $Year) ? " selected" : "") ?>><?= $i ?></option>
<?
		}
?>
		              </select>
					</td>
				  </tr>

				  <tr>
					<td>Salary<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Salary" value="<?= $Salary ?>" maxlength="10" size="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Comments</td>
					<td align="center">:</td>
					<td><input type="text" name="Comments" value="<?= $Comments ?>" maxlength="255" size="30" class="textbox" /></td>
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

					  <td width="45">Month</td>

					  <td width="110">
					    <select name="Month">
						  <option value="">All Months</option>
<?
	for ($i = 1; $i <= 12; $i ++)
	{
?>
			              <option value="<?= $i ?>"<?= (($Month == $i) ? " selected" : "") ?>><?= $sMonthsList[($i - 1)] ?></option>
<?
	}
?>
					    </select>
					  </td>

					  <td width="35">Year</td>

					  <td width="70">
					    <select name="Year">
						  <option value="">All Years</option>
<?
	for ($i = 2000; $i <= date("Y"); $i ++)
	{
?>
			              <option value="<?= $i ?>"<?= (($Year == $i) ? " selected" : "") ?>><?= $i ?></option>
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

	if ($Employee > 0)
		$sConditions .= " AND user_id='$Employee' ";

	if ($_SESSION['CountryId'] == 18)
		$sConditions .= " AND user_id IN (SELECT id FROM tbl_users WHERE country_id='18' AND status='A') ";

	if ($Month > 0)
		$sConditions .= " AND month='$Month' ";

	if ($Year > 0)
		$sConditions .= " AND year='$Year' ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_user_salaries", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_user_salaries $sConditions ORDER BY year DESC, month DESC LIMIT $iStart, $iPageSize";
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
				      <td width="26%">Employee</td>
				      <td width="10%">Year</td>
				      <td width="10%">Month</td>
				      <td width="10%">Salary</td>
				      <td width="30%">Comments</td>
				      <td width="9%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId       = $objDb->getField($i, 'id');
		$iUserId   = $objDb->getField($i, 'user_id');
		$iMonth    = $objDb->getField($i, 'month');
		$iYear     = $objDb->getField($i, 'year');
		$fSalary   = $objDb->getField($i, 'salary');
		$sComments = $objDb->getField($i, 'comments');
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="5%"><?= ($iStart + $i + 1) ?></td>
				      <td width="26%"><?= $sEmployeesList[$iUserId] ?></td>
				      <td width="10%"><span id="Year<?= $iId ?>"><?= $iYear ?></span></td>
				      <td width="10%"><span id="Month<?= $iId ?>"><?= $sMonthsList[intval($iMonth) - 1] ?></span></td>
				      <td width="10%"><span id="Salary<?= $iId ?>"><?= formatNumber($fSalary, false) ?></span></td>
				      <td width="30%"><span id="Comments<?= $iId ?>"><?= $sComments ?></span></td>

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
				        <a href="hr/delete-salary.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Salary Record?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
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
						  <td width="60">Employee</td>
						  <td width="20" align="center">:</td>
						  <td><b><?= $sEmployeesList[$iUserId] ?></b></td>
					    </tr>

					    <tr>
						  <td>Month<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select name="Month">
							  <option value=""></option>
<?
		for ($j = 1; $j <= 12; $j ++)
		{
?>
			            	  <option value="<?= $j ?>"<?= (($j == $iMonth) ? " selected" : "") ?>><?= $sMonthsList[($j - 1)] ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>Year<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						   <select name="Year">
						 	  <option value=""></option>
<?
		for ($j = 2000; $j <= date("Y"); $j ++)
		{
?>
			            	  <option value="<?= $j ?>"<?= (($j == $iYear) ? " selected" : "") ?>><?= $j ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>Salary<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="Salary" value="<?= $fSalary ?>" maxlength="10" size="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Comments</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Comments" value="<?= $sComments ?>" size="30" maxlength="255" class="textbox" /></td>
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
				      <td class="noRecord">No Salary Record Found!</td>
				    </tr>
				  </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Employee={$Employee}&Month={$Month}&Year={$Year}");
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