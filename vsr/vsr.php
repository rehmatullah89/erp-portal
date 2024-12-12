<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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
	$objDb3      = new Database( );

	$Region   = IO::intValue("Region");
	$Vendor   = IO::intValue("Vendor");
	$Brand    = IO::intValue("Brand");
	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");
	$Season   = IO::intValue("Season");
	$Tab      = IO::strValue("Tab");

	$Tab = (($Tab == "") ? "Production" : $Tab);

	if ($FromDate == "" || $ToDate == "")
	{
		$FromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 30), date("Y")));
		$ToDate   = date("Y-m-d");
	}

	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id>'0' AND id IN ({$_SESSION['Brands']})");
	$sSeasonsList = getList("tbl_seasons", "id", "season", "parent_id>'0'");

	if (@strpos($_SESSION["Brands"], ",") === FALSE)
		$Brand = $_SESSION["Brands"];

	if ($Brand > 0)
	{
		$iParent      = getDbValue("parent_id", "tbl_brands", "id='$Brand'");
		$sSeasonsList = getList("tbl_seasons", "id", "season", "brand_id='$iParent' AND parent_id>'0'");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/vsr/vsr.js"></script>
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
			    <h1>Vendor Status Report</h1>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <input type="hidden" id="Tab" name="Tab" value="<?= $Tab ?>" />

			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="52">Vendor</td>

			          <td width="200">
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

			          <td width="45">Brand</td>

			          <td width="180">
			            <select name="Brand" id="Brand" onchange="getListValues('Brand', 'Season', 'BrandSeasons');">
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

					  <td width="40">From</td>
					  <td width="78"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30" align="center">To</td>
					  <td width="78"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>

			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr>
					  <td width="50">Region</td>

					  <td width="115">
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

			          <td width="55">Season</td>

			          <td>
			            <select name="Season" id="Season">
			              <option value="">All Seasons</option>
<?
	if ($Brand > 0)
	{
		foreach ($sSeasonsList as $sKey => $sValue)
		{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Season) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
	}
?>
			            </select>
			          </td>
				    </tr>
				  </table>
			    </div>
			    </form>

<?
	$sVendorPos   = "";
	$sBrandStyles = "";
	$sBrandPos    = "";
	$sSeasonSql   = "";
	$sRegionSql   = "";
	$sVendorsSql  = "";


	if ($Tab != "Production")
	{
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

			$sVendorsSql = " AND vendor_id IN ($sVendors) ";
		}


		if ($Vendor > 0)
			$sSQL = "SELECT id FROM tbl_po WHERE vendor_id='$Vendor' $sVendorsSql";

		else
			$sSQL = "SELECT id FROM tbl_po WHERE vendor_id IN ({$_SESSION['Vendors']}) $sVendorsSql";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sVendorPos .= (",".$objDb->getField($i, 0));

		if ($sVendorPos != "")
			$sVendorPos = substr($sVendorPos, 1);


		if ($Season > 0)
			$sSeasonSql = " AND sub_season_id='$Season' ";


		if ($Brand > 0)
			$sSQL = "SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand' AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}') $sSeasonSql";

		else
			$sSQL = "SELECT id FROM tbl_styles WHERE sub_brand_id IN ({$_SESSION['Brands']}) AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}') $sSeasonSql";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sBrandStyles .= (",".$objDb->getField($i, 0));

		if ($sBrandStyles != "")
			$sBrandStyles = substr($sBrandStyles, 1);


		$sSQL = "SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE style_id IN ($sBrandStyles) AND (etd_required BETWEEN '$FromDate' AND '$ToDate')";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sBrandPos .= (",".$objDb->getField($i, 0));

		if ($sBrandPos != "")
			$sBrandPos = substr($sBrandPos, 1);
	}
