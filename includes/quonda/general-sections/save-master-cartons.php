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

    $Comments         = IO::strValue("Comments");
    $TotalCartons     = IO::intValue("TotalCartons");
    $AllCartons       = IO::getArray("TCartons");

    $bFlag = $objDb->execute("BEGIN");

    if(!empty($TotalCartons))
    {
          if(getDbValue("COUNT(1)", "tbl_qa_report_details", "audit_id='$Id'") > 0)
          {
            $sSQL  = ("UPDATE tbl_qa_report_details SET  master_cartons       = '".$TotalCartons."',
                                                     master_cartons_comments  = '".$Comments."'
                                                     WHERE audit_id='$Id'");
          }
          else
          {
              $sSQL  = ("INSERT INTO tbl_qa_report_details SET audit_id         = '$Id',
                                                     master_cartons           = '".$TotalCartons."',
                                                     master_cartons_comments  = '".$Comments."'");
          }
             $bFlag = $objDb->execute($sSQL);
    }
  
    if($bFlag ==  true)
    {
        $sSQL  = "DELETE FROM tbl_qa_carton_details WHERE audit_id='$Id'";
        $bFlag = $objDb->execute($sSQL);
    }
        
    if($bFlag ==  true)
    {
          $Weights      = IO::getArray("GrossWeight");
          $Lengths      = IO::getArray("Length");
          $Widths       = IO::getArray("Width");
          $Heights      = IO::getArray("Height");
          $CountryBlocks= IO::getArray("CountryBlock");

          $BlockIndex = 0;
          $CartonNo   = 1;
          foreach($AllCartons as $key => $Carton)
          {
              $Weight     = (float)$Weights[$key];
              $Length     = (float)$Lengths[$key];
              $Width      = (float)$Widths[$key];
              $Height     = (float)$Heights[$key];
              
              if($Weight > 0 && $bFlag == true)
              {
                    $sSQL  = ("INSERT INTO tbl_qa_carton_details SET audit_id       = '$Id',
                                                                          carton_no     = '".$CartonNo."',
                                                                          gross_weight  = '".$Weight."',
                                                                          `length`        = '".$Length."',
                                                                          width         = '".$Width."',
                                                                          height        = '".$Height."'");
                    $bFlag = $objDb->execute($sSQL);
                    
                    $CartonNo ++;
              }
          }
    }
    
/*    if($bFlag == false)
    {
        echo $sSQL;exit;
    }*/
        
  
?>
