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

	$sSQL  = ("UPDATE tbl_qa_reports SET po_id='$PoId', group_id='".IO::intValue('Group')."', additional_pos='$sAdditionalPos', audit_stage='".IO::strValue("AuditStage")."', audit_result='".IO::strValue("AuditResult")."', colors='".IO::strValue("Colors")."', sizes='".@implode(",", IO::getArray('Sizes'))."', description='".IO::strValue("Description")."', stock_status='".IO::strValue("StockStatus")."', total_gmts='$TotalGmts', defective_gmts='".IO::floatValue("GmtsDefective")."', max_defects='$MaxDefects', ship_qty='".IO::intValue("ShipQty")."', qa_comments='".IO::strValue("Comments")."', date_time=NOW( ) WHERE id='$Id'");
        $bFlag = $objDb->execute($sSQL);


	if ($bFlag == true)
	{
		$sSQL = "SELECT * FROM tbl_yarn_qa_reports WHERE audit_id='$Id'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$sSQL = "INSERT INTO tbl_yarn_qa_reports SET audit_id='$Id', ";

		else
			$sSQL = "UPDATE tbl_yarn_qa_reports SET ";


		$sSQL .= ("style_name                    = '".IO::strValue("StyleName")."',
				   yarn_content                  = '".IO::strValue("YarnContent")."',

				   style_conformity              = '".IO::strValue("StyleConformity")."',
				   material_conformity           = '".IO::strValue("MaterialConformity")."',
				   shade_conformity              = '".IO::strValue("ShadeConformity")."',

				   pc_yarn_count                 = '".IO::strValue("PcYarnCount")."',
				   carton_pallet                 = '".IO::strValue("CartonPallet")."',
				   avg_gross_weight_g            = '".IO::floatValue("AvgGrossWeightG")."',
				   avg_gross_weight_kg           = '".IO::floatValue("AvgGrossWeightKg")."',
				   avg_gross_weight_lb           = '".IO::floatValue("AvgGrossWeightLb")."',
				   tare_weight_g                 = '".IO::floatValue("TareWeightG")."',
				   tare_weight_kg                = '".IO::floatValue("TareWeightKg")."',
				   tare_weight_lb                = '".IO::floatValue("TareWeightLb")."',
				   net_weight_g                  = '".IO::floatValue("NetWeightG")."',
				   net_weight_kg                 = '".IO::floatValue("NetWeightKg")."',
				   net_weight_lb                 = '".IO::floatValue("NetWeightLb")."',
				   ref_sample_available          = '".IO::strValue("RefSampleAvailable")."',
				   sample_available              = '".IO::strValue("SampleAvailable")."',
				   pc_other                      = '".IO::strValue("PcOther")."',
				   pc_reservations               = '".IO::strValue("PcReservations")."',

				   aff_reservations              = '".IO::strValue("AffReservations")."',
				   quantities_submitted          = '".IO::strValue("QuantitiesSubmitted")."',
				   measurements_field_tests      = '".IO::strValue("MeasurementsFieldTests")."',
				   style_material_color          = '".IO::strValue("StyleMaterialColor")."',
				   appearance_functioning        = '".IO::strValue("AppearanceFunctioning")."',
				   packing                       = '".IO::strValue("Packing")."',
				   marking_label                 = '".IO::strValue("MarkingLabel")."',
				   factory_comments              = '".IO::strValue("FactoryComments")."',
				   external_lab_testing          = '".IO::strValue("ExternalLabTesting")."',
				   for_record_client             = '".IO::strValue("ForRecordClient")."',
				   sealed_at_factory             = '".IO::strValue("SealedAtFactory")."',
				   reason_for_not_taking_samples = '".IO::strValue("ReasonForNotTakingSamples")."',
				   total_cartons_selected        = '".IO::intValue("TotalCartonsSelected")."',
				   cones_inspected               = '".IO::intValue("ConesInspected")."',
				   data_measurement_test         = '".IO::strValue("DataMeasurementTest")."'");

		if ($objDb->getCount( ) == 1)
			$sSQL .= " WHERE audit_id='$Id'";

		$bFlag = $objDb->execute($sSQL);
	}


	if ($bFlag == true)
	{
		$sSQL = "SELECT * FROM tbl_yarn_product_checks WHERE audit_id='$Id'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$sSQL = "INSERT INTO tbl_yarn_product_checks SET audit_id='$Id', ";

		else
			$sSQL = "UPDATE tbl_yarn_product_checks SET ";


		$sSQL .= ("yarn_count         = '".IO::strValue("YarnCount")."',
				   actual_count_r     = '".IO::strValue("ActualCountR")."',
				   actual_count_g     = '".IO::strValue("ActualCountG")."',
				   actual_count_ac    = '".IO::strValue("ActualCountAc")."',
				   actual_count_p     = '".IO::floatValue("ActualCountP")."',
				   cv_count_r         = '".IO::strValue("CvCountR")."',
				   cv_count_g         = '".IO::strValue("CvCountG")."',
				   cv_count_ac        = '".IO::strValue("CvCountAc")."',
				   cv_count_p         = '".IO::floatValue("CvCountP")."',
				   lea_strength_r     = '".IO::strValue("LeaStrengthR")."',
				   lea_strength_g     = '".IO::strValue("LeaStrengthG")."',
				   lea_strength_ac    = '".IO::strValue("LeaStrengthAc")."',
				   lea_strength_p     = '".IO::floatValue("LeaStrengthP")."',
				   cv_strength_r      = '".IO::strValue("CvStrengthR")."',
				   cv_strength_g      = '".IO::strValue("CvStrengthG")."',
				   cv_strength_ac     = '".IO::strValue("CvStrengthAc")."',
				   cv_strength_p      = '".IO::floatValue("CvStrengthP")."',
				   clsp_r             = '".IO::strValue("ClspR")."',
				   clsp_g             = '".IO::strValue("ClspG")."',
				   clsp_ac            = '".IO::strValue("ClspAc")."',
				   clsp_p             = '".IO::floatValue("ClspP")."',
				   min_clsp_r         = '".IO::strValue("MinClspR")."',
				   min_clsp_g         = '".IO::strValue("MinClspG")."',
				   min_clsp_ac        = '".IO::strValue("MinClspAc")."',
				   min_clsp_p         = '".IO::floatValue("MinClspP")."',
				   rkm_r              = '".IO::strValue("RkmR")."',
				   rkm_g              = '".IO::strValue("RkmG")."',
				   rkm_ac             = '".IO::strValue("RkmAc")."',
				   rkm_p              = '".IO::floatValue("RkmP")."',
				   sy_str_r           = '".IO::strValue("SyStrR")."',
				   sy_str_g           = '".IO::strValue("SyStrG")."',
				   sy_str_ac          = '".IO::strValue("SyStrAc")."',
				   sy_str_p           = '".IO::floatValue("SyStrP")."',
				   cv_r               = '".IO::strValue("CvR")."',
				   cv_g               = '".IO::strValue("CvG")."',
				   cv_ac              = '".IO::strValue("CvAc")."',
				   cv_p               = '".IO::floatValue("CvP")."',
				   elongation_r       = '".IO::strValue("ElongationR")."',
				   elongation_g       = '".IO::strValue("ElongationG")."',
				   elongation_ac      = '".IO::strValue("ElongationAc")."',
				   elongation_p       = '".IO::floatValue("ElongationP")."',
				   elongation_cv_r    = '".IO::strValue("ElongationCvR")."',
				   elongation_cv_g    = '".IO::strValue("ElongationCvG")."',
				   elongation_cv_ac   = '".IO::strValue("ElongationCvAc")."',
				   elongation_cv_p    = '".IO::floatValue("ElongationCvP")."',
				   ucvm_r             = '".IO::strValue("UcvmR")."',
				   ucvm_g             = '".IO::strValue("UcvmG")."',
				   ucvm_ac            = '".IO::strValue("UcvmAc")."',
				   ucvm_p             = '".IO::floatValue("UcvmP")."',
				   cvm_10m_r          = '".IO::strValue("Cvm10mR")."',
				   cvm_10m_g          = '".IO::strValue("Cvm10mG")."',
				   cvm_10m_ac         = '".IO::strValue("Cvm10mAc")."',
				   cvm_10m_p          = '".IO::floatValue("Cvm10mP")."',
				   thin_r             = '".IO::strValue("ThinR")."',
				   thin_g             = '".IO::strValue("ThinG")."',
				   thin_ac            = '".IO::strValue("ThinAc")."',
				   thin_p             = '".IO::floatValue("ThinP")."',
				   thick_r            = '".IO::strValue("ThickR")."',
				   thick_g            = '".IO::strValue("ThickG")."',
				   thick_ac           = '".IO::strValue("ThickAc")."',
				   thick_p            = '".IO::floatValue("ThickP")."',
				   neps_r             = '".IO::strValue("NepsR")."',
				   neps_g             = '".IO::strValue("NepsG")."',
				   neps_ac            = '".IO::strValue("NepsAc")."',
				   neps_p             = '".IO::floatValue("NepsP")."',
				   ipi_value_r        = '".IO::strValue("IpiValueR")."',
				   ipi_value_g        = '".IO::strValue("IpiValueG")."',
				   ipi_value_ac       = '".IO::strValue("IpiValueAc")."',
				   ipi_value_p        = '".IO::floatValue("IpiValueP")."',
				   cv_bu_r            = '".IO::strValue("CvBuR")."',
				   cv_bu_g            = '".IO::strValue("CvBuG")."',
				   cv_bu_ac           = '".IO::strValue("CvBuAc")."',
				   cv_bu_p            = '".IO::floatValue("CvBuP")."',
				   hairiness_r        = '".IO::strValue("HairinessR")."',
				   hairiness_g        = '".IO::strValue("HairinessG")."',
				   hairiness_ac       = '".IO::strValue("HairinessAc")."',
				   hairiness_p        = '".IO::floatValue("HairinessP")."',
				   tpi_r              = '".IO::strValue("TpiR")."',
				   tpi_g              = '".IO::strValue("TpiG")."',
				   tpi_ac             = '".IO::strValue("TpiAc")."',
				   tpi_p              = '".IO::floatValue("TpiP")."',
				   count_max_r        = '".IO::strValue("CountMaxR")."',
				   count_max_g        = '".IO::strValue("CountMaxG")."',
				   count_max_ac       = '".IO::strValue("CountMaxAc")."',
				   count_max_p        = '".IO::floatValue("CountMaxP")."',
				   count_min_r        = '".IO::strValue("CountMinR")."',
				   count_min_g        = '".IO::strValue("CountMinG")."',
				   count_min_ac       = '".IO::strValue("CountMinAc")."',
				   count_min_p        = '".IO::floatValue("CountMinP")."',
				   cone_moisture_r    = '".IO::strValue("ConeMoistureR")."',
				   cone_moisture_g    = '".IO::strValue("ConeMoistureG")."',
				   cone_moisture_ac   = '".IO::strValue("ConeMoistureAc")."',
				   cone_moisture_p    = '".IO::floatValue("ConeMoistureP")."',
				   comber_noil_r      = '".IO::strValue("ComberNoilR")."',
				   comber_noil_g      = '".IO::strValue("ComberNoilG")."',
				   comber_noil_ac     = '".IO::strValue("ComberNoilAc")."',
				   comber_noil_p      = '".IO::floatValue("ComberNoilP")."',
				   tpi_cv_r           = '".IO::strValue("TpiCvR")."',
				   tpi_cv_g           = '".IO::strValue("TpiCvG")."',
				   tpi_cv_ac          = '".IO::strValue("TpiCvAc")."',
				   tpi_cv_p           = '".IO::floatValue("TpiCvP")."',

				   fc_length          = '".IO::strValue("FcLength")."',
				   fc_ui_ur           = '".IO::strValue("FcUiUr")."',
				   fc_ffi_sfi         = '".IO::strValue("FcFfiSfi")."',
				   fc_strength        = '".IO::strValue("FcStrength")."',
				   fc_mic_value       = '".IO::strValue("FcMicValue")."',
				   fc_mic_range       = '".IO::strValue("FcMicRange")."',
				   fc_color_grade     = '".IO::strValue("FcColorGrade")."',
				   fc_no_of_lots      = '".IO::strValue("FcNoOfLots")."',
				   fc_cotton_stock    = '".IO::strValue("FcCottonStock")."',
				   fc_trash           = '".IO::strValue("FcTrash")."',
				   fc_color           = '".IO::strValue("FcColor")."',
				   fc_moisture        = '".IO::strValue("FcMoisture")."',
				   fc_contamination   = '".IO::strValue("FcContamination")."',

				   fr_length          = '".IO::strValue("FrLength")."',
				   fr_denier          = '".IO::strValue("FrDenier")."',
				   fr_color           = '".IO::strValue("FrColor")."',
				   fr_polyester       = '".IO::strValue("FrPolyester")."',
				   fr_cotton          = '".IO::strValue("FrCotton")."',
				   pr_length          = '".IO::strValue("PrLength")."',
				   pr_denier          = '".IO::strValue("PrDenier")."',
				   pr_color           = '".IO::strValue("PrColor")."',
				   pr_polyester       = '".IO::strValue("PrPolyester")."',
				   pr_cotton          = '".IO::strValue("PrCotton")."',

				   acs_n              = '".IO::strValue("AcsN")."',
				   acs_sds            = '".IO::strValue("AcsSds")."',
				   acs_lls            = '".IO::strValue("AcsLls")."',
				   acs_tdl            = '".IO::strValue("AcsTdl")."',
				   acs_fdd            = '".IO::strValue("AcsFdd")."',
				   acs_l              = '".IO::strValue("AcsL")."',
				   acs_yf             = '".IO::strValue("AcsYf")."',
				   auto_cone_speed    = '".IO::floatValue("AutoConeSpeed")."',
				   cone_length        = '".IO::floatValue("ConeLength")."',
				   cone_weight        = '".IO::floatValue("ConeWeight")."'");

		if ($objDb->getCount( ) == 1)
			$sSQL .= " WHERE audit_id='$Id'";

		$bFlag = $objDb->execute($sSQL);
	}


	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_yarn_product_conformity WHERE audit_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		for ($i = 0; $i < 25; $i ++)
		{
			if (IO::strValue("CartonNo".$i) != "")
			{
				$sSQL  = "INSERT INTO tbl_yarn_product_conformity (id, audit_id, carton_no, carton_weight, cone_1_weight, cone_2_weight, cone_3_weight) VALUES ('$i', '$Id', '".IO::strValue("CartonNo".$i)."', '".IO::floatValue("CartonWeight".$i)."', '".IO::floatValue("Cone1Weight".$i)."', '".IO::floatValue("Cone2Weight".$i)."', '".IO::floatValue("Cone3Weight".$i)."')";
				$bFlag = $objDb->execute($sSQL);

				if ($bFlag == false)
					break;
			}
		}
	}


	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_yarn_appearance WHERE audit_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		for ($i = 0; $i < 6; $i ++)
		{
			if (IO::strValue("Defect".$i) != "" && (IO::intValue("Major".$i) > 0 || IO::intValue("Minor".$i) > 0))
			{
				$sSQL  = "INSERT INTO tbl_yarn_appearance (audit_id, defect, major, minor) VALUES ('$Id', '".IO::strValue("Defect".$i)."', '".IO::intValue("Major".$i)."', '".IO::intValue("Minor".$i)."')";
				$bFlag = $objDb->execute($sSQL);

				if ($bFlag == false)
					break;
			}
		}
	}


	if ($bFlag == true)
	{
		$sSQL = "SELECT * FROM tbl_yarn_packing WHERE audit_id='$Id'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$sSQL = "INSERT INTO tbl_yarn_packing SET audit_id='$Id', ";

		else
			$sSQL = "UPDATE tbl_yarn_packing SET ";


		$sSQL .= ("packing_detail                 = '".IO::strValue("PackingDetail")."',
				   carton_dimension1              = '".IO::strValue("CartonDimension1")."',
				   carton_dimension2              = '".IO::strValue("CartonDimension2")."',
				   individual_packing_conformity1 = '".IO::strValue("IndividualPackingConformity1")."',
				   individual_packing_conformity2 = '".IO::strValue("IndividualPackingConformity2")."',
				   paper_cone_conformity1         = '".IO::strValue("PaperConeConformity1")."',
				   paper_cone_conformity2         = '".IO::strValue("PaperConeConformity2")."',
				   inner_packing_conformity1      = '".IO::strValue("InnerPackingConformity1")."',
				   inner_packing_conformity2      = '".IO::strValue("InnerPackingConformity2")."',
				   assortment_found_correct1      = '".IO::strValue("AssortmentFoundCorrect1")."',
				   assortment_found_correct2      = '".IO::strValue("AssortmentFoundCorrect2")."'");

		if ($objDb->getCount( ) == 1)
			$sSQL .= " WHERE audit_id='$Id'";

		$bFlag = $objDb->execute($sSQL);
	}


	if ($bFlag == true)
	{
		$sSQL = "SELECT * FROM tbl_yarn_marking_label WHERE audit_id='$Id'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$sSQL = "INSERT INTO tbl_yarn_marking_label SET audit_id='$Id', ";

		else
			$sSQL = "UPDATE tbl_yarn_marking_label SET ";


		$sSQL .= ("bar_code_conformity1      = '".IO::strValue("BarCodeConformity1")."',
				   bar_code_conformity2      = '".IO::strValue("BarCodeConformity2")."',
				   shipping_mark_conformity1 = '".IO::strValue("ShippingMarkConformity1")."',
				   shipping_mark_conformity2 = '".IO::strValue("ShippingMarkConformity2")."',
				   other_marks1              = '".IO::strValue("OtherMarks1")."',
				   other_marks2              = '".IO::strValue("OtherMarks2")."',
				   side_mark_conformity1     = '".IO::strValue("SideMarkConformity1")."',
				   side_mark_conformity2     = '".IO::strValue("SideMarkConformity2")."',
				   count_label1              = '".IO::strValue("CountLabel1")."',
				   count_label2              = '".IO::strValue("CountLabel2")."',
				   baling_strip1             = '".IO::strValue("BalingStrip1")."',
				   baling_strip2             = '".IO::strValue("BalingStrip2")."',
				   brand_name1               = '".IO::strValue("BrandName1")."',
				   brand_name2               = '".IO::strValue("BrandName2")."',
				   other1                    = '".IO::strValue("Other1")."',
				   other2                    = '".IO::strValue("Other2")."'");

		if ($objDb->getCount( ) == 1)
			$sSQL .= " WHERE audit_id='$Id'";

		$bFlag = $objDb->execute($sSQL);
	}        
?>