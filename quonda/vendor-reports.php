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

	$Vendor     = IO::strValue("Vendor");
	$AuditStage = IO::strValue("AuditStage");
	$FromDate   = IO::strValue("FromDate");
	$ToDate     = IO::strValue("ToDate");
	$Brand      = IO::getArray("Brand");
	$Auditor    = IO::intValue("Auditor");

	if ($FromDate == "" || $ToDate == "")
	{
		$FromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 7), date("Y")));
		$ToDate   = date("Y-m-d");
	}

	if ($AuditStage == "")
		$AuditStage = "F";

	$sDefectColors    = getList("tbl_defect_types", "id", "color");
	$sVendorsList     = getList("tbl_vendors", "id", "vendor", "parent_id='0' AND sourcing='Y'");
	$sBrandsList      = getList("tbl_brands", "id", "brand", "parent_id>'0'");
	$sAuditorsList    = getList("tbl_users", "id", "name", "status='A' AND auditor='Y'");

	$sReportTypes     = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStages     = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStagesList = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')");
	
	if (@strpos($_SESSION["Email"], "mgfsourcing.com") !== FALSE)
		$sAuditorsList = getList("tbl_users", "id", "name", "status='A' AND auditor='Y' AND email LIKE '%mgfsourcing.com'");	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/fusion-charts/FusionCharts.js"></script>
  <script type="text/javascript" src="scripts/fusion-charts/FusionChartsExportComponent.js"></script>
  <script type="text/javascript" src="scripts/quonda/vendor-reports.js"></script>
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
			    <h1>vendor reports</h1>


				<form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
				<div id="SearchBar">
				  <table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
					  <td width="55">Vendor</td>

					  <td width="135">
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

					  <td width="70">From Date</td>
					  <td width="115"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:105px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="60">To Date</td>
					  <td width="115"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:105px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="45">Stage</td>

					  <td width="135">
						<select name="AuditStage">
<?
	foreach ($sAuditStagesList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $AuditStage) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
						</select>
					  </td>

					  <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
					</tr>
				  </table>
				</div>

				<div id="SubSearchBar" style="height:auto; padding-top:5px; padding-bottom:5px;">
				  <table border="0" cellpadding="0" cellspacing="0">
					<tr valign="top">
					  <td width="55">Brand</td>

					  <td width="135">
						<select name="Brand[]" multiple size="8" style="width: 133px;">
<?
	foreach ($sBrandsList as $sKey => $sValue)
	{
?>
		  				  <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Brand)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
						</select>
					  </td>
					  
			          <td width="70">Auditor</td>

			          <td>
					    <select name="Auditor" style="width: 145px;">
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
					</tr>
				  </table>
				</div>
				</form>

				<br />
<?
	$sStageTitle = $sAuditStagesList[$AuditStage];

	$sAuditorSQL      = "";
	$sMainConditions  = "  AND qa.audit_type='B' AND qa.audit_result!='' AND qa.audit_stage='$AuditStage' AND
	                       qa.vendor_id='$Vendor' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate')
	                       AND FIND_IN_SET(qa.report_id, '$sReportTypes')
	                       AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";

	if (count($Brand) > 0)
		$sMainConditions .= (" AND qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id IN (".@implode(",", $Brand).") AND vendor_id='$Vendor') ");

	if ($Auditor > 0)
	{
		$sMainConditions .= " AND qa.user_id='$Auditor' ";
		$sAuditorSQL     .= " AND qa.user_id='$Auditor' ";
	}


	$sConditions = $sMainConditions;

	@include($sBaseDir."includes/quonda/vendor-defect-types-graph.php");



	@include($sBaseDir."includes/quonda/vendor-defect-rate-graph.php");
	@include($sBaseDir."includes/quonda/vendor-defect-rate-yearly-graph.php");
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