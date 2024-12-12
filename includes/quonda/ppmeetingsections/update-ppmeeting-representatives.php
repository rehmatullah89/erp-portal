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

  $Names        = IO::getArray("Name");
  $Designations = IO::getArray("Designation");
  $Attendance   = IO::getArray("Attendance");
  
  if(!empty($Names) && !empty($Designations))
  {
      foreach($Designations as $key => $sDesignation)
      {
          if($sDesignation != "" && $Names[$key] != "")
          {
                $sSQL  = ("INSERT INTO tbl_ppmeeting_representatives SET audit_id         = '$Id',
		                                                      name          = '".$Names[$key]."',
                                                                      designation   = '".$sDesignation."',
		                                                      presence      = '".$Attendance[$key]."'");
		$bFlag = $objDb->execute($sSQL);
                
                if($bFlag == false)
                {
                    break;
                }
          }
      }
      
  }
?>
