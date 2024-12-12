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
					<td>Colors</td>
					<td align="center">:</td>
					<td><input type="text" name="Colors" value="<?= $Colors ?>" size="30" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Sample Size</td>
					<td align="center">:</td>
					<td><input type="text" name="TotalGmts" value="<?= $TotalGmts ?>" size="10" maxlength="5" class="textbox" /></td>
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

                                                <td width="80" align="center"><input type="text" id="Defects<?= $i ?>" name="Defects<?= $i ?>" value="<?= $objDb->getField($i, 'defects') ?>" maxlength="3" size="3" class="textbox defectsCount" onchange="$('Sms').value='1';" required=""/></td>
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
					    <td align="center">CAP</td>
					    <td colspan="5"><input type="text" id="Cap<?= $i ?>" name="Cap<?= $i ?>" value="<?= $objDb->getField($i, 'cap') ?>" maxlength="100" class="textbox defectCap" style="width:97.5%;" onchange="$('Sms').value='1';" /></td>
					  </tr>

                                          <tr>
					    <td align="center">Remarks</td>
					    <td colspan="5"><input type="text" id="Remarks<?= $i ?>" name="Remarks<?= $i ?>" value="<?= $objDb->getField($i, 'remarks') ?>" maxlength="100" class="textbox defectCap" style="width:97.5%;" onchange="$('Sms').value='1';" /></td>
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
                                <h2><table border="0" style="text-align:center;" cellpadding="3" cellspacing="0" width="100%"><tr><td style="border:1px; color: white;">PRODUCTION CHECKLIST</td><td style="border:1px; color: white;">TESTING CHECKLIST</td><td style="border:1px; color: white;">OTHER</td></tr></table></h2>

<?
	$sSQL = "SELECT * FROM tbl_bbg_reports WHERE audit_id='$Id'";
	$objDb->query($sSQL);

        $TrimAccssDetail    = $objDb->getField(0, 'trim_access');
        $TestReport         = $objDb->getField(0, 'test_report');
        $LabelMethod        = $objDb->getField(0, 'label_method');
        $SCWDetail          = $objDb->getField(0, 'scw_detail');
        $DWTestRecord       = $objDb->getField(0, 'dw_test_record');
        $Wrap               = $objDb->getField(0, 'wrap');
        $CSPoDetail         = $objDb->getField(0, 'cspo_detail');
        $PullTestReport     = $objDb->getField(0, 'pull_test_report');
        $FTYApproved        = $objDb->getField(0, 'fty_approve');
        $CQDetail           = $objDb->getField(0, 'cq_detail');
        $WashTestRecord     = $objDb->getField(0, 'wash_test_record');
        $AccreditedLab      = $objDb->getField(0, 'acc_lab');
        $ApprovedSample     = $objDb->getField(0, 'approved_sample');
        $FabricWeight       = $objDb->getField(0, 'fabric_weight');
        $PPMeetingRecord    = $objDb->getField(0, 'pp_meeting_record');

        $MeasurementResult     = $objDb->getField(0, 'measurement_result');
        $MeasurementWashStatus = $objDb->getField(0, 'measurement_wash_status');
        $MeasurementComments   = $objDb->getField(0, 'measurement_overall_remarks');
