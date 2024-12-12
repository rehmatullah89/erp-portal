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

	$PostId   = IO::strValue("PostId");
	$PageId   = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Vendor   = IO::strValue("Vendor");
	$OrderNo  = IO::strValue("OrderNo");
	$StyleNo  = IO::strValue("StyleNo");
	$Brand    = IO::intValue("Brand");
	$Region   = IO::intValue("Region");
	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");

	$sRegionsList = getList("tbl_countries", "id", "country", "matrix='Y'");
	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList  = getList("tbl_brands", "id", "brand", "id IN ({$_SESSION['Brands']})");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Vendor      = IO::strValue("Vendor");
		$Brand       = IO::strValue("Brand");
		$OrderNo     = IO::strValue("OrderNo");
		$OrderStatus = IO::strValue("OrderStatus");
		$AuditDate   = IO::strValue("AuditDate");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/quonda/csc-audits.js"></script>
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
			    <h1>csc audits</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="get" action="quonda/save-csc-audit.php" class="frmOutline">
				<h2>Add CSC Audit Report</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="75">Vendor<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Vendor">
						<option value=""></option>
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
				  </tr>

				  <tr>
					<td>Brand<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Brand">
						<option value=""></option>
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
				  </tr>

				  <tr>
					<td>Order No<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <input type="text" name="OrderNo" id="OrderNo" value="<?= $OrderNo ?>" maxlength="20" size="14" class="textbox" />

					  <select name="OrderStatus" id="OrderStatus">
						<option value=""></option>
						<option value="B">B</option>
						<option value="B *">B *</option>
						<option value="N">N</option>
						<option value="N *">N *</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.OrderStatus.value = "<?= $OrderStatus ?>";
					  -->
					  </script>
					</td>
				  </tr>

				  <tr>
					<td>Audit Date<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>

					  <table border="0" cellpadding="0" cellspacing="0" width="116">
						<tr>
						  <td width="82"><input type="text" name="AuditDate" id="AuditDate" value="<?= (($AuditDate == "") ? date('Y-m-d') : $AuditDate) ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('AuditDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('AuditDate'), 'yyyy-mm-dd', this);" /></td>
						</tr>
					  </table>

					</td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar"><input type="submit" id="BtnSubmit" value="" class="btnSubmit" title="Submit" onclick="return validateForm( );" /></div>
			    </form>

			    <hr />
<?
	}
?>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="55">Vendor</td>

			          <td width="180">
					    <select name="Vendor" style="width:170px;">
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

			          <td width="170">
			            <select name="Brand" style="width:160px;">
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

			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr>
			          <td width="65">Order No</td>
			          <td width="120"><input type="text" name="OrderNo" value="<?= $OrderNo ?>" class="textbox" maxlength="50" size="12" /></td>
			          <td width="62">Style No</td>
			          <td width="120"><input type="text" name="StyleNo" value="<?= $StyleNo ?>" class="textbox" maxlength="50" size="12" /></td>
					  <td width="50">Region</td>

					  <td width="130">
					    <select name="Region">
						  <option value="">All Regions</option>
