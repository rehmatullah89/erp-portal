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

	$Id      = IO::intValue('Id');
	$Referer = IO::strValue("Referer");

	if ($Referer == "")
		$Referer = $_SERVER['HTTP_REFERER'];


	$sSQL = "SELECT * FROM tbl_loom_plan WHERE po_id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		redirect($Referer, "DB_ERROR");

	$sFromDate = $objDb->getField(0, 'from_date');
	$sToDate   = $objDb->getField(0, 'to_date');
	$sLooms    = $objDb->getField(0, 'looms');

	$iLooms     = @explode(",", $sLooms);
	$iVendor    = getDbValue("vendor_id", "tbl_po", "id='$Id'");
	$sLoomsList = getList("tbl_looms", "id", "loom", "vendor_id='$iVendor'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/yarn/edit-loom-plan.js"></script>
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

			    <form name="frmData" id="frmData" method="post" action="yarn/update-loom-plan.php" class="frmOutline" onsubmit="$('BtnSave').disable( );">
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Referer" value="<?= $Referer ?>" />

			    <h2>Edit Loom Plan</h2>
			    <table width="98%" cellspacing="0" cellpadding="3" border="0" align="center">
				  <tr>
				    <td width="60">PO #</td>
				    <td width="20" align="center">:</td>
				    <td><?= getDbValue("CONCAT(order_no, ' ', order_status)", "tbl_po", "id='$Id'") ?></td>
				  </tr>

				  <tr>
				    <td>D #</td>
				    <td align="center">:</td>
				    <td><?= getDbValue("style", "tbl_styles", "id IN (SELECT styles FROM tbl_po WHERE id='$Id')") ?></td>
				  </tr>

				  <tr>
				    <td>Vendor</td>
				    <td align="center">:</td>
				    <td><?= getDbValue("vendor", "tbl_vendors", "id='$iVendor'") ?></td>
				  </tr>

				  <tr>
				    <td>Quantity</td>
				    <td align="center">:</td>
				    <td><?= formatNumber(getDbValue("quantity", "tbl_po", "id='$Id'"), false) ?></td>
				  </tr>

				  <tr>
					<td>From Date</td>
					<td align="center">:</td>

					<td>

					  <table border="0" cellpadding="0" cellspacing="0" width="116">
						<tr>
						  <td width="82"><input type="text" name="FromDate" id="FromDate" value="<?= $sFromDate ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
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
						  <td width="82"><input type="text" name="ToDate" id="ToDate" value="<?= $sToDate ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
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
	foreach ($sLoomsList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $iLooms)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
				    <td colspan="3"><h3 style="margin-top:15px;">Loom Plan</h3></td>
				  </tr>

				  <tr>
				    <td colspan="3">

					  <table border="1" bordercolor="#cccccc" cellpadding="5" cellspacing="0" width="100%">
					    <tr bgcolor="#e6e6e6">
						  <td width="60" align="center"><b>#</b></td>
						  <td width="120" align="center"><b>Date</b></td>
<?
	foreach ($iLooms as $iLoom)
	{
?>
						  <td align="center"><b><?= $sLoomsList[$iLoom] ?></b></td>
<?
	}
?>
						  <td width="100" align="center"><b>Total</b></td>
						</tr>

<?
	$iIndex    = 1;
	$iTotal    = 0;
	$iFromDate = strtotime($sFromDate);
	$iToDate   = strtotime($sToDate);

	do
	{
		$sDate = date("Y-m-d", $iFromDate);
?>
						<tr bgcolor="#f6f6f6">
						  <td align="center"><?= $iIndex ?></td>
						  <td align="center"><?= formatDate($sDate) ?></td>
<?
		foreach ($iLooms as $iLoom)
		{
			$iProduction = getDbValue("production", "tbl_loom_plan_details", "po_id='$Id' AND `date`='$sDate' AND loom_id='$iLoom'");
?>
						  <td align="center"><input type="text" name="Production<?= $iLoom ?>_<?= $iFromDate ?>" value="<?= $iProduction ?>" size="5" maxlength="5" class="textbox production<?= $iIndex ?>" onblur="updateTotal( );" /></td>
<?
			$iTotal += $iProduction;
		}
?>
						  <td align="center"><span id="Total<?= $iIndex ?>"><?= formatNumber($iTotal, false) ?></span></td>
						</tr>
<?
		$iFromDate += 86400;
		$iIndex    ++;
	}
	while ($iFromDate <= $iToDate);
?>
					  </table>

				      <input type="hidden" id="Count" value="<?= ($iIndex - 1) ?>" />
				    </td>
				  </tr>
				</table>

				<br />

			    <div class="buttonsBar">
			      <input type="submit" id="BtnSave" value="" class="btnSave" onclick="return validateForm( );" />
			      <input type="button" value="" class="btnBack" onclick="document.location='<?= $Referer ?>';" />
			    </div>
			    </form>
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