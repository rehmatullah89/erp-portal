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

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$PageId         = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Vendor         = IO::intValue("Vendor");
        $Auditor        = IO::intValue("Auditor");
        $Language       = IO::intValue("Language"); 
        $Shift1         = IO::strValue("Shift1"); 
        $Shift2         = IO::strValue("Shift2"); 
        $Shift3         = IO::strValue("Shift3"); 
        $Department     = IO::intValue("Department");        
        $PermMen        = IO::intValue("PermMen"); 
        $PermWomen      = IO::intValue("PermWomen"); 
        $PermYoung      = IO::intValue("PermYoung"); 
        $TempMen        = IO::intValue("TempMen"); 
        $TempWomen      = IO::intValue("TempWomen"); 
        $TempYoung      = IO::intValue("TempYoung");
        $MgtRep         = IO::strValue("MgtRep");
        $MgtRepEmail    = IO::strValue("MgtRepEmail");
        $ScheduleId     = IO::intValue("ScheduleId");
        $EndDate        = IO::strValue("EndDate");
        $Observations   = IO::strValue("Observations");
        $SameCompound   = IO::strValue("SameCompound");
        $PeakSeason     = IO::strValue("PeakSeason");
	$PostId         = IO::strValue("PostId");


	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Vendor         = IO::intValue("Vendor");
                $Auditor        = IO::intValue("Auditor");
                $Language       = IO::intValue("Language"); 
                $Department     = IO::intValue("Department");
                $Shift1         = IO::strValue("Shift1"); 
                $Shift2         = IO::strValue("Shift2"); 
                $Shift3         = IO::strValue("Shift3"); 
                $PermMen        = IO::intValue("PermMen"); 
                $PermWomen      = IO::intValue("PermWomen"); 
                $PermYoung      = IO::intValue("PermYoung"); 
                $TempMen        = IO::intValue("TempMen"); 
                $TempWomen      = IO::intValue("TempWomen"); 
                $TempYoung      = IO::intValue("TempYoung"); 
                $MgtRep         = IO::strValue("MgtRep");
                $EndDate        = IO::strValue("EndDate");
                $MgtRepEmail    = IO::strValue("MgtRepEmail");
                $Observations   = IO::strValue("Observations");
                $SameCompound   = IO::strValue("SameCompound");
                $PeakSeason     = IO::strValue("PeakSeason");
                $ScheduleId     = IO::intValue("ScheduleId");
	}

        $sAuditorsList      = getList("tbl_users", "id", "name");
	$sVendorsList       = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");        
        $sLanguagesList     = getList("tbl_languages", "id", "language");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/crc/crc-audits.js"></script>
  
</head>

<body>

<div id="MainDiv">
  <div id="PageLeftBorder">
    <div id="PageRightBorder">

<!--  Message Section Starts Here  -->
<?
	@include($sBaseDir."includes/messages.php");
?>
<!--  Message Section Ends Here  -->

      <div id="PageContents">

