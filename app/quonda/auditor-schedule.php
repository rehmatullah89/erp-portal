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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	
	$App   = IO::strValue("App");
	$User  = IO::strValue("User");
	$Date  = IO::strValue("Date");
	$Debug = IO::strValue("Debug");

	
	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "")
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, email, status, vendors, brands, style_categories, report_types, audit_stages, audit_services, app_skip_image, user_type, guest FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else
		{
			$iUser            = $objDb->getField(0, "id");
			$sName            = $objDb->getField(0, "name");
			$sEmail           = $objDb->getField(0, "email");
			$sBrands          = $objDb->getField(0, "brands");
			$sVendors         = $objDb->getField(0, "vendors");
			$sStyleCategories = $objDb->getField(0, "style_categories");
			$sReportTypes     = $objDb->getField(0, "report_types");
			$sAuditStages     = $objDb->getField(0, "audit_stages");
			$sAuditServices   = $objDb->getField(0, "audit_services");
			$sSkipImage       = $objDb->getField(0, "app_skip_image");
			$sUserType        = $objDb->getField(0, "user_type");
			$sGuest           = $objDb->getField(0, "guest");

			
			$sAuditStagesList     = getList("tbl_audit_stages", "code", "stage");
			$sStageColorsList     = getList("tbl_audit_stages", "code", "color");
			$sAuditServicesList   = getList("tbl_audit_services", "code", "stage");
			$sVendorCountriesList = getList("tbl_vendors", "id", "country_id");
			$sCountryHoursList    = getList("tbl_countries", "id", "hours");


			$Date        = (($Date == "") ? date("Y-m-d") : $Date);
			$sConditions = " WHERE ((user_id='$iUser' AND group_id='0') OR (group_id>'0' AND group_id IN (SELECT id FROM tbl_auditor_groups WHERE FIND_IN_SET('$iUser', users)))) AND approved='Y'
			                       AND (po_id='0' OR vendor_id IN ($sVendors))
			                       AND (po_id='0' OR po_id IN (SELECT id FROM tbl_po WHERE brand_id IN ($sBrands)))
			                       AND FIND_IN_SET(audit_stage, '$sAuditStages') AND FIND_IN_SET(report_id, '$sReportTypes')";


			$sSQL = "SELECT id FROM tbl_styles WHERE sub_brand_id IN ($sBrands) AND FIND_IN_SET(category_id, '$sStyleCategories') ";
			$objDb->query($sSQL);

			$iCount  = $objDb->getCount( );
			$sStyles = "0";

			for ($i = 0; $i < $iCount; $i ++)
				$sStyles .= (",".$objDb->getField($i, 0));


			if (@strpos($_SESSION["Email"], "marksnspencer.com") === FALSE)
				$sConditions .= " AND (style_id='0' OR style_id IN ($sStyles)) ";

			else
				$sConditions .= " AND style_id IN ($sStyles) ";


			$sSQL = "SELECT 'A' AS _Type, audit_type_id AS _AuditType, audit_code, IF(ISNULL(device_id), '', device_id) AS _DeviceId, vendor_id, '0' AS location_id, user_id, report_id, style_id, po_id, additional_pos, sizes, colors,
			                start_time, end_time, audit_stage, audit_result, aql, dhu, audit_quantity, total_gmts, sampling_plan, check_level, checked_gmts, qa_comments, '' AS details, start_date_time, TIME_TO_SEC(start_time) AS _StartTime, published
					 FROM tbl_qa_reports
					 $sConditions AND audit_date='$Date'

					 UNION

					 SELECT 'S' AS _Type, '' AS _AuditType, CONCAT('T', LPAD(id, 5, '0')) AS audit_code, '' AS _DeviceId, '0' AS vendor_id, location_id, '0' AS user_id, '0' AS report_id, '0' AS style_id, '0' AS po_id, '' AS additional_pos, '' AS sizes, '' AS colors,
					        start_time, end_time, '' AS audit_stage, '' AS audit_result, '0' AS aql, '0' AS dhu, '0' AS audit_quantity, '0' AS total_gmts, 'S' AS sampling_plan, '1' AS check_level, '0' AS checked_gmts, '' AS qa_comments, details, '' AS start_date_time, TIME_TO_SEC(start_time) AS _StartTime, '' AS published
					 FROM tbl_user_schedule
					 WHERE user_id='$iUser' AND ('$Date' BETWEEN from_date AND to_date)
					 
					 ORDER BY _StartTime";
			$objDb->query($sSQL);
			
			$iCount      = $objDb->getCount( );
			$iCompleted  = 0;
			$sAudits     = array( );
			$iProdAduits = 0;

			for ($i = 0; $i < $iCount; $i ++)
			{
				$sType          = $objDb->getField($i, '_Type');
				$iAuditType     = $objDb->getField($i, '_AuditType');
				$sDeviceId      = $objDb->getField($i, '_DeviceId');
				$iAuditorId     = $objDb->getField($i, 'user_id');
				$sAuditCode     = $objDb->getField($i, 'audit_code');
				$iBookingId     = $objDb->getField($i, 'Booking');
				$sStartTime     = $objDb->getField($i, 'start_time');
				$sEndTime       = $objDb->getField($i, 'end_time');
				$sAuditStage    = $objDb->getField($i, 'audit_stage');
				$sAuditResult   = $objDb->getField($i, 'audit_result');
				$fAql           = $objDb->getField($i, 'aql');
				$fDr            = $objDb->getField($i, 'dhu');
				$iReportId      = $objDb->getField($i, 'report_id');
				$iStyleId       = $objDb->getField($i, 'style_id');
				$iPoId          = $objDb->getField($i, 'po_id');
				$sAdditionalPos = $objDb->getField($i, 'additional_pos');
				$iVendorId      = $objDb->getField($i, 'vendor_id');
				$iSizes         = $objDb->getField($i, 'sizes');
				$sColors        = $objDb->getField($i, 'colors');
				$iOfferedQty    = $objDb->getField($i, 'audit_quantity');
				$iSampleSize    = $objDb->getField($i, 'total_gmts');
				$iSampleChecked = $objDb->getField($i, 'checked_gmts');
				$iCheckLevel    = $objDb->getField($i, 'check_level');
				$sSamplingPlan  = $objDb->getField($i, 'sampling_plan');
				$iStartTime     = $objDb->getField($i, '_StartTime');
				$sStartDateTime = $objDb->getField($i, 'start_date_time');
				$sComments      = $objDb->getField($i, 'qa_comments');
				$iLocationId    = $objDb->getField($i, 'location_id');
				$sDetails       = $objDb->getField($i, 'details');
				$sPublished     = $objDb->getField($i, 'published');


//				if ($sUserType == "MGF")
				{
					$iCountry = $sVendorCountriesList[$iVendorId];
					$iHours   = $sCountryHoursList[$iCountry];
					
					$iStartTime += ($iHours * 3600);
				}
					
				
				if ($iStyleId == 0 && $iPoId > 0)
					$iStyleId = getDbValue("style_id", "tbl_po_colors", "po_id='$iPoId'");


				$sSQL = "SELECT style, sketch_file, brand_id,
								(SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand,
								(SELECT season FROM tbl_seasons WHERE id=tbl_styles.sub_season_id) AS _Season
						 FROM tbl_styles
						 WHERE id='$iStyleId'";
				$objDb2->query($sSQL);

				$sStyle   = $objDb2->getField(0, 'style');
				$sBrand   = $objDb2->getField(0, '_Brand');
				$iBrand   = $objDb2->getField(0, 'brand_id');
				$sSeason  = $objDb2->getField(0, '_Season');
				$sPicture = $objDb2->getField(0, 'sketch_file');


				$sProductCode  = "";
				
			
				if ($iPoId == 0)
				{
					$sPo          = "N/A";
					$sPos         = "";
					$sEtdRequired = "";
					$sSizes       = array( );
				}

				else
				{
					$sSizes       = getList("tbl_sizes", "id", "size", "FIND_IN_SET(id, '$iSizes')", "size");
					$sEtdRequired = getDbValue("etd_required", "tbl_po_colors", "po_id='$iPoId'", "etd_required");
					
					if ($sUserType == "LEVIS")
					{
						$sPo          = getDbValue("GROUP_CONCAT(TRIM(CONCAT(order_no, '-', item_number)) SEPARATOR ', ')", "tbl_po", "id='$iPoId' OR ('$sAdditionalPos'!='' AND FIND_IN_SET(id, '$sAdditionalPos'))");
						$sPos         = getDbValue("GROUP_CONCAT(CONCAT(order_no, '-', item_number) SEPARATOR ',')", "tbl_po", "id='$iPoId' OR FIND_IN_SET(id, '$sAdditionalPos')", "id");
						$sProductCode = getDbValue("GROUP_CONCAT(DISTINCT(product_code) SEPARATOR ', ')", "tbl_po", "id='$iPoId' OR FIND_IN_SET(id, '$sAdditionalPos')");
					}
					
					else
					{
						$sPo  = getDbValue("GROUP_CONCAT(TRIM(CONCAT(order_no, ' ', order_status)) SEPARATOR ', ')", "tbl_po", "id='$iPoId' OR ('$sAdditionalPos'!='' AND FIND_IN_SET(id, '$sAdditionalPos'))");
						$sPos = getDbValue("GROUP_CONCAT(order_no SEPARATOR ',')", "tbl_po", "id='$iPoId' OR FIND_IN_SET(id, '$sAdditionalPos')", "id");
					}
				}


				$sColors     = trim($sColors, ",");
				$sQuantities = array( );
				$iOrderQty   = 0;
				
				$sSQL = "SELECT po.id, po.order_no, SUM(pc.order_qty) AS _Quantity
						 FROM tbl_po po, tbl_po_colors pc
						 WHERE po.id=pc.po_id AND (po.id='$iPoId' OR FIND_IN_SET(po.id, '$sAdditionalPos')) AND FIND_IN_SET(REPLACE(pc.color, ',', ' '), '$sColors') AND pc.style_id='$iStyleId'
						 GROUP BY po.id";
				$objDb2->query($sSQL);
				
				$iCount2 = $objDb2->getCount( );
				
				for ($j = 0; $j < $iCount2; $j ++)
				{
					$iPo       = $objDb2->getField($j, "id");
					$sOrderNo  = $objDb2->getField($j, "order_no");
					$iQuantity = $objDb2->getField($j, "_Quantity");
					
					$iOrderQty    += $iQuantity;
					$sQuantities[] = array("PoId" => $iPo, "PoNo" => $sOrderNo, "OrderQty" => $iQuantity);
				}	

				
				if (($iReportId == 44 || $iReportId == 45) && $iAuditType == 2 && $sAuditResult != "" && ($sAuditStage == "F" || $sAuditStage == "TG"))
				{
					if ($iSampleSize == 32)
						$sAuditStage = "V";
				}		

				
				$iAuditCode     = (int)substr($AuditCode, 1);
				$iFabricDefects = (($iReportId == 26) ? getDbValue("COUNT(1)", "tbl_tnc_report_defects", "audit_id='$iAuditCode'") : 0);
				$sColor         = (($sType == "B") ? "#660000" : $sStageColorsList[$sAuditStage]);
				$sLocation      = (($iLocationId > 0) ? getDbValue("location", "tbl_visit_locations", "id='$iLocationId'") : "");
				$sColors        = @explode(",", $sColors);
				$sOnGoing       = "N";
				
				if ($iReportId != 26 && ($iSampleSize == 0 || ($iSampleChecked > 0 && $sAuditResult == "")))
					$sOnGoing = "Y";

				if ($iReportId == 26 && $iFabricDefects > 0 && ($sAuditResult == "" || $sComments == ""))
					$sOnGoing = "Y";
				
				if ($sAuditResult != "" && $sComments == "" && !@in_array($iReportId, array(14,34)))
					$sOnGoing = "Y";
				
				


				if ($sPicture == "" || !@file_exists(ABSOLUTE_PATH.STYLES_SKETCH_DIR.$sPicture))
					$sPicture = (SITE_URL.STYLES_SKETCH_DIR."default.jpg");

				else
				{
					if (!@file_exists(ABSOLUTE_PATH.STYLES_SKETCH_DIR.'thumbs/'.$sPicture))
						createImage((ABSOLUTE_PATH.STYLES_SKETCH_DIR.$sPicture), (ABSOLUTE_PATH.STYLES_SKETCH_DIR.'thumbs/'.$sPicture), 160, 160);

					$sPicture = (SITE_URL.STYLES_SKETCH_DIR.'thumbs/'.$sPicture);
				}


			
				@list($iDefectsAllowed) = getAqlDefects($iSampleSize, $fAql, $iReportId);
				

				if ($sColor == "")
					$sColor = "#cccccc";

				if ($sAuditResult == "")
				{
					$sAuditResult = "";
					$sColor       = "#cccccc";
				}

				if ($sType == "S")
					$sColor = "#b6e500";

				else
					$iProdAduits ++;

				if ($sType == "S" && time( ) >= strtotime($sStartTime) && time( ) <= strtotime($sEndTime))
					$sOnGoing = "Y";
				
				if ($sAuditResult != "" && $sComments != "")
					$sOnGoing = "N";


				if ($iStartTime >= (2 * 3600) && $iStartTime < (4 * 3600))
					$iBlock = 1;

				else if ($iStartTime >= (4 * 3600) && $iStartTime < (6 * 3600))
					$iBlock = 2;

				else if ($iStartTime >= (6 * 3600) && $iStartTime < (8 * 3600))
					$iBlock = 3;

				else if ($iStartTime >= (8 * 3600) && $iStartTime < (10 * 3600))
					$iBlock = 4;

				else if ($iStartTime >= (10 * 3600) && $iStartTime < (12 * 3600))
					$iBlock = 5;

				else if ($iStartTime >= (12 * 3600) && $iStartTime < (14 * 3600))
					$iBlock = 6;

				else if ($iStartTime >= (14 * 3600) && $iStartTime < (16 * 3600))
					$iBlock = 7;

				else if ($iStartTime >= (16 * 3600) && $iStartTime < (18 * 3600))
					$iBlock = 8;

				else if ($iStartTime >= (18 * 3600) && $iStartTime < (20 * 3600))
					$iBlock = 9;

				else if ($iStartTime >= (20 * 3600) && $iStartTime < (22 * 3600))
					$iBlock = 10;

				else if ($iStartTime >= (22 * 3600) && $iStartTime < (24 * 3600))
					$iBlock = 11;

				else
					$iBlock = 0;


				$sSizeSpecs = array( );

				if (count($iSizes) == 1 && $iSizes[0] == "N/A")
				{

				}

				else
				{
					$iAuditSizes = @explode(",", $iSizes);

					foreach ($iAuditSizes as $iSize)
					{
						$iSamplingSize = (int)getDbValue("id", "tbl_sampling_sizes", "size LIKE '{$sSizes[$iSize]}'");
						$sSizeDetails  = array( );
												
						
						if ($iSamplingSize == 0 && strpos($sSizes[$iSize], " ") !== FALSE)
						{
							@list($sWaist, $sInseenLength) = @explode(" ", $sSizes[$iSize]);
							
							$iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '$sWaist'");
						}
					


						$sSQL = "SELECT point_id, specs, nature, cr_position, fb_position, position,
										(SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
										(SELECT point FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Point,
										(SELECT point_id FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _PointId
								 FROM tbl_style_specs
								 WHERE style_id='$iStyleId' AND size_id='$iSamplingSize' AND version='0'
								 ORDER BY id";
						$objDb2->query($sSQL);

						$iCount2 = $objDb2->getCount( );
						
						if ($iCount2 == 0 && $sSizes[$iSize] == "XXL")
						{
							$iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '2XL'");

							
							if ($iSamplingSize > 0)
							{
								$sSQL = "SELECT point_id, specs, nature, cr_position, fb_position, position,
												(SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
												(SELECT point FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Point,
												(SELECT point_id FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _PointId
										 FROM tbl_style_specs
										 WHERE style_id='$iStyleId' AND size_id='$iSamplingSize' AND version='0'
										 ORDER BY id";
								$objDb2->query($sSQL);

								$iCount2 = $objDb2->getCount( );
							}
						}
						
						if ($iCount2 == 0 && strpos($sSizes[$iSize], " ") !== FALSE)
						{
							$sSize         = str_replace(" ", "", $sSizes[$iSize]);
							$iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '$sSize'");

							if ($iSamplingSize == 0 && substr($sSizes[$iSize], -2) == " S")
							{
								$sSize         = str_replace(" S", "W", $sSizes[$iSize]);
								$iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '$sSize'");
							}
							
							if ($iSamplingSize > 0)
							{
								$sSQL = "SELECT point_id, specs, nature, cr_position, fb_position, position,
												(SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
												(SELECT point FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Point,
												(SELECT point_id FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _PointId
										 FROM tbl_style_specs
										 WHERE style_id='$iStyleId' AND size_id='$iSamplingSize' AND version='0'
										 ORDER BY id";
								$objDb2->query($sSQL);

								$iCount2 = $objDb2->getCount( );
							}
						}

						
						for($j = 0; $j < $iCount2; $j ++)
						{
							$iPoint      = $objDb2->getField($j, 'point_id');
							$sPoint      = $objDb2->getField($j, '_Point');
							$sPointId    = $objDb2->getField($j, '_PointId');
							$sNature     = $objDb2->getField($j, 'nature');
							$sSpecs      = $objDb2->getField($j, 'specs');
							$sTolerance  = $objDb2->getField($j, '_Tolerance');
							$iFbPosition = $objDb2->getField($j, 'fb_position');
							$iCrPosition = $objDb2->getField($j, 'cr_position');
							$iPosition   = $objDb2->getField($j, 'position');
							
							
							@list($fMinusTolerance, $fPlusTolerance) = parseTolerance($sTolerance);
							

							$sSizeDetails[] = array("PointId"        => $iPoint,
													"Point"          => $sPoint,
													"PointCode"      => $sPointId,
													"Nature"         => $sNature,
													"Specs"          => $sSpecs,
													"MinusTolerance" => $fMinusTolerance,
													"PlusTolerance"  => $fPlusTolerance,
													"Tolerance"      => $sTolerance,
													"CrPosition"     => $iCrPosition,
													"FbPosition"     => $iFbPosition,
													"Position"       => $iPosition);
						}


						$sSizeSpecs[] = array("Size" => $iSize, "Specs" => $sSizeDetails);
					}
				}
								
				
				$sPoColorSizeQty = array( );
				$iDoubleSampling = 0;
				$iFirstFailure   = 0;
				$iSecondFailure  = 0;
				
			
				if (($iReportId == 44 || $iReportId == 45) && $sAuditStage == "F" && $iAuditType == 2)
				{
					$sSamplingPlan   = "T";
					$iDoubleSampling = 1;
					$iFirstFailure   = 2;
					$iSecondFailure  = 2;
				}
				
				else if (($iReportId == 44 || $iReportId == 45) && $sAuditStage == "TG" && $iAuditType == 2)
				{
					$sSamplingPlan   = "D";
					$iDoubleSampling = 3;
					$iFirstFailure   = 4;
					$iSecondFailure  = 4;
				}				
					
				else if ($iReportId == 48)
				{
					$sSQL = ("SELECT po.id, po.order_no,
					                 pc.color,
					                 s.id, s.size,
									 SUM(pq.quantity) AS _Quantity
							  FROM tbl_po po, tbl_po_colors pc, tbl_po_quantities pq, tbl_sizes s
							  WHERE po.id=pc.po_id AND pc.po_id=pq.po_id AND pq.size_id=s.id AND pq.color_id=pc.id AND pc.style_id='$iStyleId' AND (pc.po_id='$iPoId' OR FIND_IN_SET(pc.po_id, '$sAdditionalPos'))
							        AND pq.quantity>'0' AND FIND_IN_SET(s.id, '$iSizes') AND FIND_IN_SET(pc.color, '".@implode(",", $sColors)."')
							  GROUP BY po.id, pc.color, s.id
							  ORDER BY po.id, pc.color, s.position");
					$objDb2->query($sSQL);

					$iCount2 = $objDb2->getCount( );

					for($j = 0; $j < $iCount2; $j ++)
					{
						$iPo       = $objDb2->getField($j, 'po.id');
						$sPoNo     = $objDb2->getField($j, 'order_no');
						$sPoColor  = $objDb2->getField($j, 'color');
						$iSize     = $objDb2->getField($j, 's.id');
						$sSize     = $objDb2->getField($j, 'size');
						$iQuantity = $objDb2->getField($j, '_Quantity');
						
						
						$sPoColorSizeQty[] = array("PoId"     => $iPo,
												   "Po"       => $sPoNo,
												   "SizeId"   => $iSize,
												   "Size"     => $sSize,
												   "ColorId"  => md5($sPoColor),
												   "Color"    => $sPoColor,
												   "Quantity" => $iQuantity,
												   "Position" => $j);
					}
					
					
					
					if ($iSampleSize < 13)
					{
						$iDoubleSampling = 0;
						$iFirstFailure   = 1;
						$iSecondFailure  = 1;
					}
					
					else if ($iSampleSize == 13)
					{
						$iDoubleSampling = 1;
						$iFirstFailure   = 2;
						$iSecondFailure  = 2;
					}
					
					else if ($iSampleSize == 20)
					{
						$iDoubleSampling = 1;
						$iFirstFailure   = 3;
						$iSecondFailure  = 4;
					}
					
					else if ($iSampleSize == 32)
					{
						$iDoubleSampling = 2;
						$iFirstFailure   = 4;
						$iSecondFailure  = 5;
					}
					
					else if ($iSampleSize == 50)
					{
						$iDoubleSampling = 3;
						$iFirstFailure   = 5;
						$iSecondFailure  = 7;
					}
					
					else if ($iSampleSize == 80)
					{
						$iDoubleSampling = 4;
						$iFirstFailure   = 7;
						$iSecondFailure  = 9;
					}
					
					else if ($iSampleSize == 125)
					{
						$iDoubleSampling = 6;
						$iFirstFailure   = 9;
						$iSecondFailure  = 13;
					}
					
					else if ($iSampleSize == 200)
					{
						$iDoubleSampling = 8;
						$iFirstFailure   = 11;
						$iSecondFailure  = 19;
					}
					
					else
					{
						$iDoubleSampling = 12;
						$iFirstFailure   = 16;
						$iSecondFailure  = 27;
					}
					
					
					$sSamplingPlan = "D";
				}
				
				
				$fDeviation = 0;
			
				if (@in_array($iReportId, array(28, 37)))
					$fDeviation = (@round((($iOfferedQty / $iOrderQty) * 100), 2) - 100);
						



				$sAuditView = array("Auditor"        => md5($iAuditorId),
				                    "AuditDate"      => $Date,
									"Style"          => $sStyle,
									"ProductCode"    => (($sProductCode == "") ? "N/A" : $sProductCode),
									"Po"             => $sPo,
									"Pos"            => $sPos,
									"BrandId"        => $iBrand,
									"Brand"          => $sBrand,
									"Season"         => $sSeason,
									"EtdRequired"    => (($sEtdRequired == "") ? "N/A" : formatDate($sEtdRequired)),
									"ReportId"       => $iReportId,
									"Vendor"         => (($sUserType == "LEVIS") ? getDbValue("CONCAT(code, ' - ', vendor)", "tbl_vendors", "id='$iVendorId'") : getDbValue("vendor", "tbl_vendors", "id='$iVendorId'")),
									"Colors"         => $sColors,
									"Sizes"          => $sSizes,
									"Quantities"     => $sQuantities,
									"SamplingPlan"   => $sSamplingPlan,
									"OfferedQty"     => $iOfferedQty,
									"OrderQty"       => $iOrderQty,
									"Deviation"      => $fDeviation,
									"SampleSize"     => $iSampleSize,
									"SampleChecked"  => $iSampleChecked,
									"Started"        => ((($sStartDateTime == "" || $sStartDateTime == "0000-00-00 00:00:00" || strtotime($sStartDateTime) < strtotime("2013-01-01 00:00:00")) && $iSampleChecked == 0) ? "N" : "Y"),
									"Comments"       => (($sComments == "") ? "N" : "Y"),
									"AuditResult"    => (($sAuditResult == "") ? "N/A" : $sAuditResult),
									"AuditStage"     => (($sType == "B") ? $sAuditServicesList[$sAuditStage] : $sAuditStagesList[$sAuditStage]),
									"Aql"            => $fAql,
									"DefectsAllowed" => $iDefectsAllowed,
									"Dr"             => $fDr,
									"Color"          => $sColor,
									"Picture"        => $sPicture,
									"SkipImage"      => (($sSkipImage == "Y") ? "Y" : "N"));
									
				if ($iReportId == 48)
					$sAuditView["PoColorSizeQty"] = $sPoColorSizeQty;


				$sAudits[] = array("Type"           => $sType,
				                   "DeviceId"       => $sDeviceId,
				                   "AuditCode"      => $sAuditCode,
				                   "StartTime"      => (($sType == "S") ? formatTime($sStartTime) : $sStartTime),
				                   "EndTime"        => (($sType == "S") ? formatTime($sEndTime) : $sEndTime),
								   "AuditType"      => $iAuditType,
				                   "AuditStage"     => $sAuditStage,
								   "AuditStageText" => (($sType == "B") ? $sAuditServicesList[$sAuditStage] : $sAuditStagesList[$sAuditStage]),
				                   "AuditResult"    => $sAuditResult,
								   "ReportId"       => $iReportId,
				                   "StyleId"        => $iStyleId,
				                   "SampleSize"     => $iSampleSize,
								   "Completed"      => (($sAuditResult != "" && $sComments != "") ? "Y" : "N"), // && ($iSampleChecked == 0 || ($iSampleChecked > 0 && $iSampleSize > 0 && $iSampleChecked == $iSampleSize))
				                   "OnGoing"        => $sOnGoing,
				                   "Color"          => $sColor,
				                   "Block"          => $iBlock,
				                   "Location"       => $sLocation,
				                   "Details"        => nl2br($sDetails),
				                   "Auditor"        => md5($iAuditorId),
				                   "AuditView"      => $sAuditView,
				                   "SizeSpecs"      => $sSizeSpecs,
								   "Published"      => $sPublished,
								   "CheckLevel"     => $iCheckLevel,
								   "SamplingPlan"   => $sSamplingPlan,
								   "DoubleSampling" => $iDoubleSampling,
								   "FirstFailure"   => $iFirstFailure,
								   "SecondFailure"  => $iSecondFailure);						   

				if ($sAuditStage != "" && $sAuditResult != "")
					$iCompleted ++;
			}




			$sToday = array("Day"       => (($Date != "") ? date("j", strtotime($Date)) : date("j")),
			                "Planned"   => $iProdAduits,
			                "Completed" => $iCompleted,
			                "Pending"   => ($iProdAduits - $iCompleted));


			$sSQL = "SELECT audit_date, DAYOFMONTH(audit_date) AS _Day, audit_stage, COUNT(1) AS _Audits
			         FROM tbl_qa_reports
			         $sConditions AND (audit_date BETWEEN DATE_SUB('$Date', INTERVAL 14 DAY) AND DATE_SUB('$Date', INTERVAL 1 DAY))
			         GROUP BY audit_date, audit_stage

			         UNION

			         SELECT from_date AS audit_date, DAYOFMONTH(from_date) AS _Day, 'Non-Prod.' AS audit_stage, COUNT(1) AS _Audits
					 FROM tbl_user_schedule
					 WHERE user_id='$iUser' AND (from_date BETWEEN DATE_SUB('$Date', INTERVAL 14 DAY) AND DATE_SUB('$Date', INTERVAL 1 DAY))
			         GROUP BY from_date
					 
			         ORDER BY audit_date DESC, audit_stage";
			$objDb->query($sSQL);
			
			$iCount   = $objDb->getCount( );
			$sSummary = array( );
			$sDays    = array( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$sAuditDate  = $objDb->getField($i, 'audit_date');
				$iDay        = $objDb->getField($i, '_Day');
				$sAuditStage = $objDb->getField($i, 'audit_stage');
				$iAudits     = $objDb->getField($i, '_Audits');

				if (!@in_array($sAuditDate, $sDays))
				{
					$sDays[] = $sAuditDate;
					$iIndex  = count($sDays);
				}

				else
				{
					$iIndex = @array_search($sAuditDate, $sDays);
					$iIndex ++;
				}


				if ($iIndex > 5)
					continue;

				if ($sAuditStage != "Non-Prod." && $sAuditStage != "Bookings")
				{
					$sAuditStage = $sAuditStagesList[$sAuditStage];

					if ($sAuditStage == "")
						$sAuditStage = "n/a";
				}


				$sSummary["Day{$iIndex}"]["Day"]     = $iDay;
				$sSummary["Day{$iIndex}"]["Date"]    = $sAuditDate;
				$sSummary["Day{$iIndex}"]["Stats"][] = array("Stage" => $sAuditStage, "Audits" => $iAudits);
				
				$aResponse["Day-{$iIndex}"]["Day"]     = $iDay;
				$aResponse["Day-{$iIndex}"]["Date"]    = $sAuditDate;
				$aResponse["Day-{$iIndex}"]["Stats"][] = array("Stage" => $sAuditStage, "Audits" => $iAudits);
			}
			
			
			
			$sSQL = "SELECT audit_date, DAYOFMONTH(audit_date) AS _Day, audit_stage, COUNT(*) AS _Audits
			         FROM tbl_qa_reports
			         $sConditions AND (audit_date BETWEEN DATE_ADD('$Date', INTERVAL 1 DAY) AND DATE_ADD('$Date', INTERVAL 14 DAY))
			         GROUP BY audit_date, audit_stage
			         ORDER BY audit_date ASC, audit_stage";
			$objDb->query($sSQL);

			$iCount   = $objDb->getCount( );
			$sPlanned = array( );
			$sDays    = array( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$sAuditDate  = $objDb->getField($i, 'audit_date');
				$iDay        = $objDb->getField($i, '_Day');
				$sAuditStage = $objDb->getField($i, 'audit_stage');
				$iAudits     = $objDb->getField($i, '_Audits');

				if (!@in_array($sAuditDate, $sDays))
				{
					$sDays[] = $sAuditDate;
					$iIndex  = count($sDays);
				}

				else
				{
					$iIndex = @array_search($sAuditDate, $sDays);
					$iIndex ++;
				}


				if ($iIndex > 5)
					continue;

				
				$sAuditStage = $sAuditStagesList[$sAuditStage];

				if ($sAuditStage == "")
					$sAuditStage = "n/a";


				$aResponse["Day+{$iIndex}"]["Day"]     = $iDay;
				$aResponse["Day+{$iIndex}"]["Date"]    = $sAuditDate;
				$aResponse["Day+{$iIndex}"]["Stats"][] = array("Stage" => $sAuditStage, "Audits" => $iAudits);
			}


			$aResponse['Status']  = "OK";
			$aResponse['Today']   = $sToday;
			$aResponse['Summary'] = $sSummary;
			$aResponse['Audits']  = $sAudits;
		}
	}


	print @json_encode($aResponse);

/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = @json_encode($aResponse);

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>