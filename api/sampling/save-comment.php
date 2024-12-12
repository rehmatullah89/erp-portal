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

	$Stage    = IO::strValue("Stage");
	$From     = IO::strValue("From");
	$Nature   = IO::strValue("Nature");
	$Comments = IO::strValue("Comments");


	$iId = getNextId("tbl_style_comments");

	$sSQL = "INSERT INTO tbl_style_comments (id, style_id, stage, `from`, `date`, nature, comments, user_id, date_time)
	                                 VALUES ('$iId', '$Id', '$Stage', '$From', CURDATE( ), '$Nature', '$Comments', '$User', NOW( ))";

	if ($objDb->execute($sSQL, true, $User, getDbValue("name", "tbl_users", "id='$User'")) == true)
	{
		$From     = "";
		$Comments = "";
	}
?>