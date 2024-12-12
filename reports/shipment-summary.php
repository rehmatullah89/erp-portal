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
	$objSpDb     = new SpDatabase( );


	$Month = IO::strValue("Month");
	$Year  = IO::intValue("Year");

	if (!$_GET)
	{
		if ($Month == "")
			$Month = date("m");

		if ($Year == "")
			$Year = date("Y");
	}

	$sMonthsList = array('January','February','March','April','May','June','July','August','September','October','November','December');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <meta http-equiv="refresh" content="600" />
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
			    <h1>Shipment Summary</h1>

		      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
					  <td width="45">Month</td>

					  <td width="105">
					    <select name="Month">
<?
	for ($i = 1; $i <= 12; $i ++)
	{
?>
			              <option value="<?= $i ?>"<?= (($Month == $i) ? " selected" : "") ?>><?= $sMonthsList[($i - 1)] ?></option>
<?
	}
?>
					    </select>
					  </td>

					  <td width="35">Year</td>

					  <td>
					    <select name="Year">
					      <option value="">All</option>
<?
	for ($i = date("Y"); $i >= 2008; $i --)
	{
?>
			              <option value="<?= $i ?>"<?= (($Year == $i) ? " selected" : "") ?>><?= $i ?></option>
<?
	}
?>
					    </select>
					  </td>

			          <td width="103"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>
				</form>

<?
	$sClass  = array("evenRow", "oddRow");
	$sData   = array( );

	$sData[0]['StartDate'] = date("Y-m-d", mktime(0, 0, 0, ($Month - 1), "01", $Year));
	$sData[0]['EndDate']   = date("Y-m-t", mktime(0, 0, 0, ($Month - 1), "01", $Year));
	$sData[0]['Title']     = ("Previous Month (".date("F y", strtotime($sData[0]['StartDate'])).")");

	$sData[1]['StartDate'] = date("Y-m-01", mktime(0, 0, 0, $Month, "01", $Year));
	$sData[1]['EndDate']   = date("Y-m-t", mktime(0, 0, 0, $Month, "01", $Year));
	$sData[1]['Title']     = ("Current Month (".date("F y", strtotime($sData[1]['StartDate'])).")");

	for ($Index = 0; $Index < 2; $Index ++)
	{
		$sStartDate = $sData[$Index]['StartDate'];
		$sEndDate   = $sData[$Index]['EndDate'];
?>
			    <div class="tblSheet">
			      <h2 class="green" style="margin-bottom:1px;"><?= $sData[$Index]['Title'] ?></h2>

<?
		$sSQL = "SELECT id, country FROM tbl_countries WHERE matrix='Y' ORDER BY country";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iCountryId = $objDb->getField($i, "id");
			$sCountry   = $objDb->getField($i, "country");


			$sSQL = "CALL sp_brand_shipment_summary('$sStartDate', '$sEndDate', '{$_SESSION['Vendors']}', '{$_SESSION['Brands']}', '$iCountryId')";
			$objSpDb->query($sSQL);

			$iCount2 = $objSpDb->getCount( );


			$sCurrencies = array( );

			for ($j = 0; $j < $iCount2; $j ++)
			{
					$sCurrency = $objSpDb->getField($j, "_Currency");

					if (!@in_array($sCurrency, $sCurrencies))
						$sCurrencies[] = $sCurrency;
			}


			if ($iCount2 > 0)
			{
?>
				  <h2 style="margin-bottom:1px; background:#444444;"><?= $sCountry ?></h2>

<?
				foreach ($sCurrencies as $sCurrencyType)
				{
?>
				  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
					<tr class="headerRow">
					  <td width="25%"><b>Buyer</b></td>
					  <td width="25%"><b>Ship Qty (Pcs)</b></td>
<?
					if ($sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y" && $sUserRights['Delete'] == "Y")
					{
?>
					  <td width="25%"><b>FOB (<?= $sCurrencyType ?>)</b></td>
					  <td width="25%"><b>Commission (<?= $sCurrencyType ?>)</b></td>
<?
					}
?>
					</tr>
<?
					$iTotalQty = 0;
					$fTotalFob = 0;
					$fTotalCom = 0;

					for ($j = 0; $j < $iCount2; $j ++)
					{
						$sBrand      = $objSpDb->getField($j, "_Brand");
						$iShipQty    = $objSpDb->getField($j, "_ShipQty");
						$fFob        = $objSpDb->getField($j, "_Fob");
						$fCommission = $objSpDb->getField($j, "_Commission");
						$sCurrency   = $objSpDb->getField($j, "_Currency");

						if ($sCurrencyType != $sCurrency)
							continue;
?>
					<tr class="<?= $sClass[($j % 2)] ?>">
					  <td><?= $sBrand ?></td>
					  <td><?= formatNumber($iShipQty, false) ?></td>
<?
						if ($sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y" && $sUserRights['Delete'] == "Y")
						{
?>
					  <td><?= formatNumber($fFob) ?></td>
					  <td><?= formatNumber($fCommission) ?></td>
<?
						}
?>
					</tr>

<?
						$iTotalQty += $iShipQty;
						$fTotalFob += $fFob;
						$fTotalCom += $fCommission;
					}
?>
					<tr class="footerRow">
					  <td><b>Grand Total</b></td>
					  <td><b><?= formatNumber($iTotalQty, false) ?></b></td>
<?
					if ($sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y" && $sUserRights['Delete'] == "Y")
					{
?>
					  <td><b><?= formatNumber($fTotalFob) ?></b></td>
					  <td><b><?= formatNumber($fTotalCom) ?></b></td>
<?
					}
?>
					</tr>
				  </table>

<?
				}
			}
		}
?>
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
	$objSpDb->close( );

	@ob_end_flush( );
?>