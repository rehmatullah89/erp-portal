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

	$PageId      = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$OrderNo     = IO::strValue("OrderNo");
	$Vendor      = IO::intValue("Vendor");
	$Brand       = IO::intValue("Brand");
	$Region      = IO::intValue("Region");
	$ShFromDate  = IO::strValue("ShFromDate");
	$ShToDate    = IO::strValue("ShToDate");
	$EtdFromDate = IO::strValue("EtdFromDate");
	$EtdToDate   = IO::strValue("EtdToDate");
	$Status      = IO::strValue("Status");
	$FinalAudit  = IO::strValue("FinalAudit");
	$Season      = IO::intValue("Season");

	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ({$_SESSION['Brands']})");
	$sSeasonsList = getList("tbl_seasons", "id", "season", "parent_id>'0'");

	if (@strpos($_SESSION["Brands"], ",") === FALSE)
		$Brand = $_SESSION["Brands"];

	if ($Brand > 0)
	{
		$iParent      = getDbValue("parent_id", "tbl_brands", "id='$Brand'");
		$sSeasonsList = getList("tbl_seasons", "id", "season", "brand_id='$iParent' AND parent_id>'0'");
	}

	if (!$_GET && ($FromDate == "" || $ToDate == ""))
	{
		$EtdFromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 7), date("Y")));
		$EtdToDate   = date("Y-m-d");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/shipping/pre-shipment-advice.js"></script>
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
			    <h1>Pre Shipment Advice</h1>

			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="35">PO #</td>
			          <td width="110"><input type="text" name="OrderNo" value="<?= $OrderNo ?>" class="textbox" maxlength="200" size="12" /></td>
			          <td width="52">Vendor</td>

			          <td width="185">
			            <select name="Vendor" style="width:175px;">
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

			          <td width="150">
			            <select name="Brand" id="Brand" style="width:140px;" onchange="getListValues('Brand', 'Season', 'BrandSeasons');">
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

					  <td width="50">Region</td>

					  <td>
					    <select name="Region">
						  <option value="">All Regions</option>
<?
	$sSQL = "SELECT id, country FROM tbl_countries WHERE matrix='Y' ORDER BY country";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, 0);
		$sValue = $objDb->getField($i, 1);
?>
	  	        		  <option value="<?= $sKey ?>"<?= (($sKey == $Region) ? " selected" : "") ?>><?= $sValue ?></option>
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
			          <td width="55">Season</td>

			          <td width="210">
			            <select name="Season" id="Season" style="width:200px;">
			              <option value="">All Seasons</option>
<?
	if ($Brand > 0)
	{
		foreach ($sSeasonsList as $sKey => $sValue)
		{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Season) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
	}
