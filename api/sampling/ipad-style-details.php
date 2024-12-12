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
		$iBrand      = $objDb->getField(0, 'sub_brand_id');
		$iSeason     = $objDb->getField(0, 'sub_season_id');
		$sStyle      = $objDb->getField(0, 'style');
		$sSpecsFile  = $objDb->getField(0, 'specs_file');
		$sSketchFile = $objDb->getField(0, 'sketch_file');
		$sSizes      = $objDb->getField(0, 'sizes');
		$sDateTime   = $objDb->getField(0, 'created');

		if ($sSketchFile == "" || !@file_exists($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile))
			$sSketchFile = "";


		$sMsSizes = array( );
		$iSizes   = @explode(",", $sSizes);

		$sSQL = "SELECT id, size FROM tbl_sampling_sizes WHERE id IN ($sSizes) ORDER BY display_order";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sMsSizes[$objDb->getField($i, 0)] = $objDb->getField($i, 1);


		$sSpecsHtml    = "";
		$sCommentsHtml = "";

		$sSpecsHtml .= '<table border="1" bordercolor="#ffffff" cellpadding="3" cellspacing="0" width="100%">';
		$sSpecsHtml .= '  <tr bgcolor="#dddddd">';
		$sSpecsHtml .= '  <td width="240">&nbsp;<b>Measurement Point</b></td>';

		foreach ($sMsSizes as $iSize => $sSize)
		{
			$sSpecsHtml .= '<td width="60" align="center"><b>'.$sSize.'</b></td>';
		}

		$sSpecsHtml .= '</tr>';



		$sSpecs = array( );

		$sSQL = "SELECT point_id, size_id, specs FROM tbl_style_specs WHERE style_id='$Style' AND version='0' ORDER BY id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for($i = 0; $i < $iCount; $i ++)
			$sSpecs[$objDb->getField($i, 'point_id')][$objDb->getField($i, 'size_id')] = $objDb->getField($i, 'specs');


		$sSQL = "SELECT DISTINCT(ss.point_id), CONCAT(mp.point_id, ' - ', mp.point) AS _Point
				 FROM tbl_style_specs ss, tbl_measurement_points mp
				 WHERE ss.point_id=mp.id AND ss.style_id='$Style' AND ss.version='0'
				 ORDER BY ss.id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for($i = 0; $i < $iCount; $i ++)
		{
			$iPoint = $objDb->getField($i, 'point_id');
			$sPoint = $objDb->getField($i, '_Point');

			$sSpecsHtml .= '<tr bgcolor="#'.((($i % 2) == 0) ? 'eeeeee' : 'f6f6f6').'">';
			$sSpecsHtml .= '  <td align="left">&nbsp;'.$sPoint.'</td>';

			foreach ($sMsSizes as $iSize => $sSize)
			{
				$sSpecsHtml .= '<td align="center">'.$sSpecs[$iPoint][$iSize].'</td>';
			}

			$sSpecsHtml .= '</tr>';
		}

		$sSpecsHtml .= '</table>';



	    $sCommentsHtml .= '<table border="0" cellpadding="8" cellspacing="0" width="100%">';

		$sSQL = "SELECT comments, `from`, `date` FROM tbl_style_comments WHERE style_id='$Style' AND stage='Tech Pack' ORDER BY `date`";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sFrom     = $objDb->getField($i, "from");
			$sDate     = $objDb->getField($i, "date");
			$sComments = $objDb->getField($i, "comments");

	    	$sCommentsHtml .= '<tr valign="top">';
	    	$sCommentsHtml .= '    <td width="25%" bgcolor="#'.(($i % 2) == 1 ? 'eeeeee' : 'aaaaaa').'"><b>'.$sFrom.'</b><br /><small>'.formatDate($sDate).'</small></td>';
	    	$sCommentsHtml .= '    <td width="75%" bgcolor="#'.(($i % 2) == 1 ? 'ffffff' : 'eeeeee').'">'.nl2br($sComments).'</td>';
	    	$sCommentsHtml .= '  </tr>';
		}

	    $sCommentsHtml .= '</table>';


		$aResponse['Status']           = "OK";
		$aResponse['Brand']            = getDbValue("brand", "tbl_brands", "id='$iBrand'");
		$aResponse['Season']           = getDbValue("season", "tbl_seasons", "id='$iSeason'");
		$aResponse['Style']            = $sStyle;
		$aResponse['ExFactoryDate']    = formatDate($sDateTime, "M d, Y");
		$aResponse['ProductionStatus'] = "On-Time";
		$aResponse['SamplesRequested'] = @implode(", ", $sMsSizes);
		$aResponse['SkecthFile']       = (($sSketchFile == "") ? "" : (SITE_URL.STYLES_SKETCH_DIR.$sSketchFile));
		$aResponse['SpecsFile']        = (($sSpecsFile == "") ? "" : (SITE_URL.STYLES_SPECS_DIR.$sSpecsFile));
		$aResponse['Specs']            = @utf8_encode($sSpecsHtml);
		$aResponse['Comments']         = @utf8_encode($sCommentsHtml);
	}

	print @json_encode($aResponse);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>