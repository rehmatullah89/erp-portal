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


	$FromDate = date("Y-m-01");
	$ToDate   = date("Y-m-d");

	$sReportTypes = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStages = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
        //	$sVendors = getDbValue("GROUP_CONCAT(DISTINCT(vendor_id) SEPARATOR ',')", "tbl_po", "FIND_IN_SET(brand_id, '{$_SESSION['Brands']}')");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/reports/mgf-report.js"></script>
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
			    <h1>MGF Report</h1>

			    <form name="frmSearch" id="frmSearch" method="post" action="<?= SITE_URL.'reports/export-mgf-report.php' ?>" class="frmOutline" onsubmit="checkDoubleSubmission( );">
				<h2>MGF Reports</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr valign="top">
					<td width="80">Brand</td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Brand[]" id="Brand" multiple size="10" style="width:300px;">
<?
	$sSQL = "SELECT id, brand FROM tbl_brands WHERE parent_id>'0' AND id IN ({$_SESSION['Brands']}) ORDER BY brand";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, "id");
		$sValue = $objDb->getField($i, "brand");
?>
	            		<option value="<?= $sKey ?>"><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr valign="top">
					<td>Vendor</td>
					<td align="center">:</td>

					<td>
					  <select name="Vendor[]" multiple size="10" style="width:300px;">
<?
        $sVendors = "";
        $sSQL = "SELECT vendor_id FROM tbl_po WHERE FIND_IN_SET(brand_id, '{$_SESSION['Brands']}')";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
            $sVendors .= ($objDb->getField($i, "vendor_id").",");
        
        $sVendors = rtrim($sVendors, ",");


	$sSQL = "SELECT id, vendor FROM tbl_vendors WHERE id IN ({$_SESSION['Vendors']}) AND id IN ($sVendors) AND parent_id='0' AND sourcing='Y' ORDER BY vendor";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, "id");
		$sValue = $objDb->getField($i, "vendor");
?>
	  	        		<option value="<?= $sKey ?>"><?= $sValue ?></option>
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
					  <select name="Region" style="width:300px;">
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
	  	        		<option value="<?= $sKey ?>"><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Audit Stage</td>
					<td align="center">:</td>

					<td>
					  <select name="AuditStage" style="width:300px;">
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
					<td>Auditor</td>
					<td align="center">:</td>

					<td>
					  <select name="Auditor" style="width:300px;">
						<option value=""></option>
<?
	$sAuditorsList = getList("tbl_users", "id", "name", "auditor='Y' AND status='A'");
	
	if (@strpos($_SESSION["Email"], "mgfsourcing.com") !== FALSE)
		$sAuditorsList = getList("tbl_users", "id", "name", "status='A' AND auditor='Y' AND email LIKE '%mgfsourcing.com'");

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

				  <tr>
					<td>Audit Result</td>
					<td align="center">:</td>

					<td>
					  <select name="AuditResult" style="width:300px;">
						<option value=""></option>
						<option value="P">Pass</option>
						<option value="F">Fail</option>
						<option value="H">Hold</option>
					  </select>
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
						  <td align="right"><!--[ <a href="./" onclick="$('FromDate').value=''; $('ToDate').value=''; return false;">Clear</a> ]--></td>
						</tr>
					  </table>

					</td>
				  </tr>
                                    <tr>
					<td>Report Type</td>
					<td align="center">:</td>

					<td>
					  <select name="ReportType" style="width:300px;">
						<option value="QS">Quality Summary Report</option>
						<option value="AP">Auditor's Performance Report</option>
					  </select>
					</td>
				  </tr> 
				</table>

				<br />

				<div class="buttonsBar">
				  <input type="submit" value="" id="BtnExport" class="btnExport" title="Export" />
				</div>
			    </form>
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