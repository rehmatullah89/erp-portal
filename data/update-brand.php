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

	if ($sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	$Id          = IO::intValue("Id");
	$OldLogo     = IO::strValue("OldLogo");
	$OldLogoPng  = IO::strValue("OldLogoPng");
	$OldLogoJpg  = IO::strValue("OldLogoJpg");
	$OldLogoSvg  = IO::strValue("OldLogoSvg");
	$sLogoSql    = "";
	$sLogoPngSql = "";
	$sLogoJpgSql = "";
	$sLogoSvgSql = "";


	$sSQL  = ("SELECT * FROM tbl_brands WHERE (brand LIKE '".IO::strValue("Brand")."' OR (code LIKE '".IO::strValue("Code")."' AND '".IO::strValue("Code")."'!='')) AND parent_id='".IO::intValue("Parent")."' AND id!='$Id'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$sStages = @implode(",", IO::getArray("Stages"));
                $sCategories = @implode(",", IO::getArray("Categories"));

		if ($sStages == "")
		{
			$sStagesList = getList("tbl_production_stages", "id", "title");

			foreach ($sStagesList as $sKey => $sValue)
				$sStages .= ((($sStages != "") ? "," : "").$sKey);
		}
		
		
		if ($_FILES['Logo']['name'] != "")
		{
			$sLogo = ($Id."-".IO::getFileName($_FILES['Logo']['name']));

			if (@move_uploaded_file($_FILES['Logo']['tmp_name'], ($sBaseDir.BRANDS_IMG_DIR."source/".$sLogo)))
				$sLogoSql = ", logo='$sLogo' ";
		}
		
		if ($_FILES['LogoPng']['name'] != "")
		{
			$sLogoPng = ($Id."-".IO::getFileName($_FILES['LogoPng']['name']));

			if (@move_uploaded_file($_FILES['LogoPng']['tmp_name'], ($sBaseDir.BRANDS_IMG_DIR."png/".$sLogoPng)))
				$sLogoPngSql = ", logo_png='$sLogoPng' ";
		}
		
		if ($_FILES['LogoJpg']['name'] != "")
		{
			$sLogoJpg = ($Id."-".IO::getFileName($_FILES['LogoJpg']['name']));

			if (@move_uploaded_file($_FILES['LogoJpg']['tmp_name'], ($sBaseDir.BRANDS_IMG_DIR."jpg/".$sLogoJpg)))
				$sLogoJpgSql = ", logo_jpg='$sLogoJpg' ";
		}
		
		if ($_FILES['LogoSvg']['name'] != "")
		{
			$sLogoSvg = ($Id."-".IO::getFileName($_FILES['LogoSvg']['name']));

			if (@move_uploaded_file($_FILES['LogoSvg']['tmp_name'], ($sBaseDir.BRANDS_IMG_DIR."svg/".$sLogoSvg)))
				$sLogoSvgSql = ", logo_svg='$sLogoSvg' ";
		}


		
		$bFlag = $objDb->execute("BEGIN");

		$sSQL  = ("UPDATE tbl_brands SET parent_id='".IO::intValue("Parent")."', brand='".IO::strValue("Brand")."', inspection_level='".IO::intValue("Level")."', code='".IO::strValue("Code")."', aql='".IO::floatValue("AQL")."', aql_minor='".IO::floatValue("AQLMinor")."', manager='".IO::intValue("Manager")."', merchandisers='".@implode(",", IO::getArray("Merchandisers"))."', vendors='".@implode(",", IO::getArray("Vendors"))."', stages='$sStages', categories='$sCategories', type='".IO::strValue("Type")."', qmip='".IO::strValue("Qmip")."', regular='".IO::strValue("Regular")."' $sLogoSql  $sLogoPngSql  $sLogoJpgSql $sLogoSvgSql WHERE id='$Id'");
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true && IO::intValue("Parent") > 0)
		{
			$sType = getDbValue("type", "tbl_brands", ("id='".IO::intValue("Parent")."'"));


			$sSQL  = "UPDATE tbl_brands SET type='$sType' WHERE id='$Id'";
			$bFlag = $objDb->execute($sSQL);
		}

		if ($bFlag == true && IO::intValue("Parent") == 0)
		{
			$sSQL  = ("UPDATE tbl_brands SET type='".IO::strValue("Type")."' WHERE parent_id='$Id'");
			$bFlag = $objDb->execute($sSQL);
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");
			
			if ($sLogo != "" && $OldLogo != "" && $sLogo != $OldLogo)
				@unlink($sBaseDir.BRANDS_IMG_DIR."source/".$OldLogo);
			
			if ($sLogoPng != "" && $OldLogoPng != "" && $sLogoPng != $OldLogoPng)
				@unlink($sBaseDir.BRANDS_IMG_DIR."png/".$OldLogoPng);
			
			if ($sLogoJpg != "" && $OldLogoJpg != "" && $sLogoJpg != $OldLogoJpg)
				@unlink($sBaseDir.BRANDS_IMG_DIR."jpg/".$OldLogoJpg);
			
			if ($sLogoSvg != "" && $OldLogoSvg != "" && $sLogoSvg != $OldLogoSvg)
				@unlink($sBaseDir.BRANDS_IMG_DIR."svg/".$OldLogoSvg);
			

			redirect("brands.php", "BRAND_UPDATED");
		}

		else
		{
			$objDb->execute("ROLLBACK");

			if ($sLogo != "" && $sLogo != $OldLogo)
				@unlink($sBaseDir.BRANDS_IMG_DIR."source/".$sLogo);
			
			if ($sLogoPng != "" && $sLogoPng != $OldLogoPng)
				@unlink($sBaseDir.BRANDS_IMG_DIR."png/".$sLogoPng);
			
			if ($sLogoJpg != "" && $sLogoJpg != $OldLogoJpg)
				@unlink($sBaseDir.BRANDS_IMG_DIR."jpg/".$sLogoJpg);
			
			if ($sLogoSvg != "" && $sLogoSvg != $OldLogoSvg)
				@unlink($sBaseDir.BRANDS_IMG_DIR."svg/".$sLogoSvg);
			
			$_SESSION['Flag'] = "DB_ERROR";
		}
	}

	else
		$_SESSION['Flag'] = "BRAND_EXISTS";



	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>