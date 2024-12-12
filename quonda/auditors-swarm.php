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

	$Vendor = IO::strValue("Vendor");

	$sVendorsList = getList("tbl_vendors v", "v.id", "CONCAT(COALESCE((SELECT CONCAT(vendor, ' &raquo;&raquo; ') FROM tbl_vendors WHERE id=v.parent_id), ''), v.vendor) AS _Vendor", "v.id IN ({$_SESSION['Vendors']}) AND v.sourcing='Y'", "_Vendor");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false&key=<?= GOOGLE_MAPS_KEY ?>"></script>
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
			    <h1>auditors swarm</h1>


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
			      <div id="AuditorsSwarm" style="width:100%; height:600px;"></div>

					  <script type="text/javascript">
					  <!--
						 var objLatLong = new google.maps.LatLng(31.4103609, 74.2271733);
						 var objOptions = { zoom:12, center:objLatLong, mapTypeId:google.maps.MapTypeId.ROADMAP };
						 var objMap     = new google.maps.Map(document.getElementById("AuditorsSwarm"), objOptions);

						 var objLatLong = new google.maps.LatLng(31.4103609, 74.2271733);
						 var objMarker  = new google.maps.Marker({ position:objLatLong, map:objMap, icon:'images/office.png' });
						 var objInfoWin = new google.maps.InfoWindow({ content:"<b>Triple Tree Solutions</b><br /><br />7.5 KM, Raiwind Road, Lahore" });

						 google.maps.event.addListener(objMarker, 'click', function( )
						 {
							 objInfoWin.open(objMap, objMarker);
						 });

<?
	$sSQL = "SELECT id, name, latitude, longitude, location_address, location_time FROM tbl_users WHERE TIME_TO_SEC(TIMEDIFF(NOW( ), location_time)) <= '43200' AND status='A' ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	for ($i = 0; $i < $iCount; $i ++)
	{
		$iUser      = $objDb->getField($i, "id");
		$sName      = $objDb->getField($i, "name");
		$sLatitude  = $objDb->getField($i, "latitude");
		$sLongitude = $objDb->getField($i, "longitude");
		$sAddress   = $objDb->getField($i, "location_address");
		$sDateTime  = $objDb->getField($i, "location_time");

		$sDateTime = formatDate($sDateTime, "h:i A");
?>
						 var objLatLong<?= $i ?> = new google.maps.LatLng(<?= $sLatitude ?>, <?= $sLongitude ?>);
						 var objMarker<?= $i ?>  = new google.maps.Marker({ position:objLatLong<?= $i ?>, map:objMap, icon:'images/<?= ((@in_array($iUser, array(1,2,3,343))) ? 'manager' : 'auditor') ?>.png' });
						 var objInfoWin<?= $i ?> = new google.maps.InfoWindow({ content:"<b><?= $sName ?></b><br /><br /><?= utf8_encode(str_replace(array("\n", "\r", "\r\n"), "<br />", htmlentities($sAddress))) ?><br />Recorded at: <?= $sDateTime ?>" });

						 objMarker<?= $i ?>.setZIndex(google.maps.Marker.MAX_ZINDEX + 1);

						 google.maps.event.addListener(objMarker<?= $i ?>, 'click', function( )
						 {
							 objInfoWin<?= $i ?>.open(objMap, objMarker<?= $i ?>);
						 });
<?
	}


	if ($Vendor > 0)
		$sSQL = "SELECT vendor, latitude, longitude, address FROM tbl_vendors WHERE id='$Vendor' AND latitude!='' AND longitude!='' ORDER BY vendor";

	else
		$sSQL = "SELECT vendor, latitude, longitude, address FROM tbl_vendors WHERE latitude!='' AND longitude!='' ORDER BY vendor";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	for ($i = 0; $i < $iCount; $i ++)
	{
		$sVendor    = $objDb->getField($i, "vendor");
		$sLatitude  = $objDb->getField($i, "latitude");
		$sLongitude = $objDb->getField($i, "longitude");
		$sAddress   = $objDb->getField($i, "address");
?>
						 var objVendorLatLong<?= $i ?> = new google.maps.LatLng(<?= $sLatitude ?>, <?= $sLongitude ?>);
						 var objVendorMarker<?= $i ?>  = new google.maps.Marker({ position:objVendorLatLong<?= $i ?>, map:objMap, icon:'images/factory.png' });
						 var objVendorInfoWin<?= $i ?> = new google.maps.InfoWindow({ content:"<b><?= $sVendor ?></b><br /><br /><?= utf8_encode(str_replace(array("\n", "\r", "\r\n"), "<br />", htmlentities($sAddress))) ?>" });

						 google.maps.event.addListener(objVendorMarker<?= $i ?>, 'click', function( )
						 {
							 objVendorInfoWin<?= $i ?>.open(objMap, objVendorMarker<?= $i ?>);
						 });
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