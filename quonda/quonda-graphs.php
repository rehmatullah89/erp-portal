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

	$Step       = IO::intValue("Step");
	$Sector     = IO::strValue("Sector");
	$Category   = IO::intValue("Category");
	$OrderNo    = IO::strValue("OrderNo");
	$AuditCode  = IO::strValue("AuditCode");
	$Vendor     = IO::intValue("Vendor");
        $Parent     = IO::intValue("Parent");
	$Brand      = IO::intValue("Brand");
	$FromDate   = IO::strValue("FromDate");
	$ToDate     = IO::strValue("ToDate");
	$Color      = IO::strValue("Color");
	$StyleNo    = IO::strValue("StyleNo");
	$Auditor    = IO::intValue("Auditor");
	$MasterId   = IO::strValue("MasterId");
	$AuditorType= IO::intValue("AuditorType");


	if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE || @strpos($_SESSION["Email"], "dkcompany.com") !== FALSE || @strpos($_SESSION["Email"], "hema.nl") !== FALSE ||
		@strpos($_SESSION["Email"], "kcmtar.com") !== FALSE || @strpos($_SESSION["Email"], "mister-lady.com") !== FALSE)
		$_REQUEST['AuditStage'] = "F";

	if ($FromDate == "" || $ToDate == "")
	{
		if (@strpos($_SESSION["Email"], "marksnspencer.com") === FALSE && @strpos($_SESSION["Email"], "dkcompany.com") === FALSE && @strpos($_SESSION["Email"], "hema.nl") === FALSE &&
			@strpos($_SESSION["Email"], "kcmtar.com") === FALSE && @strpos($_SESSION["Email"], "mister-lady.com") === FALSE)
		{
			if ($OrderNo == "" && $AuditCode == "" && $Vendor == "" && $Brand == "" && $Color == "")
			{
				$FromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 3), date("Y")));
				$ToDate   = date("Y-m-d");
			}

			else if ($OrderNo != "" || $AuditCode != "")
			{
				$FromDate = date("Y-m-d", mktime(0, 0, 0, (date("m") - 6), date("d"), date("Y")));
				$ToDate   = date("Y-m-d");
			}
		}
	}

	if (@strpos($_SESSION["Brands"], ",") === FALSE)
		$Brand = $_SESSION["Brands"];


	$sVendorsList  = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList   = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ({$_SESSION['Brands']})");
	$sDefectColors = getList("tbl_defect_types", "id", "color");


	if ($Vendor > 0)
	{
		$sSQL = "SELECT category_id FROM tbl_vendors WHERE id='$Vendor'";
		$objDb->query($sSQL);

		$Category = $objDb->getField(0, 0);
	}

	else if ($Brand > 0)
	{
		$Category = 0;
		$Color    = "";
	}


	$sReportTypes     = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStages     = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStagesList = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')");
	
	
	if (@in_array($_SESSION["UserType"], array("MGF", "CONTROLIST", "GLOBALEXPORTS")))
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
  <script type="text/javascript" src="scripts/quonda/quonda-graphs.js"></script>
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
			    <h1>quonda graphs</h1>

			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="50">PO</td>
			          <td width="130"><input type="text" name="OrderNo" value="<?= $OrderNo ?>" class="textbox" maxlength="50" size="14" /></td>
<?
        if ($_SESSION["UserType"] ==  "MGF")
        {
?> 
					  <td width="65">Master ID</td>
					  <td width="130"><input type="text" name="MasterId" value="<?= $MasterId ?>" class="textbox" maxlength="50" size="14" /></td>
<?
        }

        else
        {
?>                                  
			          <td width="80">Audit Code</td>
			          <td width="130"><input type="text" name="AuditCode" value="<?= $AuditCode ?>" class="textbox" maxlength="50" size="14" /></td>
<?
        }
?>

