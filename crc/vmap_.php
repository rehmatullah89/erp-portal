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

	
	$Vendor = IO::strValue("Vendor");
	
	
	$iVendors = array( );
	

	$sSQL = "SELECT DISTINCT(vendor_id) FROM tbl_tnc_audits WHERE vendor_id IN ({$_SESSION['Vendors']}) ";
	$objDb->query($sSQL);
	
	$iCount = $objDb->getCount( );
	
	for ($i = 0; $i < $iCount; $i ++)
		$iVendors[] = $objDb->getField($i, 0);
	
	
	$sSQL = "SELECT DISTINCT(vendor_id) FROM tbl_compliance_audits WHERE vendor_id IN ({$_SESSION['Vendors']}) ";
	$objDb->query($sSQL);
	
	$iCount = $objDb->getCount( );
	
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iVendor = $objDb->getField($i, 0);
		
		if (!@in_array($iVendor, $iVendors))
			$iVendors[] = $iVendor;
	}
	
	
	$sSQL = "SELECT DISTINCT(vendor_id) FROM tbl_quality_audits WHERE vendor_id IN ({$_SESSION['Vendors']}) ";
	$objDb->query($sSQL);
	
	$iCount = $objDb->getCount( );
	
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iVendor = $objDb->getField($i, 0);
		
		if (!@in_array($iVendor, $iVendors))
			$iVendors[] = $iVendor;
	}
	
	
	$sSQL = "SELECT DISTINCT(vendor_id) FROM tbl_safety_audits WHERE vendor_id IN ({$_SESSION['Vendors']}) ";
	$objDb->query($sSQL);
	
	$iCount = $objDb->getCount( );
	
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iVendor = $objDb->getField($i, 0);
		
		if (!@in_array($iVendor, $iVendors))
			$iVendors[] = $iVendor;
	}
	
	
	$sSQL = "SELECT DISTINCT(vendor_id) FROM tbl_production_audits WHERE vendor_id IN ({$_SESSION['Vendors']}) ";
	$objDb->query($sSQL);
	
	$iCount = $objDb->getCount( );
	
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iVendor = $objDb->getField($i, 0);
		
		if (!@in_array($iVendor, $iVendors))
			$iVendors[] = $iVendor;
	}
	
	
	$sVendors     = @implode(",", $iVendors);	
	$sVendorsList = getList("tbl_vendors v", "v.id", "CONCAT(COALESCE((SELECT CONCAT(vendor, ' &raquo;&raquo; ') FROM tbl_vendors WHERE id=v.parent_id), ''), v.vendor) AS _Vendor", "v.id IN ($sVendors) AND v.sourcing='Y'", "_Vendor");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false&key=AIzaSyBKUixemX1jXoHwR7F4dsTUiGWwmRuZwDI"></script>
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
			    <h1><img src="images/h1/crc/vmap.jpg" width="187" height="20" vspace="10" alt="" title="" /></h1>


			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="55">Vendor</td>

			          <td width="180">
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

			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>
			    </form>


			    <div class="tblSheet" style="position:relative;">
			      <div id="Vmap" style="width:100%; height:600px;"></div>

					  <script type="text/javascript">
					  <!--
						 var sStyles    = [{"featureType":"administrative","elementType":"all","stylers":[{"saturation":"-100"}]},{"featureType":"administrative.province","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","elementType":"all","stylers":[{"saturation":-100},{"lightness":"50"},{"visibility":"simplified"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":"-100"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"all","stylers":[{"lightness":"30"}]},{"featureType":"road.local","elementType":"all","stylers":[{"lightness":"40"}]},{"featureType":"transit","elementType":"all","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]},{"featureType":"water","elementType":"labels","stylers":[{"lightness":-25},{"saturation":-100}]}];
						 var objLatLong = new google.maps.LatLng(31.3974864, 74.2207633);
						 var objOptions = { zoom:10, center:objLatLong, mapTypeId:google.maps.MapTypeId.ROADMAP, styles:sStyles };
						 var objMap     = new google.maps.Map(document.getElementById("Vmap"), objOptions);
						 var objPopups  = [];

<?
	if ($Vendor > 0)
		$sSQL = "SELECT id, vendor, latitude, longitude, address FROM tbl_vendors WHERE id='$Vendor' AND latitude!='' AND longitude!='' ORDER BY vendor";

	else
		$sSQL = "SELECT id, vendor, latitude, longitude, address FROM tbl_vendors WHERE latitude!='' AND longitude!='' AND id IN ($sVendors) ORDER BY vendor";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	for ($i = 0; $i < $iCount; $i ++)
	{
		$iVendor    = $objDb->getField($i, "id");
		$sVendor    = $objDb->getField($i, "vendor");
		$sLatitude  = $objDb->getField($i, "latitude");
		$sLongitude = $objDb->getField($i, "longitude");
		$sAddress   = $objDb->getField($i, "address");
		
		
		$iLastAudit = getDbValue("id", "tbl_tnc_audits", "vendor_id='$iVendor'");
		

		$sSQL = "SELECT SUM(IF(tp.nature='Z' AND tad.score=0, 1, 0)) as _ZeroTolerancePoints, 
						SUM(IF(tp.nature='C' AND tad.score=0, 1, 0)) as _CriticalPoints,
						SUM(IF(tad.score='1', 1, 0)) as _ActualPoints,
						SUM(IF(tad.score!='-1', 1, 0)) as  _PossiblePoints
				FROM tbl_tnc_audits ta, tbl_tnc_audit_details tad, tbl_tnc_points tp
				WHERE ta.id=tad.audit_id AND tad.point_id=tp.id AND ta.id='$iLastAudit'";
		$objDb2->query($sSQL);		

		$sMarker = "";
		
		if ($objDb2->getCount( ) == 1)
		{
			$iZTPoints       = $objDb2->getField(0, '_ZeroTolerancePoints');
			$iCriticalPoints = $objDb2->getField(0, '_CriticalPoints');
			$iActualPoints   = $objDb2->getField(0, '_ActualPoints');
			$iPossiblePoint  = $objDb2->getField(0, '_PossiblePoints');
											
			$Percentage = formatNumber(($iActualPoints/$iPossiblePoint)*100);
			
			if ($iZTPoints > 0)
				$sMarker = 'red.png';
			
			else
			{
				if ($iCriticalPoints == 0 && $Percentage >= 85)
					$sMarker = 'green.png';
				
				else if ($iCriticalPoints == 0 && ($Percentage >= 70 && $Percentage <= 84))
					$sMarker = 'yellow.png';
				
				else if ($iCriticalPoints > 0 || $Percentage <= 69)
					$sMarker = 'orange.png';
				
				else
					$sMarker = 'red.png';
			}
		}

		if ($sMarker == "")
			$sMarker = 'default.png';
?>
						 var objVendorLatLong<?= $i ?> = new google.maps.LatLng(<?= $sLatitude ?>, <?= $sLongitude ?>);
						 var objVendorMarker<?= $i ?>  = new google.maps.Marker({ position:objVendorLatLong<?= $i ?>, map:objMap, icon:'images/crc/<?= $sMarker ?>' });
						 var objVendorInfoWin<?= $i ?> = new google.maps.InfoWindow({ content:"<div style=''><b style='font-weight:bold;'><?= $sVendor ?></b><br /><br /><?= utf8_encode(str_replace("\n", "<br />", htmlentities($sAddress, ENT_QUOTES))) ?></div>" });

						 google.maps.event.addListener(objVendorMarker<?= $i ?>, 'click', function( )
						 {
							for (var i = 0; i < objPopups.length; i ++)
								objPopups[i].close( );					

							
							 objVendorInfoWin<?= $i ?>.open(objMap, objVendorMarker<?= $i ?>);
						 });
						 
						 
						 objPopups.push(objVendorInfoWin<?= $i ?>);
<?
	}
	

	if ($iCount == 0)
	{
?>
						  alert("No Vendor Marked on Map, Lat/Long not available.");
<?
	}

	else if ($iCount == 1)
	{
?>
						  objMap.setCenter(new google.maps.LatLng(<?= $sLatitude ?>, <?= $sLongitude ?>));
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