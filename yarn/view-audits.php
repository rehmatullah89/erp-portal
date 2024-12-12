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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$PoId    = IO::intValue('PoId');
	$StyleId = IO::intValue('StyleId');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/glider.js"></script>
</head>

<body style="background:#ffffff;">

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body" specs="min-height:544px; height:544px;">
	  <h2>Audits History</h2>

	  <div class="tblSheet">
	    <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
		  <tr class="headerRow" style="background:#aaaaaa;">
		    <td width="8%">#</td>
		    <td width="12%">Audit Code</td>
		    <td width="12%">Stage</td>
		    <td width="12%">Type</td>
		    <td width="12%">Result</td>
		    <td width="12%">Audit Date</td>
		    <td width="12%">Quantity</td>
		    <td width="12%">Defects</td>
		    <td width="8%">DHU</td>
		  </tr>
<?
	$sClass      = array("evenRow", "oddRow");
	$iBrandId    = getDbValue("brand_id", "tbl_po", "id='$PoId'");
	$sAuditCodes = array( );

	$sSQL = "SELECT * FROM tbl_qa_reports WHERE audit_stage='F' AND audit_result='P' AND style_id='$StyleId' AND (po_id='$PoId' OR FIND_IN_SET('$PoId', additional_pos)) ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iAuditId     = $objDb->getField($i, 'id');
		$iReportId    = $objDb->getField($i, "report_id");
		$sAuditCode   = $objDb->getField($i, 'audit_code');
		$sAuditStage  = $objDb->getField($i, 'audit_stage');
		$sAuditType   = $objDb->getField($i, "audit_type");
		$sAuditResult = $objDb->getField($i, 'audit_result');
		$sAuditDate   = $objDb->getField($i, 'audit_date');
		$fDhu         = $objDb->getField($i, 'dhu');

		$sAuditCodes[] = $sAuditCode;

		switch ($sAuditStage)
		{
			case "B"  : $sAuditStageText = "Batch"; break;
			case "C"  : $sAuditStageText = "Cutting"; break;
			case "F"  : $sAuditStageText = "Final"; break;
			case "O"  : $sAuditStageText = "Output"; break;
			case "S"  : $sAuditStageText = "Sorting"; break;
			case "ST" : $sAuditStageText = "Stitching"; break;
			case "FI" : $sAuditStageText = "Finishing"; break;
			case "OL" : $sAuditStageText = "Off Loom"; break;
			case "SK" : $sAuditStageText = "Stock"; break;
			case "P"  : $sAuditStageText = "Pre-Final"; break;
		}

		switch ($sAuditResult)
		{
			case "P" : $sAuditResult = "Pass"; break;
			case "F" : $sAuditResult = "Fail"; break;
			case "H" : $sAuditResult = "Hold"; break;
		}

		switch ($sAuditType)
		{
			case "B"  : $sAuditType  = "Bulk"; break;
			case "BG" : $sAuditType = "B-Grade"; break;
			case "SS" : $sAuditType = "Sales Sample"; break;
		}

		if ($iReportId == 6)
		{
			$sSQL = "SELECT SUM(actual_1 + actual_2 + actual_3) FROM tbl_gf_rolls_info WHERE audit_id='$iAuditId'";
			$objDb2->query($sSQL);

			$iQuantity = $objDb2->getField(0, 0);


			$sSQL = "SELECT SUM(defects) FROM tbl_gf_report_defects WHERE audit_id='$iAuditId'";
			$objDb2->query($sSQL);

			$iDefects = $objDb2->getField(0, 0);
		}

		else
		{
			$iQuantity = $objDb->getField($i, "total_gmts");
			$iDefects  = $objDb->getField($i, "defective_gmts");
		}
?>
			  <tr <?= (($sAuditResult == "Fail") ? 'style="background:#f08891;"' : "class='{$sClass[($i % 2)]}'") ?>>
			    <td><?= ($i + 1) ?></td>
<?
		if (checkUserRights("qa-reports.php", "Quonda", "view"))
		{
?>
			    <td>
				  <a href="quonda/view-qa-report.php?Id=<?= $iAuditId ?>" class="lightview" rel="iframe" title="Audit Code : <?= $sAuditCode ?> :: :: width: 850, height: 550"><?= $sAuditCode ?></a>
<?
			if (@in_array($iReportId, array(1, 2, 4, 5, 6, 7, 8, 9, 10, 11))  || @in_array($iBrandId, array(32, 87, 119, 120, 121)))
			{
?>
				  <a href="quonda/export-qa-report.php?Id=<?= $iAuditId ?>&ReportId=<?= $iReportId ?>&Brand=<?= $iBrandId ?>&AuditStage=<?= $sAuditStage ?>"><img src="images/icons/pdf.gif" width="16" height="16" align="right" alt="QA Report" title="QA Report" /></a>
<?
			}
?>
			    </td>
<?
		}

		else
		{
?>
			    <td><?= $sAuditCode ?></td>
<?
		}
?>

			    <td><?= $sAuditStageText ?></td>
			    <td><?= $sAuditType ?></td>
			    <td><?= $sAuditResult ?></td>
			    <td><?= formatDate($sAuditDate) ?></td>
			    <td><?= $iQuantity ?></td>
			    <td><?= $iDefects ?></td>
			    <td><?= $fDhu ?>%</td>
			  </tr>
