<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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

	if (($Vendor > 0 || $Brand > 0) && $ReportType != "")
	{
		$sData   = array( );
		$sLabels = array( );
		$sColors = array(0x999999, 0x999999, 0x999999, 0x999999, 0x999999);

		$sSQL = "SELECT DISTINCT(po.id)
		         FROM tbl_po po, tbl_po_colors pc
		         WHERE po.id=pc.po_id";

		if ($Vendor > 0)
			$sSQL .= " AND po.vendor_id='$Vendor' ";

		else
			$sSQL .= " AND FIND_IN_SET(po.vendor_id, '$sVendors') ";


		if ($Brand > 0)
			$sSQL .= " AND po.brand_id='$Brand' ";

		else
			$sSQL .= " AND FIND_IN_SET(po.brand_id, '$sBrands') ";


		if ($Style != "")
			$sSQL .= " AND pc.style_id IN (SELECT id FROM tbl_styles WHERE style LIKE '$Style') ";

		if ($Po != "")
			$sSQL .= " AND po.order_no LIKE '$Po' ";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$sPos   = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (",".$objDb->getField($i, 0));

		if ($sPos != "")
			$sPos = substr($sPos, 1);



		$sSQL = "SELECT id
		         FROM tbl_qa_reports
		         WHERE audit_type='B' AND audit_result!='' AND (audit_date BETWEEN '$FromDate' AND '$ToDate') AND FIND_IN_SET(po_id, '$sPos')";

		if ($Line != "")
			$sSQL .= " AND FIND_IN_SET(line_id, '$Line')";

		if ($AuditStage != "")
			$sSQL .= " AND audit_stage='$AuditStage' ";

		$objDb->query($sSQL);

		$iCount  = $objDb->getCount( );
		$sAudits = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sAudits .= (",".$objDb->getField($i, 0));

		if ($sAudits != "")
			$sAudits = substr($sAudits, 1);



		if ($ReportType == "5 Major Defects")
		{
			$sSQL = "SELECT SUM(defects) AS _Defects,
							(SELECT defect FROM tbl_defect_codes WHERE id=tbl_qa_report_defects.code_id) AS _Defect,
							(SELECT code FROM tbl_defect_codes WHERE id=tbl_qa_report_defects.code_id) AS _Code,
							code_id
					 FROM tbl_qa_report_defects
					 WHERE FIND_IN_SET(audit_id, '$sAudits')
					 GROUP BY code_id
					 ORDER BY _Defects DESC
					 LIMIT 5";
		}

		else if ($ReportType == "High DR")
		{
			$sSQL = "SELECT ROUND(AVG(dhu), 2) AS _DR,
							(SELECT line FROM tbl_lines WHERE id=tbl_qa_reports.line_id) AS _Line
					 FROM tbl_qa_reports
					 WHERE FIND_IN_SET(id, '$sAudits')
					 GROUP BY line_id
					 ORDER BY _DR DESC
					 LIMIT 5";
		}

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sData[]   = $objDb->getField($i, 0);
			$sLabels[] = str_replace(" ", "\n", $objDb->getField($i, 1));
		}

		$objChart = new XYChart(463, 350);
		$objChart->setPlotArea(50, 50, 380, 215);


		if ($ReportType == "5 Major Defects")
			$objChart->addTitle("\n5 Major Defects ({$sVendor}".(($sVendor != "" && $sBrand != "") ? " / " : "")."{$sBrand})", "verdanab.ttf", 10);

		else if ($ReportType == "High DR")
			$objChart->addTitle("\n5 High DR Lines ({$sVendor}".(($sVendor != "" && $sBrand != "") ? " / " : "")."{$sBrand})", "verdanab.ttf", 10);


		$objBarLayer = $objChart->addBarLayer3($sData, $sColors);
		$objBarLayer->setBarShape(CircleShape);
		$objBarLayer->setBarWidth(30);

		$objBarLayer->setAggregateLabelStyle( );

		$objLabels = $objChart->xAxis->setLabels($sLabels);


		if ($ReportType == "5 Major Defects")
		{
			$objBarLayer->setAggregateLabelFormat("{value}");

			$objChart->yAxis->setTitle("No of Defects", "verdanab.ttf", 9);
		}

		else if ($ReportType == "High DR")
		{
			$objBarLayer->setAggregateLabelFormat("{value}%");

			$objChart->yAxis->setTitle("Defect Rate", "verdanab.ttf", 9);
		}

		$objChart->yAxis->setLabelFormat("{value}");
		$objChart->xAxis->setWidth(2);
		$objChart->yAxis->setWidth(2);

		$sChart = $objChart->makeSession("Snapshot".$iIndex);
?>
			            <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" border="0" /><br />

