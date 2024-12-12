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
**  Software Engineer:                                                                         **
**                                                                                           **
**      Name  :  Rehmat Ullah			                                                     **
**      Email :  rehmatullah@3-tree.com		                                                 **
**      Phone :  +92 344 404 3675                                                            **
**      URL   :  http://www.apparelco.com                                                    **
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

	$Id   = IO::intValue("Id");
	
        if ($Id == 0 || $Id == "")
	{
		print "ERROR|-|Invalid Section. Please select the proper Section.\n";
		exit;
	}

        $sAuditorsList = getList("tbl_users", "id", "name", "designation_id IN (SELECT id FROM tbl_designations WHERE department_id IN (5,15,41))");
        
	$sSQL = "SELECT id, audit_date, auditors FROM tbl_tnc_audits Where vendor_id='$Id' AND (follow_up_audit = '0' OR follow_up_audit = '' OR follow_up_audit IS NULL) ORDER BY id DESC";
        $Options = "";
        
        if ($objDb->query($sSQL) == true)
	{
                $iCount = $objDb->getCount( );
                
		for ($i = 0; $i < $iCount; $i ++)
                {
                    $iId         = $objDb->getField($i, 'id');
                    $sAuditDate  = $objDb->getField($i, 'audit_date');
                    $sAuditors   = $objDb->getField($i, 'auditors');

                    $iAuditors   = @explode(",", $sAuditors);
                    $sAuditors   = "";

                    foreach ($iAuditors as $iAuditor)
                            $sAuditors .= ($sAuditorsList[$iAuditor]."<br />");

                       $Options .= "<option value=".$iId." >". $sAuditDate."  -  ".$sAuditors ."</option>";
               }
               print $Options;
               exit;
	}

	else
		print "ERROR|-|A Database Error occured. Please reload your webpage and try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>