<?
	}

	if ($iCount == 0)
	{
?>
			  <tr>
			    <td colspan="9">No Audit Record Found!</td>
			  </tr>
<?
	}
?>
	        </table>
	      </div>


<?
	if (count($sAuditCodes) > 0)
	{
		@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

		$sPictures = array( );

		for ($i = 0; $i < count($sAuditCodes); $i ++)
		{
			$sAuditCode = $sAuditCodes[$i];

			$sAuditPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_*.*");
			$sAuditPictures = @array_map("strtoupper", $sAuditPictures);
			$sAuditPictures = @array_unique($sAuditPictures);

			$sPictures = @array_merge($sPictures, $sAuditPictures);
		}


		$sTemp = array( );

		foreach ($sPictures as $sPicture)
			$sTemp[] = $sPicture;

		$sPictures = $sTemp;
?>
		  <br />
		  <h2>Defect Pictures</h2>

		  <div class="tblSheet">
		    <table border="0" cellpadding="0" cellspacing="0" width="100%">
			  <tr>
<?
		if (count($sPictures) > 6)
		{
?>
			    <td width="74" align="center"><img src="images/icons/back-arrow.gif" width="48" height="48" alt="Previous" title="Previous" onclick="objQuondaGlider.previous( ); return false;" style="cursor:pointer;" /></td>
<?
		}
?>
			    <td>
				  <div style="overflow:hidden; padding:5px 0px 5px 0px;">

 				  <div id="QuondaGlider">
				    <div class="scroller">
					  <div class="content">
<?
		for ($i = 0; $i < count($sPictures); $i ++)
		{
			$sName       = @strtoupper($sPictures[$i]);
			$sName       = @basename($sName, ".JPG");
			$sName       = @basename($sName, ".GIF");
			$sName       = @basename($sName, ".PNG");
			$sName       = @basename($sName, ".BMP");
			$sParts      = @explode("_", $sName);
			$sDefectCode = intval($sParts[1]);
			$sAreaCode   = intval($sParts[2]);
			$bFlag       = true;

			$sSQL = "SELECT report_id,
							(SELECT vendor FROM tbl_vendors WHERE id=qa.vendor_id) AS _Vendor,
							(SELECT order_no FROM tbl_po WHERE id=qa.po_id) AS _PO,
							(SELECT style FROM tbl_styles WHERE id=(SELECT style_id FROM tbl_po_colors WHERE po_id=qa.po_id LIMIT 1)) AS _Style,
							(SELECT line FROM tbl_lines WHERE id=qa.line_id) AS _Line
					 FROM tbl_qa_reports qa
					 WHERE audit_code='{$sParts[0]}'";

			if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
			{
				$iReportId = $objDb->getField(0, 0);

				$sTitle  = $objDb->getField(0, 1);
				$sTitle .= (" <b>»</b> ".$objDb->getField(0, 2));
				$sTitle .= (" <b>»</b> ".$objDb->getField(0, 3));
				$sTitle .= (" <b>»</b> ".$objDb->getField(0, 4));

				$sSQL = "SELECT defect,
								(SELECT type FROM tbl_defect_types WHERE id=dc.type_id) AS _Type
						 FROM tbl_defect_codes dc
						 WHERE code='$sDefectCode' AND report_id='$iReportId'";

				if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
				{
					$sDefect = $objDb->getField(0, 0);

					$sTitle .= (" <b>»</b> ".$objDb->getField(0, 1));

					if ($iReportId != 4 && $iReportId != 6)
					{
						$sSQL = "SELECT area FROM tbl_defect_areas WHERE id='$sAreaCode'";

						if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
							$sTitle .= (" <b>»</b> ".$objDb->getField(0, 0));

						else
							$bFlag  = false;
					}

					$sTitle .= (" <b>»</b> ".$sDefect);
				}

				else
					$bFlag  = false;
			}

			else
			{
				$sTitle = "<b>### Invalid File Name ###</b>";
				$bFlag  = false;
			}
?>
					    <div class="section" id="section<?= $i ?>">
						  <div class="qaPoPic">
						    <div><a href="<?= $sPictures[$i] ?>" class="lightview" rel="gallery[defects]" title="<?= $sTitle ?> :: :: topclose: true"><img src="<?= $sPictures[$i] ?>" alt="" title="" /></a></div>
						  </div>

						  <span<?= (($bFlag == true) ? '' : ' style="color:#ff0000;"') ?>><?= @strtoupper($sName) ?></span><br />
					    </div>
<?
		}
?>
					  </div>
				    </div>
				  </div>

				  <script type="text/javascript">
				  <!--
					  var objQuondaGlider = new Glider('QuondaGlider', { duration:1.0, maxDisplay:6 });
				  -->
				  </script>
<?
		if (count($sPictures) > 6)
		{
?>
			    <td width="74" align="center"><img src="images/icons/next-arrow.gif" width="48" height="48" alt="Next" title="Next" onclick="objQuondaGlider.next( ); return false;" style="cursor:pointer;" /></td>
<?
		}
?>
			  </tr>
		    </table>
		  </div>
<?
	}
?>
	  </div>
	</div>
<!--  Body Section Ends Here  -->


  </div>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>