?>
			            </select>
			          </td>

					  <td width="115">Shipment Status</td>

					  <td width="170">
					    <select name="Status">
						  <option value="">All Shipments</option>
	  	        		  <option value="Delayed"<?= (($Status == "Delayed") ? " selected" : "") ?>>Delayed Shipments</option>
	  	        		  <option value="Short"<?= (($Status == "Short") ? " selected" : "") ?>>Short Shipments</option>
	  	        		  <option value="UnShipped"<?= (($Status == "UnShipped") ? " selected" : "") ?>>Un-Shipped</option>
					    </select>
					  </td>

					  <td width="80">Final Audit</td>

					  <td>
					    <select name="FinalAudit">
						  <option value="">All</option>
	  	        		  <option value="Y"<?= (($FinalAudit == "Y") ? " selected" : "") ?>>Done</option>
	  	        		  <option value="N"<?= (($FinalAudit == "N") ? " selected" : "") ?>>Not Done</option>
					    </select>
					  </td>
				    </tr>
				  </table>
			    </div>

			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr>
					  <td width="100">Shipped From</td>
					  <td width="78"><input type="text" name="ShFromDate" value="<?= $ShFromDate ?>" id="ShFromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ShFromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ShFromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30" align="center">To</td>
					  <td width="78"><input type="text" name="ShToDate" value="<?= $ShToDate ?>" id="ShToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ShToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ShToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="120">[ <a href="#" onclick="$('ShFromDate').value=''; $('ShToDate').value=''; return false;">Clear</a> ]</td>

					  <td width="70">ETD From</td>
					  <td width="78"><input type="text" name="EtdFromDate" value="<?= $EtdFromDate ?>" id="EtdFromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('EtdFromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('EtdFromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30" align="center">To</td>
					  <td width="78"><input type="text" name="EtdToDate" value="<?= $EtdToDate ?>" id="EtdToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('EtdToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('EtdToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td>[ <a href="#" onclick="$('EtdFromDate').value=''; $('EtdToDate').value=''; return false;">Clear</a> ]</td>
				    </tr>
				  </table>
			    </div>
			    </form>

			    <form name="frmData" id="frmData" method="post" action="shipping/toggle-po-status.php" onsubmit="$('BtnSubmit').disabled = true;">
			    <div class="tblSheet">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	$sClass         = array("evenRow", "oddRow");
	$iPageSize      = PAGING_SIZE;
	$iPageCount     = 0;
	$sConditions    = " WHERE po.id=psa.po_id";
	$sFinalAuditPos = "";

	if ($_SESSION['Guest'] == "Y")
		$sConditions .= " AND po.status='C' ";


	$sSQL = "SELECT DISTINCT(po.id)
	         FROM tbl_po po, tbl_po_colors pc, tbl_styles s, tbl_pre_shipment_advice psa";

	if ( ($ShFromDate != "" && $ShToDate != "") || $Status == "Delayed")
		$sSQL .= ", tbl_pre_shipment_detail psd ";

	$sSQL .= " WHERE po.id=pc.po_id AND pc.style_id=s.id AND psa.po_id=po.id AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}') ";

	if ( ($ShFromDate != "" && $ShToDate != "") || $Status == "Delayed")
		$sSQL .= " AND psd.po_id=po.id ";

	if ($OrderNo != "")
	{
		if (@strpos($OrderNo, ",") === FALSE)
			$sSQL .= " AND po.order_no LIKE '%$OrderNo%' ";

		else
		{
			$sPOs = @explode(",", $OrderNo);

			$sSQL .= " AND (";

			for ($i = 0; $i < count($sPOs); $i ++)
			{
				if ($i > 0)
					$sSQL .= " OR ";

				$sSQL .= " po.order_no LIKE '%{$sPOs[$i]}%' ";
			}

			$sSQL .= ")";
		}
	}

	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSQL .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' ";

	else
		$sSQL .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

	if ($Region > 0)
		$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	if ($Season > 0)
		$sSQL .= " AND s.sub_season_id='$Season' ";

	if ($EtdFromDate != "" && $EtdToDate != "")
		$sSQL .= " AND (pc.etd_required BETWEEN '$EtdFromDate' AND '$EtdToDate') ";

	if ($ShFromDate != "" && $ShToDate != "")
		$sSQL .= " AND (psd.shipping_date BETWEEN '$ShFromDate' AND '$ShToDate') ";

	if ($Status != "")
	{
		if ($Status == "Delayed")
			$sSQL .= " AND (NOT ISNULL(psd.handover_to_forwarder) AND psd.handover_to_forwarder != '0000-00-00' AND psd.handover_to_forwarder > pc.etd_required";

		else if ($Status == "Short")
			$sSQL .= " AND (psa.quantity < po.quantity AND psa.quantity > '0' AND po.status!='C') ";

		else if ($Status == "UnShipped")
			$sSQL .= " AND (psa.quantity='0' AND po.status!='C') ";
	}

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$sPos   = "";

	for ($i = 0; $i < $iCount; $i ++)
		$sPos .= (",".$objDb->getField($i, 0));

	if ($sPos != "")
		$sPos = substr($sPos, 1);

	$sConditions .= " AND po.id IN ($sPos) ";


	if ($FinalAudit != "")
	{
		$sSQL = "SELECT po_id, additional_pos FROM tbl_qa_reports WHERE audit_stage='F' AND audit_result IN ('P','A','B')";

		if ($Vendor > 0)
			$sSQL .= " AND vendor_id='$Vendor' ";

		else
			$sSQL .= " AND vendor_id IN ({$_SESSION['Vendors']}) ";

		if ($Brand > 0)
			$sSQL .= " AND brand_id='$Brand' ";

		else
			$sSQL .= " AND brand_id IN ({$_SESSION['Brands']}) ";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sFinalAuditPos .= (",".$objDb->getField($i, 0));

			if ($objDb->getField($i, 1) != "")
				$sFinalAuditPos .= (",".$objDb->getField($i, 1));
		}

		if ($sFinalAuditPos != "")
			$sFinalAuditPos = substr($sFinalAuditPos, 1);


		if ($FinalAudit == "Y")
			$sConditions .= " AND po.id IN ($sFinalAuditPos) ";

		else if ($FinalAudit == "N")
			$sConditions .= " AND po.id NOT IN ($sFinalAuditPos) ";
	}


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_po po, tbl_pre_shipment_advice psa", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT psa.po_id, psa.quantity, po.order_no, po.order_status, po.quantity, po.vendor_id, po.shipping_dates, po.status,
	                (SELECT invoice_no FROM tbl_pre_shipment_detail WHERE po_id=psa.po_id LIMIT 1) AS _Invoice,
	                (SELECT invoice_packing_list FROM tbl_pre_shipment_detail WHERE po_id=psa.po_id LIMIT 1) AS _InvoicePackingList,
	                (SELECT style FROM tbl_styles WHERE id IN (po.styles) ORDER BY id LIMIT 1) AS _Style
	         FROM tbl_po po, tbl_pre_shipment_advice psa
	         $sConditions
	         ORDER BY psa.po_id DESC
	         LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow">
				      <td width="8%">#</td>
				      <td width="12%">Order No</td>
				      <td width="15%">Vendor</td>
				      <td width="11%">Style No</td>
				      <td width="10%">PO ETD</td>
				      <td width="8%">Order Qty</td>
				      <td width="7%">Ship Qty</td>
				      <td width="29%" class="center">Options (<a href="#" onclick="checkAll( ); return false;">Check ALL</a> | <a href="#" onclick="clearAll( ); return false;">Clear ALL</a>)</td>
				    </tr>
