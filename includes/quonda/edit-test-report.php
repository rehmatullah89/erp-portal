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
				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="120"><b>Inspection Code</b></td>
					<td width="20" align="center">:</td>
					<td><b><?= $AuditCode ?></b></td>
				  </tr>

				  <tr>
					<td>Factory</td>
					<td align="center">:</td>
					<td><?= $sVendor ?></td>
				  </tr>

				  <tr>
					<td>Inspector</td>
					<td align="center">:</td>
					<td><?= $sAuditor ?></td>
				  </tr>

				  <tr valign="top">
					<td>Style</td>
					<td align="center">:</td>

          <td> 
          <input type="hidden" name="auditId" id="auditId" value="<?=$Id?>">          
<?

		$allStyles = getDbValue('GROUP_CONCAT(DISTINCT(style_id))', '`tbl_hoh_order_details` hod INNER JOIN `tbl_hoh_orders` ho ON ho.id = hod.hoh_order_id', "hod.hoh_order_id='$PO'");
		
		$allSizes = getDbValue('GROUP_CONCAT(DISTINCT(size_id))', '`tbl_hoh_order_details` hod INNER JOIN `tbl_hoh_orders` ho ON ho.id = hod.hoh_order_id', "hod.hoh_order_id='$PO'");

		$allColors = getDbValue('GROUP_CONCAT(DISTINCT(color))', '`tbl_hoh_order_details` hod INNER JOIN `tbl_hoh_orders` ho ON ho.id = hod.hoh_order_id', "hod.hoh_order_id='$PO'");

            $sStylesList = getList("tbl_styles", "id", "CONCAT(style_name, '-',style)", "id IN ($allStyles)");
            $sLotsList = getList("tbl_qa_lot_sizes", "id", "id", "audit_id='$Id'");

            if($Styles == "")
              $Styles = $Style;

            $sStyles = getList("tbl_styles", "id", "CONCAT(style_name, '-',style)", "id IN ($Styles)");

          $sCountryBlocksList = getList("tbl_country_blocks", "id", "country_block");
          $sSizesList = getList("tbl_sampling_sizes", "id", "size", "id IN ($allSizes)");

					$colorArray = explode(',', $allColors);

?>
          <?=implode(',',$sStyles)?>
          <input type="hidden" name="styles[]" id="styles" value="<?=$Styles?>">
            <!-- <select name="styles[]" id="styles" required="" style="width:150px;" multiple onmouseleave="getStyleCombinations()">                                             -->
<?
                /* foreach($sStylesList as $key => $sType)       
                 {
?>
                  <option value="<?=$key?>" <?=in_array($key, explode(",", $Styles))?'selected':''?>><?=$sType?></option>
<?
                 }   */
?>
            <!-- </select> -->
          </td>
				  </tr>

				  <tr>
					<td>Inspection Stage</td>
					<td align="center">:</td>

					<td>
<!-- 					  <select name="AuditStage" onchange="$('Sms').value='1';">
						<option value=""></option> -->
            <input type="hidden" name="AuditStage" value="<?=$AuditStage?>">
<?
	foreach ($sAuditStagesList as $sKey => $sValue)
	{
		if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE && $sValue == "Final")
			$sValue = "Firewall";

		if ( (@strpos($_SESSION["Email"], "pelknit.com") !== FALSE || @strpos($_SESSION["Email"], "fencepostproductions.com") !== FALSE) &&
			 !@in_array($sKey, array("B", "C", "O", "F")) )
			continue;

    if($sKey == $AuditStage){
      echo $sValue;
    }
    /*
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $AuditStage) ? " selected" : "") ?>><?= $sValue ?></option>
<?
*/
	}
?>
					  <!-- </select> -->
					</td>
				  </tr>

				  <tr>
					<td>HOH I.O. No.</td>
					<td align="center">:</td>

					<td>
            <?=$sHohOrderNo?>
                                            <input type="hidden" name="HOHIONo" value="<?=$sHohOrderNo?>">
                                            <input type="hidden" name="AuditType" value="B">
					</td>
				  </tr>

				  <tr>
					<td>Sampling Plan</td>
					<td align="center">:</td>

					<td>
<!-- 					  <select name="SamplingPlan">
						<option value=""></option>
						<option value="1">Single Sampling Plan</option>
						<option value="2">Double Sampling Plan</option>
					  </select> -->

					  <script type="text/javascript">
					  <!--
						// document.frmData.SamplingPlan.value = "<?= $CheckLevel ?>";
					  -->
					  </script>
            <?
              if($CheckLevel == '2')
                echo "Double Sampling Plan";
              else
                echo "Single Sampling Plan";
            ?>
            <input type="hidden" name="SamplingPlan" value="<?= $CheckLevel ?>"> 
					</td>
				  </tr>


          <tr>
          <!--<td>Sample Size</td>
          <td align="center">:</td>
          <td><?// $TotalGmts ?></td>-->
          </tr>
				</table>

				<br />
                                <h2>Inspection Sections</h2>
                                <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" style="margin-top:-10px;">
                                <tr class="sdRowHeader">
                                  <td width="25"><b>#</b></td>
                                  <td><b>Section</b></td>
                                  <td width="60" align="center"><b>Options</b></td>
                                </tr>
                                <tr class="sdRowColor">
                                  <td width="25">1</td>
                                  <td>Description/Quantity of Product</td>
                                  <td align="center"><a href="includes/quonda/edit-general-report-section.php?AuditId=<?=$Id?>&Section=8" class="lightview" rel="iframe" title="Description/Quantity of Product for Inspection#: <?= $Id ?> :: :: width: 900, height: 650"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit Description/Quantity of Product" title="Edit Description/Quantity of Product" /></a>&nbsp;</td>
                                </tr>                                
                                <tr class="sdRowColor">
                                  <td width="25">2</td>
                                  <td>Product Conformity</td>
                                  <td align="center"><a href="includes/quonda/edit-general-report-section.php?AuditId=<?=$Id?>&Section=1" class="lightview" rel="iframe" title="Product Conformity for Inspection#: <?= $Id ?> :: :: width: 900, height: 650"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit Product Conformity" title="Edit Product Conformity" /></a>&nbsp;</td>
                                </tr>
                                <tr class="sdRowColor">
                                  <td>3</td>
                                  <td>Weight Conformity</td>
                                  <td align="center"><a href="includes/quonda/edit-general-report-section.php?AuditId=<?=$Id?>&Section=2" class="lightview" rel="iframe" title="Weight Conformity for Inspection#: <?= $Id ?> :: :: width: 900, height: 650"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit Weight Conformity" title="Edit Weight Conformity" /></a>&nbsp;</td>
                                </tr>
                                <tr class="sdRowColor">
                                  <td>4</td>
                                  <td>EAN- Code</td>
                                  <td align="center"><a id="ean-section" href="includes/quonda/edit-general-report-section.php?AuditId=<?=$Id?>&Section=3&Styles=<?=$Styles?>" class="lightview" rel="iframe" title="EAN- Code for Inspection#: <?= $Id ?> :: :: width: 900, height: 650"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit EAN- Code" title="Edit EAN- Code" /></a>&nbsp;</td>
                                </tr>
