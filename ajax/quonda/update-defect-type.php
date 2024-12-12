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
	$DefectType   = IO::strValue("DefectType");
	$DefectTypeZh = IO::strValue("DefectTypeZh");
	$DefectTypeTr = IO::strValue("DefectTypeTr");
	$DefectTypeDe = IO::strValue("DefectTypeDe");	
        $DefectTypeUr = IO::strValue("DefectTypeUr");        
        $DefectTypeKh = IO::strValue("DefectTypeKh");
        $DefectTypePh = IO::strValue("DefectTypePh");
        $DefectTypeVn = IO::strValue("DefectTypeVn");
        $DefectTypeId = IO::strValue("DefectTypeId");
        
        $Stages       = implode(",", IO::getArray("Stages"));
	$Color        = IO::strValue("Color");
	$sError       = "";


	$sSQL = "SELECT id FROM tbl_defect_types WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Defect Type ID. Please select the proper Defect Type to Edit.\n";
		exit( );
	}

	if ($DefectType == "")
		$sError .= "- Invalid Defect Type\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}


	$sSQL  = "SELECT * FROM tbl_defect_types WHERE `type` LIKE '$DefectType' AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
			$sSQL = "UPDATE tbl_defect_types SET `type`='$DefectType', type_zh='$DefectTypeZh', type_tr='$DefectTypeTr', type_de='$DefectTypeDe', type_ur='$DefectTypeUr', type_kh='$DefectTypeKh', type_ph='$DefectTypePh', type_vn='$DefectTypeVn', type_id='$DefectTypeId', stages='$Stages', color='$Color' WHERE id='$Id'";

			if ($objDb->execute($sSQL) == true)
				print ("OK|-|$Id|-|<div>The selected Defect Type has been Updated successfully.</div>|-|$DefectType|-|$Color");

			else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified Defect Type already exists in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>