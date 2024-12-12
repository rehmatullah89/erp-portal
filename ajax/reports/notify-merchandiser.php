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

	$PoId    = IO::intValue("PoId");
	$BrandId = IO::intValue("BrandId");

	if ($PoId == 0 || $BrandId == 0)
	{
		print "ERROR|-|Invalid Request to notify Merchandiser.";
		exit;
	}


	$sSQL = "SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id='$PoId'";
	$objDb->query($sSQL);

	$sOrderNo = $objDb->getField(0, 0);


	$sSQL = "SELECT user_id FROM tbl_brands WHERE id='$BrandId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$iUserId = $objDb->getField(0, 0);


		$sSQL = "SELECT name, email FROM tbl_users WHERE id='$iUserId' AND status='A' AND email_alerts='Y'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
		{
			$sName  = $objDb->getField(0, 0);
			$sEmail = $objDb->getField(0, 1);

			$sLink = (SITE_URL."delay-reason.php?PoId={$PoId}&UserId={$iUserId}&Email={$sEmail}");


			$sBody = @file_get_contents("../../emails/notify-merchandiser.txt");

			$sBody = @str_replace("[Name]", $sName, $sBody);
			$sBody = @str_replace("[OrderNo]", $sOrderNo, $sBody);
			$sBody = @str_replace("[Link]", $sLink, $sBody);
			$sBody = @str_replace("[SenderName]", $_SESSION['Name'], $sBody);
			$sBody = @str_replace("[SenderEmail]", $_SESSION['Email'], $sBody);
			$sBody = @str_replace("[SiteTitle]", SITE_TITLE, $sBody);
			$sBody = @str_replace("[SiteUrl]", SITE_URL, $sBody);


			$objEmail = new PHPMailer( );

//			$objEmail->Username = "khalidch";
//			$objEmail->Password = "marketing";
//			$objEmail->From     = "khalidch@apparelco.com";
//			$objEmail->FromName = "Muhammad Khalid";
			$objEmail->Subject  = "PO Alert [$sOrderNo]";

			$objEmail->MsgHTML($sBody);
			$objEmail->AddAddress($sEmail, $sName);
//			$objEmail->AddAddress("khalidch@apparelco.com", "Muhammad Khalid");

			if ($objEmail->Send( ) == true)
				print "OK|-|An Email has been Sent to the Merchandiser.";

			else
				print "ERROR|-|An ERROR occured while sending an Email to the Merchandiser.";
		}

		else
			print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>