?>
			    <br style="line-height:4px;" />

			    <table border="0" cellpadding="0" cellspacing="0" width="100%">
				  <tr bgcolor="#494949">
					<td width="201"><input type="button" value="" class="btnProduction<?= (($Tab == 'Production') ? 'Selected' : '') ?>" title="Production" onclick="doSearch('Production');" /></td>
					<td width="159"><input type="button" value="" class="btnQuality<?= (($Tab == 'Quality') ? 'Selected' : '') ?>" title="Quality" onclick="doSearch('Quality');" /></td>
					<td width="216"><input type="button" value="" class="btnDevelopment<?= (($Tab == 'Development') ? 'Selected' : '') ?>" title="Development" onclick="doSearch('Development');" /></td>
					<td width="149"><input type="button" value="" class="btnFabric<?= (($Tab == 'Fabric') ? 'Selected' : '') ?>" title="Fabric" onclick="doSearch('Fabric');" /></td>
					<td width="206"><input type="button" value="" class="btnCompliance<?= (($Tab == 'Compliance') ? 'Selected' : '') ?>" title="Compliance" onclick="doSearch('Compliance');" /></td>
				  </tr>
				</table>

			    <div style="padding-top:6px;">
<?
	if ($Tab == "Production")
	{
?>
			      <div id="Production">
			        <table border="0" cellpadding="0" cellspacing="0" width="931">
			          <tr>
			            <td width="306">
<?
		@include($sBaseDir."includes/vsr/production/otp-last-month.php");
?>
			            </td>

			            <td></td>

			            <td width="306">
<?
		@include($sBaseDir."includes/vsr/production/otp-next-fortnight.php");
?>
			            </td>

			            <td></td>

			            <td width="306">
<?
		@include($sBaseDir."includes/vsr/production/deviation.php");
?>
			            </td>
			          </tr>

			          <tr>
			            <td colspan="5" height="4"></td>
			          </tr>

			          <tr>
			            <td width="306">
<?
		@include($sBaseDir."includes/vsr/production/order-confirmation-performance.php");
?>
			            </td>

			            <td></td>

			            <td width="306">
<?
		@include($sBaseDir."includes/vsr/production/etd-revisions.php");
?>
			            </td>

			            <td></td>

			            <td width="306">
<?
		// Need to optimize
//		@include($sBaseDir."includes/vsr/production/etd-revision-classification.php");


		//@include($sBaseDir."includes/vsr/production/late-delivery.php");
?>
			            </td>
			          </tr>

			          <tr>
			            <td colspan="5" height="60"></td>
			          </tr>
			        </table>
			      </div>
<?
	}


	if ($Tab == "Quality")
	{
		$sConditions = " AND qa.audit_type='B' AND qa.audit_result!='' AND qa.po_id IN ($sVendorPos) AND qa.po_id IN ($sBrandPos) ";

		if ($FromDate != "" && $ToDate != "")
			$sConditions .= " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";

		if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE)
			$sConditions .= " AND qa.style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$_SESSION['Brands']}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')) ";

		else
			$sConditions .= " AND (qa.style_id='0' OR qa.style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$_SESSION['Brands']}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}'))) ";
?>
			      <div id="Quality">
			        <table border="0" cellpadding="0" cellspacing="0" width="931">
			          <tr>
			            <td width="306">
<?
		@include($sBaseDir."includes/vsr/quality/defects-type-classification.php");
?>
			            </td>

			            <td></td>

			            <td width="306">
<?
		@include($sBaseDir."includes/vsr/quality/final-audits-defect-rate-histogram.php");
?>
			            </td>

			            <td></td>

			            <td width="306">
<?
		@include($sBaseDir."includes/vsr/quality/defect-rate-graph.php");
?>
			            </td>
			          </tr>

			          <tr>
			            <td colspan="5" height="4"></td>
			          </tr>

			          <tr>
			            <td width="306">
<?
		$sSQL = "SELECT id, report FROM tbl_reports ORDER BY report LIMIT 0, 1";
		$objDb->query($sSQL);

		$iReportId = $objDb->getField(0, 0);
		$sReport   = $objDb->getField(0, 1);
		$iPosition = 4;

		@include($sBaseDir."includes/vsr/quality/top-5-defect-types.php");
