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

	$sSQL = "SELECT *, (SELECT name FROM tbl_users WHERE id=tbl_comment_sheets.modified_by) AS _Person FROM tbl_comment_sheets WHERE merchandising_id='$Id'";
	$objDb->query($sSQL);

	$sMerchComments   = $objDb->getField(0, "merch_comments");
	$sSpecComments    = $objDb->getField(0, "spec_comments");
	$sOtherComments   = $objDb->getField(0, "other_comments");
	$sFittingComments = $objDb->getField(0, "fitting_comments");
	$sNoteSuggestions = $objDb->getField(0, "note_suggestions");
	$sMeasurements    = $objDb->getField(0, "measurements");
	$sMaterial        = $objDb->getField(0, "material");
	$sAccessory       = $objDb->getField(0, "accessory");
	$sArtwork         = $objDb->getField(0, "artwork");
	$sHangtag         = $objDb->getField(0, "hangtag");
	$sLabelingPacking = $objDb->getField(0, "labeling_packing");
	$sResult          = $objDb->getField(0, "result");
	$sDestination     = $objDb->getField(0, "destination");
	$sPerson          = $objDb->getField(0, "_Person");
	$sReportDate      = $objDb->getField(0, "created");


	$objPHPExcel->getActiveSheet()->setCellValue('A8', 'Style :');
	$objPHPExcel->getActiveSheet()->setCellValue('B8', $sStyle);
	$objPHPExcel->getActiveSheet()->getStyle('A8')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

	$objPHPExcel->getActiveSheet()->setCellValue('D8', 'Brand :');
	$objPHPExcel->getActiveSheet()->setCellValue('E8', $sBrand);
	$objPHPExcel->getActiveSheet()->getStyle('D8')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('E8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

	$objPHPExcel->getActiveSheet()->setCellValue('G8', 'Sample Type :');
	$objPHPExcel->getActiveSheet()->setCellValue('H8', $sSampleType);
	$objPHPExcel->getActiveSheet()->getStyle('G8')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('H8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

	$objPHPExcel->getActiveSheet()->setCellValue('A9', 'Season :');
	$objPHPExcel->getActiveSheet()->setCellValue('B9', $sSeason);
	$objPHPExcel->getActiveSheet()->getStyle('A9')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

	$objPHPExcel->getActiveSheet()->setCellValue('D9', 'Wash / Color :');
	$objPHPExcel->getActiveSheet()->setCellValue('E9', $sWash);
	$objPHPExcel->getActiveSheet()->getStyle('D9')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('E9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

	$objPHPExcel->getActiveSheet()->setCellValue('G9', 'Destination :');
	$objPHPExcel->getActiveSheet()->setCellValue('H9', $sDestination);
	$objPHPExcel->getActiveSheet()->getStyle('G9')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('H9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'borders' => array(
					'bottom'   => array('style' => PHPExcel_Style_Border::BORDER_THIN)
				)
			),
			'A11:H11'
	);



	$sSQL = "SELECT * FROM tbl_merchandisings WHERE id='$Id'";
	$objDb->query($sSQL);

	$iStyleId          = $objDb->getField(0, "style_id");
	$sStatus           = $objDb->getField(0, "status");
	$sFabricComp       = $objDb->getField(0, "fabric_comp");
	$sFabricType       = $objDb->getField(0, "fabric_type");
	$sFabricColor      = $objDb->getField(0, "fabric_color");
	$sLining           = $objDb->getField(0, "lining");
	$sInterlining      = $objDb->getField(0, "interlining");
	$sTrimsQuality     = $objDb->getField(0, "trims_quality");
	$sTrimsColor       = $objDb->getField(0, "trims_color");
	$sAccessories      = $objDb->getField(0, "accessories");
	$sLabels           = $objDb->getField(0, "labels");
	$sCareLabel        = $objDb->getField(0, "care_label");
	$sDisclaimerLabel  = $objDb->getField(0, "disclaimer_label");
	$sInseamLabel      = $objDb->getField(0, "inseam_label");
	$sSeasonLabel      = $objDb->getField(0, "season_label");
	$sAddLabel         = $objDb->getField(0, "add_label");
	$sMainLabel        = $objDb->getField(0, "main_label");
	$sEmbPrint         = $objDb->getField(0, "emb_print");
	$sWashColor        = $objDb->getField(0, "wash");
	$sIronBadge        = $objDb->getField(0, "iron_badge");
	$sRivets           = $objDb->getField(0, "rivets");
	$sButtons          = $objDb->getField(0, "buttons");
	$sOutWaistBadge    = $objDb->getField(0, "out_waist_badge");
	$sThreads          = $objDb->getField(0, "threads");
	$sSampleSizes      = $objDb->getField(0, 'sample_sizes');
	$sSampleQuantities = $objDb->getField(0, 'sample_quantities');
	$iEntryTime        = @strtotime($objDb->getField(0, 'modified'));
	$iOrderTime        = @strtotime(date("2010-01-20 23:59:59"));
	$sOrderField       = "id";

	if ($iEntryTime > $iOrderTime)
		$sOrderField = "display_order";

	$iBrand = getDbValue("sub_brand_id", "tbl_styles", "id='$iStyleId'");


	$objPHPExcel->getActiveSheet()->setCellValue('A10', 'Date :');
	$objPHPExcel->getActiveSheet()->setCellValue('B10', date("d-M-Y", $iEntryTime));
	$objPHPExcel->getActiveSheet()->getStyle('A10')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);


	$objPHPExcel->getActiveSheet()->setCellValue('A13', 'Fabric Comp.');
	$objPHPExcel->getActiveSheet()->setCellValue('B13', $sFabricComp);
	$objPHPExcel->getActiveSheet()->getStyle('A13')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->setCellValue('A14', 'Fabric Type');
	$objPHPExcel->getActiveSheet()->setCellValue('B14', $sFabricType);
	$objPHPExcel->getActiveSheet()->getStyle('A14')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->setCellValue('A15', 'Fabric Color');
	$objPHPExcel->getActiveSheet()->setCellValue('B15', $sFabricColor);
	$objPHPExcel->getActiveSheet()->getStyle('A15')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->setCellValue('A16', 'Lining');
	$objPHPExcel->getActiveSheet()->setCellValue('B16', $sLining);
	$objPHPExcel->getActiveSheet()->getStyle('A16')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->setCellValue('A17', 'Interlining');
	$objPHPExcel->getActiveSheet()->setCellValue('B17', $sInterlining);
	$objPHPExcel->getActiveSheet()->getStyle('A17')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->setCellValue('A18', 'Care Label');
	$objPHPExcel->getActiveSheet()->setCellValue('B18', $sCareLabel);
	$objPHPExcel->getActiveSheet()->getStyle('A18')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->setCellValue('A19', 'Disclaimer Label');
	$objPHPExcel->getActiveSheet()->setCellValue('B19', $sDisclaimerLabel);
	$objPHPExcel->getActiveSheet()->getStyle('A19')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->setCellValue('A20', 'Inseam Label');
	$objPHPExcel->getActiveSheet()->setCellValue('B20', $sInseamLabel);
	$objPHPExcel->getActiveSheet()->getStyle('A20')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->setCellValue('A21', 'Season/Wash ID Label');
	$objPHPExcel->getActiveSheet()->setCellValue('B21', $sSeasonLabel);
	$objPHPExcel->getActiveSheet()->getStyle('A21')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->setCellValue('A22', 'Add Label');
	$objPHPExcel->getActiveSheet()->setCellValue('B22', $sAddLabel);
	$objPHPExcel->getActiveSheet()->getStyle('A22')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->setCellValue('A23', 'Main Label');
	$objPHPExcel->getActiveSheet()->setCellValue('B23', $sMainLabel);
	$objPHPExcel->getActiveSheet()->getStyle('A23')->getFont()->setBold(true);



	$objPHPExcel->getActiveSheet()->setCellValue('D13', 'Trims Quality');
	$objPHPExcel->getActiveSheet()->setCellValue('E13', $sTrimsQuality);
	$objPHPExcel->getActiveSheet()->getStyle('D13')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->setCellValue('D14', 'Trims Color');
	$objPHPExcel->getActiveSheet()->setCellValue('E14', $sTrimsColor);
	$objPHPExcel->getActiveSheet()->getStyle('D14')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->setCellValue('D15', 'Accessories');
	$objPHPExcel->getActiveSheet()->setCellValue('E15', $sAccessories);
	$objPHPExcel->getActiveSheet()->getStyle('D15')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->setCellValue('D16', 'Labels');
	$objPHPExcel->getActiveSheet()->setCellValue('E16', $sLabels);
	$objPHPExcel->getActiveSheet()->getStyle('D16')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->setCellValue('D17', 'Emb/Applique/Print');
	$objPHPExcel->getActiveSheet()->setCellValue('E17', $sEmbPrint);
	$objPHPExcel->getActiveSheet()->getStyle('D17')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->setCellValue('D18', 'Wash / Color');
	$objPHPExcel->getActiveSheet()->setCellValue('E18', $sWashColor);
	$objPHPExcel->getActiveSheet()->getStyle('D18')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->setCellValue('D19', 'Iron Badge');
	$objPHPExcel->getActiveSheet()->setCellValue('E19', $sIronBadge);
	$objPHPExcel->getActiveSheet()->getStyle('D19')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->setCellValue('D20', 'Rivets');
	$objPHPExcel->getActiveSheet()->setCellValue('E20', $sRivets);
	$objPHPExcel->getActiveSheet()->getStyle('D20')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->setCellValue('D21', 'Buttons');
	$objPHPExcel->getActiveSheet()->setCellValue('E21', $sButtons);
	$objPHPExcel->getActiveSheet()->getStyle('D21')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->setCellValue('D22', 'Out Waist Badge');
	$objPHPExcel->getActiveSheet()->setCellValue('E22', $sOutWaistBadge);
	$objPHPExcel->getActiveSheet()->getStyle('D22')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->setCellValue('D23', 'Threads');
	$objPHPExcel->getActiveSheet()->setCellValue('E23', $sThreads);
	$objPHPExcel->getActiveSheet()->getStyle('D23')->getFont()->setBold(true);


	$iQuantities = @explode(",", $sSampleQuantities);
	$sSizes      = "";

	$sSQL = "SELECT size FROM tbl_sampling_sizes WHERE id IN ($sSampleSizes) ORDER BY $sOrderField";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sSizes .= (", ".$objDb->getField($i, 0));

		$sSizes = substr($sSizes, 2);
	}

	$objPHPExcel->getActiveSheet()->setCellValue('G13', 'No of Smpls');
	$objPHPExcel->getActiveSheet()->setCellValue('H13', @array_sum($iQuantities));
	$objPHPExcel->getActiveSheet()->getStyle('G13')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('H13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

	$objPHPExcel->getActiveSheet()->setCellValue('G14', 'Sample Size');
	$objPHPExcel->getActiveSheet()->setCellValue('H14', $sSizes);
	$objPHPExcel->getActiveSheet()->getStyle('G14')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('H14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);


	$iRowNo = 25;

	if (($iBrand == 67 || $iBrand == 75) && strtotime($sReportDate) >= strtotime("2013-02-01"))
	{
		$sComments = array(
		                     'Sample Measurements' => $sMeasurements,
		                     'Sample Fitting' => $sFittingComments,
		                     'Material' => $sMaterial,
		                     'Accessory' => $sAccessory,
		                     'Workmanship' => $sOtherComments,
		                     'Artwork' => $sArtwork,
		                     'Hangtag' => $sHangtag,
		                     'Labeling and Packing' => $sLabelingPacking,
		                     'Result' => $sResult
		                  );
	}

	else
	{
		$sComments = array(
		                     'Merchant Comments' => $sMerchComments,
		                     'Spec Comments' => $sSpecComments,
		                     'Construction/Quality/Workmanship' => $sOtherComments,
		                     'Fitting Comments' => $sFittingComments,
		                     'Note/Suggestions' => $sNoteSuggestions
		                  );
	}

	foreach ($sComments as $sTitle => $sData)
	{
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRowNo, utf8_encode($sTitle));
		$objPHPExcel->getActiveSheet()->getStyle('A'.$iRowNo)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$iRowNo.':B'.$iRowNo);

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
				array(
					'borders' => array(
						'bottom'   => array('style' => PHPExcel_Style_Border::BORDER_THIN)
					)
				),
				'A'.$iRowNo.':H'.$iRowNo
		);

		$sLines = @explode("<br />", nl2br($sData));

		for ($i = 0; $i < count($sLines); $i ++)
		{
			$sLines[$i] = str_replace("’", "'", $sLines[$i]);

			$objRichText = new PHPExcel_RichText( $objPHPExcel->getActiveSheet()->getCell('A'.($iRowNo + 1 + $i)) );
			$objRichText->createText(utf8_encode($sLines[$i]));

			$objPHPExcel->getActiveSheet()->mergeCells('A'.($iRowNo + 1 + $i).':H'.($iRowNo + 1 + $i));
		}

		$iRowNo += (2 + $i);
	}


	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'borders' => array(
					'bottom'   => array('style' => PHPExcel_Style_Border::BORDER_THIN)
				)
			),
			'A'.($iRowNo - 1 + $i).':H'.($iRowNo - 1 + $i)
	);


	switch ($sStatus)
	{
		case "A" : $sStatus = "Approved"; break;
		case "R" : $sStatus = "Rejected"; break;
		case "W" : $sStatus = "Hold"; break;
	}

	$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRowNo, 'Status');
	$objPHPExcel->getActiveSheet()->setCellValue('A'.($iRowNo + 1), $sStatus);
	$objPHPExcel->getActiveSheet()->setCellValue('A'.($iRowNo + 3), $sPerson);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$iRowNo)->getFont()->setBold(true);

	$iRowNo += 5;

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(5);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(5);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);


	//////////////////////////////////////  Measurement Specs  ////////////////////////////////////////////////////

	$iRowNo = 9;
	$iColNo = 74;

	$iSampleQuantities = @explode(",", $sSampleQuantities);
	$iSampleSizes      = array( );

	$sSQL = "SELECT size FROM tbl_sampling_sizes WHERE id IN ($sSampleSizes) ORDER BY $sOrderField";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$iSampleSizes[] = $objDb->getField($i, 0);
	}

	$iSizesCount      = count($iSampleSizes);
	$iQuantitiesCount = count($iSampleQuantities);


	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColNo).$iRowNo, 'Measurement Point');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColNo + 1).$iRowNo, '');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColNo + 2).$iRowNo, 'Tolerance');
	$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColNo + 3).($iRowNo - 1), 'Size');

	$objPHPExcel->getActiveSheet()->mergeCells(getExcelCol($iColNo).($iRowNo - 1).':'.getExcelCol($iColNo + 1).($iRowNo - 1));
	$objPHPExcel->getActiveSheet()->mergeCells(getExcelCol($iColNo).$iRowNo.':'.getExcelCol($iColNo + 1).$iRowNo);
	$objPHPExcel->getActiveSheet()->mergeCells(getExcelCol($iColNo).($iRowNo + 1).':'.getExcelCol($iColNo + 1).($iRowNo + 1));

	$sLastCell = getExcelCol($iColNo + @array_sum($iSampleQuantities) + $iQuantitiesCount + 2);

	$objPHPExcel->getActiveSheet()->mergeCells(getExcelCol($iColNo + 3).($iRowNo - 1).':'.$sLastCell.($iRowNo - 1));

	$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($iColNo).$iRowNo)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($iColNo + 2).$iRowNo)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($iColNo + 3).($iRowNo - 1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
				'borders' => array(
					'top'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'left'  => array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
				)
			),
			getExcelCol($iColNo).($iRowNo - 1).':'.$sLastCell.($iRowNo - 1)
	);

	$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($iColNo + 2).($iRowNo - 1))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($iColNo + 2).($iRowNo - 1))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

	$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($iColNo).($iRowNo - 1))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->getActiveSheet()->getStyle($sLastCell.($iRowNo - 1))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

	$iIndex = 0;

	for ($i = 0; $i < $iSizesCount; $i ++)
	{
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColNo + 3 + $iIndex).$iRowNo, $iSampleSizes[$i]);
		$objPHPExcel->getActiveSheet()->mergeCells(getExcelCol($iColNo + 3 + $iIndex).$iRowNo.':'.getExcelCol($iColNo + 3 + $iIndex + $iSampleQuantities[$i]).$iRowNo);
		$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($iColNo + 3 + $iIndex).$iRowNo)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
					'borders' => array(
						'top'     => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'left'    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THIN)
					)
				),
				getExcelCol($iColNo + 3 + $iIndex).$iRowNo.':'.getExcelCol($iColNo + 3 + $iIndex + $iSampleQuantities[$i]).$iRowNo
		);

		$iIndex += ($iSampleQuantities[$i] + 1);
	}



	$iRowNo ++;
	$iIndex = 0;

	for ($i = 0; $i < $iSizesCount; $i ++)
	{
		for ($j = 0; $j <= $iSampleQuantities[$i]; $j ++)
		{
			if ($j == 0)
				$sHeading = "Spec";

			else
				$sHeading = $j;

			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColNo + 3 + $iIndex).$iRowNo, $sHeading);
			$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($iColNo + 3 + $iIndex).$iRowNo)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$iIndex ++;
		}
	}

	$objPHPExcel->getActiveSheet()->duplicateStyleArray(
		array(
				'borders' => array(
					'top'     => array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'left'    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THIN)
				)
			),
			getExcelCol($iColNo + 3).$iRowNo.':'.($sLastCell.$iRowNo)
	);

	$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($iColNo).($iRowNo -1))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($iColNo).$iRowNo)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($iColNo + 2).($iRowNo - 1))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($iColNo + 2).$iRowNo)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

	$iRowNo ++;


	$sSQL = "SELECT ms.data, ms.tolerance, mp.point_id AS _PointId, mp.point AS _Point
	         FROM tbl_measurement_specs ms, tbl_measurement_points mp
	         WHERE ms.point_id=mp.id AND ms.merchandising_id='$Id'
	         ORDER BY ms.id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for($i = 0; $i < $iCount; $i ++)
	{
		$sPointId   = $objDb->getField($i, '_PointId');
		$sPoint     = $objDb->getField($i, '_Point');
		$sTolerance = $objDb->getField($i, 'tolerance');
		$sData      = $objDb->getField($i, 'data');

		$sData = @explode(",", $sData);

		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColNo).$iRowNo, $sPointId);
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColNo + 1).$iRowNo, @utf8_encode($sPoint));
		$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColNo + 2).$iRowNo, $sTolerance);

		$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($iColNo).$iRowNo)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($iColNo + 1).$iRowNo)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($iColNo + 2).$iRowNo)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		for ($j = 0; $j < count($sData); $j ++)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(getExcelCol($iColNo + 3 + $j).$iRowNo, $sData[$j]);
			$objPHPExcel->getActiveSheet()->getStyle(getExcelCol($iColNo + 3 + $j).$iRowNo)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
			array(
					'borders' => array(
						'top'     => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'left'    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THIN)
					)
				),
				getExcelCol($iColNo).$iRowNo.':'.(getExcelCol($iColNo + 2 + $j).$iRowNo)
		);

		$iRowNo ++;
	}

	$objPHPExcel->getActiveSheet()->getColumnDimension(getExcelCol($iColNo + 1))->setWidth(31);
	$objPHPExcel->getActiveSheet()->getColumnDimension(getExcelCol($iColNo + 2))->setWidth(10);

	for ($i = 0; $i < count($sData); $i ++)
		$objPHPExcel->getActiveSheet()->getColumnDimension(getExcelCol($iColNo + 3 + $i))->setWidth(8);


	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setScale(100);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0);
?>