<?
		}

		$iId                 = $objDb->getField($i, 'po_id');
		$sStatus             = $objDb->getField($i, 'status');
		$sInvoicePackingList = $objDb->getField($i, '_InvoicePackingList');
		$sFinalAuditDate     = "";

		switch ($sStatus)
		{
			case "C" : $sStatus = "closed"; break;
			default  : $sStatus = "working"; break;
		}


		$sSQL = "SELECT audit_date FROM tbl_qa_reports WHERE audit_stage='F' AND audit_result IN ('P','A','B') AND (po_id='$iId' OR FIND_IN_SET('$iId', additional_pos)) ORDER BY id LIMIT 1";
		$objDb2->query($sSQL);

		if ($objDb2->getCount( ) == 1)
		{
			$sFinalAuditDate = $objDb2->getField(0, 0);
			$sFinalAuditDate = formatDate($sFinalAuditDate);
		}


		$sColor = "";

		if ($objDb->getField($i, 'psa.quantity') < $objDb->getField($i, 'po.quantity') && $objDb->getField($i, 'psa.quantity') > 0 && $objDb->getField($i, 'psa.status') != "C")
			$sColor = 'style="background:#dff391;"';


		$sSQL = "SELECT DISTINCT(pc.po_id) FROM tbl_po_colors pc, tbl_pre_shipment_detail psd WHERE pc.po_id='$iId' AND pc.po_id=psd.po_id AND NOT ISNULL(psd.handover_to_forwarder) AND psd.handover_to_forwarder != '0000-00-00' AND psd.handover_to_forwarder > pc.etd_required";
		$objDb2->query($sSQL);

		if ($objDb2->getCount( ) > 0)
			$sColor = 'style="background:#f08891;"';
