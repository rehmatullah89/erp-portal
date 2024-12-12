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


	if ($Brand == 0 && $AuditCode != "")
	{
		$sSQL = "SELECT po_id, style_id FROM tbl_qa_reports WHERE audit_code='$AuditCode'";
		$objDb->query($sSQL);

		$iPo    = $objDb->getField(0, "po_id");
		$iStyle = $objDb->getField(0, "style_id");

		if ($iStyle == 0)
			$iStyle = getDbValue("style_id", "tbl_po_colors", "po_id='$iPo'");


		$Brand = getDbValue("sub_brand_id", "tbl_styles", "id='$iStyle'");
	}


	header("Content-type: text/xml");
?>
<chart caption=" " yAxisName="Defect Rate (DR)" xAxisName="Style" bgcolor="dedede" canvasBgColor="ffffff" bgAlpha="100" canvasbgAlpha="100" showBorder="0" animation="1" numberSuffix="%" showPercentageValues="1" formatNumberScale="0" showValues="0" numVDivLines="8" showLimits="1" showShadow="0" showLabels="1" labelDisplay="ROTATE" slantLabels="1" showToolTip="1" chartTopMargin="0" chartBottomMargin="0" divLineAlpha="30" anchorRadius="7" anchorBgColor="0377D0" anchorBorderColor="333333" anchorBorderThickness="2" anchorAlpha="90" showPlotBorder="1" plotBorderThickness="4" plotBorderColor="333333" plotFillColor="0377D0" plotFillAlpha="25" toolTipBgColor="DEF1FF" toolTipBorderColor="2C516D">
<?
	$sSQL = "SELECT style_id, audit_code, audit_date, dhu,
	                (SELECT style FROM tbl_styles WHERE id=tbl_qa_reports.style_id) AS _Style
	         FROM tbl_qa_reports
	         WHERE audit_stage='F' AND (audit_result='P' OR audit_result='A' OR audit_result='B') AND style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand')
	               AND ('$Vendor'='0' OR vendor_id='$Vendor')
	                AND FIND_IN_SET(audit_stage, '$sAuditStages') AND FIND_IN_SET(report_id, '$sReportTypes')
	         GROUP BY style_id
	         ORDER BY audit_date DESC
	         LIMIT 10";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = ($iCount - 1); $i >= 0; $i --)
	{
		$sStyle     = $objDb->getField($i, '_Style');
		$sAuditCode = $objDb->getField($i, 'audit_code');
		$sAuditDate = $objDb->getField($i, 'audit_date');
		$fDr        = (float)$objDb->getField($i, "dhu");
?>
<set value="<?= formatNumber($fDr) ?>" label="<?= $sStyle ?>" tooltext="Audit Code: <?= $sAuditCode ?>{br}Audit Date: <?= formatDate($sAuditDate) ?>{br}DR: <?= formatNumber($fDr) ?>%" anchorbordercolor="#83af00" anchorbgcolor="#fcbf04" />
<?
	}
?>

<styles>
<definition>
<style name="yAxisNameFont" type="FONT" face="Verdana" size="12" color="666666" bold="1" />
<style name="xAxisNameFont" type="FONT" face="Verdana" size="15" color="666666" bold="1" />
</definition>

<application>
<apply toObject="yAxisName" styles="yAxisNameFont" />
<apply toObject="xAxisName" styles="xAxisNameFont" />
</application>
</styles>

</chart>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>