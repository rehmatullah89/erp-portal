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
    $sFrontCenterLine           = IO::strValue('fron_centerline');
    $sCuffCenterLine            = IO::strValue('cuff_centerline');
    $sBackCenterLine            = IO::strValue('back_centerline');
    $sCollarCenterLine          = IO::strValue('collar_centerline');
    $sSpecialColorCenterLine    = IO::strValue('special_centerline');
    $sSleeveCenterLine          = IO::strValue('sleeve_centerline');
  
        if(getDbValue("COUNT(1)", "tbl_ppmeeting_special_centerline", "audit_id='$Id'") > 0)
        {
                    $sSQL  = ("UPDATE tbl_ppmeeting_special_centerline SET  fron_centerline      = '".($sFrontCenterLine == 'Y'?'Y':'N')."',
                                                                            cuff_centerline      = '".($sCuffCenterLine == 'Y'?'Y':'N')."',
                                                                            back_centerline      = '".($sBackCenterLine == 'Y'?'Y':'N')."',                                                                                  
                                                                            collar_centerline    = '".($sCollarCenterLine == 'Y'?'Y':'N')."',
                                                                            special_centerline   = '".($sSpecialColorCenterLine == 'Y'?'Y':'N')."',
                                                                            sleeve_centerline    = '".$sSleeveCenterLine."'
                            WHERE audit_id='$Id'");

        }else
        {
                      $sSQL  = ("INSERT INTO tbl_ppmeeting_special_centerline SET audit_id         = '$Id',
                                                                            fron_centerline      = '".($sFrontCenterLine == 'Y'?'Y':'N')."',
                                                                            cuff_centerline      = '".($sCuffCenterLine == 'Y'?'Y':'N')."',
                                                                            back_centerline      = '".($sBackCenterLine == 'Y'?'Y':'N')."',                                                                                  
                                                                            collar_centerline    = '".($sCollarCenterLine == 'Y'?'Y':'N')."',
                                                                            special_centerline   = '".($sSpecialColorCenterLine == 'Y'?'Y':'N')."',
                                                                            sleeve_centerline    = '".$sSleeveCenterLine."'");
        }
      
        $bFlag = $objDb->execute($sSQL);
        
        if($bFlag == false)
            exit($sSql);
  
?>
