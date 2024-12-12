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
	$Report      = IO::intValue("Report");
	$Vendor      = IO::intValue("Vendor");
    $Unit        = IO::strValue("Unit");
	$Floor       = IO::intValue("Floor");
	$Line        = IO::intValue("Line");
	$Color       = IO::strValue("Color");
	$OrderNo     = IO::strValue("OrderNo");
	$StyleNo     = IO::strValue("StyleNo");
	$Auditor     = IO::intValue("Auditor");
	$Brand       = IO::intValue("Brand");
	$AuditStage  = IO::strValue("AuditStage");
	$Region      = IO::intValue("Region");
	$FromDate    = IO::strValue("FromDate");
	$ToDate      = IO::strValue("ToDate");
	$AuditResult = IO::strValue("AuditResult");
	$Department  = IO::intValue("Department");
	$Customer    = IO::strValue("Customer");
	$Season      = IO::intValue("Season");
	$Program     = IO::intValue("Program");
	$DesignNo    = IO::strValue("DesignNo");
	$DesignName  = IO::strValue("DesignName");

	if (@strpos($_SESSION["Brands"], ",") === FALSE)
		$Brand = $_SESSION["Brands"];
	
	if (@strpos($_SESSION["Email"], "selimpex") !== FALSE)
		$Vendor = 13;
	
	else if (@strpos($_SESSION["Email"], "globalexports") !== FALSE)
		$Vendor = 229;
	
	if ($Vendor > 0)
		$_SESSION["QmipVendor"] = $Vendor;
	
	else
		$Vendor = $_SESSION["QmipVendor"];
	

	$sVendorBrands = getDbValue("GROUP_CONCAT(DISTINCT(brand_id) SEPARATOR ',')", "tbl_po", "vendor_id='$Vendor'");

	$sRegionsList     = getList("tbl_countries", "id", "country", "matrix='Y'");
	$sVendorsList     = getList("tbl_vendors", "id", "vendor", "FIND_IN_SET(id, '$sQmipVendors') AND id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList      = getList("tbl_brands", "id", "brand", "id IN ({$_SESSION['Brands']}) AND FIND_IN_SET(id, '$sVendorBrands')");

	$sReportTypes     = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sReportsList     = getList("tbl_reports", "id", "report", "FIND_IN_SET(id, '$sReportTypes')");

	$sAuditStages     = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStagesList = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')");
	
	if (@in_array($_SESSION["UserType"], array("GLOBALEXPORTS")))
		$sAuditorsList = getList("tbl_users", "id", "name", "status='A' AND auditor='Y' AND user_type='{$_SESSION['UserType']}'");
	
	else
		$sAuditorsList = getList("tbl_users", "id", "name", "auditor='Y' AND status='A'");


	if (!$_GET && ($FromDate == "" || $ToDate == ""))
	{
		if (@strpos($_SESSION["Email"], "apparelco.com") !== FALSE || @strpos($_SESSION["Email"], "3-tree.com") !== FALSE)
			$FromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 4), date("Y")));

		else
			$FromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 14), date("Y")));


		$ToDate = date("Y-m-d");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/qmip/qa-reports.js"></script>
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
			    <h1>QA Reports</h1>

<?
/*
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="get" action="qmip/edit-qa-report.php" class="frmOutline">
			    <input type="hidden" name="Step" value="1" />

				<h2>Add QA Report</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="75">Vendor<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td>
					  <select id="VendorX" name="Vendor" onchange="getListValues('VendorX', 'AuditCode', 'AuditCodes');">
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

				  <tr>
					<td>Audit Code<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select id="AuditCode" name="AuditCode">
					    <option value=""></option>
<?
		$sSQL = "SELECT id, audit_code FROM tbl_qa_reports WHERE vendor_id='$Vendor' AND audit_result='' AND FIND_IN_SET(report_id, '$sReportTypes') AND FIND_IN_SET(audit_stage, '$sAuditStages') ORDER BY audit_code";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sKey    = $objDb->getField($i, 0);
			$sValue  = $objDb->getField($i, 1);
?>
	  	        		<option value="<?= $sKey ?>"><?= $sValue ?></option>
<?
		}
?>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.AuditCode.value = "<?= $AuditCode ?>";
					  -->
					  </script>
					</td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar"><input type="submit" id="BtnSubmit" value="" class="btnSubmit" title="Submit" onclick="return validateForm( );" /></div>
			    </form>

			    <hr />
<?
	}
*/
?>
			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="78">Audit Code</td>
			          <td width="100"><input type="text" name="AuditCode" value="<?= $AuditCode ?>" class="textbox" maxlength="50" size="10" /></td>

