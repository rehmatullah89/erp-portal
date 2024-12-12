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
        $Section    = IO::strValue("Section");
	$sError     = "";


	$sSQL = "SELECT id, image FROM tbl_statement_sections WHERE id='$Id'";
	$objDb->query($sSQL);

        $sImage = $objDb->getField(0, 'image');
        
	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Section ID. Please select the proper Auditor Section Form to Edit.\n";
		exit( );
	}

	if ($Section == "")
		$sError .= "- Invalid Auditor Section\n";
        
        
	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}
        
            
        $sSQL   = "UPDATE tbl_statement_sections SET section = '$Section' WHERE id='$Id'";
        $Flag   = $objDb->execute($sSQL);
      
        if ($Flag == true)
        {
            
                $sFileName = $_FILES["IconImage0"]['name'];

                if ($sFileName != "")
                {
                    $exts = explode('.', $sFileName);
                    $extension = end($exts);

                    $sPicture = ("SECTION_".$Id."_".rand(1, 100).'.'.$extension);

                    if (@move_uploaded_file($_FILES["IconImage0"]['tmp_name'], ($sBaseDir.$sBaseDir.AUDITOR_APP_ICONS_DIR.$sPicture)))
                    {
                            $sSQL   = "UPDATE tbl_statement_sections SET image = '$sPicture' WHERE id='$Id'";
                            $Flag   = $objDb->execute($sSQL);
                            
                            unlink($sBaseDir.$sBaseDir.AUDITOR_APP_ICONS_DIR.$sImage);
                    }
                }
        }
        
        if ($Flag == true)
            print ("OK|-|$Id|-|<div>The selected Booking Form has been Updated successfully.</div>|-|$Section");
        else
                print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>