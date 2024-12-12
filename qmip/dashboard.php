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

	$Region      = IO::intValue("Region");
	$Vendor      = IO::intValue("Vendor");
	$Brand       = IO::intValue("Brand");
	$Category    = IO::intValue("Category");
	$Auditor     = IO::intValue("Auditor");
	$FromDate    = IO::strValue("FromDate");
	$ToDate      = IO::strValue("ToDate");
	$Customer    = IO::strValue("Customer");
	$Season      = IO::intValue("Season");
	$Program     = IO::intValue("Program");
	$DesignNo    = IO::strValue("DesignNo");
	$DesignName  = IO::strValue("DesignName");
	$AuditStage  = "";
	$AuditResult = "";


	if (@strpos($_SESSION["Email"], "selimpex") !== FALSE)
		$Vendor = 13;
	
	else if (@strpos($_SESSION["Email"], "globalexports") !== FALSE)
		$Vendor = 229;
	
	
	if ($Vendor > 0)
		$_SESSION["QmipVendor"] = $Vendor;
	
	else
		$Vendor = $_SESSION["QmipVendor"];
	
	
	if (@strpos($_SESSION["Brands"], ",") === FALSE)
		$Brand = $_SESSION["Brands"];

	if ($FromDate != "" && $ToDate == "")
		$ToDate = $FromDate;

	if ($FromDate == "" && $ToDate != "")
		$FromDate = $ToDate;

	if ($FromDate == "" && $ToDate == "")
	{
		$FromDate = date("Y-m-d");
		$ToDate   = date("Y-m-d");
	}	


	if ($_SESSION['Guest'] == "Y")
	{
		$sLowestDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 2), date("Y")));

		if (strtotime($FromDate) < strtotime($sLowestDate))
			$FromDate = $sLowestDate;

		if (strtotime($ToDate) < strtotime($sLowestDate))
			$ToDate = $sLowestDate;
	}

	if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE || @strpos($_SESSION["Email"], "dkcompany.com") !== FALSE || @strpos($_SESSION["Email"], "hema.nl") !== FALSE ||
		@strpos($_SESSION["Email"], "kcmtar.com") !== FALSE || @strpos($_SESSION["Email"], "mister-lady.com") !== FALSE)
		$AuditStage = "F";

	if (@strpos($_SESSION["Email"], "dkcompany.com") !== FALSE || @strpos($_SESSION["Email"], "hema.nl") !== FALSE)
		$AuditResult = "P";


	$sRegionsList        = getList("tbl_countries", "id", "country", "matrix='Y'");
	$sVendorsList        = getList("tbl_vendors", "id", "vendor", "FIND_IN_SET(id, '$sQmipVendors') AND id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList         = getList("tbl_brands", "id", "brand", "parent_id>'0' AND id IN ({$_SESSION['Brands']})");
	$sAllBrandsList      = getList("tbl_brands", "id", "brand");
	$sBrandTypesList     = getList("tbl_brands", "id", "type", "parent_id>'0' AND id IN ({$_SESSION['Brands']})");
	$sLocationsList      = getList("tbl_visit_locations", "id", "location");
	$sAuditorsList       = getList("tbl_users", "id", "name");
	$sActiveAuditorsList = getList("tbl_users", "id", "name", "auditor='Y' AND status='A'");
	$sStageColorsList    = getList("tbl_audit_stages", "code", "color");
	$sStageIdsList       = getList("tbl_audit_stages", "code", "id");

	$sReportTypes        = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sReportsList        = getList("tbl_reports", "id", "report", "FIND_IN_SET(id, '$sReportTypes')");

	$sAuditStages        = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStagesList    = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')");


	if ($Brand > 0)
	{
		$iParent         = getDbValue("parent_id", "tbl_brands", "id='$Brand'");
		$sCategoriesList = getList("tbl_style_categories", "id", "category", "id IN (SELECT DISTINCT(category_id) FROM tbl_styles WHERE sub_brand_id='$Brand') AND FIND_IN_SET(id, '{$_SESSION['StyleCategories']}')");
	}

	else
		$sCategoriesList = getList("tbl_style_categories", "id", "category", "FIND_IN_SET(id, '{$_SESSION['StyleCategories']}')");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <meta http-equiv="refresh" content="600" />

  <style type="text/css">
  <!--
    v\:* { behavior:url(#default#VML); }
  -->
  </style>

  <script type="text/javascript" src="http://maps.google.com/maps?file=api&v=2&key=ABQIAAAAkv6SQ09EGMhIja3dzALRjhQiw99WtJE8tPIEcxZZja-pPoKxhRSrzzEJVXkcCP8PGo-wtv9i8kfEng"></script>
  <script type="text/javascript" src="scripts/qmip/dashboard.js"></script>
  <script type="text/javascript" src="scripts/jquery.js"></script>

  <script type="text/javascript">
  <!--
		jQuery.noConflict( );

		jQuery(document).ready(function($)
		{
			setInterval(blinker, 1000);
		});


		function blinker( )
		{
			jQuery('.blink').fadeOut(500);
			jQuery('.blink').fadeIn(500);
		}
  -->
  </script>
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
			    <h1>Dashboard</h1>

			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
					  <td width="50">Region</td>

					  <td width="120">
					    <select name="Region">
						  <option value="">All Regions</option>
<?
	foreach ($sRegionsList as $sKey => $sValue)
	{
?>
	  	        		  <option value="<?= $sKey ?>"<?= (($sKey == $Region) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
					  </td>

			          <td width="54">Vendor</td>

			          <td width="180">
			            <select name="Vendor" id="Vendor" onchange="resetDates( );">
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
	if (@strpos($_SESSION["Email"], "apparelco.com") !== FALSE || @strpos($_SESSION["Email"], "3-tree.com") !== FALSE)
	{
?>
			          <td width="55">Auditor</td>

			          <td width="180">
					    <select name="Auditor" id="Auditor" onchange="resetDates( );">
						  <option value="">All Auditors</option>
<?
		foreach ($sActiveAuditorsList as $sKey => $sValue)
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

			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>

			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0" width="100%">
				    <tr>
			          <td width="45">Brand</td>

			          <td width="220">
					    <select name="Brand" id="Brand" onchange="resetDates( );<? if ($Brand > 0) {?> getListValues('Brand', 'Season', 'BrandSeasons');<?} ?>">
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

			          <td width="65">Category</td>

			          <td width="150">
					    <select name="Category">
						  <option value="">All Categories</option>
<?
	foreach ($sCategoriesList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Category) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
			          </td>

					  <td width="82"><span id="StartDate"><?= (($Auditor > 0 || $Vendor > 0 || $Brand > 0) ? "From" : "Audit") ?></span> Date</td>
					  <td width="78"><input type="text" name="FromDate" value="<?= ((@strpos($_SESSION["Email"], "apparelco.com") === FALSE && @strpos($_SESSION["Email"], "3-tree.com") === FALSE && $Auditor == 0 && $Vendor == 0 && $Brand == 0 && $FromDate == $ToDate && $FromDate == date("Y-m-d") && !$_GET) ? "" : $FromDate) ?>" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="40"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>

			          <td>

			            <div id="EndDate" style="display:<?= (($Auditor > 0 || $Vendor > 0 || $Brand > 0) ? "block" : "none") ?>">
			              <table border="0" cellpadding="0" cellspacing="0" width="100%">
			                <tr>
						      <td width="65">To Date</td>
						      <td width="78"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
						      <td width="30"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
						      <td>[ <a href="#" onclick="$('FromDate').value=''; $('ToDate').value=''; return false;">Clear</a> ]</td>
			                </tr>
			              </table>
			            </div>

			          </td>
				    </tr>
				  </table>
			    </div>

<?
	if ($Brand > 0)
	{
		$sCustomersList = getList("tbl_po", "DISTINCT(customer)", "customer", "brand_id='$Brand' AND vendor_id IN ({$_SESSION['Vendors']}) AND customer!=''");


		if (count($sCustomersList) > 1)
		{
?>
			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0" width="100%">
				    <tr>
			          <td width="70">Customer</td>

			          <td width="200">
					    <select name="Customer" style="width:190px;">
						  <option value="">All Customers</option>
<?
			foreach ($sCustomersList as $sKey => $sValue)
			{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Customer) ? " selected" : "") ?>><?= $sValue ?></option>
<?
			}
?>
					    </select>
			          </td>


			          <td width="55">Season</td>

			          <td width="200">
			            <select name="Season" id="Season" style="width:190px;">
			              <option value="">All Seasons</option>
<?
			$iParent      = getDbValue("parent_id", "tbl_brands", "id='$Brand'");
			$sSeasonsList = getList("tbl_seasons", "id", "season", "brand_id='$iParent' AND parent_id>'0'");

			foreach ($sSeasonsList as $sKey => $sValue)
			{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Season) ? " selected" : "") ?>><?= $sValue ?></option>
<?
			}
?>
			            </select>
			          </td>

			          <td width="65">Program</td>

			          <td width="350">
			            <select name="Program" id="Program" style="width:340px;">
			              <option value="">All Programs</option>
<?
			$sProgramsList = getList("tbl_programs", "id", "program", "", "id");

			foreach ($sProgramsList as $sKey => $sValue)
			{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Program) ? " selected" : "") ?>><?= $sValue ?></option>
<?
			}
?>
			            </select>
			          </td>

			          <td></td>
				    </tr>
				  </table>
			    </div>

<?
		}



		$sDesignNosList   = getList("tbl_styles", "DISTINCT(design_no)", "design_no", "sub_brand_id='$Brand' AND design_no!=''");
		$sDesignNamesList = getList("tbl_styles", "DISTINCT(design_name)", "design_name", "sub_brand_id='$Brand' AND design_name!=''");

		if (count($sDesignNosList) > 1 || count($sDesignNamesList) > 1)
		{
?>
			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0" width="100%">
				    <tr>
<?
			if (count($sDesignNosList) > 1)
			{
?>
			          <td width="70">Design No</td>

			          <td width="200">
					    <select name="DesignNo" style="width:190px;">
						  <option value="">All Design Nos</option>
<?
				foreach ($sDesignNosList as $sKey => $sValue)
				{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $DesignNo) ? " selected" : "") ?>><?= $sValue ?></option>
<?
				}
?>
					    </select>
			          </td>
<?
			}


			if (count($sDesignNamesList) > 1)
			{
?>

			          <td width="85">Design Name</td>

			          <td width="400">
			            <select name="DesignName" style="width:390px;">
			              <option value="">All Design Names</option>
<?
				foreach ($sDesignNamesList as $sKey => $sValue)
				{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $DesignName) ? " selected" : "") ?>><?= $sValue ?></option>
