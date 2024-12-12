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
	$objDb1       = new Database( );

	$User     = IO::intValue('User');
	$Brand     = IO::intValue('Brand');
	$Vendor    = IO::intValue('Vendor');
	$Audits    = IO::strValue('Audits');
	$AuditCode    = IO::strValue('AuditCode');

	$DateRange = IO::strValue('DateRange');

	@list($FromDate, $ToDate) = @explode(":", $DateRange);

	$aResponse = array();



	if(!empty($AuditCode)){

		$sDefectTypes  = array( );
		$iDefectTypes  = array( );
		$iTotalDefects = array( );

		$sSQL = "SELECT dt.id, dt.type AS _DefectType, COALESCE(SUM(qad.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id AND IF(qa.report_id=10, qad.nature='1', TRUE) AND qa.audit_code = '$AuditCode'
			 GROUP BY dc.type_id

			 UNION

	         SELECT dt.id, dt.type AS _DefectType, COALESCE(SUM(gfd.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_gf_report_defects gfd, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=gfd.audit_id AND gfd.code_id=dc.id AND dc.type_id=dt.id AND qa.report_id='6' AND qa.audit_code = '$AuditCode'
			 GROUP BY dc.type_id

			 ORDER BY _Defects DESC, _DefectType ASC
			 LIMIT 5";


		//print $sSQL;
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$defectImages=array();

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iDefectType = $objDb->getField($i, "id");
			$sDefectType = $objDb->getField($i, "_DefectType");
			$iDefects    = $objDb->getField($i, "_Defects");

			if (@in_array($iDefectType, $iDefectTypes))
			{
			$iIndex = @array_search($iDefectType, $iDefectTypes);

				$iTotalDefects[$iIndex] += $iDefects;
			}
			else
			{
			$iDefectTypes[]  = $iDefectType;
			$sDefectTypes[]  = $sDefectType;
			$iTotalDefects[] = $iDefects;
			}

			$ColorHex = getDbValue("color", "tbl_defect_types", "id ='$iDefectType'");

			$sSQL = "SELECT qa.audit_code, qa.audit_date, dc.code
						 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc
						 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id='$iDefectType' AND IF(qa.report_id=10, qad.nature='1', TRUE) AND qa.audit_code ='$AuditCode'  $sConditions

						 UNION

				         SELECT qa.audit_code, qa.audit_date, dc.code
						 FROM tbl_qa_reports qa, tbl_gf_report_defects gfd, tbl_defect_codes dc
						 WHERE qa.id=gfd.audit_id AND gfd.code_id=dc.id AND dc.type_id='$iDefectType' AND qa.report_id='6' AND qa.audit_code ='$AuditCode' $sConditions

							LIMIT 10;
						 ";

			//$sSQL = "SELECT audit_code, audit_date FROM tbl_qa_reports where audit_code = '$AuditCode'";

						 $objDb1->query($sSQL);
						 $iCount2 = $objDb1->getCount( );

			//print $sSQL;exit;
							//echo $iCount2;

							for ($j = 0; $j < $iCount2; $j++)
							{

								$sAudit_Date = $objDb1->getField($j, "audit_date");
								//print $sAudit_Date.":";
								$sAudit_Code = $objDb1->getField($j, "audit_code");

								$sCode = $objDb1->getField($j,"code");

								//echo $sCode."-";

								@list($sYear, $sMonth, $sDay) = @explode('-', $sAudit_Date);
								//print_r(explode('-', $sAuditDate));
			//print $sCode;
										$sAuditPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAudit_Code, 1)."_*.*");
										//print_r($sAuditPictures);exit;
										$files = preg_grep( "/_{$sCode}_/", $sAuditPictures );
											$fileName = "";
											if(count($files)>0){

												$selc = key($files);
												$fileName = $sAuditPictures[$selc];

											}

											if($fileName == "")
											$defectImages[$iDefectType][] = "";
											else{
												$sDefectPic = (SITE_URL.str_replace('../', '', $fileName));

											$defectImages[$iDefectType][] = $sDefectPic;
											}

										//print_r($files);

											//$sAuditPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAudit_Code, 1)."_".$sCode.".*");

										//print $sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAudit_Code, 1)."_".$sCode.".*";
										//print $sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAudit_Code, 1)."_*.*";

										//print_r($sAuditPictures);exit;
										if (count($sAuditPictures) > 0)
										{
											$sPictures = array( );
											$iLength   = strlen($sAuditCode);

											foreach ($sAuditPictures as $sDefectPicture)
											{
												/*if (substr(@basename($sDefectPicture), 0, ($iLength + 4)) == "{$sAuditCode}_001" ||
												    substr(@basename($sDefectPicture), 0, ($iLength + 4)) == "{$sAuditCode}_00_" ||
												    strlen(@basename($sDefectPicture)) < ($iLength + 6))
													continue;*/

												$sParts = @explode("_", $sDefectPicture);
												$sCode  = trim($sParts[1]);

												if (@array_key_exists($sCode, $sPictures))
													$sPictures[$sCode] ++;

												else
													$sPictures[$sCode] = 1;
											}

											@arsort($sPictures);
/*
											$sDefectPic = "";
											$sCode      = "";

											foreach ($sPictures as $sDefectCode => $iDefectCount)
											{
												//if ($sCode == "")
													$sCode = $sDefectCode;
													$sDefectPic = "";

													//$key = array_search("_{$sCode}_", $sAuditPictures); // $key = 2;

												foreach ($sAuditPictures as $sDefectPicture)
												{

													if ($sDefectPic == "" && @strpos($sDefectPicture, "_{$sCode}_") !== FALSE)
														$sDefectPic = $sDefectPicture;

													if ($sDefectPic == "")
														$sDefectPic = "N/A";
													else
														$sDefectPic = (SITE_URL.str_replace('../', '', $sDefectPic));

												}

												//$typeid = getDbValue("type_id", "tbl_defect_codes", "code ='$sCode'");
												//$defectImages[$iDefectType][] = $sDefectPic;
											}
*/
											foreach ($sAuditPictures as $sDefectPicture)
											{
												/*if (substr(@basename($sDefectPicture), 0, ($iLength + 4)) == "{$sAuditCode}_001" ||
												    substr(@basename($sDefectPicture), 0, ($iLength + 4)) == "{$sAuditCode}_00_" ||
												    strlen(@basename($sDefectPicture)) < ($iLength + 6))
													continue;*/


												//if ($sDefectPic == "" && @strpos($sDefectPicture, "_{$sCode}_") !== FALSE)
												//	$sDefectPic = $sDefectPicture;
											}


											/*if ($sDefectPic == "")
												$sDefectPic = "N/A";
											else
												$sDefectPic = (SITE_URL.str_replace('../', '', $sDefectPic));*/
										}else{

											//$sDefectPic = "N/A";
										}
									//$defectImages[$iDefectType][] = $sDefectPic;

							}

						//print $sSQL; exit;

						$aResponse['defect_images_per_type'] = $defectImages;




			$aResponse['number_defect']= $iDefectTypes;
			$aResponse['defect_types']= $sDefectTypes;
			$aResponse['defect_color'][]= $ColorHex;
			$aResponse['total_defects']= $iTotalDefects;

		}


		$aResponse['Status'] = "OK";



	}
	else {

	//$sSQL = "SELECT id, vendors, brands, style_categories, status FROM tbl_users WHERE MD5(id)='$User'";
		$sSQL = "SELECT id, vendors, brands, status FROM tbl_users WHERE id='$User'";
		$objDb->query($sSQL);

		$sBrands          = $objDb->getField(0, "brands");
		$sVendors         = $objDb->getField(0, "vendors");
		$sStyleCategories = $objDb->getField(0, "style_categories");


		$sConditions = " AND qa.audit_result!='' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";

		if ($Audits == "F")
			$sConditions .= " AND qa.audit_stage='F' ";

		else if ($Audits == "I")
			$sConditions .= " AND qa.audit_stage!='I' ";

		else
			$sConditions .= " AND qa.audit_stage!='' ";


		if ($Vendor > 0)
			$sConditions .= " AND qa.vendor_id='$Vendor' ";

		else
			$sConditions .= " AND qa.vendor_id IN ($sVendors) ";


		if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE)
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

			//$sConditions .= "  AND FIND_IN_SET(category_id, '$sStyleCategories'))) ";
			$sConditions .= "  )) ";

		}

		$sDefectTypes  = array( );
		$iDefectTypes  = array( );
		$iTotalDefects = array( );

		$sSQL = "SELECT qa.audit_code, dt.id, dt.type AS _DefectType, COALESCE(SUM(qad.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id AND IF(qa.report_id=10, qad.nature='1', TRUE) $sConditions
			 GROUP BY dc.type_id

			 UNION

	         SELECT qa.audit_code, dt.id, dt.type AS _DefectType, COALESCE(SUM(gfd.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_gf_report_defects gfd, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=gfd.audit_id AND gfd.code_id=dc.id AND dc.type_id=dt.id AND qa.report_id='6' $sConditions
			 GROUP BY dc.type_id

			 ORDER BY _Defects DESC, _DefectType ASC LIMIT 6
			 ";
		$objDb->query($sSQL);

	//echo $sSQL;exit;

	$defectImages=array();

	//$intermediate=array();

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iDefectType = $objDb->getField($i, "id");
			$sDefectType = $objDb->getField($i, "_DefectType");
			$iDefects    = $objDb->getField($i, "_Defects");

			if (@in_array($iDefectType, $iDefectTypes))
			{
				$iIndex = @array_search($iDefectType, $iDefectTypes);

				$iTotalDefects[$iIndex] += $iDefects;
			}

			else
			{
				$iDefectTypes[]  = $iDefectType;
				$sDefectTypes[]  = $sDefectType;
				$iTotalDefects[] = $iDefects;
			}

			$ColorHex = getDbValue("color", "tbl_defect_types", "id ='$iDefectType'");

			$sSQL = "SELECT qa.audit_code, qa.audit_date
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc
			 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id='$iDefectType' AND IF(qa.report_id=10, qad.nature='1', TRUE)  $sConditions

			 UNION

	         SELECT qa.audit_code, qa.audit_date
			 FROM tbl_qa_reports qa, tbl_gf_report_defects gfd, tbl_defect_codes dc
			 WHERE qa.id=gfd.audit_id AND gfd.code_id=dc.id AND dc.type_id='$iDefectType' AND qa.report_id='6' $sConditions

				LIMIT 10;
			 ";

			 $objDb1->query($sSQL);
			 $iCount2 = $objDb1->getCount( );

//print $sSQL;exit;
				//echo $iCount2;

				for ($j = 0; $j < $iCount2; $j++)
				{

					$sAudit_Date = $objDb1->getField($j, "audit_date");
					//print $sAudit_Date.":";
					$sAudit_Code = $objDb1->getField($j, "audit_code");

					@list($sYear, $sMonth, $sDay) = @explode('-', $sAudit_Date);
					//print_r(explode('-', $sAuditDate));
//print $sYear;
							$sAuditPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAudit_Code, 1)."_*.*");

							//print $sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_*.*";

							//print_r($sAuditPictures);exit;
							if (count($sAuditPictures) > 0)
							{
								$sPictures = array( );
								$iLength   = strlen($sAuditCode);

								foreach ($sAuditPictures as $sDefectPicture)
								{
									if (substr(@basename($sDefectPicture), 0, ($iLength + 4)) == "{$sAuditCode}_001" ||
									    substr(@basename($sDefectPicture), 0, ($iLength + 4)) == "{$sAuditCode}_00_" ||
									    strlen(@basename($sDefectPicture)) < ($iLength + 6))
										continue;

									$sParts = @explode("_", $sDefectPicture);
									$sCode  = trim($sParts[1]);

									if (@array_key_exists($sCode, $sPictures))
										$sPictures[$sCode] ++;

									else
										$sPictures[$sCode] = 1;
								}

								@arsort($sPictures);

								$sDefectPic = "";
								$sCode      = "";

								foreach ($sPictures as $sDefectCode => $iDefectCount)
								{
									if ($sCode == "")
										$sCode = $sDefectCode;
								}

								foreach ($sAuditPictures as $sDefectPicture)
								{
									if (substr(@basename($sDefectPicture), 0, ($iLength + 4)) == "{$sAuditCode}_001" ||
									    substr(@basename($sDefectPicture), 0, ($iLength + 4)) == "{$sAuditCode}_00_" ||
									    strlen(@basename($sDefectPicture)) < ($iLength + 6))
										continue;


									if ($sDefectPic == "" && @strpos($sDefectPicture, "_{$sCode}_") !== FALSE)
										$sDefectPic = $sDefectPicture;
								}


								if ($sDefectPic == "")
									$sDefectPic = "N/A";
								else
									$sDefectPic = (SITE_URL.str_replace('../', '', $sDefectPic));
							}
						$defectImages[$iDefectType][] = $sDefectPic;

				}

			//print $sSQL; exit;

			$aResponse['defect_images_per_type'] = $defectImages;


			$aResponse['number_defect']= $iDefectTypes;
			$aResponse['defect_types']= $sDefectTypes;
			$aResponse['defect_color'][]= $ColorHex;
			$aResponse['total_defects']= $iTotalDefects;

		}

	//print_r($intermediate);

	/*for($intermediate['defect_types'] as $ind => $val){

		$aResponse['Data'][$val[$ind]] = $intermediate['number_defect'][$ind]/$intermediate['total_defects'][$ind];

	}*/

		$aResponse['Status'] = "OK";

	}

	print @json_encode($aResponse);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>