<?
if($AuditStage == 'F')
{
?>                                
                                <tr class="sdRowColor">
                                  <td>5</td>
                                  <td>Assortment</td>
                                  <td align="center"><a href="includes/quonda/edit-general-report-section.php?AuditId=<?=$Id?>&Section=4" class="lightview" rel="iframe" title="Assortment for Inspection#: <?= $Id ?> :: :: width: 900, height: 650"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit Assortment" title="Edit Assortment" /></a>&nbsp;</td>
                                </tr>
                                <tr class="sdRowColor">
                                  <td>6</td>
                                  <td>Dimensions of Cartons</td>
                                  <td align="center"><a href="includes/quonda/edit-general-report-section.php?AuditId=<?=$Id?>&Section=5" class="lightview" rel="iframe" title="Dimensions of Cartons for Inspection#: <?= $Id ?> :: :: width: 900, height: 650"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit Dimensions of Cartons" title="Edit Dimensions of Cartons" /></a>&nbsp;</td>
                                </tr>
<?
}
?>
                                 <tr class="sdRowColor">
                                  <td><?=($AuditStage == 'F'?7:5)?></td>
                                  <td>Child Labor</td>
                                  <td align="center"><a href="includes/quonda/edit-general-report-section.php?AuditId=<?=$Id?>&Section=6" class="lightview" rel="iframe" title="Child Labor for Inspection#: <?= $Id ?> :: :: width: 900, height: 650"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit Child Labor" title="Edit Child Labor" /></a>&nbsp;</td>
                                </tr>
                                <tr class="sdRowColor">
                                  <td><?=($AuditStage == 'F'?8:6)?></td>
                                  <td>Signatures</td>
                                  <td align="center"><a href="includes/quonda/edit-general-report-section.php?AuditId=<?=$Id?>&Section=7" class="lightview" rel="iframe" title="Signatures for Inspection#: <?= $Id ?> :: :: width: 900, height: 650"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit Signatures" title="Edit Signatures" /></a>&nbsp;</td>
                                </tr>

                                </table>
                                <h2 id="DefectDetails" style="margin-bottom:0px;"><?=($AuditStage == 'F'?9:7)?>- Workmanship</h2>
						
  <h3>Defects</h3>
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
		$defectStyle = $objDb->getField($i, 'style_id');
		$defectSize = $objDb->getField($i, 'size_id');
		$defectColor = $objDb->getField($i, 'color');
		$defectSampleNo = $objDb->getField($i, 'sample_no');

		if ($objDb->getField($i, 'nature') > 0)
			$iDefects += $objDb->getField($i, 'defects');
?>

				<div id="DefectRecord<?= $i ?>" class="defectRecords">
				  <div>
				    <input type="hidden" id="DefectId<?= $i ?>" name="DefectId<?= $i ?>" value="<?= $objDb->getField($i, 'id') ?>" class="defectId" />

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
							<option value=""></option>
<?
                $LotCount = 1;
		foreach($sLotsList as $iLotId)
		{
?>
		        			<option value="<?= $iLotId ?>"><?= $LotCount++ ?></option>
<?
		}
?>
						  </select>

						  <script type="text/javascript">
						  <!--
							document.frmData.LotNo<?= $i ?>.value = "<?= str_pad($objDb->getField($i, 'lot_id'), 2, '0', STR_PAD_LEFT); ?>";
						  -->
						  </script>
						</td>
                                                  
						<td width="100" align="center">
						  <select id="Area<?= $i ?>" name="Area<?= $i ?>" class="defectArea" onchange="$('Sms').value='1';" style="width:200px;">
							<option value=""></option>
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
                                                    <option value=""></option>
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
							$dbDefectSizesListHTML = getKeyValueListHTML("defectSize","defectSizes[]",$sSizesList,true,$defectSize);
							$dbDefectColorListHTML = getListHTML("defectColor","defectColors[]",$colorArray,true,true,$defectColor);
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
                            $iWorkmanshipResult = getDbValue("workmanship_result", "tbl_qa_hohenstein", "audit_id='$Id'");
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
				  <input type="button" value="" class="btnAdd" title="Add Defect" onclick="addDefect( );" />
				</div>

