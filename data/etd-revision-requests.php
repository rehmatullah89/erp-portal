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
	$objDb3      = new Database( );

	$PageId   = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$OrderNo  = IO::strValue("OrderNo");
	$Vendor   = IO::intValue("Vendor");
	$Brand    = IO::intValue("Brand");
	$Region   = IO::intValue("Region");
	$Status   = IO::strValue("Status");
	$User     = IO::intValue("User");
	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");

	$Status = (($Status == "") ? "P" : $Status);
/*
	if ($_SESSION['Admin'] == "Y" || ($sUserRights['Edit'] == "Y" && $sUserRights['Delete'] == "Y"))
		$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");

	else
		$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND FIND_IN_SET('{$_SESSION['UserId']}', etd_managers) AND parent_id='0' AND sourcing='Y'");
*/
	$sVendorsList       = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList        = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ({$_SESSION['Brands']})");
	$sMerchandisersList = getList("tbl_users", "id", "name", "designation_id IN (SELECT id FROM tbl_designations WHERE department_id IN (11,27,28,30,33,35,40,42,47,48))");
	$sReasons           = array( );
	$sReasonsList       = array( );
	$sEtdManagersList   = getList("tbl_vendors", "id", "etd_managers", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");


	$sSQL = "SELECT id, code, reason FROM tbl_etd_revision_reasons WHERE parent_id='0' ORDER BY reason";
	$objDb->query($sSQL);

	$iCount =$objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iSuperParentId     = $objDb->getField($i, 'id');
		$sSuperParentCode   = $objDb->getField($i, 'code');
		$sSuperParentReason = $objDb->getField($i, 'reason');


		$sSQL = "SELECT id, code, reason FROM tbl_etd_revision_reasons WHERE parent_id='$iSuperParentId' ORDER BY reason";
		$objDb2->query($sSQL);

		$iCount2 =$objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iParentId     = $objDb2->getField($j, 'id');
			$sParentCode   = $objDb2->getField($j, 'code');
			$sParentReason = $objDb2->getField($j, 'reason');


			$sSQL = "SELECT id, code, reason FROM tbl_etd_revision_reasons WHERE parent_id='$iParentId' ORDER BY reason";
			$objDb3->query($sSQL);

			$iCount3 =$objDb3->getCount( );

			for ($k = 0; $k < $iCount3; $k ++)
			{
				$iId     = $objDb3->getField($k, 'id');
				$sCode   = $objDb3->getField($k, 'code');
				$sReason = $objDb3->getField($k, 'reason');

				$sReasons[$iId]     = ($sSuperParentCode.$sParentCode.$sCode." - ".$sSuperParentReason.' <b>�</b> '.$sParentReason.' <b>�</b> '.$sReason);
				$sReasonsList[$iId] = ($sSuperParentCode.$sParentCode.$sCode." - ".$sSuperParentReason." � ".$sParentReason." � ".$sReason);
			}
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/etd-revision-requests.js"></script>
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
			    <h1>ETD Revision Requests</h1>

			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="35">PO #</td>
			          <td width="115"><input type="text" name="OrderNo" value="<?= $OrderNo ?>" class="textbox" maxlength="50" size="12" /></td>
			          <td width="52">Vendor</td>

			          <td width="190">
			            <select name="Vendor" style="width:180px;">
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

			          <td width="155">
			            <select name="Brand" style="width:140px;">
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

<?
	if ($_SESSION['Admin'] == "Y" || ($sUserRights['Edit'] == "Y" && $sUserRights['Delete'] == "Y"))
	{
?>
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
<?
	}
?>

			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>

			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr>
			          <td width="50">Status</td>

			          <td width="110">
			            <select name="Status">
			              <option value="-">Any Status</option>
			              <option value="A"<?= (($Status == "A") ? " selected" : "") ?>>Approved</option>
			              <option value="P"<?= (($Status == "P") ? " selected" : "") ?>>Pending</option>
			              <option value="R"<?= (($Status == "R") ? " selected" : "") ?>>Rejected</option>
			            </select>
			          </td>

			          <td width="95">Merchandiser</td>

			          <td width="220">
			            <select name="User" style="width:200px;">
			              <option value="">All Merchandisers</option>
<?
	$sSQL = "SELECT id, name FROM tbl_users WHERE designation_id IN (SELECT id FROM tbl_designations WHERE department_id IN (11,27,28,30,33,35,40,42,46,47,48)) ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, 0);
		$sValue = $objDb->getField($i, 1);
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $User) ? " selected" : "") ?>><?= $sValue ?></option>
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
	$sConditions = "po.id=etd.po_id";

	if ($OrderNo != "")
	{
		if (@strpos($OrderNo, ",") === FALSE)
			$sConditions .= " AND po.order_no LIKE '%$OrderNo%' ";

		else
		{
			$sPOs = @explode(",", $OrderNo);

			$sConditions .= " AND (";

			for ($i = 0; $i < count($sPOs); $i ++)
			{
				if ($i > 0)
					$sSQL .= " OR ";

				$sConditions .= " po.order_no LIKE '%{$sPOs[$i]}%' ";
			}

			$sConditions .= ")";
		}
	}
