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

	$User      = IO::strValue("User");
	$AuditCode = IO::strValue("AuditCode");
	$Size      = IO::intValue('Size');


	$aResponse            = array( );
	$aResponse['Status']  = "ERROR";
	$aResponse["Message"] = "";

	if ($User == "" || $AuditCode == "" || $AuditCode{0} != "S" || $Size == 0)
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


			$iAuditCode    = (int)substr($AuditCode, 1);
			$iStyle        = getDbValue("style_id", "tbl_qa_reports", "id='$iAuditCode'");
			$sSizeLabel    = getDbValue("size", "tbl_sizes", "id='$Size'");
			$iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '$sSizeLabel'");
			$sDetails      = array( );


			$sSQL = "SELECT point_id, specs,
			                (SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
			                (SELECT point FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Point
			         FROM tbl_style_specs
			         WHERE style_id='$iStyle' AND size_id='$iSamplingSize' AND version='0'
			         ORDER BY id";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for($i = 0; $i < $iCount; $i ++)
			{
				$iPoint     = $objDb->getField($i, 'point_id');
				$sPoint     = $objDb->getField($i, '_Point');
				$sSpecs     = $objDb->getField($i, 'specs');
				$sTolerance = $objDb->getField($i, '_Tolerance');

				$sDetails[] = array("PointId"   => $iPoint,
								    "Point"     => $sPoint,
								    "Specs"     => $sSpecs,
								    "Tolerance" => $sTolerance);
			}


			$aResponse['Status'] = "OK";
			$aResponse['Specs']  = $sDetails;
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
	$objDbGlobal->close( );

	@ob_end_flush( );
?>