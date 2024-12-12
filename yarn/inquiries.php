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
	$Style    = IO::intValue("Style");
	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");
	$PostId   = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Date     = IO::strValue("Date");
		$Style    = IO::intValue("Style");
		$Quantity = IO::strValue("Quantity");
		$Types    = IO::getArray("Types");
	}

	$sTypesList = array("pxp" => "Pak x Pak",
	                    "pxu" => "Pak x US",
	                    "uxp" => "US x Pak",
	                    "uxu" => "US x US");

	$sStylesList  = getList("tbl_styles", "id", "style", "sub_brand_id='167'");
	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/yarn/inquiries.js"></script>
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
			    <h1><img src="images/h1/yarn/inquiries.jpg" width="133" height="24" style="margin:10px 0px 6px 0px;" alt="" title="" /></h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="yarn/save-inquiry.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Inquiry</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="50">Date</td>
					<td width="20" align="center">:</td>

					<td>

					  <table border="0" cellpadding="0" cellspacing="0" width="116">
						<tr>
						  <td width="82"><input type="text" name="Date" id="Date" value="<?= (($Date == "") ? date("Y-m-d") : $Date) ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('Date'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('Date'), 'yyyy-mm-dd', this);" /></td>
						</tr>
					  </table>

					</td>
				  </tr>

				  <tr>
					<td>D #</td>
					<td align="center">:</td>

					<td>
					  <select name="Style">
						<option value=""></option>
<?
		foreach ($sStylesList as $sKey => $sValue)
		{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Style) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Quantity</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="Quantity" value="<?= $Quantity ?>" maxlength="100" size="25" class="textbox" /></td>
				  </tr>

				  <tr valign="top">
					<td>Types</td>
					<td align="center">:</td>

					<td>
					  <select name="Types[]" id="Types" multiple size="4" style="width:174px;">
<?
		foreach ($sTypesList as $sKey => $sValue)
		{
?>
			              <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Types)) ? " selected" : "") ?>><?= $sValue ?></option>
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
			          <td width="35">D #</td>

			          <td width="180">
					    <select name="Style">
						  <option value=""></option>
<?
	foreach ($sStylesList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Style) ? " selected" : "") ?>><?= $sValue ?></option>
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
					  <td width="100">[ <a href="#" onclick="$('FromDate').value=''; $('ToDate').value=''; return false;">Clear</a> ]</td>
					  <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </form>
			    </div>

			    <div class="tblSheet">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";

	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND (date BETWEEN '$FromDate' AND '$ToDate') ";

	if ($Style > 0)
		$sConditions .= " AND style_id='$Style' ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_yarn_inquiries", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_yarn_inquiries $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
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
				      <td width="15%">Date</td>
				      <td width="20%">D #</td>
				      <td width="20%">Quantity</td>
				      <td width="25%">Types</td>
				      <td width="12%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId       = $objDb->getField($i, 'id');
		$iStyle    = $objDb->getField($i, 'style_id');
		$sDate     = $objDb->getField($i, 'date');
		$sQuantity = $objDb->getField($i, 'quantity');
		$sTypes    = $objDb->getField($i, 'types');

		$sTypes     = @explode(",", $sTypes);
		$sYarnTypes = "";

		foreach ($sTypes as $sType)
			$sYarnTypes .= ((($sYarnTypes != "") ? ", " : "").$sTypesList[$sType]);
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="8%"><?= ($iStart + $i + 1) ?></td>
				      <td width="15%"><span id="Date<?= $iId ?>"><?= formatDate($sDate) ?></span></td>
				      <td width="20%"><span id="Style<?= $iId ?>"><?= $sStylesList[$iStyle] ?></span></td>
				      <td width="20%"><span id="Quantity<?= $iId ?>"><?= $sQuantity ?></span></td>
				      <td width="25%"><span id="Types<?= $iId ?>"><?= $sYarnTypes ?></span></td>

				      <td width="12%" class="center">
