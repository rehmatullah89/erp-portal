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
					<td width="120"><b>Audit Code</b></td>
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

				  <tr valign="top">
					<td>Style<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>					  
<?
        if($Styles != "")
            $sStyles = getDbValue ("GROUP_CONCAT(CONCAT(style_name, ' - ',style) SEPARATOR ',')", "tbl_styles", "id IN ($Styles)");
        else
            $sStyles = getDbValue ("CONCAT(style_name, ' - ',style)", "tbl_styles", "id='$Style'");
?>
                                            <?=$sStyles?>
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
					<td>HOH I.O. No.</td>
					<td align="center">:</td>

					<td>
                                            <input type="text" name="HOHIONo" value="<?=$sHohOrderNo?>">
                                            <input type="hidden" name="AuditType" value="B">
					</td>
				  </tr>

				  <tr>
					<td>Sampling Plan</td>
					<td align="center">:</td>

					<td>
					  <select name="SamplingPlan">
						<option value=""></option>
						<option value="1">1</option>
						<option value="2">2</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.SamplingPlan.value = "<?= $CheckLevel ?>";
					  -->
					  </script>
					</td>
				  </tr>
                                  <tr>
					<td width="80">Audit Result<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="AuditResult" onchange="$('Sms').value='1';">
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
                                  <td>Product Conformity</td>
                                  <td align="center"><a href="includes/quonda/edit-hohenstein-section.php?AuditId=<?=$Id?>&Section=1" class="lightview" rel="iframe" title="Product Conformity for Inspection#: <?= $Id ?> :: :: width: 900, height: 650"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit Product Conformity" title="Edit Product Conformity" /></a>&nbsp;</td>
                                </tr>
                                <tr class="sdRowColor">
                                  <td>2</td>
                                  <td>Weight Conformity</td>
                                  <td align="center"><a href="includes/quonda/edit-hohenstein-section.php?AuditId=<?=$Id?>&Section=2" class="lightview" rel="iframe" title="Weight Conformity for Inspection#: <?= $Id ?> :: :: width: 900, height: 650"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit Weight Conformity" title="Edit Weight Conformity" /></a>&nbsp;</td>
                                </tr>
                                <tr class="sdRowColor">
                                  <td>3</td>
                                  <td>EAN- Code</td>
                                  <td align="center"><a href="includes/quonda/edit-hohenstein-section.php?AuditId=<?=$Id?>&Section=3" class="lightview" rel="iframe" title="EAN- Code for Inspection#: <?= $Id ?> :: :: width: 900, height: 650"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit EAN- Code" title="Edit EAN- Code" /></a>&nbsp;</td>
                                </tr>
<?
if($AuditStage == 'F')
{
?>                                
                                <tr class="sdRowColor">
                                  <td>4</td>
                                  <td>Assortment</td>
                                  <td align="center"><a href="includes/quonda/edit-hohenstein-section.php?AuditId=<?=$Id?>&Section=4" class="lightview" rel="iframe" title="Assortment for Inspection#: <?= $Id ?> :: :: width: 900, height: 650"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit Assortment" title="Edit Assortment" /></a>&nbsp;</td>
                                </tr>
                                <tr class="sdRowColor">
                                  <td>5</td>
                                  <td>Dimensions of Carton</td>
                                  <td align="center"><a href="includes/quonda/edit-hohenstein-section.php?AuditId=<?=$Id?>&Section=5" class="lightview" rel="iframe" title="Master Cartons for Inspection#: <?= $Id ?> :: :: width: 900, height: 650"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit Master Cartons" title="Edit Master Cartons" /></a>&nbsp;</td>
                                </tr>
<?
}
?>
                                 <tr class="sdRowColor">
                                  <td><?=($AuditStage == 'F'?6:4)?></td>
                                  <td>Child Labor</td>
                                  <td align="center"><a href="includes/quonda/edit-hohenstein-section.php?AuditId=<?=$Id?>&Section=6" class="lightview" rel="iframe" title="Child Labor for Inspection#: <?= $Id ?> :: :: width: 900, height: 650"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit Child Labor" title="Edit Child Labor" /></a>&nbsp;</td>
                                </tr>
                                <tr class="sdRowColor">
                                  <td><?=($AuditStage == 'F'?7:5)?></td>
                                  <td>Signatures</td>
                                  <td align="center"><a href="includes/quonda/edit-hohenstein-section.php?AuditId=<?=$Id?>&Section=7" class="lightview" rel="iframe" title="Signatures for Inspection#: <?= $Id ?> :: :: width: 900, height: 650"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit Signatures" title="Edit Signatures" /></a>&nbsp;</td>
                                </tr>

                                </table>
                                <h2 id="DefectDetails" style="margin-bottom:0px;"><?=($AuditStage == 'F'?8:6)?>- Workmanship</h2>
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
					<td width="50" align="center"><b>#</b></td>
					<td><b>Code - Check Points</b></td>
					<td width="100" align="center"><b>Defects</b></td>
					<td width="200" align="center"><b>Area</b></td>
					<td width="100" align="center"><b>Nature</b></td>
					<td width="50" align="center"><b>Delete</b></td>
			      </tr>
			    </table>

			    <div id="QaDefects">
