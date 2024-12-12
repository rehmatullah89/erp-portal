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

	$iTotalAudits   = 0;
	$sAuditCodes    = "";
	$iTotalGmts     = 0;
	$iTotalDefects  = 0;
	$sPos           = array( );

//	                TRIM(TRAILING ', ' FROM GROUP_CONCAT(DISTINCT(qa.audit_code) SEPARATOR ', ')) AS _AuditCodes,	
	$sSQL = "SELECT COUNT(DISTINCT(qa.id)) AS _Audits,
					COALESCE(SUM(qa.total_gmts), 0) AS _TotalGmts,
					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id AND nature>'0')) AS _Defects,
					qa.po_id, qa.additional_pos
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result!='' $sAuditorSQL
			       AND FIND_IN_SET(qa.report_id, '$sReportTypes') AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";

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

	if ($AuditStage != "''" && $AuditStage != "")
	{
		if ($Step >= 2)
			$sSQL .= " AND qa.audit_stage='$AuditStage' ";

		else
			$sSQL .= " AND qa.audit_stage IN ($AuditStage) ";
	}

	else if ($sAuditStage != "" && $Sector != "")
		$sSQL .= " AND qa.audit_stage='$sAuditStage' ";

	else
		$sSQL .= " AND qa.audit_stage!='' ";


	if ($Line > 0)
		$sSQL .= " AND qa.line_id='$Line' ";

	if ($Type > 0)
		$sSQL .= " AND qa.id IN (SELECT audit_id FROM tbl_qa_report_defects WHERE code_id IN (SELECT id FROM tbl_defect_codes WHERE type_id='$Type')) ";

	if ($Code > 0)
		$sSQL .= " AND qa.id IN (SELECT audit_id FROM tbl_qa_report_defects WHERE code_id='$Code') ";

	$sSQL .= " ORDER BY qa.audit_code ";

	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$iTotalAudits   = $objDb->getField(0, "_Audits");
//		$sAuditCodes    = $objDb->getField(0, "_AuditCodes");
		$iTotalGmts     = $objDb->getField(0, "_TotalGmts");
		$iTotalDefects  = $objDb->getField(0, "_Defects");
/*
		$iPo            = $objDb->getField(0, "po_id");
		$sAdditionalPos = $objDb->getField(0, "additional_pos");


		if (!@in_array($iPo, $sPos))
			$sPos[] = $iPo;

		if ($sAdditionalPos != "")
		{
			$iAdditionalPos = @explode(",", $sAdditionalPos);

			foreach ($iAdditionalPos as $iPo)
			{
				if (!@in_array($iPo, $sPos))
					$sPos[] = $iPo;
			}
		}
*/
	}


	
//	                TRIM(TRAILING ', ' FROM GROUP_CONCAT(DISTINCT(qa.audit_code) SEPARATOR ', ')) AS _AuditCodes,
					
	$sSQL = "SELECT COUNT(DISTINCT(qa.id)) AS _Audits,
					SUM((SELECT COALESCE(SUM(actual_1 + actual_2 + actual_3), 0) FROM tbl_gf_rolls_info WHERE audit_id=qa.id)) AS _TotalGmts,
					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id)) AS _Defects,
					qa.po_id, qa.additional_pos
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result!='' $sAuditorSQL
			       AND FIND_IN_SET(qa.report_id, '$sReportTypes') AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";

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

	if ($AuditStage != "''" && $AuditStage != "")
	{
		if ($Step >= 2)
			$sSQL .= " AND qa.audit_stage='$AuditStage' ";

		else
			$sSQL .= " AND qa.audit_stage IN ($AuditStage) ";
	}

	else if ($sAuditStage != "" && $Sector != "")
		$sSQL .= " AND qa.audit_stage='$sAuditStage' ";

	else
		$sSQL .= " AND qa.audit_stage!='' ";


	if ($Line > 0)
		$sSQL .= " AND qa.line_id='$Line' ";
/*
	if ($Type > 0)
		$sSQL .= " AND qa.id IN (SELECT audit_id FROM tbl_gf_report_defects WHERE code_id IN (SELECT id FROM tbl_defect_codes WHERE type_id='$Type')) ";

	if ($Code > 0)
		$sSQL .= " AND qa.id IN (SELECT audit_id FROM tbl_gf_report_defects WHERE code_id='$Code') ";
*/
	$sSQL .= "ORDER BY qa.audit_code";

	$objDb->query($sSQL);


	if ($objDb->getCount( ) == 1)
	{
		$iTotalAudits   += $objDb->getField(0, "_Audits");
		$iTotalGmts     += $objDb->getField(0, "_TotalGmts");
		$iTotalDefects  += $objDb->getField(0, "_Defects");
/*
		$iPo            = $objDb->getField(0, "po_id");
		$sAdditionalPos = $objDb->getField(0, "additional_pos");


		if (!@in_array($iPo, $sPos))
			$sPos[] = $iPo;

		if ($sAdditionalPos != "")
		{
			$iAdditionalPos = @explode(",", $sAdditionalPos);

			foreach ($iAdditionalPos as $iPo)
			{
				if (!@in_array($iPo, $sPos))
					$sPos[] = $iPo;
			}
		}


		if ($sAuditCodes != "" && $objDb->getField(0, "_AuditCodes") != "")
			$sAuditCodes .= ", ";

		$sAuditCodes .= $objDb->getField(0, "_AuditCodes");
*/
	}


	if ($iTotalAudits > 0)
	{
		$sAllPos        = @implode(",", $sPos);
		$iTotalOrderQty = getDbValue("SUM(quantity)", "tbl_po", "FIND_IN_SET(id, '$sAllPos')");
?>
			    <br />

			    <div class="tblSheet">
				  <h2 style="margin:0px;">QA Summary</h2>

				  <table border="0" cellpadding="5" cellspacing="0" width="100%">
<!--
					<tr bgcolor="#e0e0e0">
					  <td width="150"><b>Order Quantity</b></td>
					  <td width="20" align="center">:</td>
					  <td><?= formatNumber($iTotalOrderQty, false) ?></td>
					</tr>
-->
					<tr bgcolor="#f6f6f6">
					  <td width="150"><b>Inspected Quantity</b></td>
					  <td width="20" align="center">:</td>
					  <td><?= formatNumber($iTotalGmts, false) ?></td>
					</tr>

					<tr bgcolor="#e0e0e0">
					  <td><b>Defects Found</b></td>
					  <td align="center">:</td>
					  <td><?= formatNumber($iTotalDefects, false) ?></td>
					</tr>

					<tr bgcolor="#f6f6f6">
					  <td><b>Audits Conducted</b></td>
					  <td align="center">:</td>
					  <td><?= formatNumber($iTotalAudits, false) ?><!-- &nbsp; ( <a href="#" onclick="Effect.toggle('AuditCodes', 'slide'); return false;">Audit Codes</a> )--></td>
					</tr>
				  </table>

				  <div id="AuditCodes" style="display:none;">
					<div style="padding:5px; background:#e0e0e0;">
					  <b>Audit Codes :</b><br />
					  <br style="line-height:5px;" />
					  <?= $sAuditCodes ?><br />
					</div>
				  </div>
			    </div>
<?
	}
?>