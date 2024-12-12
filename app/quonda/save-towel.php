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


	$User      = IO::strValue('User');
	$AuditCode = IO::strValue("AuditCode");
	$ReportId  = IO::intValue('ReportId');
	$LotNo     = IO::intValue("LotNo");
	$RollNo    = IO::intValue("RollNo");
	$Width     = IO::floatValue("Width");
	$TicketPcs = IO::floatValue("TicketPcs");
	$ActualPcs = IO::floatValue("ActualPcs");
	$Holes     = IO::intValue("Holes");
	$Slubs     = IO::intValue("Slubs");
	$Stains    = IO::intValue("Stains");
	$Fly       = IO::intValue("Fly");
	$Other     = IO::intValue("Other");
	$Defects   = IO::intValue("Defects");
	$DateTime  = IO::strValue("DateTime");


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "" || $AuditCode == "" || $AuditCode{0} != "S" || $ReportId == 0 || $LotNo == 0 || $RollNo == 0 || $Width == 0 || $TicketPcs == 0 || $ActualPcs == 0)
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, status, guest FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else
		{
			$iUser  = $objDb->getField(0, "id");
			$sName  = $objDb->getField(0, "name");
			$sGuest = $objDb->getField(0, "guest");


			$iAuditCode = (int)substr($AuditCode, 1);
			$DateTime   = (($DateTime == "") ? date("Y-m-d H:i:s") : $DateTime);


			if (getDbValue("COUNT(1)", "tbl_towel_report_defects", "audit_id='$iAuditCode' AND lot_no='$LotNo' AND roll_no='$RollNo' AND date_time='$DateTime'") == 0)
			{
                $iSampleSize     = 0;
                $iAllowedDefects = 0;
                
                if ($ActualPcs >= 2 && $ActualPcs <= 8)
                    $iSampleSize = 2;
				
                else if ($ActualPcs >= 9 && $ActualPcs <= 15)
                    $iSampleSize = 3;
				
                else if ($ActualPcs >= 16 && $ActualPcs <= 25)
                    $iSampleSize = 5;
                
				else if ($ActualPcs >= 26 && $ActualPcs <= 50)
                    $iSampleSize = 8;
				
                else if ($ActualPcs >= 51 && $ActualPcs <= 90)
                    $iSampleSize = 13;
                
				else if ($ActualPcs >= 91 && $ActualPcs <= 150)
				{
                    $iSampleSize     = 20;
                    $iAllowedDefects = 1;
                }
                
				else if ($ActualPcs >= 151 && $ActualPcs <= 280)
				{
                    $iSampleSize     = 32;
                    $iAllowedDefects = 2;
                }
                
				else if ($ActualPcs >= 281 && $ActualPcs <= 500)
				{
                    $iSampleSize     = 50;
                    $iAllowedDefects = 3;
                }
				
                else if ($ActualPcs >= 501 && $ActualPcs <= 1200)
				{
                    $iSampleSize     = 80;
                    $iAllowedDefects = 5;
                }
				
                else if ($ActualPcs >= 1201 && $ActualPcs <= 3200)
				{
                    $iSampleSize     = 125;
                    $iAllowedDefects = 7;
                }
				
                else if ($ActualPcs >= 3201 && $ActualPcs <= 10000)
				{
                    $iSampleSize     = 200;
                    $iAllowedDefects = 10;
                }
                
				else if ($ActualPcs >= 10001 && $ActualPcs <= 35000)
				{
                    $iSampleSize     = 315;
                    $iAllowedDefects = 14;
                }
                
				else if ($ActualPcs >= 35001 && $ActualPcs <= 150000)
				{
                    $iSampleSize     = 500;
                    $iAllowedDefects = 21;
                }
                
				else if ($ActualPcs >= 150001 && $ActualPcs <= 500000)
				{
                    $iSampleSize     = 800;
                    $iAllowedDefects = 21;
                }
                
				else if ($ActualPcs >= 500000)
				{
                    $iSampleSize     = 1250;
                    $iAllowedDefects = 21;
                }
				
				
				$sResult = (($Defects > $iAllowedDefects) ? "F" : "P");
				
				
				$iId  = getNextId("tbl_towel_report_defects");


				$sSQL = "INSERT INTO tbl_towel_report_defects (id, audit_id, lot_no, roll_no, width, ticket_meters, actual_meters, holes, slubs, stains, fly, other, allowable_defects, result, date_time)
													   VALUES ('$iId', '$iAuditCode', '$LotNo', '$RollNo', '$Width', '$TicketPcs', '$ActualPcs', '$Holes', '$Slubs', '$Stains', '$Fly', '$Other', '$Defects', '$sResult', '$DateTime')";

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