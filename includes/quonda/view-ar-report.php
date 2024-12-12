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

	$sSQL = "SELECT * FROM tbl_ar_inspection_checklist WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sModelName          = $objDb->getField(0, "model_name");
		$sWorkingNo          = $objDb->getField(0, "working_no");
		$sFabricApproval     = $objDb->getField(0, "fabric_approval");
		$sCounterSampleAppr  = $objDb->getField(0, "counter_sample_appr");
		$sGarmentWashingTest = $objDb->getField(0, "garment_washing_test");
		$sColorShade         = $objDb->getField(0, "color_shade");
		$sAppearance         = $objDb->getField(0, "appearance");
		$sHandfeel           = $objDb->getField(0, "handfeel");
		$sPrinting           = $objDb->getField(0, "printing");
		$sEmbridery          = $objDb->getField(0, "embridery");
		$sFibreContent       = $objDb->getField(0, "fibre_content");
		$sCountryOfOrigin    = $objDb->getField(0, "country_of_origin");
		$sCareInstruction    = $objDb->getField(0, "care_instruction");
		$sSizeKey            = $objDb->getField(0, "size_key");
		$sAdiComp            = $objDb->getField(0, "adi_comp");
		$sColourSizeQty      = $objDb->getField(0, "colour_size_qty");
		$sPolybag            = $objDb->getField(0, "polybag");
		$sHangtag            = $objDb->getField(0, "hangtag");
		$sOclUpc             = $objDb->getField(0, "ocl_upc");
		$sCartonNoChecked    = $objDb->getField(0, "carton_no_checked");
	}
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

			  <tr valign="top">
			    <td>Style</td>
			    <td align="center">:</td>
			    <td><?= getDbValue("style", "tbl_styles", "id='$iStyle'") ?></td>
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
		case "B"  : $sAuditType = "Bulk"; break;
		case "BG" : $sAuditType = "B-Grade"; break;
		case "SS" : $sAuditType = "Sales Sample"; break;
	}
?>
			  <tr>
			    <td>QA Type</td>
			    <td align="center">:</td>
			    <td><?= $sAuditType ?></td>
			  </tr>

			  <tr>
			    <td>Model Name</td>
			    <td align="center">:</td>
			    <td><?= $sModelName ?></td>
			  </tr>

			  <tr>
			    <td>Article No</td>
			    <td align="center">:</td>
			    <td><?= $sColors ?></td>
			  </tr>

			  <tr>
				<td>Working No</td>
				<td align="center">:</td>
				<td><?= $sWorkingNo ?></td>
			  </tr>
		    </table>

			<h2 style="margin:5px 0px 5px 0px;">&nbsp;</h2>

<?
	$iBrand = getDbValue("sub_brand_id", "tbl_styles", "id='$iStyle'");
	$fAql   = getDbValue("aql", "tbl_brands", "id='$iBrand'");
	$fAql   = (($fAql == 0) ? 1.0 : $fAql);
?>
			<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
				<td width="75">AQL Plan</td>
				<td width="20" align="center">:</td>
				<td><?= formatNumber($fAql, true) ?></td>
			  </tr>

			  <tr>
				<td>Sample Plan</td>
				<td align="center">:</td>
				<td><?= $iTotalGmts ?></td>
			  </tr>

<?
	$sAccepted = array(
	                    "2"   => 0,
	                    "3"   => 0,
	                    "5"   => 0,
	                    "8"   => 0,
	                    "13"  => 0,
	                    "20"  => 0,
	                    "32"  => 0,
	                    "50"  => 1,
	                    "80"  => 2,
	                    "125" => 3,
	                    "200" => 5,
	                    "315" => 7,
	                    "500" => 10
	                  );
?>
			  <tr>
				<td>Accepted</td>
				<td align="center">:</td>
				<td><?= $sAccepted[$iTotalGmts] ?></td>
			  </tr>

<?
	$sRejected = array(
	                    "2"   => 1,
	                    "3"   => 1,
	                    "5"   => 1,
	                    "8"   => 1,
	                    "13"  => 1,
	                    "20"  => 1,
	                    "32"  => 1,
	                    "50"  => 2,
	                    "80"  => 3,
	                    "125" => 4,
	                    "200" => 6,
	                    "315" => 8,
	                    "500" => 11
	                  );
