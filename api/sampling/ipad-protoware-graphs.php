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
	$objDb2      = new Database( );


	$User     = IO::intValue("User");
	$Type     = IO::intValue("Type");
	$Brand    = IO::intValue("Brand");
	$Category = IO::intValue("Category");
	$Season   = IO::intValue("Season");
	$FromDate = IO::strValue('FromDate');
	$ToDate   = IO::strValue('ToDate');
	$Status   = IO::strValue('Status');

	$sUserBrands = getDbValue("brands", "tbl_users", "id='$User'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-touch-fullscreen" content="yes">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

  <script type="text/javascript" src="scripts/fusion-charts/FusionCharts.js"></script>
  <script type="text/javascript" src="scripts/fusion-charts/FusionChartsExportComponent.js"></script>
</head>

<body style="background:#ffffff;">

<div id="MainDiv" style="width:auto;">
  <table border="0" cellpadding="0" cellspacing="0" width="760">
	<tr valign="top">
	  <td width="50%">
<?
	$iAccepted = array( );
	$iRejected = array( );
	$sTypes    = array( );


	$sSQL = "SELECT m.status, t.type, COUNT(*)
	         FROM tbl_comment_sheets c, tbl_merchandisings m, tbl_styles s, tbl_sampling_types t
	         WHERE m.style_id=s.id AND m.id=c.merchandising_id AND (m.status='A' OR m.status='R') AND t.id=m.sample_type_id";

	if ($Brand > 0)
		$sSQL .= " AND s.sub_brand_id='$Brand' ";

	else
		$sSQL .= " AND s.sub_brand_id IN ($sUserBrands) AND t.final='Y' ";

	if ($Type > 0)
		$sSQL .= " AND m.sample_type_id='$Type' ";

	if ($Season > 0)
		$sSQL .= " AND s.sub_season_id='$Season' ";

	if ($FromDate != "" && $ToDate != "")
		$sSQL .= " AND (DATE_FORMAT(c.created, '%Y-%m-%d') BETWEEN '$FromDate' AND '$ToDate') ";

	if ($Status != "")
		$sSQL .= " AND m.status='$Status' ";

	if ($Category > 0)
		$sSQL .= " AND s.category_id='$Category' ";

	$sSQL .= " GROUP BY t.type, m.status
	           ORDER BY t.type, m.status";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sStatus = $objDb->getField($i, 0);
		$sType   = $objDb->getField($i, 1);
		$iAudits = $objDb->getField($i, 2);

		if (!@in_array($sType, $sTypes))
		{
			$sTypes[] = $sType;

			$iAccepted[] = 0;
			$iRejected[] = 0;
		}


		$iIndex = @array_search($sType, $sTypes);

		switch ($sStatus)
		{
			case "A" : $iAccepted[$iIndex] = $iAudits;  break;
			case "R" : $iRejected[$iIndex] = $iAudits;  break;
		}
	}
?>
					    <div id="HitRateChart">loading...</div>

					    <script type="text/javascript">
					    <!--
							var objChart = new FusionCharts("scripts/fusion-charts/charts/StackedColumn3D.swf", "HitRate", "100%", "265", "0", "1");

							objChart.setXMLData("<chart caption='Hit Rate Graph' numDivLines='10' formatNumberScale='0' showValues='0' showSum='1' showLabels='1' decimals='1' numberSuffix='' chartTopMargin='0' chartBottomMargin='0' plotFillAlpha='95'>" +

												"<categories>" +
<?
	for ($i = 0; $i < count($sTypes); $i ++)
	{
?>
												"<category label='<?= $sTypes[$i] ?>' />" +
<?
	}
?>
												"</categories>" +


												"<dataset seriesName='Accepted' color='7fff7f'>" +
<?
	for ($i = 0; $i < count($sTypes); $i ++)
	{
?>
												"<set value='<?= $iAccepted[$i] ?>' />" +
<?
	}
?>
												"</dataset>" +

												"<dataset seriesName='Rejected' color='ff0000'>" +
<?
	for ($i = 0; $i < count($sTypes); $i ++)
	{
?>
												"<set value='<?= $iRejected[$i] ?>' />" +
<?
	}
?>
												"</dataset>" +
											"</chart>");

							objChart.render("HitRateChart");
					    -->
					    </script>
				      </td>

				      <td width="50%">