/*
	if ($Vendor > 0)
		$sConditions .= " AND po.vendor_id='$Vendor' AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE FIND_IN_SET('{$_SESSION['UserId']}', etd_managers) AND parent_id='0' AND sourcing='Y') ";

	else
	{
		if ($_SESSION['Admin'] == "Y" || ($sUserRights['Edit'] == "Y" && $sUserRights['Delete'] == "Y"))
			$sConditions .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";

		else
			$sConditions .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE FIND_IN_SET('{$_SESSION['UserId']}', etd_managers) AND parent_id='0' AND sourcing='Y') ";
	}
*/
	if ($Vendor > 0)
		$sConditions .= " AND po.vendor_id='$Vendor' ";

	else
		$sConditions .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Region > 0)
		$sConditions .= " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND (DATE_FORMAT(etd.date_time, '%Y-%m-%d') BETWEEN '$FromDate' AND '$ToDate') ";

	if ($Brand > 0)
		$sConditions .= " AND po.brand_id='$Brand' ";

	else
		$sConditions .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

	if ($User > 0)
		$sConditions .= " AND etd.user_id='$User' ";

	if ($Status != "-" && $Status != "")
		$sConditions .= " AND etd.status='$Status' ";


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_po po, tbl_etd_revision_requests etd", "WHERE $sConditions", $iPageSize, $PageId);



	$sSQL = "SELECT CONCAT(po.order_no, ' ', po.order_status) AS _OrderNo, po.quantity, po.brand_id, po.vendor_id, etd.reason_id,
	                LEFT(po.shipping_dates, 10) AS _EtdRequired,
			        etd.id, etd.po_id, etd.user_id, etd.revised_etd, etd.reason, etd.status, etd.date_time
			 FROM tbl_po po, tbl_etd_revision_requests etd
			 WHERE $sConditions
	         ORDER BY po.id DESC
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
				      <td width="14%">Order No</td>
				      <td width="16%">Vendor</td>
				      <td width="13%">ETD Required</td>
				      <td width="9%">Quantity</td>
				      <td width="13%">Revised ETD</td>
				      <td width="12%">Status</td>
				      <td width="15%" class="center">Options</td>
				    </tr>
			      </table>
<?
		}


		$iId          = $objDb->getField($i, 'id');
		$iPoId        = $objDb->getField($i, 'po_id');
		$sOrderNo     = $objDb->getField($i, '_OrderNo');
		$iVendorId    = $objDb->getField($i, 'vendor_id');
		$iBrandId     = $objDb->getField($i, 'brand_id');
		$iUserId      = $objDb->getField($i, 'user_id');
		$sStatus      = $objDb->getField($i, 'status');
		$sEtdRequired = $objDb->getField($i, '_EtdRequired');
		$sRevisedEtd  = $objDb->getField($i, 'revised_etd');
		$iQuantity    = $objDb->getField($i, 'quantity');
		$iReasonId    = $objDb->getField($i, 'reason_id');
		$sReason      = $objDb->getField($i, 'reason');
		$sDateTime    = $objDb->getField($i, 'date_time');

		switch ($sStatus)
		{
			case "P" : $sStatusText = "Pending"; break;
			case "A" : $sStatusText = "Approved"; break;
			case "R" : $sStatusText = "Rejected"; break;
		}


		$sReasonTip  = "<b>Merchandiser</b><br />";
		$sReasonTip .= ($sMerchandisersList[$iUserId]."<br /><br />");
		$sReasonTip .= "<b>Reason</b><br />";

		if ($sReasons[$iReasonId] != "")
			$sReasonTip .= ($sReasons[$iReasonId]."<br /><br />");

		else
			$sReasonTip .= (addslashes(nl2br($sReason))."<br /><br />");

		$sReasonTip .= "<b>Request made on:</b><br />";
		$sReasonTip .= formatDate($sDateTime);

		$iEtdManagers   = @explode(",", $sEtdManagersList[$iVendorId]);
		$iEtdManagers[] = 319;
		$iEtdManagers[] = 552;
		$iEtdManagers[] = 15;
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
				      <td width="16%"><?= $sVendorsList[$iVendorId] ?></td>
				      <td width="13%"><?= formatDate($sEtdRequired) ?></td>
				      <td width="9%"><?= formatNumber($iQuantity, false) ?></td>
				      <td width="13%"><span id="RevisedEtd<?= $iId ?>"><?= formatDate($sRevisedEtd) ?></span></td>
				      <td width="12%"><?= $sStatusText ?></td>


				      <td width="15%" class="center">
