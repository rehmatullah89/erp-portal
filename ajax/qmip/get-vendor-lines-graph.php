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
	$Brand      = IO::intValue("Brand");
	$Date       = IO::strValue("Date");
	$Report     = IO::intValue("Report");
	$AuditStage = IO::strValue("AuditStage");
	$Lines      = IO::strValue("Line");



	if ($Vendor > 0)
		$sVendorBrands = getDbValue("GROUP_CONCAT(DISTINCT(brand_id) SEPARATOR ',')", "tbl_po", "vendor_id='$Vendor'");
	
	else
		$sVendorBrands = getDbValue("GROUP_CONCAT(DISTINCT(brand_id) SEPARATOR ',')", "tbl_po", "FIND_IN_SET(vendor_id, '$sQmipVendors')");
	
	$sReportTypes  = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStages  = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");


	$sConditions  = " AND qa.audit_type='B' AND FIND_IN_SET(qa.report_id, '$sQmipReports') ";
	$sConditions .= " AND (qa.style_id='0' OR qa.style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '$sVendorBrands') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

	if ($Vendor > 0)
		$sConditions .= " AND qa.vendor_id='$Vendor' ";
	
	else
		$sConditions .= " AND FIND_IN_SET(qa.vendor_id, '$sQmipVendors') AND FIND_IN_SET(qa.vendor_id, '{$_SESSION['Vendors']}') ";
	
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

	if ($Lines != "")
		$sConditions .= " AND FIND_IN_SET(qa.line_id, '$Lines') ";

//	if ($Vendor == 13)
//		$sConditions .= " AND qa.unit_id='259' ";



	$sFromDate = date("Y-m-d", (strtotime($Date) - (16 * 86400)));
	$sToDate   = date("Y-m-d", (strtotime($Date) - 86400));


	$sSQL = "SELECT COALESCE(SUM(qa.total_gmts), 0) AS _Gmts,
					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id)) AS _Defects
			 FROM tbl_qa_reports qa
			 WHERE (qa.audit_date BETWEEN '$sFromDate' AND '$sToDate') $sConditions
			 GROUP BY qa.audit_date";
	$objDb->query($sSQL);

	$iCount        = $objDb->getCount( );
	$fMinDr        = 0;
	$fMaxDr        = 0;
	$iTotalGmts    = 0;
	$iTotalDefects = 0;

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iGmts    = $objDb->getField($i, "_Gmts");
		$iDefects = $objDb->getField($i, "_Defects");

		$iTotalGmts    += $iGmts;
		$iTotalDefects += $iDefects;		
		$fDr            = @round((($iDefects / $iGmts) * 100), 2);

		if ($i == 0)
		{
			$fMinDr = $fDr;
			$fMaxDr = $fDr;
		}

		else
		{
			if (($fDr < $fMinDr && $fMinDr > 0) || ($fMinDr == 0 && $fDr > 0))
				$fMinDr = $fDr;

			if ($fDr > $fMaxDr)
				$fMaxDr = $fDr;
		}
	}


	$fAvgDr        = @round((($iTotalDefects / $iTotalGmts) * 100), 2);

	$FromDate      = $ToDate;
	$sLines        = array( );
	$sStats        = array( );
	$iTotalGmts    = 0;
	$iTotalDefects = 0;


	$sSQL = "SELECT qa.line_id,
	                l.line AS _Line
			 FROM tbl_qa_reports qa, tbl_lines l
			 WHERE qa.line_id=l.id AND qa.audit_date='$Date' $sConditions
			 GROUP BY qa.line_id
	         ORDER BY _Line";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iLine = $objDb->getField($i, "line_id");
		$sLine = $objDb->getField($i, "_Line");

		$sLines[$iLine] = $sLine;


		for ($j = 0; $j < 24; $j ++)
		{
			$sStats[$iLine][$j]['Samples'] = 0;
			$sStats[$iLine][$j]['Defects'] = 0;
			$sStats[$iLine][$j]['Dr']      = 0;
		}



		$sSQL = "SELECT HOUR(TIME(qap.date_time)) AS _Hour,
						COUNT(1) AS _Samples
				 FROM tbl_qa_reports qa, tbl_qa_report_progress qap
				 WHERE qa.id=qap.audit_id AND qa.line_id='$iLine' AND qa.audit_date='$Date' $sConditions
				 GROUP BY _Hour";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iHour    = $objDb2->getField($j, "_Hour");
			$iSamples = $objDb2->getField($j, "_Samples");

			$sStats[$iLine][$iHour]['Samples'] = $iSamples;
			
			$iTotalGmts += $iSamples;
		}



		$sSQL = "SELECT HOUR(TIME(qad.date_time)) AS _Hour,
						COUNT(1) AS _Defects
				 FROM tbl_qa_reports qa, tbl_qa_report_defects qad
				 WHERE qa.id=qad.audit_id AND qa.line_id='$iLine' AND qa.audit_date='$Date' $sConditions
				 GROUP BY _Hour";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iHour    = $objDb2->getField($j, "_Hour");
			$iDefects = $objDb2->getField($j, "_Defects");


			$sStats[$iLine][$iHour]['Defects'] = $iDefects;
			
			$iTotalDefects += $iDefects;
		}
	}
	
	
	$fDr = @round((($iTotalDefects / $iTotalGmts) * 100), 2);