?>

				    <tr class="<?= $sClass[($i % 2)] ?>" <?= $sColor ?>>
				      <td><?= ($iStart + $i + 1) ?></td>
<?
		if (checkUserRights("view-purchase-order.php", "Data Entry", "view"))
		{
?>
				      <td><a href="data/view-purchase-order.php?Id=<?= $iId ?>" class="lightview sheetLink" rel="iframe" title="PO # <?= $objDb->getField($i, 'order_no').' '.$objDb->getField($i, 'order_status') ?> :: :: width: 800, height: 550"><?= $objDb->getField($i, 'order_no').' '.$objDb->getField($i, 'order_status') ?></a></td>
<?
		}

		else
		{
?>
				      <td><?= ($objDb->getField($i, 'order_no').' '.$objDb->getField($i, 'order_status')) ?></td>
<?
		}
?>
				      <td><?= $sVendorsList[$objDb->getField($i, 'vendor_id')] ?></td>
				      <td><?= $objDb->getField($i, '_Style') ?></td>
				      <td><?= formatDate(substr($objDb->getField($i, 'shipping_dates'), 0, 10)) ?></td>
				      <td><?= formatNumber($objDb->getField($i, 'po.quantity')) ?></td>
				      <td><?= formatNumber($objDb->getField($i, 'psa.quantity')) ?></td>

				      <td class="right">
<?
		if ($sFinalAuditDate != "")
		{
?>
				        <img src="images/icons/audit.png" width="16" height="16" alt="<?= $sFinalAuditDate ?>" title="<?= $sFinalAuditDate ?>" />
				        &nbsp;
<?
		}

		if ($objDb->getField($i, '_Invoice') != "")
		{
			if (checkUserRights("invoice-report.php", "Reports", "view"))
			{
?>
				        <a href="reports/export-invoice-report.php?Invoice=<?= urlencode($objDb->getField($i, '_Invoice')) ?>"><img src="images/icons/report.gif" width="16" height="16" alt="Invoice Report" title="Invoice Report" /></a>
				        &nbsp;
<?
			}

			if (checkUserRights("inspection-certificate.php", "Reports", "view"))
			{
?>
				        <a href="reports/export-inspection-certificate.php?Invoice=<?= urlencode($objDb->getField($i, '_Invoice')) ?>&PoId=<?= $iId ?>"><img src="images/icons/certificate.gif" width="16" height="16" alt="Inspection Certificate" title="Inspection Certificate" /></a>
				        &nbsp;
<?
			}
		}

		if ($sInvoicePackingList != "" && @file_exists($sBaseDir.PRE_SHIPMENT_DIR.$sInvoicePackingList))
		{
?>
				        <a href="<?= PRE_SHIPMENT_DIR.$sInvoicePackingList ?>" target="_blank"><img src="images/icons/pdf.gif" width="16" height="16" alt="Invoice Packing List" title="Invoice Packing List" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="shipping/edit-pre-shipment-detail.php?Id=<?= $iId ?>&PO=<?= urlencode($objDb->getField($i, 'order_no').' '.$objDb->getField($i, 'order_status')) ?>"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}

		if ($objDb->getField($i, 'psa.quantity') > 0)
		{
?>
				        <a href="shipping/view-shipment-deviation.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="PO # <?= $objDb->getField($i, 'order_no').' '.$objDb->getField($i, 'order_status') ?> :: :: width: 700, height: 450"><img src="images/icons/deviation.gif" width="16" height="16" alt="Deviation" title="Deviation" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="shipping/toggle-po-status.php?Id=<?= $iId ?>&Status=<?= (($sStatus == 'closed') ? 'W' : 'C') ?>"><img src="images/icons/<?= $sStatus ?>.png" width="16" height="16" border="0" alt="Toggle Status" title="Toggle Status" /></a>
				        &nbsp;
<?
		}

		else
		{
?>
				        <img src="images/icons/<?= $sStatus ?>.png" width="16" height="16" border="0" alt="" title="" />
				        &nbsp;
<?
		}

		if (getDbValue("COUNT(*)", "tbl_pre_shipment_detail", "po_id='$iId'") > 0)
		{
?>
				        <a href="shipping/view-pre-shipment-detail.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="PO # <?= $objDb->getField($i, 'order_no').' '.$objDb->getField($i, 'order_status') ?> :: :: width: 800, height: 550"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a>
<?
		}

		if ($sUserRights['Edit'] == "Y")
		{
?>
				        &nbsp;
				        <input type="checkbox" name="PO[]" class="po" value="<?= $iId ?>" />
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
				    <tr>
				      <td class="noRecord">No Shipment Record Found!</td>
				    </tr>
<?
	}