<!--  Header Section Starts Here  -->
<?
	@include($sBaseDir."includes/header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Navigation Section Starts Here  -->
<?
	@include($sBaseDir."includes/navigation.php");
?>
<!--  Navigation Section Ends Here  -->


<!--  Body Section Starts Here  -->
	    <div id="Body">
		  <table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr valign="top">
			  <td width="100%">
			    <h1>CRC Audits</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
				<form name="frmData" id="frmData"  method="post" action="crc/save-crc-audit.php" enctype="multipart/form-data"  class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add New Audit</h2>

                            <table border="0" cellpadding="3" cellspacing="0" width="100%" >
                                <tr>
                                    <td width="300">Vendor<span style="color:red;">*</span></td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Vendor" id="Vendor" onchange="getSchedules(this.value, 'ScheduleId'); getListValues('Vendor', 'Department', 'VendorDepartments');">
						<option value=""></option>
<?
		foreach ($sVendorsList as $sKey => $sValue)
		{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Vendor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>
                                  <tr>
					<td>Schedule Number<span style="color:red;">*</span></td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="ScheduleId" id="ScheduleId">
						<option value=""></option>
					  </select>
					</td>
				  </tr>
                                    
                                  <tr>
					<td>Department</td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Department" id="Department"  style="width:200px;">
                                                <option value=""></option>
<?
            if($Vendor > 0)
            {
                $sDepartmentsList   = getList("tbl_crc_departments cd, tbl_vendors v", "cd.id", "cd.department", "FIND_IN_SET(cd.id, v.crc_departments) AND v.id='$Vendor'");
                
		foreach ($sDepartmentsList as $iDepartment => $sDepartment)
		{
?>
			              <option value="<?= $iDepartment ?>"<?= (($iDepartment == $Department) ? " selected" : "") ?>><?= $sDepartment ?></option>
<?
		}
            }
?>
					  </select>
					</td>
				  </tr>  
                                    
                                  <tr>
					<td>Language</td>
					<td width="20" align="center">:</td>

					<td>
                                            <select name="Language" id="Language" style="width:200px;">
                                                <option value=""></option>
<?
		foreach ($sLanguagesList as $iLanguage => $sLanguage)
		{
?>
			              <option value="<?= $iLanguage ?>"<?= (($iLanguage == $Language) ? " selected" : "") ?>><?= $sLanguage ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>    
                                    
                                  <tr>
					<td>Management Representative</td>
					<td align="center">:</td>
					<td><input type="text" name="MgtRep" value="<?= $MgtRep ?>" maxlength="250" size="26" class="textbox" /></td>
				  </tr>
                                  
                                   <tr>
					<td>Management Representative Email</td>
					<td align="center">:</td>
					<td><input type="text" name="MgtRepEmail" value="<?= $MgtRepEmail ?>" maxlength="250" size="26" class="textbox" /></td>
				  </tr>
                                    
                                  <tr>
					<td>Peak Season</td>
					<td align="center">:</td>
					<td><input type="text" name="PeakSeason" value="<?= $PeakSeason ?>" maxlength="250" size="26" class="textbox" /></td>
				  </tr>
                                  
                                  <tr>
                                      <td>Are there other factory/farms/companies in the same compound? <span style="color:red;">If yes, please specify</span></td>
					<td align="center">:</td>
					<td><input type="text" name="SameCompound" value="<?= $SameCompound ?>" maxlength="250" size="26" class="textbox" /></td>
				  </tr>
                                    
                                    <tr>
					<td>Audit End Date</td>
					<td align="center">:</td>
					<td>
                                            <table> 
                                                <tr>
                                                    <td width="78"><input type="text" name="EndDate" value="<?= $EndDate ?>" id="EndDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('EndDate'), 'yyyy-mm-dd', this);" /></td>
                                                    <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('EndDate'), 'yyyy-mm-dd', this);" /></td>
                                                </tr>
                                            </table> 
                                        </td>
                                    </tr> 
                                    <tr>
					<td>Shifts</td>
					<td width="20" align="center">:</td>
                                        <td>Shift 1:<input type="text" name="Shift1" value="<?= $Shift1 ?>" maxlength="250" size="5" class="textbox" />
                                            &nbsp;&nbsp;&nbsp; Shift 2:<input type="text" name="Shift2" value="<?= $Shift2 ?>" maxlength="250" size="5" class="textbox" />
                                            &nbsp;&nbsp;&nbsp; Shift 3:<input type="text" name="Shift3" value="<?= $Shift3 ?>" maxlength="250" size="5" class="textbox" />
                                        </td>					
				  </tr>
                                    <tr>
					<td>Total Number of Permanent Workers on Audit Date</td>
					<td align="center">:</td>
					<td>&nbsp;Male:<input type="text" name="PermMen" value="<?= $PermMen ?>" maxlength="250" size="6" class="textbox" />
                                            &nbsp;&nbsp; Female:<input type="text" name="PermWomen" value="<?= $PermWomen ?>" maxlength="250" size="6" class="textbox" />
                                            &nbsp;&nbsp; Young:<input type="text" name="PermYoung" value="<?= $PermYoung ?>" maxlength="250" size="6" class="textbox" />
                                        </td>
				    </tr>
                                    
                                    <tr>
					<td>Total Number of Temporary Workers on Audit Date</td>
					<td align="center">:</td>
					<td>&nbsp;Male:<input type="text" name="TempMen" value="<?= $TempMen ?>" maxlength="250" size="6" class="textbox" />
                                            &nbsp;&nbsp; Female:<input type="text" name="TempWomen" value="<?= $TempWomen ?>" maxlength="250" size="6" class="textbox" />
                                            &nbsp;&nbsp; Young:<input type="text" name="TempYoung" value="<?= $TempYoung ?>" maxlength="250" size="6" class="textbox" />
                                        </td>
				    </tr>
                                    
                                    <tr>
                                            <td>Factory Picture</td>
                                            <td align="center">:</td>
                                            <td><input type="file" name="AuditPicture[]" multiple=""/></td>
                                    </tr>
                                
                                    <tr>
					<td>General Observations</td>
					<td align="center">:</td>
                                        <td><textarea name="Observations" rows="6" style="width:90%;"><?= $Observations ?></textarea></td>
                                    </tr>
 				</table>

				<br />

				<div class="buttonsBar"><input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" /></div>
			    </form>

			    <hr />
<?
	}
?>

			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="52">Vendor</td>

			          <td width="180">
			            <select name="Vendor" style="width:180px;">
			              <option value="">All Vendors</option>
<?
	foreach ($sVendorsList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Vendor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
			            </select>
			          </td>

			          <td width="55">Auditor</td>

			          <td width="180">
			            <select name="Auditor" id="Auditor">
			              <option value="">All Auditors</option>
<?
	foreach ($sAuditorsList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Auditor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
			            </select>
			          </td>

			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </div>

			      <div id="SubSearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
					  <td width="52">From</td>
					  <td width="145"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:135px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="35"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="55" align="">To</td>
					  <td width="145"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:135px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td>[ <a href="#" onclick="$('FromDate').value=''; $('ToDate').value=''; return false;">Clear</a> ]</td>
			        </tr>
			      </table>
			      </div>
			    </form>


			    <div class="tblSheet">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "WHERE total_score != '0' ";

	if ($Vendor > 0)
		$sConditions .= " AND vendor_id='$Vendor' ";

	else
		$sConditions .= " AND vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Auditor > 0)
		$sConditions .= " AND auditor_id = '$Auditor' ";

	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND (audit_date BETWEEN '$FromDate' AND '$ToDate') ";


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_crc_audits", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT id, prev_audit_id, audit_sections, vendor_id, audit_date, auditor_id, total_score, score, audit_type_id, brand_id, completed FROM tbl_crc_audits $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow">
				      <td width="7%">#</td>
				      <td width="18%">Vendor</td>
				      <td width="13%">Audit Date</td>
				      <td width="20%">Auditor</td>
 					  <td width="9%">Total Score</td>
				      <td width="5%">Score</td>
				      <td width="28%" class="center">Options</td>
				    </tr>
<?
		}


		$iId         = $objDb->getField($i, 'id');
                $AuditTypeId = $objDb->getField($i, "audit_type_id");
		$iVendor     = $objDb->getField($i, 'vendor_id');
		$iTotalScore = $objDb->getField($i, 'total_score');
		$iScore      = $objDb->getField($i, 'score');
		$sAuditDate  = $objDb->getField($i, 'audit_date');
		$iAuditor    = $objDb->getField($i, 'auditor_id');
                $iBrand      = $objDb->getField($i, 'brand_id');
                $sCompleted  = $objDb->getField($i, 'completed');
                $iPrevAudit  = $objDb->getField($i, 'prev_audit_id');
                $sAuditor    = $sAuditorsList[$iAuditor];

                $iTotalAudits = ((int)($iPrevAudit == 0?getDbValue("COUNT(1)", "tbl_crc_audits", "prev_audit_id='$iId'"):getDbValue("COUNT(1)", "tbl_crc_audits", "id='$iId'"))) + 1;
?>

				    <tr class="<?= $sClass[($i % 2)] ?>" valign="top">
                                        <td><?= str_pad($iId, 5, 0, STR_PAD_LEFT) ?></td>
				      <td><?= $sVendorsList[$iVendor] ?></td>
				      <td><?= formatDate($sAuditDate) ?></td>
				      <td><?= $sAuditor ?></td>
                                      <td><?= $iTotalScore ?></td>
                                      <td><?= $iScore ?></td>

				      <td class="center">
<?
		if ($sUserRights['Edit'] == "Y" && $sCompleted == "Y")
		{
?>
				        <a href="crc/edit-crc-audit.php?Id=<?= $iId ?>&Step=1"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
                                        <a href="crc/edit-crc-audit-cap.php?Id=<?= $iId ?>&Step=1"><img src="images/icons/attendance1.png" width="16" height="16" alt="Corrective Action Plan" title="Corrective Action Plan" /></a>
                                       
                                <!--   &nbsp; <a href="crc/crc-audit-images.php?AuditId=<?php //echo $iId ?>"><img src="images/icons/pictures.gif" width="16" height="16" hspace="1" alt="Pictures" title="Pictures" /></a>  -->
                                        &nbsp;
<?
		}
?>
                                        <a href="crc/crc-audit-certifications.php?Id=<?= $iId ?>&Completed=<?=$sCompleted?>" class="lightview" rel="iframe" title="CRC Audit Certifications :: :: width: 900, height: 650"><img src="images/icons/certificate.gif" width="16" height="16" alt="Certfications" title="Certfications" /></a>						
                                        &nbsp;
                                        <a href="crc/crc-audit-schain.php?Id=<?= $iId ?>&Completed=<?=$sCompleted?>" class="lightview" rel="iframe" title="CRC Audit Supply Chain Transparency :: :: width: 900, height: 650"><img src="images/icons/sc1.png" width="16" height="16" alt="Supply Chain Transparency" title="Supply Chain Transparency" /></a>						
                                        &nbsp;
                                        <a href="crc/crc-audit-attendance.php?Id=<?= $iId ?>&Completed=<?=$sCompleted?>" class="lightview" rel="iframe" title="CRC Audit Attendance Sheet :: :: width: 900, height: 650"><img src="images/icons/attendance3.png" width="16" height="16" alt="Attendance Sheet" title="Attendance Sheet" /></a>						
<?
		if ($sUserRights['Delete'] == "Y" && $sCompleted == "Y")
		{
?>
				        <a href="crc/delete-crc-audit.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Audit?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
				        &nbsp;
<?
		}
?>
                                        <a href="crc/view-crc-audit.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="CRC Audit :: :: width: 900, height: 650"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a>						
<?
                if($AuditTypeId == 6 && $iBrand != 365)
                {
?>
  				        <a href="crc/export-mgf-audit.php?Id=<?= $iId ?>"><img src="images/icons/pdf.gif" width="16" height="16" hspace="1" alt="Export Pdf" title="Export Pdf" /></a>                                      
<?
                }
                else if ($iBrand == 365)
                {
?>
                                        <a href="crc/export-tnc-audit.php?Id=<?= $iId ?>"><img src="images/icons/pdf.gif" width="16" height="16" hspace="1" alt="Export Pdf" title="Export Pdf" /></a>                                      
                                        <a href="crc/export-tnc-fp-audit.php?Id=<?= $iId ?>"><img src="images/icons/pdf2.png" width="16" height="16" hspace="1" alt="Export Non Compliance Points Pdf" title="Export Non Compliance Points Pdf" /></a>                                      
<?
                                        if($iTotalAudits > 1)
                                        {
?>
                                        <a href="crc/export-tnc-complete-audit.php?Id=<?= $iId ?>"><img src="images/icons/pdf-book.png" width="20" height="20" hspace="1" alt="Export Complete Audit Report" title="Export Complete Audit Report" /></a>                                      
<?
                                        }
?>
<?
                }
                else
                {
?>
  				        <a href="crc/export-crc-audit.php?Id=<?= $iId ?>"><img src="images/icons/pdf.gif" width="16" height="16" hspace="1" alt="Export Pdf" title="Export Pdf" /></a>                                      
                                        <a href="crc/export-tnc-fp-audit.php?Id=<?= $iId ?>"><img src="images/icons/pdf2.png" width="16" height="16" hspace="1" alt="Export Non Compliance Points Pdf" title="Export Non Compliance Points Pdf" /></a>                                      
<?
                                        if($iTotalAudits > 1)
                                        {
?>
                                        <a href="crc/export-crc-complete-audit.php?Id=<?= $iId ?>"><img src="images/icons/pdf-book.png" width="20" height="20" hspace="1" alt="Export Complete Audit Report" title="Export Complete Audit Report" /></a>                                      
<?
                                        }
?>
<?                    
                }                    
?>
				      </td>
				    </tr>
<?
	}

	if ($iCount == 0)
	{
?>
				    <tr>
				      <td class="noRecord">No Audit Record Found!</td>
				    </tr>
<?
	}
?>
			      </table>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Vendor={$Vendor}&Auditor={$Auditor}&FromDate={$FromDate}&ToDate={$ToDate}");
?>

			  </td>
			</tr>
		  </table>

<?
	@include($sBaseDir."includes/my-profile.php");
?>
        </div>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include($sBaseDir."includes/footer.php");
?>
<!--  Footer Section Ends Here  -->

      </div>
    </div>
  </div>
</div>

<!--  Bottom Bar Section Starts Here  -->
<?
	@include($sBaseDir."includes/bottom-bar.php");
?>
<!--  Bottom Bar Section Ends Here  -->

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>