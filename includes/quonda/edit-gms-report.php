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
					<td>Sample Size</td>
					<td align="center">:</td>
					<td><input type="text" name="TotalGmts" value="<?= $TotalGmts ?>" size="10" maxlength="5" class="textbox" /></td>
				  </tr>
                                  <input type="hidden" name="Colors" value="<?= $Colors ?>" size="30" class="textbox" />
				 
				</table>
                                <br/><br/>
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                   <tr><td>
                                    <table style="width: 50%; margin-top: 0px; float: left; ">
                                        <tr>
                                            <th colspan="2" align="center"><h2>Color Wise Inspected Quantities</h2></th>
                                        </tr>
                                        <tr>
                                            <th>Color</th>
                                            <th>Quantity</th>
                                        </tr>
<?
                                        $iColors = explode(",", $Colors);
                                        foreach ($iColors as $sColor){
?>                                            
                                        <tr>
                                            <td><?=$sColor?></td>
                                            <td>
                                                <input type="text" name="CQuantity[]" value="<?= getDbValue("quantity", "tbl_qa_color_quantities", "audit_id='$Id' AND color='$sColor'")?>" size="20" class="textbox" />
                                                <input type="hidden" name="CName[]" value="<?=$sColor?>" />
                                            </td>
                                        </tr>
<?                                      
                                        }
?>
                                    </table>
                                     <table style="width: 50%; margin-top: 0px; float: right; ">
                                        <tr>
                                            <th colspan="2" align="center"><h2>Po Shipment Quantities</h2></th>
                                        </tr>
                                        <tr>
                                            <th>PO #</th>
                                            <th>Shipment Quantity</th>
                                        </tr>
<?
                                        foreach ($sPos as $sPo){
?>                                            
                                        <tr>
                                            <td><?=$sPo['name']?></td>
                                            <td>
                                                <input type="text" name="POQuantity[]" value="<?= getDbValue("quantity", "tbl_qa_po_ship_quantities", "audit_id='$Id' AND po_id='{$sPo['id']}'")?>" size="20" class="textbox" />
                                                <input type="hidden" name="PoIds[]" value="<?=$sPo['id']?>" />
                                            </td>
                                        </tr>
<?                                      
                                        }
?>
                                     </table>   
                                    </td></tr>
                               </table>   
				<br /><br /><br /><br /><br />
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
					<td width="80" align="center"><b>Defects</b></td>
					<td width="80" align="center"><b>Sample #</b></td>
					<td width="170" align="center"><b>Area</b></td>
					<td width="100" align="center"><b>Nature</b></td>
                                        <td width="100" align="center"><b>Color</b></td>
					<td width="50" align="center"><b>Delete</b></td>
			      </tr>
			    </table>

			    <div id="QaDefects">
<?
	$sDefectCode = "";
	$sDefectArea = "";
	$iDefectArea = 0;

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
			{
				$sDefectArea = $sAreaId;
				$iDefectArea = $iAreaId;
			}
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
		        			<option value="0">Minor</option>
		        			<option value="1">Major</option>
						  </select>

						  <script type="text/javascript">
						  <!--
							document.frmData.Nature<?= $i ?>.value = "<?= $objDb->getField($i, 'nature') ?>";
						  -->
						  </script>
						</td>
<?
                                                $iColors = explode(",", $Colors);
?>   
                                                <td width="100" align="center">
						<select id="DColor<?= $i ?>" name="DColor<?= $i ?>" class="defectColor" onchange="$('Sms').value='1';">
		        			<option value=""></option>
<?                                              
                                                foreach($iColors as $iColor)
                                                {                                                    
?>
                                                <option value="<?=$iColor?>"><?=$iColor?></option>
<?
                                                }
?>
						  </select>

						  <script type="text/javascript">
						  <!--
							document.frmData.DColor<?= $i ?>.value = "<?= $objDb->getField($i, 'color') ?>";
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

	if ($GmtsDefective == 0)
		$GmtsDefective = $iDefects;
?>
				</div>

				<div class="qaButtons">
				  <input type="button" value="" class="btnAdd" title="Add Defect" onclick="addDefect( );" />
				  <!--<input type="button" value="" class="btnDelete" title="Delete Defect" onclick="deleteDefect( );" />-->
				</div>
                                <h2><table border="0" style="text-align:center;" cellpadding="3" cellspacing="0" width="100%"><tr><td style="border:1px; color: white;">Material</td><td style="border:1px; color: white;">Packaging</td><td style="border:1px; color: white;">Appearance</td></tr></table></h2>

