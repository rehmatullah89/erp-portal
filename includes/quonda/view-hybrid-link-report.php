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

<?
	$sSizeTitles = "";

	$sSQL = "SELECT size FROM tbl_sizes WHERE id IN ($sSizes) ORDER BY position";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sSizeTitles .= (", ".$objDb->getField($i, 0));

		$sSizeTitles = substr($sSizeTitles, 2);
	}
?>
			  <tr>
			   <td><?= (($iReportId != 8) ? 'Sizes' : 'Range') ?></td>
			    <td align="center">:</td>
			    <td><?= $sSizeTitles ?></td>
			  </tr>
		    </table>

		    <br />
		    <h2 style="margin:0px;">Defects Details</h2>

		    <div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
			  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			    <tr class="sdRowHeader">
				  <td width="60" align="center"><b>#</b></td>
				  <td><b>Code - Check Points</b></td>
				  <td width="100" align="center"><b>Defects</b></td>
				  <td width="200"><b>Area</b></td>
				  <td width="70"><b>Nature</b></td>
			    </tr>

<?
	$iDefects = 0;

	$sSQL = "SELECT * FROM tbl_qa_report_defects WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for($i = 0; $i < $iCount; $i ++)
	{
		if ($objDb->getField($i, 'nature') == 0)
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
			default : $sNature = "N/A";
		}
?>

			    <tr class="sdRowColor">
				  <td align="center"><?= ($i + 1) ?></td>
				  <td><?= $objDb2->getField(0, 0) ?> - <?= $objDb2->getField(0, 1) ?></td>
				  <td align="center"><?= $objDb->getField($i, 'defects') ?></td>
				  <td><?= $objDb3->getField(0, 0) ?></td>
				  <td><?= $sNature ?></td>
			    </tr>
<?
		if($objDb->getField($i, 'remarks') != "")
		{
?>
                <tr class="sdRowColor">
				  <td align="center"><b>Remarks</b></td>
				  <td colspan="4"><?= $objDb->getField($i, 'remarks') ?></td>
			    </tr>
<?
		}
	}

	if ($iCount == 0)
	{
?>

			    <tr class="sdRowColor">
				  <td colspan="5" align="center">No Defect Found!</td>
			    </tr>
<?
	}

//	if ($iGmtsDefective == 0)
//		$iGmtsDefective = $iDefects;
?>
			  </table>

<?
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

				<h2>Program and Sample Size Details</h2>
				<table border="1" bordercolor="#ffffff" cellpadding="0" cellspacing="0" width="100%">
				  <tr valign="top">
					<td width="50%">
                                            <table border="0" cellpadding="3" cellspacing="0" width="100%">
                                                <tr>
                                                    <td width="200">Assortment Qty (Per Carton)</td>
                                                    <td width="20" align="center">:</td>
                                                    <td><?= $AssortmentQty ?></td>
                                                </tr>
                                                <tr>
                                                    <td width="180">Assortment Qty (Per Size)</td>
                                                    <td width="20" align="center">:</td>
                                                    <td><?= $AssortmentQtySize ?></td>
                                                </tr>
                                                <tr>
                                                    <td width="180">Solid Size Qty (# of gmts/carton)</td>
                                                    <td width="20" align="center">:</td>
                                                    <td><?= $SolidSizeQty ?></td>
                                                </tr>
                                                <tr>
                                                    <td width="180">Solid Size Qty?</td>
                                                    <td width="20" align="center">:</td>
                                                    <td>
                                                      <?= ($IsFullPacket == 'Y'?'Full Packet':($IsFullPacket == 'N'?'Blank':'')) ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="180">Lot Quantity</td>
                                                    <td width="20" align="center">:</td>
                                                    <td><?= $AuditQuantity ?></td>
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
						  <td><?= $CartonsRejected ?></td>
					    </tr>

					    <tr>
						  <td>% Defective</td>
						  <td align="center">:</td>
						  <td><?= $PercentDecfective ?></td>
					    </tr>

					    <tr>
						  <td>Acceptable Standard</td>
						  <td align="center">:</td>
						  <td><?= $Standard ?> %</td>
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
                                          <?=$iCartonNos[$c].", "?>
<?
                                        }    
