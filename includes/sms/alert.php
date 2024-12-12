<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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

	$objEmail = new PHPMailer( );

	$objEmail->IsSMTP( );
	$objEmail->SMTPAuth = true;
	$objEmail->Subject  = "From: $sSender";
	$objEmail->Body     = substr($sSms, 5);

	//$objEmail->AddAddress("omer@apparelco.com", "Omer Rauf");
	$objEmail->AddAddress("taqi@apparelco.com", "Muhammad Taqi");
	$objEmail->AddAddress("ali@apparelco.com", "Asim Ali");
	$objEmail->AddAddress("imran@apparelco.com", "Imran Bashir");
	$objEmail->AddAddress("muhammad.hafeez@apparelco.com", "Muhammad Hafeez");
	$objEmail->AddAddress("isaeed@apparelco.com", "Imran Saeed");

	if ($bDebug == true)
	{
		print $sSender."<br />";
		print $sSms."<br />";
	}


	if ($objEmail->Send( ) == false)
	{
		if ($bDebug == true)
			print ("Mail Status: ".(($objEmail->ErrorInfo == "") ? "OK" : $objEmail->ErrorInfo)."<br />");
	}


	// Reply back
/*
	$objEmail = new PHPMailer( );

	$objEmail->IsSMTP( );
	$objEmail->SMTPAuth = true;
	$objEmail->Subject  = "Y";
	$objEmail->Body     = "Y";

	$objEmail->AddAddress("+92{$sSender}@sms.apparelco.com", $sSender);
	$objEmail->Send( );

	if ($objEmail->Send( ) == false)
	{
		if ($bDebug == true)
			print ("Mail Status: ".(($objEmail->ErrorInfo == "") ? "OK" : $objEmail->ErrorInfo)."<br />");
	}
*/
?>