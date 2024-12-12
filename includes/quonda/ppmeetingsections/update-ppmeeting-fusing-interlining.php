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

  $sFabric      = IO::getArray("Fabric");
  $sComponents  = IO::getArray("Component");  
  $sArticle     = IO::getArray("Article");
  $sFbeClr      = IO::getArray("FbeClr");
  $sTemperature = IO::getArray("Temperature");
  $sMcBeltSpeed = IO::getArray("McBeltSpeed");
  $sPressure    = IO::getArray("Pressure");
  $sGrainLine   = IO::getArray("GrainLine");

  if(!empty($sComponents) && !empty($sFabric))
  {
      foreach($sComponents as $key => $sComponent)
      {
          if($sComponent != "" && $sFabric[$key] != "")
          {
                $sSQL  = ("INSERT INTO tbl_ppmeeting_fusing_interlining SET audit_id     = '$Id',
                                                                            fabric       = '".$sFabric[$key]."',
                                                                            component    = '".$sComponent."',
                                                                            article      = '".$sArticle[$key]."',
                                                                            fbe_clr      = '".$sFbeClr[$key]."',    
                                                                            temperature  = '".$sTemperature[$key]."',
                                                                            mcbelt_speed = '".$sMcBeltSpeed[$key]."',
                                                                            pressure     = '".$sPressure[$key]."',
                                                                            grain_line   = '".$sGrainLine[$key]."'");
		$bFlag = $objDb->execute($sSQL);
                
                if($bFlag == false)
                {
                    break;
                }
          }
      }
      
  }
?>
