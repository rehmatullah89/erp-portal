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

	if ($sUserRights['Edit'] != "Y")
	{
		$sSQL = ("SELECT report FROM tbl_sampling_reports WHERE id='".IO::intValue('Id')."'");
		$objDb->query($sSQL);

		print $objDb->getField(0, 0);
  	}

  	else
  	{
		$sSQL  = ("SELECT * FROM tbl_sampling_reports WHERE report LIKE '".IO::strValue("Report")."' AND id!='".IO::intValue('Id')."'");
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
		{
			$sSQL = ("UPDATE tbl_sampling_reports SET report='".IO::strValue('Report')."' WHERE id='".IO::intValue('Id')."'");
			$objDb->execute($sSQL);
		}

		$sSQL = ("SELECT report FROM tbl_sampling_reports WHERE id='".IO::intValue('Id')."'");
		$objDb->query($sSQL);

		print $objDb->getField(0, 0);
	}

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>