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
	$PoType      = IO::strValue("PoType");
	$PoNature    = IO::strValue("PoNature");
	$Vendor      = IO::intValue("Vendor");
        $Parent      = IO::intValue("Parent");
	$Brand       = IO::intValue("Brand");
	$Region      = IO::intValue("Region");
	$Style       = IO::strValue("Style");
	$Season      = IO::intValue("Season");
	$Destination = IO::intValue("Destination");
	$FromDate    = IO::strValue("FromDate");
	$ToDate      = IO::strValue("ToDate");

	if (@strpos($_SESSION["Brands"], ",") === FALSE)
		$Brand = $_SESSION["Brands"];

        $sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
//	$sVendorsList = getList("tbl_vendors v", "v.id", "CONCAT(COALESCE((SELECT CONCAT(vendor, ' &raquo;&raquo; ') FROM tbl_vendors WHERE id=v.parent_id), ''), v.vendor) AS _Vendor", "FIND_IN_SET(v.id, '{$_SESSION['Vendors']}')", "_Vendor");

	$sRegionsList      = getList("tbl_countries", "id", "country", "matrix='Y'");
	$sBrandsList       = getList("tbl_brands", "id", "brand", "parent_id>'0' AND id IN ({$_SESSION['Brands']})");
	$sSeasonsList      = getList("tbl_seasons", "id", "season", "parent_id>'0'");
	$sDestinationsList = array( );

	if ($Brand > 0)
	{
		$iParent           = getDbValue("parent_id", "tbl_brands", "id='$Brand'");
		$sSeasonsList      = getList("tbl_seasons", "id", "season", "brand_id='$iParent' AND parent_id>'0'");
		$sDestinationsList = getList("tbl_destinations", "id", "destination", "brand_id='$iParent'");
	}


	$sPrefix = "";

	if (@strpos($_SESSION["Email"], "@apparelco.com") === FALSE && @strpos($_SESSION["Email"], "@3-tree.com") === FALSE)
		$sPrefix = "vsr_";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/purchase-orders.js"></script>
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
			    <h1>PO Listing</h1>

			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="80">PO #</td>
			          <td width="130"><input type="text" name="OrderNo" value="<?= $OrderNo ?>" class="textbox" maxlength="200" size="13" /></td>
			          
                                  <td width="70">PO Nature</td>

			          <td width="130">
			            <select name="PoNature" id="PoNature" style="width: 115px;">
						  <option value="">All</option>
	  	        		  <option value="S"<?= (($PoNature == "S") ? " selected" : "") ?>>SMS</option>
	  	        		  <option value="B"<?= (($PoNature == "B") ? " selected" : "") ?>>Bulk</option>
			            </select>
			          </td>

			          <td width="70">Brand</td>

			          <td width="130">
			            <select name="Brand" id="Brand" style="width:125px;" onchange="getListValues('Brand', 'Season', 'BrandSeasons'); getListValues('Brand', 'Destination', 'BrandDestinations');">
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

					  <td width="130">
					    <select name="Region" style="width: 125px;">
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

			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>

			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr>
			          <td width="80">Style</td>
			          <td width="130"><input type="text" name="Style" value="<?= $Style ?>" class="textbox" maxlength="50" size="13" /></td>
			          <td width="70">Season</td>

			          <td width="130">
			            <select name="Season" id="Season" style="width:115px;">
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

					  <td width="70">From</td>
					  <td width="100"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:90px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="50" >To</td>
					  <td width="100"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:90px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td>[ <a href="#" onclick="$('FromDate').value=''; $('ToDate').value=''; return false;">Clear</a> ]</td>
				    </tr>
				  </table>
			    </div>

			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr>
			          <td width="80">Destination</td>

			          <td width="130">
			            <select name="Destination" id="Destination" style="width:115px;">
			              <option value="">All Destinations</option>
<?
	if ($Brand > 0)
	{
		foreach ($sDestinationsList as $sKey => $sValue)
		{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Destination) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
	}
?>
			            </select>
			          </td>

			          <td width="70">PO Type</td>

			          <td width="130">
			            <select name="PoType" id="PoType" style="width: 115px;">
						  <option value="">All</option>
	  	        		  <option value="SDP"<?= (($PoType == "SDP") ? " selected" : "") ?>>SDP</option>
	  	        		  <option value="Non-SDP"<?= (($PoType == "Non-SDP") ? " selected" : "") ?>>Non-SDP</option>
			            </select>
			          </td>

