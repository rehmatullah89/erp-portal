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

	//if ($sUserRights['Delete'] != "Y")
	//	redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id = IO::intValue('Id');

	
	$sSQL = "SELECT logo, logo_png, logo_jpg, logo_svg FROM tbl_clients WHERE id='$Id'";
	$objDb->query($sSQL);

	$sLogo    = $objDb->getField(0, "logo");
	$sLogoPng = $objDb->getField(0, "logo_png");
	$sLogoJpg = $objDb->getField(0, "logo_jpg");
	$sLogoSvg = $objDb->getField(0, "logo_svg");

	
	$sSQL = "DELETE FROM tbl_clients WHERE id='$Id'";

	if ($objDb->execute($sSQL) == true)
	{
		if ($sLogo != "")
			@unlink($sBaseDir.CLIENTS_IMG_DIR."source/".$sLogo);
		
		if ($sLogoPng != "")
			@unlink($sBaseDir.CLIENTS_IMG_DIR."png/".$sLogoPng);
		
		if ($sLogoJpg != "")
			@unlink($sBaseDir.CLIENTS_IMG_DIR."jpg/".$sLogoJpg);
		
		if ($sLogoSvg != "")
			@unlink($sBaseDir.CLIENTS_IMG_DIR."svg/".$sLogoSvg);
		
		
		$_SESSION['Flag'] = "CLIENT_DELETED";
	}

	else
		$_SESSION['Flag'] = "DB_ERROR";

	header("Location: {$_SERVER['HTTP_REFERER']}");


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>