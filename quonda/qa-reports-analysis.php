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

	$PageId      = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$AuditCode   = IO::strValue("AuditCode");
	$Report      = IO::strValue("Report");
	$Vendor      = IO::strValue("Vendor");
	$Brand       = IO::intValue("Brand");
	$AuditStage  = IO::strValue("AuditStage");
	$Region      = IO::intValue("Region");
	$FromDate    = IO::strValue("FromDate");
	$ToDate      = IO::strValue("ToDate");
	$AuditResult = IO::strValue("AuditResult");

	if (@strpos($_SESSION["Brands"], ",") === FALSE)
		$Brand = $_SESSION["Brands"];

	$sRegionsList     = getList("tbl_countries", "id", "country", "matrix='Y'");
	$sVendorsList     = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList      = getList("tbl_brands", "id", "brand", "id IN ({$_SESSION['Brands']})");

	$sReportTypes     = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sReportsList     = getList("tbl_reports", "id", "report", "FIND_IN_SET(id, '$sReportTypes') AND NOT FIND_IN_SET(id, '$sQmipReports')");

	$sAuditStages     = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStagesList = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')");


	if (!$_GET && ($FromDate == "" || $ToDate == ""))
	{
		$FromDate = date("Y-m-d");
		$ToDate   = date("Y-m-d");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/quonda/qa-reports-analysis.js"></script>
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
			    <h1>qa reports analysis</h1>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="78">Audit Code</td>
			          <td width="100"><input type="text" name="AuditCode" value="<?= $AuditCode ?>" class="textbox" maxlength="50" size="10" /></td>

			          <td width="40">Type</td>

			          <td width="140">
					    <select name="Report">
						  <option value="">All Types</option>
<?
	foreach ($sReportsList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Report) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
			          </td>

			          <td width="55">Vendor</td>

			          <td width="180">
					    <select name="Vendor" style="width:170px;">
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

			          <td width="170">
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

			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0" width="100%">
				    <tr>
			          <td width="65">Order No</td>
			          <td width="120"><input type="text" name="OrderNo" value="<?= $OrderNo ?>" class="textbox" maxlength="50" size="12" /></td>
			          <td width="62">Style No</td>
			          <td width="120"><input type="text" name="StyleNo" value="<?= $StyleNo ?>" class="textbox" maxlength="50" size="12" /></td>
			          <td width="45">Stage</td>

			          <td width="120">
			            <select name="AuditStage">
			              <option value="">All Stages</option>
<?
	foreach ($sAuditStagesList as $sKey => $sValue)
	{
		if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE && $sValue == "Final")
			$sValue = "Firewall";

		if ( (@strpos($_SESSION["Email"], "pelknit.com") !== FALSE || @strpos($_SESSION["Email"], "fencepostproductions.com") !== FALSE) &&
			 !@in_array($sKey, array("B", "C", "O", "F")) )
			continue;
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $AuditStage) ? " selected" : "") ?>><?= $sValue ?></option>
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
				    </tr>
				  </table>
			    </div>

			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr>
					  <td width="50">Region</td>

					  <td width="130">
					    <select name="Region">
						  <option value="">All Regions</option>
