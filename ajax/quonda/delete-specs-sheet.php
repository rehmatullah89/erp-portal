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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/session.php");

	if ($sUserRights['Edit'] != "Y")
	{
		print "ERROR|-|You havn\'t enough Rights to modify the selected Data.\n";
		exit( );
	}

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id    = IO::intValue('Id');
	$Index = IO::intValue("Index");
	
	
	$sSQL = "SELECT audit_date, specs_sheet_{$Index} FROM tbl_qa_reports WHERE id='$Id'";

	if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
	{
		$sAuditDate  = $objDb->getField(0, 0);
		$sSpecsSheet = $objDb->getField(0, 1);


		$sSQL = "UPDATE tbl_qa_reports SET specs_sheet_{$Index}='' WHERE id='$Id'";

		if ($objDb->execute($sSQL) == true)
		{
			@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);
			
			@unlink("../".$sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sSpecsSheet);
			@unlink("../".$sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/thumbs/".$sSpecsSheet);
			
			@unlink("../".$sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet);
			@unlink("../".$sBaseDir.SPECS_SHEETS_DIR."thumbs/".$sSpecsSheet);

			print "DELETED";
		}
	}


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>