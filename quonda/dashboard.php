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


	$Filter      = IO::strValue("Filter");
	$Region      = IO::intValue("Region");
	$Vendor      = IO::intValue("Vendor");
        $Parent      = IO::intValue("Parent");
	$Brand       = IO::intValue("Brand");
	$Category    = IO::intValue("Category");
	$FromDate    = IO::strValue("FromDate");
	$ToDate      = IO::strValue("ToDate");
	$Customer    = IO::strValue("Customer");
	$Season      = IO::intValue("Season");
	$Program     = IO::intValue("Program");
	$DesignNo    = IO::strValue("DesignNo");
	$DesignName  = IO::strValue("DesignName");
	$Auditor     = IO::intValue("Auditor");


	if ($Region == 0 && IO::strValue("Type") == "") // @in_array($_SESSION['UserId'], array(1, 2, 3)) &&
	{
		@include("dashboard-new.php");
		exit( );
	}


	$AuditStage  = "";
	$AuditResult = "";


	if (@strpos($_SESSION["Brands"], ",") === FALSE)
		$Brand = $_SESSION["Brands"];

	if ($FromDate != "" && $ToDate == "")
		$ToDate = $FromDate;

	if ($FromDate == "" && $ToDate != "")
		$FromDate = $ToDate;

	if ($Region == 0 && $FromDate == "" && $ToDate == "" && $Vendor == 0 && $Brand == 0 && $Auditor == 0)
	{
	 	if (@strpos($_SESSION["Email"], "apparelco.com") !== FALSE || @strpos($_SESSION["Email"], "3-tree.com") !== FALSE)
	 	{
			$FromDate = date("Y-m-d");
			$ToDate   = date("Y-m-d");
		}
	}


	if ($_SESSION['Guest'] == "Y")
	{
		$sLowestDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 2), date("Y")));

		if (strtotime($FromDate) < strtotime($sLowestDate))
			$FromDate = $sLowestDate;

		if (strtotime($ToDate) < strtotime($sLowestDate))
			$ToDate = $sLowestDate;
	}


	$sRegionsList        = getList("tbl_countries", "id", "country", "matrix='Y'");
	$sVendorsList        = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList         = getList("tbl_brands", "id", "brand", "parent_id>'0' AND id IN ({$_SESSION['Brands']})");
	$sAllBrandsList      = getList("tbl_brands", "id", "brand");
	$sBrandTypesList     = getList("tbl_brands", "id", "type", "parent_id>'0' AND id IN ({$_SESSION['Brands']})");
	$sLocationsList      = getList("tbl_visit_locations", "id", "location");
	$sAuditorsList       = getList("tbl_users", "id", "name");
	$sActiveAuditorsList = getList("tbl_users", "id", "name", "auditor='Y' AND status='A'");
	$sStageColorsList    = getList("tbl_audit_stages", "code", "color");
	$sStageIdsList       = getList("tbl_audit_stages", "code", "id");

	$sReportTypes        = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sReportsList        = getList("tbl_reports", "id", "report", "FIND_IN_SET(id, '$sReportTypes') AND NOT FIND_IN_SET(id, '$sQmipReports')");

	$sAuditStages        = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStagesList    = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')");


	if ($Brand > 0)
	{
		$iParent         = getDbValue("parent_id", "tbl_brands", "id='$Brand'");
		$sCategoriesList = getList("tbl_style_categories", "id", "category", "id IN (SELECT DISTINCT(category_id) FROM tbl_styles WHERE sub_brand_id='$Brand') AND FIND_IN_SET(id, '{$_SESSION['StyleCategories']}')");
	}

	else
		$sCategoriesList = getList("tbl_style_categories", "id", "category", "FIND_IN_SET(id, '{$_SESSION['StyleCategories']}')");
	
	
	$sVendorCountriesList = getList("tbl_vendors", "id", "country_id");
	$sCountryHoursList    = getList("tbl_countries", "id", "hours");	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
<style>

    #SearchBar td li {

        line-height: 13px !important;
    }

    ul.token-input-list-facebook, div.token-input-dropdown-facebook
    {
        width : 180px !important;
    }

    ul.token-input-list-facebook li input
    {
        padding-top: 0px !important;
        padding-bottom: 0px !important;
    }
