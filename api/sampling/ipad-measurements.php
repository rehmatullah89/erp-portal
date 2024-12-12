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

	$Code  = IO::strValue("Code");
	$iCode = intval(substr($Code, 1));


	$sSQL = "SELECT sample_sizes, sample_quantities, modified FROM tbl_merchandisings WHERE id='$iCode'";
	$objDb->query($sSQL);

	$sSampleSizes      = $objDb->getField(0, 'sample_sizes');
	$sSampleQuantities = $objDb->getField(0, 'sample_quantities');
	$iEntryTime        = @strtotime($objDb->getField(0, 'modified'));
	$iOrderTime        = @strtotime(date("2010-01-20 23:59:59"));
	$sOrderField       = "id";

	if ($iEntryTime > $iOrderTime)
		$sOrderField = "display_order";

	$iSampleQuantities = @explode(",", $sSampleQuantities);
	$iSampleSizes      = array( );


	$sSQL = "SELECT size FROM tbl_sampling_sizes WHERE id IN ($sSampleSizes) ORDER BY $sOrderField";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$iSampleSizes[] = $objDb->getField($i, 0);
	}

	$iSizesCount      = count($iSampleSizes);
	$iQuantitiesCount = count($iSampleQuantities);
?>
				<table border="1" bordercolor="#cccccc" cellpadding="3" cellspacing="0" width="100%">
				  <tr bgcolor="#dddddd">
					<td width="300" rowspan="3">&nbsp;<b>Measurement Point</b></td>
					<td width="80" rowspan="3" align="center"><b>Tolerance</b></td>
					<td width="<?= ((@array_sum($iSampleQuantities) + $iQuantitiesCount) * 50) ?>" align="center" colspan="<?= (@array_sum($iSampleQuantities) + $iQuantitiesCount) ?>">&nbsp;<b>Sizes</b></td>
				  </tr>

				  <tr bgcolor="#dddddd">
<?
	for ($i = 0; $i < $iSizesCount; $i ++)
	{
?>
					<td width="<?= (($iSampleQuantities[$i] + 1 + $iQuantitiesCount) * 50) ?>" align="center" colspan="<?= ($iSampleQuantities[$i] + 1) ?>"><b><?= $iSampleSizes[$i] ?></b></td>
<?
	}
?>
				  </tr>

				  <tr bgcolor="#dddddd">
<?
	for ($i = 0; $i < $iSizesCount; $i ++)
	{
		for ($j = 0; $j <= $iSampleQuantities[$i]; $j ++)
		{
			$sHeading = $j;

			if ($j == 0)
				$sHeading = "Spec";
?>
					<td width="50" align="center"><b><?= $sHeading ?></b></td>
<?
		}
	}
?>
				  </tr>
<?
	$sSQL = "SELECT ms.data, ms.tolerance, CONCAT(mp.point_id, ' - ', mp.point) AS _Point
	         FROM tbl_measurement_specs ms, tbl_measurement_points mp
	         WHERE ms.point_id=mp.id AND ms.merchandising_id='$iCode'
	         ORDER BY ms.id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for($i = 0; $i < $iCount; $i ++)
	{
		$sPoint     = $objDb->getField($i, '_Point');
		$sTolerance = $objDb->getField($i, 'tolerance');
		$sData      = $objDb->getField($i, 'data');

		$sData = @explode(",", $sData);
?>

				  <tr class="sdRowColor">
					<td width="300" align="left">&nbsp;<?= $sPoint ?></td>
					<td width="80" align="center"><?= $sTolerance ?></td>
<?
		$iIndex = 0;

		for ($j = 0; $j < $iSizesCount; $j ++)
		{
			for ($k = 0; $k <= $iSampleQuantities[$j]; $k ++)
			{
?>
					<td width="50" align="center"><?= $sData[$iIndex] ?></td>
<?
				$iIndex ++;
			}
		}
?>
				  </tr>
<?
	}
?>
				</table>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>