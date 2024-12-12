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
	***********************************************************************************************
	\*********************************************************************************************/

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Report  = IO::intValue("Id");
	$List   = IO::strValue("List");

	if ($Report == 0)
	{
		print "ERROR|-|Invalid Report. Please select the proper Report.\n";
		exit;
	}

	$DefectTypes = getList("tbl_defect_types dt ,tbl_defect_codes dc", "dt.id", "dt.type", "dt.id=dc.type_id AND report_id = '$Report'");

	if (count($DefectTypes) > 0)
	{
            print "OK|-|".$List;
            
            foreach ($DefectTypes as $iDefectType => $sDefectType)
                print ("|-|".$iDefectType."||".$sDefectType);
	}
	else
		print "OK|-|".$List."|-|";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>