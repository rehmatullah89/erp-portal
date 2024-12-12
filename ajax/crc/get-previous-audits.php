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

	$VendorId = IO::intValue("VendorId");
        $AuditId  = IO::intValue("AuditId");
        $UnitId   = IO::intValue("Unit");

	if ($VendorId == 0)
	{
		print "ERROR|-|Invalid Vendor Id. Please select the proper Vendor.\n";
		exit;
	}
        else
        {
            $str = "";
            $sSubSql = "";
            
            if($UnitId > 0)
                $sSubSql .= " AND unit_id='$UnitId' ";
            
            if($AuditId > 0)
                $sSubSql .= " AND id != '$AuditId' ";
//echo "SELECT id from tbl_crc_audits WHERE vendor_id='$VendorId' AND prev_audit_id='0' $sSubSql"; exit;                
            $sPreviousAudits   = getList("tbl_crc_audits", "id", "id", "vendor_id='$VendorId' AND prev_audit_id='0' $sSubSql");
            
            if(count($sPreviousAudits) > 0)
            {                 
                foreach($sPreviousAudits as $iAudit)
                {
                    $str .= "<option value='".$iAudit."'> C". str_pad($iAudit, 5, 0, STR_PAD_LEFT)."</option>";

                }
                
                print "OK|-|".$str;
                
            }else
                print "ERROR|-|Parent Audits for this vendor does not exist!";
        }

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>