<?
	if (@strpos($_SESSION["Email"], "apparelco.com") !== FALSE || @strpos($_SESSION["Email"], "3-tree.com") !== FALSE)
	{
?>
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
<?
	}
?>
			          <td width="55">Vendor</td>

			          <td width="180">
					    <select name="Vendor" id="Vendor" style="width:90%;" onchange="getListValues('Vendor', 'Unit', 'VendorUnits'); getListValues('Vendor', 'Floor', 'VendorFloors'); getListValues('Vendor', 'Line', 'Lines');">
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
			            <select name="Brand" id="Brand" style="width:165px;"<? if ($Brand > 0) {?> onchange=" getListValues('Brand', 'Season', 'BrandSeasons');"<?} ?>>
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

			          <td width="140">
			            <select name="AuditStage">
			              <option value="">All Stages</option>
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

					  <td width="45">Color</td>

					  <td>
					    <select name="Color">
					      <option value="">Any Color</option>
<?
	if ($Vendor > 0 || $Brand > 0)
	{
		$sColorsSQL = "";

		if ($Vendor > 0)
			$sColorsSQL .= " AND po.vendor_id='$Vendor' ";

		else
			$sColorsSQL .= " AND FIND_IN_SET(po.vendor_id, '{$_SESSION['Vendors']}') ";

		if ($Brand > 0)
			$sColorsSQL .= " AND po.brand_id='$Brand' ";

		else
			$sColorsSQL .= " AND FIND_IN_SET(po.brand_id, '{$_SESSION['Brands']}') ";


		$sSQL = "SELECT DISTINCT(pc.color)
				 FROM tbl_po_colors pc, tbl_po po, tbl_qa_reports qa
				 WHERE pc.po_id=po.id AND po.id=qa.po_id AND FIND_IN_SET(qa.report_id, '$sReportTypes') AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') $sColorsSQL
				 ORDER BY pc.color";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sKey   = $objDb->getField($i, 0);
			$sValue = $objDb->getField($i, 0);
?>
	  	        		  <option value="<?= $sKey ?>"><?= $sValue ?></option>
<?
		}
	}
?>
					    </select>

					    <script type="text/javascript">
					    <!--
						  document.frmSearch.Color.value = "<?= $Color ?>";
					    -->
					    </script>
					  </td>
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

			          <td width="110">
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
			          <td width="55">Auditor</td>

			          <td width="220">
					    <select name="Auditor">
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

					  <td width="38">Unit</td>

					  <td width="150">
					    <select name="Unit" id="Unit" onchange="getListValues('Unit', 'Floor', 'UnitFloors'); getListValues('Unit', 'Line', 'UnitLines');">
						  <option value="">All Units</option>
<?
	$sUnitsList = array( );

	if ($Vendor > 0)
		$sUnitsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='$Vendor' AND sourcing='Y'");

	foreach ($sUnitsList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Unit) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
					  </td>

					  <td width="42">Floor</td>

					  <td width="150">
					    <select name="Floor" id="Floor" onchange="getListValues('Floor', 'Line', 'FloorLines');">
						  <option value="">All Floors</option>
