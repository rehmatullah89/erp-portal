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


	$User     = IO::intValue('User');
	$Style    = IO::strValue("Style");
	$Stage    = IO::strValue("Stage");
	$From     = IO::strValue("From");
	$Nature   = IO::strValue("Nature");
	$Comments = IO::strValue("Comments");


	$aResponse = array( );


	if ($User == 0)
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid User ID";
	}

	else if ($User == 0 || $Style == 0 || $Stage == "" || $From == "" || $Comments == "")
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
			$iId = getNextId("tbl_style_comments");


			$sSQL = "INSERT INTO tbl_style_comments (id, style_id, stage, `from`, `date`, nature, comments, user_id, date_time)
											 VALUES ('$iId', '$Style', '$Stage', '$From', CURDATE( ), '$Nature', '$Comments', '$User', NOW( ))";

			if ($objDb->execute($sSQL, true, $User, getDbValue("name", "tbl_users", "id='$User'")) == true)
			{
				$aResponse['Status']  = "OK";
				$aResponse["Message"] = "Comments Saved Successfully!";
			}

			else
			{
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