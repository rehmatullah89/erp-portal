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

	if ($sUserRights['Add'] != "Y" && $sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");


	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id      = IO::intValue('Id');
	$Referer = IO::strValue('Referer');


	$sSQL = "SELECT * FROM tbl_styles WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		redirect($Referer, "DB_ERROR");

	$sSketchFile = $objDb->getField(0, 'sketch_file');

	$iPosition  = @strrpos($sSketchFile, '.');
	$sExtension = @substr($sSketchFile, $iPosition);

	switch($sExtension)
	{
		case '.jpg'  : $objPicture = @imagecreatefromjpeg($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile);
					   break;

		case '.jpeg' : $objPicture = @imagecreatefromjpeg($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile);
					   break;

		case '.png'  : $objPicture = @imagecreatefrompng($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile);
					   break;

		case '.gif'  : $objPicture = @imagecreatefromgif($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile);
					   break;

		default      : $objPicture = @imagecreatefromgd2($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile);
					   break;
	}

	$ThumbWidth  = IO::intValue('ThumbWidth');
	$ThumbHeight = IO::intValue('ThumbHeight');
	$ThumbX1     = IO::intValue('ThumbX1');
	$ThumbY1     = IO::intValue('ThumbY1');
	$ImgWidth    = IO::intValue('ImgWidth');
	$ImgHeight   = IO::intValue('ImgHeight');

	$objTemp  = @imagecreatetruecolor($ThumbWidth, $ThumbHeight);
	$objThumb = @imagecreatetruecolor($ImgWidth, $ImgHeight);

	@imagecopy($objTemp, $objPicture, 0, 0, $ThumbX1, $ThumbY1, $ThumbWidth, $ThumbHeight);
	@imagecopyresampled($objThumb, $objTemp, 0, 0, 0, 0, $ImgWidth, $ImgHeight, $ThumbWidth, $ThumbHeight);
	@imagejpeg($objThumb, ($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile), 100);
	@imagedestroy($objThumb);


	@unlink(($sBaseDir.STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile));
	createImage(($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile), ($sBaseDir.STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile), 160, 160);


	redirect($Referer, "STYLE_SKETCH_SAVED");


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>