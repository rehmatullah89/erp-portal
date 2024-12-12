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

	$PageId    = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$WorkOrder = IO::strValue("WorkOrder");
	$Vendor    = IO::intValue("Vendor");
	$Brand     = IO::intValue("Brand");
	$FromDate  = IO::strValue("FromDate");
	$ToDate    = IO::strValue("ToDate");
	$Season    = IO::intValue("Season");

	$sVendorsList      = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList       = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ({$_SESSION['Brands']})");
	$sSeasonsList      = getList("tbl_seasons", "id", "season", "parent_id>'0'");
	$sDestinationsList = getList("tbl_destinations", "id", "destination");

	if ($Brand > 0)
	{
		$iParent      = getDbValue("parent_id", "tbl_brands", "id='$Brand'");
		$sSeasonsList = getList("tbl_seasons", "id", "season", "brand_id='$iParent' AND parent_id>'0'");
	}

	$sStagesList = getList("tbl_production_stages", "id", "title", "", "position");
	$sStagesType = getList("tbl_production_stages", "id", "type", "", "position");


	$sPrefix = "";

	if (@strpos($_SESSION["Email"], "@apparelco.com") === FALSE && @strpos($_SESSION["Email"], "@3-tree.com") === FALSE)
		$sPrefix = "vsr_";


	if (!$_GET && ($FromDate == "" || $ToDate == ""))
	{
		$FromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 7), date("Y")));
		$ToDate   = date("Y-m-d");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/jquery.js"></script>
  <script type="text/javascript" src="scripts/jquery-ui.js"></script>
  <script type="text/javascript" src="scripts/jquery.jeditable.js"></script>
  <script type="text/javascript" src="scripts/jquery.jeditable.datepicker.js"></script>
  <script type="text/javascript" src="scripts/jquery.fixed_table_rc.js"></script>
  <script type="text/javascript" src="scripts/vsr/work-order-details.js"></script>

  <link type="text/css" rel="stylesheet" href="css/jquery.fixed_table_rc.css" />
  <link type="text/css" rel="stylesheet" href="css/jquery.css" />
</head>

<body>

<div id="MainDiv" style="width:98%;">
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
			    <h1>Work Order Details</h1>

			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="55">Vendor</td>

			          <td width="180">
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

			          <td width="160">
			            <select name="Brand" id="Brand" onchange="getListValues('Brand', 'Season', 'BrandSeasons');">
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

			          <td width="95">Work Order #</td>
			          <td width="160"><input type="text" name="WorkOrder" value="<?= $WorkOrder ?>" class="textbox" maxlength="50" size="50" /></td>
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>

			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr>
			          <td width="55">Season</td>

			          <td width="180">
					    <select name="Season" id="Season">
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

					  <td width="40">From</td>
					  <td width="78"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30" align="center">To</td>
					  <td width="78"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td>[ <a href="#" onclick="$('FromDate').value=''; $('ToDate').value=''; return false;">Clear</a> ]</td>
				    </tr>
				  </table>
			    </div>
			    </form>

