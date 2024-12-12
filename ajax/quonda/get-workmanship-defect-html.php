<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2       = new Database( );

	$Id = IO::intValue("auditId");
	$iCountRow = IO::intValue("iCount");
  $ReportId = IO::intValue("reportId");
	$AuditDate = IO::intValue("auditDate");

	$number = $iCountRow+1;

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

	$defectStylesListHTML = getKeyValueListHTML("defectStyle","defectStyles[]",$sScheduledStyleList,true);
	$defectSizesListHTML = getKeyValueListHTML("defectSize","defectSizes[]",$sScheduledSizeList,true);
	$defectColorListHTML = getListHTML("defectColor","defectColors[]",$sScheduledColorsList,true,true);

	$HTML = '<div id="DefectRecord'.$iCountRow.'" class="defectRecords" style="overflow: visible;">
  <div style="">
  <input type="hidden" id="DefectId'.$iCountRow.'" name="DefectId'.$iCountRow.'" value="" class="defectId">
  <input type="hidden" name="rowIds[]" value="'.$iCountRow.'" >         
  <table width="100%" cellspacing="0" cellpadding="5" bordercolor="#ffffff" border="1">           
    <tr class="sdRowColor" valign="top">           
      <td width="20" align="center">'.$number.'</td>';
  
  if ($ReportId == 6) {

  	$HTML .= '<td width="80" align="center">
							  <select id="Roll'.$iCountRow.'" name="Roll'.$iCountRow.'" class="defectRoll" onchange="$(\'Sms\').value=\'1\';">
							  <option value="></option>
							  <option value="1">01</option>
							  <option value="2">02</option>
							  <option value="3">03</option>
							  <option value="4">04</option>
							  <option value="5">05</option>
							  </select>
							</td>

							<td width="80" align="center">
							  <select id="Panel'.$iCountRow.'" name="Panel'.$iCountRow.'" class="defectPanel" onchange="$(\'Sms\').value=\'1\';">
							  <option value="></option>
							  <option value="1">01</option>
							  <option value="2">02</option>
							  <option value="3">03</option>
							  <option value="4">04</option>
							  <option value="5">05</option>
							  </select>
							</td>';
  }

	$HTML .= '<td>
      <select id="Code'.$iCountRow.'" name="Code'.$iCountRow.'" class="defectCode" required="" onchange="$(\'Sms\').value=\'1\';">';

        $sLanguage      = getDbValue("language", "tbl_users", "id='{$_SESSION['UserId']}'");
        $sDefectQuery   = ($sLanguage == 'en'?'defect':"defect_".$sLanguage);
        $sTypeQuery     = ($sLanguage == 'en'?'type':"type_".$sLanguage);
        $sAreaQuery     = ($sLanguage == 'en'?'area':"area_".$sLanguage);
           
        if (in_array($ReportId, array(41,42)))
            $sSQL = "SELECT DISTINCT(type_id), (SELECT IF($sTypeQuery IS NULL, type, $sTypeQuery) FROM tbl_defect_types WHERE id=tbl_defect_codes.type_id) FROM tbl_defect_codes WHERE report_id='$ReportId' ORDER BY type_id";
        else
            $sSQL = "SELECT DISTINCT(type_id), (SELECT type FROM tbl_defect_types WHERE id=tbl_defect_codes.type_id) FROM tbl_defect_codes WHERE report_id='$ReportId' ORDER BY type_id";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iTypeId = $objDb->getField($i, 0);
		$sType   = $objDb->getField($i, 1);

		$HTML .= '<optgroup label="'.$sType.'">';

		if ($ReportId == 7)
			$sSQL = "SELECT id, buyer_code, defect FROM tbl_defect_codes WHERE report_id='$ReportId' AND type_id='$iTypeId' ORDER BY code";

                else if (in_array($ReportId, array(41,42)))
                    $sSQL = "SELECT id, code, IF($sDefectQuery IS NULL, defect, $sDefectQuery) FROM tbl_defect_codes WHERE report_id='$ReportId' AND type_id='$iTypeId' ORDER BY code";

		else
			$sSQL = "SELECT id, code, defect FROM tbl_defect_codes WHERE report_id='$ReportId' AND type_id='$iTypeId' ORDER BY code";


		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iCodeId = $objDb2->getField($j, 0);
			$sCode   = $objDb2->getField($j, 1);
			$sDefect = $objDb2->getField($j, 2);

			$HTML .= '<option value="'.$iCodeId.'">'.$sCode.' - '.addslashes($sDefect).'</option>';			
		}

		$HTML .= '</optgroup>';		
	}

$HTML .= '</select>
      </td>';
	if ($ReportId == 6) {

$HTML .= '<td width="80" align="center">
					  <select id="Grade'.$iCountRow.'" name="Grade'.$iCountRow.'" class="defectGrade" onchange="$(\'Sms\').value=\'1\';">
					  <option value="></option>
					  <option value="1">1</option>
					  <option value="2">2</option>
					  <option value="3">3</option>
					  <option value="4">4</option>
					  </select>
					</td>';		

	}      
