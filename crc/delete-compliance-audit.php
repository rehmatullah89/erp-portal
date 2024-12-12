<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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

	@require_once("../requires/session.php");

	if ($sUserRights['Delete'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$Id = IO::strValue('Id');


	$sPictures = array( );

	$objDb->execute("BEGIN");


	$sSQL = "DELETE FROM tbl_compliance_audits WHERE id='$Id'";
	$bFlag = $objDb->execute($sSQL);

	if ($bFlag == true)
	{
		$sSQL = "SELECT * FROM tbl_compliance_audit_details WHERE audit_id='$Id'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			for ($j = 1; $j <= 15; $j ++)
			{
				$sPicture = $objDb->getField($i, "picture{$j}");

				if ($sPicture != "")
					$sPictures[] = $sPicture;
			}
		}


		$sSQL = "DELETE FROM tbl_compliance_audit_details WHERE audit_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");


		for ($i = 0; $i < count($sPictures); $i ++)
			@unlink($sBaseDir.COMPLIANCE_AUDITD_DIR.$sPictures[$i]);

		$_SESSION['Flag'] = "COMPLIANCE_AUDIT_DELETED";
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