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

	$sSQL  = ("UPDATE tbl_qa_reports SET po_id='$PoId', group_id='".IO::intValue('Group')."', additional_pos='$sAdditionalPos', audit_stage='".IO::strValue("AuditStage")."', audit_result='".IO::strValue("AuditResult")."', sizes='".@implode(",", IO::getArray('Sizes'))."', total_gmts='$TotalGmts', max_defects='$MaxDefects', knitted='".IO::floatValue("Knitted")."', dyed='".IO::floatValue("Dyed")."', cutting='".IO::intValue("Cutting")."', sewing='".IO::intValue("Sewing")."', finishing='".IO::intValue("Finishing")."', packing='".IO::intValue("Packing")."', final_audit_date='".((IO::strValue("FinalAuditDate") == "") ? "0000-00-00" : IO::strValue("FinalAuditDate"))."', qa_comments='".IO::strValue("Comments")."', date_time=NOW( ) WHERE id='$Id'");
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
            $Picture  = IO::getFileName($_FILES["Picture".$i]['name']);
			$Defects = (($Defects <= 0) ? 1 : $Defects);


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
		$sSQL = "DELETE FROM tbl_jako_audits WHERE audit_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		for ($i = 0; $i < 8; $i ++)
		{
			if (IO::strValue("StyleColor".$i) != "")
			{
				$sSQL  = "INSERT INTO tbl_jako_audits (id, audit_id, style_color, design, main_fab, trims, access, logos, color, tuv_test) VALUES ('$i', '$Id', '".IO::strValue("StyleColor".$i)."', '".IO::strValue("Design".$i)."', '".IO::strValue("MainFab".$i)."', '".IO::strValue("Trims".$i)."', '".IO::strValue("Access".$i)."', '".IO::strValue("Logos".$i)."', '".IO::strValue("Color".$i)."', '".IO::strValue("TuvTest".$i)."')";
				$bFlag = $objDb->execute($sSQL);

				if ($bFlag == false)
					break;
			}
		}
	}

	if ($bFlag == true)
	{
		$sSQL = "SELECT * FROM tbl_jako_packing WHERE audit_id='$Id'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$sSQL = "INSERT INTO tbl_jako_packing SET audit_id='$Id', ";

		else
			$sSQL = "UPDATE tbl_jako_packing SET ";


		$sSQL .= ("carton     = '".IO::strValue("Carton")."',
				   polybag    = '".IO::strValue("Polybag")."',
				   package    = '".IO::strValue("Package")."',
				   hangtag    = '".IO::strValue("HangTag")."',
				   size_label = '".IO::strValue("SizeLabel")."',
				   care_label = '".IO::strValue("CareLabel")."',
				   prod_label = '".IO::strValue("ProdLabel")."'");

		if ($objDb->getCount( ) == 1)
			$sSQL .= " WHERE audit_id='$Id'";

		$bFlag = $objDb->execute($sSQL);
	}


	if ($bFlag == true)
	{
		$sSQL = "SELECT * FROM tbl_jako_qa_reports WHERE audit_id='$Id'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$sSQL = "INSERT INTO tbl_jako_qa_reports SET audit_id='$Id', ";

		else
			$sSQL = "UPDATE tbl_jako_qa_reports SET ";

		$sSQL .= ("eta          = '".IO::strValue("Eta")."',
				   we           = '".IO::strValue("We")."',
				   wash_off     = '".IO::floatValue("WashOff")."',
				   wash_in      = '".IO::floatValue("WashIn")."',
				   measure_off  = '".IO::floatValue("MeasureOff")."',
				   measure_in   = '".IO::floatValue("MeasureIn")."',
				   pcs_measured = '".IO::intValue("PcsMeasured")."'");

		if ($objDb->getCount( ) == 1)
			$sSQL .= " WHERE audit_id='$Id'";

		$bFlag = $objDb->execute($sSQL);
	}


	$iDefectiveGmts = getDbValue("COUNT(DISTINCT(sample_no))", "tbl_qa_report_defects", "audit_id='$Id' AND nature>'0'");
	$fDhu           = round((($iDefectiveGmts / $TotalGmts) * 100), 2);
?>