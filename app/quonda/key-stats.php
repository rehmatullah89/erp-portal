<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree QUONDA App                                                                   **
	**  Version 3.0                                                                              **
	**                                                                                           **
	**  http://app.3-tree.com                                                                    **
	**                                                                                           **
	**  Copyright 2008-17 (C) Triple Tree                                                        **
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
	***********************************************************************************************
	\*********************************************************************************************/

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );
	$objDb4      = new Database( );

	$User      = IO::strValue('User');
	$AuditCode = IO::strValue('AuditCode');
	$Brand     = IO::intValue('Brand');
	$Vendor    = IO::intValue('Vendor');
	$Audits    = IO::strValue('Audits');
	$DateRange = IO::strValue('DateRange');


	@list($FromDate, $ToDate) = @explode(":", $DateRange);
	
	$FromDate = date("Y-m-d", strtotime($FromDate));
	$ToDate   = date("Y-m-d", strtotime($ToDate));


	if ($User == "")
		die("Invalid Request");


	$sSQL = "SELECT id, vendors, brands, style_categories, report_types, audit_stages, status, guest FROM tbl_users WHERE MD5(id)='$User'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		die("Invalid User");

	else if ($objDb->getField(0, "status") != "A")
		die("User Account is Disabled");


	$sBrands          = $objDb->getField(0, "brands");
	$sVendors         = $objDb->getField(0, "vendors");
	$sStyleCategories = $objDb->getField(0, "style_categories");
	$sReportTypes     = $objDb->getField(0, "report_types");
	$sAuditStages     = $objDb->getField(0, "audit_stages");
	$sGuest           = $objDb->getField(0, "guest");


	$sConditions = " AND qa.audit_type='B' AND qa.audit_result!='' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') 
	                 AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') AND FIND_IN_SET(qa.report_id, '$sReportTypes') ";

	if ($AuditCode != "")
		$sConditions .= " AND qa.audit_code='$AuditCode' ";

	if ($Audits == "F")
		$sConditions .= " AND qa.audit_stage='F' ";

	else if ($Audits == "I")
		$sConditions .= " AND qa.audit_stage!='F' ";

	if ($Vendor > 0)
		$sConditions .= " AND qa.vendor_id='$Vendor' ";

	else
		$sConditions .= " AND qa.vendor_id IN ($sVendors) ";


	if (@stripos($_SESSION["Email"], "marksnspencer.com") !== FALSE)
	{
		$sConditions .= " AND qa.style_id IN (SELECT id FROM tbl_styles WHERE  ";

		if ($Brand > 0)
			$sConditions .= " sub_brand_id='$Brand' ";

		else
			$sConditions .= " FIND_IN_SET(sub_brand_id, '$sBrands') ";

		$sConditions .= "  AND FIND_IN_SET(category_id, '$sStyleCategories')) ";
	}

	else
	{
		$sConditions .= " AND (qa.style_id='0' OR qa.style_id IN (SELECT id FROM tbl_styles WHERE ";

		if ($Brand > 0)
			$sConditions .= " sub_brand_id='$Brand' ";

		else
			$sConditions .= " FIND_IN_SET(sub_brand_id, '$sBrands') ";

		$sConditions .= "  AND FIND_IN_SET(category_id, '$sStyleCategories'))) ";
	}



	$sBrand         = "Multiple";
	$sVendor        = "Multiple";
	$sPo            = "Multiple";
	$sStyle         = "Multiple";
	$sAdditionalPos = "";
	$sSketch        = (SITE_URL.STYLES_SKETCH_DIR."default.jpg");
	$iSampleSize    = 0;
	$iDefective     = 0;
	$sMiscPics      = array( );
	$sSpecsSheets   = array( );
	$sLabPics       = array( );
	$sPackingPics   = array( );


	if ($AuditCode != "")
	{
		$sSQL = "SELECT po_id, additional_pos, style_id, report_id, audit_date, total_gmts, defective_gmts,
		                specs_sheet_1, specs_sheet_2, specs_sheet_3, specs_sheet_4, specs_sheet_5,
		                specs_sheet_6, specs_sheet_7, specs_sheet_8, specs_sheet_9, specs_sheet_10,
		                (SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor,
						(SELECT order_no FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _PO
				 FROM tbl_qa_reports
				 WHERE audit_code='$AuditCode'";
		$objDb->query($sSQL);

		$sVendor        = $objDb->getField(0, "_Vendor");
		$sPo            = $objDb->getField(0, "_PO");
		$iPo            = $objDb->getField(0, 'po_id');
		$sAdditionalPos = $objDb->getField(0, "additional_pos");
		$iStyle         = $objDb->getField(0, "style_id");
		$iReport        = $objDb->getField(0, "report_id");
		$iSampleSize    = $objDb->getField(0, "total_gmts");
		$iDefective     = $objDb->getField(0, "defective_gmts");
		$sAuditDate     = $objDb->getField(0, "audit_date");
		

		if ($iStyle == 0)
			$iStyle = getDbValue("style_id", "tbl_po_colors", "po_id='$iPo'");
		

		@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);
		
		for ($i = 1; $i <= 10; $i ++)
		{
			$sSpecsSheet = $objDb->getField(0, "specs_sheet_{$i}");
			
			if ($sSpecsSheet == "" || @strpos(strtolower($sSpecsSheet), ".pdf") !== FALSE)
				continue;
			
			
			$iPosition  = @strrpos($sPicture, '.');
			$sExtension = @substr($sPicture, $iPosition);			
			
			
			if (@file_exists(ABSOLUTE_PATH.SPECS_SHEETS_DIR.$sSpecsSheet))
			{
				if (!@file_exists(ABSOLUTE_PATH.SPECS_SHEETS_DIR."thumbs/".$sSpecsSheet))
				{
					@mkdir(ABSOLUTE_PATH.SPECS_SHEETS_DIR."thumbs/");

					createImage((ABSOLUTE_PATH.SPECS_SHEETS_DIR.$sSpecsSheet), (ABSOLUTE_PATH.SPECS_SHEETS_DIR."thumbs/".$sSpecsSheet), 240, 180);
				}

				$sLabPics[] = (SITE_URL.SPECS_SHEETS_DIR."thumbs/".$sSpecsSheet);
			}
			
			else if (@file_exists(ABSOLUTE_PATH.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sSpecsSheet))
			{
				if (!@file_exists(ABSOLUTE_PATH.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/"."thumbs/".$sSpecsSheet))
				{
					@mkdir(ABSOLUTE_PATH.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/"."thumbs/");

					createImage((ABSOLUTE_PATH.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sSpecsSheet), (ABSOLUTE_PATH.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/"."thumbs/".$sSpecsSheet), 240, 180);
				}

				$sLabPics[] = (SITE_URL.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/"."thumbs/".$sSpecsSheet);
			}
		}
		

		if ($sAdditionalPos != "")
		{
			$sPos = "";

			$sSQL = "SELECT TRIM(CONCAT(order_no, ' ', order_status)) FROM tbl_po WHERE id IN ($sAdditionalPos) ORDER BY order_no";

			if ($objDb->query($sSQL) == true)
			{
				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
					$sPos .= (", ".$objDb->getField($i, 0));
			}


			$sAdditionalPos = $sPos;
		}



		$sSQL = "SELECT style, sketch_file,
						(SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand
				 FROM tbl_styles
				 WHERE id='$iStyle'";
		$objDb->query($sSQL);

		$sStyle  = $objDb->getField(0, 'style');
		$sBrand  = $objDb->getField(0, '_Brand');
		$sSketch = $objDb->getField(0, 'sketch_file');


		if ($sSketch == "" || !@file_exists(ABSOLUTE_PATH.STYLES_SKETCH_DIR.$sSketch))
			$sSketch = (SITE_URL.STYLES_SKETCH_DIR."default.jpg");

		else
		{
			if (!@file_exists(ABSOLUTE_PATH.STYLES_SKETCH_DIR.'thumbs/'.$sSketch))
				createImage((ABSOLUTE_PATH.STYLES_SKETCH_DIR.$sSketch), (ABSOLUTE_PATH.STYLES_SKETCH_DIR.'thumbs/'.$sSketch), 160, 160);

			$sSketch = (SITE_URL.STYLES_SKETCH_DIR.'thumbs/'.$sSketch);
		}
	}

	else
	{
		$sSQL = "SELECT SUM(qa.total_gmts), SUM(qa.defective_gmts)
				 FROM tbl_po po, tbl_qa_reports qa
				 WHERE po.id=qa.po_id $sConditions";
		$objDb->query($sSQL);

		$iSampleSize = $objDb->getField(0, 0);
		$iDefective  = $objDb->getField(0, 1);
	}



	$sSQL = "SELECT DISTINCT(qa.audit_code), audit_date,
	                (SELECT line FROM tbl_lines WHERE id=qa.line_id) AS _Line
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id $sConditions
			 ORDER BY qa.id";
	$objDb->query($sSQL);

	$iCount    = $objDb->getCount( );
	$sPictures = array( );
	$sLines    = array( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sAuditCode = $objDb->getField($i, 0);
		$sAuditDate = $objDb->getField($i, 1);
		$sLine      = $objDb->getField($i, 2);

		@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

		$sQuondaDir = (ABSOLUTE_PATH.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");

		$sAuditPictures = @glob("{$sQuondaDir}?".substr($sAuditCode, 1)."_*.*");
		$sAuditPictures = @array_map("strtoupper", $sAuditPictures);
		$sAuditPictures = @array_unique($sAuditPictures);



		$sTemp   = array( );
		$iLength = strlen($sAuditCode);

		foreach ($sAuditPictures as $sPicture)
		{
			if (substr(@basename($sPicture), 0, ($iLength + 4)) == "{$sAuditCode}_00_" ||
			    substr(@basename($sPicture), 0, ($iLength + 4)) == "{$sAuditCode}_001" || @substr_count(@basename($sPicture), "_") < 3 ||
				strlen(@basename($sPicture)) < ($iLength + 6) || @stripos($sPicture, "_pack_") !== FALSE || @stripos($sPicture, "_misc_") !== FALSE)
			{
				$sDefectPic = $sPicture;
				$sPicture   = @basename($sDefectPic);
				$sPicsDir   = str_ireplace($sPicture, "", $sDefectPic);

				if (!@file_exists($sPicsDir.'thumbs/'.$sPicture))
				{
					@mkdir($sPicsDir."thumbs/");

					createImage(($sPicsDir.$sPicture), ($sPicsDir."thumbs/".$sPicture), 240, 180);
				}


				if (@stripos($sDefectPic, "_pack_") !== FALSE || @stripos($sDefectPic, "_001_") !== FALSE)
					$sPackingPics[] = (SITE_URL.str_ireplace(ABSOLUTE_PATH, "", $sPicsDir)."thumbs/".$sPicture);

				else
					$sMiscPics[] = (SITE_URL.str_ireplace(ABSOLUTE_PATH, "", $sPicsDir)."thumbs/".$sPicture);

				continue;
			}

			$sTemp[] = $sPicture;
		}
		

		$sPictures = array_merge($sPictures, $sTemp);

		$sLines["{$sAuditCode}"] = $sLine;
	}


	
	$sDefects    = array( );
	$sDefectPics = array( );

	$sSQL = "SELECT dt.id, dt.type, COALESCE(SUM(qad.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id $sConditions
			 GROUP BY dc.type_id
			 ORDER BY _Defects DESC, dt.type";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iDefectType = $objDb->getField($i, 0);
		$sDefectType = $objDb->getField($i, 1);
		$iDefects    = $objDb->getField($i, 2);


		$sDefects[] = array("DefectType"   => $sDefectType,
							"DefectsCount" => $iDefects,
							"DefectName"   => "",
							"DefectArea"   => "",
							"DefectNature" => "",
							"Line"         => "",
							"Picture"      => "",
							"Vendor"       => "",
							"Brand"        => "",
							"Po"           => "",
							"Style"        => "",
							"Id"           => "");

		if ($AuditCode != "")
		{
			$iAuditId = substr($AuditCode, 1);
			
			$sSQL = "SELECT qad.id, qad.area_id, qad.nature, qad.defects,
					        dc.code, dc.defect,
							(SELECT area FROM tbl_defect_areas WHERE id=qad.area_id) AS _Area
					 FROM tbl_qa_report_defects qad, tbl_defect_codes dc
					 WHERE qad.code_id=dc.id AND dc.type_id='$iDefectType' AND qad.audit_id='$iAuditId'
					 ORDER BY dc.defect";
			$objDb2->query($sSQL);

			$iCount2 = $objDb2->getCount( );

			for ($j = 0; $j < $iCount2; $j ++)
			{
				$iId         = $objDb2->getField($j, "id");
				$iDefectArea = $objDb2->getField($j, "area_id");
				$iNature     = $objDb2->getField($j, "nature");
				$iDefects    = $objDb2->getField($j, "defects");
				$sDefectCode = $objDb2->getField($j, "code");
				$sDefectName = $objDb2->getField($j, "defect");
				$sDefectArea = $objDb2->getField($j, "_Area");
				

				$sDefectNature = "Minor";

				if ($iNature == 1)
					$sDefectNature = "Major";

				else if ($iNature == 2)
					$sDefectNature = "Critical";


				for ($k = 0; $k < count($sPictures); $k ++)
				{
					$sName  = @strtoupper($sPictures[$k]);
					$sName  = @basename($sName, ".JPG");
					$sName  = @basename($sName, ".GIF");
					$sName  = @basename($sName, ".PNG");
					$sName  = @basename($sName, ".BMP");

					$sParts = @explode("_", $sName);
					
				
					if ($sParts[1] == $sDefectCode && intval($sParts[2]) == intval($iDefectArea))
					{
						$sPicture   = @basename($sPictures[$k]);
						$sQuondaDir = str_ireplace($sPicture, "", $sPictures[$k]);

						if (!@file_exists($sQuondaDir.'thumbs/'.$sPicture))
						{
							@mkdir($sQuondaDir.'thumbs/');

							createImage(($sQuondaDir.$sPicture), ($sQuondaDir.'thumbs/'.$sPicture), 240, 180);
						}


						$sUrl = (SITE_URL.str_ireplace(ABSOLUTE_PATH, "", $sQuondaDir)."thumbs/".$sPicture);
						
						if (@in_array($sUrl, $sDefectPics))
							continue;
						
						$sDefectPics[] = $sUrl;


						$sDefects[] = array("DefectType"   => $sDefectType,
											"DefectsCount" => $iDefects,
											"DefectName"   => $sDefectName,
											"DefectArea"   => $sDefectArea,
											"DefectNature" => $sDefectNature,
											"Line"         => $sLines[$sParts[0]],
											"Picture"      => $sUrl,
											"Vendor"       => $sVendor,
											"Brand"        => $sBrand,
											"Po"           => $sPo,
											"Style"        => $sStyle,
											"Id"           => $iId);
					
						break;
					}
				}
			}			
		}
		
		else
		{
			$sSQL = "SELECT dc.id, dc.code, dc.defect
					 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
					 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id AND dt.id='$iDefectType' $sConditions
					 GROUP BY dc.id";
			$objDb2->query($sSQL);

			$iCount2 = $objDb2->getCount( );

			for ($j = 0; $j < $iCount2; $j ++)
			{
				$iDefectCode = $objDb2->getField($j, 0);
				$sDefectCode = $objDb2->getField($j, 1);
				$sDefectName = $objDb2->getField($j, 2);


				$sSQL = "SELECT da.id, da.area, qa.po_id, qa.style_id, qad.nature, qa.report_id, COALESCE(SUM(qad.defects), 0) AS _Defects,
								(SELECT vendor FROM tbl_vendors WHERE id=qa.vendor_id) AS _Vendor,
								(SELECT order_no FROM tbl_po WHERE id=qa.po_id) AS _PO
						 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt, tbl_defect_areas da
						 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id AND da.id=qad.area_id AND dc.id='$iDefectCode'
							   $sConditions
						 GROUP BY qad.nature, qad.area_id";
				$objDb3->query($sSQL);

				$iCount3 = $objDb3->getCount( );

				for ($k = 0; $k < $iCount3; $k ++)
				{
					$iDefectArea   = $objDb3->getField($k, "id");
					$sDefectArea   = $objDb3->getField($k, "area");
					$iDefectPo     = $objDb3->getField($k, "po_id");
					$iDefectStyle  = $objDb3->getField($k, "style_id");
					$sDefectVendor = $objDb3->getField($k, "_Vendor");
					$sDefectPo     = $objDb3->getField($k, "_PO");
					$iReport       = $objDb3->getField($k, "report_id");
					$iNature       = $objDb3->getField($k, "nature");
					$iDefects      = $objDb3->getField($k, "_Defects");


					$sDefectNature = "Minor";

					if ($iNature == 1)
						$sDefectNature = "Major";

					else if ($iNature == 2)
						$sDefectNature = "Critical";


					if ($iDefectStyle == 0)
						$iDefectStyle = getDbValue("style_id", "tbl_po_colors", "po_id='$iDefectPo'");


					$sSQL = "SELECT style,
									(SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand
							 FROM tbl_styles
							 WHERE id='$iDefectStyle'";
					$objDb4->query($sSQL);

					$sDefectStyle = $objDb4->getField(0, 'style');
					$sDefectBrand = $objDb4->getField(0, '_Brand');


					for ($l = 0; $l < count($sPictures); $l ++)
					{
						$sName  = @strtoupper($sPictures[$l]);
						$sName  = @basename($sName, ".JPG");
						$sName  = @basename($sName, ".GIF");
						$sName  = @basename($sName, ".PNG");
						$sName  = @basename($sName, ".BMP");

						$sParts = @explode("_", $sName);

						if ($sParts[1] == $sDefectCode && intval($sParts[2]) == intval($iDefectArea))
						{
							$sPicture   = @basename($sPictures[$l]);
							$sQuondaDir = str_ireplace($sPicture, "", $sPictures[$l]);

							if (!@file_exists($sQuondaDir.'thumbs/'.$sPicture))
							{
								@mkdir($sQuondaDir.'thumbs/');

								createImage(($sQuondaDir.$sPicture), ($sQuondaDir.'thumbs/'.$sPicture), 240, 180);
							}


							$sUrl = (SITE_URL.str_ireplace(ABSOLUTE_PATH, "", $sQuondaDir)."thumbs/".$sPicture);
							
							if (@in_array($sUrl, $sDefectPics))
								continue;
							
							$sDefectPics[] = $sUrl;							


							$sDefects[] = array("DefectType"   => $sDefectType,
												"DefectsCount" => $iDefects,
												"DefectName"   => $sDefectName,
												"DefectArea"   => $sDefectArea,
												"DefectNature" => $sDefectNature,
												"Line"         => $sLines[$sParts[0]],
												"Picture"      => $sUrl,
												"Vendor"       => $sDefectVendor,
												"Brand"        => $sDefectBrand,
												"Po"           => $sDefectPo,
												"Style"        => $sDefectStyle,
												"Id"           => "");
						
							break;
						}
					}
				}
			}
		}
	}



	foreach ($sPictures as $sMiscPic)
	{
		$sPicture = @basename($sMiscPic);
		$bExists  = false;

		foreach ($sDefects as $sDefect)
		{
			if (@stripos($sDefect['Picture'], $sPicture) !== FALSE)
			{
				$bExists = true;

				break;
			}
		}


		if ($bExists == false)
		{
			$sQuondaDir = str_ireplace($sPicture, "", $sMiscPic);

			if (!@file_exists($sQuondaDir.'thumbs/'.$sPicture))
			{
				@mkdir($sQuondaDir.'thumbs/');

				createImage(($sQuondaDir.$sPicture), ($sQuondaDir.'thumbs/'.$sPicture), 240, 180);
			}


			if (@stripos($sPicture, "_pack_") !== FALSE || @stripos($sPicture, "_001_") !== FALSE)
				$sPackingPics[] = (SITE_URL.str_ireplace(ABSOLUTE_PATH, "", $sQuondaDir)."thumbs/".$sPicture);

			else
				$sMiscPics[] = (SITE_URL.str_ireplace(ABSOLUTE_PATH, "", $sQuondaDir)."thumbs/".$sPicture);
		}
	}




	$aResponse                  = array( );
	$aResponse['Status']        = "OK";
	$aResponse['Brand']         = $sBrand;
	$aResponse['Vendor']        = $sVendor;
	$aResponse['Po']            = $sPo;
	$aResponse['AdditionalPos'] = $sAdditionalPos;
	$aResponse['Style']         = $sStyle;
	$aResponse['Sketch']        = $sSketch;
	$aResponse['SampleSize']    = $iSampleSize;
	$aResponse['Defective']     = $iDefective;
	$aResponse['Defects']       = $sDefects;
	$aResponse['Packing']       = $sPackingPics;
	$aResponse['Labs']          = $sLabPics;
	$aResponse['Misc']          = $sMiscPics;


	print @json_encode($aResponse);

/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = @json_encode($_REQUEST).@json_encode($aResponse);

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/

	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDb4->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>