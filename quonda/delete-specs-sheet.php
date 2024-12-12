<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  MATRIX Customer Portal                                                                   **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.apparelco.com                                                              **
	**                                                                                           **
	**  Copyright 2008-15 (C) Matrix Sourcing                                                    **
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

	$Id       = IO::intValue('Id');
	$Field    = IO::strValue("Field");
	$Referer  = urldecode(IO::strValue("Referer"));
	$Redirect = IO::strValue('Redirect');


	$sSQL = "SELECT {$Field} FROM tbl_qa_reports WHERE id='$Id'";

	if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
	{
		$sSpecsSheet = $objDb->getField(0, 0);

		$sSQL  = "UPDATE tbl_qa_reports SET {$Field}='' WHERE id='$Id'";

		if ($objDb->execute($sSQL) == true)
		{
			@unlink($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet);

			$_SESSION['Flag'] = "REPORT_SAVED";
		}

		else
			$_SESSION['Flag'] = "DB_ERROR";
	}

	else
		$_SESSION['Flag'] = "DB_ERROR";


	if ($Redirect == "Y")
		redirect($_SERVER['HTTP_REFERER']);

	else
		redirect("edit-qa-report.php?Id={$Id}&Referer=".urlencode($Referer));

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>