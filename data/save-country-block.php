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

	@require_once("../requires/session.php");

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

        $Flag        = false;
	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$sSQL  = ("SELECT * FROM tbl_country_blocks WHERE country_block LIKE '".IO::strValue("BlockName")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_country_blocks");

		$sSQL = ("INSERT INTO tbl_country_blocks (id, country_block, country_codes, symbol) VALUES ('$iId', '".IO::strValue("BlockName")."', '". implode(",", IO::getArray("CountryCodes"))."', '')");
                $Flag = $objDb->execute($sSQL);
                
		if ($Flag == true)
                {
                    $sFileName = $_FILES["Picture"]['name'];
                    if ($sFileName != "")
                    {
                            $exts = explode('.', $sFileName);
                            $extension = end($exts);

                            if(@in_array(strtolower($extension), array('jpg','jpeg','gif','png')))
                            {
                                $sPicture = ("DEST_".$iId.'.'.$extension);

                                if (@move_uploaded_file($_FILES["Picture"]['tmp_name'], ($sBaseDir.SHIPPING_PORTS_DIR.$sPicture)))
                                {
                                        $sSQL  = "UPDATE tbl_country_blocks SET symbol = '$sPicture' WHERE id='$iId'";
                                        $Flag = $objDb->execute($sSQL);
                                }
                            }
                            else
                                $_SESSION['Flag'] = "DB_ERROR";
                    }
                        
                }
                
                if ($Flag == true)
			redirect($_SERVER['HTTP_REFERER'], "DESTINATION_ADDED");
		else
			$_SESSION['Flag'] = "DB_ERROR";
	}

	else
		$_SESSION['Flag'] = "DESTINATION_EXISTS";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>