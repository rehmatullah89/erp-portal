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

	if ($sUserRights['Edit'] != "Y")
	{
		print "ERROR|-|You havn\'t enough Rights to modify the selected Data.\n";
		exit( );
	}

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Id        = IO::intValue("Id");
	$Brand     = IO::intValue("Brand");
	$Parent    = IO::intValue("Parent");
	$Season    = IO::strValue("Season");
	$StartDate = IO::strValue("StartDate");
	$EndDate   = IO::strValue("EndDate");
	$sError    = "";

	$StartDate = (($StartDate == "") ? "0000-00-00" : $StartDate);
	$EndDate   = (($EndDate == "") ? "0000-00-00" : $EndDate);


	$sSQL = "SELECT id FROM tbl_seasons WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Season ID. Please select the proper Season to Edit.\n";
		exit( );
	}

	if ($Brand > 0)
	{
		$sSQL = "SELECT brand FROM tbl_brands WHERE id='$Brand'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Brand\n";

		else
			$sBrand = $objDb->getField(0, 0);
	}

	if ($Parent > 0)
	{
		$sSQL = "SELECT season FROM tbl_seasons WHERE id='$Parent'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Parent\n";

		else
			$sParent = $objDb->getField(0, 0);
	}

	if ($Season == "")
		$sError .= "- Invalid Season\n";

	if ($Parent > 0 && $StartDate == "")
		$sError .= "- Invalid Start Date\n";

	if ($Parent > 0 && $EndDate == "")
		$sError .= "- Invalid End Date\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}

	$sSQL  = "SELECT * FROM tbl_seasons WHERE brand_id='$Brand' AND parent_id='$Parent' AND season LIKE '$Season' AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
			$sSQL = "UPDATE tbl_seasons SET brand_id='$Brand', parent_id='$Parent', season='$Season', start_date='$StartDate', end_date='$EndDate' WHERE id='$Id'";

			if ($objDb->execute($sSQL) == true)
			{
				if ($Parent > 0)
				{
					$sSQL = "SELECT id FROM tbl_sampling_types WHERE brand_id='$Brand' AND id NOT IN (SELECT type_id FROM tbl_sampling_cutoff_dates WHERE season_id='$Id')";
					$objDb->query($sSQL);

					$iCount = $objDb->getCount( );

					for ($i = 0; $i < $iCount; $i ++)
					{
						$iType = $objDb->getField($i, 0);


						$iId = getNextId("tbl_sampling_cutoff_dates");

						$sSQL  = "INSERT INTO tbl_sampling_cutoff_dates (id, season_id, type_id, start_date, end_date) VALUES ('$iId', '$Id', '$iType', '0000-00-00', '0000-00-00')";
						$bFlag = $objDb2->execute($sSQL);

						if ($bFlag == false)
							break;
					}


					$sSQL = "SELECT id FROM tbl_sampling_cutoff_dates WHERE season_id='$Id' ORDER BY start_date";
					$objDb->query($sSQL);

					$iCount = $objDb->getCount( );

					for ($i = 0; $i < $iCount; $i ++)
					{
						$iDateId = $objDb->getField($i, "id");

						$sCutOffStartDate = IO::strValue("CutOffStartDate{$iDateId}");
						$sCutOffEndDate   = IO::strValue("CutOffEndDate{$iDateId}");

						$sCutOffStartDate = (($sCutOffStartDate == "") ? "0000-00-00" : $sCutOffStartDate);
						$sCutOffEndDate   = (($sCutOffEndDate == "") ? "0000-00-00" : $sCutOffEndDate);


						$sSQL = "UPDATE tbl_sampling_cutoff_dates SET start_date='$sCutOffStartDate', end_date='$sCutOffEndDate' WHERE id='$iDateId'";
						$objDb2->execute($sSQL);
					}
				}

				print ("OK|-|$Id|-|<div>The selected Season has been Updated successfully.</div>|-|$sBrand|-|$sParent|-|$Season|-|".formatDate($StartDate)."|-|".formatDate($EndDate));
			}

			else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified Season (with same Parent) already exists in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>