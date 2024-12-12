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

	$sDefectTypes  = array( );
	$iDefectTypes  = array( );
	$iTotalDefects = array( );

	$sSQL = "SELECT dt.id, dt.type AS _DefectType, COALESCE(SUM(qad.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id $sConditions $sDefectsSql
			 GROUP BY dc.type_id
			 ORDER BY _Defects DESC, _DefectType ASC";
        
/*


			 UNION

			 SELECT dt.id, dt.type AS _DefectType, COALESCE(SUM(gfd.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_gf_report_defects gfd, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=gfd.audit_id AND gfd.code_id=dc.id AND dc.type_id=dt.id $sConditions
			 GROUP BY dc.type_id
   */
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


	$sColors = array( );

	for ($i = 0; $i < count($iDefectTypes); $i ++)
		$sColors[] = hexdec(substr($sDefectColors[$iDefectTypes[$i]], 1));


	$objChart = new PieChart(920, 600);

	$objChart->setDonutSize(460, 300, 220, 0);
	$objChart->addTitle("\nDefect Classification", "verdana.ttf", 20, 0x000000);

	$objChart->setColors2(8, $sColors);
	$objChart->setBackground(0xffffff);
	$objChart->setJoinLine(0x000000);

	$objChart->set3D(25);

	$objChart->setData($iTotalDefects, $sDefectTypes);

	$objChart->setLabelLayout(SideLayout, 60);
	$objChart->setLabelFormat("{label}\n{value} Defects ({percent}%)");
	$objChart->setLabelStyle("verdana.ttf", 8, 0x000000);

	$sChart = $objChart->makeSession("DefectClass");

	$objChart->addExtraField($iDefectTypes);

	$sImageMap = $objChart->getHTMLImageMap($_SERVER['PHP_SELF'], ("Region={$Region}&AuditStage={$AuditStage}&Defect[]=".@implode("&Defect[]=", $Defect)."&Brand[]=".@implode("&Brand[]=", $Brand)."&Vendor={$Vendor}&Report={$Report}&Line[]=".@implode("&Line[]=", $Line)."&Color={$Color}&FromDate={$FromDate}&ToDate={$ToDate}&DefectType={field0}&Po={$Po}"), "title='{label} = {value} Defects ({percent}%)'");
?>
			      <br />
			      <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" border="0" usemap="#DefectClassMap" />

				  <map name="DefectClassMap">
				    <?= $sImageMap ?>
				  </map>
