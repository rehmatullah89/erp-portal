<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree QUONDA App                                                                   **
	**  Version 3.0                                                                              **
	**                                                                                           **
	**  http://app.3-tree.com                                                                    **
	**                                                                                           **
	**  Copyright 2008-17 (C) Triple Tree                                                        **
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
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$User   = IO::strValue("User");
	$Vendor = IO::intValue("Vendor");
	$Brand  = IO::intValue("Brand");


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";

	if ($User == "")
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, status, brands, guest FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else
		{
			$iUser   = $objDb->getField(0, "id");
			$sName   = $objDb->getField(0, "name");
			$sBrands = $objDb->getField(0, "brands");
			$sGuest  = $objDb->getField(0, "guest");


			if ($Vendor == 0)
				$aResponse["Message"] = "No Vendor Selected";

			else
			{
				$sFromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 30), date("Y")));
				$sToDate   = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") + 60), date("Y")));

				$sPos      = getList("tbl_po po, tbl_po_colors pc", "DISTINCT(po.id)", "CONCAT(order_no, IF(order_status!='', CONCAT(' ', order_status), '')) AS _Po", "po.id=pc.po_id AND po.vendor_id='$Vendor' AND (po.brand_id='$Brand' OR ('$Brand'='0' AND FIND_IN_SET(po.brand_id, '$sBrands'))) AND po.status!='C' AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate')", "_Po");

				$aResponse['Status'] = "OK";
				$aResponse['Pos']    = $sPos;
			}
		}
	}


	print @json_encode($aResponse);

/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = @json_encode($aResponse);

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>