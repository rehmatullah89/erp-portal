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

	$sSQL  = ("UPDATE tbl_qa_reports SET po_id='$PoId', group_id='".IO::intValue('Group')."', additional_pos='$sAdditionalPos', audit_stage='".IO::strValue("AuditStage")."', audit_status='".IO::strValue("AuditStatus")."', audit_result='".IO::strValue("AuditResult")."', batch_size='".IO::strValue("BatchSize")."', packed_percent='".IO::floatValue("PackedPercent")."', colors='".IO::strValue("Colors")."', sizes='".@implode(",", IO::getArray('Sizes'))."', description='".IO::strValue("Description")."', total_gmts='$TotalGmts', max_defects='$MaxDefects', knitted='".IO::floatValue("Knitted")."', dyed='".IO::floatValue("Dyed")."', cutting='".IO::intValue("Cutting")."', sewing='".IO::intValue("Sewing")."', finishing='".IO::intValue("Finishing")."', packing='".IO::intValue("Packing")."', final_audit_date='".((IO::strValue("FinalAuditDate") == "") ? "0000-00-00" : IO::strValue("FinalAuditDate"))."', ship_qty='".IO::intValue("BatchSize")."', re_screen_qty='".IO::intValue("ReScreenQty")."', cartons_shipped='".IO::intValue("CartonsShipped")."', qa_comments='".IO::strValue("Comments")."', date_time=NOW( ) WHERE id='$Id'");
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
			$Picture  = IO::getFileName($_FILES["Picture".$i]['name']);
			$Defects = (($Defects <= 0) ? 1 : $Defects);


			if ($DefectId > 0)
			{
				$sSQL  = "UPDATE tbl_qa_report_defects SET code_id='$Code', sample_no='$SampleNo', defects='$Defects', area_id='$Area', nature='$Nature' WHERE id='$DefectId'";
				$bFlag = $objDb->execute($sSQL);
			}	

			else
			{
				$DefectId = getNextId("tbl_qa_report_defects");

				$sSQL  = ("INSERT INTO tbl_qa_report_defects (id, audit_id, code_id, defects, area_id, sample_no, nature, picture, date_time) VALUES ('$DefectId', '$Id', '$Code', '$Defects', '$Area', '$SampleNo', '$Nature', '$Picture', NOW())");
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == false)
				break;
		}
	}

	if ($bFlag == true)
	{
		$sSQL = "SELECT * FROM tbl_ms_qa_reports WHERE audit_id='$Id'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$sSQL = "INSERT INTO tbl_ms_qa_reports SET audit_id='$Id', ";

		else
			$sSQL = "UPDATE tbl_ms_qa_reports SET ";

		$sSQL .= ("series         = '".IO::strValue("Series")."',
				   department     = '".IO::strValue("Department")."',
				   big_products   = '".IO::intValue("BigProducts")."',
				   big_size       = '".IO::strValue("BigSize")."',
				   small_products = '".IO::intValue("SmallProducts")."',
				   small_size     = '".IO::strValue("SmallSize")."',
				   action         = '".IO::strValue("Action")."'");

		if ($objDb->getCount( ) == 1)
			$sSQL .= " WHERE audit_id='$Id'";

		$bFlag = $objDb->execute($sSQL);
	}

	
    $iDefectiveGmts = getDbValue("COUNT(DISTINCT(sample_no))", "tbl_qa_report_defects", "audit_id='$Id' AND nature>'0'");
	$fDhu           = round((($iDefectiveGmts / $TotalGmts) * 100), 2);
?>