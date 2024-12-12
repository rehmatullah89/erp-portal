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

	if ($sUserRights['Delete'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id = IO::intValue('Id');

	$objDb->execute("BEGIN");

/*
	$sStyles = "";
	$sPo     = "";

	$sSQL = "SELECT id FROM tbl_styles WHERE brand_id='$Id' OR brand_id IN (SELECT id FROM tbl_brands WHERE parent_id='$Id')";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sStyles .= (",".$objDb->getField($i, 0));

		if ($sStyles != "")
			$sStyles = substr($sStyles, 1);


		$sSQL = "SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE style_id IN ($sStyles)";

		if ($objDb->query($sSQL) == true)
		{
			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
				$sPo .= (",".$objDb->getField($i, 0));

			if ($sPo != "")
				$sPo = substr($sPo, 1);
		}
	}
*/

	$sSQL = "SELECT logo, logo_png, logo_jpg, logo_svg FROM tbl_brands WHERE id='$Id'";
	$objDb->query($sSQL);

	$sLogo    = $objDb->getField(0, "logo");
	$sLogoPng = $objDb->getField(0, "logo_png");
	$sLogoJpg = $objDb->getField(0, "logo_jpg");
	$sLogoSvg = $objDb->getField(0, "logo_svg");
		
		
	$sSQL = "DELETE FROM tbl_brands WHERE id='$Id'";
	$bFlag = $objDb->execute($sSQL);
/*
	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_brands WHERE parent_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true && $sPo != "")
	{
		$sSQL  = "DELETE FROM tbl_po_quantities WHERE po_id IN ($sPo)";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true && $sStyles != "")
	{
		$sSQL  = "DELETE FROM tbl_po_colors WHERE style_id IN ($sStyles)";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true && $sPo != "")
	{
		$sSQL  = "DELETE FROM tbl_po WHERE id IN ($sPo)";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true && $sPo != "")
	{
		$sSQL = "UPDATE tbl_po SET quantity=(SELECT SUM(quantity) FROM tbl_po_quantities WHERE po_id=tbl_po.id GROUP BY po_id) WHERE id IN ($sPo)";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL  = "DELETE FROM tbl_styles WHERE brand_id='$Id' OR brand_id IN (SELECT id FROM tbl_brands WHERE parent_id='$Id')";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true && $sPo != "")
	{
		$sPo    = @explode(",", $sPo);
		$iCount = count($sPo);

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sSQL = "SELECT style_id, quantity DISTINCT(po_id) FROM tbl_po_colors WHERE style_id IN ($sStyles)";

			if ($objDb->query($sSQL) == true)
			{
				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
					$sPo .= (",".$objDb->getField($i, 0));

				if ($sPo != "")
					$sPo = substr($sPo, 1);
			}
		}
	}
*/

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_forecasts WHERE brand_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_revised_forecasts WHERE brand_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");

		
		if ($sLogo != "")
			@unlink($sBaseDir.BRANDS_IMG_DIR."source/".$sLogo);
		
		if ($sLogoPng != "")
			@unlink($sBaseDir.BRANDS_IMG_DIR."png/".$sLogoPng);
		
		if ($sLogoJpg != "")
			@unlink($sBaseDir.BRANDS_IMG_DIR."jpg/".$sLogoJpg);
		
		if ($sLogoSvg != "")
			@unlink($sBaseDir.BRANDS_IMG_DIR."svg/".$sLogoSvg);
	

		$_SESSION['Flag'] = "BRAND_DELETED";
	}

	else
	{
		$objDb->execute("ROLLBACK");

		$_SESSION['Flag'] = "DB_ERROR";
	}

	header("Location: {$_SERVER['HTTP_REFERER']}");

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>