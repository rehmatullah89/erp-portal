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

	$PageId    = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$OrderNo   = IO::strValue("OrderNo");
	$Vendor    = IO::intValue("Vendor");
	$Brand     = IO::intValue("Brand");
	$FromDate  = IO::strValue("FromDate");
	$ToDate    = IO::strValue("ToDate");
	$Status    = IO::strValue("Status");
	$SortOrder = ((IO::strValue("SortOrder") == "") ? "ASC" : IO::strValue("SortOrder"));

	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ({$_SESSION['Brands']})");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/vsr/vsr-data.js"></script>
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
			    <h1>VSR Data</h1>

<?
	if ($sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="vsr/import-vsr.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnImport').disabled=true;">
				<h2>Import VSR File</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="90">Category<span class="mandatory">*</span></td>
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

				  <tr>
					<td>BTX Division<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="BtxDivision">
						<option value=""></option>
						<option value="Y">Yes</option>
	  	        		<option value="N">No</option>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Adidas/Reebok<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="AdidasReebok">
	  	        		<option value="N">No</option>
						<option value="Y">Yes</option>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td align="right"><input type="checkbox" name="Notify" value="Y" /></td>
					<td align="center">:</td>
					<td>Send VSR Update Notifications</td>
				  </tr>

				  <tr>
					<td>VSR File<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="file" name="VsrFile" value="" size="30" class="file" /></td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar"><input type="submit" id="BtnImport" value="" class="btnImport" title="Import" onclick="return validateForm( );" /></div>
			    </form>

			    <hr />
<?
	}
?>

			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="32">PO #</td>
			          <td width="110"><input type="text" name="OrderNo" value="<?= $OrderNo ?>" class="textbox" maxlength="50" size="12" /></td>
			          <td width="52">Vendor</td>

			          <td width="170">
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

			          <td width="45">Brand</td>

			          <td width="140">
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

					  <td width="266">

					    <table border="0" cellpadding="0" cellspacing="0" width="266">
						  <tr>
						    <td width="40">From</td>
						    <td width="78"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
						    <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
						    <td width="30" align="center">To</td>
						    <td width="78"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
						    <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
						  </tr>
					    </table>

					  </td>

			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>

			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr>
<!--
					  <td width="48">Status</td>

					  <td width="110">
					    <select name="Status">
						  <option value="">Any Status</option>
	  	        		  <option value="On-Time"<?= (($Status == "On-Time") ? " selected" : "") ?>>On-Time</option>
	  	        		  <option value="Off-Time"<?= (($Status == "Off-Time") ? " selected" : "") ?>>Off-Time</option>
					    </select>
					  </td>
-->
					  <td width="75">Sort Order</td>

					  <td>
					    <select name="SortOrder">
	  	        		  <option value="ASC"<?= (($SortOrder == "ASC") ? " selected" : "") ?>>ETD Ascending</option>
	  	        		  <option value="DESC"<?= (($SortOrder == "DESC") ? " selected" : "") ?>>ETD Decending</option>
					    </select>
					  </td>
				    </tr>
				  </table>
			    </div>
			    </form>

<?
	$sClass      = array("evenRow", "evenRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = " WHERE po.id=vsr.po_id";

	if ($OrderNo != "")
		$sConditions .= " AND po.order_no LIKE '%$OrderNo%' ";

	if ($Vendor > 0)
		$sConditions .= " AND po.vendor_id='$Vendor' ";

	else
		$sConditions .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Brand > 0)
		$sConditions .= " AND po.brand_id='$Brand' ";

	else
		$sConditions .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

/*
	if ($Brand != 0)
	{
		$sSQL = "SELECT po_id FROM tbl_po_colors WHERE style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand')";



		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sPos   = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (",".$objDb->getField($i, 0));

		if ($sPos != "")
			$sPos = substr($sPos, 1);

		$sConditions .= " AND po.id IN ($sPos) ";
	}

	else
	{
		$sSQL = "SELECT po_id FROM tbl_po_colors WHERE style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id IN ({$_SESSION['Brands']}))";

		if ($FromDate != "" && $ToDate != "")
			$sSQL .= " AND (etd_required BETWEEN '$FromDate' AND '$ToDate') ";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sPos   = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (",".$objDb->getField($i, 0));

		if ($sPos != "")
			$sPos = substr($sPos, 1);

		$sConditions .= " AND po.id IN ($sPos) ";
	}
*/

	if ($FromDate != "" && $ToDate != "")
	{
		$sSQL = "SELECT po_id FROM tbl_po_colors WHERE etd_required BETWEEN '$FromDate' AND '$ToDate'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sPos   = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (",".$objDb->getField($i, 0));

		if ($sPos != "")
			$sPos = substr($sPos, 1);

		$sConditions .= " AND po.id IN ($sPos) ";
	}
