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
       
        $sBaseFabric        = IO::strValue('base_fabric');
        $sLayerColor        = IO::strValue('layer_color');
        $sPocketPrinting    = IO::strValue('pocket_printing');
        $sBackPrinting      = IO::strValue('back_printing');
        $Comments           = IO::strValue('comments');
  
        if(getDbValue("COUNT(1)", "tbl_ppmeeting_pocketing_lining", "audit_id='$Id'") > 0)
        {
                    $sSQL  = ("UPDATE tbl_ppmeeting_pocketing_lining SET  base_fabric   = '".$sBaseFabric."',
                                                                                layer_color = '".$sLayerColor."',    
                                                                                pocket_printing  = '".$sPocketPrinting."',    
                                                                                back_printing   = '".$sBackPrinting."',    
                                                                                comments            = '".$Comments."'
                                WHERE audit_id='$Id'");

        }else
        {
                      $sSQL  = ("INSERT INTO tbl_ppmeeting_pocketing_lining SET audit_id         = '$Id',
                                                                                base_fabric   = '".$sBaseFabric."',
                                                                                layer_color = '".$sLayerColor."',    
                                                                                pocket_printing  = '".$sPocketPrinting."',    
                                                                                back_printing   = '".$sBackPrinting."',    
                                                                                comments            = '".$Comments."'");
        }
      
        $bFlag = $objDb->execute($sSQL);
        
        if($bFlag == false)
            exit($sSql);
  
?>
