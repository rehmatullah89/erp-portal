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

	$RequestCode  = IO::strValue('RequestCode');
	$iRequestCode = intval(substr($RequestCode, 1));

	$aResponse = array( );


	if ($iRequestCode == 0 || strlen($RequestCode) == 0 || $RequestCode{0} != "M")
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid Request Code";
	}

	else
	{
		$sSQL = "SELECT * FROM tbl_merchandisings WHERE id='$iRequestCode'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
		{
			$aResponse['Status'] = "ERROR";
			$aResponse["Error"]  = "No Report Request Found!";
		}

		else
		{
			$sRequestDate = getDbValue("created", "tbl_merchandisings", "id='$iRequestCode'");


			$objDb->execute("BEGIN");

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

			if ($bFlag == true)
			{
				$sSQL  = "UPDATE tbl_merchandisings SET status='W' WHERE id='$iRequestCode'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				@list($sYear, $sMonth, $sDay) = @explode("-", substr($sRequestDate, 0, 10));

				$sDir = (SAMPLING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");

				@unlink($sBaseDir.$sDir.$RequestCode."_front.jpg");
				@unlink($sBaseDir.$sDir.$RequestCode."_back.jpg");


				$objDb->execute("COMMIT");

				$aResponse['Status'] = "OK";
			}

			else
			{
				$objDb->execute("ROLLBACK");

				$aResponse['Status'] = "ERROR";
			}
		}
	}


	print @json_encode($aResponse);


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>