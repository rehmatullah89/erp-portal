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

	$sWorkingNo = IO::strValue("WorkingNo");

	if ($sWorkingNo == "")
		$sWorkingNo = getDbValue("style", "tbl_styles", "id='$Style'");


	$sSQL  = ("UPDATE tbl_qa_reports SET po_id='$PoId', group_id='".IO::intValue('Group')."', additional_pos='$sAdditionalPos', audit_stage='".IO::strValue("AuditStage")."', audit_status='".IO::strValue("AuditStatus")."', audit_result='".IO::strValue("AuditResult")."', audit_type='".IO::strValue("AuditType")."', colors='".IO::strValue("Colors")."', sizes='".@implode(",", IO::getArray('Sizes'))."', total_gmts='$TotalGmts', beautiful_products='".IO::intValue("BeautifulProducts")."', ship_qty='".IO::intValue("ShipQty")."', re_screen_qty='".IO::intValue("ReScreenQty")."', knitted='".IO::floatValue("Knitted")."', dyed='".IO::floatValue("Dyed")."', cutting='".IO::intValue("Cutting")."', sewing='".IO::intValue("Sewing")."', finishing='".IO::intValue("Finishing")."', packing='".IO::intValue("Packing")."', final_audit_date='".((IO::strValue("FinalAuditDate") == "") ? "0000-00-00" : IO::strValue("FinalAuditDate"))."', standard='1.5', shipping_mark='".IO::strValue("ShippingMark")."', qa_comments='".IO::strValue("Comments")."', date_time=NOW( ) WHERE id='$Id'");
	$bFlag = $objDb->execute($sSQL);

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_ar_inspection_checklist WHERE audit_id='$Id'";
		$objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL  = ("INSERT INTO tbl_ar_inspection_checklist (audit_id, model_name, working_no, fabric_approval, counter_sample_appr, garment_washing_test, color_shade, appearance, handfeel, printing, embridery, fibre_content, country_of_origin, care_instruction, size_key, adi_comp, colour_size_qty, polybag, hangtag, ocl_upc, carton_no_checked)
													VALUES ('$Id', '".IO::strValue("ModelName")."', '".IO::strValue("WorkingNo")."', '".IO::strValue("FabricApproval")."', '".IO::strValue("CounterSampleAppr")."', '".IO::strValue("GarmentWashingTest")."', '".IO::strValue("ColorShade")."', '".IO::strValue("Appearance")."', '".IO::strValue("Handfeel")."', '".IO::strValue("Printing")."', '".IO::strValue("Embridery")."', '".IO::strValue("FibreContent")."', '".IO::strValue("CountryOfOrigin")."', '".IO::strValue("CareInstruction")."', '".IO::strValue("SizeKey")."', '".IO::strValue("AdiComp")."', '".IO::strValue("ColourSizeQty")."', '".IO::strValue("Polybag")."', '".IO::strValue("Hangtag")."', '".IO::strValue("OclUpc")."', '".IO::strValue("CartonNoChecked")."')");
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$iCount     = IO::intValue("Count");
		$sDefectIds = "";

		for ($i = 0; $i < $iCount; $i ++)
		{
			$DefectId = IO::intValue("DefectId".$i);
			$Code     = IO::intValue("Code".$i);
			$Defects  = IO::intValue("Defects".$i);
			$Area     = IO::intValue("Area".$i);
			$Nature   = IO::floatValue("Nature".$i);
            $Picture  = IO::getFileName($_FILES["Picture".$i]['name']);


			if ($Nature > 0)
				$iTotalDefects += $Defects;


			if ($DefectId > 0)
			{
				$sSQL  = "UPDATE tbl_qa_report_defects SET code_id='$Code', defects='$Defects', area_id='$Area', nature='$Nature', date_time=NOW() WHERE id='$DefectId'";
				$bFlag = $objDb->execute($sSQL);
			}

			else
			{
				$DefectId = getNextId("tbl_qa_report_defects");

				$sSQL  = ("INSERT INTO tbl_qa_report_defects (id, audit_id, code_id, defects, area_id, nature, picture, date_time) VALUES ('$DefectId', '$Id', '$Code', '$Defects', '$Area', '$Nature', $Picture, NOW())");
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == false)
				break;
		}
	}

	
	$iDefectiveGmts = getDbValue("COUNT(DISTINCT(sample_no))", "tbl_qa_report_defects", "audit_id='$Id' AND nature>'0'");
	$fDhu           = round((($iDefectiveGmts / $TotalGmts) * 100), 2);
?>