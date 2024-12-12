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


	$sSQL  = ("SELECT * FROM tbl_clients WHERE title LIKE '".IO::strValue("Client")."' AND code = '".IO::strValue("Code")."' AND id!='$Id'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		if ($_FILES['Logo']['name'] != "")
		{
			$sLogo = ($Id."-".IO::getFileName($_FILES['Logo']['name']));

			if (@move_uploaded_file($_FILES['Logo']['tmp_name'], ($sBaseDir.CLIENTS_IMG_DIR."source/".$sLogo)))
				$sLogoSql = ", logo='$sLogo' ";
		}
		
		if ($_FILES['LogoPng']['name'] != "")
		{
			$sLogoPng = ($Id."-".IO::getFileName($_FILES['LogoPng']['name']));

			if (@move_uploaded_file($_FILES['LogoPng']['tmp_name'], ($sBaseDir.CLIENTS_IMG_DIR."png/".$sLogoPng)))
				$sLogoPngSql = ", logo_png='$sLogoPng' ";
		}
		
		if ($_FILES['LogoJpg']['name'] != "")
		{
			$sLogoJpg = ($Id."-".IO::getFileName($_FILES['LogoJpg']['name']));

			if (@move_uploaded_file($_FILES['LogoJpg']['tmp_name'], ($sBaseDir.CLIENTS_IMG_DIR."jpg/".$sLogoJpg)))
				$sLogoJpgSql = ", logo_jpg='$sLogoJpg' ";
		}
		
		if ($_FILES['LogoSvg']['name'] != "")
		{
			$sLogoSvg = ($Id."-".IO::getFileName($_FILES['LogoSvg']['name']));

			if (@move_uploaded_file($_FILES['LogoSvg']['tmp_name'], ($sBaseDir.CLIENTS_IMG_DIR."svg/".$sLogoSvg)))
				$sLogoSvgSql = ", logo_svg='$sLogoSvg' ";
		}


		
		$sSQL  = ("UPDATE tbl_clients SET title='".IO::strValue("Client")."', code='".IO::strValue("Code")."', user_types='". implode(",", IO::getArray("UserType"))."' $sLogoSql  $sLogoPngSql  $sLogoJpgSql $sLogoSvgSql WHERE id='$Id'");

		if ($objDb->execute($sSQL) == true)
		{
			if ($sLogo != "" && $OldLogo != "" && $sLogo != $OldLogo)
				@unlink($sBaseDir.CLIENTS_IMG_DIR."source/".$OldLogo);
			
			if ($sLogoPng != "" && $OldLogoPng != "" && $sLogoPng != $OldLogoPng)
				@unlink($sBaseDir.CLIENTS_IMG_DIR."png/".$OldLogoPng);
			
			if ($sLogoJpg != "" && $OldLogoJpg != "" && $sLogoJpg != $OldLogoJpg)
				@unlink($sBaseDir.CLIENTS_IMG_DIR."jpg/".$OldLogoJpg);
			
			if ($sLogoSvg != "" && $OldLogoSvg != "" && $sLogoSvg != $OldLogoSvg)
				@unlink($sBaseDir.CLIENTS_IMG_DIR."svg/".$OldLogoSvg);
			

			redirect("clients.php", "CLIENT_UPDATED");
		}

		else
		{
			if ($sLogo != "" && $sLogo != $OldLogo)
				@unlink($sBaseDir.CLIENTS_IMG_DIR."source/".$sLogo);
			
			if ($sLogoPng != "" && $sLogoPng != $OldLogoPng)
				@unlink($sBaseDir.CLIENTS_IMG_DIR."png/".$sLogoPng);
			
			if ($sLogoJpg != "" && $sLogoJpg != $OldLogoJpg)
				@unlink($sBaseDir.CLIENTS_IMG_DIR."jpg/".$sLogoJpg);
			
			if ($sLogoSvg != "" && $sLogoSvg != $OldLogoSvg)
				@unlink($sBaseDir.CLIENTS_IMG_DIR."svg/".$sLogoSvg);
			
			$_SESSION['Flag'] = "DB_ERROR";
		}
	}

	else
		$_SESSION['Flag'] = "CLIENT_EXISTS";



	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>