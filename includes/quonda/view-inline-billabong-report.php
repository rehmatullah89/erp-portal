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
			    <td width="90">Vendor</td>
			    <td width="20" align="center">:</td>
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
			    <td><?= $sGroup ?></td>
			  </tr>

			  <tr>
				<td>Style</td>
				<td align="center">:</td>
				<td><?= getDbValue("style", "tbl_styles", "id='$iStyle'") ?></td>
			  </tr>

<?
	$sPos = "";

	$sSQL = "SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id IN ($sAdditionalPos) ORDER BY order_no";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (", ".$objDb->getField($i, 0));
	}
?>
			  <tr valign="top">
			    <td>PO(s)</td>
			    <td align="center">:</td>
			    <td><?= ($sPO.$sPos) ?></td>
			  </tr>
<?
        if(!empty($sAdditionalPos))
            $sSelectedPos = $iPoId.','.$sAdditionalPos;
        else
            $sSelectedPos = $iPoId;
        $sPosArr = array();
	$sSQL = "SELECT id, CONCAT(order_no, ' ', order_status) AS _Po
	         FROM tbl_po
	         WHERE vendor_id='$iVendor' AND FIND_IN_SET(id, '$sSelectedPos')
	         ORDER BY FIELD(id,{$sSelectedPos})";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iPo = $objDb->getField($i, 0);
		$sPo = $objDb->getField($i, 1);

		$sPosArr[] = array("id" => $iPo, "name" => $sPo);
	}
?>
			  <tr>
			    <td>Audit Stage</td>
			    <td align="center">:</td>
			    <td><?= $sAuditStagesList[$sAuditStage] ?></td>
			  </tr>

<?
	switch ($sAuditStatus)
	{
		case "1st" : $sAuditStatus = "1st"; break;
		case "2nd" : $sAuditStatus = "2nd"; break;
		case "3rd" : $sAuditStatus = "3rd"; break;
		case "4th" : $sAuditStatus = "4th"; break;
		case "5th" : $sAuditStatus = "5th"; break;
		case "6th" : $sAuditStatus = "6th"; break;
	}
?>
			  <tr>
			    <td>Audit Status</td>
			    <td align="center">:</td>
			    <td><?= $sAuditStatus ?></td>
			  </tr>

<?
	switch ($sAuditResult)
	{
		case "P" : $sAuditResult = "Pass"; break;
		case "F" : $sAuditResult = "Fail"; break;
		case "H" : $sAuditResult = "Hold"; break;
	}
?>
			  <tr>
			    <td>Audit Result</td>
			    <td align="center">:</td>
			    <td><?= $sAuditResult ?></td>
			  </tr>

<?
	switch ($sAuditType)
	{
		case "B"  : $sAuditType  = "Bulk"; break;
		case "BG" : $sAuditType = "B-Grade"; break;
		case "SS" : $sAuditType = "Sales Sample"; break;
	}


	if ($iReportId != 8)
	{
?>
			  <tr>
			    <td>QA Type</td>
			    <td align="center">:</td>
			    <td><?= $sAuditType ?></td>
			  </tr>
<?
	}
?>

			  <tr>
			   <td>Colors</td>
			    <td align="center">:</td>
			    <td><?= $sColors ?></td>
			  </tr>

  		    </table>

		    <br />
		 <h2 style="margin:0px;">Defects Details</h2>

		    <div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
			  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			    <tr class="sdRowHeader">
				  <td width="50" align="center"><b>#</b></td>
				  <td><b>Code - Check Points</b></td>
				  <td width="70" align="center"><b>Defects</b></td>
				  <td width="70" align="center"><b>Sample #</b></td>
				  <td width="180"><b>Area</b></td>
				  <td width="100" align="center"><b>Nature</b></td>
			    </tr>

<?
	$iDefects = 0;

	$sSQL = "SELECT * FROM tbl_qa_report_defects WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for($i = 0; $i < $iCount; $i ++)
	{
		$iDefects += $objDb->getField($i, 'defects');

		$sSQL = ("SELECT code, defect FROM tbl_defect_codes WHERE id='".$objDb->getField($i, 'code_id')."'");
		$objDb2->query($sSQL);

		$sSQL = ("SELECT area FROM tbl_defect_areas WHERE id='".$objDb->getField($i, 'area_id')."'");
		$objDb3->query($sSQL);


		switch ($objDb->getField($i, "nature"))
		{
			case 1 : $sNature = "Major"; break;
			case 0 : $sNature = "Minor"; break;
			case 2 : $sNature = "Critical"; break;
		}
?>

			    <tr class="sdRowColor">
				  <td align="center"><?= ($i + 1) ?></td>
				  <td><?= $objDb2->getField(0, 0) ?> - <?= $objDb2->getField(0, 1) ?></td>
				  <td align="center"><?= $objDb->getField($i, 'defects') ?></td>
                                  <td align="center"><?= $objDb->getField($i, 'sample_no') ?></td>
				  <td><?= $objDb3->getField(0, 0) ?></td>
				  <td align="center"><?= $sNature ?></td>
			    </tr>
<?
		if ($objDb->getField($i, 'cap') != "" || $objDb->getField($i, 'remarks') != "")
		{
?>
			    <tr class="sdRowColor">
				  <td align="center"><b>CAP</b></td>
				  <td colspan="5"><?= $objDb->getField($i, 'cap') ?></td>
			    </tr>
                            <tr class="sdRowColor">
				  <td align="center"><b>Remarks</b></td>
				  <td colspan="5"><?= $objDb->getField($i, 'remarks') ?></td>
			    </tr>
<?
		}
	}

	if ($iCount == 0)
	{
?>

			    <tr class="sdRowColor">
				  <td colspan="6" align="center">No Defect Found!</td>
			    </tr>
<?
	}

