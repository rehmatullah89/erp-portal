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
		$sVpoNo                = $objDb->getField(0, "vpo_no");
		$sReinspection         = $objDb->getField(0, "reinspection"); 
		$sGarmentTest          = $objDb->getField(0, "garment_test");
		$sShadeBand            = $objDb->getField(0, "shade_band");
		$sQaFile               = $objDb->getField(0, "qa_file");
		$sFabricTest           = $objDb->getField(0, "fabric_test");
		$sPpMeeting            = $objDb->getField(0, "pp_meeting");
		$sFittingTorque        = $objDb->getField(0, "fitting_torque");
		$sColorCheck           = $objDb->getField(0, "color_check");
		$sAccessoriesCheck     = $objDb->getField(0, "accessories_check");
		$sMeasurementCheck     = $objDb->getField(0, "measurement_check");
		$sCapOthers            = $objDb->getField(0, "cap_others");
		$sCartonNo             = $objDb->getField(0, "carton_no");
		$iMeasurementSampleQty = $objDb->getField(0, "measurement_sample_qty");
		$iMeasurementDefectQty = $objDb->getField(0, "measurement_defect_qty");
	}
?>
		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
			    <td width="140">Vendor/Factory</td>
			    <td width="20" align="center">:</td>
			    <td><?= $sVendor ?></td>
			  </tr>

			  <tr>
			    <td>Auditor</td>
			    <td align="center">:</td>
			    <td><?= $sAuditor ?></td>
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
				<td>Style</td>
				<td align="center">:</td>
				<td><?= getDbValue("style", "tbl_styles", "id='$iStyle'") ?></td>
			  </tr>
			  
			  <tr valign="top">
				<td>Master ID</td>
				<td align="center">:</td>
				<td><?= $iMasterId ?></td>
			  </tr>

<?
	if ($sVpoNo != "")
	{
?>
			  <tr valign="top">
				<td>VPO No</td>
				<td align="center">:</td>
				<td><?= $sVpoNo ?></td>
			  </tr>
<?
	}
?>

			  <tr>
			    <td>Audit Stage</td>
			    <td align="center">:</td>
			    <td><?= $sAuditStagesList[$sAuditStage] ?></td>
			  </tr>

			  <tr>
			    <td>Audit Result</td>
			    <td align="center">:</td>
			    <td><?= (($sAuditResult == "P") ? "Accepted" : (($sAuditResult == "F") ? "Rejected" : "Hold")) ?></td>
			  </tr>
			  
			  <tr valign="top">
				<td>Re-Inspection</td>
				<td align="center">:</td>
				<td><?= (($sReinspection == "Y") ? "Yes" : "No") ?></td>
			  </tr>			  

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
			   <td>Sizes</td>
			    <td align="center">:</td>
			    <td><?= $sSizeTitles ?></td>
			  </tr>
		    </table>

		    <br />
		    <h2 style="margin:0px;">Defects Details</h2>

		    <div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
			  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			    <tr class="sdRowHeader">
				  <td width="50" align="center"><b>#</b></td>
				  <td><b>Code - Check Points</b></td>
				  <td width="100" align="center"><b>Defects</b></td>
				  <td width="200"><b>Area</b></td>
				  <td width="100" align="center"><b>Nature</b></td>
			    </tr>