<?
                if ($_SESSION["UserType"] == "JCREW")
                {
?>
                    <td width="70">Vendor</td>

                    <td width="130">
                      <select name="Parent" id="Parent" style="width:115px;" onchange="getListValues('Parent', 'Vendor', 'ParentVendors');">
                        <option value="">All Vendors</option>
<?
                    $sParentsList = getList ("tbl_vendors v, tbl_factories f", "f.id", "f.parent", "FIND_IN_SET(v.id, f.vendors) AND v.id IN ({$_SESSION['Vendors']})");

                      foreach ($sParentsList as $sKey => $sValue)
                      {
?>
                        <option value="<?= $sKey ?>"<?= (($sKey == $Parent) ? " selected" : "") ?>><?= $sValue ?></option>
<?
                      }
?>
                      </select>
                    </td>
                    
                    <td width="60">Factory</td>

                    <td width="130">
                      <select name="Vendor" id="Vendor" style="width:115px;">
                        <option value="">All Factories</option>
<?
                      if($Parent != 0)
                          $sChildrenList = getList ("tbl_vendors v, tbl_factories f", "v.id", "v.vendor", "FIND_IN_SET(v.id, f.vendors) AND f.id='$Parent'");
                          
                      foreach ($sChildrenList as $sKey => $sValue)
                      {
?>
                        <option value="<?= $sKey ?>"<?= (($sKey == $Vendor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
                      }
?>
                      </select>
                    </td>
<?
                }
                else
                {
?>                                  
                                  <td width="60">Vendor</td>

			          <td width="130">
			            <select name="Vendor" style="width:115px;">
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
<?
                }
?>
			          
					  <td></td>
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
	$sConditions = "";
        

	$sSQL = "SELECT COUNT(DISTINCT(po.id))
			 FROM tbl_po po, tbl_po_colors pc, tbl_styles s
			 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.brand_id=s.sub_brand_id AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}') ";

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
                
        if($Parent > 0 && $Vendor == 0)
        {
            $sParentVendors = getDbValue("vendors", "tbl_factories", "id='$Parent'"); 
            $sSQL .= " AND FIND_IN_SET(po.vendor_id, '$sParentVendors') ";                
        }        

	if ($Region > 0)
		$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	if ($FromDate != "" && $ToDate != "")
		$sSQL .= " AND (pc.etd_required BETWEEN '$FromDate' AND '$ToDate') ";

	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' ";

	else
		$sSQL .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

	if ($PoType != "")
		$sSQL .= " AND po.order_type='$PoType' ";

	if ($PoNature != "")
		$sSQL .= " AND po.order_nature='$PoNature' ";

	if ($Style != "" && $Season > 0)
		$sSQL .= " AND (s.style LIKE '%$Style%' AND s.sub_season_id='$Season') ";

	else if ($Style != "")
		$sSQL .= " AND s.style LIKE '%$Style%' ";

	else if ($Season > 0)
		$sSQL .= " AND s.sub_season_id='$Season' ";

	if ($Destination > 0)
		$sSQL .= " AND pc.destination_id='$Destination' ";

	
//	if ($_SESSION['UserId']==1)
//		print "$sSQL $sConditions <br><br>";	

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo($sSQL, $sConditions, $iPageSize, $PageId);


	if ($_SESSION['Guest'] == "Y")
		$iPageCount = 1;

        if ($_SESSION["UserType"] == "JCREW")
            $sOrderBy = " ORDER BY po.{$sPrefix}shipping_dates DESC ";
        else
            $sOrderBy = " ORDER BY po.id DESC ";

	$sSQL = "SELECT po.id, po.order_no, po.item_number, po.order_status, po.quantity, po.vendor_id, po.brand_id, po.shipping_dates, po.{$sPrefix}shipping_dates, po.status, po.accepted, po.accepted_at, po.accepted_by,
	                (SELECT style FROM tbl_styles WHERE id IN (po.styles) ORDER BY id LIMIT 1) AS _Style,
	                (SELECT sub_season_id FROM tbl_styles WHERE id IN (po.styles) ORDER BY id LIMIT 1) AS _Season
			 FROM tbl_po po, tbl_po_colors pc, tbl_styles s
			 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.brand_id=s.sub_brand_id";

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
                
        if($Parent > 0 && $Vendor == 0)
        {
            $sParentVendors = getDbValue("vendors", "tbl_factories", "id='$Parent'"); 
            $sSQL .= " AND FIND_IN_SET(po.vendor_id, '$sParentVendors') ";                
        }            

	if ($Region > 0)
		$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	if ($FromDate != "" && $ToDate != "")
		$sSQL .= " AND (pc.etd_required BETWEEN '$FromDate' AND '$ToDate') ";

	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' ";

	else
		$sSQL .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

	if ($PoType != "")
		$sSQL .= " AND po.order_type='$PoType' ";

	if ($PoNature != "")
		$sSQL .= " AND po.order_nature='$PoNature' ";

	if ($Style != "" && $Season > 0)
		$sSQL .= " AND (s.style LIKE '%$Style%' AND s.sub_season_id='$Season') ";

	else if ($Style != "")
		$sSQL .= " AND s.style LIKE '%$Style%' ";

	else if ($Season > 0)
		$sSQL .= " AND s.sub_season_id='$Season' ";

	if ($Destination > 0)
		$sSQL .= " AND pc.destination_id='$Destination' ";

	$sSQL .= " GROUP BY po.id
	           $sOrderBy
	           LIMIT $iStart, $iPageSize";

	$objDb->query($sSQL);

//	if ($_SESSION['UserId']==1)
//		print $sSQL ."<br><br>";
	
	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow">
				      <td width="8%">#</td>
				      <td width="14%">Order No</td>
				      <td width="18%"><?=($_SESSION["UserType"] == "JCREW"?'Factory':'Vendor')?></td>
				      <td width="11%">Season</td>
				      <td width="14%">Style No</td>
				      <td width="12%">ETD Required</td>
				      <td width="8%">Quantity</td>
				      <td width="15%" class="center">Options</td>
				    </tr>
<?
		}

		
		$iId         = $objDb->getField($i, 'id');
                $iItemNo     = $objDb->getField($i, 'item_number');
		$iBrand      = $objDb->getField($i, 'brand_id');
		$sStatus     = $objDb->getField($i, 'status');
		$sAccepted   = $objDb->getField($i, 'accepted');
		$iAcceptedBy = $objDb->getField($i, 'accepted_by');
		$sAcceptedAt = $objDb->getField($i, 'accepted_at');

		$sEtdRequired = formatDate(substr($objDb->getField($i, "{$sPrefix}shipping_dates"), 0, 10));

		if ($sPrefix != "" && ($sEtdRequired == "" || $sEtdRequired == "0000-00-00"))
			$sEtdRequired = formatDate(substr($objDb->getField($i, "shipping_dates"), 0, 10));


		$sToolTip  = "<b>User:</b><br />";
		$sToolTip .= (getDbValue("name", "tbl_users", "id='$iAcceptedBy'")."<br /><br />");
		$sToolTip .= "<b>Date/Time:</b><br />";
		$sToolTip .= formatDate($sAcceptedAt, "d-M-Y h:i A");
