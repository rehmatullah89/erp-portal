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
        $ProducKeyPoint       = implode('|-|', IO::getArray("producKeyPoint"));
        $FitSample            = IO::strValue("fitSample");
        $FitSampleComments    = IO::strValue("fitSampleComments");
        $PpSample             = IO::strValue("ppSample");
        $PpSampleComments     = IO::strValue("ppSampleComments");
        $SizeSet              = IO::strValue("sizeSet");
        $SizeSetComments      = IO::strValue("sizeSetComments");
        $PatternCorrection    = IO::strValue("patternCorrection");
        $PatternComments      = IO::strValue("patternComments");
        $TechPack             = IO::strValue("techPack");
        $TechPackComments     = IO::strValue("techPackComments");
        $Comments             = IO::strValue("comments");
  
        if(getDbValue("COUNT(1)", "tbl_ppmeeting_foucspoints_comments", "audit_id='$Id'") > 0)
        {
                    $sSQL  = ("UPDATE tbl_ppmeeting_foucspoints_comments SET  fit_sample_approved   = '".($FitSample == 'Y'?'Y':'N')."',
                                                                                pp_sample_approved  = '".($PpSample == 'Y'?'Y':'N')."',
                                                                                size_set_approved   = '".($SizeSet == 'Y'?'Y':'N')."',                                                                                  
                                                                                pattern_correction  = '".($PatternCorrection == 'Y'?'Y':'N')."',
                                                                                tech_pack           = '".($TechPack == 'Y'?'Y':'N')."',
                                                                                fit_sample_comments = '".$FitSampleComments."',    
                                                                                pp_sample_comments  = '".$PpSampleComments."',    
                                                                                size_set_comments   = '".$SizeSetComments."',    
                                                                                pattern_comments    = '".$PatternComments."',    
                                                                                techpack_comments   = '".$TechPackComments."', 
                                                                                production_key_points = '".$ProducKeyPoint."', 
                                                                                comments            = '".$Comments."'
                                WHERE audit_id='$Id'");

        }else
        {
                      $sSQL  = ("INSERT INTO tbl_ppmeeting_foucspoints_comments SET audit_id         = '$Id',
                                                                                fit_sample_approved   = '".($FitSample == 'Y'?'Y':'N')."',
                                                                                pp_sample_approved  = '".($PpSample == 'Y'?'Y':'N')."',
                                                                                size_set_approved   = '".($SizeSet == 'Y'?'Y':'N')."',                                                                                  
                                                                                pattern_correction  = '".($PatternCorrection == 'Y'?'Y':'N')."',
                                                                                tech_pack           = '".($TechPack == 'Y'?'Y':'N')."',
                                                                                fit_sample_comments = '".$FitSampleComments."',    
                                                                                pp_sample_comments  = '".$PpSampleComments."',    
                                                                                size_set_comments   = '".$SizeSetComments."',    
                                                                                pattern_comments    = '".$PatternComments."',    
                                                                                techpack_comments   = '".$TechPackComments."', 
                                                                                production_key_points = '".$ProducKeyPoint."', 
                                                                                comments            = '".$Comments."'");
        }
      
        $bFlag = $objDb->execute($sSQL);
        
        if($bFlag == false)
            exit($sSql);
  
?>