?>
			  <tr>
				<td>Rejected</td>
				<td align="center">:</td>
				<td><?= $sRejected[$iTotalGmts] ?></td>
			  </tr>
			</table>

			<br />

			<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
				<td width="25"><input type="checkbox" name="FabricApproval" value="Y" <?= (($sFabricApproval == "Y") ? "checked" : "") ?> disabled /></td>
				<td>Fabric Approval</td>
				<td width="25"><input type="checkbox" name="CounterSampleAppr" value="Y" <?= (($sCounterSampleAppr == "Y") ? "checked" : "") ?> disabled /></td>
				<td>Counter Sample Appr.</td>
				<td width="25"><input type="checkbox" name="GarmentWashingTest" value="Y" <?= (($sGarmentWashingTest == "Y") ? "checked" : "") ?> disabled /></td>
				<td>Garment Washing Test</td>
			  </tr>
			</table>

			<br />

			<table border="1" bordercolor="#ffffff" cellpadding="0" cellspacing="0" width="100%">
			  <tr valign="top">
				<td width="33%">
				  <h2>Fabric/Print Check</h2>

				  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					<tr>
					  <td width="25"><input type="checkbox" name="ColorShade" value="Y" <?= (($sColorShade == "Y") ? "checked" : "") ?> disabled /></td>
					  <td>Color/Shade</td>
					</tr>

					<tr>
					  <td><input type="checkbox" name="Appearance" value="Y" <?= (($sAppearance == "Y") ? "checked" : "") ?> disabled /></td>
					  <td>Appearance</td>
					</tr>

					<tr>
					  <td><input type="checkbox" name="Handfeel" value="Y" <?= (($sHandfeel == "Y") ? "checked" : "") ?> disabled /></td>
					  <td>Handfeel</td>
					</tr>

					<tr>
					  <td><input type="checkbox" name="Printing" value="Y" <?= (($sPrinting == "Y") ? "checked" : "") ?> disabled /></td>
					  <td>Printing</td>
					</tr>

					<tr>
					  <td><input type="checkbox" name="Embridery" value="Y" <?= (($sEmbridery == "Y") ? "checked" : "") ?> disabled /></td>
					  <td>Embridery</td>
					</tr>
				  </table>

				</td>


				<td width="34%">
				  <h2>Label Check List</h2>

				  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					<tr>
					  <td width="25"><input type="checkbox" name="FibreContent" value="Y" <?= (($sFibreContent == "Y") ? "checked" : "") ?> disabled /></td>
					  <td>Fibre Content</td>
					</tr>

					<tr>
					  <td><input type="checkbox" name="CountryOfOrigin" value="Y" <?= (($sCountryOfOrigin == "Y") ? "checked" : "") ?> disabled /></td>
					  <td>Country of Origin</td>
					</tr>

					<tr>
					  <td><input type="checkbox" name="CareInstruction" value="Y" <?= (($sCareInstruction == "Y") ? "checked" : "") ?> disabled /></td>
					  <td>Care Instruction</td>
					</tr>

					<tr>
					  <td><input type="checkbox" name="SizeKey" value="Y" <?= (($sSizeKey == "Y") ? "checked" : "") ?> disabled /></td>
					  <td>Size Key</td>
					</tr>

					<tr>
					  <td><input type="checkbox" name="AdiComp" value="Y" <?= (($sAdiComp == "Y") ? "checked" : "") ?> disabled /></td>
					  <td>Adi Comp</td>
					</tr>
				  </table>
				</td>


				<td width="33%">
				  <h2>Packing Check List</h2>

				  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					<tr>
					  <td width="25"><input type="checkbox" name="ShippingMark" value="Y" <?= (($sShippingMark == "Y") ? "checked" : "") ?> disabled /></td>
					  <td>Shipping Mark</td>
					</tr>

					<tr>
					  <td><input type="checkbox" name="ColourSizeQty" value="Y" <?= (($sColourSizeQty == "Y") ? "checked" : "") ?> disabled /></td>
					  <td>Colour/Size/Qty as Print</td>
					</tr>

					<tr>
					  <td><input type="checkbox" name="Polybag" value="Y" <?= (($sPolybag == "Y") ? "checked" : "") ?> disabled /></td>
					  <td>Polybag/Marking</td>
					</tr>

					<tr>
					  <td><input type="checkbox" name="Hangtag" value="Y" <?= (($sHangtag == "Y") ? "checked" : "") ?> disabled /></td>
					  <td>Hangtag</td>
					</tr>

					<tr>
					  <td><input type="checkbox" name="OclUpc" value="Y" <?= (($sOclUpc == "Y") ? "checked" : "") ?> disabled /></td>
					  <td>OCL/UPC</td>
					</tr>
				  </table>
				</td>
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
				  <td width="70"><b>Nature</b></td>
			    </tr>

