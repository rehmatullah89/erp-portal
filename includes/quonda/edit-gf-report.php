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
					<td width="150"><b>Audit Code</b></td>
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

				  <tr>
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

				  <tr valign="top">
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
					<td>Audit Result<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="AuditResult" onchange="$('Sms').value='1';">
						<option value=""></option>
						<option value="P">Pass</option>
						<option value="F">Fail</option>
						<option value="H">Hold</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.AuditResult.value = "<?= $AuditResult ?>";
					  -->
					  </script>
					</td>
				  </tr>

				  <tr>
					<td>Dye Lot #</td>
					<td align="center">:</td>
					<td><input type="text" name="DyeLotNo" value="<?= $DyeLotNo ?>" size="20" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Acceptable Points Woven</td>
					<td align="center">:</td>
					<td><input type="text" name="AcceptablePointsWoven" value="<?= $AcceptablePointsWoven ?>" size="20" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Inspection Type<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="InspectionType">
						<option value="G">Greige</option>
						<option value="D">Dyed</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.InspectionType.value = "<?= $InspectionType ?>";
					  -->
					  </script>
					</td>
				  </tr>

				  <tr>
					<td>Cutable Fabric Width</td>
					<td align="center">:</td>
					<td><input type="text" name="CutableFabricWidth" value="<?= $CutableFabricWidth ?>" size="20" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Stock Status</td>
					<td align="center">:</td>
					<td><input type="text" name="StockStatus" value="<?= $StockStatus ?>" size="20" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Rolls Inspected</td>
					<td align="center">:</td>
					<td><input type="text" name="RollsInspected" value="<?= $RollsInspected ?>" size="20" maxlength="5" class="textbox" /></td>
				  </tr>
				</table>

				<br />
				<h2 style="margin-bottom:0px;">Rolls / Panel Information</h2>

			    <table border="1" bordercolor="#ffffff" cellpadding="4" cellspacing="0" width="100%">
				  <tr class="sdRowHeader">
				    <td width="20"><b>#</b></td>
				    <td><b>Roll No</b></td>
				    <td width="60"><b>Ref-1</b></td>
				    <td width="60"><b>Given</b></td>
				    <td width="100"><b>Actual</b></td>
				    <td width="60"><b>Ref-2</b></td>
				    <td width="60"><b>Given</b></td>
				    <td width="100"><b>Actual</b></td>
				    <td width="60"><b>Ref-3</b></td>
				    <td width="60"><b>Given</b></td>
				    <td width="80"><b>Actual</b></td>
				  </tr>
<?
	$sSQL = "SELECT * FROM tbl_gf_rolls_info WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < 5; $i ++)
	{
?>

				  <input type="hidden" name="RollId_<?= $i ?>" value="<?= $objDb->getField($i,  'id') ?>" />
				  <tr class="sdRowColor">
				    <td><?= ($i + 1) ?></td>
				    <td><input type="text" name="RollNo_<?= $i ?>" value="<?= $objDb->getField($i,  'roll_no') ?>" maxlength="50" size="18" class="textbox" /></td>
				    <td><input type="text" name="Ref_1_<?= $i ?>" value="<?= $objDb->getField($i,  'ref_1') ?>" maxlength="50" size="5" class="textbox" /></td>
				    <td><input type="text" name="Given_1_<?= $i ?>" value="<?= $objDb->getField($i,  'given_1') ?>" maxlength="8" size="5" class="textbox" /></td>
				    <td><input type="text" name="Actual_1_<?= $i ?>" value="<?= $objDb->getField($i,  'actual_1') ?>" maxlength="8" size="5" class="textbox" /></td>
				    <td><input type="text" name="Ref_2_<?= $i ?>" value="<?= $objDb->getField($i,  'ref_2') ?>" maxlength="50" size="5" class="textbox" /></td>
				    <td><input type="text" name="Given_2_<?= $i ?>" value="<?= $objDb->getField($i,  'given_2') ?>" maxlength="8" size="5" class="textbox" /></td>
				    <td><input type="text" name="Actual_2_<?= $i ?>" value="<?= $objDb->getField($i,  'actual_2') ?>" maxlength="8" size="5" class="textbox" /></td>
				    <td><input type="text" name="Ref_3_<?= $i ?>" value="<?= $objDb->getField($i,  'ref_3') ?>" maxlength="50" size="5" class="textbox" /></td>
				    <td><input type="text" name="Given_3_<?= $i ?>" value="<?= $objDb->getField($i,  'given_3') ?>" maxlength="8" size="5" class="textbox" /></td>
				    <td><input type="text" name="Actual_3_<?= $i ?>" value="<?= $objDb->getField($i,  'actual_3') ?>" maxlength="8" size="5" class="textbox" /></td>
				  </tr>
<?
	}
?>
				</table>

				<br />
				<h2 id="DefectDetails" style="margin-bottom:0px;">Defects Details</h2>
