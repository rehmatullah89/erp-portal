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

	$PageId      = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Department  = IO::strValue("Department");
	$Designation = IO::strValue("Designation");
	$PostId      = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Department     = IO::strValue("Department");
		$Designation    = IO::strValue("Designation");
		$ReportingTo    = IO::strValue("ReportingTo");
		$JobDescription = IO::strValue("JobDescription");
	}


	$sDepartmentsList  = getList("tbl_departments", "id", "department", "", "position");
	$sDesignationsList = getList("tbl_designations", "id", "designation");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/jquery.js"></script>
  <script type="text/javascript" src="scripts/jquery.autocomplete.js"></script>
  <script type="text/javascript" src="scripts/hr/designations.js"></script>
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
			    <h1>Designations</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="hr/save-designation.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Designation</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="90">Department<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td>
			          <select name="Department" id="Department">
			            <option value=""></option>
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
				  </tr>

				  <tr>
					<td>Designation<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Designation" value="<?= $Designation ?>" maxlength="100" size="30" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Reporting To</td>
					<td align="center">:</td>

					<td>
					  <select name="ReportingTo" id="ReportingTo">
					    <option value=""></option>
<?
		foreach ($sDepartmentsList as $sKey => $sValue)
		{
?>
					    <optgroup label="<?= $sValue ?>">
<?
			$sSQL = "SELECT id, designation FROM tbl_designations WHERE department_id='$sKey' ORDER BY designation";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iDesignation = $objDb->getField($i, 'id');
				$sDesignation = $objDb->getField($i, 'designation');
?>
						  <option value="<?= $iDesignation ?>"<?= (($iDesignation == $ReportingTo) ? " selected" : "") ?>><?= $sDesignation ?></option>
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

				  <tr valign="top">
					<td>Job Description</td>
					<td align="center">:</td>
					<td><textarea name="JobDescription" rows="10" style="width:98%;"><?= $JobDescription ?></textarea></td>
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
			          <td width="90">Department</td>

			          <td width="280">
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

			          <td width="85">Designation</td>
			          <td width="150"><input type="text" name="Designation" value="<?= $Designation ?>" class="textbox" maxlength="50" size="20" /></td>
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

	if ($Designation != "")
		$sConditions = " AND designation LIKE '%$Designation%' ";

	if ($Department > 0)
		$sConditions .= " AND department_id='$Department' ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_designations", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_designations $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
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
				      <td width="27%">Designation</td>
				      <td width="28%">Reporting To</td>
				      <td width="30%">Department</td>
				      <td width="10%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId             = $objDb->getField($i, 'id');
		$sDesignation    = $objDb->getField($i, 'designation');
		$iDepartment     = $objDb->getField($i, 'department_id');
		$iReportingTo    = $objDb->getField($i, 'reporting_to');
		$sJobDescription = $objDb->getField($i, 'job_description');
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>" valign="top">
				      <td width="5%"><?= ($iStart + $i + 1) ?></td>
				      <td width="27%"><span id="Designation<?= $iId ?>"><?= $sDesignation ?></span></td>
				      <td width="28%"><span id="ReportingTo<?= $iId ?>"><?= $sDesignationsList[$iReportingTo] ?></span></td>
				      <td width="30%"><span id="Department<?= $iId ?>"><?= $sDepartmentsList[$iDepartment] ?></span></td>

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
				        <a href="hr/delete-designation.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Designation?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
				        &nbsp;
<?
		}
?>
				        <a href="hr/view-designation.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="Designation: <?= $sDesignation ?> :: :: width:600, height:400"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a>
				      </td>
				    </tr>
				  </table>

				  <div id="Edit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px; position:relative;">

					  <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" onsubmit="return false;" class="frmInlineEdit">
					  <input type="hidden" name="Id" value="<?= $iId ?>" />

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="90">Department<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>

						  <td>
						    <select name="Department">
							  <option value=""></option>
<?
		foreach ($sDepartmentsList as $sKey => $sValue)
		{
?>
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $iDepartment) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>Designation<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="Designation" value="<?= $sDesignation ?>" maxlength="100" size="30" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Reporting To</td>
						  <td align="center">:</td>

						  <td>
						    <select name="ReportingTo" id="ReportingTo">
							  <option value=""></option>
<?
		foreach ($sDepartmentsList as $sKey => $sValue)
		{
?>
					    	  <optgroup label="<?= $sValue ?>">
<?
			$sSQL = "SELECT id, designation FROM tbl_designations WHERE department_id='$sKey' ORDER BY designation";
			$objDb2->query($sSQL);

			$iCount2 = $objDb2->getCount( );

			for ($j = 0; $j < $iCount2; $j ++)
			{
				$iDesignation = $objDb2->getField($j, 'id');
				$sDesignation = $objDb2->getField($j, 'designation');
?>
						  	    <option value="<?= $iDesignation ?>"<?= (($iDesignation == $iReportingTo) ? " selected" : "") ?>><?= $sDesignation ?></option>
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

					    <tr valign="top">
						  <td>Job Description</td>
						  <td align="center">:</td>
						  <td><textarea name="JobDescription" rows="10" style="width:98%;"><?= $sJobDescription ?></textarea></td>
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
				      <td class="noRecord">No Designation Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Designation={$Designation}");
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