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
  $TotalCartons     = IO::intValue("TotalCartons");
  
  $AllCartons       = IO::getArray("TCartons");
  
  if(!empty($Result))
  {
        $bFlag = $objDb->execute("BEGIN");
      
        if(getDbValue("COUNT(1)", "tbl_qa_hohenstein", "audit_id='$Id'") > 0)
        {
          $sSQL  = ("UPDATE tbl_qa_hohenstein SET  master_cartons           = '".$TotalCartons."',
                                                   master_cartons_result    = '".$Result."',
                                                   master_cartons_comments  = '".$Comments."'
                                                   WHERE audit_id='$Id'");
        }
        else
        {
            $sSQL  = ("INSERT INTO tbl_qa_hohenstein SET audit_id         = '$Id',
                                                   master_cartons           = '".$TotalCartons."',
                                                   master_cartons_result    = '".$Result."',
                                                   master_cartons_comments  = '".$Comments."'");
        }
           $bFlag = $objDb->execute($sSQL);
      
        if($bFlag ==  true)
        {
            $sSQL  = "DELETE FROM tbl_qa_master_cartons WHERE audit_id='$Id'";
            $bFlag = $objDb->execute($sSQL);
        }
        
        if($bFlag ==  true)
        {
              $Weights    = IO::getArray("GrossWeight");
              $Lengths    = IO::getArray("Length");
              $Widths     = IO::getArray("Width");
              $Heights    = IO::getArray("Height");

              foreach($AllCartons as $key => $Carton)
              {
                  $Weight     = $Weights[$key];
                  $Length     = $Lengths[$key];
                  $Width      = $Widths[$key];
                  $Height     = $Heights[$key];

                  if($Weight != "")
                  {
                        $sSQL  = ("INSERT INTO tbl_qa_master_cartons SET audit_id       = '$Id',
                                                                              carton_no     = '".$key."',
                                                                              gross_weight  = '".$Weight."',
                                                                              length        = '".$Length."',
                                                                              width         = '".$Width."',
                                                                              height        = '".$Height."'");
                        $bFlag = $objDb->execute($sSQL);

                        if($bFlag == false)
                        {
                            break;
                        }

                        $Counter ++;
                  }
              }
        }
        
  }
?>
