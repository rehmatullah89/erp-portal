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
			    <td width="130">Vendor</td>
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

			  <tr>
				<td>Test Qty</td>
				<td align="center">:</td>
				<td><?= $iTotalGmts ?></td>
			  </tr>

			  <tr>
				<td>No of Defects Allowed</td>
				<td align="center">:</td>
				<td><?= $iMaxDecfects ?></td>
			  </tr>
<?
	$sSQL = "SELECT * FROM tbl_jako_qa_reports WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$sEta         = $objDb->getField(0, "eta");
	$sWe          = $objDb->getField(0, "we");
	$fWashOff     = $objDb->getField(0, "wash_off");
	$fWashIn      = $objDb->getField(0, "wash_in");
	$fMeasureOff  = $objDb->getField(0, "measure_off");
	$fMeasureIn   = $objDb->getField(0, "measure_in");
	$iPcsMeasured = $objDb->getField(0, "pcs_measured");
?>
			  <tr>
				<td>ETA</td>
				<td align="center">:</td>
				<td><?= $sEta ?></td>
			  </tr>

			  <tr>
				<td>WE</td>
				<td align="center">:</td>
				<td><?= $sWe ?></td>
			  </tr>
		    </table>

				<br />
				<b>&nbsp; Packing</b><br />
				<br />

<?
	$sSQL = "SELECT * FROM tbl_jako_packing WHERE audit_id='$Id'";
	$objDb->query($sSQL);

	$sCarton    = $objDb->getField(0, "carton");
	$sPolybag   = $objDb->getField(0, "polybag");
	$sPackage   = $objDb->getField(0, "package");
	$sHangTag   = $objDb->getField(0, "hangtag");
	$sSizeLabel = $objDb->getField(0, "size_label");
	$sCareLabel = $objDb->getField(0, "care_label");
	$sProdLabel = $objDb->getField(0, "prod_label");
?>

			    <table border="1" bordercolor="#ffffff" cellpadding="4" cellspacing="0" width="100%">
				  <tr class="sdRowHeader">
				    <td width="14%" align="center"><b>Design</b></td>
				    <td width="15%" align="center"><b>Main Fab</b></td>
				    <td width="14%" align="center"><b>Trims</b></td>
				    <td width="14%" align="center"><b>Access</b></td>
				    <td width="14%" align="center"><b>Logos</b></td>
				    <td width="14%" align="center"><b>Colour</b></td>
				    <td width="15%" align="center"><b>TUV Test</b></td>
				  </tr>

				  <tr class="sdRowColor">
				    <td align="center"><?= (($Carton == "Y") ? "Yes" : (($Carton == "N") ? "No" : "")) ?></td>
				    <td align="center"><?= (($sPolybag == "Y") ? "Yes" : (($sPolybag == "N") ? "No" : "")) ?></td>
				    <td align="center"><?= (($sPackage == "Y") ? "Yes" : (($sPackage == "N") ? "No" : "")) ?></td>
				    <td align="center"><?= (($sHangTag == "Y") ? "Yes" : (($sHangTag == "N") ? "No" : "")) ?></td>
				    <td align="center"><?= (($sSizeLabel == "Y") ? "Yes" : (($sSizeLabel == "N") ? "No" : "")) ?></td>
				    <td align="center"><?= (($sCareLabel == "Y") ? "Yes" : (($sCareLabel == "N") ? "No" : "")) ?></td>
				    <td align="center"><?= (($sProdLabel == "Y") ? "Yes" : (($sProdLabel == "N") ? "No" : "")) ?></td>
				  </tr>
				</table>

				<br />

			    <table border="1" bordercolor="#ffffff" cellpadding="4" cellspacing="0" width="100%">
				  <tr class="sdRowHeader">
				    <td width="4%" align="center"><b>#</b></td>
				    <td width="19%" align="center"><b>Style/Color</b></td>
				    <td width="11%" align="center"><b>Design</b></td>
				    <td width="11%" align="center"><b>Main Fab</b></td>
				    <td width="11%" align="center"><b>Trims</b></td>
				    <td width="11%" align="center"><b>Access</b></td>
				    <td width="11%" align="center"><b>Logos</b></td>
				    <td width="11%" align="center"><b>Colour</b></td>
				    <td width="11%" align="center"><b>TUV Test</b></td>
				  </tr>