?>
			      </table>
			    </div>

<?
	if ($_SESSION['Guest'] != "Y")
	{
		showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&OrderNo={$OrderNo}&Vendor={$Vendor}&Brand={$Brand}&Season={$Season}&Region={$Region}&ShFromDate={$ShFromDate}&ShToDate={$ShToDate}&EtdFromDate={$EtdFromDate}&EtdToDate={$EtdToDate}&Status={$Status}&FinalAudit={$FinalAudit}");


		if ($iCount > 0 && (@strpos($_SESSION["Email"], "@apparelco.com") !== FALSE || @strpos($_SESSION["Email"], "@3-tree.com") !== FALSE))
		{
?>
				<div class="buttonsBar" style="margin-top:4px;">
				  <input type="hidden" id="ExportUrl" name="ExportUrl" value="<?= (SITE_URL."shipping/export-pre-shipment-advice.php?OrderNo={$OrderNo}&Vendor={$Vendor}&Brand={$Brand}&Season={$Season}&Region={$Region}&ShFromDate={$ShFromDate}&ShToDate={$ShToDate}&EtdFromDate={$EtdFromDate}&EtdToDate={$EtdToDate}&Status={$Status}&FinalAudit={$FinalAudit}") ?>" />
				  <input type="button" value="" id="BtnExport" class="btnExport" title="Export" onclick="exportReport( );" />
				</div>
<?
		}




		$sSQL = "SELECT DISTINCT(pc.id)
				 FROM tbl_po po, tbl_po_colors pc, tbl_styles s, tbl_pre_shipment_advice psa";

		if ( ($ShFromDate != "" && $ShToDate != "") || $Status == "Delayed")
			$sSQL .= ", tbl_pre_shipment_detail psd ";

		$sSQL .= " WHERE po.id=pc.po_id AND pc.style_id=s.id AND psa.po_id=po.id AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}') ";

		if ( ($ShFromDate != "" && $ShToDate != "") || $Status == "Delayed")
			$sSQL .= " AND psd.po_id=po.id ";

		if ($OrderNo != "")
			$sSQL .= " AND po.order_no LIKE '%$OrderNo%' ";

		if ($Vendor > 0)
			$sSQL .= " AND po.vendor_id='$Vendor' ";

		else
			$sSQL .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";

		if ($Brand > 0)
			$sSQL .= " AND po.brand_id='$Brand' ";

		else
			$sSQL .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

		if ($Season > 0)
			$sSQL .= " AND s.sub_season_id='$Season' ";

		if ($EtdFromDate != "" && $EtdToDate != "")
			$sSQL .= " AND (pc.etd_required BETWEEN '$EtdFromDate' AND '$EtdToDate') ";

		if ($ShFromDate != "" && $ShToDate != "")
			$sSQL .= " AND (psd.shipping_date BETWEEN '$ShFromDate' AND '$ShToDate') ";

		if ($Status != "")
		{
			if ($Status == "Delayed")
				$sSQL .= " AND (NOT ISNULL(psd.handover_to_forwarder) AND psd.handover_to_forwarder != '0000-00-00' AND psd.handover_to_forwarder > pc.etd_required";

			else if ($Status == "Short")
				$sSQL .= " AND (psa.quantity < po.quantity AND psa.quantity > '0' AND po.status!='C') ";

			else if ($Status == "UnShipped")
				$sSQL .= " AND (psa.quantity='0' AND po.status!='C') ";
		}

		if ($FinalAudit != "")
		{
			if ($FinalAudit == "Y")
				$sSQL .= " AND po.id IN ($sFinalAuditPos) ";

			else if ($FinalAudit == "N")
				$sSQL .= " AND po.id NOT IN ($sFinalAuditPos) ";
		}

		$objDb->query($sSQL);

		$iCount  = $objDb->getCount( );
		$sColors = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sColors .= (",".$objDb->getField($i, 0));

		if ($sColors != "")
			$sColors = substr($sColors, 1);

		$sSQL = "SELECT SUM(quantity) FROM tbl_po_quantities WHERE color_id IN ($sColors)";
		$objDb->query($sSQL);
?>
			    <div class="orderQty" style="padding-right:0px;">
			      <input type="submit" id="BtnSubmit" value="" class="btnSubmit" style="float:right;" onclick="return validateForm( );" />
				  Order Quantity: <?= formatNumber($objDb->getField(0, 0)) ?>
			    </div>
			    </form>

<?
		$sSQL = "SELECT DISTINCT(po.id)
				 FROM tbl_po po, tbl_po_colors pc, tbl_styles s, tbl_pre_shipment_advice psa";

		if ( ($ShFromDate != "" && $ShToDate != "") || $Status == "Delayed")
			$sSQL .= ", tbl_pre_shipment_detail psd ";

		$sSQL .= " WHERE po.id=pc.po_id AND pc.style_id=s.id AND psa.po_id=po.id AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}') ";

		if ( ($ShFromDate != "" && $ShToDate != "") || $Status == "Delayed")
			$sSQL .= " AND psd.po_id=po.id ";

		if ($OrderNo != "")
			$sSQL .= " AND po.order_no LIKE '%$OrderNo%' ";

		if ($Vendor > 0)
			$sSQL .= " AND po.vendor_id='$Vendor' ";

		else
			$sSQL .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";

		if ($Brand > 0)
			$sSQL .= " AND po.brand_id='$Brand' ";

		else
			$sSQL .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

		if ($Season > 0)
			$sSQL .= " AND s.sub_season_id='$Season' ";

		if ($EtdFromDate != "" && $EtdToDate != "")
			$sSQL .= " AND (pc.etd_required BETWEEN '$EtdFromDate' AND '$EtdToDate') ";

		if ($ShFromDate != "" && $ShToDate != "")
			$sSQL .= " AND (psd.shipping_date BETWEEN '$ShFromDate' AND '$ShToDate') ";

		if ($Status != "")
		{
			if ($Status == "Delayed")
				$sSQL .= " AND (NOT ISNULL(psd.handover_to_forwarder) AND psd.handover_to_forwarder != '0000-00-00' AND psd.handover_to_forwarder > pc.etd_required";

			else if ($Status == "Short")
				$sSQL .= " AND (psa.quantity < po.quantity AND psa.quantity > '0' AND po.status!='C') ";

			else if ($Status == "UnShipped")
				$sSQL .= " AND (psa.quantity='0' AND po.status!='C') ";
		}

		if ($FinalAudit != "")
		{
			if ($FinalAudit == "Y")
				$sSQL .= " AND po.id IN ($sFinalAuditPos) ";

			else if ($FinalAudit == "N")
				$sSQL .= " AND po.id NOT IN ($sFinalAuditPos) ";
		}

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sPos   = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (",".$objDb->getField($i, 0));

		if ($sPos != "")
			$sPos = substr($sPos, 1);


		$sSQL = "SELECT SUM(quantity) FROM tbl_pre_shipment_detail WHERE po_id IN ($sPos)";
		$objDb->query($sSQL);
?>
			    <div class="shipQty">Shipment Quantity: <?= formatNumber($objDb->getField(0, 0)) ?></div>
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