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


		$sConditions = " AND qa.audit_type='B' AND qa.audit_result!='' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";

		if ($Audits == "F")
			$sConditions .= " AND qa.audit_stage='F' ";

		else if ($Audits == "I")
			$sConditions .= " AND qa.audit_stage!='F' ";

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

			$sConditions .= "  AND FIND_IN_SET(category_id, '$sStyleCategories'))) ";
		}

		$sDefectTypes  = array( );
		$iDefectTypes  = array( );
		$iTotalDefects = array( );

		$sSQL = "SELECT dt.id, dt.type AS _DefectType, COALESCE(SUM(qad.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id AND IF(qa.report_id=10, qad.nature='1', TRUE) $sConditions
			 GROUP BY dc.type_id

			 UNION

	         SELECT dt.id, dt.type AS _DefectType, COALESCE(SUM(gfd.defects), 0) AS _Defects
			 FROM tbl_qa_reports qa, tbl_gf_report_defects gfd, tbl_defect_codes dc, tbl_defect_types dt
			 WHERE qa.id=gfd.audit_id AND gfd.code_id=dc.id AND dc.type_id=dt.id AND qa.report_id='6' $sConditions
			 GROUP BY dc.type_id

			 ORDER BY _Defects DESC, _DefectType ASC
			 LIMIT 5";
		$objDb->query($sSQL);
	
	//echo $sSQL;exit;
	
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