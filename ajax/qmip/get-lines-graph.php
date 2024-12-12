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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	$Vendor     = IO::intValue("Vendor");
	$Unit       = IO::intValue("Unit");
	$Brand      = IO::intValue("Brand");
	$FromDate   = IO::strValue("FromDate");
	$ToDate     = IO::strValue("ToDate");
	$Report     = IO::intValue("Report");
	$AuditStage = IO::strValue("AuditStage");
//	$Vendor     = 13;


	if ($FromDate == "" || $ToDate == "")
	{
		$FromDate = date("Y-m-d", mktime(0, 0, 0, (date("m") - 28), date("d"), date("Y")));
		$ToDate   = date("Y-m-d");
	}

	if (@strpos($_SESSION["Brands"], ",") === FALSE)
		$Brand = $_SESSION["Brands"];


	if ($Vendor > 0)
		$sVendorBrands = getDbValue("GROUP_CONCAT(DISTINCT(brand_id) SEPARATOR ',')", "tbl_po", "vendor_id='$Vendor'");
	
	else
		$sVendorBrands = getDbValue("GROUP_CONCAT(DISTINCT(brand_id) SEPARATOR ',')", "tbl_po", "FIND_IN_SET(vendor_id, '$sQmipVendors')");

	$sReportTypes     = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStages     = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStagesList = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')");


	$sLines            = array( );

	$fLineDhuType1     = array( );
	$iLineGmtsType1    = array( );
	$iLineDefectsType1 = array( );

	$fLineDhuType2     = array( );
	$iLineGmtsType2    = array( );
	$iLineDefectsType2 = array( );

	$fLineDhuType3     = array( );
	$iLineGmtsType3    = array( );
	$iLineDefectsType3 = array( );



	$sConditions  = " AND qa.audit_type='B' ";
	$sConditions .= " AND (qa.style_id='0' OR qa.style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '$sVendorBrands') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";
	
	if ($Vendor > 0)
		$sConditions .= " AND qa.vendor_id='$Vendor' ";
	
	else
		$sConditions .= " AND FIND_IN_SET(qa.vendor_id, '$sQmipVendors') ";

	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";

	if ($AuditStage != "")
		$sConditions .= " AND qa.audit_stage='$AuditStage' ";

	else
		$sConditions .= " AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";

	if ($Brand > 0)
		$sConditions .= " AND qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id='$Brand' AND vendor_id='$Vendor') ";

	if ($Report > 0)
		$sConditions .= " AND qa.report_id='$Report' ";

	else
		$sConditions .= " AND FIND_IN_SET(qa.report_id, '$sReportTypes') ";

	if ($Unit > 0)
		$sConditions .= " AND qa.unit_id='$Unit' ";



	$sSQL = "SELECT qa.line_id,
	                l.line AS _Line
			 FROM tbl_qa_reports qa, tbl_lines l
			 WHERE qa.line_id=l.id AND qa.unit_id=l.unit_id $sConditions
			 GROUP BY qa.line_id
	         ORDER BY _Line";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iLine = $objDb->getField($i, "line_id");
		$sLine = $objDb->getField($i, "_Line");

		$sLines[$iLine] = $sLine;
		$sLineIds      .= ",{$iLine}";
	}



	$sSQL = "SELECT qa.line_id,
					COALESCE(SUM(qa.total_gmts), 0) AS _Gmts,
					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id)) AS _Defects
			 FROM tbl_qa_reports qa, tbl_users u
			 WHERE qa.user_id=u.id AND u.auditor_type='1' $sConditions
			       AND FIND_IN_SET(qa.line_id, '$sLineIds')
			 GROUP BY qa.line_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iLine    = $objDb->getField($i, "line_id");
		$iGmts    = $objDb->getField($i, "_Gmts");
		$iDefects = $objDb->getField($i, "_Defects");


		$iLineGmtsType1[$iLine]    = $iGmts;
		$iLineDefectsType1[$iLine] = $iDefects;
		$fLineDhuType1[$iLine]     = @round((($iDefects / $iGmts) * 100), 2);
	}



	$sSQL = "SELECT qa.line_id,
					COALESCE(SUM(qa.total_gmts), 0) AS _Gmts,
					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id)) AS _Defects
			 FROM tbl_qa_reports qa, tbl_users u
			 WHERE qa.user_id=u.id AND u.auditor_type='2' $sConditions
			       AND FIND_IN_SET(qa.line_id, '$sLineIds')
			 GROUP BY qa.line_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iLine    = $objDb->getField($i, "line_id");
		$iGmts    = $objDb->getField($i, "_Gmts");
		$iDefects = $objDb->getField($i, "_Defects");


		$iLineGmtsType2[$iLine]    = $iGmts;
		$iLineDefectsType2[$iLine] = $iDefects;
		$fLineDhuType2[$iLine]     = @round((($iDefects / $iGmts) * 100), 2);
	}



	$sSQL = "SELECT qa.line_id,
					COALESCE(SUM(qa.total_gmts), 0) AS _Gmts,
					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id)) AS _Defects
			 FROM tbl_qa_reports qa, tbl_users u
			 WHERE qa.user_id=u.id AND u.auditor_type='3' $sConditions
			       AND FIND_IN_SET(qa.line_id, '$sLineIds')
			 GROUP BY qa.line_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iLine    = $objDb->getField($i, "line_id");
		$iGmts    = $objDb->getField($i, "_Gmts");
		$iDefects = $objDb->getField($i, "_Defects");


		$iLineGmtsType3[$iLine]    = $iGmts;
		$iLineDefectsType3[$iLine] = $iDefects;
		$fLineDhuType3[$iLine]     = @round((($iDefects / $iGmts) * 100), 2);
	}
