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

	@require_once("requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$PoId   = IO::strValue("PoId");
	$UserId = IO::strValue("UserId");
	$Email  = IO::strValue("Email");


	$sSQL = "SELECT * FROM tbl_users WHERE id='$UserId' AND email='$Email'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		redirect("./", "ERROR");


	$sSQL = "SELECT
	           CONCAT(order_no, ' ', order_status), shipping_dates,
	           (SELECT vendor FROM tbl_vendors WHERE id=tbl_po.vendor_id) AS _Vendor,
	           (SELECT brand FROM tbl_brands WHERE id=tbl_po.brand_id) AS _Brand
	         FROM tbl_po WHERE id='$PoId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		redirect("./", "ERROR");

	$sOrderNo     = $objDb->getField(0, 0);
	$sEtdRequired = formatDate(substr($objDb->getField(0, 1), 0, 10));
	$sVendor      = $objDb->getField(0, 2);
	$sBrand       = $objDb->getField(0, 3);


	$sSQL = "SELECT audit_date FROM tbl_qa_reports WHERE audit_stage='F' AND audit_result='P' AND (po_id='$PoId' OR FIND_IN_SET('$PoId', additional_pos)) LIMIT 1";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$sSQL = "SELECT final_audit_date FROM tbl_vsr WHERE po_id='$PoId'";
		$objDb->query($sSQL);
	}

	$sFinalAuditDate = $objDb->getField(0, 0);
	$sFinalAuditDate = formatDate($sFinalAuditDate);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/delay-reason.js"></script>
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
			  <td width="585">
			    <h1><img src="images/h1/po-delay-reason.jpg" width="230" height="20" vspace="10" alt="" title="" /></h1>

			    <form name="frmData" id="frmData" method="post" action="save-delay-reason.php" class="frmOutline" onsubmit="$('BtnSubmit').disable( );">
			    <input type="hidden" name="PoId" value="<?= $PoId ?>" />
			    <input type="hidden" name="UserId" value="<?= $UserId ?>" />

			    <div style="padding:10px;">
			      Please select the appropriate reason for the delay of this PO.<br />
			    </div>


			    <table width="90%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
				    <td width="120"><b>Order No</b></td>
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
				    <td><b>Final Audit Date</b></td>
				    <td align="center">:</td>
				    <td><?= $sFinalAuditDate ?></td>
				  </tr>

				  <tr>
				    <td>Delay Type</td>
				    <td align="center">:</td>

				    <td>
				      <select name="Type" onchange="getReasons(this.value);">
				        <option value=""></option>
<?
	$sSQL = "SELECT id, type FROM tbl_delay_types ORDER BY type";
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
				  </tr>

				  <tr>
				    <td>Delay Reason</td>
				    <td align="center">:</td>

				    <td>
				      <select name="Reason" id="Reason">
				        <option value=""></option>
				      </select>
				    </td>
				  </tr>
				</table>

				<br />
			    <div class="buttonsBar"><input type="submit" id="BtnSubmit" value="" class="btnSubmit" onclick="return validateForm( );" /></div>
			    </form>
			  </td>

			  <td width="5"></td>

			  <td>
<?
	@include($sBaseDir."includes/sign-in.php");
?>

			    <div style="height:5px;"></div>

<?
	@include($sBaseDir."includes/contact-info.php");
?>
			  </td>
			</tr>
		  </table>
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