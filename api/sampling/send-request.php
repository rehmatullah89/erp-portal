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


	$User    = IO::intValue('User');
	$Styles  = IO::strValue("Styles");
	$Subject = IO::strValue("Subject");
	$Message = IO::strValue("Message");
	$Date    = IO::strValue("Date");


	$aResponse = array( );


	if ($User == 0)
	{
		$aResponse['Status'] = "ERROR";
		$aResponse["Error"]  = "Invalid User ID";
	}

	else if ($User == 0 || $Styles == "" || $Subject == "" || $Message == "")
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
			$objDb->execute("BEGIN");

			$iId = getNextId("tbl_requests");


			$sSQL  = "INSERT INTO tbl_requests (id, user_id, styles, subject, message, date, date_time)
					 					VALUES ('$iId', '$User', '$Styles', '$Subject', '$Message', '$Date', NOW( ))";
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == true)
			{
				$sSQL  = "DELETE FROM tbl_basket WHERE user_id='$User' AND FIND_IN_SET(style_id, '$Styles')";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$objDb->execute("COMMIT");


				$sStyles = "";

				$sSQL = "SELECT style FROM tbl_styles WHERE FIND_IN_SET(id, '$Styles') ORDER BY id DESC";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
				{
					if ($i > 0)
						$sStyles .= ", ";

					$sStyles .= $objDb->getField($i, 0);
				}



				$sSQL = "SELECT name, email FROM tbl_users WHERE designation_id='$User'";
				$objDb->query($sSQL);

				$sSenderName  = $objDb->getField(0, 0);
				$sSenderEmail = $objDb->getField(0, 1);



				$sBody = @file_get_contents("emails/request.txt");

				$sBody = @str_replace("[Name]", $sSenderName, $sBody);
				$sBody = @str_replace("[Email]", $sSenderEmail, $sBody);
				$sBody = @str_replace("[Styles]", $sStyles, $sBody);
				$sBody = @str_replace("[Subject]", $Subject, $sBody);
				$sBody = @str_replace("[Message]", nl2br($Message), $sBody);
				$sBody = @str_replace("[Date]", formatDate($Date), $sBody);
				$sBody = @str_replace("[SiteTitle]", SITE_TITLE, $sBody);
				$sBody = @str_replace("[SiteUrl]", SITE_URL, $sBody);


				$sSQL = "SELECT name, email FROM tbl_users WHERE designation_id='170'";
				$objDb->query($sSQL);

				$sRecipientName  = $objDb->getField(0, 0);
				$sRecipientEmail = $objDb->getField(0, 1);

				$sRecipientName  = "MT Shahzad";
				$sRecipientEmail = "tahir.shahzad@apparelco.com";


				$objEmail = new PHPMailer( );

				$objEmail->Subject = $Subject;
				$objEmail->MsgHTML($sBody);
				$objEmail->AddAddress($sRecipientEmail, $sRecipientName);
				$objEmail->AddAddress($sSenderEmail, $sSenderName);
				$objEmail->Send( );


				$aResponse['Status']  = "OK";
				$aResponse["Message"] = "Your Request has been Sent Successfully!";
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