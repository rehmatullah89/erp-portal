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
        
        $sBlockCutting      = IO::strValue('block_cutting');
        $sBandKnifeCutting  = IO::strValue('band_knife_cutting');
        $sComputerCutting   = IO::strValue('computer_cutting');
        $sDieCutting        = IO::strValue('die_cutting');
        $sSpecialCutting    = IO::strValue('special_cutting');
        $sKnifeCutting      = IO::strValue('knife_cutting'); 
  
        if(getDbValue("COUNT(1)", "tbl_ppmeeting_cutting_details", "audit_id='$Id'") > 0)
        {
                    $sSQL  = ("UPDATE tbl_ppmeeting_cutting_details SET     block_cutting           = '".($sBlockCutting == 'Y'?'Y':'N')."',
                                                                            band_knife_cutting      = '".($sBandKnifeCutting == 'Y'?'Y':'N')."',
                                                                            computer_cutting        = '".($sComputerCutting == 'Y'?'Y':'N')."',
                                                                            die_cutting             = '".($sDieCutting == 'Y'?'Y':'N')."',
                                                                            special_cutting         = '".($sSpecialCutting == 'Y'?'Y':'N')."',
                                                                            knife_cutting           = '".($sKnifeCutting == 'Y'?'Y':'N')."'                                                                                                                                                            
                            WHERE audit_id='$Id'");

        }else
        {
                      $sSQL  = ("INSERT INTO tbl_ppmeeting_cutting_details SET audit_id         = '$Id',
                                                                            block_cutting           = '".($sBlockCutting == 'Y'?'Y':'N')."',
                                                                            band_knife_cutting      = '".($sBandKnifeCutting == 'Y'?'Y':'N')."',
                                                                            computer_cutting        = '".($sComputerCutting == 'Y'?'Y':'N')."',
                                                                            die_cutting             = '".($sDieCutting == 'Y'?'Y':'N')."',
                                                                            special_cutting         = '".($sSpecialCutting == 'Y'?'Y':'N')."',
                                                                            knife_cutting           = '".($sKnifeCutting == 'Y'?'Y':'N')."'");
        }
      
        $bFlag = $objDb->execute($sSQL);
      
        if($bFlag == false)
            exit($sSql);
  
?>
