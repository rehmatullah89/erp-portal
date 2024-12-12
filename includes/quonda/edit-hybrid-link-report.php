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
<?
	if ($ReportId != 8)
	{
?>
				  <tr>
					<td>Inspection Type</td>
					<td align="center">:</td>

					<td>
					  <select name="AuditType">
						<option value="B">Bulk</option>
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
        
        $sAllPos = $PO;
        if($AdditionalPos != "")
            $sAllPos = $PO.','.$AdditionalPos;
        
        $sSizes = implode(",", getList("tbl_po_quantities", "DISTINCT(size_id)", "size_id", "FIND_IN_SET(po_id, '$sAllPos')"));

        if(empty($sSizes))
            $sSizes = 0;
        
	$sSQL = "SELECT id, size FROM tbl_sizes WHERE id IN ($sSizes) ORDER BY position";

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
<?
		if ($ReportId == 28)
		{
?>
                      <tr>
					    <td align="center">Remarks</td>
					    <td colspan="5"><input type="text" id="Remarks<?= $i ?>" name="Remarks<?= $i ?>" value="<?= $objDb->getField($i, 'remarks') ?>" maxlength="100" class="textbox defectCap" style="width:97.5%;" onchange="$('Sms').value='1';" /></td>
					  </tr>
<?
		}
?>
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
        
        $sSQL = "SELECT * FROM tbl_hybrid_link_reports WHERE audit_id='$Id'";
	$objDb->query($sSQL);
        
        $AssortmentQty      = $objDb->getField(0, "assortment_qty");
        $AssortmentQtySize  = $objDb->getField(0, "assortment_qty_size");
        $SolidSizeQty       = $objDb->getField(0, "solid_size_qty");
        $IsFullPacket       = $objDb->getField(0, "is_box_full");
        $ShipmentDate       = $objDb->getField(0, "shipment_date");
        $CartonNos          = $objDb->getField(0, "carton_nos");
        $MeasurementPoints  = $objDb->getField(0, "measurement_points");
        $MeasureSampleSize  = $objDb->getField(0, "measurement_sample_size");
        $TotalTolerance     = $objDb->getField(0, "total_tolerance_pts");
        $PackingResult      = $objDb->getField(0, "packing_result");
        $ConformityResult   = $objDb->getField(0, "conformity_result");
        
?>
				</div>

				<div class="qaButtons">
				  <input type="button" value="" class="btnAdd" title="Add Defect" onclick="addDefect( );" />
				  <!--<input type="button" value="" class="btnDelete" title="Delete Defect" onclick="deleteDefect( );" />-->
				</div>
                                <h2>Program and Sample Size Details</h2>
				<table border="1" bordercolor="#ffffff" cellpadding="0" cellspacing="0" width="100%">
				  <tr valign="top">
					<td width="50%">
                                            <table border="0" cellpadding="3" cellspacing="0" width="100%">
                                                <tr>
                                                    <td width="200">Assortment Qty (Per Carton)</td>
                                                    <td width="20" align="center">:</td>
                                                    <td><input type="text" name="AssortmentQty" value="<?= $AssortmentQty ?>" size="10" class="textbox" /></td>
                                                </tr>
                                                <tr>
                                                    <td width="180">Assortment Qty (Per Size)</td>
                                                    <td width="20" align="center">:</td>
                                                    <td><input type="text" name="AssortmentQtySize" value="<?= $AssortmentQtySize ?>" size="10" class="textbox" /></td>
                                                </tr>
                                                <tr>
                                                    <td width="180">Solid Size Qty (# of gmts/carton)</td>
                                                    <td width="20" align="center">:</td>
                                                    <td><input type="text" name="SolidSizeQty" value="<?= $SolidSizeQty ?>" size="10" class="textbox" /></td>
                                                </tr>
                                                <tr>
                                                    <td width="180">Solid Size Qty?</td>
                                                    <td width="20" align="center">:</td>
                                                    <td>
                                                      <select name="IsFullPacket">
                                                            <option value=""></option>
                                                            <option value="Y">Full Packet</option>
                                                            <option value="N">Blank</option>
                                                      </select>

                                                      <script type="text/javascript">
                                                      <!--
                                                            document.frmData.IsFullPacket.value = "<?= $IsFullPacket ?>";
                                                      -->
                                                      </script>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="180">Lot Quantity</td>
                                                    <td width="20" align="center">:</td>
                                                    <td><input type="text" name="AuditQty" value="<?= $AuditQuantity ?>" size="10" class="textbox" /></td>
                                                </tr>
                                            </table>
					</td>

					<td width="50%">

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    
                                            <tr>
						  <td width="140">Carton Quantity</td>
						  <td width="20" align="center">:</td>
						  <td><?= ceil($ShipQty/($AssortmentQty+$SolidSizeQty))?></td>
					    </tr>
                                            
                                            <tr>
						  <td width="140">Total Carton Pull</td>
						  <td width="20" align="center">:</td>
                                                  <td>
<?
                                                    if($IsFullPacket == 'N')
                                                        print ceil($TotalGmts/12);
                                                    else if($IsFullPacket == 'Y')
                                                        print ceil($TotalGmts/6);
?>
                                                  </td>
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
						  <td><?= @round((($CartonsRejected /ceil($ShipQty/($AssortmentQty+$SolidSizeQty))) * 100), 2) ?>%</td>
					    </tr>
					  </table>

					</td>
				  </tr>
                                  <tr>
                                      <td><b>Carton Nos:</b></td>
                                  </tr>
                                  <tr>
                                      <td colspan="2"><span style="padding-left:70px;">&nbsp;</span>
<?
                                        $iCartonNos = explode(",", $CartonNos);    
                                        for($c=0; $c<10; $c++){
?>
                                          <input type="text" name="cartonNos[]" size="5" value="<?=$iCartonNos[$c]?>">&nbsp;
<?
                                        }    
?>
                                      </td>
                                  </tr>
				</table>

				<br />
				<h2>Quantities</h2>

<?
        
        $iOrderQty = getDbValue("SUM(quantity)", "tbl_po", "id IN ($sAllPos)");
?>
                <table border="1" bordercolor="#ffffff" cellpadding="0" cellspacing="0" width="100%">
                  <tr valign="top">
                        <td width="50%">
                            <table border="0" cellpadding="3" cellspacing="0" width="100%">
			      <tr>
				    <td width="140">PO Quantity</td>
				    <td width="20" align="center">:</td>
				    <td><?= $iOrderQty ?></td>
 			      </tr>

			      <tr>
				    <td>Shipment Quantity</td>
				    <td align="center">:</td>
				    <td><input type="text" name="ShipQty" value="<?= $ShipQty ?>" size="10" class="textbox" /></td>
			      </tr>
                              <tr>
                                <td>Shipment Date</td>
                                <td align="center">:</td>

                                <td>

                                      <table border="0" cellpadding="0" cellspacing="0" width="116">
                                        <tr>
                                            <td width="82"><input type="text" name="ShipmentDate" id="ShipmentDate" value="<?= (($ShipmentDate != "0000-00-00") ? $ShipmentDate : "") ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ShipmentDate'), 'yyyy-mm-dd', this);" /></td>
                                            <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ShipmentDate'), 'yyyy-mm-dd', this);" /></td>
                                        </tr>
                                      </table>

                                </td>
                              </tr>
			      <tr>
				    <td>Re-Screen Qty</td>
				    <td align="center">:</td>
				    <td><input type="text" name="ReScreenQty" value="<?= $ReScreenQty ?>" size="10" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Deviation</td>
				    <td align="center">:</td>
				    <td colspan="4"><?= @round((($ShipQty / $iOrderQty) * 100), 2) ?>%</td>
			      </tr>
				</table>
                            </td>
                            <td>
                                <table border="0" cellpadding="3" cellspacing="0" width="100%">
                                    <tr>
                                          <td width="190">Sample Size (GMTS Inspected)<span class="mandatory">*</span></td>
                                          <td width="20" align="center">:</td>
                                          <td><input type="text" name="TotalGmts" value="<?= $TotalGmts ?>" size="10" class="textbox" readonly/> (Pcs)</td>
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
                  </tr>
                </table>	
                
                <br />
                <h2>Measurements</h2>
                <table border="0" cellpadding="3" cellspacing="0" width="100%">
                                
                    <tr>
                        <td>Measurement Sample Size:</td><td><input type="text" name="MeasurementSampleSize" value="<?= $MeasureSampleSize ?>" size="10" class="textbox" /></td><td>Total out of Tolerance</td><td><input type="text" name="TotalTolerance" value="<?= $TotalTolerance ?>" size="10" class="textbox" /></td>
                    </tr>
                    <tr>
                        <td>Total Points Of Measure (POM):</td><td><input type="text" name="TotalMeasurePoints" value="<?= $MeasurementPoints ?>" size="10" class="textbox" /></td><td>Maximum POM OOT Accepted</td><td><?= formatNumber($MeasurementPoints*0.04,2) ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td><td>Measurement Result:</td><td><?= (($MeasurementPoints*0.04)>$TotalTolerance)?'Fail':'Pass'; ?></td>
                    </tr>
                </table>        
                 <br />
                <h2>Packing and Packaging</h2>
                <table cellspacing="0" cellpadding="5" style="margin-top:-10px;" bordercolor="#ffffff" border="1" width="100%">
                    <tbody>
                    <tr class="sdRowHeader">
                        <td width="20%"><b>Checklist</b></td><td  width="20%"><b>Result</b></td><td  width="60%"><b>Reason / Comments</b></td>
                    </tr>
<?

                    $sCheckListP = getList("tbl_hybrid_link_report_checks", "id", "title", "type='P'", "id");
                    $sCheckListG = getList("tbl_hybrid_link_report_checks", "id", "title", "type='G'", "id");

                    foreach($sCheckListP as $iCheck => $sCheck){
?>
                    <tr><td><?=$sCheck?></td><td><select name="CheckList<?=$iCheck?>"><option value=""></option><option value="NA" <?= (getDbValue("result", "tbl_hybrid_link_report_check_details", "audit_id='$Id' AND check_id='$iCheck'") == "NA"?'selected':'') ?>>Not Applicable</option><option value="NC" <?=(getDbValue("result", "tbl_hybrid_link_report_check_details", "audit_id='$Id' AND check_id='$iCheck'") == "NC"?'selected':'');?>>Not Conform</option><option value="C" <?=(getDbValue("result", "tbl_hybrid_link_report_check_details", "audit_id='$Id' AND check_id='$iCheck'") == "C"?'selected':'')?>>Conform</option></select></td><td><input type="text" size="70" name="CheckReason<?=$iCheck?>" value="<?=getDbValue("remarks", "tbl_hybrid_link_report_check_details", "audit_id='$Id' AND check_id='$iCheck'")?>"></td></tr>
<?
                    }
?>                  
                        <tr>
                            <td>&nbsp;</td>
                            <td width="180"><b>Packing & Packaging Result</b></td>
                            <td>
                              <select name="PackingResult">
                                    <option value=""></option>
                                    <option value="P">Pass</option>
                                    <option value="F">Fail</option>
                              </select>

                              <script type="text/javascript">
                              <!--
                                    document.frmData.PackingResult.value = "<?= $PackingResult ?>";
                              -->
                              </script>
                            </td>
                        </tr>  
                    <tbody>
                </table>
                 <br />
                <h2>Garment Conformity</h2>
               <table cellspacing="0" cellpadding="5" style="margin-top:-10px;" bordercolor="#ffffff" border="1" width="100%">
                    <tbody>
                    <tr class="sdRowHeader">
                        <td width="20%"><b>Checklist</b></td><td  width="20%"><b>Result</b></td><td  width="60%"><b>Reason / Comments</b></td>
                    </tr>
<?
                    foreach($sCheckListG as $iCheck => $sCheck){
?>
                    <tr><td><?=$sCheck?></td><td><select name="CheckList<?=$iCheck?>"><option value=""></option><option value="NA" <?= (getDbValue("result", "tbl_hybrid_link_report_check_details", "audit_id='$Id' AND check_id='$iCheck'") == "NA"?'selected':'') ?>>Not Applicable</option><option value="NC" <?=(getDbValue("result", "tbl_hybrid_link_report_check_details", "audit_id='$Id' AND check_id='$iCheck'") == "NC"?'selected':'');?>>Not Conform</option><option value="C" <?=(getDbValue("result", "tbl_hybrid_link_report_check_details", "audit_id='$Id' AND check_id='$iCheck'") == "C"?'selected':'')?>>Conform</option></select></td><td><input type="text" size="70" name="CheckReason<?=$iCheck?>" value="<?=getDbValue("remarks", "tbl_hybrid_link_report_check_details", "audit_id='$Id' AND check_id='$iCheck'")?>"></td></tr>
<?
                    }
?>                  
                        <tr><td>&nbsp;</td>
                            <td width="180"><b>Garment Conformity Result</b></td>
                            <td>
                              <select name="ConformityResult">
                                    <option value=""></option>
                                    <option value="P">Pass</option>
                                    <option value="F">Fail</option>
                              </select>

                              <script type="text/javascript">
                              <!--
                                    document.frmData.ConformityResult.value = "<?= $ConformityResult ?>";
                              -->
                              </script>
                            </td>
                        </tr>
                    </tbody>
                </table>
                  <br />
				<h2>Status & Comments</h2>

                            <table border="0" cellpadding="3" cellspacing="0" width="100%">
                                
                            <tr>
                              <td width="140">Final Audit Date</td>
                              <td width="20" align="center">:</td>

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
