<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree QUONDA App                                                                   **
	**  Version 3.0                                                                              **
	**                                                                                           **
	**  http://app.3-tree.com                                                                    **
	**                                                                                           **
	**  Copyright 2008-17 (C) Triple Tree                                                        **
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
	***********************************************************************************************
	\*********************************************************************************************/

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$User       = IO::strValue('User');
	$AuditCode  = IO::strValue('AuditCode');
	$Brand      = IO::intValue('Brand');
	$Vendor     = IO::intValue('Vendor');
	$Audits     = IO::strValue('Audits');
	$DateRange  = IO::strValue('DateRange');
	$DefectType = IO::intValue("DefectType");

	@list($FromDate, $ToDate) = @explode(":", $DateRange);


	if ($User == "")
		die("Invalid Request");


	$sSQL = "SELECT id, vendors, brands, style_categories, report_types, audit_stages, status, guest FROM tbl_users WHERE MD5(id)='$User'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		die("Invalid User");

	else if ($objDb->getField(0, "status") != "A")
		die("User Account is Disabled");


	$sBrands          = $objDb->getField(0, "brands");
	$sVendors         = $objDb->getField(0, "vendors");
	$sStyleCategories = $objDb->getField(0, "style_categories");
	$sReportTypes     = $objDb->getField(0, "report_types");
	$sAuditStages     = $objDb->getField(0, "audit_stages");
	$sGuest           = $objDb->getField(0, "guest");


	$sConditions = " AND qa.audit_type='B' AND qa.audit_result!='' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') AND FIND_IN_SET(qa.report_id, '$sReportTypes') ";

	if ($AuditCode != "")
		$sConditions .= " AND qa.audit_code='$AuditCode' ";

	if ($Audits == "F")
		$sConditions .= " AND qa.audit_stage='F' ";

	else if ($Audits == "I")
		$sConditions .= " AND qa.audit_stage!='F' ";

	if ($Vendor > 0)
		$sConditions .= " AND qa.vendor_id='$Vendor' ";

	else
		$sConditions .= " AND qa.vendor_id IN ($sVendors) ";


	if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE)
	{
		$sConditions .= " AND qa.style_id IN (SELECT id FROM tbl_styles WHERE  ";

		if ($Brand > 0)
			$sConditions .= " sub_brand_id='$Brand' ";

		else
			$sConditions .= " FIND_IN_SET(sub_brand_id, '$sBrands') ";

		$sConditions .= "  AND FIND_IN_SET(category_id, '$sStyleCategories')) ";
	}

	else
	{
		$sConditions .= " AND (qa.style_id='0' OR qa.style_id IN (SELECT id FROM tbl_styles WHERE ";

		if ($Brand > 0)
			$sConditions .= " sub_brand_id='$Brand' ";

		else
			$sConditions .= " FIND_IN_SET(sub_brand_id, '$sBrands') ";

		$sConditions .= "  AND FIND_IN_SET(category_id, '$sStyleCategories'))) ";
	}




	$sDefectTypes  = array( );
	$iDefectTypes  = array( );
	$iTotalDefects = array( );

	$sSQL = "SELECT dt.id, dt.type AS _DefectType, COALESCE(SUM(qad.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id AND qad.nature>'0' $sConditions
			 GROUP BY dc.type_id

			 UNION

	         SELECT dt.id, dt.type AS _DefectType, COALESCE(SUM(gfd.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_gf_report_defects gfd, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=gfd.audit_id AND gfd.code_id=dc.id AND dc.type_id=dt.id AND qa.report_id='6' $sConditions
			 GROUP BY dc.type_id

			 ORDER BY _Defects DESC, _DefectType ASC
			 LIMIT 5";
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


	$sDefectColors = getList("tbl_defect_types", "id", "color");

	header("Content-type: text/xml");
?>
<chart bgcolor="83ae00" canvasBgColor="83ae00" bgAlpha="100" canvasbgAlpha="100" showBorder="0" showPercentageInLabel="1" formatNumberScale="0" showValues="1" showLabels="0" showLegend="1" legendIconScale="2" legendPosition="RIGHT" legendBgColor="83ae00" legendBgAlpha="100" legendBorderColor="83ae00" legendShadow="0" decimals="1" numberSuffix="%" chartBottomMargin="5" plotFillAlpha="95" animation="1" labelDisplay="WRAP">
<?
	$iTotal = @array_sum($iTotalDefects);

	for ($i = 0; $i < count($iDefectTypes); $i ++)
	{
		$fPercent = @round((($iTotalDefects[$i] / $iTotal) * 100), 2);
?>
<set color='<?= $sDefectColors[$iDefectTypes[$i]] ?>' tooltext='Type: <?= htmlentities($sDefectTypes[$i], ENT_QUOTES) ?>{br}Defects: <?= $iTotalDefects[$i] ?> (<?= formatNumber($fPercent) ?>%)' label='<?= htmlentities($sDefectTypes[$i], ENT_QUOTES) ?>' value='<?= formatNumber($fPercent) ?>' isSliced='<?= (($iDefectTypes[$i] == $DefectType) ? 1 : 0) ?>' link="javascript:showTypePictures('<?= $iDefectTypes[$i] ?>')" />
<?
	}
?>

<styles>
<definition>
<style name="LabelFont" type="FONT" face="Verdana" size="11" color="ffffff" bold="0" />
<style name="LegendFont" type="FONT" face="Verdana" size="12" color="ffffff" bold="0" />
</definition>

<application>
<apply toObject="DataLabels" styles="LabelFont" />
<apply toObject="Legend" styles="LabelFont" />
</application>
</styles>

</chart>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>