<?
if($AuditStage == 'F')
{
?>                                
                                <!--- Packing Defects Starts -->
                                <h2 style="margin-bottom:0px;">10- Carton Inspection Defects & Comments</h2>
<?
                                    $sSQL = "SELECT packaging_result, packaging_comments, labeling_result, labeling_comments
                                            FROM tbl_qa_hohenstein
                                            WHERE audit_id='$Id'";
                                    $objDb->query($sSQL);

                                   $sPackingResult      = $objDb->getField(0, 'packaging_result');
                                   $sPackingComments    = $objDb->getField(0, 'packaging_comments');
                                   $sLabelingResult     = $objDb->getField(0, 'labeling_result');
                                   $sLabelingComments   = $objDb->getField(0, 'labeling_comments');
                                   
                                    $sSQL = "SELECT *
                                            FROM tbl_qa_packaging_defects
                                            WHERE audit_id='$Id'";
                                    $objDb->query($sSQL);

                                    $iPackagingCount = $objDb->getCount( );
                                   
?>
                                <div id="PackaginDefects">                                    
				<input type="hidden" id="PCountRows" name="PCountRows" value="<?= $iPackagingCount ?>" />

<table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<tr>
  <td width="170">Total Cartons Available</td>
  <td width="20" align="center"> : </td>
  <td> <input type="text" class="textbox" name="totalCartons" id="totalCartons" value="<?=$TotalCartons?>"></td>
</tr>
<tr>
  <td width="170">Cartons Picked for Inspection</td>
  <td width="20" align="center"> : </td>
  <td> <input type="text" class="textbox" name="inspectedCartons" id="inspectedCartons" value="<?=$InspectedCartons?>"></td>
</tr>
</table>
			    <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			      <tr class="sdRowHeader">
					<td width="55" align="center"><b>#</b></td>
					<td><b>Defect</b></td>
					<td width="100" align="center"><b>Sample No</b></td>
					<td width="200" align="center"><b>Picture</b></td>
					<td width="50" align="center"><b>Delete</b></td>
			      </tr>
			    </table>
                                <?
                                //$sPackagingDefectsList = getList("tbl_packaging_defects", "id", "CONCAT(code,' - ',defect)", "", "id");
                            $sPackagingDefectsList = getList("tbl_statements", "id", "statement"," sections='6'" , "id");
?>
                                 <div id="PDefect" style="display:none;">
                                                <option value=""></option>
<?
                                               foreach($sPackagingDefectsList as $iPId => $sPDefect)
                                               {
?>
                                                <option value="<?=$iPId?>"><?=$sPDefect?></option> 
<?
                                               }
?>
                                            </div>
                                 <div id="PSamples" style="display:none;">
                                                <option value=""></option>
<?
                                             for($i=1; $i<=$InspectedCartons; $i++)
                                             {
?>
                                                <option value="<?=$i?>"><?=$i?></option>
<?
                                             }
?>
                                            </div>
                            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="PDefectsTable">
<?
                        if($iPackagingCount > 0)
                        {
                                @list($sPkYear, $sPkMonth, $sPkDay) = @explode("-", $AuditDate);
                                $sPkPicsDir   = (SITE_URL.PACKAGING_PICS_DIR.$sPkYear."/".$sPkMonth."/".$sPkDay."/");
                                $sPackagingDir = ($sBaseDir.PACKAGING_PICS_DIR.$sPkYear."/".$sPkMonth."/".$sPkDay."/");
                
                                for($i = 0; $i < $iPackagingCount; $i ++)
                                {
                                    $iTableId      = $objDb->getField($i, 'id');
                                    $iDefectCodeId = $objDb->getField($i, 'defect_code_id');
                                    $iSampleNumber = $objDb->getField($i, 'sample_no');
                                    $sDefectPicture= $objDb->getField($i, 'picture');
?>
                                    <tr id="PRowNo<?=$i+1?>">
                                        <td width="50" align="center"><b><?=$i+1?></b><input type="hidden" name="PDefectRows[]" value='0'></td>
                                        <td>
                                            <select name="PDefect[]" required="">
                                                <option value=""></option>
<?
                                               foreach($sPackagingDefectsList as $iPId => $sPDefect)
                                               {
?>
                                                <option value="<?=$iPId?>" <?=($iDefectCodeId == $iPId)?'selected':''?>><?=$sPDefect?></option> 
<?
                                               }
?>
                                            </select>
                                        </td>
					<td width="100" align="center">
                                            <select name="PSamples[]" required="">
                                                <option value=""></option>
<?
                                             for($j=1; $j<=$InspectedCartons; $j++)
                                             {
?>
                                                <option value="<?=$j?>" <?=($iSampleNumber == $j)?'selected':''?>><?=$j?></option>
<?
                                             }
?>
                                            </select>
                                        </td>
                                        <td width="200" align="center">
                                            <input type="file" name="PImages[]">
<?
                                                if ($sDefectPicture != "" && @file_exists($sPackagingDir.$sDefectPicture))
                                                {
?>
                                            <br/><span>(<a href="<?= $sPkPicsDir ?><?= $sDefectPicture ?>" class="lightview"><?= $sDefectPicture ?></a>)&nbsp;</span>
						  <input type="hidden" name="PPrevPicture[]" value="<?= $sDefectPicture ?>">
<?
                                                } else {
?>
                                                  <input type="hidden" name="PPrevPicture[]" value="">
<?
                                                }
?>
                                        </td>
                                        <td width="50" align="center"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" style="cursor:pointer;" onclick="DeletePLDefect(<?=$i+1?>,<?=$iTableId?>,'P')" /></td>
			      </tr>
<?
                                }
                        }
                        else
                        {
?>
                                <tr style="line-height:0px;">
                                    <td width="50" align="center">&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td width="100" align="center">&nbsp;</td>
                                    <td width="200" align="center">&nbsp;</td>
                                    <td width="50" align="center">&nbsp;</td>
                                </tr>
<?
                        }
?>
			    </table>
                                </div>
                            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			      <tr class="sdRowHeader">
					<td width="55" align="center"><b>Result : </b></td>
                                        <td width="60">
                                            <select name="PackingResult">
                                                <option value=""></option>
                                                <option value="P" <?=($sPackingResult == 'P'?'selected':'')?>>Pass</option>
                                                <option value="F" <?=($sPackingResult == 'F'?'selected':'')?>>Fail</option>
                                            </select>
                                        </td>
					<td width="100" align="center"><b>Comments : </b></td>
                                        <td><input type="text" name="PackingComments" value="<?=$sPackingComments?>" style="width: 98%;"></td>
			      </tr>                            
			    </table>
                                <div class="qaButtons">
				  <input type="button" value="" class="btnAdd" title="Add Packaging Defect" onclick="addPLDefect('P');" />
				</div>
                                <!-- Packing Defects Ends -->

                                <!--- Labeling Defects Starts-->
                        <h2 style="margin-bottom:0px;">11- Sales Packaging Defects & Comments</h2>                        
<?

                                    $sSQL = "SELECT labeling_total_cartons, labeling_sample_size
                                            FROM tbl_qa_hohenstein
                                            WHERE audit_id='$Id'";
                                    $objDb->query($sSQL);

                                   $totalLabelingCartons      = $objDb->getField(0, 'labeling_total_cartons');
                                   $inspectedLabelingCartons    = $objDb->getField(0, 'labeling_sample_size');

                                    $sSQL = "SELECT *
                                            FROM tbl_qa_labeling_defects
                                            WHERE audit_id='$Id'";
                                    $objDb->query($sSQL);

                                    $iLabelingCount = $objDb->getCount( );
                                   
?>
                                <div id="LabelingDefects">
				<input type="hidden" id="LCountRows" name="LCountRows" value="<?= $iLabelingCount ?>" />

<table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<tr>
  <td width="170">Total Cartons Available</td>
  <td width="20" align="center"> : </td>
  <td> <input type="text" class="textbox" name="totalLabelingCartons" id="totalLabelingCartons" value="<?=$totalLabelingCartons?>"></td>
</tr>
<tr>
  <td width="170">Cartons Picked for Inspection</td>
  <td width="20" align="center"> : </td>
  <td> <input type="text" class="textbox" name="inspectedLabelingCartons" id="inspectedLabelingCartons" value="<?=$inspectedLabelingCartons?>"></td>
</tr>
</table>

			    <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			      <tr class="sdRowHeader">
					<td width="55" align="center"><b>#</b></td>
					<td><b>Defect</b></td>
					<td width="100" align="center"><b>Sample No</b></td>
					<td width="200" align="center"><b>Picture</b></td>
					<td width="50" align="center"><b>Delete</b></td>
			      </tr>
			    </table>
                                <?
                                // $sLabelingDefectsList = getList("tbl_labeling_defects", "id", "CONCAT(code,' - ',defect)", "", "id");

                            $sLabelingDefectsList = getList("tbl_statements", "id", "statement"," sections='6'" , "id");
?>
                                 <div id="LDefect" style="display:none;">
                                                <option value=""></option>
<?
                                               foreach($sLabelingDefectsList as $iPId => $sPDefect)
                                               {
?>
                                                <option value="<?=$iPId?>"><?=$sPDefect?></option> 
<?
                                               }
?>
                                            </div>
                                 <div id="LSamples" style="display:none;">
                                                <option value=""></option>
<?
                                             for($i=1; $i<=$inspectedLabelingCartons; $i++)
                                             {
?>
                                                <option value="<?=$i?>"><?=$i?></option>
<?
                                             }
?>
                                            </div>
                            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="LDefectsTable">
<?
                        if($iLabelingCount > 0)
                        {
                                @list($sPkYear, $sPkMonth, $sPkDay) = @explode("-", $AuditDate);
                                $sPkPicsDir   = (SITE_URL.PACKAGING_PICS_DIR.$sPkYear."/".$sPkMonth."/".$sPkDay."/");
                                $sPackagingDir = ($sBaseDir.PACKAGING_PICS_DIR.$sPkYear."/".$sPkMonth."/".$sPkDay."/");
                
                                for($i = 0; $i < $iLabelingCount; $i ++)
                                {
                                    $iTableId      = $objDb->getField($i, 'id');
                                    $iDefectCodeId = $objDb->getField($i, 'defect_code_id');
                                    $iSampleNumber = $objDb->getField($i, 'sample_no');
                                    $sDefectPicture= $objDb->getField($i, 'picture');
?>
                                    <tr id="LRowNo<?=$i+1?>">
                                        <td width="50" align="center"><b><?=$i+1?></b><input type="hidden" name="LDefectRows[]" value='0'></td>
                                        <td>
                                            <select name="LDefect[]" required="">
                                                <option value=""></option>
<?
                                               foreach($sLabelingDefectsList as $iPId => $sPDefect)
                                               {
?>
                                                <option value="<?=$iPId?>" <?=($iDefectCodeId == $iPId)?'selected':''?>><?=$sPDefect?></option> 
<?
                                               }
?>
                                            </select>
                                        </td>
					<td width="100" align="center">
                                            <select name="LSamples[]" required="">
                                                <option value=""></option>
<?
                                             for($j=1; $j<=$inspectedLabelingCartons; $j++)
                                             {
?>
                                                <option value="<?=$j?>" <?=($iSampleNumber == $j)?'selected':''?>><?=$j?></option>
<?
                                             }
?>
                                            </select>
                                        </td>
                                        <td width="200" align="center">
                                            <input type="file" name="LImages[]">
<?
                                                if ($sDefectPicture != "" && @file_exists($sPackagingDir.$sDefectPicture))
                                                {
?>
                                            <br/><span>(<a href="<?= $sPkPicsDir ?><?= $sDefectPicture ?>" class="lightview"><?= $sDefectPicture ?></a>)&nbsp;</span>
						  <input type="hidden" name="LPrevPicture[]" value="<?= $sDefectPicture ?>">
<?
                                                } else {
?>
                                                  <input type="hidden" name="LPrevPicture[]" value="">
<?
                                                }
?>
                                        </td>
                                        <td width="50" align="center"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" style="cursor:pointer;" onclick="DeletePLDefect(<?=$i+1?>,<?=$iTableId?>,'L')" /></td>
			      </tr>
<?
                                }
                        }
                        else
                        {
?>
                                <tr style="line-height:0px;">
                                    <td width="50" align="center">&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td width="100" align="center">&nbsp;</td>
                                    <td width="200" align="center">&nbsp;</td>
                                    <td width="50" align="center">&nbsp;</td>
                                </tr>
<?
                        }
?>
			    </table>
                                </div>
                            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			      <tr class="sdRowHeader">
					<td width="55" align="center"><b>Result : </b></td>
                                        <td width="60">
                                            <select name="LabelingResult">
                                                <option value=""></option>
                                                <option value="P" <?=($sLabelingResult == 'P'?'selected':'')?>>Pass</option>
                                                <option value="F" <?=($sLabelingResult == 'F'?'selected':'')?>>Fail</option>
                                            </select>
                                        </td>
					<td width="100" align="center"><b>Comments : </b></td>
                                        <td><input type="text" name="LabelingComments" value="<?=$sLabelingComments?>" style="width: 98%;"></td>
			      </tr>                            
			    </table>
                                <div class="qaButtons">
				  <input type="button" value="" class="btnAdd" title="Add Labeling Defect" onclick="addPLDefect('L');" />
				</div>
                                <!-- Labeling Defects Ends -->
<?
}
?>
 <style type="text/css">
   .tr-info-head-span {
    font-family: verdana, arial, sans-serif;font-size: 11px;font-weight:bold;color: #ffffff; margin-left: 5px;
   }
   .tr-info-span {
    font-family: verdana, arial, sans-serif;font-size: 11px;color: #ffffff;margin-left: 5px;
   }   
 </style>                               
 <h2><?=($AuditStage == 'F'?12:8)?>- Measurement Conformity</h2>
 <div id="styleMeasurementDiv">                            
    <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" >
            <tr class="sdRowHeader">
              <td width="25" align="center"><b>#</b></td>
              <td width="80" align="center"><b>Style</b></td>
              <td width="80" align="center"><b>Color</b></td>
              <td width="50" align="center"><b>Size</b></td>
              <td width="100" align="center"><b>Samples</b></td>
              <td width="10" align="center"></td>
              <td width="20" align="center"><b>Result</b></td>
            </tr>
    </table>
