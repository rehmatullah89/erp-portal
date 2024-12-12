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
  $Result           = IO::strValue("Result");
  $Comments         = IO::strValue("Comments");
  $TotalAssorted    = IO::intValue("TotalAssortedCartons");
  $WrongAssorted    = IO::intValue("WrongAssortedCartons");
  
  $AllCartons       = IO::getArray("TCartons");
  
  if(!empty($Result))
  {
        $bFlag = $objDb->execute("BEGIN");
      
        $sSQL  = "DELETE FROM tbl_qa_assortment WHERE audit_id='$Id'";
        $bFlag = $objDb->execute($sSQL);

        if($bFlag == true)
        {
                $sSQL  = ("INSERT INTO tbl_qa_assortment SET audit_id    = '$Id',
                                                  total_cartons_tested   = '".$TotalAssorted."',
                                                  wrong_assorted_cartons = '".$WrongAssorted."',
                                                  result                 = '".$Result."',
                                                  comments               = '".$Comments."'");
              $bFlag = $objDb->execute($sSQL);
        }

  }
?>
