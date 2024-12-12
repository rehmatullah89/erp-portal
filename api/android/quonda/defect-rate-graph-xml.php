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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$User      = IO::strValue('User');
	$AuditCode = IO::strValue('AuditCode');
	$Brand     = IO::intValue('Brand');
	$Vendor    = IO::intValue('Vendor');
	$Audits    = IO::strValue('Audits');
	$DateRange = IO::strValue('DateRange');

	@list($FromDate, $ToDate) = @explode(":", $DateRange);


	if ($User == "")
		die("Invalid Request");


	$sSQL = "SELECT id, vendors, brands, style_categories, report_types, audit_stages, status FROM tbl_users WHERE MD5(id)='$User'";
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


	$sConditions = " AND qa.audit_type='B' AND qa.audit_result!='' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') AND FIND_IN_SET(qa.report_id, '$sReportTypes') ";

	if ($AuditCode != "")
		$sConditions .= " AND qa.audit_code='$AuditCode' ";

	if ($Audits == "F")
		$sConditions .= " AND qa.audit_stage='F' ";

	else if ($Audits == "I")
		$sConditions .= " AND qa.audit_stage!='F' ";

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

		$sConditions .= "  AND FIND_IN_SET(category_id, '$sStyleCategories'))) ";
	}




	$sSQL = "SELECT COALESCE(SUM(qa.total_gmts), 0),
					SUM( (SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id AND nature>'0') )
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id AND report_id!='6' $sConditions";
	$objDb->query($sSQL);

	$iQuantity = $objDb->getField(0, 0);
	$iDefects  = $objDb->getField(0, 1);



	$sSQL = "SELECT SUM( (SELECT COALESCE(SUM(actual_1 + actual_2 + actual_3), 0) FROM tbl_gf_rolls_info WHERE audit_id=qa.id) ),
					SUM( (SELECT COALESCE(SUM(defects), 0) FROM tbl_gf_report_defects WHERE audit_id=qa.id) )
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id AND report_id='6' $sConditions";
	$objDb->query($sSQL);

	$iQuantity += $objDb->getField(0, 0);
	$iDefects  += $objDb->getField(0, 1);


	$fDefectRate = @round( (($iDefects / $iQuantity) * 100), 2);


	header("Content-type: text/xml");
?>
<chart caption=" DR <?= formatNumber($fDefectRate, true, 1) ?>% " bgcolor="83ae00" canvasBgColor="83ae00" bgAlpha="100" canvasbgAlpha="100" showBorder="0" animation="1" numberPrefix="%" showPercentageValues="1" isSmartLineSlanted="0" showValues="0" showLabels="0" showToolTip="0" showLegend="0" chartTopMargin="0" chartBottomMargin="0" pieRadius="72" clickURL="javascript:showDefectTypesGraph( )">
<set value="<?= formatNumber($fDefectRate) ?>" label="" color="ff0000" alpha="100" link="javascript:showDefectTypesGraph( )" />
<set value="<?= formatNumber(100 - $fDefectRate) ?>" label="" color="ffffff" alpha="100" link="javascript:showDefectTypesGraph( )" />

<styles>
<definition>
<style name="CaptionFont" type="FONT" face="Verdana" size="15" color="ffffff" bold="1" />
</definition>

<application>
<apply toObject="CAPTION" styles="CaptionFont" />
</application>
</styles>

</chart>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>