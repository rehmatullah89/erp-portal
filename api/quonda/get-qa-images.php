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

	$AuditCode  = IO::strValue('AuditCode');
	$iAuditCode = intval(substr($AuditCode, 1));
	$DefectCode = IO::strValue("DefectCode");


	$aResponse = array( );


	if ($iAuditCode == 0 || strlen($AuditCode) == 0 || $AuditCode{0} != "S")
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid Audit Code";
	}

	else
	{
		$sSQL = "SELECT audit_date FROM tbl_qa_reports WHERE id='$iAuditCode'";
		$objDb->query($sSQL);

		$sAuditDate = $objDb->getField(0, 0);

		@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);


		$sImages = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($AuditCode, 1)."_*.*");
		$sImages = @array_map("strtoupper", $sImages);
		$sImages = @array_unique($sImages);

		if (count($sImages) == 0)
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "No Defect Image Found!";
		}

		else
		{
			$sTemp = array( );

			foreach ($sImages as $sImage)
				$sTemp[] = $sImage;

			$sImages  = $sTemp;
			$sListing = array( );


			for ($i = 0; $i < count($sImages); $i ++)
			{
				$sName = @strtoupper($sImages[$i]);
				$sName = @basename($sName, ".JPG");
				$sName = @basename($sName, ".GIF");
				$sName = @basename($sName, ".PNG");
				$sName = @basename($sName, ".BMP");

				$sUrl = (SITE_URL.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sImages[$i]));


				if (@strpos($sName, " ") !== FALSE)
					$sTitle = "# Invalid File Name #";

				else
				{
					$sParts = @explode("_", $sName);

					$sDefectCode = $sParts[1];
					$sAreaCode   = $sParts[2];

					if ($DefectCode != "" && $DefectCode != $sDefectCode)
						continue;


					$sSQL = "SELECT report_id,
									(SELECT vendor FROM tbl_vendors WHERE id=qa.vendor_id) AS _Vendor,
									(SELECT order_no FROM tbl_po WHERE id=qa.po_id) AS _PO,
									(SELECT style FROM tbl_styles WHERE id=(SELECT style_id FROM tbl_po_colors WHERE po_id=qa.po_id LIMIT 1)) AS _Style,
									(SELECT line FROM tbl_lines WHERE id=qa.line_id) AS _Line
							 FROM tbl_qa_reports qa
							 WHERE id='$iAuditCode'";

					if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
					{
						$iReportId = $objDb->getField(0, 0);

						$sTitle  = $objDb->getField(0, 1);
						$sTitle .= (" > ".$objDb->getField(0, 2));
						$sTitle .= (" > ".$objDb->getField(0, 3));
						$sTitle .= (" > ".$objDb->getField(0, 4));

						$sSQL = "SELECT defect,
										(SELECT type FROM tbl_defect_types WHERE id=dc.type_id) AS _Type
								 FROM tbl_defect_codes dc
								 WHERE code='$sDefectCode' AND report_id='$iReportId'";

						if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
						{
							$sDefect = $objDb->getField(0, 0);

							$sTitle .= (" > ".$objDb->getField(0, 1));


							if ($iReportId != 4 && $iReportId != 6)
							{
								$sSQL = "SELECT area FROM tbl_defect_areas WHERE id='$sAreaCode'";

								if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
									$sTitle .= (" > ".$objDb->getField(0, 0));
							}

							$sTitle .= (" > ".$sDefect);
						}
					}

					else
						$sTitle = "# Invalid File Name #";
				}


				$sListing[] = "{$sUrl}||{$sTitle}";
			}


			$aResponse['Status'] = "OK";
			$aResponse['Images'] = @implode("|-|", $sListing);
		}
	}


	print @json_encode($aResponse);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>