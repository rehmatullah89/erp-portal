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

	$sSQL  = ("UPDATE tbl_qa_reports SET po_id='$PoId', audit_quantity='".IO::intValue('AuditQty')."', group_id='".IO::intValue('Group')."', additional_pos='$sAdditionalPos', audit_stage='".IO::strValue("AuditStage")."', audit_status='".IO::strValue("AuditStatus")."', audit_result='".IO::strValue("AuditResult")."', audit_type='".IO::strValue("AuditType")."', colors='".IO::strValue("Colors")."', bundle='".IO::strValue("Bundle")."', sizes='".@implode(",", IO::getArray('Sizes'))."', ship_qty='".IO::intValue("ShipQty")."', re_screen_qty='".IO::intValue("ReScreenQty")."', cartons_required='".IO::intValue("CartonsRequired")."', cartons_shipped='".IO::intValue("CartonsShipped")."', total_gmts='$TotalGmts', max_defects='$MaxDefects', total_cartons='".IO::floatValue("TotalCartons")."', rejected_cartons='".IO::floatValue("CartonsRejected")."', defective_percent='".IO::floatValue("PercentDecfective")."', knitted='".IO::floatValue("Knitted")."', dyed='".IO::floatValue("Dyed")."', cutting='".IO::intValue("Cutting")."', sewing='".IO::intValue("Sewing")."', finishing='".IO::intValue("Finishing")."', packing='".IO::intValue("Packing")."', final_audit_date='".((IO::strValue("FinalAuditDate") == "") ? "0000-00-00" : IO::strValue("FinalAuditDate"))."', standard='".IO::floatValue("Standard")."', approved_sample='".IO::strValue("ApprovedSample")."', shipping_mark='".IO::strValue("ShippingMark")."', packing_check='".IO::strValue("PackingCheck")."', approved_trims='".IO::strValue("ApprovedTrims")."', shade_band='".IO::strValue("ShadeBand")."', emb_approval='".IO::strValue("EmbApproval")."', gsm_weight='".IO::strValue("GsmWeight")."', carton_size='$CartonSize', cutting_lot_no='".IO::strValue("LotNo")."', qa_comments='".IO::strValue("Comments")."', date_time=NOW( ) WHERE id='$Id'");
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
			$Picture  = IO::getFileName($_FILES["Picture".$i]['name']);
			

			if ($DefectId > 0)
			{
				$sSQL  = "UPDATE tbl_qa_report_defects SET code_id='$Code', defects='$Defects', area_id='$Area', nature='$Nature', remarks='$Remarks' WHERE id='$DefectId'";
				$bFlag = $objDb->execute($sSQL);
			}	

			else
			{
				$DefectId = getNextId("tbl_qa_report_defects");

				$sSQL  = ("INSERT INTO tbl_qa_report_defects (id, audit_id, code_id, defects, area_id, nature, remarks, picture, date_time) VALUES ('$DefectId', '$Id', '$Code', '$Defects', '$Area', '$Nature', '$Remarks', '$Picture', NOW())");
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == false)
				break;
		}

                
                if ($bFlag == true )
		{
			$sSQL  = "DELETE FROM tbl_hybrid_link_reports WHERE audit_id='$Id'";
			$bFlag = $objDb->execute($sSQL);
                        
                        $AssortmentQty      = IO::intValue("AssortmentQty");
                        $AssortmentQtySize  = IO::intValue("AssortmentQtySize");
                        $SolidSizeQty       = IO::intValue("SolidSizeQty");
                        $IsFullPacket       = IO::strValue("IsFullPacket");
                        $ShipmentDate       = IO::strValue("ShipmentDate");
                        if($ShipmentDate == '')
                            $ShipmentDate = '0000-00-00';
                        $CartonNos          = implode(",", IO::getArray("cartonNos"));
                        $MeasurementPoints  = IO::intValue("TotalMeasurePoints");
                        $MeasureSampleSize  = IO::intValue("MeasurementSampleSize");
                        $TotalTolerance     = IO::intValue("TotalTolerance");
                        $PackingResult      = IO::strValue("PackingResult");
                        $ConformityResult   = IO::strValue("ConformityResult");
        
                        if ($bFlag == true )
                        {
                            $sSQL  = ("INSERT INTO tbl_hybrid_link_reports (audit_id, assortment_qty, assortment_qty_size, solid_size_qty, is_box_full, shipment_date, carton_nos, measurement_points, measurement_sample_size, total_tolerance_pts, packing_result, conformity_result) VALUES ('$Id', '$AssortmentQty', '$AssortmentQtySize', '$SolidSizeQty', '$IsFullPacket', '$ShipmentDate', '$CartonNos', '$MeasurementPoints', '$MeasureSampleSize', '$TotalTolerance', '$PackingResult', '$ConformityResult')");
                            $bFlag = $objDb->execute($sSQL);
                        }
		}
                
                if ($bFlag == true )
		{
                    $sSQL  = "DELETE FROM tbl_hybrid_link_report_check_details WHERE audit_id='$Id'";
                    $bFlag = $objDb->execute($sSQL);
                        
                    $sCheckList = getList("tbl_hybrid_link_report_checks", "id", "title");
                    foreach($sCheckList as $iCheck => $sCheck){
                        
                        $Result       = IO::strValue("CheckList".$iCheck);
                        $Remarks      = IO::strValue("CheckReason".$iCheck);
                            
                        if($Result != "")
                        {
                            $sSQL  = ("INSERT INTO tbl_hybrid_link_report_check_details (audit_id, check_id, result, remarks) VALUES ('$Id', '$iCheck', '$Result', '$Remarks')");
                            $bFlag = $objDb->execute($sSQL);
                        }
                        
                        if($bFlag == false)
                            break;
                    }
                }
                
	}


	$iDefectiveGmts = getDbValue("COUNT(DISTINCT(sample_no))", "tbl_qa_report_defects", "audit_id='$Id' AND nature>'0'");
	$fDhu           = round((($iDefectiveGmts / $TotalGmts) * 100), 2);
?>