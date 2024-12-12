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

	@session_start( );

	@ini_set('display_errors', 0);
	@ini_set('log_errors', 0);
	@error_reporting(0);

	@ini_set("max_execution_time", 0);
	@ini_set("mysql.connect_timeout", -1);



	$sBaseDir = "C:/wamp/www/portal/";

	@require_once($sBaseDir."requires/configs.php");
	@require_once($sBaseDir."requires/db.class.php");
	@require_once($sBaseDir."requires/PHPMailer/class.phpmailer.php");
	@require_once($sBaseDir."requires/common-functions.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	$sSQL = "SELECT DISTINCT(u.id), u.name, u.email, u.brands, u.vendors
	         FROM tbl_users u, tbl_vendors v
	         WHERE u.status='A' AND u.email_alerts='Y' AND FIND_IN_SET(u.id, v.etd_managers)
	         ORDER BY u.name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iUser    = $objDb->getField($i, "id");
		$sName    = $objDb->getField($i, "name");
		$sEmail   = $objDb->getField($i, "email");
		$sBrands  = $objDb->getField($i, "brands");
		$sVendors = $objDb->getField($i, "vendors");


		$sSQL = "SELECT CONCAT(po.order_no, ' ', po.order_status), po.id
				 FROM tbl_po po, tbl_etd_revision_requests etd
				 WHERE po.id=etd.po_id AND etd.status='P' AND FIND_IN_SET(po.brand_id, '$sBrands') AND FIND_IN_SET(po.vendor_id, '$sVendors')
				       AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE FIND_IN_SET('$iUser', etd_managers))
				 ORDER BY etd.date_time DESC";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		if ($iCount2 > 0)
		{
			$sLink = (SITE_URL."data/etd-revision-requests.php");
			$sPos  = "";

			for ($j = 0; $j < $iCount2; $j ++)
				$sPos .= ("- <a href='".SITE_URL.'po-status.php?Po='.trim($objDb2->getField($j, 0)).'&PoId='.$objDb2->getField($j, 1)."' target='_blank'>".$objDb2->getField($j, 0)."</a><br />");


			$sBody = @file_get_contents($sBaseDir."emails/notify-etd-revisions.txt");
			$sBody = @str_replace("[POs]", $sPos, $sBody);
			$sBody = @str_replace("[Link]", $sLink, $sBody);
			$sBody = @str_replace("[SiteTitle]", SITE_TITLE, $sBody);
			$sBody = @str_replace("[SiteUrl]", SITE_URL, $sBody);


			$objEmail = new PHPMailer( );

//			$objEmail->From     = SENDER_EMAIL;
//			$objEmail->FromName = SENDER_NAME;
			$objEmail->Subject  = ("[".date("d-M-Y")."] ETD Revision Requests");

			$objEmail->MsgHTML($sBody);
			$objEmail->AddAddress($sEmail, $sName);
			$objEmail->Send( );
		}
	}


	/************************************************

	// For Bangladesh COO -- Fahim Al Matin
	$sSQL = "SELECT name, email, country_id FROM tbl_users WHERE id='687' AND status='A' AND email_alerts='Y'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sName      = $objDb->getField(0, "name");
		$sEmail     = $objDb->getField(0, "email");
		$iCountryId = $objDb->getField(0, "country_id");


		$sSQL = "SELECT CONCAT(po.order_no, ' ', po.order_status), po.id
				 FROM tbl_po po, tbl_etd_revision_requests etd
				 WHERE po.id=etd.po_id AND etd.status='P' AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$iCountryId' AND parent_id='0' AND sourcing='Y')
				 ORDER BY etd.date_time DESC";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount > 0)
		{
			$sLink = (SITE_URL."data/etd-revision-requests.php");
			$sPos  = "";

			for ($i = 0; $i < $iCount; $i ++)
				$sPos .= ("- <a href='".SITE_URL.'po-status.php?Po='.trim($objDb->getField($i, 0)).'&PoId='.$objDb->getField($i, 1)."' target='_blank'>".$objDb->getField($i, 0)."</a><br />");


			$sBody = @file_get_contents($sBaseDir."emails/notify-etd-revisions.txt");
			$sBody = @str_replace("[POs]", $sPos, $sBody);
			$sBody = @str_replace("[Link]", $sLink, $sBody);
			$sBody = @str_replace("[SiteTitle]", SITE_TITLE, $sBody);
			$sBody = @str_replace("[SiteUrl]", SITE_URL, $sBody);


			$objEmail = new PHPMailer( );

//			$objEmail->From     = SENDER_EMAIL;
//			$objEmail->FromName = SENDER_NAME;
			$objEmail->Subject  = ("[".date("d-M-Y")."] ETD Revision Requests");

			$objEmail->MsgHTML($sBody);
			$objEmail->AddAddress($sEmail, $sName);
			$objEmail->Send( );
		}
	}



	// For Pakistan COO
	$sSQL = "SELECT name, email, country_id FROM tbl_users WHERE id='13' AND status='A' AND email_alerts='Y'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sName      = $objDb->getField(0, "name");
		$sEmail     = $objDb->getField(0, "email");
		$iCountryId = $objDb->getField(0, "country_id");


		$sSQL = "SELECT CONCAT(po.order_no, ' ', po.order_status)
				 FROM tbl_po po, tbl_etd_revision_requests etd
				 WHERE po.id=etd.po_id AND etd.status='P' AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$iCountryId' AND parent_id='0' AND sourcing='Y') AND po.brand_id NOT IN (79,57,73,83,85,81,77,59,112,67,75,167,206,208,136,160)
				 ORDER BY etd.date_time DESC";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount > 0)
		{
			$sLink = (SITE_URL."data/etd-revision-requests.php");
			$sPos  = "";

			for ($i = 0; $i < $iCount; $i ++)
				$sPos .= ("- ".$objDb->getField($i, 0)."<br />");


			$sBody = @file_get_contents($sBaseDir."emails/notify-etd-revisions.txt");
			$sBody = @str_replace("[POs]", $sPos, $sBody);
			$sBody = @str_replace("[Link]", $sLink, $sBody);
			$sBody = @str_replace("[SiteTitle]", SITE_TITLE, $sBody);
			$sBody = @str_replace("[SiteUrl]", SITE_URL, $sBody);


			$objEmail = new PHPMailer( );

//			$objEmail->From     = SENDER_EMAIL;
//			$objEmail->FromName = SENDER_NAME;
			$objEmail->Subject  = ("[".date("d-M-Y")."] ETD Revision Requests");

			$objEmail->MsgHTML($sBody);
			$objEmail->AddAddress($sEmail, $sName);
			$objEmail->Send( );
		}
	}




	// For Adidas/Reebok -- Tariq
	$sSQL = "SELECT name, email, country_id FROM tbl_users WHERE id='233' AND status='A' AND email_alerts='Y'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sName      = $objDb->getField(0, "name");
		$sEmail     = $objDb->getField(0, "email");
		$iCountryId = $objDb->getField(0, "country_id");


		$sSQL = "SELECT CONCAT(po.order_no, ' ', po.order_status)
				 FROM tbl_po po, tbl_etd_revision_requests etd
				 WHERE po.id=etd.po_id AND etd.status='P' AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$iCountryId' AND parent_id='0' AND sourcing='Y') AND po.brand_id IN (67,75)
				 ORDER BY etd.date_time DESC";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount > 0)
		{
			$sLink = (SITE_URL."data/etd-revision-requests.php");
			$sPos  = "";

			for ($i = 0; $i < $iCount; $i ++)
				$sPos .= ("- ".$objDb->getField($i, 0)."<br />");


			$sBody = @file_get_contents($sBaseDir."emails/notify-etd-revisions.txt");
			$sBody = @str_replace("[POs]", $sPos, $sBody);
			$sBody = @str_replace("[Link]", $sLink, $sBody);
			$sBody = @str_replace("[SiteTitle]", SITE_TITLE, $sBody);
			$sBody = @str_replace("[SiteUrl]", SITE_URL, $sBody);


			$objEmail = new PHPMailer( );

//			$objEmail->From     = SENDER_EMAIL;
//			$objEmail->FromName = SENDER_NAME;
			$objEmail->Subject  = ("[".date("d-M-Y")."] ETD Revision Requests");

			$objEmail->MsgHTML($sBody);
			$objEmail->AddAddress($sEmail, $sName);
			$objEmail->Send( );
		}
	}



	// For Brandix -- Shahan
	$sSQL = "SELECT name, email, country_id FROM tbl_users WHERE id='313' AND status='A' AND email_alerts='Y'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sName      = $objDb->getField(0, "name");
		$sEmail     = $objDb->getField(0, "email");
		$iCountryId = $objDb->getField(0, "country_id");


		$sSQL = "SELECT CONCAT(po.order_no, ' ', po.order_status)
				 FROM tbl_po po, tbl_etd_revision_requests etd
				 WHERE po.id=etd.po_id AND etd.status='P' AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$iCountryId' AND parent_id='0' AND sourcing='Y') AND po.brand_id='167'
				 ORDER BY etd.date_time DESC";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount > 0)
		{
			$sLink = (SITE_URL."data/etd-revision-requests.php");
			$sPos  = "";

			for ($i = 0; $i < $iCount; $i ++)
				$sPos .= ("- ".$objDb->getField($i, 0)."<br />");


			$sBody = @file_get_contents($sBaseDir."emails/notify-etd-revisions.txt");
			$sBody = @str_replace("[POs]", $sPos, $sBody);
			$sBody = @str_replace("[Link]", $sLink, $sBody);
			$sBody = @str_replace("[SiteTitle]", SITE_TITLE, $sBody);
			$sBody = @str_replace("[SiteUrl]", SITE_URL, $sBody);


			$objEmail = new PHPMailer( );

//			$objEmail->From     = SENDER_EMAIL;
//			$objEmail->FromName = SENDER_NAME;
			$objEmail->Subject  = ("[".date("d-M-Y")."] ETD Revision Requests");

			$objEmail->MsgHTML($sBody);
			$objEmail->AddAddress($sEmail, $sName);
			$objEmail->Send( );
		}
	}



	// For Pakistan MTAR / HomeTextile ---> Nadeem Saigol
	$sSQL = "SELECT name, email, country_id FROM tbl_users WHERE id='84' AND status='A' AND email_alerts='Y'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sName      = $objDb->getField(0, "name");
		$sEmail     = $objDb->getField(0, "email");
		$iCountryId = $objDb->getField(0, "country_id");


		$sSQL = "SELECT CONCAT(po.order_no, ' ', po.order_status)
				 FROM tbl_po po, tbl_etd_revision_requests etd
				 WHERE po.id=etd.po_id AND etd.status='P' AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$iCountryId' AND parent_id='0' AND sourcing='Y') AND po.brand_id IN (79,57,73,83,85,81,77,59,112,206,208,136,160)
				 ORDER BY etd.date_time DESC";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount > 0)
		{
			$sLink = (SITE_URL."data/etd-revision-requests.php");
			$sPos  = "";

			for ($i = 0; $i < $iCount; $i ++)
				$sPos .= ("- ".$objDb->getField($i, 0)."<br />");


			$sBody = @file_get_contents($sBaseDir."emails/notify-etd-revisions.txt");
			$sBody = @str_replace("[POs]", $sPos, $sBody);
			$sBody = @str_replace("[Link]", $sLink, $sBody);
			$sBody = @str_replace("[SiteTitle]", SITE_TITLE, $sBody);
			$sBody = @str_replace("[SiteUrl]", SITE_URL, $sBody);


			$objEmail = new PHPMailer( );

//			$objEmail->From     = SENDER_EMAIL;
//			$objEmail->FromName = SENDER_NAME;
			$objEmail->Subject  = ("[".date("d-M-Y")."] ETD Revision Requests");

			$objEmail->MsgHTML($sBody);
			$objEmail->AddAddress($sEmail, $sName);
			$objEmail->Send( );
		}
	}

*********************************************************************/


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );
?>