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

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$Id           = IO::intValue("Id");
	$DefectArea   = IO::strValue("DefectArea");
	$DefectAreaZh = IO::strValue("DefectAreaZh");
	$DefectAreaTr = IO::strValue("DefectAreaTr");
	$DefectAreaDe = IO::strValue("DefectAreaDe");	
        $DefectAreaUr = IO::strValue("DefectAreaUr");        
        $DefectAreaKh = IO::strValue("DefectAreaKh");
        $DefectAreaPh = IO::strValue("DefectAreaPh");
        $DefectAreaVn = IO::strValue("DefectAreaVn");
        $DefectAreaId = IO::strValue("DefectAreaId");
        
        $Stages       = implode(",", IO::getArray("Stages"));	
        $Reports      = implode(",", IO::getArray("Reports"));	
	$sError       = "";


	$sSQL = "SELECT id FROM tbl_defect_areas WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Defect Area ID. Please select the proper Defect Area to Edit.\n";
		exit( );
	}

	if ($DefectArea == "")
		$sError .= "- Invalid Defect Area\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}


	$sSQL  = "SELECT * FROM tbl_defect_areas WHERE `area` LIKE '$DefectArea' AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
			$sSQL = "UPDATE tbl_defect_areas SET `area`='$DefectArea', area_zh='$DefectAreaZh', area_tr='$DefectAreaTr', area_de='$DefectAreaDe', area_ur='$DefectAreaUr', area_kh='$DefectAreaKh', area_ph='$DefectAreaPh', area_vn='$DefectAreaVn', area_id='$DefectAreaId', stages='$Stages', reports='$Reports' WHERE id='$Id'";

			if ($objDb->execute($sSQL) == true)
				print ("OK|-|$Id|-|<div>The selected Defect Area has been Updated successfully.</div>|-|$DefectArea");

			else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified Defect Area already exists in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>