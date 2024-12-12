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
  <script type="text/javascript" src="scripts/reports/commission-report.js"></script>
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
			    <h1>Commission Report</h1>

			    <form name="frmSearch" id="frmSearch" method="get" action="reports/export-commission-report.php" class="frmOutline" onsubmit="checkDoubleSubmission( );">
				<h2>Commission Report</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="85">Category</td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Category">
						<option value=""></option>
<?
	$sSQL = "SELECT id, category FROM tbl_categories ORDER BY category";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, 0);
		$sValue = $objDb->getField($i, 1);
?>
	  	        		<option value="<?= $sKey ?>"><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr valign="top">
					<td>Vendor</td>
					<td align="center">:</td>

					<td>
					  <select name="Vendor[]" size="10" multiple style="min-width:200px;">
<?
	$sSQL = "SELECT id, vendor FROM tbl_vendors WHERE id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y' ORDER BY vendor";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, 0);
		$sValue = $objDb->getField($i, 1);
?>
	  	        		<option value="<?= $sKey ?>"><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr valign="top">
					<td>Brand</td>
					<td align="center">:</td>

					<td>
					  <select name="Brand[]" id="Brand" size="10" multiple onchange="getSeasons( ); getCustomers( );" style="min-width:200px;">
<?
	$sSQL = "SELECT id, brand FROM tbl_brands WHERE id IN ({$_SESSION['Brands']}) ORDER BY brand";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, 0);
		$sValue = $objDb->getField($i, 1);
?>
	            		<option value="<?= $sKey ?>"><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr valign="top">
					<td>Season</td>
					<td align="center">:</td>

					<td>
					  <select name="Season[]" id="Season" size="10" multiple style="min-width:200px;">
					  </select>
					</td>
				  </tr>


				  <tr valign="top">
					<td>Customer</td>
					<td align="center">:</td>

					<td>
					  <select name="Customer[]" id="Customer" size="10" multiple style="min-width:200px;">
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Region</td>
					<td align="center">:</td>

					<td>
					  <select name="Region">
						<option value=""></option>
<?
	$sSQL = "SELECT id, country FROM tbl_countries WHERE matrix='Y' ORDER BY country";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, 0);
		$sValue = $objDb->getField($i, 1);
?>
	  	        		<option value="<?= $sKey ?>"><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr valign="top">
					<td>Currency</td>
					<td align="center">:</td>

					<td>
					  <select name="Currency">
<?
	$sSQL = "SELECT DISTINCT(currency) FROM tbl_po WHERE vendor_id IN ({$_SESSION['Vendors']}) AND brand_id IN ({$_SESSION['Brands']}) ORDER BY currency DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sCurrency = $objDb->getField($i, 0);
?>
	  	        		<option value="<?= $sCurrency ?>"><?= $sCurrency ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Shipment Date</td>
					<td align="center">:</td>

					<td>

					  <table border="0" cellpadding="0" cellspacing="0" width="320">
						<tr>
						  <td width="78"><input type="text" name="FromDate" value="" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="30" align="center">to</td>
						  <td width="78"><input type="text" name="ToDate" value="" id="ToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
						  <td align="right">[ <a href="./" onclick="$('FromDate').value=''; $('ToDate').value=''; return false;">Clear</a> ]</td>
						</tr>
					  </table>

					</td>
				  </tr>

				  <tr valign="top">
					<td>Report Type</td>
					<td align="center">:</td>

					<td>

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="25"><input type="radio" name="Type" value="Invoice" checked /></td>
						  <td>Invoice wise Commission Report</td>
					    </tr>

					    <tr>
						  <td><input type="radio" name="Type" value="Destination" /></td>
						  <td>Destination wise Commission Report</td>
					    </tr>

					    <tr>
						  <td><input type="radio" name="Type" value="Region" /></td>
						  <td>Region wise Commission Report</td>
					    </tr>

					    <tr>
						  <td><input type="radio" name="Type" value="Style" /></td>
						  <td>Style (Division) wise Commission Report</td>
					    </tr>

					    <tr>
						  <td><input type="radio" name="Type" value="Line" /></td>
						  <td>Line wise Commission Report</td>
					    </tr>
					  </table>

					</td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar">
				  <input type="submit" value="" id="BtnExport" class="btnExport" title="Export" />
				</div>
			    </form>
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