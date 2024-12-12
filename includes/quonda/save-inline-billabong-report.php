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

	$CartonSize    = (IO::strValue("Length")."x".IO::strValue("Width")."x".IO::strValue("Height")."x".IO::strValue("Unit"));

	$sSQL  = ("UPDATE tbl_qa_reports SET po_id='$PoId', group_id='".IO::intValue('Group')."', additional_pos='$sAdditionalPos', audit_stage='".IO::strValue("AuditStage")."', audit_status='".IO::strValue("AuditStatus")."', audit_result='".IO::strValue("AuditResult")."', audit_type='".IO::strValue("AuditType")."', colors='".IO::strValue("Colors")."', cartons_required='".IO::intValue("CartonsRequired")."', cartons_shipped='".IO::intValue("CartonsShipped")."', total_gmts='$TotalGmts', max_defects='$MaxDefects', total_cartons='".IO::floatValue("TotalCartons")."', rejected_cartons='".IO::floatValue("CartonsRejected")."', defective_percent='".IO::floatValue("PercentDecfective")."', knitted='".IO::floatValue("Knitted")."', dyed='".IO::floatValue("Dyed")."', cutting='".IO::intValue("Cutting")."', sewing='".IO::intValue("Sewing")."', finishing='".IO::intValue("Finishing")."', packing='".IO::intValue("Packing")."', final_audit_date='".((IO::strValue("FinalAuditDate") == "") ? "0000-00-00" : IO::strValue("FinalAuditDate"))."', standard='".IO::floatValue("Standard")."', approved_sample='".IO::strValue("ApprovedSample")."', shipping_mark='".IO::strValue("ShippingMark")."', packing_check='".IO::strValue("PackingCheck")."', carton_size='$CartonSize', ship_qty='".IO::intValue("ShipQty")."', re_screen_qty='".IO::intValue("ReScreenQty")."', qa_comments='".IO::strValue("Comments")."', date_time=NOW( ) WHERE id='$Id'");
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
                        $SampleNo = IO::intValue("SampleNo".$i);
			$Nature   = IO::floatValue("Nature".$i);
			$Cap      = IO::strValue("Cap".$i);
			$Remarks  = IO::strValue("Remarks".$i);
			$Picture  = IO::getFileName($_FILES["Picture".$i]['name']);

		
			if ($DefectId > 0)
			{
				$sSQL  = "UPDATE tbl_qa_report_defects SET code_id='$Code', sample_no='$SampleNo', defects='$Defects', area_id='$Area', nature='$Nature', cap='$Cap', remarks='$Remarks' WHERE id='$DefectId'";
				$bFlag = $objDb->execute($sSQL);
			}

			else
			{
				$DefectId = getNextId("tbl_qa_report_defects");

				$sSQL  = ("INSERT INTO tbl_qa_report_defects (id, audit_id, sample_no, code_id, defects, area_id, nature, cap, remarks, picture, date_time) VALUES ('$DefectId', '$Id', '$SampleNo', '$Code', '$Defects', '$Area', '$Nature', '$Cap', '$Remarks', '$Picture', NOW())");
				$bFlag = $objDb->execute($sSQL);
			}


			if ($bFlag == false)
				break;
		}
	}

	if ($bFlag == true)
	{
		$sSQL = "SELECT * FROM tbl_bbg_reports WHERE audit_id='$Id'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$sSQL = "INSERT INTO tbl_bbg_reports SET audit_id='$Id', ";

		else
			$sSQL = "UPDATE tbl_bbg_reports SET ";

            $sSQL .= ("trim_access                 = '".IO::strValue("TrimAccssDetail")."',
					   test_report                 = '".IO::strValue("TestReport")."',
					   label_method                = '".IO::strValue("LabelMethod")."',
					   scw_detail                  = '".IO::strValue("SCWDetail")."',
					   dw_test_record              = '".IO::strValue("DWTestRecord")."',
					   wrap                        = '".IO::strValue("Wrap")."',
					   cspo_detail                 = '".IO::strValue("CSPoDetail")."',
					   pull_test_report            = '".IO::strValue("PullTestReport")."',
					   fty_approve                 = '".IO::strValue("FTYApproved")."',
					   cq_detail                   = '".IO::strValue("CQDetail")."',
					   wash_test_record            = '".IO::strValue("WashTestRecord")."',
					   acc_lab                     = '".IO::strValue("AccreditedLab")."',
					   approved_sample             = '".IO::strValue("ApprovedSample")."',
					   fabric_weight               = '".IO::strValue("FabricWeight")."',
					   pp_meeting_record           = '".IO::strValue("PPMeetingRecord")."',
					   actual_packing_list         = '".IO::strValue("ActualPackingList")."',
					   carton_drop_test_record     = '".IO::strValue("CartonDropTestRecord")."',
					   carton_mdw                  = '".IO::strValue("CartonMdw")."',
					   shipped_sbdc_ratio          = '".IO::strValue("ShippedSbdcRatio")."',
					   needle_detect_record        = '".IO::strValue("NeedleDetectRecord")."',
					   packing_method              = '".IO::strValue("PackingMethod")."',
					   cqnas_details               = '".IO::strValue("CqnasDetails")."',
					   packaging_trims             = '".IO::strValue("PackagingTrims")."',
					   measurement_result          = '".IO::strValue("MeasurementResult")."',
					   measurement_overall_remarks = '".IO::strValue("MeasurementComments")."',
					   measurement_wash_status     = '".IO::strValue("MeasurementWashStatus")."'");


		if ($objDb->getCount( ) == 1)
			$sSQL .= " WHERE audit_id='$Id'";

		$bFlag = $objDb->execute($sSQL);
	}


        if ($bFlag == true)
	{
		$iPoCount     = IO::intValue("PoCount");
		$sStatusIds   = "";

		for ($m = 0; $m < $iPoCount; $m ++)
		{
                        $StatusId            = (IO::intValue("StatusId".$m) == ''?0:IO::intValue("StatusId".$m));
                        $PoNo                = (IO::intValue("PoNo".$m) == ''?0:IO::intValue("PoNo".$m));
                        $Color               = (IO::strValue("Color".$m) == ''?'':IO::strValue("Color".$m));
			$Cutting             = (IO::intValue("Cutting".$m) == ''?0:IO::intValue("Cutting".$m));
			$Printing            = (IO::intValue("Printing".$m) == ''?0:IO::intValue("Printing".$m));
			$Sewing              = (IO::intValue("Sewing".$m) == ''?0:IO::intValue("Sewing".$m));
			$Washing             = (IO::intValue("Washing".$m) == ''?0:IO::intValue("Washing".$m));
			$Pressing            = (IO::intValue("Pressing".$m) == ''?0:IO::intValue("Pressing".$m));
			$Packaging           = (IO::intValue("Packaging".$m) == ''?0:IO::intValue("Packaging".$m));
                        $Packing             = (IO::intValue("Packing".$m) == ''?0:IO::intValue("Packing".$m));
                        $Sample_size         = (IO::intValue("Sample_size".$m) == ''?0:IO::intValue("Sample_size".$m));
                        $Semi_garment_bundle = (IO::intValue("Semi_garment_bundle".$m) == ''?0:IO::intValue("Semi_garment_bundle".$m));
                        $DRemarks            = IO::strValue("DRemarks".$m);

			if ($PoNo > 0)
			{
				if ($StatusId > 0)
					$sSQL  = "UPDATE tbl_bbg_status SET color='$Color', cutting='$Cutting', printing='$Printing', sewing='$Sewing', washing='$Washing', pressing='$Pressing', packaging='$Packaging', packing='$Packing', sample_size='$Sample_size', semi_garment_bundle='$Semi_garment_bundle', remarks='$DRemarks' WHERE id='$StatusId'";

				else
				{
					$StatusId = getNextId("tbl_bbg_status");

					$sSQL  = ("INSERT INTO tbl_bbg_status (id, po_id, color, audit_id, cutting, printing, sewing, washing, pressing, packaging, packing, sample_size, semi_garment_bundle, remarks) VALUES ('$StatusId', '$PoNo', '$Color', '$Id', '$Cutting', '$Printing', '$Sewing', '$Washing', '$Pressing', '$Packaging', '$Packing','$Sample_size', '$Semi_garment_bundle', '$DRemarks')");
				}

				$bFlag = $objDb->execute($sSQL);
			}

			else
			{
				if ($StatusId > 0)
				{
					$sSQL  = "DELETE FROM tbl_bbg_status WHERE id='$StatusId'";
					$bFlag = $objDb->execute($sSQL);
				}
			}

			if ($StatusId > 0)
			{
				if ($sStatusIds != "")
					$sStatusIds .= ",";

				$sStatusIds .= $StatusId;
			}

			if ($bFlag == false)
				break;
		}

		if ($bFlag == true && $sStatusIds != "")
		{
			$sSQL  = "DELETE FROM tbl_bbg_status WHERE id NOT IN ($sStatusIds) AND audit_id='$Id'";
			$bFlag = $objDb->execute($sSQL);
		}

		if ($bFlag == true && $iPoCount == 0)
		{
			$sSQL  = "DELETE FROM tbl_bbg_status WHERE audit_id='$Id'";
			$bFlag = $objDb->execute($sSQL);
		}
	}

    if ($bFlag == true)
	{
		$sSQL  = "DELETE FROM tbl_qa_report_samples WHERE audit_id='$Id'";
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


						$sSQL  = "INSERT INTO tbl_qa_report_sample_specs (sample_id, point_id, findings) VALUES ('{$iSamples[($j - 1)]}', '$iPoint', '$sFindings') ON DUPLICATE KEY UPDATE point_id='$iPoint', findings='$sFindings'";
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

	$iDefectiveGmts = getDbValue("COUNT(DISTINCT(sample_no))", "tbl_qa_report_defects", "audit_id='$Id' AND nature>'0'");
	$fDhu           = round((($iDefectiveGmts / $TotalGmts) * 100), 2);
?>