?>


				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td><?= ($iStart + $i + 1) ?></td>
				      <td><?= $objDb->getField($i, 'order_no').' '.$objDb->getField($i, 'order_status').(($_SESSION["UserType"] == "LEVIS" && $iItemNo != "")?' - '.$iItemNo:"") ?></td>
				      <td><?= $sVendorsList[$objDb->getField($i, 'vendor_id')] ?></td>
				      <td><?= $sSeasonsList[$objDb->getField($i, '_Season')] ?></td>
				      <td><?= $objDb->getField($i, '_Style') ?></td>

				      <td>
						<div class="etdRequired">
						  <span id="EtdRequired_<?= $i ?>"><?= (($sEtdRequired == "") ? 'N/A' : $sEtdRequired) ?></span>
<?
		if ($sUserRights['Edit'] == "Y")
		{
?>
						  <script type="text/javascript">
						  <!--
							  //var objEditor<?= $k ?> = new Ajax.InPlaceEditor('EtdRequired_<?= $i ?>', 'ajax/data/save-etd-required.php', { cancelControl:'button', okText:'  Ok  ', cancelText:'Cancel', clickToEditText:'Click to Edit', externalControl:'Edit<?= $i ?>', highlightcolor:'<?= HOVER_ROW_COLOR ?>', highlightendcolor:'<?= $sColor[($i % 2)] ?>', callback:function(form, value) { return 'Id=<?= $iId ?>&EtdRequired=' + encodeURIComponent(value) }, onEnterEditMode:function(form, value) { $('EtdRequired_<?= $i ?>').focus( ); } });
						  -->
						  </script>
<?
		}
?>
						</div>
				      </td>

				      <td><?= formatNumber($objDb->getField($i, 'quantity'), false) ?></td>

				      <td class="center">