<?
	foreach ($sRegionsList as $sKey => $sValue)
	{
?>
	  	        		  <option value="<?= $sKey ?>"<?= (($sKey == $Region) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
					  </td>

					  <td width="40">From</td>
					  <td width="78"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30" align="center">To</td>
					  <td width="78"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td>[ <a href="#" onclick="$('FromDate').value=''; $('ToDate').value=''; return false;">Clear</a> ]</td>
				    </tr>
				  </table>
			    </div>
			    </form>

			    <div class="tblSheet">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";

	if ($Vendor > 0)
		$sConditions .= " AND vendor_id='$Vendor' ";

	else
		$sConditions .= " AND vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Region > 0)
		$sConditions .= " AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND (audit_date BETWEEN '$FromDate' AND '$ToDate') ";

	if ($OrderNo != "")
	{
		$sConditions .= " AND (";


		$sSQL = "SELECT id FROM tbl_po WHERE order_no LIKE '%$OrderNo%'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iPoId = $objDb->getField($i, 0);

			if ($i > 0)
				$sConditions .= " OR ";

			$sConditions .= "po_id='$iPoId' OR FIND_IN_SET('$iPoId', additional_pos) ";
		}

		$sConditions .= ") ";
	}

	if ($Brand > 0)
	{
		$sSQL = "SELECT id FROM tbl_po WHERE brand_id='$Brand'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sPos   = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (",".$objDb->getField($i, 0));

		if ($sPos != "")
			$sPos = substr($sPos, 1);

		$sConditions .= " AND po_id IN ($sPos) ";
	}

	if ($StyleNo != "")
	{
		$sSQL = "SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE style_id IN (SELECT id FROM tbl_styles WHERE style LIKE '%$StyleNo%')";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sPos   = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (",".$objDb->getField($i, 0));

		if ($sPos != "")
		{
			$sPos = substr($sPos, 1);

			$sConditions .= " AND (po_id IN ($sPos) OR style_id IN (SELECT id FROM tbl_styles WHERE style LIKE '%$StyleNo%')) ";
		}

		else
			$sConditions .= " AND style_id IN (SELECT id FROM tbl_styles WHERE style LIKE '%$StyleNo%') ";

	}

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_csc_audits", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT id, po_id, vendor_id, brand_id, audit_result, audit_date, sample_size, quantity,
	                (SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_csc_audits.po_id) AS _OrderNo
	         FROM tbl_csc_audits
	         $sConditions
	         ORDER BY id DESC
	         LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="8%">#</td>
				      <td width="14%">PO</td>
				      <td width="15%">Vendor</td>
				      <td width="15%">Brand</td>
				      <td width="11%" class="center">Sample Size</td>
				      <td width="9%" class="center">Quantity</td>
				      <td width="10%" class="center">Audit Date</td>
				      <td width="8%" class="center">Result</td>
				      <td width="10%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId          = $objDb->getField($i, 'id');
		$iPoId        = $objDb->getField($i, 'po_id');
		$sOrderNo     = $objDb->getField($i, '_OrderNo');
		$iVendor      = $objDb->getField($i, 'vendor_id');
		$iBrand       = $objDb->getField($i, 'brand_id');
		$iSampleSize  = $objDb->getField($i, 'sample_size');
		$iQuantity    = $objDb->getField($i, 'quantity');
		$sAuditResult = $objDb->getField($i, 'audit_result');
		$sAuditDate   = $objDb->getField($i, 'audit_date');
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="8%"><?= ($iStart + $i + 1) ?></td>
<?
		if (checkUserRights("view-purchase-order.php", "Data Entry", "view"))
		{
?>
				      <td width="14%"><a href="data/view-purchase-order.php?Id=<?= $iPoId ?>" class="lightview sheetLink" rel="iframe" title="PO # <?= $sOrderNo ?> :: :: width: 700, height: 550"><?= $sOrderNo ?></a></td>
<?
		}

		else
		{
?>
				      <td width="14%"><?= $sOrderNo ?></td>
<?
		}
?>
				      <td width="15%"><?= $sVendorsList[$iVendor] ?></td>
				      <td width="15%"><?= $sBrandsList[$iBrand] ?></td>
				      <td width="11%" class="center"><?= formatNumber($iSampleSize, false) ?></td>
				      <td width="9%" class="center"><?= formatNumber($iQuantity, false) ?></td>
				      <td width="10%" class="center"><?= formatDate($sAuditDate) ?></td>
				      <td width="8%" class="center"><?= (($sAuditResult == "P") ? "Pass" : (($sAuditResult == "F") ? "Fail" : (($sAuditResult == "H") ? "Hold" : "N/A"))) ?></td>

				      <td width="10%" class="center">
<?
		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="quonda/edit-csc-audit.php?Id=<?= $iId ?>"><img src="images/icons/edit.gif" width="16" height="16" hspace="2" alt="Edit" title="Edit" /></a>
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="quonda/delete-csc-audit.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this CSC Audit Report?.');"><img src="images/icons/delete.gif" width="16" height="16" hspace="2" alt="Delete" title="Delete" /></a>
<?
		}
?>
				        <a href="quonda/view-csc-audit.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="CSC Audit : <?= $iId ?> :: :: width: 700, height: 550"><img src="images/icons/view.gif" width="16" height="16" hspace="2" alt="View" title="View" /></a>
				      </td>
				    </tr>
			      </table>
<?
	}

	if ($iCount == 0)
	{
?>
				  <div class="noRecord">No CSC Audit Report Found!</div>
<?
	}
?>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Vendor={$Vendor}&OrderNo={$OrderNo}&StyleNo={$StyleNo}&Brand={$Brand}&FromDate={$FromDate}&ToDate={$ToDate}");
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