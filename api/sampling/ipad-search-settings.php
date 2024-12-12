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

	$User  = IO::intValue('User');
	$Brand = IO::intValue('Brand');


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

		else
		{
			$sUserBrands = getDbValue("brands", "tbl_users", "id='$User'");
			$sBrandsList = getList("tbl_brands", "id", "brand", "parent_id>'0' AND FIND_IN_SET(id, '$sUserBrands')");

			if ($Brand > 0)
			{
				$iParent = getDbValue("parent_id", "tbl_brands", "id='$Brand'");

				$sTypesList      = getList("tbl_sampling_types", "id", "type", "brand_id='$iParent'");
				$sSeasonsList    = getList("tbl_seasons", "id", "season", "brand_id='$iParent' AND parent_id>'0'");
				$sCategoriesList = getList("tbl_style_categories", "id", "category", "id IN (SELECT DISTINCT(category_id) FROM tbl_styles WHERE sub_brand_id IN ($sUserBrands))");
			}

			else
			{
				$sTypesList      = array( );
				$sSeasonsList    = array( );
				$sCategoriesList = getList("tbl_style_categories", "id", "category", "id IN (SELECT DISTINCT(category_id) FROM tbl_styles WHERE sub_brand_id='$Brand')");
			}

			$sBrands     = array( );
			$sSeasons    = array( );
			$sTypes      = array( );
			$sCategories = array( );

			foreach ($sBrandsList as $sKey => $sValue)
				$sBrands[] = "{$sKey}||{$sValue}";

			foreach ($sSeasonsList as $sKey => $sValue)
				$sSeasons[] = "{$sKey}||{$sValue}";

			foreach ($sTypesList as $sKey => $sValue)
				$sTypes[] = "{$sKey}||{$sValue}";

			foreach ($sCategoriesList as $sKey => $sValue)
				$sCategories[] = "{$sKey}||{$sValue}";


			$aResponse['Status']     = "OK";
			$aResponse['Brands']     = @implode("|-|", $sBrands);
			$aResponse['Seasons']    = @implode("|-|", $sSeasons);
			$aResponse['Types']      = @implode("|-|", $sTypes);
			$aResponse['Categories'] = @implode("|-|", $sCategories);
			$aResponse['Statuses']   = "A||Accepted|-|R||Rejected|-|W||Working";
		}
	}


	print @json_encode($aResponse);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>