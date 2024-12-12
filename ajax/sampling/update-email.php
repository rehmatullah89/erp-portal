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

	$Id           = IO::intValue("Id");
	$Name         = IO::strValue("Name");
	$Email        = IO::strValue("Email");
        $Language     = IO::strValue("Language");
	$Brands      = @implode(",", IO::getArray("Brands"));
	$sError       = "";

        $sLanguageList  = array('en' => 'English', 'zh' => 'Chinese', 'de' => 'German', 'tr' => 'Turkish');
        $sLanguage      = $sLanguageList[$Language];
        
	$sSQL = "SELECT id FROM tbl_sampling_emails WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Sampling Email ID. Please select the proper Sampling Email to Edit.\n";
		exit( );
	}

	if ($Brands == "")
		$sError .= "- Invalid Brand\n";

	if ($Brands != "")
	{
		$sBrands = "";

		$sSQL = "SELECT brand FROM tbl_brands WHERE FIND_IN_SET(id, '$Brands')";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount == 0)
			$sError .= "- Invalid Brand\n";

		else
		{
			for ($i = 0; $i < $iCount; $i ++)
			{
				$sBrands .= ("- ".$objDb->getField($i, 0)."<br />");

				if ($i == 15)
				{
					$sBrands .= "...";

					break;
				}
			}
		}
	}

	if ($Name == "")
		$sError .= "- Invalid Name\n";

	if ($Email == "")
		$sError .= "- Invalid Email\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}


	$sSQL  = "SELECT * FROM tbl_sampling_emails WHERE brands='$Brands' AND email LIKE '$Email' AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
			$sSQL = "UPDATE tbl_sampling_emails SET brands='$Brands', name='$Name', email='$Email', language='$Language' WHERE id='$Id'";

			if ($objDb->execute($sSQL) == true)
				print ("OK|-|$Id|-|<div>The selected Sampling Email has been Updated successfully.</div>|-|$sBrands|-|$sLanguage|-|$Name|-|$Email");

			else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified Sampling Email already exists in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>