?>
			            </td>

			            <td></td>

			            <td width="306">
<?
		$sSQL = "SELECT id, report FROM tbl_reports ORDER BY report LIMIT 1, 1";
		$objDb->query($sSQL);

		$iReportId = $objDb->getField(0, 0);
		$sReport   = $objDb->getField(0, 1);
		$iPosition = 5;

		@include($sBaseDir."includes/vsr/quality/top-5-defect-types.php");
?>
			            </td>

			            <td></td>

			            <td width="306">
<?
		$sSQL = "SELECT id, report FROM tbl_reports ORDER BY report LIMIT 2, 1";
		$objDb->query($sSQL);

		$iReportId = $objDb->getField(0, 0);
		$sReport   = $objDb->getField(0, 1);
		$iPosition = 6;

		@include($sBaseDir."includes/vsr/quality/top-5-defect-types.php");
?>
			            </td>
			          </tr>

			          <tr>
			            <td colspan="5" height="4"></td>
			          </tr>

			          <tr>
			            <td width="306">
<?
		$sSQL = "SELECT id, report FROM tbl_reports ORDER BY report LIMIT 3, 1";
		$objDb->query($sSQL);

		$iReportId = $objDb->getField(0, 0);
		$sReport   = $objDb->getField(0, 1);
		$iPosition = 7;

		@include($sBaseDir."includes/vsr/quality/top-5-defect-types.php");
?>
			            </td>

			            <td></td>

			            <td width="306">
<?
		$sSQL = "SELECT id, report FROM tbl_reports ORDER BY report LIMIT 4, 1";
		$objDb->query($sSQL);

		$iReportId = $objDb->getField(0, 0);
		$sReport   = $objDb->getField(0, 1);
		$iPosition = 8;

		@include($sBaseDir."includes/vsr/quality/top-5-defect-types.php");
?>
			            </td>

			            <td></td>

			            <td width="306">
<?
		$sSQL = "SELECT id, report FROM tbl_reports ORDER BY report LIMIT 5, 1";
		$objDb->query($sSQL);

		$iReportId = $objDb->getField(0, 0);
		$sReport   = $objDb->getField(0, 1);
		$iPosition = 9;

		@include($sBaseDir."includes/vsr/quality/top-5-defect-types.php");
?>
			            </td>
			          </tr>
			        </table>
			      </div>
