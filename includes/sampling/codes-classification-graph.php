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

	$sData   = array( );
	$sLabels = array( );

	$sSQL = "SELECT sdc.`code`, sdc.defect, COALESCE(SUM(srd.defects), 0) AS _Defects
			 FROM tbl_comment_sheets cs, tbl_merchandisings m, tbl_sampling_report_defects srd, tbl_sampling_defect_codes sdc
			 WHERE cs.merchandising_id=m.id AND cs.merchandising_id=srd.merchandising_id AND srd.code_id=sdc.id AND sdc.type_id='$DefectType'";

	if ($FromDate != "" && $ToDate != "")
		$sSQL .= " AND (DATE_FORMAT(cs.created, '%Y-%m-%d') BETWEEN '$FromDate' AND '$ToDate') ";

	if ($Type > 0)
		$sSQL .= " AND m.sample_type_id='$Type' ";

	if ($Brand > 0)
		$sSQL .= " AND m.style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand' AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')) ";

	else
		$sSQL .= " AND m.style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id IN ({$_SESSION['Brands']}) AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')) ";

	$sSQL .= " GROUP BY sdc.code
			   ORDER BY _Defects";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sData[]   = $objDb->getField($i, 2);
		$sLabels[] = ($objDb->getField($i, 1)." (".$objDb->getField($i, 0).")");
	}


	$sSQL = "SELECT `type` FROM tbl_sampling_defect_types WHERE id='$DefectType'";
	$objDb->query($sSQL);

	$sDefectType = $objDb->getField(0, 0);


	$objChart = new XYChart(920, 600);
	$objChart->setPlotArea(280, 70, 600, 480);

	$objChart->addTitle("Sampling Defects Classification ({$sDefectType})", "verdana.ttf", 20);

	$objBarLayer = $objChart->addBarLayer3($sData);
	$objBarLayer->setBarShape(CircleShape);
	$objBarLayer->setAggregateLabelStyle( );
	$objBarLayer->setAggregateLabelFormat("{value}");

	$objChart->xAxis->setLabels($sLabels);
	$objChart->yAxis->setLabelFormat("{value}");

	$objChart->swapXY(true);

	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$sChart = $objChart->makeSession("CodeClass");
?>

			      <br />
			      <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" border="0" />
