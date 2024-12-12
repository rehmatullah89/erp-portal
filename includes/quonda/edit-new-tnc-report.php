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

	$sAuditStages = getDbValue ("stages", "tbl_reports", "id = '$ReportId'");
	
	if($sAuditStages != ""){

		$sAuditStagesList    = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')", "position");
	}

  $sSQL = "Select style_id,additional_styles,po_id,additional_pos,colors,sizes FROM tbl_qa_reports WHERE id='$Id'";
  
  $objDb->query($sSQL);
  $Styles   = $objDb->getField(0, 'style_id');
  $additionalStyles   = $objDb->getField(0, 'additional_styles');
  $Pos   = $objDb->getField(0, 'po_id');
  $additionalPos   = $objDb->getField(0, 'additional_pos');
  $colors   = $objDb->getField(0, 'colors');
  $sizes   = $objDb->getField(0, 'sizes');

  $allStyles = $Styles.",".$additionalStyles;

  $allStyles = rtrim($allStyles,",");

  $allPos = $Pos.",".$additionalPos;

  $allPos = rtrim($allPos,",");

  $sStylesList = getList("tbl_styles", "id", "style", "id IN ($allStyles)");

  $sPoList = getList("tbl_po", "id", "order_no", "id IN ($allPos)");

  $sScheduledStyleList = $sStylesList;
  $sScheduledSizeList = getList("tbl_sizes", "id", "size", "id IN ($sizes)");
  $sScheduledColorsList = array();

  $colorArray = explode(",", $colors);

  foreach ($colorArray as $colorSingle) {
      $sScheduledColorsList[$colorSingle] = $colorSingle;
    }  

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
	$sSQL = "SELECT id, name FROM tbl_auditor_groups ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, 0);
		$sValue = $objDb->getField($i, 1);
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
	$sStyles = getList("tbl_styles s, tbl_po_colors pc", "DISTINCT(s.id)", "s.style", "s.id=pc.style_id AND FIND_IN_SET(pc.po_id, '$sSelectedPos')", "s.style");

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
<?
	if ($ReportId != 8 && $ReportId != 54)
	{
?>
				  <tr>
					<td>QA Type<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="AuditType">
						<option value="B">Bulk</option>
						<option value="BG">B-Grade</option>
						<option value="SS">Sales Sample</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.AuditType.value = "<?= $AuditType ?>";
					  -->
					  </script>
					</td>
				  </tr>
<?
	}

	else
	{
?>
                 <input type="hidden" name="AuditType" id="AuditType" value="B" />
<?
	}
?>

				  <tr>
					<td>Inspection Type<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="InspecType">
						<option value="G">GREIGE</option>
						<option value="P">DYED / PRINTED</option>
						<option value="O">OTHER</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.InspecType.value = "<?= $InspectionType ?>";
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


	$sSQL = "SELECT id, size FROM tbl_sizes WHERE id IN (SELECT DISTINCT(size_id) FROM tbl_po_quantities WHERE po_id='$PO'";

	if ($AdditionalPos != "")
		$sSQL .= " OR po_id IN ($AdditionalPos)";

	$sSQL .= ") ORDER BY position";
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
<h2 style="margin-bottom:0px;">Inspection Sections</h2>
<table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
  <tr class="sdRowHeader">
    <td width="25"><b>#</b></td>
    <td><b>Section</b></td>
    <td width="60" align="center"><b>Options</b></td>
  </tr>
  <tr class="sdRowColor">
    <td width="25">1</td>
    <td>Description/Quantity of Product</td>
    <td align="center"><a href="includes/quonda/edit-tnc-section.php?AuditId=<?=$Id?>&Section=1" class="lightview" rel="iframe" title="Description/Quantity of Product for Inspection#: <?= $Id ?> :: :: width: 900, height: 650"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit Description/Quantity of Product" title="Edit Description/Quantity of Product" /></a>&nbsp;</td>
  </tr>   
</table>

<?
////////////////////////////Working Area/////////////////////////////////
?>

<h2 id="WorkmanshipDetails" style="margin-bottom:0px;">Workmanship</h2>
						
  <h3>Defects</h3>
<?
	$iDefects = 0;

	$sSQL = "SELECT * FROM tbl_qa_report_defects WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	$sSQL = "SELECT DISTINCT(type_id), (SELECT type FROM tbl_defect_types WHERE id=tbl_defect_codes.type_id) FROM tbl_defect_codes WHERE report_id='$ReportId' ORDER BY type_id";
	$objDb2->query($sSQL);

	$iCount2 = $objDb2->getCount( );


	if (strtotime($AuditDate) <= strtotime("2015-06-18"))
		$sSQL = "SELECT * FROM tbl_defect_areas ORDER BY area";

  else if($ReportId == 28 || $ReportId == 37 || $ReportId == 38)
    $sSQL = "SELECT * FROM tbl_defect_areas WHERE status='A' AND id IN (593,594,595,596,597,598) ORDER BY area";

  else if(in_array($ReportId, array(41,42)))
		$sSQL = "SELECT id, IF($sAreaQuery IS NULL, area, $sAreaQuery) FROM tbl_defect_areas WHERE status='A' ORDER BY area";
  else
		$sSQL = "SELECT * FROM tbl_defect_areas WHERE status='A' ORDER BY area";

	$objDb4->query($sSQL);

	$iCount4 = $objDb4->getCount( );