<?
		if ($sUserRights['Edit'] == "Y")
		{
			if ($_SESSION["Admin"] == "Y" || $sStatus != "C")
			{
?>
				        <a href="data/edit-purchase-order.php?Id=<?= $iId ?>"><img src="images/icons/edit.gif" width="16" height="16" hspace="2" alt="Edit" title="Edit" /></a>
<?
			}

			if (@strpos($_SESSION["Email"], "@apparelco.com") !== FALSE || @strpos($_SESSION["Email"], "@3-tree.com") !== FALSE)
			{
?>
				        <a href="data/request-etd-revision.php?Id=<?= $iId ?>"><img src="images/icons/calendar2.gif" width="16" height="16" hspace="2" alt="Request ETD Revision" title="Request ETD Revision" /></a>
<?
			}

			else
			{
				if ($sAccepted == "N")
				{
?>
				        <a href="data/accept-po.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Acknowledge this PO?');"><img src="images/icons/yes.png" width="16" height="16" hspace="2" alt="Acknowledge" title="Acknowledge" /></a>
<?
				}

				else
				{
?>
				        <img id="ToolTip<?= $iId ?>" src="images/icons/more.gif" width="16" height="16" hspace="2" alt="" title="" />

						<script type="text/javascript">
						<!--
							new Tip('ToolTip<?= $iId ?>',
									"<?= $sToolTip ?>",
									{ title:'Acknowledgement Details', stem:'topLeft', hook:{ tip:'topLeft', mouse:true }, offset:{ x:1, y:1 }, width:300 });
							-->
						</script>
<?
				}
			}
		}

		if ($sUserRights['Delete'] == "Y" && ($_SESSION["Admin"] == "Y" || $sStatus != "C"))
		{
?>
				        <a href="data/delete-purchase-order.php?Id=<?= $iId ?>"><img src="images/icons/delete.gif" width="16" height="16" hspace="2" alt="Delete" title="Delete" /></a>
<?
		}

		if ($iBrand == 273)
		{
?>
				        <a href="data/generate-po.php?Id=<?= $iId ?>"><img src="images/icons/pdf.gif" width="16" height="16" hspace="1" hspace="2" alt="Generate PO" title="Generate PO" /></a>
<?
		}
?>
				        <a href="data/view-purchase-order.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="PO # <?= $objDb->getField($i, 'order_no').' '.$objDb->getField($i, 'order_status') ?> :: :: width: 800, height: 550"><img src="images/icons/view.gif" width="16" height="16" hspace="2" alt="View" title="View" /></a>
				      </td>
				    </tr>
<?
	}

	if ($iCount == 0)
	{
?>
				    <tr>
				      <td class="noRecord">No PO Record Found!</td>
				    </tr>
<?
	}
?>
			      </table>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&OrderNo={$OrderNo}&Vendor={$Vendor}&Parent={$Parent}&Brand={$Brand}&Region={$Region}&Season={$Season}&Style={$Style}&FromDate={$FromDate}&ToDate={$ToDate}&PoType={$PoType}&PoNature={$PoNature}");


	if ($_SESSION['Guest'] != "Y" && $_GET)
	{
		$sSQL = "SELECT COALESCE(SUM(pq.quantity), 0)
				 FROM tbl_po po, tbl_po_quantities pq, tbl_po_colors pc, tbl_styles s
				 WHERE po.id=pq.po_id AND po.id=pc.po_id AND pc.id=pq.color_id AND pc.style_id=s.id AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')";

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

		if ($Region > 0)
			$sSQL .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

		if ($FromDate != "" && $ToDate != "")
			$sSQL .= " AND (pc.etd_required BETWEEN '$FromDate' AND '$ToDate') ";

		if ($Brand > 0)
			$sSQL .= " AND po.brand_id='$Brand' ";

		else
			$sSQL .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

		if ($PoType != "")
			$sSQL .= " AND po.order_type='$PoType' ";

		if ($PoNature != "")
			$sSQL .= " AND po.order_nature='$PoNature' ";

		if ($Style != "" && $Season > 0)
			$sSQL .= " AND (s.style LIKE '%$Style%' AND s.sub_season_id='$Season') ";

		else if ($Style != "")
			$sSQL .= " AND s.style LIKE '%$Style%' ";

		else if ($Season > 0)
			$sSQL .= " AND s.sub_season_id='$Season' ";

		if ($Destination > 0)
			$sSQL .= " AND pc.destination_id='$Destination' ";

		$objDb->query($sSQL);

		$iOrderQty = $objDb->getField(0, 0);
?>

				<div class="buttonsBar" style="margin-top:4px;">
				  <span style="float:right; color:#ffffff; font-weight:bold; font-size:12px; padding:8px 10px 0px 0px;">Order Quantity: <?= formatNumber($iOrderQty, false) ?></span>
<?
		if ($iOrderQty > 0)
		{
?>

				  <input type="hidden" id="ExportUrl" name="ExportUrl" value="<?= (SITE_URL."data/export-purchase-orders.php?OrderNo={$OrderNo}&Vendor={$Vendor}&Brand={$Brand}&Region={$Region}&Season={$Season}&Destination={$Destination}&Style={$Style}&FromDate={$FromDate}&ToDate={$ToDate}&PoType={$PoType}&PoNature={$PoNature}") ?>" />
				  <input type="button" value="" id="BtnExport" class="btnExport" title="Export" onclick="exportReport( );" />
<?
		}
?>
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