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
	$objDb2      = new Database( );


	$sSQL = "SELECT id, sender, message, date_time, (SELECT name FROM tbl_users WHERE id=tbl_chat.sender) AS _Sender FROM tbl_chat WHERE recipient='{$_SESSION['UserId']}' AND status='1' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId       = $objDb->getField($i, "id");
		$iSender   = $objDb->getField($i, "sender");
		$sSender   = $objDb->getField($i, "_Sender");
		$sMessage  = $objDb->getField($i, "message");
		$sDateTime = $objDb->getField($i, "date_time");


		print ($iSender.'|-|
				<div class="chatOther">
				  <table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
					  <td><b>'.$sSender.'</b></td>
					  <td width="55"><span>'.formatTime($sDateTime, "h:ia").'</span></td>
					</tr>

					<tr>
					  <td colspan="2">'.$sMessage.'</td>
					</tr>
				  </table>
				</div>');

		if ($i < ($iCount - 1))
			print '|--|';


		$sSQL = "UPDATE tbl_chat SET status='0' WHERE id='$iId'";
		$objDb2->execute($sSQL);
	}

	if ($iCount == 0)
		print "No-Msg";

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>