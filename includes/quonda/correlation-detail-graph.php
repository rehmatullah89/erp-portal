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

	$sData   = array( );
	$sLabels = array( );
	$iTypes  = array( );


	$sSQL = "SELECT dt.id, dt.type, COALESCE(SUM(qad.defects), 0)
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
			 $sConditions AND qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id AND qa.report_id!='6' AND qad.nature>'0'
			 GROUP BY dc.type_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iTypes[]  = $objDb->getField($i, 0);
		$sLabels[] = $objDb->getField($i, 1);
	}


	$sSQL = "SELECT DISTINCT(qa.user_id) FROM tbl_qa_reports qa $sConditions AND qa.report_id!='6' ORDER BY qa.user_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sData['Id'][$i]      = $objDb->getField($i, 0);
		$sData['Name'][$i]    = $sAuditorsList[$objDb->getField($i, 0)];
		$sData['Defects'][$i] = array( );
	}


	for ($i = 0; $i < count($sData['Id']); $i ++)
	{
		for ($j = 0; $j < count($iTypes); $j ++)
		{
			$sSQL = "SELECT COALESCE(SUM(qad.defects), 0)
					 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
					 $sConditions AND qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id AND qa.report_id!='6'
					              AND dc.type_id='{$iTypes[$j]}' AND qa.user_id='{$sData['Id'][$i]}' AND qad.nature>'0'
					 GROUP BY dc.type_id";
			$objDb->query($sSQL);

			$sData['Defects'][$i][$j] = $objDb->getField(0, 0);
		}
	}



	$objChart = new XYChart(920, 650);
	$objChart->setPlotArea(70, 80, 820, 400);

	$objTitle = $objChart->addTitle("Auditors Correlation (Defect Type wise)", "verdana.ttf", 17);
	$objTitle->setPos(0,0);

	$objChart->addLegend(70, 40, false);

	$objBarLayer = $objChart->addBarLayer2(Side);
	$objBarLayer->setBarShape(CircleShape);
	$objBarLayer->setBarGap(0.25, TouchBar);


	for ($i = 0; $i < count($sData['Id']); $i ++)
		$objBarLayer->addDataSet($sData['Defects'][$i], -1, $sData['Name'][$i]);


	$objBarLayer->setAggregateLabelStyle( );
	$objBarLayer->setAggregateLabelFormat("{value}");

	$objLabels = $objChart->xAxis->setLabels($sLabels);
	$objLabels->setFontAngle(90);

	$objChart->yAxis->setLabelFormat("{value}");

	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$sChart = $objChart->makeSession("CorrelationDetail");
?>

	  			  <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" border="0" />
