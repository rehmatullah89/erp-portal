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


	$User        = IO::strValue('User');
	$AuditCode   = IO::strValue("AuditCode");
	$ReportId    = IO::intValue('ReportId');
	$LotNo       = IO::intValue("LotNo");
	$RollNo      = IO::intValue("RollNo");
	$Width       = IO::floatValue("Width");
	$TicketYards = IO::floatValue("TicketYards");
	$ActualYards = IO::floatValue("ActualYards");
	$Holes       = IO::intValue("Holes");
	$Slubs       = IO::intValue("Slubs");
	$Stains      = IO::intValue("Stains");
	$Fly         = IO::intValue("Fly");
	$Other       = IO::intValue("Other");
	$Result      = IO::strValue("Result");
	$DateTime    = IO::strValue("DateTime");


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "" || $AuditCode == "" || $AuditCode{0} != "S" || $ReportId == 0 || $LotNo == 0 || $RollNo == 0 || $Width == 0 || $TicketYards == 0 || $ActualYards == 0 || $Result == "")
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, status FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else
		{
			$iUser = $objDb->getField(0, "id");
			$sName = $objDb->getField(0, "name");


			$iAuditCode = (int)substr($AuditCode, 1);
			$DateTime   = (($DateTime == "") ? date("Y-m-d H:i:s") : $DateTime);


			if (getDbValue("COUNT(1)", "tbl_tnc_report_defects", "audit_id='$iAuditCode' AND lot_no='$LotNo' AND roll_no='$RollNo' AND date_time='$DateTime'") == 0)
			{
				$iId  = getNextId("tbl_tnc_report_defects");


				$sSQL = "INSERT INTO tbl_tnc_report_defects (id, audit_id, lot_no, roll_no, width, ticket_meters, actual_meters, holes, slubs, stains, fly, other, result, date_time)
													VALUES ('$iId', '$iAuditCode', '$LotNo', '$RollNo', '$Width', '$TicketYards', '$ActualYards', '$Holes', '$Slubs', '$Stains', '$Fly', '$Other', '$Result', '$DateTime')";

				if ($objDb->execute($sSQL, true, $iUser, $sName) == true)
				{
					$aResponse['Status']  = "OK";
					$aResponse["Message"] = "Defect Saved Successfully!";
				}

				else
					$aResponse["Message"] = "An ERROR occured, please try again.";
			}

			else
			{
				$aResponse['Status']  = "OK";
				$aResponse["Message"] = "Defect Already entered!";
			}
		}
	}

	print @json_encode($aResponse);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>