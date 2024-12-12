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
	**  ***************************************************************************************  **
	**                                                                                           **
	**                                                                                           **
	**                                                                                           **
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

        $Id         = IO::intValue('Id');
        $Points     = IO::getArray("Point");
        $bFlag      = true;


	$_SESSION['Flag'] = "";

	$objDb->execute("BEGIN");

 
        if(!empty($Points) && $bFlag == true)
        {
            

            $AuditDate  = getDbValue("audit_date", "tbl_crc_audits", "id='$Id'");
            
            @list($sYear, $sMonth, $sDay) = @explode("-", $AuditDate);

            @mkdir(($sBaseDir.TNC_PICS_DIR.$sYear), 0777);
            @mkdir(($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth), 0777);
            @mkdir(($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth."/".$sDay), 0777);

            $sTncDir  = ($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");

            foreach ($Points as $Point)
            {
                    $Score      = IO::intValue("Score{$Point}");
                    $PrevScore  = IO::strValue("PrevScore{$Point}");
                    $CapDate    = IO::strValue("CapDate{$Point}");
                    $AltDate    = IO::strValue("AltDate{$Point}");
                    $Caps       = IO::strValue("Caps{$Point}");

                    $AltDate    = (trim($AltDate) == ""?'0000-00-00':$AltDate);

                    $sSQL  = "UPDATE tbl_crc_audit_details SET review_score         = '$Score',
                                                              corrective_action     = '$Caps',
                                                              due_date              = '$CapDate',
                                                              alternative_due_date  = '$AltDate'
                                     WHERE audit_id='$Id' AND point_id='$Point'";
                    
                    $bFlag = $objDb->execute($sSQL);

                   
                    if($bFlag == true && !empty($_FILES["files".$Point]['name']))
                    {

                        foreach($_FILES["files".$Point]['name'] as $iFile => $sFileName)
                        {        
                            if ($sFileName != "")
                            {
                                    $time = strtotime(date('Y-m-d h:i:s'));
                                   
                                    $sPicture   = "{$Id}-{$Point}-CAP-{$sFileName}";

                                    if (@move_uploaded_file($_FILES["files".$Point]['tmp_name'][$iFile], ($sTncDir.$sPicture)))
                                    {

                                            $iPicture = getNextId("tbl_crc_audit_pictures");

                                           $sSQL = "INSERT INTO tbl_crc_audit_pictures (id, audit_id, point_id, title, picture, map)
                                                                                VALUES ('$iPicture', '$Id', '$Point', '$sFileName', '$sPicture', 'Y')";

                                            $bFlag = $objDb->execute($sSQL);
                                    }                                
                            }

                        }
                    }

                    if ($bFlag == false)
                            break;
            }

        }
	if ($_SESSION['Flag'] == "")
	{
		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

        		redirect("crc-audits.php", "CRC_AUDIT_UPDATED");
		}

		else
		{
			$_SESSION['Flag'] = "DB_ERROR";

			$objDb->execute("ROLLBACK");
		}
	}

	header("Location: edit-crc-audit-cap.php?Id={$Id}");


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>