<?
                        // Measurement Sheet starts here
          $sSQL = "SELECT hiod.*,(SELECT concat(COALESCE(style_name),if(style_name is null or style_name = '', '', '-'),style) FROM tbl_styles WHERE id=hiod.style_id) AS _Style ,
              (SELECT size FROM tbl_sampling_sizes WHERE id=hiod.size_id) AS _Size
					 FROM tbl_hoh_order_details hiod
					 WHERE hiod.style_id IN ($Styles)";

					 // var_dump($sSQL);exit;
                        $objDb->query($sSQL);

			$iCount        = (int)$objDb->getCount( );
                        
                        $iCounter      = 1;
			$sSizeFindings = array( );
                        $sSizeSpecs    = array( );
                        
      $OverallResults = array();

			for($i = 0; $i < $iCount; $i ++)
			{
        $iId        = $objDb->getField($i, 'id');
        $iOrderId        = $objDb->getField($i, 'hoh_order_id');
				$sStyle      = $objDb->getField($i, '_Style');
				$iStyle      = $objDb->getField($i, 'style_id');
				$iStyleDetail      = $objDb->getField($i, 'style_detail_id');
				$sColor  = $objDb->getField($i, 'color');
				$iSize      = $objDb->getField($i, 'size_id');
				$sSize      = $objDb->getField($i, '_Size');

				$totalSamples = 3;
				$resultStatus = "-";
?>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" >
          <tr class="sdRowColor">
            <td width="25" align="center"><?=$iCounter?></td>
            <td width="80" align="center"><?=$sStyle?></td>
            <td width="80" align="center"><?=$sColor?></td>
            <td width="50" align="center"><?=$sSize?></td>
            <td width="100" align="center">
            	<?
            		
            		$sampleCount = 1;
            		$failedSampleCount = 0;
            		$passSampleCount = 0;
                $showArrow = "";
            		$sampleHTMLHideDiv = "";
                $alreadyAdded = array();
                $deviationSampleCount = $totalSamples;
                for($j=0;$j<$totalSamples;$j++) {
            			
            			$colorStyle = "";
                  $sSizeFindings = array( );

                  $iNextId = getDbValue('id', 'tbl_qa_report_samples', "audit_id='$Id' AND size_id='$iSize' AND color='$sColor' AND sample_no='$sampleCount'");

                  $iNextId = $iNextId ? $iNextId : 0;

									$status = checkSampleStatus($iStyle,$iOrderId,$iStyleDetail,$iSize,$iNextId);

									if($status == 'fail'){
										$failedSampleCount++;
										$colorStyle = 'style="color:red;"';
									} else if($status == 'pass'){
										$colorStyle = 'style="color:green;"';
										$passSampleCount++;
									}

									if($sampleCount == 3 && $passSampleCount >= 2){
										$resultStatus = "P";
                  } else if($sampleCount == 3 && $failedSampleCount >= 2){
                    $totalSamples = 5;
                  } else if($sampleCount == 5 && $failedSampleCount >= 3){
                    $resultStatus = "F";
                  } else if($sampleCount == 5 && $passSampleCount >= 3){
                    $resultStatus = "P";
                  }

                  $deviationSampleCount = $totalSamples;
                  
                  if($failedSampleCount >= 1)
                    $showArrow = '<a href="javascript:;" class="detailArrow" onclick="toggleMeasurementStyleDetail(styleDetailRow'.$iCounter.'); return false;" id="showDetail'.$iCounter.'>">V</a>';

                $sSQL = "SELECT qrs.sample_no, qrss.point_id, qrss.findings, qrss.specs
                   FROM tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss
                   WHERE qrs.audit_id='$Id' AND qrs.sample_no= '$sampleCount' AND qrs.id=qrss.sample_id AND qrs.size_id='$iSize' AND qrs.color='$sColor' AND (qrs.size='' OR qrs.size='$sSize')
                   ORDER BY qrs.sample_no, qrss.point_id";

                $objDb3->query($sSQL);

                $iCount3 = (int)$objDb3->getCount( );

                  for($m = 0; $m < $iCount3; $m++)
                  {

                    $iPoint         = $objDb3->getField($m, 'point_id');
                    $sFindings      = $objDb3->getField($m, 'findings');

                    $sSizeFindings["{$iPoint}"] = (($sFindings == '' || $sFindings == '0' || strtolower($sFindings) == 'ok')?'-':$sFindings);

                  }

                $sSQL = 'SELECT mp.id, mp.point_en, mp.tolerance, mp.position, ss.specs, mp.tolerance_unit, ss.point_id 
                FROM tbl_hoh_measurement_points mp, tbl_hoh_style_specs ss WHERE mp.style_id="'.$iStyle.'" AND mp.hoh_order_id="'.$iOrderId.'" AND mp.style_detail_id="'.$iStyleDetail.'" AND mp.id=ss.point_id AND ss.size_id="'.$iSize.'" ORDER BY mp.id ';

                $objDb4->query($sSQL);

                $iCount4 = (int)$objDb4->getCount( );

                for ($g=0; $g <$iCount4 ; $g++) { 
                    
                    $iPoint = $objDb4->getField($g, 'point_id');
                    $findings = $sSizeFindings["{$iPoint}"];

                    if($findings && $status == "fail"){

                        $position = $objDb4->getField($g, 'position');
                        $measurementPoint = $objDb4->getField($g, 'point_en');
                        $specs  = $objDb4->getField($g, 'specs');
                        $tolerance  = $objDb4->getField($g, 'tolerance');

                        $result = getTolerance($findings,$specs,$tolerance); 

                        if($result == "pass" || in_array($iPoint, $alreadyAdded)){
                          continue;
                        }

                        $frequencyArray = getFrequency($Id,$iSize,$sColor,$sSize,$specs,$tolerance,$result,$iPoint);

                        $frequencyData = $frequencyArray["{$iPoint}"];
                        $frequency = $frequencyData['frequency'];
                        $deviationHTML = $frequencyData['html'];

                        if(empty($deviationHTML)){
                          continue;
                        }
                        
                        $sampleHTMLHideDiv .= '<table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" >
                          <tr class="sdInfoRowColor"  style="background: #B2B2B2;">
                          <td colspan="6" style="padding-left: 30px;">
                            <span class="tr-info-head-span">Position:</span>
                            <span class="tr-info-span"> '.$position.'</span>
                            <span class="tr-info-span"> | </span>
                            <span class="tr-info-head-span">'.$measurementPoint.'</span>
                            <span class="tr-info-span"> | </span>
                            <span class="tr-info-span">Spec Required: '.$specs.'</span>
                            <span class="tr-info-span">Tolerance: '.$tolerance.'</span>
                            <span class="tr-info-span"> Deviations: </span>
                            '.$deviationHTML.'
                          </td>
                          <td width="54" align="center"><span class="tr-info-span" style="margin-right: 5px;">'.$frequency.'</span></td>
                        </tr>
                        </table>';

                        array_push($alreadyAdded, $iPoint);
                    }
                }

                array_push($OverallResults,$resultStatus);

?>
								<a href="includes/quonda/edit-measurement-specs.php?OrderId=<?=$iOrderId?>&OrderDetailId=<?=$iId?>&StyleId=<?= $iStyle ?>&SizeId=<?=$iSize?>&StyleDetailId=<?=$iStyleDetail?>&AuditId=<?=$Id?>&Size=<?=$sSize?>&Color=<?=$sColor?>&Style=<?=$sStyle?>&Nature=<?=$AuditStage?>&SampleId=<?=$iNextId?>&SampleNo=<?=$sampleCount?>" class="lightview" rel="iframe" title="Measurement Specs for Inspection#: <?= $Id ?> :: :: width: 900, height: 650" ><button <?=$colorStyle?>><?=$sampleCount?></button></a> &nbsp; 
<?
            		$sampleCount++;

            		if($j+1 == $totalSamples){
            			$totalSamples = 3;
            		}

            		}
            	?>

              </td>
              <td width="10" align="center"><span id="expand-combination-detail"><?=$showArrow?></span></td>
              <td width="20" align="center">
                  <a href="includes/quonda/view-measurement-specs.php?OrderId=<?=$iOrderId?>&OrderDetailId=<?=$iId?>&StyleId=<?= $iStyle ?>&SizeId=<?=$iSize?>&StyleDetailId=<?=$iStyleDetail?>&AuditId=<?=$Id?>&Size=<?=$sSize?>&Color=<?=$sColor?>&Style=<?=$sStyle?>&Nature=<?=$AuditStage?>&SampleId=<?=$iNextId?>&SampleNo=<?=$sampleCount?>&SampleCount=<?=$deviationSampleCount?>" class="lightview" rel="iframe" title="Measurement Specs for Inspection#: <?= $Id ?> :: :: width: 900, height: 650" ><button ><span id="result<?=$iId?>"><?=$resultStatus?></span></button></a>

                <!-- <span id="result<?=$iId?>"><?=$resultStatus?></span> -->
              </td>
          </tr>
        </table>
<?

          echo '<div id="styleDetailRow'.$iCounter.'" style="display:none;">';
          echo $sampleHTMLHideDiv;
          echo '</div>';
        $iCounter++;	
      }

		if(in_array('F', $OverallResults)){
			$dynamicResult = 'F';
		} else if(in_array('P', $OverallResults)){
			$dynamicResult = 'P';
		} else {
			$dynamicResult = 'N';
		}

    $sSQL = "SELECT measurement_result, measurement_remarks
            FROM tbl_qa_hohenstein
            WHERE audit_id='$Id'";
    $objDb->query($sSQL);

   $sMeasurementConformityResult      = $objDb->getField(0, 'measurement_result')?$objDb->getField(0, 'measurement_result'):$dynamicResult;
   $sMeasurementConformityRemarks    = $objDb->getField(0, 'measurement_remarks');

   $ConformityOptions = array('N' => 'Not Available/Applicable','F' => 'Fail','P' => 'Pass' );