<?
	$sSQL = "SELECT * FROM tbl_gms_reports WHERE audit_id='$Id'";
	$objDb->query($sSQL);

        $FKPanelQlty        = $objDb->getField(0, 'fk_panel_qlty');
        $MainLabel          = $objDb->getField(0, 'main_label');
        $PriceTag           = $objDb->getField(0, 'price_tag');
        $UntrimmedThread    = $objDb->getField(0, 'untrimmed_thread');
        $HandFeel           = $objDb->getField(0, 'hand_feel');
        $WashingLabel       = $objDb->getField(0, 'washing_label');
        $SpecialHangtag     = $objDb->getField(0, 'special_hangtag');
        $HandFeel2          = $objDb->getField(0, 'hand_feel2');
        $Color              = $objDb->getField(0, 'color');
        $SizeLabel          = $objDb->getField(0, 'size_label');
        $TissueStuffing     = $objDb->getField(0, 'tissue_stuffing');
        $FitOnForm          = $objDb->getField(0, 'fit_on_form');
        $ShadeLot           = $objDb->getField(0, 'shade_lot');
        $CareLabel          = $objDb->getField(0, 'care_label');
        $Polybag            = $objDb->getField(0, 'polybag');
        $Twisted            = $objDb->getField(0, 'twisted');
        $Lining             = $objDb->getField(0, 'lining');
        $IntSizeLabel       = $objDb->getField(0, 'int_size_label');
        $TrimFabric         = $objDb->getField(0, 'trim_fabric');
        $PackingMethod      = $objDb->getField(0, 'packing_method');
        $SpareButton        = $objDb->getField(0, 'spare_button');
        $Measurement        = $objDb->getField(0, 'measurement');
        $Interlining        = $objDb->getField(0, 'interlining');
        $InfoSticker        = $objDb->getField(0, 'info_sticker');
        $Smell              = $objDb->getField(0, 'smell');
        $ShoulderPad        = $objDb->getField(0, 'shoulder_pad');
        $PackingAssortment  = $objDb->getField(0, 'packing_assortment');
        $MoistureResult     = $objDb->getField(0, 'mositure_test_result');
        $WashingEffect      = $objDb->getField(0, 'washing_effect');
        $ExpCartonSize      = $objDb->getField(0, 'exp_carton_size');
        $AzoReportNo        = $objDb->getField(0, 'azo_report_no');
        $DownPouch          = $objDb->getField(0, 'down_pouch');
        $ExportCartonWeight = $objDb->getField(0, 'exp_carton_weight');
        $Padding            = $objDb->getField(0, 'padding');
        $CartonLabel        = $objDb->getField(0, 'carton_label');
        $PleaseSpecify      = $objDb->getField(0, 'please_specify');
        $GarmentMeasurement = $objDb->getField(0, 'garment_measurement');
        $MoistureMeasurement= $objDb->getField(0, 'moisture_measurement');
        $DrawnCartonNo      = $objDb->getField(0, 'drawn_carton_no');
        $AssortmentCheck    = $objDb->getField(0, 'assortment_check');
        