<?
	}


	if ($Tab == "Development")
	{
		$sConditions = "";

		$sSQL = "SELECT id FROM tbl_merchandisings WHERE style_id IN ($sBrandStyles)";
		$objDb->query($sSQL);

		$iCount  = $objDb->getCount( );
		$sMerchandisingIds = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sMerchandisingIds .= (",".$objDb->getField($i, 0));

		if ($sMerchandisingIds != "")
			$sMerchandisingIds = substr($sMerchandisingIds, 1);

		$sConditions .= " AND merchandising_id IN ($sMerchandisingIds) ";

		if ($FromDate != "" && $ToDate != "")
			$sConditions .= " AND (DATE_FORMAT(modified, '%Y-%m-%d') BETWEEN '$FromDate' AND '$ToDate') ";

		if ($sConditions != "")
			$sConditions = (" WHERE ".@substr($sConditions, 5));
?>
			      <div id="Development">
			        <table border="0" cellpadding="0" cellspacing="0" width="931">
			          <tr>
			            <td width="306">
<?
		@include($sBaseDir."includes/vsr/development/defects-type-classification.php");
?>
			            </td>

			            <td></td>

			            <td width="306">
<?
		@include($sBaseDir."includes/vsr/development/samples-status-graph.php");
?>
			            </td>

			            <td></td>

			            <td width="306">
<?
		@include($sBaseDir."includes/vsr/development/qrs-hit-rate.php");
?>
			            </td>
			          </tr>

			          <tr>
			            <td colspan="5" height="4"></td>
			          </tr>

			          <tr>
			            <td width="306">
<?
		@include($sBaseDir."includes/vsr/development/lab-dip-stats.php");
?>
			            </td>

			            <td></td>

			            <td width="306">
<?
		@include($sBaseDir."includes/vsr/development/lab-dips-approval-rate.php");
?>
			            </td>

			            <td></td>

			            <td width="306">
<?
		@include($sBaseDir."includes/vsr/development/lab-dips-turn-around-time.php");
?>
			            </td>
			          </tr>
			        </table>
			      </div>
<?
	}


	if ($Tab == "Fabric")
	{
?>
			      <div id="Fabric">
			        <table border="0" cellpadding="0" cellspacing="0" width="931">
			          <tr>
			            <td width="306">
<?
		@include($sBaseDir."includes/vsr/fabric/stats.php");
?>
			            </td>

			            <td></td>

			            <td width="306">
<?
		@include($sBaseDir."includes/vsr/fabric/approval-rate.php");
?>
			            </td>

			            <td></td>

			            <td width="306">
<?
		@include($sBaseDir."includes/vsr/fabric/turn-around-time.php");
?>
			            </td>
			          </tr>
			        </table>
			      </div>
<?
	}


	if ($Tab == "Compliance")
	{
?>
			      <div id="Compliance">
			        <table border="0" cellpadding="0" cellspacing="0" width="931">
			          <tr>
<?
		$objChart = new XYChart(296, 201);

		$objChart->addText(153, 100, "NO DATA AVAILABLE", "arialbd.ttf", 15, 0x999999, Center);

		$sChart = $objChart->makeSession("Graph");
?>
			            <td width="306">
			              <div class="vsrChart">
			                <div class="chart"><img src="requires/get-chart.php?<?= $sChart ?>" border="0" alt="" title="" /></div>
			                <div class="title"></div>

			                <div id="Handle1" class="handle" style="display:block;" onclick="showSummary(1);"></div>

			                <div id="Summary1" class="summary" style="display:none;">
			                  <div>
			                    <div class="text">Chart Description will be placed here</div>
			                    <div class="handle" onclick="hideSummary(1);"></div>
			                  </div>
			                </div>
			              </div>
			            </td>

			            <td></td>
<?
		$objChart = new XYChart(296, 201);

		$objChart->addText(153, 100, "NO DATA AVAILABLE", "arialbd.ttf", 15, 0x999999, Center);

		$sChart = $objChart->makeSession("Graph");
?>
			            <td width="306">
			              <div class="vsrChart">
			                <div class="chart"><img src="requires/get-chart.php?<?= $sChart ?>" border="0" alt="" title="" /></div>
			                <div class="title"></div>

			                <div id="Handle2" class="handle" style="display:block;" onclick="showSummary(2);"></div>

			                <div id="Summary2" class="summary" style="display:none;">
			                  <div>
			                    <div class="text">Chart Description will be placed here</div>
			                    <div class="handle" onclick="hideSummary(2);"></div>
			                  </div>
			                </div>
			              </div>
			            </td>

			            <td></td>
<?
		$objChart = new XYChart(296, 201);

		$objChart->addText(153, 100, "NO DATA AVAILABLE", "arialbd.ttf", 15, 0x999999, Center);

		$sChart = $objChart->makeSession("Graph");
?>
			            <td width="306">
			              <div class="vsrChart">
			                <div class="chart"><img src="requires/get-chart.php?<?= $sChart ?>" border="0" alt="" title="" /></div>
			                <div class="title"></div>

			                <div id="Handle3" class="handle" style="display:block;" onclick="showSummary(3);"></div>

			                <div id="Summary3" class="summary" style="display:none;">
			                  <div>
			                    <div class="text">Chart Description will be placed here</div>
			                    <div class="handle" onclick="hideSummary(3);"></div>
			                  </div>
			                </div>
			              </div>
			            </td>
			          </tr>
			        </table>
			      </div>
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
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>