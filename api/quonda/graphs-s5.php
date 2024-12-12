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

	@include("graphs/input-data.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");
?>
</head>

<body style="background:#ffffff; padding:15px;">

<h2>Defect Images</h2>
<?
	$AuditStage = IO::strValue("AuditStage");
	$Line       = IO::intValue("Line");
	$Type       = IO::intValue("Type");
	$Code       = IO::intValue("Code");
	$DefectCode = IO::strValue("DefectCode");
	$AreaCode   = IO::strValue("AreaCode");

	$sPictures = array( );


	$sSQL = "SELECT DISTINCT(qa.audit_code), audit_date
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result!=''";

	if ($FromDate != "" && $ToDate != "")
		$sSQL .= " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";

	if ($AuditCode != "")
		$sSQL .= " AND qa.audit_code LIKE '%$AuditCode%' ";

	if ($OrderNo != "")
	{
		$sSQL .= " AND (";

		$sSubSQL = "SELECT id FROM tbl_po WHERE order_no LIKE '%$OrderNo%'";
		$objDb->query($sSubSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iPoId = $objDb->getField($i, 0);

			if ($i > 0)
				$sSQL .= " OR ";

			$sSQL .= " qa.po_id='$iPoId' OR FIND_IN_SET('$iPoId', qa.additional_pos) ";
		}

		$sSQL .= ") ";
	}

	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSQL .= " AND po.vendor_id IN ($sUserVendors) ";

	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' ";

	else
		$sSQL .= " AND po.brand_id IN ($sUserBrands) ";

	if ($Line > 0)
		$sSQL .= " AND qa.line_id='$Line' ";

	if ($AuditStage != "")
	{
		if ($Category == 8)
		{
			if ($AuditStage == 0 && $AuditStage != "")
				$AuditStage = "C";

			else if ($AuditStage == 1)
				$AuditStage = "S";

			else if ($AuditStage == 2)
				$AuditStage = "O";

			else if ($AuditStage == 3)
				$AuditStage = "ST";

			else if ($AuditStage == 4)
				$AuditStage = "B";

			else if ($AuditStage == 5)
				$AuditStage = "FI";

			else if ($AuditStage == 6)
				$AuditStage = "F";

			else if ($AuditStage == 7)
				$AuditStage = "OL";

			else if ($AuditStage == 8)
				$AuditStage = "SK";

			else if ($AuditStage == 9)
				$AuditStage = "P";

			else if ($AuditStage == 10)
				$AuditStage = "I";
		}

		$sSQL .= " AND qa.audit_stage='$AuditStage' ";
	}

	$sSQL .= " ORDER BY qa.id ";

	$objDb->query($sSQL);


	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sAuditCode = $objDb->getField($i, 0);
		$sAuditDate = $objDb->getField($i, 1);

		@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

		$sAuditPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_*.*");
   		$sAuditPictures = @array_map("strtoupper", $sAuditPictures);
   		$sAuditPictures = @array_unique($sAuditPictures);

		$sTemp = array( );

		foreach ($sAuditPictures as $sPicture)
			$sTemp[] = $sPicture;

		$sAuditPictures = $sTemp;


		for ($j = 0; $j < count($sAuditPictures); $j ++)
		{
			$sName  = @strtoupper($sAuditPictures[$j]);
			$sName  = @basename($sName, ".JPG");
			$sName  = @basename($sName, ".GIF");
			$sName  = @basename($sName, ".PNG");
			$sName  = @basename($sName, ".BMP");
			$sParts = @explode("_", $sName);

			if ($sParts[1] == $DefectCode)
			{
				if (intval($AreaCode) > 0)
				{
					if (intval($sParts[2]) == intval($AreaCode))
						$sPictures[] = $sAuditPictures[$j];
				}

				else
					$sPictures[] = $sAuditPictures[$j];
			}
		}
	}

	if (count($sPictures) == 0)
	{
?>
				  <div class="noRecord">No Defect Image Found!</div>
<?
	}

	else
	{
		for ($i = 0; $i < count($sPictures);)
		{
			for ($j = 0; $j < 5; $j ++, $i ++)
			{
				if ($i < count($sPictures))
				{
					$sName  = @strtoupper($sPictures[$i]);
					$sName  = @basename($sName, ".JPG");
					$sName  = @basename($sName, ".GIF");
					$sName  = @basename($sName, ".PNG");
					$sName  = @basename($sName, ".BMP");
					$sParts = @explode("_", $sName);
					$bFlag  = true;

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
								 WHERE code='$DefectCode' AND report_id='$iReportId' AND type_id='$Type'";

						if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
						{
							$sDefect = $objDb->getField(0, 0);

							$sTitle .= (" <b>»</b> ".$objDb->getField(0, 1));

							if ($iReportId != 4 && $iReportId != 6)
							{
								$sSQL = "SELECT area FROM tbl_defect_areas WHERE id='$AreaCode'";

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
						<div><img src="<?= SITE_URL.str_replace('../', '', $sPictures[$i]) ?>" width="100%" alt="" title="" /></div>
						<b style="display:block; font-family:verdana; font-size:12px; padding-top:5px;<?= (($bFlag == true) ? '' : 'color:#ff0000;') ?>"><?= @strtoupper($sName) ?></b>

					    <hr />
<?
				}
			}
		}
	}



	$ReportId = IO::intValue("ReportId");
	$Month    = IO::strValue("Month");

	$sBackUrl = (SITE_URL."api/quonda/graphs-s".(($Month > 0) ? 2 : (($ReportId == 4) ? 3 : 4)).".php?User={$User}&OrderNo={$OrderNo}&AuditCode={$AuditCode}&Brand={$Brand}&Vendor={$Vendor}&FromDate={$FromDate}&ToDate={$ToDate}&Line={$Line}&AuditStage={$AuditStage}&Type={$Type}&Code={$Code}&Color={$Color}&Sector={$Sector}&Month={$Month}");
?>
				  <div><input type="button" value=" Back " class="button" style="font-size:16px; padding:5px 10px 5px 10px;" onclick="document.location='<?= $sBackUrl ?>';" /></div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>