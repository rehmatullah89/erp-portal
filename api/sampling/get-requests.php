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

	$User     = IO::intValue('User');
	$FromDate = IO::strValue('FromDate');
	$ToDate   = IO::strValue('ToDate');
	$Brand    = IO::intValue('Brand');
	$Category = IO::intValue('Category');
	$Season   = IO::intValue('Season');
	$StyleNo  = IO::strValue('Style');

	$aResponse = array( );


	if ($User == 0)
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid User ID";
	}

	else
	{
		$sStatus = getDbValue("status", "tbl_users", "id='$User'");

		if ($sStatus != "A")
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "User Account is Disabled";
		}

		else
		{
			$sRequests = array( );

			$sBrands      = getDbValue("brands", "tbl_users", "id='$User'");
			$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ($sBrands)");
			$sSeasonsList = getList("tbl_seasons", "id", "season", "parent_id>'0'");
			$sSizesList   = getList("tbl_sampling_sizes", "id", "size");
			$sTypesList   = getList("tbl_sampling_types", "id", "type");
			$sWashesList  = getList("tbl_sampling_washes", "id", "wash");
			$sColorsList  = getList("tbl_sampling_types", "id", "color");


			$sSQL = "SELECT m.id, m.sent_2_sampling, m.sample_sizes, m.sample_quantities, m.sample_type_id, m.wash_id,
							s.style, s.sub_brand_id, s.sub_season_id
					 FROM tbl_merchandisings m, tbl_styles s
					 WHERE m.style_id=s.id AND m.status='W' AND s.sub_brand_id IN ($sBrands)
						   AND DATE_FORMAT(m.created, '%Y-%m-%d') >= '2010-11-01'
						   AND m.id NOT IN (SELECT merchandising_id FROM tbl_comment_sheets WHERE DATE_FORMAT(created, '%Y-%m-%d') >= '2010-11-01')
						   AND m.created_by IN (SELECT id FROM tbl_users WHERE country_id IN (SELECT country_id FROM tbl_users WHERE id='$User'))
					 ORDER BY m.id DESC";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iId         = $objDb->getField($i, 'id');
				$sStyle      = $objDb->getField($i, 'style');
				$iBrand      = $objDb->getField($i, 'sub_brand_id');
				$iSeason     = $objDb->getField($i, 'sub_season_id');
				$iType       = $objDb->getField($i, "sample_type_id");
				$iWash       = $objDb->getField($i, "wash_id");
				$sSentDate   = $objDb->getField($i, "sent_2_sampling");
				$sQuantities = $objDb->getField($i, 'sample_quantities');


				$iQuantities  = @explode(",", $sQuantities);
				$iSizes       = @explode(",", $objDb->getField($i, 'sample_sizes'));
				$sRequestCode = ("M".str_pad($iId, 6, '0', STR_PAD_LEFT));
				$sSizeLabels  = "";

				for ($j = 0; $j < count($iSizes); $j ++)
					$sSizeLabels .= (", ".$sSizesList[$iSizes[$j]]);

				$sSizeLabels = substr($sSizeLabels, 2);
				$iQuantity   = @array_sum($iQuantities);
				$sDate       = formatDate($sSentDate);


				$sRequests[] = "{$sRequestCode}||{$sStyle}||{$sBrandsList[$iBrand]}||{$sSeasonsList[$iSeason]}||{$sTypesList[$iType]}||{$sWashesList[$iWash]}||{$sSizeLabels}||{$sQuantities}||{$iQuantity}||{$sDate}||N/A||N/A||0";
			}



			$sConditions = " WHERE m.style_id=s.id AND m.id=c.merchandising_id AND c.created_by='$User' ";

			if ($FromDate != "" && $ToDate != "")
				$sConditions .= " AND (DATE_FORMAT(c.created, '%Y-%m-%d') BETWEEN '$FromDate' AND '$ToDate') ";

			if ($Brand > 0)
				$sConditions .= " AND s.sub_brand_id='$Brand' ";

			else
				$sConditions .= " AND s.sub_brand_id IN ($sBrands) ";

			if ($Season > 0)
				$sConditions .= " AND s.sub_season_id='$Season' ";

			if ($StyleNo != "")
				$sConditions .= " AND s.style LIKE '$StyleNo' ";

			if ($Category > 0)
				$sConditions .= " AND s.category_id='$Category' ";


			$sSQL = "SELECT m.id, m.sent_2_sampling, m.sample_sizes, m.sample_quantities, m.sample_type_id, m.wash_id, m.status,
							s.style, s.sub_brand_id, s.sub_season_id,
							c.created_by
					 FROM tbl_comment_sheets c, tbl_merchandisings m, tbl_styles s
					 $sConditions
					 ORDER BY m.id DESC";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iId         = $objDb->getField($i, 'id');
				$sStyle      = $objDb->getField($i, 'style');
				$iBrand      = $objDb->getField($i, 'sub_brand_id');
				$iSeason     = $objDb->getField($i, 'sub_season_id');
				$iType       = $objDb->getField($i, "sample_type_id");
				$iWash       = $objDb->getField($i, "wash_id");
				$sSentDate   = $objDb->getField($i, "sent_2_sampling");
				$sQuantities = $objDb->getField($i, 'sample_quantities');
				$sStatus     = $objDb->getField($i, 'status');
				$iCreatedBy  = $objDb->getField($i, 'created_by');


				$iQuantities  = @explode(",", $sQuantities);
				$iSizes       = @explode(",", $objDb->getField($i, 'sample_sizes'));
				$sRequestCode = ("M".str_pad($iId, 6, '0', STR_PAD_LEFT));
				$sSizeLabels  = "";

				for ($j = 0; $j < count($iSizes); $j ++)
					$sSizeLabels .= (", ".$sSizesList[$iSizes[$j]]);

				$sSizeLabels = substr($sSizeLabels, 2);
				$iQuantity   = @array_sum($iQuantities);
				$sDate       = formatDate($sSentDate);


				$sRequests[] = "{$sRequestCode}||{$sStyle}||{$sBrandsList[$iBrand]}||{$sSeasonsList[$iSeason]}||{$sTypesList[$iType]}||{$sWashesList[$iWash]}||{$sSizeLabels}||{$sQuantities}||{$iQuantity}||{$sDate}||{$sColorsList[$iType]}||{$sStatus}||{$iCreatedBy}";
			}


			$aResponse['Status']   = "OK";
			$aResponse['Requests'] = ((count($sRequests) > 0) ? @implode("|-|", $sRequests) : "");
		}
	}

	print @json_encode($aResponse);


/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = @json_encode($aResponse);

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>