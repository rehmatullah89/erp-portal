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

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$AuditId   = IO::strValue('AuditId');
        $PointId   = IO::intValue('Point');
        $AuditDate = IO::strValue('AuditDate');
	$Referer   = urlencode(IO::strValue('Referer'));

	@list($sYear, $sMonth, $sDay) = @explode("-", $AuditDate);

	@mkdir(($sBaseDir.TNC_PICS_DIR.$sYear), 0777);
	@mkdir(($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth), 0777);
	@mkdir(($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth."/".$sDay), 0777);

	$sTncDir  = ($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");
	$sMessage = "";


	for ($i = 1; $i <= 5; $i ++)
	{
		if ($_FILES["Image{$i}"]['name'] != "")
		{
                        $Image = IO::getFileName($_FILES["Image{$i}"]['name']);

                        $iPictureId = getNextId("tbl_tnc_audit_pictures");
			$sPicture   = "{$AuditId}-{$PointId}-{$Image}";

                       
			if (@move_uploaded_file($_FILES["Image{$i}"]['tmp_name'], ($sTncDir.$sPicture)))
			{
                                
                                if(!empty($PointId) && $PointId !=0)
                                    $Image = 'Point No. '. $PointId;
				$sSQL = "INSERT INTO tbl_tnc_audit_pictures (id, audit_id, point_id, title, picture) VALUES ('$iPictureId', '$AuditId', '$PointId', '$Image', '$sPicture')";
                                $objDb->execute($sSQL);


                                $sMessage .= "<span style='color:#000000;'>- {$Image} uploaded successfully.</span><br />";
			}

			else
				$sMessage .= "- {$Image} uploading failed.<br />";
		}
	}


	$_SESSION['Message'] = $sMessage;

	redirect("tnc-audit-images.php?AuditId={$AuditId}&Referer={$Referer}");
?>