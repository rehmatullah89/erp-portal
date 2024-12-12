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
                        $sPos           = "";
                        $sPosArr        = array();
                        $sSelectedPos   = $iPoId;

                        if ($sAdditionalPos != "")
                                $sSelectedPos .= ",{$sAdditionalPos}";

                        $sSQL = "SELECT id, CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id IN ($sSelectedPos) ORDER BY order_no";

                        if ($objDb->query($sSQL) == true)
                        {
                            $iCount = $objDb->getCount( );

                            for ($i = 0; $i < $iCount; $i ++)
                            {
                                    $iPo = $objDb->getField($i, 0);
                                    $sPo = $objDb->getField($i, 1);
                                    $sPos .= ($objDb->getField($i, 1).", ");

                                    $sPosArr[] = array("id" => $iPo, "name" => $sPo);
                            }
                        }
?>
			  <tr valign="top">
			    <td>PO(s)</td>
			    <td align="center">:</td>
                            <td><?= rtrim($sPos, ", ") ?></td>
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
                    <br/><br/>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                       <tr><td>
                        <table style="width: 50%; margin-top: 0px; float: left; " cellpadding="2">
                            <tr>
                                <th colspan="2" align="center"><h2>Color Wise Inspected Quantities</h2></th>
                            </tr>
                            <tr>
                                <th>Color</th>
                                <th>Quantity</th>
                            </tr>
<?
                            $iColors = explode(",", $sColors);
                            foreach ($iColors as $sColor){
?>                                            
                            <tr>
                                <td><?=$sColor?></td>
                                <td><?= getDbValue("quantity", "tbl_qa_color_quantities", "audit_id='$Id' AND color='$sColor'")?></td>
                            </tr>
<?                                      
                            }
?>
                        </table>
                         <table style="width: 50%; margin-top: 0px; float: right; " cellpadding="2">
                            <tr>
                                <th colspan="2" align="center"><h2>Po Shipment Quantities</h2></th>
                            </tr>
                            <tr>
                                <th>PO #</th>
                                <th>Shipment Quantity</th>
                            </tr>
<?
                            foreach ($sPosArr as $iKey => $sPo){
?>                                            
                            <tr>
                                <td><?=$sPo['name']?></td>
                                <td><?= getDbValue("quantity", "tbl_qa_po_ship_quantities", "audit_id='$Id' AND po_id='{$sPo['id']}'")?></td>
                            </tr>
<?                                      
                            }
?>
                         </table>   
                        </td></tr>
                   </table>  
		    <br />
		    <h2 style="margin:0px;">Defects Details</h2>

		    <div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
			  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			    <tr class="sdRowHeader">
				  <td width="60" align="center"><b>#</b></td>
				  <td><b>Code - Check Points</b></td>
				  <td width="70" align="center"><b>Defects</b></td>
				  <td width="70" align="center"><b>Sample #</b></td>
				  <td width="180"><b>Area</b></td>
                                  <td width="100"><b>Color</b></td>
				  <td width="70"><b>Nature</b></td>
			    </tr>

