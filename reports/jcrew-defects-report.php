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


        $Year     = (IO::strValue("Year") != ""?IO::strValue("Year"):date("Y"));
        $Month    = IO::intValue("Month");
	$FromDate = "{$Year}-01-01";
	$ToDate   = "{$Year}-12-31";

	$sReportTypes = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
        $sMonthList   = array(1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December');
        $sReportYearList = getList("tbl_qa_reports", "DISTINCT YEAR(audit_date)", "YEAR(audit_date)", "report_id='46'");

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
			    <h1>j.crew Defects Report</h1>

			    <form name="frmSearch" id="frmSearch" method="post" action="<?= SITE_URL.'reports/export-jcrew-defects-report.php' ?>" class="frmOutline" onsubmit="checkDoubleSubmission( );">
				<h2>J.Crew Defects Reports</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
                                    
				  <tr>
					<td width="80">Region</td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Region" id="Region" onchange="getListValues('Region', 'Vendor', 'RegionVendors');" style="width:300px;">
						<option value="">All Regions</option>
<?
        $sJcrewVendors = getDbValue("vendors", "tbl_brands", "id='526'");
        $sCrewCountries= getDbValue("GROUP_CONCAT(DISTINCT country_id SEPARATOR ',')", "tbl_vendors", "id IN ($sJcrewVendors) AND id IN ({$_SESSION['Vendors']})");
        
	$sSQL = "SELECT id, country FROM tbl_countries WHERE matrix='Y' AND id IN ($sCrewCountries) ORDER BY country";
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

				  <tr valign="top">
					<td>Factory</td>
					<td align="center">:</td>

					<td>
					  <select name="Vendor[]" id="Vendor" multiple size="10" style="width:300px;">
                                              <option value="">All Factories</option>
<?
if(IO::strValue("Region") != "")
{
       $sSQL = "SELECT id, vendor FROM tbl_vendors WHERE id IN ($sJcrewVendors) AND id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y' ORDER BY vendor";
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
						<option value="">All Stages</option>
<?
        $sJcrewStages     = getDbValue("stages", "tbl_reports", "id='46'");
	$sAuditStagesList = getList("tbl_audit_stages", "code", "stage", "code IN ('". implode("','", explode(",", $sJcrewStages))."')");

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
                                    
                                    <tr id="YearId">
					<td>Audit Year</td>
					<td align="center">:</td>

					<td>
                                            <select name="Year" id="Year" style="width:300px;">
<?
                                        foreach($sReportYearList as $sYear)
                                        {
?>
                                                <option value="<?=$sYear?>" <?=($Year == $sYear?'selected':'')?>><?=$sYear?></option>
<?
                                        }
?>
                                            </select>
					</td>
				  </tr>
                                    
                                    <tr id="YearId">
					<td>Audit Month</td>
					<td align="center">:</td>

					<td>
                                            <select name="Month" id="Month" style="width:300px;">
                                                <option value="">All Months</option>
<?
                                        foreach($sMonthList as $iMonth => $sMonth)
                                        {
?>
                                                <option value="<?=$iMonth?>" <?=($Month == $iMonth?'selected':'')?>><?=$sMonth?></option>
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
                                            <select name="ReportType" style="width:300px;" onchange="ToggleYearDate(this.value);">
						<option value="DP">Defects Percentage Report</option>
						<option value="DC">Defects By Category Report</option>
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
<script>
    function ToggleYearDate(val)
    {
        if(val == 'PS')
        {
            document.getElementById("DateId").style.display = "none";
            document.getElementById("YearId").style.display = "";
        }
        else
        {
            document.getElementById("DateId").style.display = "";
            document.getElementById("YearId").style.display = "none";
        }        
    }
</script>
</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>