?>
<table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" >
<tr>
	<td colspan="6"><h3>Measurement Conformity Result & Comment</h3></td>
</tr>
<tr>
	<td colspan="6">
		<select name="MeasurementConformityResult">
			<?
				foreach ($ConformityOptions as $key => $value) {
					$selected = "";
					if($sMeasurementConformityResult == $key)
						$selected = "selected";

					echo '<option '.$selected.' value="'.$key.'" >'.$value.'</option>';
				}
			?>
		</select>
<br />
<br />
<textarea type="text" name="MeasurementConformityRemarks" rows="5" cols="50"><?=$sMeasurementConformityRemarks?></textarea>
	</td>
</tr>
                        </table>
                        </div>
<?
                            /*if($iCount < $TotalGmts)
                            {
?>
                                <div class="qaButtons" style="height: 30px; background: #494949;">
                                    <a style="text-decoration: none; overflow: hidden; display: block; float: right;" class="btnAdd lightview" href="includes/quonda/add-measurement-specs.php?Sizes=<?= $Sizes ?>&Colors=<?=$Colors?>&AuditId=<?=$Id?>&Style=<?=$Style?>" rel="iframe" title="Inspection#: <?= $Id ?> :: :: width: 350, height: 260"></a>
				</div>
<?
                            }*/
