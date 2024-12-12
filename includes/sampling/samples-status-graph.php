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


	$sSQL = "SELECT m.status, COUNT(*)
	         FROM tbl_comment_sheets cs, tbl_merchandisings m
	         WHERE cs.merchandising_id=m.id";

	if ($FromDate != "" && $ToDate != "")
		$sSQL .= " AND (DATE_FORMAT(cs.created, '%Y-%m-%d') BETWEEN '$FromDate' AND '$ToDate') ";

	if ($Type > 0)
		$sSQL .= " AND m.sample_type_id='$Type' ";

	if ($Brand > 0)
		$sSQL .= " AND m.style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand' AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')) ";

	else
		$sSQL .= " AND m.style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id IN ({$_SESSION['Brands']}) AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')) ";

	$sSQL .= "GROUP BY m.status
	          ORDER BY m.status";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		switch ($objDb->getField($i, 0))
		{
			case "A" : $sStatus = "Approved"; $sColor=0x33ff33; break;
			case "R" : $sStatus = "Rejected"; $sColor=0xff3333; break;
			default  : $sStatus = "Waiting"; $sColor=0x6666ff; break;
		}


		$sColors[] = $sColor;
		$sLabels[] = $sStatus;
		$sData[]   = $objDb->getField($i, 1);
	}


	$objChart = new PieChart(450, 400);
	$objChart->setDonutSize(225, 190, 120, 0);

	if ($Brand > 0)
		$objChart->addTitle("MATRIX Statistics ({$sBrandsList[$Brand]})", "verdanab.ttf", 11);

	else
		$objChart->addTitle("MATRIX Statistics", "verdana.ttf", 20);

	if ($sSubTitle != "")
		$objChart->addText(225, 20, $sSubTitle, "verdanab.ttf", 10, 0x555555, 8);

	$objChart->set3D(20);
	$objChart->setData($sData, $sLabels);
	$objChart->setColors2(DataColor, $sColors);

	$objChart->addLegend(10, 355, 0);

	$objChart->setLabelLayout(SideLayout, 0);
	$objChart->setLabelFormat("{value} ({percent}%)");
	$objChart->setLabelStyle("verdana.ttf", 10, 0x000000);

	$sChart = $objChart->makeSession("MatrixSampling");
?>

			    <br />

			    <table border="0" cellpadding="0" cellspacing="0" width="100%">
			      <tr valign="top">
			        <td width="49%"><img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" /></td>
			        <td width="2%" bgcolor="#f9f9f9"></td>
<?
	$sData   = array( );
	$sLabels = array( );
	$sColors = array( );

	$sSQL = "SELECT IF(cs.status='', 'W', cs.status), COUNT(*)
	         FROM tbl_comment_sheets cs, tbl_merchandisings m
	         WHERE cs.merchandising_id=m.id";

	if ($FromDate != "" && $ToDate != "")
		$sSQL .= " AND (DATE_FORMAT(cs.created, '%Y-%m-%d') BETWEEN '$FromDate' AND '$ToDate') ";

	if ($Type > 0)
		$sSQL .= " AND m.sample_type_id='$Type' ";

	if ($Brand > 0)
		$sSQL .= " AND m.style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand' AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')) ";

	else
		$sSQL .= " AND m.style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id IN ({$_SESSION['Brands']}) AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')) ";

	$sSQL .= "GROUP BY IF(cs.status='', 'W', cs.status)
	          ORDER BY cs.status";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		switch ($objDb->getField($i, 0))
		{
			case "A" : $sStatus = "Approved"; $sColor=0x33ff33; break;
			case "R" : $sStatus = "Rejected"; $sColor=0xff3333; break;
			default  : $sStatus = "Waiting"; $sColor=0x6666ff; break;
		}


		$sColors[] = $sColor;
		$sLabels[] = $sStatus;
		$sData[]   = $objDb->getField($i, 1);
	}


	$objChart = new PieChart(450, 400);
	$objChart->setDonutSize(225, 190, 120, 0);

	if ($Brand > 0)
		$objChart->addTitle("Buyer Statistics ({$sBrandsList[$Brand]})", "verdanab.ttf", 11);

	else
		$objChart->addTitle("Buyer Statistics", "verdana.ttf", 20);

	if ($sSubTitle != "")
		$objChart->addText(225, 20, $sSubTitle, "verdanab.ttf", 10, 0x555555, 8);

	$objChart->set3D(20);
	$objChart->setData($sData, $sLabels);
	$objChart->setColors2(DataColor, $sColors);

	$objChart->addLegend(10, 355, 0);
	$objChart->setLabelLayout(SideLayout, 0);
	$objChart->setLabelFormat("{value} ({percent}%)");
	$objChart->setLabelStyle("verdana.ttf", 10, 0x000000);

	$sChart = $objChart->makeSession("BuyerSampling");
?>
			        <td width="49%"><img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" /></td>
			      </tr>
			    </table>
