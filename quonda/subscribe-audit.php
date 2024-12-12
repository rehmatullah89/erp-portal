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

	$Id = IO::intValue('Id');


	$sSQL = "SELECT *,
	                (SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _PO,
	                (SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _Auditor,
	                (SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor
	         FROM tbl_qa_reports
	         WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sAuditCode     = $objDb->getField(0, "audit_code");
		$sVendor        = $objDb->getField(0, "_Vendor");
		$sAuditor       = $objDb->getField(0, "_Auditor");
		$iPoId          = $objDb->getField(0, "po_id");
		$sPO            = $objDb->getField(0, "_PO");
		$sAdditionalPos = $objDb->getField(0, "additional_pos");
		$iStyle         = $objDb->getField(0, "style_id");
		$sAuditStage    = $objDb->getField(0, "audit_stage");
		$sAuditDate     = $objDb->getField(0, "audit_date");
		$sStartTime     = $objDb->getField(0, 'start_time');
		$sEndTime       = $objDb->getField(0, 'end_time');
	}

	$sFlag = $_SESSION['Flag'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
</head>

<body>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body" style="min-height:396px; height:396px;">
<?
	@include($sBaseDir."includes/messages.php");
?>

		<form name="frmData" id="frmData" method="post" action="quonda/save-audit-subscription.php" class="frmOutline" onsubmit="$('BtnSubmit').disabled=true;">
		<input type="hidden" name="Id" value="<?= $Id ?>" />

		<h2>Subscribe Audit</h2>

		<table border="0" cellpadding="3" cellspacing="0" width="100%">
		  <tr>
			<td width="90">Audit Code</td>
			<td width="20" align="center">:</td>
			<td><b><?= $sAuditCode ?></b></td>
		  </tr>

		  <tr>
			<td>Vendor</td>
			<td align="center">:</td>
			<td><?= $sVendor ?></td>
		  </tr>

		  <tr>
			<td>Brand</td>
			<td align="center">:</td>
			<td><?= getDbValue("brand", "tbl_brands", "id=(SELECT sub_brand_id FROM tbl_styles WHERE id='$iStyle')") ?></td>
		  </tr>

		  <tr>
			<td>Auditor</td>
			<td align="center">:</td>
			<td><?= $sAuditor ?></td>
		  </tr>

<?
	$sPos = "";

	$sSQL = "SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id IN ($sAdditionalPos) ORDER BY order_no";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (", ".$objDb->getField($i, 0));
	}
?>
		  <tr valign="top">
			<td>PO(s)</td>
			<td align="center">:</td>
			<td><?= ($sPO.$sPos) ?></td>
		  </tr>

		  <tr>
			<td>Style</td>
			<td align="center">:</td>
			<td><?= getDbValue("style", "tbl_styles", "id='$iStyle'") ?></td>
		  </tr>

		  <tr>
			<td>Audit Stage</td>
			<td align="center">:</td>
			<td><?= getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'") ?></td>
		  </tr>

		  <tr>
			<td>Audit Date</td>
			<td align="center">:</td>
			<td><?= formatDate($sAuditDate) ?></td>
		  </tr>

		  <tr>
			<td>Start Time</td>
			<td align="center">:</td>
			<td><?= formatTime($sStartTime) ?></td>
		  </tr>

		  <tr>
			<td>End Time</td>
			<td align="center">:</td>
			<td><?= formatTime($sEndTime) ?></td>
		  </tr>
<?
 	if ($sFlag != "AUDIT_SUBSCRIBED")
 	{
?>
		  <tr>
			<td><b>Alert Type</b></td>
			<td align="center">:</td>

		    <td>

			  <table border="0" cellpadding="0" cellspacing="0" width="100%">
			    <tr>
			 	  <td width="25"><input type="checkbox" name="AlertTypes[]" value="SMS" checked /></td>
				  <td width="60">SMS</td>
			 	  <td width="25"><input type="checkbox" name="AlertTypes[]" value="Email" /></td>
				  <td width="60">Email</td>
				  <td></td>
			    </tr>
			  </table>

		    </td>
		  </tr>

		  <tr>
			<td>Your Name</td>
			<td align="center">:</td>
			<td><?= $_SESSION['Name'] ?></td>
		  </tr>

		  <tr>
			<td>Your Email</td>
			<td align="center">:</td>
			<td><?= $_SESSION['Email'] ?></td>
		  </tr>

		  <tr>
			<td>Your Phone</td>
			<td align="center">:</td>
			<td><?= getDbValue("mobile", "tbl_users", "id='{$_SESSION['UserId']}'") ?></td>
		  </tr>
<?
	}
?>
		</table>

<?
 	if ($sFlag != "AUDIT_SUBSCRIBED")
 	{
?>
		<br />
		<br />

		<div class="buttonsBar"><input type="submit" id="BtnSubmit" value="" class="btnSubmit" title="Submit" /></div>
<?
	}
?>
		</form>

	  <br style="line-height:2px;" />
    </div>
<!--  Body Section Ends Here  -->

  </div>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>