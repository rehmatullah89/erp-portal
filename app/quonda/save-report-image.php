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
	$Image     = IO::strValue('Image');


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "" || $AuditCode == "" || $AuditCode{0} != "S" || $Image == "")
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
			$sType      = ((@strpos($Image, "_MISC_") !== FALSE) ? "M" : "P");


			if (getDbValue("COUNT(1)", "tbl_qa_report_images", "audit_id='$iAuditCode' AND image='$Image'") == 0)
			{
				$sSQL  = "INSERT INTO tbl_qa_report_images SET audit_id = '$iAuditCode',
				                                               image    = '$Image',
															   type     = '$sType'";

				if ($objDb->execute($sSQL, true, $iUser, $sName) == true)
				{
					$aResponse['Status']  = "OK";
					$aResponse["Message"] = "Report Image Saved Successfully!";
				}

				else
					$aResponse["Message"] = "An ERROR occured while processing your request.";
			}

			else
			{
				$aResponse['Status']  = "OK";
				$aResponse["Message"] = "Report Image is already saved.";
			}
		}
	}

	print @json_encode($aResponse);


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>