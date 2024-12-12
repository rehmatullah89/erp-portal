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
	$sLabels = array("0 to <1%", "1 to <2%", "2 to <3%", "3 to <4%", "4 to <5%", "5 to <10%", "10 to <15%", "15 to <25%", "25 to <50%", "50+ %");
	$sColors = array(0x9999ff, 0x9999ff, 0x9999ff, 0x9999ff, 0x00ffff, 0x00ffff, 0xff0000, 0xff0000, 0xff0000, 0xff0000);


	$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports qa WHERE (qa.audit_stage='F' OR qa.audit_stage='FR') AND (qa.dhu >= 0 AND qa.dhu < 1) $sConditions";
	$objDb->query($sSQL);

	$sData[0] = $objDb->getField(0, 0);


	$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports qa WHERE (qa.audit_stage='F' OR qa.audit_stage='FR') AND (qa.dhu >= 1 AND qa.dhu < 2) $sConditions";
	$objDb->query($sSQL);

	$sData[1] = $objDb->getField(0, 0);


	$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports qa WHERE (qa.audit_stage='F' OR qa.audit_stage='FR') AND (qa.dhu >= 2 AND qa.dhu < 3) $sConditions";
	$objDb->query($sSQL);

	$sData[2] = $objDb->getField(0, 0);


	$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports qa WHERE (qa.audit_stage='F' OR qa.audit_stage='FR') AND (qa.dhu >= 3 AND qa.dhu < 4) $sConditions";
	$objDb->query($sSQL);

	$sData[3] = $objDb->getField(0, 0);


	$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports qa WHERE (qa.audit_stage='F' OR qa.audit_stage='FR') AND (qa.dhu >= 4 AND qa.dhu < 5) $sConditions";
	$objDb->query($sSQL);

	$sData[4] = $objDb->getField(0, 0);


	$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports qa WHERE (qa.audit_stage='F' OR qa.audit_stage='FR') AND (qa.dhu >= 5 AND qa.dhu < 10) $sConditions";
	$objDb->query($sSQL);

	$sData[5] = $objDb->getField(0, 0);


	$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports qa WHERE (qa.audit_stage='F' OR qa.audit_stage='FR') AND (qa.dhu >= 10 AND qa.dhu < 15) $sConditions";
	$objDb->query($sSQL);

	$sData[6] = $objDb->getField(0, 0);


	$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports qa WHERE (qa.audit_stage='F' OR qa.audit_stage='FR') AND (qa.dhu >= 15 AND qa.dhu < 25) $sConditions";
	$objDb->query($sSQL);

	$sData[7] = $objDb->getField(0, 0);


	$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports qa WHERE (qa.audit_stage='F' OR qa.audit_stage='FR') AND (qa.dhu >= 25 AND qa.dhu < 50) $sConditions";
	$objDb->query($sSQL);

	$sData[8] = $objDb->getField(0, 0);


	$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports qa WHERE (qa.audit_stage='F' OR qa.audit_stage='FR') AND qa.dhu >= 50 $sConditions";
	$objDb->query($sSQL);

	$sData[9] = $objDb->getField(0, 0);


	$objChart = new XYChart(920, 500);
	$objChart->setPlotArea(70, 80, 820, 370);

	$objTitle = $objChart->addTitle("Defect Rate Histogram (Final Audits)", "verdana.ttf", 20);
	$objTitle->setPos(0,0);

	$objBarLayer = $objChart->addBarLayer3($sData, $sColors);
	$objBarLayer->setBarShape(CircleShape);
	$objBarLayer->setAggregateLabelStyle( );
	$objBarLayer->setAggregateLabelFormat("{value}");

	$objChart->xAxis->setLabels($sLabels);
	$objChart->yAxis->setLabelFormat("{value}");
	$objChart->xAxis->setWidth(2);
	$objChart->yAxis->setWidth(2);

	$sChart = $objChart->makeSession("FinalAudits");
?>
			      <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" border="0" />
