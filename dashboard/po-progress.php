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

<body style="min-width:1400px; margin:0px; background:#ffffff;">

<div>
  <div style="padding:15px 0px 10px 15px;">
    <table border="0" cellspacing="0" cellpadding="0" width="100%">
      <tr valign="top">
        <td width="100%"><img src="images/dashboard/matrix-sourcing.png" width="306" height="51" vspace="18" alt="" title="" /></td>

        <td width="600" bgcolor="#9dc01c">

          <div style="padding-top:5px;">
            <table border="0" cellspacing="0" cellpadding="0" width="95%">
              <tr>
                <td width="55%" align="right" style="color:#ffffff; font-size:24px; overflow:hidden;"><?= getDbValue("CONCAT(order_no, ' ', order_status)", "tbl_po", "id='$iPo'") ?></td>
                <td width="10%" align="center" rowspan="2" style="color:#ffffff; font-size:56px;">/</td>
                <td width="35%" align="left" style="color:#ffffff; font-size:24px; overflow:hidden;"><?= getDbValue("style", "tbl_styles", "id='$iStyle'") ?></td>
              </tr>

              <tr>
                <td align="right" style="color:#ffffff; font-size:17px;">PO #</td>
                <td align="left" style="color:#ffffff; font-size:17px;">Style #</td>
              </tr>
            </table>
          </div>

        </td>
      </tr>
    </table>
  </div>


  <div style="padding:0px 0px 0px 10px;">
    <div style="float:right; background:<?= $sStageColor ?>; padding:10px 50px 10px 50px; font-size:36px; color:#ffffff;"><?= strtoupper($sAuditStage) ?></div>
  </div>

  <h2 style="background:<?= $sAuditColor ?>; font-size:30px; font-weight:normal; margin:10px 0px 0px 0px; padding:10px;">Stage Wise PO Progress</h2>
 <div style="padding:5px;">
     <table border="0" cellpadding="3" cellspacing="0" width="100%" align="left">
        <tr>
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
                        </tr>
                        <tr>
<?
		}
                $iIndex ++;
	}
  ?>
     </table>                            
  <table border="0" cellspacing="0" cellpadding="5" width="100%">
<?
    foreach($AuditStages as $iAuditStage)
    {
?>
      <tr><td colspan="2"><h2 style="background:green; font-size:30px; font-weight:normal; margin:10px 0px 0px 0px; padding:10px;">Stage: <?=$sAuditStagesList[$iAuditStage]?></h2></td></tr>
      <tr>        
      <td width="50%" style="vertical-align:top;">
    <?
        $sSQL = "SELECT GROUP_CONCAT(DISTINCT(id) SEPARATOR ', ') AS _AuditIds, GROUP_CONCAT(DISTINCT(audit_code) SEPARATOR ', ') AS _Audits,
                            (SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _Auditor,
                            (SELECT line FROM tbl_lines WHERE id=tbl_qa_reports.line_id) AS _Line
			 FROM tbl_qa_reports
			 WHERE po_id='$iPo' AND style_id='$iStyle' AND audit_stage='$iAuditStage'
                         GROUP By user_id, line_id";

        $objDb->query($sSQL);
        $iCount = $objDb->getCount( );

    ?>
          <div style="padding-left:5px;">
          <table border="0" cellspacing="0" cellpadding="5" width="100%">
              <tr valign="top">
                  <td><h3>Auditors</h3></td><td><h3>Audit Line</h3></td><td><h3>Audit Codes</h3></td>
              </tr>
<?
               

                for ($i = 0; $i < $iCount; $i ++)
                {
                   $iAudits  = $objDb->getField($i, "_AuditIds"); 
                   $sAudits  = $objDb->getField($i, "_Audits"); 
                   $sAuditor = $objDb->getField($i, "_Auditor"); 
                   $sLine    = $objDb->getField($i, "_Line"); 
                   
                   $sAuditsLinks = "";
                   $iAuditsList = getList("tbl_qa_reports", "id", "audit_code", "id IN ($iAudits)");
                   foreach($iAuditsList as $iAudit => $sAudit)
                       $sAuditsLinks .= "<a href=". SITE_URL ."dashboard/progress.php?AuditCode=". $sAudit ." target='_blank'>". $sAudit ."</a>, ";
?>
              <tr><td><?=$sAuditor?></td><td><?=$sLine?></td><td><?=  rtrim($sAuditsLinks,', ')?></td></tr>
<?
                }
?>
          </table>
          </div>    
         </td>

      <td width="50%">
        <div>
<?

        $sStageAudits = array();
        
        $sSQL = "SELECT id FROM tbl_qa_reports WHERE po_id='$iPo' AND style_id='$iStyle' AND audit_stage='$iAuditStage'";
        $objDb->query($sSQL);
	$iCount = $objDb->getCount( );
        
        for ($i = 0; $i < $iCount; $i ++)
            $sStageAudits[] = $objDb->getField($i, "id");
        
        $iTotalDefects  = 0;
        $iDefectTypes   = array( );
        $sDefectTypes   = array( );
	$iDefectsArr    = array( );
        
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
			<div id="DefectClassChart<?=$iAuditStage?>" style="border:solid 1px #eeeeee;">loading...</div>

			<script type="text/javascript">
			<!--
			var objChart = new FusionCharts("scripts/fusion-charts/charts/Pie2D.swf", "DefectClass<?=$iAuditStage?>", "100%", "400", "0", "1");

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


			objChart.render("DefectClassChart<?=$iAuditStage?>");
			-->
			</script>
	    </div>
      </td>
    </tr>
<?
    }
?>
  </table>

</div>

<div style="font-size:14px; background:#595959; text-align:center; color:#ffffff; padding:8px; clear:both;">COPYRIGHTS TRIPLE TREE, INFORMATION IS PROVIDED FOR INTERNAL PURPOSES ONLY - THIS SERVICE IS PROVIDED BY THE CREATIVE AND IT DIVISION AT TRIPLE TREE</div>
</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>