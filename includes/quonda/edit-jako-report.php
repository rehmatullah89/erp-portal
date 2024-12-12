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
					<td width="130"><b>Audit Code</b></td>
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
					<td>Test Qty</td>
					<td align="center">:</td>
					<td><input type="text" name="TotalGmts" value="<?= $TotalGmts ?>" size="10" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>No of Defects Allowed</td>
				    <td align="center">:</td>
				    <td><input type="text" name="MaxDefects" value="<?= $MaxDefects ?>" size="10" class="textbox" readonly /></td>
				  </tr>
<?
	$sSQL = "SELECT * FROM tbl_jako_qa_reports WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$Eta         = $objDb->getField(0, "eta");
	$We          = $objDb->getField(0, "we");
	$WashOff     = $objDb->getField(0, "wash_off");
	$WashIn      = $objDb->getField(0, "wash_in");
	$MeasureOff  = $objDb->getField(0, "measure_off");
	$MeasureIn   = $objDb->getField(0, "measure_in");
	$PcsMeasured = $objDb->getField(0, "pcs_measured");
?>
				  <tr>
					<td>ETA</td>
					<td align="center">:</td>
					<td><input type="text" name="Eta" value="<?= $Eta ?>" size="10" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>WE</td>
					<td align="center">:</td>
					<td><input type="text" name="We" value="<?= $We ?>" size="10" maxlength="50" class="textbox" /></td>
				  </tr>
				</table>

				<br />
				<b>&nbsp; Packing</b><br />
				<br />

<?
	$sSQL = "SELECT * FROM tbl_jako_packing WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$Carton    = $objDb->getField(0, "carton");
		$Polybag   = $objDb->getField(0, "polybag");
		$Package   = $objDb->getField(0, "package");
		$HangTag   = $objDb->getField(0, "hangtag");
		$SizeLabel = $objDb->getField(0, "size_label");
		$CareLabel = $objDb->getField(0, "care_label");
		$ProdLabel = $objDb->getField(0, "prod_label");
	}

	else if ($AuditStage == "F")
	{
		$Carton    = "Y";
		$Polybag   = "Y";
		$Package   = "Y";
		$HangTag   = "Y";
		$SizeLabel = "Y";
		$CareLabel = "Y";
		$ProdLabel = "Y";
	}
?>

			    <table border="1" bordercolor="#ffffff" cellpadding="4" cellspacing="0" width="100%">
				  <tr class="sdRowHeader">
				    <td width="14%" align="center"><b>Design</b></td>
				    <td width="15%" align="center"><b>Main Fab</b></td>
				    <td width="14%" align="center"><b>Trims</b></td>
				    <td width="14%" align="center"><b>Access</b></td>
				    <td width="14%" align="center"><b>Logos</b></td>
				    <td width="14%" align="center"><b>Colour</b></td>
				    <td width="15%" align="center"><b>TUV Test</b></td>
				  </tr>

				  <tr class="sdRowColor">
				    <td align="center">
				      <select name="Carton">
				        <option value=""></option>
				        <option value="Y"<?= (($Carton == "Y") ? "selected" : "") ?>>Yes</option>
				        <option value="N"<?= (($Carton == "N") ? "selected" : "") ?>>No</option>
				      </select>
				    </td>

				    <td align="center">
				      <select name="Polybag">
				        <option value=""></option>
				        <option value="Y"<?= (($Polybag == "Y") ? "selected" : "") ?>>Yes</option>
				        <option value="N"<?= (($Polybag == "N") ? "selected" : "") ?>>No</option>
				      </select>
				    </td>

				    <td align="center">
				      <select name="Package">
				        <option value=""></option>
				        <option value="Y"<?= (($Package == "Y") ? "selected" : "") ?>>Yes</option>
				        <option value="N"<?= (($Package == "N") ? "selected" : "") ?>>No</option>
				      </select>
				    </td>

				    <td align="center">
				      <select name="HangTag">
				        <option value=""></option>
				        <option value="Y"<?= (($HangTag == "Y") ? "selected" : "") ?>>Yes</option>
				        <option value="N"<?= (($HangTag == "N") ? "selected" : "") ?>>No</option>
				      </select>
				    </td>

				    <td align="center">
				      <select name="SizeLabel">
				        <option value=""></option>
				        <option value="Y"<?= (($SizeLabel == "Y") ? "selected" : "") ?>>Yes</option>
				        <option value="N"<?= (($SizeLabel == "N") ? "selected" : "") ?>>No</option>
				      </select>
				    </td>

				    <td align="center">
				      <select name="CareLabel">
				        <option value=""></option>
				        <option value="Y"<?= (($CareLabel == "Y") ? "selected" : "") ?>>Yes</option>
				        <option value="N"<?= (($CareLabel == "N") ? "selected" : "") ?>>No</option>
				      </select>
				    </td>

				    <td align="center">
				      <select name="ProdLabel">
				        <option value=""></option>
				        <option value="Y"<?= (($ProdLabel == "Y") ? "selected" : "") ?>>Yes</option>
				        <option value="N"<?= (($ProdLabel == "N") ? "selected" : "") ?>>No</option>
				      </select>
				    </td>
				  </tr>
				</table>


				<br />

			    <table border="1" bordercolor="#ffffff" cellpadding="4" cellspacing="0" width="100%">
				  <tr class="sdRowHeader">
				    <td width="4%" align="center"><b>#</b></td>
				    <td width="19%" align="center"><b>Style/Color</b></td>
				    <td width="11%" align="center"><b>Design</b></td>
				    <td width="11%" align="center"><b>Main Fab</b></td>
				    <td width="11%" align="center"><b>Trims</b></td>
				    <td width="11%" align="center"><b>Access</b></td>
				    <td width="11%" align="center"><b>Logos</b></td>
				    <td width="11%" align="center"><b>Colour</b></td>
				    <td width="11%" align="center"><b>TUV Test</b></td>
				  </tr>
