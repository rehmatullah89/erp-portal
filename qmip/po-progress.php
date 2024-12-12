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


	$iPo    = IO::strValue("PO");
	$iStyle = IO::strValue("Style");

	$sAuditStagesList = getList("tbl_audit_stages", "code", "stage");
	$AuditStages      = getList("tbl_qa_reports q, tbl_audit_stages s", "q.audit_stage", "q.audit_stage", "q.audit_stage=s.code AND q.po_id='$iPo' AND q.style_id='$iStyle'", "s.position");
	$sDefectColors    = getList("tbl_defect_types dt, tbl_defect_codes dc, tbl_qa_report_defects qad", "dt.id", "dt.color", "qad.code_id=dc.id AND dc.type_id=dt.id AND qad.audit_id IN (SELECT id FROM tbl_qa_reports WHERE po_id='$iPo' AND style_id='$iStyle')");
	$sDefectTypesList = getList("tbl_defect_types", "id", "type");
	
	
	$sSQL = "SELECT vendor_id, brand_id FROM tbl_po WHERE id='$iPo'";
	$objDb->query($sSQL);

	$iVendor = $objDb->getField(0, 'vendor_id');
	$iBrand  = $objDb->getField(0, 'brand_id');
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
			    <h1><img src="images/h1/qmip/po-progress.jpg" width="311" height="20" alt="" title="" style="margin:10px 0px 10px 0px;" /></h1>

				  <div style="padding:5px 0px 5px 5px;">
					<table border="0" cellspacing="0" cellpadding="5" width="100%">
					  <tr>
						<td width="10%" style="font-size:14px;"><b>Vendor</b></td>
						<td width="48%" style="font-size:14px;"><?= getDbValue("vendor", "tbl_vendors", "id='$iVendor'") ?></td>
						<td width="10%" style="font-size:14px;"><b>Brand</b></td>
						<td width="32%" style="font-size:14px;"><?= getDbValue("brand", "tbl_brands", "id='$iBrand'") ?></td>
					  </tr>
					  
					  <tr valign="top">
						<td style="font-size:14px;"><b>PO #</b></td>
						<td style="font-size:14px;"><?= getDbValue("CONCAT(order_no, ' ', order_status)", "tbl_po", "id='$iPo'") ?></td>
						<td width="70" style="font-size:14px;"><b>Style #</b></td>
						<td style="font-size:14px;"><?= getDbValue("style", "tbl_styles", "id='$iStyle'") ?></td>
					  </tr>
					</table>
				  </div>
				  
				  <h2 style="background:#aaaaaa; font-size:18px; font-weight:normal; margin:10px 0px 0px 0px; padding:10px;">Defect Types</h2>				  

				  <div style="padding:8px;">
				    <table border="0" cellpadding="3" cellspacing="0" width="100%">
					  <tr valign="top">
<?
  	$iIndex = 1;

	foreach ($sDefectColors as $sKey => $sValue)
	{	
?>
						<td width="2%" align="left" style="text-align:left;"><div style="height:11px; width:11px; background:<?= $sValue ?>;"></div></td>
						<td width="14%" align="left" style="text-align:left;"><?= $sDefectTypesList[$sKey] ?></td>
<?
		if (($iIndex % 6) == 0)
		{
?>
						<td></td>
					  </tr>
					</table>

					<table border="0" cellpadding="3" cellspacing="0" width="100%">
					  <tr>
<?
		}
		
		$iIndex ++;
	}
?>
				        <td></td>
					  </tr>
					</table>
				  </div>	
				 
				    <br />
				 
				    <table border="0" cellspacing="0" cellpadding="0" width="100%">
