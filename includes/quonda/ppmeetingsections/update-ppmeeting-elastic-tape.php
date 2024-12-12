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
    $sFacricColor       = IO::getArray("FabColor");
    $sElasticColors     = IO::getArray("ElasticColor");
    $sElasticWidth      = IO::getArray("ElasticWidth");
    $sConusumption      = IO::getArray("Conusumption");
    $sPreShrunk         = IO::getArray("PreShrunk");
    $sPlacement         = IO::getArray("Placement");

  if(!empty($sFacricColor) && !empty($sElasticColors))
  {
      foreach($sElasticColors as $key => $sElasticColor)
      {
          if($sElasticColor != "" && $sFacricColor[$key] != "")
          {
                $sSQL  = ("INSERT INTO tbl_ppmeeting_elastic_tape SET audit_id         = '$Id',
		                                                      fabric_color      = '".$sFacricColor[$key]."',
                                                                      elastic_color     = '".$sElasticColor."',
                                                                      elastic_width     = '".$sElasticWidth[$key]."',
                                                                      consumption       = '".$sConusumption[$key]."',
                                                                      pre_shrunk        = '".$sPreShrunk[$key]."',
		                                                      plcement          = '".$sPlacement[$key]."'");
		$bFlag = $objDb->execute($sSQL);
                
                if($bFlag == false)
                {
                    break;
                }
          }
      }
      
  }
?>