?>
 <?
	$sSQL = "SELECT quantity FROM tbl_po WHERE id='$PO'";
	$objDb->query($sSQL);

	$iOrderQty = $objDb->getField(0, 0);

	if (count($AdditionalPos) > 0)
	{
		$sSQL = "SELECT SUM(quantity) FROM tbl_po WHERE id IN ($AdditionalPos)";
		$objDb->query($sSQL);

		$iOrderQty += $objDb->getField(0, 0);
	}
        
        $sSamplePick = getDbValue("sample_pick", "tbl_bookings b, tbl_qa_reports qa", "qa.booking_id=b.id AND qa.id='$Id'");
        
        if($sSamplePick != "")
        {
            
            $sSQL = "SELECT airway_bill_applicable, airway_bill_number, airway_bill_comments
                                            FROM tbl_qa_hohenstein
                                            WHERE audit_id='$Id'";
                                    $objDb->query($sSQL);

            $AirwayBill     = $objDb->getField(0, 'airway_bill_applicable');
            $BillNumber     = $objDb->getField(0, 'airway_bill_number');
            $AirwayComments = $objDb->getField(0, 'airway_bill_comments');
?>
                <h2>Air Way Bill?</h2>
                <table border="0" cellpadding="3" cellspacing="0" width="100%">
                    <tr>
                    <td width="140">Is Airway Bill Number applicable for this?<span class="mandatory">*</span></td>
                    <td width="20" align="center">:</td>
                    <td>
                        <input type="radio" name="AirwayBill" value="Y" onchange="toggleBillDisplay(this.value);" <?=($AirwayBill == 'Y'?'checked':'')?>> Yes &nbsp; <input type="radio" name="AirwayBill" value="N" onchange="toggleBillDisplay(this.value);"  <?=($AirwayBill == 'N'?'checked':'')?>> No &nbsp;
                    </td>
                    </tr>
                        
                    <tr><td colspan="3">&nbsp;</td></tr>
                    
                    <tr id="ToggleRowId" style="<?=($AirwayBill == 'Y'?'':'display:none;')?>">
                        <td width="140">Airway Bill Number<span class="mandatory">*</span></td>
                        <td width="20" align="center">:</td>
                        <td>
                            <input type="text" class="textbox" name="BillNumber" id="BillNumber" value="<?=$BillNumber?>" />
                        </td>
                    </tr>

                    <tr valign="top">
                          <td width="140">Comments/ Notes</td>
                          <td width="20" align="center">:</td>
                          <td><textarea name="AirwayComments" class="textarea" style="width:98%; height:80px;"><?= $AirwayComments?></textarea></td>
                    </tr>

                </table>
<?
        }
