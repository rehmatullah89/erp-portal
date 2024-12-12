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

	$Id         = IO::intValue("Id");
	$Report     = IO::strValue("Report");
	$DefectType = IO::strValue("DefectType");
	$Code       = IO::strValue("Code");
	$BuyerCode  = IO::strValue("BuyerCode");
	$Defect     = IO::strValue("Defect");
	$DefectZh   = IO::strValue("DefectZh");
	$DefectTr   = IO::strValue("DefectTr");
	$DefectDe   = IO::strValue("DefectDe");
        $DefectUr   = IO::strValue("DefectUr");        
        $DefectKh   = IO::strValue("DefectKh");
        $DefectPh   = IO::strValue("DefectPh");
        $DefectVn   = IO::strValue("DefectVn");
        $DefectId   = IO::strValue("DefectId");
        
        $Stages     = implode(",", IO::getArray("Stages"));
	$sError     = "";

	$sSQL = "SELECT id FROM tbl_defect_codes WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Defect Code ID. Please select the proper Defect Code to Edit.\n";
		exit( );
	}

	if ($Report > 0)
	{
		$sSQL = "SELECT report FROM tbl_reports WHERE id='$Report'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Report\n";

		else
			$sReport = $objDb->getField(0, 0);
	}

	else
		$sError .= "- Invalid Report\n";

	if ($DefectType > 0)
	{
		$sSQL = "SELECT `type` FROM tbl_defect_types WHERE id='$DefectType'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Defect Type\n";

		else
			$sDefectType = $objDb->getField(0, 0);
	}

	else
		$sError .= "- Invalid Defect Type\n";

	if ($Code == "")
		$sError .= "- Invalid Code\n";

	if ($Defect == "")
		$sError .= "- Invalid Defect\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}


	$sSQL  = "SELECT * FROM tbl_defect_codes WHERE `code` LIKE '$Code' AND report_id='$Report' AND type_id='$DefectType' AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
			$sSQL = "UPDATE tbl_defect_codes SET report_id='$Report', type_id='$DefectType', `code`='$Code', buyer_code='$BuyerCode', defect='$Defect', defect_zh='$DefectZh', defect_tr='$DefectTr', defect_de='$DefectDe', defect_ur='$DefectUr', defect_kh='$DefectKh', defect_ph='$DefectPh', defect_vn='$DefectVn', defect_id='$DefectId', stages='$Stages'  WHERE id='$Id'";

			if ($objDb->execute($sSQL) == true)
				print ("OK|-|$Id|-|<div>The selected Defect Code has been Updated successfully.</div>|-|$sReport|-|$sDefectType|-|$Code|-|$BuyerCode|-|$Defect");

			else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified Defect Code (with same Report Type & Defect Type) already exists in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>