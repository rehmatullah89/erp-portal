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
        
        $sImageSql  = "";
        $sIconImage = $_FILES["IconImage"]['name'];


	$sSQL  = ("SELECT * FROM tbl_statement_sections WHERE `section` LIKE '".IO::strValue("Section")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_statement_sections");
                
                if(!empty($sIconImage))
                {
                    $exts       = explode('.', $sIconImage);
                    $extension  = end($exts);
                    
                    $sPicture = ("SECTION_".$iId."_".rand(1, 100).'.'.$extension);

                    if (@move_uploaded_file($_FILES["IconImage"]['tmp_name'], ($sBaseDir.AUDITOR_APP_ICONS_DIR.$sPicture)))
                    {
                            $sImageSql = $sPicture;
                    }
                }

		$sSQL = ("INSERT INTO tbl_statement_sections (id, section, image, position) VALUES ('$iId', '".IO::strValue("Section")."', '$sImageSql', '$iId')");

		if ($objDb->execute($sSQL) == true)
			redirect($_SERVER['HTTP_REFERER'], "SECTION_ADDED");

		else
			$_SESSION['Flag'] = "DB_ERROR";
	}
        
	else
		$_SESSION['Flag'] = "SECTION_EXISTS";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>