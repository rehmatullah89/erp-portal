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
//echo "<pre>";print_r($_POST);exit;
	$iTotalDefects = 0;
	$CartonSize    = (IO::strValue("Length")."x".IO::strValue("Width")."x".IO::strValue("Height")."x".IO::strValue("Unit"));

	$sSQL  = ("UPDATE tbl_qa_reports SET po_id='$PoId', group_id='".IO::intValue('Group')."', additional_pos='$sAdditionalPos', audit_stage='".IO::strValue("AuditStage")."', audit_status='".IO::strValue("AuditStatus")."', audit_result='".IO::strValue("AuditResult")."', audit_type='".IO::strValue("AuditType")."', colors='".IO::strValue("Colors")."', bundle='".IO::strValue("Bundle")."', sizes='".@implode(",", IO::getArray('Sizes'))."', ship_qty='".IO::intValue("ShipQty")."', re_screen_qty='".IO::intValue("ReScreenQty")."', cartons_required='".IO::intValue("CartonsRequired")."', cartons_shipped='".IO::intValue("CartonsShipped")."', total_gmts='$TotalGmts', defective_gmts='".IO::floatValue("GmtsDefective")."', max_defects='$MaxDefects', total_cartons='".IO::floatValue("TotalCartons")."', rejected_cartons='".IO::floatValue("CartonsRejected")."', defective_percent='".IO::floatValue("PercentDecfective")."', knitted='".IO::floatValue("Knitted")."', dyed='".IO::floatValue("Dyed")."', cutting='".IO::intValue("Cutting")."', sewing='".IO::intValue("Sewing")."', finishing='".IO::intValue("Finishing")."', packing='".IO::intValue("Packing")."', final_audit_date='".((IO::strValue("FinalAuditDate") == "") ? "0000-00-00" : IO::strValue("FinalAuditDate"))."', standard='".IO::floatValue("Standard")."', approved_sample='".IO::strValue("ApprovedSample")."', shipping_mark='".IO::strValue("ShippingMark")."', packing_check='".IO::strValue("PackingCheck")."', approved_trims='".IO::strValue("ApprovedTrims")."', shade_band='".IO::strValue("ShadeBand")."', emb_approval='".IO::strValue("EmbApproval")."', gsm_weight='".IO::strValue("GsmWeight")."', carton_size='$CartonSize', cutting_lot_no='".IO::strValue("LotNo")."', qa_comments='".IO::strValue("Comments")."', date_time=NOW( ) WHERE id='$Id'");
	$bFlag = $objDb->execute($sSQL);

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
			$Remarks  = IO::strValue("Remarks".$i);
                        $Status   = IO::strValue("Status".$i);
			$Picture  = IO::getFileName($_FILES["Picture".$i]['name']);
			$Defects = (($Defects <= 0) ? 1 : $Defects);

			
			if ($Nature > 0)
				$iTotalDefects += $Defects;


			if ($DefectId > 0)
			{
				$sSQL  = "UPDATE tbl_qa_report_defects SET code_id='$Code', defects='$Defects', area_id='$Area', nature='$Nature', remarks='$Remarks', status='$Status', date_time=NOW() WHERE id='$DefectId'";
				$bFlag = $objDb->execute($sSQL);
			}

			else
			{
				$DefectId = getNextId("tbl_qa_report_defects");

				$sSQL  = ("INSERT INTO tbl_qa_report_defects (id, audit_id, code_id, defects, area_id, nature, remarks, picture, status, date_time) VALUES ('$DefectId', '$Id', '$Code', '$Defects', '$Area', '$Nature', '$Remarks', '{$DefectId}-{$Picture}', '$Status', NOW())");
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == false)
				break;
		}
	}
        
        if ($bFlag == true)
	{
            $sSQL  = "DELETE FROM tbl_qa_levis_reports WHERE audit_id='$Id'";
            $bFlag = $objDb->execute($sSQL);
                
        }
        
        if ($bFlag == true)
        {
            $sSQL  = ("INSERT INTO tbl_qa_levis_reports (audit_id, safety, critical_failure, sewing, appearance, measurements, sundries_missing, sundries_broken, accuracy, physicals, other, cartons_sampled, cartons_in_error, units_sampled, units_in_errors, overage, shortage, wrong_size, wrong_pc, irregulars, wrong_sundries) VALUES ('$Id', '".IO::intValue("Safety")."', '".IO::intValue("CriticalFailure")."','".IO::intValue("CSewing")."','".IO::intValue("Appearance")."','".IO::intValue("Measurements")."','".IO::intValue("SundriesBroken")."','".IO::intValue("SundriesBroken")."','".IO::intValue("Accuracy")."','".IO::intValue("Physicals")."','".IO::intValue("Other")."','".IO::intValue("CartonsSampled")."','".IO::intValue("CartonsInError")."','".IO::intValue("UnitsSampled")."','".IO::intValue("UnitsInErrors")."','".IO::intValue("Overage")."','".IO::intValue("Shortage")."','".IO::intValue("WrongSize")."','".IO::intValue("WrongPC")."','".IO::intValue("Irregulars")."','".IO::intValue("WrongSundries")."')");
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
                            if(!empty($sColor) && !empty($iSize))
                            {
				$sSize         = getDbValue("size", "tbl_sizes", "id='$iSize'");
				$iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '$sSize'");
				$iSamples      = array(0, 0, 0, 0, 0, 0);

                                if($iSamplingSize == "")
                                {
                                    @list($sWaist, $sInseenLength) = @explode(" ", $sSize);

                                    $iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '$sWaist'");
                                }

				$sSQL = "SELECT point_id FROM tbl_style_specs WHERE style_id='$Style' AND size_id='$iSamplingSize' AND version='0' ORDER BY id";
                                $objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for($i = 0; $i < $iCount; $i ++)
				{
					$iPoint = $objDb->getField($i, 'point_id');


					for ($j = 1; $j <= 6; $j ++)
					{
						$sFindings = IO::strValue("Specs{$iSamplingSize}_{$iColor}_{$iPoint}_{$j}");

						if ($sFindings != "" && $iSamples[($j - 1)] == 0)
						{
							$iSampleNo = (getDbValue("COALESCE(MAX(sample_no), '0')", "tbl_qa_report_samples", "audit_id='$Id' AND size_id='$iSamplingSize' AND color LIKE '$sColor'") + 1);
							$iSampleId = getNextId("tbl_qa_report_samples");

							$sSQL  = "INSERT INTO tbl_qa_report_samples (id, audit_id, size_id, po_size_id, size, color, sample_no, date_time) VALUES ('$iSampleId', '$Id', '$iSamplingSize', '$iSize', '$sSize', '$sColor', '$iSampleNo', NOW( ))";
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


					for ($j = 1; $j <= 6; $j ++)
					{
						$sFindings = IO::strValue("Specs{$iSamplingSize}_{$iColor}_{$iPoint}_{$j}");
                                                $sReplaceSpecs = IO::strValue("ReplaceSpecs{$iSamplingSize}_{$iPoint}");
                                                
						if ($sFindings == "")
							continue;

						$sSQL  = "INSERT INTO tbl_qa_report_sample_specs (sample_id, point_id, findings, specs) VALUES ('{$iSamples[($j - 1)]}', '$iPoint', '$sFindings', '$sReplaceSpecs') ON DUPLICATE KEY UPDATE point_id='$iPoint', findings='$sFindings', specs='$sReplaceSpecs'";
						$bFlag = $objDb2->execute($sSQL);

						if ($bFlag == false)
							break;
					}
				}

				if ($bFlag == false)
					break;
                            }
			}


			if ($bFlag == false)
				break;

			$iColor ++;
		}
	}
        
        /*if($bFlag == true)
        {
            $SpecsList  = IO::getArray("ReplaceSpecs");
            $SpecsItems = IO::getArray("ReplaceSpecItems");
            
            if(count($SpecsList) > 0)
            {
                foreach($SpecsList as $iKey => $sSpec)
                {
                    if($sSpec != "")
                    {
                        $sSpecItems = explode("_", $SpecsItems[$iKey]);
                        $iSStyle    = @$sSpecItems[0];
                        $iSSize     = @$sSpecItems[1];
                        $iSPoint    = @$sSpecItems[2];
                        
                        if($iSStyle>0 && $iSSize > 0 && $iSPoint > 0)
                        {
                            $sSQL  = "UPDATE tbl_style_specs SET specs='$sSpec' WHERE size_id='$iSSize' AND style_id='$iSStyle' AND point_id='$iSPoint'";
                            $bFlag = $objDb->execute($sSQL);
                        }
                    }
                }
            }
        }*/
        

	$fDhu = round((($iTotalDefects / $TotalGmts) * 100), 2);
?>