<?
		if ($_SESSION['Admin'] == "Y" || @in_array($_SESSION['UserId'], $iEtdManagers))
		{
			if ($sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y")
			{
?>
				        <a href="./" onclick="Effect.SlideDown('Edit<?= $iId ?>'); return false;"><img src="images/icons/edit.gif" width="16" height="16" hspace="2" alt="Edit" title="Edit" /></a>
<?
			}

			if ($sUserRights['Edit'] == "Y")
			{
?>
				        <a href="data/update-etd-revision-request.php?Id=<?= $iId ?>&Status=A&PoId=<?= $iPoId ?>&OriginalEtd=<?= $sEtdRequired ?>&RevisedEtd=<?= $sRevisedEtd ?>&UserId=<?= $iUserId ?>"><img src="images/icons/yes.png" width="16" height="16" hspace="2" alt="Approve" title="Approve" /></a>
				        <a href="data/update-etd-revision-request.php?Id=<?= $iId ?>&Status=R&PoId=<?= $iPoId ?>&OriginalEtd=<?= $sEtdRequired ?>&RevisedEtd=<?= $sRevisedEtd ?>&UserId=<?= $iUserId ?>"><img src="images/icons/no.png" width="16" height="16" hspace="2" alt="Reject" title="Reject" /></a>
<?
			}
		}
?>
				        <img id="Reason<?= $iId ?>" src="images/icons/view.gif" width="16" height="16" hspace="2" alt="" title="" />

						<script type="text/javascript">
						<!--
							new Tip('Reason<?= $iId ?>',
									"<?= $sReasonTip ?>",
									{ title:'ETD Revision Details', stem:'topLeft', hook:{ tip:'topLeft', mouse:true }, offset:{ x:1, y:1 }, width:300 });
							-->
						</script>
<?
		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="data/delete-etd-revision-request.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this ETD Revision Request?');"><img src="images/icons/delete.gif" width="16" height="16" hspace="2" alt="Delete" title="Delete" /></a>
<?
		}
?>
				      </td>
				    </tr>
			      </table>

				  <div id="Edit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" onsubmit="return false;" class="frmInlineEdit">
					  <input type="hidden" name="Id" value="<?= $iId ?>" />

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="80"><b>Order No</b></td>
						  <td width="20" align="center">:</td>
						  <td><?= $sOrderNo ?></td>
					    </tr>

					    <tr>
						  <td><b>Vendor</b></td>
						  <td align="center">:</td>
						  <td><?= $sVendorsList[$iVendorId] ?></td>
					    </tr>

					    <tr>
						  <td><b>Brand</b></td>
						  <td align="center">:</td>
						  <td><?= $sBrandsList[$iBrandId] ?></td>
					    </tr>

					    <tr>
						  <td><b>Original ETD</b></td>
						  <td align="center">:</td>
						  <td><?= $sEtdRequired ?></td>
					    </tr>

					    <tr>
						  <td>Revised ETD</td>
						  <td align="center">:</td>

						  <td>

						    <table border="0" cellpadding="0" cellspacing="0" width="116">
							  <tr>
							    <td width="82"><input type="text" name="RevisedEtd" id="RevisedEtd_<?= $iId ?>" value="<?= $sRevisedEtd ?>" readonly class="textbox" style="width:70px;" onclick="displayCalendar($('RevisedEtd_<?= $iId ?>'), 'yyyy-mm-dd', this);" /></td>
							    <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('RevisedEtd_<?= $iId ?>'), 'yyyy-mm-dd', this);" /></td>
							  </tr>
						    </table>

						  </td>
					    </tr>

					    <tr>
						  <td>Merchandiser</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Merchandiser">
<?
		foreach ($sMerchandisersList as $sKey => $sValue)
		{
?>
			                  <option value="<?= $sKey ?>"<?= (($sKey == $iUserId) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
						</tr>

					    <tr valign="top">
						  <td>Reason</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Reason">
<?
		foreach ($sReasonsList as $sKey => $sValue)
		{
?>
			                  <option value="<?= $sKey ?>"<?= (($sKey == $iReasonId) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td colspan="3">
						    <input type="submit" value="SAVE" class="btnSmall" onclick="validateEditForm(<?= $iId ?>);" />
						    <input type="button" value="CANCEL" class="btnSmall" onclick="Effect.SlideUp('Edit<?= $iId ?>');" />
						  </td>
					    </tr>
					  </table>
					  </form>

				    </div>
				  </div>

				  <div id="Msg<?= $iId ?>" class="msgOk" style="display:none;"></div>
<?
	}

	if ($iCount == 0)
	{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr>
				      <td class="noRecord">No ETD Revision Request Found!</td>
				    </tr>
			      </table>
<?
	}
?>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&OrderNo={$OrderNo}&Vendor={$Vendor}&Brand={$Brand}&Region={$Region}&Status={$Status}&User={$User}&FromDate={$FromDate}&ToDate={$ToDate}");
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
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>