<?
	$iDefects = 0;

	$sSQL = "SELECT * FROM tbl_qa_report_defects WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for($i = 0; $i < $iCount; $i ++)
	{
		$sDColor = $objDb->getField($i, 'color');
		
		if ($objDb->getField($i, 'nature') > 0)
				$iDefects += $objDb->getField($i, 'defects');


		$sSQL = ("SELECT code, defect FROM tbl_defect_codes WHERE id='".$objDb->getField($i, 'code_id')."'");
		$objDb2->query($sSQL);

		$sSQL = ("SELECT area FROM tbl_defect_areas WHERE id='".$objDb->getField($i, 'area_id')."'");
		$objDb3->query($sSQL);
?>

			    <tr class="sdRowColor">
				  <td align="center"><?= ($i + 1) ?></td>
				  <td><?= $objDb2->getField(0, 0) ?> - <?= $objDb2->getField(0, 1) ?></td>
				  <td align="center"><?= $objDb->getField($i, 'defects') ?></td>
                                  <td align="center"><?= $objDb->getField($i, 'sample_no') ?></td>
				  <td><?= $objDb3->getField(0, 0) ?></td>
                                  <td><?=$sDColor?></td>
				  <td><?= (($objDb->getField($i, 'nature') == 1) ? "MAJOR" : "minor") ?></td>
			    </tr>
<?
		if($objDb->getField($i, 'remarks') != "")
		{
?>
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

			  <table border="1" bordercolor="#ffffff" cellpadding="0" cellspacing="0" width="100%">
			    <tr valign="top">
				  <td width="50%">

				    <h2>Work-ManShip</h2>

				    <table border="0" cellpadding="3" cellspacing="0" width="100%">
					  <tr>
					    <td width="140">Total GMTS Inspected</td>
					    <td width="20" align="center">:</td>
					    <td><?= $iTotalGmts ?> (Pcs)</td>
					  </tr>

					  <tr>
					    <td># of GMTS Defective</td>
					    <td align="center">:</td>
					    <td><?= $iGmtsDefective ?> (Pcs)</td>
					  </tr>

					  <tr>
					    <td>Max Allowable Defects</td>
					    <td align="center">:</td>
					    <td><?= $iMaxDefects ?></td>
					  </tr>

					  <tr>
					    <td>Number of Defects</td>
					    <td align="center">:</td>
					    <td><?= (int)$iDefects ?></td>
					  </tr>

					  <tr>
					    <td>D.H.U</td>
					    <td align="center">:</td>
					    <td><?= @round(( ($iDefects / $iTotalGmts) * 100), 2) ?>%</td>
					  </tr>
				    </table>

				  </td>

				  <td width="50%">

				    <h2>Assortment</h2>

				    <table border="0" cellpadding="3" cellspacing="0" width="100%">
					  <tr>
					    <td width="140">Total Cartons Inspected</td>
					    <td width="20" align="center">:</td>
					    <td><?= $iTotalCartons ?></td>
					  </tr>

					  <tr>
					    <td># of Cartons Rejected</td>
					    <td align="center">:</td>
					    <td><?= $iCartonsRejected ?></td>
					  </tr>

					  <tr>
					    <td>% Defective</td>
					    <td align="center">:</td>
					    <td><?= $fPercentDecfective ?></td>
					  </tr>

					  <tr>
					    <td>Acceptable Standard</td>
					    <td align="center">:</td>
					    <td><?= $fStandard ?> %</td>
					  </tr>

					  <tr>
					    <td>D.H.U</td>
					    <td align="center">:</td>
					    <td><?= @round(( ($fCartonsRejected / $fTotalCartons) * 100), 2) ?>%</td>
					  </tr>
				    </table>

				  </td>
			    </tr>
			  </table>
		    </div>
<br/>
                    <table border="0" style="text-align:center;" cellpadding="1" cellspacing="0" width="100%"><tr><td width="33%"><h2>Material</h2></td><td width="33%"><h2>Packaging</h2></td><td width="33%"><h2>Appearance</h2></td></tr></table>

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
                                        <?= $FKPanelQlty ?>					 
                                    </td>
				    <td width="170">Price Tag</td>
                                    <td>
                                        <?= $PriceTag ?>					 
                                    </td>
                                    <td width="170">Un-trimmed Thread</td>
                                    <td>
                                        <?= $UntrimmedThread ?>					 
                                    </td>
 			      </tr>
                              <tr>
				    <td width="170">Hand Feel</td>
				    <td>
					  <?= $HandFeel ?>		
                                    </td>
				    <td width="170">Special Hangtag</td>
                                    <td>
					 <?= $SpecialHangtag ?>	
                                    </td>
                                    <td width="170">Hand feel</td>
                                    <td>
					 <?= $HandFeel2 ?>	
                                    </td>
 			      </tr>

                              <tr>
				    <td width="170">Color</td>
				    <td>
					 <?= $Color ?>	
			            </td>
				    <td width="170">Tissue Paper / Stuffing</td>
                                    <td>
					<?= $TissueStuffing ?>	
				    </td>
                                    <td width="170">Fit on Form</td>
                                    <td>
					<?= $FitOnForm ?>	
				    </td>
 			      </tr>

                              <tr>
				    <td width="170">Shade Lot </td>
				    <td>
					<?= $ShadeLot ?>	
				    </td>
				    <td width="170">Polybag</td>
                                    <td>
					<?= $Polybag ?>						
                                    </td>
                                    <td width="170">Twisted / Unbalance</td>
                                    <td>
					<?= $Twisted ?>						
                                    </td>
 			      </tr>

                              <tr>
				    <td width="170">Lining </td>
				    <td>
					<?= $Lining ?>
				    </td>
				    <td width="170">Packing Method </td>
                                    <td>
					 <?= $PackingMethod ?>
				    </td>
                                    <td width="170" colspan="2"><h3>Others </h3></td>
 			      </tr>
                              <tr>
                                   <td width="170">Trim Fabric </td>
                                    <td>
					 <?= $TrimFabric ?>
				    </td>
                                    <td width="170">Spare Button</td>
                                    <td>
					  <?= $SpareButton ?>
				    </td>
                                    <td width="170">Measurement</td>
                                    <td>
					 <?= $Measurement ?>
				    </td>
                              </tr>
                              <tr>
                                  <td width="170">Interlining</td>
                                    <td>
					 <?= $Interlining ?>
                                    </td>
                                    <td width="170">OSOC EAN / INFO. Sticker</td>
                                    <td>
					  <?= $InfoSticker ?>
				    </td>
                                    <td width="170">Smell</td>
                                    <td>
					 <?= $Smell ?>
				    </td>
                              </tr>
                              <tr>
                                  <td width="170">Shoulder Pad</td>
                                    <td>
					<?= $ShoulderPad ?>
				    </td>
                                    <td width="170">Packing Assortment</td>
                                    <td>
					 <?= $PackingAssortment ?>
				    </td>
                                     <td width="170">Moisture Test Result</td>
                                    <td>
					<?= $MoistureResult ?>
				    </td>
                              </tr>
                              <tr>
                                  <td width="170">Washing Effect</td>
                                  <td>
                                      <?= $WashingEffect ?>
                                  </td>
                                   <td width="170">Export Carton Size</td>
                                  <td>
                                       <?= $ExpCartonSize ?>
                                  </td>
                                    <td width="170">"Pass" Azo Test Report No.</td>
                                  <td>
                                      <?= $AzoReportNo ?>
                                  </td>
                              </tr>
                              <tr>
                                  <td width="170">Down Pouch</td>
                                    <td>
					  <?= $DownPouch ?>
				    </td>
                                    <td width="170">Export Carton Weight</td>
                                    <td>
					<?= $ExportCartonWeight ?>
				    </td>
                                    <td width="170">Please specify:</td>
                                    <td>
					  <?= $PleaseSpecify ?>
				    </td>
                              </tr>
                              <tr>
                                  <td width="170">Padding</td>
                                    <td>
					 <?= $Padding ?>
				    </td>
                                    <td width="170">Carton Label</td>
                                    <td>
					<?= $CartonLabel ?>
			            </td>
                                    <td width="170" colspan="2"><h3>Trims </h3></td>                                    
                              </tr>
                              <tr>
                                  <td colspan="4">&nbsp;</td>
                                   <td width="170"> Main Label </td>
				    <td>
                                        <?= $MainLabel ?>					 
		                    </td>
                              </tr>
                              <tr>
                                  <td colspan="4">&nbsp;</td>
                                  <td width="170">Washing Label</td>
				    <td>
					<?= $WashingLabel ?>	
                                    </td>
                              </tr>
                              <tr>
                                  <td colspan="4">&nbsp;</td>
                                  <td width="170"> Size Label </td>
				    <td>
					 <?= $SizeLabel ?>	
				    </td>
                              </tr>
                              <tr>
                                  <td colspan="4">&nbsp;</td>
                                  <td width="170"> Care Label  </td>
				    <td>
					<?= $CareLabel ?>	
                                    </td>
                              </tr>    
                              <tr>
                                  <td colspan="4">&nbsp;</td>
                                  <td width="170"> International Size Label  </td>
				    <td>
					  <?= $IntSizeLabel ?>
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
                                        <td width="30">#</td>
                                        <td><b>Drawn Carton No.</b></td>
                                        <td><b>Assortment Check</b></td>
                                    </tr>
<?
                                $i=1;
                                if(count($iDrawnCartonNos) > 0){
                                    foreach($iDrawnCartonNos as $key => $iCartonNo){
?>
                                    <tr>
                                        <td><?=$i?>-</td>
                                        <td><?=$iCartonNo?></td>
                                        <td><?= ($iAssortmentChecks[$key] == 'Y'? 'Yes': 'No')?></td>
                                    </tr>                                        
<?              
                                        $i++;
                                    }
                                }
?>
                                </table><br />
		    <br />
		    <h2>Quantities</h2>

<?
	$iOrderQty = getDbValue("SUM(quantity)", "tbl_po", "id IN ({$sSelectedPos})");
?>
		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
			    <td width="140">Order Qty</td>
			    <td width="20" align="center">:</td>
			    <td><?= $iOrderQty ?></td>
			    <td width="140">Total Cartons Required</td>
			    <td width="20" align="center">:</td>
			    <td><?= $fCartonsRequired ?></td>
			  </tr>

			  <tr>
			    <td>Ship Qty</td>
			    <td align="center">:</td>
			    <td><?= $iShipQty ?></td>
			    <td>Total Cartons Shipped</td>
			    <td align="center">:</td>
			    <td><?= $fCartonsShipped ?></td>
			  </tr>

			  <tr>
			    <td>Re-Screen Qty</td>
			    <td align="center">:</td>
			    <td><?= $iReScreenQty ?></td>
			    <td>Deviation</td>
			    <td align="center">:</td>
			    <td><?= @round(( ($fCartonsShipped / $fCartonsRequired) * 100), 2) ?>%</td>
			  </tr>

			  <tr>
			    <td>Deviation</td>
			    <td align="center">:</td>
			    <td colspan="4"><?= @round(( ($iShipQty / $iOrderQty) * 100), 2) ?>%</td>
			  </tr>
		    </table>

		    <br />
		    <h2>Status & Comments</h2>

		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  
			  <tr>
				<td width="250">Garment Measurement Inspection</td>
				<td width="20" align="center">:</td>
				<td ><?= ($GarmentMeasurement == 'P'? 'Pass': ($GarmentMeasurement == 'F'? 'Fail': ''))?></td>
			  </tr>

                          <tr>
				<td width="250">Moisture measurement inspection result</td>
				<td align="center">:</td>
				<td><?= ($MoistureMeasurement == 'P'? 'Pass': ($MoistureMeasurement == 'F'? 'Fail': ''))?></td>
			  </tr>
                          
			  <tr>
				<td width="250">Final Audit Date</td>
				<td align="center">:</td>
				<td><?= (($sFinalAuditDate != "0000-00-00") ? formatDate($sFinalAuditDate) : "Not Provided") ?></td>
			  </tr>

			  <tr valign="top">
			    <td>QA Comments</td>
			    <td align="center">:</td>
			    <td><?= nl2br($sComments) ?></td>
			  </tr>
		    </table>