<?
	$iDefects = 0;

	$sSQL = "SELECT * FROM tbl_qa_report_defects WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for($i = 0; $i < $iCount; $i ++)
	{
		if ($objDb->getField($i, "nature") > 0)
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
				  <td align="center"><?= $sNature ?></td>
			    </tr>
<?
		if ($objDb->getField($i, 'cap') != "")
		{
?>
			    <tr class="sdRowColor">
				  <td align="center">CAP</td>
				  <td colspan="4"><?= $objDb->getField($i, 'cap') ?></td>
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
					    <td>Number of Defects</td>
					    <td align="center">:</td>
					    <td><?= (int)$iDefects ?></td>
					  </tr>

					  <tr>
					    <td>D.H.U</td>
					    <td align="center">:</td>
					    <td><?= @round(( ($iDefects / $iTotalGmts) * 100), 2) ?>%</td>
					  </tr>
					  
<?
	switch ($iInspectionLevel)
	{
		case 1 : $sLevel = "I"; break;
		case 2 : $sLevel = "II"; break;
		case 3 : $sLevel = "III"; break;
		case 4 : $sLevel = "S-1"; break;
		case 5 : $sLevel = "S-2"; break;
		case 6 : $sLevel = "S-3"; break;
		case 7 : $sLevel = "S-4"; break;
	}
?>
					  <tr>
					    <td>Inspection Level</td>
					    <td align="center">:</td>
					    <td><?= $sLevel ?></td>
					  </tr>
					  
					  <tr>
					    <td>AQL</td>
					    <td align="center">:</td>
					    <td><?= formatNumber($fAql, true, (($fAql < 1) ? 2 : 1)) ?></td>
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

		    <br />
		    <h2>Quantities</h2>

<?
	$sSQL = "SELECT quantity FROM tbl_po WHERE id='$iPoId'";
	$objDb->query($sSQL);

	$iOrderQty = $objDb->getField(0, 0);

	if ($sAdditionalPos != "")
	{
		$sSQL = "SELECT SUM(quantity) FROM tbl_po WHERE id IN ($sAdditionalPos)";
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
			    <td><?= @round(( ($iShipQty / $iOrderQty) * 100), 2) ?>%</td>
			  </tr>

			  <tr>
			    <td>Ship Qty</td>
			    <td align="center">:</td>
			    <td><?= $iShipQty ?></td>
			    <td>Total Cartons Shipped</td>
			    <td align="center">:</td>
			    <td><?= $fCartonsShipped ?></td>
			  </tr>
		    </table>

		    <br />
		    <h2>Status & Comments</h2>

		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
			    <td width="170">Approved Sample</td>
			    <td width="20" align="center">:</td>
			    <td><?= $sApprovedSample ?></td>
			  </tr>

			  <tr>
				<td>Garment Test</td>
				<td align="center">:</td>
				<td><?= (($sGarmentTest == "Y") ? "Yes" : "No") ?></td>
			  </tr>

			  <tr>
				<td>Shade Band</td>
				<td align="center">:</td>
				<td><?= (($sShadeBand == "Y") ? "Yes" : "No") ?></td>
			  </tr>

			  <tr>
				<td>Fabric/ Yarn Test</td>
				<td align="center">:</td>
				<td><?= (($sFabricTest == "Y") ? "Yes" : "No") ?></td>
			  </tr>

			  <tr>
				<td>QA File</td>
				<td align="center">:</td>
				<td><?= (($sQaFile == "Y") ? "Yes" : "No") ?></td>
			  </tr>

			  <tr>
				<td>PP Meeting Minutes</td>
				<td align="center">:</td>
				<td><?= (($sPpMeeting == "Y") ? "Yes" : "No") ?></td>
			  </tr>

			  <tr>
			    <td> Shipping Mark/UCC label</td>
			    <td align="center">:</td>
			    <td><?= (($sShippingMark == "Y") ? "Yes" : "No") ?></td>
			  </tr>

			  <tr>
			    <td>Packing Check</td>
			    <td align="center">:</td>
			    <td><?= (($sPackingCheck == "Y") ? "Yes" : "No") ?></td>
			  </tr>

			  <tr>
				<td>Fitting </td>
				<td align="center">:</td>
				<td><?= (($sFittingTorque == "Y") ? "Yes" : "No") ?></td>
			  </tr>

			  <tr>
				<td>Color Check</td>
				<td align="center">:</td>
				<td><?= (($sColorCheck == "Y") ? "Yes" : "No") ?></td>
			  </tr>

			  <tr>
				<td>Accessories Check</td>
				<td align="center">:</td>
				<td><?= (($sAccessoriesCheck == "Y") ? "Yes" : "No") ?></td>
			  </tr>

			  <tr>
				<td>Measurement Check</td>
				<td align="center">:</td>
				<td><?= (($sMeasurementCheck == "Y") ? "Yes" : "No") ?></td>
			  </tr>

			  <tr>
				<td>Cutting/Knitting (%)</td>
				<td align="center">:</td>
				<td><?= (($iCutting == 0) ? "Not Provided" : $iCutting) ?></td>
			  </tr>

			  <tr>
				<td>Sewing/Linking (%)</td>
				<td align="center">:</td>
				<td><?= (($iSewing == 0) ? "Not Provided" : $iSewing) ?></td>
			  </tr>

			  <tr>
				<td>Finishing (%)</td>
				<td align="center">:</td>
				<td><?= (($iFinishing == 0) ? "Not Provided" : $iFinishing) ?></td>
			  </tr>

			  <tr>
				<td>Packed (%)</td>
				<td align="center">:</td>
				<td><?= (($iPacking == 0) ? "Not Provided" : $iPacking) ?></td>
			  </tr>

			  <tr>
				<td>Final Audit Date</td>
				<td align="center">:</td>
				<td><?= (($sFinalAuditDate != "0000-00-00") ?  date('d-M-Y',strtotime('-10 hour',strtotime($sFinalAuditDate))) : "Not Provided") ?></td>
			  </tr>

			  <tr>
			    <td>Carton No</td>
			    <td align="center">:</td>
			    <td><?= $sCartonNo ?></td>
			  </tr>

			  <tr>
			    <td>Measurement Inspected Qty</td>
			    <td align="center">:</td>
			    <td><?= $iMeasurementSampleQty ?></td>
			  </tr>

			  <tr>
			    <td>Measurement Defective Qty</td>
			    <td align="center">:</td>
			    <td><?= $iMeasurementDefectQty ?></td>
			  </tr>

			  <tr valign="top">
			    <td>CAP - Others</td>
			    <td align="center">:</td>
			    <td><?= nl2br($sCapOthers) ?></td>
			  </tr>

			  <tr valign="top">
			    <td>QA Comments</td>
			    <td align="center">:</td>
			    <td><?= nl2br($sComments) ?></td>
			  </tr>
		    </table>
