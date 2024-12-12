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
        
        $sPanelInspection   = IO::strValue('panel_inspection');
        $sApprovepattern    = IO::strValue('approve_pattern');
        $sOther             = IO::strValue('other'); 
  
        if(getDbValue("COUNT(1)", "tbl_ppmeeting_panel_inspection", "audit_id='$Id'") > 0)
        {
                    $sSQL  = ("UPDATE tbl_ppmeeting_panel_inspection SET     panel_inspection  = '".($sPanelInspection == 'Y'?'Y':'N')."',
                                                                      approve_pattern   = '".($sApprovepattern == 'Y'?'Y':'N')."',
                                                                      other             = '".$sOther."'
                            WHERE audit_id='$Id'");

        }else
        {
                      $sSQL  = ("INSERT INTO tbl_ppmeeting_panel_inspection SET audit_id         = '$Id',
                                                                            panel_inspection  = '".($sPanelInspection == 'Y'?'Y':'N')."',
                                                                            approve_pattern   = '".($sApprovepattern == 'Y'?'Y':'N')."',
                                                                            other             = '".$sOther."'");
        }
      
        $bFlag = $objDb->execute($sSQL);
      
        if($bFlag == false)
            exit($sSql);
  
?>
