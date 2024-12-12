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

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id>'0'");
	$sVendorsList = getList("tbl_vendors", "id", "vendor", "parent_id='0' AND sourcing='Y'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

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
			    <h1>Brand Placements</h1>

<?
	$sClass = array("evenRow", "oddRow");

	foreach ($sBrandsList as $sKey => $sValue)
	{
?>
			    <h2 class="green" style="margin-bottom:1px;"><?= $sValue ?></h2>

			    <div class="tblSheet">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow" valign="top">
				      <td width="5%">#</td>
				      <td width="75%">Vendor</td>
				      <td width="10%">POs</td>
				      <td width="10%" class="center">Details</td>
				    </tr>
<?
		$sSQL = "SELECT id FROM tbl_styles WHERE sub_brand_id='$sKey'";
		$objDb->query($sSQL);

		$iCount  = $objDb->getCount( );
		$sStyles = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sStyles .= (",".$objDb->getField($i, 0));

		$sStyles = substr($sStyles, 1);


		$sSQL = "SELECT po_id FROM tbl_po_colors WHERE style_id IN ($sStyles)";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sPOs   = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sPOs .= (",".$objDb->getField($i, 0));

		$sPOs = substr($sPOs, 1);


		$sSQL = "SELECT DISTINCT(vendor_id) FROM tbl_po WHERE id IN ($sPOs)";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iVendor = $objDb->getField($i, 0);


			$sSQL = "SELECT COUNT(*) FROM tbl_po WHERE vendor_id='$iVendor' AND id IN ($sPOs)";
			$objDb2->query($sSQL);

			$iPOs = $objDb2->getField(0, 0);
?>

				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td><?= ($i + 1) ?></td>
				      <td><?= $sVendorsList[$iVendor] ?></span></td>
				      <td><?= $iPOs ?></td>

				      <td class="center">
<?
			if (checkUserRights("view-purchase-order.php", "Data Entry", "view"))
			{
?>
				        <a href="data/purchase-orders.php?Brand=<?= $sKey ?>&Vendor=<?= $iVendor ?>"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a>
<?
			}
?>
				      </td>
				    </tr>
<?
		}

		if ($iCount == 0)
		{
?>

				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td colspan="4" class="center">No Vendor Found!</td>
				    </tr>
<?
		}
?>
			      </table>
		        </div>

		        <br />
<?
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