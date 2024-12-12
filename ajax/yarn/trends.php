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

	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");


	$sSQL = "SELECT * FROM tbl_cotton_rates WHERE (day BETWEEN '$FromDate' AND '$ToDate') ORDER BY day";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	$sFromDate = $objDb->getField(0, 'day');
	$sToDate   = $objDb->getField(($iCount - 1), 'day');
?>
					<chart caption='Cotton Rates (<?= formatDate($sFromDate) ?> ... <?= formatDate($sToDate) ?>)' legendPosition='BOTTOM' palette='1' numberPrefix='$' decimals='3' formatNumberScale='3' showToolTip='1' labelDisplay='AUTO' chartBottomMargin='15' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='cotton-rates'>
					<categories>
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$sDate = $objDb->getField($i, 'day');
?>
									<category label='<?= formatDate($sDate, "d-M-y") ?>' />
<?
	}
?>
									</categories>" +

									<dataset seriesName='PAK Cotton' color='AFD8F8' lineThickness='2' >
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$fPakCotton = $objDb->getField($i, 'pak_cotton');

		if ($fPakCotton == 0)
		{
			for ($j = ($i + 1); $j < $iCount; $j ++)
			{
				$fPakCotton = $objDb->getField($j, 'pak_cotton');

				if ($fPakCotton > 0)
					break;
			}
		}

		$fPakCotton /= 100;
?>
										  <set value='<?= formatNumber($fPakCotton, true, 3) ?>' />
<?
	}
?>
										</dataset>

										<dataset seriesName='US Cotton (NY)' color='F6BD0F' lineThickness='2' >
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$fUsCotton = $objDb->getField($i, 'us_cotton');

		if ($fUsCotton == 0)
		{
			for ($j = ($i + 1); $j < $iCount; $j ++)
			{
				$fUsCotton = $objDb->getField($j, 'us_cotton');

				if ($fUsCotton > 0)
					break;
			}
		}

		$fUsCotton /= 100;
?>
										  <set value='<?= formatNumber($fUsCotton, true, 3) ?>' />
<?
	}
?>
										</dataset>
										</chart>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>