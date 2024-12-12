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

	$PageId     = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Employee   = IO::strValue("Employee");
	$Department = IO::intValue("Department");
	$Country    = IO::intValue("Country");

	$sDepartmentsList  = getList("tbl_departments", "id", "department");
	$sDesignationsList = getList("tbl_designations", "id", "designation");
	$sCountriesList    = getList("tbl_countries", "id", "country", "id IN (SELECT DISTINCT(country_id) FROM tbl_users)");

	if ($_SESSION['CountryId'] == 18)
		$sCountriesList = getList("tbl_countries", "id", "country", "id='18'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
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
			    <h1>Employees</h1>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="70">Employee</td>
			          <td width="130"><input type="text" name="Employee" value="<?= $Employee ?>" class="textbox" maxlength="50" size="15" /></td>
			          <td width="90">Departments</td>

			          <td width="260">
			            <select name="Department">
			              <option value="">All Departments</option>
<?
	foreach ($sDepartmentsList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Department) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
			            </select>
			          </td>

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
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = " WHERE (email LIKE '%@apparelco.com' OR email LIKE '%@3-tree.com' OR email LIKE '%@lulusar.com' OR email LIKE '%@gaia.com.pk') AND status='A' ";

	if ($Employee != "")
		$sConditions .= " AND (name LIKE '%$Employee%' OR username LIKE '%$Employee%' OR email LIKE '%$Employee%') ";

	if ($_SESSION['CountryId'] == 18)
		$sConditions .= " AND country_id='18' ";

	if ($Department > 0)
		$sConditions .= " AND designation_id IN (SELECT id FROM tbl_designations WHERE department_id='$Department') ";

	if ($Country > 0)
		$sConditions .= " AND country_id='$Country' ";

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_users", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT id, name, email, country_id, designation_id, joining_date, status FROM tbl_users $sConditions ORDER BY name ASC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow">
				      <td width="5%">#</td>
				      <td width="18%">Name</td>
				      <td width="25%">Email</td>
				      <td width="16%">Designation</td>
				      <td width="12%">Country</td>
				      <td width="10%">Joining Date</td>
				      <td width="14%" class="center">Options</td>
				    </tr>
<?
		}

		$iId = $objDb->getField($i, 'id');

		switch ($objDb->getField($i, "status"))
		{
			case "A" : $sStatus = "Active"; break;
			case "D" : $sStatus = "Disabled"; break;
			case "P" : $sStatus = "Pending"; break;
			case "L" : $sStatus = "Left"; break;
		}
?>

				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td><?= ($iStart + $i + 1) ?></td>
				      <td><?= $objDb->getField($i, 'name') ?></td>
				      <td><?= $objDb->getField($i, 'email') ?></td>
				      <td><?= $sDesignationsList[$objDb->getField($i, 'designation_id')] ?></td>
				      <td><?= $sCountriesList[$objDb->getField($i, 'country_id')] ?></td>
				      <td><?= formatDate($objDb->getField($i, 'joining_date')) ?></td>

				      <td class="center">
<?
		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="hr/edit-employee.php?Id=<?= $iId ?>"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit Profile" title="Edit Profile" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y")
		{
?>
				        <a href="hr/edit-employee-evolutionary-profile.php?Id=<?= $iId ?>"><img src="images/icons/more.gif" width="16" height="16" alt="Edit Evolutionary Profile" title="Edit Evolutionary Profile" /></a>
				        &nbsp;
<?
		}
?>
				        <a href="hr/view-employee-evolutionary-profile.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="Employee Evolutionary Profile :: :: width: 700, height: 550"><img src="images/icons/view2.gif" width="16" height="16" alt="Evolutionary Profile" title="Evolutionary Profile" /></a>
				        &nbsp;
				        <a href="hr/view-employee-profile.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="Employee Profile :: :: width: 700, height: 550"><img src="images/icons/view.gif" width="16" height="16" alt="Employee Profile" title="Employee Profile" /></a>
				      </td>
				    </tr>
<?
	}

	if ($iCount == 0)
	{
?>
				    <tr>
				      <td class="noRecord">No Employee Record Found!</td>
				    </tr>
<?
	}
?>
			      </table>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Employee={$Employee}&Department={$Department}&Country={$Country}");
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