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
	if ($ReportId != 8)
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

				  <tr>
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
				<h2 id="DefectDetails" style="margin-bottom:0px;">Defects Details</h2>
<?
	$sSQL = "SELECT * FROM tbl_towel_report_defects WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
?>
				<input type="hidden" id="Count" name="Count" value="<?= $iCount ?>" />

			    <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			      <tr class="sdRowHeader">
					<td width="20" align="center" rowspan="2"><b>#</b></td>
					<td width="42" align="center" rowspan="2"><b>Lot No</b></td>
					<td width="42" align="center" rowspan="2"><b>Roll No</b></td>
					<td width="42" align="center" rowspan="2"><b>Pcs Width</b></td>
					<td width="42" align="center" rowspan="2"><b>Ticket Pcs</b></td>
					<td width="42" align="center" rowspan="2"><b>Actual Pcs</b></td>
					<td width="230" colspan="5" align="center"><b><i>Defects</i></b></td>
                                        <td width="50" align="center" rowspan="2"><b>Defective Pcs</b></td>
                                        <td width="50" align="center" rowspan="2"><b>Delete</b></td>
			      </tr>

				  <tr class="sdRowHeader">
					<td width="50" align="center"><b>Holes</b></td>
					<td width="50" align="center"><b>Slubs</b></td>
					<td width="50" align="center"><b>Stains</b></td>
					<td width="50" align="center"><b>Fly</b></td>
					<td width="50" align="center"><b>Other</b></td>
				   </tr>

			    </table>

			    <div id="QaDefects">
<?
	for($i = 0; $i < $iCount; $i ++)
	{
?>
				<div id="DefectRecord<?= $i ?>" class="defectRecords">
				  <div>
				    <input type="hidden" id="DefectId<?= $i ?>" name="DefectId<?= $i ?>" value="<?= $objDb->getField($i, 'id') ?>" class="defectId" />

					<table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
					  <tr class="sdRowColor" valign="top">
						<td width="20" align="center" class="serial"><?= ($i + 1) ?></td>
						<td width="50" align="center"><input type="text" id="LotNo<?= $i ?>" name="LotNo<?= $i ?>" value="<?= $objDb->getField($i, 'lot_no') ?>" maxlength="3" size="3" class="textbox defectsCount" onchange="$('Sms').value='1';" /></td>
						<td width="50" align="center"><input type="text" id="RollNo<?= $i ?>" name="RollNo<?= $i ?>" value="<?= $objDb->getField($i, 'roll_no') ?>" maxlength="3" size="3" class="textbox defectsCount" onchange="$('Sms').value='1';" /></td>
						<td width="50" align="center"><input type="text" id="Width<?= $i ?>" name="Width<?= $i ?>" value="<?= $objDb->getField($i, 'width') ?>" maxlength="3" size="3" class="textbox defectsCount" onchange="$('Sms').value='1';" /></td>
						<td width="50" align="center"><input type="text" id="TicketMeters<?= $i ?>" name="TicketMeters<?= $i ?>" value="<?= $objDb->getField($i, 'ticket_meters') ?>" maxlength="3" size="3" class="textbox defectsCount" onchange="$('Sms').value='1';" /></td>
						<td width="50" align="center"><input type="text" id="ActualMeters<?= $i ?>" name="ActualMeters<?= $i ?>" value="<?= $objDb->getField($i, 'actual_meters') ?>" maxlength="3" size="3" class="textbox defectsCount" onchange="$('Sms').value='1';" /></td>
						<td width="50" align="center"><input type="text" id="Holes<?= $i ?>" name="Holes<?= $i ?>" value="<?= $objDb->getField($i, 'holes') ?>" maxlength="3" size="3" class="textbox defectsCount" onchange="$('Sms').value='1';" /></td>
						<td width="50" align="center"><input type="text" id="Slubs<?= $i ?>" name="Slubs<?= $i ?>" value="<?= $objDb->getField($i, 'slubs') ?>" maxlength="3" size="3" class="textbox defectsCount" onchange="$('Sms').value='1';" /></td>
						<td width="50" align="center"><input type="text" id="Stains<?= $i ?>" name="Stains<?= $i ?>" value="<?= $objDb->getField($i, 'stains') ?>" maxlength="3" size="3" class="textbox defectsCount" onchange="$('Sms').value='1';" /></td>
						<td width="50" align="center"><input type="text" id="Fly<?= $i ?>" name="Fly<?= $i ?>" value="<?= $objDb->getField($i, 'fly') ?>" maxlength="3" size="3" class="textbox defectsCount" onchange="$('Sms').value='1';" /></td>
						<td width="50" align="center"><input type="text" id="Other<?= $i ?>" name="Other<?= $i ?>" value="<?= $objDb->getField($i, 'other') ?>" maxlength="3" size="3" class="textbox defectsCount" onchange="$('Sms').value='1';" /></td>
                                                <td width="50" align="center"><input type="text" id="AllowedDefects<?= $i ?>" name="AllowedDefects<?= $i ?>" value="<?= $objDb->getField($i, 'allowable_defects') ?>" maxlength="3" size="3" class="textbox defectsCount" onchange="$('Sms').value='1';" /></td>
						<td width="50" align="center"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" style="cursor:pointer;" class="deleteDefect" rel="<?= $i ?>" /></td>
					  </tr>

					  <tr>
					    <td align="center"><img src="images/icons/pictures.gif" width="16" height="16" alt="Defect Picture" title="Defect Picture" /></td>

					    <td colspan="5">
					      <input type="file" id="Picture<?= $i ?>" name="Picture<?= $i ?>" value="" class="textbox defectPicture" size="30" />
<?
                                            $sPicture = $objDb->getField($i, 'picture');

                                            if (!empty($sPicture) && !@file_exists($sPicsDir.$sPicture))                        
                                            {
?>
                                                <span>&bull; (<a href="<?= $sPicsDir ?><?= $sPicture ?>" class="lightview"><?= $sPicture ?></a>)&nbsp;</span>
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
				  <input type="button" value="" class="btnAdd" title="Add Defect" onclick="addTnCDefect( );" />
				  <!--<input type="button" value="" class="btnDelete" title="Delete Defect" onclick="deleteDefect( );" />-->
				</div>

				<br />
				<h2>Status & Comments</h2>

				<table border="0" cellpadding="3" cellspacing="0">
				  <tr valign="top">
					<td>QA Comments</td>
					<td align="center">:</td>
                                        <td><textarea name="Comments" class="textarea" cols="50" rows="8"><?= $Comments ?></textarea></td>
				  </tr>
				</table>
