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
        $sMainLabel         = IO::strValue('mainlabel');
        $sMainLabelColor1   = IO::strValue('mainlabel_color1');
        $sMainLabelColor2   = IO::strValue('mainlabel_color2');
        $sSizeLabel         = IO::strValue('sizelabel');
        $sSizeLabelColor1   = IO::strValue('sizelabel_color1');
        $sSizeLabelColor2   = IO::strValue('sizelabel_color2');
        $sCareLabel         = IO::strValue('carelabel');
        $sCareLabelColor1   = IO::strValue('carelabel_color1');
        $sCareLabelColor2   = IO::strValue('carelabel_color2');
        $sBarCodeSticker    = IO::strValue('barcode_sticker'); //checkbox
        $sContents          = IO::strValue('contents');
        $sPriceTicket       = IO::strValue('price_ticket');
        $sAttachAtSizeLabel = IO::strValue('attachat_sizelabel');
        $sAttachMethod      = IO::strValue('attach_method');
  
        if(getDbValue("COUNT(1)", "tbl_ppmeeting_label_trim", "audit_id='$Id'") > 0)
        {
                    $sSQL  = ("UPDATE tbl_ppmeeting_label_trim SET      mainlabel           = '".$sMainLabel."',
                                                                        mainlabel_color1    = '".$sMainLabelColor1."',
                                                                        mainlabel_color2    = '".$sMainLabelColor2."',
                                                                        sizelabel           = '".$sSizeLabel."',
                                                                        sizelabel_color1    = '".$sSizeLabelColor1."',
                                                                        sizelabel_color2    = '".$sSizeLabelColor1."',
                                                                        carelabel           = '".$sCareLabel."',
                                                                        carelabel_color1    = '".$sCareLabelColor1."',
                                                                        carelabel_color2    = '".$sCareLabelColor2."',
                                                                        barcode_sticker     = '".($sBarCodeSticker == 'Y'?'Y':'N')."',
                                                                        contents            = '".$sContents."',    
                                                                        price_ticket        = '".$sPriceTicket."',    
                                                                        attachat_sizelabel  = '".$sAttachAtSizeLabel."',        
                                                                        attach_method       = '".$sAttachMethod."'
                            WHERE audit_id='$Id'");

        }else
        {
                    $sSQL  = ("INSERT INTO tbl_ppmeeting_label_trim SET   audit_id         = '$Id',
                                                                          mainlabel           = '".$sMainLabel."',
                                                                          mainlabel_color1    = '".$sMainLabelColor1."',
                                                                          mainlabel_color2    = '".$sMainLabelColor2."',
                                                                          sizelabel           = '".$sSizeLabel."',
                                                                          sizelabel_color1    = '".$sSizeLabelColor1."',
                                                                          sizelabel_color2    = '".$sSizeLabelColor1."',
                                                                          carelabel           = '".$sCareLabel."',
                                                                          carelabel_color1    = '".$sCareLabelColor1."',
                                                                          carelabel_color2    = '".$sCareLabelColor2."',
                                                                          barcode_sticker     = '".($sBarCodeSticker == 'Y'?'Y':'N')."',
                                                                          contents            = '".$sContents."',    
                                                                          price_ticket        = '".$sPriceTicket."',    
                                                                          attachat_sizelabel  = '".$sAttachAtSizeLabel."',        
                                                                          attach_method       = '".$sAttachMethod."'");
        }
     
        $bFlag = $objDb->execute($sSQL);
        
        if($bFlag == true)
        {
                   
            $sMainLabelAttach   = $_FILES["mainlabel_attachment"]['name'];
            $sSizeLabelAttach   = $_FILES["sizelabel_attachment"]['name'];
            $sCareLabelAttach   = $_FILES["carelabel_attachment"]['name'];
            $sCareLabelInstruct = $_FILES["carelabel_instructs"]['name'];
            
            if(!empty($sMainLabelAttach))
            {
                $exts       = explode('.', $sMainLabelAttach);
                $extension  = end($exts);
                
                if(in_array(strtolower($extension), array('jpg','jpeg','tiff','gif','bmp','png','pdf')))
                {        
                    $sPicture = ($Id."_MainLabel_".rand(5, 15).'.'.$extension);
                    
                    if (@move_uploaded_file($_FILES["mainlabel_attachment"]['tmp_name'], ($sBaseDir.QUONDA_PICS_DIR."ppmeeting/".$sPicture)))
                    {
                            $sSQL  = ("UPDATE tbl_ppmeeting_label_trim SET  mainlabel_attachment = '".$sPicture."' WHERE audit_id='$Id'");
                            $bFlag = $objDb->execute($sSQL);
                    }
                }
            }
            
            if(!empty($sSizeLabelAttach))
            {
                $exts       = explode('.', $sSizeLabelAttach);
                $extension  = end($exts);
                
                if(in_array(strtolower($extension), array('jpg','jpeg','tiff','gif','bmp','png','pdf')))
                {        
                    $sPicture = ($Id."_SizeLabel_".rand(5, 15).'.'.$extension);
                    
                    if (@move_uploaded_file($_FILES["sizelabel_attachment"]['tmp_name'], ($sBaseDir.QUONDA_PICS_DIR."ppmeeting/".$sPicture)))
                    {
                            $sSQL  = ("UPDATE tbl_ppmeeting_label_trim SET  sizelabel_attachment  = '".$sPicture."' WHERE audit_id='$Id'");
                            $bFlag = $objDb->execute($sSQL);
                    }
                }
            }
            
            if(!empty($sCareLabelAttach))
            {
                $exts       = explode('.', $sCareLabelAttach);
                $extension  = end($exts);
                
                if(in_array(strtolower($extension), array('jpg','jpeg','tiff','gif','bmp','png','pdf')))
                {        
                    $sPicture = ($Id."_CareLabel_".rand(5, 15).'.'.$extension);
                    
                    if (@move_uploaded_file($_FILES["carelabel_attachment"]['tmp_name'], ($sBaseDir.QUONDA_PICS_DIR."ppmeeting/".$sPicture)))
                    {
                            $sSQL  = ("UPDATE tbl_ppmeeting_label_trim SET  carelabel_attachment  = '".$sPicture."' WHERE audit_id='$Id'");
                            $bFlag = $objDb->execute($sSQL);
                    }
                }
            }
            
            if(!empty($sCareLabelInstruct))
            {
                $exts       = explode('.', $sCareLabelInstruct);
                $extension  = end($exts);
                
                if(in_array(strtolower($extension), array('jpg','jpeg','tiff','gif','bmp','png','pdf')))
                {        
                    $sPicture = ($Id."_Instructs_".rand(5, 15).'.'.$extension);
                    
                    if (@move_uploaded_file($_FILES["carelabel_instructs"]['tmp_name'], ($sBaseDir.QUONDA_PICS_DIR."ppmeeting/".$sPicture)))
                    {
                            $sSQL  = ("UPDATE tbl_ppmeeting_label_trim SET  carelabel_instructs  = '".$sPicture."' WHERE audit_id='$Id'");
                            $bFlag = $objDb->execute($sSQL);
                    }
                }
            }
        }
        
        if($bFlag == false)
            exit($sSql);
  
?>