<?
	$iOnTime = array( );
	$iLate   = array( );
	$sTypes  = array( );


	$sSQL = "SELECT IF(m.created <= m.required_date, 'O', 'L'), t.type, COUNT(*)
	         FROM tbl_comment_sheets c, tbl_merchandisings m, tbl_styles s, tbl_sampling_types t
	         WHERE m.style_id=s.id AND m.id=c.merchandising_id AND t.id=m.sample_type_id AND NOT ISNULL(m.required_date) AND m.required_date!='0000-00-00' AND m.status!='W'";

	if ($Brand > 0)
		$sSQL .= " AND s.sub_brand_id='$Brand' ";

	else
		$sSQL .= " AND s.sub_brand_id IN ($sUserBrands) AND t.final='Y' ";

	if ($Type > 0)
		$sSQL .= " AND m.sample_type_id='$Type' ";

	if ($Season > 0)
		$sSQL .= " AND s.sub_season_id='$Season' ";

	if ($FromDate != "" && $ToDate != "")
		$sSQL .= " AND (DATE_FORMAT(c.created, '%Y-%m-%d') BETWEEN '$FromDate' AND '$ToDate') ";

	if ($Status != "")
		$sSQL .= " AND m.status='$Status' ";

	if ($Category > 0)
		$sSQL .= " AND s.category_id='$Category' ";

	$sSQL .= " GROUP BY t.type, m.status
	           ORDER BY t.type, m.status";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sStatus = $objDb->getField($i, 0);
		$sType   = $objDb->getField($i, 1);
		$iAudits = $objDb->getField($i, 2);

		if (!@in_array($sType, $sTypes))
		{
			$sTypes[] = $sType;

			$iOnTime[] = 0;
			$iLate[]   = 0;
		}


		$iIndex = @array_search($sType, $sTypes);

		switch ($sStatus)
		{
			case "O" : $iOnTime[$iIndex] = $iAudits;  break;
			case "L" : $iLate[$iIndex] = $iAudits;  break;
		}
	}
?>
					    <div id="OtpChart">loading...</div>

					    <script type="text/javascript">
					    <!--
							var objChart = new FusionCharts("scripts/fusion-charts/charts/StackedColumn3D.swf", "Otp", "100%", "265", "0", "1");

							objChart.setXMLData("<chart caption='OTP Graph' numDivLines='10' formatNumberScale='0' showValues='0' showSum='1' showLabels='1' decimals='1' numberSuffix='' chartTopMargin='0' chartBottomMargin='0' plotFillAlpha='95'>" +

												"<categories>" +
<?
	for ($i = 0; $i < count($sTypes); $i ++)
	{
?>
												"<category label='<?= $sTypes[$i] ?>' />" +
<?
	}
?>
												"</categories>" +


												"<dataset seriesName='ON Time' color='7fff7f'>" +
<?
	for ($i = 0; $i < count($sTypes); $i ++)
	{
?>
												"<set value='<?= $iOnTime[$i] ?>' />" +
<?
	}
?>
												"</dataset>" +

												"<dataset seriesName='Late' color='ff0000'>" +
<?
	for ($i = 0; $i < count($sTypes); $i ++)
	{
?>
												"<set value='<?= $iLate[$i] ?>' />" +
<?
	}
?>
												"</dataset>" +

											"</chart>");


							objChart.render("OtpChart");
					    -->
					    </script>
	  </td>
	</tr>
  </table>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>