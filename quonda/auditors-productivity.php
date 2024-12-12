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

	$Auditor    = IO::getArray("Auditor");
	$Vendor     = IO::intValue("Vendor");
	$Brand      = IO::intValue("Brand");
	$AuditStage = IO::strValue("AuditStage");
	$FromDate   = IO::strValue("FromDate");
	$ToDate     = IO::strValue("ToDate");
	$Region     = IO::intValue("Region");


	if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE || @strpos($_SESSION["Email"], "dkcompany.com") !== FALSE || @strpos($_SESSION["Email"], "hema.nl") !== FALSE ||
	    @strpos($_SESSION["Email"], "kcmtar.com") !== FALSE || @strpos($_SESSION["Email"], "mister-lady.com") !== FALSE)
		$AuditStage = "F";


	if ($FromDate == "" || $ToDate == "")
	{
		if (@strpos($_SESSION["Email"], "marksnspencer.com") === FALSE && @strpos($_SESSION["Email"], "dkcompany.com") === FALSE && @strpos($_SESSION["Email"], "hema.nl") === FALSE &&
			@strpos($_SESSION["Email"], "kcmtar.com") === FALSE && @strpos($_SESSION["Email"], "mister-lady.com") === FALSE)
		{
			$FromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 2), date("Y")));
			$ToDate   = date("Y-m-d");
		}
	}

	$sAuditorsList    = getList("tbl_users", "id", "name", "auditor='Y'");
	$sVendorsList     = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList      = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ({$_SESSION['Brands']})");

	$sReportTypes     = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStages     = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStagesList = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
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
			    <h1>auditors productivity</h1>

			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="48">Brand</td>

			          <td width="160">
			            <select name="Brand">
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

			          <td width="55">Vendor</td>

			          <td width="185">
			            <select id="Vendor" name="Vendor" style="width:175px;">
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

			          <td width="82">Audit Stage</td>

			          <td width="110">
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

					  <td width="50">Region</td>

					  <td width="120">
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

			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>

			    <div id="SubSearchBar" style="height:auto; padding:8px;">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr valign="top">
					  <td width="55" style="line-height:15px;">Auditor</td>

					  <td width="200">
					    <select name="Auditor[]" size="10" multiple>
<?
	foreach ($sAuditorsList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Auditor)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
					  </td>

					  <td width="75" style="line-height:15px;">Audit Date</td>
					  <td width="78"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="35" align="center" style="line-height:15px;">To</td>
					  <td width="78"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
				    </tr>
				  </table>
			    </div>
			    </form>

<?
	$sConditions = " WHERE qa.audit_type='B' AND FIND_IN_SET(report_id, '$sReportTypes') AND NOT FIND_IN_SET(report_id, '$sQmipReports') ";
	$sAuditors   = @implode(",", $Auditor);

	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";

	if ($Vendor > 0)
		$sConditions .= " AND qa.vendor_id='$Vendor' ";

	else
		$sConditions .= " AND qa.vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Region > 0)
	{
		$sSQL = "SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y'";
		$objDb->query($sSQL);

		$iCount   = $objDb->getCount( );
		$sVendors = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sVendors .= (",".$objDb->getField($i, 0));

		if ($sVendors != "")
			$sVendors = substr($sVendors, 1);

		$sConditions .= " AND qa.vendor_id IN ($sVendors) ";
	}

	if ($sAuditors != "")
		$sConditions .= " AND qa.user_id IN ($sAuditors) ";

	if ($AuditStage != "")
		$sConditions .= " AND qa.audit_stage='$AuditStage' ";

	else
		$sConditions .= " AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";


	if ($Brand > 0)
		$sSQL = "SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand')";

	else
		$sSQL = "SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id IN ({$_SESSION['Brands']}))";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$sPos   = "";

	for ($i = 0; $i < $iCount; $i ++)
		$sPos .= (",".$objDb->getField($i, 0));

	if ($sPos != "")
		$sPos = substr($sPos, 1);

	$sConditions .= " AND qa.po_id IN ($sPos) ";


	$sData   = array( );
	$sLabels = array( );
	$sColors = array( );

	$sSQL = "SELECT COUNT(*) AS _Total, qa.user_id, SUM(TIME_TO_SEC(TIMEDIFF(end_time, start_time))) AS _Time, SUM(total_gmts) AS _Gmts
	         FROM tbl_qa_reports qa
	         $sConditions
	         GROUP BY qa.user_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iTotalAudits = $objDb->getField($i, "_Total");
		$iAuditorId   = $objDb->getField($i, "user_id");

		$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports qa $sConditions AND qa.user_id='$iAuditorId' AND qa.audit_result!='' AND date_time <= ADDTIME(audit_date, '72:00:00')";
		$objDb2->query($sSQL);

		$iInTimeAudits = $objDb2->getField(0, 0);


 		$sData[]   = @round((($iInTimeAudits / $iTotalAudits) * 100), 2);
		$sLabels[] = $sAuditorsList[$iAuditorId];
		$sColors[] = 0x999999;
	}


	$objChart = new XYChart(920, 580);
	$objChart->setPlotArea(70, 80, 820, 360);

	$objTitle = $objChart->addTitle(("\nAuditors Productivity (".formatDate($FromDate)." to ".formatDate($ToDate).")"), "verdana.ttf", 17);
	$objTitle->setPos(0,0);

	$objBarLayer = $objChart->addBarLayer3($sData, $sColors);
	$objBarLayer->setBarShape(CircleShape);
	$objBarLayer->setAggregateLabelStyle( );
	$objBarLayer->setAggregateLabelFormat("{value}%");

	$objLabels = $objChart->xAxis->setLabels($sLabels);
	$objLabels->setFontAngle(90);

	$objChart->yAxis->setLabelFormat("{value}%");

	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$sChart = $objChart->makeSession("Productivity");
