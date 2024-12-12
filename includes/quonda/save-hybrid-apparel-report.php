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

	$CartonSize = (IO::strValue("Length")."x".IO::strValue("Width")."x".IO::strValue("Height")."x".IO::strValue("Unit"));

	$sSQL  = ("UPDATE tbl_qa_reports SET po_id='$PoId', group_id='".IO::intValue('Group')."', additional_pos='$sAdditionalPos', audit_stage='".IO::strValue("AuditStage")."', audit_status='".IO::strValue("AuditStatus")."', audit_result='".IO::strValue("AuditResult")."', audit_type='".IO::strValue("AuditType")."', colors='".IO::strValue("Colors")."', sizes='".@implode(",", IO::getArray('Sizes'))."', ship_qty='".IO::intValue("ShipQty")."', re_screen_qty='".IO::intValue("ReScreenQty")."', cartons_required='".IO::intValue("CartonsRequired")."', cartons_shipped='".IO::intValue("CartonsShipped")."', total_gmts='$TotalGmts', max_defects='$MaxDefects', total_cartons='".IO::floatValue("TotalCartons")."', rejected_cartons='".IO::floatValue("CartonsRejected")."', defective_percent='".IO::floatValue("PercentDecfective")."', knitted='".IO::floatValue("Knitted")."', dyed='".IO::floatValue("Dyed")."', cutting='".IO::intValue("Cutting")."', sewing='".IO::intValue("Sewing")."', finishing='".IO::intValue("Finishing")."', packing='".IO::intValue("Packing")."', final_audit_date='".((IO::strValue("FinalAuditDate") == "") ? "0000-00-00" : IO::strValue("FinalAuditDate"))."', standard='".IO::floatValue("Standard")."', approved_sample='".IO::strValue("ApprovedSample")."', shipping_mark='".IO::strValue("ShippingMark")."', packing_check='".IO::strValue("PackingCheck")."', carton_size='$CartonSize', qa_comments='".IO::strValue("Comments")."', date_time=NOW( ) WHERE id='$Id'");
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
			$Cap      = IO::strValue("Cap".$i);
			$Picture  = IO::getFileName($_FILES["Picture".$i]['name']);


			if ($DefectId > 0)
			{
				$sSQL  = "UPDATE tbl_qa_report_defects SET code_id='$Code', defects='$Defects', area_id='$Area', nature='$Nature', cap='$Cap' WHERE id='$DefectId'";
				$bFlag = $objDb->execute($sSQL);
			}	

			else
			{
				$DefectId = getNextId("tbl_qa_report_defects");

				$sSQL  = ("INSERT INTO tbl_qa_report_defects (id, audit_id, code_id, defects, area_id, nature, cap, picture, date_time) VALUES ('$DefectId', '$Id', '$Code', '$Defects', '$Area', '$Nature', '$Cap', '{$DefectId}-{$Picture}', NOW())");
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == false)
				break;
		}
	}

	if ($bFlag == true)
	{
		$sSQL = "SELECT * FROM tbl_hybrid_apparel_reports WHERE audit_id='$Id'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$sSQL = "INSERT INTO tbl_hybrid_apparel_reports SET audit_id='$Id', ";

		else
			$sSQL = "UPDATE tbl_hybrid_apparel_reports SET ";
		
			$sSQL .= ("total_ctns = '".IO::intValue("TotalCtns")."',
				   fabric     = '".IO::strValue("Fabric")."',
				   content    = '".IO::strValue("Content")."',
				   weight     = '".IO::strValue("Weight")."',
				   rib        = '".IO::strValue("Rib")."',
				   label_size = '".IO::strValue("LabelSize")."',
				   thread     = '".IO::strValue("Thread")."',
				   measurement_result  = '".IO::strValue("MeasurementResult")."',
				   measurement_remarks = '".IO::strValue("MeasurementRemarks")."'");

		if ($objDb->getCount( ) == 1)
			$sSQL .= " WHERE audit_id='$Id'";

		$bFlag = $objDb->execute($sSQL);
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