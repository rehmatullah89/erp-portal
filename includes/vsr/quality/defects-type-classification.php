<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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

	$sData       = array( );
	$sLabels     = array( );
	$sDefects    = array( );
	$iDefectType = array( );

	$sSQL = "SELECT dt.type, COALESCE(SUM(qad.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id $sConditions
			 GROUP BY dc.type_id
			 ORDER BY _Defects DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$sDefects[$objDb->getField($i, 0)] = $objDb->getField($i, 1);


	$sSQL = "SELECT dt.type, COALESCE(SUM(gfd.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_gf_report_defects gfd, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=gfd.audit_id AND gfd.code_id=dc.id AND dc.type_id=dt.id $sConditions
			 GROUP BY dc.type_id
			 ORDER BY _Defects DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$sDefects[$objDb->getField($i, 0)] += $objDb->getField($i, 1);


	@arsort($sDefects);


	foreach ($sDefects AS $sDefect => $iDefects)
	{
		$sData[]   = $iDefects;
		$sLabels[] = $sDefect;


		$sSQL = "SELECT id FROM tbl_defect_types WHERE type='$sDefect'";
		$objDb->query($sSQL);

		$iDefectType[] = $objDb->getField(0, 0);
	}


	$objChart = new PieChart(296, 201);
	$objChart->setDonutSize(148, 92, 110, 0);
	$objChart->set3D(15);
	$objChart->setData($sData, $sLabels);
	$objChart->setLabelStyle("", 8, Transparent);

	$sChart = $objChart->makeSession("DefectClass");

	$objChart->addExtraField($iDefectType);

	$sImageMap = $objChart->getHTMLImageMap($_SERVER['PHP_SELF'], "Tab=Quality&Vendor={$Vendor}&Brand={$Brand}&FromDate={$FromDate}&ToDate={$ToDate}\" onclick=\"return false;", "title='{label} = {value} Defects ({percent}%)'");
?>
			              <div class="vsrChart">
			                <div class="chart"><img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" border="0" usemap="#DefectClassMap" /></div>
			                <div class="title"><b>Defects Classification</b></div>

			                <div id="Handle1" class="handle" style="display:block;" onclick="showSummary(1);"></div>

			                <div id="Summary1" class="summary" style="display:none;">
			                  <div>
			                    <div class="text">Defects Classification</div>
			                    <div class="handle" onclick="hideSummary(1);"></div>
			                  </div>
			                </div>

						    <map name="DefectClassMap">
							  <?= $sImageMap ?>
						    </map>
			              </div>