/*
	if ($Status != "")
	{
		if ($Status == "Off-Time")
			$sConditions .= " AND (
								 (CURDATE( ) > vsr.dyeing_end_date AND vsr.dyeing < 100) OR
								 (CURDATE( ) > vsr.cutting_end_date AND vsr.cutting < 100) OR
								 (CURDATE( ) > vsr.stitching_end_date AND vsr.stitching < 100) OR
								 (CURDATE( ) > vsr.final_audit_date AND vsr.packing < 100) OR
								 (DATE_ADD(vsr.final_audit_date, INTERVAL 7 DAY) > DATE_ADD(DATE_FORMAT(LEFT(po.shipping_dates, 10), '%Y-%m-%d'), INTERVAL 2 DAY))
							   )";

		else if ($Status == "On-Time")
			$sConditions .= " AND ((CURDATE( ) > vsr.dyeing_end_date AND vsr.dyeing = 100) OR CURDATE( ) < vsr.dyeing_end_date)
			                  AND ((CURDATE( ) > vsr.cutting_end_date AND vsr.cutting = 100) OR CURDATE( ) < vsr.cutting_end_date)
							  AND ((CURDATE( ) > vsr.stitching_end_date AND vsr.stitching = 100) OR CURDATE( ) < vsr.stitching_end_date)
							  AND ((CURDATE( ) > vsr.final_audit_date AND vsr.packing = 100) OR CURDATE( ) < vsr.final_audit_date)
							  AND (DATE_ADD(vsr.final_audit_date, INTERVAL 7 DAY) <= DATE_ADD(DATE_FORMAT(LEFT(po.shipping_dates, 10), '%Y-%m-%d'), INTERVAL 2 DAY))";
	}
*/
	$sSQL = "SELECT COUNT(*) FROM tbl_po po, tbl_vsr vsr $sConditions";

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo($sSQL, "", $iPageSize, $PageId);

	$sSQL = "SELECT vsr.po_id, po.order_no, po.order_status, po.vendor_id, po.shipping_dates, po.quantity, vsr.style_id, vsr.dyeing, vsr.dyeing_end_date, vsr.cutting, vsr.cutting_end_date, vsr.stitching, vsr.stitching_end_date, vsr.packing, vsr.final_audit_date
	         FROM tbl_po po, tbl_vsr vsr
	         $sConditions
	         ORDER BY LEFT(po.shipping_dates, 10) $SortOrder
	         LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	if ($iCount > 0)
	{
?>
<!--
			    <div class="tblSheet" style="margin-bottom:4px;">
				  <h1 class="green small"><img src="images/h1/vsr/legends.jpg" width="78" height="15" alt="" title="" style="margin-top:6px;" /></h1>

				  <div style="padding:10px;">
				    <table border="0" cellpadding="0" cellspacing="0" width="100%">
					  <tr>
					    <td width="22"><div style="width:14px; height:14px; background:#dddddd;"></div></td>
					    <td width="110">Dyeing</td>
					    <td width="22"><div style="width:14px; height:14px; background:#dff391;"></div></td>
					    <td width="110">Cutting</td>
					    <td width="22"><div style="width:14px; height:14px; background:#718ba3;"></div></td>
					    <td width="110">Stitching</td>
					    <td width="22"><div style="width:14px; height:14px; background:#777777;"></div></td>
					    <td width="110">Packing</td>
					    <td width="22"><div style="width:14px; height:14px; background:#e8192b;"></div></td>
					    <td>ETD</td>
					  </tr>
				    </table>
				  </div>
				</div>
-->

<?
	}
?>

			    <div class="tblSheet">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow">
				      <td width="8%">#</td>
				      <td width="13%">Order No</td>
				      <td width="20%">Vendor</td>
				      <td width="13%">Brand</td>
				      <td width="14%">Style No</td>
				      <td width="12%">ETD Required</td>
				      <td width="9%">Quantity</td>
				      <td width="11%" class="center">Options</td>
				    </tr>
