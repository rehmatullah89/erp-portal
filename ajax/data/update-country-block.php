<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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

	$Id          = IO::intValue("Id");
	$BlockName   = IO::strValue("BlockName");
        $CountryCodes= implode(",", IO::getArray("CountryCodes"));
	$sError      = "";

	$sSQL = "SELECT id FROM tbl_country_blocks WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Country Block ID. Please select the proper Country Block to Edit.\n";
		exit( );
	}

	if ($BlockName == "")
		$sError .= "- Invalid Block Name\n";

	if ($CountryCodes == "")
		$sError .= "- Invalid Country Codes\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}

	$sSQL  = "SELECT * FROM tbl_country_blocks WHERE country_block LIKE '$BlockName' AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
                        $sFileName = $_FILES["Picture"]['name'];

                        if ($sFileName != "" && $sFileName != "undefined")
                        {
                                $exts = explode('.', $sFileName);
                                $extension = end($exts);

                                if(@in_array(strtolower($extension), array('jpg','jpeg','gif','png')))
                                {
                                    $sPicture = ("DEST_".$Id.'.'.$extension);

                                    if (@move_uploaded_file($_FILES["Picture"]['tmp_name'], ($sBaseDir.$sBaseDir.SHIPPING_PORTS_DIR.$sPicture)))
                                    {
                                        $sSQL = "UPDATE tbl_country_blocks SET country_block='$BlockName', country_codes='$CountryCodes', symbol = '$sPicture' WHERE id='$Id'";
                                    }
                                }
                        }else                    
                            $sSQL = "UPDATE tbl_country_blocks SET country_block='$BlockName', country_codes='$CountryCodes' WHERE id='$Id'";

			if ($objDb->execute($sSQL) == true)
                            print ("OK|-|$Id|-|<div>The selected Country Block has been Updated successfully.</div>|-|$BlockName|-|$CountryCodes");
                        else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified Country Block already exists in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>