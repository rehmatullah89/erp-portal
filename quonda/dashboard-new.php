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


	$sVendorsList = getList("tbl_vendors v", "v.id", "CONCAT(COALESCE((SELECT CONCAT(vendor, ' &raquo;&raquo; ') FROM tbl_vendors WHERE id=v.parent_id), ''), v.vendor) AS _Vendor", "v.id IN ({$_SESSION['Vendors']}) AND v.sourcing='Y'", "_Vendor");
	
	if ($FromDate == "")
		$FromDate = getDbValue("MAX(audit_date)", "tbl_qa_reports", "vendor_id IN ({$_SESSION['Vendors']}) AND brand_id IN ({$_SESSION['Brands']}) AND audit_date<=CURDATE( )");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <style type="text/css">
  <!--
    v\: { behavior:url(#default#VML); }
  -->
  </style>

  <script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false&key=<?= GOOGLE_MAPS_KEY ?>"></script>
  <script type="text/javascript" src="scripts/quonda/dashboard.js"></script>
  <script type="text/javascript" src="scripts/jquery.js"></script>

  <script type="text/javascript">
  <!--
		jQuery.noConflict( );
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
					  <td width="80">Audit Date</td>
					  <td width="78"><input type="text" name="FromDate" value="<?= (($FromDate == "") ? date("Y-m-d") : $FromDate) ?>" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="60"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="200">
<?
	if ($_SESSION['UserType'] != 'MATRIX' && $_SESSION['UserType'] != 'TRIPLETREE')
	{
?>
						[ <a href="quonda/dashboard.php?Type=Recent" style="color:#ffff00;">Recent Audits</a> ]
<?
	}
?>					  
					  </td>
					  
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>
				</form>
				

			    <div class="tblSheet" style="position:relative;">
			      <div id="CountryWiseStats" style="width:100%; height:600px; background:#293133;"></div>

					  <script type="text/javascript">
					  <!--
						var sStyle = [
										{
											"featureType": "all",
											"elementType": "all",
											"stylers": [
												{
													"saturation": "32"
												},
												{
													"lightness": "-3"
												},
												{
													"visibility": "on"
												},
												{
													"weight": "1.18"
												}
											]
										},
										{
											"featureType": "administrative",
											"elementType": "labels",
											"stylers": [
												{
													"visibility": "off"
												}
											]
										},
										{
											"featureType": "administrative.country",
											"elementType": "geometry.stroke",
											"stylers": [
												{
													"visibility": "on"
												},
												{
													"color": "#737373"
												},
												{
													"weight": "1.00"
												}
											]
										},
										{
											"featureType": "administrative.country",
											"elementType": "labels.text.fill",
											"stylers": [
												{
													"visibility": "on"
												},
												{
													"color": "#808080"
												}
											]
										},
										{
											"featureType": "administrative.country",
											"elementType": "labels.text.stroke",
											"stylers": [
												{
													"weight": "0.01"
												}
											]
										},
										{
											"featureType": "landscape",
											"elementType": "labels",
											"stylers": [
												{
													"visibility": "off"
												}
											]
										},
										{
											"featureType": "landscape.man_made",
											"elementType": "all",
											"stylers": [
												{
													"saturation": "-70"
												},
												{
													"lightness": "14"
												},
												{
													"visibility": "off"
												}
											]
										},
										{
											"featureType": "landscape.man_made",
											"elementType": "geometry",
											"stylers": [
												{
													"visibility": "off"
												}
											]
										},
										{
											"featureType": "poi",
											"elementType": "geometry",
											"stylers": [
												{
													"visibility": "off"
												}
											]
										},
										{
											"featureType": "poi",
											"elementType": "labels",
											"stylers": [
												{
													"visibility": "off"
												}
											]
										},
										{
											"featureType": "road",
											"elementType": "geometry",
											"stylers": [
												{
													"visibility": "off"
												}
											]
										},
										{
											"featureType": "road",
											"elementType": "geometry.fill",
											"stylers": [
												{
													"visibility": "off"
												}
											]
										},
										{
											"featureType": "road",
											"elementType": "labels",
											"stylers": [
												{
													"visibility": "off"
												}
											]
										},
										{
											"featureType": "road",
											"elementType": "labels.text.fill",
											"stylers": [
												{
													"visibility": "off"
												}
											]
										},
										{
											"featureType": "transit",
											"elementType": "geometry",
											"stylers": [
												{
													"visibility": "off"
												}
											]
										},
										{
											"featureType": "transit",
											"elementType": "geometry.fill",
											"stylers": [
												{
													"visibility": "off"
												}
											]
										},
										{
											"featureType": "transit",
											"elementType": "labels",
											"stylers": [
												{
													"visibility": "off"
												}
											]
										},
										{
											"featureType": "transit",
											"elementType": "labels.text",
											"stylers": [
												{
													"visibility": "off"
												}
											]
										},
										{
											"featureType": "water",
											"elementType": "all",
											"stylers": [
												{
													"saturation": "100"
												},
												{
													"lightness": "-14"
												}
											]
										},
										{
											"featureType": "water",
											"elementType": "labels",
											"stylers": [
												{
													"visibility": "off"
												},
												{
													"lightness": "12"
												}
											]
										}
									];
					  
						 var objLatLong = new google.maps.LatLng(31.4103609, 74.2271733);
						 var objOptions = { zoom:3, center:objLatLong, mapTypeId:google.maps.MapTypeId.ROADMAP, styles:sStyle, mapTypeControl:false, streetViewControl:false };
						 var objMap     = new google.maps.Map(document.getElementById("CountryWiseStats"), objOptions);
<?
	$sVendors = getDbValue("GROUP_CONCAT(DISTINCT(vendor_id) SEPARATOR ',')", "tbl_qa_reports", "audit_date='$FromDate' AND vendor_id IN ({$_SESSION['Vendors']}) AND brand_id IN ({$_SESSION['Brands']})");
	

	$sSQL = "SELECT c.id, c.country, c.latitude, c.longitude,
	                COUNT(1) AS _Audits,
					SUM(IF(qa.audit_result!='', '1', '0')) AS _Completed,
					SUM(IF(audit_result='P', '1', '0')) AS _Passed,
					SUM(IF(audit_result='F', '1', '0')) AS _Failed
			 FROM tbl_countries c, tbl_vendors v, tbl_qa_reports qa
			 WHERE c.id=v.country_id AND v.id=qa.vendor_id AND c.latitude!='' AND c.longitude!='' 
			       AND qa.audit_date='$FromDate' AND qa.vendor_id IN ({$_SESSION['Vendors']}) AND qa.brand_id IN ({$_SESSION['Brands']})
			 GROUP BY c.id
			 ORDER BY c.country";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	for ($i = 0; $i < $iCount; $i ++)
	{
		$iCountry   = $objDb->getField($i, "id");
		$sCountry   = $objDb->getField($i, "country");
		$sLatitude  = $objDb->getField($i, "latitude");
		$sLongitude = $objDb->getField($i, "longitude");
		$iAudits    = $objDb->getField($i, "_Audits");
		$iCompleted = $objDb->getField($i, "_Completed");
		$iPassed    = $objDb->getField($i, "_Passed");
		$iFailed    = $objDb->getField($i, "_Failed");
?>
						 var objCountryLatLong<?= $i ?> = new google.maps.LatLng(<?= $sLatitude ?>, <?= $sLongitude ?>);
						 var objCountryMarker<?= $i ?>  = new google.maps.Marker({ position:objCountryLatLong<?= $i ?>, map:objMap, icon:'images/country.png' });
						 var objCountryInfoWin<?= $i ?> = new google.maps.InfoWindow({ content:"<div style='width:200px; height:150px;'><h4><?= $sCountry ?></h4><br />Total Audits: <?= $iAudits ?><br />Completed: <?= $iCompleted ?><br />Passed: <?= $iPassed ?><br />Failed: <?= $iFailed ?><br /><br /><a><a href='quonda/dashboard.php?Region=<?= $iCountry ?>&FromDate=<?= $FromDate ?>&ToDate=<?= $FromDate ?>'>Expand</a></b></div>" });
/*
						 google.maps.event.addListener(objCountryMarker<?= $i ?>, 'mouseover', function( )
						 {
							 objCountryInfoWin<?= $i ?>.open(objMap, objCountryMarker<?= $i ?>);
						 });
						 
						 google.maps.event.addListener(objCountryMarker<?= $i ?>, 'mouseout', function( )
						 {
							 objCountryInfoWin<?= $i ?>.close( );
						 });
*/
						 google.maps.event.addListener(objCountryMarker<?= $i ?>, 'click', function( )
						 {
							 objCountryInfoWin<?= $i ?>.open(objMap, objCountryMarker<?= $i ?>);
						 });
<?
	}
?>

					  -->
					  </script>
				  </div>

			      <br />
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