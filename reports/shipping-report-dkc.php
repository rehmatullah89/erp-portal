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
        
        $sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
        $sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ({$_SESSION['Brands']})");
	$sSeasonsList = getList("tbl_seasons", "id", "season", "parent_id>'0'");

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
			    <h1>Shipping Report DKC</h1>

			    <form name="frmSearch" id="frmSearch" method="get" action="reports/export-shipping-report-dkc.php" class="frmOutline" onsubmit="checkDoubleSubmission( );">
				<h2>Shipping Report DKC</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
                                <tr valign="top">
                                      <td width="95">PO#</td>
					<td align="center" width="20">:</td>

					<td>
                                            <input type="text" name="OrderNo" value="<?= IO::strValue("OrderNo") ?>" class="textbox" maxlength="200" size="12" />
					</td>
				  </tr>  
				  <tr valign="top">
                                      <td width="85">Vendor</td>
					<td align="center" width="20">:</td>

					<td>
                                            <select name="Vendor" style="width: 200px;">
                                              <option value="">All Vendors</option>
<?
                                                foreach ($sVendorsList as $sKey => $sValue)
                                                {
?>
                                                        <option value="<?= $sKey ?>"<?= (($sKey == IO::intValue("Vendor")) ? " selected" : "") ?>><?= $sValue ?></option>
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
                                    <select name="Brand" id="Brand" style="width:140px;" onchange="getListValues('Brand', 'Season', 'BrandSeasons');">
			              <option value="">All Brands</option>
<?
	foreach ($sBrandsList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == IO::intValue("Brand")) ? " selected" : "") ?>><?= $sValue ?></option>
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
					 <select name="Season" id="Season" style="width:200px;">
			              <option value="">All Seasons</option>
<?
	if (IO::intValue("Brand") > 0)
	{
		foreach ($sSeasonsList as $sKey => $sValue)
		{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == IO::intValue("Season")) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
	}
?>
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
					<td>Shipment Status</td>
					<td align="center">:</td>

					<td>
					  <select name="Status">
						  <option value="">All Shipments</option>
	  	        		  <option value="Delayed"<?= ((IO::strValue("Status") == "Delayed") ? " selected" : "") ?>>Delayed Shipments</option>
	  	        		  <option value="Short"<?= ((IO::strValue("Status") == "Short") ? " selected" : "") ?>>Short Shipments</option>
	  	        		  <option value="UnShipped"<?= ((IO::strValue("Status") == "UnShipped") ? " selected" : "") ?>>Un-Shipped</option>
					    </select>
					</td>
				  </tr>
                                    
                                  <tr valign="top">
					<td>Final Audit</td>
					<td align="center">:</td>

					<td>
					  <select name="FinalAudit">
						  <option value="">All</option>
	  	        		  <option value="Y"<?= ((IO::strValue("FinalAudit") == "Y") ? " selected" : "") ?>>Done</option>
	  	        		  <option value="N"<?= ((IO::strValue("FinalAudit") == "N") ? " selected" : "") ?>>Not Done</option>
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
					<td>ETD From</td>
					<td align="center">:</td>
                                        <td>
                                            <table border="0" cellpadding="0" cellspacing="0" width="320">
						<tr>
						  <td width="78"><input type="text" name="EtdFromDate" value="" id="EtdFromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('EtdFromDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('EtdFromDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="30" align="center">to</td>
						  <td width="78"><input type="text" name="EtdToDate" value="" id="EtdToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('EtdToDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('EtdToDate'), 'yyyy-mm-dd', this);" /></td>
						  <td align="right">[ <a href="./" onclick="$('EtdFromDate').value=''; $('EtdToDate').value=''; return false;">Clear</a> ]</td>
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