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
?>
<?
        $sHandWashApproved      = IO::strValue('hand_wash_approved');
        $sShadeBandApproed      = IO::strValue('shade_band_approved');
        $sAbrasionStds          = IO::strValue('abrasion_stds');
        $sGmtDyeingApproved     = IO::strValue('gmt_dyeing_approved');
        $sWashingProcessingTa   = IO::strValue('washing_processing_ta');
        $sBulkApprovalDate1     = IO::strValue('bulk_approval_date1');
        $sBulkApprovalDate2     = IO::strValue('bulk_approval_date2');
        $sWashComments1         = IO::strValue('wash_comments1');
        $sSpecialTack           = IO::strValue('special_tack');
        $sShadeLabels           = IO::strValue('shade_labels');
        $sShrinkageCheck        = IO::strValue('shrinkage_check');
        $sColorTest             = IO::strValue('color_test');
        $sFabricGrouping        = IO::strValue('fabric_grouping');
        $sMarkerGrouping        = IO::strValue('marker_grouping');
        $sBeforeWashData        = IO::strValue('before_wash_data');
        $sAfterWashData         = IO::strValue('after_wash_data');            
        $sReviewWashSpec        = IO::strValue('review_wash_spec');
        $sSpecialAdjustment     = IO::strValue('spcial_adjustments');
        $sApplicationString     = IO::strValue('application_strings');
        $sWashComments2         = IO::strValue('wash_comments2');
        $sLaundryName           = IO::strValue('laundry_name');
        $sDryProcess            = IO::strValue('dry_process_treatment');
        $sWetProcess            = IO::strValue('wet_process_treatment');
        $sTaCapacity            = IO::strValue('ta_capacity');
    
        if(getDbValue("COUNT(1)", "tbl_ppmeeting_garment_wash", "audit_id='$Id'") > 0)
        {
                    $sSQL  = ("UPDATE tbl_ppmeeting_garment_wash SET    hand_wash_approved      = '".($sHandWashApproved)."',
                                                                        shade_band_approved     = '".($sShadeBandApproed)."',
                                                                        abrasion_stds           = '".($sAbrasionStds)."',                                                                                  
                                                                        gmt_dyeing_approved     = '".($sGmtDyeingApproved)."',
                                                                        washing_processing_ta   = '".($sWashingProcessingTa)."',
                                                                        bulk_approval_date1     = '".($sBulkApprovalDate1)."',                                                                                  
                                                                        bulk_approval_date2     = '".($sBulkApprovalDate2)."',
                                                                        special_tack            = '".($sSpecialTack)."',
                                                                        shade_labels            = '".($sShadeLabels)."',     
                                                                        shrinkage_check         = '".($sShrinkageCheck)."',         
                                                                        color_test              = '".($sColorTest)."',
                                                                        fabric_grouping         = '".($sFabricGrouping)."',
                                                                        marker_grouping         = '".($sMarkerGrouping)."',                                                                                  
                                                                        before_wash_data        = '".($sBeforeWashData)."',
                                                                        after_wash_data         = '".($sAfterWashData)."',
                                                                        review_wash_spec        = '".($sReviewWashSpec)."',                                                                                  
                                                                        spcial_adjustments      = '".($sSpecialAdjustment)."',
                                                                        application_strings     = '".($sApplicationString)."',
                                                                        laundry_name            = '".$sLaundryName."',
                                                                        dry_process_treatment   = '".$sDryProcess."',
                                                                        wet_process_treatment   = '".$sWetProcess."',
                                                                        ta_capacity             = '".$sTaCapacity."',    
                                                                        wash_comments1          = '".$sWashComments1."',    
                                                                        wash_comments2          = '".$sWashComments2."'
                            WHERE audit_id='$Id'");

        }else
        {
                      $sSQL  = ("INSERT INTO tbl_ppmeeting_garment_wash SET audit_id         = '$Id',
                                                                        hand_wash_approved      = '".($sHandWashApproved)."',
                                                                        shade_band_approved     = '".($sShadeBandApproed)."',
                                                                        abrasion_stds           = '".($sAbrasionStds)."',                                                                                  
                                                                        gmt_dyeing_approved     = '".($sGmtDyeingApproved)."',
                                                                        washing_processing_ta   = '".($sWashingProcessingTa)."',
                                                                        bulk_approval_date1     = '".($sBulkApprovalDate1)."',                                                                                  
                                                                        bulk_approval_date2     = '".($sBulkApprovalDate2)."',
                                                                        special_tack            = '".($sSpecialTack)."',
                                                                        shade_labels            = '".($sShadeLabels)."',     
                                                                        shrinkage_check         = '".($sShrinkageCheck)."',         
                                                                        color_test              = '".($sColorTest)."',
                                                                        fabric_grouping         = '".($sFabricGrouping)."',
                                                                        marker_grouping         = '".($sMarkerGrouping)."',                                                                                  
                                                                        before_wash_data        = '".($sBeforeWashData)."',
                                                                        after_wash_data         = '".($sAfterWashData)."',
                                                                        review_wash_spec        = '".($sReviewWashSpec)."',                                                                                  
                                                                        spcial_adjustments      = '".($sSpecialAdjustment)."',
                                                                        application_strings     = '".($sApplicationString)."',
                                                                        laundry_name            = '".$sLaundryName."',
                                                                        dry_process_treatment   = '".$sDryProcess."',
                                                                        wet_process_treatment   = '".$sWetProcess."',
                                                                        ta_capacity             = '".$sTaCapacity."',    
                                                                        wash_comments1          = '".$sWashComments1."',    
                                                                        wash_comments2          = '".$sWashComments2."'");
        }
      
        $bFlag = $objDb->execute($sSQL);

        if($bFlag == false)
            exit($sSql);
  
?>