//	if ($iGmtsDefective == 0)
//		$iGmtsDefective = $iDefects;
?>
			  </table>
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

                        <table id="Mytable" border="1" style="margin-top:-5px;" cellpadding="6" cellspacing="0" width="100%">
			      <tr>
				    <td width="200">TRIMS/ACCESSORIES DETAILS</td>
				    <td>
					<?= ($TrimAccssDetail == 'Y'?'Yes':($TrimAccssDetail == 'N'?'No':'Not Provided')) ?>
                                    </td>
				    <td width="200"> TEST REPORTS </td>
				    <td>
					<?= ($TestReport == 'Y'?'Yes':($TestReport == 'N'?'No':'Not Provided')) ?>
                                    </td>
                                    <td width="200">LABELS AND LABELING MITHOD</td>
                                    <td>
                                        <?= ($LabelMethod == 'Y'?'Yes':($LabelMethod == 'N'?'No':'Not Provided')) ?>
			            </td>
 			      </tr>
                              <tr>
				    <td width="200">STYLING /CONSTRUCTION/WASH DETAILS</td>
				    <td>
                                        <?= ($SCWDetail == 'Y'?'Yes':($SCWDetail == 'N'?'No':'Not Provided')) ?>
			            </td>
				    <td width="200"> DRY & WET RUB TEST RECORD </td>
				    <td>
                                      <?= ($DWTestRecord == 'Y'?'Yes':($DWTestRecord == 'N'?'No':'Not Provided')) ?>
                                    </td>
                                    <td width="200">WRAP /SA8000 CERTIFICATE    WRAP/SA8000</td>
                                    <td>
                                        <?= ($Wrap == 'Y'?'Yes':($Wrap == 'N'?'No':'Not Provided')) ?>
                                    </td>
 			      </tr>

                              <tr>
				    <td width="200">CUTTING  SIZE B/D/ COLOR RATIO VS PO DETAILS</td>
				    <td>
                                        <?= ($CSPoDetail == 'Y'?'Yes':($CSPoDetail == 'N'?'No':'Not Provided')) ?>
                                    </td>
				    <td width="200"> PULL TEST REPORT (IF CHILED ORDERAND) MUST ATTACH </td>
				    <td>
                                        <?= ($PullTestReport == 'Y'?'Yes':($PullTestReport == 'N'?'No':'Not Provided')) ?>
                                    </td>
                                    <td width="200">FTY  APPROVED ( FACTORY QA'S/COMPLAINCE AUDIT)</td>
                                    <td>
                                        <?= ($FTYApproved == 'Y'?'Yes':($FTYApproved == 'N'?'No':'Not Provided')) ?>
			            </td>
 			      </tr>

                              <tr>
				    <td width="200">COMPLETED QA's FILE  DETAILS </td>
				    <td>
                                        <?= ($CQDetail == 'Y'?'Yes':($CQDetail == 'N'?'No':'Not Provided')) ?>
		                    </td>
				    <td width="200"> WASHING TEST RECORD  </td>
				    <td>
                                        <?= ($WashTestRecord == 'Y'?'Yes':($WashTestRecord == 'N'?'No':'Not Provided')) ?>
			            </td>
                                    <td width="200">ACCREDITED LAB </td>
                                    <td>
                                        <?= ($AccreditedLab == 'Y'?'Yes':($AccreditedLab == 'N'?'No':'Not Provided')) ?>
		                    </td>
 			      </tr>

                              <tr>
				    <td width="200">APPROVED SAMPLES (WITH DESIGN APPROVED HISTORY NOTES) </td>
				    <td>
                                        <?= ($ApprovedSample == 'Y'?'Yes':($ApprovedSample == 'N'?'No':'Not Provided')) ?>
		                    </td>
				    <td width="200"> FABRIC WEIGHT(IF SWEATER OR KNITTED )  </td>
				    <td>
                                        <?= ($FabricWeight == 'Y'?'Yes':($FabricWeight == 'N'?'No':'Not Provided')) ?>
                                    </td>
                                    <td width="200">PP MEETING RECORD </td>
                                    <td>
                                        <?= ($PPMeetingRecord == 'Y'?'Yes':($PPMeetingRecord == 'N'?'No':'Not Provided')) ?>
                                    </td>
 			      </tr>
                            </table>
            <h2><table border="0" style="text-align:left;" cellpadding="3" cellspacing="0" width="100%"><tr><td style="border:1px; color: white;">ORDER DETAILS</td><td style="border:1px; color: white;">PRODUCTION STATUS</td><td style="border:1px; color: white;">SAMPLING STATUS</td></tr></table></h2>
            <table id="Mytable2" border="0" cellpadding="6" cellspacing="0" width="100%">
                <tr>
                    <th width="60"> P.O.#</th>
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

                foreach($sPosArr as $key => $value){
                    $poId = $value['id'];
                    $sSQL = "SELECT * FROM tbl_bbg_status WHERE audit_id='$Id' AND po_id='$poId'";
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
                    <td width="60"><?= $value['name'] ?></td>
                    <td width="40"><?= $Cutting?></td>
                    <td width="42"><?= $Printing?></td>
                    <td width="32"><?= $Sewing?></td>
                    <td width="40"><?= $Washing?></td>
                    <td width="42"><?= $Pressing?></td>
                    <td width="47"><?= $Packaging?></td>
                    <td width="38"><?= $Packing?></td>
                    <td width="43"><?= $Sample_size?></td>
                    <td width="43"><?= $SGB?></td>
                    <td width="150"><?= $DRemarks?></td>
                </tr>
                <?
                    $m++;
                } ?>
                <input type="hidden" value="<?= $m ?>" name="PoCount">
            </table>
            <br/>
<?
        $sColors = @explode(",", $sColors);
	$iSizes  = @explode(",", $sSizes);

	foreach ($sColors as $sColor)
	{
		foreach ($iSizes as $iSize)
		{
				$sSize         = getDbValue("size", "tbl_sizes", "id='$iSize'");
				$iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '$sSize'");


				$sSQL = "SELECT qrs.sample_no, qrss.point_id, qrss.findings
						 FROM tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss
						 WHERE qrs.audit_id='$Id' AND qrs.id=qrss.sample_id AND qrs.size_id='$iSamplingSize' AND (qrs.color='$sColor' OR qrs.color='')
						 ORDER BY qrs.sample_no, qrss.point_id";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				if ($iCount == 0)
					continue;


				$sSizeFindings = array( );

				for($i = 0; $i < $iCount; $i ++)
				{
					$iSampleNo = $objDb->getField($i, 'sample_no');
					$iPoint    = $objDb->getField($i, 'point_id');
					$sFindings = $objDb->getField($i, 'findings');

					$sSizeFindings["{$iSampleNo}-{$iPoint}"] = $sFindings;
				}
?>
		    <h2 style="margin:0px;">Measurement Sheet (Size: <?= $sSize ?>, Color: <?= (($sColor == "") ? $sColors : $sColor) ?>)</h2>

		    <div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
			  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			    <tr class="sdRowHeader">
				  <td width="40" align="center"><b>#</b></td>
				  <td><b>Measurement Point</b></td>
				  <td width="90" align="center"><b>Specs</b></td>
				  <td width="90" align="center"><b>Tolerance</b></td>
				  <td width="50" align="center"><b>1</b></td>
				  <td width="50" align="center"><b>2</b></td>
				  <td width="50" align="center"><b>3</b></td>
				  <td width="50" align="center"><b>4</b></td>
				  <td width="50" align="center"><b>5</b></td>
			    </tr>
<?
				$sSQL = "SELECT point_id, specs,
								(SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
								(SELECT point FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Point
						 FROM tbl_style_specs
						 WHERE style_id='$iStyle' AND size_id='$iSamplingSize' AND version='0' AND specs!='0' AND specs!=''
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
				  <td align="center"><?= $sSizeFindings["{$j}-{$iPoint}"] ?></td>
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
		}
?>
        <br />
		    <h2>Measurement Result</h2>

		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
				<td width="140">Result</td>
				<td width="20" align="center">:</td>
				<td><?= (($MeasurementResult == "P") ? "Pass" : (($MeasurementResult == "F") ? "Fail" : "Pending")) ?></td>
			  </tr>

              <tr>
				<td width="140">Wash Status</td>
				<td width="20" align="center">:</td>
				<td><?= (($MeasurementWashStatus == "B") ? "BW /BP" : (($MeasurementWashStatus == "A") ? "AW /AP" : "NON WASH")) ?></td>
			  </tr>

			  <tr valign="top">
			    <td>Remarks</td>
			    <td align="center">:</td>
			    <td><?= nl2br($MeasurementComments) ?></td>
			  </tr>
			</table>

		    <br />
		    <h2>Comments</h2>

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
			    <td><?= nl2br($sComments) ?></td>
			  </tr>
		    </table>

			<br />
