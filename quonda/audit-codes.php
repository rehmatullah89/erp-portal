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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	$PageId          = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$AuditCode       = IO::strValue("AuditCode");
	$Report          = IO::strValue("Report");
	$Auditor         = IO::intValue("Auditor");
	$Group           = IO::intValue("Group");
	$Brand           = IO::intValue("Brand");
	$Vendor          = IO::intValue("Vendor");
        $Parent          = IO::intValue("Parent");
	$FromDate        = IO::strValue("FromDate");
	$ToDate          = IO::strValue("ToDate");
	$Region          = IO::intValue("Region");
	$Approved        = IO::strValue("Approved");
	$Department      = IO::intValue("Department");
	$Maker           = IO::strValue("Maker");
	$LotNo           = IO::strValue("LotNo");
	$InspecType      = IO::strValue("InspecType");
	$PostId          = IO::strValue("PostId");
	$AuditQty        = IO::strValue("AuditQty");       
	$AuditType       = IO::strValue("AuditType");       
	$CheckLevel      = IO::strValue("CheckLevel");
	$HohOrderNo      = IO::strValue("HohOrderNo");
	$InspectionLevel = IO::strValue("InspectionLevel");
	$Completed       = IO::strValue("Completed");
	$AuditStage      = "";

	if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE || @strpos($_SESSION["Email"], "dkcompany.com") !== FALSE || @strpos($_SESSION["Email"], "hema.nl") !== FALSE ||
	    @strpos($_SESSION["Email"], "kcmtar.com") !== FALSE || @strpos($_SESSION["Email"], "mister-lady.com") !== FALSE)
		$AuditStage = "F";


	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Auditor         = IO::strValue("Auditor");
		$Group           = IO::strValue("Group");
		$Department      = IO::strValue("Department");
		$Brand           = IO::intValue("Brand");
		$Vendor          = IO::intValue("Vendor");
		$Parent          = IO::intValue("Parent");
		$Unit            = IO::strValue("Unit");
		$Report          = IO::strValue("Report");
		$Line            = IO::strValue("Line");
		$AuditDate       = IO::strValue("AuditDate");
		$StartHour       = IO::strValue("StartHour");
		$StartMinutes    = IO::strValue("StartMinutes");
		$EndHour         = IO::strValue("EndHour");
		$EndMinutes      = IO::strValue("EndMinutes");
		$AuditStage      = IO::strValue("AuditStage");
		$Po              = IO::intValue("Po");
		$OrderNo         = IO::strValue("OrderNo");
		$OrderNos        = IO::getArray("OrderNo");
		$StyleId         = IO::intValue("StyleId");
		$StyleNo         = IO::strValue("StyleNo");
		$Colors          = IO::getArray("Colors");
		$Sizes           = IO::getArray("Sizes");
		$SampleSize      = IO::intValue("SampleSize");
		$CheckLevel      = IO::strValue("CheckLevel");
		$InspectionLevel = IO::strValue("InspectionLevel");
		$AuditType       = IO::strValue("AuditType"); 
	}
	

	if ($PageId == 1 && $AuditCode == "" && $Auditor == 0 && $Group == 0 && $Vendor == 0 && $Region == 0 && ($FromDate == "" || $ToDate == ""))
	{
		if (@strpos($_SESSION["Email"], "marksnspencer.com") === FALSE && @strpos($_SESSION["Email"], "dkcompany.com") === FALSE && @strpos($_SESSION["Email"], "hema.nl") === FALSE &&
			@strpos($_SESSION["Email"], "kcmtar.com") === FALSE && @strpos($_SESSION["Email"], "mister-lady.com") === FALSE)
		{
			$FromDate = date("Y-m-d");
			$ToDate   = date("Y-m-d");
		}
	}


	$sRegionsList        = getList("tbl_countries", "id", "country", "matrix='Y'");	
	$sAllAuditorsList    = getList("tbl_users", "id", "name", "auditor='Y'");
	$sUsersList          = getList("tbl_users", "id", "name");
	$sActiveAuditorsList = getList("tbl_users", "id", "name", "auditor='Y' AND status='A'");
	$sGroupsList         = getList("tbl_auditor_groups", "id", "name");
	$sDepartmentsList    = getList("tbl_departments", "id", "department", "`code`!=''");
	$sGroupsList         = getList("tbl_auditor_groups", "id", "name");

	$sAuditStages        = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditTypes         = getList("tbl_audit_types", "id", "type", "", "position");
	$sAuditStagesList    = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')", "position");
	$sReportTypes        = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");        
	$sReportsList        = getList("tbl_reports", "id", "report", "FIND_IN_SET(id, '$sReportTypes')");
	$sBrandsList         = getList("tbl_brands", "id", "brand", "id IN ({$_SESSION['Brands']})");
	$sAllVendorsList     = getList("tbl_vendors", "id", "vendor");
            
	if (@strpos($_SESSION["Email"], "@gms-fashion") !== FALSE)
		$sActiveAuditorsList = getList("tbl_users", "id", "name", "auditor='Y' AND status='A' AND email LIKE '%@gms-fashion%'");
        
	if (@strpos($_SESSION["Email"], "@3-tree.com") !== FALSE)
		$sActiveAuditorsList = getList("tbl_users", "id", "name", "auditor='Y' AND status='A'");

	if (@in_array($_SESSION["UserType"], array("MGF", "CONTROLIST", "GLOBALEXPORTS", "LEVIS", "HOHENSTEIN", "HYBRID", "MATRIX")) || @strpos($_SESSION["Email"], "@jcrew.com") !== FALSE) 
		$sActiveAuditorsList = getList("tbl_users", "id", "name", "auditor='Y' AND status='A' AND user_type='{$_SESSION['UserType']}'");
        else if(@in_array($_SESSION["UserType"], array("JCREW")))      
        {
                $sAuditorSubQuery = " AND ";
                $sMyVendors = explode(",", $_SESSION['Vendors']);

                if(count($sMyVendors) > 1)
                    $sAuditorSubQuery .= " ( ";
                
                $iIndex = 0;
                foreach($sMyVendors as $iMyVendor)
                {
                    if($iMyVendor != 0)
                    {
                        if($iIndex == 0)
                            $sAuditorSubQuery .= " FIND_IN_SET(".trim($iMyVendor).", vendors) ";
                        else
                            $sAuditorSubQuery .= " OR FIND_IN_SET(".trim($iMyVendor).", vendors) ";

                        $iIndex ++;    
                    } 
                }
                
                 if(count($sMyVendors) > 1)
                    $sAuditorSubQuery .= " ) ";
                 
                $sActiveAuditorsList = getList("tbl_users", "id", "name", "auditor='Y' AND status='A' AND email NOT LIKE '%@3-tree.com%' AND email NOT LIKE '%@jcrew.com' AND email NOT LIKE '%@apparelco.com' AND user_type='{$_SESSION['UserType']}' $sAuditorSubQuery");
        }
        
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?
        if(@in_array($_SESSION["UserType"], array("JCREW")))
            @include("audit-codes-new.php");
        else
            @include("audit-codes-old.php");

	$objDb->close( );
	$objDb2->close( );
        $objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>