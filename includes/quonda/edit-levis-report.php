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
               if($AuditTypeId == 2 && $TotalGmts == 32)
                    $AuditStage = "V";
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
                                    <td>Category</td>
                                    <td align="center">:</td>
                                    <td><?= getDbValue("category", "tbl_po", "id = '$PO'") ?></td>
                                  </tr>
                                  
                                  <tr>
                                    <td>Product Code</td>
                                    <td align="center">:</td>
                                    <td><?= getDbValue("GROUP_CONCAT(product_code SEPARATOR ',')", "tbl_po", "id IN ($sSelectedPos)") ?></td>
                                  </tr>
                          
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
                                    <td>Audit Type</td>
                                    <td align="center">:</td>
                                   <td>
                                        <select name="AuditTypeId">
<?      
                                    $sAuditTypes = getList("tbl_audit_types", "id", "type");    
                                    foreach($sAuditTypes as $sKey => $sValue)
                                    {
?>
                                    <option value="<?= $sKey ?>"<?= (($sKey == $AuditTypeId) ? " selected" : "") ?>><?= $sValue ?></option>
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
						<option value="N">Fail-NV</option>
                                                <option value="E">Exception</option>
                                                <option value="R">Rescreen</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.AuditResult.value = "<?= $AuditResult ?>";
					  -->
					  </script>
					</td>
				  </tr>
<input type="hidden" name="AuditType" id="AuditType" value="B" />

				  <tr>
					<td>Colors</td>
					<td align="center">:</td>
					<td><input type="text" name="Colors" value="<?= $Colors ?>" size="30" class="textbox" /></td>
				  </tr>
				</table>

				<br />
				<h2 id="SizeRequirements"><?= (($ReportId == 8) ? 'Range' : 'Audit Sizes') ?></h2>

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
		for ($j = 0; $j < 10; $j ++, $i ++)
		{
			if ($i < $iCount)
			{
				$sKey   = $objDb->getField($i, 0);
				$sValue = $objDb->getField($i, 1);
?>
					    <td width="25"><input type="checkbox" name="Sizes[]" value="<?= $sKey ?>" <?= ((@in_array($sKey, $iSizes)) ? "checked" : "") ?> /></td>
					    <td><?= $sValue ?></td>
<?
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
	$iDefects = 0;
        
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
                                        <td width="100" align="center"><b>Sample#</b></td>
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
		$sPicture = $objDb->getField($i, 'picture');
		
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

						<td width="100" align="center"><input type="text" id="Defects<?= $i ?>" name="Defects<?= $i ?>" value="<?= $objDb->getField($i, 'defects') ?>" maxlength="3" size="3" class="textbox defectsCount" onchange="$('Sms').value='1';" required/></td>
                                                <td width="100" align="center"><input type="text" id="SampleNo<?= $i ?>" name="SampleNo<?= $i ?>" value="<?= $objDb->getField($i, 'sample_no') ?>" maxlength="3" size="3" class="textbox sampleNos" onblur="getMaxAllowed(<?=$i?>,<?=$TotalGmts?>);" onchange="$('Sms').value='1';" /></td>
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
						  <select id="Nature<?= $i ?>" name="Nature<?= $i ?>" class="defectNature" onchange="$('Sms').value='1';" required>
		        			<option value=""></option>
    		        			<option value="2">Critical</option>
		        			<option value="1">Major</option>
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
					      <input type="file" id="Picture<?= $i ?>" name="Picture<?= $i ?>" value="" class="textbox defectPicture" size="30"/>
<?
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

	if ($GmtsDefective == 0)
		$GmtsDefective = $iDefects;
        
        
        
        $sSQL = "SELECT * FROM tbl_qa_levis_reports WHERE audit_id = '$Id'";
		$objDb->query($sSQL);

        $iSafety            = $objDb->getField(0, "safety");
        $iCriticalFailure   = $objDb->getField(0, "critical_failure");
        $iSewing            = $objDb->getField(0, "sewing");
        $iAppearance        = $objDb->getField(0, "appearance");
        $iMeasurements      = $objDb->getField(0, "measurements");
        $iSundriseMissing   = $objDb->getField(0, "sundries_missing");
        $iSundriesBroken    = $objDb->getField(0, "sundries_broken");
        $iAccuracy          = $objDb->getField(0, "accuracy");
        $iPhysicals         = $objDb->getField(0, "physicals");
        $iOther             = $objDb->getField(0, "other");
        $iCartonsSampled    = $objDb->getField(0, "cartons_sampled");
        $iCartonsInError    = $objDb->getField(0, "cartons_in_error");
        $iUnitsSampled      = $objDb->getField(0, "units_sampled");
        $iUnitsInErrors     = $objDb->getField(0, "units_in_errors");
        $iOverage           = $objDb->getField(0, "overage");
        $iShortage          = $objDb->getField(0, "shortage");
        $iWrongSize         = $objDb->getField(0, "wrong_size");
        $iWrongPC           = $objDb->getField(0, "wrong_pc");
        $iIrregulars        = $objDb->getField(0, "irregulars");
        $iWrongSundries     = $objDb->getField(0, "wrong_sundries");
?>
				</div>

				<div class="qaButtons">
				  <input type="button" value="" class="btnAdd" title="Add Defect" onclick="addDefect( );" />
				  <!--<input type="button" value="" class="btnDelete" title="Delete Defect" onclick="deleteDefect( );" />-->
				</div>
                                
				<table border="1" bordercolor="#ffffff" cellpadding="0" cellspacing="0" width="100%">
				  <tr valign="top">
                                  <tr><td colspan="2"> <h2>Critical Defects</h2></td></tr>
					<td width="50%">
					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="140">Safety</td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="Safety" value="<?= $iSafety ?>" size="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Sewing</td>
						  <td align="center">:</td>
						  <td><input type="text" name="CSewing" value="<?= $iSewing ?>" size="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Measurements</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Measurements" value="<?= $iMeasurements ?>" size="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Accuracy</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Accuracy" value="<?= $iAccuracy ?>" size="10" class="textbox" /></td>
					    </tr>
                                            
                                            <tr>
						  <td>Other</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Other" value="<?= $iOther ?>" size="10" class="textbox" /></td>
					    </tr>
					  </table>

					</td>

					<td width="50%">

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="140">Critical Failure</td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="CriticalFailure" value="<?= $iCriticalFailure ?>" size="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Appearance</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Appearance" value="<?= $iAppearance ?>" size="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Sundries Broken</td>
						  <td align="center">:</td>
						  <td><input type="text" name="SundriesBroken" value="<?= $iSundriesBroken ?>" size="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Physicals</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Physicals" value="<?= $iPhysicals ?>" size="10" class="textbox" /></td>
					    </tr>

					    <tr>
                                                <td colspan="3">&nbsp;</td>
					    </tr>
					  </table>

					</td>
				  </tr>
				</table>

				<br />
                                <table border="1" bordercolor="#ffffff" cellpadding="0" cellspacing="0" width="100%">
				  <tr valign="top">
                                  <tr><td colspan="2"> <h2>Accuracy Information</h2></td></tr>
					<td width="50%">
					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="140">Cartons Sampled</td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="CartonsSampled" value="<?= $iCartonsSampled ?>" size="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Units sampled</td>
						  <td align="center">:</td>
						  <td><input type="text" name="UnitsSampled" value="<?= $iUnitsSampled ?>" size="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Overage</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Overage" value="<?= $iOverage ?>" size="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Wrong Size</td>
						  <td align="center">:</td>
						  <td><input type="text" name="WrongSize" value="<?= $iWrongSize ?>" size="10" class="textbox" /></td>
					    </tr>
                                            
                                            <tr>
						  <td>Irregulars</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Irregulars" value="<?= $iIrregulars ?>" size="10" class="textbox" /></td>
					    </tr>
					  </table>

					</td>

					<td width="50%">

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="140">Cartons in Error</td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="CartonsInError" value="<?= $iCartonsInError ?>" size="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Units in Errors</td>
						  <td align="center">:</td>
						  <td><input type="text" name="UnitsInErrors" value="<?= $iUnitsInErrors ?>" size="10" class="textbox" /></td>
					    </tr>
                                            
                                            <tr>
						  <td>Shortage</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Shortage" value="<?= $iShortage ?>" size="10" class="textbox" /></td>
					    </tr>
                                            
					    <tr>
						  <td>Wrong PC</td>
						  <td align="center">:</td>
						  <td><input type="text" name="WrongPC" value="<?= $iWrongPC ?>" size="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Wrong Sundries</td>
						  <td align="center">:</td>
						  <td><input type="text" name="WrongSundries" value="<?= $iWrongSundries ?>" size="10" class="textbox" /></td>
					    </tr>
					  </table>

					</td>
				  </tr>
				</table>

				<br />
				<table border="1" bordercolor="#ffffff" cellpadding="0" cellspacing="0" width="100%">
				  <tr valign="top">
					<td width="50%">

					  <h2>Work-ManShip</h2>

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="140">Total GMTS Inspected<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="TotalGmts" value="<?= $TotalGmts ?>" size="10" class="textbox" /> (Pcs)</td>
					    </tr>

					    <tr>
						  <td># of GMTS Defective</td>
						  <td align="center">:</td>
						  <td><input type="text" name="GmtsDefective" value="<?= $GmtsDefective ?>" size="10" class="textbox" /> (Pcs)</td>
					    </tr>

					    <tr>
						  <td>Max Allowable Defects</td>
						  <td align="center">:</td>
						  <td><input type="text" name="MaxDefects" value="<?= $MaxDefects ?>" size="10" class="textbox" readonly /></td>
					    </tr>

					    <tr>
						  <td>Number of Defects</td>
						  <td align="center">:</td>
						  <td><?= (int)$iDefects ?></td>
					    </tr>

					    <tr>
						  <td>D.H.U</td>
						  <td align="center">:</td>
						  <td><?= @round((($iDefects / $TotalGmts) * 100), 2) ?>%</td>
					    </tr>
					  </table>

					</td>

					<td width="50%">

					  <h2>Assortment</h2>

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="140">Total Cartons Inspected</td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="TotalCartons" value="<?= $TotalCartons ?>" size="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td># of Cartons Rejected</td>
						  <td align="center">:</td>
						  <td><input type="text" name="CartonsRejected" value="<?= $CartonsRejected ?>" size="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>% Defective</td>
						  <td align="center">:</td>
						  <td><input type="text" name="PercentDecfective" value="<?= $PercentDecfective ?>" size="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Acceptable Standard</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Standard" value="<?= $Standard ?>" size="10" class="textbox" /> %</td>
					    </tr>

					    <tr>
						  <td>D.H.U</td>
						  <td align="center">:</td>
						  <td><?= @round((($CartonsRejected / $TotalCartons) * 100), 2) ?>%</td>
					    </tr>
					  </table>

					</td>
				  </tr>
				</table>

				<br />
				<h2>Quantities</h2>

<?
	$sSQL = "SELECT quantity FROM tbl_po WHERE id='$PO'";
	$objDb->query($sSQL);

	$iOrderQty = $objDb->getField(0, 0);

	if ($AdditionalPos != "")
	{
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
				    <td width="140">Total Cartons Required</td>
				    <td width="20" align="center">:</td>
				    <td><input type="text" name="CartonsRequired" value="<?= $CartonsRequired ?>" size="10" class="textbox" /></td>
 			      </tr>

			      <tr>
				    <td>Ship Qty</td>
				    <td align="center">:</td>
				    <td><input type="text" name="ShipQty" value="<?= $ShipQty ?>" size="10" class="textbox" /></td>
				    <td>Total Cartons Shipped</td>
				    <td align="center">:</td>
				    <td><input type="text" name="CartonsShipped" value="<?= $CartonsShipped ?>" size="10" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Re-Screen Qty</td>
				    <td align="center">:</td>
				    <td><input type="text" name="ReScreenQty" value="<?= $ReScreenQty ?>" size="10" class="textbox" /></td>
				    <td>Deviation</td>
				    <td align="center">:</td>
				    <td><?= @round((($CartonsShipped / $CartonsRequired) * 100), 2) ?>%</td>
			      </tr>

			      <tr>
				    <td>Deviation</td>
				    <td align="center">:</td>
				    <td colspan="4"><?= @round((($ShipQty / $iOrderQty) * 100), 2) ?>%</td>
			      </tr>
				</table>
				<br />
                        
    <h2>Measurement Specs</h2>                            
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
                                <a href="includes/quonda/edit-measurement-specs.php?QaSampleId=<?=$iId?>&SamplingSize=<?= $iSamplingSize ?>&SizeId=<?=$iSize?>&Size=<?=$sSize?>&AuditId=<?=$Id?>&Color=<?=$sColor?>&Style=<?=$Style?>&SampleNo=<?=$iSampleNo?>&Nature=<?=$sPointsNature?>" class="lightview" rel="iframe" title="Measurement Specs for Audit#: <?= $Id ?> :: :: width: 900, height: 650"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit Measurement Specs" title="Edit Measurement Specs" /></a>&nbsp;
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
                                <div class="qaButtons">
                                    <a style="text-decoration: none; overflow: hidden;" href="includes/quonda/add-measurement-specs.php?Sizes=<?= $Sizes ?>&Colors=<?=$Colors?>&AuditId=<?=$Id?>&Style=<?=$Style?>" class="lightview" rel="iframe" title="Audit#: <?= $Id ?> :: :: width: 350, height: 260"><h2 style="font-size:14px;">+ADD&nbsp;&nbsp;</h2></a>
				</div>
<?
                            }
?>
				<br />
				<h2>Status & Comments</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr valign="top">
					<td width="90">QA Comments</td>
					<td width="20" align="center">:</td>
					<td><textarea name="Comments" class="textarea" style="width:98%; height:80px;"><?= $Comments ?></textarea></td>
				  </tr>
				</table>