</style>
  <meta http-equiv="refresh" content="600" />

  <style type="text/css">
  <!--
    v\:* { behavior:url(#default#VML); }
  -->
  </style>

  <script type="text/javascript" src="https://maps.google.com/maps?file=api&v=2&key=<?= GOOGLE_MAPS_KEY ?>"></script>
  <script type="text/javascript" src="scripts/quonda/dashboard.js"></script>
  <script type="text/javascript" src="scripts/jquery.js"></script>
<?
        if ($_SESSION["UserType"] == "MGF")
        {
?>
  <script type="text/javascript" src="scripts/jquery.tokeninput.js"></script>
  <link type="text/css" rel="stylesheet" href="css/jquery.tokeninput.css" />
<?
        }
?>
  <script type="text/javascript">
  <!--
		jQuery.noConflict( );

		jQuery(document).ready(function($)
		{
			setInterval(blinker, 1000);

                 jQuery("#Auditor").tokenInput("ajax/quonda/get-auditors-list.php?Auditor="+jQuery('#Auditor').val(),
                    {
                            queryParam         :  "Auditor",
                            minChars           :  3,
                            tokenLimit         :  1,
                            hintText           :  "Search the Auditor Name",
                            noResultsText      :  "No matching Auditor found",
                            theme              :  "facebook",
                            preventDuplicates  :  true,
                            prePopulate        :  <?= ($Auditor >0?@json_encode(array(array("id" => $Auditor, "name" => getDbValue("name", "tbl_users", "id='$Auditor'")))) : "''")?>
                    });
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
			    <h1>quonda dashboard</h1>

			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
<?
	if (@in_array($_SESSION['UserId'], array(1, 2, 3)))
	{
?>
	  	        		  <input type="hidden" name="Region" value="<?= $Region ?>" />
<?
	}

	else
	{
?>
					  <td width="70">Region</td>

					  <td width="130">
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
<?
	}


    if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE")))
    {
?>
					  <td width="70">Type</td>

					  <td width="130">
					    <select name="Filter">
						  <option value="">All Types</option>
	  	        		  <option value="A"<?= (($Filter == "A") ? " selected" : "") ?>>Production Audits</option>
	  	        		  <option value="S"<?= (($Filter == "S") ? " selected" : "") ?>>Non-Production Activities</option>
	  	        		  <!--<option value="Q"<?= (($Filter == "Q") ? " selected" : "") ?>>QMIP Audits</option>-->
	  	        		  <option value="P"<?= (($Filter == "P") ? " selected" : "") ?>>Pilot Audits</option>
					    </select>
					  </td>
<?
    }
?>
<?
                if ($_SESSION["UserType"] == "JCREW")
                {
?>
                    <td width="70">Vendor</td>

                    <td width="130">
                      <select name="Parent" id="Parent" style="width:125px;" onchange="getListValues('Parent', 'VendorId', 'ParentVendors'); resetDates( );">
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
                    
                    <td width="70">Factory</td>

                    <td width="130">
                      <select name="Vendor" id="VendorId" style="width:115px;">
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
			          <td width="70">Vendor</td>

			          <td width="130">
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
                }
?>
					  
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>

			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0" width="100%">
				    <tr>
			          <td width="70">Brand</td>

			          <td width="130">
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

					  <td width="70"><span id="StartDate"><?= (($Auditor > 0 || $Vendor > 0 || $Brand > 0) ? "From" : "Audit") ?></span> Date</td>
					  <td width="100"><input type="text" name="FromDate" value="<?= ((@strpos($_SESSION["Email"], "apparelco.com") === FALSE && @strpos($_SESSION["Email"], "3-tree.com") === FALSE && $Auditor == 0 && $Vendor == 0 && $Brand == 0 && $FromDate == $ToDate && $FromDate == date("Y-m-d") && !$_GET) ? "" : $FromDate) ?>" id="FromDate" readonly class="textbox" style="width:90px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="40"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
                                          
                                          <td width="70">Auditor</td>
                                            <td id="AuditorTd" width="125"><input type="text" name="Auditor" id="Auditor" value="" class="textbox" size="14" maxlength="200" /></td>        

			          <td>

			            <div id="EndDate" style="display:<?= (($Auditor > 0 || $Vendor > 0 || $Brand > 0) ? "block" : "none") ?>">
			              <table border="0" cellpadding="0" cellspacing="0" width="100%">
			                <tr>
						      <td width="65">To Date</td>
						      <td width="100"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:90px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
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
		if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE")))
		{
?>
                            <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0" width="100%">
				    <tr>
			          <td width="70">Category</td>

			          <td width="130">
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
                                  <td>&nbsp;</td>
                                         
				    </tr>
				  </table>
			    </div>
<?
                }
?> 

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



	if ($Vendor > 0 && $FromDate != "" && $ToDate != "")
		@include($sBaseDir."includes/quonda/vendor-dashboard.php");

	else if ($Auditor > 0 && $FromDate != "" && $ToDate != "")
		@include($sBaseDir."includes/quonda/auditor-dashboard.php");

	else if ((@strpos($_SESSION["Email"], "apparelco.com") === FALSE && @strpos($_SESSION["Email"], "3-tree.com") === FALSE && $Region == 0 && $Auditor == 0 && $Vendor == 0 && $FromDate == "" && $ToDate == "") || IO::strValue("Type") == "Recent")
		@include($sBaseDir."includes/quonda/recent-dashboard.php");

	else if (($Auditor > 0 || $Vendor > 0 || $Brand > 0) && (($FromDate != "" && $ToDate != "" && $FromDate != $ToDate) || ($FromDate == "" && $ToDate == "")))
		@include($sBaseDir."includes/quonda/dates-dashboard.php");

	else
		@include($sBaseDir."includes/quonda/day-dashboard-new.php");
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