<?
	$iDefects = 0;

	$sSQL = "SELECT * FROM tbl_gf_report_defects WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	$sSQL = "SELECT DISTINCT(type_id), (SELECT type FROM tbl_defect_types WHERE id=tbl_defect_codes.type_id) FROM tbl_defect_codes WHERE report_id='$ReportId' ORDER BY type_id";
	$objDb2->query($sSQL);

	$iCount2 = $objDb2->getCount( );
?>
				<input type="hidden" id="Count" name="Count" value="<?= $iCount ?>" />

			    <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			      <tr class="sdRowHeader">
					<td width="40" align="center"><b>#</b></td>
					<td width="80" align="center"><b>Roll #</b></td>
					<td width="80" align="center"><b>Panel #</b></td>
					<td><b>Code - Check Points</b></td>
					<td width="80" align="center"><b>Grade</b></td>
					<td width="100" align="center"><b>Defects</b></td>
					<td width="50" align="center"><b>Delete</b></td>
			      </tr>
			    </table>

			    <div id="QaDefects">
<?
	$sDefectCode = "";
	$sDefectArea = "";

	for($i = 0; $i < $iCount; $i ++)
	{
		$iDefects += $objDb->getField($i, 'defects');
		$iRoll     = $objDb->getField($i, 'roll');
		$iPanel    = $objDb->getField($i, 'panel');
?>

				<div id="DefectRecord<?= $i ?>" class="defectRecords">
				  <div>
				    <input type="hidden" id="DefectId<?= $i ?>" name="DefectId<?= $i ?>" value="<?= $objDb->getField($i, 'id') ?>" class="defectId" />

					<table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
					  <tr class="sdRowColor" valign="top">
						<td width="40" align="center" class="serial"><?= ($i + 1) ?></td>

						<td width="80" align="center">
						  <select id="Roll<?= $i ?>" name="Roll<?= $i ?>" class="defectRoll" onchange="$('Sms').value='1';">
							<option value=""></option>
							<option value="1">01</option>
							<option value="2">02</option>
							<option value="3">03</option>
							<option value="4">04</option>
							<option value="5">05</option>
						  </select>

						  <script type="text/javascript">
						  <!--
							document.frmData.Roll<?= $i ?>.value = "<?= $iRoll ?>";
						  -->
						  </script>
						</td>

						<td width="80" align="center">
						  <select id="Panel<?= $i ?>" name="Panel<?= $i ?>" class="defectPanel" onchange="$('Sms').value='1';">
							<option value=""></option>
							<option value="1">01</option>
							<option value="2">02</option>
							<option value="3">03</option>
							<option value="4">04</option>
							<option value="5">05</option>
						  </select>

						  <script type="text/javascript">
						  <!--
							document.frmData.Panel<?= $i ?>.value = "<?= $iPanel ?>";
						  -->
						  </script>
						</td>

						<td>
                                                    <select id="Code<?= $i ?>" name="Code<?= $i ?>" class="defectCode" onchange="$('Sms').value='1';" required="">
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

						<td width="80" align="center">
						  <select id="Grade<?= $i ?>" name="Grade<?= $i ?>" class="defectGrade" onchange="$('Sms').value='1';">
							<option value=""></option>
		        			<option value="1">1</option>
		        			<option value="2">2</option>
		        			<option value="3">3</option>
		        			<option value="4">4</option>
						  </select>

						  <script type="text/javascript">
						  <!--
							document.frmData.Grade<?= $i ?>.value = "<?= $objDb->getField($i, 'grade') ?>";
						  -->
						  </script>
						</td>

                                                <td width="100" align="center"><input type="text" id="Defects<?= $i ?>" name="Defects<?= $i ?>" value="<?= $objDb->getField($i, 'defects') ?>" maxlength="3" size="5" class="textbox defectsCount" onchange="$('Sms').value='1';" required=""/></td>
						<td width="50" align="center"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" style="cursor:pointer;" class="deleteDefect" rel="<?= $i ?>" /></td>
					  </tr>

					  <tr>
					    <td align="center"><img src="images/icons/pictures.gif" width="16" height="16" alt="Defect Picture" title="Defect Picture" /></td>

					    <td colspan="6">
					      <input type="file" id="Picture<?= $i ?>" name="Picture<?= $i ?>" value="" class="textbox defectPicture" size="30" />
<?
		$sPicture = $objDb->getField($i, 'picture');

		if ($sPicture != "" && @file_exists($sQuondaDir.$sPicture))                        
		{
?>
							<span>&bull; (<a href="<?= $sPicsDir ?><?= $sPicture ?>" class="lightview"><?= $sPicture ?></a>)&nbsp;</span>
<?
		}

		else if ($sPicture != "")
		{
?>
						  <span>&bull; (<?= $sPicture ?>)&nbsp;</span>
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
				  <!--<input type="button" value="" class="btnDelete" title="Delete Defect" onclick="deleteDefect( );" />-->
				</div>

				<h2>Fabric Inspection Standard Check-List</h2>

<?
	$sSQL = "SELECT * FROM tbl_gf_inspection_checklist WHERE audit_id='$Id'";
	$objDb->query($sSQL);
?>
			    <table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
				    <td width="140">Color Match to Standard</td>
				    <td width="20" align="center">:</td>

				    <td width="140">
				      <select name="ColorMatch">
				        <option value=""></option>
				        <option value="A">Accept</option>
				        <option value="R">Reject</option>
				        <option value="N">Not Applicable</option>
				      </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.ColorMatch.value = "<?= $objDb->getField(0, 'color_match') ?>";
					  -->
					  </script>
				    </td>

				    <td width="50">Remarks</td>
				    <td width="20" align="center">:</td>
				    <td><input type="text" name="ColorMatchRemarks" value="<?= $objDb->getField(0, 'color_match_remarks') ?>" size="70" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Shading</td>
				    <td align="center">:</td>

				    <td>
				      <select name="Shading">
				        <option value=""></option>
				        <option value="A">Accept</option>
				        <option value="R">Reject</option>
				        <option value="N">Not Applicable</option>
				      </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.Shading.value = "<?= $objDb->getField(0, 'shading') ?>";
					  -->
					  </script>
				    </td>

				    <td>Remarks</td>
				    <td align="center">:</td>
				    <td><input type="text" name="ShadingRemarks" value="<?= $objDb->getField(0, 'shading_remarks') ?>" size="70" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Hand Feel</td>
				    <td align="center">:</td>

				    <td>
				      <select name="HandFeel">
				        <option value=""></option>
				        <option value="A">Accept</option>
				        <option value="R">Reject</option>
				        <option value="N">Not Applicable</option>
				      </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.HandFeel.value = "<?= $objDb->getField(0, 'hand_feel') ?>";
					  -->
					  </script>
				    </td>

				    <td>Remarks</td>
				    <td align="center">:</td>
				    <td><input type="text" name="HandFeelRemarks" value="<?= $objDb->getField(0, 'hand_feel_remarks') ?>" size="70" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Lab Testing</td>
				    <td align="center">:</td>

				    <td>
				      <select name="LabTesting">
				        <option value=""></option>
				        <option value="A">Accept</option>
				        <option value="R">Reject</option>
				        <option value="P">Pending</option>
				      </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.LabTesting.value = "<?= $objDb->getField(0, 'lab_testing') ?>";
					  -->
					  </script>
				    </td>

				    <td>Remarks</td>
				    <td align="center">:</td>
				    <td><input type="text" name="LabTestingRemarks" value="<?= $objDb->getField(0, 'lab_testing_remarks') ?>" size="70" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Fabric Width</td>
					<td align="center">:</td>
					<td colspan="4"><input type="text" name="FabricWidth" value="<?= $FabricWidth ?>" size="7" maxlength="5" class="textbox" /></td>
				  </tr>
				</table>

				<br />
				<h2>Quantities</h2>

<?
	$sSQL = "SELECT quantity FROM tbl_po WHERE id='$PO'";
	$objDb->query($sSQL);

	$iOrderQty = $objDb->getField(0, 0);

	if (count($AdditionalPos) > 0)
	{
		$AdditionalPos = @implode(",", $AdditionalPos);


		$sSQL = "SELECT SUM(quantity) FROM tbl_po WHERE id IN ($AdditionalPos)";
		$objDb->query($sSQL);

		$iOrderQty += $objDb->getField(0, 0);
	}
?>
				<table border="0" cellpadding="3" cellspacing="0" width="100%">
			      <tr>
				    <td width="140">Order Qty</td>
				    <td width="20" align="center">:</td>
				    <td><?= $iOrderQty ?></td>
 			      </tr>

			      <tr>
				    <td>Ship Qty</td>
				    <td align="center">:</td>
				    <td><input type="text" name="ShipQty" value="<?= $ShipQty ?>" size="10" maxlength="8" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Re-Screen Qty</td>
				    <td align="center">:</td>
				    <td><input type="text" name="ReScreenQty" value="<?= $ReScreenQty ?>" size="10" maxlength="8" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>No of Rolls</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Rolls" value="<?= $Rolls ?>" size="10" maxlength="5" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Deviation</td>
				    <td align="center">:</td>
				    <td><?= @round(( ($ShipQty / $iOrderQty) * 100), 2) ?>%</td>
			      </tr>
				</table>

				<br />
				<h2>Status & Comments</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr valign="top">
					<td width="140">QA Comments</td>
					<td width="20" align="center">:</td>
					<td><textarea name="Comments" class="textarea" style="width:98%; height:80px;"><?= $Comments ?></textarea></td>
				  </tr>
				</table>

				<br />