?>
			    <div class="tblSheet">
	  			  <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" border="0" />
			    </div>

			    <hr />

<?
	$sData   = array( );
	$sLabels = array( );
	$sColors = array( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iTotalAudits = $objDb->getField($i, "_Total");
		$iAuditorId   = $objDb->getField($i, "user_id");

 		$sData[]   = $iTotalAudits;
		$sLabels[] = $sAuditorsList[$iAuditorId];
		$sColors[] = 0x999999;
	}



	$objChart = new XYChart(920, 580);
	$objChart->setPlotArea(70, 80, 820, 360);

	$objTitle = $objChart->addTitle(("\nAudit Entries (".formatDate($FromDate)." to ".formatDate($ToDate).")"), "verdana.ttf", 17);
	$objTitle->setPos(0,0);

	$objBarLayer = $objChart->addBarLayer3($sData, $sColors);
	$objBarLayer->setBarShape(CircleShape);
	$objBarLayer->setAggregateLabelStyle( );
	$objBarLayer->setAggregateLabelFormat("{value}");

	$objLabels = $objChart->xAxis->setLabels($sLabels);
	$objLabels->setFontAngle(90);

	$objChart->yAxis->setLabelFormat("{value}");

	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$sChart = $objChart->makeSession("Audits");
?>
			    <div class="tblSheet">
	  			  <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" border="0" />
			    </div>

			    <hr />

<?
	$sData   = array( );
	$sLabels = array( );
	$sColors = array( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iTime      = $objDb->getField($i, "_Time");
		$iAuditorId = $objDb->getField($i, "user_id");

 		$sData[]   = @round(($iTime / 3600), 1);
		$sLabels[] = $sAuditorsList[$iAuditorId];
		$sColors[] = 0x999999;
	}



	$objChart = new XYChart(920, 580);
	$objChart->setPlotArea(70, 80, 820, 360);

	$objTitle = $objChart->addTitle(("\nAudit Time in Hrs (".formatDate($FromDate)." to ".formatDate($ToDate).")"), "verdana.ttf", 17);
	$objTitle->setPos(0,0);

	$objBarLayer = $objChart->addBarLayer3($sData, $sColors);
	$objBarLayer->setBarShape(CircleShape);
	$objBarLayer->setAggregateLabelStyle( );
	$objBarLayer->setAggregateLabelFormat("{value}");

	$objLabels = $objChart->xAxis->setLabels($sLabels);
	$objLabels->setFontAngle(90);

	$objChart->yAxis->setLabelFormat("{value}");

	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$sChart = $objChart->makeSession("Time");
?>
			    <div class="tblSheet">
	  			  <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" border="0" />
			    </div>

			    <hr />

<?
	$sData   = array( );
	$sLabels = array( );
	$sColors = array( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iGmts      = $objDb->getField($i, "_Gmts");
		$iAuditorId = $objDb->getField($i, "user_id");

 		$sData[]   = $iGmts;
		$sLabels[] = $sAuditorsList[$iAuditorId];
		$sColors[] = 0x999999;
	}



	$objChart = new XYChart(920, 580);
	$objChart->setPlotArea(70, 80, 820, 360);

	$objTitle = $objChart->addTitle(("\nAudit Quantity (".formatDate($FromDate)." to ".formatDate($ToDate).")"), "verdana.ttf", 17);
	$objTitle->setPos(0,0);

	$objBarLayer = $objChart->addBarLayer3($sData, $sColors);
	$objBarLayer->setBarShape(CircleShape);
	$objBarLayer->setAggregateLabelStyle( );
	$objBarLayer->setAggregateLabelFormat("{value}");

	$objLabels = $objChart->xAxis->setLabels($sLabels);
	$objLabels->setFontAngle(90);

	$objChart->yAxis->setLabelFormat("{value}");

	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$sChart = $objChart->makeSession("Gmts");
?>
			    <div class="tblSheet">
	  			  <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" border="0" />
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