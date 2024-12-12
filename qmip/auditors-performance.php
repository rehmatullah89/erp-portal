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
	@require_once($sBaseDir."scripts/fusion-charts/PHP/FusionCharts.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	$Vendor     = IO::intValue("Vendor");
	$Brand      = IO::intValue("Brand");
	$FromDate   = IO::strValue("FromDate");
	$ToDate     = IO::strValue("ToDate");
	$Auditor    = IO::intValue("Auditor");
	$Unit       = IO::strValue("Unit");
	$ReportType = IO::strValue("ReportType");
	$Floor      = IO::strValue("Floor");

	
	if ($FromDate == "" || $ToDate == "")
	{
		$FromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 28), date("Y")));
		$ToDate   = date("Y-m-d");
	}

	if (@strpos($_SESSION["Brands"], ",") === FALSE)
		$Brand = $_SESSION["Brands"];
	
	if ($Vendor > 0)
		$_SESSION["QmipVendor"] = $Vendor;
	
	else
		$Vendor = $_SESSION["QmipVendor"];	


	$sVendorBrands    = getDbValue("GROUP_CONCAT(DISTINCT(brand_id) SEPARATOR ',')", "tbl_po", "vendor_id='$Vendor'");
	$sAuditStages     = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");

	$sVendorsList     = getList("tbl_vendors", "id", "vendor", "FIND_IN_SET(id, '$sQmipVendors') AND id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList      = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ({$_SESSION['Brands']}) AND id IN ($sVendorBrands)");
	$sAuditStagesList = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')");
    $sReportTypeList  = getList("tbl_reports", "id", "report", "id IN (15, 16, 17, 18, 21, 22)");
	
	if (@in_array($_SESSION["UserType"], array("GLOBALEXPORTS")))
		$sAuditorsList = getList("tbl_users", "id", "name", "status='A' AND auditor='Y' AND user_type='{$_SESSION['UserType']}'");
	
	else
		$sAuditorsList = getList("tbl_users", "id", "name", "status='A' AND auditor='Y'");
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
			    <h1>Auditors Performance</h1>

			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="40">Brand</td>

			          <td width="110">
			            <select name="Brand" style="color: black;" id="VBrand" style="width:150px;">
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
                                  <td width="55">Auditor</td>
			          <td width="160">
					    <select name="Auditor" style="color: black;">
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
					  
                                 <td width="40" style="line-height:18px;">From</td>
					  <td width="78"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30" align="center" style="line-height:18px;">To</td>
					  <td width="78"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="50"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td style="line-height:18px;">[ <a href="#" onclick="clearDates( ); return false;">Clear Dates</a> ]</td>                      
                                  
                                  <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>
				
			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0" width="100%">
				    <tr>
			          <td width="30">Vendor</td>

			          <td width="90">
                                 <select id="Vendor"  style="color: black;" name="Vendor" onchange="getListValues('Vendor', 'Unit', 'VendorUnits');">
                                    <option value=""> All Vendors</option>
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
                                  <td width="18">Unit </td>
				  <td width="90">
						    <select name="Unit"  style="color: black;" id="Unit" onchange="getListValues('Unit', 'Floor', 'UnitFloors');">
							  <option value=""></option>
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
                        <td width="23">Floor</td>
				  <td width="80">
						    <select name="Floor"  style="color: black;" id="Floor">
							  <option value=""></option>