?>

                        <table id="Mytable" border="0" cellpadding="6" cellspacing="0" width="100%">
			      <tr>
				    <td width="200">TRIMS/ACCESSORIES DETAILS</td>
				    <td>
					  <select name="TrimAccssDetail">
						<option value=""></option>
                                                <option value="NA">N/A</option>
						<option value="Y">Yes</option>
						<option value="N">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.TrimAccssDetail.value = "<?= $TrimAccssDetail ?>";
					  -->
					  </script>
                                    </td>
				    <td width="200"> TEST REPORTS </td>
				    <td>
					  <select name="TestReport">
						<option value=""></option>
                                                <option value="NA">N/A</option>
						<option value="Y">Yes</option>
						<option value="N">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.TestReport.value = "<?= $TestReport ?>";
					  -->
					  </script>
                                    </td>
                                    <td width="200">LABELS AND LABELING MITHOD</td>
                                    <td>
					  <select name="LabelMethod">
						<option value=""></option>
                                                <option value="NA">N/A</option>
						<option value="Y">Yes</option>
						<option value="N">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.LabelMethod.value = "<?= $LabelMethod ?>";
					  -->
					  </script>
                                    </td>
 			      </tr>
                              <tr>
				    <td width="200">STYLING /CONSTRUCTION/WASH DETAILS</td>
				    <td>
					  <select name="SCWDetail">
						<option value=""></option>
                                                <option value="NA">N/A</option>
						<option value="Y">Yes</option>
						<option value="N">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.SCWDetail.value = "<?= $SCWDetail ?>";
					  -->
					  </script>
                                    </td>
				    <td width="200"> DRY & WET RUB TEST RECORD </td>
				    <td>
					  <select name="DWTestRecord">
						<option value=""></option>
                                                <option value="NA">N/A</option>
						<option value="Y">Yes</option>
						<option value="N">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.DWTestRecord.value = "<?= $DWTestRecord ?>";
					  -->
					  </script>
                                    </td>
                                    <td width="200">WRAP /SA8000 CERTIFICATE    WRAP/SA8000</td>
                                    <td>
					  <select name="Wrap">
						<option value=""></option>
                                                <option value="NA">N/A</option>
						<option value="Y">Yes</option>
						<option value="N">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.Wrap.value = "<?= $Wrap ?>";
					  -->
					  </script>
                                    </td>
 			      </tr>

                              <tr>
				    <td width="200">CUTTING  SIZE B/D/ COLOR RATIO VS PO DETAILS</td>
				    <td>
					  <select name="CSPoDetail">
						<option value=""></option>
                                                <option value="NA">N/A</option>
						<option value="Y">Yes</option>
						<option value="N">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.CSPoDetail.value = "<?= $CSPoDetail ?>";
					  -->
					  </script>
                                    </td>
				    <td width="200"> PULL TEST REPORT (IF CHILED ORDERAND) MUST ATTACH </td>
				    <td>
					  <select name="PullTestReport">
						<option value=""></option>
                                                <option value="NA">N/A</option>
						<option value="Y">Yes</option>
						<option value="N">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.PullTestReport.value = "<?= $PullTestReport ?>";
					  -->
					  </script>
                                    </td>
                                    <td width="200">FTY  APPROVED ( FACTORY QA'S/COMPLAINCE AUDIT)</td>
                                    <td>
					  <select name="FTYApproved">
						<option value=""></option>
                                                <option value="NA">N/A</option>
						<option value="Y">Yes</option>
						<option value="N">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.FTYApproved.value = "<?= $FTYApproved ?>";
					  -->
					  </script>
                                    </td>
 			      </tr>

                              <tr>
				    <td width="200">COMPLETED QA's FILE  DETAILS </td>
				    <td>
					  <select name="CQDetail">
						<option value=""></option>
                                                <option value="NA">N/A</option>
						<option value="Y">Yes</option>
						<option value="N">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.CQDetail.value = "<?= $CQDetail ?>";
					  -->
					  </script>
                                    </td>
				    <td width="200"> WASHING TEST RECORD  </td>
				    <td>
					  <select name="WashTestRecord">
						<option value=""></option>
                                                <option value="NA">N/A</option>
						<option value="Y">Yes</option>
						<option value="N">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.WashTestRecord.value = "<?= $WashTestRecord ?>";
					  -->
					  </script>
                                    </td>
                                    <td width="200">ACCREDITED LAB </td>
                                    <td>
					  <select name="AccreditedLab">
						<option value=""></option>
                                                <option value="NA">N/A</option>
						<option value="Y">Yes</option>
						<option value="N">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.AccreditedLab.value = "<?= $AccreditedLab ?>";
					  -->
					  </script>
                                    </td>
 			      </tr>

                              <tr>
				    <td width="200">APPROVED SAMPLES (WITH DESIGN APPROVED HISTORY NOTES) </td>
				    <td>
					  <select name="ApprovedSample">
						<option value=""></option>
                                                <option value="NA">N/A</option>
						<option value="Y">Yes</option>
						<option value="N">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.ApprovedSample.value = "<?= $ApprovedSample ?>";
					  -->
					  </script>
                                    </td>
				    <td width="200"> FABRIC WEIGHT(IF SWEATER OR KNITTED )  </td>
				    <td>
					  <select name="FabricWeight">
						<option value=""></option>
                                                <option value="NA">N/A</option>
						<option value="Y">Yes</option>
						<option value="N">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.FabricWeight.value = "<?= $FabricWeight ?>";
					  -->
					  </script>
                                    </td>
                                    <td width="200">PP MEETING RECORD </td>
                                    <td>
					  <select name="PPMeetingRecord">
						<option value=""></option>
                                                <option value="NA">N/A</option>
						<option value="Y">Yes</option>
						<option value="N">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.PPMeetingRecord.value = "<?= $PPMeetingRecord ?>";
					  -->
					  </script>
                                    </td>
 			      </tr>
                            </table>
            <h2><table border="0" style="text-align:left;" cellpadding="3" cellspacing="0" width="100%"><tr><td style="border:1px; color: white;">ORDER DETAILS</td><td style="border:1px; color: white;">PRODUCTION STATUS</td><td style="border:1px; color: white;">SAMPLING STATUS</td></tr></table></h2>
            <table id="Mytable2" border="0" cellpadding="6" cellspacing="0" width="100%">
                <tr>
                    <th width="60"> P.O.#</th>
                    <th width="60"> Color</th>
                    <th width="40">CUTTING (%)</th>
                    <th width="42">PRINTING (%)</th>
                    <th width="32">SEWING (%)</th>
                    <th width="40">WASHING (%)</th>
                    <th width="42">PRESSING (%)</th>
                    <th width="47">PACKAGING (%)</th>
                    <th width="38">PACKING (%)</th>
                    <th width="43">SAMPLING SIZE</th>
                    <th width="43">IF SEMI GARMENTS, BUNDLE# NEEDS</th>
                    <th width="150">REMARKS</th>
                </tr>
                <?
                $m = 0;
                foreach($sPos as $key => $value){
                    $poId = $value['id'];
                    $sColorList = getList('tbl_po_colors', 'id', 'color', "po_id='$poId'");

                    foreach($sColorList as $sColor){
                        $sSQL = "SELECT * FROM tbl_bbg_status WHERE audit_id='$Id' AND po_id='$poId' AND color='$sColor'";
                        $objDb->query($sSQL);

                        $StatusId    = $objDb->getField(0, 'id');
                        $Cutting     = $objDb->getField(0, 'cutting');
                        $Printing    = $objDb->getField(0, 'printing');
                        $Sewing      = $objDb->getField(0, 'sewing');
                        $Washing     = $objDb->getField(0, 'washing');
                        $Pressing    = $objDb->getField(0, 'pressing');
                        $Packaging   = $objDb->getField(0, 'packaging');
                        $Packing     = $objDb->getField(0, 'packing');
                        $Sample_size = $objDb->getField(0, 'sample_size');
                        $SGB         = $objDb->getField(0, 'semi_garment_bundle');
                        $DRemarks    = $objDb->getField(0, 'remarks');
    ?>
                    <tr>
                        <td width="60"><?= $value['name'] ?><input type="hidden" name="PoNo<?= $m ?>" value="<?= $poId ?>"><input type="hidden" name="StatusId<?= $m ?>" value="<?= $StatusId ?>"></td>
                        <td width="40"><input type="hidden" name="Color<?= $m?>" value="<?= $sColor?>"><?= $sColor?></td>
                        <td width="40"><input type="text" name="Cutting<?= $m?>" value="<?= $Cutting?>"></td>
                        <td width="42"><input type="text" name="Printing<?= $m?>" value="<?= $Printing?>"></td>
                        <td width="32"><input type="text" name="Sewing<?= $m?>" value="<?= $Sewing?>"></td>
                        <td width="40"><input type="text" name="Washing<?= $m?>" value="<?= $Washing?>"></td>
                        <td width="42"><input type="text" name="Pressing<?= $m?>" value="<?= $Pressing?>"></td>
                        <td width="47"><input type="text" name="Packaging<?= $m?>" value="<?= $Packaging?>"></td>
                        <td width="38"><input type="text" name="Packing<?= $m?>" value="<?= $Packing?>"></td>
                        <td width="43"><input type="text" name="Sample_size<?= $m?>" value="<?= $Sample_size?>"></td>
                        <td width="43"><input type="text" name="Semi_garment_bundle<?= $m?>" value="<?= $SGB?>"></td>
                        <td width="150"><input type="text" name="DRemarks<?= $m?>" value="<?= $DRemarks?>"></td>
                    </tr>
                    <?
                    $m++;
                    }
                }?>
                <input type="hidden" value="<?= $m ?>" name="PoCount">
            </table>
<?
    // Measurement Sheet starts here
        $sColors = @explode(",", $Colors);
	$iColor  = 0;

	foreach ($sColors as $sColor)
	{
		foreach ($iSizes as $iSize)
		{
			$sSize         = getDbValue("size", "tbl_sizes", "id='$iSize'");
			$iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '$sSize'");


			$sSQL = "SELECT qrs.sample_no, qrss.point_id, qrss.findings
					 FROM tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss
					 WHERE qrs.audit_id='$Id' AND qrs.id=qrss.sample_id AND qrs.size_id='$iSamplingSize' AND qrs.color='$sColor'
					 ORDER BY qrs.sample_no, qrss.point_id";
			$objDb->query($sSQL);

			$iCount        = $objDb->getCount( );
			$sSizeFindings = array( );

			for($i = 0; $i < $iCount; $i ++)
			{
				$iSampleNo = $objDb->getField($i, 'sample_no');
				$iPoint    = $objDb->getField($i, 'point_id');
				$sFindings = $objDb->getField($i, 'findings');

				$sSizeFindings["{$iSampleNo}-{$iPoint}"] = $sFindings;
			}
?>
				<h2 style="margin:0px;">Measurement Sheet (Size: <?= $sSize ?>, Color: <?= $sColor ?>)</h2>

				<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
				  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
					<tr class="sdRowHeader">
					  <td width="40" align="center"><b>#</b></td>
					  <td><b>Measurement Point</b></td>
					  <td width="90" align="center"><b>Specs</b></td>
					  <td width="90" align="center"><b>Tolerance</b></td>
					  <td width="60" align="center"><b>1</b></td>
					  <td width="60" align="center"><b>2</b></td>
					  <td width="60" align="center"><b>3</b></td>
					  <td width="60" align="center"><b>4</b></td>
					  <td width="60" align="center"><b>5</b></td>
					</tr>
<?
			$sSQL = "SELECT point_id, specs,
							(SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
							(SELECT point FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Point
					 FROM tbl_style_specs
					 WHERE style_id='$Style' AND size_id='$iSamplingSize' AND version='0' AND specs!='0' AND specs!=''
					 ORDER BY id";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for($i = 0; $i < $iCount; $i ++)
			{
				$iPoint     = $objDb->getField($i, 'point_id');
				$sSpecs     = $objDb->getField($i, 'specs');
				$sPoint     = $objDb->getField($i, '_Point');
				$sTolerance = $objDb->getField($i, '_Tolerance');
?>

					<tr class="sdRowColor">
					  <td align="center"><?= ($i + 1) ?></td>
					  <td><?= $sPoint ?></td>
					  <td align="center"><?= $sSpecs ?></td>
					  <td align="center"><?= $sTolerance ?></td>
<?
				for ($j = 1; $j <= 5; $j ++)
				{
?>
				  	<td align="center"><input type="text" name="Specs<?= $iSamplingSize ?>_<?= $iColor ?>_<?= $iPoint ?>_<?= $j ?>" value="<?= $sSizeFindings["{$j}-{$iPoint}"] ?>" size="5" maxlength="10" class="textbox" /></td>
<?
				}
?>
			    	</tr>
<?
			}
?>
				</table>
				</div>
<?
		}


		$iColor ++;
	}
?>
				<br />
                                <h2>Measurement Result</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="75">Result<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="MeasurementResult">
						<option value=""></option>
						<option value="P">Pass</option>
						<option value="F">Fail</option>
						<option value="H">Pending</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.MeasurementResult.value = "<?= $MeasurementResult ?>";
					  -->
					  </script>
					</td>
				  </tr>
                                  <tr>
					<td width="75">Wash Status</td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="MeasurementWashStatus">
						<option value=""></option>
						<option value="B">BW /BP</option>
						<option value="A">AW /AP</option>
						<option value="N">NON WASH</option>
					  </select>
					  <script type="text/javascript">
					  <!--
						document.frmData.MeasurementWashStatus.value = "<?= $MeasurementWashStatus ?>";
					  -->
					  </script>
					</td>
				  </tr>

				  <tr valign="top">
					<td>Remarks</td>
					<td align="center">:</td>
					<td><textarea name="MeasurementComments" class="textarea" style="width:98%; height:80px;"><?= $MeasurementComments ?></textarea></td>
				  </tr>
				</table>

				<br />
				<h2>Status & Comments</h2>

				<table border="0" cellpadding="3" cellspacing="0" >
				  <tr>
					<td width="100">Ship Qty</td>
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
                    <td><textarea name="Comments" class="textarea" cols="110" rows="5"><?= $Comments ?></textarea></td>
				  </tr>
				</table>
