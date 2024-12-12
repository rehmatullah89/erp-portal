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
	**  Project Developer:                                                                       **
	**                                                                                           **
	**      Name  :  Rehmatullah Bhatti                                                          **
	**      Email :  rehmatullahbhatti@gmail.com                                                 **
	**      Phone :  +92 344 40 43 675                                                           **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
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
        $sSQL = "SELECT child_labour_site_name, child_labour_site_address, child_labour_site_phone, child_labour_site_fax, child_labour_site_email, child_labour_site_person,
                    child_labour_conformance, child_labour_non_conformance, child_labour_comments, 
                    child_labour_recommendations, child_labour_deadline, child_labour_result
                FROM tbl_qa_hohenstein
                WHERE audit_id='$Id'";
        
	$objDb->query($sSQL);
        
        $sSiteName          = $objDb->getField(0, "child_labour_site_name");
        $sSiteFax           = $objDb->getField(0, "child_labour_site_fax");
        $sSiteAddress       = $objDb->getField(0, "child_labour_site_address");
        $sSiteEmail         = $objDb->getField(0, "child_labour_site_email");
        $sSitePhone         = $objDb->getField(0, "child_labour_site_phone");
        $sSitePerson        = $objDb->getField(0, "child_labour_site_person");
	$sCLConformance     = $objDb->getField(0, "child_labour_conformance");
	$sCLNonConformance  = $objDb->getField(0, "child_labour_non_conformance");
        $sCLComments        = $objDb->getField(0, "child_labour_comments");
        $sCLRecommendation  = $objDb->getField(0, "child_labour_recommendations");
        $sCLDeadLine        = $objDb->getField(0, "child_labour_deadline");
        $sCLResult          = $objDb->getField(0, "child_labour_result");
            
        $sChildLabourQuestions = getList("tbl_child_labour_questions", "id", "question", "status='A'", "position");
        $sChildLabourResults   = getList("tbl_qa_child_labour_details", "question_id", "answer", "audit_id='$Id'");
        $sChildLabourRemarks   = getList("tbl_qa_child_labour_details", "question_id", "remarks", "audit_id='$Id'");
?>
         <div style="margin: 10px;">
            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                    <td width="120"><b>Site Name</b></td>
                    <td width="20">:</td>
                    <td><input type="text" name="SiteName" value="<?=$sSiteName?>" size="25"/></td>

                    <td width="130"><b>Site Fax</b></td>
                    <td width="20">:</td>
                    <td><input type="text" name="SiteFax" value="<?=$sSiteFax?>" size="25"></td>
                </tr>
                <tr>
                    <td width="80"><b>Site Address</b></td>
                    <td width="20">:</td>
                    <td  width="80"><input type="text" name="SiteAddress" value="<?=$sSiteAddress?>" size="25"></td>
                      
                    <td width="80"><b>Site Email</b></td>
                    <td width="20">:</td>
                    <td  width="80"><input type="text" name="SiteEmail" value="<?=$sSiteEmail?>" size="25"></td>
                </tr>
                <tr>
                    <td width="80"><b>Site Phone</b></td>
                    <td width="20">:</td>
                    <td><input type="text" name="SitePhone" value="<?=$sSitePhone?>" size="25"></td>
                      
                    <td width="80"><b>Site Contact Person</b></td>
                    <td width="20">:</td>
                    <td  width="80"><input type="text" name="SitePerson" value="<?=$sSitePerson?>" size="25"></td>
                </tr>
                <tr>
                    <td width="80"><b>Result</b></td>
                    <td width="20">:</td>
                    <td colspan="4">
                        <select name="Result" required="" onchange="ToggleDiv(this.value);">
                            <option value="">Select Result</option>
                            <option value="P" <?=($sCLResult == 'P'?'selected':'')?>>Pass</option>
                            <option value="F" <?=($sCLResult == 'F'?'selected':'')?>>Fail</option>
                            <option value="I" <?=($sCLResult == 'I'?'selected':'')?>>Improve</option>
                        </select>
                    </td>
                </tr>
            </table>
            </div>
        <div id="MyMainDiv" style="<?=($sCLResult == 'P' || $sCLResult == '')?'display: none;':''?>">
	<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">	
            <h3>Child Labour Check</h3>            
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td width="250"><b>Section</b></td>
                  <td><b>Comments</b></td>
            </tr>
            <tr>
                <td>1</td>
                <td>Conformance</td>
                <td><input type="text" name="Conformance" value="<?=$sCLConformance?>" size="90"></td>
            </tr>
            <tr>
                <td>2</td>
                <td>Non-Conformance</td>
                <td><input type="text" name="NonConformance" value="<?=$sCLNonConformance?>" size="90"></td>
            </tr>
            <tr>
                <td>3</td>
                <td>Any other comments</td>
                <td><input type="text" name="OtherComments" value="<?=$sCLComments?>" size="90"></td>
            </tr>
            <tr>
                <td>4</td>
                <td>Recommendation for corrective action</td>
                <td><input type="text" name="Recommendations" value="<?=$sCLRecommendation?>" size="90"></td>
            </tr>
            <tr>
                <td>5</td>
                <td>Deadline for implementiong corrective action</td>
                <td><input type="text" name="DeadLine" value="<?=$sCLDeadLine?>" size="90"></td>
            </tr>
        </table>
        <br/>
        <h3>Interview - Questions for Child Labor Inspection</h3>
         <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
            <tr class="sdRowHeader">
                  <td width="15"><b>#</b></td>
                  <td width="300"><b>Question</b></td>
                  <td width="110"><b>Result</b></td>
                  <td><b>Remarks</b></td>
            </tr>
