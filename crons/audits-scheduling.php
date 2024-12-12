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
	@require_once($sBaseDir."requires/common-functions.php");
	@require_once($sBaseDir."requires/PHPMailer/class.phpmailer.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );



	$sSQL = "SELECT DISTINCT(po.id)
	         FROM tbl_po po, tbl_po_colors pc
	         WHERE po.id=pc.po_id AND po.status!='C'
	               AND (DATEDIFF(pc.etd_required, NOW( ))='3' OR (po.brand_id='124' AND DATEDIFF(pc.etd_required, NOW( ))='12'))";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$iPOs   = array( );

	if ($iCount == 0)
		exit( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iPo = $objDb->getField($i, 0);

		if (getDbValue("COUNT(*)", "tbl_qa_reports", "(po_id='$iPo' OR FIND_IN_SET('$iPo', additional_pos)) AND audit_stage='F' AND audit_result='P'") > 0)
			continue;

		$iPOs[] = $iPo;
	}


	$sAllPOs        = @implode(",", $iPOs);
	$iMerchandisers = array( );


	$sSQL = "SELECT merchandisers FROM tbl_brands WHERE parent_id>'0' AND merchandisers!='' GROUP BY merchandisers";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iMerchants = @explode(",", $objDb->getField($i, 0));


		for ($j = 0; $j < count($iMerchants); $j ++)
		{
			if (!@in_array($iMerchants[$j], $iMerchandisers))
				$iMerchandisers[] = $iMerchants[$j];
		}
	}



	$sAllBrandsList  = getList("tbl_brands", "id", "brand", "parent_id>'0'");
	$sAllVendorsList = getList("tbl_vendors", "id", "vendor", "parent_id='0'");


	foreach ($iMerchandisers as $iMerchandiser)
	{
		$sSQL = "SELECT name, email, vendors, brands FROM tbl_users WHERE id='$iMerchandiser' AND status='A' AND email_alerts='Y'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			continue;

		$sName        = $objDb->getField(0, "name");
		$sEmail       = $objDb->getField(0, "email");
		$sBrandsList  = $objDb->getField(0, "brands");
		$sVendorsList = $objDb->getField(0, "vendors");


		if ($iMerchandiser == 61)
			$sVendorsList = 194;

		if ($iMerchandiser == 376)
			$sVendorsList = str_replacE(",194,", ",", $sVendorsList);


		$sSQL = "SELECT GROUP_CONCAT(id SEPARATOR ',') FROM tbl_brands WHERE parent_id>'0' AND FIND_IN_SET('$iMerchandiser', merchandisers)";
		$objDb->query($sSQL);

		$sBrands = $objDb->getField(0, 0);



		$sSQL = "SELECT DISTINCT(po.id) AS _PoId, po.vendor_id, po.brand_id,
						CONCAT(po.order_no, ' ', po.order_status) AS _Po,
						pc.etd_required AS _EtdRequired,
						(SELECT style FROM tbl_styles WHERE id=pc.style_id) AS _Style
				 FROM tbl_po po, tbl_po_colors pc
				 WHERE po.id=pc.po_id AND FIND_IN_SET(po.id, '$sAllPOs') AND FIND_IN_SET(po.brand_id, '$sBrands')
				       AND FIND_IN_SET(po.brand_id, '$sBrandsList') AND FIND_IN_SET(po.vendor_id, '$sVendorsList')
				 ORDER BY _Po";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount == 0)
			continue;


		$sLink = (SITE_URL."quonda/schedule.php");
		$sPos  = "";

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sPo          = $objDb->getField($i, "_Po");
			$iPo          = $objDb->getField($i, "_PoId");
			$sStyle       = $objDb->getField($i, "_Style");
			$sEtdRequired = $objDb->getField($i, "_EtdRequired");
			$iVendor      = $objDb->getField($i, "vendor_id");
			$iBrand       = $objDb->getField($i, "brand_id");


			$sPos .= '<tr>';
			$sPos .= ('<td align="center">'.($i + 1).'</td>');
			$sPos .= ("<td><a href='".SITE_URL.'po-status.php?Po='.trim($sPo).'&PoId='.$iPo."' target='_blank'>".$sPo."</a></td>");
			$sPos .= ('<td>'.$sStyle.'</td>');
			$sPos .= ('<td>'.formatDate($sEtdRequired).'</td>');
			$sPos .= ('<td>'.$sAllVendorsList[$iVendor].'</td>');
			$sPos .= ('<td>'.$sAllBrandsList[$iBrand].'</td>');
			$sPos .= '</tr>';
		}


		$sBody = @file_get_contents($sBaseDir."emails/audits-scheduling.txt");
		$sBody = @str_replace("[Name]", $sName, $sBody);
		$sBody = @str_replace("[POs]", $sPos, $sBody);
		$sBody = @str_replace("[Link]", $sLink, $sBody);
		$sBody = @str_replace("[SiteTitle]", SITE_TITLE, $sBody);
		$sBody = @str_replace("[SiteUrl]", SITE_URL, $sBody);


		$objEmail = new PHPMailer( );

//		$objEmail->From     = SENDER_EMAIL;
//		$objEmail->FromName = SENDER_NAME;
		$objEmail->Subject  = ("[".date("d-M-Y")."] Audit Scheduling Alert");

		$objEmail->MsgHTML($sBody);
		$objEmail->AddAddress($sEmail, $sName);
		$objEmail->AddAddress("adil@apparelco.com", "Adil Saleem");
		$objEmail->AddAddress("islam@apparelco.com", "Muhammad Islam");
		$objEmail->Send( );
	}



	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );
?>