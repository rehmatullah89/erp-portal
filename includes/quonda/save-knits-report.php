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

	$fDhu = round((($iTotalDefects / $TotalGmts) * 100), 2);
?>