?>
		                     <chart caption='Daily Activity Chart (<?= formatDate($Date) ?>)' subCaption='Total Garments Inspected: <?= formatNumber($iTotalGmts, false) ?>   -   Total Garments Rejected: <?= formatNumber($iTotalDefects, false) ?>   -   DR: <?= formatNumber($fDr) ?>%' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='<?= ((count($fUnitDr) <= 10) ? 'AUTO' : 'Stagger') ?>' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='line-wise-health'>
								<categories>
<?
	for ($i = 8; $i < 24; $i ++)
	{
?>
								<category label='<?= $i ?>-<?= (($i == 23) ? '0' : ($i + 1)) ?>' />
<?
	}
?>
								</categories>

								<trendlines>
								<line startvalue='<?= $fMinDr ?>' endValue='<?= $fMaxDr ?>' displayValue=' ' color='BC9F3F' isTrendZone='1' showOnTop='0' alpha='25' valueOnRight='1' />
								<line startvalue='<?= $fMinDr ?>' endValue='<?= $fMinDr ?>' displayValue='Min DR' color='894D1B' isTrendZone='0' showOnTop='1' alpha='50' valueOnRight='1' />
								<line startvalue='<?= $fMaxDr ?>' endValue='<?= $fMaxDr ?>' displayValue='Max DR' color='894D1B' isTrendZone='0' showOnTop='1' alpha='50' valueOnRight='1' />
								<line startvalue='<?= $fAvgDr ?>' endValue='<?= $fAvgDr ?>' displayValue='Avg DR' color='0000FF' isTrendZone='0' showOnTop='1' alpha='50' valueOnRight='1' />
								</trendlines>

<?
	$sQueryString = "Date={$Date}&Vendor={$Vendor}";

	if ($AuditStage != "")
		$sQueryString .= "&AuditStage={$AuditStage}";

	if ($Brand > 0)
		$sQueryString .= "&Brand={$Brand}";

	if ($Report > 0)
		$sQueryString .= "&Report={$Report}";



	foreach ($sLines as $iLine => $sLine)
	{
		$sParams  = $sQueryString;
		$sParams .= "&Line={$iLine}";
?>
								<dataset seriesName='<?= $sLine ?>'>
<?
		for ($i = 8; $i < 24; $i ++)
		{
			$fDr = @round((($sStats[$iLine][$i]['Defects'] / $sStats[$iLine][$i]['Samples']) * 100), 2);
?>
								<set value='<?= (($fDr > 0 && $sStats[$iLine][$i]['Samples'] >= 20) ? formatNumber($fDr) : '') ?>' tooltext='Line: <?= $sLine ?>{br}Samples: <?= $sStats[$iLine][$i]['Samples'] ?>{br}Defects: <?= $sStats[$iLine][$i]['Defects'] ?>{br}DR: <?= formatNumber($fDr) ?>%' link='javascript:showDefectsGraph("<?= "{$sParams}&Hour={$i}" ?>")' />
<?
		}
?>
								</dataset>
<?
	}
?>
							</chart>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>