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

	$sPictures = array( );

	$sSQL = "SELECT DISTINCT(qa.audit_code), audit_date
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result!='' AND NOT FIND_IN_SET(qa.report_id, '$sQmipReports') ";

	if ($iDepartment > 0)
		$sSQL .= " AND qa.department_id='$iDepartment' ";

	if ($sBrands != "")
		$sSQL .= " AND FIND_IN_SET(qa.brand_id, '$sBrands') ";

	if ($iBrand > 0)
		$sSQL .= " AND qa.brand_id='$iBrand' ";

	if ($AuditStage != "")
		$sSQL .= " AND qa.audit_stage='$AuditStage' ";

	else
		$sSQL .= " AND qa.audit_stage!='' ";

	if ($sVendors != "")
		$sSQL .= " AND FIND_IN_SET(qa.vendor_id, '$sVendors') ";

	if ($iVendor > 0)
		$sSQL .= " AND qa.vendor_id='$iVendor' ";

	if ($Vendor > 0)
		$sSQL .= " AND qa.vendor_id='$Vendor' ";

	if ($sFromDate != "" AND $sToDate != "")
		$sSQL .= " AND (qa.audit_date BETWEEN '$sFromDate' AND '$sToDate') ";

	else if ($sLastAudits != "" && $sLastAudits != "0")
		$sSQL .= " AND FIND_IN_SET(qa.id, '$sLastAudits') ";

	$sSQL .= " ORDER BY RAND( )";
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
		{
			if (@stripos(strtolower($sPicture), "_pack_") !== FALSE || @stripos(strtolower($sPicture), "_001_") !== FALSE || @stripos(strtolower($sPicture), "_misc_") !== FALSE ||
			    @stripos(strtolower($sPicture), "_00_") !== FALSE || @substr_count(strtolower($sPicture), "_") < 3)
				continue;


			$sTemp[] = $sPicture;
		}

		$sAuditPictures = $sTemp;


		for ($j = 0; $j < count($sAuditPictures); $j ++)
		{
			$sName  = @strtoupper($sAuditPictures[$j]);
			$sName  = @basename($sName, ".JPG");
			$sName  = @basename($sName, ".GIF");
			$sName  = @basename($sName, ".PNG");
			$sName  = @basename($sName, ".BMP");
			$sParts = @explode("_", $sName);

			$sPictures[] = $sAuditPictures[$j];
		}
	}


	shuffle($sPictures);
	$iPictures = count($sPictures);

	if ($iPictures == 0)
	{
?>
				<div style="padding:10px;">
				  No Defect Image Found!<br />
				</div>
<?
	}

	else
	{
		$iIndex = 0;
?>
				<div style="overflow:hidden;<?= (($iHeight > 0) ? " height:{$iHeight}px;" : '') ?>">
				  <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
		for ($i = 0; $i < 4; $i ++)
		{
?>
	    			<tr valign="top">
<?
			for ($j = 0; $j < 4; $j ++)
			{
				if ($iIndex < $iPictures)
				{
					$sName  = @strtoupper($sPictures[$iIndex]);
					$sName  = @basename($sName, ".JPG");
					$sName  = @basename($sName, ".GIF");
					$sName  = @basename($sName, ".PNG");
					$sName  = @basename($sName, ".BMP");
					$sParts = @explode("_", $sName);

					$sAuditCode   = $sParts[0];
					$sDefectCode  = $sParts[1];
					$sAreaCode    = $sParts[2];
					$sDefectTitle = "";


					$sSQL = "SELECT report_id,
									(SELECT vendor FROM tbl_vendors WHERE id=qa.vendor_id) AS _Vendor,
									(SELECT order_no FROM tbl_po WHERE id=qa.po_id) AS _PO,
									(SELECT style FROM tbl_styles WHERE id=(SELECT style_id FROM tbl_po_colors WHERE po_id=qa.po_id LIMIT 1)) AS _Style,
									(SELECT line FROM tbl_lines WHERE id=qa.line_id) AS _Line
							 FROM tbl_qa_reports qa
							 WHERE audit_code='$sAuditCode'";

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

							$sDefectTitle = $objDb->getField(0, 0);
							$sTitle      .= (" <b>»</b> ".$objDb->getField(0, 1));

							if ($iReportId != 4 && $iReportId != 6)
							{
								$sSQL = "SELECT area FROM tbl_defect_areas WHERE id='$sAreaCode'";

								if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
									$sTitle .= (" <b>»</b> ".$objDb->getField(0, 0));
							}

							$sTitle .= (" <b>»</b> ".$sDefect);
						}
					}

					else
						$sTitle = "<b>### Invalid File Name ###</b>";
?>
					  <td width="25%" align="center">
						<div style="border:solid 1px #888888; margin:0px 10px 5px 10px; height:122px; overflow:hidden;">
						  <div style="padding:1px;"><a href="<?= $sPictures[$iIndex] ?>" class="lightview" rel="gallery[defects]" title="<?= $sTitle ?> :: :: topclose: true"><img src="<?= $sPictures[$iIndex] ?>" alt="" title="" width="100%" /></a></div>
						</div>

						<div style="overflow:hidden; line-height:13px; height:13px; text-align:center;"><?= $sDefectTitle ?></div>
					  </td>
<?
					$iIndex ++;
				}

				else
				{
?>
	      			  <td width="25%"></td>
<?
				}
			}
?>
					</tr>
<?
			if ($i < 4)
			{
?>
					<tr>
					  <td colspan="4" height="18"></td>
					</tr>
<?
			}

			if ($iIndex >= $iPictures)
				break;
		}
?>
	  			  </table>
				</div>
<?
	}
?>
