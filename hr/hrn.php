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

	$Employee   = IO::strValue("Employee");
	$Department = IO::intValue("Department");
	$Country    = IO::intValue("Country");

	$sDepartmentsList  = getList("tbl_departments", "id", "department");
	$sDesignationsList = getList("tbl_designations", "id", "designation");
	$sCountriesList    = getList("tbl_countries", "id", "country", "id IN (SELECT DISTINCT(country_id) FROM tbl_users WHERE status='A' AND (email LIKE '%@apparelco.com' OR email LIKE '%@3-tree.com' OR email LIKE '%@3-tree.com' OR email LIKE '%@matrixsourcings.com%'))");

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
			    <h1>Human Resource Navigator</h1>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="70">Employee</td>
			          <td width="130"><input type="text" name="Employee" value="<?= $Employee ?>" class="textbox" maxlength="50" size="15" /></td>
			          <td width="90">Departments</td>

			          <td width="300">
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
<?
	$sDepartmentsList[0] = "Employees with No Department";

	$sConditions = " WHERE status='A' AND (email LIKE '%@apparelco.com' OR email LIKE '%@3-tree.com' OR email LIKE '%@lulusar.com' OR email LIKE '%@matrixsourcings.com%') ";

	if ($Employee != "")
		$sConditions .= " AND (name LIKE '%$Employee%' OR username LIKE '%$Employee%') ";

	if ($Country > 0)
		$sConditions .= " AND country_id='$Country' ";

	if ($_SESSION['CountryId'] == 18)
		$sConditions .= " AND country_id='18' ";


	foreach ($sDepartmentsList as $iDepartment => $sDepartment)
	{
		if ($Department > 0 && $iDepartment != $Department)
			continue;


		if ($iDepartment == 0)
		{
			$sSQL = "SELECT id, name, country_id, designation_id, picture
					 FROM tbl_users
					 $sConditions AND designation_id='0'
					 ORDER BY name ASC";
		}

		else
		{
			$sSQL = "SELECT id, name, country_id, designation_id, picture
					 FROM tbl_users
					 $sConditions AND designation_id IN (SELECT id FROM tbl_designations WHERE department_id='$iDepartment')
					 ORDER BY name ASC";
		}

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount == 0)
			continue;
?>

			    <div class="tblSheet"<?= (($i < ($iCount - 1)) ? ' style="margin-bottom:10px;"' : '') ?>>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="100%"><?= $sDepartment ?></td>
				    </tr>
				  </table>

				  <div style="height:5px;"></div>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
		for ($i = 0; $i < $iCount;)
		{
?>
				    <tr valign="top">
<?
			for ($j = 0; $j < 5; $j ++)
			{
				if ($i < $iCount)
				{
					$iId          = $objDb->getField($i, 'id');
					$sName        = $objDb->getField($i, "name");
					$iDesignation = $objDb->getField($i, "designation_id");
					$iCountry     = $objDb->getField($i, "country_id");
					$sPicture     = $objDb->getField($i, "picture");

					if ($sPicture == "" || !@file_exists($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture))
						$sPicture = "default.jpg";
?>
				      <td width="20%">
					    <div id="ProfilePic">
						  <div id="Pic"><a href="hr/view-employee-stats.php?Id=<?= $iId ?>&Department=<?= $Department ?>&Country=<?= $Country ?>"><img src="<?= USERS_IMG_PATH.'thumbs/'.$sPicture ?>" alt="<?= $sName ?>" title="<?= $sName ?>" /></a></div>
					    </div>

				        <div style="padding:5px 0px 5px 0px;">
				          <b><?= $sName ?></b><br />
				          <i style="color:#777777;"><?= $sDesignationsList[$iDesignation] ?></i><br />
				          <?= $sCountriesList[$iCountry] ?><br />
				        </div>
				      </td>
<?
					$i ++;
				}

				else
				{
?>
				      <td width="20%">&nbsp;</td>
<?
				}
			}
?>
				    </tr>

<?
		}
?>
			      </table>
			    </div>
<?
	}
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