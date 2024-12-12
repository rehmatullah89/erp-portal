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
	@require_once($sBaseDir."requires/chart.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$PO          = IO::strValue('PO');
	$Style       = IO::strValue('Style');
	$Vendor      = IO::intValue('Vendor');
	$Brand       = IO::intValue('Brand');
	$Region      = IO::intValue('Region');
	$Line        = IO::intValue("Line");
	$Nature      = IO::strValue("Nature");
	$AuditStage  = IO::strValue("AuditStage");
	$Report      = IO::intValue('Report');
	$AuditStatus = IO::strValue("AuditStatus");
	$AuditResult = IO::strValue("AuditResult");
	$FromDate    = IO::strValue('FromDate');
	$ToDate      = IO::strValue('ToDate');
	$Customers   = IO::getArray('Customers');

	if (!$_GET)
	{
		$FromDate = date("Y-m-d");
		$ToDate   = date("Y-m-d");
	}


	$sReportTypes = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStages = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/reports/quality-report.js"></script>
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
			    <h1>Quality Report</h1>

			    <input type="hidden" id="ReportUrl" name="ReportUrl" value="<?= $_SERVER['PHP_SELF'] ?>" />
			    <input type="hidden" id="ExportUrl" name="ExportUrl" value="<?= SITE_URL.'reports/export-quality-report.php' ?>" />
			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" class="frmOutline" onsubmit="$('BtnSearch').disabled=true;">

				<h2>Quality Summary Report</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="80">PO</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="PO" value="<?= $PO ?>" size="21" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Style</td>
					<td align="center">:</td>
					<td><input type="text" name="Style" value="<?= $Style ?>" size="21" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Vendor</td>
					<td align="center">:</td>

					<td>
					  <select name="Vendor" id="Vendor" onchange="getListValues('Vendor', 'Line', 'Lines');">
						<option value=""></option>
<?
	$sSQL = "SELECT id, vendor FROM tbl_vendors WHERE id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y' ORDER BY vendor";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, "id");
		$sValue = $objDb->getField($i, "vendor");
?>
	  	        		<option value="<?= $sKey ?>"<?= (($sKey == $Vendor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Brand</td>
					<td align="center">:</td>

					<td>
					  <select name="Brand" onchange="showBrandCustomers(this);">
						<option value=""></option>
<?
	$sSQL = "SELECT id, brand FROM tbl_brands WHERE parent_id>'0' AND id IN ({$_SESSION['Brands']}) ORDER BY brand";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, "id");
		$sValue = $objDb->getField($i, "brand");
?>
	            		<option value="<?= $sKey ?>"<?= (($sKey == $Brand) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>
<?
	if($Brand)
		$style = "";
	else 
		$style = "display:none;"
	
?>				  
				  <tr id="brandCustomers" style="<?=$style?>">
				  	<td valign="top">Customer</td>
				  	<td align="center" valign="top">:</td>
				  	<td>
				  		<select name="Customers[]" multiple size="10" style="width:300px;">

<?
        $sCustomers = array();
        $sSQL = "SELECT DISTINCT p.customer as _CUSTOMER FROM tbl_qa_reports qa, tbl_po p WHERE qa.po_id = p.id AND p.customer != '' AND qa.brand_id = '365' ORDER BY _CUSTOMER";  
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
            array_push($sCustomers, $objDb->getField($i, "_CUSTOMER"));
        
	foreach($sCustomers as $sCustomer)
	{
?>
	  	        		<option value="<?= $sCustomer ?>"<?= ((@in_array($sCustomer, $Customers)) ? ' selected' : '') ?> ><?= $sCustomer ?></option>
<?
	}
?>

				  		</select>
				  	</td>
				  </tr>
				  <tr>
					<td>Region</td>
					<td align="center">:</td>

					<td>
					  <select name="Region">
						<option value=""></option>
<?
	$sSQL = "SELECT id, country FROM tbl_countries WHERE matrix='Y' ORDER BY country";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, 0);
		$sValue = $objDb->getField($i, 1);
?>
	  	        		<option value="<?= $sKey ?>"<?= (($sKey == $Region) ? " selected" : "") ?>><?= $sValue ?></option>
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
					  <select name="Line" id="Line">
						<option value=""></option>
<?
	$sSQL = "SELECT id, line FROM tbl_lines WHERE vendor_id='$Vendor' ORDER BY line";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, 0);
		$sValue = $objDb->getField($i, 1);