?>
 
				<h2>Result & Comment</h2>
        <input type="hidden" name="TotalGmts" value="<?= $TotalGmts ?>" size="10" class="textbox" />
				<table border="0" cellpadding="3" cellspacing="0" width="100%">

          <tr>
          <td width="140">Inspection Result<span class="mandatory">*</span></td>
          <td width="20" align="center">:</td>

          <td>
            <select name="AuditResult" id="auditResult" onchange="$('Sms').value='1';">
            <option value=""></option>
            <option value="P">Pass</option>
            <option value="F">Fail</option>
            <option value="S">Subject to Client Decision</option>
            </select>

            <script type="text/javascript">
            <!--
            document.frmData.AuditResult.value = "<?= $AuditResult ?>";
            -->
            </script>
          </td>
          </tr>

          <tr valign="top">
                <td width="140">Inspector Comments</td>
                <td width="20" align="center">:</td>
                <td><textarea name="Comments" class="textarea" style="width:98%; height:80px;"><?= $Comments ?></textarea></td>
          </tr>

				</table>


<?
$defectStylesListHTML = getKeyValueListHTML("defectStyle","defectStyles[]",$sStylesList,true);
$defectSizesListHTML = getKeyValueListHTML("defectSize","defectSizes[]",$sSizesList,true);
$defectColorListHTML = getListHTML("defectColor","defectColors[]",$colorArray,true,true);
?>
<script>

		var auditId = '<?=$Id?>';


    var i = parseInt(jQuery("#PCountRows").val())+parseInt(1);
    var j = parseInt(jQuery("#LCountRows").val())+parseInt(1);
    var defectStylesListHTML = '<?=$defectStylesListHTML?>';
    var defectSizesListHTML = '<?=$defectSizesListHTML?>';
    var defectColorListHTML = '<?=$defectColorListHTML?>';

    jQuery(document).on("focusout","#inspectedCartons",function(){
      
      var value = jQuery("#inspectedCartons").val();

      if(value && value != '0'){

          var optionsHTML = '<option value=""></option>';

          for(var i=1; i<=value; i++) {

            optionsHTML += '<option value="'+i+'">'+i+'</option>';
          }

          jQuery("#PSamples").html(optionsHTML); 

          jQuery('select[name^=PSamples]').each(function(){
              var selectedValue = jQuery(this).val();
              jQuery(this).find('option').remove().end().append(optionsHTML).val(selectedValue);
          });
              
      }


    });

    jQuery(document).on("focusout","#inspectedLabelingCartons",function(){
      
      var value = jQuery("#inspectedLabelingCartons").val();

      if(value && value != '0'){

        var optionsHTML = '<option value=""></option>';

        for(var i=1; i<=value; i++) {

          optionsHTML += '<option value="'+i+'">'+i+'</option>';
        }

        jQuery("#LSamples").html(optionsHTML);

          jQuery('select[name^=LSamples]').each(function(){
              var selectedValue = jQuery(this).val();
              jQuery(this).find('option').remove().end().append(optionsHTML).val(selectedValue);
          });

      }


    });

    function toggleMeasurementStyleDetail(id){
      
      if(jQuery(id).is(":visible")){
        jQuery(id).hide();
      } else {
        jQuery(id).show();
      }
    }

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
        cell3.innerHTML = "<select style='text-align:center;' name='"+PL+"Samples[]' required>"+document.getElementById(PL+"Samples").innerHTML+"</select>";
        cell3.style = 'text-align:center;';
        cell4.innerHTML = "<input style='text-align:center;' type='file' class='textbox' name='"+PL+"Images[]' value=''  style='width:95%;'/><input type='hidden' name='"+PL+"PrevPicture[]' value=''>";
        cell4.style = 'text-align:center;';
        cell5.innerHTML = "<img style='text-align:center;' src='images/icons/delete.gif' width='16' height='16' alt='Delete' title='Delete' style='cursor:pointer;' onclick=\"DeletePLDefect("+inc+", 0, '"+PL+"');\" />";
        cell5.style = 'text-align:center;';
        
        inc++;        
        document.getElementById(PL+"CountRows").value = inc;
        
        if(PL == 'P')
            i = inc;
        else
            j = inc;
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
        
        if(PL == 'P')
            i = inc;
        else
            j = inc;
    }

