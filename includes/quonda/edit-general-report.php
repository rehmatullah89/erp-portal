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

	$sAuditStages   = getDbValue("stages", "tbl_reports", "id = '$ReportId'");
        $sAuditSections = getDbValue("sections", "tbl_reports", "id = '$ReportId'");
        $sSectionsList  = getList("tbl_qa_sections", "id", "section", "id IN ($sAuditSections)", "position");

	if($sAuditStages != "")
            $sAuditStagesList    = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')", "position");

        $sStylesList            = getList("tbl_styles", "id", "style", "id IN ($sSelectedStyles)");
        $sPoList                = getList("tbl_po", "id", "order_no", "id IN ($sSelectedPos)");
        $sScheduledSizeList     = getList("tbl_sizes", "id", "size", "id IN ($Sizes)");        
        $sAuditGroupList        = getList("tbl_auditor_groups", "id", "name", "id != '0'", "name");
        $sStyles                = getList("tbl_styles s, tbl_po_colors pc", "DISTINCT(s.id)", "s.style", "s.id=pc.style_id AND FIND_IN_SET(pc.po_id, '$sSelectedPos')", "s.style");
        $iColors                = explode(",", $Colors);        
        
        foreach($iColors as $sColor)
            $sScheduledColorsList["{$sColor}"] = $sColor;
?>
				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="140"><b>Audit Code</b></td>
					<td width="20" align="center">:</td>
					<td><b><?= $AuditCode ?></b></td>
				  </tr>

				  <tr>
					<td>Vendor</td>
					<td align="center">:</td>
					<td><?= $sVendor ?></td>
				  </tr>

				  <tr>
					<td>Auditor</td>
					<td align="center">:</td>
					<td><?= $sAuditor ?></td>
				  </tr>

				  <tr style="display: none;">
				    <td>Group</td>
				    <td align="center">:</td>

				    <td>
					  <select name="Group">
						<option value=""></option>
<?

                                        foreach($sAuditGroupList as $sKey => $sValue)
                                        {		
?>
                                            <option value="<?= $sKey ?>"<?= (($sKey == $Group) ? ' selected' : '') ?>><?= $sValue ?></option>
<?
                                        }
?>
					  </select>
					</td>
				  </tr>

				  <tr valign="top">
					<td>PO<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="PO" id="PO" value="" class="textbox" size="30" maxlength="200" /></td>
				  </tr>

				  <tr valign="top" style="display: none;">
					<td>Style<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Style" id="Style">
						<option value=""></option>
<?
                                        foreach ($sStyles as $sKey => $sValue)
                                        {
?>
                                            <option value="<?= $sKey ?>"<?= (($sKey == $Style) ? ' selected' : '') ?>><?= $sValue ?></option>
<?
                                        }
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Audit Stage<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="AuditStage" onchange="$('Sms').value='1';">
						<option value=""></option>
