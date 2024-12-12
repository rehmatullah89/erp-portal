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
	$OrderNo  = IO::strValue("OrderNo");
	$Vendor   = IO::intValue("Vendor");
	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");
	$PostId   = IO::strValue("PostId");


	if ($PageId == 1 && $OrderNo == "" && $Vendor == 0 && ($FromDate == "" || $ToDate == ""))
	{
		$FromDate = date("Y-m-d");
		$ToDate   = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") + 2), date("Y")));
	}

	$sAuditorsList = getList("tbl_users", "id", "name", "auditor='Y' AND status='A'");
	$sUsersList    = getList("tbl_users", "id", "name");
	$sVendorsList  = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sGroupsList   = getList("tbl_auditor_groups", "id", "name");

	$sReportTypes  = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sReportsList  = getList("tbl_reports", "id", "report", "FIND_IN_SET(id, '$sReportTypes') AND NOT FIND_IN_SET(id, '$sQmipReports')");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/quonda/audit-schedules.js"></script>
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
			    <h1>audit schedules</h1>


			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="70">Order No</td>
			          <td width="135"><input type="text" name="OrderNo" value="<?= $OrderNo ?>" class="textbox" maxlength="50" size="15" /></td>
			          <td width="55">Vendor</td>

			          <td width="180">
					    <select name="Vendor">
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
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "WHERE po.id=sch.po_id AND FIND_IN_SET(po.vendor_id, '{$_SESSION['Vendors']}') AND FIND_IN_SET(po.brand_id, '{$_SESSION['Brands']}') AND sch.status='P'
	                AND FIND_IN_SET(sch.report_id, '$sReportTypes') AND NOT FIND_IN_SET(sch.report_id, '$sQmipReports') ";

	if ($OrderNo != "")
		$sConditions .= " AND sch.order_no LIKE '%$OrderNo%' ";

	if ($Vendor > 0)
		$sConditions .= " AND sch.vendor_id='$Vendor' ";

	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND (sch.audit_date BETWEEN '$FromDate' AND '$ToDate') ";

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_audit_schedules sch, tbl_po po", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT sch.*, CONCAT(po.order_no, ' ', po.status) AS _OrderNo,
	                (SELECT line FROM tbl_lines WHERE id=sch.line_id) AS _Line
	         FROM tbl_audit_schedules sch, tbl_po po
	         $sConditions
	         ORDER BY sch.id DESC
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
				      <td width="6%">#</td>
				      <td width="19%">PO / Style</td>
				      <td width="18%">Auditor</td>
				      <td width="18%">Vendor</td>
				      <td width="10%">Line</td>
				      <td width="10%">Audit Date</td>
				      <td width="9%">Start Time</td>
				      <td width="10%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}


		$iId         = $objDb->getField($i, 'id');
		$iAuditor    = $objDb->getField($i, 'user_id');
		$iGroup      = $objDb->getField($i, 'group_id');
		$iVendor     = $objDb->getField($i, 'vendor_id');
		$iReport     = $objDb->getField($i, 'report_id');
		$iLine       = $objDb->getField($i, 'line_id');
		$sLine       = $objDb->getField($i, '_Line');
		$sAuditDate  = $objDb->getField($i, 'audit_date');
		$sStartTime  = $objDb->getField($i, 'start_time');
		$sEndTime    = $objDb->getField($i, 'end_time');
		$iStyleId    = $objDb->getField($i, 'style_id');
		$sColors     = $objDb->getField($i, 'colors');
		$iSampleSize = $objDb->getField($i, 'sample_size');
		$sOrderNo    = $objDb->getField($i, '_OrderNo');


		@list($iStartHour, $iStartMinutes) = @explode(":", $sStartTime);
		@list($iEndHour, $iEndMinutes)     = @explode(":", $sEndTime);

		if ($iStartHour >= 12)
		{
			if ($iStartHour > 12)
				$iStartHour -= 12;

			$sStartAmPm  = "PM";
		}

		else
			$sStartAmPm = "AM";


		if ($iEndHour >= 12)
		{
			if ($iEndHour > 12)
				$iEndHour -= 12;

			$sEndAmPm  = "PM";
		}

		else
			$sEndAmPm = "AM";


		$sStyleNo = "";

		if ($iStyleId > 0)
			$sStyleNo = ("/ ".getDbValue("style", "tbl_styles", "id='$iStyleId'"));


		$sComplete = "Y";

		if ($sStartTime == "00:00:00" || $sEndTime == "00:00:00" || $iLine == 0)
			$sComplete = "N";
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="6%"><?= ($iStart + $i + 1) ?></td>
				      <td width="19%"><?= $sOrderNo ?> <?= $sStyleNo ?></td>
				      <td width="18%"><span id="Auditor<?= $iId ?>"><?= $sUsersList[$iAuditor] ?></span></td>
				      <td width="18%"><span id="Vendor<?= $iId ?>"><?= $sVendorsList[$iVendor] ?></span></td>
				      <td width="10%"><span id="Line<?= $iId ?>"><?= $sLine ?></span></td>
				      <td width="10%"><span id="Date<?= $iId ?>"><?= formatDate($sAuditDate) ?></span></td>
				      <td width="9%"><span id="StartTime<?= $iId ?>"><?= (str_pad($iStartHour, 2, '0', STR_PAD_LEFT).":".str_pad($iStartMinutes, 2, '0', STR_PAD_LEFT)." ".$sStartAmPm) ?></span></td>

				      <td width="10%" class="center">
