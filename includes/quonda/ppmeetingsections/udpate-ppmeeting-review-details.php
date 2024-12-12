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
  $ApprovalComments = implode('|-|', IO::getArray("approvalComments"));
  $FitRevision      = IO::strValue("fitRevision");
  $BulkFabric       = IO::strValue("bulkFabric");
  $BulkTrim         = IO::strValue("bulkTrim");
  $ProducFactory    = IO::strValue("producFactory");
  $SampleRoom       = IO::strValue("sampleRoom");
  
        if(getDbValue("COUNT(1)", "tbl_ppmeeting_review_details", "audit_id='$Id'") > 0)
        {
                    $sSQL  = ("UPDATE tbl_ppmeeting_review_details SET      bulk_fabric        = '".($BulkFabric == 'Y'?'Y':'N')."',
                                                                            bulk_trim          = '".($BulkTrim == 'Y'?'Y':'N')."',
                                                                            production_factory = '".($ProducFactory == 'Y'?'Y':'N')."',                                                                                  
                                                                            sample_room        = '".($SampleRoom == 'Y'?'Y':'N')."',
                                                                            approval_comments  = '".$ApprovalComments."',    
                                                                            fit_revision       = '".$FitRevision."'
                            WHERE audit_id='$Id'");

        }else
        {
                      $sSQL  = ("INSERT INTO tbl_ppmeeting_review_details SET audit_id         = '$Id',
                                                                            bulk_fabric        = '".($BulkFabric == 'Y'?'Y':'N')."',
                                                                            bulk_trim          = '".($BulkTrim == 'Y'?'Y':'N')."',
                                                                            production_factory = '".($ProducFactory == 'Y'?'Y':'N')."',                                                                                  
                                                                            sample_room        = '".($SampleRoom == 'Y'?'Y':'N')."',
                                                                            approval_comments  = '".$ApprovalComments."',    
                                                                            fit_revision       = '".$FitRevision."'");
        }
      
        $bFlag = $objDb->execute($sSQL);
        
        if($bFlag == true)
        {
            $frontPicture   = $_FILES["frontImage"]['name'];
            $backPicture    = $_FILES["backImage"]['name'];
            
            if(!empty($frontPicture))
            {
                $exts       = explode('.', $frontPicture);
                $extension  = end($exts);
                
                if(in_array(strtolower($extension), array('jpg','jpeg','tiff','gif','bmp','png','pdf')))
                {        
                    $sPicture = ($Id."_FRONT_".rand(5, 15).'.'.$extension);
                    
                    if (@move_uploaded_file($_FILES["frontImage"]['tmp_name'], ($sBaseDir.QUONDA_PICS_DIR."ppmeeting/".$sPicture)))
                    {
                            $sSQL  = ("UPDATE tbl_ppmeeting_review_details SET  front_picture = '".$sPicture."' WHERE audit_id='$Id'");
                            $bFlag = $objDb->execute($sSQL);
                    }
                }
            }
            
            if(!empty($backPicture))
            {
                $exts       = explode('.', $backPicture);
                $extension  = end($exts);
                
                if(in_array(strtolower($extension), array('jpg','jpeg','tiff','gif','bmp','png','pdf')))
                {        
                    $sPicture = ($Id."_Back_".rand(5, 15).'.'.$extension);
                    
                    if (@move_uploaded_file($_FILES["backImage"]['tmp_name'], ($sBaseDir.QUONDA_PICS_DIR."ppmeeting/".$sPicture)))
                    {
                            $sSQL  = ("UPDATE tbl_ppmeeting_review_details SET  back_picture  = '".$sPicture."' WHERE audit_id='$Id'");
                            $bFlag = $objDb->execute($sSQL);
                    }
                }
            }
        }
        
        if($bFlag == false)
            exit($sSql);
  
?>