?>

                        <table id="Mytable" border="0" cellpadding="6" cellspacing="0" width="100%">
			      <tr>
				    <td width="170">Fabric/knitting Panel Quality :</td>
				    <td>
					  <select name="FKPanelQlty">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.FKPanelQlty.value = "<?= $FKPanelQlty ?>";
					  -->
					  </script>
                                    </td>
				    <td width="170">Price Tag</td>
                                    <td>
					  <select name="PriceTag">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.PriceTag.value = "<?= $PriceTag ?>";
					  -->
					  </script>
                                    </td>
                                    <td width="160">Un-trimmed Thread</td>
                                    <td>
					  <select name="UntrimmedThread">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.UntrimmedThread.value = "<?= $UntrimmedThread ?>";
					  -->
					  </script>
                                    </td>
 			      </tr>
                              <tr>
				    <td width="170">Hand Feel</td>
				    <td>
					  <select name="HandFeel">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.HandFeel.value = "<?= $HandFeel ?>";
					  -->
					  </script>
                                    </td>
				    <td width="170">Special Hangtag</td>
                                    <td>
					  <select name="SpecialHangtag">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.SpecialHangtag.value = "<?= $SpecialHangtag ?>";
					  -->
					  </script>
                                    </td>
                                    <td width="170">Hand feel</td>
                                    <td>
					  <select name="HandFeel2">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.HandFeel2.value = "<?= $HandFeel2 ?>";
					  -->
					  </script>
                                    </td>
 			      </tr>

                              <tr>
				    <td width="170">Color</td>
				    <td>
					  <select name="Color">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.Color.value = "<?= $Color ?>";
					  -->
					  </script>
                                    </td>
				    <td width="170">Tissue Paper / Stuffing</td>
                                    <td>
					  <select name="TissueStuffing">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.TissueStuffing.value = "<?= $TissueStuffing ?>";
					  -->
					  </script>
                                    </td>
                                    <td width="170">Fit on Form</td>
                                    <td>
					  <select name="FitOnForm">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.FitOnForm.value = "<?= $FitOnForm ?>";
					  -->
					  </script>
                                    </td>
 			      </tr>

                              <tr>
				    <td width="170">Shade Lot </td>
				    <td>
					  <select name="ShadeLot">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.ShadeLot.value = "<?= $ShadeLot ?>";
					  -->
					  </script>
                                    </td>
				    <td width="170">Polybag</td>
                                    <td>
					  <select name="Polybag">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.Polybag.value = "<?= $Polybag ?>";
					  -->
					  </script>
                                    </td>
                                    <td width="170">Twisted / Unbalance</td>
                                    <td>
					  <select name="Twisted">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.Twisted.value = "<?= $Twisted ?>";
					  -->
					  </script>
                                    </td>
 			      </tr>

                              <tr>
				    <td width="170">Lining </td>
				    <td>
					  <select name="Lining">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.Lining.value = "<?= $Lining ?>";
					  -->
					  </script>
                                    </td>
				    <td width="170">Packing Method </td>
                                    <td>
					  <select name="PackingMethod">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.PackingMethod.value = "<?= $PackingMethod ?>";
					  -->
					  </script>
                                    </td>
                                    <td width="170" colspan="2"><h3>Others </h3></td>
                              </tr>
                              <tr>
                                   <td width="170">Trim Fabric </td>
                                    <td>
					  <select name="TrimFabric">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.TrimFabric.value = "<?= $TrimFabric ?>";
					  -->
					  </script>
                                    </td>
                                    <td width="170">Spare Button</td>
                                    <td>
					  <select name="SpareButton">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.SpareButton.value = "<?= $SpareButton ?>";
					  -->
					  </script>
                                    </td>
                                    <td width="170">Measurement</td>
                                    <td>
					  <select name="Measurement">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.Measurement.value = "<?= $Measurement ?>";
					  -->
					  </script>
                                    </td>
                              </tr>
                              <tr>
                                  <td width="170">Interlining</td>
                                    <td>
					  <select name="Interlining">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.Interlining.value = "<?= $Interlining ?>";
					  -->
					  </script>
                                    </td>
                                    <td width="170">OSOC EAN / INFO. Sticker</td>
                                    <td>
					  <select name="InfoSticker">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.InfoSticker.value = "<?= $InfoSticker ?>";
					  -->
					  </script>
                                    </td>
                                    <td width="170">Smell</td>
                                    <td>
					  <select name="Smell">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.Smell.value = "<?= $Smell ?>";
					  -->
					  </script>
                                    </td>
                              </tr>
                              <tr>
                                  <td width="170">Shoulder Pad</td>
                                    <td>
					  <select name="ShoulderPad">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.ShoulderPad.value = "<?= $ShoulderPad ?>";
					  -->
					  </script>
                                    </td>
                                    <td width="170">Packing Assortment</td>
                                    <td>
					  <select name="PackingAssortment">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.PackingAssortment.value = "<?= $PackingAssortment ?>";
					  -->
					  </script>
                                    </td>
                                     <td width="170">Moisture Test Result</td>
                                    <td>
					  <select name="MoistureResult">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.MoistureResult.value = "<?= $MoistureResult ?>";
					  -->
					  </script>
                                    </td>
                              </tr>
                              <tr>
                                  <td width="170">Washing Effect</td>
                                  <td>
                                        <select name="WashingEffect">
                                              <option value=""></option>
                                              <option value="X">Confirm</option>
                                              <option value="n">Non-Confirm</option>
                                              <option value="Z">Not Applicable</option>
                                        </select>

                                        <script type="text/javascript">
                                        <!--
                                              document.frmData.WashingEffect.value = "<?= $WashingEffect ?>";
                                        -->
                                        </script>
                                  </td>
                                  <td width="170">Export Carton Size</td>
                                  <td>
                                        <select name="ExpCartonSize">
                                              <option value=""></option>
                                              <option value="X">Confirm</option>
                                              <option value="n">Non-Confirm</option>
                                              <option value="Z">Not Applicable</option>
                                        </select>

                                        <script type="text/javascript">
                                        <!--
                                              document.frmData.ExpCartonSize.value = "<?= $ExpCartonSize ?>";
                                        -->
                                        </script>
                                  </td>
                                    <td width="170">"Pass" Azo Test Report No.</td>
                                  <td>
                                      <input class='textbox' name="AzoReportNo" type="text" value="<?=$AzoReportNo?>" size="12" maxlength="20">                                        
                                  </td>
                              </tr>
                              <tr>
                                  <td width="170">Down Pouch</td>
                                    <td>
					  <select name="DownPouch">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.DownPouch.value = "<?= $DownPouch ?>";
					  -->
					  </script>
                                    </td>
                                    <td width="170">Export Carton Weight</td>
                                    <td>
					  <select name="ExportCartonWeight">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.ExportCartonWeight.value = "<?= $ExportCartonWeight ?>";
					  -->
					  </script>
                                    </td>
                                    <td width="170">Please specify:</td>
                                    <td>
                                        <select name="PleaseSpecify">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.PleaseSpecify.value = "<?= $PleaseSpecify ?>";
					  -->
					  </script>
                                    </td>
                              </tr>
                              <tr>
                                  <td width="170">Padding</td>
                                    <td>
					  <select name="Padding">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.Padding.value = "<?= $Padding ?>";
					  -->
					  </script>
                                    </td>
                                    <td width="170">Carton Label</td>
                                    <td>
					  <select name="CartonLabel">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.CartonLabel.value = "<?= $CartonLabel ?>";
					  -->
					  </script>
                                    </td>
                                    <td colspan="2" width="170"><h3>Trims</h3></td>
                              </tr>
                              <tr>
                                  <td width="170" colspan="4">&nbsp;</td>
                                  <td width="170"> Main Label </td>
                                    <td>
                                          <select name="MainLabel">
                                                <option value=""></option>
                                                <option value="X">Confirm</option>
                                                <option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
                                          </select>

                                          <script type="text/javascript">
                                          <!--
                                                document.frmData.MainLabel.value = "<?= $MainLabel ?>";
                                          -->
                                          </script>
                                    </td>
                              </tr>
                              <tr>
                                  <td width="170" colspan="4">&nbsp;</td>
                                  <td width="170">Washing Label</td>
				    <td>
					  <select name="WashingLabel">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.WashingLabel.value = "<?= $WashingLabel ?>";
					  -->
					  </script>
                                    </td>
                              </tr>
                              <tr>
                                  <td width="170" colspan="4">&nbsp;</td>
                                  <td width="170"> Size Label </td>
				    <td>
					  <select name="SizeLabel">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.SizeLabel.value = "<?= $SizeLabel ?>";
					  -->
					  </script>
                                    </td>
                              </tr>
                               <tr>
                                  <td width="170" colspan="4">&nbsp;</td>
                                  <td width="170"> Care Label  </td>
				    <td>
					  <select name="CareLabel">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.CareLabel.value = "<?= $CareLabel ?>";
					  -->
					  </script>
                                    </td>
                               </tr>
                               <tr>
                                  <td width="170" colspan="4">&nbsp;</td>
                                   <td width="170"> International Size Label  </td>
				    <td>
					  <select name="IntSizeLabel">
						<option value=""></option>
						<option value="X">Confirm</option>
						<option value="n">Non-Confirm</option>
                                                <option value="Z">Not Applicable</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.IntSizeLabel.value = "<?= $IntSizeLabel ?>";
					  -->
					  </script>
                                    </td>
                               </tr>
                            </table>
                                <br />
				<h2>Cartons Check List</h2>
