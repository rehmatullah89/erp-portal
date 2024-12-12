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
	$objDb3      = new Database( );

	$PageId  = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$OrderNo = IO::strValue("OrderNo");
	$Vendor  = IO::intValue("Vendor");
	$Brand   = IO::intValue("Brand");


	$sAuditorsList    = getList("tbl_users", "id", "name", "auditor='Y' AND status='A'");
	$sVendorsList     = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0'");
	$sReportsList     = getList("tbl_reports", "id", "report");
	$sGroupsList      = getList("tbl_auditor_groups", "id", "name");
	$sBrandsList      = getList("tbl_brands", "id", "brand", "id IN ({$_SESSION['Brands']})");

	$sReportTypes     = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sReportsList     = getList("tbl_reports", "id", "report", "FIND_IN_SET(id, '$sReportTypes')");

	$sAuditStages     = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStagesList = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/quonda/schedule.js"></script>
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
			    <h1>schedule audits</h1>

			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="70">Order No</td>
			          <td width="135"><input type="text" name="OrderNo" value="<?= $OrderNo ?>" class="textbox" maxlength="50" size="15" /></td>
			          <td width="55">Vendor</td>

			          <td width="200">
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

			          <td width="45">Brand</td>

			          <td width="200">
			            <select name="Brand" style="width:165px;">
			              <option value="">All Brands</option>
<?
	foreach ($sBrandsList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Brand) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
			            </select>
			          </td>

			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>
			    </form>

