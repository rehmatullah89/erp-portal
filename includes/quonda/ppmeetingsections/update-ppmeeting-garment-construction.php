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
        $sApprovedSample    = IO::strValue('approved_sample');
        $sSpecialMarking    = IO::strValue('special_marking');
        $sSpecialMethod     = IO::strValue('special_method');
        $sStayStitch        = IO::strValue('stay_stitch');
        $sTackingOperation  = IO::strValue('tacking_operation');
        $sExtraOperation    = IO::strValue('extra_operation');
        $sActionPlan        = IO::strValue('action_plan');
        $sDetailChecks      = IO::strValue('detail_checks');
        $sKeyPoints         = IO::strValue('key_points');
  
        if(getDbValue("COUNT(1)", "tbl_ppmeeting_garment_construction", "audit_id='$Id'") > 0)
        {
                    $sSQL  = ("UPDATE tbl_ppmeeting_garment_construction SET    approved_sample    = '".($sApprovedSample == 'Y'?'Y':'N')."',
                                                                                special_marking    = '".($sSpecialMarking == 'Y'?'Y':'N')."',
                                                                                special_method     = '".($sSpecialMethod == 'Y'?'Y':'N')."',                                                                                  
                                                                                stay_stitch        = '".($sStayStitch == 'Y'?'Y':'N')."',
                                                                                tacking_operation  = '".($sTackingOperation == 'Y'?'Y':'N')."',
                                                                                extra_operation    = '".($sExtraOperation == 'Y'?'Y':'N')."',
                                                                                action_plan        = '".$sActionPlan."',
                                                                                detail_checks      = '".$sDetailChecks."',    
                                                                                key_points         = '".$sKeyPoints."'
                            WHERE audit_id='$Id'");

        }else
        {
                      $sSQL  = ("INSERT INTO tbl_ppmeeting_garment_construction SET audit_id         = '$Id',
                                                                            approved_sample    = '".($sApprovedSample == 'Y'?'Y':'N')."',
                                                                                special_marking    = '".($sSpecialMarking == 'Y'?'Y':'N')."',
                                                                                special_method     = '".($sSpecialMethod == 'Y'?'Y':'N')."',                                                                                  
                                                                                stay_stitch        = '".($sStayStitch == 'Y'?'Y':'N')."',
                                                                                tacking_operation  = '".($sTackingOperation == 'Y'?'Y':'N')."',
                                                                                extra_operation    = '".($sExtraOperation == 'Y'?'Y':'N')."',
                                                                                action_plan        = '".$sActionPlan."',
                                                                                detail_checks      = '".$sDetailChecks."',    
                                                                                key_points         = '".$sKeyPoints."'");
        }
      
        $bFlag = $objDb->execute($sSQL);
           
        if($bFlag == false)
            exit($sSql);
  
?>
