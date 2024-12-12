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
	**      Name  :  Rehmat Ullah                                                                **
	**      Email :  rehmatullah@3-tree.com                                                      **
	**      Phone :  +92 344 404 3675                                                            **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/session.php");

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	$Vendor         = IO::intValue("Vendor");
	$Auditor        = IO::intValue("Auditor");
        $Brand          = IO::intValue("Brand");        
        $ParentSection  = IO::intValue("Section");
        $Unit           = IO::intValue("Unit");
        $AuditDate      = IO::strValue("AuditDate");
        $AuditType      = IO::intValue("AuditType");
        $Points         = IO::getArray("Points");
        $QuestionType   = IO::strValue("ddQuestions");
        $GroupAudit     = IO::strValue("GroupAudit");
        $PreviousAudit  = IO::intValue("PreviousAudit");
        $Sections       = IO::getArray("Sections");
        
        $sAuditSections = ""; 
        $sSectionsList  = getList("tbl_tnc_sections", "id", "id", "parent_id='$ParentSection'");
        
        if($QuestionType == 'Q')
        {
            $Points         = IO::getArray("Points");
            $sAuditSections = implode(",", getList("tbl_tnc_sections", "id", "id", "parent_id='$ParentSection' AND id IN (SELECT DISTINCT section_id from tbl_tnc_points WHERE FIND_IN_SET(id, ". implode(",", $Points)."))"));
        }
        else if($QuestionType == 'S')
        {
            $sAuditSections = implode(",", $Sections);
            $Points         = getList("tbl_tnc_points", "id", "id", "section_id IN (". implode(',', $Sections).") AND FIND_IN_SET('$Brand', brands)");
        }   
        else if($QuestionType == 'A')
        {
            $sAuditSections = implode(",", $sSectionsList);
            $Points         = getList("tbl_tnc_points", "id", "id", "section_id IN (". implode(',', $sSectionsList).") AND FIND_IN_SET('$Brand', brands)");
        }   
        
        if($PreviousAudit > 0)
        {
            $sPreviousAuditSections = getDbValue("GROUP_CONCAT(audit_sections SEPARATOR ',')", "tbl_crc_audits", "prev_audit_id='$PreviousAudit' OR id='$PreviousAudit'");
            $sInterSection          = array_intersect(explode(",", $sAuditSections), explode(",", $sPreviousAuditSections));
            if(count($sInterSection) > 0)
                    $_SESSION['Flag'] = "CRC_AUDIT_EXISTS";
        }
        
        /*if(@in_array($AuditType, array(1,2,3)))
        {
            if($QuestionType == 'Q')
                $Points = IO::getArray("Points");
            
            else if($QuestionType == 'A')
                $Points = getList("tbl_tnc_points", "id", "id", "section_id IN (". implode(',', $sSectionsList).") AND FIND_IN_SET('$Brand', brands)");
        }
        else if($AuditType == 4)
        {
            if($QuestionType == 'Q')
                $Points = IO::getArray("Points");
            
            else if($QuestionType == 'A')
                $Points = getList("tbl_tnc_points", "id", "point", "section_id IN (". implode(',', $sSectionsList).") AND FIND_IN_SET('$Brand', brands)");
            
            else if($QuestionType == 'F' && $PreviousAudit != "")
            {
                $Points = getList("tbl_crc_audit_details", "point_id", "point_id", "audit_id='$PreviousAudit' AND score != '1'");
            }
        }
        else if($AuditType == 5 && $PreviousAudit != "")
        {
            $sZeroTolerancePoints = getList("tbl_tnc_points", "id", "id", "nature='Z' AND FIND_IN_SET('$Brand', brands)");
            $Points = getList("tbl_crc_audit_details", "point_id", "point_id", "audit_id='$PreviousAudit' AND score != '1' AND point_id IN (". implode(',', $sZeroTolerancePoints).")");
        }*/
        
        if($_SESSION['Flag'] == "")
        {
            $sUnitSql = "";
            
            if($Unit >0)
                $sUnitSql = " AND unit_id='$Unit' ";
                
            $sSQL = "SELECT * FROM tbl_crc_audits WHERE vendor_id='$Vendor' AND audit_date='$AuditDate' AND section_id='$ParentSection' AND audit_sections = '$sAuditSections' $sUnitSql";
            $objDb->query($sSQL);

            if ($objDb->getCount( ) == 0)
            {
                    $objDb->execute("BEGIN");

                    $iSchedule = getNextId("tbl_crc_audits");

                    $sSQL  = ("INSERT INTO tbl_crc_audits (id, prev_audit_id, audit_sections, questions_type, vendor_id, auditor_id, brand_id, section_id, unit_id, points, audit_date, audit_type_id, created_at, created_by, modified_at, modified_by)
                                                   VALUES ('$iSchedule', '$PreviousAudit', '$sAuditSections', '$QuestionType', '$Vendor', '$Auditor', '$Brand', '$ParentSection', '$Unit', '".@implode(",", $Points)."', '$AuditDate', '$AuditType', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')");
                    $bFlag = $objDb->execute($sSQL);


                    if ($bFlag == true)
                    {
                            $objDb->execute("COMMIT");

                            redirect("tnc-schedules.php", "CRC_AUDIT_ADDED");
                    }

                    else
                    {
                            $objDb->execute("ROLLBACK");

                            $_SESSION['Flag'] = "DB_ERROR";
                    }
            }
            else
                $_SESSION['Flag'] = "CRC_AUDIT_EXISTS";
        }

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>