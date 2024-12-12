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

	$Style= IO::strValue("Style");


	$sSQL = "SELECT * FROM tbl_styles WHERE id='$Style'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$aResponse = array( );

		$aResponse['Status']  = "Error";
		$aResponse['Message'] = "No Style Found";

		print @json_encode($aResponse);
	}


	$iBrand    = $objDb->getField(0, 'sub_brand_id');
	$iSeason   = $objDb->getField(0, 'sub_season_id');
	$sStyle    = $objDb->getField(0, 'style');
	$sDateTime = $objDb->getField(0, 'created');


	$sSQL = "SELECT id, sample_type_id, status, created FROM tbl_merchandisings WHERE style_id='$Style' AND status!='W' ORDER BY id DESC";
	$objDb->query($sSQL);

	$iCount    = $objDb->getCount( );
	$sPictures = array( );
	$aData     = array( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iMerchandisingId = $objDb->getField($i, 'id');
		$iSampleType      = $objDb->getField($i, "sample_type_id");
		$sStatus          = $objDb->getField($i, 'status');
		$sDateTime        = $objDb->getField($i, 'created');


		@list($sYear, $sMonth, $sDay) = @explode("-", substr($sDateTime, 0, 10));

		$sCode = ("M".str_pad($iMerchandisingId, 6, '0', STR_PAD_LEFT));

		$sPictures = @glob($sBaseDir.SAMPLING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sCode."_*.*");
		$sTemp     = array( );

		foreach ($sPictures as $sPicture)
			$sTemp[] = str_replace("../../", SITE_URL, strtolower($sPicture));

		$sPictures = $sTemp;

		$aData[] = @implode("|-|", array($sCode, getDbValue("type", "tbl_sampling_types", "id='$iSampleType'"), formatDate($sDateTime), (($sStatus == "A") ? "Approved" : "Rejected"), @implode("||", $sPictures)));
	}


	$aResponse = array( );

	$aResponse['Status'] = "OK";
	$aResponse['Audits'] = @implode("|--|", $aData);


	print @json_encode($aResponse);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>