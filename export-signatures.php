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

	@require_once("requires/session.php");
	@require_once("requires/Rtf/Rtf.php");

	$sFile = IO::strValue("File");
	$sCode = stripslashes(IO::strValue("Code"));

	if (substr($sFile, -5) == ".html")
	{
		$iSize = @strlen($sCode);

		if(ini_get('zlib.output_compression'))
			@ini_set('zlib.output_compression', 'Off');

		header('Content-Description: File Transfer');
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private", false);
		header('Content-Type: application/force-download');
		header("Content-Type: application/download");
		header("Content-Type: text/html");
		header("Content-Disposition: attachment; filename=\"".basename($sFile)."\";");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: $iSize");

		print $sCode;
	}

	else
	{
		$objRtf = new Rtf( );

		$objSection = &$objRtf->addSection( );

		$objSection->writeText($sCode, new Font(12), new ParFormat('left'));

		$objRtf->sendRtf($sFile);
	}

	@ob_end_flush( );
?>