<?
		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="./" onclick="Effect.SlideDown('Edit<?= $iId ?>'); return false;"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="yarn/delete-inquiry.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Inquiry?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
<?
		}
?>
				        <a href="yarn/view-inquiry.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="D # <?= $sStylesList[$iStyle] ?> :: :: width: 800, height: 350"><img src="images/icons/view.gif" width="16" height="16" hspace="2" alt="View" title="View" /></a>
				      </td>
				    </tr>
				  </table>

				  <div id="Edit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" onsubmit="return false;" class="frmInlineEdit">
					  <input type="hidden" name="Id" value="<?= $iId ?>" />

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="80">Date</td>
						  <td width="20" align="center">:</td>
						  <td><?= formatDate($sDate) ?></td>
						</tr>

					    <tr>
						  <td>D #</td>
						  <td align="center">:</td>
						  <td><?= $sStylesList[$iStyle] ?></td>
						</tr>

						<tr>
						  <td>Construction</td>
						  <td align="center">:</center>
						  <td><?= getDbValue("greige_construction", "tbl_gf_specs", "style_id='$iStyle'") ?></td>
						</tr>

					    <tr>
						  <td>Quantity</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Quantity" value="<?= $sQuantity ?>" size="30" maxlength="100" class="textbox" /></td>
					    </tr>
					  </table>

					  <br />

					  <table border="1" bordercolor="#cccccc" cellpadding="5" cellspacing="0" width="100%">
					    <tr bgcolor="#eeeeee">
						  <td width="110"></td>
<?
		$sSQL = "SELECT * FROM tbl_yarn_inquiry_details WHERE inquiry_id='$iId' ORDER BY vendor_id";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iVendor = $objDb2->getField($j, "vendor_id");
?>
						  <td align="center"><b><?= $sVendorsList[$iVendor] ?></b></td>
<?
		}
?>
						</tr>

<?
		foreach ($sTypes as $sType)
		{
?>
						<tr bgcolor="#f6f6f6">
						  <td><b><?= $sTypesList[$sType] ?></b></td>
<?
			for ($j = 0; $j < $iCount2; $j ++)
			{
				$iVendor = $objDb2->getField($j, "vendor_id");
				$fPrice  = $objDb2->getField($j, "{$sType}_price");
?>
						  <td align="center"><input type="text" name="Price<?= $iVendor ?>_<?= $sType ?>" value="<?= (($fPrice > 0) ? formatNumber($fPrice) : "") ?>" size="10" maxlength="5" class="textbox" /></td>
<?
			}
?>
						</tr>
<?
		}
?>

						<tr bgcolor="#f6f6f6">
						  <td><b>Response Time</b></td>
<?
		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iVendor       = $objDb2->getField($j, "vendor_id");
			$sResponseTime = $objDb2->getField($j, "response_time");
?>
						  <td align="center"><input type="text" name="ResponseTime<?= $iVendor ?>" value="<?= $sResponseTime ?>" size="10" maxlength="50" class="textbox" /></td>
<?
		}
?>
						</tr>

						<tr bgcolor="#f6f6f6">
						  <td><b>Shipment Date<b/></td>
<?
		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iVendor       = $objDb2->getField($j, "vendor_id");
			$sShipmentDate = $objDb2->getField($j, "shipment_date");
?>
						  <td align="center"><input type="text" name="ShipmentDate<?= $iVendor ?>" value="<?= $sShipmentDate ?>" size="10" maxlength="50" class="textbox" /></td>
<?
		}
?>
						</tr>
					  </table>

					  <br />
					  <input type="submit" value="SAVE" class="btnSmall" onclick="validateEditForm(<?= $iId ?>);" />
					  <input type="button" value="CANCEL" class="btnSmall" onclick="Effect.SlideUp('Edit<?= $iId ?>');" />
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
				      <td class="noRecord">No Inquiry Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Date={$Date}&Style={$Style}");
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