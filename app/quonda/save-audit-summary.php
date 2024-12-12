<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree QUONDA App                                                                   **
	**  Version 3.0                                                                              **
	**                                                                                           **
	**  http://app.3-tree.com                                                                    **
	**                                                                                           **
	**  Copyright 2008-17 (C) Triple Tree                                                        **
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
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	$User      = IO::strValue('User');
	$AuditCode = IO::strValue("AuditCode");


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "" || $AuditCode == "" || $AuditCode{0} != "S")
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, status, guest FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else if ((int)getDbValue("COUNT(1)", "tbl_qa_reports", "audit_code='$AuditCode'") == 0)
			$aResponse["Message"] = "Invalid Request, The selected Audit Code has been Deleted.";

		else
		{
			$iUser  = $objDb->getField(0, "id");
			$sName  = $objDb->getField(0, "name");
			$sGuest = $objDb->getField(0, "guest");


			$iAuditCode = (int)substr($AuditCode, 1);
			$iReportId  = getDbValue("report_id", "tbl_qa_reports", "id='$iAuditCode'");


			$bFlag = $objDb->execute("BEGIN", true, $iUser, $sName);

			if ($iReportId == 20 || $iReportId == 23)
			{
				if (getDbValue("COUNT(1)", "tbl_kik_inspection_summary", "audit_id='$iAuditCode'") == 0)
				{
					$sSQL  = ("INSERT INTO tbl_kik_inspection_summary (audit_id) VALUES ('$iAuditCode')");
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}

				if ($bFlag == true)
				{
					$sSQL  = ("UPDATE tbl_kik_inspection_summary SET shipping_marks                = '".IO::strValue("ShippingMarks")."',
																	 shipping_marks_remarks        = '".IO::strValue("ShippingMarksRemarks")."',
																	 material_conformity           = '".IO::strValue("MaterialConformity")."',
																	 material_conformity_remarks   = '".IO::strValue("MaterialConformityRemarks")."',
																	 style                         = '".IO::strValue("ProductStyle")."',
																	 style_remarks                 = '".IO::strValue("ProductStyleRemarks")."',
																	 colour                        = '".IO::strValue("ProductColour")."',
																	 colour_remarks                = '".IO::strValue("ProductColourRemarks")."',
																	 export_carton_packing         = '".IO::strValue("ExportCartonPacking")."',
																	 export_carton_packing_remarks = '".IO::strValue("ExportCartonPackingRemarks")."',
																	 inner_carton_packing          = '".IO::strValue("InnerCartonPacking")."',
																	 inner_carton_packing_remarks  = '".IO::strValue("InnerCartonPackingRemarks")."',
																	 product_packaging             = '".IO::strValue("ProductPackaging")."',
																	 product_packaging_remarks     = '".IO::strValue("ProductPackagingRemarks")."',
																	 assortment                    = '".IO::strValue("Assortment")."',
																	 assortment_remarks            = '".IO::strValue("AssortmentRemarks")."',
																	 labeling                      = '".IO::strValue("Labeling")."',
																	 labeling_remarks              = '".IO::strValue("LabelingRemarks")."',
																	 markings                      = '".IO::strValue("Markings")."',
																	 markings_remarks              = '".IO::strValue("MarkingsRemarks")."',
																	 workmanship                   = '".IO::strValue("Workmanship")."',
																	 workmanship_remarks           = '".IO::strValue("WorkmanshipRemarks")."',
																	 appearance                    = '".IO::strValue("Appearance")."',
																	 appearance_remarks            = '".IO::strValue("AppearanceRemarks")."',
																	 function                      = '".IO::strValue("Function")."',
																	 function_remarks              = '".IO::strValue("FunctionRemarks")."',
																	 printed_materials             = '".IO::strValue("PrintedMaterials")."',
																	 printed_materials_remarks     = '".IO::strValue("PrintedMaterialsRemarks")."',
																	 finishing                     = '".IO::strValue("WorkmanshipFinishing")."',
																	 finishing_remarks             = '".IO::strValue("WorkmanshipFinishingRemarks")."',
																	 measurement                   = '".IO::strValue("Measurement")."',
																	 measurement_remarks           = '".IO::strValue("MeasurementRemarks")."',
																	 fabric_weight                 = '".IO::strValue("FabricWeight")."',
																	 fabric_weight_remarks         = '".IO::strValue("FabricWeightRemarks")."',
																	 calibrated_scales             = '".IO::strValue("CalibratedScales")."',
																	 calibrated_scales_remarks     = '".IO::strValue("CalibratedScalesRemarks")."',
																	 cords_norm                    = '".IO::strValue("CordNorm")."',
																	 cords_norm_remarks            = '".IO::strValue("CordNormRemarks")."',
																	 inspection_conditions         = '".IO::strValue("InspectionConditions")."',
																	 inspection_conditions_remarks = '".IO::strValue("InspectionConditionsRemarks")."',
																	 remarks_1                     = '".IO::strValue("Remarks1")."',
																	 remarks_2                     = '".IO::strValue("Remarks2")."',
																	 remarks_3                     = '".IO::strValue("Remarks3")."',
																	 remarks_4                     = '".IO::strValue("Remarks4")."'
							   WHERE audit_id='$iAuditCode'");

					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}
			}
			
			else if ($iReportId == 32)
			{
				if (getDbValue("COUNT(1)", "tbl_arcadia_inspection_summary", "audit_id='$iAuditCode'") == 0)
				{
					$sSQL  = ("INSERT INTO tbl_arcadia_inspection_summary (audit_id) VALUES ('$iAuditCode')");
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}

				if ($bFlag == true)
				{
					$sSQL  = ("UPDATE tbl_arcadia_inspection_summary SET shipping_marks                = '".IO::strValue("ShippingMarks")."',
																		 shipping_marks_remarks        = '".IO::strValue("ShippingMarksRemarks")."',
																		 material_conformity           = '".IO::strValue("MaterialConformity")."',
																		 material_conformity_remarks   = '".IO::strValue("MaterialConformityRemarks")."',
																		 style                         = '".IO::strValue("ProductStyle")."',
																		 style_remarks                 = '".IO::strValue("ProductStyleRemarks")."',
																		 colour                        = '".IO::strValue("ProductColour")."',
																		 colour_remarks                = '".IO::strValue("ProductColourRemarks")."',
																		 export_carton_packing         = '".IO::strValue("ExportCartonPacking")."',
																		 export_carton_packing_remarks = '".IO::strValue("ExportCartonPackingRemarks")."',
																		 inner_carton_packing          = '".IO::strValue("InnerCartonPacking")."',
																		 inner_carton_packing_remarks  = '".IO::strValue("InnerCartonPackingRemarks")."',
																		 product_packaging             = '".IO::strValue("ProductPackaging")."',
																		 product_packaging_remarks     = '".IO::strValue("ProductPackagingRemarks")."',
																		 assortment                    = '".IO::strValue("Assortment")."',
																		 assortment_remarks            = '".IO::strValue("AssortmentRemarks")."',
																		 labeling                      = '".IO::strValue("Labeling")."',
																		 labeling_remarks              = '".IO::strValue("LabelingRemarks")."',
																		 markings                      = '".IO::strValue("Markings")."',
																		 markings_remarks              = '".IO::strValue("MarkingsRemarks")."',
																		 workmanship                   = '".IO::strValue("Workmanship")."',
																		 workmanship_remarks           = '".IO::strValue("WorkmanshipRemarks")."',
																		 appearance                    = '".IO::strValue("Appearance")."',
																		 appearance_remarks            = '".IO::strValue("AppearanceRemarks")."',
																		 function                      = '".IO::strValue("Function")."',
																		 function_remarks              = '".IO::strValue("FunctionRemarks")."',
																		 printed_materials             = '".IO::strValue("PrintedMaterials")."',
																		 printed_materials_remarks     = '".IO::strValue("PrintedMaterialsRemarks")."',
																		 finishing                     = '".IO::strValue("WorkmanshipFinishing")."',
																		 finishing_remarks             = '".IO::strValue("WorkmanshipFinishingRemarks")."',
																		 measurement                   = '".IO::strValue("Measurement")."',
																		 measurement_remarks           = '".IO::strValue("MeasurementRemarks")."',
																		 fabric_weight                 = '".IO::strValue("FabricWeight")."',
																		 fabric_weight_remarks         = '".IO::strValue("FabricWeightRemarks")."',
																		 calibrated_scales             = '".IO::strValue("CalibratedScales")."',
																		 calibrated_scales_remarks     = '".IO::strValue("CalibratedScalesRemarks")."',
																		 cords_norm                    = '".IO::strValue("CordNorm")."',
																		 cords_norm_remarks            = '".IO::strValue("CordNormRemarks")."',
																		 inspection_conditions         = '".IO::strValue("InspectionConditions")."',
																		 inspection_conditions_remarks = '".IO::strValue("InspectionConditionsRemarks")."',
																		 remarks_1                     = '".IO::strValue("Remarks1")."',
																		 remarks_2                     = '".IO::strValue("Remarks2")."',
																		 remarks_3                     = '".IO::strValue("Remarks3")."',
																		 remarks_4                     = '".IO::strValue("Remarks4")."'
							   WHERE audit_id='$iAuditCode'");

					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}
			}

			else if ($iReportId == 35)
			{
				if (getDbValue("COUNT(1)", "tbl_timezone_inspection_summary", "audit_id='$iAuditCode'") == 0)
				{
					$sSQL  = ("INSERT INTO tbl_timezone_inspection_summary (audit_id) VALUES ('$iAuditCode')");
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}

				if ($bFlag == true)
				{
					$sSQL  = ("UPDATE tbl_timezone_inspection_summary SET shipping_marks                = '".IO::strValue("ShippingMarks")."',
																		  shipping_marks_remarks        = '".IO::strValue("ShippingMarksRemarks")."',
																		  material_conformity           = '".IO::strValue("MaterialConformity")."',
																		  material_conformity_remarks   = '".IO::strValue("MaterialConformityRemarks")."',
																		  style                         = '".IO::strValue("ProductStyle")."',
																		  style_remarks                 = '".IO::strValue("ProductStyleRemarks")."',
																		  colour                        = '".IO::strValue("ProductColour")."',
																		  colour_remarks                = '".IO::strValue("ProductColourRemarks")."',
																		  export_carton_packing         = '".IO::strValue("ExportCartonPacking")."',
																		  export_carton_packing_remarks = '".IO::strValue("ExportCartonPackingRemarks")."',
																		  inner_carton_packing          = '".IO::strValue("InnerCartonPacking")."',
																		  inner_carton_packing_remarks  = '".IO::strValue("InnerCartonPackingRemarks")."',
																		  product_packaging             = '".IO::strValue("ProductPackaging")."',
																		  product_packaging_remarks     = '".IO::strValue("ProductPackagingRemarks")."',
																		  assortment                    = '".IO::strValue("Assortment")."',
																		  assortment_remarks            = '".IO::strValue("AssortmentRemarks")."',
																		  labeling                      = '".IO::strValue("Labeling")."',
																		  labeling_remarks              = '".IO::strValue("LabelingRemarks")."',
																		  markings                      = '".IO::strValue("Markings")."',
																		  markings_remarks              = '".IO::strValue("MarkingsRemarks")."',
																		  workmanship                   = '".IO::strValue("Workmanship")."',
																		  workmanship_remarks           = '".IO::strValue("WorkmanshipRemarks")."',
																		  appearance                    = '".IO::strValue("Appearance")."',
																		  appearance_remarks            = '".IO::strValue("AppearanceRemarks")."',
																		  function                      = '".IO::strValue("Function")."',
																		  function_remarks              = '".IO::strValue("FunctionRemarks")."',
																		  printed_materials             = '".IO::strValue("PrintedMaterials")."',
																		  printed_materials_remarks     = '".IO::strValue("PrintedMaterialsRemarks")."',
																		  finishing                     = '".IO::strValue("WorkmanshipFinishing")."',
																		  finishing_remarks             = '".IO::strValue("WorkmanshipFinishingRemarks")."',
																		  measurement                   = '".IO::strValue("Measurement")."',
																		  measurement_remarks           = '".IO::strValue("MeasurementRemarks")."',
																		  fabric_weight                 = '".IO::strValue("FabricWeight")."',
																		  fabric_weight_remarks         = '".IO::strValue("FabricWeightRemarks")."',
																		  calibrated_scales             = '".IO::strValue("CalibratedScales")."',
																		  calibrated_scales_remarks     = '".IO::strValue("CalibratedScalesRemarks")."',
																		  cords_norm                    = '".IO::strValue("CordNorm")."',
																		  cords_norm_remarks            = '".IO::strValue("CordNormRemarks")."',
																		  inspection_conditions         = '".IO::strValue("InspectionConditions")."',
																		  inspection_conditions_remarks = '".IO::strValue("InspectionConditionsRemarks")."',
																		  remarks_1                     = '".IO::strValue("Remarks1")."',
																		  remarks_2                     = '".IO::strValue("Remarks2")."',
																		  remarks_3                     = '".IO::strValue("Remarks3")."',
																		  remarks_4                     = '".IO::strValue("Remarks4")."'
							   WHERE audit_id='$iAuditCode'");

					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}
			}

			if ($bFlag == true)
			{
				$objDb->execute("COMMIT", true, $iUser, $sName);

				$aResponse['Status']  = "OK";
				$aResponse["Message"] = "Overall Audit Summary Saved Successfully!";
			}

			else
			{
				$objDb->execute("ROLLBACK", true, $iUser, $sName);

				$aResponse["Message"] = "An ERROR occured, please try again.";
			}
		}
	}

	print @json_encode($aResponse);

/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = $Cap."\n\n".@json_encode($aResponse)."<bR>".$sSQL;

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>