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

	$User      = IO::strValue('User');
	$AuditCode = IO::strValue('AuditCode');
	$Brand     = IO::intValue('Brand');
	$Vendor    = IO::intValue('Vendor');
	$Audits    = IO::strValue('Audits');
	$DateRange = IO::strValue('DateRange');

	@list($FromDate, $ToDate) = @explode(":", $DateRange);


	if ($User == "")
		die("Invalid Request");


	$sSQL = "SELECT id, vendors, brands, style_categories, status, guest FROM tbl_users WHERE MD5(id)='$User'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		die("Invalid User");

	else if ($objDb->getField(0, "status") != "A")
		die("User Account is Disabled");


	$sBrands          = $objDb->getField(0, "brands");
	$sVendors         = $objDb->getField(0, "vendors");
	$sStyleCategories = $objDb->getField(0, "style_categories");
	$sGuest           = $objDb->getField(0, "guest");



	$sSQL = "SELECT COUNT(*) AS _Total, SUM(CASE m.status WHEN 'R' THEN 1 ELSE 0 END) AS _Rejected
	         FROM tbl_comment_sheets cs, tbl_merchandisings m, tbl_styles s
	         WHERE cs.merchandising_id=m.id AND m.style_id=s.id AND (DATE_FORMAT(cs.created, '%Y-%m-%d') BETWEEN '$FromDate' AND '$ToDate')
	               AND FIND_IN_SET(s.category_id, '$sStyleCategories')";

	if ($Brand > 0)
		$sSQL .= " AND s.sub_brand_id='$Brand' ";

	else
		$sSQL .= " AND FIND_IN_SET(s.sub_brand_id, '$sBrands') ";

	if ($AuditCode != "")
		$sSQL .= "  AND s.id IN (SELECT style_id FROM tbl_qa_reports WHERE audit_code='$AuditCode') ";

	$objDb->query($sSQL);

	$iTotal    = $objDb->getField(0, '_Total');
	$iRejected = $objDb->getField(0, '_Rejected');

	$fRejectionRate = @round((($iRejected / $iTotal) * 100), 2);
?>
<chart caption="SRR <?= formatNumber($fRejectionRate) ?>%" bgcolor="d1d1d1" canvasBgColor="d1d1d1" bgAlpha="100" canvasbgAlpha="100" showBorder="0" numberPrefix="%" showPercentageValues="1" isSmartLineSlanted="0" showValues="0" showLabels="0" showToolTip="0" showLegend="0" chartTopMargin="0" chartBottomMargin="0" pieRadius="72">
<set value="<?= formatNumber($fRejectionRate) ?>" label="" color="ff0000" alpha="100" />
<set value="<?= formatNumber(100 - $fRejectionRate) ?>" label="" color="ffffff" alpha="100" />

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