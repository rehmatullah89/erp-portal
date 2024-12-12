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

	if ($sUserRights['Add'] != "Y" && $sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
        $objDb3      = new Database( );

	$Id         = IO::intValue('Id');
	$Step       = IO::intValue("Step");
        $SectionId  = IO::intValue("SectionId");
	$CategoryId = IO::intValue("CategoryId");
	

	$sSQL = "SELECT * FROM tbl_crc_audits WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sAuditDate     = $objDb->getField(0, "audit_date"); 
		$Vendor         = $objDb->getField(0, "vendor_id");
		$PSectionId     = $objDb->getField(0, "section_id");
		$Points         = $objDb->getField(0, "points");
		$Language       = $objDb->getField(0, "language");
		$Department     = $objDb->getField(0, "department");
		$Shift1         = $objDb->getField(0, "shift1");
		$Shift2         = $objDb->getField(0, "shift2");
		$Shift3         = $objDb->getField(0, "shift3");
		$PermMen        = $objDb->getField(0, "perm_male");
		$PermWomen      = $objDb->getField(0, "perm_female"); 
		$PermYoung      = $objDb->getField(0, "perm_young"); 
		$TempMen        = $objDb->getField(0, "temp_male"); 
		$TempWomen      = $objDb->getField(0, "temp_female"); 
		$TempYoung      = $objDb->getField(0, "temp_young"); 
		$MgtRep         = $objDb->getField(0, "mgt_representative");
		$EndDate        = $objDb->getField(0, "audit_end_date");
		$MgtRepEmail    = $objDb->getField(0, "mgt_rep_email");
		$SameCompound   = $objDb->getField(0, "same_compound");
		$PeakSeason     = $objDb->getField(0, "peak_season");
		$Observations   = $objDb->getField(0, "observations");
		$sPictures      = explode(",", $objDb->getField(0, "picture"));
	}

	else
		redirect($_SERVER['HTTP_REFERER'], "ERROR");
        
		
	@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

	@mkdir(($sBaseDir.TNC_PICS_DIR.$sYear), 0777);
	@mkdir(($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth), 0777);
	@mkdir(($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth."/".$sDay), 0777);

	$sTncDir = (TNC_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");
		
	$sDepartmentsList   = getList("tbl_crc_departments cd, tbl_vendors v", "cd.id", "cd.department", "FIND_IN_SET(cd.id, v.crc_departments) AND v.id='$Vendor'");
	$sLanguagesList     = getList("tbl_languages", "id", "language");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
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
			   <h1>crc audits</h1>

			    <form name="frmData" id="frmData" method="post" enctype="multipart/form-data" action="crc/update-crc-audit.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
                <input type="hidden" name="Step" value="<?= $Step ?>" />
				<input type="hidden" name="SectionId" value="<?= $SectionId ?>" />
				<input type="hidden" name="CategoryId" value="<?= $CategoryId ?>" />
<?
	if($Step == 1)
	{
?>
                           <h2>CRC Audit Details</h2> 
                           <table border="0" cellpadding="3" cellspacing="0" width="100%" >
			     <tr>
                                 <td width="300">Vendor<span style="color:red;">*</span></td>
					<td width="20" align="center">:</td>

                                        <td><?= getDbValue('vendor', "tbl_vendors", "id='$Vendor'");?></td>
				  </tr>
                                  <tr>
					<td>Schedule Number<span style="color:red;">*</span></td>
					<td width="20" align="center">:</td>

                                        <td><?= "C".str_pad($Id, 5, 0, STR_PAD_LEFT)?></td>
				  </tr>
                                    
                                  <tr>
					<td>Department</td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Department" id="Department"  style="width:200px;">
                                                <option value=""></option>
<?
		foreach ($sDepartmentsList as $iDepartment => $sDepartment)
		{
?>
			              <option value="<?= $iDepartment ?>"<?= (($iDepartment == $Department) ? " selected" : "") ?>><?= $sDepartment ?></option>
<?
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
					<td>Audit Date</td>
					<td align="center">:</td>
					<td>
                                            <table> 
                                                <tr>
                                                    <td width="78"><input type="text" name="AuditDate" value="<?= $sAuditDate ?>" id="AuditDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('AuditDate'), 'yyyy-mm-dd', this);" /></td>
                                                    <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Audit Date" title="Audit Date" style="cursor:pointer;"  onclick="displayCalendar($('AuditDate'), 'yyyy-mm-dd', this);" /></td>
                                                </tr>
                                            </table> 
                                        </td>
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
					<td>Total Number of Permanent Workers on Audit Date:</td>
					<td align="center">:</td>
					<td>Male:<input type="text" name="PermMen" value="<?= $PermMen ?>" maxlength="250" size="6" class="textbox" />
                                            &nbsp;&nbsp; Female:<input type="text" name="PermWomen" value="<?= $PermWomen ?>" maxlength="250" size="6" class="textbox" />
                                            &nbsp;&nbsp; Young:<input type="text" name="PermYoung" value="<?= $PermYoung ?>" maxlength="250" size="6" class="textbox" />
                                        </td>
				    </tr>
                                    
                                    <tr>
					<td>Total Number of Temporary Workers on Audit Date:</td>
					<td align="center">:</td>
					<td>Male:<input type="text" name="TempMen" value="<?= $TempMen ?>" maxlength="250" size="6" class="textbox" />
                                            &nbsp;&nbsp; Female:<input type="text" name="TempWomen" value="<?= $TempWomen ?>" maxlength="250" size="6" class="textbox" />
                                            &nbsp;&nbsp; Young:<input type="text" name="TempYoung" value="<?= $TempYoung ?>" maxlength="250" size="6" class="textbox" />
                                        </td>
				    </tr>
                                    <tr>
                                            <td>Factory Picture</td>
                                            <td align="center">:</td>
                                            <td><input type="file" name="AuditPicture[]" multiple=""/><br/>
<?
                    if(!empty($sPictures))
                    {
                        foreach($sPictures as $sPicture)
                        {
?>
                                            <a href="<?= ($sTncDir.$sPicture) ?>" class="lightview" rel="gallery[defects]" title="Factory Picture :: :: topclose: true"><?=$sPicture;?></a>
                                            <a  href="crc/delete-crc-factory-image.php?File=<?= $sPicture ?>&AuditId=<?= $Id ?>&AuditDate=<?= $sAuditDate ?>&Referer=<?= urlencode($Referer) ?>" onclick="return confirm('Are you SURE, You want to Delete this Image?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
                                            <br/>
<?
                        }
                    }
?>
                                            </td>
                                    </tr>
                               
                                    <tr>
					<td>General Observations</td>
					<td align="center">:</td>
                                        <td><textarea name="Observations" rows="6" style="width:90%;"><?= $Observations ?></textarea></td>
                                    </tr>
                               
 				</table>  
                                <br/>
<?
                    }

					
                $sSectionsList = getList("tbl_tnc_sections s, tbl_tnc_points p", "s.id", "s.section", "s.id = p.section_id AND s.parent_id='$PSectionId' AND p.id IN ($Points)", "s.position");

                foreach($sSectionsList as $iSection => $sSection)
                {
					if ($SectionId > 0 && $iSection != $SectionId)
						continue;
?>
                    <h2><?="Section: ".$sSection?></h2>
<? 
                    $sSubSQL = "";
                    
                    if(!empty($CategoryId))
                        $sSubSQL = " AND c.id = '$CategoryId'";
                    
                    $sCategoriesList  = getList("tbl_tnc_categories c, tbl_tnc_points p", "c.id", "c.category", "c.id = p.category_id AND c.section_id='$iSection' AND p.id IN ($Points) $sSubSQL", "c.position");

                    $k=0;
                    
                    if($Step == 1 && $k == 0)
                    {
?>
                         <table id="SectionsTable" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                                <tr class="sdRowHeader">
                                    <td width="50" align="center"><b>#</b></td>
                                    <td ><b>Categories</b></td>
                                    <td width="100" ><b>Options</b></td>
                                </tr>
<?
                    }
                    
					
                    foreach ($sCategoriesList as $iCategory => $sCategory)
                    {
                        if($Step == 1)
                        {

?>
                                <tr>
                                    <td align="center"><?=++$k?></td>
                                    <td><?=$sCategory?></td>
                                    <td>
                                        <a href="crc/edit-crc-audit.php?Id=<?=$Id?>&SectionId=<?= $iSection ?>&CategoryId=<?= $iCategory ?>"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit: <?=$sCategory?>" title="Edit :<?=$sCategory?>" /></a>&nbsp;
                                    </td>
                                </tr>
<?
                        }
						
                        else
                        {
/*
                             $sSQL = "SELECT tp.id, cad.score, cad.remarks, tp.point
									 FROM tbl_crc_audit_details cad, tbl_tnc_points tp
									 WHERE cad.point_id=tp.id AND cad.audit_id='$Id' AND tp.category_id='$iCategory'
									 ORDER BY tp.position";
*/
							$sSQL = "SELECT id, point FROM tbl_tnc_points WHERE category_id='$iCategory' AND id IN ($Points) ORDER BY position";
							$objDb->query($sSQL);

							$iCount = $objDb->getCount( );
							
							if ($iCount == 0)
								continue;
?>
                            <h3><?= $sCategory ?></h3>

			    <table border="1" bordercolor="#aaaaaa" cellpadding="5" cellspacing="0" width="100%">
			      <tr bgcolor="#eaeaea">
					<td width="5%"><b>#</b></td>
					<td width="40%"><b>Point</b></td>
					<td width="6%" align="center"><b>Score</b></td>
					<td width="30%"><b>Remarks</b></td>
                    <td width="20%" align="center"><b>Attachments</b></td>                                        
			      </tr>
<?
							for ($i = 0; $i < $iCount; $i ++)
							{
								$iPoint   = $objDb->getField($i, 'id');
								$sPoint   = $objDb->getField($i, 'point');
								

								$iScore   = -1;
								$sRemarks = "";

								$sSQL = "SELECT id, score, remarks FROM tbl_crc_audit_details WHERE point_id='$iPoint' AND audit_id='$Id'";
								$objDb2->query($sSQL);
								
								if ($objDb2->getCount( ) > 1)
								{
									$iDetail  = $objDb2->getField(0, 'id');
									$iScore   = $objDb2->getField(0, 'score');
									$sRemarks = $objDb2->getField(0, 'remarks');
									
									
									$sSQL = "DELETE FROM tbl_crc_audit_details WHERE point_id='$iPoint' AND audit_id='$Id' AND id!='$iDetail'";
									$objDb2->execute($sSQL);
								}
								
								else if ($objDb2->getCount( ) == 1)
								{
									$iScore   = $objDb2->getField(0, 'score');
									$sRemarks = $objDb2->getField(0, 'remarks');
								}
												
											   
								$sTitlesList   = getList("tbl_crc_audit_pictures", "id", "title", "point_id='$iPoint' AND audit_id='$Id' AND map='N'");    
								$sPicturesList = getList("tbl_crc_audit_pictures", "id", "picture", "point_id='$iPoint' AND audit_id='$Id' AND map='N'");                                    
?>

				  <tr valign="top" class="<?= $sClass[($i % 2)] ?>">
				    <td align="center"><?= ($i + 1) ?></td>

				    <td>
					  <input type="hidden" name="Point[]" value="<?= $iPoint ?>" />
					  <?= $sPoint ?>
				    </td>

				    <td align="center">
					  <select name="Score<?= $iPoint ?>">
					    <option value="-1">N/A</option>
					    <option value="1"<?= (($iScore == 1) ? " selected" : "") ?> style="background:#00ff00;">1</option>
					    <option value="0"<?= (($iScore == 0) ? " selected" : "") ?> style="background:#ff0000;">0</option>
					  </select>
				    </td>

				    <td><textarea name="Remarks<?= $iPoint ?>" rows="4" style="width:98%; height:100%;"><?= $sRemarks ?></textarea></td>
                                    <td><input name="files<?=$iPoint?>[]" multiple="multiple" type="file" value="" maxlength="200" size="40" />
<?
                                        foreach ($sPicturesList as $iPicture => $sPicture)
                                        {
                                                $extensions = explode('.', $sPicture);
                                                $extension  = end($extensions);                                                
?>
                                        <a href="<?= ($sTncDir.$sPicture) ?>" class="<?=(@in_array(strtolower($extension), array('png', 'jpg', 'jpeg', 'gif', 'bmp'))?'lightview':'')?>" rel="gallery[defects]" title="<?= $sTitlesList[$iPicture] ?> :: :: topclose: true"><?=$sTitlesList[$iPicture];?></a>
                                        <a  href="crc/delete-crc-audit-image.php?File=<?= $sPicture ?>&ImageId=<?= $iPicture ?>&AuditId=<?= $Id ?>&AuditDate=<?= $sAuditDate ?>&Referer=<?= urlencode($Referer) ?>" onclick="return confirm('Are you SURE, You want to Delete this Image?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
                                        <br/>
<?
                                        }
?>
                                    </td>
				  </tr>
<?
							}
?>
	            </table>    
<?
                        }
                    }

                    if($Step == 1)
                    {
?>
                        </table>
<?
                    }


                }
            
?>
				<div class="buttonsBar">
				  <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm('<?= $Step ?>');" />
				  <input type="button" value="" class="btnBack" title="Back" onclick="document.location='crc/<?= (($SectionId > 0 && $CategoryId > 0) ? ('edit-crc-audit.php?Id='.$Id.'&Step=1') : 'crc-audits.php') ?>';" />
				</div>
			    </form>

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