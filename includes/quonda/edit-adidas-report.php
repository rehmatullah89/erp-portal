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

	$sSQL = "SELECT * FROM tbl_ar_inspection_checklist WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$ModelName          = $objDb->getField(0, "model_name");
		$WorkingNo          = $objDb->getField(0, "working_no");
		$FabricApproval     = $objDb->getField(0, "fabric_approval");
		$CounterSampleAppr  = $objDb->getField(0, "counter_sample_appr");
		$GarmentWashingTest = $objDb->getField(0, "garment_washing_test");
		$SealingSampleAppr  = $objDb->getField(0, "sealing_sample_appr");
		$MetalDetection     = $objDb->getField(0, "metal_detection");
		$ColorShade         = $objDb->getField(0, "color_shade");
		$Appearance         = $objDb->getField(0, "appearance");
		$Handfeel           = $objDb->getField(0, "handfeel");
		$Printing           = $objDb->getField(0, "printing");
		$Embridery          = $objDb->getField(0, "embridery");
		$FibreContent       = $objDb->getField(0, "fibre_content");
		$CountryOfOrigin    = $objDb->getField(0, "country_of_origin");
		$CareInstruction    = $objDb->getField(0, "care_instruction");
		$SizeKey            = $objDb->getField(0, "size_key");
		$AdiComp            = $objDb->getField(0, "adi_comp");
		$ColourSizeQty      = $objDb->getField(0, "colour_size_qty");
		$Polybag            = $objDb->getField(0, "polybag");
		$Hangtag            = $objDb->getField(0, "hangtag");
		$OclUpc             = $objDb->getField(0, "ocl_upc");
		$DecorativeLabel    = $objDb->getField(0, "decorative_label");
		$CareLabel          = $objDb->getField(0, "care_label");
		$SecurityLabel      = $objDb->getField(0, "security_label");
		$AdditionalLabel    = $objDb->getField(0, "additional_label");
		$PackingMode        = $objDb->getField(0, "packing_mode");
		$CartonNoChecked    = $objDb->getField(0, "carton_no_checked");
	}