<?
		if ($ReportType == "5 Major Defects")
		{
			$sDefectPics = array( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$sDefectCode = $objDb->getField($i, "_Code");
				$iDefectCode = $objDb->getField($i, "code_id");


				$sSQL = "SELECT qa.audit_code, qa.audit_date
						 FROM tbl_qa_reports qa, tbl_qa_report_defects qard
						 WHERE qa.id=qard.audit_id AND qard.code_id='$iDefectCode' AND qa.id IN ($sAudits)";
				$objDb2->query($sSQL);

				$iCount2 = $objDb2->getCount( );

				for ($j = 0; $j < $iCount2; $j ++)
				{
					$sAuditCode = $objDb2->getField($j, 0);
					$sAuditDate = $objDb2->getField($j, 1);

					@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

					$sAuditPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_".$sDefectCode."_*.*");
					$sAuditPictures = @array_map("strtoupper", $sAuditPictures);
					$sAuditPictures = @array_unique($sAuditPictures);

					if (count($sAuditPictures) > 0)
					{
						$sDefectPics[$i] = $sAuditPictures[0];
						break;
					}
				}
			}
?>
					    <h3 class="green">Images from "<?= $ReportType ?>"</h3>

					    <table border="0" cellpadding="5" cellspacing="0" width="100%" align="center" bgcolor="#494949">
	    				  <tr valign="top">
<?
			for ($i = 0; $i < 5; $i ++)
			{
				if ($i < count($sDefectPics))
				{
					$sName = @strtoupper($sDefectPics[$i]);
					$sName = @basename($sName, ".JPG");

					if (@strpos($sName, " ") !== FALSE)
					{
						$sTitle = "<b>### Invalid File Name ###</b>";
						$bFlag  = false;
					}

					else
					{
						$sParts = @explode("_", $sName);

						$sAuditCode  = $sParts[0];
						$sDefectCode = intval($sParts[1]);
						$sAreaCode   = intval($sParts[2]);
						$bFlag       = true;

						$sSQL = "SELECT report_id,
										(SELECT vendor FROM tbl_vendors WHERE id=qa.vendor_id) AS _Vendor,
										(SELECT order_no FROM tbl_po WHERE id=qa.po_id) AS _PO,
										(SELECT style FROM tbl_styles WHERE id=qa.style_id) AS _Style,
										(SELECT line FROM tbl_lines WHERE id=qa.line_id) AS _Line
								 FROM tbl_qa_reports qa
								 WHERE audit_code='$sAuditCode'";

						if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
						{
							$iReportId = $objDb->getField(0, 0);

							$sTitle  = ("<span style='color:#ff0000;'>Vendor:</span> ".$objDb->getField(0, 1));
							$sTitle .= (" <b>»</b> <span style='color:#ff0000;'>PO:</span> ".$objDb->getField(0, 2));
							$sTitle .= (" <b>»</b> <span style='color:#ff0000;'>Style:</span> ".$objDb->getField(0, 3));
							$sTitle .= (" <b>»</b> <span style='color:#ff0000;'>Line:</span> ".$objDb->getField(0, 4));

							$sSQL = "SELECT defect,
											(SELECT type FROM tbl_defect_types WHERE id=dc.type_id) AS _Type
									 FROM tbl_defect_codes dc
									 WHERE code='$sDefectCode' AND report_id='$iReportId'";

							if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
							{
								$sDefect = $objDb->getField(0, 0);

								$sTitle .= (" <b>»</b> <span style='color:#ff0000;'>Defect Type:</span> ".$objDb->getField(0, 1));

								if ($iReportId != 4)
								{
									$sSQL = "SELECT area FROM tbl_defect_areas WHERE id='$sAreaCode'";

									if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
										$sTitle .= (" <b>»</b> <span style='color:#ff0000;'>Defect Area:</span> ".$objDb->getField(0, 0));

									else
										$bFlag  = false;
								}

								$sTitle .= (" <b>»</b> <span style='color:#ff0000;'>Defect Area:</span> ".$sDefect);
							}

							else
								$bFlag  = false;
						}

						else
						{
							$sTitle = "<b>### Invalid File Name ###</b>";
							$bFlag  = false;
						}
					}
?>
						    <td width="20%" align="center">
							  <div class="qaMiniPic">
							    <div><a href="<?= $sDefectPics[$i] ?>" class="lightview" rel="gallery[snapshot1]" title="<?= $sTitle ?> :: :: topclose: true"><img src="<?= $sDefectPics[$i] ?>" alt="<?= @strtoupper($sName) ?>" title="<?= @strtoupper($sName) ?>" /></a></div>
							  </div>
						    </td>
<?
				}

				else
				{
?>
	      			  	    <td width="20%"><div style="width:80px; height:80px;"></div></td>
<?
				}
			}
?>
						  </tr>

						  <tr>
<?
			for ($i = 0; $i < 5; $i ++)
			{
				if ($i < count($sDefectPics))
				{
?>
	      			  	    <td width="20%" align="center" style="color:#ffffff;"><?= $sLabels[$i] ?></td>
<?
				}

				else
				{
?>
	      			  	    <td width="20%"><div style="width:80px; height:80px;"></div></td>
<?
				}
			}
?>
						  </tr>
	  			  	    </table>
<?
		}
	}
?>
