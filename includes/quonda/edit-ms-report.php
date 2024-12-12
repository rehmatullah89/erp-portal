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
					<td width="180"><b>Audit Code</b></td>
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
					<td>Color</td>
					<td align="center">:</td>
					<td><input type="text" name="Colors" value="<?= $Colors ?>" size="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Description</td>
					<td align="center">:</td>
					<td><input type="text" name="Description" value="<?= $Description ?>" size="20" maxlength="250" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Batch Size</td>
					<td align="center">:</td>
					<td><input type="text" name="BatchSize" value="<?= $BatchSize ?>" size="10" maxlength="50" class="textbox" /></td>
				  </tr>

<?
	$sSQL = "SELECT * FROM tbl_ms_qa_reports WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$Series        = $objDb->getField(0, 'series');
	$Department    = $objDb->getField(0, 'department');
	$BigProducts   = $objDb->getField(0, 'big_products');
	$BigSize       = $objDb->getField(0, 'big_size');
	$SmallProducts = $objDb->getField(0, 'small_products');
	$SmallSize     = $objDb->getField(0, 'small_size');
	$Action        = $objDb->getField(0, 'action');
?>
				  <tr>
					<td>Series</td>
					<td align="center">:</td>
					<td><input type="text" name="Series" value="<?= $Series ?>" size="20" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Department</td>
					<td align="center">:</td>
					<td><input type="text" name="Department" value="<?= $Department ?>" size="20" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>% of Packed</td>
					<td align="center">:</td>
					<td><input type="text" name="PackedPercent" value="<?= $PackedPercent ?>" size="10" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Sample Size</td>
					<td align="center">:</td>
					<td><input type="text" name="TotalGmts" value="<?= $TotalGmts ?>" size="10" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Total Faulty Products</td>
				    <td align="center">:</td>
				    <td><input type="text" name="GmtsDefective" value="<?= $GmtsDefective ?>" size="10" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Faulty Products Allowed (AQL)</td>
				    <td align="center">:</td>
				    <td><input type="text" name="MaxDefects" value="<?= $MaxDefects ?>" size="10" class="textbox" readonly /></td>
				  </tr>

			      <tr>
				    <td>Re-Screen Qty</td>
				    <td align="center">:</td>
				    <td><input type="text" name="ReScreenQty" value="<?= $ReScreenQty ?>" size="10" class="textbox" /></td>
			      </tr>
				</table>

				<br />
				<h2 id="DefectDetails" style="margin-bottom:0px;">Defects Details</h2>
<?
	$sSQL = "SELECT * FROM tbl_qa_report_defects WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


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
                                        <td width="80" align="center"><b>Defects</b></td>
					<td width="80" align="center"><b>Sample #</b></td>
					<td width="170" align="center"><b>Area</b></td>
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

		$sSQL = "SELECT DISTINCT(type_id), (SELECT type FROM tbl_defect_types WHERE id=tbl_defect_codes.type_id) FROM tbl_defect_codes WHERE report_id='$ReportId' ORDER BY type_id";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

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

                                                <td width="80" align="center"><input type="text" id="Defects<?= $i ?>" name="Defects<?= $i ?>" value="<?= $objDb->getField($i, 'defects') ?>" maxlength="3" size="3" class="textbox defectsCount" onchange="$('Sms').value='1';" required="" /></td>
                                                <td width="80" align="center"><input type="text" id="SampleNo<?= $i ?>" name="SampleNo<?= $i ?>" value="<?= $objDb->getField($i, 'sample_no') ?>" maxlength="3" size="3" class="textbox sampleNos" onblur="getMaxAllowed(<?=$i?>,<?=$TotalGmts?>);" onchange="$('Sms').value='1';" /></td>

						<td width="170" align="center">
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

			    <table border="1" bordercolor="#ffffff" cellpadding="4" cellspacing="0" width="100%">
				  <tr class="sdRowHeader">
				    <td width="50%" align="center"><b>Measurements</b></td>
				    <td width="20%" align="center"><b>Number of Products</b></td>
				    <td width="30%" align="center"><b>Size affected</b></td>
				  </tr>

				  <tr class="sdRowColor">
				    <td>M+ &nbsp; Critical measurements out of tolerance, too big</td>
				    <td align="center"><input type="text" name="BigProducts" value="<?= $BigProducts ?>" maxlength="3" size="10" class="textbox" /></td>
				    <td align="center"><input type="text" name="BigSize" value="<?= $BigSize ?>" maxlength="50" size="35" class="textbox" /></td>
				  </tr>

				  <tr class="sdRowColor">
				    <td>M- &nbsp; Critical measurements out of tolerance, too small</td>
				    <td align="center"><input type="text" name="SmallProducts" value="<?= $SmallProducts ?>" maxlength="3" size="10" class="textbox" /></td>
				    <td align="center"><input type="text" name="SmallSize" value="<?= $SmallSize ?>" maxlength="50" size="35" class="textbox" /></td>
				  </tr>
				</table>

				<br />
				<h2>Status & Comments</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="100">Audit Result<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

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

				  <tr valign="top">
				    <td>Cartons Shipped</td>
				    <td align="center">:</td>
				    <td><input type="text" name="CartonsShipped" value="<?= $CartonsShipped ?>" size="10" class="textbox" /></td>
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

				  <tr valign="top">
					<td>Action</td>
					<td align="center">:</td>
					<td><textarea name="Action" class="textarea" style="width:98%; height:80px;"><?= $Action ?></textarea></td>
				  </tr>
				</table>