?>
				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="90"><b>Audit Code</b></td>
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
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.AuditResult.value = "<?= $AuditResult ?>";
					  -->
					  </script>
					</td>
				  </tr>

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

				  <tr>
					<td>Model Name</td>
					<td align="center">:</td>
					<td><input type="text" name="ModelName" value="<?= $ModelName ?>" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Article No</td>
					<td align="center">:</td>
					<td><input type="text" name="Colors" value="<?= $Colors ?>" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Working No</td>
					<td align="center">:</td>
					<td><input type="text" name="WorkingNo" value="<?= $WorkingNo ?>" maxlength="50" class="textbox" /></td>
				  </tr>
				</table>

				<h2 style="margin:5px 0px 5px 0px;">&nbsp;</h2>

			    <table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="75">Sample Plan</td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="TotalGmts">
					    <option value="2"<?= (($TotalGmts == 2) ? ' selected' : '') ?>>2</option>
					    <option value="3"<?= (($TotalGmts == 3) ? ' selected' : '') ?>>3</option>
					    <option value="5"<?= (($TotalGmts == 5) ? ' selected' : '') ?>>5</option>
					    <option value="8"<?= (($TotalGmts == 8) ? ' selected' : '') ?>>8</option>
					    <option value="13"<?= (($TotalGmts == 13) ? ' selected' : '') ?>>13</option>
					    <option value="20"<?= (($TotalGmts == 20) ? ' selected' : '') ?>>20</option>
					    <option value="32"<?= (($TotalGmts == 32) ? ' selected' : '') ?>>32</option>
					    <option value="50"<?= (($TotalGmts == 50) ? ' selected' : '') ?>>50</option>
					    <option value="80"<?= (($TotalGmts == 80) ? ' selected' : '') ?>>80</option>
					    <option value="125"<?= (($TotalGmts == 125) ? ' selected' : '') ?>>125</option>
					    <option value="200"<?= (($TotalGmts == 200) ? ' selected' : '') ?>>200</option>
					    <option value="315"<?= (($TotalGmts == 315) ? ' selected' : '') ?>>315</option>
					    <option value="500"<?= (($TotalGmts == 500) ? ' selected' : '') ?>>500</option>
					  </select>
					</td>
				  </tr>
				</table>

				<br />

			    <table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
				    <td width="25"><input type="checkbox" name="FabricApproval" value="Y" <?= (($FabricApproval == "Y") ? "checked" : "") ?> /></td>
				    <td>Fabric Approval</td>
				    <td width="25"><input type="checkbox" name="SealingSampleAppr" value="Y" <?= (($SealingSampleAppr == "Y") ? "checked" : "") ?> /></td>
				    <td>Counter Sample Appr.</td>
				    <td width="25"><input type="checkbox" name="GarmentWashingTest" value="Y" <?= (($GarmentWashingTest == "Y") ? "checked" : "") ?> /></td>
				    <td>Product Wash Test</td>
				  </tr>

				  <tr>
				    <td><input type="checkbox" name="MetalDetection" value="Y" <?= (($MetalDetection == "Y") ? "checked" : "") ?> /></td>
				    <td colspan="5">Metal Detection</td>
				  </tr>
			    </table>

				<br />

				<table border="1" bordercolor="#ffffff" cellpadding="0" cellspacing="0" width="100%">
				  <tr valign="top">
					<td width="33%">
					  <h2>Fabric/Artwork Check List</h2>

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="25"><input type="checkbox" name="ColorShade" value="Y" <?= (($ColorShade == "Y") ? "checked" : "") ?> /></td>
						  <td>Color/Shade</td>
					    </tr>

					    <tr>
						  <td><input type="checkbox" name="Appearance" value="Y" <?= (($Appearance == "Y") ? "checked" : "") ?> /></td>
						  <td>Appearance</td>
					    </tr>

					    <tr>
						  <td><input type="checkbox" name="Handfeel" value="Y" <?= (($Handfeel == "Y") ? "checked" : "") ?> /></td>
						  <td>Handfeel</td>
					    </tr>
					  </table>

					</td>


					<td width="34%">
					  <h2>Label Check List</h2>

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="25"><input type="checkbox" name="FibreContent" value="Y" <?= (($FibreContent == "Y") ? "checked" : "") ?> /></td>
						  <td>Fibre Content</td>
					    </tr>

					    <tr>
						  <td><input type="checkbox" name="CountryOfOrigin" value="Y" <?= (($CountryOfOrigin == "Y") ? "checked" : "") ?> /></td>
						  <td>Country of Origin</td>
					    </tr>

					    <tr>
						  <td><input type="checkbox" name="CareInstruction" value="Y" <?= (($CareInstruction == "Y") ? "checked" : "") ?> /></td>
						  <td>Care Instruction</td>
					    </tr>

					    <tr>
						  <td><input type="checkbox" name="SizeKey" value="Y" <?= (($SizeKey == "Y") ? "checked" : "") ?> /></td>
						  <td>Size Key</td>
					    </tr>

					    <tr>
						  <td><input type="checkbox" name="DecorativeLabel" value="Y" <?= (($DecorativeLabel == "Y") ? "checked" : "") ?> /></td>
						  <td>Decorative Label</td>
					    </tr>

					    <tr>
						  <td><input type="checkbox" name="CareLabel" value="Y" <?= (($CareLabel == "Y") ? "checked" : "") ?> /></td>
						  <td>Care Label</td>
					    </tr>

					    <tr>
						  <td><input type="checkbox" name="SecurityLabel" value="Y" <?= (($SecurityLabel == "Y") ? "checked" : "") ?> /></td>
						  <td>Security Label</td>
					    </tr>

					    <tr>
						  <td><input type="checkbox" name="AdditionalLabel" value="Y" <?= (($AdditionalLabel == "Y") ? "checked" : "") ?> /></td>
						  <td>Additional Label</td>
					    </tr>
					  </table>
					</td>


					<td width="33%">
					  <h2>Packing Check List</h2>

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="25"><input type="checkbox" name="OclUpc" value="Y" <?= (($OclUpc == "Y") ? "checked" : "") ?> /></td>
						  <td>Outer Carton Labels</td>
					    </tr>

					    <tr>
						  <td><input type="checkbox" name="PackingMode" value="Y" <?= (($PackingMode == "Y") ? "checked" : "") ?> /></td>
						  <td>Packing Mode</td>
					    </tr>

					    <tr>
						  <td><input type="checkbox" name="Polybag" value="Y" <?= (($Polybag == "Y") ? "checked" : "") ?> /></td>
						  <td>Polybag/Polybag Sticker</td>
					    </tr>

					    <tr>
						  <td><input type="checkbox" name="Hangtag" value="Y" <?= (($Hangtag == "Y") ? "checked" : "") ?> /></td>
						  <td>Hangtag</td>
					    </tr>
					  </table>
					</td>
				  </tr>
				</table>

				<br />
				<h2 id="DefectDetails" style="margin-bottom:0px;">Defects Details</h2>
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
			$sSQL = "SELECT id, buyer_code, defect FROM tbl_defect_codes WHERE report_id='$ReportId' AND type_id='$iTypeId' ORDER BY code";
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
				  <!--<input type="button" value="" class="btnDelete" title="Delete Defect" onclick="deleteDefect( );" />-->
				</div>

				<h2>Beautiful Audit Criteria Breakdown</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="99%" align="center">
				  <tr>
					<td width="150">No of Beautiful Products</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="BeautifulProducts" value="<?= $BeautifulProducts ?>" size="8" maxlength="10" class="textbox" /></td>
				  </tr>
				</table>

				<br />
				<b>&nbsp; Number of NB Product per Criteria</b><br />

				<div style=" padding:10px;">
				  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr bgcolor="#eeeeee">