<?
            $iCounter = 1;    
            foreach($sChildLabourQuestions as $iQuestion => $sQuestion)
            {
?>
                <tr>
                    <td><?=$iCounter++?></td>
                    <td><?=$sQuestion?><input type="hidden" name="Questions[]" value="<?=$iQuestion?>"></td>
                    <td>
                        <select name="Answers[]">
                            <option value="">Select Result</option>
                            <option value="Y" <?=($sChildLabourResults[$iQuestion] == 'Y'?'selected':'')?>>Yes</option>
                            <option value="N" <?=($sChildLabourResults[$iQuestion] == 'N'?'selected':'')?>>No</option>
                        </select>
                    </td>
                    <td>
                        <textarea name="QuestionRemarks[]" style="width: 95%;" rows="5"><?=$sChildLabourRemarks[$iQuestion]?></textarea>
                    </td>
                </tr>
<?
            }
?>
         </table><br/>
         <h3>Child Labour Record Sheet</h3>
         <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="ChildLaborTable" style="text-align:center;">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td width="90"><b>Name</b></td>
                  <td width="75"><b>Birthday</b></td>
                  <td width="60"><b>Attending School</b></td>
                  <td width="60"><b>Present During Regular Class Session</b></td>
                  <td width="65"><b>Met in Non Hazardous Areas</b></td>
                  <td width="60"><b>Receives Education</b></td>
                  <td width="80"><b>Since When in the Company</b></td>
                  <td width="70"><b>Working under ILO Convention 138 exceptions</b></td>
                  <td><b>Comments</b></td>
            </tr>
