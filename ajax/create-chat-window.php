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

	$Id = IO::intValue("Id");

	if ($Id == 0)
		exit;


	$sSQL = "SELECT name, picture, designation_id FROM tbl_users WHERE id='$Id'";
	$objDb->query($sSQL);

	$sName        = $objDb->getField(0, "name");
	$sPicture     = $objDb->getField(0, "picture");
	$iDesignation = $objDb->getField(0, "designation_id");

	if ($sPicture == "" || !@file_exists($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture))
		$sPicture = "default.jpg";


	$sSQL = "SELECT designation, department_id FROM tbl_designations WHERE id='$iDesignation'";
	$objDb->query($sSQL);

	$sDesignation = $objDb->getField(0, 'designation');
	$iDepartment  = $objDb->getField(0, 'department_id');

	$sDepartment  = getDbValue("department", "tbl_departments", "id='$iDepartment'");


	print ($Id.'|-|
		<div id="ChatWin'.$Id.'" class="chatWin" style="display:block;">
		  <div>
			<div class="chatWinTitle" id="ChatWinTitle'.$Id.'">
			  <table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr bgcolor="#666666">
				  <td width="85"></td>
				  <td><b>'.$sName.'</b></td>
				  <td width="18"></td>
				  <td width="18"><img src="images/icons/close.gif" alt="Close" title="Close" onclick="hideChatWin(\''.$Id.'\');" style="cursor:pointer;" /></td>
				</tr>

				<tr>
				  <td></td>
				  <td colspan="3"><i>'.$sDesignation.'</i></td>
				</tr>

				<tr>
				  <td></td>
				  <td colspan="3"><span>'.$sDepartment.'</span></td>
				</tr>
			  </table>

			  <div class="userPic"><img src="'.USERS_IMG_PATH.'thumbs/'.$sPicture.'" alt="'.$sName.'" title="'.$sName.'" /></div>
			</div>

			<div id="ChatWinContents">
			  <div class="chatWinArea" id="ChatWinArea'.$Id.'">');


	$sDateTime = date("Y-m-d H:i:s", @mktime((date("H") - 1), date("i"), date("s"), date("m"), date("d"), date("Y")));

	$sSQL = "SELECT * FROM tbl_chat WHERE (recipient='{$_SESSION['UserId']}' OR recipient='$Id') AND (sender='{$_SESSION['UserId']}' OR sender='$Id') AND date_time >= '$sDateTime' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId        = $objDb->getField($i, "id");
		$iSender    = $objDb->getField($i, 'sender');
		$iRecipient = $objDb->getField($i, 'recipient');
		$sMessage   = $objDb->getField($i, 'message');
		$sDateTime  = $objDb->getField($i, 'date_time');

		print ('<div class="'.(($iSender == $_SESSION['UserId']) ? 'chatMe' : 'chatOther').'">
				  <table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
					  <td><b>'.(($iSender == $_SESSION['UserId']) ? $_SESSION['Name'] : $sName).'</b></td>
					  <td width="55"><span>'.formatTime($sDateTime, "h:ia").'</span></td>
					</tr>

					<tr>
					  <td colspan="2">'.$sMessage.'</td>
					</tr>
				  </table>
				</div>');

		$sSQL = "UPDATE tbl_chat SET status='0' WHERE id='$iId'";
		$objDb2->execute($sSQL);
	}

	print ('  </div>

			  <form name="frmChatWin'.$Id.'" id="frmChatWin'.$Id.'" onsubmit="return sendChatMessage('.$Id.');">
				<input type="hidden" name="Recipient" value="'.$Id.'" />
				<input type="text" name="Message" id="Message'.$Id.'" value="" maxlength="250" class="textbox" autocomplete="off" />
				<input type="submit" value="Submit" class="button" />
			  </form>
			</div>
		  </div>
		</div>');

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>