<?
	foreach ($sAuditStagesList as $sKey => $sValue)
	{
		if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE && $sValue == "Final")
			$sValue = "Firewall";

		if ( (@strpos($_SESSION["Email"], "pelknit.com") !== FALSE || @strpos($_SESSION["Email"], "fencepostproductions.com") !== FALSE) &&
			 !@in_array($sKey, array("B", "C", "O", "F")) )
			continue;
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $AuditStage) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Audit Status</td>
					<td align="center">:</td>

					<td>
					  <select name="AuditStatus">
						<option value=""></option>
						<option value="1st">1st</option>
						<option value="2nd">2nd</option>
						<option value="3rd">3rd</option>
						<option value="4th">4th</option>
						<option value="5th">5th</option>
						<option value="6th">6th</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.AuditStatus.value = "<?= $AuditStatus ?>";
					  -->
					  </script>
					</td>
				  </tr>

				  <tr>
					<td>Audit Result<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="AuditResult" onchange="$('Sms').value='1';">
						<option value=""></option>
						<option value="P">Pass</option>
						<option value="F">Fail</option>
						<option value="H">Hold</option>
						<option value="R">Re-Inspection</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.AuditResult.value = "<?= $AuditResult ?>";
					  -->
					  </script>
					</td>
				  </tr>

				  <tr style="display: none;">
					<td>Maker</td>
					<td align="center">:</td>
					<td><input type="text" name="Maker" value="<?= $Maker ?>" size="30" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Colors</td>
					<td align="center">:</td>
					<td><input type="text" name="Colors" value="<?= $Colors ?>" size="30" class="textbox" /></td>
				  </tr>
				</table>

				<br />
				<h2 id="SizeRequirements">Size / Ranges</h2>

				<div id="SizesList">
				  <div style="padding:5px;">
				    <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
	$iSizes = @explode(",", $Sizes);


	$sSQL = "SELECT id, size FROM tbl_sizes WHERE id IN (SELECT DISTINCT(size_id) FROM tbl_po_quantities WHERE po_id IN ($sSelectedPos)) ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount;)
	{
?>
                <tr>
<?
		for ($j = 0; $j < 10; $j ++)
		{
			if ($i < $iCount)
			{
				$sKey   = $objDb->getField($i, 0);
				$sValue = $objDb->getField($i, 1);
?>
					    <td width="25"><input type="checkbox" name="Sizes[]" value="<?= $sKey ?>" <?= ((@in_array($sKey, $iSizes)) ? "checked" : "") ?> /></td>
					    <td><?= $sValue ?></td>
<?
				$i ++;
			}

			else
			{
?>
					    <td></td>
					    <td></td>
<?
			}
		}
?>
					  </tr>
<?
	}
?>
				    </table>
				  </div>
				</div>


