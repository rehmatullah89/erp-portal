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
	$Audits    = IO::strValue('Audits'); // design Auditcode specific Query
	$AuditCode    = IO::strValue('AuditCode'); // design Auditcode specific Query
	$AuditType    = IO::strValue('AuditType');
	
	$DateRange = IO::strValue('DateRange');

	$aResponse = array( );

	@list($FromDate, $ToDate) = @explode(":", $DateRange);
	
	
	if(!empty($AuditCode)){
	
		$sSQL = "SELECT COALESCE(SUM(qa.total_gmts), 0),
					SUM( (SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id) )
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id AND report_id!='6' AND qa.audit_code ='$AuditCode'";
			 
			 //echo $sSQL;exit;
		$objDb->query($sSQL);
		
		$iQuantity = $objDb->getField(0, 0);
		$iDefects  = $objDb->getField(0, 1);

//print_r($iQuantity);

		$sSQL = "SELECT SUM( (SELECT COALESCE(SUM(actual_1 + actual_2 + actual_3), 0) FROM tbl_gf_rolls_info WHERE audit_id=qa.id) ),
					SUM( (SELECT COALESCE(SUM(defects), 0) FROM tbl_gf_report_defects WHERE audit_id=qa.id) )
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id AND report_id='6' AND qa.audit_code ='$AuditCode'";
		$objDb->query($sSQL);

		$iQuantity += $objDb->getField(0, 0);
		$iDefects  += $objDb->getField(0, 1);


		$fDefectRate = @round( (($iDefects / $iQuantity) * 100), 2);

		$aResponse['Status'] = "OK";
		$aResponse['defect_rate']  = $fDefectRate;
		$aResponse['sample_reject_rate']  = (rand(0,100)/10);		
	
	
	
	}else{
	

	//style_categories
	$sSQL = "SELECT id, vendors, brands, status FROM tbl_users WHERE id='$User'";
	$objDb->query($sSQL);

	//echo $sSQL; 

	$sBrands          = $objDb->getField(0, "brands");
	$sVendors         = $objDb->getField(0, "vendors");
	$sStyleCategories = $objDb->getField(0, "style_categories");

//	$sStyleCategories = $objDb->getField(0, "style_categories");

	$sConditions = " AND qa.audit_type='B' AND qa.audit_result!='' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";

	if ($AuditType == "F")
		$sConditions .= " AND qa.audit_stage='F' ";

	else if ($AuditType == "I")
		$sConditions .= " AND qa.audit_stage!='F' ";

	else
		$sConditions .= " AND qa.audit_stage!='' ";

	if ($Brand > 0)
		$sConditions .= " AND po.brand_id='$Brand' ";

	else
		$sConditions .= " AND po.brand_id IN ($sBrands) ";


	if ($Vendor > 0)
		$sConditions .= " AND po.vendor_id='$Vendor' ";

	else
		$sConditions .= " AND po.vendor_id IN ($sVendors) ";


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

		$sConditions .= "  OR FIND_IN_SET(category_id, '$sStyleCategories'))) ";
	}




	$sSQL = "SELECT COALESCE(SUM(qa.total_gmts), 0),
					SUM( (SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id) )
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id AND report_id!='6' $sConditions";
	
	
	$objDb->query($sSQL);
//echo $sSQL;exit(0);

	$iQuantity = $objDb->getField(0, 0);
	$iDefects  = $objDb->getField(0, 1);

//print_r($iQuantity);

	$sSQL = "SELECT SUM( (SELECT COALESCE(SUM(actual_1 + actual_2 + actual_3), 0) FROM tbl_gf_rolls_info WHERE audit_id=qa.id) ),
					SUM( (SELECT COALESCE(SUM(defects), 0) FROM tbl_gf_report_defects WHERE audit_id=qa.id) )
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id AND report_id='6' $sConditions";
	$objDb->query($sSQL);

	$iQuantity += $objDb->getField(0, 0);
	$iDefects  += $objDb->getField(0, 1);


	$fDefectRate = @round( (($iDefects / $iQuantity) * 100), 2);

	/*if ($User == 0)
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid User ID";
	}
	else
	{
	*/
		$aResponse['Status'] = "OK";
		$aResponse['defect_rate']  = $fDefectRate;
		$aResponse['sample_reject_rate']  = (rand(0,100)/10);		
	//}
	
	}
	

	print @json_encode($aResponse);

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>