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

	$Id   = IO::intValue('Id');
	$Step = IO::intValue("Step");

	$sSQL = "SELECT * FROM tbl_crc_audits WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$Vendor     = $objDb->getField(0, "vendor_id");
		$Auditor    = $objDb->getField(0, "auditor_id");
                $Brand      = $objDb->getField(0, "brand_id");
                $Points     = $objDb->getField(0, "points");
                $Section    = $objDb->getField(0, "section_id");
		$AuditDate  = $objDb->getField(0, "audit_date");
                $AuditType  = $objDb->getField(0, "audit_type_id");
                $Unit       = $objDb->getField(0, "unit_id");
                $PrevAuditId= $objDb->getField(0, "prev_audit_id"); 
                $AuditSections= $objDb->getField(0, "audit_sections"); 
                $ddQuestion = $objDb->getField(0, "questions_type");
                $Points     = @explode(",", $Points);
	}
	else
		redirect($_SERVER['HTTP_REFERER'], "ERROR");
        
                $sAuditTypesList    = getList("tbl_crc_audit_types", "id", "type", "id>0", "position");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/crc/edit-tnc-schedule.js"></script>
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
			   <h1>Crc schedules</h1>

			    <form name="frmData" id="frmData" method="post" action="crc/update-tnc-schedule.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			    <input type="hidden" name="Id" id="Id" value="<?= $Id ?>" />

<?
                $sVendors               = getDbValue("vendors", "tbl_brands", "id='$Brand'");
		$sVendorsList           = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y' AND id IN ($sVendors)");
		$sAuditorsList          = getList("tbl_users", "id", "name", "auditor_type='6'");
                $sBrandsList            = getList("tbl_brands", "id", "brand", "id IN ({$_SESSION['Brands']})");
                $sSectionList           = getList("tbl_tnc_sections", "id", "section");
                $sParentSectionsList    = getList("tbl_tnc_sections", "id", "section", "parent_id='0'");
?>
				<h2>Edit CRC Schedule</h2>
                                <table border="0" cellpadding="3" cellspacing="0" width="100%" id="Mytable">
                                    <tr>
					<td width="95">Brand</td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Brand" id="Brand" style="width: 210px;" onchange="getListValues('Brand', 'Vendor', 'BrandVendorsRecomended'); resetSections();">
						<option value=""></option>
<?
			foreach ($sBrandsList as $iBrand => $sBrand)
			{
?>
                                                <option value="<?= $iBrand ?>"<?= (($iBrand == $Brand) ? " selected" : "") ?>><?= $sBrand ?></option>
<?
			}
?>
					  </select>
					</td>
				  </tr>
			     <tr>
					<td >Vendor</td>
					<td  align="center">:</td>

					<td>
                                            <select name="Vendor" id="Vendor" style="width: 210px;" onchange="getListValues('Vendor', 'Unit', 'VendorUnits'); hideQuestionType();" >
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
                                  <tr id="UnitId">
					  	  <td>Unit</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Unit" id="Unit" style="width: 210px;">
							  <option value=""></option>
<?
			$sUnitsList = array( );

			if ($Vendor > 0)
				$sUnitsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='$Vendor' AND sourcing='Y'");

			foreach ($sUnitsList as $sKey => $sValue)
			{
?>
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $Unit) ? " selected" : "") ?>><?= $sValue ?></option>
<?
			}
?>
						    </select>
						  </td>
					    </tr>
 
                                    <tr valign="top">
					<td>Auditor</td>
					<td align="center">:</td>

					<td>
					  <select name="Auditor" id="Auditor" style="width: 210px;">
                                              <option value=""></option>
