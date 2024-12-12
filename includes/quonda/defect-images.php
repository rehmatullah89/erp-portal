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
			 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result!='' AND FIND_IN_SET(qa.report_id, '$sReportTypes') $sAuditorSQL
			       AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";

	$sSQL .= " AND (qa.style_id='0' OR qa.style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$_SESSION['Brands']}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

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

	if ($StyleNo != "")
	{
		$sSQL .= " AND (";

		$sSubSQL = "SELECT id FROM tbl_styles WHERE style LIKE '%$StyleNo%'";
		$objDb->query($sSubSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iStyleId = $objDb->getField($i, 0);

			if ($i > 0)
				$sSQL .= " OR ";

			$sSQL .= " qa.style_id='$iStyleId' ";
		}

		$sSQL .= ") ";
	}

	if ($Vendor > 0)
		$sSQL .= " AND po.vendor_id='$Vendor' ";

	else
		$sSQL .= " AND po.vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Brand > 0)
		$sSQL .= " AND po.brand_id='$Brand' ";

	else
		$sSQL .= " AND po.brand_id IN ({$_SESSION['Brands']}) ";

	if ($Line > 0)
		$sSQL .= " AND qa.line_id='$Line' ";

	if ($AuditStage != "")
	{
		if ($Category == 8)
			$AuditStage = getDbValue("code", "tbl_audit_stages", "id='$AuditStage'");

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
//   		$sAuditPictures = @array_map("strtoupper", $sAuditPictures);
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
				  <br />
<?
	}

	else
	{
?>
				  <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
		for ($i = 0; $i < count($sPictures);)
		{
?>
	    			<tr valign="top">
<?
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
						$iReportId = $objDb->getField(0, "report_id");

						$sTitle  = $objDb->getField(0, "_Vendor");
						$sTitle .= (" <b>»</b> ".$objDb->getField(0, "_PO"));
						$sTitle .= (" <b>»</b> ".$objDb->getField(0, "_Style"));
						$sTitle .= (" <b>»</b> ".$objDb->getField(0, "_Line"));

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
					  <td width="20%" align="center">
						<div class="qaPic">
						  <div><a href="<?= $sPictures[$i] ?>" class="lightview" rel="gallery[defects]" title="<?= $sTitle ?> :: :: topclose: true"><img src="<?= $sPictures[$i] ?>" alt="" title="" /></a></div>
						</div>

						<span<?= (($bFlag == true) ? '' : ' style="color:#ff0000;"') ?>><?= @strtoupper($sName) ?></span><br />
					  </td>
<?
				}

				else
				{
?>
	      			  <td width="20%"></td>
<?
				}
			}
?>
					</tr>
<?
			if ($i < count($sPictures))
			{
?>
					<tr>
					  <td colspan="5"><hr /></td>
					</tr>
<?
			}
		}
?>
	  			  </table>
<?
	}
?>