function toggleBillDisplay(Val)
{
    if(Val == 'Y')
        document.getElementById("ToggleRowId").style.display = '';
    else
        document.getElementById("ToggleRowId").style.display = 'none';
}
</script>

<?

function checkSampleStatus($iStyleId,$iOrderId,$iStyleDetailId,$iSizeId,$sampleId){

	$SampleResult = getDbValue('result', 'tbl_qa_report_samples', "id='$sampleId'");

		if($SampleResult == 'F'){
			return 'fail';
		} else if($SampleResult == 'P') {
			return 'pass';
		} else {
			return 'not-performed';
		}
	
}

function getListHTML($id,$name,$listInArray,$simpleArray=false,$empty=false,$selectedValue=""){

  $listHTML = '<select required="" name="'.$name.'" id="'.$id.'" style="width:150px;">';

    if($empty){

      $listHTML .=   '<option value=""> </option>';
    }

    $counter = 1;

   foreach($listInArray as $key => $value)       
   {

    $selected = "";

    if($simpleArray){

      if($value == $selectedValue)
        $selected = 'selected';

      $listHTML .=   '  <option value="'.$value.'" '.$selected.'>'.$value.'</option>';

    } else {

      if($counter == $selectedValue)
        $selected = 'selected';
      
      $listHTML .=   '  <option value="'.$counter.'" '.$selected.'>'.$value.'</option>';
    }

    $counter++;

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

function getFrequency($Id,$iSize,$sColor,$sSize,$specs,$tolerance,$result,$iPoint){

    $objDb5       = new Database( );

    $frequencyArray = array();

    $sSQL = "SELECT qrs.sample_no, qrss.point_id, qrss.findings, qrss.specs
       FROM tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss
       WHERE qrs.audit_id='$Id' AND qrs.id=qrss.sample_id AND qrs.size_id='$iSize' AND qrss.point_id='$iPoint' AND qrs.color='$sColor' AND (qrs.size='' OR qrs.size='$sSize')
       ORDER BY qrs.sample_no, qrss.point_id";

    $objDb5->query($sSQL);

    $iCount5 = (int)$objDb5->getCount( );

      for($g = 0; $g < $iCount5; $g ++)
      {
        $iSampleNo         = $objDb5->getField($g, 'sample_no');
        $iPoint         = $objDb5->getField($g, 'point_id');
        $sFindings      = $objDb5->getField($g, 'findings');

      $html = "";

        if($sFindings != "" && $sFindings != "ok") {

          $result = getTolerance($sFindings,$specs,$tolerance);

          $deviation = $sFindings - $specs;

          if($deviation > 0){
            $deviation = '+'.$deviation;
          }

          $html = '<span class="tr-info-span"> ('.$iSampleNo.') '.$deviation.' </span>';
          
          if($result == 'fail'){

            if(array_key_exists ("{$iPoint}", $frequencyArray)){

              $oldValue = $frequencyArray["{$iPoint}"];

              $oldFrequency = $oldValue['frequency'];
              $oldHTML = $oldValue['html'];

              $newFrequency = intval($oldFrequency) + 1;
              $newHTML = $oldHTML.$html;

              $frequencyArray["{$iPoint}"] =  array('frequency'=>$newFrequency,"html"=>$newHTML);


            } else {

              $frequencyArray["{$iPoint}"] = array("frequency"=>1,"html"=>$html);

            }
          }
        }

      }

  $objDb5->close( );

  return $frequencyArray;

}

function getTolerance($finding,$specs,$tolerance){

  $fSpecs           = ConvertToFloatValue($specs);
  $fTolerance       = parseTolerance($tolerance);

  $fNTolerance       = $fTolerance[0];
  $fPTolerance       = $fTolerance[1];

  $fPositiveTolerance = ($fSpecs + $fPTolerance);
  $fNegativeTolerance = ($fSpecs - $fNTolerance);

  if($finding > $fPositiveTolerance ||  $finding < $fNegativeTolerance){

      return 'fail';

  } else {

      return 'pass';
  }      

}

function ConvertToFloatValue($str)
{       
    $num = explode(' ', $str);
    
    if (strpos(@$num[0], '/') !== false)
    {
        $num1 = explode('/', @$num[0]);
        $num1 = @$num1[0] / @$num1[1];
    }
    
    else
        $num1= @$num[0];
    
    if (strpos(@$num[1], '/') !== false )
    {
        $num2 = explode('/', @$num[1]);
        $num2 = @$num2[0] / @$num2[1];
    }
    
    else
        $num2= @$num[1];
    
    
    return @number_format(($num1 + $num2),2);
} 
?>