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
  $Result       = IO::strValue("Result");
  $Comments     = IO::strValue("Comments");
  $Format       = IO::strValue("BarcodeFormat");

  $objDb->execute("BEGIN");

    if(getDbValue("COUNT(1)", "tbl_qa_hohenstein", "audit_id='$Id'") > 0)
    {
      $sSQL  = ("UPDATE tbl_qa_hohenstein SET  ean_result     = '".$Result."',
                                   ean_comments   = '".$Comments."',
                                               barcode_format = '".$Format."'
                                               WHERE audit_id='$Id'");
    }
    else
    {
        $sSQL  = ("INSERT INTO tbl_qa_hohenstein SET audit_id = '$Id',
                                              barcode_format  = '".$Format."',
                                              ean_result      = '".$Result."',
                                              ean_comments    = '".$Comments."'");
    }

      $bFlag = $objDb->execute($sSQL);

      if($bFlag) {
          
          $objDb2->execute("COMMIT");

      } else {
          
          $objDb2->execute("ROLLBACK");
        
      }

?>
