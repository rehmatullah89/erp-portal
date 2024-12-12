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


	$sSQL = "SELECT DISTINCT(user_id) FROM tbl_user_stats WHERE DATE_FORMAT(login_date_time, '%Y-%m-%d')=CURDATE( ) AND TIME_TO_SEC(TIMEDIFF(NOW( ), logout_date_time)) <= '600' AND status='1' AND user_id!='{$_SESSION['UserId']}' ORDER BY id DESC";
	$objDb->query($sSQL);

	$iOnlineUsers = $objDb->getCount( );
	$sOnlineUsers = "";

	for ($i = 0; $i < $iOnlineUsers; $i ++)
		$sOnlineUsers .= (",".$objDb->getField($i, 0));

	$sOnlineUsers = substr($sOnlineUsers, 1);


	print ($iOnlineUsers.'|-|');


	$sSQL = "SELECT id, department FROM tbl_departments WHERE department!='Support Services' ORDER BY department";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iDepartmentId = $objDb->getField($i, 'id');
		$sDepartment   = $objDb->getField($i, 'department');

		$sSQL = "SELECT id, name FROM tbl_users WHERE status='A' AND designation_id IN (SELECT id FROM tbl_designations WHERE department_id='$iDepartmentId') AND id IN ($sOnlineUsers) ORDER BY name";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		print ('<b class="'.(($iCount2 == 0) ? 'close' : 'open').'">'.$sDepartment.'</b>
		        <ul>');

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iUserId = $objDb2->getField($j, 'id');
			$sName   = $objDb2->getField($j, 'name');

			print ('<li onclick="showChatWin(\''.$iUserId.'\');"><img src="images/icons/online.png" alt="" title="" />'.$sName.'</li>');
		}

		print '</ul>';
	}

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>