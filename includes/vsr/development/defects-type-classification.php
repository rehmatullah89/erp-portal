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
	$iDefectType = array( );

	$sSQL = "SELECT merchandising_id FROM tbl_comment_sheets $sConditions";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	$sMerchandisingIds = "";

	for ($i = 0; $i < $iCount; $i ++)
		$sMerchandisingIds .= (",".$objDb->getField($i, 0));

	if ($sMerchandisingIds != "")
		$sMerchandisingIds = substr($sMerchandisingIds, 1);


	$sSQL = "SELECT sdt.type, COALESCE(SUM(srd.defects), 0), sdt.id
			 FROM tbl_comment_sheets cs, tbl_sampling_report_defects srd, tbl_sampling_defect_codes sdc, tbl_sampling_defect_types sdt
			 WHERE cs.merchandising_id=srd.merchandising_id AND srd.code_id=sdc.id AND sdc.type_id=sdt.id AND cs.merchandising_id IN ($sMerchandisingIds)
			 GROUP BY sdc.type_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sData[]       = $objDb->getField($i, 1);
		$sLabels[]     = $objDb->getField($i, 0);
		$iDefectType[] = $objDb->getField($i, 2);
	}


	$objChart = new PieChart(296, 201);
	$objChart->setDonutSize(148, 92, 110, 0);
	$objChart->set3D(15);
	$objChart->setData($sData, $sLabels);
	$objChart->setLabelStyle("", 8, Transparent);

	$sChart = $objChart->makeSession("DefectClass");

	$objChart->addExtraField($iDefectType);

	$sImageMap = $objChart->getHTMLImageMap($_SERVER['PHP_SELF'], "Tab=Development&Vendor={$Vendor}&Brand={$Brand}&FromDate={$FromDate}&ToDate={$ToDate}\" onclick=\"return false;", "title='{label} = {value} Defects ({percent}%)'");
?>
			              <div class="vsrChart">
			                <div class="chart"><img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" border="0" usemap="#DefectClassMap" /></div>
			                <div class="title"><b>Defects Classification</b></div>

			                <div id="Handle1" class="handle" style="display:block;" onclick="showSummary(1);"></div>

			                <div id="Summary1" class="summary" style="display:none;">
			                  <div>
			                    <div class="text">
			                      Defects Classification
			                    </div>

			                    <div class="handle" onclick="hideSummary(1);"></div>
			                  </div>
			                </div>

						    <map name="DefectClassMap">
							  <?= $sImageMap ?>
						    </map>
			              </div>
