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

	$User     = IO::intValue('User');
	$Brand    = IO::intValue("Brand");
	$Category = IO::intValue("Category");


	$aResponse = array( );


	if ($User == 0)
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid User ID";
	}

	else
	{
		$sStatus = getDbValue("status", "tbl_users", "id='$User'");

		if ($sStatus != "A")
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "User Account is Disabled";
		}

		else if ($Brand == 0)
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "No Brand Selected";
		}

		else
		{
			$sConditions = "WHERE sub_brand_id='$Brand' ";

			if ($Category > 0)
				$sConditions .= " AND category_id='$Category' ";


			$sSeasons = array( );

			$sSQL = "SELECT DISTINCT(sub_season_id),
			                (SELECT season FROM tbl_seasons WHERE id=tbl_styles.sub_season_id) AS _Season
			         FROM tbl_styles
			         $sConditions
			         ORDER BY _Season";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iSeason = $objDb->getField($i, 'sub_season_id');
				$sSeason = $objDb->getField($i, '_Season');

				$sSeasons[] = "{$iSeason}||{$sSeason}";
			}

			$aResponse['Status']  = "OK";
			$aResponse['Seasons'] = @implode("|-|", $sSeasons);
		}
	}


	print @json_encode($aResponse);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>