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

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	$User      = IO::strValue('User');
	$AuditCode = IO::strValue("AuditCode");
	$CartonNos = IO::strValue("CartonNos");
	$sError    = "";

	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "" || $AuditCode == "" || $AuditCode{0} != "S")
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, status FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else if ((int)getDbValue("COUNT(1)", "tbl_qa_reports", "audit_code='$AuditCode'") == 0)
			$aResponse["Message"] = "Invalid Request, The selected Audit Code has been Deleted.";

		else
		{
			$iUser = $objDb->getField(0, "id");
			$sName = $objDb->getField(0, "name");


			$iAuditCode = (int)substr($AuditCode, 1);
			$iReportId  = getDbValue("report_id", "tbl_qa_reports", "id='$iAuditCode'");
			
			$CartonNos = substr($CartonNos, 1, -1);
			$CartonNos = str_replace(", ", ",", $CartonNos);


			$iPo    = getDbValue("po_id", "tbl_qa_reports", "id='$iAuditCode'");
			$iPoQty = getDbValue("quantity", "tbl_po", "id='$iPo'");

			$iUnitsPackedQty      = IO::intValue("UnitsPackedQty");
			$iUnitsFinishedQty    = IO::intValue("UnitsFinishedQty");
			$iUnitsNotFinishedQty = IO::intValue("UnitsNotFinishedQty");

			$fUnitsPackedPercent      = @round((($iUnitsPackedQty / $iPoQty) * 100), 2);
			$fUnitsFinishedPercent    = @round((($iUnitsFinishedQty / $iPoQty) * 100), 2);
			$fUnitsNotFinishedPercent = @round((($iUnitsNotFinishedQty / $iPoQty) * 100), 2);



			$bFlag = $objDb->execute("BEGIN", true, $iUser, $sName);

			if ($iReportId == 20 || $iReportId == 23)
			{
				if (getDbValue("COUNT(1)", "tbl_kik_inspection_summary", "audit_id='$iAuditCode'") == 0)
				{
					$sSQL  = ("INSERT INTO tbl_kik_inspection_summary (audit_id) VALUES ('$iAuditCode')");
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}

				if ($bFlag == true)
				{
					$sSQL  = ("UPDATE tbl_kik_inspection_summary SET shipment_units       = '".IO::intValue("ShipmentQtyUnits")."',
																	 shipment_ctns        = '".IO::intValue("ShipmentQtyCtns")."',
																	 presented_qty        = '".IO::intValue("PresentedQty")."',
																	 packed_qty           = '$iUnitsPackedQty',
																	 packed_percent       = '$fUnitsPackedPercent',
																	 finished_qty         = '$iUnitsFinishedQty',
																	 finished_percent     = '$fUnitsFinishedPercent',
																	 not_finished_qty     = '$iUnitsNotFinishedQty',
																	 not_finished_percent = '$fUnitsNotFinishedPercent',
																	 carton_nos           = '$CartonNos'
							   WHERE audit_id='$iAuditCode'");

					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}

				if ($bFlag == true)
				{
					$sSQL  = "DELETE FROM tbl_kik_samples_per_size WHERE audit_id='$iAuditCode'";
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}

				if ($bFlag == true)
				{
					for ($i = 1; $i <= 10; $i ++)
					{
						if (IO::strValue("SizeColor{$i}") == "" && IO::intValue("SizeQty{$i}") == 0 && IO::intValue("SampleQty{$i}") == 0)
							continue;


						$sSizeColor = IO::strValue("SizeColor{$i}");

						if (getDbValue("COUNT(1)", "tbl_kik_samples_per_size", "audit_id='$iAuditCode' AND size_color='$sSizeColor'") > 0)
						{
/*
							$sError = "Duplicate Size/Color";
							$bFlag  = false;

							break;
*/
							$sSizeColor .= " *";
						}

						while (getDbValue("COUNT(1)", "tbl_kik_samples_per_size", "audit_id='$iAuditCode' AND size_color='$sSizeColor'") > 0)
						{
							$sSizeColor .= "*";
						}



						$sSQL  = ("INSERT INTO tbl_kik_samples_per_size SET audit_id   = '$iAuditCode',
																			size_color = '$sSizeColor',
																			size_qty   = '".IO::intValue("SizeQty{$i}")."',
																			sample_qty = '".IO::intValue("SampleQty{$i}")."'");
						$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);

						if ($bFlag == false)
							break;
					}
				}
			}
			
			else if ($iReportId == 32)
			{
				if (getDbValue("COUNT(1)", "tbl_arcadia_inspection_summary", "audit_id='$iAuditCode'") == 0)
				{
					$sSQL  = ("INSERT INTO tbl_arcadia_inspection_summary (audit_id) VALUES ('$iAuditCode')");
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}

				if ($bFlag == true)
				{
					$sSQL  = ("UPDATE tbl_arcadia_inspection_summary SET shipment_units       = '".IO::intValue("ShipmentQtyUnits")."',
																		 shipment_ctns        = '".IO::intValue("ShipmentQtyCtns")."',
																		 presented_qty        = '".IO::intValue("PresentedQty")."',
																		 packed_qty           = '$iUnitsPackedQty',
																		 packed_percent       = '$fUnitsPackedPercent',
																		 finished_qty         = '$iUnitsFinishedQty',
																		 finished_percent     = '$fUnitsFinishedPercent',
																		 not_finished_qty     = '$iUnitsNotFinishedQty',
																		 not_finished_percent = '$fUnitsNotFinishedPercent',
																		 carton_nos           = '$CartonNos'
							   WHERE audit_id='$iAuditCode'");

					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}

				if ($bFlag == true)
				{
					$sSQL  = "DELETE FROM tbl_arcadia_samples_per_size WHERE audit_id='$iAuditCode'";
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}

				if ($bFlag == true)
				{
					for ($i = 1; $i <= 10; $i ++)
					{
						if (IO::strValue("SizeColor{$i}") == "" && IO::intValue("SizeQty{$i}") == 0 && IO::intValue("SampleQty{$i}") == 0)
							continue;


						$sSizeColor = IO::strValue("SizeColor{$i}");

						if (getDbValue("COUNT(1)", "tbl_arcadia_samples_per_size", "audit_id='$iAuditCode' AND size_color='$sSizeColor'") > 0)
						{
/*
							$sError = "Duplicate Size/Color";
							$bFlag  = false;

							break;
*/
							$sSizeColor .= " *";
						}

						while (getDbValue("COUNT(1)", "tbl_arcadia_samples_per_size", "audit_id='$iAuditCode' AND size_color='$sSizeColor'") > 0)
						{
							$sSizeColor .= "*";
						}



						$sSQL  = ("INSERT INTO tbl_arcadia_samples_per_size SET audit_id   = '$iAuditCode',
																				size_color = '$sSizeColor',
																				size_qty   = '".IO::intValue("SizeQty{$i}")."',
																				sample_qty = '".IO::intValue("SampleQty{$i}")."'");
						$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);

						if ($bFlag == false)
							break;
					}
				}
			}
			
			else if ($iReportId == 35)
			{
				if (getDbValue("COUNT(1)", "tbl_timezone_inspection_summary", "audit_id='$iAuditCode'") == 0)
				{
					$sSQL  = ("INSERT INTO tbl_timezone_inspection_summary (audit_id) VALUES ('$iAuditCode')");
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}

				if ($bFlag == true)
				{
					$sSQL  = ("UPDATE tbl_timezone_inspection_summary SET shipment_units       = '".IO::intValue("ShipmentQtyUnits")."',
																		  shipment_ctns        = '".IO::intValue("ShipmentQtyCtns")."',
																		  presented_qty        = '".IO::intValue("PresentedQty")."',
																		  packed_qty           = '$iUnitsPackedQty',
																		  packed_percent       = '$fUnitsPackedPercent',
																		  finished_qty         = '$iUnitsFinishedQty',
																		  finished_percent     = '$fUnitsFinishedPercent',
																		  not_finished_qty     = '$iUnitsNotFinishedQty',
																		  not_finished_percent = '$fUnitsNotFinishedPercent',
																		  carton_nos           = '$CartonNos'
							   WHERE audit_id='$iAuditCode'");

					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}

				if ($bFlag == true)
				{
					$sSQL  = "DELETE FROM tbl_timezone_samples_per_size WHERE audit_id='$iAuditCode'";
					$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
				}

				if ($bFlag == true)
				{
					for ($i = 1; $i <= 10; $i ++)
					{
						if (IO::strValue("SizeColor{$i}") == "" && IO::intValue("SizeQty{$i}") == 0 && IO::intValue("SampleQty{$i}") == 0)
							continue;


						$sSizeColor = IO::strValue("SizeColor{$i}");

						if (getDbValue("COUNT(1)", "tbl_timezone_samples_per_size", "audit_id='$iAuditCode' AND size_color='$sSizeColor'") > 0)
							$sSizeColor .= " *";


						while (getDbValue("COUNT(1)", "tbl_timezone_samples_per_size", "audit_id='$iAuditCode' AND size_color='$sSizeColor'") > 0)
						{
							$sSizeColor .= "*";
						}



						$sSQL  = ("INSERT INTO tbl_timezone_samples_per_size SET audit_id   = '$iAuditCode',
																				 size_color = '$sSizeColor',
																				 size_qty   = '".IO::intValue("SizeQty{$i}")."',
																				 sample_qty = '".IO::intValue("SampleQty{$i}")."'");
						$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);

						if ($bFlag == false)
							break;
					}
				}
			}

			if ($bFlag == true)
			{
				$sSQL  = ("UPDATE tbl_qa_reports SET ship_qty='".IO::intValue("PresentedQty")."' WHERE id='$iAuditCode'");
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);
			}

			if ($bFlag == true)
			{
				$objDb->execute("COMMIT", true, $iUser, $sName);

				$aResponse['Status']  = "OK";
				$aResponse["Message"] = "Audit Quantity Saved Successfully!";
			}

			else
			{
				$aResponse["Message"] = (($sError != "") ? $sError : "An ERROR occured, please try again.");

				$objDb->execute("ROLLBACK", true, $iUser, $sName);
			}
		}
	}

	print @json_encode($aResponse);

/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Alert";
	$objEmail->Body    = $Cap."\n\n".@json_encode($aResponse)."<bR>".$sSQL;

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>