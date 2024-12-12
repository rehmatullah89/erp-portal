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
	$objDb2      = new Database( );
	$objDb3      = new Database( );


	$User      = IO::strValue('User');
	$AuditCode = IO::strValue("AuditCode");


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "" || $AuditCode == "" || $AuditCode{0} != "S")
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, status, guest FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else if ((int)getDbValue("COUNT(1)", "tbl_qa_reports", "audit_code='$AuditCode'") == 0)
			$aResponse["Message"] = "Invalid Request, The selected Audit Code has been Deleted.";

		else
		{
			$iUser  = $objDb->getField(0, "id");
			$sName  = $objDb->getField(0, "name");
			$sGuest = $objDb->getField(0, "guest");


			$iAuditCode = (int)substr($AuditCode, 1);

			
			$sSQL = "SELECT style_id, po_id, vendor_id, audit_stage, dhu, audit_result, ship_qty,
							(SELECT line FROM tbl_lines WHERE id=tbl_qa_reports.line_id) AS _Line
			         FROM tbl_qa_reports
			         WHERE id='$iAuditCode'";
			$objDb->query($sSQL);

			$iStyleId     = $objDb->getField(0, 'style_id');
			$iPoId        = $objDb->getField(0, 'po_id');
			$iVendorId    = $objDb->getField(0, 'vendor_id');
			$sAuditStage  = $objDb->getField(0, 'audit_stage');
			$fDhu         = $objDb->getField(0, 'dhu');
			$sLine        = $objDb->getField(0, "_Line");
			$sAuditResult = $objDb->getField(0, "audit_result");
			$iShipQty     = $objDb->getField(0, "ship_qty");


			$sSQL = "SELECT style, brand_id, sub_brand_id FROM tbl_styles WHERE id='$iStyleId'";
			$objDb->query($sSQL);

			$sStyle   = $objDb->getField(0, "style");
			$iBrand   = $objDb->getField(0, "brand_id");
			$iBrandId = $objDb->getField(0, "sub_brand_id");


			$sSQL = "UPDATE tbl_qa_reports SET published='N', published_at='0000-00-00 00:00:00' WHERE id='$iAuditCode'";

			if ($objDb->execute($sSQL, true, $iUser, $sName) == true)
			{
				$aResponse['Status']  = "OK";
				$aResponse["Message"] = "{$AuditCode} QA Report Finalized Successfully!";
			}

			else
			{
				$aResponse["Message"] = "An ERROR occured, please try again.";

				$objDb->execute("ROLLBACK", true, $iUser, $sName);
			}
		}
	}

	print @json_encode($aResponse);

/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = @json_encode($aResponse)."<bR>".$sSQL;

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/

	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>