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

	if ($sUserRights['Delete'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$Id         = IO::strValue('Id');

	$sAuditDate = getDbValue("audit_date", "tbl_crc_audits", "id='$Id'");
	$sPictures  = array( );

	$sSQL = "SELECT picture FROM tbl_crc_audit_pictures WHERE id='$Id'";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$sPictures[] = $objDb->getField($i, "picture");


	$objDb->execute("BEGIN");

	
        $sSQL = "DELETE FROM tbl_crc_audit_details WHERE audit_id='$Id'";
        $bFlag = $objDb->execute($sSQL);
	
        if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_crc_audit_supply_chain WHERE audit_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}
        
        if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_crc_attendance WHERE audit_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}
        
        if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_crc_attendance_details WHERE audit_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}
        
        if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_crc_audit_certifications WHERE audit_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}
        
	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_crc_audit_pictures WHERE audit_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}
        
        if ($bFlag == true)
	{
		$sSQL = "UPDATE tbl_crc_audits SET total_score = '0', score = '0'  WHERE id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");


		@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

		foreach ($sPictures as $sPicture)
			@unlink($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sPicture);


		$_SESSION['Flag'] = "CRC_AUDIT_DELETED";
	}

	else
	{
		$objDb->execute("ROLLBACK");

		$_SESSION['Flag'] = "DB_ERROR";
	}

	header("Location: {$_SERVER['HTTP_REFERER']}");


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>