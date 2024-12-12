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
  
    $sLinesAllocated        = IO::strValue("lines_allocated");
    $sMachineLine           = IO::strValue("machine_line");
    $sTargetPerHourDay      = IO::strValue("target_per_hourday");
    $sCuttingDate           = IO::strValue("cutting_date");
    $sSewingDate            = IO::strValue("sewing_date");
    $sFinishingDate         = IO::strValue("finishing_date");
    $sRemarksBeading        = IO::strValue("remarks_beading");

    $sOperationApplication  = implode("|-|", IO::getArray("operation_application"));
    $sFolderAttachment      = implode("|-|", IO::getArray("folder_attachment"));
    $sMachineType           = implode("|-|", IO::getArray("machine_type"));
    $sTotalLinesAllocated   = implode("|-|", IO::getArray("total_lines_allocated"));
    $sAttachmentRequired    = implode("|-|", IO::getArray("attchments_required"));
    $sSpecialManual         = implode("|-|", IO::getArray("special_manual"));

    $sPilotDate             = IO::strValue("pilot_date");
    $sWashReviewDate        = IO::strValue("wash_review_date");
    $sOutputReviewDate      = IO::strValue("output_review_date");
    $sBulkReviewDate        = IO::strValue("bulk_review_date");
    $sPrintReviewDate       = IO::strValue("print_review_date");
    $sColorsReviewDate      = IO::strValue("colors_review_date");
    $sIroningReviewDate     = IO::strValue("ironing_review_date");

    $sRiskPoints            = implode("|-|", IO::getArray("risk_points"));
    $sActionPlan            = implode("|-|", IO::getArray("action_plans"));
    $sOwners                = implode("|-|", IO::getArray("owners"));
    $sDates                 = implode("|-|", IO::getArray("dates"));
    $sExecutive             = IO::strValue("executive");

        if(getDbValue("COUNT(1)", "tbl_ppmeeting_machine_layout", "audit_id='$Id'") > 0)
        {
                    $sSQL  = ("UPDATE tbl_ppmeeting_machine_layout SET  lines_allocated         = '".$sLinesAllocated."',
                                                                            machine_line            = '".$sMachineLine."',
                                                                            target_per_hourday      = '".$sTargetPerHourDay."',
                                                                            cutting_date            = '".$sCuttingDate."',                                                                                  
                                                                            sewing_date             = '".$sSewingDate."',
                                                                            finishing_date          = '".$sFinishingDate."',    
                                                                            remarks_beading         = '".$sRemarksBeading."',                                                                                
                                                                            operation_application   = '".$sOperationApplication."',
                                                                            folder_attachment       = '".$sFolderAttachment."',
                                                                            machine_type            = '".$sMachineType."',
                                                                            total_lines_allocated   = '".$sTotalLinesAllocated."',
                                                                            attchments_required     = '".$sAttachmentRequired."',
                                                                            special_manual          = '".$sSpecialManual."',                                                                                
                                                                            pilot_date              = '".$sPilotDate."',
                                                                            wash_review_date        = '".$sWashReviewDate."',
                                                                            output_review_date      = '".$sOutputReviewDate."',
                                                                            bulk_review_date        = '".$sBulkReviewDate."',
                                                                            print_review_date       = '".$sPrintReviewDate."',
                                                                            colors_review_date      = '".$sColorsReviewDate."',
                                                                            ironing_review_date     = '".$sIroningReviewDate."',                                                                                
                                                                            risk_points             = '".$sRiskPoints."',
                                                                            action_plans            = '".$sActionPlan."',
                                                                            owners                  = '".$sOwners."',
                                                                            dates                   = '".$sDates."',
                                                                            executive               = '".$sExecutive."'
                            WHERE audit_id='$Id'");

        }else
        {
                      $sSQL  = ("INSERT INTO tbl_ppmeeting_machine_layout SET audit_id              = '$Id',
                                                                            lines_allocated         = '".$sLinesAllocated."',
                                                                            machine_line            = '".$sMachineLine."',
                                                                            target_per_hourday      = '".$sTargetPerHourDay."',
                                                                            cutting_date            = '".$sCuttingDate."',                                                                                  
                                                                            sewing_date             = '".$sSewingDate."',
                                                                            finishing_date          = '".$sFinishingDate."',    
                                                                            remarks_beading         = '".$sRemarksBeading."',                                                                                
                                                                            operation_application   = '".$sOperationApplication."',
                                                                            folder_attachment       = '".$sFolderAttachment."',
                                                                            machine_type            = '".$sMachineType."',
                                                                            total_lines_allocated   = '".$sTotalLinesAllocated."',
                                                                            attchments_required     = '".$sAttachmentRequired."',
                                                                            special_manual          = '".$sSpecialManual."',                                                                                
                                                                            pilot_date              = '".$sPilotDate."',
                                                                            wash_review_date        = '".$sWashReviewDate."',
                                                                            output_review_date      = '".$sOutputReviewDate."',
                                                                            bulk_review_date        = '".$sBulkReviewDate."',
                                                                            print_review_date       = '".$sPrintReviewDate."',
                                                                            colors_review_date      = '".$sColorsReviewDate."',
                                                                            ironing_review_date     = '".$sIroningReviewDate."',                                                                                
                                                                            risk_points             = '".$sRiskPoints."',
                                                                            action_plans            = '".$sActionPlan."',
                                                                            owners                  = '".$sOwners."',
                                                                            dates                   = '".$sDates."',
                                                                            executive               = '".$sExecutive."'");
        }
      
        $bFlag = $objDb->execute($sSQL);
        
        if($bFlag == false)
            exit($sSql);
?>
