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

	$sSQL  = ("UPDATE tbl_qa_reports SET po_id='$PoId', group_id='".IO::intValue('Group')."', additional_pos='$sAdditionalPos', audit_stage='".IO::strValue("AuditStage")."', audit_status='".IO::strValue("AuditStatus")."', audit_result='".IO::strValue("AuditResult")."', audit_type='".IO::strValue("AuditType")."', total_gmts='$TotalGmts', ship_qty='".IO::intValue("ShipQty")."', re_screen_qty='".IO::intValue("ReScreenQty")."', knitted='".IO::floatValue("Knitted")."', dyed='".IO::floatValue("Dyed")."', cutting='".IO::intValue("Cutting")."', sewing='".IO::intValue("Sewing")."', finishing='".IO::intValue("Finishing")."', packing='".IO::intValue("Packing")."', final_audit_date='".((IO::strValue("FinalAuditDate") == "") ? "0000-00-00" : IO::strValue("FinalAuditDate"))."', approved_sample='".IO::strValue("ApprovedSample")."', approved_trims='".IO::strValue("ApprovedTrims")."', qa_comments='".(trim(IO::strValue("Comments")) == ""?'N/A':IO::strValue("Comments"))."', date_time=NOW( ) WHERE id='$Id'");
	$bFlag = $objDb->execute($sSQL);

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_arcadia_inspection_summary WHERE audit_id='$Id'";
		$objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$iPo                    = getDbValue("po_id", "tbl_qa_reports", "id='$Id'");
		$iPoQty                 = getDbValue("quantity", "tbl_po", "id='$iPo'");

		$iUnitsPackedQty        = IO::intValue("UnitsPackedQty");
		$iUnitsFinishedQty      = IO::intValue("UnitsFinishedQty");
		$iUnitsNotFinishedQty   = IO::intValue("UnitsNotFinishedQty");

		$fUnitsPackedPercent    = @round((($iUnitsPackedQty / $iPoQty) * 100), 2);
		$fUnitsFinishedPercent  = @round((($iUnitsFinishedQty / $iPoQty) * 100), 2);
		$fUnitsNotFinishedPercent= @round((($iUnitsNotFinishedQty / $iPoQty) * 100), 2);


		$sSQL  = ("INSERT INTO tbl_arcadia_inspection_summary SET audit_id                      = '$Id',
		                                                      style                             = '".IO::strValue("ProductStyle")."',
		                                                      style_remarks                     = '".IO::strValue("ProductStyleRemarks")."',
		                                                      colour                            = '".IO::strValue("ProductColour")."',
		                                                      colour_remarks                    = '".IO::strValue("ProductColourRemarks")."',
		                                                      assortment                        = '".IO::strValue("Assortment")."',
		                                                      assortment_remarks                = '".IO::strValue("AssortmentRemarks")."',
		                                                      fabric_weight                     = '".IO::strValue("FabricGauge")."',
		                                                      fabric_weight_remarks             = '".IO::strValue("FabricGaugeRemarks")."',
		                                                      lining                            = '".IO::strValue("Lining")."',
		                                                      lining_remarks                    = '".IO::strValue("LiningRemarks")."',
		                                                      labeling_main                     = '".IO::strValue("Labeling")."',
		                                                      labeling_main_remarks             = '".IO::strValue("LabelingRemarks")."',
		                                                      labeling_others                   = '".IO::strValue("LabelingOther")."',
		                                                      labeling_others_remarks           = '".IO::strValue("LabelingOtherRemarks")."',
		                                                      hangtag_others                    = '".IO::strValue("HangTag")."',
		                                                      hangtag_others_remarks            = '".IO::strValue("HangTagRemarks")."',
		                                                      price_ticket                      = '".IO::strValue("PriceTicket")."',
		                                                      price_ticket_remarks              = '".IO::strValue("PriceTicketRemarks")."',
		                                                      export_carton_packing             = '".IO::strValue("ExportCartonDimension")."',
		                                                      export_carton_packing_remarks     = '".IO::strValue("ExportCartonDimensionRemarks")."',
		                                                      ans_label                         = '".IO::strValue("AsnLabel")."',
		                                                      ans_label_remarks                 = '".IO::strValue("AsnLabelRemarks")."',
		                                                      product_packaging                 = '".IO::strValue("Packaging")."',
		                                                      product_packaging_remarks         = '".IO::strValue("PackagingRemarks")."',
		                                                      appearance                        = '".IO::strValue("InnerCartonAppearance")."',
		                                                      appearance_remarks                = '".IO::strValue("InnerCartonAppearanceRemarks")."',
		                                                      polybag_quality_size              = '".IO::strValue("PolybagQuality")."',
		                                                      polybag_quality_size_remarks      = '".IO::strValue("PolybagQualityRemarks")."',
		                                                      polybag_sticker                   = '".IO::strValue("PolybagSticker")."',
		                                                      polybag_sticker_remarks           = '".IO::strValue("PolybagStickerRemarks")."',
		                                                      hanger                            = '".IO::strValue("Hanger")."',
		                                                      hanger_remarks                    = '".IO::strValue("HangerRemarks")."',
		                                                      embroidery                        = '".IO::strValue("Embroidery")."',
		                                                      embroidery_remarks                = '".IO::strValue("EmbroideryRemarks")."',
		                                                      buttoning                         = '".IO::strValue("Buttoning")."',
		                                                      buttoning_remarks                 = '".IO::strValue("ButtoningRemarks")."',
		                                                      wash_effect                       = '".IO::strValue("WashEffect")."',
                                                                      wash_effect_remarks               = '".IO::strValue("WashEffectRemarks")."',
		                                                      dummy_fit                         = '".IO::strValue("FitDummy")."',
                                                                      dummy_fit_remarks                 = '".IO::strValue("FitDummyRemarks")."',
		                                                      product_safety                    = '".IO::strValue("PullTesting")."',
                                                                      remarks_1                         = '".IO::strValue("Remarks1")."',
		                                                      remarks_2                         = '".IO::strValue("Remarks2")."',
		                                                      remarks_3                         = '".IO::strValue("Remarks3")."',
		                                                      remarks_4                         = '".IO::strValue("Remarks4")."',
		                                                      carton_nos                        = '".@implode(",", array_filter(IO::getArray("CartonNos")))."', 
                                                                      measurement_result                = '".IO::strValue("MeasurementResult")."',
		                                                      measurement_overall_remarks       = '".IO::strValue("MeasurementComments")."',
		                                                      product_safety_remarks            = '".IO::strValue("PullTestingRemarks")."'");
		$bFlag = $objDb->execute($sSQL);
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
		$iDefective = (int)getDbValue("SUM(defects)", "tbl_qa_report_defects", "audit_id='$Id'");
		
		if ($iDefective > $TotalGmts)
			$iDefective = $TotalGmts;
		
		$fDhu = round((($iDefective / $TotalGmts) * 100), 2);
		
		
		$sSQL  = "UPDATE tbl_qa_reports SET defective_gmts='$iDefective' WHERE id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}
        
        if($bFlag == false)
        {
            echo $sSQL;exit;
        }
?>