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
	$sLabels = array("1%", "2%", "3%", "4%", "5%", "10%", "15%", "25%", "50%", "+%");
	$sColors = array(0x9999ff, 0x9999ff, 0x9999ff, 0x9999ff, 0x00ffff, 0x00ffff, 0xff0000, 0xff0000, 0xff0000, 0xff0000);


	$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports qa WHERE qa.audit_stage='F' AND (qa.dhu >= 0 AND qa.dhu < 1) $sConditions";
	$objDb->query($sSQL);

	$sData[0] = $objDb->getField(0, 0);


	$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports qa WHERE qa.audit_stage='F' AND (qa.dhu >= 1 AND qa.dhu < 2) $sConditions";
	$objDb->query($sSQL);

	$sData[1] = $objDb->getField(0, 0);


	$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports qa WHERE qa.audit_stage='F' AND (qa.dhu >= 3 AND qa.dhu < 4) $sConditions";
	$objDb->query($sSQL);

	$sData[2] = $objDb->getField(0, 0);


	$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports qa WHERE qa.audit_stage='F' AND (qa.dhu >= 4 AND qa.dhu < 5) $sConditions";
	$objDb->query($sSQL);

	$sData[3] = $objDb->getField(0, 0);


	$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports qa WHERE qa.audit_stage='F' AND (qa.dhu >= 5 AND qa.dhu < 10) $sConditions";
	$objDb->query($sSQL);

	$sData[4] = $objDb->getField(0, 0);


	$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports qa WHERE qa.audit_stage='F' AND (qa.dhu >= 10 AND qa.dhu < 15) $sConditions";
	$objDb->query($sSQL);

	$sData[5] = $objDb->getField(0, 0);


	$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports qa WHERE qa.audit_stage='F' AND (qa.dhu >= 15 AND qa.dhu < 20) $sConditions";
	$objDb->query($sSQL);

	$sData[6] = $objDb->getField(0, 0);


	$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports qa WHERE qa.audit_stage='F' AND (qa.dhu >= 20 AND qa.dhu < 25) $sConditions";
	$objDb->query($sSQL);

	$sData[7] = $objDb->getField(0, 0);


	$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports qa WHERE qa.audit_stage='F' AND (qa.dhu >= 25 AND qa.dhu < 50) $sConditions";
	$objDb->query($sSQL);

	$sData[8] = $objDb->getField(0, 0);


	$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports qa WHERE qa.audit_stage='F' AND qa.dhu >= 50 $sConditions";
	$objDb->query($sSQL);

	$sData[9] = $objDb->getField(0, 0);


	$objChart = new XYChart(296, 201);
	$objChart->setPlotArea(30, 20, 250, 150);

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
			              <div class="vsrChart">
			                <div class="chart"><img src="requires/get-chart.php?<?= $sChart ?>" border="0" alt="" title="" /></div>
			                <div class="title"><b>Final Audits Defects Rate</b></div>

			                <div id="Handle2" class="handle" style="display:block;" onclick="showSummary(2);"></div>

			                <div id="Summary2" class="summary" style="display:none;">
			                  <div>
			                    <div class="text">Final Audits Defects Rate Histogram</div>
			                    <div class="handle" onclick="hideSummary(2);"></div>
			                  </div>
			                </div>
			              </div>
