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


	$sAuditCode = IO::strValue("AuditCode");

	if ($sAuditCode == "")
		$sAuditCode = getDbValue("audit_code", "tbl_qa_reports", "audit_result!=''", "id DESC");


	$iAuditId = substr($sAuditCode, 1);


	$sSQL = "SELECT user_id, style_id, vendor_id, report_id, start_time, end_time, audit_date, audit_stage, audit_result, total_gmts, checked_gmts, max_defects, dhu,
					(SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _PO,
					(SELECT line FROM tbl_lines WHERE id=tbl_qa_reports.line_id) AS _Line
			 FROM tbl_qa_reports
			 WHERE id='$iAuditId'";
	$objDb->query($sSQL);

	$iVendor       = $objDb->getField(0, 'vendor_id');
	$iUser         = $objDb->getField(0, 'user_id');
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
	$iMaxDefects    = $objDb->getField(0, "max_defects");
	$iChecked       = $objDb->getField(0, 'checked_gmts');
	$iReport        = $objDb->getField(0, "report_id");

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

	$iBrand        = getDbValue("sub_brand_id", "tbl_styles", "id='$iStyle'");
	$sDefectColors = getList("tbl_defect_types", "id", "color");


	$iMajor    = getDbValue("SUM(defects)", "tbl_qa_report_defects", "nature='1' AND audit_id='$iAuditId'");
	$iCritical = getDbValue("SUM(defects)", "tbl_qa_report_defects", "nature='2' AND audit_id='$iAuditId'");
	$iMinor    = getDbValue("SUM(defects)", "tbl_qa_report_defects", "nature='0' AND audit_id='$iAuditId'");


	$iMainBrand      = getDbValue("brand_id", "tbl_styles", "id='$iStyle'");
	$fAql            = getDbValue("aql", "tbl_brands", "id='$iMainBrand'");
	$fAql            = (($fAql == 0) ? 2.5 : $fAql);
	$iDefectsAllowed = 0;

	$iAqlChart         = array( );

	$iAqlChart["13"]   = array("0.65" => 0, "1" => 0, "1.5" => 0, "2.5" => 0, "4" => 1);
	$iAqlChart["20"]   = array("0.65" => 0, "1" => 0, "1.5" => 0, "2.5" => 1, "4" => 2);
	$iAqlChart["32"]   = array("0.65" => 0, "1" => 0, "1.5" => 1, "2.5" => 2, "4" => 3);
	$iAqlChart["50"]   = array("0.65" => 0, "1" => 1, "1.5" => 2, "2.5" => 3, "4" => 5);
	$iAqlChart["80"]   = array("0.65" => 1, "1" => 2, "1.5" => 3, "2.5" => 5, "4" => 7);
	$iAqlChart["125"]  = array("0.65" => 2, "1" => 3, "1.5" => 5, "2.5" => 7, "4" => 10);
	$iAqlChart["200"]  = array("0.65" => 3, "1" => 5, "1.5" => 7, "2.5" => 10, "4" => 14);
	$iAqlChart["315"]  = array("0.65" => 5, "1" => 7, "1.5" => 10, "2.5" => 14, "4" => 21);
	$iAqlChart["500"]  = array("0.65" => 7, "1" => 10, "1.5" => 14, "2.5" => 21, "4" => 21);
	$iAqlChart["800"]  = array("0.65" => 10, "1" => 14, "1.5" => 21, "2.5" => 21, "4" => 21);
	$iAqlChart["1250"] = array("0.65" => 14, "1" => 21, "1.5" => 21, "2.5" => 21, "4" => 21);

	if (@isset($iAqlChart["{$iQuantity}"]["{$fAql}"]))
		$iDefectsAllowed = $iAqlChart["{$iQuantity}"]["{$fAql}"];
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
        <td width="330"><img src="images/dashboard/matrix-sourcing.png" width="306" height="51" vspace="18" alt="" title="" /></td>
        <td bgcolor="#9dc01c" align="center"><img src="images/dashboard/quality-outlook.png" width="446" height="42" vspace="22" alt="" title="" /></td>

        <td width="600" bgcolor="#9dc01c">

          <div style="padding-top:5px;">
            <table border="0" cellspacing="0" cellpadding="0" width="95%">
              <tr>
                <td width="55%" align="right" style="color:#ffffff; font-size:24px; overflow:hidden;"><?= $sPO ?></td>
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
    <div style="padding:10px 0px 0px 0px; font-size:36px; color:#585858;">AUDIT CODE : <?= $sAuditCode ?></div>

    <div style="clear:both; float:right; padding:10px 20px 10px 0px; font-size:30px; color:#585858;">Target DR: <?= formatNumber($fTargetDr) ?>%</div>

    <div style="padding:10px 0px 10px 0px; font-size:36px; color:#999999;">
<?
	if ($iChecked == 0)
	{
?>
      AUDIT NOT STARTED YET
<?
	}

	else if ($iChecked > 0 && $iQuantity == 0)
	{
?>
      CURRENTLY AUDITING: <span style="color:#ff0000; font-size:36px;"><?= ($iChecked + 1) ?></span>
<?
	}

	else if ($iChecked < $iQuantity)
	{
?>
      CURRENTLY AUDITING: <span style="color:#ff0000; font-size:36px;"><?= ($iChecked + 1) ?></span> of <?= $iQuantity ?>
<?
	}

	else
	{
?>
      AUDITED QUANTITY: <?= $iQuantity ?>
<?
	}
?>
    </div>
  </div>

  <h2 style="background:<?= $sAuditColor ?>; font-size:30px; font-weight:normal; margin:10px 0px 0px 0px; padding:10px;"><?= (($sAuditResult == "") ? "PROGRESS" : "AUDIT COMPLETED") ?></h2>

  <table border="0" cellspacing="0" cellpadding="10" width="100%" id="Progress">
    <tr valign="top">
      <td>
		  <div style="padding:0px 0px 10px 10px;">
			<table border="0" cellspacing="0" cellpadding="5" width="100%">
			  <tr>
				<td width="10%" style="font-size:14px;"><b>Vendor</b></td>
				<td width="40%" style="font-size:14px;"><?= getDbValue("vendor", "tbl_vendors", "id='$iVendor'") ?></td>
				<td width="15%" style="font-size:14px;"><b>Brand</b></td>
				<td width="35%" style="font-size:14px;"><?= getDbValue("brand", "tbl_brands", "id='$iBrand'") ?></td>
			  </tr>

			  <tr>
				<td style="font-size:14px;"><b>Auditor</b></td>
				<td style="font-size:14px;"><?= getDbValue("name", "tbl_users", "id='$iUser'") ?></td>
				<td width="70" style="font-size:14px;"><b>Audit Date</b></td>
				<td style="font-size:14px;"><?= formatDate($sAuditDate) ?></td>
			  </tr>
			</table>
		  </div>

        <div style="position:relative; padding:25px; background:#eeeeee; background:-webkit-linear-gradient(#eeeeee, #cccccc); background:-moz-linear-gradient(#eeeeee, #cccccc); background:linear-gradient(#eeeeee, #cccccc);">

          <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
              <td width="65%"><div style="padding:0px 0px 5px 5px; font-size:24px;">DEFECTS ALLOWED: <?= $iDefectsAllowed ?></div></td>
              <td width="35%" align="right"><div style="padding:0px 0px 5px 5px; font-size:24px;">AQL: <?= $fAql ?></div></td>
            </tr>
          </table>

          <table border="0" cellspacing="0" cellpadding="5" width="100%">
            <tr>
              <td width="33%" style="font-size:16px;">Critical Defects Logged: <?= formatNumber($iCritical, false) ?></td>
              <td width="34%" align="center" style="font-size:16px;">Major Defects Logged: <?= formatNumber($iMajor, false) ?></td>
              <td width="33%" align="right" style="font-size:16px;">Minor Defects Logged: <?= formatNumber($iMinor, false) ?></td>
            </tr>

            <tr>
              <td colspan="3" height="25"></td>
            </tr>

          <table border="0" cellspacing="0" cellpadding="5" width="100%">
            <tr>
              <td width="50%" style="font-size:24px;">0</td>
              <td width="50%" align="right" style="font-size:24px;"><?= $iQuantity ?></td>
            </tr>

            <tr>
              <td colspan="2" width="100%">
                <div style="background:#ffffff; height:110px; position:relative;">
                  <div style="position:absolute; height:100px; width:<?= ($fCompleted - 1) ?>%; left:0.5%; top:5px; background:#ff7007; background:-webkit-linear-gradient(#fbb313, #ff7007); background:-moz-linear-gradient(#fbb313, #ff7007); background:linear-gradient(#fbb313, #ff7007);"></div>
                </div>
              </td>
            </tr>

<?
	if ($iQuantity > 0)
	{
?>
            <tr valign="top">
              <td style="font-size:24px;">COMPLETED: &nbsp;<?= formatNumber($fCompleted, false) ?>%</td>

              <td align="right" style="font-size:24px;">
                <?= (($sAuditResult == "") ? "CURRENT" : "") ?> DR: <span style="color:#ff0000;"><?= formatNumber($fDhu) ?>%</span><br />
                <span style="font-size:13px;">(Based on No of Defects, not Defective Garments)</span>
              </td>
            </tr>
<?
	}
?>
          </table>
        </div>
      </td>

      <td width="760">
        <div>
<?
	$sDefectTypes  = array( );
	$iDefectTypes  = array( );
	$iTotalDefects = array( );

	$sSQL = "SELECT dt.id, dt.type AS _DefectType, COALESCE(SUM(qad.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id AND qa.audit_type='B' AND qa.id='$iAuditId'
			 GROUP BY dc.type_id

			 UNION

	         SELECT dt.id, dt.type AS _DefectType, COALESCE(SUM(gfd.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_gf_report_defects gfd, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=gfd.audit_id AND gfd.code_id=dc.id AND dc.type_id=dt.id AND qa.report_id='6' AND qa.audit_type='B' AND qa.id='$iAuditId'
			 GROUP BY dc.type_id

			 ORDER BY _Defects DESC, _DefectType ASC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iDefectType = $objDb->getField($i, "id");
		$sDefectType = $objDb->getField($i, "_DefectType");
		$iDefects    = $objDb->getField($i, "_Defects");

		if (@in_array($iDefectType, $iDefectTypes))
		{
			$iIndex = @array_search($iDefectType, $iDefectTypes);

			$iTotalDefects[$iIndex] += $iDefects;
		}

		else
		{
			$iDefectTypes[]  = $iDefectType;
			$sDefectTypes[]  = $sDefectType;
			$iTotalDefects[] = $iDefects;
		}
	}
?>
			<div id="DefectClassChart" style="border:solid 1px #eeeeee;">loading...</div>

			<script type="text/javascript">
			<!--
			var objChart = new FusionCharts("scripts/fusion-charts/charts/Pie2D.swf", "DefectClass", "100%", "400", "0", "1");

			objChart.setXMLData("<chart caption='Defect Classification' showPercentageInLabel='1' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' bgcolor='ffffff' bordercolor='ffffff' animation='1' labelDisplay='WRAP' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='defect-classification'>" +
<?
	$iTotal = @array_sum($iTotalDefects);

	for ($i = 0; $i < count($iDefectTypes); $i ++)
	{
		$fPercent = @round((($iTotalDefects[$i] / $iTotal) * 100), 2);
?>
								"<set color='<?= $sDefectColors[$iDefectTypes[$i]] ?>' tooltext='Type: <?= htmlentities($sDefectTypes[$i], ENT_QUOTES) ?>{br}Defects: <?= $iTotalDefects[$i] ?> (<?= formatNumber($fPercent) ?>%)' label='<?= htmlentities($sDefectTypes[$i], ENT_QUOTES) ?> [<?= $iTotalDefects[$i] ?>]' value='<?= $iTotalDefects[$i] ?>' />" +
<?
	}
?>

								"</chart>");


			objChart.render("DefectClassChart");
			-->
			</script>
	    </div>
      </td>
    </tr>
  </table>

<?
	$sPictures = array( );

	@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

        if(strtotime($sAuditDate) < strtotime('2017-02-15'))
        {
                $sAuditPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_*.*");
                $sAuditPictures = @array_map("strtoupper", $sAuditPictures);
                $sAuditPictures = @array_unique($sAuditPictures);

                $sTemp = array( );

                foreach ($sAuditPictures as $sPicture)
                        $sTemp[] = $sPicture;

                $sAuditPictures = $sTemp;


                for ($j = 0; $j < count($sAuditPictures); $j ++)
                {
                        $sName  = @strtoupper($sAuditPictures[$j]);
                        $sName  = @basename($sName, ".JPG");
                        $sName  = @basename($sName, ".GIF");
                        $sName  = @basename($sName, ".PNG");
                        $sName  = @basename($sName, ".BMP");
                        $sParts = @explode("_", $sName);

                        $sPictures[] = $sAuditPictures[$j];
                }            
        }
        else 
        {
            $sDefectImages = getList("tbl_qa_report_defects", "id", "UPPER(picture)", "audit_id='$iAuditId'");
            
            foreach ($sDefectImages as $iDefectId => $sImage)
            {
                    if (@file_exists($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage) && @filesize($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage))
                    {
                            $sPictures[]    = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sImage);
                    }
            }
     
        }
        
	


	$iPictures = count($sPictures);

	if ($iPictures == 0)
	{
?>
				<div style="padding:20px; font-size:24px; border:solid 1px #cccccc; background:#f6f6f6; margin:0px 10px 10px 10px;">
				  No Defect Image Uploaded!<br />
				</div>
<?
	}

	else
	{
?>
				<div style="position:relative;">
				<ul style="list-style:none; margin:0px 10px 10px 10px; padding:0px;">
<?
		for ($i = 0; $i < $iPictures; $i ++)
		{
			$sName  = @strtoupper($sPictures[$i]);
			$sName  = @basename($sName, ".JPG");
			$sName  = @basename($sName, ".GIF");
			$sName  = @basename($sName, ".PNG");
			$sName  = @basename($sName, ".BMP");
			$sParts = @explode("_", $sName);

			//$sAuditCode   = $sParts[0];
			$sDefectCode  = $sParts[1];
			$sAreaCode    = $sParts[2];
			$sDefectTitle = "";
			$sTitle       = "";


			$sSQL = "SELECT defect,
							(SELECT type FROM tbl_defect_types WHERE id=tbl_defect_codes.type_id) AS _Type
					 FROM tbl_defect_codes
					 WHERE code='$sDefectCode' AND report_id='$iReport'";

			if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			{
				$sDefectTitle = $objDb->getField(0, 0);

				$sTitle .= $objDb->getField(0, 1);


				if ($iReport != 4 && $iReport != 6)
				{
					$sSQL = "SELECT area FROM tbl_defect_areas WHERE id='$sAreaCode'";

					if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
						$sTitle .= (" <b>�</b> ".$objDb->getField(0, 0));
				}

				$sTitle .= (" <b>�</b> ".$sDefectTitle);
			}
?>
				<li style="float:right; width:134px; overflow:hidden; margin:0px 0px 0px 10px; padding:0px;">
					<div class="qaPic" style="width:134px; height:113px;">
					  <div><a href="<?= $sPictures[$i] ?>" class="lightview" rel="gallery[defects]" title="<?= $sTitle ?> :: :: topclose: true"><img src="<?= $sPictures[$i] ?>" alt="" title="" style="width:130px; height:109px;" /></a></div>
					</div>

					<div style="overflow:hidden; line-height:13px; height:13px; font-size:11px; text-align:center;"><?= $sDefectTitle ?></div>
				  </li>
<?
		}
?>
	  			</ul>
			</div>

			<div style="clear:both; height:20px;"></div>
<?
	}
?>


</div>

<div style="font-size:14px; background:#595959; text-align:center; color:#ffffff; padding:8px; clear:both;">COPYRIGHTS TRIPLE TREE, INFORMATION IS PROVIDED FOR INTERNAL PURPOSES ONLY - THIS SERVICE IS PROVIDED BY THE CREATIVE AND IT DIVISION AT TRIPLE TREE</div>

<?
//	if ($sAuditResult == "")
	{
?>
<script type="text/javascript">
<!--
	setInterval(function( )
	{
		document.location.reload( );
	},

	30000);
-->
</script>
<?
	}
?>
</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>