<?      
            $sSQL = "SELECT *
                    FROM tbl_qa_child_labour
	         WHERE audit_id='$Id'";
        
            $objDb->query($sSQL);
            
            $iCount = $objDb->getCount();
        
            if($iCount > 0)
            {
                for($i=0; $i<$iCount; $i++)
                {
                    $sName                  = $objDb->getField($i, "name");
                    $sBirthMonth            = $objDb->getField($i, "birth_month");
                    $sBirthYear             = $objDb->getField($i, "birth_year");
                    $sAttendSchool          = $objDb->getField($i, "attended_school");
                    $sSchoolLessons         = $objDb->getField($i, "school_lessons");
                    $sNonHazerdous          = $objDb->getField($i, "non_hazaradous_areas");
                    $sEducation             = $objDb->getField($i, "education");
                    $sJoiningMonth          = $objDb->getField($i, "joining_month");
                    $sJoiningYear           = $objDb->getField($i, "joining_year");
                    $sWorkUnderIlo          = $objDb->getField($i, "working_under_ilo");
                    $sChildLabourComments   = $objDb->getField($i, "comments");
                    
?>
               <tr>
                <td><?=$i+1?></td>
                <td><input type="text" name="Name[]" value="<?=$sName?>" size="10"/></td>
                        <td id="BirthdayId">
                            <select name="BirthMonth[]">
                                <option value=""></option>
                                <option value="1" <?=($sBirthMonth == 1?'selected':'')?>>Jan</option>
                                <option value="2" <?=($sBirthMonth == 2?'selected':'')?>>Feb</option>
                                <option value="3" <?=($sBirthMonth == 3?'selected':'')?>>Mar</option>
                                <option value="4" <?=($sBirthMonth == 4?'selected':'')?>>Apr</option>
                                <option value="5" <?=($sBirthMonth == 5?'selected':'')?>>May</option>
                                <option value="6" <?=($sBirthMonth == 6?'selected':'')?>>Jun</option>
                                <option value="7" <?=($sBirthMonth == 7?'selected':'')?>>Jul</option>
                                <option value="8" <?=($sBirthMonth == 8?'selected':'')?>>Aug</option>
                                <option value="9" <?=($sBirthMonth == 9?'selected':'')?>>Sep</option>
                                <option value="10" <?=($sBirthMonth == 10?'selected':'')?>>Oct</option>
                                <option value="11" <?=($sBirthMonth == 11?'selected':'')?>>Nov</option>
                                <option value="12" <?=($sBirthMonth == 12?'selected':'')?>>Dec</option>                                
                            </select>
                            <select name="BirthYear[]">
                                <option value=""></option>
<?
                                for($k=2018; $k>=1980; $k--)
                                {
?>
                                <option value="<?=$k?>" <?=($sBirthYear == $k?'selected':'')?>><?=$k?></option>
<?
                                }
?>
                            </select>
                        </td>
                        <td id="AttendSchoolId">
                            <select name="AttendSchool[]">
                                <option value=""></option>
                                <option value="Y" <?=($sAttendSchool == 'Y'?'selected':'')?>>Yes</option>
                                <option value="N" <?=($sAttendSchool == 'N'?'selected':'')?>>No</option>
                            </select>
                        </td>
                        <td id="RegClassId">
                            <select name="RegClass[]">
                                <option value=""></option>
                                <option value="Y" <?=($sSchoolLessons == 'Y'?'selected':'')?>>Yes</option>
                                <option value="N" <?=($sSchoolLessons == 'N'?'selected':'')?>>No</option>
                            </select>
                        </td>
                        <td id="HazardAreaId">
                            <select name="HazardArea[]">
                                <option value=""></option>
                                <option value="Y" <?=($sNonHazerdous == 'Y'?'selected':'')?>>Yes</option>
                                <option value="N" <?=($sNonHazerdous == 'N'?'selected':'')?>>No</option>
                            </select>
                        </td>
                        <td id="ReceiveEduId">
                            <select name="ReceiveEducation[]">
                                <option value=""></option>
                                <option value="Y" <?=($sEducation == 'Y'?'selected':'')?>>Yes</option>
                                <option value="N" <?=($sEducation == 'N'?'selected':'')?>>No</option>
                            </select>
                        </td>
                        <td id="CompanyId">
                            <select name="InCompanyMonth[]">
                                <option value=""></option>
                                <option value="1" <?=($sJoiningMonth == 1?'selected':'')?>>Jan</option>
                                <option value="2" <?=($sJoiningMonth == 2?'selected':'')?>>Feb</option>
                                <option value="3" <?=($sJoiningMonth == 3?'selected':'')?>>Mar</option>
                                <option value="4" <?=($sJoiningMonth == 4?'selected':'')?>>Apr</option>
                                <option value="5" <?=($sJoiningMonth == 5?'selected':'')?>>May</option>
                                <option value="6" <?=($sJoiningMonth == 6?'selected':'')?>>Jun</option>
                                <option value="7" <?=($sJoiningMonth == 7?'selected':'')?>>Jul</option>
                                <option value="8" <?=($sJoiningMonth == 8?'selected':'')?>>Aug</option>
                                <option value="9" <?=($sJoiningMonth == 9?'selected':'')?>>Sep</option>
                                <option value="10" <?=($sJoiningMonth == 10?'selected':'')?>>Oct</option>
                                <option value="11" <?=($sJoiningMonth == 11?'selected':'')?>>Nov</option>
                                <option value="12" <?=($sJoiningMonth == 12?'selected':'')?>>Dec</option>                                  
                            </select>
                            <select name="InCompanyYear[]">
                                <option value=""></option>
<?
                                for($k=2018; $k>=1980; $k--)
                                {
?>
                                <option value="<?=$k?>" <?=($sJoiningYear == $k?'selected':'')?>><?=$k?></option>
<?
                                }
?>
                            </select>
                        </td>
                        <td id="WorkingIloId">
                            <select name="WorkingIlo[]">
                                <option value=""></option>
                                <option value="Y" <?=($sWorkUnderIlo == 'Y'?'selected':'')?>>Yes</option>
                                <option value="N" <?=($sWorkUnderIlo == 'N'?'selected':'')?>>No</option>
                            </select>
                        </td>
                        <td><input type="text" name="Remarks[]" value="<?=$sChildLabourComments?>" style="width:99%;"/></td>
            </tr>
                    
<?
                }
            }
            else {
                $iCount = 1;
?>
            <tr>
                <td>1</td>
                <td><input type="text" name="Name[]" value="" size="10"/></td>
                        <td id="BirthdayId">
                            <select name="BirthMonth[]">
                                <option value=""></option>
                                <option value="1">Jan</option>
                                <option value="2">Feb</option>
                                <option value="3">Mar</option>
                                <option value="4">Apr</option>
                                <option value="5">May</option>
                                <option value="6">Jun</option>
                                <option value="7">Jul</option>
                                <option value="8">Aug</option>
                                <option value="9">Sep</option>
                                <option value="10">Oct</option>
                                <option value="11">Nov</option>
                                <option value="12">Dec</option>                                
                            </select>
                            <select name="BirthYear[]">
                                <option value=""></option>
<?
                                for($k=2018; $k>=1980; $k--)
                                {
?>
                                <option value="<?=$k?>"><?=$k?></option>
<?
                                }
?>
                            </select>
                        </td>
                        <td id="AttendSchoolId">
                            <select name="AttendSchool[]">
                                <option value=""></option>
                                <option value="Y">Yes</option>
                                <option value="N">No</option>
                            </select>
                        </td>
                        <td id="RegClassId">
                            <select name="RegClass[]">
                                <option value=""></option>
                                <option value="Y">Yes</option>
                                <option value="N">No</option>
                            </select>
                        </td>
                        <td id="HazardAreaId">
                            <select name="HazardArea[]">
                                <option value=""></option>
                                <option value="Y">Yes</option>
                                <option value="N">No</option>
                            </select>
                        </td>
                        <td id="ReceiveEduId">
                            <select name="ReceiveEducation[]">
                                <option value=""></option>
                                <option value="Y">Yes</option>
                                <option value="N">No</option>
                            </select>
                        </td>
                        <td id="CompanyId">
                            <select name="InCompanyMonth[]">
                                <option value=""></option>
                                <option value="1">Jan</option>
                                <option value="2">Feb</option>
                                <option value="3">Mar</option>
                                <option value="4">Apr</option>
                                <option value="5">May</option>
                                <option value="6">Jun</option>
                                <option value="7">Jul</option>
                                <option value="8">Aug</option>
                                <option value="9">Sep</option>
                                <option value="10">Oct</option>
                                <option value="11">Nov</option>
                                <option value="12">Dec</option>                                
                            </select>
                            <select name="InCompanyYear[]">
                                <option value=""></option>
<?
                                for($k=2018; $k>=1980; $k--)
                                {
?>
                                <option value="<?=$k?>"><?=$k?></option>
<?
                                }
?>
                            </select>
                        </td>
                        <td id="WorkingIloId">
                            <select name="WorkingIlo[]">
                                <option value=""></option>
                                <option value="Y">Yes</option>
                                <option value="N">No</option>
                            </select>
                        </td>
                        <td><input type="text" name="Remarks[]" value="" style="width:99%;"/></td>
            </tr>
<?
            }
