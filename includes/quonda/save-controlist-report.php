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

	
	$sSQL  = ("UPDATE tbl_qa_reports SET po_id='$PoId', audit_quantity='".IO::intValue('AuditQuantity')."', group_id='".IO::intValue('Group')."', additional_pos='$sAdditionalPos', audit_stage='".IO::strValue("AuditStage")."', audit_status='".IO::strValue("AuditStatus")."', audit_result='".IO::strValue("AuditResult")."', audit_type='".IO::strValue("AuditType")."', colors='".IO::strValue("Colors")."', bundle='".IO::strValue("Bundle")."', sizes='".@implode(",", IO::getArray('Sizes'))."', ship_qty='".IO::intValue("ShipQty")."', re_screen_qty='".IO::intValue("ReScreenQty")."', cartons_required='".IO::intValue("CartonsRequired")."', cartons_shipped='".IO::intValue("CartonsShipped")."', total_gmts='$TotalGmts', max_defects='$MaxDefects', total_cartons='".IO::floatValue("TotalCartons")."', rejected_cartons='".IO::floatValue("CartonsRejected")."', defective_percent='".IO::floatValue("PercentDecfective")."', knitted='".IO::floatValue("Knitted")."', dyed='".IO::floatValue("Dyed")."', cutting='".IO::intValue("Cutting")."', sewing='".IO::intValue("Sewing")."', finishing='".IO::intValue("Finishing")."', packing='".IO::intValue("Packing")."', final_audit_date='".((IO::strValue("FinalAuditDate") == "") ? "0000-00-00" : IO::strValue("FinalAuditDate"))."', standard='".IO::floatValue("Standard")."', approved_sample='".IO::strValue("ApprovedSample")."', shipping_mark='".IO::strValue("ShippingMark")."', packing_check='".IO::strValue("PackingCheck")."', approved_trims='".IO::strValue("ApprovedTrims")."', shade_band='".IO::strValue("ShadeBand")."', emb_approval='".IO::strValue("EmbApproval")."', gsm_weight='".IO::strValue("GsmWeight")."', carton_size='$CartonSize', cutting_lot_no='".IO::strValue("LotNo")."', qa_comments='".IO::strValue("Comments")."', date_time=NOW( ) WHERE id='$Id'");
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
			$SampleNo = IO::intValue("SampleNo".$i);
			$Picture  = IO::getFileName($_FILES["Picture".$i]['name']);


				if ($DefectId > 0)
				{
					$sSQL  = "UPDATE tbl_qa_report_defects SET code_id='$Code', defects='$Defects', area_id='$Area', nature='$Nature', remarks='$Remarks', sample_no='$SampleNo' WHERE id='$DefectId'";
					$bFlag = $objDb->execute($sSQL);
				}

				else
				{
					$DefectId = getNextId("tbl_qa_report_defects");

					$sSQL  = ("INSERT INTO tbl_qa_report_defects (id, audit_id, code_id, defects, area_id, nature, remarks, sample_no, picture, date_time) VALUES ('$DefectId', '$Id', '$Code', '$Defects', '$Area', '$Nature', '$Remarks', '$SampleNo', '$Picture', NOW())");
					$bFlag = $objDb->execute($sSQL);
				}

			if ($bFlag == false)
				break;
		}
	}
	
	if ($bFlag == true)
	{
		$sSQL  = "DELETE FROM tbl_qa_report_cartons WHERE audit_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}
	
	if ($bFlag == true)
	{
		$sColors = @explode(",", IO::strValue("Colors"));
		$iIndex  = 0;
		
		foreach ($sColors as $sColor)
		{
			$sCartonNos = @implode(",", IO::getArray("CartonNos{$iIndex}"));
			$sColor     = addslashes($sColor);
			
			$sSQL  = "INSERT INTO tbl_qa_report_cartons (audit_id, color, carton_nos) VALUES ('$Id', '$sColor', '$sCartonNos')";
			$bFlag = $objDb->execute($sSQL);
			
			if ($bFlag == false)
				break;
			
			$iIndex	++;
		}
	}

	
	$iDefectiveGmts = getDbValue("COUNT(DISTINCT(sample_no))", "tbl_qa_report_defects", "audit_id='$Id' AND nature>'0'");
	$fDhu           = round((($iDefectiveGmts / $TotalGmts) * 100), 2);
?>