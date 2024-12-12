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


	$Vendor     = IO::strValue("Vendor");
	$AuditStage = IO::strValue("AuditStage");
	$FromDate   = IO::strValue("FromDate");
	$ToDate     = IO::strValue("ToDate");
	$Brand      = IO::getArray("Brand");
	$Type       = IO::intValue("Type");


	$sReportTypes = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sConditions  = " AND qa.audit_type='B' AND qa.audit_result!='' AND qa.audit_stage='$AuditStage' AND qa.vendor_id='$Vendor' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') AND FIND_IN_SET(qa.report_id, '$sReportTypes') ";

	if (count($Brand) > 0 && $Brand[0] != "")
		$sConditions .= (" AND qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id IN (".@implode(",", $Brand).") AND vendor_id='$Vendor') ");



	$sDefectType   = getDbValue("type", "tbl_defect_types", "id='$Type'");
	$sDefectColors = getList("tbl_defect_types", "id", "color");

	$sDefectCodes  = array( );
	$iDefectCodes  = array( );
	$iTotalDefects = array( );


/*	$sSQL = "SELECT dc.id, CONCAT(dc.defect, '   (', dc.`code`, ')') AS _DefectCode, COALESCE(SUM(qad.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id AND dt.id='$Type' AND qa.report_id!='6' AND qad.nature>'0' $sConditions
			 GROUP BY dc.id

	         UNION

	         SELECT dc.id, CONCAT(dc.defect, '   (', dc.`code`, ')') AS _DefectCode, COALESCE(SUM(gfd.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_gf_report_defects gfd, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=gfd.audit_id AND gfd.code_id=dc.id AND dc.type_id=dt.id AND dt.id='$Type' AND qa.report_id='6' $sConditions
			 GROUP BY dc.id

			 ORDER BY _Defects ASC, _DefectCode ASC";*/
        
        $sSQL = "SELECT dc.id, CONCAT(dc.defect, '   (', dc.`code`, ')') AS _DefectCode, COALESCE(SUM(qad.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id AND dt.id='$Type' AND qa.report_id!='6' AND qad.nature>'0' $sConditions
			 GROUP BY dc.id
			 ORDER BY _Defects ASC, _DefectCode ASC";
        
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iDefectCode = $objDb->getField($i, "id");
		$sDefectCode = $objDb->getField($i, "_DefectCode");
		$iDefects    = $objDb->getField($i, "_Defects");

		if (@in_array($iDefectCode, $iDefectCodes))
		{
			$iIndex = @array_search($iDefectCode, $iDefectCodes);

			$iTotalDefects[$iIndex] += $iDefects;
		}

		else
		{
			$iDefectCodes[]  = $iDefectCode;
			$sDefectCodes[]  = $sDefectCode;
			$iTotalDefects[] = $iDefects;
		}
	}
?>
	<chart caption='<?= $sDefectType ?>' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='' chartBottomMargin='5' plotFillAlpha='95' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='<?= strtolower(str_replace(" ", "-", $sDefectType)) ?>'>
<?
	for ($i = 0; $i < count($iDefectCodes); $i ++)
	{
?>
		<set color='<?= $sDefectColors[$Type] ?>' tooltext='<?= htmlentities($iTotalDefects[$i], ENT_QUOTES) ?>{br}Code: <?= $sDefectCodes[$i] ?>{br}Defects: <?= $iTotalDefects[$i] ?>' label='<?= $sDefectCodes[$i] ?>' value='<?= $iTotalDefects[$i] ?>' link='javascript:showCodeGraph(<?= $Type ?>, <?= $iDefectCodes[$i] ?>)' />
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