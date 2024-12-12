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
    $sAuditCode   = getDbValue("audit_code", "tbl_qa_reports", "id='$Id'"); 
    $sAuditDate   = getDbValue("audit_date", "tbl_qa_reports", "id='$Id'"); 
    @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);
    
    @mkdir(($sBaseDir.$sBaseDir.SIGNATURES_PICS_DIR.$sYear), 0777);
    @mkdir(($sBaseDir.$sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth), 0777);
    @mkdir(($sBaseDir.$sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay), 0777);
    
    $sSignsDir = ($sBaseDir.$sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");

    $Comments      = IO::strValue("Comments");
    $Inspector     = IO::strValue("Inspector");
    $Manufacturer  = IO::strValue("Manufacturer");
  
    if(!empty($Inspector) && !empty($Manufacturer))
    {
          $bFlag = $objDb->execute("BEGIN");

          if(getDbValue("COUNT(1)", "tbl_qa_reports", "id='$Id'") > 0)
          {
            $sSQL  = ("UPDATE tbl_qa_reports SET  signatures_inspector       = '".$Inspector."',
                                                     signatures_manufacturer    = '".$Manufacturer."',
                                                     signatures_comments        = '".$Comments."'
                                                     WHERE id='$Id'");
          }
          else
          {
              $sSQL  = ("INSERT INTO tbl_qa_reports SET id           = '$Id',
                                                     signatures_inspector     = '".$Inspector."',
                                                     signatures_manufacturer  = '".$Manufacturer."',
                                                     signatures_comments      = '".$Comments."'");
          }

          $bFlag = $objDb->execute($sSQL);

          if($bFlag ==  true)
          {
                $InspectorSign    = $_FILES["InspectorSign"]['name'];
                $ManufactureSign  = $_FILES["ManufactureSign"]['name'];

                if($InspectorSign != "")
                {
                    $exts       = explode('.', $InspectorSign);
                    $extension  = end($exts);

                    if(@in_array(strtolower($extension), array('jpg','jpeg')))
                    {        
                        
                        unlink($sSignsDir."{$sAuditCode}_inspector.jpg");
                    
                        $Picture = ($sAuditCode.'_inspector.'.$extension);
                        if (@move_uploaded_file($_FILES["InspectorSign"]['tmp_name'], ($sSignsDir.$Picture)))
                        {
                                $File = "success";
                        }
                        /*else
                        {
                            echo "error";exit;
                        }*/
                    }
                }
                
                if($ManufactureSign != "")
                {
                    $exts       = explode('.', $ManufactureSign);
                    $extension  = end($exts);

                    if(@in_array(strtolower($extension), array('jpg','jpeg')))
                    {                                
                        unlink($sSignsDir."{$sAuditCode}_manufacturer.jpg");
                    
                        $Picture = ($sAuditCode.'_manufacturer.'.$extension);
                        if (@move_uploaded_file($_FILES["ManufactureSign"]['tmp_name'], ($sSignsDir.$Picture)))
                        {
                                $File = "success";
                        }
                    }
                }
          }
    }
?>
