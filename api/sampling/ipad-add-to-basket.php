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


	$User   = IO::intValue('User');
	$Styles = IO::strValue("Styles");

	$aResponse = array( );


	if ($User == 0)
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid User ID";
	}

	else if ($Styles == "")
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid Request - Incomplete data";
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
			$iStyles = @explode(",", $Styles);
			$bFlag   = true;


			$objDb->execute("BEGIN");

			for ($i = 0; $i < count($iStyles); $i ++)
			{
				if (getDbValue("COUNT(*)", "tbl_basket", "user_id='$User' AND style_id='{$iStyles[$i]}'") == 1)
					continue;


				$iId = getNextId("tbl_basket");

				$sSQL = "INSERT INTO tbl_basket (id, user_id, style_id, date_time) VALUES ('$iId', '$User', '{$iStyles[$i]}', NOW( ))";
				$bFlag = $objDb->execute($sSQL);

				if ($bFlag == false)
					break;
			}

			if ($bFlag == true)
			{
				$objDb->execute("COMMIT");

				$aResponse['Status']  = "OK";
				$aResponse["Message"] = "Added to Basket Successfully!";
			}

			else
			{
				$objDb->execute("ROLLBACK");

				$aResponse['Status'] = "ERROR";
				$aResponse["Error"]  = "An ERROR occured, please try again.";
			}
		}
	}

	print @json_encode($aResponse);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>