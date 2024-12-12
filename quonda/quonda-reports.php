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

	$Region     = IO::strValue("Region");
	$AuditStage = IO::strValue("AuditStage");
	$Vendor     = IO::intValue("Vendor");
	$Brand      = IO::getArray("Brand");
	$Line       = IO::getArray("Line");
	$Defect     = IO::getArray("Defect");
	$Report     = IO::intValue("Report");
	$FromDate   = IO::strValue("FromDate");
	$ToDate     = IO::strValue("ToDate");
        $Customer   = IO::getArray("Customer");
	$DefectType = IO::intValue("DefectType");
	$DefectCode = IO::intValue("DefectCode");
	$Color      = IO::strValue("Color");
	$Po         = IO::strValue("Po");
	$Auditor    = IO::intValue("Auditor");
	$StyleNo    = IO::strValue("StyleNo");
	$MasterId   = IO::strValue("MasterId");
	$AuditorType= IO::intValue("AuditorType");
	$Styles     = IO::getArray("Styles");

	
	if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE || @strpos($_SESSION["Email"], "dkcompany.com") !== FALSE || @strpos($_SESSION["Email"], "hema.nl") !== FALSE &&
		@strpos($_SESSION["Email"], "kcmtar.com") !== FALSE || @strpos($_SESSION["Email"], "mister-lady.com") !== FALSE)
		$AuditStage = "F";

	if (!$_GET && ($FromDate == "" || $ToDate == ""))
	{
		if (@strpos($_SESSION["Email"], "marksnspencer.com") === FALSE && @strpos($_SESSION["Email"], "dkcompany.com") === FALSE && @strpos($_SESSION["Email"], "hema.nl") === FALSE &&
			@strpos($_SESSION["Email"], "kcmtar.com") === FALSE && @strpos($_SESSION["Email"], "mister-lady.com") === FALSE)
		{
			$FromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 5), date("Y")));
			$ToDate   = date("Y-m-d");
		}
	}

	// if (count($Brand) == 0)
	// 	$Brand = @explode(",", $_SESSION['Brands']);


	$sVendorsList       = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList        = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ({$_SESSION['Brands']})");
	$sDefectColors      = getList("tbl_defect_types", "id", "color");

	$sReportTypes       = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sReportsList       = getList("tbl_reports", "id", "report", "FIND_IN_SET(id, '$sReportTypes')");

	$sAuditStages       = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStagesList   = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')");
        
        $sCustomersList     = getList("tbl_customers", "customer", "customer", "brand_id='364'");
	
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
  <script type="text/javascript" src="scripts/quonda/quonda-reports.js"></script>
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
			    <h1>Quonda Reports</h1>

			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
					  <td width="90">Region</td>

					  <td width="130">
					    <select name="Region">
						  <option value="">All Regions</option>
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

<?
	if (@strpos($_SESSION["Email"], "marksnspencer.com") === FALSE && @strpos($_SESSION["Email"], "dkcompany.com") === FALSE && @strpos($_SESSION["Email"], "hema.nl") === FALSE &&
		@strpos($_SESSION["Email"], "kcmtar.com") === FALSE)
	{
?>
			          <td width="80">Audit Stage</td>

			          <td width="130">
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
<?
	}
?>

			          <td width="80">Vendor</td>

			          <td width="135">
<?
        if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE")))
        {
?>                                         
			            <select id="Vendor" name="Vendor" onchange="getLines('Vendor', 'Line');" style="width:175px;">
<?
        }else{
?>
                                    <select id="Vendor" name="Vendor" style="width:175px;">    
<?
        }
