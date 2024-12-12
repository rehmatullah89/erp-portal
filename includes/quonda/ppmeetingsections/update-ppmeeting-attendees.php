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

  $Ateendess    = IO::getArray("Name");
  $Designations = IO::getArray("Designation");

  if(!empty($Ateendess) && !empty($Designations))
  {
      foreach($Ateendess as $key => $sAttendee)
      {
          if($sAttendee != "" && $Designations[$key] != "")
          {
                $sSQL  = ("INSERT INTO tbl_ppmeeting_attendees SET audit_id         = '$Id',
		                                                      name          = '".$sAttendee."',
		                                                      designation   = '".$Designations[$key]."'");
		$bFlag = $objDb->execute($sSQL);
                
                if($bFlag == false)
                {
                    break;
                }
          }
      }
      
  }
?>
