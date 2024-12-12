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
	**  ***************************************************************************************  **
	**                                                                                           **
	**                                                                                           **
	**                                                                                           **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/session.php");
	@require_once("../requires/image-functions.php");

	if ($sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Id             = IO::intValue('Id');
	$Vendor         = IO::intValue("Vendor");
	$Auditor        = IO::intValue("Auditor");
        $Brand          = IO::intValue("Brand");
        $Points         = IO::getArray("Points");
        $ParentSection  = IO::intValue("Section");
        $Unit           = IO::intValue("Unit");
        $AuditDate      = IO::strValue("AuditDate");
        $AuditType      = IO::intValue("AuditType");
        $QuestionType   = IO::strValue("ddQuestions");
        $PreviousAudit  = IO::intValue("PreviousAudit");
        $Sections       = IO::getArray("Sections");
        
	$bFlag = true;


	$_SESSION['Flag'] = "";

	$objDb->execute("BEGIN");

	
        $sSQL = ("SELECT * FROM tbl_crc_audits WHERE audit_date='".$AuditDate."' AND vendor_id='".$Vendor."' AND section_id='".$ParentSection."' AND id!='$Id'");
        $objDb->query($sSQL);

        if ($objDb->getCount( ) == 0)
        {            
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
                    $sPreviousAuditSections = getDbValue("GROUP_CONCAT(audit_sections SEPARATOR ',')", "tbl_crc_audits", "id!='$Id' AND (prev_audit_id='$PreviousAudit' OR id='$PreviousAudit') ");

                    if(COUNT(array_intersect(explode(",", $sAuditSections), explode(",", $sPreviousAuditSections)) > 0))
                            $_SESSION['Flag'] = "CRC_AUDIT_EXISTS";
                }
        
                /*if(@in_array($AuditType, array(1,2,3)))
                {
                    if($QuestionType == 'S')
                        $Points = IO::getArray("Points");

                    else if($QuestionType == 'A')
                        $Points = getList("tbl_tnc_points", "id", "id", "section_id IN (". implode(',', $sSectionsList).") AND FIND_IN_SET('$Brand', brands)");
                }
                else if($AuditType == 4)
                {
                    if($QuestionType == 'S')
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
                
                

                $sSQL  = ("UPDATE tbl_crc_audits SET audit_date  = '".IO::strValue("AuditDate")."',
                                                                                         vendor_id      = '".IO::intValue("Vendor")."',
                                                                                         prev_audit_id  = '$PreviousAudit', 
                                                                                         audit_sections = '$sAuditSections',
                                                                                         questions_type = '$QuestionType',    
                                                                                         auditor_id     = '$Auditor',
                                                                                         brand_id       = '$Brand',
                                                                                         audit_type_id  = '$AuditType',
                                                                                         unit_id        = '$Unit',    
                                                                                         points         = '".@implode(",", $Points)."',    
                                                                                         modified_at    = NOW( ),
                                                                                         modified_by    = '{$_SESSION['UserId']}'
                                  WHERE id='$Id'");
                $bFlag = $objDb->execute($sSQL);
        }

        else
                $_SESSION['Flag'] = "CRC_AUDIT_EXISTS";



	if ($_SESSION['Flag'] == "")
	{
		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");
                        redirect("tnc-schedules.php?Id={$Id}", "CRC_AUDIT_UPDATED");
		}

		else
		{
			$_SESSION['Flag'] = "DB_ERROR";

			$objDb->execute("ROLLBACK");
		}
	}

	header("Location: edit-tnc-schedule.php?Id={$Id}");


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>