<?
	foreach ($sRegionsList as $sKey => $sValue)
	{
?>
	  	        		  <option value="<?= $sKey ?>"<?= (($sKey == $Region) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
					  </td>

			          <td width="46">Result</td>

			          <td>
			            <select name="AuditResult">
			              <option value="">All Results</option>
						  <option value="P">Pass</option>
						  <option value="F">Fail</option>
						  <option value="H">Hold</option>
					    </select>

					    <script type="text/javascript">
					    <!--
						  document.frmSearch.AuditResult.value = "<?= $AuditResult ?>";
					    -->
					    </script>
			          </td>
				    </tr>
				  </table>
			    </div>
			    </form>

			    <div class="tblSheet">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = " WHERE audit_result!='' ";

	if ($AuditCode != "")
		$sConditions .= " AND audit_code LIKE '%$AuditCode%' ";

	if ($Report > 0)
		$sConditions .= " AND report_id='$Report' ";

	else
		$sConditions .= " AND FIND_IN_SET(report_id, '$sReportTypes') AND NOT FIND_IN_SET(report_id, '$sQmipReports') ";

	if ($AuditResult != "")
		$sConditions .= " AND audit_result='$AuditResult' ";

	if ($AuditStage != "")
		$sConditions .= " AND audit_stage='$AuditStage' ";

	else
		$sConditions .= " AND FIND_IN_SET(audit_stage, '$sAuditStages') ";

	if ($Vendor > 0)
		$sConditions .= " AND vendor_id='$Vendor' ";

	else
		$sConditions .= " AND vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Region > 0)
		$sConditions .= " AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND (audit_date BETWEEN '$FromDate' AND '$ToDate') ";



	$sSQL = "SELECT id FROM tbl_styles WHERE sub_brand_id IN ({$_SESSION['Brands']}) AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}') ";
	$objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	$sStyles = "";

	for ($i = 0; $i < $iCount; $i ++)
		$sStyles .= (",".$objDb->getField($i, 0));

	if ($sStyles != "")
		$sStyles = substr($sStyles, 1);

	if (@strpos($_SESSION["Email"], "apparelco.com") !== FALSE || @strpos($_SESSION["Email"], "3-tree.com") !== FALSE)
		$sConditions .= " AND (style_id='0' OR style_id IN ($sStyles)) ";

	else
		$sConditions .= " AND style_id IN ($sStyles) ";



	if ($Brand > 0)
	{
		$sSQL = "SELECT id FROM tbl_po WHERE brand_id='$Brand'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sPos   = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (",".$objDb->getField($i, 0));

		if ($sPos != "")
			$sPos = substr($sPos, 1);

		$sConditions .= " AND po_id IN ($sPos) ";
	}

	else
	{
		if ($Vendor > 0)
		{
			$sSQL = "SELECT id FROM tbl_po WHERE vendor_id='$Vendor' AND brand_id IN ({$_SESSION['Brands']})";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );
			$sPos   = "";

			for ($i = 0; $i < $iCount; $i ++)
				$sPos .= (",".$objDb->getField($i, 0));

			if ($sPos != "")
				$sPos = substr($sPos, 1);

			$sConditions .= " AND po_id IN ($sPos) ";
		}

		else
			$sConditions .= " AND (po_id='0' OR po_id IN (SELECT id FROM tbl_po WHERE vendor_id IN ({$_SESSION['Vendors']}) AND brand_id IN ({$_SESSION['Brands']})))";
	}



	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_qa_reports", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT id, audit_code, po_id, vendor_id, audit_stage, audit_result, audit_date, report_id,
	                (SELECT style FROM tbl_styles WHERE id=tbl_qa_reports.style_id) AS _Style
	         FROM tbl_qa_reports
	         $sConditions
	         ORDER BY id DESC
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
				      <td width="7%">#</td>
				      <td width="10%">Audit Code</td>
				      <td width="15%">PO</td>
				      <td width="13%">Style</td>
				      <td width="17%">Vendor</td>
				      <td width="6%">Stage</td>
				      <td width="6%">Result</td>
				      <td width="10%">Audit Date</td>
				      <td width="16%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId          = $objDb->getField($i, 'id');
		$sAuditCode   = $objDb->getField($i, 'audit_code');
		$iPoId        = $objDb->getField($i, 'po_id');
		$sStyle       = $objDb->getField($i, '_Style');
		$iVendor      = $objDb->getField($i, 'vendor_id');
		$sAuditStage  = $objDb->getField($i, 'audit_stage');
		$sAuditResult = $objDb->getField($i, 'audit_result');
		$sAuditDate   = $objDb->getField($i, 'audit_date');
		$iReportId    = $objDb->getField($i, 'report_id');
		$sPo          = "";
		$iBrandId     = "";


		if ($iPoId > 0)
		{
			$sSQL = "SELECT CONCAT(order_no, ' ', order_status) AS _PO, brand_id FROM tbl_po WHERE id='$iPoId'";
			$objDb2->query($sSQL);

			$sPo      = $objDb2->getField(0, '_PO');
			$iBrandId = $objDb2->getField(0, 'brand_id');
		}


		switch ($sAuditResult)
		{
			case "A" : $sAuditResult = "Pass"; break;
			case "B" : $sAuditResult = "Pass"; break;
			case "C" : $sAuditResult = "Fail"; break;
			case "P" : $sAuditResult = "Pass"; break;
			case "F" : $sAuditResult = "Fail"; break;
			case "H" : $sAuditResult = "Hold"; break;
		}
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="7%"><?= ($iStart + $i + 1) ?></td>
				      <td width="10%"><?= $sAuditCode ?></td>

<?
		if (checkUserRights("view-purchase-order.php", "Data Entry", "view"))
		{
?>
				      <td width="15%"><a href="data/view-purchase-order.php?Id=<?= $iPoId ?>" class="lightview sheetLink" rel="iframe" title="PO # <?= $sPo ?> :: :: width: 700, height: 550"><?= $sPo ?></a></td>
<?
		}

		else
		{
?>
				      <td width="15%"><?= $sPo ?></td>
<?
		}
?>
				      <td width="13%"><?= $sStyle ?></td>
				      <td width="17%"><?= $sVendorsList[$iVendor] ?></td>
				      <td width="6%"><?= $sAuditStage ?></td>
				      <td width="6%"><?= $sAuditResult ?></td>
				      <td width="10%"><?= formatDate($sAuditDate) ?></td>

				      <td width="16%">
				        <a href="quonda/view-qa-report.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="Audit Code : <?= $sAuditCode ?> :: :: width: 850, height: 550"><img src="images/icons/view.gif" width="16" height="16" hspace="1" alt="View" title="View" /></a>
				        <a href="quonda/qa-report-images.php?AuditCode=<?= $sAuditCode ?>"><img src="images/icons/pictures.gif" width="16" height="16" hspace="1" alt="Pictures" title="Pictures" /></a>
<?
		if (checkUserRights("qa-reports.php", "Quonda", "view"))
		{
			if (!@in_array($iReportId, array(3, 12)) || @in_array($iBrandId, array(32, 87, 119, 120, 121)))
			{
?>
				        <a href="quonda/export-qa-report.php?Id=<?= $iId ?>&ReportId=<?= $iReportId ?>&Brand=<?= $iBrandId ?>&AuditStage=<?= $sAuditStage ?>"><img src="images/icons/pdf.gif" width="16" height="16" hspace="1" alt="QA Report" title="QA Report" /></a>
<?
			}

			if (@strpos($_SESSION["Email"], "apparelco.com") !== FALSE || @strpos($_SESSION["Email"], "3-tree.com") !== FALSE)
			{
?>
				        <a href="quonda/send-qa-report.php?Id=<?= $iId ?>"><img src="images/icons/email.gif" width="16" height="16" hspace="1" alt="Email Report" title="Email Report" /></a>
<?
			}
		}
?>
				      </td>
				    </tr>
			      </table>
<?
	}

	if ($iCount == 0)
	{
?>
				  <div class="noRecord">No QA Report Found!</div>
<?
	}
?>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&AuditCode={$AuditCode}&Report={$Report}&Vendor={$Vendor}&Brand={$Brand}&AuditStage={$AuditStage}&FromDate={$FromDate}&ToDate={$ToDate}&AuditResult={$AuditResult}");

	
	if ($iCount > 0)
	{
?>
				<div class="buttonsBar" style="margin-top:4px;">
				  <input type="hidden" id="ExportUrl" name="ExportUrl" value="<?= (SITE_URL."quonda/export-qa-reports-analysis.php?AuditCode={$AuditCode}&Report={$Report}&Vendor={$Vendor}&Brand={$Brand}&AuditStage={$AuditStage}&FromDate={$FromDate}&ToDate={$ToDate}&AuditResult={$AuditResult}&Region={$Region}") ?>" />
				  <input type="button" value="" id="BtnExport" class="btnExport" title="Export" onclick="exportReport( );" />
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