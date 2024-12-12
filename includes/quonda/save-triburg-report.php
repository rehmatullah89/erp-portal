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

	if ($sAdditionalPos == "")
		$sAdditionalPos="0";
	

	$sSQL  = ("UPDATE tbl_qa_reports SET po_id='$PoId', check_level='".IO::intValue('SamplingPlan')."', group_id='".IO::intValue('Group')."', additional_pos='$sAdditionalPos', audit_stage='".IO::strValue("AuditStage")."', audit_status='".IO::strValue("AuditStatus")."', 
										audit_result='".IO::strValue("AuditResult")."', audit_type='".IO::strValue("AuditType")."', total_gmts='$TotalGmts', ship_qty='".IO::intValue("ShipQty")."', re_screen_qty='".IO::intValue("ReScreenQty")."',
										knitted='".IO::floatValue("Knitted")."', dyed='".IO::floatValue("Dyed")."', cutting='".IO::intValue("Cutting")."', sewing='".IO::intValue("Sewing")."', finishing='".IO::intValue("Finishing")."', 
										packing='".IO::intValue("Packing")."', final_audit_date='".((IO::strValue("FinalAuditDate") == "") ? "0000-00-00" : IO::strValue("FinalAuditDate"))."', approved_sample='".IO::strValue("ApprovedSample")."', 
										approved_trims='".IO::strValue("ApprovedTrims")."', qa_comments='".(trim(IO::strValue("Comments")) == ""?'N/A':IO::strValue("Comments"))."', total_cartons='".IO::intValue("TotalCartons")."', date_time=NOW( ) WHERE id='$Id'");
	$bFlag = $objDb->execute($sSQL);

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_triburg_inspection_summary WHERE audit_id='$Id'";
		$objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{		
		$sSQL  = ("INSERT INTO tbl_triburg_inspection_summary SET audit_id                      = '$Id',
		                                                      inspection_status                   = '".IO::intValue("QtyOfLots")."',
		                                                      visual_audit                   = '".IO::strValue("VisualAudit")."',
		                                                      visual_audit_remarks             = '".IO::strValue("VisualAuditRemarks")."',
		                                                      shipping_marks                = '".IO::strValue("ShippingMarks")."',
		                                                      shipping_marks_remarks        = '".IO::strValue("ShippingMarksRemarks")."',
		                                                      material_conformity           = '".IO::strValue("MaterialConformity")."',
		                                                      material_conformity_remarks   = '".IO::strValue("MaterialConformityRemarks")."',
		                                                      product_apperance                         = '".IO::strValue("GeneralApperance")."',
		                                                      product_apperance_remarks                 = '".IO::strValue("GeneralApperanceRemarks")."',
		                                                      product_color                        = '".IO::strValue("ProductColor")."',
		                                                      product_color_remarks                = '".IO::strValue("ProductColorRemarks")."',
		                                                      hand_feel         = '".IO::strValue("HandFeel")."',
		                                                      hand_feel_remarks  = '".IO::strValue("HandFeelRemarks")."',
		                                                      wearer_test          = '".IO::strValue("WearerTest")."',
		                                                      wearer_test_remarks  = '".IO::strValue("WearerTestRemarks")."',
		                                                      packing_count             = '".IO::strValue("CountAccuracy")."',
		                                                      packing_count_remarks     = '".IO::strValue("CountAccuracyRemarks")."',
		                                                      packing_ftp                    = '".IO::strValue("PackingFtp")."',
		                                                      packing_ftp_remarks            = '".IO::strValue("PackingFtpRemarks")."',
		                                                      packing_gtp                      = '".IO::strValue("PackingGtp")."',
		                                                      packing_gtp_remarks              = '".IO::strValue("PackingGtpRemarks")."',
		                                                      packing                      = '".IO::strValue("Packing")."',
		                                                      packing_remarks              = '".IO::strValue("PackingRemakrs")."',
		                                                      carton_drop_test                   = '".IO::strValue("CartonDropTest")."',
		                                                      carton_drop_remarks           = '".IO::strValue("CartonDropTestRemarks")."',
		                                                      shade_band                    = '".IO::strValue("ShadeBand")."',
		                                                      shade_band_remarks            = '".IO::strValue("ShadeBandRemarks")."',
		                                                      carton_quality                      = '".IO::strValue("CartonQuality")."',
		                                                      carton_quality_remarks              = '".IO::strValue("CartonQualityRemarks")."',
		                                                      carton_weight             = '".IO::strValue("CartonWeight")."',
		                                                      carton_weight_remarks     = '".IO::strValue("CartonWeightRemarks")."',
		                                                      carton_dimension                     = '".IO::strValue("CartonDimension")."',
		                                                      carton_dimension_remarks             = '".IO::strValue("CartonDimensionRemarks")."',
		                                                      barcode_verification                   = '".IO::strValue("BarCodeVerification")."',
		                                                      barcode_verification_remarks           = '".IO::strValue("BarCodeVerificationRemarks")."',
		                                                      labeling                 = '".IO::strValue("Labelling")."',
		                                                      labeling_remarks         = '".IO::strValue("LabellingRemarks")."',
                                                                      markings                  = '".IO::strValue("Markings")."',
		                                                      markings_remarks          = '".IO::strValue("MarkingsRemarks")."',
                                                                      workmanship             = '".IO::strValue("Workmanship")."',
		                                                      workmanship_remarks     = '".IO::strValue("WorkmanshipRemarks")."',
		                                                      appearance                    = '".IO::strValue("Appearance")."',
		                                                      appearance_remarks            = '".IO::strValue("AppearanceRemarks")."',
		                                                      function         = '".IO::strValue("Function")."',
		                                                      function_remarks = '".IO::strValue("FunctionRemarks")."',
		                                                      printed_materials                       = '".IO::strValue("PrintedMaterial")."',
		                                                      printed_materials_remarks               = '".IO::strValue("PrintedMaterialRemarks")."',
		                                                      finishing                = '".IO::strValue("WorkmanshipFinishing")."',
		                                                      finishing_remarks        = '".IO::strValue("WorkmanshipFinishingRemarks")."',
		                                                      fitting                     = '".IO::strValue("Fitting")."',
		                                                      fitting_remarks                     = '".IO::strValue("FittingRemarks")."',
		                                                      pp_sample                     = '".IO::strValue("PPSample")."',
		                                                      pp_sample_remarks                     = '".IO::strValue("PPSampleRemarks")."',
		                                                      metal_detection_test                    = '".IO::strValue("MetalDetection")."',
		                                                      metal_detection_test_remarks                = '".IO::strValue("MetalDetectionRemarks")."',
		                                                      measurement_result                 = '".IO::strValue("MeasurementResult")."',
		                                                      measurement_result_remarks                 = '".IO::strValue("MeasurementComments")."',
		                                                      shipment_audit                    = '".IO::strValue("ShipmentResult")."',
		                                                      shipment_audit_remarks                = '".IO::strValue("ShipmentResultRemarks")."',
		                                                      remarks                  = '".IO::strValue("Remarks")."'");
		$bFlag = $objDb->execute($sSQL);
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
		$sSQL  = "DELETE FROM tbl_qa_report_quantities WHERE audit_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}
        
        if ($bFlag == true)
        {
				$sSQL = "SELECT sizes, colors FROM tbl_qa_reports WHERE id='$Id'";
				$objDb->query($sSQL);

				$iSizes  = $objDb->getField(0, 'sizes');
				$sColors = $objDb->getField(0, 'colors');


					
			$sSQL = "SELECT po.id, pc.color, s.id
					  FROM tbl_po po, tbl_po_colors pc, tbl_po_quantities pq, tbl_sizes s
					  WHERE po.id=pc.po_id AND pc.po_id=pq.po_id AND pq.size_id=s.id AND pc.style_id='$Style' AND (pc.po_id='$PoId' OR FIND_IN_SET(pc.po_id, '$sAdditionalPos'))
							AND pq.quantity>'0' AND FIND_IN_SET(s.id, '$iSizes') AND FIND_IN_SET(pc.color, '$sColors')
					  GROUP BY po.id, pc.color, s.id
					  ORDER BY po.id, pc.color, s.position";
			$objDb->query($sSQL);

			$iCount    = $objDb->getCount( );
			$iTotalQty = 0;

			for($i = 0; $i < $iCount; $i ++)
			{
				$iPo      = $objDb->getField($i, 'po.id');
				$sPoColor = $objDb->getField($i, 'color');
				$iSize    = $objDb->getField($i, 's.id');
				

				$iQuantity = IO::intValue("Qty_{$iPo}_{$iSize}_".md5($sPoColor));
				
				if ($iQuantity > 0)
				{
					$sSQL  = ("INSERT INTO tbl_qa_report_quantities (audit_id, po_id, size_id, color, quantity) VALUES ('$Id', '$iPo', '$iSize', '$sPoColor', '$iQuantity')");
					$bFlag = $objDb2->execute($sSQL, true, $iUser, $sName);

					if ($bFlag == false)
						break;
					
					$iTotalQty += $iQuantity;
				}
			}
			
			
			if ($bFlag == true && $iTotalQty > 0)
			{
				$sSQL  = "UPDATE tbl_qa_reports SET ship_qty='$iTotalQty' WHERE id='$Id'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

/*
                $sPosArr = IO::getArray("PosArr");
		$Colors = @explode(",", getDbValue("colors", "tbl_qa_reports", "id='$Id'"));
		$Sizes  = @explode(",", getDbValue("sizes", "tbl_qa_reports", "id='$Id'"));
		
                foreach($sPosArr as $iPo)
                {
                    foreach ($Colors as $sColor)
                    {
                            foreach ($Sizes as $iSize)
                            {
                                $sSpecialColor = str_replace(["'",",",'"',"&"," "], "", $sColor);
                                $Sizes      = IO::strValue("SizesArr{$iPo}_{$iSize}_{$sSpecialColor}");
                                $Color      = IO::strValue("ColorsArr{$iPo}_{$iSize}_{$sSpecialColor}");
                                $Quantity   = IO::strValue("QuantitiesArr{$iPo}_{$iSize}_{$sSpecialColor}");

                                if($Quantity > 0)
                                {
                                    $sSQL  = "INSERT INTO tbl_qa_report_quantities (audit_id, po_id, size_id, color, quantity) VALUES ('$Id', '$iPo', '$iSize', '$sColor', '$Quantity')";
				    $bFlag = $objDb->execute($sSQL);
                                    
                                    if($bFlag == false)
                                        break;
                                }
                            }
                    }
                }
*/
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
                $sSQL = "DELETE FROM tbl_qa_packaging_details WHERE audit_id='$Id'";
                $bFlag = $objDb->execute($sSQL);
        }

	if ($bFlag == true)
        {
            $sSampleNos     = IO::getArray("SampleNos");
            $sCartons       = IO::getArray("CartonNo");
            $sCartonStatus  = IO::getArray("CartonStatus");
            
            foreach($sSampleNos as $key => $SampleCount)
            {
                $iCarton = $sCartons[$key];
                $iStatus = $sCartonStatus[$key];
                
                if(trim($iCarton) != "")
                {
                    $iPackagingId = getNextId("tbl_qa_packaging_details");
                    
                    $sSQL = "INSERT INTO tbl_qa_packaging_details SET id='$iPackagingId', audit_id='$Id', carton_no='$iCarton', result='$iStatus', sample_no='$SampleCount'";
                    $bFlag = $objDb->execute($sSQL);
                }
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
			$Area     = IO::intValue("Area".$i);
			$Nature   = IO::floatValue("Nature".$i);
			$Picture  = IO::getFileName($_FILES["Picture".$i]['name']);
			$Defects = (($Defects <= 0) ? 1 : $Defects);
                        

			if ($Nature > 0)
				$iTotalDefects += $Defects;

			if ($DefectId > 0)
			{
				$sSQL  = "UPDATE tbl_qa_report_defects SET code_id='$Code', defects='$Defects', area_id='$Area', nature='$Nature' WHERE id='$DefectId'";
				$bFlag = $objDb->execute($sSQL);
			}
			else
			{
				$DefectId = getNextId("tbl_qa_report_defects");

				$sSQL  = ("INSERT INTO tbl_qa_report_defects (id, audit_id, code_id, defects, area_id, nature, picture, date_time) VALUES ('$DefectId', '$Id', '$Code', '$Defects', '$Area', '$Nature', '$Picture', NOW())");
				$bFlag = $objDb->execute($sSQL);
			}


			if ($bFlag == false)
				break;
		}
	}
        
        if ($bFlag == true)
        {
                $sSQL = "DELETE FROM tbl_qa_packaging_defects WHERE audit_id='$Id'";
                $bFlag = $objDb->execute($sSQL);
        }
        
        if($bFlag == true)
        {
            $PDefectId              = getNextId("tbl_qa_packaging_defects");
            $iPackagingDefectRows   = IO::getArray("PackagingDefectRows");
            $iDefects               = IO::getArray("PDefect");
            $iSampleIds             = IO::getArray("PDSamples");
            $sPrevPictures          = IO::getArray("PrevPicture");
            
            if(!empty($_FILES["PackaginImage"]['name']))
            {
                @list($sPkYear, $sPkMonth, $sPkDay) = @explode("-", $sAuditDate);

                @mkdir(($sBaseDir.PACKAGING_PICS_DIR.$sPkYear), 0777);
                @mkdir(($sBaseDir.PACKAGING_PICS_DIR.$sPkYear."/".$sPkMonth), 0777);
                @mkdir(($sBaseDir.PACKAGING_PICS_DIR.$sPkYear."/".$sPkMonth."/".$sPkDay), 0777);

                $sPackagingDir = ($sBaseDir.PACKAGING_PICS_DIR.$sPkYear."/".$sPkMonth."/".$sPkDay."/");
            }
                
            foreach($iPackagingDefectRows as $iKey => $sZero)
            {
                $iDefect        = $iDefects[$iKey];
                $iSampleId      = $iSampleIds[$iKey];
                $sPrevPicture   = $sPrevPictures[$iKey];
                $sPkPicture     = $_FILES["PackaginImage"]['name'][$iKey];
               
                if($iDefect != "" && $iSampleId != "")
                {
                    $sPkPictureSql  = "";
                    
                    if($sPkPicture != "")
                    {                        
                        $sPkPicture = $Id."_".$iDefect."_".$sPkPicture;
                        if (@move_uploaded_file($_FILES["PackaginImage"]['tmp_name'][$iKey], ($sPackagingDir.$sPkPicture)))
                        {
                            $sPkPictureSql = $sPkPicture;
                        }
                    }
                    else if($sPrevPicture != "")
                        $sPkPictureSql = $sPrevPicture;

                    $sSQL  = ("INSERT INTO tbl_qa_packaging_defects SET id='$PDefectId', audit_id='$Id', defect_code_id='$iDefect', sample_no='$iSampleId', picture='$sPkPictureSql', date_time= NOW()");
                    $bFlag = $objDb->execute($sSQL);
                    
                    $PDefectId++;
                }
            }
        }
        
        if($bFlag == false)
        {
            echo $sSQL;
            exit;
        }

		
	$iDefectiveGmts = getDbValue("COUNT(DISTINCT(sample_no))", "tbl_qa_report_defects", "audit_id='$Id' AND nature>'0'");
	$fDhu           = round((($iDefectiveGmts / $TotalGmts) * 100), 2);
?>