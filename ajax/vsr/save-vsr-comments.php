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

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$PoId     = IO::intValue("PoId");
	$Comments = IO::strValue("Comments");
	$sError   = "";

	if ($PoId == 0)
		$sError .= "- Invalid PO\n";

	if ($Comments == "")
		$sError .= "- Invalid Comments\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}


	$iId = getNextId("tbl_vsr_comments");

	$sSQL = "INSERT INTO tbl_vsr_comments (id, po_id, user_id, comments, date_time) VALUES ('$iId', '$PoId', '{$_SESSION['UserId']}', '$Comments', NOW( ))";

	if ($objDb->execute($sSQL) == true)
	{
		print "OK|-|$PoId|-|";

		$sSQL = "SELECT user_id, comments, date_time FROM tbl_vsr_comments WHERE id='$iId'";
		$objDb->query($sSQL);

		$iUserId   = $objDb->getField(0, "user_id");
		$sComments = $objDb->getField(0, "comments");
		$sDateTime = $objDb->getField(0, "date_time");


		$sSQL = "SELECT name, picture FROM tbl_users WHERE id='$iUserId'";
		$objDb->query($sSQL);

		$sName    = $objDb->getField(0, "name");
		$sPicture = $objDb->getField(0, "picture");

		if ($sPicture == "" || !@file_exists("../../".USERS_IMG_PATH.'thumbs/'.$sPicture))
			$sPicture = "default.jpg";
?>
							      <div style="background:#eaeaea; padding:10px; margin-bottom:10px;">
								    <table border="0" cellpadding="0" cellspacing="0" width="100%">
								      <tr valign="top">
								  	    <td width="82" align="center">
									      <div id="ProfilePic" style="margin:0px 0px 5px 0px; width:82px;">
									   	    <div id="Pic"><img src="<?= USERS_IMG_PATH.'thumbs/'.$sPicture ?>" alt="<?= $sName ?>" title="<?= $sName ?>" style="width:78px; height:59px;" /></div>
									      </div>

									      <i style="color:#999999; font-size:10px;"><?= $sName ?></i><br />
									    </td>

									    <td width="20"></td>

									    <td>
									      <h4 style="font-size:11px;"><?= formatDate($sDateTime, "l, jS F, Y   h:i A") ?></h4>
									      <?= nl2br($sComments) ?><br />
									    </td>
								      </tr>
								    </table>
							      </div>
<?
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>