?>
				<input type="hidden" id="Count" name="Count" value="<?= $iCount ?>" />
			    <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			      <tr class="sdRowHeader">
					<td width="20" align="center"><b>#</b></td>
					<td ><b>Code - Check Points</b></td>
					<td width="60" align="center"><b>Defects</b></td>
                                        <td width="60" align="center"><b>Lot#</b></td>
					<td width="100" align="center"><b>Area</b></td>
					<td width="80" align="center"><b>Nature</b></td>
					<td width="80" align="center"><b>Style</b></td>
					<td width="80" align="center"><b>Color</b></td>
					<td width="80" align="center"><b>Size</b></td>
					<td width="60" align="center"><b>Sample No.</b></td>
					<td width="40" align="center"><b>Delete</b></td>
			      </tr>
			    </table>

			    <div id="QaDefects">
<?
	$sDefectCode = "";
	$sDefectArea = "";

	for($i = 0; $i < $iCount; $i ++)
	{
		$defectStyle = $objDb->getField($i, 'style_id');
		$defectSize = $objDb->getField($i, 'size_id');
		$defectColor = $objDb->getField($i, 'color');
    $defectSampleNo = $objDb->getField($i, 'sample_no');
		$defectLotNo = $objDb->getField($i, 'lot_no');

		if ($objDb->getField($i, 'nature') > 0)
			$iDefects += $objDb->getField($i, 'defects');
?>

				<div id="DefectRecord<?= $i ?>" class="defectRecords">
				  <div>
				    <input type="hidden" id="DefectId<?= $i ?>" name="DefectId<?= $i ?>" value="<?= $objDb->getField($i, 'id') ?>" class="defectId" />
				    <input type="hidden" name="rowIds[]" value="<?= $i ?>" >

					<table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
					  <tr class="sdRowColor" valign="top">
						<td width="20" align="center" class="serial"><?= ($i + 1) ?></td>

						<td>
						  <select id="Code<?= $i ?>" name="Code<?= $i ?>" class="defectCode" onchange="$('Sms').value='1';">
							<option value=""></option>
<?
		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iTypeId = $objDb2->getField($j, 0);
			$sType   = $objDb2->getField($j, 1);
?>
		        			<optgroup label="<?= $sType ?>">
<?
			$sSQL = "SELECT id, code, defect FROM tbl_defect_codes WHERE report_id='$ReportId' AND type_id='$iTypeId' ORDER BY code";
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );

			for ($k = 0; $k < $iCount3; $k ++)
			{
				$iCodeId = $objDb3->getField($k, 0);
				$sCode   = $objDb3->getField($k, 1);
				$sDefect = $objDb3->getField($k, 2);

?>
		        			  <option value="<?= $iCodeId ?>"><?= $sCode ?> - <?= $sDefect ?></option>
<?
				if ($iCodeId == $objDb->getField($i, 'code_id'))
					$sDefectCode = $sCode;
			}
?>
		        			</optgroup>
<?
		}
?>
						  </select>

						  <script type="text/javascript">
						  <!--
							document.frmData.Code<?= $i ?>.value = "<?= $objDb->getField($i, 'code_id') ?>";
						  -->
						  </script>
						</td>

						<td width="60" align="center"><input type="text" id="Defects<?= $i ?>" name="Defects<?= $i ?>" value="<?= $objDb->getField($i, 'defects') ?>" maxlength="3" size="3" class="textbox defectsCount" onchange="$('Sms').value='1';" /></td>
                                                
                                                <td width="60" align="center">
						  <select id="LotNo<?= $i ?>" name="LotNo<?= $i ?>" class="defectLot" onchange="$('Sms').value='1';" style="width:200px;">
<?

  $sLotsList = getList("tbl_qa_lot_sizes", "id", "id", "audit_id='$Id'"); 

                $LotCount = 1;
		foreach($sLotsList as $iLotId)
		{
?>
		        			<option value="<?= $iLotId ?>" <?= (($defectLotNo == $iLotId)?'selected="selected"':'')?> ><?= $LotCount++ ?></option>
<?
		}
