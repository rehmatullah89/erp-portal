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

	$User      = IO::strValue('User');
	$AuditCode = IO::strValue('AuditCode');

	if ($User == "")
		die("Invalid Request");


	$sSQL = "SELECT id, vendors, brands, style_categories, status, guest FROM tbl_users WHERE MD5(id)='$User'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		die("Invalid User");

	else if ($objDb->getField(0, "status") != "A")
		die("User Account is Disabled");


	$sBrands          = $objDb->getField(0, "brands");
	$sVendors         = $objDb->getField(0, "vendors");
	$sStyleCategories = $objDb->getField(0, "style_categories");
	$sGuest           = $objDb->getField(0, "guest");


	$sDefectTypes  = array( );
	$iDefectTypes  = array( );
	$iTotalDefects = array( );

	$sSQL = "SELECT dt.id, dt.type AS _DefectType, COALESCE(SUM(qad.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id AND qad.nature>'0' AND qa.audit_code='$AuditCode'
			 GROUP BY dc.type_id

			 UNION

	         SELECT dt.id, dt.type AS _DefectType, COALESCE(SUM(gfd.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_gf_report_defects gfd, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=gfd.audit_id AND gfd.code_id=dc.id AND dc.type_id=dt.id AND qa.report_id='6' AND qa.audit_code='$AuditCode'
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
<chart bgcolor="ffffff" canvasBgColor="ffffff" bgAlpha="100" canvasbgAlpha="100" showBorder="0" showPercentageInLabel="1" formatNumberScale="0" showValues="0" showLabels="0" showLegend="0" legendIconScale="1" legendPosition="RIGHT" legendBgColor="f6f6f6" legendBgAlpha="100" legendBorderColor="eeeeee" legendShadow="0" decimals="1" numberSuffix="" chartLeftMargin="0" chartRightMargin="0" chartTopMargin="0" chartBottomMargin="0" plotFillAlpha="95" animation="1" labelDisplay="WRAP">
<?
	$iTotal = @array_sum($iTotalDefects);

	for ($i = 0; $i < count($iDefectTypes); $i ++)
	{
		$fPercent = @round((($iTotalDefects[$i] / $iTotal) * 100), 2);
?>
<set ChartNoDataText='' color='<?= $sDefectColors[$iDefectTypes[$i]] ?>' tooltext='Type: <?= htmlentities($sDefectTypes[$i], ENT_QUOTES) ?>{br}Defects: <?= $iTotalDefects[$i] ?> (<?= formatNumber($fPercent) ?>%)' label='<?= htmlentities($sDefectTypes[$i], ENT_QUOTES) ?>' value='<?= $iTotalDefects[$i] ?>' />
<?
	}
?>

<styles>
<definition>
<style name="LabelFont" type="FONT" face="Verdana" size="11" color="333333" bold="0" />
<style name="LegendFont" type="FONT" face="Verdana" size="1" color="666666" bold="0" />
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