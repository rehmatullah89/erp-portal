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
        $sIroningPressing   = IO::strValue('ironing_pressing');
        $sSpecialBuck       = IO::strValue('special_buck');
        $sPakcingPackaging  = IO::strValue('pakcing_packaging');
        $sPriceTickets      = IO::strValue('price_tickets');
        $sSpecialTag        = IO::strValue('special_tag');
    
        if(getDbValue("COUNT(1)", "tbl_ppmeeting_pressing_packing", "audit_id='$Id'") > 0)
        {
                    $sSQL  = ("UPDATE tbl_ppmeeting_pressing_packing SET    ironing_pressing    = '".($sIroningPressing)."',
                                                                            special_buck        = '".($sSpecialBuck)."',
                                                                            pakcing_packaging   = '".($sPakcingPackaging)."',                                                                                  
                                                                            price_tickets       = '".($sPriceTickets)."',
                                                                            special_tag         = '".($sSpecialTag)."'
                            WHERE audit_id='$Id'");

        }else
        {
                      $sSQL  = ("INSERT INTO tbl_ppmeeting_pressing_packing SET audit_id        = '$Id',
                                                                            ironing_pressing    = '".($sIroningPressing)."',
                                                                            special_buck        = '".($sSpecialBuck)."',
                                                                            pakcing_packaging   = '".($sPakcingPackaging)."',                                                                                  
                                                                            price_tickets       = '".($sPriceTickets)."',
                                                                            special_tag         = '".($sSpecialTag)."'");
        }
      
        $bFlag = $objDb->execute($sSQL);

        if($bFlag == false)
            exit($sSql);
  
?>