?>
	  	        		<option value="<?= $sKey ?>"<?= (($sKey == $Line) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>
				  
				  <tr>
					<td>Defects</td>
					<td align="center">:</td>

					<td>
					  <select name="Nature" id="Nature">
						<option value=""></option>
	  	        		<option value="0"<?= (($Nature == "0") ? " selected" : "") ?>>Minor</option>
						<option value="1"<?= (($Nature == "1") ? " selected" : "") ?>>Major</option>
						<option value="2"<?= (($Nature == "2") ? " selected" : "") ?>>Critical</option>
					  </select>
					</td>
				  </tr>				  

				  <tr>
					<td>Audit Stage</td>
					<td align="center">:</td>

					<td>
					  <select name="AuditStage">
						<option value=""></option>
<?
	$sAuditStagesList = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')");

	foreach ($sAuditStagesList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $AuditStage) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Report Type</td>
					<td align="center">:</td>

					<td>
					  <select name="Report">
						<option value=""></option>
<?
	$sReportsList = getList("tbl_reports", "id", "report", "FIND_IN_SET(id, '$sReportTypes')");

	foreach ($sReportsList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Report) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Audit Status</td>
					<td align="center">:</td>

					<td>
					  <select name="AuditStatus">
						<option value=""></option>
						<option value="1st">1st</option>
						<option value="2nd">2nd</option>
						<option value="3rd">3rd</option>
						<option value="4th">4th</option>
						<option value="5th">5th</option>
						<option value="6th">6th</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmSearch.AuditStatus.value = "<?= $AuditStatus ?>";
					  -->
					  </script>
					</td>
				  </tr>

				  <tr>
					<td>Audit Result</td>
					<td align="center">:</td>

					<td>
					  <select name="AuditResult">
						<option value=""></option>
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

				  <tr>
					<td>Audit Date</td>
					<td align="center">:</td>

					<td>

					  <table border="0" cellpadding="0" cellspacing="0" width="320">
						<tr>
						  <td width="78"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="30" align="center">to</td>
						  <td width="78"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
						  <td align="right">[ <a href="./" onclick="$('FromDate').value=''; $('ToDate').value=''; return false;">Clear</a> ]</td>
						</tr>
					  </table>

					</td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar">
				  <input type="submit" value="" id="BtnSubmit" class="btnSubmit" title="Submit" />
<?
	if ($_GET)
	{
?>
				  <input type="button" value="" id="BtnExport" class="btnExport" title="Export" onclick="exportReport( );" />
<?
		$sConditions  = " AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') AND FIND_IN_SET(qa.report_id, '$sReportTypes') "; //  AND NOT FIND_IN_SET(qa.report_id, '$sQmipReports')

		$Customers = @implode("','", $Customers);

		if ($PO != "")
		{
			$sConditions .= " AND (";

			$sSubSQL = "SELECT id FROM tbl_po WHERE order_no LIKE '%$PO%'";
			$objDb->query($sSubSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iPoId = $objDb->getField($i, 0);

				if ($i > 0)
					$sConditions .= " OR ";

				$sConditions .= " qa.po_id='$iPoId' OR FIND_IN_SET('$iPoId', qa.additional_pos) ";
			}

			$sConditions .= ") ";
		}

		if ($Style != "")
			$sConditions .= " AND po.id IN (SELECT po_id FROM tbl_po_colors WHERE style_id IN (SELECT id FROM tbl_styles WHERE style='$Style'))";

		if ($Brand > 0)
			$sConditions .= " AND po.brand_id='$Brand'";

		if ($Vendor > 0)
			$sConditions .= " AND qa.vendor_id='$Vendor'";

		if ($Region > 0)
			$sConditions .= " AND qa.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y')";

		if ($Line > 0)
			$sConditions .= " AND qa.line_id='$Line'";

		if ($AuditStage != "")
			$sConditions .= " AND qa.audit_stage='$AuditStage'";

		if ($Report > 0)
			$sConditions .= " AND qa.report_id='$Report'";

		if ($AuditStatus != "")
			$sConditions .= " AND qa.audit_status='$AuditStatus'";

		if ($AuditResult != "")
			$sConditions .= " AND qa.audit_result='$AuditResult'";

		if ($FromDate != "" && $ToDate != "")
			$sConditions .= " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";
		
		if ($Nature != "")
			$sConditions .= " AND qad.nature='$Nature' ";

		if ($Customers != "")
			$sConditions .= " AND po.customer IN ('".$Customers."') ";


		$iDefects = array( );

		//AND qa.audit_type='B'
		$sSQL = "SELECT dt.id, SUM(qad.defects)
				 FROM tbl_qa_reports qa, tbl_po po, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
				 WHERE qa.po_id=po.id AND qa.audit_result!='' AND qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id  $sConditions
				 GROUP BY dt.id
				 ORDER BY dt.id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$iDefects[$objDb->getField($i, 0)] = $objDb->getField($i, 1);		
		
		
/*
		$sSQL = "SELECT qa.id
				 FROM tbl_qa_reports qa, tbl_po po
				 WHERE qa.po_id=po.id AND qa.audit_result!='' AND qa.audit_type='B' $sConditions
				 ORDER BY qa.id DESC";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iAuditId = $objDb->getField($i, "id");

			$sSQL = "SELECT dt.id, SUM(qad.defects)
 			         FROM tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
					 WHERE qad.audit_id='$iAuditId' AND qad.code_id=dc.id AND dc.type_id=dt.id
					 GROUP BY dt.id
					 ORDER BY dt.id";
			$objDb2->query($sSQL);

			$iCount2 = $objDb2->getCount( );

			for ($j = 0; $j < $iCount2; $j ++)
				$iDefects[$objDb2->getField($j, 0)] += $objDb2->getField($j, 1);
		}
*/


		$sData   = array( );
		$sLabels = array( );
		$sColors = array( );
		$sTypes  = "0";

		$sSQL = "SELECT id, type, color FROM tbl_defect_types ORDER BY type";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			if ((int)$iDefects[$objDb->getField($i, 0)] > 0)
			{
				$sLabels[] = $objDb->getField($i, 1);
				$sData[]   = (int)$iDefects[$objDb->getField($i, 0)];
				$sColors[] = hexdec(substr($objDb->getField($i, 2), 1));
				
				$sTypes .= (",".$objDb->getField($i, 0));
			}
		}
?>
				  <input type="hidden" name="Types" value="<?= $sTypes ?>" />
<?
	}
?>
				</div>
			    </form>
			  </td>
			</tr>
		  </table>

<?
	if ($_GET)
	{
		$objChart = new PieChart(920, 400);
		$objChart->setDonutSize(460, 200, 180, 0);
		$objChart->addTitle("Defect Classification", "verdanab.ttf", 11);

		$objChart->setColors2(8, $sColors);
		$objChart->set3D(25);

		$objChart->setData($sData, $sLabels);

		$objChart->setLabelLayout(SideLayout, 40);
		$objChart->setLabelFormat("{label}\n{percent}%");
		$objChart->setLabelStyle("verdana.ttf", 10, 0x000000);

		$sChart = $objChart->makeSession("QualityReport");
?>
		  <br />

		  <div class="tblSheet">
		    <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" />
		  </div>
<?
	}

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