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

	$User = IO::intValue('User');


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
			$sUserBrands     = getDbValue("brands", "tbl_users", "id='$User'");

			$sBrandsList     = getList("tbl_brands", "id", "brand", "id IN ($sUserBrands)");
			$sCategoriesList = getList("tbl_style_categories", "id", "category", "id IN (SELECT DISTINCT(category_id) FROM tbl_styles WHERE sub_brand_id IN ($sUserBrands))");

			$sBrands     = array( );
			$sCategories = array( );
			$sSeasons    = array( );

			foreach ($sBrandsList as $sKey => $sValue)
			{
				$sBrands[] = "{$sKey}||{$sValue}";

				$iParent = getDbValue("parent_id", "tbl_brands", "id='$sKey'");


				$sSQL = "SELECT id, season FROM tbl_seasons WHERE brand_id='$iParent' AND parent_id>'0'";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
					$sSeasons[] = ($objDb->getField($i, 0)."||{$sValue} > ".$objDb->getField($i, 1));
			}

			foreach ($sCategoriesList as $sKey => $sValue)
				$sCategories[] = "{$sKey}||{$sValue}";


			$aResponse['Status']     = "OK";
			$aResponse['Brands']     = @implode("|-|", $sBrands);
			$aResponse['Categories'] = @implode("|-|", $sCategories);
			$aResponse['Seasons']    = @implode("|-|", $sSeasons);
		}
	}


	print @json_encode($aResponse);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>