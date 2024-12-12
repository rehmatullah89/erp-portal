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
	$sColors = array( );

	$sSQL = "SELECT ROUND(AVG(qa.dhu), 2) AS _Dhu, qa.user_id FROM tbl_qa_reports qa $sConditions GROUP BY qa.user_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sData[]     = $objDb->getField($i, "_Dhu");
		$sLabels[]   = $sAuditorsList[$objDb->getField($i, "user_id")];
		$sColors[]   = 0x999999;
	}

/*
	$sSQL = "SELECT DISTINCT(qa.user_id) FROM tbl_qa_reports qa $sConditions";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iAuditorId = $objDb->getField($i, "user_id");


		$sSQL = "SELECT
				   ROUND(
					 COALESCE(
					   (
						 (
						   SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id GROUP BY audit_id))
						   /
						   COALESCE(SUM(qa.total_gmts), 0)
						 )
						 *
						 100
					   ),
					 0),
				   2) AS _Dhu
			   FROM tbl_qa_reports qa $sConditions AND qa.user_id='$iAuditorId'";
		$objDb->query($sSQL);

		$fDhu = $objDb->getField(0, 0);


		if ($fDhu == 0)
		{
			$sSQL = "SELECT SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id GROUP BY audit_id)), FROM tbl_qa_reports qa $sConditions";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );
		}


		$sData[]   = $fDhu;
		$sLabels[] = $sAuditorsList[$iAuditorId];
		$sColors[] = 0x999999;
	}
*/

	$objChart = new XYChart(920, 580);
	$objChart->setPlotArea(70, 80, 820, 360);

	$objTitle = $objChart->addTitle(("\nAuditors Correlation (".formatDate($FromDate)." to ".formatDate($ToDate).")"), "verdana.ttf", 17);
	$objTitle->setPos(0,0);

	$objBarLayer = $objChart->addBarLayer3($sData, $sColors);
	$objBarLayer->setBarShape(CircleShape);
	$objBarLayer->setAggregateLabelStyle( );
	$objBarLayer->setAggregateLabelFormat("{value}%");

	$objLabels = $objChart->xAxis->setLabels($sLabels);
	$objLabels->setFontAngle(90);

	$objChart->yAxis->setLabelFormat("{value}%");

	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$sChart = $objChart->makeSession("CorrelationSummary");
?>

	  			  <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" border="0" />
