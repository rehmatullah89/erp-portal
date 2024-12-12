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
			   <h1><img src="images/h1/crc/tnc-caps.jpg" width="153" height="20" vspace="10" alt="" title="" /></h1>

			    <form name="frmData" id="frmData" method="post" enctype="multipart/form-data" action="crc/update-crc-audit-cap.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
                            <input type="hidden" name="Step" value="<?= $Step ?>" />
<?
                $FailuerPoints    = getDbValue("GROUP_CONCAT(point_id SEPARATOR ', ')", "tbl_crc_audit_details", "audit_id='$Id' AND score='0'");
                $sSectionsList    = getList("tbl_tnc_sections s, tbl_tnc_points p", "s.id", "s.section", "s.id = p.section_id AND s.parent_id='$PSectionId' AND p.id IN ($FailuerPoints)");
                
                foreach($sSectionsList as $iSection => $sSection)
                {
?>
                    <h2><?="Section: ".$sSection?></h2>
<? 
                    $sSubSQL = "";
                    
                    if(!empty($CategoryId))
                        $sSubSQL = " AND c.id = '$CategoryId'";
                    
                    $sCategoriesList  = getList("tbl_tnc_categories c, tbl_tnc_points p", "c.id", "c.category", "c.id = p.category_id AND c.section_id='$iSection' AND p.id IN ($FailuerPoints) $sSubSQL", "c.position");

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
                                        <a href="crc/edit-crc-audit-cap.php?Id=<?=$Id?>&CategoryId=<?= $iCategory ?>" target="_blank"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit: <?=$sCategory?>" title="Edit :<?=$sCategory?>" /></a>&nbsp;
                                    </td>
                                </tr>
<?
                        }
                        else
                        {
                            
?>
                            <h3><?= $sCategory?></h3>
<?
                                    $sSQL = "SELECT tp.id, cad.review_score, cad.score, cad.remarks, tp.point, cad.corrective_action, cad.due_date, cad.alternative_due_date
                             FROM tbl_crc_audit_details cad, tbl_tnc_points tp
                             WHERE cad.point_id=tp.id AND cad.audit_id='$Id' AND tp.category_id='$iCategory' AND cad.score='0'
                             ORDER BY tp.position";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );
?>
			    <table border="1" bordercolor="#aaaaaa" cellpadding="5" cellspacing="0" width="100%">
			      <tr bgcolor="#eaeaea">
					<td width="3%"><b>#</b></td>
					<td width="32%"><b>Point</b></td>
                                        <td width="6%"><b>Score</b></td>
					<td width="12%" align="center"><b>Dates</b></td>
					<td width="27%"><b>Corrective Action</b></td>
                                        <td width="20%" align="center"><b>Attachments</b></td>
                                        
			      </tr>

<?
			for ($i = 0; $i < $iCount; $i ++)
			{
				$iPoint   = $objDb->getField($i, 'id');
				$sPoint   = $objDb->getField($i, 'point');
				$iScore   = $objDb->getField($i, 'score');
                                $iRScore  = $objDb->getField($i, 'review_score');
				$sRemarks = $objDb->getField($i, 'remarks');
                                $sCapDate = $objDb->getField($i, 'due_date');
                                $sAltDate = $objDb->getField($i, 'alternative_due_date');
                                $sCaps    = $objDb->getField($i, 'corrective_action');
                               
                                if($iRScore != "")
                                    $iScore = $iRScore;
                               
                                $sTitlesList    = getList("tbl_crc_audit_pictures", "id", "title", "point_id='$iPoint' AND audit_id='$Id' AND map='Y'");    
                                $sPicturesList  = getList("tbl_crc_audit_pictures", "id", "picture", "point_id='$iPoint' AND audit_id='$Id' AND map='Y'");
                                
                                $sNTitlesList    = getList("tbl_crc_audit_pictures", "id", "title", "point_id='$iPoint' AND audit_id='$Id' AND map='N'");    
                                $sNPicturesList  = getList("tbl_crc_audit_pictures", "id", "picture", "point_id='$iPoint' AND audit_id='$Id' AND map='N'");
                                
                                
?>

				  <tr valign="top" class="<?= $sClass[($i % 2)] ?>">
				    <td align="center"><?= ($i + 1) ?></td>

				    <td>
					  <input type="hidden" name="Point[]" value="<?= $iPoint ?>" />
					  <?php echo $sPoint."<br/>";
                                          
                                            foreach ($sNPicturesList as $iPicture => $sPicture)
                                            {
                                                $extensions = explode('.', $sPicture);
                                                $extension  = end($extensions);                                                
?>
                                                <a href="<?= ($sTncDir.$sPicture) ?>" class="<?=(@in_array(strtolower($extension), array('png', 'jpg', 'jpeg', 'gif', 'bmp'))?'lightview':'')?>" rel="gallery[defects]" title="<?= $sNTitlesList[$iPicture] ?> :: :: topclose: true"><?=$sNTitlesList[$iPicture];?></a>, 
<?
                                            }
                                          ?>
                                          
				    </td>
                                    
                                    <td align="center">
					  <select name="Score<?= $iPoint ?>">
					    <option value="-1">N/A</option>
					    <option value="1"<?= (($iScore == 1) ? " selected" : "") ?> style="background:#00ff00;">1</option>
					    <option value="0"<?= (($iScore == 0) ? " selected" : "") ?> style="background:#ff0000;">0</option>
					  </select>
                                        <input type="hidden" name="PrevScore<?= $iPoint ?>" value="<?=$iScore?>"/>
				    </td>

				    <td align="center">
                                        <span>Due Date</span>
					    <table> 
                                                <tr>
                                                    <td width="78"><input type="text" name="CapDate<?=$iPoint?>" value="<?= (trim($sCapDate) == '0000-00-00'?"":trim($sCapDate)) ?>" id="CapDate<?=$i?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('CapDate<?=$i?>'), 'yyyy-mm-dd', this);" required/></td>
                                                    <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('CapDate<?=$i?>'), 'yyyy-mm-dd', this);" /></td>
                                                </tr>
                                            </table> <br/>
                                            <span>Alternative Date</span>
					    <table> 
                                                <tr>
                                                    <td width="78"><input type="text" name="AltDate<?=$iPoint?>" value="<?= (trim($sAltDate) == '0000-00-00'?"":trim($sAltDate)) ?>" id="AltDate<?=$iPoint?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('AltDate<?=$iPoint?>'), 'yyyy-mm-dd', this);" /></td>
                                                    <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('AltDate<?=$iPoint?>'), 'yyyy-mm-dd', this);" /></td>
                                                </tr>
                                            </table> <br/>
                                    </td>

				    <td><textarea name="Caps<?= $iPoint ?>" rows="4" style="width:98%; height:100%;"><?= $sCaps ?></textarea>
                                        <?= ($sRemarks != "")?"<br/><b>Remarks:</b> ".$sRemarks:"";?>
                                    </td>
                                    <td><input name="files<?=$iPoint?>[]" multiple="multiple" type="file" value="" maxlength="200" size="40" />
