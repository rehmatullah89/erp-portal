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

        $sColor             = IO::strValue("color");
        $sSewingThread      = IO::strValue("sewing_thread");
        $sOverLock          = IO::strValue("over_lock");
        $sButtonHole        = IO::strValue("button_hole");                
        $sBtmSize           = IO::strValue("btm_size");
        $sTopColor          = IO::strValue("top_color");
        $sBtmColor          = IO::strValue("btm_color");
        $sTicketSize1       = IO::strValue("ticket_size1");
        $sColorSpclNdl      = IO::strValue("color_spcl_ndl");
        $sConsumpMachine    = IO::strValue("consum_machine"); 
        $sTicketSize2       = IO::strValue("ticket_size2");
        $sColorQty          = IO::strValue("color_qty");
        $sStitchSpi         = IO::strValue("stitch_spi");

        if(getDbValue("COUNT(1)", "tbl_ppmeeting_sewing_details", "audit_id='$Id'") > 0)
        {
                    $sSQL  = ("UPDATE tbl_ppmeeting_sewing_details SET  color           = '".$sColor."',
                                                                        sewing_thread   = '".$sSewingThread."',
                                                                        over_lock       = '".$sOverLock."',
                                                                        button_hole     = '".$sButtonHole."',
                                                                        btm_size        = '".$sBtmSize."',
                                                                        top_color       = '".$sTopColor."',
                                                                        btm_color       = '".$sBtmColor."',
                                                                        ticket_size1    = '".$sTicketSize1."',
                                                                        color_spcl_ndl  = '".$sColorSpclNdl."',
                                                                        consum_machine  = '".$sConsumpMachine."',
                                                                        ticket_size2    = '".$sTicketSize2."', 
                                                                        color_qty       = '".$sColorQty."',     
                                                                        stitch_spi      = '".$sStitchSpi."'
                            WHERE audit_id='$Id'");

        }else
        {
                      $sSQL  = ("INSERT INTO tbl_ppmeeting_sewing_details SET   audit_id         = '$Id',
                                                                                color           = '".$sColor."',
                                                                                sewing_thread   = '".$sSewingThread."',
                                                                                over_lock       = '".$sOverLock."',
                                                                                button_hole     = '".$sButtonHole."',
                                                                                btm_size        = '".$sBtmSize."',
                                                                                top_color       = '".$sTopColor."',
                                                                                btm_color       = '".$sBtmColor."',
                                                                                ticket_size1    = '".$sTicketSize1."',
                                                                                color_spcl_ndl  = '".$sColorSpclNdl."',
                                                                                consum_machine  = '".$sConsumpMachine."',
                                                                                ticket_size2    = '".$sTicketSize2."', 
                                                                                color_qty       = '".$sColorQty."',     
                                                                                stitch_spi      = '".$sStitchSpi."'");
        }
      
        $bFlag = $objDb->execute($sSQL);
      
        if($bFlag == false)
            exit($sSql);
?>
