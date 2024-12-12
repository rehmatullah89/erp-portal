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

	$Id      = IO::strValue("Id");
	$Referer = urldecode(IO::strValue("Referer"));

	if ($Referer == "")
		$Referer = $_SERVER['HTTP_REFERER'];


	$sSQL = "SELECT
	           CONCAT(order_no, ' ', order_status), shipping_dates,
	           (SELECT vendor FROM tbl_vendors WHERE id=tbl_po.vendor_id) AS _Vendor,
	           (SELECT brand FROM tbl_brands WHERE id=tbl_po.brand_id) AS _Brand
	         FROM tbl_po WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		redirect("./", "ERROR");

	$sOrderNo     = $objDb->getField(0, 0);
	$sVendor      = $objDb->getField(0, 2);
	$sBrand       = $objDb->getField(0, 3);
	$sEtdRequired = formatDate(substr($objDb->getField(0, 1), 0, 10));
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/request-etd-revision.js"></script>
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
			    <h1><img src="images/h1/data/request-etd-revision.jpg" width="298" height="23" style="margin:9px 0px 8px 0px;" alt="" title="" /></h1>

			    <form name="frmData" id="frmData" method="post" action="data/save-etd-revision-request.php" class="frmOutline" onsubmit="$('BtnSubmit').disable( );">
			    <input type="hidden" name="PoId" value="<?= $Id ?>" />
			    <input type="hidden" name="Referer" value="<?= $Referer ?>" />

			    <h2>PO Details</h2>

			    <div style="padding:10px;">
			      Please provide the required information below to submit an ETD Revision Request.<br />
			    </div>

			    <table width="98%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="80"><b>Order No</b></td>
				    <td width="20" align="center">:</td>
				    <td><?= $sOrderNo ?></td>
				  </tr>

				  <tr>
				    <td><b>Vendor</b></td>
				    <td align="center">:</td>
				    <td><?= $sVendor ?></td>
				  </tr>

				  <tr>
				    <td><b>Brand</b></td>
				    <td align="center">:</td>
				    <td><?= $sBrand ?></td>
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
						  <td width="82"><input type="text" name="RevisedEtd" id="RevisedEtd" value="" readonly class="textbox" style="width:70px;" onclick="displayCalendar($('RevisedEtd'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('RevisedEtd'), 'yyyy-mm-dd', this);" /></td>
						</tr>
					  </table>

				    </td>
				  </tr>

				  <tr>
				    <td>Merchandiser</td>
				    <td align="center">:</td>

				    <td>
				      <select name="Merchandiser">
				        <option value=""></option>
<?
	$sSQL = "SELECT id, name FROM tbl_users WHERE designation_id IN (SELECT id FROM tbl_designations WHERE department_id IN (11,27,28,30,33,35,40,42,46,47,48)) ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, 0);
		$sValue = $objDb->getField($i, 1);
?>
	  	        		<option value="<?= $sKey ?>"<?= (($sKey == $Merchandiser) ? " selected" : "") ?>><?= $sValue ?></option>
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
						<option value=""></option>
<?
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
?>
					  <option value="<?= $iId ?>"><?= ($sSuperParentCode.$sParentCode.$sCode) ?> - <?= $sSuperParentReason ?> » <?= $sParentReason ?> » <?= $sReason ?></option>
<?
			}
		}
	}
?>
					  </select>
				    </td>
				  </tr>
				</table>

				<br />
			    <div class="buttonsBar">
			      <input type="submit" id="BtnSubmit" value="" class="btnSubmit" onclick="return validateForm( );" />
				  <input type="button" value="" class="btnBack" title="Back" onclick="document.location='<?= $Referer ?>';" />
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
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>