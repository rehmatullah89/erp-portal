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

	$sSQL = "SELECT sdt.type, COALESCE(SUM(srd.defects), 0) AS _Defects, sdt.id
			 FROM tbl_comment_sheets cs, tbl_merchandisings m, tbl_sampling_report_defects srd, tbl_sampling_defect_codes sdc, tbl_sampling_defect_types sdt
			 WHERE cs.merchandising_id=m.id AND cs.merchandising_id=srd.merchandising_id AND srd.code_id=sdc.id AND sdc.type_id=sdt.id";

	if ($FromDate != "" && $ToDate != "")
		$sSQL .= " AND (DATE_FORMAT(cs.created, '%Y-%m-%d') BETWEEN '$FromDate' AND '$ToDate') ";

	if ($Type > 0)
		$sSQL .= " AND m.sample_type_id='$Type' ";

	if ($Brand > 0)
		$sSQL .= " AND m.style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand' AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')) ";

	else
		$sSQL .= " AND m.style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id IN ({$_SESSION['Brands']}) AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')) ";

	$sSQL .= "GROUP BY sdc.type_id
			  ORDER BY _Defects DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sLabels[]     = $objDb->getField($i, 0);
		$sData[]       = $objDb->getField($i, 1);
		$iDefectType[] = $objDb->getField($i, 2);
	}


	$objChart = new PieChart(920, 420);
	$objChart->setDonutSize(460, 230, 180, 0);

	if ($Brand > 0)
		$objChart->addTitle("Sampling Defects Classification ({$sBrandsList[$Brand]})", "verdana.ttf", 20);

	else
		$objChart->addTitle("Sampling Defects Classification", "verdana.ttf", 20);

	if ($sSubTitle != "")
		$objChart->addText(460, 35, $sSubTitle, "verdanab.ttf", 10, 0x555555, 8);

	$objChart->set3D(25);
	$objChart->setData($sData, $sLabels);
	$objChart->setLabelLayout(SideLayout, 60);
	$objChart->setLabelFormat("{label}\n{value} Defects ({percent}%)");
	$objChart->setLabelStyle("verdana.ttf", 10, 0x000000);

	$sChart = $objChart->makeSession("DefectClass");

	$objChart->addExtraField($iDefectType);

	$sImageMap = $objChart->getHTMLImageMap($_SERVER['PHP_SELF'], "Brand={$Brand}&Type={$Type}&FromDate={$FromDate}&ToDate={$ToDate}&DefectType={field0}", "title='{label} = {value} Defects ({percent}%)'");
?>

			      <br />
			      <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" border="0" usemap="#DefectClassMap" />

				  <map name="DefectClassMap">
				    <?= $sImageMap ?>
				  </map>