<?
	$sDefectCode = "";
	$sDefectArea = "";

	for($i = 0; $i < $iCount; $i ++)
	{
		if ($objDb->getField($i, 'nature') > 0)
			$iDefects += $objDb->getField($i, 'defects');
?>

				<div id="DefectRecord<?= $i ?>" class="defectRecords">
				  <div>
				    <input type="hidden" id="DefectId<?= $i ?>" name="DefectId<?= $i ?>" value="<?= $objDb->getField($i, 'id') ?>" class="defectId" />

					<table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
					  <tr class="sdRowColor" valign="top">
						<td width="50" align="center" class="serial"><?= ($i + 1) ?></td>

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

						<td width="100" align="center"><input type="text" id="Defects<?= $i ?>" name="Defects<?= $i ?>" value="<?= $objDb->getField($i, 'defects') ?>" maxlength="3" size="3" class="textbox defectsCount" onchange="$('Sms').value='1';" /></td>

						<td width="200" align="center">
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

						<td width="100" align="center">
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

						<td width="50" align="center"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" style="cursor:pointer;" class="deleteDefect" rel="<?= $i ?>" /></td>
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
				<div class="qaButtons">
				  <input type="button" value="" class="btnAdd" title="Add Defect" onclick="addDefect( );" />
				</div>

<?
if($AuditStage == 'F')
{
?>                                
                                <!--- Packing Defects Starts------->
                                <h2 style="margin-bottom:0px;">9- Carton Inspection Defects & Comments</h2>
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
			      <tr class="sdRowHeader">
					<td width="55" align="center"><b>#</b></td>
					<td><b>Defect</b></td>
					<td width="100" align="center"><b>Sample No</b></td>
					<td width="200" align="center"><b>Picture</b></td>
					<td width="50" align="center"><b>Delete</b></td>
			      </tr>
			    </table>
                                <?
                            $sPackagingDefectsList = getList("tbl_packaging_defects", "id", "CONCAT(code,' - ',defect)", "", "id");
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
                                <!-- Packing Defects Ends --->

                                <!--- Labeling Defects Starts----->
                        <h2 style="margin-bottom:0px;">10- Sales Packaging Defects & Comments</h2>                        
<?
                                    $sSQL = "SELECT *
                                            FROM tbl_qa_labeling_defects
                                            WHERE audit_id='$Id'";
                                    $objDb->query($sSQL);

                                    $iLabelingCount = $objDb->getCount( );
                                   
?>
                                <div id="LabelingDefects">
				<input type="hidden" id="LCountRows" name="LCountRows" value="<?= $iLabelingCount ?>" />

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
                            $sLabelingDefectsList = getList("tbl_labeling_defects", "id", "CONCAT(code,' - ',defect)", "", "id");
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
                                             for($i=1; $i<=$InspectedCartons; $i++)
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
                                <!-- Labeling Defects Ends --->
<?
}
?>
                                
 <h2><?=($AuditStage == 'F'?11:7)?>- Measurement Conformity</h2>                            
    <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" style="margin-top:-10px;">
            <tr class="sdRowHeader">
              <td width="25"><b>#</b></td>
              <td><b>Color</b></td>
              <td width="80" align="center"><b>Size</b></td>
              <td width="80" align="center"><b>Nature</b></td>
              <td width="80" align="center"><b>Sample#</b></td>
              <td width="90" align="center"><b>Options</b></td>
            </tr>
<?
                        // Measurement Sheet starts here
                        $sSQL = "SELECT qrs.id, qrs.sample_no, qrs.size_id, qrs.color, qrs.size, qrs.nature
					 FROM tbl_qa_report_samples qrs
					 WHERE qrs.audit_id='$Id'                                        
					 ORDER BY qrs.sample_no, qrs.size_id";
                        $objDb->query($sSQL);

			$iCount        = (int)$objDb->getCount( );
                        
                        $iCounter      = 1;
			$sSizeFindings = array( );
                        $sSizeSpecs    = array( );
                        
                         
			for($i = 0; $i < $iCount; $i ++)
			{
                                $iId        = $objDb->getField($i, 'id');
				$iSampleNo  = $objDb->getField($i, 'sample_no');
				$sSize      = $objDb->getField($i, 'size');
				$sColor     = $objDb->getField($i, 'color');
                                $iSamplingSize = $objDb->getField($i, 'size_id');
                                $sPointsNature = $objDb->getField($i, 'nature');
                                
?>
                        <tr class="sdRowColor">
                            <td><?=$iCounter++?></td><td><?=$sColor?></td><td align="center"><?=$sSize?></td><td align="center"><?=($sPointsNature == 'C')?'Critical':($sPointsNature=='FB'?"Full Body":'')?></td><td align="center"><?=$iSampleNo?></td>
                            <td align="center">
                                <a href="includes/quonda/edit-measurement-specs.php?QaSampleId=<?=$iId?>&SamplingSize=<?= $iSamplingSize ?>&SizeId=<?=$iSize?>&Size=<?=$sSize?>&AuditId=<?=$Id?>&Color=<?=$sColor?>&Style=<?=$Style?>&SampleNo=<?=$iSampleNo?>&Nature=<?=$sPointsNature?>" class="lightview" rel="iframe" title="Measurement Specs for Inspection#: <?= $Id ?> :: :: width: 900, height: 650"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit Measurement Specs" title="Edit Measurement Specs" /></a>&nbsp;
<?
                            if ($sUserRights['Delete'] == "Y")
                            {
?>
                                <a href="includes/quonda/delete-measurement-specs.php?QaSampleId=<?=$iId?>&AuditId=<?=$Id?>" title="Delete Measurement Specs" onclick="return confirm('Are your sure, you want to delete sample specs for specified color/size and nature?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" style="cursor:pointer;" /></a>&nbsp;
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
                            if($iCount < $TotalGmts)
                            {
?>
                                <div class="qaButtons" style="height: 30px; background: #494949;">
                                    <a style="text-decoration: none; overflow: hidden; display: block; float: right;" class="btnAdd lightview" href="includes/quonda/add-measurement-specs.php?Sizes=<?= $Sizes ?>&Colors=<?=$Colors?>&AuditId=<?=$Id?>&Style=<?=$Style?>" rel="iframe" title="Inspection#: <?= $Id ?> :: :: width: 350, height: 260"></a>
				</div>
<?
                            }
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
?>
				<h2>Quantities & Comments</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
                                    <tr>
                                        <td width="180">Order Qty</td>
                                        <td width="20" align="center">:</td>
                                        <td><?= $iOrderQty ?></td>
                                    </tr>
                                    
                                    <tr>
                                        <td width="180">Ship Qty</td>
                                        <td width="20" align="center">:</td>
                                        <td><input type="text" name="ShipQty" value="<?= $ShipQty ?>" size="10" class="textbox" /></td>
                                    </tr>
                                    
                                    <tr>
                                            <td width="140">Total GMTS Inspected<span class="mandatory">*</span></td>
                                            <td width="20" align="center">:</td>
                                            <td><input type="text" name="TotalGmts" value="<?= $TotalGmts ?>" size="10" class="textbox" /> (Pcs)</td>
                                    </tr>
                                    
                                    <tr>
					<td>Total Cartons Inspected</td>
					<td align="center">:</td>
                                        <td><input type="text" class="textbox" name="InspectedCartons" value="<?=$InspectedCartons?>" /></td>
                                    </tr>
                                    
                                    <tr valign="top">
                                          <td width="140">QA Comments</td>
                                          <td width="20" align="center">:</td>
                                          <td><textarea name="Comments" class="textarea" style="width:98%; height:80px;"><?= $Comments ?></textarea></td>
                                    </tr>
				</table>
<script>
    var i= parseInt(document.getElementById("PCountRows").value)+parseInt(1);
    var j= parseInt(document.getElementById("LCountRows").value)+parseInt(1);
    
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
</script>