?>   
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
        if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE")))
        {
?>  
			          <td width="25">PO</td>
			          <td width="120"><input type="text" name="Po" value="<?= $Po ?>" class="textbox" size="13" /></td>
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
			          <td width="90">Auditor</td>

			          <td width="130">
					    <select name="Auditor" style="width: 90%;">
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
	if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE")))
	{
?>   
					  <td width="80">Article Type</td>

					  <td width="130">
					    <select name="Color" style="width:92%;">
					      <option value="">Any Type</option>
<?
		$sSQL = "SELECT DISTINCT(color)
				 FROM tbl_po_colors
				 WHERE po_id IN (SELECT DISTINCT(po_id)
								 FROM tbl_qa_reports
								 WHERE report_id='6' AND po_id > '0'
									   AND po_id IN (SELECT id FROM tbl_po WHERE FIND_IN_SET(brand_id, '{$_SESSION['Brands']}') AND FIND_IN_SET(vendor_id, '{$_SESSION['Vendors']}'))
									   AND vendor_id IN ({$_SESSION['Vendors']}))
				 ORDER BY color";
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
	
	else if ($_SESSION["UserType"] == "MGF")
	{
?>
                    <td width="80">Style</td>
					<td width="130"><input type="text" name="StyleNo" value="<?= $StyleNo ?>" class="textbox" maxlength="50" size="12" /></td>
<?
	}	
?>
			          <td width="80">Report Type</td>

			          <td width="135">
					    <select name="Report" style="width: 90%;">
						  <option value="">All Report Types</option>
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
                                  <td width="1"></td>
                                <td width="40" style="line-height:18px;">Style</td>

			          <td width="130">

			            <select name="Styles[]" style="width:98px;" id="Style">
                                        <option value="">All Styles</option>
<?
        $sStyles = array();
        $sBrands = implode(",", $Brand);

        $sSQL = "SELECT DISTINCT po.styles as _STYLE FROM tbl_qa_reports qa, tbl_po po WHERE qa.po_id = po.id AND po.styles != '' AND qa.brand_id IN($sBrands) ";

        $objDb->query($sSQL);

        $iCount = $objDb->getCount( );

        for ($i = 0; $i < $iCount; $i ++){

          array_push($sStyles, $objDb->getField($i, "_STYLE"));
        }

        $sAStyles = implode(",", $sStyles);
        $stylesArray = explode(",", $sAStyles);
        $stylesFilterArray = array();
        $styleString = "";

        foreach ($stylesArray as $styleId) {
          
          if(!in_array($styleId, $stylesFilterArray)){

            $styleString .= $styleId.", ";
            
            array_push($stylesFilterArray, $styleId);

          }
        }

        $styleString = rtrim($styleString,", ");

        $sStylesList  = getList("tbl_styles", "id", "CONCAT(style, ' (',(select season from tbl_seasons where id=tbl_styles.sub_season_id),')')", "id IN ($styleString)");
        
  foreach($sStylesList as $id => $sStyle)
  {
  	if($sStyle == "")
  		continue;  	
?>
                  <option value="<?= $id ?>" <?= ((@in_array($id, $Styles)) ? " selected" : "") ?>><?= $sStyle ?></option>
<?
  }
?>
            </select>
			          </td>  
				      <td></td>
				    </tr>
				  </table>
			    </div>

			    <div id="SubSearchBar" style="height:auto; padding-top:5px; padding-bottom:5px;">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr valign="top">
<?
        if ($_SESSION["UserType"] == "MGF")
        {
?>                                             
						<td width="90" style="line-height:18px;">Auditor Type</td>
						<td width="130">
						<select name="AuditorType">
							<option value="" >All Auditor Types</option>
							<option value="4" <?= (($AuditorType == 4) ? " selected" : "") ?>>MCA</option>
							<option value="5" <?= (($AuditorType == 5) ? " selected" : "") ?>>FCA</option>
						</select>
						</td>  

					<td width="80" style="line-height:18px;">Master ID</td>
					<td width="130"><input type="text" name="MasterId" value="<?= $MasterId ?>" class="textbox" maxlength="50" size="12" /></td>
<?
	}
?>
					  <td width="90" style="line-height:18px;">Audit Date</td>
					  <td width="100"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:92px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="80" style="line-height:18px;" align="center">To</td>
					  <td width="100"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:92px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td style="line-height:18px;">[ <a href="#" onclick="$('FromDate').value=''; $('ToDate').value=''; return false;">Clear</a> ]</td>
                                          <td></td>
                                          
				    </tr>
				  </table>
			    </div>

			    <div id="SubSearchBar" style="height:auto; padding-top:5px; padding-bottom:5px;">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr valign="top">
			          <td width="90" style="line-height:18px;">Defects</td>

			          <td width="130">
			            <select id="Defect" name="Defect[]" multiple size="8" style="width:130px;">
						  <option value="0"<?= ((@in_array("0", $Defect)) ? " selected" : "") ?>>Minor</option>						
	  	        		  <option value="1"<?= ((@in_array("1", $Defect)) ? " selected" : "") ?>>Major</option>
						  <option value="2"<?= ((@in_array("2", $Defect)) ? " selected" : "") ?>>Critical</option>
			            </select>
			          </td>
<?
        if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE")))
        {
?>  
			          <td width="80" style="line-height:18px;">Line</td>

			          <td width="130">
			            <select id="Line" name="Line[]" multiple size="8" style="width:130px;">
<?
	$sSQL = "SELECT id, line FROM tbl_lines WHERE vendor_id='$Vendor' ORDER BY line";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

            for ($i = 0; $i < $iCount; $i ++)
            {
		$sKey   = $objDb->getField($i, 0);
		$sValue = $objDb->getField($i, 1);
?>
	  	        		  <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Line)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
            }
?>
			            </select>
			          </td>
<?
	}
?>
			          <td width="80" style="line-height:18px;">Brand</td>

			          <td width="130">
			            <select name="Brand[]" id="Brand" multiple size="8" style="width:130px;" onchange="getPoStyles('Brand', 'Style');">
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
                                  
                                  <td width="65" id="CustomerTd" style="<?=(in_array('365', $Brand)?'':'display:none;')?>">Customer</td>
                                          <td style="line-height:18px;" width="120">
                                              <select id="CustomerSelect" name="Customer[]" multiple="" size="8" style="<?=(in_array('365', $Brand)?'':'display:none;')?> width:200px;">
                                              <option value=""></option>
<?
                                        foreach($sCustomersList as $sCustomer)
                                        {
?>
                                              <option value="<?=$sCustomer?>" <?=in_array($sCustomer, $Customer)?'selected':''?>><?=$sCustomer?></option>
<?
                                        }
?>
                                          </select>
                                      </td>
		          
					  <td></td>
				    </tr>
				  </table>
			    </div>
			    </form>

			    <div class="tblSheet">
<?
//AND qa.audit_type='B'
	$sConditions = " AND qa.audit_result!='' AND FIND_IN_SET(qa.report_id, '$sReportTypes') "; //  AND NOT FIND_IN_SET(qa.report_id, '$sQmipReports')
	$sColorSQL   = "";
	$sAuditorSQL = "";
	
	if ($Auditor > 0)
		$sAuditorSQL = " AND qa.user_id='$Auditor' ";

	if ($Report > 0)
		$sConditions .= " AND qa.report_id='$Report' ";

	if ($FromDate !=  "" && $ToDate != "")
		$sConditions .= " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";

	if ($Po != "")
	{
		$sSQL = "SELECT id FROM tbl_po WHERE order_no='$Po'";
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
	
	if ($Auditor > 0)
		$sConditions .= " AND qa.user_id='$Auditor' ";

	if ($Vendor > 0)
		$sConditions .= " AND qa.vendor_id='$Vendor' ";

	else
		$sConditions .= " AND qa.vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Region > 0)
		$sConditions .= " AND qa.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	if ($AuditStage != "")
		$sConditions .= " AND qa.audit_stage='$AuditStage' ";

	else
		$sConditions .= " AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";

	if (count($Line) > 0 && @implode(",", $Line) != "")
		$sConditions .= " AND qa.line_id IN (".@implode(",", $Line).") ";


	if ($Color != "")
		$sConditions .= " AND qa.po_id IN (SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE color='$Color') ";

        if (!empty($Customer))
		$sConditions .= (" AND qa.po_id IN (SELECT id FROM tbl_po WHERE customer IN ('". implode("','", $Customer)."')) ");
        
	if (count($Brand) > 0)
	{
		if ($Vendor > 0)
			$sConditions .= (" AND qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id IN (".@implode(",", $Brand).") AND vendor_id='$Vendor') ");

		else
			$sConditions .= (" AND qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id IN (".@implode(",", $Brand).") AND vendor_id IN ({$_SESSION['Vendors']}) ) ");
	}

	else
	{
		if ($Vendor > 0)
			$sConditions .= " AND qa.po_id IN (SELECT id FROM tbl_po WHERE FIND_IN_SET(brand_id, '{$_SESSION['Brands']}') AND vendor_id='$Vendor') ";

		else
			$sConditions .= " AND qa.po_id IN (SELECT id FROM tbl_po WHERE FIND_IN_SET(brand_id, '{$_SESSION['Brands']}') AND FIND_IN_SET(vendor_id, '{$_SESSION['Vendors']}')) ";
	}

        if (!empty($Styles))
        {
                $sStyles = @trim(implode(",", $Styles));

                if($sStyles != "")
                    $sConditions .= " AND qa.style_id IN($sStyles)";
        }        

        if($MasterId != "")        
                $sConditions .= " AND qa.master_id = '$MasterId' ";
        
        if ($AuditorType > 0)
            $sConditions .= " AND qa.user_id IN (SELECT id from tbl_users WHERE status='A' AND auditor='Y' AND user_type='MGF' AND auditor_type='$AuditorType' ) ";
        
	$sDefectsSql = "";
	
	if (count($Defect) > 0 && @implode(",", $Defect) != "")
		$sDefectsSql = " AND qad.nature IN (".@implode(",", $Defect).") ";
	
	if ($DefectCode > 0)
		@include($sBaseDir."includes/quonda/defect-area-classification-graph.php");

	else if ($DefectType > 0)
		@include($sBaseDir."includes/quonda/defect-code-classification-graph.php");

	else
	{
		@include($sBaseDir."includes/quonda/defect-type-classification-graph.php");
?>
			      <hr />
<?
		@include($sBaseDir."includes/quonda/defects-classification-graph.php");
?>
			      <hr />
<?
		@include($sBaseDir."includes/quonda/final-audits-defect-rate-histogram.php");
	}
?>
			      <hr />
<?
	@include($sBaseDir."includes/quonda/defect-rate-graph.php");

	if ($sBackUrl != "")
	{
?>

				  <hr style="margin-top:0px;" />
				  &nbsp; [ <b><a href="<?= $sBackUrl ?>">Back</a></b> ]<br />
				  <br />
<?
	}
?>
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
<!--  Bottom Bar Section Ends Here  -->

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>