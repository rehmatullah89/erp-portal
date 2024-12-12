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


	$sSQL = "SELECT id, audit_code, user_id, style_id, vendor_id, audit_date, audit_stage, audit_result, total_gmts, checked_gmts, dhu,
					(SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _PO,
					(SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id) AS _Defects,
					(SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id AND nature='1') AS _JkDefects,
					(SELECT COALESCE(SUM(defects), 0) FROM tbl_gf_report_defects WHERE audit_id=tbl_qa_reports.id) AS _GfDefects
			 FROM tbl_qa_reports
			 WHERE audit_result!='' AND NOT FIND_IN_SET(report_id, '$sQmipReports')
			 ORDER BY id DESC
			 LIMIT 6";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/fusion-charts/FusionCharts.js"></script>
  <script type="text/javascript" src="scripts/fusion-charts/FusionChartsExportComponent.js"></script>
</head>

<body style="margin:0px; background:#ffffff;">

<div>
  <div style="padding:15px 0px 10px 15px;">
    <table border="0" cellspacing="0" cellpadding="0" width="100%">
      <tr valign="top">
        <td width="330"><img src="images/dashboard/matrix-sourcing.png" width="306" height="51" vspace="18" alt="" title="" /></td>
        <td bgcolor="#9dc01c" align="center"><img src="images/dashboard/quality-outlook.png" width="446" height="42" vspace="22" alt="" title="" /></td>

        <td width="600" bgcolor="#9dc01c">
        </td>
      </tr>
    </table>
  </div>


  <div style="padding:10px; font-size:36px; background:#888888;">
    <marquee scrollAmount="3" scrollDelay="1" onmouseover="this.stop( );" onmouseout="this.start( );">
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$sAuditCode = $objDb->getField($i, 'audit_code');
?>
      <a href="<?= SITE_URL ?>dashboard/progress.php?AuditCode=<?= $sAuditCode ?>" target="_blank" style="font-size:36px; color:#ffffff;"><?= $sAuditCode ?></a>
      <b>&nbsp;&bull;&nbsp;</b>
<?
	}
?>
    </marquee>
  </div>

  <table border="0" cellspacing="0" cellpadding="10" width="100%" id="Progress">
    <tr valign="top">
      <td width="49.7%">
<?
	$iHalf = @ceil($iCount / 2);

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sAuditCode     = $objDb->getField($i, 'audit_code');
		$iVendor        = $objDb->getField($i, 'vendor_id');
		$iUser          = $objDb->getField($i, 'user_id');
		$iStyle         = $objDb->getField($i, "style_id");
		$sPO            = $objDb->getField($i, "_PO");
		$sAuditDate     = $objDb->getField($i, 'audit_date');
		$sAuditStage    = $objDb->getField($i, 'audit_stage');
		$sAuditResult   = $objDb->getField($i, 'audit_result');
		$iQuantity      = $objDb->getField($i, 'total_gmts');
		$fDhu           = $objDb->getField($i, 'dhu');
		$iChecked       = $objDb->getField($i, 'checked_gmts');
		$iDefects       = $objDb->getField($i, '_Defects');
		$iDefects      += $objDb->getField($i, '_GfDefects');
		$iDefects      += $objDb->getField($i, '_JkDefects');

		if ($sAuditResult != "" && $iChecked == 0)
			$iChecked = $iQuantity;


		$sStageDr    = getDbValue("dr_field", "tbl_audit_stages", "code='$sAuditStage'");
		$fTargetDr   = getDbValue($sStageDr, "tbl_vendors", "id='$iVendor'");
		$sStageColor = getDbValue("color", "tbl_audit_stages", "code='$sAuditStage'");
		$sAuditStage = getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'");

		switch ($sAuditResult)
		{
			case "P"  : $sAuditColor = "#83ae00"; break;
			case "F"  : $sAuditColor = "#ff0000"; break;
			case "H"  : $sAuditColor = "#eebb22"; break;
			case "A"  : $sAuditColor = "#83ae00"; break;
			case "B"  : $sAuditColor = "#83ae00"; break;
			case "C"  : $sAuditColor = "#ff0000"; break;
			default   : $sAuditColor = "#898989";
		}

		if ($iQuantity > 0)
			$fCompleted = @round(($iChecked / $iQuantity) * 100);

		else if ($iChecked > 0)
			$fCompleted = 50;

		$iBrand = getDbValue("sub_brand_id", "tbl_styles", "id='$iStyle'");
