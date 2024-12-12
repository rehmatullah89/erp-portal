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

	if ($sUserRights['Edit'] != "Y" && $sUserRights['Delete'] != "Y")
	{
		print "ERROR|-|You havn\'t enough Rights to modify the selected Data.\n";
		exit( );
	}

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$iDefect    = IO::strValue("DefectId");
        $sType      = IO::strValue("Type");
        
        if ($iDefect > 0 && ($sType == 'P' || $sType == ''))
	{
            $sPicture     = getDbValue("picture", "tbl_qa_packaging_defects", "id='$iDefect'");
            
            $sSQL  = "DELETE FROM tbl_qa_packaging_defects WHERE id='$iDefect'";
            $bFlag = $objDb->execute($sSQL);
                
            if($bFlag == true && $sPicture != "")
            {
                $AuditDate    = getDbValue("qa.audit_date", "tbl_qa_reports qa, tbl_qa_packaging_defects pd", "qa.id=pd.audit_id AND pd.id='$iDefect'");
                @list($sYear, $sMonth, $sDay) = @explode("-", $AuditDate);
                @unlink($sBaseDir.PACKAGING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sPicture);
            }
		print "SUCCESS";
	}
        else if ($iDefect > 0 && $sType == 'L')
	{
            $sPicture     = getDbValue("picture", "tbl_qa_labeling_defects", "id='$iDefect'");
            
            $sSQL  = "DELETE FROM tbl_qa_labeling_defects WHERE id='$iDefect'";
            $bFlag = $objDb->execute($sSQL);
                
            if($bFlag == true && $sPicture != "")
            {
                $AuditDate    = getDbValue("qa.audit_date", "tbl_qa_reports qa, tbl_qa_labeling_defects pd", "qa.id=pd.audit_id AND pd.id='$iDefect'");
                @list($sYear, $sMonth, $sDay) = @explode("-", $AuditDate);
                @unlink($sBaseDir.PACKAGING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sPicture);
            }
		print "SUCCESS";
	}
        else
            print "ERROR";
	
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>