<?
	$sClass      = array("evenRow", "evenRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "WHERE po.id=pc.po_id AND pc.po_id=vd.po_id AND pc.style_id=vd.style_id AND pc.id=vd.color_id AND vsr.id=vd.work_order_id AND po.status!='C' AND po.accepted='Y' AND po.order_nature='B' ";

	if ($Vendor > 0)
		$sConditions .= " AND po.vendor_id='$Vendor' ";

	else
		$sConditions .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Brand > 0)
		$sConditions .= " AND po.brand_id='$Brand' ";

	else
		$sConditions .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

	if ($Season > 0)
		$sConditions .= " AND pc.style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand' AND sub_season_id='$Season') ";

	if ($WorkOrder != "")
	{
		if (@strpos($WorkOrder, ",") !== FALSE)
		{
			$sWorkOrders  = @explode(",", $WorkOrder);
			$sConditions .= " AND (";

			for ($i = 0; $i < count($sWorkOrders); $i ++)
			{
				$sConditions .= (($i > 0) ? " OR " : "");
				$sConditions .= " vsr.work_order_no LIKE '{$sWorkOrders[$i]}'";
			}

			$sConditions .= ")";
		}

		else
			$sConditions .= " AND vsr.work_order_no LIKE '$WorkOrder'";
	}

	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND (pc.{$sPrefix}etd_required BETWEEN '$FromDate' AND '$ToDate') ";


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_po po, tbl_po_colors pc, tbl_vsr_details vd, tbl_vsr2 vsr", $sConditions, $iPageSize, $PageId);



	$sSQL = "SELECT DISTINCT(po.id) FROM tbl_po po, tbl_po_colors pc, tbl_vsr_details vd, tbl_vsr2 vsr $sConditions";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$sPos   = "0";

	for ($i = 0; $i < $iCount; $i ++)
		$sPos .= (",".$objDb->getField($i, 0));


	$sSQL = "SELECT DISTINCT(pc.style_id) FROM tbl_po po, tbl_po_colors pc, tbl_vsr_details vd, tbl_vsr2 vsr $sConditions";
	$objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	$sStyles = "0";

	for ($i = 0; $i < $iCount; $i ++)
		$sStyles .= (",".$objDb->getField($i, 0));


	$sSQL = "SELECT DISTINCT(category_id) FROM tbl_styles WHERE FIND_IN_SET(id, '$sStyles')";
	$objDb->query($sSQL);

	$iCount      = $objDb->getCount( );
	$sCategories = "0";

	for ($i = 0; $i < $iCount; $i ++)
		$sCategories .= (",".$objDb->getField($i, 0));


	if ($Brand == 0)
	{
		$sSQL = "SELECT DISTINCT(sub_brand_id) FROM tbl_styles WHERE FIND_IN_SET(id, '$sStyles')";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
			$Brand = $objDb->getField(0, 0);
	}

	$sStages      = getDbValue("GROUP_CONCAT(stages SEPARATOR ',')", "tbl_style_categories", "FIND_IN_SET(id, '$sCategories')");
	$sBrandStages = "";

	if ($Brand > 0)
		$sBrandStages = (" AND FIND_IN_SET(id, '".getDbValue("stages", "tbl_brands", "id='$Brand'")."') ");


	$sSQL = "SELECT DISTINCT(id) FROM tbl_production_stages WHERE FIND_IN_SET(id, '$sStages') $sBrandStages ORDER BY position";
	$objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	$iStages = array( );

	for ($i = 0; $i < $iCount; $i ++)
		$iStages[] = $objDb->getField($i, 0);


	$sSQL = "SELECT DISTINCT(pq.size_id), s.size
			 FROM tbl_po_colors pc, tbl_po_quantities pq, tbl_sizes s
			 WHERE pc.id=pq.color_id AND pc.po_id=pq.po_id AND FIND_IN_SET(pc.po_id, '$sPos') AND FIND_IN_SET(pc.style_id, '$sStyles') AND s.id=pq.size_id
			 ORDER BY s.position";
	$objDb->query($sSQL);

	$iCount     = $objDb->getCount( );
	$sSizesList =  array( );

	for ($i = 0; $i < $iCount; $i ++)
		$sSizesList[$objDb->getField($i, 0)] = $objDb->getField($i, 1);



	$sSQL = "SELECT po.vendor_id, po.customer, po.brand_id, CONCAT(po.order_no, ' ', po.order_status) AS _OrderNo, vsr.work_order_no, vsr.season_id, pc.*, vd.*,
	                (SELECT style FROM tbl_styles WHERE id=pc.style_id) AS _Style
	         FROM tbl_po po, tbl_po_colors pc, tbl_vsr_details vd, tbl_vsr2 vsr
	         $sConditions
	         ORDER BY vsr.work_order_no, _Style, _OrderNo
	         LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
