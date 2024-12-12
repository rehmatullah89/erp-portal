<?
	$iDefects = 0;

	$sSQL = "SELECT * FROM tbl_qa_report_defects WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);
	$iCount = $objDb->getCount( );

	$sSQL = "SELECT DISTINCT(type_id), (SELECT type FROM tbl_defect_types WHERE id=tbl_defect_codes.type_id) FROM tbl_defect_codes WHERE report_id='$ReportId' ORDER BY type_id";
	$objDb2->query($sSQL);
	$iCount2 = $objDb2->getCount( );

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
		$defectStyle    = $objDb->getField($i, 'style_id');
		$defectSize     = $objDb->getField($i, 'size_id');
		$defectColor    = $objDb->getField($i, 'color');
                $defectSampleNo = $objDb->getField($i, 'sample_no');
		$defectLotNo    = $objDb->getField($i, 'lot_no');

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
							$dbDefectStylesListHTML = getKeyValueListHTML("defectStyle","defectStyles[]",$sStylesList,true,$defectStyle);
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
                                                <input type="file" id="Picture<?= $i ?>" name="Picture<?= $i ?>[]" multiple="" value="" class="textbox defectPicture" size="30" />
<?
		$sPicture = $objDb->getField($i, 'picture');
                $sPictures = explode(",", $objDb->getField($i, 'pictures'));
		
		if(!empty($sPicture))
		{
?>
						  <span>&bull; (<a href="<?= $sPicsDir ?><?= $sPicture ?>" class="lightview"><?= $sPicture ?></a>)&nbsp;</span>
						  <input type="hidden" name="PrevPicture<?= $i ?>" value="<?= $sPicture ?>">
<?
		}
                
                if(!empty($sPictures))
                {
                    foreach($sPictures as $sPicture)
                    {
                        if ($sPicture != "" && @file_exists($sQuondaDir.$sPicture))
                        {
?>
                                                      <br/><span>&bull; (<a href="<?= $sPicsDir ?><?= $sPicture ?>" class="lightview"><?= $sPicture ?></a>)&nbsp;</span>
<?
                        }
                    }
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