<br />
<?
        $sReportItems   = getDbValue("items", "tbl_reports", "id='$ReportId'");  
        $sCheckResults  = getList("tbl_qa_checklist_results", "item_id", "check_value", "audit_id='$Id'");
        $sTextResults   = getList("tbl_qa_checklist_results", "item_id", "text_value", "audit_id='$Id'");
        
        $sSQL = "SELECT * FROM tbl_qa_checklist WHERE id IN ($sReportItems) ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

        if($iCount > 0)
        {
            print "<h2 style='margin-bottom:0px;'>Inspection Checklist</h2><br/>";
?>
            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" style="margin-top:-10px;">
                <tr class="sdRowHeader">
                  <td width="25"><b>#</b></td>
                  <td><b>Item</b></td>
                  <td width="250" align="center"><b>Options</b></td>
                </tr>
<?
            for ($i = 0; $i < $iCount; $i++)
            {
                $iItemId    = $objDb->getField($i, "id");
                $sItem      = $objDb->getField($i, "item");
                $sFieldType = $objDb->getField($i, "field_type");
                $sMandatory = $objDb->getField($i, "mandatory");
?>
                <tr>
                    <td><?=$i+1?></td>
                    <td><?=$sItem?></td>
                    <td>
<?
                            if($sFieldType == 'YN' || $sFieldType == 'CB')
                            {
?>
                        <select name="CheckFieldC[<?=$iItemId?>]" <?=($sMandatory == 'Y'?'required':'')?>>
<?
                        if($sFieldType == 'YN')
                        {
?>
                            <option value="A" <?=(in_array($sCheckResults[$iItemId], array('NA','NA'))?'selected':'')?>>N/A</option>
<?
                        }
?>
                            <option value="Y" <?=($sCheckResults[$iItemId] == 'Y'?'selected':'')?>>Yes</option>
                            <option value="N" <?=($sCheckResults[$iItemId] == 'N'?'selected':'')?>>No</option>
                        </select>
<?
                            }
                            else if($sFieldType == 'CC')
                            {
?>
                        <span>
                            <select name="CheckFieldC[<?=$iItemId?>]" <?=($sMandatory == 'Y'?'required':'')?>>
                                <option value="A" <?=($sCheckResults[$iItemId] == 'NA'?'selected':'')?>>N/A</option>
                                <option value="Y" <?=($sCheckResults[$iItemId] == 'Y'?'selected':'')?>>Yes</option>
                                <option value="N" <?=($sCheckResults[$iItemId] == 'N'?'selected':'')?>>No</option>
                            </select>
                            &nbsp;
                            <input type="text" value="<?=$sTextResults[$iItemId]?>" name="CheckFieldT[<?=$iItemId?>]" <?=($sMandatory == 'Y'?'required':'')?> style="width:175px;"/>
                        </span>
<?
                            }
                            else
                            {
?>
                              <input type="<?=($sFieldType == 'NF')?'number':'text'?>" value="<?=$sTextResults[$iItemId]?>" name="CheckFieldT[<?=$iItemId?>]" <?=($sMandatory == 'Y'?'required':'')?> style="width:95%;"/>
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
?>
                <br />
                        <h2 style="margin-bottom:0px;">Inspection Sections</h2><br/>
                        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" style="margin-top:-10px;">
                                <tr class="sdRowHeader">
                                  <td width="25"><b>#</b></td>
                                  <td><b>Section</b></td>
                                  <td width="60" align="center"><b>Options</b></td>
                                </tr>
<?
                                    $iIndex = 1;
                                    foreach($sSectionsList as $iSection => $sSection)
                                    {
                                           if($iSection == 1)
                                           {
?>
                                            <tr class="<?=($iIndex%2==0)?'oddRow':'evenRow'?>">
                                                <td width="25"><?=$iIndex++?></td>
                                                  <td><?=$sSection?></td>
                                                    <td align="center"><a href="includes/quonda/edit-report-section.php?AuditId=<?=$Id?>&Section=<?=$iSection?>" class="lightview" rel="iframe" title="Description/Quantity of Product for Inspection#: <?= $Id ?> :: :: width: 900, height: 650"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit Description/Quantity of Product" title="Edit Description/Quantity of Product" /></a>&nbsp;</td>
                                            </tr>
<?
                                           }                                                                                      
                                           else if($iSection == 9)
                                           {
?>
                                            <tr class="<?=($iIndex%2==0)?'oddRow':'evenRow'?>">
                                                <td><?=$iIndex++?></td>
                                                <td><?=$sSection?></td>
                                                <td align="center"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit <?=$sSection?>" title="Edit <?=$sSection?>" style="cursor: pointer;" onclick="toggleDiv('WorkmanshipTable');"/>&nbsp;</td>
                                            </tr>

                                            <tr id="WorkmanshipTable" style="display:none;">
                                              <td colspan="3">
                                              <h3>Defects</h3>
<?
                                                /*if($ReportId == 54)
                                                    include("general-sections/edit-tnc-workmanship.php");
                                                else*/
                                                    include("general-sections/edit-other-workmanship.php");
?>
                                    </td>
                                </tr> 
<?
                                           }
                                           else if($iSection == 10)
                                           {
?>
                                            <tr class="<?=($iIndex%2==0)?'oddRow':'evenRow'?>">
                                                <td><?=$iIndex++?></td>
                                                <td><?=$sSection?></td>
                                                <td align="center"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit <?=$sSection?>" title="Edit <?=$sSection?>" style="cursor: pointer;" onclick="toggleDiv('PackingLabelingTable');"/>&nbsp;</td>
                                            </tr>

                                            <tr id="PackingLabelingTable" style="display:none;">
                                              <td colspan="3">
<?
                                                include("general-sections/edit-packaging-labeling.php");
?>
                                            </td>
                                </tr> 
<?
                                           }
                                           else if($iSection == 12)
                                           {
?>
                                            <tr class="<?=($iIndex%2==0)?'oddRow':'evenRow'?>">
                                                <td><?=$iIndex++?></td>
                                                <td><?=$sSection?></td>
                                                <td align="center"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit <?=$sSection?>" title="Edit <?=$sSection?>" style="cursor: pointer;" onclick="toggleDiv('MeasurementTable');"/>&nbsp;</td>
                                            </tr>

                                            <tr id="MeasurementTable" style="display:none;">
                                              <td colspan="3">
											  
												<table id="MeasurementSpecsTable" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
												  <tr class="sdRowHeader">
													<td width="40" align="center"><b>#</b></td>
													<td width="200"><b>Style</b></td>
													<td width="150"><b>Size</b></td>
													<td width="120" align="center"><b>Sample No</b></td>
													<td width="94" align="center"><b>Options</b></td>
												  </tr>
<?
												$sSQL = "SELECT * FROM tbl_qa_report_samples WHERE audit_id='$Id' ORDER BY style_id, size_id, sample_no";
												$objDb->query($sSQL);
												
												$iCount = $objDb->getCount( );
												
												for ($i = 0; $i < $iCount; $i ++)
												{
													$iSample   = $objDb->getField($i, "id");
													$iStyle    = $objDb->getField($i, "style_id");
													$iSize     = $objDb->getField($i, "size_id");
													$sColor    = $objDb->getField($i, "color");
													$sNature   = $objDb->getField($i, "nature");
													$sResult   = $objDb->getField($i, "result");
													$iSampleNo = $objDb->getField($i, "sample_no");
													

													$sStyle = $sStylesList[$iStyle];
//													$sSize  = $sScheduledSizeList[$iSize];
													
													
													if ($iStyle == 0)
														$sStyle = $sStylesList[$sSelectedStyles];
													
													$sSize = getDbValue("size", "tbl_sampling_sizes", "id='$iSize'");
?>
                                                                                                    <tr bgcolor="<?= ((($i % 2) == 0) ? "#f0f0f0" : "#e6e6e6") ?>">
                                                                                                          <td align="center"><?= ($i + 1) ?></td>
                                                                                                          <td><?= $sStyle ?></td>
                                                                                                          <td><?= $sSize ?></td>
                                                                                                          <td align="center"><?= $iSampleNo ?></td>
                                                                                                          <td align="center">
                                                                                                              <a href="includes/quonda/general-sections/edit-sample-measurements.php?AuditId=<?= $Id ?>&SampleId=<?= $iSample ?>" class="lightview" rel="iframe" title="Sample Measurements :: :: width: 900, height: 650"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit" title="Edit" /></a>
                                                                                                                                                                                                                  <?
                                                                                                          if ($sUserRights['Delete'] == "Y")
                                                                                                          {
  ?>
                                                                                                              <a href="includes/quonda/delete-measurement-specs.php?QaSampleId=<?=$iSample?>&AuditId=<?=$Id?>" title="Delete Measurement Specs" onclick="return confirm('Are your sure, you want to delete sample specs for specified size and style?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" style="cursor:pointer;" /></a>&nbsp;
  <?
                                                                                                          }
  ?>
                                                                                                          </td>
                                                                                                    </tr>
<?
												}
?>
												</table>											  
												
												<br />
                                                                                                <div align="right" class="right">
                                                                                                    <a href="includes/quonda/add-measurement-specs.php?Sizes=<?= $Sizes ?>&AuditId=<?=$Id?>&Styles=<?=$sSelectedStyles?>" class="lightview" style="font: bold 11px Arial; text-decoration: none; background-color: #EEEEEE; color: #333333; padding: 2px 6px 2px 6px; border-top: 1px solid #CCCCCC; border-right: 1px solid #333333; border-bottom: 1px solid #333333; border-left: 1px solid #CCCCCC;" rel="iframe" title="Audit Code: <?= $AuditCode ?> :: :: width: 450, height: 360">  Add Measurement Sample  </a>
                                                                                                </div>
                     								  
<?
//                                                 @include("general-sections/edit-measurement-conformity.php"); 
?>
                                              </td>
                                            </tr> 
<?
                                           }                                           
                                           else
                                           {
?>
                                            <tr class="<?=($iIndex%2==0)?'oddRow':'evenRow'?>">
                                                <td width="25"><?=$iIndex++?></td>
                                                  <td><?=$sSection?></td>
                                                    <td align="center"><a href="includes/quonda/edit-report-section.php?AuditId=<?=$Id?>&Section=<?=$iSection?>" class="lightview" rel="iframe" title="<?=$sSection?> for Inspection Code#: <?= $AuditCode ?> :: :: width: 900, height: 650"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit <?=$sSection?>" title="Edit <?=$sSection?>" /></a>&nbsp;</td>
                                            </tr>
<?
                                           }
                                           
                                }
?>
                        </table>

<?
////////////////////////////Working Area/////////////////////////////////
?>

				<br />
<?
                                    if($ReportId == 54)
                                    {
?>
				<h2>Status & Comments</h2>

				<table border="0" cellpadding="3" cellspacing="0">
				  <tr valign="top">
					<td>QA Comments</td>
					<td align="center">:</td>
                                        <td><textarea name="Comments" class="textarea" cols="50" rows="8"><?= $Comments ?></textarea></td>
				  </tr>
				</table>
<?
                                    }
                                    else
                                    {
                                        include("general-sections/general-information.php");
                                    }
?>

<?
function getMultipleListHTML($id,$name,$listInArray=array(),$simpleArray=false,$selectedValue=array(),$sFunction="")
{

		$onChangeString = "";

		if($sFunction != ""){
			$onChangeString = 'onchange="'.$sFunction.'"';
		}

    $listHTML = '<select required="" name="'.$name.'" id="'.$id.'" style="width:150px;" multiple '.$onChangeString.' >';
                                                                        
     foreach($listInArray as $key => $value)       
     {

        $selected = "";

        if($simpleArray){

            if(in_array($value, $selectedValue))
                $selected = 'selected';

            $listHTML .=   '  <option value="'.$value.'" '.$selected.'>'.$value.'</option>';

        } else {

            if(in_array($key, $selectedValue))
                $selected = 'selected';
            
            $listHTML .=   '  <option value="'.$key.'" '.$selected.'>'.$value.'</option>';
        }

     }   

    $listHTML .=   '</select>';

    return $listHTML;
}

function getListHTML($id,$name,$listInArray=array(),$empty=false,$selectedValue="", $sFunction="")
{

		$onChangeString = "";

		$selected = "";

		if($sFunction != ""){
			$onChangeString = 'onchange="'.$sFunction.'"';
		}

    $listHTML = '<select required="" name="'.$name.'" id="'.$id.'" style="width:150px;" '.$onChangeString.' >';

    if($empty){

      $listHTML .=   '<option value=""> </option>';
    }

   foreach($listInArray as $key => $value)       
   {

   	$selected = "";

    if($key == $selectedValue)
        $selected = 'selected';

		$listHTML .=   '  <option value="'.$key.'" '.$selected.'>'.$value.'</option>';  

   }   

  $listHTML .=   '</select>';

  return $listHTML;
}

function getKeyValueListHTML($id,$name,$listInArray,$empty=false,$selectedValue=""){

	$listHTML = '<select required="" name="'.$name.'" id="'.$id.'" style="width:150px;">';

		if($empty){

			$listHTML .=   '<option value=""> </option>';
		}

	 foreach($listInArray as $key => $value)       
	 {

	 	$selected = "";


		 	if($key == $selectedValue)
		 		$selected = 'selected';
			
			$listHTML .=   '  <option value="'.$key.'" '.$selected.'>'.$value.'</option>'; 

}
  $listHTML .=   '</select>';

  return $listHTML;

}

$defectStylesListHTML = getKeyValueListHTML("defectStyle","defectStyles[]",$sStylesList,true);
$defectSizesListHTML = getKeyValueListHTML("defectSize","defectSizes[]",$sScheduledSizeList,true);
$defectColorListHTML = getListHTML("defectColor","defectColors[]",$sScheduledColorsList,true);

?>

<script>

var auditId = "<?=$Id?>";
var auditDate = "<?=$AuditDate?>";
var reportId = "<?=$ReportId?>";
var defectStylesListHTML = '<?=$defectStylesListHTML?>';
var defectSizesListHTML = '<?=$defectSizesListHTML?>';
var defectColorListHTML = '<?=$defectColorListHTML?>';

function addTnCDefect( )
{
	var iCount = parseInt($('Count').value);

	var sHtml  = "				<div id=\"DefectRecord" + iCount + "\" class=\"defectRecords\" style=\"display:none;\">";
        sHtml += "				  <div>";
        sHtml += "				    <input type=\"hidden\" id=\"DefectId" + iCount + "\" name=\"DefectId" + iCount + "\" value=\"\" class=\"defectId\" />";

        sHtml += "					<table border=\"1\" bordercolor=\"#ffffff\" cellpadding=\"5\" cellspacing=\"0\" width=\"100%\">";
        sHtml += "					  <tr class=\"sdRowColor\" valign=\"top\">";
        sHtml += "						<td width=\"<?= (($ReportId == 26 || $ReportId == 30) ? 20 : 50) ?>\" align=\"center\">" + (iCount + 1) + "</td>";

        sHtml += "                              <td width=\"50\" align=\"center\"><input type=\"text\" id=\"LotNo" + iCount + "\" name=\"LotNo" + iCount + "\" value='' maxlength='3' size='3' class='textbox defectsCount' onchange=\"$('Sms').value='1';\" /></td>";
	sHtml += "				<td width=\"50\" align=\"center\"><input type=\"text\" id=\"RollNo" + iCount + "\"  name=\"RollNo" + iCount + "\" value='' maxlength='3' size='3' class='textbox defectsCount' onchange=\"$('Sms').value='1';\" /></td>";
        sHtml += "                              <td width=\"50\" align=\"center\"><input type=\"text\" id=\"Width" + iCount + "\"  name=\"Width" + iCount + "\" value='' maxlength='3' size='3' class='textbox defectsCount' onchange=\"$('Sms').value='1';\" /></td>";
        sHtml += "                              <td width=\"50\" align=\"center\"><input type=\"text\" id=\"TicketMeters" + iCount + "\" name=\"TicketMeters" + iCount + "\" value='' maxlength='3' size='3' class='textbox defectsCount' onchange=\"$('Sms').value='1';\" /></td>";
        sHtml += "                              <td width=\"50\" align=\"center\"><input type=\"text\" id=\"ActualMeters" + iCount + "\" name=\"ActualMeters" + iCount + "\" value='' maxlength='3' size='3' class='textbox defectsCount' onchange=\"$('Sms').value='1';\" /></td>";
        sHtml += "                              <td width=\"50\" align=\"center\"><input type=\"text\" id=\"Holes" + iCount + "\" name=\"Holes" + iCount + "\" value='' maxlength='3' size='3' class='textbox defectsCount' onchange=\"$('Sms').value='1';\" /></td>";
        sHtml += "                              <td width=\"50\" align=\"center\"><input type=\"text\" id=\"Slubs" + iCount + "\" name=\"Slubs" + iCount + "\" value='' maxlength='3' size='3' class='textbox defectsCount' onchange=\"$('Sms').value='1';\" /></td>";
        sHtml += "                              <td width=\"50\" align=\"center\"><input type=\"text\" id=\"Stains" + iCount + "\" name=\"Stains" + iCount + "\" value='' maxlength='3' size='3' class='textbox defectsCount' onchange=\"$('Sms').value='1';\" /></td>";
        sHtml += "                              <td width=\"50\" align=\"center\"><input type=\"text\" id=\"Fly" + iCount + "\" name=\"Fly" + iCount + "\" value='' maxlength='3' size='3' class='textbox defectsCount' onchange=\"$('Sms').value='1';\" /></td>";
        sHtml += "                              <td width=\"50\" align=\"center\"><input type=\"text\" id=\"Other" + iCount + "\" name=\"Other" + iCount + "\" value='' maxlength='3' size='3' class='textbox defectsCount' onchange=\"$('Sms').value='1';\" /></td>";

        sHtml += "                              <td width=\"50\" align=\"center\"><img src=\"images/icons/delete.gif\" width=\"16\" height=\"16\" alt=\"Delete\" title=\"Delete\" style=\"cursor:pointer;\" class=\"deleteDefect\" rel=\"" + iCount + "\" /></td>";

        sHtml += "					  </tr><tr>";
        sHtml += "						<td align=\"center\"><img src=\"images/icons/pictures.gif\" width=\"16\" height=\"16\" alt=\"Defect Picture\" title=\"Defect Picture\" /></td>";
        sHtml += "						<td colspan=\"<?= $iColumns ?>\"><input type=\"file\" id=\"Picture" + iCount + "\" name=\"Picture" + iCount + "\" value=\"\" size=\"30\" class=\"textbox defectPicture\" /></td>";
        sHtml += "					  </tr>";
        sHtml += "					</table>";
        sHtml += "				  </div>";
        sHtml += "				</div>";


		new Insertion.Bottom('QaDefects', sHtml);

		Effect.SlideDown('DefectRecord' + iCount);

		$('Count').value = (iCount + 1);
}

function addWorkmanShipDefect(){

	var iCount = parseInt($('Count').value);

  jQuery.post("ajax/quonda/get-workmanship-defect-html.php",
    { iCount:iCount,reportId:reportId,auditId:auditId,auditDate:auditDate },

    function (sHtml)
    {

    	new Insertion.Bottom('QaDefects', sHtml);

    	Effect.SlideDown('DefectRecord' + iCount);

    	$('Count').value = (iCount + 1);
    });


}

function toggleDiv(ToggleDiv)
{
    if(ToggleDiv == "WorkmanshipTable")
    {
        jQuery("#MeasurementTable").hide();
        jQuery("#PackingLabelingTable").hide();
    }
    else if(ToggleDiv == "MeasurementTable")
    {
        jQuery("#WorkmanshipTable").hide();
        jQuery("#PackingLabelingTable").hide();
    }
    else if(ToggleDiv == "PackingLabelingTable")
    {
        jQuery("#WorkmanshipTable").hide();
        jQuery("#MeasurementTable").hide();
    }

    jQuery("#"+ToggleDiv).toggle( "slow", function() {
        // Animation complete.
    });
}

var i = parseInt(jQuery("#PCountRows").val())+parseInt(1);
var j = parseInt(jQuery("#LCountRows").val())+parseInt(1);

function addPLDefect(PL)
{
    if(PL == 'P')
        inc = i;
    else
        inc = j;

    var table = document.getElementById(PL+"DefectsTable");
    var rowCount = table.rows.length;
    var row = table.insertRow(rowCount);
    row.id = PL+"RowNo"+inc;
    var cell1  = row.insertCell(0);
    var cell2  = row.insertCell(1);
    var cell3  = row.insertCell(2);
    var cell4  = row.insertCell(3);
    var cell5  = row.insertCell(4);

    cell1.innerHTML = "<b>"+inc+"</b><input type='hidden' name='"+PL+"DefectRows[]' value='0'>";
    cell1.style = 'text-align:center;';
    cell2.innerHTML = "<select style='text-align:center;' name='"+PL+"Defect[]' required>"+document.getElementById(PL+"Defect").innerHTML+"</select>";
    cell3.innerHTML = "<input type='text' name='"+PL+"Samples[]' size='5' value='' required=''>";
    cell3.style = 'text-align:center;';
    cell4.innerHTML = "<input style='text-align:center;' type='file' class='textbox' name='"+PL+"Images[]' value=''  style='width:95%;'/><input type='hidden' name='"+PL+"PrevPicture[]' value=''>";
    cell4.style = 'text-align:center;';
    cell5.innerHTML = "<img style='text-align:center;' src='images/icons/delete.gif' width='16' height='16' alt='Delete' title='Delete' style='cursor:pointer;' onclick=\"DeletePLDefect("+inc+", 0, '"+PL+"');\" />";
    cell5.style = 'text-align:center;';

    inc++;        
    document.getElementById(PL+"CountRows").value = inc;
}
    
function DeletePLDefect(Num, Id, PL) 
{
    if(PL == 'P')
        inc = i;
    else
        inc = j;

    var result = confirm("Are you sure, you want to delete this record permanenetly?");

    if (result) 
    {
        var table = document.getElementById(PL+"DefectsTable");
        var rowCount = table.rows.length;

        var element = document.getElementById(PL+"RowNo"+Num);
        element.parentNode.removeChild(element);

        if(rowCount == (parseInt(Num)-parseInt(1)))
        {
           inc--;
           document.getElementById("CountRows").value = inc;        
        }

        if(Id > 0)
        {
            jQuery.post("ajax/quonda/delete-packaging-defect.php",
                { DefectId:Id , Type:PL},

                function (sResponse)
                {
                        if (sResponse == 'SUCCESS')
                        {
                                alert("Record Deleted Successfully!");
                        }
                },

            "text");
        }
    }   
} 
</script>