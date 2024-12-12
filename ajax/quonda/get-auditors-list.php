<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  MATRIX Customer Portal                                                                   **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.apparelco.com                                                              **
	**                                                                                           **
	**  Copyright 2008-15 (C) Matrix Sourcing                                                    **
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

	$Auditor     = IO::strValue("Auditor");
	$sAuditors   = array( );

            
        if ($_SESSION["UserType"] == "MGF")
            $sSQL = "SELECT id, name FROM tbl_users WHERE name LIKE '%{$Auditor}%' AND status='A' AND auditor='Y' AND user_type='MGF' ORDER By name LIMIT 0,100";
        else if ($_SESSION["UserType"] == "JCREW")
            $sSQL = "SELECT id, name FROM tbl_users WHERE name LIKE '%{$Auditor}%' AND status='A' AND auditor='Y' AND user_type='JCREW' ORDER By name LIMIT 0,100";
        else
            $sSQL = "SELECT id, name FROM tbl_users WHERE name LIKE '%{$Auditor}%' AND status='A' AND auditor='Y' ORDER By name LIMIT 0,100";
            
            
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iAuditor = $objDb->getField($i, 0);
		$sAuditor = $objDb->getField($i, 1);

		$sAuditors[] = array("id" => $iAuditor, "name" => $sAuditor);
	}

	print @json_encode($sAuditors);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>