<?
	$sSQL = "SELECT * FROM tbl_jako_audits WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < 8; $i ++)
	{
?>

				  <tr class="sdRowColor">
				    <td align="center"><?= ($i + 1) ?></td>
				    <td align="center"><input type="text" name="StyleColor<?= $i ?>" id="StyleColor<?= $i ?>" value="<?= $objDb->getField($i,  'style_color') ?>" maxlength="50" size="20" class="textbox" /></td>

				    <td align="center">
				      <select name="Design<?= $i ?>">
				        <option value=""></option>
				        <option value="Y"<?= (($objDb->getField($i,  'design') == "Y") ? "selected" : "") ?>>Yes</option>
				        <option value="N"<?= (($objDb->getField($i,  'design') == "N") ? "selected" : "") ?>>No</option>
				      </select>
				    </td>

				    <td align="center">
				      <select name="MainFab<?= $i ?>">
				        <option value=""></option>
				        <option value="Y"<?= (($objDb->getField($i,  'main_fab') == "Y") ? "selected" : "") ?>>Yes</option>
				        <option value="N"<?= (($objDb->getField($i,  'main_fab') == "N") ? "selected" : "") ?>>No</option>
				      </select>
				    </td>

				    <td align="center">
				      <select name="Trims<?= $i ?>">
				        <option value=""></option>
				        <option value="Y"<?= (($objDb->getField($i,  'trims') == "Y") ? "selected" : "") ?>>Yes</option>
				        <option value="N"<?= (($objDb->getField($i,  'trims') == "N") ? "selected" : "") ?>>No</option>
				      </select>
				    </td>

				    <td align="center">
				      <select name="Access<?= $i ?>">
				        <option value=""></option>
				        <option value="Y"<?= (($objDb->getField($i,  'access') == "Y") ? "selected" : "") ?>>Yes</option>
				        <option value="N"<?= (($objDb->getField($i,  'access') == "N") ? "selected" : "") ?>>No</option>
				      </select>
				    </td>

				    <td align="center">
				      <select name="Logos<?= $i ?>">
				        <option value=""></option>
				        <option value="Y"<?= (($objDb->getField($i,  'logos') == "Y") ? "selected" : "") ?>>Yes</option>
				        <option value="N"<?= (($objDb->getField($i,  'logos') == "N") ? "selected" : "") ?>>No</option>
				      </select>
				    </td>

				    <td align="center">
				      <select name="Color<?= $i ?>">
				        <option value=""></option>
				        <option value="Y"<?= (($objDb->getField($i,  'color') == "Y") ? "selected" : "") ?>>Yes</option>
				        <option value="N"<?= (($objDb->getField($i,  'color') == "N") ? "selected" : "") ?>>No</option>
				      </select>
				    </td>

				    <td align="center">
				      <select name="TuvTest<?= $i ?>">
				        <option value=""></option>
				        <option value="Y"<?= (($objDb->getField($i,  'tuv_test') == "Y") ? "selected" : "") ?>>Yes</option>
				        <option value="N"<?= (($objDb->getField($i,  'tuv_test') == "N") ? "selected" : "") ?>>No</option>
				      </select>
				    </td>
				  </tr>
<?
	}