<?
	if ($sUserRights['Add'] == "Y")
	{
		$sSQL = "SELECT DISTINCT(po.id)
				 FROM tbl_po po, tbl_po_colors pc
				 WHERE po.id=pc.po_id AND po.status!='C' AND (DATEDIFF(pc.etd_required, NOW( )) BETWEEN 0 AND '3')
				       AND FIND_IN_SET(po.brand_id, '{$_SESSION['Brands']}') AND FIND_IN_SET(po.vendor_id, '{$_SESSION['Vendors']}')";

		if ($OrderNo != "")
			$sSQL .= " AND po.order_no LIKE '%{$OrderNo}%' ";

		if ($Vendor > 0)
			$sSQL .= " AND po.vendor_id='$Vendor' ";

		if ($Brand > 0)
			$sSQL .= " AND po.brand_id='$Brand' ";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sPOs   = "0";

		for ($i = 0; $i < $iCount; $i ++)
			$sPOs .= (",".$objDb->getField($i, 0));


		$iPageSize   = 10;
		$iPageCount  = 0;
		$sConditions = " WHERE po.id=pc.po_id AND FIND_IN_SET(po.id, '$sPOs')";

		@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_po po", "WHERE FIND_IN_SET(id, '$sPOs')", $iPageSize, $PageId);


		$sSQL = "SELECT DISTINCT(po.id) AS _PoId, po.vendor_id, pc.etd_required,
		                CONCAT(po.order_no, ' ', po.order_status) AS _Po
				 FROM tbl_po po, tbl_po_colors pc
				 $sConditions
				 ORDER BY _Po
				 LIMIT $iStart, $iPageSize";
		$objDb->query($sSQL);

		$iPoCount = $objDb->getCount( );

		for ($iIndex = 0, $iSerial = 1; $iIndex < $iPoCount; $iIndex ++)
		{
			$sPo          = $objDb->getField($iIndex, "_Po");
			$iPo          = $objDb->getField($iIndex, "_PoId");
			$sStyle       = $objDb->getField($iIndex, "_Style");
			$sEtdRequired = $objDb->getField($iIndex, "etd_required");
			$iVendor      = $objDb->getField($iIndex, "vendor_id");


			$iFinalAudits = getDbValue("COUNT(*)", "tbl_qa_reports", "(po_id='$iPo' OR FIND_IN_SET('$iPo', additional_pos)) AND audit_stage='F' AND audit_result='P'");

			if ($iFinalAudits > 0)
			{
?>
				<div class="tblSheet">
				  <h2 style="margin-bottom:0px;"><?= ($iStart + $iSerial) ?>). &nbsp; <?= $sPo ?> &nbsp; - &nbsp; <?= $sVendorsList[$iVendor] ?> &nbsp; - &nbsp;  <?= formatDate($sEtdRequired) ?></h2>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow" style="background:#aaaaaa;">
				      <td width="8%">#</td>
				      <td width="12%">Audit Code</td>
				      <td width="12%">Stage</td>
				      <td width="12%">Type</td>
				      <td width="12%">Result</td>
				      <td width="12%">Audit Date</td>
				      <td width="12%">Quantity</td>
				      <td width="12%">Defects</td>
				      <td width="8%">DHU</td>
				    </tr>
<?
				$sSQL = "SELECT * FROM tbl_qa_reports WHERE (po_id='$iPo' OR FIND_IN_SET('$iPo', additional_pos)) AND audit_stage='F' AND audit_result='P' AND FIND_IN_SET(audit_stage, '$sAuditStages') AND FIND_IN_SET(report_id, '$sReportTypes') ORDER BY id";
				$objDb2->query($sSQL);

				$iCount2 = $objDb2->getCount( );

				for ($j = 0; $j < $iCount2; $j ++)
				{
					$iAuditId     = $objDb2->getField($j, 'id');
					$iPoId        = $objDb2->getField($j, 'po_id');
					$iReportId    = $objDb2->getField($j, "report_id");
					$sAuditCode   = $objDb2->getField($j, 'audit_code');
					$sAuditStage  = $objDb2->getField($j, 'audit_stage');
					$sAuditType   = $objDb2->getField($j, "audit_type");
					$sAuditResult = $objDb2->getField($j, 'audit_result');
					$sAuditDate   = $objDb2->getField($j, 'audit_date');
					$fDhu         = $objDb2->getField($j, 'dhu');


					switch ($sAuditResult)
					{
						case "P" : $sAuditResult = "Pass"; break;
						case "F" : $sAuditResult = "Fail"; break;
						case "H" : $sAuditResult = "Hold"; break;
					}

					switch ($sAuditType)
					{
						case "B"  : $sAuditType  = "Bulk"; break;
						case "BG" : $sAuditType = "B-Grade"; break;
						case "SS" : $sAuditType = "Sales Sample"; break;
					}

					if ($iReportId == 6)
					{
						$sSQL = "SELECT SUM(actual_1 + actual_2 + actual_3) FROM tbl_gf_rolls_info WHERE audit_id='$iAuditId'";
						$objDb3->query($sSQL);

						$iQuantity = $objDb3->getField(0, 0);


						$sSQL = "SELECT SUM(defects) FROM tbl_gf_report_defects WHERE audit_id='$iAuditId'";
						$objDb3->query($sSQL);

						$iDefects = $objDb3->getField(0, 0);
					}

					else
					{
						$iQuantity = $objDb3->getField($j, "total_gmts");
						$iDefects  = $objDb3->getField($j, "defective_gmts");
					}

					$iBrandId = getDbValue("brand_id", "tbl_po", "id='$iPoId'");
?>
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td><?= ($j + 1) ?></td>
<?
					if (checkUserRights("qa-reports.php", "Quonda", "view"))
					{
?>
				      <td><a href="quonda/qa-reports.php?AuditCode=<?= $sAuditCode ?>" target="_blank"><?= $sAuditCode ?></a></td>
<?
					}

					else
					{
?>
				      <td><?= $sAuditCode ?></td>
<?
					}
?>

				      <td><?= $sAuditStagesList[$sAuditStage] ?></td>
				      <td><?= $sAuditType ?></td>
				      <td><?= $sAuditResult ?></td>
				      <td><?= formatDate($sAuditDate) ?></td>
				      <td><?= $iQuantity ?></td>
				      <td><?= $iDefects ?></td>
				      <td><?= $fDhu ?>%</td>
				    </tr>
<?
				}
?>
				  </table>
				</div>

				<hr />
<?
			}

			else
			{
				$iAuditor      = 0;
				$iGroup        = 0;
				$iReport       = 0;
				$iLine         = 0;
				$iStartHour    = "";
				$iStartMinutes = "";
				$sStartAmPm    = "";
				$iEndHour      = "";
				$iEndMinutes   = "";
				$sEndAmPm      = "";
				$sEndTime      = "";
				$iStyleId      = 0;
				$sColors       = "";
				$iSampleSize   = "";


				$sSQL = "SELECT * FROM tbl_audit_schedules WHERE po_id='$iPo'";
				$objDb2->query($sSQL);

				$iSchedule = $objDb2->getCount( );

				if ($iSchedule > 0)
				{
					$iAuditor     = $objDb2->getField(0, 'user_id');
					$iGroup       = $objDb2->getField(0, 'group_id');
					$iReport      = $objDb2->getField(0, 'report_id');
					$iLine        = $objDb2->getField(0, 'line_id');
					$sEtdRequired = $objDb2->getField(0, 'audit_date');
					$sStartTime   = $objDb2->getField(0, 'start_time');
					$sEndTime     = $objDb2->getField(0, 'end_time');
					$iStyleId     = $objDb2->getField(0, 'style_id');
					$sColors      = $objDb2->getField(0, 'colors');
					$iSampleSize  = $objDb2->getField(0, 'sample_size');


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
				}
?>
			    <div id="Schedule<?= $iSerial ?>">
			    <form name="frmData<?= $iSerial ?>" id="frmData<?= $iSerial ?>" class="frmOutline" onsubmit="return false;">
			    <input type="hidden" name="Id" id="Id" value="<?= $iSerial ?>" />
			    <input type="hidden" name="OrderNo" id="OrderNo" value="<?= $iPo ?>" />
			    <input type="hidden" name="Vendor" id="Vendor" value="<?= $iVendor ?>" />

				<h2><?= ($iStart + $iSerial) ?>). &nbsp; <?= $sPo ?> &nbsp; - &nbsp; <?= $sVendorsList[$iVendor] ?> &nbsp; - &nbsp;  <?= formatDate($sEtdRequired) ?></h2>

				<table border="0" cellpadding="0" cellspacing="0" width="100%">
				  <tr valign="top">
				    <td width="50%">

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="80">Auditor<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>

						  <td>
						    <select name="Auditor">
							  <option value=""></option>
<?
				foreach ($sAuditorsList as $sKey => $sValue)
				{
?>
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $iAuditor) ? " selected" : "") ?>><?= $sValue ?></option>
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
						  <td>Line</td>
						  <td align="center">:</td>

						  <td>
						    <select id="Line" name="Line">
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
							    <td width="82"><input type="text" name="AuditDate" id="AuditDate<?= $iIndex ?>" value="<?= $sEtdRequired ?>" readonly class="textbox" style="width:70px;" <? if ($iSchedule == 0) { ?> onclick="displayCalendar($('AuditDate<?= $iIndex ?>'), 'yyyy-mm-dd', this);"<? } ?> /></td>
							    <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;" <? if ($iSchedule == 0) { ?> onclick="displayCalendar($('AuditDate<?= $iIndex ?>'), 'yyyy-mm-dd', this);"<? } ?> /></td>
							  </tr>
 						    </table>

						  </td>
					    </tr>

					    <tr>
						  <td>Start Time</td>
						  <td align="center">:</td>

						  <td>
						    <select name="StartHour">
							  <option value=""></option>
							  <option value="00"<?= (($iStartHour != "" && $iStartHour == 0) ? " selected" : "") ?>>00</option>
