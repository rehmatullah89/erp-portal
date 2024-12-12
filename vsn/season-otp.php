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

	$PageId      = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Brand       = IO::intValue("Brand");
        
        if (@strpos($_SESSION["Brands"], ",") === FALSE)
            $Brand = $_SESSION["Brands"];
        
	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ({$_SESSION['Brands']})");
        $sSeasonsList = getList("tbl_seasons", "id", "season", "parent_id>'0'");
        
    if ($Brand > 0)
	{
		$iParent           = getDbValue("parent_id", "tbl_brands", "id='$Brand'");
		$sSeasonsList      = getList("tbl_seasons", "id", "season", "brand_id='$iParent' AND parent_id>'0'");
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
			    <h1>Season OTP</h1>

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
        
	if ($Brand > 0){
            $sConditions .= " AND po.brand_id='$Brand' ";
            $BrandOrSeaon = 'Season';
            $query = "SELECT s.sub_season_id, pc.order_qty, pc.ontime_qty
			 FROM tbl_po po, tbl_po_colors pc, tbl_styles s
			 WHERE po.id=pc.po_id AND pc.style_id=s.id $sConditions";
        }
        else{
            $sConditions .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";
            $BrandOrSeaon = 'Brand';
            $query = "SELECT po.brand_id, COALESCE(SUM(pc.order_qty), 0), COALESCE(SUM(pc.ontime_qty), 0)
			FROM tbl_po po, tbl_po_colors pc, tbl_styles s
			WHERE po.id=pc.po_id AND pc.style_id=s.id $sConditions GROUP BY po.brand_id";
        }
        
	//@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_po", $sConditions, $iPageSize, $PageId);
        $sBgColor = "";

?>		
		<table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
		<tr  class="headerRow">
			<td width="40%"><b><?= $BrandOrSeaon ?></b></td>
			<td width="20%"><b>Order Qty</b></td>
			<td width="20%"><b>OnTime Qty</b></td>
			<td width="20%"><b>OTP</b></td>
		</tr>
<?		
		$sClass     = array("evenRow", "oddRow");
		$iOrderQty  = array( );
		$iOnTimeQty = array( );
		$objDb->query($query);

		$iCount = $objDb->getCount( );
		if($iCount > 0){
			for ($i = 0; $i < $iCount; $i ++)
			{
                            if ($Brand > 0){
                                $iSeasonId  = $objDb->getField($i, 0);
                                $iOrderQty[$iSeasonId]  = $objDb->getField($i, 1);
                                $iOnTimeQty[$iSeasonId] = $objDb->getField($i, 2);
                            }else{
                                $iBrandId  = $objDb->getField($i, 0);
                                $iOrderQty[$iBrandId]  = $objDb->getField($i, 1);
                                $iOnTimeQty[$iBrandId] = $objDb->getField($i, 2);
                            }
			}
                        
			$j=0;
			foreach ($iOrderQty as $iBrandnSeasonId => $iBrandQty)
			{
                                $fOtp = @round((($iOnTimeQty[$iBrandnSeasonId] / $iBrandQty) * 100), 2);
	?>
				<tr class="<?= $sClass[($j++ % 2)] ?>" valign="top" <?= $sBgColor ?>>
					<td width="40%"><?= ($Brand > 0)?$sSeasonsList[$iBrandnSeasonId]:$sBrandsList[$iBrandnSeasonId] ?></td>
					<td width="20%"><?= formatNumber($iBrandQty, false)?></td>
					<td width="20%"><?= formatNumber($iOnTimeQty[$iBrandnSeasonId], false)?></td>
					<td width="20%"><?= formatNumber($fOtp).'%' ?></td>
				</tr>
	<?		}
		}else{
	?>
				    <tr>
				      <td class="noRecord">No Record Found!</td>
				    </tr>
<?
	}
?>
			      </table>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords);

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
	$objDbGlobal->close( );

	@ob_end_flush( );
?>