?>
						                     <chart caption='Line wise Unit Health' subCaption='' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='<?= ((count($fLineDhuType2) <= 10) ? 'AUTO' : 'Stagger') ?>' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='line-wise-progress'>

											 <categories>
<?
	foreach ($sLines as $iLine => $sLine)
	{
?>
											<category label='<?= htmlentities($sLine, ENT_QUOTES) ?>' />
<?
	}
?>
											</categories>


											<dataset seriesName='QMIP Auditors' color=''>
<?
	foreach ($sLines as $iLine => $sLine)
	{
?>
											<set value='<?= $fLineDhuType2[$iLine] ?>' tooltext='Line: <?= $sLine ?>{br}DR: <?= formatNumber($fLineDhuType2[$iLine]) ?>%{br}TGI: <?= formatNumber($iLineGmtsType2[$iLine], false) ?>{br}TGR: <?= formatNumber($iLineDefectsType2[$iLine], false) ?>' link='' />
<?
	}
?>
											</dataset>


											<dataset seriesName='3rd Party Auditors'>
<?
	foreach ($sLines as $iLine => $sLine)
	{
?>
											  <set value='<?= $fLineDhuType1[$iLine] ?>'  tooltext='Line: <?= $sLine ?>{br}DR: <?= formatNumber($fLineDhuType1[$iLine]) ?>%{br}TGI: <?= formatNumber($iLineGmtsType1[$iLine], false) ?>{br}TGR: <?= formatNumber($iLineDefectsType1[$iLine], false) ?>' />
<?
	}
?>
											</dataset>

											<dataset seriesName='QMIP Corelation Auditors' renderAs='Line' color='00ff00'>
<?
	foreach ($sLines as $iLine => $sLine)
	{
?>
											  <set value='<?= $fLineDhuType3[$iLine] ?>'  tooltext='Line: <?= $sLine ?>{br}DR: <?= formatNumber($fLineDhuType3[$iLine]) ?>%{br}TGI: <?= formatNumber($iLineGmtsType3[$iLine], false) ?>{br}TGR: <?= formatNumber($iLineDefectsType3[$iLine], false) ?>' />
<?
	}
?>
											</dataset>
										</chart>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>