<?
				}
?>
			            </select>
			          </td>
<?
			}
?>
			          <td></td>
				    </tr>
				  </table>
			    </div>
<?
		}
	}
?>
			    </form>


			    <div class="tblSheet" style="position:relative;">
<?
	if ($Category > 0)
		$sStyleCategoriesSql .= " AND category_id='$Category' ";

	else
		$sStyleCategoriesSql .= " AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}') ";


	if ($Season > 0)
		$sStyleCategoriesSql .= " AND sub_season_id='$Season' ";

	if ($Program > 0)
		$sStyleCategoriesSql .= " AND program_id='$Program' ";

	if ($DesignNo != "")
		$sStyleCategoriesSql .= " AND design_no='$DesignNo' ";

	if ($DesignName != "")
		$sStyleCategoriesSql .= " AND design_name='$DesignName' ";



	if ($Vendor > 0 && $FromDate != "" && $ToDate != "" && $_GET)
		@include($sBaseDir."includes/qmip/vendor-dashboard.php");

	else if ($Auditor > 0 && $FromDate != "" && $ToDate != "")
		@include($sBaseDir."includes/qmip/auditor-dashboard.php");

	else if (@strpos($_SESSION["Email"], "apparelco.com") === FALSE && @strpos($_SESSION["Email"], "3-tree.com") === FALSE && $Auditor == 0 && $Vendor == 0 && $FromDate == "" && $ToDate == "" && !$_GET)
		@include($sBaseDir."includes/qmip/recent-dashboard.php");

	else if (($Auditor > 0 || $Vendor > 0 || $Brand > 0) && (($FromDate != "" && $ToDate != "" && $FromDate != $ToDate) || ($FromDate == "" && $ToDate == "")))
		@include($sBaseDir."includes/qmip/dates-dashboard.php");

	else
		@include($sBaseDir."includes/qmip/day-dashboard.php");
?>
			    </div>
			  </td>
			</tr>
		  </table>

		  <br clear="all" />

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