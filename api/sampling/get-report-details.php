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
	$objDb3      = new Database( );

	$User         = IO::strValue('User');
	$RequestCode  = IO::strValue('RequestCode');
	$iRequestCode = intval(substr($RequestCode, 1));

	$aResponse = array( );


	if ($User == 0)
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid User ID";
	}

	else if ($iRequestCode == 0 || strlen($RequestCode) == 0 || $RequestCode{0} != "M")
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid Request Code";
	}

	else
	{
		$sSQL = "SELECT style_id, sample_sizes, sample_quantities FROM tbl_merchandisings WHERE id='$iRequestCode'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "No Report Request Found!";
		}

		else
		{
			$iStyleId          = $objDb->getField(0, "style_id");
			$sSampleSizes      = $objDb->getField(0, 'sample_sizes');
			$sSampleQuantities = $objDb->getField(0, 'sample_quantities');


			$iQuantities = @explode(",", $sSampleQuantities);
			$iSizes      = @explode(",", $sSampleSizes);
			$sSizes      = array( );


			$sSQL = "SELECT size FROM tbl_sampling_sizes WHERE id IN ($sSampleSizes) ORDER BY FIELD(id, $sSampleSizes)";

			if ($objDb->query($sSQL) == true)
			{
				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
					$sSizes[] = $objDb->getField($i, 0);
			}

			$iSizesCount      = count($sSizes);
			$iQuantitiesCount = count($iQuantities);
			$sSpecs           = array( );



			$sSQL = "SELECT point_id, size_id, specs FROM tbl_style_specs WHERE style_id='$iStyleId' AND FIND_IN_SET(size_id, '$sSampleSizes') AND version='0' ORDER BY id";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );
			$bFlag  = true;

			if ($iCount > 0)
			{
				for($i = 0; $i < $iCount; $i ++)
					$sSpecs[$objDb->getField($i, 'point_id')][$objDb->getField($i, 'size_id')] = $objDb->getField($i, 'specs');



				$objDb->execute("BEGIN");

/*
				$sSQL  = "DELETE FROM tbl_comment_sheets WHERE merchandising_id='$iRequestCode'";
				$bFlag = $objDb->execute($sSQL);

				if ($bFlag == true)
				{
					$sSQL  = "DELETE FROM tbl_measurement_specs WHERE merchandising_id='$iRequestCode'";
					$bFlag = $objDb->execute($sSQL);
				}

				if ($bFlag == true)
				{
					$sSQL  = "DELETE FROM tbl_measurement_comments WHERE merchandising_id='$iRequestCode'";
					$bFlag = $objDb->execute($sSQL);
				}

				if ($bFlag == true)
				{
					$sSQL  = "DELETE FROM tbl_ms_sampling WHERE merchandising_id='$iRequestCode'";
					$bFlag = $objDb->execute($sSQL);
				}
*/
				if ($bFlag == true && getDbValue("COUNT(*)", "tbl_comment_sheets", "merchandising_id='$iRequestCode'") == 0)
				{
					$sSQL  = "INSERT INTO tbl_comment_sheets (merchandising_id, report_id, status, created, created_by, modified, modified_by) VALUES ('$iRequestCode', '0', '', NOW( ), '$User', NOW( ), '$User')";
					$bFlag = $objDb->execute($sSQL);
				}

				if ($bFlag == true)
				{
					$sSQL  = "UPDATE tbl_merchandisings SET status='W' WHERE id='$iRequestCode' AND (status='' OR ISNULL(status))";
					$bFlag = $objDb->execute($sSQL);
				}

				if ($bFlag == true && getDbValue("COUNT(*)", "tbl_measurement_specs", "merchandising_id='$iRequestCode'") == 0)
				{
					$sSQL = "SELECT DISTINCT(point_id) AS _Point,
									(SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance
							 FROM tbl_style_specs
							 WHERE style_id='$iStyleId' AND version='0'
							 ORDER BY id";
					$objDb->query($sSQL);

					$iCount = $objDb->getCount( );

					for($i = 0; $i < $iCount; $i ++)
					{
						$iPoint     = $objDb->getField($i, '_Point');
						$sTolerance = $objDb->getField($i, '_Tolerance');


						$sData = "";

						for ($j = 0; $j < count($iSizes); $j ++)
						{
							$sData .= "{$sSpecs[$iPoint][$iSizes[$j]]},";


							for ($k = 0; $k < $iQuantities[$j]; $k ++)
								$sData .= ",";
						}


						$iMsId = getNextId("tbl_measurement_specs");

						$sSQL  = "INSERT INTO tbl_measurement_specs (id, merchandising_id, point_id, tolerance, data) VALUES ('$iMsId', '$iRequestCode', '$iPoint', '$sTolerance', '$sData')";
						$bFlag = $objDb2->execute($sSQL);

						if ($bFlag == false)
							break;
					}

					if ($bFlag == true)
						$objDb->execute("COMMIT");

					else
						$objDb->execute("ROLLBACK");
				}
			}



			$sSQL = "SELECT category_id FROM tbl_measurement_points WHERE id IN (SELECT point_id FROM tbl_measurement_specs WHERE merchandising_id='$iRequestCode') LIMIT 1";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 1)
			{
				$iCategory = $objDb->getField(0, 0);

				$sCategory = getDbValue("category", "tbl_sampling_categories", "id='$iCategory'");
			}

			else
				$sCategory = "-";


			$iReportId    = (int)getDbValue("report_id", "tbl_comment_sheets", "merchandising_id='$iRequestCode'");
			$sReportsList = getList("tbl_sampling_reports", "id", "report");
			$sReports     = array( );

			foreach ($sReportsList as $sKey => $sValue)
				$sReports[] = "{$sKey}||{$sValue}";




			$sSQL = "SELECT ms.id, ms.point_id, ms.tolerance, ms.data, mp.tolerance AS _MpTolerance, mp.point AS _Point
					 FROM tbl_measurement_specs ms, tbl_measurement_points mp
					 WHERE ms.point_id=mp.id AND ms.merchandising_id='$iRequestCode'
					 ORDER BY ms.id";
			$objDb->query($sSQL);

			$iCount      = $objDb->getCount( );
			$sDetails    = "";
			$sSizeLabels = array( );

			for($i = 0; $i < $iCount; $i ++)
			{
				$iId        = $objDb->getField($i, 'id');
				$iPointId   = $objDb->getField($i, 'point_id');
				$sPoint     = $objDb->getField($i, '_Point');
				$sTolerance = $objDb->getField($i, 'tolerance');
				$sData      = $objDb->getField($i, 'data');

				if ($sTolerance == "")
					$sTolerance = $objDb->getField($i, '_MpTolerance');

				$sData = @explode(",", $sData);



				$sDetails .= $iPointId;
				$sDetails .= "||";
				$sDetails .= $sPoint;
				$sDetails .= "||";
				$sDetails .= "{$sTolerance} ";

				for ($j = 0; $j < count($sData); $j ++)
					$sDetails .= "||{$sData[$j]} ";

				if ($i < ($iCount - 1))
					$sDetails .= "|--|";
			}


			for ($i = 0; $i < count($iSizes); $i ++)
			{
				for ($j = 0, $k = 1; $j < $iQuantities[$i]; $j ++, $k ++)
					$sSizeLabels[] = ($sSizes[$i].(($iQuantities[$i] > 1) ? " ({$k})" : ""));
			}



			$sRequestDate = getDbValue("created", "tbl_merchandisings", "id='$iRequestCode'");
			$sBack        = "N";
			$sFront       = "N";

			@list($sYear, $sMonth, $sDay) = @explode("-", substr($sRequestDate, 0, 10));

			$sDir = (SAMPLING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");

			if (@file_exists($sBaseDir.$sDir.$RequestCode."_front.jpg"))
			{
				$Front  = ($RequestCode."_front.jpg");
				$sFront = "Y";
			}

			if (@file_exists($sBaseDir.$sDir.$RequestCode."_back.jpg"))
			{
				$Back  = ($RequestCode."_back.jpg");
				$sBack = "Y";
			}



			$aResponse['Status']     = "OK";
			$aResponse['Category']   = $sCategory;
			$aResponse['Reports']    = @implode("|-|", $sReports);
			$aResponse['ReportId']   = $iReportId;
			$aResponse['SizeLabels'] = @implode("|-|", $sSizeLabels);
			$aResponse['Points']     = $sDetails;
			$aResponse['Front']      = $sFront;
			$aResponse['Back']       = $sBack;
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
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>