<?
                         $iDrawnCartonNos   = explode(",", $DrawnCartonNo);      
                         $iAssortmentChecks = explode(",", $AssortmentCheck);    
?>
                                <table id="InspectionsTable" border="0" cellpadding="3" cellspacing="0" width="400">
                                    <tr>
                                        <td width="20">&nbsp;</td>
                                        <td><b>Drawn Carton No.</b></td>
                                        <td><b>Assortment Check</b></td>
                                    </tr>
<?
                                $i=1;
                                if(count($iDrawnCartonNos) > 0){
                                    foreach($iDrawnCartonNos as $key => $iCartonNo){
?>
                                    <tr>
                                        <td><?=$i?></td>
                                        <td><input class='textbox' name="CartonNo_<?=$i?>" type="text" value="<?=$iCartonNo?>"  style='width:95%;'></td>
                                        <td><input class='textbox' name="AssortCheck_<?=$i?>" type="checkbox" value="Y" <?= ($iAssortmentChecks[$key] == 'Y'?'checked':'') ?> style='width:95%;'></td>
                                    </tr>                                        
<?              
                                        $i++;
                                    }
                                }
                                else{
?>
                                     <tr>
                                         <td>1</td>
                                        <td><input class='textbox' name="CartonNo_1" type="text" value="" style='width:95%;'></td>
                                        <td><input class='textbox' name="AssortCheck_1" type="checkbox" value="Y" style='width:95%;'></td>
                                    </tr>
<?
                                }
