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

	$iTotalDefects = 0;
	$CartonSize    = (IO::strValue("Length")."x".IO::strValue("Width")."x".IO::strValue("Height")."x".IO::strValue("Unit"));
        
	$Comments = IO::strValue("Comments");
	
	if ($Comments == "")
		$Comments = "N/A";

        $sAuditResult   = IO::strValue("AuditResult");
        $sPublished     = IO::strValue("Publish");
        
        if($sAuditResult == 'H')
            $sPublished = 'N';
        
	$sSQL  = ("UPDATE tbl_qa_reports SET audit_stage='".IO::strValue("AuditStage")."', audit_status='".IO::strValue("AuditStatus")."', audit_result='".IO::strValue("AuditResult")."', audit_type='".IO::strValue("AuditType")."', colors='".IO::strValue("Colors")."', sizes='".@implode(",", IO::getArray('Sizes'))."', ship_qty='".IO::intValue("ShipQty")."', re_screen_qty='".IO::intValue("ReScreenQty")."', cartons_required='".IO::intValue("CartonsRequired")."', cartons_shipped='".IO::intValue("CartonsShipped")."', total_gmts='$TotalGmts', defective_gmts='".IO::floatValue("GmtsDefective")."', max_defects='$MaxDefects', total_cartons='".IO::floatValue("TotalCartons")."', rejected_cartons='".IO::floatValue("CartonsRejected")."', defective_percent='".IO::floatValue("PercentDecfective")."', knitted='".IO::floatValue("Knitted")."', dyed='".IO::floatValue("Dyed")."', cutting='".IO::intValue("Cutting")."', sewing='".IO::intValue("Sewing")."', finishing='".IO::intValue("Finishing")."', packing='".IO::intValue("Packing")."', final_audit_date='".((IO::strValue("FinalAuditDate") == "") ? "0000-00-00" : IO::strValue("FinalAuditDate"))."', standard='".IO::floatValue("Standard")."', approved_sample='".IO::strValue("ApprovedSample")."', shipping_mark='".IO::strValue("ShippingMark")."', packing_check='".IO::strValue("PackingCheck")."', carton_size='$CartonSize', qa_comments='$Comments', published='$sPublished', date_time=NOW( ) WHERE id='$Id'");
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
			$Defects = (($Defects <= 0) ? 1 : $Defects);


			if ($Nature > 0)
				$iTotalDefects += $Defects;


			if ($DefectId > 0)
			{
				$sSQL  = "UPDATE tbl_qa_report_defects SET code_id='$Code', defects='$Defects', area_id='$Area', nature='$Nature', cap='$Cap' WHERE id='$DefectId'";
				$bFlag = $objDb->execute($sSQL);
			}	

			else
			{
				$DefectId = getNextId("tbl_qa_report_defects");

				$sSQL  = ("INSERT INTO tbl_qa_report_defects (id, audit_id, code_id, defects, area_id, nature, cap, picture, date_time) VALUES ('$DefectId', '$Id', '$Code', '$Defects', '$Area', '$Nature', '$Cap', '$Picture', NOW( ))");
				$bFlag = $objDb->execute($sSQL);
			}


			if ($bFlag == false)
				break;
		}
	}

	if ($bFlag == true)
	{
		$sSQL = "SELECT * FROM tbl_mgf_reports WHERE audit_id='$Id'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$sSQL = "INSERT INTO tbl_mgf_reports SET audit_id='$Id', ";

		else
			$sSQL = "UPDATE tbl_mgf_reports SET ";

		$sSQL .= ("vpo_no                 = '".IO::strValue("VpoNo")."',
				   reinspection           = '".IO::strValue("ReInspection")."',
				   garment_test           = '".IO::strValue("GarmentTest")."',
				   shade_band             = '".IO::strValue("ShadeBand")."',
				   qa_file                = '".IO::strValue("QaFile")."',
				   fabric_test            = '".IO::strValue("FabricTest")."',
				   pp_meeting             = '".IO::strValue("PpMeeting")."',
				   fitting_torque         = '".IO::strValue("FittingTorque")."',
				   color_check            = '".IO::strValue("ColorCheck")."',
				   accessories_check      = '".IO::strValue("AccessoriesCheck")."',
				   measurement_check      = '".IO::strValue("MeasurementCheck")."',
				   cap_others             = '".IO::strValue("CapOthers")."',
				   carton_no              = '".IO::strValue("CartonNo")."',
				   measurement_sample_qty = '".IO::intValue("MeasurementSampleQty")."',
				   measurement_defect_qty = '".IO::intValue("MeasurementDefectQty")."'");

		if ($objDb->getCount( ) == 1)
			$sSQL .= " WHERE audit_id='$Id'";

		$bFlag = $objDb->execute($sSQL);
	}
	
	if ($bFlag == true)
	{
		//$iDefective = (int)getDbValue("COUNT(DISTINCT(sample_no))", "tbl_qa_report_defects", "audit_id='$Id' AND nature>'0'");
		$iDefective = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id' AND nature>'0'");
		
		if ($iDefective > $TotalGmts)
			$iDefective = $TotalGmts;
		
		$sSQL  = "UPDATE tbl_qa_reports SET defective_gmts='$iDefective' WHERE id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}
	

	$fDhu = round((($iTotalDefects / $TotalGmts) * 100), 2);
?>