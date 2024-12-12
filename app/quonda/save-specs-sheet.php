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


	$User        = IO::strValue('User');
	$AuditCode   = IO::strValue("AuditCode");
	$SpecsSheets = IO::strValue('SpecsSheets');


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "" || $AuditCode == "" || $AuditCode{0} != "S" || $SpecsSheets == "")
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


			$iAuditCode   = (int)substr($AuditCode, 1);
			$sSpecsSheets = @explode(",", $SpecsSheets);
			$iIndex       = 0;


			$sSQL = "SELECT * FROM tbl_qa_reports WHERE id='$iAuditCode'";
			$objDb->query($sSQL);

			$iIndex = 0;
			$sSQL   = "UPDATE tbl_qa_reports SET";

			for ($i = 1; $i <= 10; $i ++)
			{
				$sSpecsSheet = $objDb->getField(0, "specs_sheet_{$i}");

				if ($sSpecsSheet == "" && $iIndex < count($sSpecsSheets))
				{
					if ($iIndex > 0)
						$sSQL .= ", ";

					$sSQL .= " specs_sheet_{$i}='{$sSpecsSheets[$iIndex]}' ";

					$iIndex ++;
				}
			}


			if ($iIndex > 0)
			{
				$sSQL .= "WHERE id='$iAuditCode'";
				$bFlag = $objDb->execute($sSQL, true, $iUser, $sName);


				if ($bFlag == true)
				{
					$aResponse['Status']  = "OK";
					$aResponse["Message"] = "Specs Sheets Saved Successfully!";
				}

				else
					$aResponse["Message"] = "An ERROR occured while processing your request.";
			}

			else
			{
				$aResponse['Status']  = "OK";
				$aResponse["Message"] = "Specs Sheets are already linked with this report.";
			}
		}
	}

	print @json_encode($aResponse);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>