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

	$User         = IO::strValue('User');
	$RequestCode  = IO::strValue('RequestCode');
	$ReportId     = IO::intValue("ReportId");
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

	else if ($ReportId == 0)
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid Report Type";
	}

	else
	{
		$sUser = getDbValue("name", "tbl_users", "id='$User'");


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


			$iQuantities      = @explode(",", $sSampleQuantities);
			$iSizes           = @explode(",", $sSampleSizes);
			$iSizesCount      = count($iSizes);
			$iQuantitiesCount = count($iQuantities);
			$iTotalQuantity   = @array_sum($iQuantities);
			$sFindings        = array( );
			$sSpecs           = array( );


			$sSQL = "SELECT point_id, size_id, specs FROM tbl_style_specs WHERE style_id='$iStyleId' AND FIND_IN_SET(size_id, '$sSampleSizes') AND version='0' ORDER BY id";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for($i = 0; $i < $iCount; $i ++)
				$sSpecs[$objDb->getField($i, 'point_id')][$objDb->getField($i, 'size_id')] = $objDb->getField($i, 'specs');


			for ($i = 0; $i < $iTotalQuantity; $i ++)
				$sFindings[$i] = @explode(",", IO::strValue("Size{$i}"));



			$objDb->execute("BEGIN");


			$sSQL = "SELECT ms.id, ms.data
					 FROM tbl_measurement_specs ms, tbl_measurement_points mp
					 WHERE ms.point_id=mp.id AND ms.merchandising_id='$iRequestCode'
					 ORDER BY ms.id";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for($i = 0; $i < $iCount; $i ++)
			{
				$iId   = $objDb->getField($i, 'id');
				$sData = $objDb->getField($i, 'data');

				$sData = @explode(",", $sData);


				for ($j = 0, $iPosition = 0, $iIndex = 0; $j < $iSizesCount; $j ++)
				{
					$sData[$iPosition] = trim($sData[$iPosition]);

					$iPosition ++;

					for ($k = 0; $k < $iQuantities[$j]; $k ++, $iPosition ++)
					{
						$sData[$iPosition] = trim($sFindings[$iIndex][$i]);

						$iIndex ++;
					}
				}

				$sData = @implode(",", $sData);



				$sSQL = "UPDATE tbl_measurement_specs SET data='$sData' WHERE id='$iId' AND merchandising_id='$iRequestCode'";
				$bFlag = $objDb2->execute($sSQL, true, $User, $sUser);

				if ($bFlag == false)
					break;
			}

			if ($bFlag == true)
			{
				$sSQL  = "UPDATE tbl_comment_sheets SET report_id='$ReportId' WHERE merchandising_id='$iRequestCode'";
				$bFlag = $objDb->execute($sSQL, true, $User, $sUser);
			}

			if ($bFlag == true)
			{
				$objDb->execute("COMMIT");

				$aResponse['Status']  = "OK";
				$aResponse["Message"] = "Report Saved Successfully!";
			}

			else
			{
				$objDb->execute("ROLLBACK");

				$aResponse['Status'] = "ERROR";
				$aResponse["Error"]  = "Unable to Save the Report";
			}

		}
	}


	print @json_encode($aResponse);

/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = $sBody;

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>