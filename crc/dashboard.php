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

	$Brand  = IO::intValue("Brand");
	$Vendor = IO::intValue("Vendor");
	$Region = IO::intValue("Region");
	$Type   = IO::strValue("Type");


	if (@strpos($_SESSION["Brands"], ",") === FALSE)
		$Brand = $_SESSION["Brands"];

	$sRegionsList = getList("tbl_countries", "id", "country", "matrix='Y'");
	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id>'0' AND id IN ({$_SESSION['Brands']})");

	if ($Brand > 0)
	{
		$sVendors    = getDbValue("vendors", "tbl_brands", "id='$Brand'");
		$sConditions = "";

		if ($sVendors != "")
			$sConditions .= " AND id IN ($sVendors) ";

		if ($Region > 0)
			$sConditions .= " AND country_id='$Region' ";

		$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y' {$sConditions}");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/fusion-charts/FusionCharts.js"></script>
  <script type="text/javascript" src="scripts/fusion-charts/FusionChartsExportComponent.js"></script>
  <script type="text/javascript" src="scripts/crc/dashboard.js"></script>
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
			    <h1>Vendor Management Dashboard</h1>


			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="45">Brand</td>

			          <td width="175">
			            <select name="Brand" id="Brand" onchange="getBrandRegionVendors('Brand', 'Region', 'Vendor');">
			              <option value="">- Select -</option>
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

					  <td width="50">Region</td>

					  <td width="120">
					    <select name="Region" id="Region" onchange="getBrandRegionVendors('Brand', 'Region', 'Vendor');">
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

			          <td width="52">Vendor</td>

			          <td width="190">
			            <select name="Vendor" id="Vendor" style="width:180px;">
			              <option value="">- Select -</option>
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

			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>

			    </form>




<?
	if ($Brand > 0 && $Vendor == 0)
	{
?>
				<h1><?= $sBrandsList[$Brand] ?></h1>

        		<div id="chartdiv" align="center">Loading chart ...</div>

        		<script type="text/javascript">
        		<!--
            		var chart = new FusionCharts("scripts/fusion-charts/charts/MSLine.swf", "ChartId", "100%", "600", "0", "0");

					chart.setXMLData('<chart caption="Vendors Comparison" yAxisMinValue="0" yAxisMaxValue="100" numberSuffix="%" lineThickness="2" showValues="0" showLabels="1" formatNumberScale="0" canvasBorderThickness="1" canvasBorderColor="cccccc" anchorRadius="4" divLineAlpha="20" divLineColor="CC3300" divLineIsDashed="1" showAlternateHGridColor="1" alternateHGridColor="a6a6a6" shadowAlpha="80" labelStep="1" numVDivLines="5" chartRightMargin="35" bgColor="FFFFFF" alternateHGridAlpha="5" showLegend="1" legendPosition="BOTTOM ">' +
									 '<categories >' +
									 	'<category label="Safety" />' +
									 	'<category label="Quality" />' +
									 	'<category label="Production" />' +
									 '</categories>' +

<?
	foreach ($sVendorsList as $iVendor => $sVendor)
	{
?>
									 '<dataset seriesName="<?= $sVendor ?>">' +
<?
		$fSafety = getDbValue("COALESCE(rating, '0')", "tbl_safety_audits", "vendor_id='$iVendor'", "id DESC");

		if ($fSafety == 0)
		{
?>
									 	'<set link="<?= SITE_URL ?>crc/dashboard.php?Brand=<?= $Brand ?>&Vendor=<?= $iVendor ?>&Type=Safety" />' +
<?
		}

		else
		{
?>
									 	'<set value="<?= @round($fSafety, 2) ?>" link="<?= SITE_URL ?>crc/dashboard.php?Brand=<?= $Brand ?>&Vendor=<?= $iVendor ?>&Type=Safety" />' +
<?
		}


		$fQuality = getDbValue("COALESCE(rating, '0')", "tbl_quality_audits", "vendor_id='$iVendor'", "id DESC");

		if ($fQuality == 0)
		{
?>
									 	'<set link="<?= SITE_URL ?>crc/dashboard.php?Brand=<?= $Brand ?>&Vendor=<?= $iVendor ?>&Type=Quality" />' +
<?
		}

		else
		{
?>
									 	'<set value="<?= @round($fQuality, 2) ?>" link="<?= SITE_URL ?>crc/dashboard.php?Brand=<?= $Brand ?>&Vendor=<?= $iVendor ?>&Type=Quality" />' +
<?
		}



		$fProduction = getDbValue("COALESCE(rating, '0')", "tbl_production_audits", "vendor_id='$iVendor'", "id DESC");

		if ($fProduction == 0)
		{
?>
									 	'<set link="<?= SITE_URL ?>crc/dashboard.php?Brand=<?= $Brand ?>&Vendor=<?= $iVendor ?>&Type=Production" />' +
<?
		}

		else
		{
?>
									 	'<set value="<?= @round($fProduction, 2) ?>" link="<?= SITE_URL ?>crc/dashboard.php?Brand=<?= $Brand ?>&Vendor=<?= $iVendor ?>&Type=Production" />' +
<?
		}
?>
									 '</dataset>' +
<?
	}
?>
									 '<trendlines>' +
									 	'<line startvalue="0" endValue="40" displayValue="Critical" color="BC9F3F" isTrendZone="1" showOnTop="0" alpha="25" valueOnRight="1" />' +
									 	'<line startvalue="40" endValue="60" displayValue="Warning" color="894D1B" isTrendZone="1" showOnTop="0" alpha="10" valueOnRight="1" />' +
									 '</trendlines>' +
									 '</chart>');

				   chart.render("chartdiv");
				-->
				</script>

				<br />

			    <div class="tblSheet" style="padding-bottom:1px;">
			      <h2 style="margin-bottom:1px; margin-right:1px;"><?= $sBrandsList[$Brand] ?> Vendors</h2>

			      <ul>

<?
		foreach ($sVendorsList as $iVendor => $sVendor)
		{
?>
			        <li><a href="crc/dashboard.php?Brand=<?= $Brand ?>&Vendor=<?= $iVendor ?>"><?= $sVendor ?></a></li>
<?
		}
?>
			      <ul>
			    </div>
<?
		$sSQL = "SELECT * FROM tbl_crc_reports WHERE brand_id='$Brand' AND report!='' ORDER BY id DESC";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount > 0)
		{
			$sClass              = array("evenRow", "oddRow");
			$sCertificationsList = getList("tbl_certifications", "id", "title");
?>
			    <br />

			    <div class="tblSheet" style="padding-bottom:1px;">
			      <h2 style="margin-bottom:1px; margin-right:1px;">Documents</h2>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="10%">#</td>
				      <td width="80%">Report Title</td>
				      <td width="10%" class="center">Download</td>
				    </tr>
<?
			for ($i = 0; $i < $iCount; $i ++)
			{
				$sTitle  = $objDb->getField($i, 'title');
				$sReport = $objDb->getField($i, 'report');
?>
				    <tr class="<?= $sClass[($i % 2)] ?>" valign="top">
				      <td width="8%"><?= ($i + 1) ?></td>
				      <td width="20%"><?= $sTitle ?></td>

				      <td width="12%" class="center">
<?
				if ($sReport != "" && @file_exists($sBaseDir.CRC_REPORTS_DIR.$sReport))
				{
?>
				        <a href="crc/download-report.php?File=<?= $sReport ?>"><img src="images/icons/download.gif" width="16" height="16" alt="Download" title="Download" /></a>
<?
				}
?>
				      </td>
				    </tr>
<?
			}
?>
				  </table>
			    </div>

			    <br />
<?
		}
	}



	else if ($Vendor > 0)
	{
		$bGraph = false;
?>
				<h1><?= $sVendorsList[$Vendor] ?></h1>

				<table border="0" width="100%" cellspacing="0" cellpadding="0">
				  <tr>
				    <td width="91"><div style="background:#eeeeee; border:solid 1px #aaaaaa; padding:1px;"><a href="crc/view-vendor-profile.php?Id=<?= $Vendor ?>" class="lightview" title="<?= $sVendorsList[$Vendor] ?> :: :: width: 600, height: 460"><img src="images/crc/factory-profile.png" width="90" height="64" alt="" title="" /></a></div></td>

				    <td>
				      <div class="carousel">
				        <ul style="list-style:none; padding:0px; margin:0px; height:68px; overflow:hidden;">
<?
		$sSQL = "SELECT caption, picture FROM tbl_vendor_profile_pictures WHERE vendor_id='$Vendor' ORDER BY id";
		$objDb->query($sSQL);

		$iCount    = $objDb->getCount( );
		$bPictures = false;

		if ($iCount > 0)
		{
			for ($i = 0; $i < $iCount; $i ++)
			{
				$sCaption = $objDb->getField($i, 0);
				$sPicture = $objDb->getField($i, 1);

				if ($sPicture == "" || !@file_exists($sBaseDir.VENDOR_PICS_IMG_PATH."/thumbs/".$sPicture) || !@file_exists($sBaseDir.VENDOR_PICS_IMG_PATH."/enlarged/".$sPicture))
					continue;
?>
				          <li style="float:left; margin:0px 0px 0px 5px; border:solid 1px #aaaaaa; padding:1px; height:64px;">
				            <a href="<?= ($sBaseDir.VENDOR_PICS_IMG_PATH."/enlarged/".$sPicture) ?>" class="lightview" rel="gallery" title="<?= $sCaption ?> :: :: autosize:true :: :: topclose: true"><img src="<?= ($sBaseDir.VENDOR_PICS_IMG_PATH."/thumbs/".$sPicture) ?>" width="96" height="64" alt="<?= $sCaption ?>" title="<?= $sCaption ?>" /></a>
				          </li>
<?
				$bPictures = true;
			}
		}
?>
				        </ul>
				      </div>
				    </td>
				  </tr>
				</table>

<?
		if ($bPictures == true)
		{
?>
			    <script type="text/javascript" src="scripts/jquery.js"></script>
			    <script type="text/javascript" src="scripts/jquery.jcarousellite.js"></script>

				<script type="text/javascript">
				<!--
					jQuery.noConflict( );

					jQuery(document).ready(function($)
					{
						$(".carousel").jCarouselLite(
						{
							auto      :  2000,
							speed     :  1000,
							circular  :  true,
							visible   :  8
						});
					});
				-->
				</script>
<?
		}
?>
				<br />

<?
		$sSQL = "SELECT * FROM tbl_vendors WHERE id='$Vendor'";
		$objDb->query($sSQL);

		$sProfile                    = $objDb->getField(0, "profile");
		$sAddress                    = $objDb->getField(0, "address");
		$sDateOfFoundation           = $objDb->getField(0, 'date_of_foundation');
		$sProductRange               = $objDb->getField(0, 'product_range');
		$sOwnership                  = $objDb->getField(0, 'ownership');
		$sProductionCapability       = $objDb->getField(0, 'production_capability');
		$sFactoryArea                = $objDb->getField(0, 'factory_area');
		$sProductionCapacity         = $objDb->getField(0, 'production_capacity');
		$sStitchingMachines          = $objDb->getField(0, 'stitching_machines');
		$sActiveCustomers            = $objDb->getField(0, 'active_customers');
		$sApprovedCustomers          = $objDb->getField(0, 'approved_customers');
		$iPermanentEmployees         = $objDb->getField(0, 'permanent_employees');
		$sCertifications             = $objDb->getField(0, 'certifications');
		$sThirdPartyComplianceAudits = $objDb->getField(0, 'third_party_compliance_audits');
		$sAnnualTurnoverVolume       = $objDb->getField(0, 'annual_turnover_volume');
		$sAnnualTurnoverValue        = $objDb->getField(0, 'annual_turnover_value');


		if ($sProfile != "")
		{
?>
			    <div class="tblSheet" style="padding-bottom:1px;">
			      <h2 style="margin-bottom:1px; margin-right:1px;">Vendor Brief</h2>

			      <div style="padding:15px; line-height:18px;"><?= nl2br($sProfile) ?></div>
			    </div>

			    <br />
<?
		}
?>

			    <div class="tblSheet" style="padding-bottom:1px;">
			      <h2 style="margin-bottom:1px; margin-right:1px;">Vendor Profile</h2>

				  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
					<tr>
					  <td bgcolor="<?= ODD_ROW_COLOR ?>" width="200">Date of Foundation</td>
					  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $sDateOfFoundation ?></td>
					</tr>

					<tr valign="top">
					  <td bgcolor="<?= ODD_ROW_COLOR ?>">Location</td>
					  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= nl2br($sAddress) ?></td>
					</tr>

					<tr>
					  <td bgcolor="<?= ODD_ROW_COLOR ?>">Product Range</td>
					  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $sProductRange ?></td>
					</tr>

					<tr>
					  <td bgcolor="<?= ODD_ROW_COLOR ?>">Ownership</td>
					  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $sOwnership ?></td>
					</tr>

					<tr valign="top">
					  <td bgcolor="<?= ODD_ROW_COLOR ?>">Production Capability</td>
					  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= nl2br($sProductionCapability) ?></td>
					</tr>

					<tr>
					  <td bgcolor="<?= ODD_ROW_COLOR ?>">Factory/Construction Area</td>
					  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $sFactoryArea ?></td>
					</tr>

					<tr valign="top">
					  <td bgcolor="<?= ODD_ROW_COLOR ?>">Production Capacity</td>
					  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $sProductionCapacity ?></td>
					</tr>

					<tr>
					  <td bgcolor="<?= ODD_ROW_COLOR ?>">Total Stitching Machines</td>
					  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $sStitchingMachines ?></td>
					</tr>

					<tr valign="top">
					  <td bgcolor="<?= ODD_ROW_COLOR ?>">Active Customers</td>
					  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= nl2br($sActiveCustomers) ?></td>
					</tr>

					<tr valign="top">
					  <td bgcolor="<?= ODD_ROW_COLOR ?>">Approved Customers</td>
					  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= nl2br($sApprovedCustomers) ?></td>
					</tr>

					<tr>
					  <td bgcolor="<?= ODD_ROW_COLOR ?>">Permanent Employees</td>
					  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= formatNumber($iPermanentEmployees, false) ?></td>
					</tr>

					<tr>
					  <td bgcolor="<?= ODD_ROW_COLOR ?>">Certifications</td>
					  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $sCertifications ?></td>
					</tr>

					<tr>
					  <td bgcolor="<?= ODD_ROW_COLOR ?>">3rd Party Compliance Audits</td>
					  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $sThirdPartyComplianceAudits ?></td>
					</tr>

					<tr>
					  <td bgcolor="<?= ODD_ROW_COLOR ?>">Annual Turnover (volume)</td>
					  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $sAnnualTurnoverVolume ?></td>
					</tr>

					<tr>
					  <td bgcolor="<?= ODD_ROW_COLOR ?>">Annual Turnover (value)</td>
					  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $sAnnualTurnoverValue ?></td>
					</tr>
				  </table>
				</div>


				<br />

<?
		$sSQL = "SELECT * FROM tbl_vendor_certifications WHERE vendor_id='$Vendor' AND certificate!='' ORDER BY id DESC";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount > 0)
		{
			$sClass              = array("evenRow", "oddRow");
			$sCertificationsList = getList("tbl_certifications", "id", "title");
?>
			    <div class="tblSheet" style="padding-bottom:1px;">
			      <h2 style="margin-bottom:1px; margin-right:1px;">Certifications</h2>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="10%">#</td>
				      <td width="50%">Certification</td>
				      <td width="15%">From Date</td>
				      <td width="15%">To Date</td>
				      <td width="10%" class="center">Download</td>
				    </tr>
<?
			for ($i = 0; $i < $iCount; $i ++)
			{
				$iVendor        = $objDb->getField($i, 'vendor_id');
				$iCertification = $objDb->getField($i, 'certificate_id');
				$sCertificate   = $objDb->getField($i, 'certificate');
				$sFromDate      = $objDb->getField($i, 'from_date');
				$sToDate        = $objDb->getField($i, 'to_date');
?>
				    <tr class="<?= $sClass[($i % 2)] ?>" valign="top">
				      <td width="8%"><?= ($i + 1) ?></td>
				      <td width="20%"><?= $sCertificationsList[$iCertification] ?></td>
				      <td width="15%"><?= formatDate($sFromDate) ?></td>
				      <td width="15%"><?= formatDate($sToDate) ?></td>

				      <td width="12%" class="center">
<?
				if ($sCertificate != "" && @file_exists($sBaseDir.VENDOR_CERTIFICATIONS_DIR.$sCertificate))
				{
?>
				        <a href="<?= VENDOR_CERTIFICATIONS_DIR.$sDir.$sCertificate ?>"><img src="images/icons/pdf.gif" width="16" height="16" alt="PDF" title="PDF" /></a>
<?
				}
?>
				      </td>
				    </tr>
<?
			}
?>
				  </table>
			    </div>

			    <br />
<?
		}




		$iComplianceAudit = getDbValue("id", "tbl_compliance_audits", "vendor_id='$Vendor'", "id DESC");
		$iComplianceScore = @round(getDbValue("AVG(IF(rating='1', '80', IF(rating='2', '79', IF(rating='3', '60', '40'))))", "tbl_compliance_audit_details", "audit_id='$iComplianceAudit'"));


		$iQualityAudit = getDbValue("id", "tbl_quality_audits", "vendor_id='$Vendor'", "id DESC");
		$iQualityScore = @round(getDbValue("AVG(IF(rating='1', '100', IF(rating='2', '75', IF(rating='3', '50', '25'))))", "tbl_quality_audit_details", "audit_id='$iQualityAudit'"));


		if ($iComplianceAudit > 0 && $iQualityAudit > 0)
		{
?>
						  <div>
						    <h2 style="margin:0px;">Overall Vendor Evaluation</h2>

							<div id="RadarChart">
							  <div id="VendorRadarChart">loading...</div>
							</div>
						  </div>

						  <br />

							<script type="text/javascript">
							<!--
								var objChart = new FusionCharts("scripts/fusion-charts/power-charts/Radar.swf", "VendorSummary", "100%", "452", "0", "1");


								objChart.setXMLData("<chart caption='' bgColor='FFFFFF' radarFillColor='FFFFFF' plotFillAlpha='40' plotBorderThickness='0' anchorAlpha='100' numVDivLines='10' formatNumberScale='0' showValues='0' showLabels='1' labelDisplay='ROTATE' showLegend='0' chartBottomMargin='0' legendPosition='BOTTOM'>" +
													"<categories>" +
													"<category label='On-Time Delivery' />" +
													"<category label='Compliance' />" +
													"<category label='Development Capacity' />" +
													"<category label='Production Capacity' />" +
													"<category label='Quality' />" +
													"</categories>" +

													"<dataset seriesname='' color='f1882c' anchorSides='10' anchorBorderColor='b05506' anchorBgAlpha='0' anchorRadius='5'>" +
													"<set value='50' />" +
													"<set value='<?= $iComplianceScore ?>' />" +
													"<set value='50' />" +
													"<set value='50' />" +
													"<set value='<?= $iQualityScore ?>' />" +
													"</dataset>" +
													"</chart>");


								objChart.render("VendorRadarChart");
							-->
							</script>
<?
			$bGraph = true;
		}








		$sSQL = "SELECT * FROM tbl_compliance_audits WHERE vendor_id='$Vendor' ORDER BY id DESC LIMIT 1";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
		{
			$iAudit     = $objDb->getField(0, "id");
			$iAuditType = $objDb->getField(0, "type_id");

			$sAuditType = getDbValue("title", "tbl_compliance_types", "id='$iAuditType'");


			$sSections = array("Workforce Management" => array("Hiring Practices"                => "2,3",
															   "Factory Documentation"           => "1,7",
															   "Workers/Management Relationship" => "4,5",
															   "Work Hours"                      => "6",
															   "Total Compensation"              => "8,9"),

							   "HSE Management"       => array("Safety"                          => "10,13,14,15,20",
															   "Health"                          => "16,17,21,22",
															   "Environment"                     => "11,12,18,19"));


			$fScores   = array( );
			$fAvgScore = 0;

			foreach ($sSections as $sSection => $sSubSections)
			{
				$fSubScore = 0;


				foreach ($sSubSections as $sSubSection => $sQuestions)
				{
					$fScore     = getDbValue("AVG(IF(rating='1', '80', IF(rating='2', '79', IF(rating='3', '60', '40'))))", "tbl_compliance_audit_details", "audit_id='$iAudit' AND FIND_IN_SET(question_id, '$sQuestions')");
					$fScore     = @round($fScore, 2);

					$fSubScore += $fScore;

					$fScores[$sSubSection] = $fScore;
				}


				$fSubScore         /= count($sSubSections);
				$fScores[$sSection] = $fSubScore;
			}


			$fAvgScore = @round(getDbValue("AVG(IF(rating='1', '80', IF(rating='2', '79', IF(rating='3', '60', '40'))))", "tbl_compliance_audit_details", "audit_id='$iAudit'"), 2);
?>
						    <h2 style="margin:0px;">Compliance Footprint</h2>

						  <div>
							<div style="border:solid 1px #cccccc;">
								<div id="SummaryChart">
								  <div id="ComplianceSummaryChart">loading...</div>
								</div>

								<div id="DetailedChart" style="position:relative; display:none;">
								  <div id="ComplianceDetailedChart">loading...</div>

								  <div style="position:absolute; right:10px; top:10px;"><a href="#" onclick="showSummary( ); return false;"><b>&lt; Back</b></a></div>
								</div>
							 </div>
						  </div>

							<br />

						<script type="text/javascript">
						<!--
							var objChart = new FusionCharts("scripts/fusion-charts/charts/MSColumnLine3D.swf", "ComplianceSummary", "100%", "450", "0", "1");

							objChart.setXMLData("<chart caption='' numVDivLines='10' yAxisMinValue='0' yAxisMaxValue='100' formatNumberScale='0' showValues='0' showLabels='1' labelDisplay='ROTATE' showLegend='0' chartBottomMargin='0' legendPosition='BOTTOM'>" +
												"<categories>" +
<?
			foreach ($sSections as $sSection => $sSubSections)
			{
				foreach ($sSubSections as $sSubSection => $sQuestions)
				{
?>
												"<category label='<?= $sSubSection ?>' />" +
<?
				}
			}
?>
												"</categories>" +

												"<dataset seriesName='Score'>" +
<?
			foreach ($fScores as $sSection => $fScore)
			{
				$sColor = "#f1882c";

				if ($fScore >= 80)
					$sColor = "#555655";

				else if ($fScore >= 61)
					$sColor = "#878887";

				else if ($fScore >= 41)
					$sColor = "#f8b171";
?>
												"<set value='<?= $fScore ?>' color='<?= $sColor ?>' link='javaScript:showDetails(\"<?= $iAudit ?>\", \"<?= $sSection ?>\");' />" +
<?
  			}
?>
												"</dataset>" +

												"<trendlines>" +
												"  <line toolText='Average Score: (<?= $fAvgScore ?>%)' startValue='<?= $fAvgScore ?>' displayValue='Avg. <?= $fAvgScore ?>' color='#0000ff' />" +
												"</trendlines>" +
												"</chart>");


								objChart.render("ComplianceSummaryChart");



								function showSummary( )
								{
										$("DetailedChart").hide( );
										$('SummaryChart').show( );
								}


								function showDetails(iAudit, sSection)
								{
									var sUrl    = "ajax/libs/compliance-chart.php";
									var sParams = ("Audit=" + iAudit + "&Section=" + sSection);


									$('Processing').show( );

									new Ajax.Request(sUrl, { method:'post', parameters:sParams, onSuccess:_showDetails });
								}


								function _showDetails(sResponse)
								{
									if (sResponse.status == 200 && sResponse.statusText == "OK")
									{
										$('SummaryChart').hide( );
										$("DetailedChart").show( );


										var objChart = new FusionCharts("scripts/fusion-charts/charts/MSColumnLine3D.swf", "ComplianceDetailed" + Math.random( ), "100%", "450", "0", "1");

										objChart.setXMLData(sResponse.responseText);
										objChart.render("ComplianceDetailedChart");


										$('Processing').hide( );
									}
								}
							-->
							</script>
<?
			$bGraph = true;
		}





		if ($Type == "Safety" || $Type == "")
		{
			$sSQL = "SELECT id, audit_date FROM tbl_safety_audits WHERE vendor_id='$Vendor' ORDER BY id DESC LIMIT 1";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 1)
			{
				$iAudit = $objDb->getField(0, "id");
				$sDate  = $objDb->getField(0, "audit_date");


				$sCategories = getList("tbl_safety_categories", "id", "title");
				$fScores     = array( );

				foreach ($sCategories as $iCategory => $sCategory)
				{
					$sQuestions = getDbValue("GROUP_CONCAT(id SEPARATOR ',')", "tbl_safety_questions", "category_id='$iCategory'");

					$fScore              = getDbValue("AVG(IF(rating='1', '80', IF(rating='2', '79', IF(rating='3', '60', '40'))))", "tbl_safety_audit_details", "audit_id='$iAudit' AND FIND_IN_SET(question_id, '$sQuestions')");
					$fScores[$iCategory] = @round($fScore, 2);
				}


				$fAvgScore = @round(getDbValue("AVG(IF(rating='1', '80', IF(rating='2', '79', IF(rating='3', '60', '40'))))", "tbl_safety_audit_details", "audit_id='$iAudit'"), 2);

				$sColor = "#f1882c";

				if ($fAvgScore >= 80)
					$sColor = "#878887";

				else if ($fAvgScore >= 61)
					$sColor = "#bfbfbf";

				else if ($fAvgScore >= 41)
					$sColor = "#f8b171";
?>
						    <div id="SafetyChart" style="position:relative; border:solid 1px #cccccc;">
						      <div style="height:400px; position:relative;">
						        <div style="height:400px; border-bottom:solid 5px <?= $sColor ?>; padding-left:200px;">
						          <table border="0" cellspacing="0" cellpadding="0" width="100%">
						            <tr height="400" valign="bottom">
<?
				foreach ($fScores as $iCategory => $fScore)
				{
					$sColor = "#f1882c";

					if ($fScore >= 80)
						$sColor = "#878887";

					else if ($fScore >= 61)
						$sColor = "#bfbfbf";

					else if ($fScore >= 41)
						$sColor = "#f8b171";
?>
						              <td align="center"><div style="width:25px; height:<?= @round($fScore * 4) ?>px; background:<?= $sColor ?>; cursor:pointer;"  onclick="Lightview.show({href:'<?=SITE_URL?>/crc/view-safety-audit.php?Id=<?= $iAudit ?>&Category=<?= $iCategory ?>' , rel:'iframe', options: { width: 800, height: 600 }});"></div></td>
<?
				}
?>
						            </tr>
						          </table>
						        </div>

						        <h1 style="position:absolute; left:0px; top:0px; right:0px; background:none; border-top:solid 3px #444444; color:#222222; padding:8px 0px 0px 10px; height:auto; line-height:24px; font-size:24px;">
						          Safety Footprint<br />
						          <span style="font-size:18px; font-weight:none; float:left;">(<?= formatDate($sDate) ?>)</span>
						        </h1>
						      </div>

<?
				$sColor = "#f1882c";

				if ($fAvgScore >= 80)
					$sColor = "#878887";

				else if ($fAvgScore >= 61)
					$sColor = "#bfbfbf";

				else if ($fAvgScore >= 41)
					$sColor = "#f8b171";
?>
							  <div style="margin:0px 0px 0px 0px;">
							    <table border="0" cellspacing="0" cellpadding="0" width="100%">
								  <tr bgcolor="#dddddd">
								    <td width="200"><div style="padding:7px 0px 7px 0px; font-size:17px; text-align:center; background:<?= $sColor ?>; font-weight:bold;"><?= @round($fAvgScore, 1) ?>%</div></td>
<?
				foreach ($fScores as $iCategory => $fScore)
				{
?>
								    <td align="center" style="padding:8px 0px 8px 0px; font-size:17px;"><?= @round($fScore, 1) ?>%</td>
<?
				}
?>
								  </tr>

								  <tr>
								    <td width="200"></td>
								    <td colspan="<?= count($fScores) ?>" height="10"></td>
								  </tr>

								  <tr>
								    <td width="200"></td>
<?
				$sIcons = getList("tbl_safety_categories", "id", "icon");

				foreach ($fScores as $iCategory => $fScore)
				{
?>
								    <td align="center"><img src="images/crc/<?= $sIcons[$iCategory] ?>" width="48" height="48" alt="<?= $sCategories[$iCategory] ?>" title="<?= $sCategories[$iCategory] ?>" style="cursor:pointer;" onclick="showAuditPics(<?= $iCategory ?>);" /></td>
<?
				}
?>
								  </tr>

								  <tr valign="top">
								    <td width="200"></td>
<?
				foreach ($fScores as $iCategory => $fScore)
				{
?>
								    <td align="center"><?= $sCategories[$iCategory] ?></td>
<?
				}
?>
								  </tr>
							    </table>
							  </div>

							  <br />
						    </div>


						    <script type="text/javascript" src="scripts/html5.js"></script>
						    <script type="text/javascript" src="scripts/jquery.js"></script>
						    <script type="text/javascript" src="scripts/timeline/js/storyjs-embed.js"></script>

							<script type="text/javascript">
							<!--
								function showAuditPics(iCategory)
								{
									$("SafetyPics").style.display = "block";

<?
				foreach ($sCategories as $iCategory => $sCategory)
				{
?>
									$("SafetyCategory<?= $iCategory ?>").style.display = "none";
<?
				}
?>

									$("SafetyCategory" + iCategory).style.display = "block";


									if (jQuery("#timeline" + iCategory).length == 1)
									{
										jQuery(".timeline div div").remove( );

										createStoryJS(
										{
											type               :  'timeline',
											width              :  '100%',
											height             :  '650',
											source             :  ('crc/dashboard.json.php?Vendor=<?= $Vendor ?>&Category=' + iCategory),
											embed_id           :  ('timeline' + iCategory),
											debug              :  false,
											hash_bookmark      :  false,
											start_at_end       :  false,
											start_zoom_adjust  :  -2
										});
									}
								}
							-->
							</script>


						    <div id="SafetyPics" style="border:solid 1px #aaaaaa; padding:1px; margin-top:20px; display:none;">
<?
				foreach ($sCategories as $iCategory => $sCategory)
				{
					$sSQL = "SELECT title, question_id, `date`, picture FROM tbl_audit_pictures WHERE vendor_id='$Vendor' AND category_id='$iCategory' ORDER BY question_id, title, `date`";
					$objDb->query($sSQL);

					$iCount = $objDb->getCount( );
?>
						      <div id="SafetyCategory<?= $iCategory ?>" style="display:none;">
						        <h3><?= $sCategory ?></h3>

<?
					if ($iCount == 0)
					{
?>
							    <br />
							    &nbsp; No Audit Pictures Available<br />
							    <br />
<?
					}

					else
					{
?>
				                <div class="timeline">
				                  <div id="timeline<?= $iCategory ?>"></div>
				                </div>

						        <br />
<?
					}
?>
						      </div>
<?
				}
?>
						    </div>


						    <br />

							<script type="text/javascript">
							<!--
								jQuery.noConflict( );

								jQuery(document).ready(function($)
								{
									jQuery("img.media-image").live("click", function( )
									{
										var sImage = jQuery(this).attr("src");
										var sTitle = jQuery(this).parent( ).parent( ).parent( ).parent( ).parent( ).find(".container h3 a").text( );

										if (sImage.indexOf("default.jpg") == -1)
										{
											Lightview.show({ href    : sImage,
															 rel     : "image",
															 title   : sTitle,
															 options :  { autosize:true, topclose:false }
														   });
										}
									});
								});
							-->
							</script>

							<style type="text/css">
							<!--
								#storyjs .container h2.start, .vco-storyjs .container h3, .vco-timeline .vco-navigation .timenav .content .marker .flag .flag-content h3
								{
								  background  :  none !important;
								}
							-->
							</style>
<?
				$bGraph = true;
			}
		}







		if ($Type == "Quality" || $Type == "")
		{
			$sSQL = "SELECT * FROM tbl_quality_audits WHERE vendor_id='$Vendor' ORDER BY id DESC LIMIT 1";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 1)
			{
				$iAudit = $objDb->getField(0, "id");
				$sDate  = $objDb->getField(0, "audit_date");


				$sAreasList = getList("tbl_quality_areas", "id", "title", "", "position");
				$iIndex     = 0;
				$sGrades    = array( );

				foreach ($sAreasList as $iArea => $sArea)
				{
					$sSQL = "SELECT SUM(IF(qad.rating='1', '1', '0')) AS _GradeA,
									SUM(IF(qad.rating='2', '1', '0')) AS _GradeB,
									SUM(IF(qad.rating='3', '1', '0')) AS _GradeC,
									SUM(IF(qad.rating='4', '1', '0')) AS _GradeD
							 FROM tbl_quality_audit_details qad, tbl_quality_points qp
							 WHERE qad.audit_id='$iAudit' AND qad.point_id=qp.id AND qp.area_id='$iArea'";
					$objDb->query($sSQL);

					$iGradeA = $objDb->getField(0, "_GradeA");
					$iGradeB = $objDb->getField(0, "_GradeB");
					$iGradeC = $objDb->getField(0, "_GradeC");
					$iGradeD = $objDb->getField(0, "_GradeD");

					$iTotalGrades = ($iGradeA + $iGradeB + $iGradeC + $iGradeD);
					$fGradeA      = @round((($iGradeA / $iTotalGrades) * 100), 2);
					$fGradeB      = @round((($iGradeB / $iTotalGrades) * 100), 2);
					$fGradeC      = @round((($iGradeC / $iTotalGrades) * 100), 2);
					$fGradeD      = @round((($iGradeD / $iTotalGrades) * 100), 2);
					$fTotalGrades = round(($fGradeA + $fGradeB + $fGradeC + $fGradeD), 2);

					$fOverallGrade = @round(($fGradeA + $fGradeB), 2);

					$bPass     = (($iGradeC > 0 || $iGradeD > 0) ? false : true);
					$sComments = '<span style="color:#5f91a8;">No Values Entered in the Technical Evaluation Sheet; Please Revise</span>';

					if ($iTotalGrades > 0)
					{
						if ($bPass == true)
							$sComments = '<span style="color:#00a3dc;">Evaluation Passed</span>';

						else
							$sComments = '<span style="color:#a8a9ad;">Immediate Action Required; Re-Evaluation Necessary</span>';
					}


					$sGrades[$iArea]["A"] = $fGradeA;
					$sGrades[$iArea]["B"] = $fGradeB;
					$sGrades[$iArea]["C"] = $fGradeC;
					$sGrades[$iArea]["D"] = $fGradeD;

					$iIndex ++;
				}


				$sSQL = "SELECT SUM(IF(rating='1', '1', '0')) AS _GradeA,
								SUM(IF(rating='2', '1', '0')) AS _GradeB,
								SUM(IF(rating='3', '1', '0')) AS _GradeC,
								SUM(IF(rating='4', '1', '0')) AS _GradeD
						 FROM tbl_quality_audit_details
						 WHERE audit_id='$iAudit'";
				$objDb->query($sSQL);

				$iGradeA = $objDb->getField(0, "_GradeA");
				$iGradeB = $objDb->getField(0, "_GradeB");
				$iGradeC = $objDb->getField(0, "_GradeC");
				$iGradeD = $objDb->getField(0, "_GradeD");

				$iTotalGrades = ($iGradeA + $iGradeB + $iGradeC + $iGradeD);
				$fGradeA      = @round((($iGradeA / $iTotalGrades) * 100), 2);
				$fGradeB      = @round((($iGradeB / $iTotalGrades) * 100), 2);
				$fGradeC      = @round((($iGradeC / $iTotalGrades) * 100), 2);
				$fGradeD      = @round((($iGradeD / $iTotalGrades) * 100), 2);


				$iMaxGrade = $iGradeA;

				if ($iMaxGrade < $iGradeB)
					$iMaxGrade = $iGradeB;

				if ($iMaxGrade < $iGradeC)
					$iMaxGrade = $iGradeC;

				if ($iMaxGrade < $iGradeD)
					$iMaxGrade = $iGradeD;


				$sColor = "#f1882c";
				$sGrade = "d";

				if ($iMaxGrade == $iGradeA && $fGradeA >= 85)
				{
					$sColor = "#555655";
					$sGrade = "a";
				}

				else if (($iMaxGrade == $iGradeA && $fGradeA < 85) || ($iMaxGrade == $iGradeB))
				{
					$sColor = "#878887";
					$sGrade = "b";
				}

				else if ($iMaxGrade == $iGradeC)
				{
					$sColor = "#f8b171";
					$sGrade = "c";
				}
?>
						    <div style="position:relative; border:solid 1px #cccccc;">
						      <div style="position:relative;">
						        <div style="border-bottom:solid 5px <?= $sColor ?>;">
						          <table border="0" cellspacing="0" cellpadding="0" width="100%">
						            <tr>
<?
				foreach ($sAreasList as $iArea => $sArea)
				{
					if ($sGrades[$iArea]["A"] == 0 && $sGrades[$iArea]["B"] == 0 && $sGrades[$iArea]["C"] == 0 && $sGrades[$iArea]["D"] == 0)
						continue;
?>
						              <td align="center">
						                <div style="position:relative; height:400px; width:40px; margin:0px auto 0px auto;" onclick="Lightview.show({href:'<?=SITE_URL?>/crc/view-quality-audit.php?Id=<?= $iAudit ?>&Area=<?= $iArea ?>' , rel:'iframe', options: { width: 800, height: 600 }});">
						                  <div style="position:absolute; left:0px; bottom:0px; cursor:pointer; width:8px; height:<?= @round($sGrades[$iArea]["A"] * 4) ?>px; background:#555655;" title="<?= $sGrades[$iArea]["A"] ?>%"></div>
						                  <div style="position:absolute; left:10px; bottom:0px; cursor:pointer; width:8px; height:<?= @round($sGrades[$iArea]["B"] * 4) ?>px; background:#878887;" title="<?= $sGrades[$iArea]["B"] ?>%"></div>
						                  <div style="position:absolute; left:20px; bottom:0px; cursor:pointer; width:8px; height:<?= @round($sGrades[$iArea]["C"] * 4) ?>px; background:#f8b171;" title="<?= $sGrades[$iArea]["C"] ?>%"></div>
						                  <div style="position:absolute; left:30px; bottom:0px; cursor:pointer; width:8px; height:<?= @round($sGrades[$iArea]["D"] * 4) ?>px; background:#f1882c;" title="<?= $sGrades[$iArea]["D"] ?>%"></div>
						                </div>
						              </td>
<?
				}
?>
						            </tr>
						          </table>
						        </div>


						        <h1 style="position:absolute; left:0px; top:0px; right:0px; background:none; border-top:solid 3px #444444; color:#222222; padding:8px 0px 0px 10px; height:auto; line-height:24px; font-size:24px;">
						          Quality Footprint<br />
						          <span style="font-size:18px; font-weight:none; float:left;">(<?= formatDate($sDate) ?>)</span>
						        </h1>

						        <div style="position:absolute; left:0px; top:12px; right:0px;">
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
								  <tr>
									<td></td>

									<td width="150" align="left"><b style="padding:4px; display:block; line-height:22px;">Total Count &nbsp; A/B/C/D<br />% of A/B/C/D</b></td>

									<td width="200">
									  <table border="1" bordercolor="#cccccc" cellpadding="4" cellspacing="0" width="100%">
										<tr>
										  <td width="25%" align="center" bgcolor="#00ff00"><b><?= $iGradeA ?></b></td>
										  <td width="25%" align="center" bgcolor="#99cc00"><b><?= $iGradeB ?></b></td>
										  <td width="25%" align="center" bgcolor="#ff6600"><b><?= $iGradeC ?></b></td>
										  <td width="25%" align="center" bgcolor="#ff0000"><b><?= $iGradeD ?></b></td>
										</tr>

										<tr>
										  <td align="center" style="font-size:9px;"><?= $fGradeA ?></td>
										  <td align="center" style="font-size:9px;"><?= $fGradeB ?></td>
										  <td align="center" style="font-size:9px;"><?= $fGradeC ?></td>
										  <td align="center" style="font-size:9px;"><?= $fGradeD ?></td>
										</tr>
									  </table>
									</td>

									<td width="80" align="center"><img src="images/crc/<?= $sGrade ?>.png" width="64" alt="" title="" /></td>
								  </tr>
								</table>
								</div>
						      </div>

							  <div style="margin:15px 0px 0px 0px;">
							    <table border="0" cellspacing="0" cellpadding="0" width="100%">
								  <tr>
<?
				$sIconsList = getList("tbl_quality_areas", "id", "icon", "", "position");

				foreach ($sAreasList as $iArea => $sArea)
				{
					if ($sGrades[$iArea]["A"] == 0 && $sGrades[$iArea]["B"] == 0 && $sGrades[$iArea]["C"] == 0 && $sGrades[$iArea]["D"] == 0)
						continue;
?>
								    <td align="center"><img src="images/crc/<?= $sIconsList[$iArea] ?>" width="42" height="42" alt="<?= $sArea ?>" title="<?= $sArea ?>" /></td>
<?
				}
?>
								  </tr>

								  <tr>
<?
				foreach ($sAreasList as $iArea => $sArea)
				{
					if ($sGrades[$iArea]["A"] == 0 && $sGrades[$iArea]["B"] == 0 && $sGrades[$iArea]["C"] == 0 && $sGrades[$iArea]["D"] == 0)
						continue;

					$fPercent = ($sGrades[$iArea]["A"] + $sGrades[$iArea]["B"]);
?>
								    <td align="center"><span style="display:inline-block; min-width:50px; margin:15px 0px 0px 0px; padding:5px; border:dotted 1px #cccccc; background:#f6f6f6; font-size:12px;"><?= $fPercent ?>%</span></td>
<?
				}
?>
								  </tr>
							    </table>
							  </div>


							  <div style="margin:10px 0px 0px 0px; border-top:solid 3px #cccccc;">
							    <table border="0" cellspacing="0" cellpadding="0" width="100%">
							      <tr bgcolor="#eeeeee">
							        <td width="40%"><b style="padding-left:10px;">Quality Area</b></td>

							        <td width="15%" align="center">
								      <div style="padding:8px 0px 5px 0px;">
								        <span style="display:inline-block; background:#555655; height:10px; width:10px; margin-right:5px;"></span>
								        Grade A
								      </div>
							        </td>

							        <td width="15%" align="center">
								      <div style="padding:8px 0px 5px 0px;">
								        <span style="display:inline-block; background:#878887; height:10px; width:10px; margin-right:5px;"></span>
								        Grade B
								      </div>
							        </td>

							        <td width="15%" align="center">
								      <div style="padding:8px 0px 5px 0px;">
								        <span style="display:inline-block; background:#f8b171; height:10px; width:10px; margin-right:5px;"></span>
								        Grade C
								      </div>
							        </td>

							        <td width="15%" align="center">
								      <div style="padding:8px 0px 5px 0px;">
								        <span style="display:inline-block; background:#f1882c; height:10px; width:10px; margin-right:5px;"></span>
								        Grade D
								      </div>
							        </td>
							      </tr>

								  <tr bgcolor="#eeeeee">
								    <td colspan="5" height="5"></td>
								  </tr>
<?
				foreach ($sAreasList as $iArea => $sArea)
				{
					if ($sGrades[$iArea]["A"] == 0 && $sGrades[$iArea]["B"] == 0 && $sGrades[$iArea]["C"] == 0 && $sGrades[$iArea]["D"] == 0)
						continue;
?>
								  <tr style="border-top:solid 1px #aaaaaa;">
								    <td><span style="display:block; padding:5px 0px 5px 10px;"><?= $sArea ?></span></td>
								    <td align="center"><?= @round($sGrades[$iArea]["A"], 0) ?>%</td>
								    <td align="center"><?= @round($sGrades[$iArea]["B"], 0) ?>%</td>
								    <td align="center"><?= @round($sGrades[$iArea]["C"], 0) ?>%</td>
								    <td align="center"><?= @round($sGrades[$iArea]["D"], 0) ?>%</td>
								  </tr>
<?
				}
?>
								  <tr bgcolor="#6db5dc">

<?
				foreach ($sCategoryList as $iCat => $sCategory)
				{
?>
								    <td width="2" bgcolor="#ffffff"></td>
								    <td align="center" style="padding:8px 0px 8px 0px; color:#ffffff; font-size:12px; border-top:solid 3px #c8e3f1;"><?= $sCategory ?></td>
<?
				}
?>
								  </tr>
							    </table>
							  </div>
						    </div>

						    <br />
<?
				$bGraph = true;
			}
		}







		if ($Type == "Production" || $Type == "")
		{
			$sSQL = "SELECT * FROM tbl_production_audits WHERE vendor_id='$Vendor' ORDER BY id DESC LIMIT 1";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 1)
			{
				$iAudit = $objDb->getField(0, "id");
				$sDate  = $objDb->getField(0, "audit_date");

				$sCategoryList = getList("tbl_production_categories", "id", "title", "", "position");

				$iGreen  = getDbValue("COUNT(*)", "tbl_production_audit_details", "weightage='5' AND audit_id='$iAudit'");
				$iYellow = getDbValue("COUNT(*)", "tbl_production_audit_details", "weightage='3' AND audit_id='$iAudit'");
				$iRed    = getDbValue("COUNT(*)", "tbl_production_audit_details", "weightage='1' AND audit_id='$iAudit'");

				$iIndex     = 0;
				$sGrades    = array( );

				foreach ($sCategoryList as $iCat => $sCategory)
				{
					$sSQL = "SELECT SUM(IF(pad.weightage>'3', '1', '0')) AS _Green,
									SUM(IF(pad.weightage>'1' and pad.weightage<'3', '1', '0')) AS _Yellow,
									SUM(IF(pad.weightage<'2', '1', '0')) AS _Red
							 FROM tbl_production_audit_details pad, tbl_production_questions as paq
							 WHERE pad.audit_id='$iAudit' AND pad.question_id=paq.id AND paq.category_id='$iCat'";

					$objDb->query($sSQL);


					$iGreen  = $objDb->getField(0, "_Green");
					$iYellow = $objDb->getField(0, "_Yellow");
					$iRed    = $objDb->getField(0, "_Red");

					$sGrades[$iCat]["Green"]  = $iGreen;
					$sGrades[$iCat]["Yellow"] = $iYellow;
					$sGrades[$iCat]["Red"]    = $iRed;

				}

				$iMax = $iGreen;

				if ($iMax < $iYellow)
					$iMax = $iYellow;

				if ($iMax < $iRed)
					$iMax = $iRed;
?>
						    <div style="position:relative; border:solid 1px #cccccc;">
						      <div style="height:250px; position:relative;">
						        <div style="height:250px;">
						          <table border="0" cellspacing="0" cellpadding="0" width="100%">
						            <tr height="250" valign="bottom">
						              <td width="150"></td>
<?
				foreach ($sCategoryList as $iCat => $sCategory)
				{
?>
						              <td width="2"></td>

						              <td align="center">
						                <div id="ProductionChart<?= $iCat ?>">loading...</div>

										<script type="text/javascript">
										<!--
											var objChart<?= $iCat ?> = new FusionCharts("scripts/fusion-charts/charts/Doughnut2D.swf", "ProductionChart_<?= $iCat ?>", "100%", "200", "0", "1");

											objChart<?= $iCat ?>.setXMLData("<chart caption=\"\" bgcolor=\"ffffff\" canvasBgColor=\"555655\" bgAlpha=\"100\" canvasbgAlpha=\"100\" showBorder=\"0\" animation=\"1\" numberPrefix=\"%\" showPercentageValues=\"1\" isSmartLineSlanted=\"0\" showValues=\"0\" showLabels=\"0\" showToolTip=\"1\" showLegend=\"0\" chartTopMargin=\"0\" chartBottomMargin=\"0\" pieRadius=\"64\">" +
																			"<set value=\"<?= $sGrades[$iCat]["Green"] ?>\" label=\"\" color=\"555655\" alpha=\"100\" link =\"Javascript: Lightview.show({href:'<?=SITE_URL?>/crc/view-production-audit-performance.php?Id=<?=$iAudit?>&Cat=<?=$iCat?>&Standard=green' , rel:'iframe', options: { width: 800, height: 400 }});\" />" +
																			"<set value=\"<?= $sGrades[$iCat]["Yellow"] ?>\" label=\"\" color=\"f8b171\" alpha=\"100\" link =\"Javascript: Lightview.show({href:'<?=SITE_URL?>/crc/view-production-audit-performance.php?Id=<?=$iAudit?>&Cat=<?=$iCat?>&Standard=yellow' , rel:'iframe', options: { width: 800, height: 400 }});\" />" +
																			"<set value=\"<?= $sGrades[$iCat]["Red"] ?>\" label=\"\" color=\"f1882c\" alpha=\"100\" link =\"Javascript: Lightview.show({href:'<?=SITE_URL?>/crc/view-production-audit-performance.php?Id=<?=$iAudit?>&Cat=<?=$iCat?>&Standard=red' , rel:'iframe', options: { width: 800, height: 400 }});\" />" +
																			"</chart>");

											objChart<?= $iCat ?>.render("ProductionChart<?= $iCat ?>");
										-->
										</script>

						              </td>
<?
				}
?>
						            </tr>
						          </table>
						        </div>

						        <h1 style="position:absolute; left:0px; top:0px; right:0px; background:none; border-top:solid 3px #444444; color:#222222; padding:8px 0px 0px 10px; height:auto; line-height:24px; font-size:24px;">
						          Development & Production Footprint<br />
						          <span style="font-size:18px; font-weight:none; float:left;">(<?= formatDate($sDate) ?>)</span>
						        </h1>
						      </div>

							  <div style="margin:0px 0px 0px 0px;">
							    <table border="0" cellspacing="0" cellpadding="0" width="100%">
								  <tr bgcolor="#dddddd">
								    <td width="150" style="border-top:solid 3px #bbbbbb;"></td>
<?
				foreach ($sCategoryList as $iCat => $sCategory)
				{
?>
								    <td width="2" bgcolor="#ffffff"></td>
								    <td align="center" style="padding:8px 0px 8px 0px; font-size:12px; border-top:solid 3px #bbbbbb;"><?= $sCategory ?></td>
<?
				}
?>
								  </tr>

								  <tr>
								    <td colspan="<?= ((count($sCategoryList) * 2) + 1) ?>" height="5"></td>
								  </tr>

								  <tr style="border-bottom:solid 1px #999999;">
								    <td width="150">
								      <div style="padding:0px 0px 5px 0px;">
								        <span style="display:inline-block; background:#555655; height:10px; width:10px; margin:0px 10px 0px 5px;"></span>
								        Above Par
								      </div>
								    </td>
<?
				foreach ($sCategoryList as $iCat => $sCategory)
				{
					$iTotal   = ($sGrades[$iCat]["Green"] + $sGrades[$iCat]["Yellow"] + $sGrades[$iCat]["Red"]);
					$iGiven   = $sGrades[$iCat]["Green"];
					$fPercent = @round(( ($iGiven / $iTotal) * 100), 0);
?>
								    <td width="2"></td>
								    <td align="center"><?= $fPercent ?>%</td>
<?
				}
?>
								  </tr>

								  <tr style="border-bottom:solid 1px #999999;">
								    <td width="150">
								      <div style="padding:5px 0px 5px 0px;">
								        <span style="display:inline-block; background:#f8b171; height:10px; width:10px; margin:0px 10px 0px 5px;"></span>
								        At Par
								      </div>
								    </td>
<?
				foreach ($sCategoryList as $iCat => $sCategory)
				{
					$iTotal   = ($sGrades[$iCat]["Green"] + $sGrades[$iCat]["Yellow"] + $sGrades[$iCat]["Red"]);
					$iGiven   = $sGrades[$iCat]["Yellow"];
					$fPercent = @round(( ($iGiven / $iTotal) * 100), 0);
?>
								    <td width="2"></td>
								    <td align="center"><?= $fPercent ?>%</td>
<?
				}
?>
								  </tr>

								  <tr style="border-bottom:solid 1px #999999;">
								    <td width="150">
								      <div style="padding:5px 0px 5px 0px;">
								        <span style="display:inline-block; background:#f1882c; height:10px; width:10px; margin:0px 10px 0px 5px;"></span>
								        Below Par
								      </div>
								    </td>
<?
				foreach ($sCategoryList as $iCat => $sCategory)
				{
					$iTotal   = ($sGrades[$iCat]["Green"] + $sGrades[$iCat]["Yellow"] + $sGrades[$iCat]["Red"]);
					$iGiven   = $sGrades[$iCat]["Red"];
					$fPercent = @round(( ($iGiven / $iTotal) * 100), 0);
?>
								    <td width="2"></td>
								    <td align="center"><?= $fPercent ?>%</td>
<?
				}
?>
								  </tr>
							    </table>
							  </div>
						    </div>

						    <br />
<?
				$bGraph = true;
			}
		}



		if ($bGraph == false)
		{
?>
							<br />
							No Data Available for this Vendor<br />
<?
		}
	}
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