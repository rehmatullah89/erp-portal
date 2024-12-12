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

	$sSQL = "SELECT * FROM tbl_mgf_reports WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$VpoNo                = $objDb->getField(0, "vpo_no");
		$Reinspection         = $objDb->getField(0, "reinspection");
		$GarmentTest          = $objDb->getField(0, "garment_test");
		$ShadeBand            = $objDb->getField(0, "shade_band");
		$QaFile               = $objDb->getField(0, "qa_file");
		$FabricTest           = $objDb->getField(0, "fabric_test");
		$PpMeeting            = $objDb->getField(0, "pp_meeting");
		$FittingTorque        = $objDb->getField(0, "fitting_torque");
		$ColorCheck           = $objDb->getField(0, "color_check");
		$AccessoriesCheck     = $objDb->getField(0, "accessories_check");
		$MeasurementCheck     = $objDb->getField(0, "measurement_check");
		$CapOthers            = $objDb->getField(0, "cap_others");
		$CartonNo             = $objDb->getField(0, "carton_no");
		$MeasurementSampleQty = $objDb->getField(0, "measurement_sample_qty");
		$MeasurementDefectQty = $objDb->getField(0, "measurement_defect_qty");
	}
?>
				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="140"><b>Audit Code</b></td>
					<td width="20" align="center">:</td>
					<td><b><?= $AuditCode ?></b></td>
				  </tr>

				  <tr>
					<td>Vendor/Factory</td>
					<td align="center">:</td>
					<td><?= $sVendor ?></td>
				  </tr>

				  <tr>
					<td>Auditor</td>
					<td align="center">:</td>
					<td><?= $sAuditor ?></td>
				  </tr>
<?
	$sPos = "";

	$sSQL = "SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id='$PO' OR ('$AdditionalPos'!='' AND FIND_IN_SET(id, '$AdditionalPos')) ORDER BY order_no";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= ((($i > 0) ? ", " : "").$objDb->getField($i, 0));
	}
?>
				  <tr valign="top">
					<td>PO(s)</td>
					<td align="center">:</td>
					<td><?= $sPos ?></td>
				  </tr>

				  <tr>
					<td>Style</td>
					<td align="center">:</td>
					<td><?= getDbValue("style", "tbl_styles", "id='$Style'") ?></td>
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
<?
                                            if($AuditResult == ""){
?>
						<option value=""></option>
<?
                                            }
?>						<option value="P">Accepted</option>
						<option value="F">Rejected</option>
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
					<td>Re-Inspection</td>
					<td align="center">:</td>
					<td><input type="checkbox" name="ReInspection" value="Y" <?= (($Reinspection == "Y") ? "checked" : "") ?> /></td>
				  </tr>

				  <tr>
					<td>Colors</td>
					<td align="center">:</td>
					<td><input type="text" name="Colors" value="<?= $Colors ?>" size="30" class="textbox" /></td>
				  </tr>
				</table>

                <input type="hidden" name="PO" value="<?= $sSelectedPos ?>" />
				<input type="hidden" name="Style" value="<?= $Style ?>" />
				<input type="hidden" name="AuditType" value="<?= $AuditType ?>" />
                <input type="hidden" name="AuditStatus" value="<?= $AuditStatus ?>" />

				<br />
				<h2 id="SizeRequirements">Audit Sizes</h2>

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
					  <tr valign="top">
<?
		for ($j = 0; $j < 8; $j ++)
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
		if ($objDb->getField($i, "nature") > 0)
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

                                                <td width="100" align="center"><input type="text" id="Defects<?= $i ?>" name="Defects<?= $i ?>" value="<?= $objDb->getField($i, 'defects') ?>" maxlength="3" size="3" class="textbox defectsCount" onchange="$('Sms').value='1';" required=""/></td>

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
					    <td align="center">CAP</td>
					    <td colspan="5"><input type="text" id="Cap<?= $i ?>" name="Cap<?= $i ?>" value="<?= $objDb->getField($i, 'cap') ?>" maxlength="100" class="textbox defectCap" style="width:97.5%;" onchange="$('Sms').value='1';" /></td>
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

	if ($GmtsDefective == 0)
		$GmtsDefective = $iDefects;
