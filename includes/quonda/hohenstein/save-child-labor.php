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
?>
<?

  $SiteName         = IO::strValue("SiteName");
  $SiteFax          = IO::strValue("SiteFax");
  $SiteAddress      = IO::strValue("SiteAddress");
  $SiteEmail        = IO::strValue("SiteEmail");  
  $SitePhone        = IO::strValue("SitePhone"); 
  $SitePerson       = IO::strValue("SitePerson"); 
  $Result           = IO::strValue("Result");
  
  $Conformance      = IO::strValue("Conformance");
  $NonConformance   = IO::strValue("NonConformance");
  $OtherComments    = IO::strValue("OtherComments");
  $Recommendations  = IO::strValue("Recommendations");
  $Deadline         = IO::strValue("DeadLine");
  
  $sStatementList = getList("tbl_statements", "id", "statement", "FIND_IN_SET('1', sections)");

  if(!empty($Result))
  {
      $bFlag = $objDb->execute("BEGIN");
      
      if($Result == 'P')
      {
            $sSQL  = "UPDATE tbl_qa_hohenstein SET child_labour_site_name = '$SiteName', child_labour_site_address = '$SiteAddress', child_labour_site_phone = '$SitePhone', child_labour_site_fax = '$SiteFax', child_labour_site_email = '$SiteEmail', child_labour_site_person = '$SitePerson', child_labour_result='P' WHERE audit_id='$Id'";
            $bFlag = $objDb->execute($sSQL);
      }else
      {
          
            $sSQL  = "UPDATE tbl_qa_hohenstein SET child_labour_site_name = '$SiteName', child_labour_site_address = '$SiteAddress', child_labour_site_phone = '$SitePhone', child_labour_site_fax = '$SiteFax', child_labour_site_email = '$SiteEmail', child_labour_site_person = '$SitePerson',
                        child_labour_result='$Result', child_labour_conformance='$Conformance', child_labour_non_conformance='$NonConformance',
                        child_labour_comments='$OtherComments', child_labour_recommendations='$Recommendations', child_labour_deadline='$Deadline'
                        WHERE audit_id='$Id'";
            $bFlag = $objDb->execute($sSQL);
          
            if($bFlag == true)
            {
                $sSQL  = "DELETE FROM tbl_qa_child_labour_details WHERE audit_id='$Id'";
                $bFlag = $objDb->execute($sSQL);                
            }
            
            if($bFlag == true)
            {
                $Questions  = IO::getArray("Questions");
                $Answers    = IO::getArray("Answers");
                $Remarks    = IO::getArray("QuestionRemarks");
                
                foreach($Questions as $key => $iQuestion)
                {
                    $Answer    = $Answers[$key];
                    $Remark    = $Remarks[$key];
                    
                    $sSQL  = ("INSERT INTO tbl_qa_child_labour_details SET audit_id      = '$Id',
                                                                            question_id  = '".$iQuestion."',
                                                                            answer       = '".$Answer."',
                                                                            remarks      = '".$Remark."'");
                    $bFlag = $objDb->execute($sSQL);

                      if($bFlag == false)
                          break;
                }
            }
            
             if($bFlag == true)
            {
                $sSQL  = "DELETE FROM tbl_qa_child_labour WHERE audit_id='$Id'";
                $bFlag = $objDb->execute($sSQL);                
            }
            
            if($bFlag == true)
            {
                $Names              = IO::getArray("Name");
                $BirthMonths        = IO::getArray("BirthMonth");
                $BirthYears         = IO::getArray("BirthYear");
                $AttendSchools      = IO::getArray("AttendSchool");
                $RegClasses         = IO::getArray("RegClass");
                $HazardAreas        = IO::getArray("HazardArea");
                $ReceiveEducations  = IO::getArray("ReceiveEducation");
                $InCompanyMonths    = IO::getArray("InCompanyMonth");
                $InCompanyYears     = IO::getArray("InCompanyYear");
                $WorkingIlos        = IO::getArray("WorkingIlo");
                $Remarks            = IO::getArray("Remarks");
                
                foreach($Names as $key => $Name)
                {
                    if($Name != "")
                    {
                        $BirthMonth         = $BirthMonths[$key];
                        $BirthYear          = $BirthYears[$key];
                        $AttendSchool       = $AttendSchools[$key];
                        $RegClass           = $RegClasses[$key];
                        $HazardArea         = $HazardAreas[$key];
                        $ReceiveEducation   = $ReceiveEducations[$key];
                        $InCompanyMonth     = $InCompanyMonths[$key];
                        $InCompanyYear      = $InCompanyYears[$key];
                        $WorkingIlo         = $WorkingIlos[$key];                        
                        $Remark             = $Remarks[$key];

                        $sSQL  = ("INSERT INTO tbl_qa_child_labour SET audit_id      = '$Id',
                                                                        person_no  = '".$key."',
                                                                        name       = '".$Name."',
                                                                        birth_month  = '".$BirthMonth."',
                                                                        birth_year       = '".$BirthYear."',
                                                                        attended_school  = '".$AttendSchool."',
                                                                        school_lessons       = '".$RegClass."',
                                                                        non_hazaradous_areas  = '".$HazardArea."',
                                                                        education       = '".$ReceiveEducation."',
                                                                        joining_month  = '".$InCompanyMonth."',
                                                                        joining_year       = '".$InCompanyYear."',
                                                                        working_under_ilo  = '".$WorkingIlo."',
                                                                        comments      = '".$Remark."'");
                        $bFlag = $objDb->execute($sSQL);

                          if($bFlag == false)
                              break;
                    }
                }
            }
      }      
  }
?>
