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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	$AuditCode  = IO::strValue('AuditCode');
	$iAuditCode = intval(substr($AuditCode, 1));


	$aResponse = array( );


	if ($iAuditCode == 0 || strlen($AuditCode) == 0 || $AuditCode{0} != "S")
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid Audit Code";
	}

	else
	{
		$sSQL = "SELECT *,
						(SELECT order_no FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _Po
				 FROM tbl_qa_reports
				 WHERE id='$iAuditCode'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "No QA Report Found!";
		}

		else
		{
			$iPoId                  = $objDb->getField(0, "po_id");
			$sPo                    = $objDb->getField(0, "_Po");
			$sAdditionalPos         = $objDb->getField(0, "additional_pos");
			$iStyle                 = $objDb->getField(0, "style_id");
			$sAuditStage            = $objDb->getField(0, "audit_stage");
			$sDyeLotNo              = $objDb->getField(0, "dye_lot_no");
			$sAcceptablePointsWoven = $objDb->getField(0, "acceptable_points_woven");
			$sInspectionType        = $objDb->getField(0, "inspection_type");
			$sCutableFabricWidth    = $objDb->getField(0, "cutable_fabric_width");
			$sStockStatus           = $objDb->getField(0, "stock_status");
			$iRollsInspected        = $objDb->getField(0, "rolls_inspected");
			$iNoOfRolls             = $objDb->getField(0, "no_of_rolls");
			$iFabricWidth           = $objDb->getField(0, "fabric_width");
			$sAuditResult           = $objDb->getField(0, "audit_result");
			$iShipQty               = $objDb->getField(0, "ship_qty");
			$iReScreenQty           = $objDb->getField(0, "re_screen_qty");
			$sComments              = $objDb->getField(0, "qa_comments");


			$sSQL = "SELECT * FROM tbl_gf_inspection_checklist WHERE audit_id='$iAuditCode'";
			$objDb->query($sSQL);

			$sColorMatch = $objDb->getField(0, "color_match");
			$sShading    = $objDb->getField(0, "shading");
			$sHandFeel   = $objDb->getField(0, "hand_feel");
			$sLabTesting = $objDb->getField(0, "lab_testing");


			$sSQL = "SELECT order_no FROM tbl_po WHERE id IN ($sAdditionalPos) ORDER BY order_no";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
				$sPo .= (",".$objDb->getField($i, 0));


			if ($iStyle > 0)
				$sSQL = "SELECT style FROM tbl_styles WHERE id='$iStyle'";

			else
				$sSQL = "SELECT style FROM tbl_styles WHERE id=(SELECT style_id FROM tbl_po_colors WHERE po_id='$iPoId' LIMIT 1)";

			$objDb->query($sSQL);

			$sStyle = $objDb->getField(0, 0);



			$sAuditStage = getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'");

			switch ($sAuditResult)
			{
				case "P" : $sAuditResult = "Pass"; break;
				case "F" : $sAuditResult = "Fail"; break;
				case "H" : $sAuditResult = "Hold"; break;
				case "A" : $sAuditResult = "Grade A"; break;
				case "B" : $sAuditResult = "Grade B"; break;
				case "C" : $sAuditResult = "Grade C"; break;
			}

			$sComments = (($sComments == "") ? "No comments given" : $sComments);


			$aResponse['Status'] = "OK";
			$aResponse['Report'] = "{$sPo}|-|{$sStyle}|-|{$sAuditStage}|-|{$sInspectionType}|-|{$sDyeLotNo} |-|{$sAcceptablePointsWoven} |-|{$sCutableFabricWidth} |-|{$sStockStatus} |-|{$iRollsInspected} |-|";


			$sSQL = "SELECT * FROM tbl_gf_rolls_info WHERE audit_id='$iAuditCode' ORDER BY id";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < 5; $i ++)
			{
				$sRollNo   = "";
				$sRef_1    = "";
				$sGiven_1  = "";
				$sActual_1 = "";
				$sRef_2    = "";
				$sGiven_2  = "";
				$sActual_2 = "";
				$sRef_3    = "";
				$sGiven_3  = "";
				$sActual_3 = "";

				if ($i < $iCount)
				{
					$sRollNo   = $objDb->getField($i, "roll_no");
					$sRef_1    = $objDb->getField($i, "ref_1");
					$sGiven_1  = $objDb->getField($i, "given_1");
					$sActual_1 = $objDb->getField($i, "actual_1");
					$sRef_2    = $objDb->getField($i, "ref_2");
					$sGiven_2  = $objDb->getField($i, "given_2");
					$sActual_2 = $objDb->getField($i, "actual_2");
					$sRef_3    = $objDb->getField($i, "ref_3");
					$sGiven_3  = $objDb->getField($i, "given_3");
					$sActual_3 = $objDb->getField($i, "actual_3");
				}

				$aResponse['Report'] .= "{$sRollNo} |-|{$sRef_1} |-|{$sGiven_1} |-|{$sActual_1} |-|{$sRef_2} |-|{$sGiven_2} |-|{$sActual_2} |-|{$sRef_3} |-|{$sGiven_3} |-|{$sActual_3} |-|";
			}

       		$aResponse['Report'] .= "{$sColorMatch} |-|{$sShading} |-|{$sHandFeel} |-|{$sLabTesting} |-|{$iFabricWidth} |-|{$sAuditResult} |-|{$iShipQty} |-|{$iReScreenQty} |-|{$iNoOfRolls} |-|{$sComments}";
		}
	}


	print @json_encode($aResponse);

/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = @json_encode($aResponse);

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/

	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>