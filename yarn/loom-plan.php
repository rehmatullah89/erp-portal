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

	$PageId   = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Vendor   = IO::intValue("Vendor");
	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");
	$PostId   = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Vendor   = IO::intValue("Vendor");
		$Po       = IO::intValue("Po");
		$FromDate = IO::strValue("FromDate");
		$ToDate   = IO::strValue("ToDate");
		$Looms    = IO::getArray("Looms");
	}


	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y' AND brandix='Y'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/yarn/loom-plan.js"></script>
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
			    <h1><img src="images/h1/yarn/loom-plan.jpg" width="143" height="20" vspace="10" alt="" title="" /></h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="yarn/save-loom-plan.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Loom Plan</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="75">Vendor</td>
					<td width="20" align="center">:</td>

					<td>
					  <select id="Vendor" name="Vendor" onchange="getListValues('Vendor', 'Po', 'Pos'); getLoomsList('Vendor', 'Looms');">
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
					<td>PO #</td>
					<td align="center">:</td>

					<td>
					  <select id="Po" name="Po">
					    <option value=""></option>
<?
		$sSQL = "SELECT id, CONCAT(order_no, ' ', order_status) AS _Po FROM tbl_po WHERE vendor_id='$Vendor' AND status!='C' ORDER BY _Po";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sKey    = $objDb->getField($i, 0);
			$sValue  = $objDb->getField($i, 1);
?>
	  	        		<option value="<?= $sKey ?>"<?= (($sKey == $Po) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>From Date</td>
					<td align="center">:</td>

					<td>

					  <table border="0" cellpadding="0" cellspacing="0" width="116">
						<tr>
						  <td width="82"><input type="text" name="FromDate" id="FromDate" value="<?= (($FromDate == "") ? date("Y-m-d") : $FromDate) ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
						</tr>
					  </table>

					</td>
				  </tr>

				  <tr>
					<td>To Date</td>
					<td align="center">:</td>

					<td>

					  <table border="0" cellpadding="0" cellspacing="0" width="116">
						<tr>
						  <td width="82"><input type="text" name="ToDate" id="ToDate" value="<?= (($ToDate == "") ? date("Y-m-d") : $ToDate) ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
						</tr>
					  </table>

					</td>
				  </tr>

				  <tr valign="top">
					<td>Looms</td>
					<td align="center">:</td>

					<td>
					  <select name="Looms[]" id="Looms" multiple size="4" style="width:195px;">
<?
		$sLoomsList = getList("tbl_looms", "id", "loom", "vendor_id='$Vendor'");

		foreach ($sLoomsList as $sKey => $sValue)
		{
?>
			              <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Looms)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar"><input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" /></div>
			    </form>

			    <hr />
<?
	}
?>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="50">Vendor</td>

			          <td width="200">
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

					  <td width="40">From</td>
					  <td width="78"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate2" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate2'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate2'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30" align="center">To</td>
					  <td width="78"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate2" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate2'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate2'), 'yyyy-mm-dd', this);" /></td>
					  <td width="100">[ <a href="#" onclick="$('FromDate2').value=''; $('ToDate2').value=''; return false;">Clear</a> ]</td>
					  <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </form>
			    </div>

			    <div class="tblSheet">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = " WHERE po.id=lp.po_id ";

	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND po.id IN (SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE etd_required BETWEEN '$FromDate' AND '$ToDate') ";

	if ($Vendor > 0)
		$sConditions .= " AND po.vendor_id='$Vendor' ";

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_po po, tbl_loom_plan lp", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT CONCAT(po.order_no, ' ', po.order_status) AS _Po, po.id, po.vendor_id, po.quantity, lp.looms,
	                (SELECT style FROM tbl_styles WHERE FIND_IN_SET(id, po.styles) LIMIT 1) AS _Style
	        FROM tbl_po po, tbl_loom_plan lp
	        $sConditions
	        ORDER BY po.id DESC
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
				      <td width="18%">PO #</td>
				      <td width="18%">D #</td>
				      <td width="20%">Vendor</td>
				      <td width="10%">Quantity</td>
				      <td width="10%">Looms</td>
				      <td width="16%" class="center">Options</td>
				    </tr>
