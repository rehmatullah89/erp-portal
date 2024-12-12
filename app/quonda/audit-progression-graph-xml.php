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

	$User      = IO::strValue('User');
	$AuditCode = IO::strValue('AuditCode');
	$Brand     = IO::intValue('Brand');
	$Vendor    = IO::intValue('Vendor');
	$Audits    = IO::strValue('Audits');
	$DateRange = IO::strValue('DateRange');

	@list($FromDate, $ToDate) = @explode(":", $DateRange);


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


	if ($AuditCode != "")
	{
		$sSQL = "SELECT po_id, style_id FROM tbl_qa_reports WHERE audit_code='$AuditCode'";
		$objDb->query($sSQL);

		$iPo    = $objDb->getField(0, "po_id");
		$iStyle = $objDb->getField(0, "style_id");

		if ($iStyle == 0)
			$iStyle = getDbValue("style_id", "tbl_po_colors", "po_id='$iPo'");
	}


	header("Content-type: text/xml");
?>
<chart caption=" " yAxisName="Defect Rate (DR)" xAxisName="Audit Date" bgcolor="dedede" canvasBgColor="ffffff" bgAlpha="100" canvasbgAlpha="100" showBorder="0" animation="1" numberSuffix="%" showPercentageValues="1" formatNumberScale="0" showValues="0" numVDivLines="8" showLimits="1" showShadow="0" showLabels="1" labelDisplay="ROTATE" slantLabels="1" showToolTip="1" chartTopMargin="0" chartBottomMargin="0" divLineAlpha="30" anchorRadius="7" anchorBgColor="0377D0" anchorBorderColor="333333" anchorBorderThickness="2" anchorAlpha="90" showPlotBorder="1" plotBorderThickness="4" plotBorderColor="333333" plotFillColor="0377D0" plotFillAlpha="25" toolTipBgColor="DEF1FF" toolTipBorderColor="2C516D">
<?
	$sSQL = "SELECT audit_code, audit_date, audit_stage, audit_result, dhu
	         FROM tbl_qa_reports
	         WHERE audit_result!='' AND style_id>'0' AND style_id='$iStyle'
	               AND FIND_IN_SET(audit_stage, '$sAuditStages') AND FIND_IN_SET(report_id, '$sReportTypes')
	         ORDER BY audit_date DESC
	         LIMIT 10";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = ($iCount - 1); $i >= 0; $i --)
	{
		$sAuditCode   = $objDb->getField($i, 'audit_code');
		$sAuditDate   = $objDb->getField($i, 'audit_date');
		$sAuditStage  = $objDb->getField($i, 'audit_stage');
		$sAuditResult = $objDb->getField($i, 'audit_result');
		$fDr          = (float)$objDb->getField($i, "dhu");


		$sColor      = getDbValue("color", "tbl_audit_stages", "code='$sAuditStage'");
		$sAuditStage = getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'");

		if ($sColor == "")
			$sColor = "#cccccc";

		switch ($sAuditResult)
		{
			case "P"  :  $sAuditResult = "Pass"; break;
			case "F"  :  $sAuditResult = "Fail"; break;
			case "H"  :  $sAuditResult = "Hold"; break;
			case "A"  :  $sAuditResult = "Pass"; break;
			case "B"  :  $sAuditResult = "Pass"; break;
			case "C"  :  $sAuditResult = "Fail"; break;
			default   :  $sAuditResult = "-"; break;
		}
?>
<set value="<?= formatNumber($fDr) ?>" label="<?= formatDate($sAuditDate) ?>" tooltext="Audit Code: <?= $sAuditCode ?>{br}Audit Stage: <?= $sAuditStage ?>{br}Audit Date: <?= formatDate($sAuditDate) ?>{br}DR: <?= formatNumber($fDr) ?>%" anchorbordercolor="#<?= (($sAuditResult == "Pass") ? "83af00" : (($sAuditResult == "Fail") ? "e40001" : "fe7100")) ?>" anchorbgcolor="<?= $sColor ?>" />
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