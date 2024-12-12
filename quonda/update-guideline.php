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

	@require_once("../requires/session.php");
	@require_once("../requires/image-functions.php");

    checkLogin( );

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Report     = IO::intValue("Report");
        $DefectType = IO::intValue("DefectType");
        $GuideLine  = IO::strValue("GuideLine");
        
	$sSQL = ("UPDATE tbl_defect_guidelines SET guidelines='". utf8_encode($GuideLine)."' WHERE type_id='".$DefectType."' AND report_id='".$Report."'");

	if ($objDb->execute($sSQL) == true)
	{
		$_SESSION['Flag'] = "GUIDELINE_UPDATED";

		header("Location: guidelines.php");
	}

	else
	{
		$_SESSION['Flag'] = "DB_ERROR";

		header("Location: edit-guideline.php?ReportId={$Report}&TypeId={$DefectType}&Referer={$_SERVER['HTTP_REFERER']}");
	}

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>