?>
						  </select>

						</td>
                                                  
						<td width="100" align="center">
						  <select id="Area<?= $i ?>" name="Area<?= $i ?>" class="defectArea" onchange="$('Sms').value='1';" style="width:200px;">

<?
		for ($j = 0; $j < $iCount4; $j ++)
		{
			$iAreaId = $objDb4->getField($j, 0);
			$sArea   = $objDb4->getField($j, 1);

			$sAreaId = str_pad($iAreaId, 2, '0', STR_PAD_LEFT);

?>
		        			<option value="<?= $sAreaId ?>"><?= $sArea ?></option>
<?
			if ($iAreaId == $objDb->getField($i, 'area_id'))
				$sDefectArea = $sAreaId;
		}
?>
						  </select>

						  <script type="text/javascript">
						  <!--
							document.frmData.Area<?= $i ?>.value = "<?= str_pad($objDb->getField($i, 'area_id'), 2, '0', STR_PAD_LEFT); ?>";
						  -->
						  </script>
						</td>

						<td width="80" align="center">
						  <select id="Nature<?= $i ?>" name="Nature<?= $i ?>" class="defectNature" onchange="$('Sms').value='1';">
                                                    <option value="2">Critical</option>
                                                    <option value="1">Major</option>
                                                    <option value="0">Minor</option>
						  </select>

						  <script type="text/javascript">
						  <!--
							document.frmData.Nature<?= $i ?>.value = "<?= $objDb->getField($i, 'nature') ?>";
						  -->
						  </script>
						</td>
						<?
							$dbDefectStylesListHTML = getKeyValueListHTML("defectStyle","defectStyles[]",$sScheduledStyleList,true,$defectStyle);
							$dbDefectSizesListHTML = getKeyValueListHTML("defectSize","defectSizes[]",$sScheduledSizeList,true,$defectSize);
							$dbDefectColorListHTML = getListHTML("defectColor","defectColors[]",$sScheduledColorsList,true,$defectColor);
						?>
						<td width='80' align='center'><?=$dbDefectStylesListHTML?></td>
						<td width='80' align='center'><?=$dbDefectColorListHTML?></td>
						<td width='80' align='center'><?=$dbDefectSizesListHTML?></td>
						<td width='60' align='center'><input type="text" style="width: 50px;" name="defectSampleNo[]" value="<?= $defectSampleNo?>"></td>
						<td width="40" align="center"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" style="cursor:pointer;" class="deleteDefect" rel="<?= $i ?>" /></td>
					  </tr>

					  <tr>
					    <td align="center"><img src="images/icons/pictures.gif" width="16" height="16" alt="Defect Picture" title="Defect Picture" /></td>

					    <td colspan="5">
					      <input type="file" id="Picture<?= $i ?>" name="Picture<?= $i ?>" value="" class="textbox defectPicture" size="30" />
<?
		$sPicture = $objDb->getField($i, 'picture');
		
		if(!empty($sPicture))
		{
?>
						  <span>&bull; (<a href="<?= $sPicsDir ?><?= $sPicture ?>" class="lightview"><?= $sPicture ?></a>)&nbsp;</span>
						  <input type="hidden" name="PrevPicture<?= $i ?>" value="<?= $sPicture ?>">
<?
		}
?>
					    </td>
					  </tr>
					</table>
				  </div>
				</div>
<?
	}
?>
				</div>
<?
                            $iWorkmanshipResult = getDbValue("workmanship_result", "tbl_qa_reports", "id='$Id'");
?>
                                <div>
                                    <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                                        <tr class="sdRowHeader">
                                                  <td width="150" align="center"><b>Workmanship Result : </b></td>
                                                  <td>                                   
                                                      <select name="WorkmanshipResult" style="min-width: 150px;">
                                                          <option value="">N/A</option>
                                                          <option value="P" <?=($iWorkmanshipResult == 'P'?'selected':'')?>>Pass</option>
                                                          <option value="F" <?=($iWorkmanshipResult == 'F'?'selected':'')?>>Fail</option>
                                                      </select>
                                                  </td>
                                        </tr>
                                    </table>
                                </div>
				<div class="qaButtons">
				  <input type="button" value="" class="btnAdd" title="Add Defect" onclick="addWorkmanShipDefect( );" />
				</div>
<?
////////////////////////////Working Area/////////////////////////////////
?>

				<br />
				<h2>Status & Comments</h2>

				<table border="0" cellpadding="3" cellspacing="0">
				  <tr valign="top">
					<td>QA Comments</td>
					<td align="center">:</td>
                                        <td><textarea name="Comments" class="textarea" cols="50" rows="8"><?= $Comments ?></textarea></td>
				  </tr>
				</table>

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

$defectStylesListHTML = getKeyValueListHTML("defectStyle","defectStyles[]",$sScheduledStyleList,true);
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
</script>