<?
    foreach($AuditStages as $sStageCode)
    {
?>
					  <tr>
					    <td colspan="2"><h2 style="background:<?= getDbValue("color", "tbl_audit_stages", "code='$sStageCode'") ?>; font-size:18px; font-weight:normal; margin:2px 0px 2px 0px; padding:6px 0px 6px 10px;"><?= $sAuditStagesList[$sStageCode] ?></h2></td>
					  </tr>
					  
					  <tr valign="top">
					    <td width="50%">
<?
		$sSQL = "SELECT GROUP_CONCAT(DISTINCT(id) SEPARATOR ', ') AS _AuditIds, 
		                GROUP_CONCAT(DISTINCT(audit_code) SEPARATOR ', ') AS _Audits,
						(SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _Auditor,
						(SELECT line FROM tbl_lines WHERE id=tbl_qa_reports.line_id) AS _Line,
						audit_date
				FROM tbl_qa_reports
				WHERE (po_id='$iPo' OR FIND_IN_SET('$iPo', additional_pos)) AND style_id='$iStyle' AND audit_stage='$sStageCode'
				GROUP BY user_id, audit_date, line_id
				ORDER BY audit_date, _Auditor, _Line";
		$objDb->query($sSQL);
		
		$iCount = $objDb->getCount( );
?>
						  <div style="height:400px; overflow:auto;">  
							<table border="1" bordercolor="#aaaaaa" cellspacing="0" cellpadding="4" width="100%">
							  <tr bgcolor="#cccccc">
								<td width="30%"><b>Auditor</b></td>
								<td width="22%"><b>Line</b></td>
								<td width="22%"><b>Date</b></td>
								<td width="26%"><b>Audit Codes</b></td>
							  </tr>
<?              
		for ($i = 0; $i < $iCount; $i ++)
		{
			$iAudits  = $objDb->getField($i, "_AuditIds"); 
			$sAudits  = $objDb->getField($i, "_Audits"); 
			$sAuditor = $objDb->getField($i, "_Auditor"); 
			$sLine    = $objDb->getField($i, "_Line");
			$sDate    = $objDb->getField($i, "audit_date");

			$sAuditsLinks = "";
			$iAuditsList = getList("tbl_qa_reports", "id", "audit_code", "id IN ($iAudits)");
			
			foreach($iAuditsList as $iAudit => $sAudit)
				$sAuditsLinks .= ("<a href='". SITE_URL ."qmip/audit-progress.php?AuditCode={$sAudit}' target='_blank'>{$sAudit}</a>, ");
?>
							  <tr>
							    <td><?= $sAuditor ?></td>
								<td><?= $sLine ?></td>
								<td><?= formatDate($sDate) ?></td>
								<td><?= rtrim($sAuditsLinks,', ') ?></td>
							  </tr>
<?
		}
?>
						    </table>
						  </div>	
						</td>

					    <td width="50%">
<?
		$sStageAudits = array();

		$sSQL = "SELECT id FROM tbl_qa_reports WHERE po_id='$iPo' AND style_id='$iStyle' AND audit_stage='$sStageCode'";
		$objDb->query($sSQL);
		
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sStageAudits[] = $objDb->getField($i, "id");

		
		$iTotalDefects = 0;
		$iDefectTypes  = array( );
		$sDefectTypes  = array( );
		$iDefectsArr   = array( );

		$sSQL = "SELECT dt.id, COALESCE(SUM(qad.defects), 0) AS _Defects,  dt.type AS _DefectType
				 FROM tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
				 WHERE qad.code_id=dc.id AND dc.type_id=dt.id AND qad.audit_id IN (".  implode(",", $sStageAudits).")
				 GROUP BY dc.type_id";
		$objDb->query($sSQL);
		
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iDefectType = $objDb->getField($i, "id");
			$sDefectType = $objDb->getField($i, "_DefectType");
			$iDefects    = $objDb->getField($i, "_Defects");

			$iDefectTypes[]  = $iDefectType;
			$sDefectTypes[]  = $sDefectType;
			$iDefectsArr[]   = $iDefects;
			$iTotalDefects  += $iDefects;
		}
?>
						<div id="DefectClassChart<?=$sStageCode?>" style="border:solid 1px #eeeeee;">loading...</div>

						<script type="text/javascript">
						<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/Pie2D.swf", "DefectClass<?=$sStageCode?>", "100%", "400", "0", "1");

						objChart.setXMLData("<chart caption='' showPercentageInLabel='1' formatNumberScale='0' showValues='0' showLabels='0' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' bgcolor='ffffff' bordercolor='ffffff' animation='1' labelDisplay='WRAP' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0'>" +
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$fPercent = @round((($iDefectsArr[$i] / $iTotalDefects) * 100), 2);
?>
											"<set color='<?= $sDefectColors[$iDefectTypes[$i]] ?>' tooltext='Type: <?= htmlentities($sDefectTypes[$i], ENT_QUOTES) ?>{br}Defects: <?= $iDefectsArr[$i] ?> (<?= formatNumber($fPercent) ?>%)' label='<?= htmlentities($sDefectTypes[$i], ENT_QUOTES) ?> [<?= $iDefectsArr[$i] ?>]' value='<?= $iDefectsArr[$i] ?>' />" +
<?
	}
?>
											"</chart>");


						objChart.render("DefectClassChart<?=$sStageCode?>");
						-->
						</script>
					  </td>
					</tr>
<?
    }
?>
				  </table>

  				</div>
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