?>
        <div style="background:<?= $sAuditColor ?>; color:#ffffff; padding:5px 0px 5px 10px; font-size:30px;">AUDIT CODE : <?= $sAuditCode ?></div>

        <div onclick="window.open('<?= SITE_URL ?>dashboard/progress.php?AuditCode=<?= $sAuditCode ?>');" style="cursor:pointer; position:relative; padding:25px; background:#eeeeee; background:-webkit-linear-gradient(#eeeeee, #cccccc); background:-moz-linear-gradient(#eeeeee, #cccccc); background:linear-gradient(#eeeeee, #cccccc);">
			<table border="0" cellspacing="0" cellpadding="3" width="100%">
			  <tr>
				<td width="100" style="font-size:14px;"><b>Vendor</b></td>
				<td width="300" style="font-size:14px;"><?= getDbValue("vendor", "tbl_vendors", "id='$iVendor'") ?></td>
				<td width="100" style="font-size:14px;"><b>Brand</b></td>
				<td style="font-size:14px;"><?= getDbValue("brand", "tbl_brands", "id='$iBrand'") ?></td>
			  </tr>

			  <tr>
				<td style="font-size:14px;"><b>PO #</b></td>
				<td style="font-size:14px;"><?= $sPO ?></td>
				<td width="70" style="font-size:14px;"><b>Style #</b></td>
				<td style="font-size:14px;"><?= getDbValue("style", "tbl_styles", "id='$iStyle'") ?></td>
			  </tr>

			  <tr>
				<td style="font-size:14px;"><b>Auditor</b></td>
				<td style="font-size:14px;"><?= getDbValue("name", "tbl_users", "id='$iUser'") ?></td>
				<td width="70" style="font-size:14px;"><b>Audit Date</b></td>
				<td style="font-size:14px;"><?= formatDate($sAuditDate) ?></td>
			  </tr>

			  <tr>
				<td style="font-size:14px;"><b>Audit Stage</b></td>
				<td style="font-size:14px;"><?= $sAuditStage ?></td>
				<td width="70" style="font-size:14px;"><b>Target DR</b></td>
				<td style="font-size:14px;"><?= formatNumber($fTargetDr) ?>%</td>
			  </tr>
			</table>

          <br />

          <table border="0" cellspacing="0" cellpadding="5" width="100%">
            <tr>
              <td width="50%" style="font-size:24px;">0</td>
              <td width="50%" align="right" style="font-size:24px;"><?= (($iQuantity == 0) ? "CUSTOM SIZE" : $iQuantity) ?></td>
            </tr>

            <tr>
              <td colspan="2" width="100%">
                <div style="background:#ffffff; height:90px; position:relative;">
                  <div style="position:absolute; height:80px; width:<?= ($fCompleted - 1) ?>%; left:0.5%; top:5px; background:#ff7007; background:-webkit-linear-gradient(#fbb313, #ff7007); background:-moz-linear-gradient(#fbb313, #ff7007); background:linear-gradient(#fbb313, #ff7007);"></div>
                </div>
              </td>
            </tr>
<?
		if ($iQuantity > 0)
		{
?>
            <tr>
              <td style="font-size:24px;">COMPLETED: &nbsp;<?= formatNumber($fCompleted, false) ?>%</td>
              <td align="right" style="font-size:24px;"><?= (($sAuditResult == "") ? "CURRENT" : "") ?> DR: <span style="color:#ff0000;"><?= formatNumber($fDhu) ?>%</span></td>
            </tr>
<?
		}
?>
          </table>
        </div>

        <br />
<?
		if ($i == ($iHalf - 1))
		{
?>
      </td>

      <td width="0.6%"></td>

      <td width="49.7%">
<?
		}
	}
?>
      </td>
    </tr>
  </table>
</div>

<div style="font-size:14px; background:#595959; text-align:center; color:#ffffff; padding:8px; clear:both;">COPYRIGHTS TRIPLE TREE, INFORMATION IS PROVIDED FOR INTERNAL PURPOSES ONLY - THIS SERVICE IS PROVIDED BY THE CREATIVE AND IT DIVISION AT TRIPLE TREE</div>


<script type="text/javascript">
<!--
	setInterval(function( )
	{
		document.location.reload( );
	},

	60000);
-->
</script>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>