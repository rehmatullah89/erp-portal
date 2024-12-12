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

  $Id                   = IO::strValue("Id");
  $Result               = IO::strValue("Result");
  $Comments             = IO::strValue("Comments");
  
  $Colors               = IO::getArray("Color");  
  $ColorResult          = IO::getArray("ColorResult");
  
  $PieceColors          = IO::getArray("PieceColor");
  $PColorResult         = IO::getArray("PColorResult");

  $sAuditCode   = "S".str_pad($Id, 4, '0', STR_PAD_LEFT);
  $sAuditDate   = getDbValue("audit_date", "tbl_qa_reports", "id='$Id'");   
  @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);
  
  @mkdir(($sBaseDir.$sBaseDir.CARTONS_PICS_DIR."/".$sYear), 0777);
  @mkdir(($sBaseDir.$sBaseDir.CARTONS_PICS_DIR."/".$sYear."/".$sMonth), 0777);
  @mkdir(($sBaseDir.$sBaseDir.CARTONS_PICS_DIR."/".$sYear."/".$sMonth."/".$sDay), 0777);

  $sWeightsDir = ($sBaseDir.$sBaseDir.CARTONS_PICS_DIR."/".$sYear."/".$sMonth."/".$sDay."/");
  
  //if(!empty($Result))
  {
        $bFlag = $objDb->execute("BEGIN");

        $sSQL  = "DELETE FROM tbl_qa_weight_conformity WHERE audit_id='$Id' AND `type`='F'";
        $bFlag = $objDb->execute($sSQL);

        if($bFlag ==  true)
        {
              foreach($Colors as $key => $sColor)
              {
                      $sSQL  = ("INSERT INTO tbl_qa_weight_conformity SET audit_id  = '$Id',
                                                                          color    = '".$sColor."',
                                                                          `type`    = 'F',
                                                                          result   = '".$ColorResult[$key]."'");
                      $bFlag = $objDb->execute($sSQL);

                      if($bFlag == false) 
                            break;
              }
        }

        if($bFlag ==  true)
        {
              $sSQL  = "DELETE FROM tbl_qa_weight_details WHERE audit_id='$Id' AND `type`='F'";
              $bFlag = $objDb->execute($sSQL);
        }

        if($bFlag ==  true)
        {
              $Counter = 1;

              foreach($Colors as $key => $sColor)
              {
                  if($sColor != "")
                  {
                          $iSerial  = 1;
                          $Weights  = IO::getArray("Weight{$Counter}");  

                          foreach($Weights as $iWeight)
                          {
                              if($iWeight != "")
                              {
                                  $Files     = $_FILES["File{$Counter}_{$iSerial}"]['name'];

                                  foreach($Files as $key => $File)
                                  {
                                      $exts       = explode('.', $File);
                                      $extension  = end($exts);

                                      if(@in_array(strtolower($extension), array('jpg','jpeg')))
                                      {        
                                          $Picture = ($sAuditCode."_Fabric_".$sColor."-".$iSerial."_".rand(10, 1000).'.'.$extension);

                                          if (@move_uploaded_file($_FILES["File{$Counter}_{$iSerial}"]['tmp_name'][$key], ($sWeightsDir.$Picture)))
                                          {
                                                  $sSQL     = ("INSERT INTO tbl_qa_weight_pictures SET audit_id = '$Id',
                                                                                      color = '".$sColor."', 
                                                                                      `type`    = 'F',
                                                                                      serial   = '".$iSerial."',
                                                                                      picture   = '".$Picture."'");
                                                  $bFlag = $objDb->execute($sSQL);
                                          }                                        
                                      }
                                  }

                                  $sSQL     = ("INSERT INTO tbl_qa_weight_details SET audit_id = '$Id',
                                                                                      color    = '".$sColor."', 
                                                                                      `type`   = 'F',
                                                                                      serial   = '".$iSerial."',
                                                                                      weight   = '".$iWeight."'");
                                  $bFlag = $objDb->execute($sSQL);

                                  if($bFlag == false)
                                    break;
                              }

                              $iSerial ++;
                          }                    
                  }

                  $Counter++;
              }
        }
      /********************/
      
      if($bFlag ==  true)
      {
        $sSQL  = "DELETE FROM tbl_qa_weight_conformity WHERE audit_id='$Id' AND `type`='P'";
        $bFlag = $objDb->execute($sSQL);
      }
      
      if($bFlag ==  true)
      {
            foreach($PieceColors as $key => $sColor)
            {
                if($sColor != "" && $bFlag == true)
                {                       
                        
                    $sSQL  = ("INSERT INTO tbl_qa_weight_conformity SET audit_id  = '$Id',
                                                                    `type` = 'P',    
                                                                    color    = '".$sColor."',
                                                                    result   = '".$PColorResult[$key]."'");
                    $bFlag = $objDb->execute($sSQL);                       
                        
                    if($bFlag == false)
                        break;
                }
            }
      }

      
      if($bFlag ==  true)
      {
            $sSQL  = "DELETE FROM tbl_qa_weight_details WHERE audit_id='$Id' AND `type`='P'";
            $bFlag = $objDb->execute($sSQL);
      }
      
      if($bFlag ==  true)
      {
            $Counter = 1;
            
            foreach($PieceColors as $key => $sColor)
            {
                if($sColor != "")
                {
                        $iSerial  = 1;
                        $Weights  = IO::getArray("PieceWeight{$Counter}");  
                        
                        foreach($Weights as $iWeight)
                        {
                            if($iWeight != "")
                            {
                                $Files     = $_FILES["PieceFile{$Counter}_{$iSerial}"]['name'];

                                foreach($Files as $key => $File)
                                {
                                    $exts       = explode('.', $File);
                                    $extension  = end($exts);

                                    if(@in_array(strtolower($extension), array('jpg','jpeg')))
                                    {        
                                        $Picture = ($sAuditCode."_Piece_".$sColor."-".$iSerial."_".rand(10, 1000).'.'.$extension);

                                        if (@move_uploaded_file($_FILES["PieceFile{$Counter}_{$iSerial}"]['tmp_name'][$key], ($sWeightsDir.$Picture)))
                                        {
                                                $sSQL     = ("INSERT INTO tbl_qa_weight_pictures SET audit_id = '$Id',
                                                                                    color    = '".$sColor."',
                                                                                    serial   = '".$iSerial."',
                                                                                    `type`   = 'P',    
                                                                                    picture  = '".$Picture."'");
                                                $bFlag = $objDb->execute($sSQL);
                                        }                                        
                                    }
                                }
                        
                                $sSQL     = ("INSERT INTO tbl_qa_weight_details SET audit_id = '$Id',
                                                                                    color    = '".$sColor."',
                                                                                    `type`    = 'P',
                                                                                    serial   = '".$iSerial."',
                                                                                    weight   = '".$iWeight."'");
                                $bFlag = $objDb->execute($sSQL);

                                if($bFlag == false)
                                    break;
                                
                                $iSerial ++;
                            }
                        }                    
                }
                
                $Counter++;
            }
      }
      /********************/
      if($bFlag == true && $Id > 0)
      {
          if(getDbValue("COUNT(1)", "tbl_qa_report_details", "audit_id='$Id'") > 0)
          {
            $sSQL  = ("UPDATE tbl_qa_report_details SET  fabric_weight_conformity_result    = '".$Result."',
                                                        fabric_weight_conformity_comments   = '".$Comments."'
                                                        WHERE audit_id='$Id'");
          }
          else
          {
              $sSQL  = ("INSERT INTO tbl_qa_report_details SET audit_id        = '$Id',
                                                    fabric_weight_conformity_result   = '".$Result."',
                                                    fabric_weight_conformity_comments = '".$Comments."'");
          }
            $bFlag = $objDb->execute($sSQL);
      }
  }
  
  if($bFlag == false)
  {
      echo $sSQL;exit;
  }
?>