<?
	$sFloorsList = getList("tbl_floors", "id", "floor", "vendor_id='$Vendor' AND unit_id='$Unit'");

	foreach ($sFloorsList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Floor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
					  </td>

			          <td width="38">Line</td>

			          <td width="150">
			            <select id="Line" name="Line">
			              <option value="">All Lines</option>
<?
	$sLinesList = getList("tbl_lines", "id", "line", "vendor_id='$Vendor' AND unit_id='$Unit' AND floor_id='$Floor'");

	foreach ($sLinesList as $sKey => $sValue)
	{
?>
	  	        		  <option value="<?= $sKey ?>"<?= (($sKey == $Line) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
			            </select>
			          </td>

					  <td></td>
				    </tr>
				  </table>
			    </div>

<?
	if ($Brand > 0)
	{
?>
			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr>
			          <td width="70">Customer</td>

			          <td width="200">
					    <select name="Customer" style="width:190px;">
						  <option value="">All Customers</option>
<?
		$sCustomersList = getList("tbl_po", "DISTINCT(customer)", "customer", "brand_id='$Brand' AND vendor_id IN ({$_SESSION['Vendors']}) AND customer!=''");

		foreach ($sCustomersList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Customer) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					    </select>
			          </td>

			          <td width="55">Season</td>

			          <td width="200">
			            <select name="Season" id="Season" style="width:190px;">
			              <option value="">All Seasons</option>
<?
		$iParent      = getDbValue("parent_id", "tbl_brands", "id='$Brand'");
		$sSeasonsList = getList("tbl_seasons", "id", "season", "brand_id='$iParent' AND parent_id>'0'");

		foreach ($sSeasonsList as $sKey => $sValue)
		{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Season) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
			            </select>
			          </td>

			          <td width="65">Program</td>

			          <td width="350">
			            <select name="Program" id="Program" style="width:340px;">
			              <option value="">All Programs</option>
<?
		$sProgramsList = getList("tbl_programs", "id", "program", "", "id");

		foreach ($sProgramsList as $sKey => $sValue)
		{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Program) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
			            </select>
			          </td>

					  <td></td>
				    </tr>
				  </table>
			    </div>
<?
	}
?>
			    </form>

			    <div class="tblSheet">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = " WHERE audit_result!='' AND FIND_IN_SET(report_id, '$sReportTypes') ";

	if ($AuditCode != "")
		$sConditions .= " AND audit_code LIKE '%$AuditCode%' ";

	if ($Auditor > 0)
		$sConditions .= " AND user_id='$Auditor' ";

	if ($Report > 0)
		$sConditions .= " AND report_id='$Report' ";

	else
		$sConditions .= " AND FIND_IN_SET(report_id, '$sReportTypes') ";

	if ($AuditResult != "")
		$sConditions .= " AND audit_result='$AuditResult' ";

	if ($AuditStage != "")
		$sConditions .= " AND audit_stage='$AuditStage' ";

	else
		$sConditions .= " AND FIND_IN_SET(audit_stage, '$sAuditStages') ";

	if ($Vendor > 0)
		$sConditions .= " AND vendor_id='$Vendor' ";

	else
		$sConditions .= " AND FIND_IN_SET(vendor_id, '$sQmipVendors') AND FIND_IN_SET(vendor_id, '{$_SESSION['Vendors']}') ";

	if ($Unit > 0)
		$sConditions .= " AND unit_id='$Unit' ";

	if ($Floor > 0)
		$sConditions .= " AND line_id IN (SELECT id FROM tbl_lines WHERE floor_id='$Floor') ";

	if ($Line > 0)
		$sConditions .= " AND line_id='$Line' ";

	if ($Region > 0)
		$sConditions .= " AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND (audit_date BETWEEN '$FromDate' AND '$ToDate') ";

	if ($Department > 0)
		$sConditions .= " AND department_id='$Department' ";


	if ($Brand > 0)
		$sSQL = "SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand' AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}') ";

	else
		$sSQL = "SELECT id FROM tbl_styles WHERE sub_brand_id IN ({$_SESSION['Brands']}) AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}') ";

	if ($Season > 0)
		$sSQL .= " AND sub_season_id='$Season' ";

	if ($Program > 0)
		$sSQL .= " AND program_id='$Program' ";

	if ($DesignNo != "")
		$sSQL .= " AND design_no='$DesignNo' ";

	if ($DesignName != "")
		$sSQL .= " AND design_name='$DesignName' ";


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


	if ($OrderNo != "")
	{
		$sConditions .= " AND (";


		$sSQL = "SELECT id FROM tbl_po WHERE order_no LIKE '%$OrderNo%'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iPoId = $objDb->getField($i, 0);

			if ($i > 0)
				$sConditions .= " OR ";

			$sConditions .= "po_id='$iPoId' OR FIND_IN_SET('$iPoId', additional_pos) ";
		}

		$sConditions .= ") ";
	}

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

	if ($StyleNo != "")
	{
		$sSQL = "SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE style_id IN (SELECT id FROM tbl_styles WHERE style LIKE '%$StyleNo%')";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sPos   = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (",".$objDb->getField($i, 0));

		if ($sPos != "")
		{
			$sPos = substr($sPos, 1);

			$sConditions .= " AND (po_id IN ($sPos) OR style_id IN (SELECT id FROM tbl_styles WHERE style LIKE '%$StyleNo%' AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}'))) ";
		}

		else
			$sConditions .= " AND style_id IN (SELECT id FROM tbl_styles WHERE style LIKE '%$StyleNo%' AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')) ";

	}

	if ($Color != "")
	{
		$sSQL = "SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE color='$Color'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sPos   = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (",".$objDb->getField($i, 0));

		if ($sPos != "")
			$sPos = substr($sPos, 1);

		$sConditions .= " AND po_id IN ($sPos) ";
	}


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_qa_reports", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT id, audit_code, po_id, vendor_id, audit_stage, audit_result, audit_date, report_id,
	                (SELECT style FROM tbl_styles WHERE id=tbl_qa_reports.style_id) AS _Style,
                        (SELECT line FROM tbl_lines WHERE id=tbl_qa_reports.line_id) AS _Line,
                        (SELECT report FROM tbl_reports WHERE id=tbl_qa_reports.report_id) AS _Report
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
				      <td width="5%">#</td>
				      <td width="10%">Audit Code</td>
				      <td width="12%">PO</td>
				      <td width="10%">Style</td>
                      <td width="20%">Report</td>
				      <td width="10%">Line</td>
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
		$sLine        = $objDb->getField($i, '_Line');
		$sReport      = $objDb->getField($i, '_Report');
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
			case "R" : $sAuditResult = "Re-Inspection"; break;
		}
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="5%"><?= ($iStart + $i + 1) ?></td>
				      <td width="10%"><?= $sAuditCode ?></td>

<?
		if (checkUserRights("view-purchase-order.php", "Data Entry", "view"))
		{
?>
				      <td width="12%"><a href="data/view-purchase-order.php?Id=<?= $iPoId ?>" class="lightview sheetLink" rel="iframe" title="PO # <?= $sPo ?> :: :: width: 850, height: 550"><?= $sPo ?></a></td>
<?
		}

		else
		{
?>
				      <td width="12%"><?= $sPo ?></td>
<?
		}
?>
				      <td width="10%"><?= $sStyle ?></td>
                      <td width="20%"><?= str_replace('Vendor ', '', $sReport) ?></td>
				      <td width="10%"><?= $sLine ?></td>
				      <td width="6%"><?= $sAuditResult ?></td>
				      <td width="10%"><?= formatDate($sAuditDate) ?></td>

				      <td width="16%">
<?
		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="qmip/edit-qa-report.php?Id=<?= $iId ?>"><img src="images/icons/edit.gif" width="16" height="16" hspace="2" alt="Edit" title="Edit" /></a>
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="qmip/delete-audit-code.php?Id=<?= $iId ?>&AuditDate=<?= $sAuditDate ?>" onclick="return confirm('Are you SURE, You want to Delete this QA Report?.');"><img src="images/icons/delete.gif" width="16" height="16" hspace="1" alt="Delete" title="Delete" /></a>
<?
		}
