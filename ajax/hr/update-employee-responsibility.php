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

	if ($sUserRights['Add'] != "Y" || $sUserRights['Edit'] != "Y")
	{
		$sSQL = ("SELECT responsibility FROM tbl_user_responsibilities_score WHERE user_id='".IO::intValue('UserId')."' AND responsibility_id='".IO::intValue('ResponsibilityId')."'");
		$objDb->query($sSQL);

		print $objDb->getField(0, 0);
  	}

  	else
  	{
		$sSQL  = ("SELECT * FROM tbl_user_responsibilities_score WHERE responsibility LIKE '".IO::strValue("Responsibility")."' AND user_id='".IO::intValue('UserId')."' AND responsibility_id!='".IO::intValue('ResponsibilityId')."'");
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
		{
			$sSQL = ("UPDATE tbl_user_responsibilities_score SET responsibility='".IO::strValue('Responsibility')."' WHERE user_id='".IO::intValue('UserId')."' AND responsibility_id='".IO::intValue('ResponsibilityId')."'");
			$objDb->execute($sSQL);
		}

		$sSQL = ("SELECT responsibility FROM tbl_user_responsibilities_score WHERE user_id='".IO::intValue('UserId')."' AND responsibility_id='".IO::intValue('ResponsibilityId')."'");
		$objDb->query($sSQL);

		print $objDb->getField(0, 0);
	}

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>