?>
         </table> 
            <input type="hidden" name="Counter" id="Counter" value="<?=$iCount+1?>">
            <a id="BtnAddRow" onclick="AddChildLaborRecords()">Add [+]</a> / <a id="BtnDelRow" onclick="DeleteChildLaborRecords()">Remove [-]</a>
            <br/><br/>            
	</div>
        </div>    
	<script type="text/javascript">
	    <!--

    function ToggleDiv(Val)
    {
        if(Val != '' && Val != 'P')
            document.getElementById("MyMainDiv").style.display = '';
        else
            document.getElementById("MyMainDiv").style.display = 'none';
    }
            
    var i=document.getElementById("Counter").value;
    function AddChildLaborRecords() 
    {
        var table = document.getElementById("ChildLaborTable");
        var rowCount = table.rows.length;
        var row     = table.insertRow(rowCount);
        var cell1   = row.insertCell(0);
        var cell2   = row.insertCell(1);
        var cell3   = row.insertCell(2);
        var cell4   = row.insertCell(3);
        var cell5   = row.insertCell(4);
        var cell6   = row.insertCell(5);
        var cell7   = row.insertCell(6);
        var cell8   = row.insertCell(7);
        var cell9   = row.insertCell(8);
        var cell10  = row.insertCell(9);
        
        cell1.innerHTML = i;
        cell2.innerHTML = '<input type="text" name="Name[]" value="" size="10"/>';
        cell3.innerHTML = document.getElementById("BirthdayId").innerHTML;
        cell4.innerHTML = document.getElementById("AttendSchoolId").innerHTML;
        cell5.innerHTML = document.getElementById("RegClassId").innerHTML;
        cell6.innerHTML = document.getElementById("HazardAreaId").innerHTML;
        cell7.innerHTML = document.getElementById("ReceiveEduId").innerHTML;
        cell8.innerHTML = document.getElementById("CompanyId").innerHTML;
        cell9.innerHTML = document.getElementById("WorkingIloId").innerHTML;
        cell10.innerHTML = '<input type="text" name="Remarks[]" value="" style="width:99%;"/>';
        i++;
        document.getElementById("CountRows").value = i;
    }

    function DeleteChildLaborRecords() {
        var table = document.getElementById("ChildLaborTable");
        var rowCount = table.rows.length;
        
        if(rowCount > 2)
        {
            table.deleteRow(rowCount-1);
            i--;
            document.getElementById("CountRows").value = i;
        }
    }
    -->
</script> 