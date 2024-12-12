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

    $Id           = IO::intValue('AuditId');
    $SectionId    = IO::intValue('Section');
    $UserType     = IO::strValue('UserType');
    $ImgData      = IO::strValue('imgData');
    //echo $ImgData;exit;
    function base64_to_jpeg($img, $output_file) 
    {
        $path = "../../includes/quonda/{$output_file}";

	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	//$file = UPLOAD_DIR . uniqid() . '.png';
	$success = file_put_contents($path, $data);
        
       // $status = file_put_contents($path,base64_decode($base64_string));
    }
        
    $sAuditCode   = getDbValue("audit_code", "tbl_qa_reports", "id='$Id'"); 
    $sAuditDate   = getDbValue("audit_date", "tbl_qa_reports", "id='$Id'"); 
    @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);
    
    @mkdir(($sBaseDir.$sBaseDir.SIGNATURES_PICS_DIR.$sYear), 0777);
    @mkdir(($sBaseDir.$sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth), 0777);
    @mkdir(($sBaseDir.$sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay), 0777);
    
    $sSignsDir = ($sBaseDir.$sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");

  base64_to_jpeg($ImgData,"myfile.jpeg");  
  
/*    unlink($sSignsDir."{$sAuditCode}_manufacturer.jpg");
                    
    $Picture = ($sAuditCode.'_manufacturer.'.$extension);
    if (@move_uploaded_file($_FILES["ManufactureSign"]['tmp_name'], ($sSignsDir.$Picture)))
    {
            $File = "success";
    }*/
?>