?>


			    <div class="tblSheet" style="overflow:auto;">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		if ($i  == 0)
		{
?>
				    <thead>
                                        <tr class="headerRow" style="color: white;">
				      <th width="25">#</td>
				      <th width="100">WO #</td>
					  <th width="110">PO</td>
					  <th width="110">Style</td>
					  <th width="200">Color</td>
				      <th width="70">Season</td>
				      <th width="70">Quantity</td>
				      <th width="80">Brand</td>
				      <th width="120">Customer</td>
				      <th width="120">Vendor</td>
					  <th width="80" class="center">Price ($)</td>
					  <th width="110" class="center">ETD Required</td>
					  <th width="150">Destination</td>
					  <th width="100">PO Ref</td>
					  <th width="180">Fabric</td>
					  <th width="100" class="center">VSL Date</td>
					  <th width="100" class="center">Po Issue Date</td>
					  <th width="200">Notes</td>
<?
			foreach ($sSizesList as $iSize => $sSize)
			{
?>
				      <th width="60" class="center"><?= $sSize ?></td>
<?
			}
?>
				      <th width="60" class="center">Total</td>
<?
			foreach ($iStages as $iStage)
			{
?>
				      <th width="120" class="center"><?= $sStagesList[$iStage] ?><br />Start Date</td>
				      <th width="120" class="center"><?= $sStagesList[$iStage] ?><br />End Date</td>
				      <th width="120" class="center"><?= $sStagesList[$iStage] ?><br />Completed</td>
<?
			}
?>
				      <th width="100" class="center">Final Audit</td>
				      <th width="70" class="center">Ship Qty</td>
				      <th width="70" class="center">Balance</td>
				      <th width="300">Comments</td>
				    </tr>
				    </thead>

				    </tbody>
<?
		}


		$iWorkOrder   = $objDb->getField($i, 'vd.work_order_id');
		$sWorkOrder   = $objDb->getField($i, 'vsr.work_order_no');
		$iBrand       = $objDb->getField($i, 'po.brand_id');
		$sCustomer    = $objDb->getField($i, 'po.customer');
		$iVendor      = $objDb->getField($i, 'po.vendor_id');
		$iSeason      = $objDb->getField($i, 'vsr.season_id');
		$iColor       = $objDb->getField($i, 'vd.color_id');
		$iPo          = $objDb->getField($i, 'vd.po_id');
		$sPo          = $objDb->getField($i, '_OrderNo');
		$sStyle       = $objDb->getField($i, '_Style');
		$sColor       = $objDb->getField($i, 'pc.color');
		$fPrice       = $objDb->getField($i, "pc.{$sPrefix}price");
		$iStyle       = $objDb->getField($i, 'pc.style_id');
		$iDestination = $objDb->getField($i, 'pc.destination_id');
		$sEtdRequired = $objDb->getField($i, "pc.{$sPrefix}etd_required");
		$sFinalAudit  = $objDb->getField($i, 'vd.final_date');
		$iShipQty     = $objDb->getField($i, 'vd.ship_qty');
		$sComments    = $objDb->getField($i, 'vd.comments');
		$sPoRef       = $objDb->getField($i, 'vd.po_ref_no');
		$sFabric      = $objDb->getField($i, 'vd.fabric');
		$sVslDate     = $objDb->getField($i, 'vd.vsl_date');
		$sPoIssueDate = $objDb->getField($i, 'vd.po_issue_date');
		$sNotes       = $objDb->getField($i, 'vd.notes');
		$iOrderQty    = $objDb->getField($i, 'pc.order_qty');