<?
		}

		$iPoId             = $objDb->getField($i, 'po_id');
		$iStyleId          = $objDb->getField($i, 'style_id');
		$sEtdRequired      = substr($objDb->getField($i, 'shipping_dates'), 0, 10);
		$iDyeing           = $objDb->getField($i, 'dyeing');
		$iCutting          = $objDb->getField($i, 'cutting');
		$iStitching        = $objDb->getField($i, 'stitching');
		$iPacking          = $objDb->getField($i, 'packing');
		$sDyeEndDate       = $objDb->getField($i, 'dyeing_end_date');
		$sCuttingEndDate   = $objDb->getField($i, 'cutting_end_date');
		$sStitchingEndDate = $objDb->getField($i, 'stitching_end_date');
		$sFinalAuditDate   = $objDb->getField($i, 'final_audit_date');

		$iDyeEndDate       = strtotime($sDyeEndDate);
		$iCuttingEndDate   = strtotime($sCuttingEndDate);
		$iStitchingEndDate = strtotime($sStitchingEndDate);
		$iFinalAuditDate   = strtotime($sFinalAuditDate);
		$iEtdRequired      = strtotime($sEtdRequired);
		$iToday            = strtotime(date("Y-m-d"));

		if ($iStyleId == 0)
		{
			$sSQL = "SELECT style_id FROM tbl_po_colors WHERE po_id='$iPoId' LIMIT 1";
			$objDb2->query($sSQL);

			$iStyleId = $objDb2->getField(0, 0);
		}

		$sSQL = "SELECT style, sub_brand_id FROM tbl_styles WHERE id='$iStyleId'";
		$objDb2->query($sSQL);

		$sStyle = $objDb2->getField(0, 0);
		$iBrand = $objDb2->getField(0, 1);


		$sBgColor = "";
/*
		if ($iToday > $iDyeEndDate && $iDyeing < 100)
			$sBgColor = 'style="background:#dddddd;"';

		else if ($iToday > $iCuttingEndDate && $iCutting < 100)
			$sBgColor = 'style="background:#dff391;"';

		else if ($iToday > $iStitchingEndDate && $iStitching < 100)
			$sBgColor = 'style="background:#718ba3;"';

		else if ($iToday > $iFinalAuditDate && $iPacking < 100)
			$sBgColor = 'style="background:#777777;"';

		else if (($iFinalAuditDate + 604800) > ($iEtdRequired + 172800))
			$sBgColor = 'style="background:#e8192b"';
*/
?>


				    <tr class="<?= $sClass[($i % 2)] ?>" <?= $sBgColor ?>>
				      <td><?= ($iStart + $i + 1) ?></td>
<?
		if (checkUserRights("view-purchase-order.php", "Data Entry", "view"))
		{
?>
				      <td><a href="data/view-purchase-order.php?Id=<?= $iPoId ?>" class="lightview sheetLink" rel="iframe" title="PO # <?= $objDb->getField($i, 'order_no') ?> :: :: width: 700, height: 550"><?= $objDb->getField($i, 'order_no') ?></a></td>
<?
		}

		else
		{
?>
				      <td><?= $objDb->getField($i, 'order_no') ?></td>
<?
		}
?>
				      <td><?= $sVendorsList[$objDb->getField($i, 'vendor_id')] ?></td>
				      <td><?= $sBrandsList[$iBrand] ?></td>
				      <td><?= $sStyle ?></td>
				      <td><?= formatDate($sEtdRequired) ?></td>
				      <td><?= formatNumber($objDb->getField($i, 'quantity'), false) ?></td>

				      <td class="center">
<?
		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="vsr/edit-vsr-po.php?Id=<?= $iPoId ?>&PO=<?= urlencode($objDb->getField($i, 'order_no').' '.$objDb->getField($i, 'order_status')) ?>"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="vsr/delete-vsr-po.php?Id=<?= $iPoId ?>" onclick="return confirm('Are you SURE, You want to Delete this PO Status Report?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
				        &nbsp;
<?
		}
?>
				        <a href="vsr/view-vsr-po.php?Id=<?= $iPoId ?>" class="lightview" rel="iframe" title="PO # <?= $objDb->getField($i, 'order_no').' '.$objDb->getField($i, 'order_status') ?> :: :: width: 520, height: 460"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a>
				      </td>
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

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&OrderNo={$OrderNo}&Vendor={$Vendor}&Brand={$Brand}&FromDate={$FromDate}&ToDate={$ToDate}&Status={$Status}&SortOrder={$SortOrder}");
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