<?
		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="quonda/confirm-audit-schedule.php?Id=<?= $iId ?>" onclick="return confirmSchedule(<?= $iId ?>);"><img src="images/icons/yes.png" width="16" height="16" hspace="2" alt="Confirm Audit" title="Confirm Audit" /></a>
				        <a href="./" onclick="Effect.SlideDown('Edit<?= $iId ?>'); return false;"><img src="images/icons/edit.gif" width="16" height="16" hspace="2" alt="Edit" title="Edit" /></a>
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="quonda/delete-audit-schedule.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Audit Schedule?');"><img src="images/icons/delete.gif" width="16" height="16" hspace="2" alt="Delete" title="Delete" /></a>
<?
		}
?>
				      </td>
				    </tr>
				  </table>


				  <div id="Edit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" onsubmit="return false;" class="frmInlineEdit">
					  <input type="hidden" name="Complete" id="Complete<?= $iId ?>" value="<?= $sComplete ?>" />
					  <input type="hidden" name="Id" value="<?= $iId ?>" />

					  <table border="0" cellpadding="0" cellspacing="0" width="100%">
					    <tr valign="top">
						  <td width="50"%>

						    <table border="0" cellpadding="3" cellspacing="0" width="100%">
							  <tr>
							    <td width="80">Auditor<span class="mandatory">*</span></td>
							    <td width="20" align="center">:</td>

							    <td>
								  <select name="Auditor">
								    <option value=""></option>
