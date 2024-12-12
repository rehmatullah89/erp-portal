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
**      Name  :  Rehmat Ullah			                                             **
**      Email :  rehmatullah@3-tree.com		                                             **
**      Phone :  +92 344 404 3675                                                            **
**      URL   :  http://www.apparelco.com                                                    **
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

	$Id         = IO::intValue("Id");
	$List       = IO::strValue("List");
        $ScheduleId = IO::strValue("ScheduleId");
        $Points     = getDbValue("points", "tbl_crc_audits", "id='$ScheduleId'");
        
        $sCategoriesList  = getList("tbl_tnc_categories c, tbl_tnc_points p", "c.id", "c.category", "");
        
	if ($Id == 0)
	{
		print "ERROR|-|Invalid Section. Please select the proper Section.\n";
		exit;
	}


	$sSQL = "SELECT DISTINCT c.id, c.category FROM tbl_tnc_categories c, tbl_tnc_points p WHERE c.id = p.category_id AND c.section_id='$Id' AND p.id IN ($Points) ORDER BY c.position";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		print ("OK|-|".$List);

		for ($i = 0; $i < $iCount; $i ++)
			print ("|-|".$objDb->getField($i, 0)."||".$objDb->getField($i, 1));
	}

	else
		print "ERROR|-|A Database Error occured. Please reload your webpage and try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>