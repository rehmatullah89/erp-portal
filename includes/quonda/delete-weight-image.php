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
	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
        $objDb       = new Database( );
        
	$File      = IO::strValue("File");
	$AuditDate = IO::strValue('AuditDate');
        $AuditId   = IO::strValue('AuditId');

	@list($sYear, $sMonth, $sDay) = @explode("-", $AuditDate);

        if($File != "" && $AuditId > 0)
        {
            $sSQL  = "DELETE FROM tbl_qa_weight_pictures WHERE audit_id='$AuditId' AND `picture` LIKE '$File'";
            $bFlag = $objDb->execute($sSQL);
            
            @unlink($sBaseDir.$sBaseDir.CARTONS_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$File);
        }
        
        $objDb->close( );
        $objDbGlobal->close( );

        @ob_end_flush( );
        
        $_SESSION["Flag1122"] = "Image Deleted Successfuly";
        redirect($_SERVER['HTTP_REFERER']);
?>