?>
                                      </td>
                                  </tr>
				</table>

				<br />
				<h2>Quantities</h2>

<?
        $sAllPos = $PO;
        if($AdditionalPos != "")
            $sAllPos = $PO.','.$AdditionalPos;
        
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
				    <td><?= $ShipQty ?></td>
			      </tr>
                              <tr>
                                <td>Shipment Date</td>
                                <td align="center">:</td>
                                <td width="82"><?= (($ShipmentDate != "0000-00-00") ? $ShipmentDate : "") ?></td>
                              </tr>
			      <tr>
				    <td>Re-Screen Qty</td>
				    <td align="center">:</td>
				    <td><?= $ReScreenQty ?></td>
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
                                          <td><<?= $TotalGmts ?> (Pcs)</td>
                                    </tr>

                                    <tr>
                                          <td># of GMTS Defective</td>
                                          <td align="center">:</td>
                                          <td><?= $GmtsDefective ?> (Pcs)</td>
                                    </tr>

                                    <tr>
                                          <td>Max Allowable Defects</td>
                                          <td align="center">:</td>
                                          <td><?= $MaxDefects ?></td>
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
                        <td>Measurement Sample Size:</td><td><?= $MeasureSampleSize ?></td><td>Total out of Tolerance</td><td><?= $TotalTolerance ?></td>
                    </tr>
                    <tr>
                        <td>Total Points Of Measure (POM):</td><td><?= $MeasurementPoints ?></td><td>Maximum POM OOT Accepted</td><td><?= formatNumber($MeasurementPoints*0.04,2) ?></td>
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
                        
                        $result = getDbValue("result", "tbl_hybrid_link_report_check_details", "audit_id='$Id' AND check_id='$iCheck'");
?>
                    <tr><td><?=$sCheck?></td><td><?= ($result == 'NA'?'Not Applicable':($result == 'NC'?'Not Conform':($result == 'C'?'Conform':'')))?></td><td><?=getDbValue("remarks", "tbl_hybrid_link_report_check_details", "audit_id='$Id' AND check_id='$iCheck'")?></td></tr>
<?
                    }
?>                  
                        <tr>
                            <td>&nbsp;</td>
                            <td width="180"><b>Packing & Packaging Result</b></td>
                            <td>
                              <?= ($PackingResult == 'P'?'Pass':'Fail') ?>
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
                        
                        $result = getDbValue("result", "tbl_hybrid_link_report_check_details", "audit_id='$Id' AND check_id='$iCheck'");
?>
                    <tr><td><?=$sCheck?></td><td><?= ($result == 'NA'?'Not Applicable':($result == 'NC'?'Not Conform':($result == 'C'?'Conform':'')))?></td><td><?=getDbValue("remarks", "tbl_hybrid_link_report_check_details", "audit_id='$Id' AND check_id='$iCheck'")?></td></tr>
<?
                    }
?>                  
                        <tr><td>&nbsp;</td>
                            <td width="180"><b>Garment Conformity Result</b></td>
                            <td>
                                <?= ($ConformityResult == 'Y'?'Pass':'Fail') ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                  <br />
		    </div>

		   
		    <br />
		    <h2>Status & Comments</h2>

		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
				<td width="100">Final Audit Date</td>
				<td width="20" align="center">:</td>
				<td><?= (($sFinalAuditDate != "0000-00-00") ? formatDate($sFinalAuditDate) : "Not Provided") ?></td>
			  </tr>

			  <tr valign="top">
			    <td>QA Comments</td>
			    <td align="center">:</td>
			    <td><?= nl2br($sComments) ?></td>
			  </tr>
		    </table>