?>
				</div>

				<div class="qaButtons">
				  <input type="button" value="" class="btnAdd" title="Add Defect" onclick="addDefect( );" />
				  <!--<input type="button" value="" class="btnDelete" title="Delete Defect" onclick="deleteDefect( );" />-->
				</div>

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
					  
					  <input type="hidden" name="GmtsDefective" value="<?= $GmtsDefective ?>" size="10" class="textbox" />
					</td>

					<td width="50%">

					  <h2>Assortment</h2>

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="150">Total Cartons Inspected <span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="TotalCartons" value="<?= $TotalCartons ?>" size="10" class="textbox" /></td>
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

					  <input type="hidden" name="CartonsRejected" value="<?= $CartonsRejected ?>" size="10" class="textbox" />
					  <input type="hidden" name="PercentDecfective" value="<?= $PercentDecfective ?>" size="10" class="textbox" />
					</td>
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
				    <td width="140">Deviation</td>
				    <td width="20" align="center">:</td>
				    <td><?= @round((($ShipQty / $iOrderQty) * 100), 2) ?>%</td>
 			      </tr>

			      <tr>
				    <td>Ship Qty/Output Qty <span class="mandatory">*</span></td>
				    <td align="center">:</td>
				    <td><input type="text" name="ShipQty" value="<?= $ShipQty ?>" size="10" class="textbox" /></td>
				    <td>Total Cartons Shipped <span class="mandatory">*</span></td>
				    <td align="center">:</td>
				    <td><input type="text" name="CartonsShipped" value="<?= $CartonsShipped ?>" size="10" class="textbox" /></td>
			      </tr>
				</table>

				<input type="hidden" name="CartonsRequired" value="<?= $CartonsRequired ?>" size="10" class="textbox" />

				<br />
				<h2>Status & Comments</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="175">Approved Sample</td>
					<td width="20" align="center">:</td>
					<td>
					  <select name="ApprovedSample">
						<option value=""></option>
						<option value="Yes">Yes</option>
						<option value="No">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.ApprovedSample.value = "<?= $ApprovedSample ?>";
					  -->
					  </script>
					</td>
				  </tr>

				  <tr>
					<td>Garment Test</td>
					<td align="center">:</td>
                                        <td>
					  <select name="GarmentTest">
						<option value=""></option>
						<option value="Y">Yes</option>
						<option value="N">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.GarmentTest.value = "<?= $GarmentTest ?>";
					  -->
					  </script>
					</td>
				  </tr>

				  <tr>
					<td>Shade Band</td>
					<td align="center">:</td>
                                        <td>
					  <select name="ShadeBand">
						<option value=""></option>
						<option value="Y">Yes</option>
						<option value="N">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.ShadeBand.value = "<?= $ShadeBand ?>";
					  -->
					  </script>
					</td>
				  </tr>

				  <tr>
					<td>Fabric/ Yarn Test</td>
					<td align="center">:</td>
					<td><input type="checkbox" name="FabricTest" value="Y" <?= (($FabricTest == "Y") ? "checked" : "") ?> /></td>
				  </tr>

				  <tr>
					<td>QA File</td>
					<td align="center">:</td>
                                        <td>
					  <select name="QaFile">
						<option value=""></option>
						<option value="Y">Yes</option>
						<option value="N">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.QaFile.value = "<?= $QaFile ?>";
					  -->
					  </script>
					</td>
				  </tr>

				  <tr>
					<td> PP Meeting Minutes</td>
					<td align="center">:</td>
                                        <td>
					  <select name="PpMeeting">
						<option value=""></option>
						<option value="Y">Yes</option>
						<option value="N">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.PpMeeting.value = "<?= $PpMeeting ?>";
					  -->
					  </script>
					</td>
				  </tr>

				  <tr>
					<td>Shipping Mark/UCC label</td>
					<td align="center">:</td>
                                        <td>
					  <select name="ShippingMark">
						<option value=""></option>
						<option value="Y">Yes</option>
						<option value="N">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.ShippingMark.value = "<?= $ShippingMark ?>";
					  -->
					  </script>
					</td>
				  </tr>

				  <tr>
					<td>Packing Check</td>
					<td align="center">:</td>
                                        <td>
					  <select name="PackingCheck">
						<option value=""></option>
						<option value="Y">Yes</option>
						<option value="N">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.PackingCheck.value = "<?= $PackingCheck ?>";
					  -->
					  </script>
					</td>
				  </tr>

				  <tr>
					<td>Fitting </td>
					<td align="center">:</td>
                                        <td>
					  <select name="FittingTorque">
						<option value=""></option>
						<option value="Y">Yes</option>
						<option value="N">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.FittingTorque.value = "<?= $FittingTorque ?>";
					  -->
					  </script>
					</td>
				  </tr>

				  <tr>
					<td>Color Check</td>
					<td align="center">:</td>
                                        <td>
					  <select name="ColorCheck">
						<option value=""></option>
						<option value="Y">Yes</option>
						<option value="N">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.ColorCheck.value = "<?= $ColorCheck ?>";
					  -->
					  </script>
					</td>
				  </tr>

				  <tr>
					<td>Accessories Check</td>
					<td align="center">:</td>
                    
					<td>
					  <select name="AccessoriesCheck">
						<option value=""></option>
						<option value="Y">Yes</option>
						<option value="N">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.AccessoriesCheck.value = "<?= $AccessoriesCheck ?>";
					  -->
					  </script>
					</td>
				  </tr>

				  <tr>
					<td>Measurement Check</td>
					<td align="center">:</td>
					<td><input type="checkbox" name="MeasurementCheck" value="Y" <?= (($MeasurementCheck == "Y") ? "checked" : "") ?> /></td>
				  </tr>

			      <tr>
				    <td width="140">Cutting/Knitting (%) <span class="mandatory">*</span></td>
				    <td width="20" align="center">:</td>
				    <td><input type="text" name="Cutting" value="<?= $Cutting ?>" size="8" maxlength="10" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Sewing/Linking (%) <span class="mandatory">*</span></td>
				    <td align="center">:</td>
				    <td><input type="text" name="Sewing" value="<?= $Sewing ?>" size="8" maxlength="10" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Finishing (%) <span class="mandatory">*</span></td>
				    <td align="center">:</td>
				    <td><input type="text" name="Finishing" value="<?= $Finishing ?>" size="8" maxlength="10" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Packed (%) <span class="mandatory">*</span></td>
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
				    <td>Carton No</td>
				    <td align="center">:</td>
				    <td><input type="text" name="CartonNo" value="<?= $CartonNo ?>" size="20" maxlength="250" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Measurement Inspected Qty <span class="mandatory">*</span></td>
				    <td align="center">:</td>
				    <td><input type="text" name="MeasurementSampleQty" value="<?= $MeasurementSampleQty ?>" size="20" maxlength="10" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Measurement Defective Qty <span class="mandatory">*</span></td>
				    <td align="center">:</td>
				    <td><input type="text" name="MeasurementDefectQty" value="<?= $MeasurementDefectQty ?>" size="20" maxlength="10" class="textbox" /></td>
			      </tr>

				  <tr valign="top">
					<td>CAP - Others</td>
					<td align="center">:</td>
					<td><textarea name="CapOthers" class="textarea" style="width:98%; height:80px;"><?= $CapOthers ?></textarea></td>
				  </tr>

				  <tr valign="top">
					<td>QA Comments</td>
					<td align="center">:</td>
					<td><textarea name="Comments" class="textarea" style="width:98%; height:80px;"><?= (($Comments == "N/A") ? "" : $Comments) ?></textarea></td>
				  </tr>
				  
				  <tr>
					<td>Publish Report</td>
					<td align="center">:</td>
                    
					<td>
					  <select name="Publish">
						<option value="Y">Yes</option>
						<option value="N">No</option>
					  </select>
					</td>
				  </tr>
				</table>

