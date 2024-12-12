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

	if ($FromDate == "" || $ToDate == "")
	{
		$FromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 7), date("Y")));
		$ToDate   = date("Y-m-d");
	}

	if ($AuditStage == "")
		$AuditStage = "B";

	$sDefectColors    = getList("tbl_defect_types", "id", "color");
	$sVendorsList     = getList("tbl_vendors", "id", "vendor", "parent_id='0' AND sourcing='Y'");
	$sBrandsList      = getList("tbl_brands", "id", "brand", "parent_id>'0'");
	$sAuditStagesList = getList("tbl_audit_stages", "code", "stage");
	$sStageColorsList = getList("tbl_audit_stages", "code", "color");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/fusion-charts/FusionCharts.js"></script>
  <script type="text/javascript" src="scripts/fusion-charts/FusionChartsExportComponent.js"></script>
</head>

<body style="margin:0px; background:#ffffff; padding:10px;">

<form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
<div id="SearchBar">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
	  <td width="55">Vendor</td>

	  <td width="220">
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
	  <td width="75">From Date</td>
	  <td width="78"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
	  <td width="50"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
	  <td width="55">To Date</td>
	  <td width="78"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
	  <td width="50"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
	  <td width="45">Stage</td>

	  <td width="120">
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
	  <td width="45">Brand</td>

	  <td>
		<select name="Brand[]" multiple size="8">
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
	</tr>
  </table>
</div>
</form>

<br />

<div>
<?
	$sStageTitle = $sAuditStagesList[$AuditStage];


	$sMainConditions  = "  AND qa.audit_type='B' AND qa.audit_result!='' AND qa.report_id!='6' AND NOT FIND_IN_SET(qa.report_id, '$sQmipReports') AND qa.audit_stage='$AuditStage' AND
	                       qa.vendor_id='$Vendor' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";

	if (count($Brand) > 0)
		$sMainConditions .= (" AND qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id IN (".@implode(",", $Brand).") AND vendor_id='$Vendor') ");

	$sExcludedDefects = "0";
/*
	if ($AuditStage == "O")
	{
		$sExcludedTypes = "4,27";


		$sSQL = "SELECT id FROM tbl_defect_codes WHERE FIND_IN_SET(type_id, '$sExcludedTypes')";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$iCodes = array( );

		for ($i = 0; $i < $iCount; $i ++)
			$iCodes[] = $objDb->getField($i, 0);


		$sSQL = "SELECT id FROM tbl_defect_codes WHERE code='112' OR (FIND_IN_SET(code, '115,199') AND report_id='1')";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$iCodes[] = $objDb->getField($i, 0);


		$sExcludedDefects = @implode(",", $iCodes);
	}

	else if ($AuditStage == "C")
	{
		$sSQL = "SELECT id FROM tbl_defect_codes WHERE FIND_IN_SET(code, '115,601') AND report_id='1'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$iCodes = array( );

		for ($i = 0; $i < $iCount; $i ++)
			$iCodes[] = $objDb->getField($i, 0);


		$sExcludedDefects = @implode(",", $iCodes);
	}
*/
?>
  <h2>Line Wise Progress Report (<?= $sStageTitle ?>)<br />From: <?= formatDate($FromDate) ?>  &nbsp; To:  <?= formatDate($ToDate) ?></h2>

  <table border="0" cellspacing="0" cellpadding="5" width="100%">
    <tr valign="top">
      <td width="25%">
        <div class="tblSheet">
<?
	$sConditions = $sMainConditions;

	@include($sBaseDir."includes/dashboard/vendor-lines-graph.php");
?>
	    </div>
      </td>
    </tr>
  </table>

  <br />
  <h2>Defects Classification (<?= $sStageTitle ?>)<br />From: <?= formatDate($FromDate) ?>  &nbsp; To:  <?= formatDate($ToDate) ?></h2>

  <table border="0" cellspacing="0" cellpadding="5" width="100%">
    <tr valign="top">
      <td width="25%">
        <div class="tblSheet">
<?
	$sConditions = $sMainConditions;

	@include($sBaseDir."includes/dashboard/vendor-defect-types-graph.php");
?>
	    </div>
      </td>
    </tr>
  </table>


  <br />
  <h2>Defect Code: Skip / Broken (201)<br /><br />Line Wise Progress Report (<?= $sStageTitle ?>)<br />From: <?= formatDate($FromDate) ?>  &nbsp; To:  <?= formatDate($ToDate) ?></h2>

  <table border="0" cellspacing="0" cellpadding="5" width="100%">
    <tr valign="top">
<?
	$sDefectCode = "201";

	$sSQL = "SELECT id FROM tbl_defect_codes WHERE code='$sDefectCode'";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$iCodes = array( );

	for ($i = 0; $i < $iCount; $i ++)
		$iCodes[] = $objDb->getField($i, 0);

	$sDefectCodes = @implode(",", $iCodes);


	$sConditions = $sMainConditions;
?>
      <td width="25%">
        <div class="tblSheet">
<?
	@include($sBaseDir."includes/dashboard/vendor-defect-code-graph.php");
?>
	    </div>
      </td>
    </tr>
  </table>


  <br />
  <h2>Defect Rate Analysis<br /><br />From: <?= formatDate($FromDate) ?>  &nbsp; To:  <?= formatDate($ToDate) ?></h2>

  <div class="tblSheet">
<?
	@include($sBaseDir."includes/dashboard/vendor-defect-rate-graph.php");
?>
  </div>


  <br />
  <h2>Defect Rate Analysis - Yearly</h2>

  <div class="tblSheet">
<?
	@include($sBaseDir."includes/dashboard/vendor-defect-rate-yearly-graph.php");
?>
  </div>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>