?>

				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td><?= ($iStart + $i + 1) ?></td>
				      <td><?= $sWorkOrder ?></td>
					  <td><?= $sPo ?></td>
					  <td><?= $sStyle ?></td>
					  <td><?= $sColor ?></td>
				      <td><?= $sSeasonsList[$iSeason] ?></td>
				      <td class="center"><?= formatNumber($iOrderQty, false) ?></td>
				      <td><?= $sBrandsList[$iBrand] ?></td>
				      <td><?= $sCustomer ?></td>
				      <td><?= $sVendorsList[$iVendor] ?></td>
					  <td class="center"><span id="vsr_price|<?= $iColor ?>|<?= $iWorkOrder ?>" class="textEdit"><?= formatNumber($fPrice) ?></span></td>
					  <td class="center"><span id="vsr_etd_required|<?= $iColor ?>|<?= $iWorkOrder ?>" class="dateEdit"><?= formatDate($sEtdRequired, "m/d/Y") ?></span></td>
					  <td><?= $sDestinationsList[$iDestination] ?></td>
					  <td><span id="po_ref_no|<?= $iColor ?>|<?= $iWorkOrder ?>" class="textEdit"><?= $sPoRef ?></span></td>
					  <td><span id="fabric|<?= $iColor ?>|<?= $iWorkOrder ?>" class="textEdit"><?= $sFabric ?></span></td>
					  <td class="center"><span id="vsl_date|<?= $iColor ?>|<?= $iWorkOrder ?>" class="dateEdit"><?= formatDate($sVslDate, "m/d/Y") ?></span></td>
					  <td class="center"><span id="po_issue_date|<?= $iColor ?>|<?= $iWorkOrder ?>" class="dateEdit"><?= formatDate($sPoIssueDate, "m/d/Y") ?></span></td>
					  <td><span id="notes|<?= $iColor ?>|<?= $iWorkOrder ?>" class="textareaEdit"><?= $sNotes ?></span></td>
<?
		$iSubTotal = 0;

		foreach ($sSizesList as $iSize => $sSize)
		{
			$iQuantity = getDbValue("quantity", "tbl_po_quantities", "po_id='$iPo' AND color_id='$iColor' AND size_id='$iSize'");
?>
				      <td class="center"><?= formatNumber($iQuantity, false) ?></td>
<?
			$iSubTotal += $iQuantity;
		}
?>
				      <td class="center"><?= formatNumber($iSubTotal, false) ?></td>
<?
		foreach ($iStages as $iStage)
		{
			$sSQL = "SELECT start_date, end_date, completed FROM tbl_vsr_data WHERE work_order_id='$iWorkOrder' AND color_id='$iColor' AND stage_id='$iStage'";
			$objDb2->query($sSQL);

			if ($objDb2->getCount( ) == 1)
			{
				$sStartDate = $objDb2->getField(0, "start_date");
				$sEndDate   = $objDb2->getField(0, "end_date");
				$iCompleted = $objDb2->getField(0, "completed");
?>
				      <td class="center"><span id="start_date<?= $iStage ?>|<?= $iColor ?>|<?= $iWorkOrder ?>" class="dateEdit"><?= formatDate($sStartDate, "m/d/Y") ?></span></td>
				      <td class="center"><span id="end_date<?= $iStage ?>|<?= $iColor ?>|<?= $iWorkOrder ?>" class="dateEdit"><?= formatDate($sEndDate, "m/d/Y") ?></span></td>
				      <td class="center"><span id="completed<?= $iStage ?>|<?= $iColor ?>|<?= $iWorkOrder ?>" class="textEdit"><?= formatNumber($iCompleted, false, 0, false) ?></span> <?= (($sStagesType[$iStage] == "P") ? "%" : "Pcs") ?></td>
<?
			}

			else
			{
?>
				      <td class="center">-</td>
				      <td class="center">-</td>
				      <td class="center">-</td>
<?
			}
		}
