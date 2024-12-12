<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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

	$Id = IO::intValue('Id');

	$sSQL = "SELECT *,
	                (SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_csc_audits.po_id) AS _OrderNo,
	                (SELECT vendor FROM tbl_vendors WHERE id=tbl_csc_audits.vendor_id) AS _Vendor,
	                (SELECT brand FROM tbl_brands WHERE id=tbl_csc_audits.brand_id) AS _Brand
	         FROM tbl_csc_audits
	         WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sOrderNo     = $objDb->getField(0, "_OrderNo");
		$sVendor      = $objDb->getField(0, "_Vendor");
		$sBrand       = $objDb->getField(0, "_Brand");
		$iPoId        = $objDb->getField(0, "po_id");
		$sAuditDate   = $objDb->getField(0, "audit_date");
		$sAuditResult = $objDb->getField(0, "audit_result");
		$iSampleSize  = $objDb->getField(0, "sample_size");
		$iQuantity    = $objDb->getField(0, "quantity");
	}


	$sSQL = "SELECT report_id FROM tbl_qa_reports WHERE po_id='$iPoId' OR FIND_IN_SET('$iPoId', additional_pos) LIMIT 1";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
		$ReportId = $objDb->getField(0, 0);

	else
		$ReportId = 1;
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
	<div id="Body" style="min-height:544px; height:544px;">
	  <h2>CSC Audit Report</h2>

	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	    <tr bgcolor="#ffffff">
		  <td width="100%">

		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
			    <td width="90">Vendor</td>
			    <td width="20" align="center">:</td>
			    <td><?= $sVendor ?></td>
			  </tr>

			  <tr>
			    <td>Brand</td>
			    <td align="center">:</td>
			    <td><?= $sBrand ?></td>
			  </tr>

			  <tr>
			    <td>Order No</td>
			    <td align="center">:</td>
			    <td><?= $sOrderNo ?></td>
			  </tr>

<?
	switch ($sAuditResult)
	{
		case "P" : $sAuditResult = "Pass"; break;
		case "F" : $sAuditResult = "Fail"; break;
		case "H" : $sAuditResult = "Hold"; break;
		default  : $sAuditResult = "N/A"; break;
	}
?>
			  <tr>
			    <td>Audit Result</td>
			    <td align="center">:</td>
			    <td><?= $sAuditResult ?></td>
			  </tr>

			  <tr>
			   <td>Sample Size</td>
			    <td align="center">:</td>
			    <td><?= formatNumber($iSampleSize, false) ?></td>
			  </tr>

			  <tr>
			   <td>Quantity</td>
			    <td align="center">:</td>
			    <td><?= formatNumber($iQuantity, false) ?></td>
			  </tr>
		    </table>

		    <br />
		    <h2 style="margin:0px;">Defects Details</h2>

		    <div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
			  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			    <tr class="sdRowHeader">
				  <td width="50" align="center"><b>#</b></td>
				  <td width="150"><b>PO Style</b></td>
				  <td><b>Code - Check Points</b></td>
				  <td width="100" align="center"><b>Defects</b></td>
			    </tr>

<?
	$sPoStylesList = array( );

	$sSQL = "SELECT DISTINCT(style_id),
	                (SELECT style FROM tbl_styles WHERE id=tbl_po_colors.style_id) AS _Style
	         FROM tbl_po_colors
	         WHERE po_id='$iPoId'
	         ORDER BY _Style";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$sPoStylesList[$objDb->getField($i, 0)] = $objDb->getField($i, 1);


	$sSQL = "SELECT id, style_id, code_id, defects FROM tbl_csc_audit_defects WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for($i = 0; $i < $iCount; $i ++)
	{
		$sSQL = ("SELECT code, defect FROM tbl_defect_codes WHERE id='".$objDb->getField($i, 'code_id')."'");
		$objDb2->query($sSQL);
?>

			    <tr class="sdRowColor">
				  <td align="center"><?= ($i + 1) ?></td>
				  <td><?= $sPoStylesList[$objDb->getField($i, 'style_id')] ?></td>
				  <td><?= $objDb2->getField(0, 0) ?> - <?= $objDb2->getField(0, 1) ?></td>
				  <td align="center"><?= $objDb->getField($i, 'defects') ?></td>
			    </tr>
<?
	}

	if ($iCount == 0)
	{
?>

			    <tr class="sdRowColor">
				  <td colspan="4" align="center">No Defect Found!</td>
			    </tr>
<?
	}
?>
			  </table>

		  </td>
	    </tr>
	  </table>

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
	$objDbGlobal->close( );

	@ob_end_flush( );
?>