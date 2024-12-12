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

	$Brand = IO::intValue("Brand");

	if (@strpos($_SESSION["Brands"], ",") === FALSE)
		$Brand = $_SESSION["Brands"];


	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ({$_SESSION['Brands']})");
    $sSeasonsList = getList("tbl_seasons", "id", "season", "parent_id>'0'");

    if ($Brand > 0)
	{
		$iParent      = getDbValue("parent_id", "tbl_brands", "id='$Brand'");
		$sSeasonsList = getList("tbl_seasons", "id", "season", "brand_id='$iParent' AND parent_id>'0'");
	}
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
			    <h1>Seasons OTP</h1>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="45">Brand</td>

			          <td width="160">
			            <select name="Brand">
			              <option value="">All Brands</option>
<?
	foreach ($sBrandsList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Brand) ? " selected" : "") ?>><?= $sValue ?></option>
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

			    <div class="tblSheet">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;

	$sConditions = " AND po.order_nature='B' AND po.status='C' ";

	$query = "";
    $BrandOrSeaon = "";

	if ($Brand > 0)
	{
		$sConditions .= " AND po.brand_id='$Brand' ";


		$sSQL = "SELECT s.sub_season_id, COALESCE(SUM(pc.order_qty), 0) AS _OrderQty, COALESCE(SUM(pc.ontime_qty), 0) AS _OnTimeQty
				 FROM tbl_po po, tbl_po_colors pc, tbl_styles s
				 WHERE po.id=pc.po_id AND pc.style_id=s.id $sConditions
				 GROUP BY s.sub_season_id";
	}

	else
	{
		$sConditions .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";


		$sSQL = "SELECT po.brand_id, COALESCE(SUM(pc.order_qty), 0) AS _OrderQty, COALESCE(SUM(pc.ontime_qty), 0) AS _OnTimeQty
				 FROM tbl_po po, tbl_po_colors pc, tbl_styles s
				 WHERE po.id=pc.po_id AND pc.style_id=s.id $sConditions
				 GROUP BY po.brand_id";
	}

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
?>
					<tr  class="headerRow">
					  <td width="40%"><b><?= (($Brand > 0) ? "Season" : "Brand") ?></b></td>
					  <td width="20%"><b>Order Qty</b></td>
					  <td width="20%"><b>On-Time Qty</b></td>
					  <td width="20%"><b>OTP</b></td>
					</tr>
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrand     = $objDb->getField($i, "brand_id");
		$iSeason    = $objDb->getField($i, "sub_season_id");
		$iOrderQty  = $objDb->getField($i, "_OrderQty");
		$iOnTimeQty = $objDb->getField($i, "_OnTimeQty");


		$fOtp = @round((($iOnTimeQty / $iOrderQty) * 100), 2);
		$fOtp = (($fOtp > 100) ? 100 : $fOtp);
?>
					<tr class="<?= $sClass[($j++ % 2)] ?>">
					  <td><?= (($Brand > 0) ? $sSeasonsList[$iSeason] : $sBrandsList[$iBrand]) ?></td>
					  <td><?= formatNumber($iOrderQty, false) ?></td>
					  <td><?= formatNumber($iOnTimeQty, false) ?></td>
				 	  <td><?= formatNumber($fOtp) ?>%</td>
					</tr>
<?
	}


	if ($iCount == 0)
	{
?>
				    <tr>
				      <td class="noRecord">No Record Found!</td>
				    </tr>
<?
	}
?>
			      </table>
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
	$objDbGlobal->close( );

	@ob_end_flush( );
?>