<?
		foreach ($sAuditorsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= ($sKey == $Auditor)? " selected" : ""; ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>
                                    
                                    <tr valign="top">
					<td>Audit Type</td>
					<td align="center">:</td>

					<td>
					  <select name="AuditType" id="AuditType" style="width: 210px;" onchange="resetSections2();"> <!-- getQuestionsOptions(this.value); -->
                                              <option value=""></option>
<?
                                                foreach($sAuditTypesList as $AuditTypeCode => $sAuditType){
?>
                                              <option value="<?=$AuditTypeCode;?>"<?= (($AuditType == $AuditTypeCode) ? " selected" : "") ?>><?=$sAuditType?></option>
<?
                                                }
?>
					  </select>
					</td>
				    </tr>
                                    
                                    <tr valign="top">
					<td>Group Audit</td>
					<td align="center">:</td>

					<td>
					  <select name="GroupAudit" id="GroupAudit" style="width: 210px;" onchange="getPreviousAudits(this.value); resetSections2();">
                                              <option value="N" <?=($PrevAuditId>0?'':'selected')?>>No</option>
                                              <option value="Y" <?=($PrevAuditId>0?'selected':'')?>>Yes</option>
					  </select>
					</td>
				    </tr>
                                    
                                    <tr id="PreviousAuditBlock" valign="top" style="<?=(($PrevAuditId>0)?'':'display:none;')?>">
					<td>Parent Audit</td>
					<td align="center">:</td>

					<td>
                                            <select name="PreviousAudit" id="PreviousAudit" style="width: 210px;">
<?
                                            if($Unit > 0)
                                                $sUnitSql = " AND unit_id='$Unit' ";

                                            $sPreviousAudits   = getList("tbl_crc_audits", "id", "id", "vendor_id='$VendorId' AND prev_audit_id='0' $sUnitSql");
            
                                            foreach($sPreviousAudits as $iAudit)
                                            {
?>
                                                <option value="<?=$iAudit?>" <?=($PrevAuditId == $iAudit)?'selected':''?>><?="C".str_pad($iAudit, 5, 0, STR_PAD_LEFT)?></option>  
<?
                                            }
?>
                                            </select>
					</td>
				    </tr>
				  
                                  <tr>
					<td width="60">Audit Date</td>
					<td width="20" align="center">:</td>
					<td>
					  <table border="0" cellpadding="0" cellspacing="0" width="116">
						<tr>
						  <td width="82"><input type="text" name="AuditDate" id="AuditDate" value="<?= $AuditDate ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('AuditDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('AuditDate'), 'yyyy-mm-dd', this);" /></td>
						</tr>
					  </table>

					</td>
				  </tr>
                                    <tr id="AuditSectionId" style="<?=($AuditType == 5)?'display:none;':''?>">
				    <td width="60">Audit Section</td>
				    <td width="20" align="center">:</td>

				    <td>
                                        <select name="Section" id="Section" style="width: 210px;" onchange="resetPoints()">
				        <option value=""></option>
<?
                            foreach ($sParentSectionsList as $iSection => $sSection)
                            {
?>
                                <option value="<?= $iSection ?>"<?= (($iSection == $Section) ? " selected" : "") ?>><?= $sSection ?></option>
<?                          }
?>  
                                      </select>
                                    </td>
				  </tr>  
                                    
                                     <tr valign="top" id="SelectQuestionsBlock" style="<?=($AuditType == 5)?'display:none;':''?>">
					<td>Select Questions</td>
					<td align="center">:</td>

					<td>
					  <select name="ddQuestions" id="ddQuestions" style="width: 210px;" onchange="getPoints(document.getElementById('Section').value, this.value, 'Points');">
                                              <option value=""></option>
                                              <option value="A" <?=($ddQuestion == 'A')?'selected':''?>>All Categories/ All Questions</option>
                                              <option value="Q" <?=($ddQuestion == 'Q')?'selected':''?>>Selective Questionare</option>
                                              <option value="S" <?=($ddQuestion == 'S')?'selected':''?>>Selective Section</option>
                                              <option id="toggleOpt" style="<?=($AuditType == 4)?'':'display:none;'?>" value="F" <?=($ddQuestion == 'F')?'selected':''?>>Only Failed in Previous Audit</option>
					  </select>
					</td>
				    </tr>
                                    
                                    <tr style="<?=(in_array($ddQuestion, array('S', 'Q')))?'':'display:none;'?>" id="PointsBlock">
                                    <td>Audit Points</td>
					<td align="center">:</td>

					<td>
                                            <div id="toggleChecks"><input type="checkbox"  onClick="checkAll(this)" checked/> Toggle All<br/></div>
					</td>
				    </tr>
                                    <?
                                    if($Section > 0 && $ddQuestion == 'Q')
                                    {
?>
                                        <tr id="PointsBlock2"> <td>&nbsp</td>
                                        <td colspan="2"><div id="Questions">
<?
                                        $sBrandSections   = getList("tbl_tnc_points", "DISTINCT section_id", "section_id", "FIND_IN_SET('$Brand', brands)");
                                        $sSectionsList    = getList("tbl_tnc_sections", "id", "section", "parent_id='$Section' AND id IN (". implode(",", $sBrandSections).")");
                                        
                                        foreach($sSectionsList as $iSection => $sSection)
                                        {
?>
                                            <h2><?= $sSection ?></h2>
<?
                                            $sCategoriesList  = getList("tbl_tnc_categories", "id", "category", (($iSection > 0) ? "section_id='$iSection'" : ""), "position");
                                            foreach ($sCategoriesList as $iCategory => $sCategory)
                                            {
?>    
                                                <h3><?= $sCategory ?></h3>
<?
                                                $sPointsList = getList("tbl_tnc_points", "id", "point", "category_id=$iCategory AND FIND_IN_SET('$Brand', brands)");

                                                foreach ($sPointsList as $iPoint => $sPoint)
                                                {
?>
                                                    <div style='padding-bottom:1px;'><input type='checkbox' name=Points[] id='<?=$iPoint?>' value='<?=$iPoint?>' <?=(@in_array($iPoint, $Points)?'checked':'')?>/><label for='<?=$iPoint?>'><?=$sPoint?></label></div><br/>
<?
                                                }
                                            }

                                        }
?>
                                            </div></td></tr>        
<?
                                    }
                                    else if($Section > 0 && $ddQuestion == 'S')
                                    {
?>
                                        <tr id="PointsBlock2"> <td>&nbsp</td>
                                            <td colspan="2">
                                                <div id="Questions">
<?
                                            $iAuditSections = explode(",", $AuditSections);
                                            $sBrandSections   = getList("tbl_tnc_points", "DISTINCT section_id", "section_id", "FIND_IN_SET('$Brand', brands)");
                                            $sSectionsList    = getList("tbl_tnc_sections", "id", "section", "parent_id='$Section' AND id IN (". implode(",", $sBrandSections).")");
                                            
                                            foreach($sSectionsList as $iSection => $sSection)
                                            {
                                                echo "<h3><div style='padding-bottom:1px;'><input type='checkbox' name=Sections[] id='".$iSection."' value='".$iSection."' ".(@in_array($iSection, $iAuditSections)?'checked':'')."/><label for='".$iSection."'>".$sSection."</label></div></h3><br/>";
                                            }
?>
                                                </div>
                                            </td>
                                        </tr>        
<?
                                    }
                                    else{
                                    ?>
                                    <tr id="PointsBlock2">
                                        <td>&nbsp</td>
                                        <td colspan="2"><div id="Questions"></div></td>
                                    </tr> 
                                    <?}?>
                                  
 				</table>

				<br />

				<div class="buttonsBar">
				  <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" />
				  <input type="button" value="" class="btnBack" title="Back" onclick="document.location='crc/tnc-schedules.php'" />
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