?>
				      <td class="center"><span id="final_date|<?= $iColor ?>|<?= $iWorkOrder ?>" class="dateEdit"><?= formatDate($sFinalAudit, "m/d/Y") ?></span></td>
				      <td class="center"><span id="ship_qty|<?= $iColor ?>|<?= $iWorkOrder ?>" class="textEdit"><?= formatNumber($iShipQty, false) ?></span>   </td>
				      <td class="center"><span id=""><?= formatNumber(($iSubTotal - $iShipQty), false) ?></span></td>
				      <td><span id="comments|<?= $iColor ?>|<?= $iWorkOrder ?>" class="textareaEdit"><?= $sComments ?></span></td>
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
			        </tbody>
			      </table>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&WorkOrder={$WorkOrder}&Vendor={$Vendor}&Brand={$Brand}&FromDate={$FromDate}&ToDate={$ToDate}&Season={$Season}");


	if ($iCount > 0)
	{
?>
				<script type="text/javascript">
				<!--
					jQuery.noConflict( );

					jQuery(document).ready(function($)
					{
						 $('.textEdit').editable('ajax/vsr/update-work-order-status.php',
						 {
							 type        : 'text',
							 width       : '80px',
							 cancel      : '<',
							 submit      : 'OK',
							 indicator   : '',
							 tooltip     : '',
							 placeholder : 'n/a'
						 });


						 $('.textareaEdit').editable('ajax/vsr/update-work-order-status.php',
						 {
							 type        : 'textarea',
							 width       : '180px',
							 cancel      : '<',
							 submit      : 'OK',
							 indicator   : '',
							 tooltip     : '',
							 placeholder : 'n/a'
						 });


						 $('.dateEdit').editable('ajax/vsr/update-work-order-status.php',
						 {
							 type        : 'datepicker',
							 width       : '80px',
							 cancel      : '<',
							 submit      : 'OK',
							 indicator   : '',
							 tooltip     : '',
							 placeholder : 'n/a'
						 });

/*
						$('.tblSheet table').fxdHdrCol(
						{
							fixedCols :  7,
							width     :  "100%",
							height    :  "400",
							sort      :  false,

							colModal  :
							[
								{ width: 25, align: 'left' },
								{ width: 100, align: 'left' },
								{ width: 110, align: 'left' },
								{ width: 110, align: 'left' },
								{ width: 200, align: 'left' },
								{ width: 70, align: 'left' },
								{ width: 70, align: 'left' },
								{ width: 80, align: 'left' },
								{ width: 120, align: 'left' },
								{ width: 120, align: 'left' },
								{ width: 80, align: 'left' },
								{ width: 110, align: 'center' },
								{ width: 150, align: 'left' },
								{ width: 100, align: 'left' },
								{ width: 180, align: 'left' },
								{ width: 100, align: 'center' },
								{ width: 100, align: 'center' },
								{ width: 200, align: 'left' },
<?
			foreach ($sSizesList as $iSize => $sSize)
			{
?>
								{ width: 60, align: 'center' },
<?
			}
?>
								{ width: 60, align: 'center' },
<?
			foreach ($iStages as $iStage)
			{
?>
								{ width: 120, align: 'center' },
								{ width: 120, align: 'center' },
								{ width: 120, align: 'center' },
<?
			}
?>
								{ width: 100, align: 'center' },
								{ width: 70, align: 'center' },
								{ width: 70, align: 'center' },
								{ width: 300, align: 'left' }
							]
						});
*/
					});
				-->
				</script>
<?
	}


	if ($_GET  && $iCount > 0)
	{
?>
				<div class="buttonsBar" style="margin-top:4px;">
				  <input type="hidden" id="ExportUrl" name="ExportUrl" value="<?= (SITE_URL."vsr/export-work-order-details.php?WorkOrder={$WorkOrder}&Vendor={$Vendor}&Brand={$Brand}&FromDate={$FromDate}&ToDate={$ToDate}&Season={$Season}") ?>" />
				  <input type="button" value="" id="BtnExport" class="btnExport" title="Export" onclick="exportReport( );" />
				</div>
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