<?
                if ($_SESSION["UserType"] == "JCREW")
                {
?>
                    <td width="54">Vendor</td>

                    <td width="150">
                      <select name="Parent" id="Parent" style="width:130px;" onchange="getListValues('Parent', 'Vendor', 'ParentVendors');">
                        <option value="">All Vendors</option>
<?
                    $sParentsList = getList ("tbl_vendors v, tbl_factories f", "f.id", "f.parent", "FIND_IN_SET(v.id, f.vendors) AND v.id IN ({$_SESSION['Vendors']})");

                      foreach ($sParentsList as $sKey => $sValue)
                      {
?>
                        <option value="<?= $sKey ?>"<?= (($sKey == $Parent) ? " selected" : "") ?>><?= $sValue ?></option>
<?
                      }
?>
                      </select>
                    </td>
                    
                    <td width="60">Factory</td>

                    <td width="130">
                      <select name="Vendor" id="Vendor" style="width:115px;">
                        <option value="">All Factories</option>
<?
                      if($Parent != 0)
                          $sChildrenList = getList ("tbl_vendors v, tbl_factories f", "v.id", "v.vendor", "FIND_IN_SET(v.id, f.vendors) AND f.id='$Parent'");
                          
                      foreach ($sChildrenList as $sKey => $sValue)
                      {
?>
                        <option value="<?= $sKey ?>"<?= (($sKey == $Vendor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
                      }
?>
                      </select>
                    </td>
<?
                }
                else
                {
?>                                                                    
			          <td width="54">Vendor</td>

			          <td width="150">
			            <select name="Vendor" style="width:135px;">
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
<?
                }
?>
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>

			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr>
			          <td width="50">Style</td>
			          <td width="130"><input type="text" name="StyleNo" value="<?= $StyleNo ?>" class="textbox" maxlength="50" size="14" /></td>
<?
	if ($Category == 8)
	{
?>
					  <td width="88">Article Type</td>

					  <td width="250">
					    <input type="hidden" name="Category" value="<?= $Category ?>" />
					    <input type="hidden" name="Step" value="1" />

					    <select name="Color" style="width:240px;">
					      <option value="">Any Type</option>
<?
		$sSQL = "SELECT DISTINCT(color) FROM tbl_po_colors WHERE po_id IN (SELECT DISTINCT(po_id) FROM tbl_qa_reports WHERE report_id='6' AND po_id > '0') ORDER BY color";
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
?>
					    </select>

					    <script type="text/javascript">
					    <!--
						  document.frmSearch.Color.value = "<?= $Color ?>";
					    -->
					    </script>
					  </td>
<?
	}
	
	else
	{
?>
			          <td width="80">Auditor</td>

			          <td width="130">
					    <select name="Auditor" style="width: 125px;">
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
<?
	}
?>
                                  <td width="54">Brand</td>

			          <td width="100">
			            <select name="Brand" style="width:135px;">
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
<?
        if ($_SESSION["UserType"] ==  "MGF")
        {
?>                                             
					<td width="90" style="line-height:18px;">Auditor Type</td>
					<td width="100">
					<select name="AuditorType">
					<option value="" >All Auditor Types</option>
					<option value="4" <?= (($AuditorType == 4) ? " selected" : "") ?>>MCA</option>
					<option value="5" <?= (($AuditorType == 5) ? " selected" : "") ?>>FCA</option>
					</select>
					</td>  
<?
	}
?>
				    </tr>
				  </table>
			    </div>

				<div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0">
				  <tr>
					  <td width="50">From</td>
					  <td width="100"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:92px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="80" >To</td>
					  <td width="100"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:92px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td>[ <a href="#" onclick="clearDates( ); return false;">Clear Dates</a> ]</td>
					  </tr>
				  </table>
				 </div>
			    </form>

<?
	$sAuditorSQL = "";

	if ($Auditor > 0)
		$sAuditorSQL = " AND qa.user_id='$Auditor' ";
	
	
	if ($Step == 0)
	{
		@include($sBaseDir."includes/quonda/cumulative-graph.php");
		@include($sBaseDir."includes/quonda/audit-status-graph.php");
//		@include($sBaseDir."includes/quonda/style-graph.php");
	}

	else if (($Step > 0 && $Step <= 5) && $Category == 8)
		@include($sBaseDir."includes/quonda/gf-defect-type-graphs.php");

	else if ($Step == 1 && $Sector == "")
	{
		if ($Category == 12)
			@include($sBaseDir."includes/quonda/yarn-graphs.php");

		else
			@include($sBaseDir."includes/quonda/line-wise-graphs.php");
	}

	else
	{
		$AuditStage = IO::strValue("AuditStage");
		$Line       = IO::intValue("Line");
		$Type       = IO::intValue("Type");
		$Code       = IO::intValue("Code");


		$sConditions  = " AND qa.audit_type='B' AND qa.audit_result!='' AND FIND_IN_SET(qa.report_id, '$sReportTypes') "; //  AND NOT FIND_IN_SET(qa.report_id, '$sQmipReports')
		$sConditions .= " AND (qa.style_id='0' OR qa.style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$_SESSION['Brands']}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

		if ($Auditor > 0)
			$sConditions .= " AND qa.user_id='$Auditor' ";
	
		if ($FromDate != "" && $ToDate != "")
			$sConditions .= " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";

		if ($OrderNo != "")
		{
			$sSQL = "SELECT id FROM tbl_po WHERE order_no='$OrderNo'";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );


			$sConditions .= " AND ( ";

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iPoId = $objDb->getField($i, 0);

				if ($i > 0)
					$sConditions .= " OR ";

				$sConditions .= " qa.po_id='$iPoId' OR FIND_IN_SET('$iPoId', qa.additional_pos) ";
			}

			$sConditions .= " ) ";
		}

		if ($StyleNo != "")
		{
			$sConditions .= " AND (";

			$sSQL = "SELECT id FROM tbl_styles WHERE style LIKE '%$StyleNo%'";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iStyleId = $objDb->getField($i, 0);

				if ($i > 0)
					$sConditions .= " OR ";

				$sConditions .= " qa.style_id='$iStyleId' ";
			}

			$sConditions .= ") ";
		}

		if ($Vendor > 0)
			$sConditions .= " AND qa.vendor_id='$Vendor' ";

		else
			$sConditions .= " AND qa.vendor_id IN ({$_SESSION['Vendors']}) ";
                        
                if($Parent > 0 && $Vendor == 0)
                    $sConditions .= " AND vendor_id IN (SELECT DISTINCT vendors from tbl_factories WHERE id='$Parent') ";        

		if ($AuditStage != "")
			$sConditions .= " AND qa.audit_stage='$AuditStage' ";

		else
			$sConditions .= " AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";

		if ($Line > 0)
			$sConditions .= " AND qa.line_id='$Line' ";

		if ($Brand > 0)
		{
			if ($Vendor > 0)
				$sConditions .= " AND qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id='$Brand' AND vendor_id='$Vendor') ";

			else
				$sConditions .= " AND qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id='$Brand' AND vendor_id IN ({$_SESSION['Vendors']})) ";
		}

		else
		{
			if ($Vendor > 0)
				$sConditions .= " AND qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id IN ({$_SESSION['Brands']}) AND vendor_id='$Vendor') ";

			else
				$sConditions .= " AND qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id IN ({$_SESSION['Brands']}) AND vendor_id IN ({$_SESSION['Vendors']})) ";
		}

		if ($AuditCode != "")
			$sConditions .= " AND qa.audit_code LIKE '%$AuditCode%' ";
                
                if($MasterId != "")        
                        $sConditions .= " AND qa.master_id = '$MasterId' ";
                
                if ($AuditorType > 0)
                        $sConditions .= " AND qa.user_id IN (SELECT id from tbl_users WHERE status='A' AND auditor='Y' AND email LIKE '%mgfsourcing.com' AND auditor_type='$AuditorType' ) ";
?>
			    <div class="tblSheet">
<?
		if ($Step == 1 && $Sector != "")
			@include($sBaseDir."includes/quonda/vendor-stage-graph.php");

		else if ($Step >= 2)
			@include($sBaseDir."includes/quonda/vendor-line-graph.php");


		if ($Step >= 2)
			@include($sBaseDir."includes/quonda/defect-type-graph.php");


		if ($Step >= 3)
			@include($sBaseDir."includes/quonda/defect-code-graph.php");


		if ($Step >= 4)
			@include($sBaseDir."includes/quonda/defect-area-graph.php");


		if ($Step == 5)
			@include($sBaseDir."includes/quonda/defect-images.php");
?>
			    </div>
<?
	}

	if ($Category != 8)
		@include($sBaseDir."includes/quonda/qa-summary.php");
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