?>
				</table>

				<br />
				<h2 id="DefectDetails" style="margin-bottom:0px;">Defects Details</h2>
<?
	$sSQL = "SELECT * FROM tbl_qa_report_defects WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	$sSQL = "SELECT DISTINCT(type_id), (SELECT type FROM tbl_defect_types WHERE id=tbl_defect_codes.type_id) FROM tbl_defect_codes WHERE report_id='$ReportId' ORDER BY type_id";
	$objDb2->query($sSQL);

	$iCount2 = $objDb2->getCount( );


	if (strtotime($AuditDate) <= strtotime("2015-06-18"))
		$sSQL = "SELECT * FROM tbl_defect_areas ORDER BY area";

	else
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
?>

				<div id="DefectRecord<?= $i ?>" class="defectRecords">
				  <div>
				    <input type="hidden" id="DefectId<?= $i ?>" name="DefectId<?= $i ?>" value="<?= $objDb->getField($i, 'id') ?>" class="defectId" />

					<table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
					  <tr class="sdRowColor" valign="top">
						<td width="50" align="center" class="serial"><?= ($i + 1) ?></td>

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

                                                <td width="100" align="center"><input type="text" id="Defects<?= $i ?>" name="Defects<?= $i ?>" value="<?= $objDb->getField($i, 'defects') ?>" maxlength="3" size="3" class="textbox defectsCount" onchange="$('Sms').value='1';" required="" /></td>

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
                                                    <select id="Nature<?= $i ?>" name="Nature<?= $i ?>" class="defectNature" onchange="$('Sms').value='1';" required="">
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
		
		if ($sPicture != "" && @file_exists($sQuondaDir.$sPicture))
		{
?>
						  <span>&bull; (<a href="<?= $sPicsDir ?><?= $sPicture ?>" class="lightview"><?= $sPicture ?></a>)&nbsp;</span>
						  <input type="hidden" name="PrevPicture<?= $i ?>" value="<?= $sPicture ?>">
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

				<br />
				<h2>Wash Test</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="120">Off Tolerance</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="WashOff" value="<?= $WashOff ?>" size="10" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>In Tolerance</td>
					<td align="center">:</td>
					<td><input type="text" name="WashIn" value="<?= $WashIn ?>" size="10" maxlength="10" class="textbox" /></td>
				  </tr>
				</table>

				<br />
				<h2>Measure</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="120">Off Tolerance</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="MeasureOff" value="<?= $MeasureOff ?>" size="10" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>In Tolerance</td>
					<td align="center">:</td>
					<td><input type="text" name="MeasureIn" value="<?= $MeasureIn ?>" size="10" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>No of Pcs. Measured</td>
					<td align="center">:</td>
					<td><input type="text" name="PcsMeasured" value="<?= $PcsMeasured ?>" size="10" maxlength="10" class="textbox" /></td>
				  </tr>
				</table>

				<br />
				<h2>Status & Comments</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="100">Overall Grade<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="AuditResult" onchange="$('Sms').value='1';">
						<option value=""></option>
						<option value="A">A</option>
						<option value="B">B</option>
						<option value="C">C</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.AuditResult.value = "<?= $AuditResult ?>";
					  -->
					  </script>
					</td>
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

				  <tr valign="top">
					<td>QA Comments</td>
					<td align="center">:</td>
					<td><textarea name="Comments" class="textarea" style="width:98%; height:80px;"><?= $Comments ?></textarea></td>
				  </tr>
				</table>

				<br />