<?
				for ($i = 1; $i <= 12; $i ++)
				{
?>
	  	        			  <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>"<?= (($iStartHour == $i) ? " selected" : "") ?>><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
<?
				}
?>
						    </select>

						    <select name="StartMinutes">
							  <option value=""></option>
<?
				for ($i = 0; $i <= 59; $i ++)
				{
?>
	  	        			  <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>"<?= (($iStartMinutes != "" && $iStartMinutes == $i) ? " selected" : "") ?>><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
<?
				}
?>
						    </select>

						    <select name="StartAmPm">
							  <option value=""></option>
							  <option value="AM"<?= (($sStartAmPm == "AM") ? " selected" : "") ?>>AM</option>
							  <option value="PM"<?= (($sStartAmPm == "PM") ? " selected" : "") ?>>PM</option>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>End Time</td>
						  <td align="center">:</td>

						  <td>
						    <select name="EndHour">
							  <option value=""></option>
							  <option value="00"<?= (($iEndHour != "" && $iEndHour == 0) ? " selected" : "") ?>>00</option>
<?
				for ($i = 1; $i <= 12; $i ++)
				{
?>
	  	        			  <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>"<?= (($iEndHour == $i) ? " selected" : "") ?>><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
<?
				}
?>
						    </select>

						    <select name="EndMinutes">
							  <option value=""></option>
<?
				for ($i = 0; $i <= 59; $i ++)
				{
?>
	  	        			  <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>"<?= (($iEndHour != "" && $iEndHour == $i) ? " selected" : "") ?>><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
<?
				}
?>
						    </select>

						    <select name="EndAmPm">
							  <option value=""></option>
							  <option value="AM"<?= (($sEndAmPm == "AM") ? " selected" : "") ?>>AM</option>
							  <option value="PM"<?= (($sEndAmPm == "PM") ? " selected" : "") ?>>PM</option>
						    </select>
						  </td>
					    </tr>
					  </table>

					</td>

					<td width="50%">

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
					  	  <td width="80">Style No</td>
						  <td width="20" align="center">:</td>

						  <td>
						    <select name="StyleNo" id="StyleNo">
							  <option value=""></option>
<?
				$sSQL = "SELECT id, style FROM tbl_styles WHERE id IN (SELECT DISTINCT(style_id) FROM tbl_po_colors WHERE po_id='$iPo') ORDER BY style";
				$objDb2->query($sSQL);

				$iCount2 = $objDb2->getCount( );

				for ($i = 0; $i < $iCount2; $i ++)
				{
					$sKey   = $objDb2->getField($i, 'id');
					$sValue = $objDb2->getField($i, 'style');
?>
							  <option value="<?= $sKey ?>"<?= (($sKey == $iStyleId) ? " selected" : "") ?>><?= $sValue ?></option>
<?
				}
?>
						    </select>
						  </td>
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

				<br />
<?
				if ($iSchedule > 0)
				{
?>
			    </form>
			    </div>

			    <hr />

			    <script type="text/javascript">
			    <!--
					var objForm = $("frmData<?= $iSerial ?>");
					objForm.disable( );
			    -->
			    </script>
<?
				}

				else
				{
?>
				<div class="buttonsBar"><input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm(<?= $iSerial ?>);" /></div>
			    </form>

			    <hr />
			    </div>

			    <div id="Msg<?= $iSerial ?>" class="msgOk" style="display:none;"></div>
<?
				}
			}

			$iSerial ++;
		}


		showPaging($PageId, $iPageCount, $iPoCount, $iStart, $iTotalRecords, "&OrderNo={$OrderNo}&Vendor={$Vendor}&Brand={$Brand}");
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
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>