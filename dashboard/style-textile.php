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

	$Vendor     = 19;
	$AuditStage = IO::strValue("AuditStage");
	$FromDate   = IO::strValue("FromDate");
	$ToDate     = IO::strValue("ToDate");

	if ($FromDate == "" || $ToDate == "")
	{
		$FromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 7), date("Y")));
		$ToDate   = date("Y-m-d");
	}

	if ($AuditStage == "")
		$AuditStage = "B";

	$sDefectColors    = getList("tbl_defect_types", "id", "color");
	$sUnits           = array("", "I", "II", "III", "IV");
	$sShifts          = array("", "Day Shifts", "Night Shifts");
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
</form>

<br />

<div>
<?
	$sStageTitle = $sAuditStagesList[$AuditStage];


	$sMainConditions  = "  AND qa.audit_type='B' AND qa.audit_result!='' AND qa.report_id!='6' AND qa.audit_stage='$AuditStage' AND
	                       qa.vendor_id='$Vendor' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate')
	                       AND NOT FIND_IN_SET(qa.report_id, '$sQmipReports')";
	$sExcludedDefects = "0";

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

	$iShift = 0;
?>
  <h2>Line Wise Progress Report (<?= $sStageTitle ?>)<br />From: <?= formatDate($FromDate) ?>  &nbsp; To:  <?= formatDate($ToDate) ?></h2>

  <table border="0" cellspacing="0" cellpadding="5" width="100%">
    <tr valign="top">
<?
	for ($iUnit = 1; $iUnit <= 4; $iUnit ++)
	{
		$sConditions = $sMainConditions;

		if ($iUnit == 1)
			$sConditions .= " AND l.line NOT LIKE '%Z%' AND l.line NOT LIKE '%Y%' AND l.line NOT LIKE '%X%' ";

		else if ($iUnit == 2)
			$sConditions .= " AND l.line LIKE '%Z%' ";

		else if ($iUnit == 3)
			$sConditions .= " AND l.line LIKE '%Y%' ";

		else if ($iUnit == 4)
			$sConditions .= " AND l.line LIKE '%X%' ";
?>
      <td width="25%">
        <div class="tblSheet">
<?
		@include($sBaseDir."includes/dashboard/style-lines-graph.php");
?>
	    </div>
      </td>
<?
	}
?>
    </tr>
  </table>

  <br />
  <h2>Defects Classification (<?= $sStageTitle ?>)<br />From: <?= formatDate($FromDate) ?>  &nbsp; To:  <?= formatDate($ToDate) ?></h2>

  <table border="0" cellspacing="0" cellpadding="5" width="100%">
    <tr valign="top">
<?
	for ($iUnit = 1; $iUnit <= 4; $iUnit ++)
	{
		$sConditions = $sMainConditions;

		if ($iUnit == 1)
			$sConditions .= " AND l.line NOT LIKE '%Z%' AND l.line NOT LIKE '%Y%' AND l.line NOT LIKE '%X%' ";

		else if ($iUnit == 2)
			$sConditions .= " AND l.line LIKE '%Z%' ";

		else if ($iUnit == 3)
			$sConditions .= " AND l.line LIKE '%Y%' ";

		else if ($iUnit == 4)
			$sConditions .= " AND l.line LIKE '%X%' ";
?>
      <td width="25%">
        <div class="tblSheet">
<?
		@include($sBaseDir."includes/dashboard/style-defect-types-graph.php");
?>
	    </div>
      </td>
<?
	}
?>
    </tr>
  </table>

  <br />
  <h2>Day/Night Shifts Comparison<br /><br />Line Wise Progress Report (<?= $sStageTitle ?>)<br />From: <?= formatDate($FromDate) ?>  &nbsp; To:  <?= formatDate($ToDate) ?></h2>

  <table border="0" cellspacing="0" cellpadding="5" width="100%">
    <tr valign="top">
<?
	$iShift      = 1;
	$sConditions = $sMainConditions;
	$sConditions .= " AND l.line NOT LIKE '%Z%' AND l.line NOT LIKE '%Y%' AND l.line NOT LIKE '%X%' AND l.line NOT LIKE '%U%' AND l.line NOT LIKE '%V%' AND l.line NOT LIKE '%W%' ";
?>
      <td width="50%">
        <div class="tblSheet">
<?
		@include($sBaseDir."includes/dashboard/style-lines-graph.php");
?>
	    </div>
      </td>


<?
	$iShift      = 2;
	$sConditions = $sMainConditions;
	$sConditions .= " AND (l.line LIKE '%U%' OR l.line LIKE '%V%' OR l.line LIKE '%W%') ";
?>
      <td width="50%">
        <div class="tblSheet">
<?
		@include($sBaseDir."includes/dashboard/style-lines-graph.php");
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


	for ($iUnit = 1; $iUnit <= 4; $iUnit ++)
	{
		$sConditions = $sMainConditions;

		if ($iUnit == 1)
			$sConditions .= " AND l.line NOT LIKE '%Z%' AND l.line NOT LIKE '%Y%' AND l.line NOT LIKE '%X%' ";

		else if ($iUnit == 2)
			$sConditions .= " AND l.line LIKE '%Z%' ";

		else if ($iUnit == 3)
			$sConditions .= " AND l.line LIKE '%Y%' ";

		else if ($iUnit == 4)
			$sConditions .= " AND l.line LIKE '%X%' ";
?>
      <td width="25%">
        <div class="tblSheet">
<?
		@include($sBaseDir."includes/dashboard/style-defect-code-graph.php");
?>
	    </div>
      </td>
<?
	}
?>
    </tr>
  </table>


  <br />
  <h2>Defect Rate Analysis<br /><br />From: <?= formatDate($FromDate) ?>  &nbsp; To:  <?= formatDate($ToDate) ?></h2>

  <div class="tblSheet">
<?
	@include($sBaseDir."includes/dashboard/style-defect-rate-graph.php");
?>
  </div>


  <br />
  <h2>Defect Rate Analysis - Yearly</h2>

  <div class="tblSheet">
<?
	@include($sBaseDir."includes/dashboard/style-defect-rate-yearly-graph.php");
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