<?
		}

		$iId       = $objDb->getField($i, 'id');
		$sPo       = $objDb->getField($i, '_Po');
		$sStyle    = $objDb->getField($i, '_Style');
		$iVendor   = $objDb->getField($i, 'vendor_id');
		$iQuantity = $objDb->getField($i, 'quantity');
		$sLooms    = $objDb->getField($i, 'looms');

		$iLooms    = count(@explode(",", $sLooms));
?>
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td><?= ($iStart + $i + 1) ?></td>
				      <td><?= $sPo ?></td>
				      <td><?= $sStyle ?></td>
				      <td><?= $sVendorsList[$iVendor] ?></td>
				      <td><?= formatNumber($iQuantity, false) ?></td>
				      <td><?= $iLooms ?></td>

				      <td class="center">
<?
		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="yarn/edit-loom-plan.php?Id=<?= $iId ?>"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="yarn/delete-loom-plan.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this PO Loom Plan?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
<?
		}
?>
				        <a href="yarn/view-loom-plan.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="PO # <?= $sPo ?> :: :: width: 800, height: 600"><img src="images/icons/view.gif" width="16" height="16" hspace="2" alt="View" title="View" /></a>
				      </td>
				    </tr>
<?
	}

	if ($iCount == 0)
	{
?>
				    <tr>
				      <td class="noRecord">No Loom Plan Found!</td>
				    </tr>
<?
	}
?>
			      </table>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Date={$Date}&Style={$Style}");
?>

			  </td>
			</tr>
		  </table>

		  <hr />

		  <form name="frmSearch" id="frmSearch" method="get" action="yarn/export-loom-plan.php" class="frmOutline" onsubmit="checkDoubleSubmission( );">
		    <h2>Export Loom Plan</h2>

		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
		      <tr valign="top">
			    <td width="80">
			      Vendor<br />
			      [ <a href="./" onclick="selectAll('Vendors'); return false;">All</a> | <a href="./" onclick="clearAll('Vendors'); return false;">None</a> ]<br />
			    </td>

			    <td width="20" align="center">:</td>

			    <td>
			      <select name="Vendor[]" id="Vendors" size="10" multiple>
<?
	foreach ($sVendorsList as $sKey => $sValue)
	{
?>
		            <option value="<?= $sKey ?>"><?= $sValue ?></option>
<?
	}
?>
				  </select>
				</td>
			  </tr>

			  <tr>
				<td>Start Date</td>
				<td align="center">:</td>

				<td>

				  <table border="0" cellpadding="1" cellspacing="0" width="320">
					<tr>
					  <td width="78"><input type="text" name="FromDate" value="<?= date('Y-m-01') ?>" id="FromDate3" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate3'), 'yyyy-mm-dd', this);" /></td>
					  <td><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate3'), 'yyyy-mm-dd', this);" /></td>
					</tr>
				  </table>

				</td>
			  </tr>

			   <tr>
				<td>End Date</td>
				<td align="center">:</td>
				<td>

				  <table border="0" cellpadding="1" cellspacing="0" width="320">
					<tr>
					  <td width="78"><input type="text" name="ToDate" value="<?= date('Y-m-t') ?>" id="ToDate3" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate3'), 'yyyy-mm-dd', this);" /></td>
					  <td><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate3'), 'yyyy-mm-dd', this);" /></td>
					</tr>
				  </table>

				</td>
			  </tr>
		    </table>

		    <br />
		    <div class="buttonsBar"><input type="submit" value="" id="BtnExport" class="btnExport" title="Export" /></div>
		  </form>

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