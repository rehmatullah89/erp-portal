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

	$sSQL  = ("SELECT * FROM tbl_shipping_ports WHERE port_name LIKE '".IO::strValue("PortName")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_shipping_ports");

		$sSQL = ("INSERT INTO tbl_shipping_ports (id, port_name, booking_form) VALUES ('$iId', '".IO::strValue("PortName")."', '".IO::strValue("DisplayAtBooking")."')");
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
                                $sPicture = ("PORT_SYMBOL_".$iId.'.'.$extension);

                                if (@move_uploaded_file($_FILES["Picture"]['tmp_name'], ($sBaseDir.SHIPPING_PORTS_DIR.$sPicture)))
                                {
                                        $sSQL  = "UPDATE tbl_shipping_ports SET symbol = '$sPicture' WHERE id='$iId'";
                                        $Flag = $objDb->execute($sSQL);
                                }
                            }
                            else
                                $_SESSION['Flag'] = "DB_ERROR";
                    }
                    
                    $sPdfSymbol = $_FILES["PdfSymbol"]['name'];
                    if ($sPdfSymbol != "")
                    {
                            $exts = explode('.', $sPdfSymbol);
                            $extension = end($exts);

                            if(@in_array(strtolower($extension), array('jpg','jpeg','gif','png')))
                            {
                                $sPdfPicture = ("PDF_SYMBOL_".$iId.'.'.$extension);

                                if (@move_uploaded_file($_FILES["PdfSymbol"]['tmp_name'], ($sBaseDir.SHIPPING_PORTS_DIR.$sPdfPicture)))
                                {
                                        $sSQL  = "UPDATE tbl_shipping_ports SET pdf_symbol = '$sPdfPicture' WHERE id='$iId'";
                                        $Flag = $objDb->execute($sSQL);
                                }
                            }
                            else
                                $_SESSION['Flag'] = "DB_ERROR";
                    }
                        
                }
                
                if ($Flag == true)
			redirect($_SERVER['HTTP_REFERER'], "PORT_ADDED");
		else{
                    echo $sSQL;exit;
			$_SESSION['Flag'] = "DB_ERROR";
                }
	}

	else
		$_SESSION['Flag'] = "PORT_EXISTS";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>