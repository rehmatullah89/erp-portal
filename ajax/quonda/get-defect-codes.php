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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$DefectType = IO::intValue("DefectType");
	$Report     = IO::intValue("Report");

	if ($DefectType == 0)
	{
		print "ERROR|-|Invalid Defect Type. Please select the proper Defect Type.\n";
		exit;
	}

	$sSQL = "SELECT id, CONCAT(`code`, ' - ', defect) FROM tbl_defect_codes WHERE type_id='$DefectType' AND report_id='$Report' ORDER BY `code`";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		print "OK";

		for ($i = 0; $i < $iCount; $i ++)
			print ("|-|".$objDb->getField($i, 0)."||".$objDb->getField($i, 1));
	}

	else
		print "ERROR|-|A Database Error occured. Please reload your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>