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

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id         = IO::intValue("Id");
	$SamplePick = IO::strValue("SamplePick");
        $Materials  = (IO::strValue("Materials") == 'Y'?'Y':'N');
        $Status     = IO::strValue("Status");
	$sError     = "";
        
	$sSQL = "SELECT id FROM tbl_sample_picks WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Sample Pick ID. Please select the proper Sample Pick to Edit.\n";
		exit( );
	}

	if ($SamplePick == "")
		$sError .= "- Invalid Sample Pick\n";
        
        if ($Status == "")
		$sError .= "- Invalid Sample Pick Status\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}


	$sSQL  = "SELECT * FROM tbl_sample_picks WHERE (title LIKE '$SamplePick') AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
                        $sDStatus = ($Status == 'A'?'Active':'In-Active');
			$sSQL = "UPDATE tbl_sample_picks SET title='$SamplePick', materials='$Materials', status='$Status' WHERE id='$Id'";
                        
			if ($objDb->execute($sSQL) == true)
				print ("OK|-|$Id|-|<div>The selected Sample Pick has been Updated successfully.</div>|-|$SamplePick|-|$sDStatus");

			else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified Sample Pick already exists in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>