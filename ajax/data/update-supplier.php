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

	$Id             = IO::intValue("Id");
	$Supplier       = IO::strValue("Supplier");
        $Code           = IO::strValue("Code"); 
        $City           = IO::strValue("City");
        $Address        = IO::strValue("Address");
        $Country        = IO::intValue("Country");
        $Latitude       = IO::strValue("Latitude");
        $Longitude      = IO::strValue("Longitude");
        $Email          = IO::strValue("Email");
        $Phone          = IO::strValue("Phone");
        $Fax            = IO::strValue("Fax");       
        $Profile        = IO::strValue("Profile");       
        $PersonName     = IO::strValue("PersonName");
        $PersonEmail    = IO::strValue("PersonEmail");
        $PersonPhone    = IO::strValue("PersonPhone");
        $PersonFax      = IO::strValue("PersonFax");
        $PortRequired   = IO::strValue("PortRequired");
        $PersonPicture  = IO::strValue("PersonPicture");
	$sError                     = "";

	$sSQL = "SELECT id FROM tbl_suppliers WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Supplier ID. Please select the proper Supplier to Edit.\n";
		exit( );
	}

	if ($Supplier == "")
		$sError .= "- Invalid Supplier\n";

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

	$sSQL  = "SELECT * FROM tbl_suppliers WHERE supplier LIKE '$Supplier' AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
			$sSQL = "UPDATE tbl_suppliers SET supplier='$Supplier', port_required='$PortRequired', code='$Code', city='$City', address='$Address', country_id='$Country', latitude='$Latitude', longitude='$Longitude', profile='$Profile', email='$Email', phone='$Phone', fax='$Fax', contact_person='$PersonName', person_phone='$PersonPhone', person_email='$PersonEmail', person_fax='$PersonFax', updated_by='".$_SESSION['UserId']."', updated_at=NOW() WHERE id='$Id'";

			if ($objDb->execute($sSQL) == true)
				print ("OK|-|$Id|-|<div>The selected Supplier has been Updated successfully.</div>|-|$Supplier|-|$Code|-|$sCategory|-|$sCountry|-|$City");

			else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified Supplier / Code / Country already exists in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>