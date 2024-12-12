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

	$PageId   = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Vendor   = IO::intValue("Vendor");
	$Auditor  = IO::intValue("Auditor");
	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");
	$PostId   = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$AuditDate = IO::strValue("AuditDate");
		$Vendor    = IO::strValue("Vendor");
		$Auditors  = IO::getArray("Auditors");
		$Cutting   = IO::intValue("Cutting");
		$Sewing    = IO::intValue("Sewing");
		$Packing   = IO::intValue("Packing");
		$Finishing = IO::intValue("Finishing");
	}

	$sVendorsList  = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sAuditorsList = getList("tbl_users", "id", "name", "designation_id IN (SELECT id FROM tbl_designations WHERE department_id IN (5,15,41))");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/crc/quality-audits.js"></script>
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
			    <h1>Quality Audits</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="crc/save-quality-audit.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Quality Audit</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
				    <td width="70">Audit Date<span class="mandatory">*</span></td>
				    <td width="20" align="center">:</td>

				    <td>

 					  <table border="0" cellpadding="0" cellspacing="0" width="116">
					    <tr>
						  <td width="82"><input type="text" name="AuditDate" id="AuditDate" value="<?= $AuditDate ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('AuditDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('AuditDate'), 'yyyy-mm-dd', this);" /></td>
					    </tr>
					  </table>

				    </td>
				  </tr>

				  <tr>
					<td>Vendor<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Vendor">
						<option value=""></option>
<?
		foreach ($sVendorsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Vendor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr valign="top">
					<td>Auditor(s)<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Auditors[]" id="Auditors" multiple size="10" style="min-width:204px;">
<?
		foreach ($sAuditorsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Auditors)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Cutting</td>
					<td align="center">:</td>
					<td><input type="text" name="Cutting" value="<?= $Cutting ?>" size="12" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Sewing</td>
					<td align="center">:</td>
					<td><input type="text" name="Sewing" value="<?= $Sewing ?>" size="12" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Packing</td>
					<td align="center">:</td>
					<td><input type="text" name="Packing" value="<?= $Packing ?>" size="12" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Finishing</td>
					<td align="center">:</td>
					<td><input type="text" name="Finishing" value="<?= $Finishing ?>" size="12" maxlength="10" class="textbox" /></td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar"><input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" /></div>
			    </form>

			    <hr />
<?
	}
?>

			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="52">Vendor</td>

			          <td width="180">
			            <select name="Vendor" style="width:180px;">
			              <option value="">All Vendors</option>
<?
	foreach ($sVendorsList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Vendor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
			            </select>
			          </td>

			          <td width="55">Auditor</td>

			          <td width="180">
			            <select name="Auditor" id="Auditor">
			              <option value="">All Auditors</option>
<?
	foreach ($sAuditorsList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Auditor) ? " selected" : "") ?>><?= $sValue ?></option>
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
					  <td width="30"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="60">[ <a href="#" onclick="$('FromDate').value=''; $('ToDate').value=''; return false;">Clear</a> ]</td>
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>
			    </form>

			    <div class="tblSheet">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";


	if ($Vendor > 0)
		$sSQL .= " WHERE vendor_id='$Vendor' ";

	else
		$sSQL .= " WHERE vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Auditor > 0)
		$sSQL .= " AND FIND_IN_SET('Auditor', auditors) ";

	if ($FromDate != "" && $ToDate != "")
		$sSQL .= " AND (audit_date BETWEEN '$FromDate' AND '$ToDate') ";

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_quality_audits", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT id, vendor_id, audit_date, auditors, grade, rating FROM tbl_quality_audits $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow">
				      <td width="8%">#</td>
				      <td width="22%">Vendor</td>
				      <td width="15%" class="center">Audit Date</td>
				      <td width="10%" class="center">Grade</td>
				      <td width="10%" class="center">Rating</td>
				      <td width="20%">Auditors</td>
				      <td width="15%" class="center">Options</td>
				    </tr>
<?
		}

		$iId        = $objDb->getField($i, 'id');
		$iVendor    = $objDb->getField($i, 'vendor_id');
		$sAuditDate = $objDb->getField($i, 'audit_date');
		$sGrade     = $objDb->getField($i, 'grade');
		$fRating    = $objDb->getField($i, 'rating');
		$sAuditors  = $objDb->getField($i, 'auditors');

		$iAuditors = @explode(",", $sAuditors);
		$sAuditors = "";

		foreach ($iAuditors as $iAuditor)
			$sAuditors .= ($sAuditorsList[$iAuditor]."<br />");
?>

				    <tr class="<?= $sClass[($i % 2)] ?>" valign="top">
				      <td><?= ($iStart + $i + 1) ?></td>
				      <td><?= $sVendorsList[$iVendor] ?></td>
				      <td class="center"><?= formatDate($sAuditDate) ?></td>
				      <td class="center"><?= $sGrade ?></td>
				      <td class="center"><?= formatNumber($fRating) ?></td>
				      <td><?= $sAuditors ?></td>

				      <td class="center">
<?
		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="crc/edit-quality-audit.php?Id=<?= $iId ?>"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="crc/delete-quality-audit.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Audit?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
				        &nbsp;
<?
		}
?>
				        <a href="crc/view-quality-audit.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="Quality Audit :: :: width: 900, height: 650"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a>
				      </td>
				    </tr>
<?
	}

	if ($iCount == 0)
	{
?>
				    <tr>
				      <td class="noRecord">No Audit Record Found!</td>
				    </tr>
<?
	}
?>
			      </table>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Vendor={$Vendor}&Auditor={$Auditor}&FromDate={$FromDate}&ToDate={$ToDate}");
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