<?
		$sUnitsList = array( );

		if ($Unit > 0)
			$sFloorList = getList("tbl_floors", "id", "floor", "unit_id = '$Unit'");

		foreach ($sFloorList as $sKey => $sValue)
		{
?>
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $Floor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>  
			<td width="52">Report Type </td>
				  <td width="90">
				    <select name="ReportType"  style="color: black;">
        				<option value="">All Report Types</option>
<?
		foreach ($sReportTypeList as $sKey => $sValue)
		{
?>
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $ReportType) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>  
                        <td width="110">&nbsp;</td>
					</tr>
				  </table>
			    </div>					
			    </form>
				

			    <div class="tblSheet">
<?
	if($Vendor > 0)
                $sConditions  = " AND qa.vendor_id='$Vendor' ";

        if($Auditor > 0)
                $sConditions  = " AND qa.user_id='$Auditor' ";

        if($Unit > 0)
                $sConditions  = " AND qa.unit_id='$Unit' ";

        if($ReportType > 0)
                $sConditions  = " AND qa.report_id='$ReportType' ";
        
        if ($Floor > 0)
		$sConditions .= " AND qa.unit_id IN (SELECT id FROM tbl_floors WHERE unit_id='$Unit') ";

        if ($Brand > 0)
		$sConditions .= " AND qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id='$Brand' AND vendor_id='$Vendor') ";

	
            
            
            
            $sSQL = "SELECT (Select name from tbl_users where id=qa.user_id) as Auditor, count(qa.audit_code) as TotalAudits,sum(qa.defective_gmts) as DefectiveInspected, sum(qa.total_gmts) as TotalInspected, (Select report from tbl_reports where id=qa.report_id) as Report, qa.user_id, qa.report_id
			 FROM tbl_qa_reports qa
			 WHERE qa.report_id IN (15, 16, 17, 18, 21, 22) AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') $sConditions
			 GROUP BY qa.user_id, qa.report_id Order By qa.user_id";
            //echo $sSQL;exit;
            $objDb->query($sSQL);
            $iCount = $objDb->getCount( );

?>
</div>

            <div class="content-area">
                <div id="content-area-inner-main">

                    <div class="gen-chart-render">

                        <?
                      
                        $totalInspections = 0;
                        $totalGarmentInspected = 0;
                        $totalGarmentRejected = 0;
                        $previousUser = 0;
                        $j = 0; //first Index
                        $k = 1; // Second Index
                        for ($i = 0; $i < $iCount; $i ++)
                        {
                            $sAuditor            = $objDb->getField($i, "Auditor");
                            $iTotalAudits        = $objDb->getField($i, "TotalAudits");
                            $iTotalInspected     = $objDb->getField($i, "TotalInspected");
                            $iDefectiveInspected = $objDb->getField($i, "DefectiveInspected");
                            $sReport             = $objDb->getField($i, "Report");
                            $iUserId             = $objDb->getField($i, "user_id");
                            $iReportId           = $objDb->getField($i, "report_id");
                            
                            if($previousUser != $iUserId){
                               $previousUser = $iUserId;
                               $arrData[$j][$k] = $sAuditor;
                               
                               $arrData[$j][$k+1] = '';
                               $arrData[$j][$k+2] = '';
                               $arrData[$j][$k+3] = '';
                               $arrData[$j][$k+4] = '';
                               $arrData[$j][$k+5] = '';
                               $arrData[$j][$k+6] = '';
                               $j++;
                            }
                            
                            if($iReportId == 15){
                                $arrData[$j-1][$k+1] = $iTotalInspected;
                            }else if($iReportId == 16){
                                $arrData[$j-1][$k+2] = $iTotalInspected;
                            }else if($iReportId == 17){
                                $arrData[$j-1][$k+3] = $iTotalInspected;
                            }else if($iReportId == 18){
                                $arrData[$j-1][$k+4] = $iTotalInspected;
                            }else if($iReportId == 21){
                                $arrData[$j-1][$k+5] = $iTotalInspected;
                            }else if($iReportId == 22){
                                $arrData[$j-1][$k+6] = $iTotalInspected;
                            }
                            
                            $totalInspections += $iTotalAudits;
                            $totalGarmentRejected += $iDefectiveInspected;
                            $totalGarmentInspected += $iTotalInspected;                            
                        }
                      
                        $sSQL = "SELECT (Select name from tbl_users where id=qa.user_id) as Auditor, sum(qa.total_gmts) as CountAudits
			 FROM tbl_qa_reports qa
			 WHERE qa.report_id IN (15, 16, 17, 18, 21, 22) AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') $sConditions
			 GROUP BY qa.user_id Order By CountAudits DESC";
                        $objDb->query($sSQL);
                        $iCount1 = $objDb->getCount( );
                        $sortedArray = array();
                        for($j=0; $j<$iCount1; $j++){
                            foreach($arrData as $k1 => $v1){
                                if($arrData[$k1][1] == $objDb->getField($j, "Auditor")){
                                    $sortedArray[] = $arrData[$k1]; 
                                }
                            }
                        }
                      
                       //Initialize <chart> element
                        $strXML = "<chart caption='Auditors Performance' numberPrefix='' showValues='0' showSum='0' formatNumberScale='0' labelDisplay='ROTATE'>";

                        //Initialize <categories> element - necessary to generate a stacked chart
                        $strVendorTypes = "<categories>";
            
                        //Initiate <dataset> elements
                        $strVendorCutting = "<dataset seriesName='Vendor Cutting'>";
                        $strVendorRoving = "<dataset seriesName='Vendor Roving'>";
                        $strVendorFinishing = "<dataset seriesName='Vendor Finishing'>";
                        $strVendorFinal = "<dataset seriesName='Vendor Final'>";
                        $strVendorEmbalish = "<dataset seriesName='Vendor Embalishment'>";
                        $strVendorEndLine = "<dataset seriesName='Vendor End Line'>";
                        
                        //Iterate through the data
                        foreach ($sortedArray as $arSubData) {
                            //Append <category name='...' /> to strCategories
                            $strVendorTypes .= "<category name='" . $arSubData[1] . "' />";                           
                            $strVendorCutting .= "<set value='" . $arSubData[2] . "' />";
                            $strVendorRoving .= "<set value='" . $arSubData[3] . "' />";
                            $strVendorFinishing .= "<set value='" . $arSubData[4] . "' />";
                            $strVendorFinal .= "<set value='" . $arSubData[5] . "' />";
                            $strVendorEmbalish .= "<set value='" . $arSubData[6] . "' />";
                            $strVendorEndLine .= "<set value='" . $arSubData[7] . "' />";
                        }

                        //Close <categories> element
                          $strVendorTypes .= "</categories>";

                        //Close <dataset> elements
                        $strVendorCutting .= "</dataset>";
                        $strVendorRoving .= "</dataset>";
                        $strVendorFinishing .= "</dataset>";
                        $strVendorFinal .= "</dataset>";
                        $strVendorEmbalish .= "</dataset>";
                        $strVendorEndLine .= "</dataset>";
             
                        //Assemble the entire XML now
                        $strXML = $strXML . $strVendorTypes . $strVendorCutting . $strVendorRoving . $strVendorFinishing . $strVendorFinal . $strVendorEmbalish . $strVendorEndLine . "</chart>";
                        //Create the chart - Stacked Column 3D Chart with data contained in strXML
                        echo renderChart("scripts/fusion-charts/charts/StackedColumn3D.swf", "", $strXML, "auditorsPerformance", 940, 500, false, false);
                        
                        echo "<span style='margin-left: 90px; font-weight: 900; font-size: 12px;'>Total Inspections:".$totalInspections."</span>";        
                        echo "<span style='margin-left: 80px; font-weight: 900; font-size: 12px;'>Total Garments Inspected:".$totalGarmentInspected."</span>";        
                        echo "<span style='margin-left: 80px; font-weight: 900 ; font-size: 12px;'>Total Garments Rejected:".$totalGarmentRejected."</span>";        
                        
                        ?>

                    </div>
                    <div class="clear"></div>
                    <p>&nbsp;</p>
                    <p class="small"> <!--<p class="small">This dashboard was created using FusionCharts XT, FusionWidgets v3 and FusionMaps v3 You are free to reproduce and distribute this dashboard in its original form, without changing any content, whatsoever. <br />
            &copy; All Rights Reserved</p>
          <p>&nbsp;</p>-->
                    </p>

                    <div class="underline-dull"></div>
                </div>
            </div>

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
<script>
function clearDates( )
{
	$('FromDate').value = "";
	$('ToDate').value   = "";
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