?>
                                </table><br />
                                <input type="hidden" name="CountRows" id="CountRows" value="<?=$i?>">
                                <a id="BtnAddRow" onclick="AddNewRow()">Add Row [+]</a>&nbsp;&nbsp;&nbsp;
                                <a id="BtnDelRow" onclick="DeleteRow()">Remove Row [-]</a>
                                
				<br /><br />
				<h2>Status & Comments</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
                                  
                                  <tr>
					<td width="230">Garment Measurement Inspection</td>
					<td width="20" align="center">:</td>
					<td>
					  <select name="GarmentMeasurement">
						<option value=""></option>
						<option value="P">Pass</option>
						<option value="F">Fail</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.GarmentMeasurement.value = "<?= $GarmentMeasurement ?>";
					  -->
					  </script>
                                        </td>
				  </tr>
                                  
                                  <tr>
					<td width="230">Moisture measurement inspection result</td>
					<td width="20" align="center">:</td>
					<td>
					  <select name="MoistureMeasurement">
						<option value=""></option>
						<option value="P">Pass</option>
						<option value="F">Fail</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.MoistureMeasurement.value = "<?= $MoistureMeasurement ?>";
					  -->
					  </script>
                                        </td>
				  </tr>

				  <tr>
					<td>Ship Qty</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="ShipQty" value="<?= $ShipQty ?>" size="8" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Re-Screen Qty</td>
					<td align="center">:</td>
					<td><input type="text" name="ReScreenQty" value="<?= $ReScreenQty ?>" size="8" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr valign="top">
					<td>QA Comments</td>
					<td align="center">:</td>
                    <td><textarea name="Comments" class="textarea" cols="80" rows="5"><?= $Comments ?></textarea></td>
				  </tr>
				</table>
<script type="text/javascript">
	<!--

        var i=document.getElementById("CountRows").value;
        function AddNewRow() {
            if(i <= 30)
            {
                var table = document.getElementById("InspectionsTable");
                var rowCount = table.rows.length;
                var row = table.insertRow(rowCount);
                var cell1 = row.insertCell(0);
                var cell2 = row.insertCell(1);
                var cell3 = row.insertCell(2);

                var pName = "CartonNo_"+i;
                var pDesig = "AssortCheck_"+i;
                cell1.innerHTML = i;
                cell2.innerHTML = "<input type='text' class='textbox' name="+pName+" id="+pName+" value='<?=IO::strValue('pName')?>'  style='width:95%;'/>";
                cell3.innerHTML = "<input type='checkbox' class='textbox' name="+pDesig+" id="+pDesig+" value='Y' style='width:95%;'/>";
                i++;
                document.getElementById("CountRows").value = i;
            }
        }

        function DeleteRow() {
            var table = document.getElementById("InspectionsTable");
            var rowCount = table.rows.length;
            table.deleteRow(rowCount-1);
            i--;
            document.getElementById("CountRows").value = i;
        }
        -->
</script>                