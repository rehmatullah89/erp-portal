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

	$Id = IO::intValue('Id');

	$sSQL = "SELECT * FROM tbl_yarn_inquiries WHERE id='$Id'";
	$objDb->query($sSQL);

	$iStyle    = $objDb->getField(0, 'style_id');
	$sDate     = $objDb->getField(0, 'date');
	$sQuantity = $objDb->getField(0, 'quantity');
	$sTypes    = $objDb->getField(0, 'types');

	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");

	$sTypesList = array("pxp" => "Pak x Pak",
	                    "pxu" => "Pak x US",
	                    "uxp" => "US x Pak",
	                    "uxu" => "US x US");

	$sStyle        = getDbValue("style", "tbl_styles", "id='$iStyle'");
	$sConstruction = getDbValue("greige_construction", "tbl_gf_specs", "style_id='$iStyle'");
	$sTypes        = @explode(",", $sTypes);
	$sYarnTypes    = "";

	foreach ($sTypes as $sType)
		$sYarnTypes .= ((($sYarnTypes != "") ? ", " : "").$sTypesList[$sType]);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
</head>

<body>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body" style="min-height:344px; height:344px;">
	  <h2>Inquiry Details</h2>

	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
	    <tr>
		  <td width="80">Date</td>
		  <td width="20" align="center">:</td>
		  <td><?= formatDate($sDate) ?></td>
	    </tr>

	    <tr>
		  <td>D #</td>
		  <td align="center">:</td>
		  <td><?= $sStyle ?></td>
 	    </tr>

	    <tr>
		  <td>Construction</td>
		  <td align="center">:</td>
		  <td><?= $sConstruction ?></td>
 	    </tr>

	    <tr>
		  <td>Quantity</td>
		  <td align="center">:</td>
		  <td><?= $sQuantity ?></td>
	    </tr>
	    </table>

	    <br />

	  <table border="1" bordercolor="#cccccc" cellpadding="5" cellspacing="0" width="100%">
		<tr bgcolor="#eeeeee">
		  <td width="110"></td>
<?
	$sSQL = "SELECT * FROM tbl_yarn_inquiry_details WHERE inquiry_id='$Id' ORDER BY vendor_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iVendor = $objDb->getField($i, "vendor_id");
?>
		  <td align="center"><b><?= $sVendorsList[$iVendor] ?></b></td>
<?
	}
?>
		</tr>

<?
	foreach ($sTypes as $sType)
	{
?>
		<tr bgcolor="#f6f6f6">
		  <td><b><?= $sTypesList[$sType] ?></b></td>
<?
		for ($i = 0; $i < $iCount; $i ++)
		{
			$fPrice = $objDb->getField($i, "{$sType}_price");
?>
		  <td align="center"><?= (($fPrice > 0) ? formatNumber($fPrice) : "") ?></td>
<?
		}
?>
		</tr>
<?
	}
?>

		<tr bgcolor="#f6f6f6">
		  <td><b>Response Time</b></td>
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$sResponseTime = $objDb->getField($i, "response_time");
?>
		  <td align="center"><?= $sResponseTime ?></td>
<?
	}
?>
		</tr>

		<tr bgcolor="#f6f6f6">
		  <td><b>Shipment Date<b/></td>
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$sShipmentDate = $objDb->getField($i, "shipment_date");
?>
		  <td align="center"><?= $sShipmentDate ?></td>
<?
	}
?>
		</tr>
	  </table>
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