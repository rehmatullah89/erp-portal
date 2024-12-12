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

		$iLastAudits = @explode(",", $sLastAudits);
		$iAuditId    = $iLastAudits[1];

		if ($iAuditId > 0)
		{
			$sSQL = "SELECT audit_code, user_id, style_id, vendor_id, start_time, end_time, audit_date, audit_stage, audit_result, total_gmts, ship_qty, dhu, qa_comments,
							(SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _PO,
							(SELECT line FROM tbl_lines WHERE id=tbl_qa_reports.line_id) AS _Line,
							(SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id AND nature>'0') AS _Defects,
							(SELECT COALESCE(SUM(defects), 0) FROM tbl_gf_report_defects WHERE audit_id=tbl_qa_reports.id) AS _GfDefects
					 FROM tbl_qa_reports
					 WHERE id='$iAuditId'";
			$objDb->query($sSQL);

			$sAuditCode     = $objDb->getField(0, 'audit_code');
			$iVendorId      = $objDb->getField(0, 'vendor_id');
			$iUserId        = $objDb->getField(0, 'user_id');
			$iStyle         = $objDb->getField(0, "style_id");
			$sPO            = $objDb->getField(0, "_PO");
			$sStartTime     = $objDb->getField(0, 'start_time');
			$sEndTime       = $objDb->getField(0, 'end_time');
			$sAuditDate     = $objDb->getField(0, 'audit_date');
			$sAuditStage    = $objDb->getField(0, 'audit_stage');
			$sAuditResult   = $objDb->getField(0, 'audit_result');
			$sLine          = $objDb->getField(0, '_Line');
			$iQuantity      = $objDb->getField(0, 'total_gmts');
			$fDhu           = $objDb->getField(0, 'dhu');
			$iShipQty       = $objDb->getField(0, 'ship_qty');
			$iDefects       = $objDb->getField(0, '_Defects');
			$iDefects      += $objDb->getField(0, '_GfDefects');
			$sComments      = $objDb->getField(0, "qa_comments");


			switch ($sAuditResult)
			{
				case "P"  :  $sAuditResult = "Pass"; $sColor = "#63b200"; break;
				case "F"  :  $sAuditResult = "Fail"; $sColor = "#ff0f00"; break;
				case "H"  :  $sAuditResult = "Held"; $sColor = "#ff8400"; break;
				case "A"  :  $sAuditResult = "Pass"; $sColor = "#63b200"; break;
				case "B"  :  $sAuditResult = "Pass"; $sColor = "#63b200"; break;
				case "C"  :  $sAuditResult = "Fail"; $sColor = "#ff0f00"; break;
			}


			if ($iStyle > 0)
			{
				$sSQL = "SELECT style, sketch_file FROM tbl_styles WHERE id='$iStyle'";
				$objDb->query($sSQL);

				$sStyle   = $objDb->getField(0, 'style');
				$sPicture = $objDb->getField(0, 'sketch_file');
			}
?>

          <h2 style="background:#b0b0b0; font-size:21px; font-weight:normal; text-align:center; color:#222222; padding:8px; margin:15px 0px 2px 0px;">MOST RECENT AUDIT</h2>
          <h2 style="background:<?= $sColor ?>; font-size:21px; font-weight:normal; text-align:center; color:#ffffff; padding:8px;"><?= strtoupper($sAuditResult) ?></h2>

          <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr valign="top">
              <td width="60%">

			    <table border="0" cellspacing="0" cellpadding="3" width="100%">
				  <tr>
				    <td width="110" style="font-size:14px;"><b>Audit Code</b></td>
				    <td style="font-size:14px;"><b style="color:#ff8400;"><?= $sAuditCode ?></b></td>
				  </tr>

				  <tr>
				    <td style="font-size:14px;"><b>Vendor</b></td>
				    <td style="font-size:14px;"><?= $sVendorsList[$iVendorId] ?></td>
				  </tr>

				  <tr>
				    <td style="font-size:14px;"><b>Order #</b></td>
				    <td style="font-size:14px;"><?= $sPO ?></td>
				  </tr>

				  <tr>
				    <td style="font-size:14px;"><b>Line</b></td>
				    <td style="font-size:14px;"><?= $sLine ?></td>
				  </tr>

				  <tr>
				    <td style="font-size:14px;"><b>Auditor</b></td>
				    <td style="font-size:14px;"><?= getDbValue("name", "tbl_users", "id='$iUserId'") ?></td>
				  </tr>

				  <tr>
				    <td style="font-size:14px;"><b>Ship QTY</b></td>
				    <td style="font-size:14px;"><?= formatNumber($iShipQty, false) ?></td>
				  </tr>

				  <tr>
				    <td style="font-size:14px;"><b>Audit Date</b></td>
				    <td style="font-size:14px;"><?= formatDate($sAuditDate) ?></td>
				  </tr>

				  <tr>
				    <td style="font-size:14px;"><b>Audit Time</b></td>
				    <td style="font-size:14px;"><?= formatTime($sStartTime) ?> - <?= formatTime($sEndTime) ?></td>
				  </tr>

				  <tr>
				    <td style="font-size:14px;"><b>Audit Stage</b></td>
				    <td style="font-size:14px;"><b><?= getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'") ?></b></td>
				  </tr>

				  <tr>
				    <td style="font-size:14px;"><b>Sample Size</b></td>
				    <td style="font-size:14px;"><?= $iQuantity ?></td>
				  </tr>

				  <tr>
				    <td style="font-size:14px;"><b>Defects</b></td>
				    <td style="font-size:14px;"><?= $iDefects ?></td>
				  </tr>

				  <tr>
				    <td style="font-size:14px;"><b>DR</b></td>
				    <td style="font-size:14px;"><b style="color:#ff0f00;"><?= formatNumber($fDhu) ?>%</b></td>
				  </tr>

				  <tr valign="top">
				    <td style="font-size:14px;"><b>Comments</b></td>
				    <td><div style="height:60px; font-size:14px; overflow:hidden;"><?= nl2br($sComments) ?></div></td>
				  </tr>
			    </table>
			  </td>

			  <td width="5%"></td>

			  <td width="35%">
<?
			if ($iStyle > 0)
			{
?>
			    <center><b style="font-size:14px;">Style # <?= $sStyle ?></b></center>
<?
			}

			if ($sPicture != "")
			{
?>
                <center><img src="<?= (STYLES_SKETCH_DIR.$sPicture) ?>" width="100%" vspace="10" alt="" title="" /></center>
<?
			}
?>
			  </td>
			</tr>
		  </table>
<?
		}
?>