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
  $Sizes        = IO::getArray("Sizes");
  $EanCodes     = IO::getArray("EanCodes");
  $Positions    = IO::getArray("Positions");
  $EanResults   = IO::getArray("EanCodeResults");
  $Counter      = 1;

  if(!empty($Sizes) && !empty($EanCodes))
  {
     
      $bFlag = $objDb->execute("BEGIN");
      
      $sSQL  = "DELETE FROM tbl_qa_ean_codes WHERE audit_id='$Id'";
      $bFlag = $objDb->execute($sSQL);
      
      if($bFlag ==  true)
      {
            foreach($EanCodes as $key => $sCode)
            {
                $iSize          = $Sizes[$key];
                $sPosition      = $Positions[$key];
                $sEanResult     = $EanResults[$key];
               
                if($sCode != "" && $iSize != "")
                {
                      $sSQL  = ("INSERT INTO tbl_qa_ean_codes SET audit_id  = '$Id',
                                                                serial      = '".$Counter."',
                                                                size_id     = '".$iSize."',
                                                                position    = '".$sPosition."',
                                                                result     = '".$sEanResult."',    
                                                                code        = '".$sCode."'");
                      $bFlag = $objDb->execute($sSQL);

                      if($bFlag == false)
                      {
                          break;
                      }

                      $Counter ++;
                }
            }
      }
      
      if($bFlag == true && $Id > 0)
      {
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
      }
  }
?>