<?
		$bAuditor = false;

		foreach ($sAuditorsList as $sKey => $sValue)
		{
?>
			            	  		<option value="<?= $sKey ?>"<?= (($sKey == $iAuditor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
			if ($sKey == $iAuditor)
				$bAuditor = true;
		}


		if ($bAuditor == false)
		{
?>
			            	  		<option value="<?= $iAuditor ?>" selected><?= getDbValue("name", "tbl_users", "id='$iAuditor'") ?></option>
<?
		}
?>
								  </select>
							    </td>
							  </tr>

							  <tr>
							    <td>Group</td>
							    <td align="center">:</td>

							    <td>
								  <select name="Group">
								    <option value=""></option>
<?
		foreach ($sGroupsList as $sKey => $sValue)
		{
?>
			            	  	    <option value="<?= $sKey ?>"<?= (($sKey == $iGroup) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
								  </select>
							    </td>
							  </tr>

							  <tr>
							    <td>Vendor<span class="mandatory">*</span></td>
							    <td align="center">:</td>

							    <td>
								  <select id="Vendor<?= $i ?>" name="Vendor" onchange="getListValues('Vendor<?= $i ?>', 'Line<?= $i ?>', 'Lines');">
								    <option value=""></option>
<?
		foreach ($sVendorsList as $sKey => $sValue)
		{
?>
			            	  		<option value="<?= $sKey ?>"<?= (($sKey == $iVendor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
								  </select>
							    </td>
							  </tr>

							  <tr>
							   <td>Report Type<span class="mandatory">*</span></td>
							    <td align="center">:</td>

							    <td>
								  <select name="Report">
								    <option value=""></option>
<?
		foreach ($sReportsList as $sKey => $sValue)
		{
?>
			            	  		<option value="<?= $sKey ?>"<?= (($sKey == $iReport) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
								  </select>
							    </td>
							  </tr>

							  <tr>
							    <td>Line<span class="mandatory">*</span></td>
							    <td align="center">:</td>

							    <td>
								  <select id="Line<?= $i ?>" name="Line">
								    <option value=""></option>
<?
		$sSQL = "SELECT id, line FROM tbl_lines WHERE vendor_id='$iVendor' ORDER BY line";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$sKey   = $objDb2->getField($j, 0);
			$sValue = $objDb2->getField($j, 1);
?>
	  	        			  		<option value="<?= $sKey ?>"<?= (($sKey == $iLine) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
								  </select>
							    </td>
							  </tr>

							  <tr>
							    <td>Audit Date<span class="mandatory">*</span></td>
							    <td align="center">:</td>

							    <td>

								  <table border="0" cellpadding="0" cellspacing="0" width="116">
								    <tr>
								 	  <td width="82"><input type="text" name="AuditDate" id="AuditDate<?= $i ?>" value="<?= $sAuditDate ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('AuditDate<?= $i ?>'), 'yyyy-mm-dd', this);" /></td>
									  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('AuditDate<?= $i ?>'), 'yyyy-mm-dd', this);" /></td>
								    </tr>
								  </table>

							    </td>
							  </tr>

							  <tr>
							    <td>Start Time<span class="mandatory">*</span></td>
							    <td align="center">:</td>

							    <td>
								  <select name="StartHour">
								    <option value="00">00</option>
<?
		for ($j = 1; $j <= 12; $j ++)
		{
?>
	  	        			  		<option value="<?= str_pad($j, 2, '0', STR_PAD_LEFT) ?>"<?= (((int)$iStartHour == $j) ? " selected" : "") ?>><?= str_pad($j, 2, '0', STR_PAD_LEFT) ?></option>
<?
		}
?>
								  </select>

								  <select name="StartMinutes">
<?
		for ($j = 0; $j <= 59; $j ++)
		{
?>
	  	        			  		<option value="<?= str_pad($j, 2, '0', STR_PAD_LEFT) ?>"<?= (((int)$iStartMinutes == $j) ? " selected" : "") ?>><?= str_pad($j, 2, '0', STR_PAD_LEFT) ?></option>
<?
		}
?>
								  </select>

								  <select name="StartAmPm">
								    <option value="AM"<?= (($sStartAmPm == "AM") ? " selected" : "") ?>>AM</option>
								    <option value="PM"<?= (($sStartAmPm == "PM") ? " selected" : "") ?>>PM</option>
								  </select>
							    </td>
							  </tr>

							  <tr>
							    <td>End Time<span class="mandatory">*</span></td>
							    <td align="center">:</td>

							    <td>
								  <select name="EndHour">
								    <option value="00">00</option>
<?
		for ($j = 1; $j <= 12; $j ++)
		{
?>
	  	        			  		<option value="<?= str_pad($j, 2, '0', STR_PAD_LEFT) ?>"<?= (((int)$iEndHour == $j) ? " selected" : "") ?>><?= str_pad($j, 2, '0', STR_PAD_LEFT) ?></option>
<?
		}
?>
								  </select>

								  <select name="EndMinutes">
<?
		for ($j = 0; $j <= 59; $j ++)
		{
?>
	  	        			  		<option value="<?= str_pad($j, 2, '0', STR_PAD_LEFT) ?>"<?= (((int)$iEndMinutes == $j) ? " selected" : "") ?>><?= str_pad($j, 2, '0', STR_PAD_LEFT) ?></option>
<?
		}
?>
								  </select>

								  <select name="EndAmPm">
								    <option value="AM"<?= (($sEndAmPm == "AM") ? " selected" : "") ?>>AM</option>
								    <option value="PM"<?= (($sEndAmPm == "PM") ? " selected" : "") ?>>PM</option>
								  </select>
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

						  </td>

						  <td width="50%">

						    <table border="0" cellpadding="3" cellspacing="0" width="100%">
							  <tr>
							    <td width="80">Order No</td>
							    <td width="20" align="center">:</td>
							    <td><?= $sOrderNo ?></td>
							  </tr>

							  <tr>
							    <td>Style No</td>
							    <td align="center">:</td>
							    <td><?= substr($sStyleNo, 2) ?></td>
							  </tr>

							  <tr>
							    <td>Colors</td>
							    <td align="center">:</td>
							    <td><input type="text" name="Colors" value="<?= $sColors ?>" size="20" maxlength="255" class="textbox" /></td>
							  </tr>

							  <tr>
							    <td>Sample Size</td>
							    <td align="center">:</td>
							    <td><input type="text" name="SampleSize" value="<?= $iSampleSize ?>" size="20" maxlength="10" class="textbox" /></td>
							  </tr>
						    </table>

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
				      <td class="noRecord">No Audit Schedule Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&OrderNo={$OrderNo}&Vendor={$Vendor}&FromDate={$FromDate}&ToDate={$ToDate}");
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