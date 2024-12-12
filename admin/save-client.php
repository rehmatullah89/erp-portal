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
	**  Software Engineer:                                                                       **
	**                                                                                           **
	**      Name  :  Rehmat Ullah                                                                **
	**      Email :  rehmatullah@3-tree.com                                                      **
	**      Phone :  +92 344 404 3675                                                            **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
	**                                                                                           **
	**  ***************************************************************************************  **
	**                                                                                           **
	**                                                                                           **
	**                                                                                           **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/session.php");

	//if ($sUserRights['Add'] != "Y")
	//	redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$sSQL  = ("SELECT * FROM tbl_clients WHERE title LIKE '".IO::strValue("Client")."' AND code = '".IO::strValue("Code")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId      = getNextId("tbl_clients");
		$sLogo    = "";
		$sLogoPng = "";
		$sLogoJpg = "";
		$sLogoSvg = "";		

		
		if ($_FILES['Logo']['name'] != "")
		{
			$sLogo = ($iId."-".IO::getFileName($_FILES['Logo']['name']));

			if (!@move_uploaded_file($_FILES['Logo']['tmp_name'], ($sBaseDir.CLIENTS_IMG_DIR.'source/'.$sLogo)))
					$sLogo = "";
		}
		
		if ($_FILES['LogoPng']['name'] != "")
		{
			$sLogoPng = ($iId."-".IO::getFileName($_FILES['LogoPng']['name']));

			if (!@move_uploaded_file($_FILES['LogoPng']['tmp_name'], ($sBaseDir.CLIENTS_IMG_DIR.'png/'.$sLogoPng)))
					$sLogoPng = "";
		}
		
		if ($_FILES['LogoJpg']['name'] != "")
		{
			$sLogoJpg = ($iId."-".IO::getFileName($_FILES['LogoJpg']['name']));

			if (!@move_uploaded_file($_FILES['LogoJpg']['tmp_name'], ($sBaseDir.CLIENTS_IMG_DIR.'jpg/'.$sLogoJpg)))
					$sLogoJpg = "";
		}
		
		if ($_FILES['LogoSvg']['name'] != "")
		{
			$sLogoSvg = ($iId."-".IO::getFileName($_FILES['LogoSvg']['name']));

			if (!@move_uploaded_file($_FILES['LogoSvg']['tmp_name'], ($sBaseDir.CLIENTS_IMG_DIR.'svg/'.$sLogoSvg)))
					$sLogoSvg = "";
		}

		
		$sSQL = ("INSERT INTO tbl_clients (id, code, user_types, title, position, logo, logo_png, logo_jpg, logo_svg) 
		                           VALUES ('$iId', '".IO::strValue("Code")."', '". implode(",", IO::getArray("UserType"))."', '".IO::strValue("Client")."', '$iId', '$sLogo', '$sLogoPng', '$sLogoJpg', '$sLogoSvg')");

		if ($objDb->execute($sSQL) == true)
			redirect($_SERVER['HTTP_REFERER'], "CLIENT_ADDED");

		else
		{
			$_SESSION['Flag'] = "DB_ERROR";
			
			
			if ($sLogo != "")
				@unlink($sBaseDir.CLIENTS_IMG_DIR."source/".$sLogo);
			
			if ($sLogoPng != "")
				@unlink($sBaseDir.CLIENTS_IMG_DIR."png/".$sLogoPng);
			
			if ($sLogoJpg != "")
				@unlink($sBaseDir.CLIENTS_IMG_DIR."jpg/".$sLogoJpg);
			
			if ($sLogoSvg != "")	
				@unlink($sBaseDir.CLIENTS_IMG_DIR."svg/".$sLogoSvg);			
		}
	}

	else
		$_SESSION['Flag'] = "CLIENT_EXISTS";


	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>