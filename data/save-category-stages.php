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

	@require_once("../requires/session.php");
		
	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Brand      = IO::intValue('Brand');
        $Categories = IO::strValue('Categories');
	$bFlag      = $objDb->execute("BEGIN");
        
        $iCategories = explode(",", $Categories);
        $sStagesList = getList("tbl_production_stages", "id", "title", "", "position");
        
        $sSQL  = "DELETE FROM tbl_brand_stages WHERE brand_id='$Brand'";
        $bFlag = $objDb->execute($sSQL);
            
        if($bFlag ==  true)
        {
            foreach($iCategories as $Category)
            {
                $iCatStageDays = array();
                
                foreach($sStagesList as $iStage => $sStage)
                {
                    $Days = IO::strValue("Stage{$Category}_{$iStage}");
                    
                    if($Days > 0)
                        $iCatStageDays[$iStage] = $Days;
                }
                
                if(!empty($iCatStageDays))
                {
                      $sSQL  = ("INSERT INTO tbl_brand_stages SET brand_id    = '$Brand',
                                                                category_id   = '".$Category."',
                                                                stages        = '".implode(",", array_keys($iCatStageDays))."',
                                                                days          = '".implode(",", $iCatStageDays)."'");
                      $bFlag = $objDb->execute($sSQL);
                      
                      if($bFlag == false)
                          break;
                }
            }
        }
        
	if($bFlag == true)
	{
		$_SESSION['Flag'] = "DATA_SAVED";
		
		$objDb->execute("COMMIT");
                 
                header("Location: add-category-stages.php?Id={$Brand}&Categories={$Categories}");
	}
	else
	{
		$_SESSION['Flag'] = "DB_ERROR";
		
		$objDb->execute("ROLLBACK");
		header("Location: add-category-stages.php?Id={$Brand}&Categories={$Categories}");
	}

	
	$objDb->close( );	
	$objDbGlobal->close( );

	@ob_end_flush( );
?>