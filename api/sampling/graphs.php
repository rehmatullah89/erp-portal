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

	$Id       = IO::intValue("Id");
	$User     = IO::intValue("User");
	$Style    = IO::strValue("Style");
	$Brand    = IO::strValue("Brand");
	$Season   = IO::strValue("Season");
	$Category = IO::strValue("Category");
	$Back     = IO::strValue("Back");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/fusion-charts/FusionCharts.js"></script>
  <script type="text/javascript" src="api/sampling/scripts/graphs.js"></script>
</head>

<body>

<div id="MainDiv">
<?
	$sConditions = "";

	if ($Style != "")
		$sConditions .= " AND (style LIKE '%$Style%' OR style_name LIKE '%$Style%') ";

	if ($Brand > 0)
		$sConditions .= " AND sub_brand_id='$Brand' ";

	else
	{
		$sUserBrands = getDbValue("brands", "tbl_users", "id='$User'");

		$sConditions .= " AND sub_brand_id IN ($sUserBrands) ";
	}

	if ($Category > 0)
		$sConditions .= " AND category_id='$Category' ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));


	$sStyles  = "0";
	$sSeasons = "0";

	$sSQL = "SELECT id, sub_season_id FROM tbl_styles $sConditions ORDER BY id DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iSeason = $objDb->getField($i, 'sub_season_id');

		if ($Season == 0 || $Season == $iSeason)
			$sStyles  .= (",".$objDb->getField($i, 'id'));

		$sSeasons .= ",{$iSeason}";
	}


	$sSQL = "SELECT COUNT(DISTINCT(style_id)) FROM tbl_po_colors WHERE FIND_IN_SET(style_id, '$sStyles')";
	$objDb->query($sSQL);

	$iProduction = $objDb->getField(0, 0);



	$sSQL = "SELECT s.style,
	                SUM(CASE m.status WHEN 'A' THEN 1 ELSE 0 END) AS _Approved,
	                SUM(CASE m.status WHEN 'R' THEN 1 ELSE 0 END) AS _Rejected
	         FROM tbl_styles s, tbl_merchandisings m
	         WHERE s.id=m.style_id AND FIND_IN_SET(s.id, '$sStyles')
	         GROUP BY s.id
	         ORDER BY s.style";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	$sSeason = getDbValue("season", "tbl_seasons", "id='$Season'");
?>
  <table border="0" cellspacing="0" cellpadding="0" width="102%">
    <tr valign="top">
      <td bgcolor="#a60800"><h1><?= (($Brand > 0) ? getDbValue("brand", "tbl_brands", "id='$Brand'") : "Styles Listing") ?></h1></td>

      <td width="170" bgcolor="#ffffff">
		<h2 class="white"><?= (($Season > 0) ? $sSeason : "") ?></h2>
      </td>
    </tr>
  </table>


  <div id="Graph">
    <div id="Scroller">

	  <div id="StyleInfo">
		Total No of Styles<?= (($Season > 0) ? " '<b>{$sSeason}</b>' " : "") ?>: <?= $iCount ?><br />
		Approved for Production: <?= $iProduction ?><br />
		Pending Approvals: <?= ($iCount - $iProduction) ?><br />
	  </div>


	  <table border="0" cellspacing="0" cellpadding="0" width="102%">
		<tr>
		  <td width="100%" bgcolor="#ffffff"><h1 class="medium">Stats</h1></td>
		</tr>
	  </table>

	  <div id="StatsChart">loading...</div>

		<script type="text/javascript">
		<!--
			var objChart = new FusionCharts("scripts/fusion-charts/charts/StackedColumn3DLine.swf", "Stats", "100%", "300", "0", "1");

			objChart.setXMLData("<chart caption='Style wise Submissions' formatNumberScale='0' showValues='0' showLabels='1' showSum='1' chartBottomMargin='5' legendPosition='BOTTOM'>" +
								"<categories>" +
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$sStyle = $objDb->getField($i, 'style');
?>
								"<category label='<?= $sStyle ?>' />" +
<?
	}
?>
								"</categories>" +

								"<dataset seriesName='Rejected' color='#fd5c5c'>" +
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iRejected = $objDb->getField($i, '_Rejected');
?>
								"<set value='<?= $iRejected ?>' />" +
<?
	}
?>
								"</dataset>" +

								"<dataset seriesName='Approved' color='#30ed6e'>" +
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iApproved = $objDb->getField($i, '_Approved');
?>
								"<set value='<?= $iApproved ?>' />" +
<?
	}
?>
								"</dataset>" +

								"<dataset seriesName='Total' renderAs='Line' color='#30a4ed'>" +
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iRejected = $objDb->getField($i, '_Rejected');
		$iApproved = $objDb->getField($i, '_Approved');
?>
								"<set value='<?= ($iRejected + $iApproved) ?>' />" +
<?
	}
?>
								"</dataset>" +
								"</chart>");


			objChart.render("StatsChart");
		-->
		</script>


<?
	if ($Back != "No")
	{
?>
	  <br />
	  <br />
	  <center><a href="api/sampling/audits.php?Id=<?= $Id ?>&User=<?= $User ?>&Brand=<?= $Brand ?>&Category=<?= $Category ?>&Season=<?= $Season ?>&Style=<?= $Style ?>"><img src="api/sampling/images/backward.png" height="39" alt="" title="" /></a></center>
<?
	}
?>
    </div>
  </div>


  <div id="ProtoWare">
    <div>
      <a href="<?= SITE_URL.substr($_SERVER['REQUEST_URI'], 1) ?>">www.3-tree.com</a>
      ProtoWare&reg;
    </div>
  </div>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>