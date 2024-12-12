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

	$sSQL = "SELECT COUNT(*) FROM tbl_user_notifications WHERE user_id='{$_SESSION['UserId']}' AND status='N'";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getField(0, 0);

		print "OK|-|$iCount|-|";

		$sSQL = "SELECT subject, date_time, body FROM tbl_user_notifications WHERE user_id='{$_SESSION['UserId']}' ORDER BY id DESC LIMIT 10";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sSubject  = $objDb->getField($i, 'subject');
			$sBody     = $objDb->getField($i, 'body');
			$sDateTime = $objDb->getField($i, 'date_time');

			$sSubject = str_replace("***", "", $sSubject);

			print ('<div class="'.((($i % 2) == 0) ? 'even' : 'odd').'Notification">');
			print ("  <b>".$sSubject."</b>");
			print "  <div>";
			print ("    <i>".formatDate($sDateTime, "F d, Y h:i A")."</i>");
			print (nl2br($sBody)."<br />");
			print "  </div>";
			print "</div>";
		}
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>