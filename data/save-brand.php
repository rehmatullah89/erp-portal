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

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$sSQL  = ("SELECT * FROM tbl_brands WHERE (brand LIKE '".IO::strValue("Brand")."' OR (code LIKE '".IO::strValue("Code")."' AND '".IO::strValue("Code")."'!='')) AND parent_id='".IO::intValue("Parent")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$sStages     = @implode(",", IO::getArray("Stages"));
                $sCategories = @implode(",", IO::getArray("Categories"));

		if ($sStages == "")
		{
			$sStagesList = getList("tbl_production_stages", "id", "title");

			foreach ($sStagesList as $sKey => $sValue)
				$sStages .= ((($sStages != "") ? "," : "").$sKey);
		}



		$bFlag    = $objDb->execute("BEGIN");
		$iId      = getNextId("tbl_brands");
		$sLogo    = "";
		$sLogoPng = "";
		$sLogoJpg = "";
		$sLogoSvg = "";
		
		
		if ($_FILES['Logo']['name'] != "")
		{
			$sLogo = ($iId."-".IO::getFileName($_FILES['Logo']['name']));

			if (!@move_uploaded_file($_FILES['Logo']['tmp_name'], ($sBaseDir.BRANDS_IMG_DIR.'source/'.$sLogo)))
					$sLogo = "";
		}
		
		if ($_FILES['LogoPng']['name'] != "")
		{
			$sLogoPng = ($iId."-".IO::getFileName($_FILES['LogoPng']['name']));

			if (!@move_uploaded_file($_FILES['LogoPng']['tmp_name'], ($sBaseDir.BRANDS_IMG_DIR.'png/'.$sLogoPng)))
					$sLogoPng = "";
		}
		
		if ($_FILES['LogoJpg']['name'] != "")
		{
			$sLogoJpg = ($iId."-".IO::getFileName($_FILES['LogoJpg']['name']));

			if (!@move_uploaded_file($_FILES['LogoJpg']['tmp_name'], ($sBaseDir.BRANDS_IMG_DIR.'jpg/'.$sLogoJpg)))
					$sLogoJpg = "";
		}
		
		if ($_FILES['LogoSvg']['name'] != "")
		{
			$sLogoSvg = ($iId."-".IO::getFileName($_FILES['LogoSvg']['name']));

			if (!@move_uploaded_file($_FILES['LogoSvg']['tmp_name'], ($sBaseDir.BRANDS_IMG_DIR.'svg/'.$sLogoSvg)))
					$sLogoSvg = "";
		}
		

		$sSQL  = ("INSERT INTO tbl_brands (id, parent_id, brand, code, aql, aql_minor, manager, merchandisers, vendors, stages, categories, type, qmip, regular, logo, logo_png, logo_jpg, logo_svg, inspection_level)
		                           VALUES ('$iId', '".IO::intValue("Parent")."', '".IO::strValue("Brand")."', '".IO::strValue("Code")."', '".IO::floatValue("AQL")."', '".IO::floatValue("AQLMinor")."', '".IO::intValue("Manager")."', '".@implode(",", IO::getArray("Merchandisers"))."', '".@implode(",", IO::getArray("Vendors"))."', '$sStages', '$sCategories', '".IO::strValue("Type")."', '".IO::strValue("Qmip")."', '".IO::strValue("Regular")."', '$sLogo', '$sLogoPng', '$sLogoJpg', '$sLogoSvg', '".IO::strValue("Level")."')");
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true && IO::intValue("Parent") > 0)
		{
			$sType = getDbValue("type", "tbl_brands", ("id='".IO::intValue("Parent")."'"));


			$sSQL  = "UPDATE tbl_brands SET type='$sType' WHERE id='$iId'";
			$bFlag = $objDb->execute($sSQL);
		}
/*
		if (IO::intValue("Parent") == 385)
		{
			$sUsers = array(2, 3, 319, 709);
			
			$sSQL = "SELECT id FROM tbl_users WHERE user_type='GLOBALEXPORTS' AND status='A'";
			$objDb->query($sSQL);
			
			$iCount = $objDb->getCount( );
			
			for ($i = 0; $i < $iCount; $i ++)
				$sUsers[] = $objDb->getField($i, "id");
			
			
			foreach ($sUsers as $iUser)
			{
				$sBrands = getDbValue("brands", "tbl_users", "id='$iUser'");
			

				$sSQL  = "UPDATE tbl_users SET brands='{$sBrands},{$iId}' WHERE id='$iUser'";
				$bFlag = $objDb->execute($sSQL);

				if ($bFlag == false)
					break;
			}
		}
*/
		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			redirect($_SERVER['HTTP_REFERER'], "BRAND_ADDED");
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION['Flag'] = "DB_ERROR";
			
			if ($sLogo != "")
				@unlink($sBaseDir.BRANDS_IMG_DIR."source/".$sLogo);
			
			if ($sLogoPng != "")
				@unlink($sBaseDir.BRANDS_IMG_DIR."png/".$sLogoPng);
			
			if ($sLogoJpg != "")
				@unlink($sBaseDir.BRANDS_IMG_DIR."jpg/".$sLogoJpg);
			
			if ($sLogoSvg != "")	
				@unlink($sBaseDir.BRANDS_IMG_DIR."svg/".$sLogoSvg);
		}
	}

	else
		$_SESSION['Flag'] = "BRAND_EXISTS";


	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>