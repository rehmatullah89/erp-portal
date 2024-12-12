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

	$sSQL  = ("UPDATE tbl_qa_reports SET po_id='$PoId', group_id='".IO::intValue('Group')."', additional_pos='$sAdditionalPos', audit_stage='".IO::strValue("AuditStage")."', audit_status='".IO::strValue("AuditStatus")."', audit_result='".IO::strValue("AuditResult")."', audit_type='".IO::strValue("AuditType")."', total_gmts='$TotalGmts', ship_qty='".IO::intValue("ShipQty")."', re_screen_qty='".IO::intValue("ReScreenQty")."', knitted='".IO::floatValue("Knitted")."', dyed='".IO::floatValue("Dyed")."', cutting='".IO::intValue("Cutting")."', sewing='".IO::intValue("Sewing")."', finishing='".IO::intValue("Finishing")."', packing='".IO::intValue("Packing")."', final_audit_date='".((IO::strValue("FinalAuditDate") == "") ? "0000-00-00" : IO::strValue("FinalAuditDate"))."', approved_sample='".IO::strValue("ApprovedSample")."', approved_trims='".IO::strValue("ApprovedTrims")."', qa_comments='".IO::strValue("Comments")."', date_time=NOW( ) WHERE id='$Id'");
	$bFlag = $objDb->execute($sSQL);

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_arcadia_inspection_summary WHERE audit_id='$Id'";
		$objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$iPo    = getDbValue("po_id", "tbl_qa_reports", "id='$Id'");
		$iPoQty = getDbValue("quantity", "tbl_po", "id='$iPo'");

		$iUnitsPackedQty      = IO::intValue("UnitsPackedQty");
		$iUnitsFinishedQty    = IO::intValue("UnitsFinishedQty");
		$iUnitsNotFinishedQty = IO::intValue("UnitsNotFinishedQty");

		$fUnitsPackedPercent      = @round((($iUnitsPackedQty / $iPoQty) * 100), 2);
		$fUnitsFinishedPercent    = @round((($iUnitsFinishedQty / $iPoQty) * 100), 2);
		$fUnitsNotFinishedPercent = @round((($iUnitsNotFinishedQty / $iPoQty) * 100), 2);



		$sSQL  = ("INSERT INTO tbl_arcadia_inspection_summary SET audit_id                      = '$Id',
		                                                      qty_of_lots                   = '".IO::intValue("QtyOfLots")."',
		                                                      qty_per_lot                   = '".IO::intValue("QtyPerLot")."',
		                                                      inspection_status             = '".IO::strValue("InspectionStatus")."',
		                                                      shipping_marks                = '".IO::strValue("ShippingMarks")."',
		                                                      shipping_marks_remarks        = '".IO::strValue("ShippingMarksRemarks")."',
		                                                      material_conformity           = '".IO::strValue("MaterialConformity")."',
		                                                      material_conformity_remarks   = '".IO::strValue("MaterialConformityRemarks")."',
		                                                      style                         = '".IO::strValue("ProductStyle")."',
		                                                      style_remarks                 = '".IO::strValue("ProductStyleRemarks")."',
		                                                      colour                        = '".IO::strValue("ProductColour")."',
		                                                      colour_remarks                = '".IO::strValue("ProductColourRemarks")."',
		                                                      export_carton_packing         = '".IO::strValue("ExportCartonPacking")."',
		                                                      export_carton_packing_remarks = '".IO::strValue("ExportCartonPackingRemarks")."',
		                                                      inner_carton_packing          = '".IO::strValue("InnerCartonPacking")."',
		                                                      inner_carton_packing_remarks  = '".IO::strValue("InnerCartonPackingRemarks")."',
		                                                      product_packaging             = '".IO::strValue("ProductPackaging")."',
		                                                      product_packaging_remarks     = '".IO::strValue("ProductPackagingRemarks")."',
		                                                      assortment                    = '".IO::strValue("Assortment")."',
		                                                      assortment_remarks            = '".IO::strValue("AssortmentRemarks")."',
		                                                      labeling                      = '".IO::strValue("Labeling")."',
		                                                      labeling_remarks              = '".IO::strValue("LabelingRemarks")."',
		                                                      markings                      = '".IO::strValue("Markings")."',
		                                                      markings_remarks              = '".IO::strValue("MarkingsRemarks")."',
		                                                      workmanship                   = '".IO::strValue("Workmanship")."',
		                                                      workmanship_remarks           = '".IO::strValue("WorkmanshipRemarks")."',
		                                                      appearance                    = '".IO::strValue("Appearance")."',
		                                                      appearance_remarks            = '".IO::strValue("AppearanceRemarks")."',
		                                                      function                      = '".IO::strValue("Function")."',
		                                                      function_remarks              = '".IO::strValue("FunctionRemarks")."',
		                                                      printed_materials             = '".IO::strValue("PrintedMaterials")."',
		                                                      printed_materials_remarks     = '".IO::strValue("PrintedMaterialsRemarks")."',
		                                                      finishing                     = '".IO::strValue("WorkmanshipFinishing")."',
		                                                      finishing_remarks             = '".IO::strValue("WorkmanshipFinishingRemarks")."',
		                                                      measurement                   = '".IO::strValue("Measurement")."',
		                                                      measurement_remarks           = '".IO::strValue("MeasurementRemarks")."',
		                                                      fabric_weight                 = '".IO::strValue("FabricWeight")."',
		                                                      fabric_weight_remarks         = '".IO::strValue("FabricWeightRemarks")."',
		                                                      calibrated_scales             = '".IO::strValue("CalibratedScales")."',
		                                                      calibrated_scales_remarks     = '".IO::strValue("CalibratedScalesRemarks")."',
		                                                      cords_norm                    = '".IO::strValue("CordNorm")."',
		                                                      cords_norm_remarks            = '".IO::strValue("CordNormRemarks")."',
		                                                      inspection_conditions         = '".IO::strValue("InspectionConditions")."',
		                                                      inspection_conditions_remarks = '".IO::strValue("InspectionConditionsRemarks")."',
		                                                      remarks_1                     = '".IO::strValue("Remarks1")."',
		                                                      remarks_2                     = '".IO::strValue("Remarks2")."',
		                                                      remarks_3                     = '".IO::strValue("Remarks3")."',
		                                                      remarks_4                     = '".IO::strValue("Remarks4")."',
		                                                      carton_nos                    = '".@implode(",", array_filter(IO::getArray("CartonNos")))."',
		                                                      shipment_units                = '".IO::intValue("ShipmentQtyUnits")."',
		                                                      shipment_ctns                 = '".IO::intValue("ShipmentQtyCtns")."',
		                                                      presented_qty                 = '".IO::intValue("PresentedQty")."',
		                                                      packed_qty                    = '$iUnitsPackedQty',
		                                                      packed_percent                = '$fUnitsPackedPercent',
		                                                      finished_qty                  = '$iUnitsFinishedQty',
		                                                      finished_percent              = '$fUnitsFinishedPercent',
		                                                      not_finished_qty              = '$iUnitsNotFinishedQty',
		                                                      not_finished_percent          = '$fUnitsNotFinishedPercent',
		                                                      measurement_result            = '".IO::strValue("MeasurementResult")."',
		                                                      measurement_overall_remarks   = '".IO::strValue("MeasurementComments")."'");
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL  = "DELETE FROM tbl_arcadia_samples_per_size WHERE audit_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		for ($i = 1; $i <= 10; $i ++)
		{
                        $sSizeColor = IO::strValue("SizeColor{$i}");
                        $iSizeQty   = IO::intValue("SizeQty{$i}");
                        $iSampleQty = IO::intValue("SampleQty{$i}");
                        
			if ($sSizeColor == "" || $iSizeQty == 0 || $iSampleQty == 0)
				continue;


			if (getDbValue("COUNT(1)", "tbl_arcadia_samples_per_size", ("audit_id='$Id' AND size_color='".$sSizeColor."'")) > 0)
			{
				$_SESSION['Flag'] = "QA_REPORT_DUPLICATE_SIZE_COLOR";

				$bFlag  = false;

				break;
			}


                       $sSQL  = ("INSERT INTO tbl_arcadia_samples_per_size SET audit_id   = '$Id',
																	size_color = '".$sSizeColor."',
																	size_qty   = '".$iSizeQty."',
																	sample_qty = '".$iSampleQty."'");
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == false)
				break;
		}
	}

	if ($bFlag == true)
	{
		$sSQL  = "DELETE FROM tbl_qa_report_sample_specs WHERE sample_id IN (SELECT id FROM tbl_qa_report_samples WHERE audit_id='$Id')";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL  = "DELETE FROM tbl_qa_report_samples WHERE audit_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$Colors = @explode(",", getDbValue("colors", "tbl_qa_reports", "id='$Id'"));
		$Sizes  = @explode(",", getDbValue("sizes", "tbl_qa_reports", "id='$Id'"));
		$iColor = 0;


		foreach ($Colors as $sColor)
		{
			foreach ($Sizes as $iSize)
			{
				$sSize         = getDbValue("size", "tbl_sizes", "id='$iSize'");
				$iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '$sSize'");
				$iSamples      = array(0, 0, 0, 0, 0);


				$sSQL = "SELECT point_id FROM tbl_style_specs WHERE style_id='$Style' AND size_id='$iSamplingSize' AND version='0' AND specs!='0' AND specs!='' ORDER BY id";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for($i = 0; $i < $iCount; $i ++)
				{
					$iPoint = $objDb->getField($i, 'point_id');


					for ($j = 1; $j <= 5; $j ++)
					{
						$sFindings = IO::strValue("Specs{$iSamplingSize}_{$iColor}_{$iPoint}_{$j}");

						if ($sFindings != "" && $iSamples[($j - 1)] == 0)
						{
							$iSampleNo = (getDbValue("COALESCE(MAX(sample_no), '0')", "tbl_qa_report_samples", "audit_id='$Id' AND size_id='$iSamplingSize' AND color LIKE '$sColor'") + 1);
							$iSampleId = getNextId("tbl_qa_report_samples");

							$sSQL  = "INSERT INTO tbl_qa_report_samples (id, audit_id, size_id, color, sample_no, date_time) VALUES ('$iSampleId', '$Id', '$iSamplingSize', '$sColor', '$iSampleNo', NOW( ))";
							$bFlag = $objDb2->execute($sSQL);

							$iSamples[($j - 1)] = $iSampleId;
						}

						if ($bFlag == false)
							break;
					}
				}

				if ($bFlag == false)
					break;


				for($i = 0; $i < $iCount; $i ++)
				{
					$iPoint = $objDb->getField($i, 'point_id');


					for ($j = 1; $j <= 5; $j ++)
					{
						$sFindings = IO::strValue("Specs{$iSamplingSize}_{$iColor}_{$iPoint}_{$j}");

						if ($sFindings == "")
							continue;


						$sSQL  = "INSERT INTO tbl_qa_report_sample_specs (sample_id, point_id, findings) VALUES ('{$iSamples[($j - 1)]}', '$iPoint', '$sFindings')";
						$bFlag = $objDb2->execute($sSQL);

						if ($bFlag == false)
							break;
					}
				}


				if ($bFlag == false)
					break;
			}


			if ($bFlag == false)
				break;

			$iColor ++;
		}
	}

	
	if ($bFlag == true)
	{
		$iCount = IO::intValue("Count");

		for ($i = 0; $i < $iCount; $i ++)
		{
			$DefectId = IO::intValue("DefectId".$i);
			$Code     = IO::intValue("Code".$i);
			$Defects  = IO::intValue("Defects".$i);
                        $SampleNo = IO::intValue("SampleNo".$i);
			$Area     = IO::intValue("Area".$i);
			$Nature   = IO::floatValue("Nature".$i);
			$Picture  = IO::getFileName($_FILES["Picture".$i]['name']);
			$Defects = (($Defects <= 0) ? 1 : $Defects);
                        

			if ($DefectId > 0)
			{
				$sSQL  = "UPDATE tbl_qa_report_defects SET code_id='$Code', sample_no='$SampleNo', defects='$Defects', area_id='$Area', nature='$Nature' WHERE id='$DefectId'";
				$bFlag = $objDb->execute($sSQL);
			}

			else
			{
				$DefectId = getNextId("tbl_qa_report_defects");

				$sSQL  = ("INSERT INTO tbl_qa_report_defects (id, audit_id, sample_no, code_id, defects, area_id, nature, picture, date_time) VALUES ('$DefectId', '$Id', '$SampleNo', '$Code', '$Defects', '$Area', '$Nature', '$Picture', NOW())");
				$bFlag = $objDb->execute($sSQL);
			}


			if ($bFlag == false)
				break;
		}
	}
        

	$iDefectiveGmts = getDbValue("COUNT(DISTINCT(sample_no))", "tbl_qa_report_defects", "audit_id='$Id' AND nature>'0'");
	$fDhu           = round((($iDefectiveGmts / $TotalGmts) * 100), 2);
?>