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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/session.php");

	if ($sUserRights['Edit'] != "Y")
	{
		print "ERROR|-|You havn\'t enough Rights to modify the selected Data.\n";
		exit( );
	}

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id      = IO::intValue("Id");
	$Office  = IO::strValue("Office");
	$Phone   = IO::strValue("Phone");
	$Fax     = IO::strValue("Fax");
	$Address = IO::strValue("Address");
	$Country = IO::intValue("Country");
	$sError  = "";

	$sSQL = "SELECT id FROM tbl_offices WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Office ID. Please select the proper Office to Edit.\n";
		exit( );
	}

	if ($Office == "")
		$sError .= "- Invalid Office\n";

	if ($Phone == "")
		$sError .= "- Invalid Phone Number\n";

	if ($Country > 0)
	{
		$sSQL = "SELECT country FROM tbl_countries WHERE id='$Country'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Country\n";

		else
			$sCountry = $objDb->getField(0, 0);
	}

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}



	$sSQL  = "SELECT * FROM tbl_offices WHERE office LIKE '$Office' AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
			$sSQL = ("UPDATE tbl_offices SET office='$Office', phone='$Phone', fax='$Fax', address='$Address', country_id='$Country' WHERE id='$Id'");

			if ($objDb->execute($sSQL) == true)
				print ("OK|-|$Id|-|<div>The selected Office has been Updated successfully.</div>|-|$Office|-|$Phone|-|$Fax|-|$Address|-|$sCountry");

			else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified Office already exists in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>