<?
	$sSQL = "SELECT * FROM tbl_jako_audits WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < 8; $i ++)
	{
?>

				  <tr class="sdRowColor">
				    <td align="center"><?= ($i + 1) ?></td>
				    <td align="center"><?= $objDb->getField($i,  'style_color') ?></td>
				    <td align="center"><?= (($objDb->getField($i,  'design') == "Y") ? "Yes" : (($objDb->getField($i,  'design') == "N") ? "No" : "")) ?></td>
				    <td align="center"><?= (($objDb->getField($i,  'main_fab') == "Y") ? "Yes" : (($objDb->getField($i,  'main_fab') == "N") ? "No" : "")) ?></td>
				    <td align="center"><?= (($objDb->getField($i,  'trims') == "Y") ? "Yes" : (($objDb->getField($i,  'trims') == "N") ? "No" : "")) ?></td>
				    <td align="center"><?= (($objDb->getField($i,  'access') == "Y") ? "Yes" : (($objDb->getField($i,  'access') == "N") ? "No" : "")) ?></td>
				    <td align="center"><?= (($objDb->getField($i,  'logos') == "Y") ? "Yes" : (($objDb->getField($i,  'logos') == "N") ? "No" : "")) ?></td>
				    <td align="center"><?= (($objDb->getField($i,  'color') == "Y") ? "Yes" : (($objDb->getField($i,  'color') == "N") ? "No" : "")) ?></td>
				    <td align="center"><?= (($objDb->getField($i,  'tuv_test') == "Y") ? "Yes" : (($objDb->getField($i,  'tuv_test') == "N") ? "No" : "")) ?></td>
				  </tr>
<?
	}
?>
				</table>

		    <br />
		    <h2 style="margin:0px;">Defects Details</h2>

		    <div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
			  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			    <tr class="sdRowHeader">
				  <td width="50" align="center"><b>#</b></td>
				  <td><b>Code - Check Points</b></td>
				  <td width="100" align="center"><b>Defects</b></td>
				  <td width="200" align="center"><b>Area</b></td>
				  <td width="100" align="center"><b>Nature</b></td>
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


		switch ($objDb->getField($i, "nature"))
		{
			case 1   :  $sNature = "Major"; break;
			default  :  $sNature = "Minor"; break;
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
		    </div>

			<br />
			<h2>Wash Test</h2>

			<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
				<td width="120">Off Tolerance</td>
				<td width="20" align="center">:</td>
				<td><?= $fWashOff ?></td>
			  </tr>

			  <tr>
				<td>In Tolerance</td>
				<td align="center">:</td>
				<td><?= $fWashIn ?></td>
			  </tr>
			</table>

			<br />
			<h2>Measure</h2>

			<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
				<td width="120">Off Tolerance</td>
				<td width="20" align="center">:</td>
				<td><?= $fMeasureOff ?></td>
			  </tr>

			  <tr>
				<td>In Tolerance</td>
				<td align="center">:</td>
				<td><?= $fMeasureIn ?></td>
			  </tr>

			  <tr>
				<td>No of Pcs. Measured</td>
				<td align="center">:</td>
				<td><?= $iPcsMeasured ?></td>
			  </tr>
			</table>

		    <br />
		    <h2>Status & Comments</h2>

		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
			    <td width="100">Audit Result</td>
			    <td width="20" align="center">:</td>
			    <td><?= $sAuditResult ?></td>
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

			  <tr valign="top">
			    <td>QA Comments</td>
			    <td align="center">:</td>
			    <td><?= nl2br($sComments) ?></td>
			  </tr>
		    </table>