?>
				        <a href="qmip/view-qa-report.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="Audit Code : <?= $sAuditCode ?> :: :: width: 850, height: 550"><img src="images/icons/view.gif" width="16" height="16" hspace="1" alt="View" title="View" /></a>
				        <a href="qmip/qa-report-images.php?AuditCode=<?= $sAuditCode ?>"><img src="images/icons/pictures.gif" width="16" height="16" hspace="1" alt="Pictures" title="Pictures" /></a>
				        <a href="qmip/export-qa-report.php?Id=<?= $iId ?>&ReportId=<?= $iReportId ?>&Brand=<?= $iBrandId ?>&AuditStage=<?= $sAuditStage ?>"><img src="images/icons/pdf.gif" width="16" height="16" hspace="1" alt="QA Report" title="QA Report" /></a>
<?
		if (@strpos($_SESSION["Email"], "apparelco.com") !== FALSE || @strpos($_SESSION["Email"], "3-tree.com") !== FALSE)
		{
?>
				        <a href="qmip/send-qa-report.php?Id=<?= $iId ?>"><img src="images/icons/email.gif" width="16" height="16" hspace="1" alt="Email Report" title="Email Report" /></a>
<?
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
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&AuditCode={$AuditCode}&Report={$Report}&Vendor={$Vendor}&Color={$Color}&OrderNo={$OrderNo}&StyleNo={$StyleNo}&Auditor={$Auditor}&Brand={$Brand}&AuditStage={$AuditStage}&FromDate={$FromDate}&ToDate={$ToDate}&AuditResult={$AuditResult}&Department={$iDepartment}&Customer={$Customer}&Season={$Season}&Program={$Program}&DesignNo={$DesignNo}&DesignName={$DesignName}");
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