<?
	$sSQL = "SELECT * FROM tbl_ar_beautiful_products WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	for ($i = 1; $i <= 9; $i ++)
	{
?>
					  <td width="11.1%" align="center"><b>C<?= $i ?></b></td>
<?
	}
?>
				    </tr>

				    <tr bgcolor="#f6f6f6">
<?
	for ($i = 1; $i <= 9; $i ++)
	{
?>
					  <td align="center"><input type="text" name="C<?= $i ?>" value="<?= $objDb->getField(0, "c{$i}") ?>" size="5" maxlength="5" class="textbox" /></td>
<?
	}
?>
				    </tr>
				  </table>
				</div>

				<br />
				<h2>&nbsp;</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="180">Pieces Available for Inspection</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="ShipQty" value="<?= $ShipQty ?>" size="8" maxlength="10" class="textbox" /></td>
				  </tr>

			      <tr>
				    <td>Re-Screen Qty</td>
				    <td align="center">:</td>
				    <td><input type="text" name="ReScreenQty" value="<?= $ReScreenQty ?>" size="8" maxlength="10" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Knitted (%)</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Knitted" value="<?= $Knitted ?>" size="8" maxlength="10" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Dyed (%)</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Dyed" value="<?= $Dyed ?>" size="8" maxlength="10" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Cutting</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Cutting" value="<?= $Cutting ?>" size="8" maxlength="10" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Sewing</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Sewing" value="<?= $Sewing ?>" size="8" maxlength="10" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Finishing</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Finishing" value="<?= $Finishing ?>" size="8" maxlength="10" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Packing</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Packing" value="<?= $Packing ?>" size="8" maxlength="10" class="textbox" /></td>
			      </tr>

				  <tr>
				    <td>Final Audit Date</td>
				    <td align="center">:</td>

				    <td>

					  <table border="0" cellpadding="0" cellspacing="0" width="116">
					    <tr>
					  	  <td width="82"><input type="text" name="FinalAuditDate" id="FinalAuditDate" value="<?= (($FinalAuditDate != "0000-00-00") ? $FinalAuditDate : "") ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FinalAuditDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FinalAuditDate'), 'yyyy-mm-dd', this);" /></td>
					    </tr>
					  </table>

				    </td>
				  </tr>

				  <tr>
					<td>Carton No. Checked</td>
					<td align="center">:</td>
					<td><input type="text" name="CartonNoChecked" value="<?= $CartonNoChecked ?>" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr valign="top">
					<td>Comments</td>
					<td align="center">:</td>
					<td><textarea name="Comments" class="textarea" style="width:98%; height:80px;"><?= $Comments ?></textarea></td>
				  </tr>
				</table>

				<br />
