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

        $sFabric        = IO::strValue("fabric");
        $sBtnColor      = IO::strValue("btn_color");
        $sSize          = IO::strValue("size");
        $sColor         = IO::strValue("color");
        $sTkt           = IO::strValue("tkt");
        $sConsump       = IO::strValue("consump");
        $sLock          = IO::strValue("lock_attach");
        $sChain         = IO::strValue("chain");
        $sNormal        = IO::strValue("normal");
        $sShank         = IO::strValue("shank");
        $sPcsPerGarment = IO::strValue("pcs_per_garment");
        $sExtraBtns     = IO::strValue("extra_btns");

        if(getDbValue("COUNT(1)", "tbl_ppmeeting_button_holes", "audit_id='$Id'") > 0)
        {
                    $sSQL  = ("UPDATE tbl_ppmeeting_button_holes SET    fabric          = '".$sFabric."',
                                                                        btn_color       = '".$sBtnColor."',
                                                                        size            = '".$sSize."',
                                                                        color           = '".$sColor."',
                                                                        tkt             = '".$sTkt."',
                                                                        consump         = '".$sConsump."',
                                                                        lock_attach     = '".$sLock."',
                                                                        chain           = '".$sChain."',
                                                                        normal          = '".$sNormal."',
                                                                        shank           = '".$sShank."',
                                                                        pcs_per_garment = '".$sPcsPerGarment."', 
                                                                        extra_btns      = '".$sExtraBtns."'
                            WHERE audit_id='$Id'");

        }else
        {
                      $sSQL  = ("INSERT INTO tbl_ppmeeting_button_holes SET audit_id         = '$Id',
                                                                        fabric          = '".$sFabric."',
                                                                        btn_color       = '".$sBtnColor."',
                                                                        size            = '".$sSize."',
                                                                        color           = '".$sColor."',
                                                                        tkt             = '".$sTkt."',
                                                                        consump         = '".$sConsump."',
                                                                        lock_attach     = '".$sLock."',
                                                                        chain           = '".$sChain."',
                                                                        normal          = '".$sNormal."',
                                                                        shank           = '".$sShank."',
                                                                        pcs_per_garment = '".$sPcsPerGarment."', 
                                                                        extra_btns      = '".$sExtraBtns."'");
        }
      
        $bFlag = $objDb->execute($sSQL);
      
        if($bFlag == false)
            exit($sSql);
?>
