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
	**  Software Engineer:                                                                       **
	**                                                                                           **
	**      Name  :  Rehmat Ullah                                                                **
	**      Email :  rehmatullah@3-tree.com                                                      **
	**      Phone :  +92 344 404 3675                                                            **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/session.php");
	@require_once("../requires/image-functions.php");

	if ($sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Id             = IO::intValue('AuditId');
        $Certifications = getList("tbl_certification_types", "id", "certification");
	$bFlag = true;


	$_SESSION['Flag'] = "";
	$objDb->execute("BEGIN");

        $sSQL  = "DELETE from tbl_crc_audit_certifications WHERE audit_id='$Id'";
        $bFlag = $objDb->execute($sSQL);


	if ($bFlag == true){
		foreach ($Certifications as $iCertificate => $sCertificate)
		{
			$Apply      = IO::strValue("Apply{$iCertificate}");
                        $Comments   = IO::strValue("Comments{$iCertificate}");
                        
                        $sSQL  = "INSERT INTO tbl_crc_audit_certifications SET audit_id = '$Id', certification_id = '$iCertificate', apply = '$Apply', comments = '$Comments', file = ''";
                        $bFlag = $objDb->execute($sSQL);

                        if ($bFlag == true)
                        {
                            foreach($_FILES["file".$iCertificate]['name'] as $iFileName => $sFileName)
                            {
                                if($sFileName != "")
                                {        
                                    $extension  = explode('.', $sFileName);
                                    $ext        = end($extension);
                                    $File       = "{$Id}-".rand(5, 15)."-{$sCertificate}.{$ext}";

                                    if (@move_uploaded_file($_FILES["file".$iCertificate]['tmp_name'][$iFileName], ($sBaseDir.TNC_PICS_DIR.$File)))
                                    {
                                        $iPicture = getNextId("tbl_crc_audit_pictures");

                                        $sSQL  = "INSERT INTO tbl_crc_audit_pictures SET id='$iPicture', point_id='0', audit_id = '$Id', certification_id = '$iCertificate', title = '$File', picture = '$File'";
                                        $bFlag = $objDb->execute($sSQL);  

                                        if ($bFlag == false)
                                            break;
                                    }   
                                }
                                
                            }                            
                        }
		}
	}


	if ($_SESSION['Flag'] == "")
	{
		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

                        $_SESSION["Flag1122"] = "Updated Successfully"; 
//                        redirect($_SERVER['HTTP_REFERER'], "Updated Successfully!");
		}

		else
		{
			$_SESSION['Flag'] = "DB_ERROR";

			$objDb->execute("ROLLBACK");
		}
	}

            header("Location: {$_SERVER['HTTP_REFERER']}");


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>