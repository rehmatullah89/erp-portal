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

    $sParts         = IO::getArray("Parts");
    $sHMatching     = IO::getArray("HMatching");
    $sVMatchinge    = IO::getArray("VMatching");
    $sHpMatching    = IO::getArray("HpMatching");
    $sInPairs       = IO::getArray("InPairs");
    $sBalanceOnly   = IO::getArray("BalanceOnly");
    $sMirror        = IO::getArray("Mirror");
    $sRemarks       = IO::getArray("Remarks");

  if(!empty($sParts) && !empty($sHMatching))
  {
      foreach($sParts as $key => $sPart)
      {
          if($sPart != "" && $sHMatching[$key] != "")
          {
                $sSQL  = ("INSERT INTO tbl_ppmeeting_fabric_plied SET audit_id      = '$Id',
		                                                      parts         = '".$sPart."',
                                                                      h_matching    = '".$sHMatching[$key]."',
                                                                      v_matching    = '".$sVMatchinge[$key]."',
                                                                      hp_matching   = '".$sHpMatching[$key]."',
                                                                      in_pairs      = '".$sInPairs[$key]."',
                                                                      balance_only  = '".$sBalanceOnly[$key]."',
                                                                      mirror        = '".$sMirror[$key]."',    
                                                                      remarks       = '".$sRemarks[$key]."'");
		$bFlag = $objDb->execute($sSQL);
                
                if($bFlag == false)
                {
                    break;
                }
          }
      }
      
  }
?>
