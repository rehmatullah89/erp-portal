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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Recipient = IO::intValue("Recipient");
	$Message   = IO::strValue("Message");


	$sSQL = "SELECT status FROM tbl_user_stats WHERE user_id='$Recipient' ORDER BY id DESC LIMIT 1";
	$objDb->query($sSQL);

	$sStatus = $objDb->getField(0, 0);


	$iId = getNextId("tbl_chat");

	$sSQL = "INSERT INTO tbl_chat (id, recipient, sender, message, status, date_time) VALUES ('$iId', '$Recipient', '{$_SESSION['UserId']}', '$Message', '1', NOW( ))";

	if ($objDb->execute($sSQL) == true)
	{
		$sSQL = "SELECT message, date_time FROM tbl_chat WHERE id='$iId'";
		$objDb->query($sSQL);

		$sMessage  = $objDb->getField(0, "message");
		$sDateTime = $objDb->getField(0, "date_time");

		print ($Recipient.'|-|
				<div class="chatMe">
				  <table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
					  <td><b>'.$_SESSION['Name'].'</b></td>
					  <td width="55"><span>'.formatTime($sDateTime, "h:ia").'</span></td>
					</tr>

					<tr>
					  <td colspan="2">'.$sMessage.(($sStatus == "0") ? '<br /><span style="color:#ff0000;">-- This user is offline.</span>' : '').'</td>
					</tr>
				  </table>
				</div>');
	}

	else
		print "ERROR|-|$Recipient|-|A ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>