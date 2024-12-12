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

	$PageId   	= ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$PostId   	= IO::strValue("PostId");

	$AirwayBill = IO::strValue("AirwayBill");
	$Company 	= IO::strValue("Company");
	$FromDate 	= IO::strValue("FromDate");
	$ToDate 	= IO::strValue("ToDate");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$AirwayBill = IO::strValue("AirwayBill");
		$Company 	= IO::strValue("Company");
		$Type 		= IO::strValue("Type");
		$Employee 	= IO::intValue("Employee");
		$Country 	= IO::intValue("Country");
		$Address 	= IO::strValue("Address");
		$Date 	    = IO::strValue("Date");
	}

	$sEmployeesList = getList("tbl_users", "id", "name", "status='A'");
	$sCountriesList = getList("tbl_countries", "id", "country");
	$sCompaniesList = array("TCS", "DHL", "OCS", "FEDEX");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/couriers.js"></script>
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
			    <h1>Couriers</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="data/save-courier.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Courier Item</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="100">Airway Bill #<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="AirwayBill" value="<?= $AirwayBill ?>" maxlength="50" size="25" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Company<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Company">
					    <option value=""></option>
<?
		foreach ($sCompaniesList as $sCompany)
		{
?>
			            <option value="<?= $sCompany ?>"<?= (($sCompany == $Company) ? " selected" : "") ?>><?= $sCompany ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Courier Type<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Type">
					    <option value=""></option>
			            <option value="Sent"<?= (($Type == "Sent") ? " selected" : "") ?>>Sent</option>
			            <option value="Received"<?= (($Type == "Received") ? " selected" : "") ?>>Received</option>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Employee</td>
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
					<td>Country<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Country">
					    <option value=""></option>
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
				  </tr>

				  <tr valign="top">
					<td>Address</td>
					<td align="center">:</td>
					<td><textarea type="text" name="Address" rows="5" cols="50"><?= $Address ?></textarea></td>
				  </tr>

				  <tr>
					<td width="60">Date<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td>
					  <table border="0" cellpadding="0" cellspacing="0" width="116">
						<tr>
						  <td width="82"><input type="text" name="Date" id="Date" value="<?= $Date ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('Date'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('Date'), 'yyyy-mm-dd', this);" /></td>
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
			          <td width="50">AWB #</td>
			          <td width="140"><input type="text" name="AirwayBill" value="<?= $AirwayBill ?>" size="14" class="textbox" maxlength="25" /></td>
			          <td width="70">Company</td>

			          <td width="130">
			            <select name="Company" id="Company">
			              <option value="">All Companies</option>
<?
	foreach ($sCompaniesList as $sValue)
	{
?>
			              <option value="<?= $sValue ?>"<?= (($sValue == $Company) ? " selected" : "") ?>><?= $sValue ?></option>
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
					  <td>[ <a href="#" onclick="$('FromDate').value=''; $('ToDate').value=''; return false;">Clear</a> ]</td>

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
	$sConditions = "";

	if ($AirwayBill != "")
		$sConditions .= " AND awb_no LIKE '%$AirwayBill%' ";

	if ($Company != "")
		$sConditions .= " AND company='$Company' ";

	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND (date BETWEEN '$FromDate' AND '$ToDate') ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_couriers", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_couriers $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow">
				      <td width="7%">#</td>
				      <td width="16%">AWB Number</td>
				      <td width="12%">Company</td>
				      <td width="8%">Type</td>
				      <td width="14%">Country</td>
					  <td width="23%">Sender/Receiver</td>
					  <td width="10%" class="center">Date</td>
				      <td width="10%" class="center">Options</td>
				    </tr>
<?
		}

		$iId        = $objDb->getField($i, 'id');
		$sAwbNumber = $objDb->getField($i, 'awb_no');
		$sType      = $objDb->getField($i, 'type');
		$iCountry   = $objDb->getField($i, 'country_id');
		$sCompany   = $objDb->getField($i, 'company');
		$iEmployee  = $objDb->getField($i, 'user_id');
		$sDate      = $objDb->getField($i, 'date');
?>

				    <tr class="<?= $sClass[($i % 2)] ?>" valign="top">
				      <td valign="top"><?= ($iStart + $i + 1) ?></td>
				      <td><?= $sAwbNumber ?></td>
				      <td><?= $sCompany ?></td>
				      <td><?= $sType ?></td>
				      <td><?= $sCountriesList[$iCountry] ?></td>
				      <td><?= ((@in_array($iEmployee, $sEmployeesList)) ? $sEmployeesList[$iEmployee] : getDbValue("name", "tbl_users", "id='$iEmployee'")) ?></td>
				      <td class="center"><?= formatDate($sDate) ?></td>

				      <td class="center">
<?

		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="data/edit-courier.php?Id=<?= $iId ?>" ><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="data/delete-courier.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Courier Item?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
<?
		}
?>
				      </td>
				    </tr>

<?
	}

	if ($iCount == 0)
	{
?>
				    <tr>
				      <td class="noRecord">No Couriers Record Found!</td>
				    </tr>
<?
	}
?>
				  </table>
			    </div>

<?
	if ($iCount > 0)
	{
?>
				<div class="buttonsBar" style="margin:4px 1px 2px 1px;">
				  <input type="button" value="" id="BtnExport" class="btnExport" title="Export" onclick="document.location='<?=SITE_URL?>data/export-couriers.php?AirwayBill=<?=$AirwayBill?>&Company=<?=$Company?>&FromDate=<?=$FromDate?>&ToDate=<?=$ToDate?>';" />
				</div>
<?
	}

	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&AirwayBill={$AirwayBill}");
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