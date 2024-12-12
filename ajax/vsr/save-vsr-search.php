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

	$Title = IO::strValue("Title");
	$Title = (($Title == "") ? "NoName Search" : $Title);

	$_POST['Title'] = "";

	$Params = @serialize($_POST);

	$sSQL = "SELECT id FROM tbl_user_searches WHERE user_id='{$_SESSION['UserId']}' AND params='$Params'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		print "OK|-|The specified Search is already Exists into your Saved Searches.\n";
		exit( );
	}


	$iId = getNextId("tbl_user_searches");

	$sSQL = "INSERT INTO tbl_user_searches (id, user_id, title, params, date_time) VALUES ('$iId', '{$_SESSION['UserId']}', '$Title', '$Params', NOW( ))";

	if ($objDb->execute($sSQL) == true)
		print "OK|-|Your Search has been Saved successfully!";

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>