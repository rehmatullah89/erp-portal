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

	$AuditStage = IO::strValue("AuditStage");
	$Line       = IO::intValue("Line");
	$Type       = IO::intValue("Type");
	$Code       = IO::intValue("Code");


	$sConditions = " AND qa.audit_type='B' AND qa.audit_result!='' ";

	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";

	if ($OrderNo != "")
	{
		$sSQL = "SELECT id FROM tbl_po WHERE order_no='$OrderNo'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );


		$sConditions .= " AND ( ";

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iPoId = $objDb->getField($i, 0);

			if ($i > 0)
				$sConditions .= " OR ";

			$sConditions .= " qa.po_id='$iPoId' OR FIND_IN_SET('$iPoId', qa.additional_pos) ";
		}

		$sConditions .= " ) ";
	}

	if ($Vendor > 0)
		$sConditions .= " AND qa.vendor_id='$Vendor' ";

	else
		$sConditions .= " AND qa.vendor_id IN ($sUserVendors) ";

	if ($AuditStage != "")
		$sConditions .= " AND qa.audit_stage='$AuditStage' ";

	else
		$sConditions .= " AND qa.audit_stage!='' ";

	if ($Line > 0)
		$sConditions .= " AND qa.line_id='$Line' ";

	if ($Brand > 0)
	{
		if ($Vendor > 0)
			$sConditions .= " AND qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id='$Brand' AND vendor_id='$Vendor') ";

		else
			$sConditions .= " AND qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id='$Brand' AND vendor_id IN ($sUserVendors)) ";
	}

	else
	{
		if ($Vendor > 0)
			$sConditions .= " AND qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id IN ($sUserBrands) AND vendor_id='$Vendor') ";

		else
			$sConditions .= " AND qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id IN ($sUserBrands) AND vendor_id IN ($sUserVendors)) ";
	}

	if ($AuditCode != "")
		$sConditions .= " AND qa.audit_code LIKE '%$AuditCode%' ";
?>