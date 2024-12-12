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

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$sSQL  = ("SELECT * FROM tbl_crc_reports WHERE brand_id='".IO::intValue("Brand")."' AND title LIKE '".IO::strValue("Title")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_crc_reports");


		if ($_FILES['Report']['name'] != "")
		{
			$sReport = ($iId."-".IO::getFileName($_FILES['Report']['name']));

			@move_uploaded_file($_FILES['Report']['tmp_name'], ($sBaseDir.CRC_REPORTS_DIR.$sReport));


			$sSQL = ("INSERT INTO tbl_crc_reports (id, brand_id, title, report, date_time) VALUES
			                                      ('$iId', '".IO::intValue("Brand")."', '".IO::strValue("Title")."', '$sReport', NOW( ))");

			if ($objDb->execute($sSQL) == true)
				redirect($_SERVER['HTTP_REFERER'], "CRC_REPORT_ADDED");

			else
			{
				$_SESSION['Flag'] = "DB_ERROR";

				@unlink($sBaseDir.CRC_REPORTS_DIR.$sReport);
			}
		}

		else
			$_SESSION['Flag'] = "ERROR";
	}

	else
		$_SESSION['Flag'] = "CRC_REPORT_EXISTS";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>