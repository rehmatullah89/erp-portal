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

	@require_once("../requires/session.php");

	if ($sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Id      = IO::intValue('Id');
	$Referer = urlencode(IO::strValue('Referer'));


	if (@strpos($_SESSION["Email"], "@apparelco.com") !== FALSE || @strpos($_SESSION["Email"], "@3-tree.com") !== FALSE || 
	    @strpos($_SESSION["Email"], "@selimpex.com") !== FALSE || @strpos($_SESSION["Email"], "@global-exports.com") !== FALSE)
		@include($sBaseDir."includes/data/update-po-matrix.php");

	else
		@include($sBaseDir."includes/data/update-po-others.php");


	header("Location: edit-purchase-order.php?Id={$Id}&Referer={$Referer}");

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>