$HTML .= '<td width="60" align="center">
      <input type="text" id="Defects'.$iCountRow.'" name="Defects'.$iCountRow.'" value="1" maxlength="3" size="3" class="textbox defectsCount" required="" onchange="$(\'Sms\').value=\'1\';">
      </td>';

if (@in_array($ReportId, array(28,37,38))) {

	$TotalGmts = getDbValue("total_gmts", "tbl_qa_reports", "id='$Id'");	

	$HTML .= '<td width="100" align="center">
							<input type="text" id="SampleNo'.$iCountRow.'" name="SampleNo'.$iCountRow.'" value=" maxlength="3" size="'.(($ReportId == 6) ? 5 : 3).'" class="textbox sampleNos" onblur="getMaxAllowed('.$iCount.','.$TotalGmts.');" onchange="$(\'Sms\').value=\'1\';" />
						</td>';
}

$HTML .= '<td width="60" align="center">
      <select id="LotNo'.$iCountRow.'" name="LotNo'.$iCountRow.'" class="defectLot" onchange="$(\'Sms\').value=\'1\';" style="width:200px;">';

      	$sLotsList = getList("tbl_qa_lot_sizes", "id", "id", "audit_id='$Id'");  
        
        $LotCounter = 1;

        $HTML .= '<option value=""></option>';

				foreach($sLotsList as $iLotId)
				{

					$HTML .= '<option value="'.$iLotId.'">'.addslashes($LotCounter++).'</option>';

				}      	
				
				$HTML .= '</select>           
      </td>';

	if ($ReportId != 6) {

		$HTML .= '<td width="100" align="center">
      <select id="Area'.$iCountRow.'" name="Area'.$iCountRow.'" class="defectArea" onchange="$(\'Sms\').value=\'1\';" style="width:200px;">';

			if (strtotime($AuditDate) <= strtotime("2015-06-18"))
				$sSQL = "SELECT * FROM tbl_defect_areas ORDER BY area";

	    else if($ReportId == 28 || $ReportId == 37 || $ReportId == 38)
	      $sSQL = "SELECT * FROM tbl_defect_areas WHERE status='A' AND id IN (593,594,595,596,597,598) ORDER BY area";

	    else if(in_array($ReportId, array(41,42)))
				$sSQL = "SELECT id, IF($sAreaQuery IS NULL, area, $sAreaQuery) FROM tbl_defect_areas WHERE status='A' ORDER BY area";
	    else
				$sSQL = "SELECT * FROM tbl_defect_areas WHERE status='A' ORDER BY area";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iAreaId = $objDb->getField($i, 0);
			$sArea   = $objDb->getField($i, 1);

			$sAreaId = str_pad($iAreaId, 2, '0', STR_PAD_LEFT);

			$HTML .= '<option value="'.$sAreaId.'">'.addslashes($sArea).'</option>';
		}

		$HTML .= '</select>
      </td>';
	}

$HTML .= '<td width="80" align="center">
      <select id="Nature'.$iCountRow.'" name="Nature'.$iCountRow.'" class="defectNature" required="" onchange="$(\'Sms\').value=\'1\';">
      <option value=""></option>
      <option value="2">Critical</option>
      <option value="1">Major</option>
      <option value="0">Minor</option>
      </select>
      </td>
      <td width="80" align="center">
      	'.$defectStylesListHTML.'
      </td>
      <td width="80" align="center">
      	'.$defectColorListHTML.'
      </td>
      <td width="80" align="center">
      	'.$defectSizesListHTML.'
      </td>
      <td width="60" align="center">
      	<input type="text" style="width:50px;" name="defectSampleNo[]">
      </td>
      <td width="40" align="center"><img src="images/icons/delete.gif" alt="Delete" title="Delete" style="cursor:pointer;" class="deleteDefect" rel="'.$iCountRow.'" width="16" height="16">
      </td>
      </tr>';

  if (@in_array($ReportId, array(14,25,29,34))) {

	$HTML .= '<tr>
	    <td align="center">CAP</td>
	    <td colspan=""><input type="text" id="Cap'.$iCountRow.'" name="Cap'.$iCountRow.'" value=" maxlength="250" class="textbox defectCap" style="width:97.5%;" onchange="$(\'Sms\').value=\'1\';" /></td>
	    </tr>';    
  }

  if (@in_array($ReportId, array(25,28,37,38))) {

	 $HTML .= '<tr>
	    <td align="center">Remarks</td>
	    <td colspan=""><input type="text" id="Remarks'.$iCountRow.'" name="Remarks'.$iCountRow.'" value=" maxlength="250" class="textbox defectCap" style="width:97.5%;" onchange="$(\'Sms\').value=\'1\';" /></td>
	    </tr>';   
  }

$HTML .= '<tr>
      <td align="center">
      	<img src="images/icons/pictures.gif" alt="Defect Picture" title="Defect Picture" width="16" height="16">
      </td>
      <td colspan="">
      	<input type="file" id="Picture'.$iCountRow.'" name="Picture'.$iCountRow.'[]" multiple="" value="" size="30" class="textbox defectPicture">
      </td>
    </tr>';

$HTML .= '</table>
  </div>
</div>';

print $HTML;

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

    if(in_array($key, $selectedValue))
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

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>