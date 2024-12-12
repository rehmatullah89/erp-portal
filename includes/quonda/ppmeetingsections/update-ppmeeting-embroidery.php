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
        $sApprovedStatuses    = IO::getArray("ApprovedStd");
        $sMaterialStatus      = IO::getArray("MaterialStatus");
        $sSubContractTa       = IO::getArray("SubContractTa");
        $sSubContractDetails  = IO::getArray("SubContractDetails");


  if(!empty($sApprovedStatuses) && !empty($sMaterialStatus))
  {
      foreach($sApprovedStatuses as $key => $sApprovedStatus)
      {
          if($sApprovedStatus != "" && $sMaterialStatus[$key] != "")
          {
                $sSQL  = ("INSERT INTO tbl_ppmeeting_embroidery    SET audit_id             = '$Id',
		                                                      approved_status       = '".$sApprovedStatus."',
                                                                      material_status       = '".$sMaterialStatus[$key]."',
                                                                      subcontract_ta        = '".$sSubContractTa[$key]."',
		                                                      subcontract_details   = '".$sSubContractDetails[$key]."'");
		$bFlag = $objDb->execute($sSQL);
                
                if($bFlag == false)
                {
                    break;
                }
          }
      }
  }
?>
