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

	$sSQL  = ("UPDATE tbl_qa_reports SET po_id='$PoId', group_id='".IO::intValue('Group')."', additional_pos='$sAdditionalPos', audit_stage='".IO::strValue("AuditStage")."', audit_status='".IO::strValue("AuditStatus")."', audit_result='".IO::strValue("AuditResult")."', audit_type='".IO::strValue("AuditType")."', colors='".IO::strValue("Colors")."', cartons_required='".IO::intValue("CartonsRequired")."', cartons_shipped='".IO::intValue("CartonsShipped")."', total_gmts='$TotalGmts', max_defects='$MaxDefects', total_cartons='".IO::floatValue("TotalCartons")."', rejected_cartons='".IO::floatValue("CartonsRejected")."', defective_percent='".IO::floatValue("PercentDecfective")."', knitted='".IO::floatValue("Knitted")."', dyed='".IO::floatValue("Dyed")."', cutting='".IO::intValue("Cutting")."', sewing='".IO::intValue("Sewing")."', finishing='".IO::intValue("Finishing")."', packing='".IO::intValue("Packing")."', final_audit_date='".((IO::strValue("FinalAuditDate") == "") ? "0000-00-00" : IO::strValue("FinalAuditDate"))."', standard='".IO::floatValue("Standard")."', approved_sample='".IO::strValue("ApprovedSample")."', shipping_mark='".IO::strValue("ShippingMark")."', packing_check='".IO::strValue("PackingCheck")."', carton_size='$CartonSize', ship_qty='".IO::intValue("ShipQty")."', re_screen_qty='".IO::intValue("ReScreenQty")."', qa_comments='".IO::strValue("Comments")."', date_time=NOW( ) WHERE id='$Id'");
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
			$Cap      = IO::strValue("Cap".$i);
			$DColor   = IO::strValue("DColor".$i);
			$Remarks  = IO::strValue("Remarks".$i);
			$Picture  = IO::getFileName($_FILES["Picture".$i]['name']);
			

			if ($DefectId > 0)
			{
				$sSQL  = "UPDATE tbl_qa_report_defects SET code_id='$Code', defects='$Defects', sample_no='$SampleNo', area_id='$Area', nature='$Nature', cap='$Cap', color='$DColor', remarks='$Remarks' WHERE id='$DefectId'";
				$bFlag = $objDb->execute($sSQL);
			}

			else
			{
				$DefectId = getNextId("tbl_qa_report_defects");

				$sSQL  = ("INSERT INTO tbl_qa_report_defects (id, audit_id, sample_no, code_id, defects, area_id, nature, color, cap, remarks, picture, date_time) VALUES ('$DefectId', '$Id', '$SampleNo', '$Code', '$Defects', '$Area', '$Nature', '$DColor', '$Cap', '$Remarks', '$Picture', NOW())");
				$bFlag = $objDb->execute($sSQL);
			}


			if ($bFlag == false)
				break;
		}
	}

	if ($bFlag == true)
	{
            	$sSQL = "SELECT * FROM tbl_gms_reports WHERE audit_id='$Id'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$sSQL = "INSERT INTO tbl_gms_reports SET audit_id='$Id', ";

		else
			$sSQL = "UPDATE tbl_gms_reports SET ";
                
                
                $DrawnCartonNo = "";
                $AssortmentCheck = "";
                $CountRows = IO::intValue("CountRows");
             
                if($CountRows > 0){
                    for($j =1; $j <= $CountRows; $j++){
                        
                        if(IO::strValue("CartonNo_".$j) != '')
                        {
                            $DrawnCartonNo .=  str_replace(",", "", IO::strValue("CartonNo_".$j)).",";

                            $AssortCheck = IO::strValue("AssortCheck_".$j);
                            if($AssortCheck != 'Y')
                                    $AssortCheck = "N";
                            
                            $AssortmentCheck .= $AssortCheck.",";
                        }
                    }
                }                

            $sSQL .= ("fk_panel_qlty                 = '".IO::strValue("FKPanelQlty")."',
					   main_label                 = '".IO::strValue("MainLabel")."',
					   price_tag                = '".IO::strValue("PriceTag")."',
					   untrimmed_thread                  = '".IO::strValue("UntrimmedThread")."',
					   hand_feel              = '".IO::strValue("HandFeel")."',
					   washing_label                        = '".IO::strValue("WashingLabel")."',
					   special_hangtag                 = '".IO::strValue("SpecialHangtag")."',
					   hand_feel2            = '".IO::strValue("HandFeel2")."',
					   color                 = '".IO::strValue("Color")."',
					   size_label                   = '".IO::strValue("SizeLabel")."',
					   tissue_stuffing            = '".IO::strValue("TissueStuffing")."',
					   fit_on_form                     = '".IO::strValue("FitOnForm")."',
					   shade_lot             = '".IO::strValue("ShadeLot")."',
					   care_label               = '".IO::strValue("CareLabel")."',
					   polybag           = '".IO::strValue("Polybag")."',
					   twisted         = '".IO::strValue("Twisted")."',
					   lining     = '".IO::strValue("Lining")."',
					   int_size_label                  = '".IO::strValue("IntSizeLabel")."',
                                           packing_method                  = '".IO::strValue("PackingMethod")."',    
					   trim_fabric          = '".IO::strValue("TrimFabric")."',
					   spare_button        = '".IO::strValue("SpareButton")."',
					   measurement              = '".IO::strValue("Measurement")."',
					   interlining               = '".IO::strValue("Interlining")."',
					   info_sticker             = '".IO::strValue("InfoSticker")."',
					   smell          = '".IO::strValue("Smell")."',
					   shoulder_pad = '".IO::strValue("ShoulderPad")."',
					   packing_assortment     = '".IO::strValue("PackingAssortment")."',
                                           mositure_test_result     = '".IO::strValue("MoistureResult")."',
                                           washing_effect     = '".IO::strValue("WashingEffect")."',
                                           exp_carton_size     = '".IO::strValue("ExpCartonSize")."',
                                           azo_report_no     = '".IO::strValue("AzoReportNo")."',
                                           down_pouch     = '".IO::strValue("DownPouch")."',
                                           exp_carton_weight     = '".IO::strValue("ExportCartonWeight")."',
                                           padding     = '".IO::strValue("Padding")."',    
                                           carton_label     = '".IO::strValue("CartonLabel")."',
                                           please_specify     = '".IO::strValue("PleaseSpecify")."',
                                           garment_measurement     = '".IO::strValue("GarmentMeasurement")."',    
                                           moisture_measurement     = '".IO::strValue("MoistureMeasurement")."',
                                           drawn_carton_no     = '".rtrim($DrawnCartonNo, ',')."',
                                           assortment_check     = '".rtrim($AssortmentCheck, ',')."'");


		if ($objDb->getCount( ) == 1)
			$sSQL .= " WHERE audit_id='$Id'";

		$bFlag = $objDb->execute($sSQL);
	}
        
        if($bFlag == true)
        {
            $sSQL  = "DELETE FROM tbl_qa_color_quantities WHERE audit_id='$Id'";
            $bFlag = $objDb->execute($sSQL);
                
            $ColorsQtity = IO::getArray('CQuantity');
            $ColorNames  = IO::getArray('CName');
            
            if($bFlag == true)
            {
                foreach($ColorNames as $key => $sColor){

                    $iColorQty = (int)$ColorsQtity[$key];
                    $sSQL = "INSERT INTO tbl_qa_color_quantities SET audit_id='$Id', color='$sColor', quantity='$iColorQty'";
                    $bFlag = $objDb->execute($sSQL);

                    if ($bFlag == false)
                        break;                
                }
            }
        }
        
        if($bFlag == true)
        {
            $sSQL  = "DELETE FROM tbl_qa_po_ship_quantities WHERE audit_id='$Id'";
            $bFlag = $objDb->execute($sSQL);
                
            $PosQtity = IO::getArray('POQuantity');
            $PoIds    = IO::getArray('PoIds');
            
            if($bFlag == true)
            {
                foreach($PoIds as $key => $iPo){

                    $iPoQty = (int)$PosQtity[$key];
                    $sSQL = "INSERT INTO tbl_qa_po_ship_quantities SET audit_id='$Id', po_id='$iPo', quantity='$iPoQty'";
                    $bFlag = $objDb->execute($sSQL);

                    if ($bFlag == false)
                        break;                
                }
            }
        }
       

	$iDefectiveGmts = getDbValue("COUNT(DISTINCT(sample_no))", "tbl_qa_report_defects", "audit_id='$Id' AND nature>'0'");
	$fDhu           = round((($iDefectiveGmts / $TotalGmts) * 100), 2);
?>