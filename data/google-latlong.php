<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  MATRIX Customer Portal                                                                   **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.apparelco.com                                                              **
	**                                                                                           **
	**  Copyright 2008-15 (C) Matrix Sourcing                                                    **
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

	$sLatitude  = IO::strValue("Lat");
	$sLongitude = IO::strValue("Lon");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>

  <style type="text/css">
  <!--
    v\:* { behavior:url(#default#VML); }
  -->
  </style>

  <script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false&key=AIzaSyBKUixemX1jXoHwR7F4dsTUiGWwmRuZwDI"></script>
</head>

<body>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body">
      <h2>Google Maps Latitude, Longitude</h2>
      &nbsp;Drag the Marker on the desired Location to get the Latitude & Longitude of that Place.<br />
      <br />

	  <table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
		  <td width="620"><div id="map" style="width:600px; height:495px"></div></td>

		  <td>
			<div id="geo">
			  <form name="setLatLon" onsumbit="return false;">
				<b>Coordinates:</b><br />

				<table cellspacing="0" cellpadding="3" border="0" width="100%">
				  <tr><td width="30">Lat:</td><td><input type='text' name='lat' id="frmLat" size="10" class="textbox" /></td></tr>
				  <tr><td>Lon:</td><td><input type='text' name='lon' id="frmLon" size="10" class="textbox" /></td></tr>
				</table>
			  </form>
			</div>
		  </td>
		</tr>
	  </table>

		<script type="text/javascript">
		<!--
		//<![CDATA[
		
			var objLatLong = new google.maps.LatLng(31.4103609, 74.2271733);
			var objOptions = { zoom:5, center:objLatLong, mapTypeId:google.maps.MapTypeId.ROADMAP, mapTypeControl:false, streetViewControl:false };
			var objMap     = new google.maps.Map(document.getElementById("map"), objOptions);
			 
			var objMarker = new google.maps.Marker({ draggable:true, position:objLatLong, map:objMap, title:"Factory Location" });

			google.maps.event.addListener(objMarker, 'dragend', function (event)
			{
				setPosition(event.latLng);
			});
			
			
			google.maps.event.addListener(objMap, 'click', function(event)
			{
				objMarker.setPosition(event.latLng);
				setPosition(event.latLng);
			});
			
			
			function setPosition(objLatLong)
			{
				document.getElementById("frmLat").value = objLatLong.lat();
				document.getElementById("frmLon").value = objLatLong.lng();
				
				if (parent.document.getElementById("<?= $sLatitude ?>"))
				{
					parent.document.getElementById("<?= $sLatitude ?>").value  = objLatLong.lat();
					parent.document.getElementById("<?= $sLongitude ?>").value = objLatLong.lng();
				}
			}
		//]]>
		-->
		</script>
	</div>
<!--  Body Section Ends Here  -->


  </div>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>