<?
                                        foreach ($sPicturesList as $iPicture => $sPicture)
                                        {
                                                $extensions = explode('.', $sPicture);
                                                $extension  = end($extensions);                                                
?>
                                        <a href="<?= ($sTncDir.$sPicture) ?>" class="<?=(@in_array(strtolower($extension), array('png', 'jpg', 'jpeg', 'gif', 'bmp'))?'lightview':'')?>" rel="gallery[defects]" title="<?= $sTitlesList[$iPicture] ?> :: :: topclose: true"><?=$sTitlesList[$iPicture];?></a><br/>
<?
                                        }
?>
                                    </td>
				  </tr>
<?
			}
?>
                                <input type="hidden" name="Counter" id="Counter" value="<?=$iCount?>">
	            </table>    
                        <div class="buttonsBar">
                            <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm();" />
                            <input type="button" value="" class="btnBack" title="Back" onclick="document.location='crc/<?= (($Step == 0) ? 'crc-audits.php' : ('edit-crc-audit-cap.php?Id='.$Id.'&Step='.($Step - 1))) ?>';" />
                        </div>
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
<script>
    function validateForm()
    {
        var count = document.getElementById("Counter").value; 
        
        for(i=0; i<count; i++)
        {
            var capDate = document.getElementById("CapDate"+i).value; 
            
            if(capDate == "" || capDate == "0000-00-00")
            {
                alert("Please Enter CAP Date to proceed!");
                 return false
            }
        }
        
        return true;
    }
</script>
</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>