<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$StartDate = IO::strValue("StartDate");
	$EndDate   = IO::strValue("EndDate");

	$StartDate = (($StartDate == "") ? "0000-00-00" : $StartDate);
	$EndDate   = (($EndDate == "") ? "0000-00-00" : $EndDate);


	$sSQL  = ("SELECT * FROM tbl_seasons WHERE brand_id='".IO::intValue("Brand")."' AND parent_id='".IO::intValue("Parent")."' AND season LIKE '".IO::strValue("Season")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$objDb->execute("BEGIN");


		$iSeason = getNextId("tbl_seasons");

		$sSQL  = ("INSERT INTO tbl_seasons (id, brand_id, parent_id, season, start_date, end_date, position) VALUES ('$iSeason', '".IO::intValue("Brand")."', '".IO::intValue("Parent")."', '".IO::strValue("Season")."', '$StartDate', '$EndDate', '$iSeason')");
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true && IO::intValue("Parent") > 0)
		{
			$sSQL = ("SELECT id FROM tbl_sampling_types WHERE brand_id='".IO::intValue("Brand")."'");
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iType = $objDb->getField($i, 0);


				$iId = getNextId("tbl_sampling_cutoff_dates");

				$sSQL  = "INSERT INTO tbl_sampling_cutoff_dates (id, season_id, type_id, start_date, end_date) VALUES ('$iId', '$iSeason', '$iType', '0000-00-00', '0000-00-00')";
				$bFlag = $objDb2->execute($sSQL);

				if ($bFlag == false)
					break;
			}
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			redirect($_SERVER['HTTP_REFERER'], "SEASON_ADDED");
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION['Flag'] = "DB_ERROR";
		}
	}

	else
		$_SESSION['Flag'] = "SEASON_EXISTS";


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>