<?
	$sSQL = "SELECT * FROM tbl_qa_report_defects WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for($i = 0; $i < $iCount; $i ++)
	{
		$sSQL = ("SELECT code, defect FROM tbl_defect_codes WHERE id='".$objDb->getField($i, 'code_id')."'");
		$objDb2->query($sSQL);

		$sSQL = ("SELECT area FROM tbl_defect_areas WHERE id='".$objDb->getField($i, 'area_id')."'");
		$objDb3->query($sSQL);
?>

			    <tr class="sdRowColor">
				  <td align="center"><?= ($i + 1) ?></td>
				  <td><?= $objDb2->getField(0, 0) ?> - <?= $objDb2->getField(0, 1) ?></td>
				  <td align="center"><?= $objDb->getField($i, 'defects') ?></td>
				  <td><?= $objDb3->getField(0, 0) ?></td>
				  <td><?= (($objDb->getField($i, 'nature') == 1) ? "MAJOR" : "minor") ?></td>
			    </tr>
<?
	}

	if ($iCount == 0)
	{
?>

			    <tr class="sdRowColor">
				  <td colspan="4" align="center">No Defect Found!</td>
			    </tr>
<?
	}
?>
			  </table>

		    <br />
		    <h2>&nbsp;</h2>

		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
				<td width="180">Pieces Available for Inspection</td>
				<td width="20" align="center">:</td>
				<td><?= $iShipQty ?></td>
			  </tr>

			  <tr>
				<td>No of Beautiful Products</td>
				<td align="center">:</td>
				<td><?= $iBeautifulProducts ?></td>
			  </tr>

			  <tr>
				<td>Knitted (%)</td>
				<td align="center">:</td>
				<td><?= (($fKnitted == 0) ? "Not Provided" : $fKnitted) ?></td>
			  </tr>

			  <tr>
				<td>Dyed (%)</td>
				<td align="center">:</td>
				<td><?= (($fDyed == 0) ? "Not Provided" : $fDyed) ?></td>
			  </tr>

			  <tr>
				<td>Cutting</td>
				<td align="center">:</td>
				<td><?= (($iCutting == 0) ? "Not Provided" : $iCutting) ?></td>
			  </tr>

			  <tr>
				<td>Sewing</td>
				<td align="center">:</td>
				<td><?= (($iSewing == 0) ? "Not Provided" : $iSewing) ?></td>
			  </tr>

			  <tr>
				<td>Finishing</td>
				<td align="center">:</td>
				<td><?= (($iFinishing == 0) ? "Not Provided" : $iFinishing) ?></td>
			  </tr>

			  <tr>
				<td>Packing</td>
				<td align="center">:</td>
				<td><?= (($iPacking == 0) ? "Not Provided" : $iPacking) ?></td>
			  </tr>

			  <tr>
				<td>Final Audit Date</td>
				<td align="center">:</td>
				<td><?= (($sFinalAuditDate != "0000-00-00") ? formatDate($sFinalAuditDate) : "Not Provided") ?></td>
			  </tr>

			  <tr>
				<td>Carton No. Checked</td>
				<td align="center">:</td>
				<td><?= $sCartonNoChecked ?></td>
			  </tr>

			  <tr valign="top">
			    <td>QA Comments</td>
			    <td align="center">:</td>
			    <td><?= nl2br($sComments) ?></td>
			  </tr>
		    </table>
