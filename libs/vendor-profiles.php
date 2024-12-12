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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

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
			  <td width="585">
			    <h1>Vendor Profiles</h1>

			    <div class="tblSheet">
			      <img src="images/headers/libs/vendor-profiles.jpg" width="581" height="205" alt="" title="" /><br />

			      <div style="padding:10px 10px 25px 10px;">
			        Please select any of the vendor from the list below to see the profile of that vendor.<br />

<?
	$sConditions = "";

	if (strpos($_SESSION["Email"], "@kik.go.com") !== FALSE)
		$sConditions = " AND id IN (SELECT DISTINCT(vendor_id) FROM tbl_po WHERE brand_id='194') ";


	$sSQL = "SELECT id, country FROM tbl_countries WHERE matrix='Y' ORDER BY country";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iCountryId = $objDb->getField($i, 0);
		$sCountry   = $objDb->getField($i, 1);

		$sSQL = "SELECT id, vendor,
		                (SELECT COUNT(*) FROM tbl_vendor_profile_pictures WHERE vendor_id=tbl_vendors.id) AS _Pictures
		         FROM tbl_vendors
		         WHERE country_id='$iCountryId' AND parent_id='0' AND sourcing='Y' $sConditions
		         ORDER BY vendor";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		if ($iCount2 == 0)
			continue;
?>
			        <br />
			        <h2><?= $sCountry ?></h2>

			        <table border="0" cellpadding="3" cellspacing="0" width="100%">
<?
		for ($j = 0; $j < $iCount2;)
		{
?>
			          <tr>
<?
			for ($k = 0; $k < 3; $k ++)
			{
				if ($j < $iCount2)
				{
					$iVendorId = $objDb2->getField($j, 0);
					$sVendor   = $objDb2->getField($j, 1);
					$iPictures = $objDb2->getField($j, 2);
?>
			            <td width="33%"><b>&raquo;</b> <a href="libs/vendor-profile.php?Id=<?= $iVendorId ?>"<?= (($iPictures == 0) ? ' style="color:#ff0000;"' : '') ?>><?= $sVendor ?></a></td>
<?
				 	$j ++;
				}

				else
				{
?>
			            <td width="33%"></td>
<?
				}
			}
?>
			          </tr>
<?
		}
?>
			        </table>
<?
	}
?>
			      </div>
			    </div>
			  </td>

			  <td width="5"></td>

			  <td>
<?
	@include($sBaseDir."includes/sign-in.php");
?>

			    <div style="height:5px;"></div>

<?
	@include($sBaseDir."includes/contact-info.php");
?>
			  </td>
			</tr>
		  </table>
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