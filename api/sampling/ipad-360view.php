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

	$Style = IO::strValue("Style");

	$aResponse = array( );


	$sSQL = "SELECT * FROM tbl_styles WHERE id='$Style'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid Style ID";
	}

	else
	{
		$sSQL = "SELECT id,
						(SELECT created FROM tbl_comment_sheets WHERE merchandising_id=tbl_merchandisings.id) AS _Created
				 FROM tbl_merchandisings
				 WHERE style_id='$Style'
				 ORDER BY id DESC";
		$objDb->query($sSQL);

		$iCount   = $objDb->getCount( );
		$s360Pics = array( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iMerchandisingId = $objDb->getField($i, 'id');
			$sDateTime        = $objDb->getField($i, '_Created');


			@list($sYear, $sMonth, $sDay) = @explode("-", substr($sDateTime, 0, 10));

			$sCode = ("M".str_pad($iMerchandisingId, 6, '0', STR_PAD_LEFT));

			$sPictures = @glob($sBaseDir.SAMPLING_360_DIR."{$sYear}/{$sMonth}/{$sDay}/thumbs/{$sCode}_*.*");

			if (count($sPictures) > 0)
			{
				for ($j = 0; $j < count($sPictures); $j ++)
					$s360Pics[] = (SITE_URL.SAMPLING_360_DIR.$sYear."/".$sMonth."/".$sDay."/originals/".@basename($sPictures[$j]));

				break;
			}
		}
	}


	$aResponse = array( );

	$aResponse['Status']  = "OK";
	$aResponse['360View'] = $s360Pics;


	print @json_encode($aResponse);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>